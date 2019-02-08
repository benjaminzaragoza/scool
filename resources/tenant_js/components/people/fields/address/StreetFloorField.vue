<template>
    <v-text-field
            name="floor"
            label="Pis"
            hint="P.ex. 3r o 4 o Àtic o buit"
            v-model="dataFloor"
            @input="input"
            @blur="blur"
    ></v-text-field>
</template>

<script>
export default {
  name: 'StreetFloorField',
  data () {
    return {
      dataFloor: this.floor
    }
  },
  model: {
    prop: 'floor',
    event: 'input'
  },
  props: {
    floor: {},
    required: {
      type: Boolean,
      default: false
    }
  },
  watch: {
    floor (floor) {
      this.dataFloor = floor
    }
  },
  methods: {
    input () {
      this.$emit('input', this.dataFloor)
    },
    blur () {
      this.$emit('blur', this.dataFloor)
    }
  }
}
</script>
