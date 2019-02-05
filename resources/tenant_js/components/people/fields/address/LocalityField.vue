<template>
    <v-combobox
            name="locality"
            label="Localitat"
            tabindex = "-1"
            item-text="name"
            :loading="loadingLocality"
            cache-items
            required
            autocomplete
            clearable
            :items="localities"
            :search-input.sync="searchLocalities"
            :error-messages="localityErrors"
            v-model="locality"
            @input="$v.locality.$touch()"
            @blur="$v.locality.$touch()"
    ></v-combobox>
</template>

<script>
export default {
  name: 'LocalityField'
}
</script>
