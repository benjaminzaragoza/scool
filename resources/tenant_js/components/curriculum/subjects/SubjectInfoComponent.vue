<template>
  <v-card v-if="subject()">
    <v-card-text>
        <v-container fluid grid-list-md text-xs-center>
          <v-layout row wrap>
            <v-flex xs3>
              Codi : {{ subject().code }}
            </v-flex>
            <v-flex xs3>
             Número UF : {{ subject().number }}
            </v-flex>
            <v-flex xs3>
             Hores : {{ subject().hours }}
            </v-flex>
            <v-flex xs3>
             Hores setmanals : {{ subject().week_hours }}
            </v-flex>
            <v-flex xs3>
              Data d'inici: {{ subject().start_date }}
            </v-flex>
            <v-flex xs3>
              Data fí: {{ subject().end_date }}
            </v-flex>

          </v-layout>
        </v-container>
        {{ subject() }}
    </v-card-text>
  </v-card>
</template>

<script>
  export default {
    name: 'SubjectInfoComponent',
    props: {
      subjectId: {
        required: true
      },
      subjects: {
        type: Array,
        required: true
      }
    },
    methods: {
      subject () {
        return this.subjects.find(subject => subject.id === this.subjectId)
      }
    }
  }
</script>
