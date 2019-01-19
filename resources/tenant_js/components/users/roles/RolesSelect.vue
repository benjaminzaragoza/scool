<template>
    <v-autocomplete
            v-model="dataSelectedRoles"
            :items="dataRoles"
            attach
            label="Roles"
            multiple
            return-object
            item-text="name"
            @input="input"
    >
        <template slot="selection" slot-scope="data">{{ data.item.name }}, </template>
        <template slot="item" slot-scope="data">
            <v-checkbox v-model="data.tile.props.value"></v-checkbox>
            {{ data.item.name }} ({{ data.item.guard_name }})
        </template>
    </v-autocomplete>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'RolesSelect',
  data () {
    return {
      dataSelectedRoles: this.selectedRoles
    }
  },
  model: {
    prop: 'selectedRoles',
    event: 'input'
  },
  props: {
    selectedRoles: {},
    roles: {
      type: Array,
      required: false
    },
    exclude: {
      type: Array,
      required: false
    }
  },
  computed: {
    ...mapGetters({
      storeRoles: 'roles'
    }),
    dataRoles () {
      if (this.roles) return this.excludeRoles(this.roles)
      else return this.excludeRoles(this.storeRoles)
    }
  },
  watch: {
    selectedRoles (selectedRoles) {
      this.dataSelectedRoles = selectedRoles
    }
  },
  methods: {
    excludeRoles (roles) {
      if (this.exclude) {
        if (this.exclude.length > 0) {
          return roles.filter((role1) => {
            return !this.exclude.some(role2 => { return role2.id === role1.id })
          })
        }
      }
      return roles
    },
    input () {
      this.$emit('input', this.dataSelectedRoles)
    }
  }
}
</script>
