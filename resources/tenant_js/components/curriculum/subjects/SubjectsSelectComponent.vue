<template>
    <v-autocomplete
            :name="name"
            :label="label"
            :items="subjects"
            v-model="internalSubject"
            item-text="name"
            :item-value="itemValue"
            clearable
            @input="input"
            @blur="blur"
            :error-messages="errorMessages"
    >
        <template slot="item" slot-scope="{ item: subject }">
            <v-list-tile-content>
                <v-list-tile-title v-html="subject.name"></v-list-tile-title>
                <v-list-tile-sub-title v-html="subject.code"></v-list-tile-sub-title>
            </v-list-tile-content>
        </template>
    </v-autocomplete>
</template>

<script>
  export default {
    name: 'SubjectsSelectComponent',
    data () {
      return {
        internalSubject: this.subject
      }
    },
    model: {
      prop: 'subject',
      event: 'input'
    },
    props: {
      subjects: {
        type: Array,
        required: true
      },
      name: {
        type: String,
        default: 'subject'
      },
      subject: {},
      label: {
        type: String,
        default: 'Escolliu una Unitat Formativa'
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
      subject (newSubject) {
        this.internalSubject = newSubject
      }
    },
    methods: {
      input () {
        this.$emit('input', this.internalSubject)
      },
      blur () {
        this.$emit('blur', this.internalSubject)
      }
    }
  }
</script>
