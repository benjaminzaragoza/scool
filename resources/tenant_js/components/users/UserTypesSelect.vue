<template>
    <v-autocomplete
            :name="name"
            :label="label"
            :items="userTypes"
            v-model="internalUserType"
            :item-text="itemText"
            :item-value="itemValue"
            clearable
            @input="input"
            @blur="blur"
    >
        <template slot="selection" slot-scope="data">{{ data.item.name }}</template>
        <template slot="item" slot-scope="{ item: userType }">
            {{ userType.name }}
        </template>
    </v-autocomplete>
</template>

<script>
export default {
  name: 'UserTypesSelect',
  model: {
    prop: 'user',
    event: 'input'
  },
  data () {
    return {
      internalUserType: this.userType
    }
  },
  props: {
    userType: {},
    userTypes: {
      type: Array,
      required: false
    },
    name: {
      type: String,
      default: 'user'
    },
    label: {
      type: String,
      default: "Tipus d'usuari"
    },
    itemValue: {
      type: String,
      default: 'id'
    },
    itemText: {
      type: String,
      default: 'name'
    }
  },
  watch: {
    userType (userType) {
      this.internalUserType = userType
    }
  },
  methods: {
    input () {
      this.$emit('input', this.internalUserType)
    },
    blur () {
      this.$emit('blur', this.internalUserType)
    }
  }
}
</script>
