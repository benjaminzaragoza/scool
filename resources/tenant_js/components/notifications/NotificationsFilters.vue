<template>
    <v-autocomplete
            name="filters"
            label="Altres filtres"
            :items="dataFilters"
            v-model="dataFilter"
            clearable
            @input="input"
            @blur="blur"
            chips
    ></v-autocomplete>
</template>

<script>
export default {
  name: 'NotificationsFilters',
  data () {
    return {
      dataFilters: this.filters,
      dataFilter: this.filter
    }
  },
  model: {
    prop: 'filter',
    event: 'input'
  },
  props: {
    filters: {
      filter: Array,
      required: true
    },
    filter: {}
  },
  watch: {
    filter (filter) {
      this.dataFilter = filter
    }
  },
  methods: {
    input () {
      this.$emit('input', this.dataFilter)
    },
    blur () {
      this.$emit('blur', this.dataFilter)
    }
  }
}
</script>
