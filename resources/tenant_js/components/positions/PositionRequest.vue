<template>
    <span>
        <v-btn
                class="mx-0 font-weight-light"
                color="success"
                @click="dialog = true"
        >
            Sol·licitar un càrrec
        </v-btn>

        <v-dialog
                v-if="dialog"
                v-model="dialog"
                width="500"
        >   <v-card>
              <v-card-title
                      class="headline primary lighten-2 text--lighten-3 grey--text text--darken-2"
                      primary-title
              >
                  Càrrecs
              </v-card-title>

              <v-card-text>
                <positions-list></positions-list>
              </v-card-text>

              <v-divider></v-divider>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                          color="primary"
                          flat
                          @click="dialog = false"
                  >
                  Cancel·lar
                </v-btn>
                <v-btn
                        color="primary"
                        @click="dialog = false"
                >
                  Sol·licitar
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>

    </span>
</template>

<script>
import PositionsList from './PositionsList'
export default {
  name: 'PositionRequest',
  components: {
    'positions-list': PositionsList
  },
  data () {
    return {
      dialog: false
    }
  }
}
</script>
