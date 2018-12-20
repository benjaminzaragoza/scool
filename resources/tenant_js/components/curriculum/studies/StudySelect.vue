<template>
    <span>
        <v-autocomplete
                prepend-icon="add"
                @click:prepend="dialog=true"
                :name="name"
                :label="label"
                :items="dataStudies"
                v-model="dataStudy"
                item-text="full_search"
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
        <v-dialog v-model="dialog" v-if="dialog" fullscreen @keydown.esc="dialog=false">
            <v-toolbar color="blue darken-3">
                <v-btn icon dark @click.native="dialog=false">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title class="white--text title">Afegir estudi</v-toolbar-title>
            </v-toolbar>
            <v-card>
                    <v-card-text class="px-0 mb-2">
                        <v-container fluid grid-list-md text-xs-center>
                            <v-layout row wrap>
                                <v-flex xs12>
                                     <study-add
                                             @close="dialog = false"
                                             :departments="departments"
                                             :families="families"
                                             @added="add"
                                     ></study-add>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>
        </v-dialog>
    </span>
</template>

<script>
import StudyAdd from './StudyAddComponent'

export default {
  name: 'StudySelect',
  components: {
    'study-add': StudyAdd
  },
  data () {
    return {
      dataStudy: this.study,
      dialog: false
    }
  },
  model: {
    prop: 'study',
    event: 'input'
  },
  props: {
    studies: {
      type: Array,
      required: false
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
  computed: {
    dataStudies () {
      return this.$store.getters.studies
    },
    departments () {
      return this.$store.getters.departments
    },
    families () {
      return this.$store.getters.families
    }
  },
  watch: {
    study (newStudy) {
      this.dataStudy = newStudy
    }
  },
  methods: {
    add (study) {
      this.dataStudies.push(study)
      this.dataStudy = this.dataStudies.find(s => {
        return s.id === study.id
      })
    },
    input () {
      this.$emit('input', this.dataStudy)
    },
    blur () {
      this.$emit('blur', this.dataStudy)
    }
  }
}
</script>
