<template>
    <v-card>
        <v-toolbar dark color="primary">
            <v-btn icon dark @click.native="$emit('close')">
                <v-icon>close</v-icon>
            </v-btn>
            <v-toolbar-title>
                Configuració del mòdul d'incidències
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn dark flat @click.native="$emit('close')" class="hidden-sm-and-down">
                    <v-icon right dark class="mr-2">save</v-icon> Guardar
                </v-btn>
            </v-toolbar-items>
        </v-toolbar>
        <form>
            <v-container fluid grid-list-md text-xs-center>
                <v-layout row wrap>
                    <v-flex md12 v-for="setting in settings" :key="setting.key">
                        <v-text-field
                                ref="subject_field"
                                v-focus
                                v-model="values[setting.key]"
                                name="managerEmail"
                                :label="setting.label"
                                :hint="setting.hint"
                                autofocus
                        ></v-text-field>
                    </v-flex>
                </v-layout>
            </v-container>
        </form>
    </v-card>
</template>

<script>
export default {
  name: 'IncidentSettingsComponent',
  data () {
    return {
      values: [],
      loading: false,
      settings: []
    }
  },
  created () {
    this.loading = true
    window.axios.get('/ap1/v1/settings/filter/incidents').then(response => {
      this.loading = false
      this.settings = response.data
      this.settings.forEach(setting => {
        this.values[setting.key] = setting.value
      })
    }).catch(error => {
      this.$snackbar.showError(error)
      this.loading = false
    })
  }
}
</script>
