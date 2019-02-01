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
            :tabindex="tabindex"
            :error-messages="errorMessages"
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
    prop: 'userType',
    event: 'input'
  },
  data () {
    return {
      internalUserType: this.userType
    }
  },
  props: {
    userType: {},
    tabindex: {
      type: Number,
      required: false
    },
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
    },
    errorMessages: {
      type: Array,
      required: false
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
