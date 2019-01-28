<template>
    <v-tooltip bottom>
        <v-btn
                class="ma-0"
                slot="activator"
                icon
                color="pink"
                flat
                @click.native="suspend"
                :disabled="suspending"
                :loading="suspending"
                :id="'google_user_suspend_' + user.primaryEmail.replace('@','_')">
            <v-icon>stop</v-icon>
        </v-btn>
        <span>Suspendre l'usuari</span>
    </v-tooltip>
</template>

<script>
export default {
  name: 'GoogleUserSuspendIcon',
  data () {
    return {
      suspending: false
    }
  },
  props: {
    user: {
      type: Object,
      required: true
    }
  },
  methods: {
    async suspend () {
      let res = await this.$confirm("Esteu segurs que voleu suspendre l'usuari?", {
        title: 'Esteu segurs?',
        buttonTrueText: 'Suspendre'
      })
      if (res) {
        this.suspending = true
        window.axios.delete('/api/v1/gsuite/active/users/' + user.id).then(response => {
          this.suspending = false
          this.$snackbar.showMessage('Usuari suspès correctament')
        }).catch(error => {
          this.suspending = false
          this.$snackbar.showError(error)
        })
      }
    }
  }
}
</script>
