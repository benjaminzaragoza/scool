<template>
    <v-select
            :label="label"
            :items="administrativeStatuses"
            v-model="internalAdministrativeStatus"
            item-text="name"
            :item-value="itemValue"
            autocomplete
            clearable
            @input="input"
            :error-messages="errorMessages"
    >
    </v-select>
</template>

<style>

</style>

<script>
  export default {
    data () {
      return {
        internalAdministrativeStatus: this.administrativeStatus
      }
    },
    model: {
      prop: 'administrativeStatus',
      event: 'input'
    },
    props: {
      administrativeStatus: {
        type: Object
      },
      label: {
        type: String,
        default: 'Escolliu el tipus de professor'
      },
      administrativeStatuses: {
        type: Array,
        required: true
      },
      errorMessages: {
        required: false
      },
      itemValue: {
        type: String,
        default: 'id'
      }
    },
    methods: {
      input (value) {
        this.$emit('input', value)
      }
    }
  }
</script>
