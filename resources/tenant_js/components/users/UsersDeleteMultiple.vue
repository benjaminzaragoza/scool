<template>
    <v-btn color="error" @click="remove" :loading="loading" :disabled="loading">
        <v-icon>delete</v-icon> Eliminar
    </v-btn>
</template>

<script>
export default {
  name: 'UsersDeleteMultiple',
  data () {
    return {
      loading: false
    }
  },
  props: {
    users: {
      type: Array,
      required: true
    }
  },
  methods: {
    remove () {
      this.loading = true
      window.axios.post('/api/v1/users/multiple', { users: this.users.map(user => user.id) }).then(response => {
        this.$snackbar.showMessage("S'han esborrar correctament " + response.data + ' usuaris')
        this.$emit('deleted', response.data)
        this.loading = false
      }).catch(error => {
        this.$snackbar.showError(error)
        this.loading = false
      })
    }
  }
}
</script>
