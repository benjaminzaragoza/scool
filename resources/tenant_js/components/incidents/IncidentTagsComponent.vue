<template>
    <span>
        <v-chip label :color="tag.color" text-color="white" v-for="(tag,key) in tags" :key="tag.id" close v-model="close[key]">
            <v-icon left v-text="tag.icon"></v-icon>{{ tag.value }}
        </v-chip>
        <v-btn v-role="'IncidentsManager'" icon flat color="teal" class="text--white ma-0" @click="add">
          <v-icon>add</v-icon>
        </v-btn>
    </span>
</template>

<script>

export default {
  name: 'IncidentTags',
  data () {
    return {
      loading: false,
      close: []
    }
  },
  props: {
    tags: {
      type: Array,
      required: true
    }
  },
  watch: {
    close (newValue) {
      console.log('close changed:')
      console.log(newValue)
      if (this.$hasRole('IncidentsManager')) {
        // TODO remove tag
      }
    }
  },
  methods: {
    async remove () {
      console.log('TODO')
    },
    add () {
      console.log('TODO ADD')
    }
  },
  created () {
    this.tags.forEach(tag => {
      this.close.push(true)
    })
  }
}
</script>
