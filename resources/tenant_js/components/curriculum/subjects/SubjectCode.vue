<template>
    <v-text-field
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
    subjectGroup: {},
    errorMessages: {
      type: Array,
      required: false
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
      let code = ''
      if (this.course) {
        code = this.course.code
        if (this.subjectGroup) code = code + '_' + this.subjectGroup.code
      }
      return code
    }
  },
  created () {
    if (!this.code) {
      this.calculateCode()
    }
  }
}
</script>
