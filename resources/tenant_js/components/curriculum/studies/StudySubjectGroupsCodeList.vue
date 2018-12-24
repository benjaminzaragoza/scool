<template>
    <span>
        <v-chip small v-model="subjectGroup.close" v-for="subjectGroup in subjectGroups" :key="subjectGroup.id" label close @input="remove(subjectGroup)">{{subjectGroup.code}} ({{subjectGroup.number}})</v-chip>
    </span>
</template>
<script>
export default {
  name: 'StudySubjectGroupsCodeList',
  data () {
    return {
      subjectGroups: this.study.subjectGroups
    }
  },
  props: {
    study: {
      type: Object,
      required: true
    }
  },
  methods: {
    remove (subjectGroup) {
      console.log('TODO')
      console.log('subjectGroup:')
      console.log(subjectGroup)
    }
  }
}
</script>
