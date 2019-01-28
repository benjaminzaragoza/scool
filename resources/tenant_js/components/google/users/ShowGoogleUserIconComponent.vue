<template>
    <span style="display:inline-block">
        <span v-if="icon">
            <v-tooltip bottom>
                <v-btn slot="activator" icon class="mx-0 ma-0" title="" @click.native.stop="dialog = true">
                    <v-icon color="primary">visibility</v-icon>
                </v-btn>
                <span>Fitxa de l'usuari</span>
            </v-tooltip>
        </span>
        <span v-else>
            <v-tooltip bottom>
                <v-btn slot="activator" class="mx-0 ma-0" title="Vegeu la fitxa de l'usuari" @click.native.stop="dialog = false">
                    <v-icon color="primary">visibility</v-icon> Compte de Google
                </v-btn>
                <span>Fitxa de l'usuari</span>
            </v-tooltip>
        </span>
        <v-dialog v-if="dialog" v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition" @keydown.esc="dialog = false">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click.native="dialog = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Compte de Google</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn dark flat @click.native="dialog = false">Imprimir</v-btn>
                    </v-toolbar-items>
                </v-toolbar>

                <v-container grid-list-lg fluid>
                    <v-layout row wrap>
                         <v-flex xs12>
                             <v-avatar>
                                <img v-if="user.thumbnailPhotoUrl" :src="user.thumbnailPhotoUrl">
                                <img v-else src="/img/default.png" alt="photo per defecte">
                            </v-avatar>
                            <v-form>
                                <v-text-field
                                        v-model="user.fullName"
                                        label="fullName"
                                        readonly
                                ></v-text-field>
                                <v-text-field
                                        v-model="user.givenName"
                                        label="givenName"
                                        readonly
                                ></v-text-field>
                                <v-text-field
                                        v-model="user.familyName"
                                        label="familyName"
                                        readonly
                                ></v-text-field>
                                <v-text-field
                                        v-model="user.primaryEmail"
                                        label="primaryEmail"
                                        readonly
                                ></v-text-field>
                                <v-text-field
                                        v-model="user.id"
                                        label="Id"
                                        readonly
                                ></v-text-field>
                                <v-text-field
                                        v-model="user.employeeId"
                                        label="employeeId"
                                        readonly
                                ></v-text-field>
                                <v-text-field
                                        v-model="user.mobile"
                                        label="Mobile"
                                        readonly
                                ></v-text-field>
                                <v-text-field
                                        v-model="user.personalEmail"
                                        label="personalEmail"
                                        readonly
                                ></v-text-field>

                                <v-text-field
                                        v-model="user.isAdmin"
                                        label="isAdmin"
                                        readonly
                                ></v-text-field>
                                <v-text-field
                                        v-model="user.lastLoginTime"
                                        label="lastLoginTime"
                                        readonly
                                ></v-text-field>
                                <v-text-field
                                        v-model="user.creationTime"
                                        label="creationTime"
                                        readonly
                                ></v-text-field>
                                <v-text-field
                                        v-model="user.suspended"
                                        label="suspended"
                                        readonly
                                ></v-text-field>
                                <v-text-field
                                        v-model="user.suspensionReason"
                                        label="suspensionReason"
                                        readonly
                                ></v-text-field>
                                <v-text-field
                                        v-model="user.orgUnitPath"
                                        label="orgUnitPath"
                                        readonly
                                ></v-text-field>
                                <v-text-field
                                        v-model="user.organizations"
                                        label="organizations"
                                        readonly
                                ></v-text-field>
                                <v-textarea
                                        label="Json"
                                        :value="user.json"
                                        readonly
                                ></v-textarea>
                            </v-form>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card>
        </v-dialog>
    </span>
</template>

<style>

</style>

<script>

export default {
  data () {
    return {
      dialog: false,
      internalUser: this.user
    }
  },
  props: {
    user: {
      type: Object,
      default: () => { return {} }
    },
    users: {
      type: Array,
      default: () => []
    },
    icon: {
      type: Boolean,
      default: true
    }
  },
  watch: {
    user: function (newUser) {
      this.internalUser = this.user
    }
  }
}
</script>
