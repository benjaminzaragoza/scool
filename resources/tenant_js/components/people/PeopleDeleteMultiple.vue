<template>
    <v-btn color="error" @click="remove" :loading="loading" :disabled="loading">
        <v-icon>delete</v-icon> Eliminar
    </v-btn>
</template>

<script>
export default {
  name: 'PeopleDeleteMultiple',
  data () {
    return {
      loading: false
    }
  },
  props: {
    people: {
      type: Array,
      required: true
    }
  },
  methods: {
    async remove () {
      let res = await this.$confirm('Esteu segurs que voleu eliminar aquestes dades personals?', { title: 'Esteu segurs?', buttonTrueText: 'Eliminar' })
      if (res) {
        this.removePeople()
      }
    },
    removePeople () {
      this.loading = true
      window.axios.post('/api/v1/people/multiple', { people: this.people.map(user => user.id) }).then(response => {
        this.$snackbar.showMessage("S'han esborrat correctament " + response.data + ' dades personals')
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
