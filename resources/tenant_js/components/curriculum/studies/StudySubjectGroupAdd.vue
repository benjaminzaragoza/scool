<template>
    <span>
        <v-btn flat title="Afegir MP a l'estudi" icon @click="dialog = true" color="success" >
            <v-icon>fas fa-plus</v-icon>
            <v-badge left overlap color="primary">
              <span slot="badge" v-text="number" class="caption"></span>
            </v-badge>
        </v-btn>
        <v-dialog v-model="dialog" v-if="dialog" fullscreen @keydown.esc="dialog = false">
            <v-toolbar dense color="blue darken-3">
                <v-btn icon dark @click.native="dialog = false">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title class="white--text">Nou Mòdul Professional</v-toolbar-title>
            </v-toolbar>
            <v-card>
                    <v-card-text class="px-0 mb-2">
                        <v-container fluid grid-list-md text-xs-center>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <subject-group-add-form @close="dialog = false" :study="study"></subject-group-add-form>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>
        </v-dialog>
    </span>
</template>

<script>
import SubjectGroupAddForm from '../subjectGroups/SubjectGroupAddForm'

export default{
  name: 'StudySubjectGroupAdd',
  components: {
    'subject-group-add-form': SubjectGroupAddForm
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
      return this.study.subject_groups_number || '?'
    },
    number () {
      if (this.study.subjectGroups) return this.study.subjectGroups.length
      return 0
    }
  }
}
</script>
