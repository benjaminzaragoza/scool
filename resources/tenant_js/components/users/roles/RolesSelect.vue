<template>
    <v-autocomplete
            v-model="dataSelectedRoles"
            :items="roles"
            attach
            chips
            label="Roles"
            multiple
            item-value="id"
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
    }
  },
  watch: {
    selectedRoles (selectedRoles) {
      this.dataSelectedRoles = selectedRoles
    }
  },
  methods: {
    input () {
      this.$emit('input', this.dataSelectedRoles)
    }
  }
}
</script>
