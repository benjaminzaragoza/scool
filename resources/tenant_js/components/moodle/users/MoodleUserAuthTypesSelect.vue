<template>
    <v-autocomplete
            :name="name"
            :label="label"
            :items="userAuthTypes"
            v-model="internalUserAuthType"
            @input="input"
            @blur="blur"
            clearable
    >
    </v-autocomplete>
</template>

<script>
export default {
  name: 'MoodleUserAuthTypesSelect',
  model: {
    prop: 'userAuthType',
    event: 'input'
  },
  data () {
    return {
      internalUserAuthType: this.userAuthType
    }
  },
  props: {
    userAuthType: {},
    userAuthTypes: {
      type: Array,
      default: function () {
        return [
          'manual',
          'nologin',
          'ldap'
        ]
      }
    },
    name: {
      type: String,
      default: 'authType'
    },
    label: {
      type: String,
      default: "Tipus d'autenticació"
    }
  },
  watch: {
    userAuthType (userAuthType) {
      this.internalUserAuthType = userAuthType
    }
  },
  methods: {
    input () {
      this.$emit('input', this.internalUserAuthType)
    },
    blur () {
      this.$emit('blur', this.internalUserAuthType)
    }
  }
}
</script>
