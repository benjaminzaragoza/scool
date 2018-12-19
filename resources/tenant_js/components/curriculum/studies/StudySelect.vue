<template>
    <v-autocomplete
            :name="name"
            :label="label"
            :items="studies"
            v-model="dataStudy"
            item-text="name"
            :item-value="itemValue"
            clearable
            @input="input"
            @blur="blur"
            :error-messages="errorMessages"
    >
        <template slot="item" slot-scope="{ item: study }">
            <v-list-tile-content>
                <v-list-tile-title v-html="study.name"></v-list-tile-title>
                <v-list-tile-sub-title v-html="study.code"></v-list-tile-sub-title>
            </v-list-tile-content>
        </template>
    </v-autocomplete>
</template>

<script>
export default {
  name: 'StudySelectComponent',
  data () {
    return {
      dataStudy: this.study
    }
  },
  model: {
    prop: 'study',
    event: 'input'
  },
  props: {
    studies: {
      type: Array,
      required: true
    },
    name: {
      type: String,
      default: 'study'
    },
    study: {},
    label: {
      type: String,
      default: 'Escolliu un estudi'
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
    study (newStudy) {
      this.dataStudy = newStudy
    }
  },
  methods: {
    input () {
      this.$emit('input', this.dataStudy)
    },
    blur () {
      this.$emit('blur', this.dataStudy)
    }
  }
}
</script>
