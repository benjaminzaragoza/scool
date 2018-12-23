<template>
    <span>
        <v-autocomplete
                :name="name"
                :label="label"
                :items="dataSubjectGroupTypes"
                v-model="dataSubjectGroupType"
                item-text="full_search"
                :item-value="itemValue"
                clearable
                @input="input"
                @blur="blur"
                :error-messages="errorMessages"
        >
                <template
                        slot="selection"
                        slot-scope="{ item: SubjectGroupType }"
                >
                    {{ SubjectGroupType.code }} - {{ SubjectGroupType.name }}
              </template>
            <template slot="item" slot-scope="{ item: SubjectGroupType }">
                <v-list-tile-content>
                    <v-list-tile-title v-html="SubjectGroupType.name"></v-list-tile-title>
                    <v-list-tile-sub-title v-html="SubjectGroupType.code"></v-list-tile-sub-title>
                </v-list-tile-content>
            </template>
        </v-autocomplete>
    </span>
</template>

<script>
export default {
  name: 'SubjectGroupTypes',
  data () {
    return {
      dataSubjectGroupType: this.subjectGroupType
    }
  },
  model: {
    prop: 'subjectGroupType',
    event: 'input'
  },
  props: {
    name: {
      type: String,
      default: 'SubjectGroupType'
    },
    subjectGroupType: {},
    label: {
      type: String,
      default: 'Escolliu un tipus de Mòdul Professional'
    },
    errorMessages: {
      type: Array,
      required: false
    },
    itemValue: {
      type: String,
      default: null
    }
  },
  computed: {
    dataSubjectGroupTypes () {
      return this.$store.getters.subjectGroupTypes
    }
  },
  methods: {
    input () {
      this.$emit('input', this.dataSubjectGroupType)
    },
    blur () {
      this.$emit('blur', this.dataSubjectGroupType)
    }
  }
}
</script>
