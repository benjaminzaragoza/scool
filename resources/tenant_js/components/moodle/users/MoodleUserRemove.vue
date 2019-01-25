<template>
    <v-btn icon :title="title" flat :color="color" @click="remove(user)" :disabled="removing === user.id" :loading="removing  === user.id">
        <v-icon>remove</v-icon>
    </v-btn>
</template>

<script>
export default {
  name: 'moodleUserRemove',
  data () {
    return {
      removing: false
    }
  },
  props: {
    user: {
      type: Object,
      required: true
    },
    title: {
      type: String,
      default: "Eliminar l'usuari"
    },
    color: {
      type: String,
      default: 'error'
    },
    icon: {
      type: String,
      default: 'edit'
    }
  },
  methods: {
    async remove (user) {
      let res = await this.$confirm('Els usuaris esborrar no es poden recuperar.', { title: 'Esteu segurs?', buttonTrueText: 'Eliminar' })
      if (res) {
        this.removing = user.id
        window.axios.delete('/api/v1/moodle/users/' + user.id).then(() => {
          this.dataUsers.splice(this.dataUsers.indexOf(user), 1)
          this.$snackbar.showMessage('Usuari esborrat correctament')
          this.removing = null
        }).catch(error => {
          this.$snackbar.showError(error)
          this.removing = null
        })
      }
    }
  }
}
</script>
