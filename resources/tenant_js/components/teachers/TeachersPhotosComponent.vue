<template>
    <v-container grid-list-md fluid>
    <v-layout row wrap>
        <v-flex xs8>
            <v-alert v-model="dimensionsAlert" type="warning" dismissible>
                Les fotos han de tenir unes dimensions de 670*790
            </v-alert>

            <h1 class="title">Fotos disponibles:</h1>

            <v-autocomplete
                    :items="internalAvailablePhotos"
                    v-model="photo"
                    label="Selecciona foto"
                    item-text="filename"
                    item-value="name"
                    chips
                    clearable
            >
                <template slot="selection" slot-scope="data">
                    <v-chip
                            :selected="data.selected"
                            :key="JSON.stringify(data.item)"
                            class="chip--select"
                            @input="data.parent.selectItem(data.item)"
                    >
                        <user-avatar :hash-id="data.item.slug"
                                     :alt="data.item.filename"
                        ></user-avatar>
                        {{ data.item.filename }}
                    </v-chip>
                </template>
                <template slot="item" slot-scope="data">
                    <template>
                        <v-list-tile-avatar v-if="data.item.slug">
                            <img :src="'/teacher_photo/' + data.item.slug">
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title v-html="data.item.filename"></v-list-tile-title>
                        </v-list-tile-content>
                    </template>
                </template>
            </v-autocomplete>

            <template v-if="showPhoto">
                <span v-if="uploading">Pujant la foto...</span>
                <img ref='photoImage' height="59" width="50"
                     :src="photoPath" alt="Uploaded photo" @click="upload" @error="errorOnPhoto" >
                <span v-if="fileUploaded">Foto pujada</span>
            </template>

            <v-alert :value="true" type="error" v-for="error in errors" :key="error.id">
                <template v-for="errorMessage in errors">
                    {{ errorMessage[0] }}
                </template>
            </v-alert>

            <form class="upload">
                <input
                        ref="photo"
                        type="file"
                        name="teacher_photo"
                        accept="image/*"
                        :disabled="uploading"
                        @change="photoChange"/>
            </form>

            <v-btn
                    :loading="uploading"
                    :disabled="uploading"
                    color="blue-grey"
                    class="white--text"
                    @click.native="upload"
            >
                Pujar foto
                <v-icon right dark>cloud_upload</v-icon>
            </v-btn>
            <v-btn v-if="internalAvailablePhotos.length > 0"
                    href="/unassigned_teacher_photos"
                    color="info"
                    class="white--text"
            >
                Baixar zip
                <v-icon right dark>file_download</v-icon>
            </v-btn>
            <v-btn
                    :loading="refreshing"
                    :disabled="refreshing"
                    color="info"
                    class="white--text"
                    @click.native="refresh"
            >
                Actualitzar
                <v-icon right dark>refresh</v-icon>
            </v-btn>

            <v-dialog v-model="removeAlldialog" persistent max-width="290">
                <v-btn slot="activator"
                       color="error"
                >
                    Esborrar tots
                    <v-icon right dark>delete</v-icon>
                </v-btn>
                <v-card>
                    <v-card-title class="headline">Si us plau confirmeu</v-card-title>
                    <v-card-text>Esteu segurs que voleu esborrar totes les fotos?</v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="green darken-1" flat @click.native="removeAlldialog = false">Cancel·lar</v-btn>
                        <v-btn color="red darken-1"
                               flat
                               :loading="removingAll"
                               :disabled="removingAll"
                               @click.native="removeAll">Eliminar tots</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <v-dialog v-model="removeDialog" persistent max-width="290" v-if="photo">
                <v-btn slot="activator"
                       color="error"
                >
                    Eliminar
                    <v-icon right dark>delete</v-icon>
                </v-btn>
                <v-card>
                    <v-card-title class="headline">Si us plau confirmeu</v-card-title>
                    <v-card-text>Esteu segurs que voleu esborrar la foto?</v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="green darken-1" flat @click.native="removeDialog = false">Cancel·lar</v-btn>
                        <v-btn color="red darken-1"
                               flat
                               :loading="deleting"
                               :disabled="deleting"
                               @click.native="removeSelectedPhoto">Eliminar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <v-btn
                    v-if="photo"
                    :href="'/teacher_photo/' + photo.slug + '/download'"
                    @click.native="downloadSelectedPhoto"
            >
                Baixar
                <v-icon right dark>file_download</v-icon>
            </v-btn>

            <v-layout row wrap>
                <v-flex xs2>
                    <v-switch
                            label="Veure graella"
                            v-model="availableGrid"
                    ></v-switch>
                </v-flex>
            </v-layout>

        </v-flex>
        <v-flex xs4>
            <v-alert v-model="dimensionsAlert" type="warning" dismissible>
                Podeu pujar múltiples fotos de cop utilitzant un fitxer zip
            </v-alert>
            <h1 class="title">Fitxers zip:</h1>

            <v-autocomplete
                    :items="internalZips"
                    v-model="zipFile"
                    label="Selecciona fitxer zip"
                    item-text="filename"
                    item-value="name"
                    chips
                    clearable
            >
                <template slot="selection" slot-scope="data">
                    <v-chip
                            :selected="data.selected"
                            :key="JSON.stringify(data.item)"
                            class="chip--select"
                            @input="data.parent.selectItem(data.item)"
                    >
                        {{ data.item.filename }}
                    </v-chip>
                </template>
                <template slot="item" slot-scope="data">
                    <template>
                        <v-list-tile-content>
                            <v-list-tile-title v-html="data.item.filename"></v-list-tile-title>
                        </v-list-tile-content>
                    </template>
                </template>
            </v-autocomplete>

            <v-alert :value="true" type="error" v-for="error in zipErrors" :key="error.id">
                <template v-for="errorMessage in errors">
                    {{ errorMessage[0] }}
                </template>
            </v-alert>

            <form class="upload">
                <input
                        ref="zip"
                        type="file"
                        name="photos"
                        accept="application/zip, application/octet-stream"
                        :disabled="uploadingZip"
                        @change="zipChange"/>

            </form>

            <v-btn
                    :loading="uploadingZip"
                    :disabled="uploadingZip"
                    color="blue-grey"
                    class="white--text"
                    @click.native="uploadZip"
            >
                Pujar zip
                <v-icon right dark>cloud_upload</v-icon>
            </v-btn>

            <v-btn
                    v-if="zipFile"
                    :href="'/unassigned_teacher_photos/' + zipFile.slug"
                    color="info"
                    class="white--text"
            >
                Baixar
                <v-icon right dark>file_download</v-icon>
            </v-btn>
            <v-btn
                    v-if="zipFile"
                    :loading="deletingZip"
                    :disabled="deletingZip"
                    color="red"
                    class="white--text"
                    @click.native="removeSelectedZip"
            >
                Eliminar
                <v-icon right dark>delete</v-icon>
            </v-btn>
        </v-flex>
        <v-flex xs12>
            <v-container grid-list-md fluid>
                <v-layout row wrap v-if="availableGrid">
                    <v-flex v-for="photo in internalAvailablePhotos" :key="photo.slug" md2 lg1>
                        <v-card height="100%">
                            <v-card-media :src="'/teacher_photo/' + photo.slug" height="200px" >
                            </v-card-media>
                            <v-card-title primary-title>
                                <p>
                                    <input v-if="editing === photo.slug"
                                           @keyup.esc="cancelEditing(photo)"
                                           @keyup.enter="edit(photo)"
                                           type="text" v-model="filename"
                                           @focus="$event.target.select()" id="filename">
                                    <span v-else @dblclick="confirmEdit(photo)">{{ photo.filename }}</span>
                                    <v-icon color="green" @click="confirmEdit(photo)" v-if="editing !== photo.slug">edit</v-icon>
                                    <template v-else>
                                        <v-icon color="red" @click="cancelEditing(photo)" dark>cancel</v-icon>
                                        <v-icon color="green" dark @click="edit(photo)">done</v-icon>
                                    </template>
                                </p>
                            </v-card-title>
                            <v-card-actions>
                                <v-btn
                                        v-if="confirmingRemove !== photo.slug"
                                       flat color="red"
                                       @click="confirmRemove(photo)"
                                       icon
                                >
                                    <v-icon dark>delete</v-icon>
                                </v-btn>
                                <template v-else>
                                    <v-btn flat color="green" @click="cancelRemove(photo)"
                                           icon
                                    >

                                        <v-icon dark>cancel</v-icon>
                                    </v-btn>

                                    <v-btn flat color="red" @click="remove(photo)"
                                           :loading="deleting === photo.slug"
                                           :disabled="deleting === photo.slug"
                                           icon
                                    >

                                        <v-icon dark>done</v-icon>
                                    </v-btn>
                                </template>

                                <v-btn flat
                                       :href="'/teacher_photo/' + photo.slug + '/download'"
                                       icon>
                                    <v-icon dark>file_download</v-icon>
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-flex>
        <v-flex xs12>
            <h1 class="title text-md-center">Professors</h1>
            <v-container grid-list-md fluid fluid>
                <v-layout row wrap>
                <v-flex x3>
                    <v-checkbox
                            label='Només professors sense foto'
                            v-model="onlyteachersWithoutPhoto"
                    ></v-checkbox>
                    <v-btn
                           slot="activator"
                           class="white--text"
                           color="teal"
                           title="Assignar fotos automàticament. Només funciona si el fitxer original conté codi professor"
                           @click="assignPhotos()"
                           :loading="assigningPhotos"
                           :disabled="assigningPhotos"
                    >
                        <v-icon dark>settings</v-icon> Automàtic
                    </v-btn>
                </v-flex>
                <v-flex xs9>
                    <v-text-field
                            v-model="searchTeacherPhoto"
                            append-icon="search"
                            label="Busqueu un professor"
                            single-line
                            hide-details
                    ></v-text-field>
                </v-flex>
            </v-layout>
            </v-container>
        </v-flex>
        <v-flex xs12>
            <v-container fluid>
                <v-layout v-if="enabledGrid">
                <v-data-iterator
                        :items="filteredTeachers"
                        content-tag="v-layout"
                        row
                        wrap
                        no-data-text="No hi han dades disponibles"
                        no-results-text="No s'ha trobat cap registre coincident"
                        rows-per-page-text="Fotos per pàgina"
                        :rows-per-page-items="[12, {'text':'Tots','value':-1},24,36,50,100]"
                        :search="searchTeacherPhoto"
                        :pagination.sync="pagination"
                >
                    <v-flex
                            slot="item"
                            slot-scope="{ item : teacher}"
                            xs12
                            sm6
                            md4
                            lg2
                            xl1 style="min-width: 130px;">
                        <v-card height="100%">
                            <v-card-media :src="teacherPhotoPath(teacher)" height="200px" >
                            </v-card-media>
                            <v-card-title primary-title>
                                <p>
                                    <span> {{ teacher.code }} {{ teacher.user.name }} ({{ teacher.id }})</span>
                                </p>
                            </v-card-title>
                            <v-card-actions>
                                <v-btn v-if="photo && !teacher.user.photo"
                                       slot="activator"
                                       flat color="teal"
                                       icon
                                       title="Assignar foto seleccionada"
                                       @click="assignPhoto(teacher)"
                                       :loading="assigningPhoto"
                                       :disabled="assigningPhoto"
                                >
                                    <v-icon dark>add</v-icon>
                                </v-btn>

                                <v-btn v-if="teacher.user.photo"
                                       slot="activator"
                                       flat color="red"
                                       icon
                                       title="Desassignar foto seleccionada"
                                       @click="unassignPhoto(teacher)"
                                       :loading="unassigningPhoto"
                                       :disabled="unassigningPhoto"
                                >
                                    <v-icon dark>remove</v-icon>
                                </v-btn>

                                <v-btn v-if="teacher.user.photo"
                                       title="Baixar la foto"
                                       flat
                                       :href="'/user/' + teacher.user.hashid + '/photo/download?' + encodeURI((new Date()).toString())"
                                       icon>
                                    <v-icon dark>file_download</v-icon>
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-data-iterator>
            </v-layout>
            </v-container>
        </v-flex>
    </v-layout>
