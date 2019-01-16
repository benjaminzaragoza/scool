<template>
    <v-flex xs12>
        <v-layout>
            <v-flex xs12 md6 lg4>
                <dashboard-positions v-if="$hasRole('Teacher')" :user="user"></dashboard-positions>
                <material-card v-else
                        v-bind="$attrs"
                        class="v-card--material-stats"
                        v-on="$listeners"
                >
                    <v-card
                            slot="offset"
                            color="primary"
                            class="elevation-3 pa-4"
                            dark
                    >
                        <v-icon
                                size="40"
                        >
                            person
                        </v-icon>
                    </v-card>
                    <div class="text-xs-right">
                        <p class="category grey--text font-weight-light">Sol·licitar una plaça</p>
                        <h3 class=" font-weight-light mt-5">
                                Si us plau ompliu el <a href="/nou_professor" target="_blank">formulari de petició de plaça</a>
                        </h3>
                    </div>

                    <template slot="actions">
                        <v-flex
                                xs12
                                text-xs-right
                        >
                        </v-flex>
                    </template>
                </material-card>
            </v-flex>
        </v-layout>
    </v-flex>
</template>

<script>
export default {
  name: 'TeachersWelcome',
  props: {
    user: {
      type: Object,
      required: true
    }
  }
}
</script>

<style lang="scss">
    .v-card--material-stats {
        display: flex;
        flex-wrap: wrap;
        position: relative;

        .v-offset {
            display: inline-block;
            flex: 0 1;
            margin-top: 0;
            margin-left: 0;
            margin-right: auto;
            margin-bottom: 0 !important;
            max-width: auto;
            padding: 0 16px 0;
        }

        .v-card {
            border-radius: 4px;
            flex: 0 1 auto;
        }

        .v-card__text {
            display: inline-block;
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
        }

        .v-card__actions {
            flex: 1 0 100%;
        }
    }
</style>
