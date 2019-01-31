<template>
    <v-card>
        <v-container fluid grid-list-xs>
            <v-layout row wrap>
                <v-flex xs2>
                   TODO
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
