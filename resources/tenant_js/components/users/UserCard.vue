<template>
    <v-card class="ma-3 elevation-3">
        <v-toolbar dense color="white" class="elevation-0">
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn icon @click.native="$emit('close')">
                    <v-icon color="success">edit</v-icon>
                </v-btn>
                <v-btn icon @click.native="$emit('close')">
                    <v-icon color="grey">close</v-icon>
                </v-btn>
                <v-btn icon @click.native="$emit('close')">
                    <v-icon color="grey">remove</v-icon>
                </v-btn>
            </v-toolbar-items>
        </v-toolbar>
        <v-container fluid grid-list-xs>
            <v-layout row wrap align-center>
                <v-flex xs4>
                    <v-avatar
                            size="150"
                            color="grey lighten-4"
                    >
                        <img src="http://i.pravatar.cc/128" alt="avatar">
                    </v-avatar>
                </v-flex>
                <v-flex xs8>
                    <v-tooltip left>
                        <h1 slot="activator"
                            class="grey--text text--darken-3 headline font-weight-black"
                            style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            Sergi Tur Badenas
                        </h1>
                        <span>Sergi Tur Badenas</span>
                    </v-tooltip>

                    <h2
                            class="pink--text text--lighten-2 font-weight-bold"
                            style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        <v-tooltip bottom v-if="false">
                            <v-icon slot="activator" color="success">check</v-icon>
                            <span>Email verificat correctament!</span>
                        </v-tooltip>
                        <v-tooltip bottom v-else>
                            <v-icon slot="activator" color="error">close</v-icon>
                            <span>Email pendent de verificar!</span>
                        </v-tooltip>

                        <v-tooltip left>
                            <span slot="activator">sergiturbadenas@gmail.com</span>
                            <span>sergiturbadenas@gmail.com</span>
                        </v-tooltip>
                    </h2>
                    <p class="grey--text mt-2"> (+34) 679 525 437
                        <v-tooltip bottom v-if="true">
                            <v-icon small slot="activator" color="success">check</v-icon>
                            <span>Mòbil verificat correctament!</span>
                        </v-tooltip>
                        <v-tooltip bottom v-else>
                            <v-icon small slot="activator" color="error">close</v-icon>
                            <span>Mòbil pendent de verificar!</span>
                        </v-tooltip>
                        <span class="ml-2 mr-2">|</span>
                        <span class="grey--text text--darken-2 mt-2 font-weight-bold">Professor</span>
                    </p>
                    <p class="grey--text text--darken-2 mt-2">Vist per últim cop el 12 de Gener de 2019 a les 13:42:45 des de l'adreça IP 192.168.50.41</p>
                </v-flex>
            </v-layout>
        </v-container>

    </v-card>

</template>

<script>
import UserAvatar from '../ui/UserAvatarComponent'

export default {
  name: 'UserdCard',
  components: {
    'user-avatar': UserAvatar
  },
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
