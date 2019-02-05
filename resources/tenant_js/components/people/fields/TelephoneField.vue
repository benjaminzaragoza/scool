<template>
    <v-text-field
            name="phone"
            label="Telèfon"
            v-model="phone"
    ></v-text-field>
</template>

<script>
export default {
  name: 'TelephoneField',
  data () {
    return {
      phone: null
    }
  }
}
</script>
