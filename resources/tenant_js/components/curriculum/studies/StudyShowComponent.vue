<template>
    <v-card>
        <v-toolbar dark color="primary" :id="'study_' + study.id + '_show_toolbar'">
            <span class="hidden-sm-and-down">
              {{ study.id }}
            </span>
            <v-toolbar-title>
                <inline-text-field-edit-dialog :object="dataStudy" field="code" label="Codi" @save="refresh"></inline-text-field-edit-dialog>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <study-delete :study="study" v-role="'IncidentsManager'" :alt="true" @before="$emit('close')" class="hidden-sm-and-down"></study-delete>
                <v-btn dark flat @click.native="$emit('close')" class="hidden-sm-and-down">
                    Sortir
                    <v-icon right dark>exit_to_app</v-icon>
                </v-btn>
            </v-toolbar-items>
        </v-toolbar>
        <v-container text-md-center class="pb-0 pt-1">
            <v-expansion-panel class="mb-3 mt-2" :value="show">
                <v-expansion-panel-content>
                    <div slot="header" class="font-weight-medium">Dades del estudi</div>
                    <v-layout row wrap>
                        <v-flex md8>
                            <v-list two-line subheader>
                                <v-list-tile avatar>
                                    <v-list-tile-content>
                                        <v-list-tile-title>
                                            <inline-text-field-edit-dialog v-model="dataStudy" field="name" label="Nom" @save="refresh()"></inline-text-field-edit-dialog>
                                        </v-list-tile-title>
                                        <v-list-tile-sub-title>Nom</v-list-tile-sub-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                                <v-list-tile avatar>
                                    <v-list-tile-content>
                                        <v-list-tile-title>
                                            <inline-text-field-edit-dialog v-model="dataStudy" field="shortname" label="Nom curt" @save="refresh()"></inline-text-field-edit-dialog>
                                        </v-list-tile-title>
                                        <v-list-tile-sub-title>Nom curt</v-list-tile-sub-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                                <v-list-tile avatar>
                                    <v-list-tile-content>
                                        <v-list-tile-title>
                                            <inline-text-field-edit-dialog v-model="dataStudy" field="code" label="Codi" @save="refresh()"></inline-text-field-edit-dialog>
                                        </v-list-tile-title>
                                        <v-list-tile-sub-title>Codi</v-list-tile-sub-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                                <v-list-tile avatar>
                                    <v-list-tile-content>
                                        <v-list-tile-title>
                                            <study-tags @refresh="refresh(false)" :study="study"></study-tags>
                                        </v-list-tile-title>
                                        <v-list-tile-sub-title>Etiquetes</v-list-tile-sub-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                            </v-list>
                        </v-flex>
                        <v-flex md4>
                            <v-list two-line>
                                <v-list-tile avatar>
                                    <v-list-tile-content>
                                        <v-list-tile-title :title="study.formatted_created_at" v-text="study.formatted_created_at_diff"></v-list-tile-title>
                                        <v-list-tile-sub-title>Data de creació
                                        </v-list-tile-sub-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                                <v-list-tile avatar>
                                    <v-list-tile-content>
                                        <v-list-tile-title :title="study.formatted_updated_at" v-text="study.formatted_created_at_diff">Password</v-list-tile-title>
                                        <v-list-tile-sub-title>Data de modificació
                                        </v-list-tile-sub-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                                <v-list-tile>
                                    <v-list-tile-content>
                                        <v-list-tile-title>
                                            <study-family :study="study" @assigned="refresh"></study-family>
                                        </v-list-tile-title>
                                        <v-list-tile-sub-title>Familia
                                        </v-list-tile-sub-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                                <v-list-tile>
                                    <v-list-tile-content>
                                        <v-list-tile-title>
                                            <study-department :study="study" @assigned="refresh"></study-department>
                                        </v-list-tile-title>
                                        <v-list-tile-sub-title>Departament
                                        </v-list-tile-sub-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                            </v-list>
                        </v-flex>
                    </v-layout>
                </v-expansion-panel-content>
            </v-expansion-panel>
        </v-container>
    </v-card>
</template>

<script>
import StudyDeleteComponent from './StudyDeleteComponent'
import InlineTextAreaEditDialog from '../../ui/InlineTextAreaEditDialog'
import InlineTextFieldEditDialog from '../../ui/InlineTextFieldEditDialog'
import StudyTagsComponent from './StudyTagsComponent'
import StudyDepartment from './StudyDepartment'
import StudyFamily from './StudyFamily'

import * as actions from '../../../store/action-types'

export default {
  name: 'IncidentShow',
  components: {
    'study-delete': StudyDeleteComponent,
    'inline-text-area-edit-dialog': InlineTextAreaEditDialog,
    'inline-text-field-edit-dialog': InlineTextFieldEditDialog,
    'study-tags': StudyTagsComponent,
    'study-department': StudyDepartment,
    'study-family': StudyFamily
  },
  data () {
    return {
      show: this.showData ? 0 : null,
      dataStudy: this.study
    }
  },
  props: {
    study: {
      type: Object,
      required: true
    },
    showData: {
      type: Boolean,
      default: true
    }
  },
  methods: {
    refresh () {
      this.$store.dispatch(actions.SET_STUDIES).catch(error => {
        this.$snackbar.showError(error)
      })
    },
    close () {
      this.$emit('close')
    }
  }
}
</script>

<style scoped>
    .v-list__tile__title {
        height: auto;
    }
</style>
