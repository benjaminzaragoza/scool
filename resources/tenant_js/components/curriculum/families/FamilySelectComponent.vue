<template>
    <v-autocomplete
            :name="name"
            :label="label"
            :items="dataFamilies"
            v-model="internalFamily"
            item-text="name"
            :item-value="itemValue"
            clearable
            @input="input"
            @blur="blur"
            :error-messages="errorMessages"
    >
        <template slot="item" slot-scope="{ item: family }">
            <v-list-tile-content>
                <v-list-tile-title v-html="family.name"></v-list-tile-title>
                <v-list-tile-sub-title v-html="family.code"></v-list-tile-sub-title>
            </v-list-tile-content>
        </template>
    </v-autocomplete>
</template>

<script>
export default {
  name: 'FamilySelectComponent',
  data () {
    return {
      internalFamily: this.family,
      dataFamilies: []
    }
  },
  model: {
    prop: 'family',
    event: 'input'
  },
  props: {
    families: {
      type: Array,
      required: false
    },
    name: {
      type: String,
      default: 'family'
    },
    family: {},
    label: {
      type: String,
      default: 'Escolliu una família'
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
  computed: {
    storeFamilies () {
      return this.$store.getters.families
    }
  },
  watch: {
    family (newFamily) {
      this.internalFamily = newFamily
    }
  },
  methods: {
    input () {
      this.$emit('input', this.internalFamily)
    },
    blur () {
      this.$emit('blur', this.internalFamily)
    }
  },
  created () {
    this.dataFamilies = this.families ? this.families : this.storeFamilies
  }
}
</script>
