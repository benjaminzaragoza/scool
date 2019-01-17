<template>
    <v-btn icon class="mx-0" @click="remove">
        <v-icon color="error">delete</v-icon>
    </v-btn>
</template>

<script>
import * as actions from '../../../store/action-types'

export default {
  name: 'PermissionDelete',
  data () {
    return {
      deleting: false
    }
  },
  props: {
    permission: {
      type: Object,
      required: true
    }
  },
  methods: {
    async remove () {
      let res = await this.$confirm('Esteu segurs que voleu eliminar aquest permís?', { title: 'Esteu segurs?', buttonTrueText: 'Eliminar' })
      if (res) {
        this.removePermission()
      }
    },
    removePermission () {
      this.deleting = true
      this.$store.dispatch(actions.DELETE_PERMISSION, this.permission).then(response => {
        this.deleting = false
        this.$snackbar.showMessage('Permís eliminat correctament')
      }).catch(error => {
        this.$snackbar.showError(error)
        this.deleting = false
      })
    }
  }
}
</script>
