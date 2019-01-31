<template>
    <span style="display:inline-block">
        <v-tooltip bottom>
            <v-btn icon flat slot="activator" class="mx-0" @click="dialog=true">
                <v-icon color="error">remove</v-icon>
            </v-btn>
            <span>Eliminar les dades personals</span>
        </v-tooltip>
        <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition" @keydown.esc="dialog = false">
            TODO
        </v-dialog>
    </span>
</template>

<script>
export default {
  name: 'PersonDeleteIcon',
  data () {
    return {
      dialog: false
    }
  }
}
</script>
