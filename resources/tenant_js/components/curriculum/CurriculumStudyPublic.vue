<script>
export default {
  name: 'CurriculumStudyPublic',
  data () {
    return {
      todo: []
    }
  },
  props: {
    study: {
      type: Object,
      required: true
    }
  }
}
</script>
