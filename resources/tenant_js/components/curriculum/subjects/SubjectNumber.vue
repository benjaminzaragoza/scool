<template>
    <v-text-field
            v-model="dataNumber"
            name="number"
            label="Número de la UF"
            @input="input"
            @blur="blur"
            :error-messages="errorMessages"
            hint="Les UFs estan númerades, indiqueu el número que correspon a la UF"
    ></v-text-field>
</template>
<script>
export default {
  name: 'SubjectNumber',
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
    subjectGroup: {},
    errorMessages: {
      type: Array,
      required: false
    }
  },
  methods: {
    input () {
      this.$emit('input', this.dataNumber)
    },
    blur () {
      this.$emit('blur', this.dataNumber)
    }
  }
}
</script>
