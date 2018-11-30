<template>
    <span>
        <v-switch
                :label="existingUser ? 'Crear utilitzant un usuari local existent' : 'Crear nou usuari desde zero'"
                v-model="existingUser"
        ></v-switch>
        <form v-if="existingUser">
            <v-container fluid grid-list-md text-xs-center>
                <v-layout row wrap>
                    <v-flex xs12>
                        <user-select
                                label="Usuari a afegir"
                                :users="localUsers"
                                v-model="user"
                                :item-value="null"
                        ></user-select>
                        <template v-if="checking">
                            <v-progress-linear  :indeterminate="true"></v-progress-linear>
                            Comprovant l'usuari seleccionat...
                        </template>
                    </v-flex>
                    <v-flex xs12>
                        <template v-if="checkResult !== null">
                            <v-alert v-if="checkResult=== true" :value="true" type="success">S'ha comprovat que l'usuari seleccionat no té cap usuari a Moodle. Podeu procedir a crear el compte</v-alert>
                            <template v-if="checkResult=== false">
                                <v-alert v-for="message in checkResultErrorMessage" :value="true" type="error" :key="JSON.stringify(message)">L'usuari seleccionat té possibles problemes alhora de crear un usuari a Moodle: {{ message }}</v-alert>
                            </template>
                            <v-list two-line>
                              <template v-for="errorUser in checkResultErrorUsers">
                                <v-list-tile
                                        :key="errorUser.id"
                                        avatar
                                        :href="'https://www.iesebre.com/moodle/user/profile.php?id=' + errorUser.id"
                                        target="_blank"
                                >
                                  <v-list-tile-avatar>
                                    <img :src="errorUser.profileimageurlsmall">
                                  </v-list-tile-avatar>
                                  <v-list-tile-content>
                                    <v-list-tile-title>{{ errorUser.fullname }} - (uidnumber: {{ errorUser.idnumber }})</v-list-tile-title>
                                    <v-list-tile-sub-title v-text="errorUser.email"></v-list-tile-sub-title>
                                  </v-list-tile-content>
                                </v-list-tile>
                              </template>
                            </v-list>
                        </template>
                    </v-flex>
                    <v-flex xs12>
                        <template v-if="checkResult !== null">

                            <v-dialog v-model="showUsernameFieldHelpDialog" width="700">
                              <v-btn  slot="activator" color="red lighten-2" dark>Click Me</v-btn>
                               <v-card>
                                <v-card-title
                                        class="headline grey lighten-2"
                                        primary-title
                                >
                                   Com utilitzar l'email com a nom d'usuari a Moodle...
                                </v-card-title>
                                <v-card-text>
                                  Per defecte Moodle no suporta l'arroba (@) i altres caràcters especials als noms d'usuari. Cal que activeu a:<br/><br/>
                                    <pre>Inici / ► Administració del lloc / ► Seguretat / ► Normatives del lloc</pre><br/>
                                  la opció <strong>Permet caràcters estesos en els noms d'usuari</strong>.
                                </v-card-text>

                                <v-divider></v-divider>

                                <v-card-actions>
                                  <v-spacer></v-spacer>
                                  <v-btn
                                          color="primary"
                                          flat
                                          @click="showUsernameFieldHelpDialog = false"
                                  >
                                    Acceptar
                                  </v-btn>
                                </v-card-actions>
                              </v-card>
                            </v-dialog>

                                                                <!--@click:prepend="this.showUsernameFieldHelpDialog = true"-->


                            <v-text-field
                                    prepend-icon="help"
                                    @click:prepend="activeUsernameFieldHelpDialog"
                                    readonly
                                    v-model="username"
                                    name="username"
                                    label="Nom d'usuari"
                            ></v-text-field>
                            <v-text-field
                                    readonly
                                    v-model="name"
                                    name="name"
                                    label="Nom"
                            ></v-text-field>
                            <v-text-field
                                    readonly
                                    v-model="lastname"
                                    name="lastname"
                                    label="Cognoms"
                            ></v-text-field>
                            <v-text-field
                                    readonly
                                    v-model="email"
                                    name="email"
                                    label="Correu electrònic"
                            ></v-text-field>
                            <v-text-field
                                    readonly
                                    v-model="idnumber"
                                    name="idnumber"
                                    label="Identificador local(idnumber)"
                            ></v-text-field>
                            <v-checkbox
                                    readonly
                                    label="Crear password de Moodle i enviar per correu electrònic"
                                    v-model="createpassword"
                            ></v-checkbox>
                        </template>
                    </v-flex>
                </v-layout>
            </v-container>
            <v-btn @click="confirmAdd"
                       id="add_moodle_user_button"
                       color="primary"
                       class="white--text"
                       :loading="adding"
                       :disabled="adding">Afegir</v-btn>
            <v-btn @click="close()"
                   id="close_button"
                   color="error"
                   class="white--text"
            >Tancar</v-btn>
        </form>
        <form v-else>
            <v-container fluid grid-list-md text-xs-center>
                <v-layout row wrap>
                    <!--TODO-->
                    <v-flex xs12>
                        <v-text-field
                                ref="subject_field"
                                v-focus
                                v-model="subject"
                                name="subject"
                                label="Títol"
                                :error-messages="subjectErrors"
                                @input="$v.subject.$touch()"
                                @blur="$v.subject.$touch()"
                                hint="El títol ha d'incloure la informació bàsica per entendre el problema o incidència que teniu"
                                autofocus
                        ></v-text-field>
                    </v-flex>
                    <v-flex xs12>
                        TODO
                    </v-flex>
                </v-layout>
            </v-container>
            <template v-if="$hasRole('IncidentsManager')">
                <v-btn @click="add(false)"
                       id="add_incident_button"
                       color="teal"
                       class="white--text"
                       :loading="adding"
                       :disabled="adding"
                >Afegir</v-btn>
                <v-btn @click="add(true)"
                       id="add_and_close_incident_button"
                       color="primary"
                       class="white--text"
                       :loading="adding"
                       :disabled="adding"
                >Afegir i tancar</v-btn>
            </template>
            <template v-else>
                <v-btn @click="add(true)"
                       id="add_and_close_incident_button"
                       color="primary"
                       class="white--text"
                       :loading="adding"
                       :disabled="adding"
                >Afegir</v-btn>
            </template>
            <v-btn @click="close()"
                   id="close_button"
                   color="error"
                   class="white--text"
            >Tancar</v-btn>
        </form>
    </span>
