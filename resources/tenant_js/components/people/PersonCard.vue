<template>
    <v-card class="elevation-3" v-if="!closed">
        <v-toolbar dense color="white" class="elevation-0">
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn icon @click.native="$emit('editing');edit();">
                    <v-icon color="success">edit</v-icon>
                </v-btn>
                <v-btn icon @click.native="closed=true;$emit('close')">
                    <v-icon color="grey">close</v-icon>
                </v-btn>
                <v-btn v-if="!minified" icon @click.native="minified=true;$emit('minified')">
                    <v-icon color="grey">remove</v-icon>
                </v-btn>
                <v-btn v-else icon @click.native="minified=false;$emit('maxified')">
                    <v-icon color="grey">add</v-icon>
                </v-btn>
            </v-toolbar-items>
        </v-toolbar>
        <v-container fluid grid-list-xs v-if="!minified">
            <v-layout row wrap>
                <v-flex xs8 v-if="internalPerson !== null">
                    <h1 class="grey--text text--darken-3 headline font-weight-black">
                        <v-tooltip bottom v-if="checkName()">
                            <v-icon slot="activator" color="success">check</v-icon>
                            <span>Nom complet dades personals coincideix correctament amb el nom d'usuari</span>
                        </v-tooltip>
                        <v-tooltip bottom v-else>
                            <v-icon slot="activator" color="error">close</v-icon>
                            <span>No coincideix Nom complet dades personals amb el nom d'usuari</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <span slot="activator" v-text="internalPerson.givenName"></span>
                            <span>Nom</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <span slot="activator" v-text="internalPerson.sn1"></span>
                            <span>1r cognom</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <span slot="activator" v-text="internalPerson.sn2"></span>
                            <span>2n cognom</span>
                        </v-tooltip>
                    </h1>
                    <h2
                            class="pink--text text--lighten-2 font-weight-bold"
                            style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        <v-tooltip bottom v-if="checkEmail()">
                            <v-icon slot="activator" color="success">check</v-icon>
                            <span>Coincideix email dades personals amb el email d'usuari</span>
                        </v-tooltip>
                        <v-tooltip bottom v-else>
                            <v-icon slot="activator" color="error">close</v-icon>
                            <span>No coincideix email dades personals amb el email d'usuari</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <v-btn small slot="activator" icon color="pink" flat class="ma-0">
                                <v-icon small>info</v-icon>
                            </v-btn>
                            <span>Vegeu altres adreçes de correu electrònic</span>
                        </v-tooltip>
                        <v-tooltip left v-if="internalPerson.email">
                            <span slot="activator" v-text="internalPerson.email"></span>
                            <span v-text="internalPerson.email"></span>
                        </v-tooltip>
                        <span v-else>No s'ha indicat cap email personal</span>
                    </h2>

                    <h3 class="grey--text text--darken-3 subheading font-weight-bold">
                        <v-tooltip bottom>
                            <span slot="activator" v-text="internalPerson.identifier_value"></span>
                            <span v-text="internalPerson.identifier_type"></span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <v-btn slot="activator" icon color="pink" flat>
                                <v-icon>info</v-icon>
                            </v-btn>
                            <span>Vegeu altres identificadors de l'usuari</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <span slot="activator" v-text="internalPerson.mobile"></span>
                            <span>Mòbil</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <v-btn slot="activator" icon color="pink" flat>
                                <v-icon>info</v-icon>
                            </v-btn>
                            <span>Vegeu altres mòbils de l'usuari</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <span slot="activator" v-text="internalPerson.phone"></span>
                            <span>Telèfon</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <v-btn slot="activator" icon color="pink" flat>
                                <v-icon>info</v-icon>
                            </v-btn>
                            <span>Vegeu altres telèfons de l'usuari</span>
                        </v-tooltip>
                    </h3>


                    <p class="grey--text text--darken-2 mt-2">
                        <v-tooltip bottom>
                            <span slot="activator" v-if="internalPerson.gender"  class="font-weight-bold" v-text="internalPerson.gender"></span>
                            <span v-text="internalPerson.civil_status"></span>
                        </v-tooltip>
                        <span v-if="internalPerson.birthdate || internalPerson.birthplace_id">
                            <span v-text="genderText()"></span>
                            <span v-if="internalPerson.birthdate">
                                el
                                <span class="font-weight-bold"> {{ internalPerson.birthdate_formatted }}</span>
                            </span>
                            <span v-if="internalPerson.birthplace_id">
                                a
                                <span class="font-weight-bold">
                                    <v-tooltip bottom>
                                        <span slot="activator">{{ internalPerson.birthplace_name }}</span>
                                        <span>{{ internalPerson.birthplace_postalcode }}</span>
                                    </v-tooltip>
                                </span>
                            </span>
                        </span>
                    </p>

                    <!--TODO-->
                    <p class="grey--text text--darken-2 mt-2">
                        C/ Alcanyiz nº 26 4t 2a 43500 TORTOSA
                    </p>


                    <p class="grey--text text--darken-2 mt-2">
                        <template v-if="internalPerson.created_at">
                            Dades personals creades
                            <v-tooltip bottom>
                                <span slot="activator" class="font-weight-bold">{{ internalPerson.formatted_created_at_diff }}</span>
                                <span>{{ internalPerson.formatted_created_at}}</span>
                            </v-tooltip>
                        </template>
                        <template v-if="internalPerson.updated_at"> | Ultima modificació
                            <v-tooltip bottom>
                                <span slot="activator" class="font-weight-bold">{{ internalPerson.formatted_updated_at_diff }}</span>
                                <span>{{ internalPerson.formatted_updated_at}}</span>
                            </v-tooltip>
                        </template>
                    </p>

                    <p class="grey--text text--darken-2 mt-2"
                       style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        Notes:
                        <v-tooltip bottom>
                            <span slot="activator">{{ internalPerson.notes }}</span>
                            <span>{{ internalPerson.notes}}</span>
                        </v-tooltip>
                    </p>
                </v-flex>
                <v-flex 12>
                    <template v-if="fetching">
                        <v-progress-circular
                                :width="3"
                                color="red"
                                indeterminate
                                class="mr-2"
                        ></v-progress-circular>
                        Obtenint les dades personals de l'usuari
                    </template>

                </v-flex>
            </v-layout>
        </v-container>
    </v-card>
