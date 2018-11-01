<template>
    <span style="display:inline-block">
        <v-btn v-if="icon" icon class="mx-0" title="Vegeu la fitxa del professor" @click.native.stop="prepareDialog">
            <v-icon color="primary">visibility</v-icon>
        </v-btn>
        <v-btn v-else class="mx-0" title="Vegeu la fitxa del professor" @click.native.stop="prepareDialog">
            <v-icon color="primary">visibility</v-icon> Fitxa del professor
        </v-btn>
        <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition" @keydown.esc="dialog = false">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click.native="dialog = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Fitxa del professor</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn dark flat @click.native="dialog = false">Imprimir</v-btn>
                    </v-toolbar-items>
                </v-toolbar>

                <v-container grid-list-lg fluid>
                    <v-layout row wrap>
                         <v-flex xs9>
                            <personal-data-card :user="internalTeacher"></personal-data-card>
                        </v-flex>
                        <v-flex xs3>
                            <v-card>
                                <v-container grid-list-xs fluid>
                                    <v-layout row wrap>
                                        <v-flex xs12>
                                            <v-container grid-list-xs fluid>
                                                <v-layout row wrap>
                                                    <v-flex xs12 >
                                                        <h1 class="title primary--text">
                                                            <div>
                                                                <p>Dades professionals</p>
                                                            </div>

                                                        </h1>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <v-container grid-list-xs fluid>
                                                            <v-layout row wrap>
                                                                <v-flex xs12>
                                                                    <v-list two-line>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher.code"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Codi</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher.specialty_code + ' ' + internalTeacher.specialty">

                                                                                </v-list-tile-title>
                                                                                <v-list-tile-sub-title>Especialitat</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher.family_code + ' ' + internalTeacher.family"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Familia</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher.force"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Cos</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title :title="internalTeacher.administrative_status_code" v-html="internalTeacher.administrative_status"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Estat administratiu</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>

                                                                    </v-list>
                                                                </v-flex>
                                                            </v-layout>
                                                        </v-container>
                                                    </v-flex>
                                                </v-layout>
                                            </v-container>
                                        </v-flex>
                                    </v-layout>
                                </v-container>

                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-container>
                <v-container grid-list-lg fluid>
                    <v-layout row wrap >
                        <v-flex xs12>
                            <v-card>
                                <v-container grid-list-xs fluid>
                                    <v-layout row wrap>
                                        <v-flex xs12>
                                            <v-container grid-list-xs fluid>
                                                <v-layout row wrap>
                                                    <v-flex xs4 >
                                                        <h1 class="title primary--text">
                                                            <div>
                                                                <p>Plaça i càrrecs</p>
                                                            </div>
                                                        </h1>
                                                    </v-flex>
                                                    <v-flex xs4 >
                                                        <h1 class="title primary--text">
                                                            <div>
                                                                <p>Formació</p>
                                                            </div>
                                                        </h1>
                                                    </v-flex>
                                                    <v-flex xs4 >
                                                        <h1 class="title primary--text">
                                                            <div>
                                                                <p>Altres dades</p>
                                                            </div>
                                                        </h1>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <v-container grid-list-xs fluid>
                                                            <v-layout row wrap>
                                                                <v-flex xs4>
                                                                    <v-list two-line>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title>
                                                                                    <span :title="internalTeacher.job_description" v-html="internalTeacher.job"></span>
                                                                                </v-list-tile-title>
                                                                                <v-list-tile-sub-title>Codi plaça</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-divider></v-divider>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher.job_family"></v-list-tile-title>
                                                                                <v-list-tile-sub-title >Família</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-divider></v-divider>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher.job_specialty_code + ' ' + internalTeacher.job_specialty"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Especialitat</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-divider></v-divider>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher.job_order"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Ordre</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-divider></v-divider>
                                                                        <!--TODO-->
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title>TODO</v-list-tile-title>
                                                                                <v-list-tile-sub-title>Càrrecs</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                    </v-list>
                                                                </v-flex>
                                                                <v-flex xs4>
                                                                    <v-list two-line>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher.titulacio_acces"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Titulació d'accés</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-divider></v-divider>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher.altres_titulacions"></v-list-tile-title>
                                                                                <v-list-tile-sub-title >Altres titulacions</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-divider></v-divider>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher.idiomes"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Idiomes</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-divider></v-divider>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher.perfil_professional"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Pèrfils professionals</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-divider></v-divider>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher.altres_formacions"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Altres formacions</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                    </v-list>
                                                                </v-flex>
                                                                <v-flex xs4>
                                                                    <v-list two-line>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher.data_superacio_oposicions"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Data Superació oposicions</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-divider></v-divider>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher.lloc_destinacio_definitiva"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Lloc destinació definitiva</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-divider></v-divider>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher.data_inici_treball"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Any inici serveis ensenyament</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-divider></v-divider>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher.person_notes"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Notes</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-divider></v-divider>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher.data_incorporacio_centre"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Data incorporació centre</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                    </v-list>
                                                                </v-flex>
                                                            </v-layout>
                                                        </v-container>
                                                    </v-flex>
                                                </v-layout>
                                            </v-container>
                                        </v-flex>
                                    </v-layout>
                                </v-container>

                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card>
        </v-dialog>
    </span>
</template>

<style>

</style>

<script>
import axios from 'axios'
import PersonalDataCard from '../people/PersonalDataCardComponent'

export default {
  components: {
    'personal-data-card': PersonalDataCard
  },
  data () {
    return {
      dialog: false,
      internalTeacher: this.teacher
    }
  },
  props: {
    teacher: {
      type: Object,
      default: () => { return {} }
    },
    teachers: {
      type: Array,
      default: () => []
    },
    icon: {
      type: Boolean,
      default: true
    }
  },
  watch: {
    teacher: function (newTeacher) {
      this.internalTeacher = this.teacher
    }
  },
  methods: {
    prepareDialog () {
      if (!_.isEmpty(this.internalTeacher) && this.teachers.length > 0) {
        this.dialog = true
        return
      }
      axios.get('/api/v1/teacher').then(response => {
        this.internalTeacher = response.data
        this.dialog = true
      }).catch(error => {
        console.log(error)
      })
    }
  }
}
</script>
