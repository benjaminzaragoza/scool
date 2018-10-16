<template>
    <button type="button" class="btn" :class="cssClass" @click="confirmDeletion"> {{ text }}</button>
</template>

<script>
import swal from 'sweetalert'
import * as types from '../store/mutation-types'

export default {
  data () {
    return {
      cssClass: 'btn-danger',
      text: 'Eliminar'
    }
  },
  props: {
    tenant: {
      type: Object,
      required: true
    }
  },
  methods: {
    confirmDeletion () {
      swal('Esteu segurs que voleu eliminar?', {
        buttons: {
          cancel: 'Cancel·lar',
          remove: 'Eliminar'
        }
      })
        .then((value) => {
          switch (value) {
            case 'cancel':
              break
            case 'remove':
              this.remove()
              break
            default:
              break
          }
        })
    },
    remove () {
        axios.delete('api/v1/tenant/' + this.tenant.id) // eslint-disable-line
        .then(response => {
          this.$store.commit(types.REMOVE_TENANT, response.data)
        }).catch(error => {
          console.log(error)
          swal('Error', 'Ha ocorregut un error', 'error')
        })
    }
  }
}
</script>
