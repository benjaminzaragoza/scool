<template>
    <v-data-table
            class="px-0 mb-2 hidden-sm-and-down"
            :headers="headers"
            :items="filteredLessons"
            :search="search"
            item-key="id"
            no-results-text="No s'ha trobat cap registre coincident"
            no-data-text="No hi han dades disponibles"
            rows-per-page-text="Lliçons per pàgina"
    >
        <template slot="items" slot-scope="{item: lesson}">
            <tr>
                <td class="text-xs-left" v-html="lesson.id"></td>
                <td class="text-xs-left" v-html="lesson.subject_id"></td>
                <td class="text-xs-left" v-html="lesson.job_id"></td>
                <td class="text-xs-left" v-html="lesson.start"></td>
                <td class="text-xs-left" v-html="lesson.end"></td>
            </tr>
        </template>
    </v-data-table>
</template>

<script>
export default {
  name: 'SubjectLessonsComponent',
  data () {
    return {
      internalLessons: this.lessons,
      search: ''
    }
  },
  computed: {
    filteredLessons () {
      return this.internalLessons
    },
    headers () {
      let headers = []
      headers.push({ text: 'Id', align: 'left', value: 'id' })
      // if (this.showJobTypeHeader) {
      //   headers.push({text: 'Tipus', value: 'type'})
      // }
      headers.push({ text: 'subject_id', value: 'subject_id' })
      headers.push({ text: 'job_id', value: 'job_id' })
      headers.push({ text: 'start_date', value: 'start' })
      headers.push({ text: 'end_date', value: 'end' })
      return headers
    }
  },
  props: {
    lessons: {
      type: Array,
      required: true
    }
  }
}
</script>
