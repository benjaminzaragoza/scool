<template>
    <span>
        <v-chip small v-for="subjectGroup in study.subjectGroups" :key="subjectGroup.id" label>{{subjectGroup.code}} ({{subjectGroup.number}})</v-chip>
    </span>
</template>
<script>
export default {
  name: 'StudySubjectGroupsCodeList',
  props: {
    study: {
      type: Object,
      required: true
    }
  }
}
</script>
