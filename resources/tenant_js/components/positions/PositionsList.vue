<template>
    <v-autocomplete
            v-model="selectedPosition"
            :items="dataPositions"
            attach
            chips
            label="Càrrecs"
            item-value="id"
            item-text="value"
    >
        <template slot="selection" slot-scope="data">
            <v-chip
                    small
                    label
                    @input="data.parent.selectItem(data.item)"
                    :selected="data.selected"
                    class="chip--select-multi"
                    :color="data.item.color"
                    text-color="white"
                    :key="JSON.stringify(data.item)"
            ><v-icon small left v-text="data.item.icon"></v-icon>{{ data.item.name }}</v-chip>
        </template>
        <template slot="item" slot-scope="data">
            {{ data.item.name }}
        </template>
    </v-autocomplete>
</template>

<script>
export default {
  name: 'PositionsList',
  data () {
    return {
      selectedPosition: null,
      dataPositions: [],
      fetching: false
    }
  },
  props: {
    positions: {
      type: Array,
      default: function () {
        return undefined
      }
    }
  },
  methods: {
    fetch () {
      this.fetching = true
      window.axios.get('/api/v1/positions').then(response => {
        this.dataPositions = response.data
        this.fetching = false
      }).catch(error => {
        this.$snackbar.showError(error)
        this.fetching = false
      })
    }
  },
  created () {
    if (this.positions === undefined) this.fetch()
    else this.dataPositions = this.positions
  }
}
</script>
