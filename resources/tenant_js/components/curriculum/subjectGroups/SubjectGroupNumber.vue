<template>
    <v-text-field
            append-icon="refresh"
            @click:append="recalculateNumber"
            autofocus
            v-model="dataNumber"
            name="number"
            label="Número del MP"
            @input="input"
            @blur="blur"
            :error-messages="errorMessages"
            hint="Les MPS estan númerades/ordenades, indiqueu el número que correspon al MP"
    ></v-text-field>
</template>
<script>
export default {
  name: 'SubjectGroupNumber',
  data () {
    return {
      dataNumber: this.number
    }
  },
  model: {
    prop: 'number',
    event: 'input'
  },
  props: {
    number: {},
    study: {},
    errorMessages: {
      type: Array,
      required: false
    }
  },
  watch: {
    number (number) {
      this.dataNumber = number
    }
  },
  methods: {
    input () {
      this.$emit('input', this.dataNumber)
    },
    blur () {
      this.$emit('blur', this.dataNumber)
    },
    recalculateNumber () {
      this.$emit('recalculate')
    }
  }
}
</script>
