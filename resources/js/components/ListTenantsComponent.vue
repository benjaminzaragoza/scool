<template>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Les vostres organitzacions</h3>

            <div class="box-tools pull-right">
                <button @click="refresh"
                        type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Refrescar">
                    <i class="fa fa-refresh" :class="{ 'fa-spin': loading }"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Tancar">
                    <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Eliminar">
                    <i class="fa fa-times"></i></button>
            </div>
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Nom</th>
                        <th>Domini</th>
                        <th>URL de la base de dades</th>
                        <th>Accions</th>
                    </tr>
                    <tr v-for="tenant in internalTenants" :key="tenant.id">
                        <td>{{tenant.id}}</td>
                        <td><edit-tenant-name-icon :tenant="tenant" :value="tenant.name"></edit-tenant-name-icon></td>
                        <td><a target="_blank" :href="fullUrl(tenant.subdomain)">{{fullUrl (tenant.subdomain)}}</a></td>
                        <td>mysql://{{tenant.username}}@{{tenant.hostname}}:{{tenant.port}}/{{tenant.database}}</td>
                        <td>
                            <test-connection-button :tenant="tenant"></test-connection-button>
                            <test-admin-user-button :tenant="tenant"></test-admin-user-button>
                            <delete-tenant-button :tenant="tenant"></delete-tenant-button>
                            <change-password-tenant-admin-button :tenant="tenant"></change-password-tenant-admin-button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import TestConnectionButtonComponent from './TestConnectionButtonComponent'
import TestAdminUserButtonComponent from './TestAdminUserButtonComponent'
import DeleteTenantButtonComponent from './DeleteTenantButtonComponent'
import EditTenantNameIconComponent from './EditTenantNameIconComponent'
import ChangePasswordTenantAdminButtonComponent from './ChangePasswordTenantAdminButtonComponent'
import * as types from '../store/mutation-types'
import * as actions from '../store/action-types'
import { mapGetters } from 'vuex'
import swal from 'sweetalert'

export default {
  components: {
    'test-connection-button': TestConnectionButtonComponent,
    'test-admin-user-button': TestAdminUserButtonComponent,
    'delete-tenant-button': DeleteTenantButtonComponent,
    'edit-tenant-name-icon': EditTenantNameIconComponent,
    'change-password-tenant-admin-button': ChangePasswordTenantAdminButtonComponent
  },
  data () {
    return {
      loading: false
    }
  },
  computed: {
    ...mapGetters({
      internalTenants: 'tenants'
    })
  },
  props: {
    tenants: {
      type: Array,
      required: false
    }
  },
  methods: {
    refresh () {
      this.loading = true
      this.$store.dispatch(actions.GET_TENANTS).then(() => {
        this.loading = false
      }).catch(error => {
        console.log(error)
        swal('Error', 'Ha passat un error', 'error')
        this.loading = false
      })
    },
    fullUrl (subdomain) {
        return 'http://' + subdomain + '.' + Laravel.app.domain // eslint-disable-line
    }
  },
  mounted () {
    this.$store.commit(types.SET_TENANTS, this.tenants)
  }
}
</script>
