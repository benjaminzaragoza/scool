<template>
    <v-btn v-if="alt" flat title="Eliminar el Mòdul Professional"  class="ma-0"
           :loading="loading" :disabled="loading" :id="'delete_subjectGroup_' + subjectGroup.id" @click="remove" >
        Eliminar
        <v-icon right dark>delete</v-icon>
    </v-btn>
    <v-btn v-else flat color="error" icon title="Eliminar el Mòdul Professional" class="ma-0"
           :loading="loading" :disabled="loading" :id="'delete_subjectGroup_' + subjectGroup.id" @click="remove">
        <v-icon>delete</v-icon>
    </v-btn>
</template>

<script>

import * as actions from '../../../store/action-types'

export default {
  name: 'SubjectGroupDelete',
  data () {
    return {
      loading: false
    }
  },
  props: {
    subjectGroup: {
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
      let res = await this.$confirm('Els mòduls professionals esborrats no es poden recuperar.', { title: 'Esteu segurs?', buttonTrueText: 'Eliminar' })
      if (res) {
        this.$emit('before')
        this.loading = true
        this.$store.dispatch(actions.DELETE_SUBJECT_GROUP, this.subjectGroup).then(() => {
          this.$snackbar.showMessage('Mòdul Professional eliminat correctament')
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
