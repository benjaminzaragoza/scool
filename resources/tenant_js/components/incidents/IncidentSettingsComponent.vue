<template>
    <span id="incident_settings">
        <v-toolbar dark color="primary">
            <v-btn icon dark @click.native="$emit('close')">
                <v-icon>close</v-icon>
            </v-btn>
            <v-toolbar-title v-html="title"></v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn dark flat @click.native="$emit('close')" class="hidden-sm-and-down">
                    Sortir
                    <v-icon right dark>exit_to_app</v-icon>
                </v-btn>
            </v-toolbar-items>
        </v-toolbar>
        <v-stepper v-model="step" vertical>
            <v-progress-linear v-if="loading" :indeterminate="true"></v-progress-linear>
            <v-stepper-step :complete="step > 1" step="1">
              Activació del mòdul
              <small>En aquest apartat podeu activar o desactiva el mòdul d'incidències</small>
            </v-stepper-step>

            <v-stepper-content step="1">
              <v-card color="grey lighten-5" class="mb-5" height="200px">
                  <v-form>
                      <v-switch :label="activate ? 'Actiu' : 'Inactiu'" v-model="activate"></v-switch>
                  </v-form>
              </v-card>
              <v-btn color="primary" @click="step = 2">Continuar</v-btn>
              <v-btn flat @click.native="$emit('close')"><v-icon right dark class="mr-1">exit_to_app</v-icon>Sortir</v-btn>
            </v-stepper-content>

            <v-stepper-step :complete="step > 2" step="2">Usuaris del mòdul</v-stepper-step>

            <v-stepper-content step="2">
              <v-card color="grey lighten-5" class="mb-5">
                  <incident-users @loaded="loading = false" ></incident-users>
              </v-card>
              <v-btn color="primary" @click="step = 3">Continuar</v-btn>
              <v-btn flat @click="step = 1">Anterior</v-btn>
            </v-stepper-content>

            <v-stepper-step :complete="step > 3" step="3">Configuració del mòdul</v-stepper-step>

            <v-stepper-content step="3">
              <v-card color="grey lighten-5" class="mb-5" height="200px">
                 <settings module="incidents" @close="settingsDialog = false" title="Prova"></settings>
              </v-card>
              <v-btn color="primary" @click.native="$emit('close')"><v-icon right dark class="mr-1">exit_to_app</v-icon> Finalitzar</v-btn>
              <v-btn flat @click="step = 2">Anterior</v-btn>
            </v-stepper-content>
        </v-stepper>
    </span>
</template>

<script>
import SettingsComponent from '../ui/SettingsComponent'
import IncidentUsersComponent from './IncidentUsersComponent'

export default {
  name: 'IncidentSettings',
  components: {
    'settings': SettingsComponent,
    'incident-users': IncidentUsersComponent
  },
  data () {
    return {
      step: 1,
      activate: true,
      loading: true
    }
  },
  props: {
    title: {
      type: String,
      default: "Configuració mòdul d'incidències"
    }
  }
}
</script>
