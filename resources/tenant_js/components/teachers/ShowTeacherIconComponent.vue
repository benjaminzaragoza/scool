<template>
    <span style="display:inline-block">
        <v-btn v-if="icon" icon class="mx-0" title="Vegeu la fitxa del professor" @click="dialog=true">
            <v-icon color="primary">visibility</v-icon>
        </v-btn>
        <v-btn v-else class="mx-0" title="Vegeu la fitxa del professor" @click="dialog=true">
            <v-icon color="primary">visibility</v-icon> Fitxa del professor
        </v-btn>
        <v-dialog v-if="dialog" v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition" @keydown.esc="dialog = false">
            <teacher-show-card @close="dialog=false" :teacher="teacher" :teachers="teachers"></teacher-show-card>
        </v-dialog>
    </span>
</template>

<style>

</style>

<script>
import TeacherShowCard from './TeacherShowCard'

export default {
  name: 'ShowTeacherIcon',
  components: {
    'teacher-show-card': TeacherShowCard
  },
  data () {
    return {
      dialog: false
    }
  },
  props: {
    icon: {
      type: Boolean,
      default: true
    },
    teacher: {
      type: Object,
      required: true
    },
    teachers: {
      type: Array,
      required: true
    }
  }
}
</script>
