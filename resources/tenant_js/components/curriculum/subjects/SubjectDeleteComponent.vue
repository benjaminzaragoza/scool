<template>
    <v-btn v-if="alt" flat title="Eliminar la unitat formativa"  class="ma-0"
           :loading="loading" :disabled="loading" :id="'delete_subject_' + subject.id" @click="remove" >
        Eliminar
        <v-icon right dark>delete</v-icon>
    </v-btn>
    <v-btn v-else flat color="error" icon title="Eliminar la unitat formativa" class="ma-0"
           :loading="loading" :disabled="loading" :id="'delete_subject_' + subject.id" @click="remove">
        <v-icon>delete</v-icon>
    </v-btn>
</template>

<script>

import * as actions from '../../../store/action-types'

export default {
  name: 'SubjectDelete',
  data () {
    return {
      loading: false
    }
  },
  props: {
    subject: {
      type: Object,
      required: true
    },
    alt: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    async remove () {
      let res = await this.$confirm('Les unitats formatives esborrades no es poden recuperar.', { title: 'Esteu segurs?', buttonTrueText: 'Eliminar' })
      if (res) {
        this.$emit('before')
        this.loading = true
        this.$store.dispatch(actions.DELETE_SUBJECT, this.subject).then(response => {
          this.$snackbar.showMessage('Unitat formativa eliminada correctament')
          this.loading = false
        }).catch(error => {
          this.$snackbar.showError(error)
          this.loading = false
        })
      }
    }
  }
}
</script>
