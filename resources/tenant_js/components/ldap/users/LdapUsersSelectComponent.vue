<template>
    <v-autocomplete
            :items="ldapUsers"
            v-model="ldapUser"
            hint="Utilitzeu la funcionalitat autocompletar per buscar usuaris..."
            label="Escolliu un usuari de Ldap"
            persistent-hint
            prepend-icon="mdi-city"
            clearable
            chips
            item-text="fullname"
            return-object
            @change="$emit('selected', ldapUser)"
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
                {{ data.item.dn }}| {{ data.item.email }}
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
                    <v-list-tile-title v-html="data.item.dn"></v-list-tile-title>
                    <v-list-tile-sub-title v-html="data.item.email"></v-list-tile-sub-title>
                </v-list-tile-content>
            </template>
        </template>
    </v-autocomplete>
</template>

<script>
export default {
  name: 'LdapUsersSelectComponent',
  data () {
    return {
      isEditing: true,
      ldapUser: null,
      ldapUsers: [],
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
      this.ldapUser = null
      this.$emit('selected', this.ldapUser)
    },
    refresh () {
      this.getLdapUsers(true)
    },
    selectCurrentUser () {
      if (this.user.ldapDn) {
        this.ldapUser = this.ldapUsers.find(ldapUser => {
          return ldapUser.dn === this.user.ldapDn
        })
        if (!this.ldapUser) this.$snackbar.showError('El compte ' + this.user.ldapDn + ' no existeix a Ldap')
      }
    },
    getLdapUsers (refresh) {
      refresh = refresh || false
      this.loading = true
      let url = '/api/v1/ldap/users'
      if (!refresh) url = url + '?cache=true'
      window.axios.get(url).then(response => {
        this.loading = false
        this.ldapUsers = response.data
        this.selectCurrentUser()
      }).catch(error => {
        this.loading = false
        this.$snackbar.showError(error)
      })
    }
  },
  created () {
    this.ldapUsers = this.getLdapUsers()
  }
}
</script>
