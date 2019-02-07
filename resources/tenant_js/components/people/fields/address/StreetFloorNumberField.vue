<template>
    <v-text-field
            name="floor_number"
            label="# pis"
            v-model="dataFloorNumber"
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
  }
}
</script>
