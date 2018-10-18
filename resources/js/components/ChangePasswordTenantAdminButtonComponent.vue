<template>
    <div style="display: inline;">
        <button type="button" class="btn" :class="cssClass" data-toggle="modal" data-target="#modal-change-password-admin-user"> {{ text }}</button>
        <div class="modal fade" id="modal-change-password-admin-user">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Canviar la paraula de pas de l'usuari {{ loggedUser.email }}</h4>
                    </div>
                    <div class="modal-body">
                        <form role="form" @submit.prevent="changePassword" @keydown="clearErrors($event.target.name)">
                            <div class="box-body">
                                <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('password') }">
                                    <label>Nova paraula de pas</label>
                                    <input type="password" class="form-control" placeholder="Paraula de pas" name="password" v-model="form.password"/>
                                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                    <transition name="fade">
                                        <span class="help-block" v-if="form.errors.has('password')" v-text="form.errors.get('password')"></span>
                                    </transition>
                                </div>
                                <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('password_confirmation') }">
                                    <label>Confirmació paraula de pas</label>
                                    <input type="password" class="form-control" placeholder="Confirmació paraula de pas" name="password_confirmation" v-model="form.password_confirmation"/>
                                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                    <transition name="fade">
                                        <span class="help-block" v-if="form.errors.has('password_confirmation')" v-text="form.errors.get('password_confirmation')"></span>
                                    </transition>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tancar</button>
                        <button type="button" class="btn btn-warning" @click="changePassword"
                                :disabled="changingPassword" :loading="changingPassword">Canviar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import swal from 'sweetalert'
import Form from 'acacha-forms'

export default {
  data () {
    return {
      cssClass: 'btn-warning',
      text: 'Change Password',
      form: new Form({ password: '', password_confirmation: '' }),
      loading: false,
      changingPassword: false
    }
  },
  computed: {
    ...mapGetters([
      'loggedUser'
    ])
  },
  props: {
    tenant: {
      type: Object,
      required: true
    }
  },
  methods: {
    clearErrors (name) {
      if (name === 'password_confirmation') name = 'password'
      this.form.errors.clear(name)
    },
    changePassword () {
      this.changingPassword = true
      this.form.put('/api/v1/tenant/' + this.tenant.id + '/password', {
        password: this.form.password,
        password_confirmation: this.form.password_confirmation
      }).then((response) => {
        this.changingPassword = false
      }).catch((error) => {
        this.changingPassword = false
        console.log(error)
        if (error.response) {
          if (parseInt(error.response.status) !== 422) swal('Error', error.message, 'error')
        } else {
          swal('Error', error.message, 'error')
        }
      })
    }
  }
}
</script>
