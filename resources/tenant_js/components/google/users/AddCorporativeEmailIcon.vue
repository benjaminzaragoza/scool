<template>
    <span style="display:inline-block">
        <v-btn icon class="mx-0" title="Afegiu correu corporatiu" @click.native.stop="addGoogleUser">
            <v-icon color="primary">add</v-icon>
        </v-btn>
        <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition" @keydown.esc="dialog = false">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click.native="dialog = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Afegir correu corporatiu</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn dark flat @click.native="dialog = false">Sortir</v-btn>
                    </v-toolbar-items>
                </v-toolbar>

                <v-container grid-list-lg fluid>
                <v-layout row wrap>
                     <v-flex xs12>
                        {{ user }}
                    </v-flex>
                </v-layout>
                </v-container>
            </v-card>
        </v-dialog>

    </span>
</template>

<script>
import axios from 'axios'
import withSnackbar from '../../mixins/withSnackbar'

export default {
  name: 'AddCorporativeEmailIcon',
  mixins: [withSnackbar],
  data () {
    return {
      dialog: false
    }
  },
  props: {
    user: {
      type: Object,
      default: () => { return {} }
    }
  },
  methods: {
    addGoogleUser () {
      console.log('USER:')
      console.log(this.user)
      axios.post('/api/v1/gsuite/users/search', {
        employeeId: this.user.id,
        personalEmail: this.user.email,
        mobile: this.user.mobile
      }).then(response => {
        console.log('RESPONSE.DATA  11:')
        console.log(response.data)
        axios.post('/api/v1/user/' + this.user.id + '/gsuite', {
          google_id: response.data.id,
          google_email: response.data.primaryEmail
        }).then(response => {
          this.showMessage('Usuari Google assignat correctament')
          this.$emit('added', response.data)
        }).catch(error => {
          this.showError(error)
        })
      }).catch(error => {
        console.log(error)
      })
    }
  }
}
</script>
