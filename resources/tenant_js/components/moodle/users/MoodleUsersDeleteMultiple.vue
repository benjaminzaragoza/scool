<template>
    <v-btn color="error" @click="remove" :loading="loading" :disabled="loading">
        <v-icon>delete</v-icon> Eliminar
    </v-btn>
</template>

<script>
export default {
  name: 'MoodleUsersDeleteMultiple',
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
    async remove () {
      let res = await this.$confirm('Esteu segurs que voleu eliminar aquests usuaris?', { title: 'Esteu segurs?', buttonTrueText: 'Eliminar' })
      if (res) {
        this.removeUsers()
      }
    },
    removeUsers () {
      this.loading = true
      window.axios.post('/api/v1/moodle/users/multiple', { users: this.users.map(user => user.id) }).then(response => {
        this.$snackbar.showMessage("S'han esborrat correctament " + response.data + ' usuaris')
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
