<template>
    <v-text-field
            append-icon="refresh"
            @click:append="calculateCode"
            v-model="dataCode"
            name="code"
            label="Codi del MP"
            @input="input"
            @blur="blur"
            :error-messages="errorMessages"
            hint="Seguiu el format CODI-ESTUDI_CODI-MP. Exemple: DAM_MP1"
    ></v-text-field>
</template>
<script>
export default {
  name: 'SubjectGroupCode',
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
    number: {},
    study: {},
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
    },
    number () {
      this.calculateCode()
    },
    study () {
      this.calculateCode()
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
      if (this.study) code = this.study.code
      if (this.number) code = code + '_MP' + this.number
      this.dataCode = code
      this.$emit('input', this.dataCode)
    }
  },
  created () {
    if (!this.code || this.code === '') {
      this.calculateCode()
    }
  }
}
</script>
