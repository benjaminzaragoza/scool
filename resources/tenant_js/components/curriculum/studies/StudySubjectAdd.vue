<template>
    <span>
        <v-btn flat title="Afegir UF a l'estudi" icon @click="dialog = true" color="secondary" >
            <v-icon>fas fa-plus-circle</v-icon>
            <v-badge left overlap color="success">
              <span slot="badge" v-text="number" class="caption"></span>
            </v-badge>
        </v-btn>
        <v-dialog v-model="dialog" v-if="dialog" fullscreen @keydown.esc="dialog = false">
            <v-toolbar dense color="blue darken-3">
                <v-btn icon dark @click.native="dialog = false">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title class="white--text">Nova Unitat Formativa</v-toolbar-title>
            </v-toolbar>
            <v-card>
                    <v-card-text class="px-0 mb-2">
                        <v-container fluid grid-list-md text-xs-center>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <subject-add-form @close="dialog = false" :study="study"></subject-add-form>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>
        </v-dialog>
    </span>
</template>

<script>
import SubjectAddForm from '../subjects/SubjectAddForm'
export default{
  name: 'StudySubjectAdd',
  components: {
    'subject-add-form': SubjectAddForm
  },
  data () {
    return {
      dialog: false
    }
  },
  props: {
    study: {
      type: Object,
      required: true
    }
  },
  computed: {
    totalNumber () {
      return this.study.subjects_number || '?'
    },
    number () {
      if (this.study.subjects) return this.study.subjects.length
      return 0
    }
  }
}
</script>
