<template>
    <v-btn v-if="alt" flat title="Eliminar el càrrec"  class="ma-0"
           :loading="loading" :disabled="loading" :id="'delete_position_' + position.id" @click="remove" >
        Eliminar
        <v-icon right dark>delete</v-icon>
    </v-btn>
    <v-btn v-else flat color="error" icon title="Eliminar l'estudi" class="ma-0"
           :loading="loading" :disabled="loading" :id="'delete_position_' + position.id" @click="remove">
        <v-icon>delete</v-icon>
    </v-btn>
</template>

<script>

import * as actions from '../../store/action-types'

export default {
  name: 'PositionDelete',
  data () {
    return {
      loading: false
    }
  },
  props: {
    position: {
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
      let res = await this.$confirm('Els càrrecs esborrats no es poden recuperar.', { title: 'Esteu segurs?', buttonTrueText: 'Eliminar' })
      if (res) {
        this.$emit('before')
        this.loading = true
        this.$store.dispatch(actions.DELETE_POSITION, this.position).then(response => {
          this.$snackbar.showMessage('Càrrec eliminat correctament')
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
