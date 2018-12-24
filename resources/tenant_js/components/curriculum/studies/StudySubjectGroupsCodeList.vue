<template>
    <span>
        <v-progress-circular indeterminate color="primary" v-if="removing"></v-progress-circular>
        <v-chip small v-model="subjectGroup.close" v-for="subjectGroup in subjectGroups" :key="subjectGroup.id" label close @input="remove(subjectGroup)">{{subjectGroup.code}} ({{subjectGroup.number}})</v-chip>
    </span>
</template>
<script>
import * as actions from '../../../store/action-types'

export default {
  name: 'StudySubjectGroupsCodeList',
  data () {
    return {
      subjectGroups: this.study.subjectGroups,
      removing: false
    }
  },
  props: {
    study: {
      type: Object,
      required: true
    }
  },
  methods: {
    async remove (subjectGroup) {
      let res = await this.$confirm('Els mòduls professionals esborrats no es poden recuperar.', { title: 'Esteu segurs?', buttonTrueText: 'Eliminar' })
      if (res) {
        this.removing = true
        this.$store.dispatch(actions.DELETE_SUBJECT_GROUP, subjectGroup).then(() => {
          this.$snackbar.showMessage('Mòdul Professional eliminat correctament')
          this.$emit('removed', subjectGroup)
          this.removing = false
        }).catch(error => {
          this.$snackbar.showError(error)
          this.removing = false
        })
      } else {
        console.log('ELSE')
        subjectGroup.close = true
      }
    }
  }
}
</script>
