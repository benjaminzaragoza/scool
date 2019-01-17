<template>
    <v-btn icon class="mx-0" @click="remove">
        <v-icon color="error">delete</v-icon>
    </v-btn>
</template>

<script>
import * as actions from '../../../store/action-types'

export default {
  name: 'RoleDelete',
  data () {
    return {
      deleting: false
    }
  },
  props: {
    role: {
      type: Object,
      required: true
    }
  },
  methods: {
    async remove () {
      let res = await this.$confirm('Esteu segurs que voleu eliminar aquest rol?', { title: 'Esteu segurs?', buttonTrueText: 'Eliminar' })
      if (res) {
        this.removeRole()
      }
    },
    removeRole () {
      this.deleting = true
      this.$store.dispatch(actions.DELETE_ROLE, this.role).then(response => {
        this.deleting = false
        this.$snackbar.showMessage('Rol eliminat correctament')
      }).catch(error => {
        this.$snackbar.showError(error)
        this.deleting = false
      })
    }
  }
}
</script>
