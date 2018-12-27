<template>
    <span>
        <v-toolbar color="secondary">
                <v-toolbar-title><span class="secondary--text text--lighten-5">{{ study.name}}</span></v-toolbar-title>
            </v-toolbar>

        <ul>
            <li>Enllaç al tríptic: </li>
            <li>Durada: 2 cursos acadèmics</li>
            <li>Horari:1r de 8h a 14,30h 2n de 15,00h a 20,30h</li>
            <li>Hores de pràctiques: 355</li>
            <li>Modalitat dual: Estada a empresa remunerada</li>
            <li>Informat a Secretaria o iesebre@iesebre.com</li>
        </ul>

        <v-layout row wrap >
        <v-flex xs12>
            <v-toolbar color="secondary">
                <v-toolbar-title><span class="secondary--text text--lighten-5">Pla docent</span></v-toolbar-title>
            </v-toolbar>
            <v-container grid-list-md text-xs-center fluid>
                <v-layout row wrap>
                    <v-flex xs3>
                        <v-card color="accent">
                            <v-card-text class="px-0"><span class="subheading accent--text text--lighten-5">Mòdul Professional</span></v-card-text>
                        </v-card>
                    </v-flex>
                    <v-flex xs9>
                        <v-card color="accent">
                            <v-card-text class="px-0"><span class="subheading accent--text text--lighten-5">Unitats Formativa</span></v-card-text>
                        </v-card>
                    </v-flex>
                    <template v-for="subjectGroup in study.subjectGroups">
                        <v-flex xs3 text-xs-left>
                            <v-card height="100%">
                                <v-card-text>{{ subjectGroup.code }} {{ subjectGroup.name }}</v-card-text>
                            </v-card>
                        </v-flex>
                        <v-flex xs9>
                            <v-card>
                                <v-card-text>
                                    {{ subjectGroup }}
                                    <v-list>
                                        <v-list-tile>UF1 TODO i mostrar hores</v-list-tile>
                                        <v-list-tile>PROVA</v-list-tile>
                                        <v-list-tile>PROVA</v-list-tile>
                                        <v-list-tile>PROVA</v-list-tile>
                                        <v-list-tile>PROVA</v-list-tile>
                                        <v-list-tile>PROVA</v-list-tile>
                                    </v-list>
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
export default {
  name: 'CurriculumStudyPublic',
  data () {
    return {
      todo: []
    }
  },
  props: {
    study: {
      type: Object,
      required: true
    }
  }
}
</script>
