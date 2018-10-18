<template>
    <button type="button" class="btn" :class="cssClass" @click="test"> {{ text }}</button>
</template>

<script>
export default {
  data () {
    return {
      cssClass: 'btn-default',
      text: 'Test connection'
    }
  },
  props: {
    tenant: {
      type: Object,
      required: true
    }
  },
  methods: {
    test () {
        axios.get('api/v1/tenant/' + this.tenant.id + '/test') // eslint-disable-line
        .then(response => {
          if (response.data.connection === 'ok') {
            this.cssClass = 'btn-success'
            this.text = 'Ok'
          } else {
            this.cssClass = 'btn-danger'
            this.text = 'Error'
          }
        }).catch(error => {
          console.log(error)
        })
    }
  }
}
</script>
