<template>
    <span>
        <v-btn flat title="Afegir MP a l'estudi" icon @click="dialog = true" color="success" >
            <v-icon>fas fa-plus</v-icon>
            <v-badge left overlap color="primary">
              <span slot="badge" v-text="number" class="caption"></span>
            </v-badge>
        </v-btn>
    </span>
</template>

<script>
export default{
  name: 'StudySubjectGroupAdd',
  data () {
    return {
      dialog: false
    }
  },
  props: {
    study: {
      type: Object,
      required: true
    }
  },
  computed: {
    totalNumber () {
      return this.study.subject_groups_number || '?'
    },
    number () {
      if (this.study.subjectGroups) return this.study.subjectGroups.length
      return 0
    }
  }
}
</script>
