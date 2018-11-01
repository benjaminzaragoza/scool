<template>
    <v-card height="100%">
        <img ref='previewImage' height="200" width="100%"
             :src="'img/placeholder.png'" @click="upload" >
        <v-card-title primary-title>
            <p>
                {{ this.label }}
            </p>
        </v-card-title>
        <v-card-actions>
            <form class="upload">
                <input
                        ref="file"
                        type="file"
                        :name="name"
                        accept="image/*"
                        :disabled="uploading"
                        @change="fileChange"/>
            </form>

            <v-btn
                    flat color="teal"
                    :loading="uploading"
                    :disabled="uploading"
                    @click.native="upload"
                    icon
            >
                <v-icon dark>file_upload</v-icon>
            </v-btn>
            <confirm-icon icon="delete"
                          color="pink"
                          :working="deleting"
                          @confirmed="remove()"
                          tooltip="Eliminar"
            ></confirm-icon>
        </v-card-actions>
    </v-card>
</template>

<script>
import axios from 'axios'
import withSnackbar from '../mixins/withSnackbar'
import ConfirmIconComponent from './ConfirmIconComponent'

export default {
  components: {
    'confirm-icon': ConfirmIconComponent
  },
  name: 'UploadCardComponent',
  mixins: [withSnackbar],
  data () {
    return {
      uploading: false,
      deleting: false,
      path: ''
    }
  },
  props: {
    name: {
      type: String,
      required: true
    },
    label: {
      type: String,
      required: true
    },
    url: {
      type: String,
      default: '/file/upload/to/local'
    },
    removeUrl: {
      type: String,
      default: '/file/remove/from/local'
    }
  },
  methods: {
    fileChange (event) {
      this.uploading = true
      let target = event.target || event.srcElement
      if (target.value.length !== 0) {
        const formData = new FormData()
        formData.append(this.name, this.$refs.file.files[0])
        this.preview()
        this.save(formData)
      }
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
    save (formData) {
      axios.post(this.url, formData)
        .then(response => {
          this.uploading = false
          this.path = response.data
          this.$emit('input', this.path)
        })
        .catch(error => {
          this.uploading = false
          this.showError(error)
        })
    },
    upload () {
      this.$refs.file.click()
    },
    remove () {
      this.deleting = true
      axios.post(this.removeUrl, { path: this.path })
        .then(response => {
          this.deleting = false
          this.path = ''
          this.$emit('input', this.path)
          this.$refs.previewImage.setAttribute('src', 'img/placeholder.png')
        })
        .catch(error => {
          this.deleting = false
          console.log(error)
          this.showError(error)
        })
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
