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
                <v-flex xs4 align-top>
                    <user-avatar :hash-id="internalUser.hashid"
                                 :alt="internalUser.name"
                                 color="grey lighten-4"
                                 size="135"
                    ></user-avatar>
                </v-flex>
                <v-flex xs8>
                    <v-tooltip left>
                        <h1 slot="activator"
                            class="grey--text text--darken-3 headline font-weight-black"
                            style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                            v-text="user.name">
                        </h1>
                        <span v-text="user.name"></span>
                    </v-tooltip>

                    <h2
                            class="pink--text text--lighten-2 font-weight-bold"
                            style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        <v-tooltip bottom v-if="user.email_verified_at">
                            <v-icon slot="activator" color="success">check</v-icon>
                            <span>Email verificat correctament el {{ user.email_verified_at_formatted }}</span>
                        </v-tooltip>
                        <v-tooltip bottom v-else>
                            <v-icon slot="activator" color="error">close</v-icon>
                            <span>Email pendent de verificar</span>
                        </v-tooltip>

                        <v-tooltip left>
                            <span slot="activator" v-text="user.email"></span>
                            <span v-text="user.email"></span>
                        </v-tooltip>
                    </h2>
                    <p class="grey--text mt-2">
                        <span v-if="user.mobile">
                            (+34) {{ user.mobile }}
                            <v-tooltip bottom v-if="user.mobile_verified_at">
                                <v-icon small slot="activator" color="success">check</v-icon>
                                <span>Mòbil verificat correctament el {{ user.mobile_verified_at_formatted }}</span>
                            </v-tooltip>
                            <v-tooltip bottom v-else>
                                <v-icon small slot="activator" color="error">close</v-icon>
                                <span>Mòbil pendent de verificar!</span>
                            </v-tooltip>
                            <span class="ml-2 mr-2" v-if="user.user_type">|</span>
                        </span>
                        <span class="grey--text text--darken-2 mt-2 font-weight-bold" v-text="formatUserType(user.user_type)"></span>
                    </p>
                    <p class="grey--text text--darken-2 mt-2" v-if="user.last_login">
                        Vist/a per últim cop
                        <v-tooltip bottom>
                            <span slot="activator">{{ user.last_login_diff }}</span>
                            <span>{{ user.last_login_formatted }}</span>
                        </v-tooltip> des de l'adreça IP {{ user.last_login_ip }}
                    </p>
                    <p class="grey--text text--darken-2 mt-2" v-else>No ha entrat mai al sistema</p>
                </v-flex>
            </v-layout>
        </v-container>

    </v-card>

</template>

<script>
import UserAvatar from '../ui/UserAvatarComponent'

export default {
  name: 'UserCard',
  components: {
    'user-avatar': UserAvatar
  },
  data () {
    return {
      internalUser: this.user,
      closed: false,
      minified: false
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
    edit () {
      console.log('TODO EDIT')
    },
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
    },
    formatUserType (userType) {
      return this.userTypeTranslations[userType]
    }
  },
  created () {
    this.userTypeTranslations = {
      'teacher': 'Professor/a',
      'student': 'Alumne/a'
    }
  }
}
</script>

<style scoped>

</style>
