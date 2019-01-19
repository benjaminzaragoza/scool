<template>
    <v-btn icon small color="error" flat class="ma-0">
        <v-icon small>remove</v-icon>
    </v-btn>
</template>

<script>
export default {
  name: 'RemoveRoleToUserIcon',
  props: {
    user: {
      type: Object,
      required: true
    }
  }
}
</script>
