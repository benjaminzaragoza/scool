<template>
    <v-autocomplete
            :name="name"
            :label="label"
            :required="required"
            clearable
            :error-messages="errorMessages"
            @input="input()"
            @blur="blur()"
            :items="families"
            v-model="internalFamily"
            item-text="name"
            :item-value="itemValue"
    >
        <template slot="item" slot-scope="{item: family}">
            <v-list-tile-content v-text="family.name"></v-list-tile-content>
        </template>
    </v-autocomplete>
</template>

<script>
  export default {
    name: 'FamilySelectComponent',
    model: {
      prop: 'family',
      event: 'input'
    },
    data () {
      return {
        internalFamily: this.family
      }
    },
    props: {
      name: {
        type: String,
        default: 'name'
      },
      label: {
        type: String,
        default: 'Família'
      },
      families: {
        type: Array,
        required: true
      },
      family: {
        required: true
      },
      errorMessages: {
        type: Array,
        required: false
      },
      required: {
        type: Boolean,
        default: true
      },
      itemValue: {
        type: String,
        default: 'id'
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
    }
  }
</script>
