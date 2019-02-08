<template>
    <v-text-field
            name="floor_number"
            label="# pis"
            hint="P.ex. 2a o 1 o buit"
            v-model="dataFloorNumber"
            @input="input"
            @blur="blur"
    ></v-text-field>
</template>

<script>
export default {
  name: 'StreetFloorNumberField',
  data () {
    return {
      dataFloorNumber: this.floorNumber
    }
  },
  model: {
    prop: 'floorNumber',
    event: 'input'
  },
  props: {
    floorNumber: {}
  },
  watch: {
    floorNumber (floorNumber) {
      this.dataFloorNumber = floorNumber
    }
  },
  methods: {
    input () {
      this.$emit('input', this.dataFloorNumber)
    },
    blur () {
      this.$emit('blur', this.dataFloorNumber)
    }
  }
}
</script>
