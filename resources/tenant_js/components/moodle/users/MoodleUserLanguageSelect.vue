<template>
    <v-autocomplete
            :name="name"
            :label="label"
            :items="languages"
            v-model="internalLanguage"
            @input="input"
            @blur="blur"
            clearable
    >
    </v-autocomplete>
</template>

<script>
export default {
  name: 'MoodleUserLanguageSelect',
  model: {
    prop: 'language',
    event: 'input'
  },
  data () {
    return {
      internalLanguage: this.language
    }
  },
  props: {
    language: {},
    languages: {
      type: Array,
      default: function () {
        return [
          'ca',
          'es',
          'en'
        ]
      }
    },
    name: {
      type: String,
      default: 'language'
    },
    label: {
      type: String,
      default: 'Idioma'
    }
  },
  watch: {
    language (language) {
      this.internalLanguage = language
    }
  },
  methods: {
    input () {
      this.$emit('input', this.internalLanguage)
    },
    blur () {
      this.$emit('blur', this.internalLanguage)
    }
  }
}
</script>
