<template>
    <v-card>
        <v-container fluid grid-list-xs>
            <v-layout row wrap>
                <v-flex xs2>
                    <v-container grid-list-xs text-xs-center>
                        <v-layout row wrap>
                            <v-flex xs12>
                                <user-avatar :hash-id="internalUser.hashid"
                                             :alt="internalUser.name"
                                ></user-avatar>
                            </v-flex>
                            <v-flex xs12>
                                <span class="title" v-html="internalUser.name"></span>
                                <span class="subtitle" v-html="internalUser.email"></span>
                            </v-flex>
                            <v-flex xs12>
                                <v-list two-line>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title v-html="internalUser.givenName"></v-list-tile-title>
                                            <v-list-tile-sub-title>Nom</v-list-tile-sub-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    <v-divider></v-divider>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title v-html="internalUser.sn1"></v-list-tile-title>
                                            <v-list-tile-sub-title>1r Cognom</v-list-tile-sub-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    <v-divider></v-divider>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title v-html="internalUser.sn2"></v-list-tile-title>
                                            <v-list-tile-sub-title>2n Cognom</v-list-tile-sub-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                </v-list>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-flex>
                <v-flex xs10>
                    <v-container grid-list-xs>
                        <v-layout row wrap>
                            <v-flex xs3 >
                                <h1 class="title primary--text">
                                    <div>
                                        <p>Dades personals</p>
                                    </div>

                                </h1>
                            </v-flex>
                            <v-flex xs4 >
                                <h1 class="title primary--text">
                                    <div>
                                        <p>Adreça</p>
                                    </div>
                                </h1>
                            </v-flex>
                            <v-flex xs5 >
                                <h1 class="title primary--text">
                                    <div>
                                        <p>Contacte</p>
                                    </div>
                                </h1>
                            </v-flex>
                            <v-flex xs12>
                                <v-container grid-list-xs>
                                    <v-layout row wrap>
                                        <v-flex xs3>
                                            <v-list two-line>
                                                <v-list-tile>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="internalUser.identifier"></v-list-tile-title>
                                                        <v-list-tile-sub-title v-html="internalUser.identifier_type"></v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </v-list-tile>
                                                <v-list-tile v-if="hasTIS()">
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="internalUser.tis"></v-list-tile-title>
                                                        <v-list-tile-sub-title>TIS</v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </v-list-tile>
                                                <v-list-tile>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="internalUser.birthdate"></v-list-tile-title>
                                                        <v-list-tile-sub-title>Data de naixement</v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </v-list-tile>
                                                <v-list-tile>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="internalUser.gender"></v-list-tile-title>
                                                        <v-list-tile-sub-title>Sexe</v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </v-list-tile>
                                                <v-list-tile v-if="hasBirthplace()">
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="internalUser.birthplace"></v-list-tile-title>
                                                        <v-list-tile-sub-title>Lloc de naixement</v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </v-list-tile>
                                                <v-list-tile v-if="hasCivilStatus()">
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="internalUser.civil_status"></v-list-tile-title>
                                                        <v-list-tile-sub-title>Estat civil</v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </v-list-tile>
                                            </v-list>
                                        </v-flex>
                                        <v-flex xs4>
                                            <v-list two-line>
                                                <v-list-tile>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="address()"></v-list-tile-title>
                                                        <v-list-tile-sub-title>Adreça</v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </v-list-tile>
                                                <v-list-tile>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="internalUser.address_name"></v-list-tile-title>
                                                        <v-list-tile-sub-title>Codi Postal</v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </v-list-tile>
                                                <v-list-tile>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="internalUser.address_location"></v-list-tile-title>
                                                        <v-list-tile-sub-title>Població</v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </v-list-tile>
                                                <v-list-tile>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="internalUser.address_province"></v-list-tile-title>
                                                        <v-list-tile-sub-title>Província</v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </v-list-tile>
                                                <!--TODO-->
                                                <v-list-tile>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title>Catalunya</v-list-tile-title>
                                                        <v-list-tile-sub-title>País</v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </v-list-tile>
                                            </v-list>
                                        </v-flex>
                                        <v-flex xs5>
                                            <v-list two-line>
                                                <v-list-tile>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="internalUser.personal_email"></v-list-tile-title>
                                                        <v-list-tile-sub-title>Email personal</v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </v-list-tile>
                                                <v-list-tile>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="other_emails()"></v-list-tile-title>
                                                        <v-list-tile-sub-title>Altres emails</v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </v-list-tile>
                                                <v-list-tile>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="internalUser.mobile"></v-list-tile-title>
                                                        <v-list-tile-sub-title>Mòbil</v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </v-list-tile>
                                                <v-list-tile>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="internalUser.phone"></v-list-tile-title>
                                                        <v-list-tile-sub-title>Telèfon fix</v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </v-list-tile>
                                                <v-list-tile>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="other_phones()"></v-list-tile-title>
                                                        <v-list-tile-sub-title>Altres telèfons</v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </v-list-tile>
                                            </v-list>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-flex>
            </v-layout>
        </v-container>

    </v-card>

</template>

<script>
import UserAvatar from '../ui/UserAvatarComponent'

export default {
  components: {
    'user-avatar': UserAvatar
  },
  name: 'PersonalDataCardComponent',
  data () {
    return {
      internalUser: this.user
    }
  },
  props: {
    user: {
      type: Object,
      required: true
    }
  },
  watch: {
    user: function (newUser) {
      this.internalUser = this.user
    }
  },
  methods: {
    formatDate (date) {
      if (!date) return null
      const [year, month, day] = date.split('-')
      return `${day}/${month}/${year}`
    },
    address () {
      return this.internalUser.address_name + ' ' + this.internalUser.address_number + ' ' + this.internalUser.address_floor + ' ' + this.internalUser.address_floor_number
    },
    other_emails () {
      if (this.internalUser.other_emails) {
        return JSON.parse(this.internalUser.other_emails).join()
      }
    },
    other_phones () {
      let result = ''
      if (this.internalUser.other_phones) {
        result = JSON.parse(this.internalUser.other_phones).join()
      }
      if (this.internalUser.other_mobiles) {
        result = result + ' ' + JSON.parse(this.internalUser.other_mobiles).join()
      }
      return result
    },
    hasTIS () {
      return this.internalUser.tis
    },
    hasBirthplace () {
      return this.internalUser.birthplace
    },
    hasCivilStatus () {
      return this.internalUser.civil_status
    }
  }
}
</script>

<style scoped>

</style>
