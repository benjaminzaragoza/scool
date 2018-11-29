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
                                <v-alert v-for="message in checkResultErrorMessage" :value="true" type="error">L'usuari seleccionat té possibles problemes alhora de crear un usuari a Moodle: {{ message }}</v-alert>
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
                </v-layout>
            </v-container>
            <template v-if="$hasRole('IncidentsManager')">
                <v-btn @click="confirmAdd"
                       id="add_incident_button"
                       color="teal"
                       class="white--text"
                       :loading="adding || checking"
                       :disabled="adding || checking || checkResult === null"
                >Afegir</v-btn>
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
        <form v-else>
            <v-container fluid grid-list-md text-xs-center>
                <v-layout row wrap>
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
      existingUser: true,
      subject: '',
      description: '',
      adding: false,
      user: null,
      checking: false,
      checkResult: null,
      checkResultErrorMessage: '',
      checkResultErrorUsers: []
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
      } else {
        this.checkResult = null
        this.checkResultErrorUsers = []
      }
    }
  },
  methods: {
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
    add () {
      this.adding = true
      window.axios.post('/api/v1/moodle/users').then((response) => {
        this.adding = false
        this.$snackbar.showMessage('Usuari afegit correctament a Moodle')
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
        console.log(response.data)
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
