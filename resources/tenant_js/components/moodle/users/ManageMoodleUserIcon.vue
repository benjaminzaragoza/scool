<template>
    <span>
        <v-tooltip bottom>
            <v-btn slot="activator" small icon class="mx-0 pa-0" @click.native.stop="dialog=true">
                <v-icon small color="primary">add</v-icon>
            </v-btn>
            <span>Afegiu usuari de Moodle</span>
        </v-tooltip>
        <v-dialog v-if="dialog" v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition" @keydown.esc="dialog = false">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click.native="dialog = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Compte de Moodle</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn dark flat @click.native="dialog = false">
                            <v-icon class="mr-2">exit_to_app</v-icon> Sortir</v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-container grid-list-lg fluid>
                <v-layout row wrap>
                     <v-flex xs12>
                         <v-card class="elevation-3">
                             <v-toolbar class="headline font-weight-regular primary white--text" dense>
                                <v-toolbar-title class="font-weight-regular white--text">Associar compte de Moodle</v-toolbar-title>
                                <v-spacer></v-spacer>
                                <v-toolbar-items>
                                    <v-btn icon class="white--text" @click="refresh" :loading="refreshing" :disabled="refreshing">
                                        <v-icon>refresh</v-icon>
                                    </v-btn>
                                </v-toolbar-items>
                            </v-toolbar>
                            <v-card-text>
                                <v-subheader class="pa-0">Escolliu un compte de Moodle que vulgueu associar a l'usuari {{ user.name }} ({{ user.email }})</v-subheader>
                                <moodle-users-select :user="user" v-if="dialog" ref="select" @selected="select"></moodle-users-select>
                                <p class="mt-3">
                                    <v-icon>add</v-icon><a href="https://iesebre.scool.test/moodle/users?action=create" target="_blank">Crear un nou compte de Moodle</a>.
                                        Un cop creat el nou compte, torneu a aquesta pàgina i actualitzeu prement el boto:
                                    <v-btn icon @click="refresh" :loading="refreshing" :disabled="refreshing">
                                        <v-icon>refresh</v-icon>
                                    </v-btn>
                                </p>
                            </v-card-text>
                            <v-card-actions>
                                <v-btn flat @click="dialog = false">
                                    <v-icon class="mr-2">exit_to_app</v-icon> Sortir
                                </v-btn>
                                <v-btn color="primary" @click="associate" :disabled="associating || !selectedMoodleuser" :loading="associating">Associar</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-layout>
                </v-container>
            </v-card>
        </v-dialog>
    </span>
</template>
<script>
import MoodleUsersSelectComponent from './MoodleUsersSelectComponent'
export default {
  name: 'ManageMoodleUserIcon',
  components: {
    'moodle-users-select': MoodleUsersSelectComponent
  },
  data () {
    return {
      dialog: false,
      associating: false,
      refreshing: false,
      selectedMoodleuser: null
    }
  },
  props: {
    user: {
      type: Object,
      required: true
    }
  },
  methods: {
    refresh () {
      this.$refs.select.refresh()
    },
    associate () {
      this.associating = true
      window.axios.post('/api/v1/user/' + this.user.id + '/moodle', {
        google_id: this.selectedMoodleuser.id,
        google_email: this.selectedMoodleuser.primaryEmail
      }).then(response => {
        this.$snackbar.showMessage('Usuari Moodle associat correctament')
        this.$emit('associated', response.data)
        this.dialog = false
        this.associating = false
      }).catch(error => {
        if (error.status === 422) this.$snackbar.showError('Error de validació, possiblement el compte de Moodle que voleu assignar ja està assignat a un altre usuari.')
        else this.$snackbar.showError(error)
        this.associating = false
      })
    }
  }
}
</script>
