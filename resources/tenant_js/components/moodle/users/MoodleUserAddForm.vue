<template>
    <form>
        <v-container fluid grid-list-md text-xs-center>
            <v-layout row wrap>
                <v-flex xs12 v-if="this.existingUser">
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
                    <template v-if="checkResult !== null || this.existingUser === false">
                        <v-dialog v-model="showUsernameFieldHelpDialog" width="700">
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
                        <v-text-field
                                prepend-icon="help"
                                @click:prepend="activeUsernameFieldHelpDialog"
                                readonly
                                v-model="username"
                                name="username"
                                label="Nom d'usuari"
                        ></v-text-field>
                        <v-text-field v-if="user && user.givenName"
                                      readonly
                                      v-model="name"
                                      name="name"
                                      label="Nom"
                        ></v-text-field>
                        <v-text-field v-else
                                      v-model="name"
                                      name="name"
                                      label="Nom"
                        ></v-text-field>
                        <v-text-field v-if="user && user.lastname"
                                      readonly
                                      v-model="lastname"
                                      name="lastname"
                                      label="Cognoms"
                        ></v-text-field>
                        <template v-else>
                            <v-text-field
                                    v-model="sn1"
                                    name="sn1"
                                    label="1r cognom"
                            ></v-text-field>
                            <v-text-field
                                    v-model="sn2"
                                    name="sn2"
                                    label="2n cognom"
                            ></v-text-field>
                        </template>
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
                                label="Crear password de Moodle i enviar per correu electrònic"
                                v-model="createpassword"
                        ></v-checkbox>
                        <v-text-field v-if="!createpassword"
                                      v-model="password"
                                      type="password"
                                      name="password"
                                      label="Paraula de pas"
                        ></v-text-field>
                    </template>
                </v-flex>
            </v-layout>
        </v-container>
        <v-btn @click="confirmAdd"
               id="add_moodle_user_button"
               color="primary"
               class="white--text"
               :loading="adding"
               :disabled="adding || invalid">Afegir</v-btn>
        <v-btn @click="close()"
               id="close_button"
               color="error"
               class="white--text"
        >Tancar</v-btn>
    </form>
</template>

<script>
// TODO: Link a mateix formulari en Moodle: // https://www.iesebre.com/moodle/user/editadvanced.php?id=-1

import UserSelect from '../../users/UsersSelectComponent'
import MoodleUserAddForm from './MoodleUserAddForm'
export default {
  name: 'MoodleUserAddForm',
  components: {
    'user-select': UserSelect,
    'moodle-user-add-form': MoodleUserAddForm
  },
  data () {
    return {
      showUsernameFieldHelpDialog: false,
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
      sn1: '',
      sn2: '',
      email: '',
      idnumber: '',
      createpassword: true,
      password: ''
    }
  },
  computed: {
    invalid () {
      if (!this.username) return true
      if (!this.email) return true
      if (!this.name) return true
      if (!this.lastname) return true
      if (!this.idnumber) return true
      if (this.createpassword === false && !this.password) return true
      return false
    }
  },
  props: {
    localUsers: {
      type: Array,
      required: true
    },
    existingUser: {
      type: Boolean,
      default: false
    }
  },
  watch: {
    existingUser (existingUser) {
      if (!existingUser) this.reset()
    },
    sn1 (newSn1) {
      this.lastname = ''
      if (newSn1) this.lastname = newSn1.trim()
      if (this.sn2) {
        if (newSn1) this.lastname = this.lastname + ' ' + this.sn2.trim()
        else this.lastname = this.sn2.trim()
      }
      if (this.lastname) this.lastname.trim()
    },
    sn2 (newSn2) {
      this.lastname = ''
      if (this.sn1) this.lastname = this.sn1.trim()
      if (newSn2) {
        if (this.sn1) this.lastname = this.lastname + ' ' + newSn2.trim()
        else this.lastname = newSn2.trim()
      }
      if (this.lastname) this.lastname.trim()
    },
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
    reset () {
      this.checkResult = null
      this.checkResultErrorUsers = []
      this.username = ''
      this.name = ''
      this.lastname = ''
      this.sn1 = ''
      this.sn2 = ''
      this.email = ''
      this.idnumber = ''
    },
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
    async updatePerson () {
      try {
        await window.axios.post('/api/v1/people', {
          givenName: this.name,
          sn1: this.sn1,
          sn2: this.sn2,
          user_id: this.idnumber
        })
      } catch (error) {
        this.$snackbar.showError(error)
      }
    },
    async add () {
      this.adding = true
      let user = {
        'username': this.username,
        'firstname': this.name,
        'lastname': this.lastname,
        'email': this.email,
        'idnumber': this.idnumber
      }
      if (this.createpassword) user['createpassword'] = true
      else user['password'] = this.password
      if (user.lastname) await this.updatePerson()
      window.axios.post('/api/v1/moodle/users', {
        user: user
      }).then((response) => {
        this.adding = false
        this.$snackbar.showMessage('Usuari afegit correctament a Moodle')
        this.$emit('close')
        this.$emit('created', response.data)
      }).catch((error) => {
        this.$snackbar.showError(error)
        this.adding = false
      })
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
