<template>
    <v-text-field
            :label="label"
            v-model="code"
            :error-messages="errorMessages"
            @input="input"
            @blur="blur"
            required
    ></v-text-field>
</template>

<script>
  export default {
    name: 'TeacherCodeComponent',
    model: {
      prop: 'code',
      event: 'input'
    },
    data () {
      return {
        internalCode: this.code
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
    }
  }
</script>
