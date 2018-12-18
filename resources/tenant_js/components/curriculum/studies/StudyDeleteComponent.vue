<template>
    <v-btn v-if="alt" flat title="Eliminar l'estudi"  class="ma-0"
           :loading="loading" :disabled="loading" :id="'delete_study_' + study.id" @click="remove" >
        Eliminar
        <v-icon right dark>delete</v-icon>
    </v-btn>
    <v-btn v-else flat color="error" icon title="Eliminar l'estudi" class="ma-0"
           :loading="loading" :disabled="loading" :id="'delete_study_' + study.id" @click="remove">
        <v-icon>delete</v-icon>
    </v-btn>
</template>

<script>

import * as actions from '../../../store/action-types'

export default {
  name: 'StudyDelete',
  data () {
    return {
      loading: false
    }
  },
  props: {
    study: {
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
      let res = await this.$confirm('Els estudis esborrats no es poden recuperar.', { title: 'Esteu segurs?', buttonTrueText: 'Eliminar' })
      if (res) {
        this.$emit('before')
        this.loading = true
        this.$store.dispatch(actions.DELETE_STUDY, this.study).then(response => {
          this.$snackbar.showMessage('Estudi eliminat correctament')
          this.loading = false
        }).catch(error => {
          console.log('HAY')
          console.log(error)
          this.$snackbar.showError(error)
          this.loading = false
        })
      }
    }
  }
}
</script>
