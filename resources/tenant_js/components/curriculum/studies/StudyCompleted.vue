<template>
    <span>
        <v-btn :title="title" flat icon>
        <v-icon color="success" v-if="study.completed">check</v-icon>
        <v-icon color="error" v-else>close</v-icon>
    </v-btn>
        <span class="caption">MPS: {{ totalSubjectGroupsNumber }} UFs: {{ totalSubjectsNumber }} </span>
    </span>
</template>

<script>
export default {
  name: 'StudyCompleted',
  props: {
    study: {
      type: Object,
      required: true
    }
  },
  computed: {
    title () {
      return this.study.completed ? 'Estudi completat: ' + this.subjectGroupsNumber + " MPs d'un total de " + this.totalSubjectGroupsNumber : 'Estudi pendent:'
    },
    totalSubjectsNumber () {
      return this.study.subjects_number || '?'
    },
    subjectsNumber () {
      if (this.study.subjects) return this.study.subjects.length
      return 0
    },
    totalSubjectGroupsNumber () {
      return this.study.subject_groups_number || '?'
    },
    subjectGroupsNumber () {
      if (this.study.subjectGroups) return this.study.subjectGroups.length
      return 0
    }
  }
}
</script>
