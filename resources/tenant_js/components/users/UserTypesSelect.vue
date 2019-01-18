<template>
    <v-autocomplete
            :name="name"
            :label="label"
            :items="userTypes"
            v-model="internalUserType"
            :item-text="itemText"
            :item-value="itemValue"
            chips
            clearable
            @input="input"
            @blur="blur"
    >
        <template slot="selection" slot-scope="data">
            <v-chip
                    @input="data.parent.selectItem(data.item)"
                    :selected="data.selected"
                    class="chip--select-multi"
                    :key="JSON.stringify(data.item)"
                    color="secondary"
            >
                {{ data.item.name }}
            </v-chip>
        </template>
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
      default: "Escolliu un tipus d'usuari"
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
