<template>
    <span>
        <v-switch
                :label="existingUser ? 'Crear utilitzant un usuari local existent' : 'Crear nou usuari desde zero'"
                v-model="existingUser"
        ></v-switch>
        <template v-if="existingUser">
            <moodle-user-add-form :existing-user="existingUser" :local-users="localUsers"></moodle-user-add-form>
        </template>
        <template v-else>
            <moodle-user-add-form :existing-user="existingUser" :local-users="localUsers"></moodle-user-add-form>
        </template>
    </span>
</template>

<script>
import MoodleUserAddForm from './MoodleUserAddForm'
export default {
  name: 'MoodleUserAdd',
  components: {
    'moodle-user-add-form': MoodleUserAddForm
  },
  data () {
    return {
      existingUser: true
    }
  },
  props: {
    localUsers: {
      type: Array,
      required: true
    }
  }
}
</script>
