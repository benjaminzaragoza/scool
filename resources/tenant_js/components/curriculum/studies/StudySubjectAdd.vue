<template>
    <span>
        <v-btn flat title="Afegir UF a l'estudi" icon @click="dialog = true" color="secondary" >
            <v-icon>fas fa-plus-circle</v-icon>
            <v-badge left overlap color="success">
              <span slot="badge" v-text="number" class="caption"></span>
            </v-badge>
        </v-btn>
    </span>
</template>

<script>
export default{
  name: 'StudySubjectAdd',
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
      return this.study.subjects_number || '?'
    },
    number () {
      if (this.study.subjects) return this.study.subjects.length
      return 0
    }
  }
}
</script>
