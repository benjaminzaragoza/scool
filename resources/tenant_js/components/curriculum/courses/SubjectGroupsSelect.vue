<template>
    <v-autocomplete
            :name="name"
            :label="label"
            :items="courses"
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
</template>

<script>
export default {
  name: 'CoursesSelect',
  data () {
    return {
      internalCourse: this.course
    }
  },
  model: {
    prop: 'course',
    event: 'input'
  },
  props: {
    courses: {
      type: Array,
      required: true
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
      default: 'id'
    }
  },
  watch: {
    course (newCourse) {
      this.internalCourse = newCourse
    }
  },
  methods: {
    input () {
      this.$emit('input', this.internalCourse)
    },
    blur () {
      this.$emit('blur', this.internalCourse)
    }
  }
}
</script>
