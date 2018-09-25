<template>
    <v-autocomplete
            :name="name"
            :label="label"
            :required="required"
            clearable
            :error-messages="errorMessages"
            @input="input()"
            @blur="blur()"
            :items="specialties"
            v-model="internalSpecialty"
            item-text="full_search"
            :item-value="itemValue"
            return-object
    >
        <template slot="item" slot-scope="{item: specialty}">
            <v-list-tile-content v-text="specialty.code + ' ' + specialty.name"></v-list-tile-content>
        </template>
    </v-autocomplete>
</template>

<script>
  export default {
    name: 'SpecialtySelectComponent',
    model: {
      prop: 'specialty',
      event: 'input'
    },
    data () {
      return {
        internalSpecialty: this.specialty
      }
    },
    props: {
      name: {
        type: String,
        default: 'name'
      },
      label: {
        type: String,
        default: 'Especialitat'
      },
      specialties: {
        type: Array,
        required: true
      },
      specialty: {
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
      specialty (newSpecialty) {
        this.internalSpecialty = newSpecialty
      }
    },
    methods: {
      input () {
        this.$emit('input', this.internalSpecialty)
      },
      blur () {
        this.$emit('blur', this.internalSpecialty)
      }
    }
  }
</script>
