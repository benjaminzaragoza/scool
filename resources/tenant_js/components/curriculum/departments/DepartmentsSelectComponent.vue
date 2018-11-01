<template>
    <v-autocomplete
            :name="name"
            :label="label"
            :items="departments"
            v-model="internalDepartment"
            item-text="name"
            :item-value="itemValue"
            clearable
            @input="input"
            @blur="blur"
            :error-messages="errorMessages"
    >
        <template slot="item" slot-scope="{ item: department }">
            <v-list-tile-content>
                <v-list-tile-title v-html="department.name"></v-list-tile-title>
                <v-list-tile-sub-title v-html="department.code"></v-list-tile-sub-title>
            </v-list-tile-content>
        </template>
    </v-autocomplete>
</template>

<script>
export default {
  name: 'DepartmentsSelectComponent',
  data () {
    return {
      internalDepartment: this.department
    }
  },
  model: {
    prop: 'department',
    event: 'input'
  },
  props: {
    departments: {
      type: Array,
      required: true
    },
    name: {
      type: String,
      default: 'department'
    },
    department: {},
    label: {
      type: String,
      default: 'Escolliu un departament'
    },
    errorMessages: {
      type: Array,
      required: false
    },
    itemValue: {
      type: String,
      default: 'id'
    }
  },
  watch: {
    department (newDepartment) {
      this.internalDepartment = newDepartment
    }
  },
  methods: {
    input () {
      this.$emit('input', this.internalDepartment)
    },
    blur () {
      this.$emit('blur', this.internalDepartment)
    }
  }
}
</script>
