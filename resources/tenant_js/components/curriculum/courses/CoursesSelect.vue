<template>
    <span>
        <v-autocomplete
                prepend-icon="add"
                @click:prepend="dialog=true"
                :name="name"
                :label="label"
                :items="filteredCourses"
                v-model="internalCourse"
                item-text="name"
                :item-value="itemValue"
                clearable
                @input="input"
                @blur="blur"
                :error-messages="errorMessages"
        >
        <template slot="item" slot-scope="{ item: course }">
            <v-list-tile-content>
                <v-list-tile-title v-html="course.name"></v-list-tile-title>
                <v-list-tile-sub-title v-html="course.code"></v-list-tile-sub-title>
            </v-list-tile-content>
        </template>
    </v-autocomplete>
        <v-dialog v-model="dialog" v-if="dialog" fullscreen @keydown.esc="dialog=false">
            <v-toolbar color="blue darken-3">
                <v-btn icon dark @click.native="dialog=false">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title class="white--text title">Afegir curs</v-toolbar-title>
            </v-toolbar>
            <v-card>
                    <v-card-text class="px-0 mb-2">
                        <v-container fluid grid-list-md text-xs-center>
                            <v-layout row wrap>
                                <v-flex xs12>
                                     <course-add @close="dialog = false"></course-add>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>
        </v-dialog>
    </span>
</template>

<script>
import CourseAdd from './CourseAdd'
export default {
  name: 'CoursesSelect',
  components: {
    'course-add': CourseAdd
  },
  data () {
    return {
      dialog: false,
      internalCourse: this.course
    }
  },
  model: {
    prop: 'course',
    event: 'input'
  },
  props: {
    study: {
      default: null
    },
    name: {
      type: String,
      default: 'course'
    },
    course: {},
    label: {
      type: String,
      default: 'Escolliu un curs'
    },
    errorMessages: {
      type: Array,
      required: false
    },
    itemValue: {
      type: String,
      default: null
    }
  },
  computed: {
    filteredCourses () {
      if (this.study) {
        if (Number.isInteger(parseInt(this.study))) return this.filterCoursesByStudy(this.study)
        if (this.study.id) return this.filterCoursesByStudy(this.study.id)
      }
      return this.courses
    },
    courses () {
      return this.$store.getters.courses
    }
  },
  watch: {
    course (course) {
      this.internalCourse = course
      this.selectCourse()
    }
  },
  methods: {
    input () {
      this.$emit('input', this.internalCourse)
    },
    blur () {
      this.$emit('blur', this.internalCourse)
    },
    filterCourses (id) {
      return this.courses.filter(course => { return course.id === id })
    },
    filterCoursesByStudy (id) {
      return this.courses.filter(course => { return course.study_id === id })
    },
    selectCourse () {
      if (this.itemValue === null) {
        if (Number.isInteger(parseInt(this.course))) this.internalCourse = this.filterCourses(this.course)[0]
        if (this.course.id) this.internalCourse = this.filterCourses(this.course.id)[0]
      }
    }
  },
  created () {
    this.selectCourse()
  }
}
</script>