</template>

<script>
export default {
  name: 'PersonCard',
  data () {
    return {
      internalPerson: this.person,
      closed: false,
      minified: false,
      fetching: false
    }
  },
  props: {
    person: {
      type: Object,
      default: null
    },
    personId: {
      type: Number,
      default: null
    }
  },
  watch: {
    person: function (newUser) {
      this.internalPerson = this.person
    }
  },
  methods: {
    genderText () {
      if (this.internalPerson.gender) {
        if (this.internalPerson.gender === 'Home') return 'Nascut'
        if (this.internalPerson.gender === 'Dona') return 'Nascuda'
      }
      return 'Nascut/da'
    },
    checkEmail () {
      return (this.internalPerson.userEmail === this.internalPerson.email)
    },
    checkName () {
      return this.fullname() === this.internalPerson.name
    },
    fullname () {
      let fullname = ''
      if (this.internalPerson.givenName) fullname = fullname + this.internalPerson.givenName
      if (this.internalPerson.sn1) {
        fullname = fullname + ' ' + this.internalPerson.sn1
        if (this.internalPerson.sn2) fullname = fullname + ' ' + this.internalPerson.sn2
      }
      return fullname
    },
    edit () {
      console.log('TODO EDIT')
    },
    formatDate (date) {
      if (!date) return null
      const [year, month, day] = date.split('-')
      return `${day}/${month}/${year}`
    },
    address () {
      return this.internalPerson.address_name + ' ' + this.internalPerson.address_number + ' ' + this.internalPerson.address_floor + ' ' + this.internalPerson.address_floor_number
    },
    other_emails () {
      if (this.internalPerson.other_emails) {
        return JSON.parse(this.internalPerson.other_emails).join()
      }
    },
    other_phones () {
      let result = ''
      if (this.internalPerson.other_phones) {
        result = JSON.parse(this.internalPerson.other_phones).join()
      }
      if (this.internalPerson.other_mobiles) {
        result = result + ' ' + JSON.parse(this.internalPerson.other_mobiles).join()
      }
      return result
    },
    hasTIS () {
      return this.internalPerson.tis
    },
    hasBirthplace () {
      return this.internalPerson.birthplace
    },
    hasCivilStatus () {
      return this.internalPerson.civil_status
    },
    fetchPerson (message = false) {
      if (this.personId) {
        this.fetching = true
        window.axios.get('/api/v1/people/' + this.personId).then((response) => {
          this.internalPerson = response.data
          this.fetching = false
          if (message) this.$snackbar.showMessage('Dades personals obtingudes correctament')
        }).catch(error => {
          this.fetching = false
          this.$snackbar.showError(error)
        })
      }
    }
  },
  created () {
    if (!this.person) {
      if (this.personId) this.fetchPerson()
    }
  }
}
</script>

<style scoped>

</style>
