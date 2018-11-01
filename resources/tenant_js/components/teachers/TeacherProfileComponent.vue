<template>
    <v-card>
        <v-toolbar dark color="primary">
            <v-toolbar-title>Fitxa del professor</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn dark flat>Editar TODO</v-btn>
                <v-btn dark flat @click.native="dialog = false">Imprimir</v-btn>
                <v-btn dark flat @click.native="dialog = false">PDF</v-btn>
            </v-toolbar-items>
        </v-toolbar>

        <v-container grid-list-lg fluid>
            <v-layout row wrap>
                <v-flex xs9>
                    <personal-data-card :user="teacher"></personal-data-card>
                </v-flex>
                <v-flex xs3>
                    <v-card>
                        <v-container grid-list-xs>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-container grid-list-xs>
                                        <v-layout row wrap>
                                            <v-flex xs12 >
                                                <h1 class="title primary--text">
                                                    <div>
                                                        <p>Dades professionals</p>
                                                    </div>
                                                </h1>
                                            </v-flex>
                                            <v-flex xs12>
                                                <v-container grid-list-xs>
                                                    <v-layout row wrap>
                                                        <v-flex xs12>
                                                            <v-list two-line>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title v-html="teacher.teacher.code"></v-list-tile-title>
                                                                        <v-list-tile-sub-title>Codi</v-list-tile-sub-title>
                                                                    </v-list-tile-content>
                                                                </v-list-tile>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title v-html="specialty()"></v-list-tile-title>
                                                                        <v-list-tile-sub-title>Especialitat</v-list-tile-sub-title>
                                                                    </v-list-tile-content>
                                                                </v-list-tile>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title v-html="family()"></v-list-tile-title>
                                                                        <v-list-tile-sub-title>Familia</v-list-tile-sub-title>
                                                                    </v-list-tile-content>
                                                                </v-list-tile>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title v-html="force()"></v-list-tile-title>
                                                                        <v-list-tile-sub-title>Cos</v-list-tile-sub-title>
                                                                    </v-list-tile-content>
                                                                </v-list-tile>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title v-html="status()"></v-list-tile-title>
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
                        <v-container grid-list-xs>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-container grid-list-xs>
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
                                                <v-container grid-list-xs>
                                                    <v-layout row wrap>
                                                        <v-flex xs4>
                                                            <v-list two-line>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title>
                                                                            <span :title="jobDescription()">{{ job() }}</span>
                                                                        </v-list-tile-title>
                                                                        <v-list-tile-sub-title>Codi plaça</v-list-tile-sub-title>
                                                                    </v-list-tile-content>
                                                                </v-list-tile>
                                                                <v-divider></v-divider>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title v-html="jobFamily()"></v-list-tile-title>
                                                                        <v-list-tile-sub-title >Família</v-list-tile-sub-title>
                                                                    </v-list-tile-content>
                                                                </v-list-tile>
                                                                <v-divider></v-divider>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title v-html="jobSpecialty()"></v-list-tile-title>
                                                                        <v-list-tile-sub-title>Especialitat</v-list-tile-sub-title>
                                                                    </v-list-tile-content>
                                                                </v-list-tile>
                                                                <v-divider></v-divider>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title v-html="jobOrder()"></v-list-tile-title>
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
                                                                        <v-list-tile-title v-html="teacher.teacher.titulacio_acces"></v-list-tile-title>
                                                                        <v-list-tile-sub-title>Titulació d'accés</v-list-tile-sub-title>
                                                                    </v-list-tile-content>
                                                                </v-list-tile>
                                                                <v-divider></v-divider>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title v-html="teacher.teacher.altres_titulacions"></v-list-tile-title>
                                                                        <v-list-tile-sub-title >Altres titulacions</v-list-tile-sub-title>
                                                                    </v-list-tile-content>
                                                                </v-list-tile>
                                                                <v-divider></v-divider>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title v-html="teacher.teacher.idiomes"></v-list-tile-title>
                                                                        <v-list-tile-sub-title>Idiomes</v-list-tile-sub-title>
                                                                    </v-list-tile-content>
                                                                </v-list-tile>
                                                                <v-divider></v-divider>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title v-html="teacher.teacher.perfil_professional"></v-list-tile-title>
                                                                        <v-list-tile-sub-title>Pèrfils professionals</v-list-tile-sub-title>
                                                                    </v-list-tile-content>
                                                                </v-list-tile>
                                                                <v-divider></v-divider>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title v-html="teacher.teacher.altres_formacions"></v-list-tile-title>
                                                                        <v-list-tile-sub-title>Altres formacions</v-list-tile-sub-title>
                                                                    </v-list-tile-content>
                                                                </v-list-tile>
                                                            </v-list>
                                                        </v-flex>
                                                        <v-flex xs4>
                                                            <v-list two-line>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title v-html="teacher.teacher.data_superacio_oposicions"></v-list-tile-title>
                                                                        <v-list-tile-sub-title>Data Superació oposicions</v-list-tile-sub-title>
                                                                    </v-list-tile-content>
                                                                </v-list-tile>
                                                                <v-divider></v-divider>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title v-html="teacher.teacher.lloc_destinacio_definitiva"></v-list-tile-title>
                                                                        <v-list-tile-sub-title>Lloc destinació definitiva</v-list-tile-sub-title>
                                                                    </v-list-tile-content>
                                                                </v-list-tile>
                                                                <v-divider></v-divider>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title v-html="teacher.teacher.data_inici_treball"></v-list-tile-title>
                                                                        <v-list-tile-sub-title>Any inici serveis ensenyament</v-list-tile-sub-title>
                                                                    </v-list-tile-content>
                                                                </v-list-tile>
                                                                <v-divider></v-divider>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title v-html="teacher.person && teacher.person.notes">---</v-list-tile-title>
                                                                        <v-list-tile-sub-title>Notes</v-list-tile-sub-title>
                                                                    </v-list-tile-content>
                                                                </v-list-tile>
                                                                <v-divider></v-divider>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title v-html="teacher.teacher.data_incorporacio_centre"></v-list-tile-title>
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
</template>