</template>

<script>

// https://www.iesebre.com/moodle/user/editadvanced.php?id=-1
import UserSelect from '../../users/UsersSelectComponent'

export default {
  name: 'MoodleUserAdd',
  components: {
    'user-select': UserSelect
  },
  data () {
    return {
      showUsernameFieldHelpDialog: false,
      existingUser: true,
      subject: '',
      description: '',
      adding: false,
      user: null,
      checking: false,
      checkResult: null,
      checkResultErrorMessage: [],
      checkResultErrorUsers: [],
      username: '',
      name: '',
      lastname: '',
      email: '',
      idnumber: '',
      createpassword: true
    }
  },
  props: {
    localUsers: {
      type: Array,
      required: true
    }
  },
  watch: {
    user (newuser) {
      if (newuser) {
        this.checkResult = null
        this.checkResultErrorUsers = []
        this.checkMoodleUser(newuser)
        this.initializeMoodleUserFields(newuser)
      } else {
        this.checkResult = null
        this.checkResultErrorUsers = []
      }
    }
  },
  methods: {
    activeUsernameFieldHelpDialog () {
      this.showUsernameFieldHelpDialog = true
    },
    initializeMoodleUserFields (user) {
      this.name = user.givenName
      this.lastname = user.lastname
      this.username = user.email
      this.email = user.email
      this.idnumber = user.id
    },
    async confirmAdd () {
      if (!this.checkResult) {
        let res = await this.$confirm("L'usuari sembla que ja existeix a Moodle i li duplicarieu les dades. Segur que voleu afegir l'usuari?", { title: 'Esteu segurs?', buttonTrueText: 'Afegir' })
        if (res) {
          this.add()
        }
      } else {
        this.add()
      }
    },
    // lastName (user) {
    //   if (user.sn1) {
    //     let sn2 = user.sn2 ? ' ' + user.sn2 : ''
    //     return user.sn1 + sn2
    //   } else {
    //     return user.name.split(' ').shift().join(' ')
    //   }
    // },
    // givenName (user) {
    //   if (user.givenName) return user.givenName
    //   return user.name.split(' ')[0]
    // },
    add () {
      this.adding = true
      console.log('username:')
      console.log(this.username(this.user))
      let formParams = {
        'username': this.username,
        'firstname': this.name,
        'lastname': this.lastname,
        'email': this.email,
        'createpassword': true,
        'idnumber': this.idnumber
      }
      console.log(formParams)
      // window.axios.post('/api/v1/moodle/users', {
      //   user: {
      //     'username': this.username(this.user),
      //     'firstname': this.givenName(this.user),
      //     'lastname': this.last_name(this.user),
      //     'email': this.user.email,
      //     'createpassword': true,
      //     'idnumber': this.user.id
      //   }
      // }).then((response) => {
      //   this.adding = false
      //   this.$snackbar.showMessage('Usuari afegit correctament a Moodle')
      // }).catch((error) => {
      //   this.$snackbar.showError(error)
      //   this.adding = false
      // })
    },
    close () {
      this.$emit('close')
    },
    checkMoodleUser (user) {
      this.checking = true
      window.axios.post('/api/v1/moodle/users/check', {
        'user': user
      }).then((response) => {
        if (response.data.status === 'Error') {
          this.checkResult = false
          this.checkResultErrorMessage = response.data.message
          this.checkResultErrorUsers = response.data.users
        }
        if (response.data.status === 'Success') this.checkResult = true
        this.checking = false
      }).catch(error => {
        this.$snackbar.showError(error)
        this.checking = false
      })
    }
  }
}
</script>
