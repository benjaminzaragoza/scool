<template>
    <v-text-field
            :label="label"
            v-model="code"
            :error-messages="errorMessages"
            @input="input"
            @blur="blur"
            :loading="loading"
            required
    ></v-text-field>
</template>

<script>
  import axios from 'axios'
  import withSnackbar from '../mixins/withSnackbar'

  export default {
    name: 'TeacherCodeComponent',
    mixins: [withSnackbar],
    model: {
      prop: 'code',
      event: 'input'
    },
    data () {
      return {
        internalCode: this.code,
        loading: false
      }
    },
    props: {
      code: {
        required: true
      },
      errorMessages: {
        type: Array,
        required: false
      },
      label: {
        type: String,
        default: 'Codi professor'
      }
    },
    watch: {
      code (newCode) {
        this.internalCode = newCode
      }
    },
    methods: {
      input () {
        this.$emit('input', this.internalCode)
      },
      blur () {
        this.$emit('blur', this.internalCode)
      }
    },
    created () {
      if (this.code === null) {
        this.loading = true
        axios.get('/api/v1/teacher/available_code').then(response => {
          this.loading = false
          console.log(response)
        }).catch(error => {
          this.loading = false
          console.log(error)
          this.showError(error)
        })
      }
    }
  }
</script>
