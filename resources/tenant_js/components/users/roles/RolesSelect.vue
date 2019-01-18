<template>
    <v-autocomplete
            v-model="selectedRoles"
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
  methods: {
    input () {
      this.$emit('input', this.selectedRoles)
    }
  }
}
</script>
