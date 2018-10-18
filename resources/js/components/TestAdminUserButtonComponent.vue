<template>
    <button type="button" class="btn" :class="cssClass" @click="askPassword"> {{ text }}</button>
</template>

<script>
import swal from 'sweetalert'

export default {
  data () {
    return {
      cssClass: 'btn-default',
      text: 'Test user'
    }
  },
  props: {
    tenant: {
      type: Object,
      required: true
    }
  },
  methods: {
    askPassword () {
      this.test('secret')
    },
    test (password) {
        axios.post('api/v1/tenant/' + this.tenant.id + '/test-user',{'password': password}) // eslint-disable-line
        .then(response => {
          if (response.data.connection === 'ok') {
            this.cssClass = 'btn-success'
            this.text = 'Ok'
          } else {
            this.cssClass = 'btn-danger'
            this.text = 'Error'
            swal('Error', response.data.exception, 'error')
          }
        }).catch(error => {
          console.log(error)
        })
    }
  }
}
</script>
