<template>
    <span>
        <v-progress-circular indeterminate color="primary" v-if="removing"></v-progress-circular>
        <v-chip v-model="subject.close" v-for="subject in subjects" :key="subject.id"
                small label close
                :title="subject.name"
                @input="remove(subject)">{{subject.code}} ({{subject.number}})</v-chip>
    </span>
</template>
<script>
import * as actions from '../../../store/action-types'

export default {
  name: 'SubjectGroupSubjectsList',
  data () {
    return {
      subjects: this.subjectGroup.subjects,
      removing: false
    }
  },
  props: {
    subjectGroup: {
      type: Object,
      required: true
    }
  },
  watch: {
    subjectGroup (subjectGroup) {
      this.subjects = subjectGroup.subjects
    }
  },
  methods: {
    async remove (subject) {
      let res = await this.$confirm('Les unitats formatives no es poden recuperar.', { title: 'Esteu segurs?', buttonTrueText: 'Eliminar' })
      if (res) {
        this.removing = true
        this.$store.dispatch(actions.DELETE_SUBJECT, subject).then(() => {
          this.$snackbar.showMessage('Unitat formativa eliminada correctament')
          this.$emit('removed', subject)
          this.removing = false
        }).catch(error => {
          this.$snackbar.showError(error)
          this.removing = false
        })
      } else subject.close = true
    }
  }
}
</script>
