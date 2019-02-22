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
                :loading="removing">
            <v-icon>delete</v-icon>
        </v-btn>
        <span>Esborrar usuari</span>
    </v-tooltip>
</template>

<script>
export default {
  name: 'LdapUserDeleteIcon',
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
        window.axios.delete('/api/v1/ldap/users/' + this.user.id).then(response => {
          if (this.user.employeeId) {
            window.axios.delete('/api/v1/user/' + this.user.employeeId + '/ldap').then(response => {
              this.$snackbar.showMessage('Usuari esborrat correctament')
              this.removing = false
            }).catch(error => {
              console.log(error)
              this.$snackbar.showMessage('Usuari esborrat correctament de Ldap però amb error local')
              this.$snackbar.showError(error)
              this.removing = false
            })
          } else {
            this.$snackbar.showMessage('Usuari esborrat correctament')
            this.removing = false
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
