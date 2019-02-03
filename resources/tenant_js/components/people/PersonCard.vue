<template>
    <v-card class="elevation-3" v-if="!closed && person !== null">
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
                <v-flex xs8>
                    {{ person.givenName }} | {{ person.sn1 }} | {{ person.sn2 }}
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
        window.axios.get('/api/v1/people/' + this.person_id).then((response) => {
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
    console.log('CREATED!')
    if (!this.person) {
      console.log('PERSON IS NULL')
      console.log('this.personId:')
      console.log(this.personId)
      if (this.personId) {
        console.log('PERSON_ID IS NOT NULL. FETCHING!')
        this.fetchPerson()
      }
    }
  }
}
</script>

<style scoped>

</style>
