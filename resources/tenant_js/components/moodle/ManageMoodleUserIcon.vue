<template>
    <span>
        <v-btn small icon class="mx-0 pa-0" title="Afegiu usuari de Moodle" @click.native.stop="dialog=true">
            <v-icon small color="primary">add</v-icon>
        </v-btn>
        <v-dialog v-if="dialog" v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition" @keydown.esc="dialog = false">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click.native="dialog = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Afegir rol</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn dark flat @click.native="dialog = false">Sortir</v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-container grid-list-lg fluid>
                <v-layout row wrap>
                     <v-flex xs12>
                         TODO
                         <!--<v-card class="elevation-3">-->
                             <!--<v-toolbar class="headline font-weight-regular primary white&#45;&#45;text" dense>-->
                                <!--<v-toolbar-title class="font-weight-regular white&#45;&#45;text">Associar compte de Google</v-toolbar-title>-->
                                <!--<v-spacer></v-spacer>-->
                                <!--<v-toolbar-items>-->
                                    <!--<v-btn icon class="white&#45;&#45;text" @click="refresh" :loading="refreshing" :disabled="refreshing">-->
                                        <!--<v-icon>refresh</v-icon>-->
                                    <!--</v-btn>-->
                                <!--</v-toolbar-items>-->
                            <!--</v-toolbar>-->
                            <!--<v-card-text>-->
                                <!--<v-subheader class="pa-0">Escolliu un compte de Google que vulgueu associar a l'usuari {{ user.name }} ({{ user.email }})</v-subheader>-->
                                <!--<google-users-select :user="user" v-if="dialog" ref="select" @selected="select"></google-users-select>-->
                                <!--<p class="mt-3">-->
                                    <!--<v-icon>add</v-icon><a href="https://iesebre.scool.test/google_users?action=create" target="_blank">Crear un nou compte de Google</a>.-->
                                        <!--Un cop creat el nou compte, torneu a aquesta pàgina i actualitzeu prement el boto:-->
                                    <!--<v-btn icon @click="refresh" :loading="refreshing" :disabled="refreshing">-->
                                        <!--<v-icon>refresh</v-icon>-->
                                    <!--</v-btn>-->
                                <!--</p>-->
                            <!--</v-card-text>-->
                            <!--<v-card-actions>-->
                                <!--<v-btn color="primary" @click="associate" :disabled="associating || !selectedGoogleuser" :loading="associating">Associar</v-btn>-->
                                <!--<v-btn flat @click="dialog = false">Sortir</v-btn>-->
                            <!--</v-card-actions>-->
                        <!--</v-card>-->
                    </v-flex>
                </v-layout>
                </v-container>
            </v-card>
        </v-dialog>
    </span>
</template>
<script>
export default {
  name: 'ManageMoodleUserIcon',
  data () {
    return {
      dialog: false
    }
  },
  methods: {
    addMoodleUser () {
      console.log('TODO')
    }
  },
  props: {
    user: {
      type: Object,
      required: true
    }
  }
}
</script>
