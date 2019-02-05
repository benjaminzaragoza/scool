<template>
    <v-text-field
            name="street"
            label="Adreça"
            hint="P.ex. C/ Alcanyiz o Avg/ Generalitat"
            v-model="street"
            :error-messages="streetErrors"
            :counter="255"
            @input="$v.street.$touch()"
            @blur="$v.street.$touch()"
            required
    ></v-text-field>
</template>

<script>
export default {
  name: 'StreetNumberField',
  data () {
    return {
      value: ''
    }
  }
}
</script>

<style scoped>

</style>
