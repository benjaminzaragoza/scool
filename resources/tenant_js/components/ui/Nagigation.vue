<template>
    <v-navigation-drawer
            v-model="dataDrawer"
            fixed
            app
            clipped
    >
        <v-list dense>
            <template v-for="(item, i) in items">
                <template v-if="$haveRole(item.role)">
                    <v-layout
                            row
                            v-if="item.heading"
                            align-center
                            :key="i"
                    >
                        <v-flex xs6>
                            <v-subheader v-if="item.heading">
                                {{ item.heading }}
                            </v-subheader>
                        </v-flex>
                    </v-layout>
                    <v-list-group v-else-if="item.children" v-model="item.model" no-action>
                        <v-list-tile slot="item" :href="item.href" :target="item.target">
                            <v-list-tile-action>
                                <v-icon>{{ item.model ? item.icon : item['icon-alt'] }}</v-icon>
                            </v-list-tile-action>
                            <v-list-tile-content>
                                <v-list-tile-title v-text="item.text"></v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile
                                v-for="(child, i) in item.children"
                                :key="i"
                                :href="item.href"
                                :target="item.target"
                        >
                            <v-list-tile-action v-if="child.icon">
                                <v-icon>{{ child.icon }}</v-icon>
                            </v-list-tile-action>
                            <v-list-tile-content>
                                <v-list-tile-title v-text="child.text"></v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </v-list-group>
                    <v-list-tile v-else :href="item.href" :target="item.target"
                                 :style="selectedStyle(item)">
                        <v-list-tile-action>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title v-text="item.text"></v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </template>
            </template>
        </v-list>
    </v-navigation-drawer>
</template>

<script>
export default {
  name: 'Navigation',
  data () {
    return {
      dataDrawer: this.drawer,
      items: window.scool_menu
    }
  },
  props: {
    drawer: {
      Type: Boolean,
      default: false
    }
  },
  watch: {
    dataDrawer (drawer) {
      this.$emit('input', drawer)
    },
    drawer (drawer) {
      this.dataDrawer = drawer
    }
  },
  model: {
    prop: 'drawer',
    event: 'input'
  },
  methods: {
    setSelectedItem () {
      const currentPath = window.location.pathname
      const selected = this.items.indexOf(this.items.find(item => item.href === currentPath))
      if (this.items[selected]) this.items[selected].selected = true
    },
    selectedStyle (item) {
      if (item.selected) {
        return {
          'border-left': '5px solid #F0B429',
          'background-color': '#F0F4F8',
          'font-size': '1em'
        }
      }
    }
  },
  created () {
    this.setSelectedItem()
  }
}
</script>
