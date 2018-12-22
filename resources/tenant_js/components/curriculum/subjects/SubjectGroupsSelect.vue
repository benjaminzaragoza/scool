<template>
    <span>
        <v-autocomplete
                prepend-icon="add"
                @click:prepend="dialog=true"
                :name="name"
                :label="label"
                :items="filteredSubjectGroups"
                v-model="dataSubjectGroup"
                item-text="full_search"
                :item-value="itemValue"
                clearable
                @input="input"
                @blur="blur"
                :error-messages="errorMessages"
        >
        <template
                slot="selection"
                slot-scope="{ item: subjectGroup }"
        >
                {{ subjectGroup.name }}
        </template>
        <template slot="item" slot-scope="{ item: subjectGroup }">
            <v-list-tile-content>
                <v-list-tile-title v-html="subjectGroup.name"></v-list-tile-title>
                <v-list-tile-sub-title v-html="subjectGroup.code"></v-list-tile-sub-title>
            </v-list-tile-content>
        </template>
    </v-autocomplete>
        <v-dialog v-model="dialog" v-if="dialog" fullscreen @keydown.esc="dialog=false">
            <v-toolbar color="blue darken-3">
                <v-btn icon dark @click.native="dialog=false">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title class="white--text title">Afegir Mòdul Professional</v-toolbar-title>
            </v-toolbar>
            <v-card>
                    <v-card-text class="px-0 mb-2">
                        <v-container fluid grid-list-md text-xs-center>
                            <v-layout row wrap>
                                <v-flex xs12>
                                     <subject-group-add @close="dialog = false"></subject-group-add>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>
        </v-dialog>
    </span>
</template>

<script>
import SubjectGroupAdd from './SubjectGroupAdd'
export default {
  name: 'SubjectGroupSelect',
  components: {
    'subject-group-add': SubjectGroupAdd
  },
  data () {
    return {
      dataSubjectGroup: this.subjectGroup,
      dialog: false
    }
  },
  model: {
    prop: 'subjectGroup',
    event: 'input'
  },
  props: {
    study: {
      default: null
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
      default: null
    }
  },
  computed: {
    filteredSubjectGroups () {
      if (this.study) {
        if (Number.isInteger(parseInt(this.study))) return this.filterSubjectGroupsByStudy(this.study)
        if (this.study.id) return this.filterSubjectGroupsByStudy(this.study.id)
      }
      return this.subjectGroups
    },
    subjectGroups () {
      return this.$store.getters.subjectGroups
    },
    subjectGroupId() {
      if (Number.isInteger(parseInt(this.subjectGroup))) return this.subjectGroup
      if (this.subjectGroup) return this.subjectGroup.id
      return null
    }
  },
  watch: {
    subjectGroup (newSubjectGroup) {
      this.dataSubjectGroup = newSubjectGroup
      this.selectSubjectGroup()
    }
  },
  methods: {
    input () {
      this.$emit('input', this.dataSubjectGroup)
    },
    blur () {
      this.$emit('blur', this.dataSubjectGroup)
    },
    filterSubjectGroups (id) {
      return this.subjectGroups.filter(subjectGroup => { return subjectGroup.id === id })
    },
    filterSubjectGroupsByStudy (id) {
      return this.subjectGroups.filter(subjectGroup => { return subjectGroup.study_id === id })
    },
    selectSubjectGroup () {
      if (this.itemValue === null) {
        this.dataSubjectGroup = this.filterSubjectGroups(this.subjectGroupId)[0]
      }
    }
  },
  created () {
    this.selectSubjectGroup()
  }
}
</script>
