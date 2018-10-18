<template>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Crear Organització</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                    <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                    <i class="fa fa-times"></i></button>
            </div>
        </div>
        <form role="form" @submit.prevent="create" @keydown="clearErrors($event.target.name)">
            <div class="box-body">
                <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('name') }">
                    <label for="inputName">Nom</label>
                    <input id="inputName" type="text" class="form-control" placeholder="Nom de l'organització" name="name" value="" v-model="form.name" autofocus/>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    <transition name="fade">
                        <span class="help-block" v-if="form.errors.has('name')" v-text="form.errors.get('name')"></span>
                    </transition>
                </div>
                <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('subdomain') }">
                    <label for="inputSubdomain">Subdomini (Exemple: elmeusubdomini.{{domain()}})</label>
                    <input id="inputSubdomain" type="text" class="form-control" placeholder="Subdomini" name="subdomain" value="" v-model="form.subdomain"/>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    <transition name="fade">
                        <span class="help-block" v-if="form.errors.has('subdomain')" v-text="form.errors.get('subdomain')"></span>
                    </transition>
                </div>
                <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('password') }">
                    <label for="inputPassword">Paraula de pas de l'administrador {{ loggedUser.email }}</label>
                    <input id="inputPassword" type="password" class="form-control" placeholder="Paraula de pas" name="password" v-model="form.password"/>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    <transition name="fade">
                        <span class="help-block" v-if="form.errors.has('password')" v-text="form.errors.get('password')"></span>
                    </transition>
                </div>
                <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('password_confirmation') }">
                    <label for="inputPasswordConfirmation">Confirmació Paraula de pas</label>
                    <input id="inputPasswordConfirmation" type="password" class="form-control" placeholder="Confirmació paraula de pas" name="password_confirmation" v-model="form.password_confirmation"/>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    <transition name="fade">
                        <span class="help-block" v-if="form.errors.has('password_confirmation')" v-text="form.errors.get('password_confirmation')"></span>
                    </transition>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary" @click.prevent="create" :disabled="form.errors.any()"><i v-if="form.submitting" class="fa fa-refresh fa-spin"></i> Crear</button>
            </div>
        </form>
    </div>
</template>

<style>
    .fade-enter-active, .fade-leave-active {
        transition: opacity 1s ease;
    }
    .fade-enter, .fade-leave-to {
        opacity: 0;
    }
</style>

<script>
import { mapGetters } from 'vuex'
import * as types from '../store/mutation-types'
import Form from 'acacha-forms'
import swal from 'sweetalert'

export default {
  data () {
    return {
      form: new Form({ name: '', subdomain: '', password: '', password_confirmation: '' })
    }
  },
  computed: {
    ...mapGetters([
      'loggedUser'
    ])
  },
  methods: {
    domain () {
      return window.Laravel.app.domain
    },
    create () {
      this.form.subdomain = this.form.subdomain.toLowerCase()
      this.form.post('/api/v1/tenant').then((response) => {
        this.$store.commit(types.ADD_TENANT, response.data)
      }).catch((error) => {
        console.log(error)
        console.dir(error)
        if (error.response) {
          if (parseInt(error.response.status) !== 422) swal('Error', error.message, 'error')
        } else {
          swal('Error', error.message, 'error')
        }
      })
    },
    clearErrors (name) {
      if (name === 'password_confirmation') name = 'password'
      this.form.errors.clear(name)
    }
  },
  mounted () {
    this.form.clearOnSubmit = true
  }
}
</script>
