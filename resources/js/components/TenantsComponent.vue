<template>
    <div id="user_tenants">
        <list-tenants :tenants="tenants"></list-tenants>
        <create-tenant></create-tenant>
    </div>
</template>

<script>

import CreateTenant from './CreateTenantComponent'
import ListTenants from './ListTenantsComponent'

export default {
  components: {
    'create-tenant': CreateTenant,
    'list-tenants': ListTenants
  },
  props: {
    tenants: {
      type: Array,
      required: false
    }
  }
}
</script>
