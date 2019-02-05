<template>
    <v-combobox
            name="province"
            label="Província"
            tabindex = "-1"
            item-text="name"
            autocomplete
            :loading="loadingProvince"
            cache-items
            required
            clearable
            :items="provinces"
            :search-input.sync="searchProvinces"
            v-model="province"
            :error-messages="provinceErrors"
            @input="$v.province.$touch()"
            @blur="$v.province.$touch()"
    ></v-combobox>
</template>

<script>
export default {
  name: 'ProvinceField'
}
</script>
