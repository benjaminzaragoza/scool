<template>
    <span>
        <v-btn icon class="mx-0 pa-0" title="Afegiu usuari de Moodle" @click.native.stop="addMoodleUser">
            <v-icon color="primary">add</v-icon>
        </v-btn>
    </span>
</template>
<script>
export default {
  name: 'ManageMoodleUserIcon',
  methods: {
    addMoodleUser () {
      console.log('TODO')
    }
  }
}
</script>
