<template>
    <v-autocomplete
            :name="name"
            :label="label"
            :items="subjectGroups"
            v-model="dataSubjectGroup"
            item-text="name"
            :item-value="itemValue"
            clearable
            @input="input"
            @blur="blur"
            :error-messages="errorMessages"
    >
        <template slot="item" slot-scope="{ item: subjectGroup }">
            <v-list-tile-content>
                <v-list-tile-title v-html="subjectGroup.name"></v-list-tile-title>
                <v-list-tile-sub-title v-html="subjectGroup.code"></v-list-tile-sub-title>
            </v-list-tile-content>
        </template>
    </v-autocomplete>
</template>

<script>
export default {
  name: 'SubjectSelectComponent',
  data () {
    return {
      dataSubjectGroup: this.subjectGroup
    }
  },
  model: {
    prop: 'subjectGroup',
    event: 'input'
  },
  props: {
    subjectGroups: {
      type: Array,
      required: true
    },
    name: {
      type: String,
      default: 'subjectGroup'
    },
    subjectGroup: {},
    label: {
      type: String,
      default: 'Escolliu un Mòdul professional'
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
    subjectGroup (newSubjectGroup) {
      this.dataSubjectGroup = newSubjectGroup
    }
  },
  methods: {
    input () {
      this.$emit('input', this.dataSubjectGroup)
    },
    blur () {
      this.$emit('blur', this.dataSubjectGroup)
    }
  }
}
</script>
