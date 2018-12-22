<template>
    <v-text-field
            append-icon="refresh"
            @click:append="calculateCode"
            v-model="dataCode"
            name="code"
            label="Codi de la UF"
            @input="input"
            @blur="blur"
            :error-messages="errorMessages"
            hint="Seguiu el format CODICURS_CODIMODULPROFESSIONAL_CODIUF. Exemple: 2DAM_MP1_UF1"
            autofocus
    ></v-text-field>
</template>
<script>
export default {
  name: 'SubjectCode',
  data () {
    return {
      dataCode: this.code
    }
  },
  model: {
    prop: 'code',
    event: 'input'
  },
  props: {
    code: {},
    course: {},
    number: {},
    subjectGroup: {},
    errorMessages: {
      type: Array,
      required: false
    }
  },
  watch: {
    code (code) {
      if (code || code === '') {
        this.calculateCode()
      }
    }
  },
  methods: {
    input () {
      this.$emit('input', this.dataCode)
    },
    blur () {
      this.$emit('blur', this.dataCode)
    },
    calculateCode () {
      console.log('calculateCode!!!!!!')
      let code = ''
      console.log(this.course)
      if (this.course) {
        console.log('1')
        code = this.course.code
        if (this.subjectGroup) code = code + '_' + this.subjectGroup.code
      }
      this.dataCode = code
    }
  },
  created () {
    if (!this.code || this.code === '') {
      this.calculateCode()
    }
  }
}
</script>
