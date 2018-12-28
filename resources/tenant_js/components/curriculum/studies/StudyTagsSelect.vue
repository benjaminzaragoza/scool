<template>
    <v-autocomplete
            v-model="dataSelectedTags"
            :items="dataTags"
            attach
            chips
            label="Etiquetes d'estudis"
            multiple
            item-value="id"
            item-text="value"
            @input="input"
            @blur="blur"
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
            ><v-icon small left v-text="data.item.icon"></v-icon>{{ data.item.value }}</v-chip>
        </template>
        <template slot="item" slot-scope="data">
            <v-checkbox v-model="data.tile.props.value"></v-checkbox>
            <v-chip small label :title="data.item.description" :color="data.item.color" text-color="white">
                <v-icon small left v-text="data.item.icon"></v-icon>{{ data.item.value }}
            </v-chip>
        </template>
    </v-autocomplete>
</template>

<script>
export default {
  name: 'StudyTagsSelect',
  data () {
    return {
      dataSelectedTags: this.selectedTags,
      dataTags: []
    }
  },
  model: {
    prop: 'selectedTags',
    event: 'input'
  },
  props: {
    tags: {
      type: Array,
      required: false
    },
    selectedTags: {
      type: Array,
      required: true
    }
  },
  watch: {
    tags (tags) {
      this.dataTags = tags
    }
  },
  computed: {
    storeTags () {
      return this.$store.getters.studiesTags
    }
  },
  methods: {
    input () {
      this.$emit('input', this.dataStudy)
    },
    blur () {
      this.$emit('blur', this.dataStudy)
    }
  },
  created () {
    this.dataTags = this.tags ? this.tags : this.storeTags
  }
}
</script>
