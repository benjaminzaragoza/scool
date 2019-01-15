<template>
    <v-btn color="primary" flat title="Enviar email" icon @click="send">
        <v-icon>email</v-icon>
    </v-btn>
</template>

<script>
export default {
  name: 'PositionSendEmail',
  data () {
    return {
      loading: false
    }
  },
  props: {
    position: {
      type: Object,
      required: true
    }
  },
  methods: {
    send () {
      console.log('TODO SEND')
    }
  }
}
</script>