</v-container>

</template>

<style>
    .upload > input
    {
        display: none;
    }
</style>

<script>
import axios from 'axios'
import withSnackbar from '../mixins/withSnackbar'
import UserAvatar from '../ui/UserAvatarComponent'

export default {
  name: 'TeachersPhotosComponent',
  components: {
    'user-avatar': UserAvatar
  },
  mixins: [withSnackbar],
  data () {
    return {
      searchTeacherPhoto: '',
      pagination: {
        rowsPerPage: 12
      },
      assigningPhoto: false,
      assigningPhotos: false,
      unassigningPhoto: false,
      confirmingRemove: null,
      dimensionsAlert: true,
      deleting: null,
      deletingZip: false,
      uploading: false,
      uploadingZip: false,
      fileUploaded: false,
      refreshing: false,
      photo: null,
      zipFile: null,
      availableGrid: false,
      enabledGrid: true,
      photoPath: '',
      internalAvailablePhotos: this.availablePhotos,
      internalZips: this.zips,
      errors: [],
      zipErrors: [],
      onlyteachersWithoutPhoto: true,
      editing: null,
      filename: '',
      pushEditing: false,
      removingAll: false,
      removeAlldialog: false,
      removeDialog: false,
      internalTeachers: this.teachers
    }
  },
  computed: {
    showPhoto () {
      return this.uploading || this.uploadingZip || this.fileUploaded
    },
    filteredTeachers () {
      let result = []
      if (this.onlyteachersWithoutPhoto) {
        result = this.internalTeachers.filter(teacher => {
          return teacher.user.photo === null
        })
      } else result = this.internalTeachers

      result.map(teacher => {
        teacher.name = teacher.user.name
        return teacher
      })

      return result
    }
  },
  props: {
    availablePhotos: {
      type: Array,
      required: true
    },
    zips: {
      type: Array,
      required: true
    },
    teachers: {
      type: Array,
      required: true
    }
  },
  methods: {
    teacherPhotoPath (teacher) {
      return '/user/' + teacher.user.hashid + '/photo?hash=' + teacher.user.photo_hash
    },
    assignPhotos () {
      this.assigningPhotos = true
      axios.post('/api/v1/teachers/photos').then(response => {
        this.assigningPhotos = false
        this.showMessage(response.data + ' Fotos assignades correctament')
        this.refresh()
      }).catch(error => {
        this.assigningPhotos = false
        this.showError(error)
      })
    },
    assignPhoto (teacher) {
      this.assigningPhoto = true
      axios.post('/api/v1/teacher/' + teacher.user.id + '/photo', {
        photo: this.photo.slug
      }).then(response => {
        this.assigningPhoto = false
        teacher.user.photo = response.data.photo
        teacher.user.photo_hash = response.data.photo_hash
        this.refresh()
        this.showMessage('Foto assignada correctament')
      }).catch(error => {
        this.assigningPhoto = false
        this.showError(error)
      })
    },
    unassignPhoto (teacher) {
      this.unassigningPhoto = true
      axios.delete('/api/v1/teacher/' + teacher.user.id + '/photo').then(response => {
        this.unassigningPhoto = false
        teacher.user.photo = null
        teacher.user.photo_hash = null
        this.refresh()
        this.showMessage('Foto desassignada correctament')
      }).catch(error => {
        this.unassigningPhoto = false
        this.showError(error)
      })
    },
    upload () {
      this.$refs.photo.click()
    },
    uploadZip () {
      this.$refs.zip.click()
    },
    refresh () {
      this.refreshing = true
      axios.get('/api/v1/unassigned_teacher_photo')
        .then(response => {
          this.refreshing = false
          this.internalAvailablePhotos = response.data
        })
        .catch(error => {
          this.refreshing = false
          this.showError(error)
        })
    },
    photoChange (event) {
      this.uploading = true
      let target = event.target || event.srcElement
      if (target.value.length !== 0) {
        const formData = new FormData()
        formData.append('teacher_photo', this.$refs.photo.files[0])

        this.preview()

        this.save(formData)
      }
    },
    zipChange (event) {
      this.uploadingZip = true
      let target = event.target || event.srcElement
      if (target.value.length !== 0) {
        const formData = new FormData()
        formData.append('photos', this.$refs.zip.files[0])
        this.saveZip(formData)
      }
    },
    preview () {
      if (this.$refs.photo.files && this.$refs.photo.files[0]) {
        let reader = new FileReader()
        reader.onload = e => {
          this.$refs.photoImage.setAttribute('src', e.target.result)
        }
        reader.readAsDataURL(this.$refs.photo.files[0])
      }
    },
    errorOnPhoto () {
      // TODO
    },
    cancelRemove (photo) {
      this.confirmingRemove = null
    },
    confirmRemove (photo) {
      this.confirmingRemove = photo.slug
    },
    removeSelectedPhoto () {
      this.deleting = this.photo.slug
      this.remove(this.photo)
    },
    remove (photo) {
      this.deleting = photo.slug
      axios.delete('/api/v1/unassigned_teacher_photo/' + photo.slug)
        .then(response => {
          this.deleting = null
          this.internalAvailablePhotos.splice(this.internalAvailablePhotos.indexOf(photo), 1)
          this.removeDialog = false
        })
        .catch(error => {
          this.showError(error)
        })
    },
    save (formData) {
      axios.post('/api/v1/unassigned_teacher_photo', formData)
        .then(response => {
          this.uploading = false
          this.fileUploaded = true
          this.internalAvailablePhotos.push(response.data)
          this.errors = []
        })
        .catch(error => {
          this.uploading = false
          this.errors = error.data && error.data.errors
          this.showError(error)
        })
    },
    saveZip (formData) {
      axios.post('/api/v1/unassigned_teacher_photos', formData)
        .then(response => {
          this.uploadingZip = false
          this.errors = []
          const zip = {
            filename: response.data.filename,
            slug: response.data.slug
          }
          this.internalZips.push(zip)
          this.zipFile = zip
        })
        .catch(error => {
          this.uploadingZip = false
          this.errors = error.data && error.data.errors
          this.showError(error)
        })
    },
    removeSelectedZip () {
      this.deletingZip = true
      axios.delete('/api/v1/unassigned_teacher_photos/' + this.zipFile.slug)
        .then(response => {
          this.deletingZip = false
          this.internalZips.splice(this.internalZips.indexOf(this.zipFile), 1)
        })
        .catch(error => {
          this.deletingZip = false
          this.showError(error)
        })
    },
    confirmEdit (photo) {
      this.editing = photo.slug
      this.filename = photo.filename
      window.Vue.nextTick(() => {
        document.getElementById('filename').focus()
      })
    },
    edit (photo) {
      this.pushEditing = true
      axios.put('/api/v1/teacher_photo/' + photo.slug, { filename: this.filename })
        .then(response => {
          photo.slug = response.data
          photo.filename = this.filename
          this.pushEditing = false
          this.editing = null
        })
        .catch(error => {
          this.pushEditing = false
          this.showError(error)
        })
    },
    cancelEditing (photo) {
      this.editing = null
    },
    removeAll () {
      this.removingAll = true
      axios.delete('/api/v1/unassigned_teacher_photos')
        .then(response => {
          this.removingAll = false
          this.removeAlldialog = false
          this.internalAvailablePhotos = []
        })
        .catch(error => {
          this.removingAll = false
          this.showError(error)
        })
    }
  }
}
</script>
