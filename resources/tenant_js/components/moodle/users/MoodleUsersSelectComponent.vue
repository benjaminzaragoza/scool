<template>
    <v-autocomplete
            :items="moodleUsers"
            v-model="moodleUser"
            hint="Utilitzeu la funcionalitat autocompletar per buscar usuaris..."
            label="Escolliu un usuari de Moodle"
            persistent-hint
            prepend-icon="mdi-city"
            clearable
            chips
            item-text="fullname"
            return-object
            @change="$emit('selected',moodleUser)"
            :loading="loading"
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
                    <img v-if="data.item.profileimageurl" :src="data.item.profileimageurl">
                    <img v-else src="/img/default.png" alt="photo per defecte">
                </v-avatar>
                {{ data.item.fullname }} | {{ data.item.email }}
            </v-chip>
        </template>
        <template
                slot="item"
                slot-scope="data"
        >
            <template v-if="typeof data.item !== 'object'">
                <v-list-tile-content v-text="data.item"></v-list-tile-content>
            </template>
            <template>
                <v-list-tile-avatar>
                    <img v-if="data.item.profileimageurl" :src="data.item.profileimageurl">
                    <img v-else src="/img/default.png" alt="photo per defecte">
                </v-list-tile-avatar>
                <v-list-tile-content>
                    <v-list-tile-title v-html="data.item.fullname"></v-list-tile-title>
                    <v-list-tile-sub-title v-html="data.item.email"></v-list-tile-sub-title>
                </v-list-tile-content>
            </template>
        </template>
    </v-autocomplete>
</template>

<script>
import axios from 'axios'

export default {
  name: 'MoodleUsersSelectComponent',
  data () {
    return {
      isEditing: true,
      moodleUser: null,
      moodleUsers: [],
      loading: false
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
      this.moodleUser = null
      this.$emit('selected', this.moodleUser)
    },
    refresh () {
      this.getMoodleUsers(true)
    },
    selectCurrentUser () {
      if (this.user.moodleId) {
        this.moodleUser = this.moodleUsers.find(moodleUser => {
          return parseInt(moodleUser.id) === parseInt(this.user.moodleId)
        })
        if (!this.moodleUser) this.$snackbar.showError('El compte ' + this.user.moodleId + ' no existeix a Moodle')
      }
    },
    getMoodleUsers (refresh) {
      refresh = refresh || false
      this.loading = true
      let url = '/api/v1/moodle/users'
      if (!refresh) url = url + '?cache=true'
      axios.get(url).then(response => {
        this.loading = false
        this.moodleUsers = response.data
        this.selectCurrentUser()
      }).catch(error => {
        this.loading = false
        this.$snackbar.showError(error)
      })
    }
  },
  created () {
    this.moodleUsers = this.getMoodleUsers()
  }
}
</script>
