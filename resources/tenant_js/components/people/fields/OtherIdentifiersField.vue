<template>
    <v-dialog
            v-model="dialog"
            width="500"
    >
        <v-tooltip bottom slot="activator">
            <v-btn icon small color="accent" slot="activator">
                <v-icon small>credit_card</v-icon>
            </v-btn>
            <span>Afegir altres identificadors personals</span>
        </v-tooltip>
        <v-card>
            <v-toolbar dense color="primary">
                <v-toolbar-title class="white--text">Altres identificadors personals</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon class="white--text" @click="dialog=false">
                    <v-icon>close</v-icon>
                </v-btn>
            </v-toolbar>
            <v-card-text>
                <span style="display: flex">
                    <full-identifier-field v-model="identifier" required validate></full-identifier-field>
                    <v-tooltip bottom>
                        <v-btn slot="activator" icon color="success" @click="add">
                            <v-icon>add</v-icon>
                        </v-btn>
                        <span>Afegir identificador alternatiu</span>
                    </v-tooltip>
                </span>
                <v-list class="elevation-2" v-if="dataOtherIdentifiers && dataOtherIdentifiers.length > 0">
                    <v-list-tile v-for="identifier in dataOtherIdentifiers" :key="identifier.value">
                        <v-list-tile-content>
                            <v-list-tile-title>{{ identifier.type_id }} - {{ identifier.value }}</v-list-tile-title>
                        </v-list-tile-content>
                        <v-list-tile-action>
                            <v-tooltip bottom>
                                <v-btn flat small slot="activator" icon color="error" @click="remove(identifier)">
                                    <v-icon small>remove</v-icon>
                                </v-btn>
                                <span>Eliminar identificador alternatiu</span>
                            </v-tooltip>
                        </v-list-tile-action>
                    </v-list-tile>
                </v-list>
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
import FullIdentifierField from './FullIdentifierField'
export default {
  name: 'OtherIdentifiersField',
  components: {
    'full-identifier-field': FullIdentifierField
  },
  data () {
    return {
      dialog: false,
      identifier: null,
      dataOtherIdentifiers: null
    }
  },
  model: {
    prop: 'otherIdentifiers',
    event: 'input'
  },
  props: {
    otherIdentifiers: ''
  },
  methods: {
    remove (identifier) {
      this.dataOtherIdentifiers.splice(this.dataOtherIdentifiers.indexOf(identifier), 1)
    },
    add () {
      if (!this.dataOtherIdentifiers) this.dataOtherIdentifiers = []
      this.dataOtherIdentifiers.push(this.identifier)
    },
    input () {
      this.$emit('input', this.dataOtherIdentifiers)
    },
    blur () {
      this.$emit('blur', this.dataOtherIdentifiers)
    }
  }
}
</script>