<style>

</style>

<script>
import PersonalDataCard from '../people/PersonalDataCardComponent'

export default {
  components: {
    'personal-data-card': PersonalDataCard
  },
  props: {
    teacher: {
      type: Object,
      required: true
    },
    teachers: {
      type: Array,
      required: true
    }
  },
  methods: {
    specialty () {
      if (this.teacher && this.teacher.teacher && this.teacher.teacher.specialty) {
        return this.teacher.teacher.specialty.code + ' ' + this.teacher.teacher.specialty.name
      }
    },
    family () {
      if (this.teacher && this.teacher.teacher && this.teacher.teacher.specialty && this.teacher.teacher.specialty.family) {
        return this.teacher.teacher.specialty.family.code + ' ' + this.teacher.teacher.specialty.family.name
      }
    },
    force () {
      if (this.teacher && this.teacher.teacher && this.teacher.teacher.specialty && this.teacher.teacher.specialty.force) {
        return this.teacher.teacher.specialty.force.name
      }
    },
    status () {
      if (this.teacher && this.teacher.teacher && this.teacher.teacher.administrative_status) {
        return this.teacher.teacher.administrative_status.name
      }
    },
    job () {
      if (this.teacher.jobs[0]) {
        return this.teacher.jobs[0].family.code + '_' + this.teacher.jobs[0].specialty.code + '_' + this.teacher.jobs[0].order + '_' + this.teacher.jobs[0].code
      }
    },
    jobDescription () {
      if (this.teacher.jobs[0]) {
        return 'Plaça num ' + this.teacher.jobs[0].order + ' de la família ' + this.teacher.jobs[0].family.name + ', especialitat ' + this.teacher.jobs[0].specialty.name + ', assignada al professor ' + this.teacherDescription(this.teacher.jobs[0].code)
      }
      return ''
    },
    jobFamily () {
      if (this.teacher.jobs[0]) {
        return this.teacher.jobs[0].family.name
      }
      return ''
    },
    jobSpecialty () {
      if (this.teacher.jobs[0]) {
        return this.teacher.jobs[0].specialty.name
      }
      return ''
    },
    jobOrder () {
      if (this.teacher.jobs[0]) {
        return this.teacher.jobs[0].order
      }
      return ''
    },
    teacherDescription (teacherCode) {
      let teacher = this.teachers.find(teacher => { return teacher.code === teacherCode })
      return teacher.name + ' (' + teacher.code + ')'
    }
  }
}
</script>
