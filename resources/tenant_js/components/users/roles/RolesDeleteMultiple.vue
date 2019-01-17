<template>
    <v-btn color="error" @click="remove" :loading="loading" :disabled="loading">
        <v-icon>delete</v-icon> Eliminar
    </v-btn>
</template>

<script>
export default {
  name: 'RolesDeleteMultiple',
  data () {
    return {
      loading: false
    }
  },
  props: {
    roles: {
      type: Array,
      required: true
    }
  },
  methods: {
    remove () {
      this.loading = true
      window.axios.post('/api/v1/roles/multiple', { roles: this.roles.map(role => role.id) }).then(response => {
        this.$snackbar.showMessage("S'han esborrat correctament " + response.data + ' roles')
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
