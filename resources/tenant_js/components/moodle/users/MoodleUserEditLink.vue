<template>
    <v-btn icon :title="title" flat :color="color" :href="href()" target="_blank">
        <v-icon v-text="icon"></v-icon>
    </v-btn>
</template>

<script>
export default {
  name: 'MoodleUserEditLink',
  props: {
    user: {
      type: Object,
      required: true
    },
    title: {
      type: String,
      default: "Edita l'usuari a Moodle"
    },
    color: {
      type: String,
      default: 'success'
    },
    icon: {
      type: String,
      default: 'edit'
    }
  },
  methods: {
    href () {
      return 'https://www.iesebre.com/moodle/user/editadvanced.php?id=' + this.user.id + '&course=1&returnto=profile'
    }
  }
}
</script>
