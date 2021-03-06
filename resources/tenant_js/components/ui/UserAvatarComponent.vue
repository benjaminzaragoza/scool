<template>
    <span>
        <v-avatar color="grey lighten-4" :size="size" v-if="hashId" :tile="tile" @click="change" @dblclick="$emit('dblclick')">
            <img ref="previewImage"
                 :src="imageUrl"
                 :alt="alt"
                 :title="alt">
            <form class="upload" v-if="editable">
                <input
                        ref="file"
                        type="file"
                        name="photo"
                        accept="image/*"
                        :disabled="uploading"
                        @change="photoChange"/>
            </form>
        </v-avatar>
        <v-tooltip bottom slot="activator" v-if="removable && user.photo">
            <v-btn icon slot="activator" style="margin: 0px" @click="remove" :loading="deleting" :disabled="deleting">
                <v-icon small color="pink">delete</v-icon>
            </v-btn>
            <span>Eliminar foto</span>
        </v-tooltip>
    </span>
</template>

<script>
import axios from 'axios'

export default {
  name: 'UserAvatarComponent',
  data () {
    return {
      uploading: false,
      deleting: false,
      path: ''
    }
  },
  props: {
    editable: {
      type: Boolean,
      default: false
    },
    removable: {
      type: Boolean,
      default: false
    },
    user: {
      type: Object,
      default: () => { return {} }
    },
    hashId: {
      required: true
    },
    size: {
      type: String,
      default: '40'
    },
    alt: {
      required: true
    },
    tile: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    imageUrl () {
      if (this.user.photo_hash) {
        return '/user/' + this.hashId + '/photo?' + this.user.photo_hash
      }
      return '/user/' + this.hashId + '/photo'
    }
  },
  methods: {
    photoChange (event) {
      this.uploading = true
      let target = event.target || event.srcElement
      if (target.value.length !== 0) {
        const formData = new FormData()
        formData.append('photo', this.$refs.file.files[0])
        this.preview()
        this.save(formData)
      }
    },
    save (formData) {
      axios.post('/api/v1/user/' + this.user.id + '/photo', formData)
        .then(response => {
          this.uploading = false
          this.path = response.data
          this.$emit('input', this.path)
        })
        .catch(error => {
          this.uploading = false
          this.$snackbar.showError(error)
        })
    },
    preview () {
      if (this.$refs.file.files && this.$refs.file.files[0]) {
        let reader = new FileReader()
        reader.onload = e => {
          this.$refs.previewImage.setAttribute('src', e.target.result)
        }
        reader.readAsDataURL(this.$refs.file.files[0])
      }
    },
    change () {
      if (this.editable) this.$refs.file.click()
      this.$emit('click')
    },
    async remove () {
      let res = await this.$confirm("Voleu eliminar l'Avatar de l'usuari?", {
        title: 'Esteu segurs?',
        buttonTrueText: 'Eliminar'
      })
      if (res) {
        this.deleting = true
        axios.delete('/api/v1/user/' + this.user.id + '/photo').then(response => {
          this.deleting = false
          this.path = ''
          this.$emit('input', this.path)
          this.$refs.previewImage.setAttribute('src', 'img/default.png')
          this.user.photo = null
        })
          .catch(error => {
            this.deleting = false
            this.$snackbar.showError(error)
          })
      }
    }
  }
}
</script>

<style scoped>
    .upload > input
    {
        display: none;
    }
</style>
