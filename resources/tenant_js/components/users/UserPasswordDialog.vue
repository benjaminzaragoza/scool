<template>
    <v-dialog
            v-if="dataDialog"
            v-model="dataDialog"
            width="750"
            @keydown.esc="dataDialog=false"
            :fullscreen="$vuetify.breakpoint.smAndDown"
            :hide-overlay="$vuetify.breakpoint.smAndDown"
    >
        <v-toolbar color="primary" dense>
            <v-toolbar-title class="white--text">Canviar paraula de pas de
                <span>{{ user.name }}</span> </v-toolbar-title>
        </v-toolbar>
        <user-password-card-form :user="user" @close="dataDialog=false"></user-password-card-form>
    </v-dialog>
</template>

<script>
import UserPasswordCardForm from './UserPasswordCardForm'

export default {
  name: 'UserPasswordDialog',
  components: {
    'user-password-card-form': UserPasswordCardForm
  },
  model: {
    prop: 'dialog',
    event: 'input'
  },
  data () {
    return {
      dataDialog: this.dialog
    }
  },
  watch: {
    dialog () {
      this.dataDialog = this.dialog
    },
    dataDialog (dataDialog) {
      this.$emit('input', dataDialog)
    }
  },
  props: {
    user: {
      type: Object,
      required: true
    },
    dialog: {}
  }
}
</script>
