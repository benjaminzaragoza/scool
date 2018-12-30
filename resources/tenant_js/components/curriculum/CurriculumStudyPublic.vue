<template>
    <span>
        <span class="title secondary--text text--lighten-2 pt-5">{{ study.name}}</span>
        <v-divider></v-divider>

        <ul class="mt-3">
            <li>Codi: {{ study.code}}</li>
            <li>Programació: TODO</li>
            <li>Enllaç al tríptic: </li>
            <li>Durada: 2 cursos acadèmics</li>
            <li>Horari:1r de 8h a 14,30h 2n de 15,00h a 20,30h</li>
            <li>Hores de pràctiques: 355</li>
            <li>Modalitat dual: Estada a empresa remunerada</li>
            <li>Informat a Secretaria o iesebre@iesebre.com</li>
            <li>Llei: LOE/LOGSE -> TODO</li>
            <li>Etiquetes: CFGM/CFGS/Altres</li>
            <li>Total hores: 2000</li>
        </ul>

        <v-layout row wrap mt-3>
        <v-flex xs12>
            <span class="title secondary--text text--lighten-2">Pla docent</span>
            <v-divider></v-divider>
            <v-container grid-list-md text-xs-center fluid>
                <v-layout row wrap>
                    <v-flex xs4>
                        <v-card color="accent">
                            <v-card-text class="pa-2">
                                <span class="subheading accent--text text--lighten-5">Mòduls Professionals
                                    <template v-if="$hasRole('CurriculumManager')">
                                         <v-btn icon small href="/curriculum/subjectGroups" target="_blank" title="Modificar els Mòduls Professionals" color="success">
                                            <v-icon small>edit</v-icon>
                                        </v-btn>
                                    </template>
                                </span>
                            </v-card-text>
                        </v-card>
                    </v-flex>
                    <v-flex xs7>
                        <v-card color="accent">
                            <v-card-text class="pa-2">
                                <span class="subheading accent--text text--lighten-5">Unitats Formatives
                                    <template v-if="$hasRole('CurriculumManager')">
                                         <v-btn icon small href="/curriculum/subjects" target="_blank" title="Modificar les Unitats Formatives" color="success">
                                            <v-icon small>edit</v-icon>
                                        </v-btn>
                                    </template>
                                </span>
                            </v-card-text>
                        </v-card>
                    </v-flex>
                    <v-flex xs1>
                        <v-card color="accent" class="fill-height">
                            <v-card-text class="pa-2 "><span class="subheading accent--text text--lighten-5">Hores</span></v-card-text>
                        </v-card>
                    </v-flex>
                    <template v-for="(subjectGroup,index) in study.subjectGroups">
                        <v-flex xs4 text-xs-left :key="'subject_group' + subjectGroup.id">
                            <v-card height="100%">
                                <v-card-text>
                                    <strong>{{ subjectGroup.code }}</strong>
                                    <inline-text-field-edit-dialog v-if="$hasRole('CurriculumManager')"
                                            style="display:inline-block;width: auto;"
                                            v-model="study.subjectGroups[index]"
                                            field="name"
                                            label="Nom"
                                            @save="showMessage('Nom actualitzat correctament')"
                                    >
                                    </inline-text-field-edit-dialog>
                                    <span v-else>{{ subjectGroup.name }}</span>
                                    <br v-if="subjectGroup.subjects && subjectGroup.subjects.length > 1"/>
                                    Hores: {{ subjectGroup.hours }}
                                    <template v-if="$hasRole('CurriculumManager')">
                                         <v-btn icon flat small href="/curriculum" target="_blank" v-if="totalSubjectsHours(subjectGroup) !== subjectGroup.hours" :title="'No coincideix el nombre d\'hores -> MP: ' + subjectGroup.hours + ' Total UFS: ' + totalSubjectsHours(subjectGroup)" color="red">
                                            <v-icon>notification_important</v-icon>
                                        </v-btn>
                                    </template>
                                </v-card-text>
                            </v-card>
                        </v-flex>
                        <v-flex xs7 text-xs-left :key="'subject_group_subjects_' + subjectGroup.id">
                            <v-card v-for="subject in subjectGroup.subjects" :key="subject.id" class="mb-1" :class="{ 'fill-height': subjectGroup.subjects && subjectGroup.subjects.length === 1 }">
                                <v-card-text class="pa-2">
                                    <strong>{{ subject.code }}</strong> {{ subject.name }}
                                </v-card-text>
                            </v-card>
                        </v-flex>
                        <v-flex xs1 :key="'subject_group_subject_hours' + subjectGroup.id">
                            <v-card v-for="subject in subjectGroup.subjects" :key="subject.id" class="mb-1" :class="{ 'fill-height': subjectGroup.subjects && subjectGroup.subjects.length === 1 }">
                                <v-card-text class="pa-2">
                                    {{ subject.hours }}
                                </v-card-text>
                            </v-card>
                        </v-flex>
                    </template>
                </v-layout>
            </v-container>
        </v-flex>
    </v-layout>
    </span>
</template>
<script>
import InlineTextFieldEditDialog from '../ui/InlineTextFieldEditDialog'

export default {
  name: 'CurriculumStudyPublic',
  components: {
    'inline-text-field-edit-dialog': InlineTextFieldEditDialog
  },
  props: {
    study: {
      type: Object,
      required: true
    }
  },
  methods: {
    totalSubjectsHours (subjectGroup) {
      if (subjectGroup.subjects) {
        return subjectGroup.subjects.map(subjectGroup => subjectGroup['hours']).reduce((a, b) => a + b)
      }
      return 0
    },
    showMessage (message) {
      this.$snackbar.showMessage(message)
    }
  }
}
</script>
