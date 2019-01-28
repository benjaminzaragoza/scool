<template>
    <v-tooltip bottom>
        <v-btn
                class="ma-0"
                slot="activator"
                icon
                color="primary"
                flat
                @click.native="active"
                :disabled="activating"
                :loading="activating"
                :id="'google_user_active_' + user.primaryEmail.replace('@','_')">
            <v-icon>play_arrow</v-icon>
        </v-btn>
        <span>Activar l'usuari</span>
    </v-tooltip>
</template>

<script>
export default {
  name: 'GoogleUserActiveIcon',
  data () {
    return {
      activating: false
    }
  },
  props: {
    user: {
      type: Object,
      required: true
    }
  },
  methods: {
    async active () {
      let res = await this.$confirm("Esteu segurs que voleu activar l'usuari?", {
        title: 'Esteu segurs?',
        buttonTrueText: 'Activar'
      })
      if (res) {
        this.activating = true
        window.axios.post('/api/v1/gsuite/active/users/' + this.user.id).then(response => {
          this.activating = false
          this.$snackbar.showMessage('Usuari activat correctament')
        }).catch(error => {
          this.activating = false
          this.$snackbar.showError(error)
        })
      }
    }
  }
}
</script>


