<template>
    <v-autocomplete
            :items="googleUsers"
            v-model="googleUser"
            hint="Utilitzeu la funcionalitat autocompletar per buscar usuaris..."
            label="Escolliu un usuari de Google"
            persistent-hint
            prepend-icon="mdi-city"
            clearable
            chips
            item-text="fullName"
            return-object
            @change="$emit('selected',googleUser)"
    >
        <template
                slot="selection"
                slot-scope="data"
        >
            <v-chip
                    :selected="data.selected"
                    close
                    class="chip--select-multi"
                    @input="remove(data.item)"
            >
                <v-avatar>
                    <img v-if="data.item.thumbnailPhotoUrl" :src="data.item.thumbnailPhotoUrl">
                    <img v-else src="/img/default.png" alt="photo per defecte">
                </v-avatar>
                {{ data.item.primaryEmail }}
            </v-chip>
        </template>
        <template
                slot="item"
                slot-scope="data"
        >
            <template v-if="typeof data.item !== 'object'">
                <v-list-tile-content v-text="data.item"></v-list-tile-content>
            </template>
            <template v-else>
                <v-list-tile-avatar>
                    <img v-if="data.item.thumbnailPhotoUrl" :src="data.item.thumbnailPhotoUrl">
                    <img v-else src="/img/default.png" alt="photo per defecte">
                </v-list-tile-avatar>
                <v-list-tile-content>
                    <v-list-tile-title v-html="data.item.fullName"></v-list-tile-title>
                    <v-list-tile-sub-title v-html="data.item.primaryEmail"></v-list-tile-sub-title>
                </v-list-tile-content>
            </template>
        </template>
    </v-autocomplete>
</template>

<script>
import axios from 'axios'
import withSnackbar from '../../mixins/withSnackbar'

export default {
  name: 'GoogleUsersSelectComponent',
  mixins: [withSnackbar],
  data () {
    return {
      isEditing: true,
      googleUser: null,
      googleUsers: []
    }
  },
  props: {
    user: {
      type: Object,
      default: () => { return {} }
    }
  },
  methods: {
    remove () {
      this.googleUser = null
    },
    refresh () {
      this.getGoogleUsers(true)
    },
    selectCurrentUser () {
      if (this.user.corporativeEmail) {
        this.googleUser = this.googleUsers.find(googleUser => {
          return googleUser.primaryEmail === this.user.corporativeEmail
        })
        if (!this.googleUser) this.showError('El compte ' + this.user.corporativeEmail + ' no existeix a Google')
      }
    },
    getGoogleUsers (refresh) {
      refresh = refresh || false
      this.refreshing = true
      let url = '/api/v1/gsuite/users'
      if (!refresh) url = url + '?cache=true'
      axios.get(url).then(response => {
        this.refreshing = false
        this.googleUsers = response.data
        this.selectCurrentUser()
      }).catch(error => {
        this.refreshing = false
        console.log(error)
      })
    }
  },
  created () {
    this.googleUsers = this.getGoogleUsers()
  }
}
</script>
