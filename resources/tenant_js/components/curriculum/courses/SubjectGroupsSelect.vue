<template>
    <v-autocomplete
            :name="name"
            :label="label"
            :items="subjectGroups"
            v-model="internalSubjectGroup"
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
  name: 'SubjectGroupsSelect',
  data () {
    return {
      internalSubjectGroup: this.subjectGroup
    }
  },
  model: {
    prop: 'subjectGroup',
    event: 'input'
  },
  props: {
    name: {
      type: String,
      default: 'subjectGroup'
    },
    subjectGroup: {},
    label: {
      type: String,
      default: 'Escolliu un curs'
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
    subjectGroups () {
      return this.$store.getters.subjectGroups
    }
  },
  watch: {
    subjectGroup (subjectGroup) {
      this.internalSubjectGroup = subjectGroup
    }
  },
  methods: {
    input () {
      this.$emit('input', this.internalSubjectGroup)
    },
    blur () {
      this.$emit('blur', this.internalSubjectGroup)
    }
  }
}
</script>
