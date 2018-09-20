<template>
    <span style="display:inline-block">
        <v-btn icon class="mx-0" title="Afegiu correu corporatiu" @click.native.stop="prepareDialog">
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
  export default {
    name: 'AddCorporativeEmailIcon',
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
      prepareDialog () {
        console.log('USER:')
        console.log(this.user)

        // TODO:
        // - SearchGoogleUser:
        // - Hi ha algun usuari a Google amb employeeId = user.id ?
        // - Hi ha algun usuari a Google amb secondaryEmail = user.email
        // - Hi ha algun usuari a Google amb mobile = user.mobile
        // - Hi ha algun usuari a Google amb givenName i familyName igual al del usuari?

        // this.dialog = true
        // TODO
        // if (! _.isEmpty(this.internalUser) && this.users.length > 0) {
        //   this.dialog = true
        //   return
        // }
        // axios.get('/api/v1/user').then(response => {
        //   this.internalUser = response.data
        //   this.dialog = true
        // }).catch(error => {
        //   console.log(error)
        // })
      }
    }
  }
</script>
