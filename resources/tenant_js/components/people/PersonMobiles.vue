<template>
    <span>
        <span v-text="mobile"></span>
        <v-btn icon flat small @click="showmobiles">
            <v-icon small>info</v-icon>
        </v-btn>
    </span>
</template>

<script>
export default {
  name: 'PersonMobiles',
  props: {
    mobile: {
      type: String,
      required: true
    },
    user: {
      type: Object,
      required: true
    }
  },
  methods: {
    showEmails () {
      'TODO show other mobiles'
    }
  }
}
</script>
