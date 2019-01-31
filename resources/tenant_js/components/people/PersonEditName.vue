<template>
    <v-tooltip bottom style="max-width: 25px; overflow: hidden;">
        <span slot="activator" v-text="value"></span>
        <span v-text="value"></span>
    </v-tooltip>
</template>

<script>
export default {
  name: 'PersonEditName',
  props: {
    value: {
      type: String,
      required: true
    }
  }
}
</script>
