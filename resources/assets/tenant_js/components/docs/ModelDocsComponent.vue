<template>
    <v-dialog v-model="dialog" fullscreen @keydown.esc="dialog = false">
        <v-tooltip bottom slot="activator" v-if="tooltip">
            <v-btn icon slot="activator" style="margin: 0px">
                <v-icon :color="color" :dark="dark">{{icon}}</v-icon>
            </v-btn>
            <span v-html="tooltip"></span>
        </v-tooltip>
        <v-btn icon slot="activator" v-else style="margin: 0px">
            <v-icon :color="color" :dark="dark">{{icon}}</v-icon>
        </v-btn>
        <v-card v-if="dialog">
            <v-toolbar color="blue darken-3" dark>
                <v-toolbar-side-icon></v-toolbar-side-icon>
                <v-toolbar-title v-html="title" ></v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon dark @click.native="dialog = false">
                    <v-icon>close</v-icon>
                </v-btn>
            </v-toolbar>
            <v-list two-line subheader>
                <template v-for="(collection,index) in collections">
                    <v-subheader v-html="collection"></v-subheader>
                    <v-list-tile v-for="mediaItem in filteredMedia(collection)" :key="mediaItem.id" avatar @click="">
                        <v-list-tile-avatar>
                            <v-icon>{{iconByMimeType(mediaItem.mime_type)}}</v-icon>
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title>{{ mediaItem.file_name }}</v-list-tile-title>
                            <v-list-tile-sub-title>{{ filesize(mediaItem.size) }}</v-list-tile-sub-title>
                        </v-list-tile-content>
                        <v-list-tile-action>
                            <v-btn icon ripple :href="'/media/' + mediaItem.id + '/download'">
                                <v-icon color="green" title="Baixar" >file_download</v-icon>
                            </v-btn>
                        </v-list-tile-action>
                    </v-list-tile>

                    <v-divider v-if="index != collections.length - 1"></v-divider>
                </template>
            </v-list>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click.native="dialog = false" v-html="cancel"></v-btn>
                <v-btn color="success">Pujar fitxer TODO</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
  import filesize from 'filesize'

  export default {
    name: 'ModelDocsComponent',
    data () {
      return {
        dialog: false,
        internalMedia: this.media
      }
    },
    props: {
      media: {
        type: Array,
        default: []
      },
      icon: {
        type: String,
        default: 'attach_file'
      },
      color: {
        type: String,
        default: 'primary'
      },
      dark: {
        type: Boolean,
        default: false
      },
      tooltip: {
        type: String,
        default: 'Fitxers associats'
      },
      title: {
        type: String,
        default: 'Fitxers associats'
      },
      cancel: {
        type: String,
        default: 'Sortir'
      }
    },
    computed: {
      collections () {
        return [...new Set(this.media.map(x => x.collection_name))]
      }
    },
    methods: {
      filteredMedia (collection) {
        return this.media.filter(x => x.collection_name === collection);
      },
      iconByMimeType (mimeType) {
        if (mimeType.startsWith('image')) {
          return 'image'
        }
        // TODO other mime types to icons
        return 'cancel'
      },
      filesize (size) {
        return filesize(size)
      }
    }
  }
</script>
