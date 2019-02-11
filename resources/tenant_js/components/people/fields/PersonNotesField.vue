<template>
    <v-dialog
            v-model="dialog"
            width="500"
    >
        <v-tooltip bottom slot="activator">
            <v-btn icon small color="success" slot="activator">
                <v-icon small>comment</v-icon>
            </v-btn>
            <span>Afegir comentaris/notes sobre la persona</span>
        </v-tooltip>
        <v-card>
            <v-toolbar dense color="primary">
                <v-toolbar-title class="white--text">Notes/Comentaris sobre la persona</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon class="white--text" @click="dialog=false">
                    <v-icon>close</v-icon>
                </v-btn>
            </v-toolbar>
            <v-card-text>
                <v-textarea
                        label="Notes/comentaris"
                        v-model="dataNotes"
                        hint="Podeu posar qualsevol text que creieu convenient..."
                        @input="input"
                        @blur="blur"
                ></v-textarea>
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions>
                <v-btn
                        flat
                        color="primary"
                        @click="dialog = false"
                >
                    Tancar
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
  name: 'PersonNotesField',
  data () {
    return {
      dialog: false,
      dataNotes: null
    }
  },
  model: {
    prop: 'notes',
    event: 'input'
  },
  props: {
    notes: ''
  },
  methods: {
    input () {
      this.$emit('input', this.dataNotes)
    },
    blur () {
      this.$emit('blur', this.dataNotes)
    }
  }
}
</script>
