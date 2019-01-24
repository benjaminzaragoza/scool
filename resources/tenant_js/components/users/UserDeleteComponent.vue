<template>
    <v-tooltip bottom>
        <v-btn slot="activator" icon class="mx-0" @click="remove">
            <v-icon color="error">delete</v-icon>
        </v-btn>
        <span>Eliminar l'usuari</span>
    </v-tooltip>
</template>

<script>
import * as actions from '../../store/action-types'

export default {
  name: 'UserDelete',
  data () {
    return {
      deleting: false
    }
  },
  props: {
    user: {
      type: Object,
      required: true
    }
  },
  methods: {
    async remove () {
      let res = await this.$confirm('Esteu segurs que voleu eliminar aquest usuari?', { title: 'Esteu segurs?', buttonTrueText: 'Eliminar' })
      if (res) {
        this.removeUser()
      }
    },
    removeUser () {
      this.deleting = true
      this.$store.dispatch(actions.DELETE_USER, this.user).then(response => {
        this.deleting = false
        this.$snackbar.showMessage('Usuari eliminat correctament')
      }).catch(error => {
        this.$snackbar.showError(error)
        this.deleting = false
      })
    }
  }
}
</script>
