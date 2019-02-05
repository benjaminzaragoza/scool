<template>
    <v-combobox
            name="postal_code"
            label="Codi postal"

            :loading="loadingPostalCode"
            cache-items
            required
            autocomplete
            clearable
            :error-messages="postalCodeErrors"
            @input="$v.postal_code.$touch()"
            @blur="$v.postal_code.$touch()"
            :items="postalCodes"
            :search-input.sync="searchPostalCodes"
            v-model="postal_code"
    ></v-combobox>
</template>

<script>
export default {
  name: 'PostalcodeField'
}
</script>
