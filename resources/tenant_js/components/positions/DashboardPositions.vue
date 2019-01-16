<template>
    <material-card
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
            <p class="category grey--text font-weight-light">Càrrecs</p>
            <h3
                    class="title display-1 font-weight-light">
                888 <small>666</small>
            </h3>
        </div>

        <template slot="actions">
            <v-flex
                    xs12
                    text-xs-right
            >
                <position-request></position-request>
            </v-flex>
        </template>
    </material-card>
</template>

<script>
import PositionRequest from './PositionRequest'
export default {
  name: 'DashboardPositions',
  components: {
    'position-request': PositionRequest
  },
  data () {
    return {
      loading: false
    }
  },
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
