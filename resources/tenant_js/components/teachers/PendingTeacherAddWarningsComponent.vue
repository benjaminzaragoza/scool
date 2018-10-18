<template>
    <v-container fluid grid-list-md text-xs-center>
        <v-layout row wrap>
            <v-flex xs12>
                <v-alert v-model="showError" type="error" dismissible>
                    <span class="title">Aquest formulari ÉS NOMÉS PER A PROFESSORS NOUS</span><br/>
                    <template v-if="!loggedUser">
                        Si ja disposeu d'usuari si us plau entreu...
                        <login-button redirect="/teacher/profile"></login-button>
                    </template>
                </v-alert>
            </v-flex>
            <v-alert v-model="showWarning" type="warning" dismissible v-if="loggedUser">
                <span class="title">Actualment esteu dins del sistema amb l'usuari <strong>{{loggedUser}}</strong></span><br/>
                <span class="subheading">Podeu canviar les vostres dades de professor al <a href="/teacher/profile">perfil de professor</a></span><br/>
                <span class="subheading">No cal que torneu a introduir les vostres dades, aquest formulari és només per a professors nous</span>
            </v-alert>
        </v-layout>
    </v-container>
</template>

<style>

</style>

<script>
  export default {
    data () {
      return {
        showError: true,
        showWarning: true
      }
    },
    props: {
      loggedUser: {
        type: String,
        required: true
      }
    }
  }
</script>
