<template>
    <v-tooltip bottom>
        <v-btn
                class="ma-0"
                slot="activator"
                icon
                color="error"
                flat
                @click.native="remove"
                :disabled="removing"
                :loading="removing"
                :id="'google_user_remove_' + user.primaryEmail.replace('@','_')">
            <v-icon>delete</v-icon>
        </v-btn>
        <span>Esborrar usuari</span>
    </v-tooltip>
</template>

<script>
import * as mutations from '../../../store/mutation-types'

export default {
  name: 'GoogleUserDeleteIcon',
  data () {
    return {
      removing: false
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
      let res = await this.$confirm('Els usuaris esborrats no es poden recuperar.', {
        title: 'Esteu segurs?',
        buttonTrueText: 'Eliminar'
      })
      if (res) {
        this.removing = true
        window.axios.delete('/api/v1/gsuite/users/' + this.user.id).then(response => {
          if (this.user.employeeId) {
            window.axios.delete('/api/v1/user/' + this.user.employeeId + '/gsuite').then(response => {
              this.$snackbar.showMessage('Usuari esborrat correctament')
              this.removing = false
              this.$store.commit(mutations.DELETE_GOOGLE_USER, this.user)
            }).catch(error => {
              console.log(error)
              this.$snackbar.showMessage('Usuari esborrat correctament de Google però amb error local')
              this.$snackbar.showError(error)
              this.removing = false
            })
          } else {
            this.$snackbar.showMessage('Usuari esborrat correctament')
            this.removing = false
            this.$store.commit(mutations.DELETE_GOOGLE_USER, user)
          }
          this.$snackbar.showMessage('Usuari esborrat correctament')
        }).catch(error => {
          this.removing = false
          this.$snackbar.showError(error)
        })
      }
    }
  }
}
</script>
