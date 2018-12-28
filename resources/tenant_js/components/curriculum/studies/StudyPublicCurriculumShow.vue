<template>
    <v-btn title="Mostrar el pla docent de l'estudi" icon :href="'/public/curriculum/estudis/' + study.slug" target="_blank">
        <v-icon>fas fa-graduation-cap</v-icon>
    </v-btn>
</template>

<script>
export default {
  name: 'StudyPublicCurriculumShow',
  props: {
    study: {
      type: Object,
      required: true
    }
  }
}
</script>
