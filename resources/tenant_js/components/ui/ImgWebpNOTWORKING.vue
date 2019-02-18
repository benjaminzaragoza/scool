<template>
    <img :src="dataSrc" :alt="alt" :height="height">
</template>

<!--TODO-->
NOT WORKING MODERNIZR with webpack4
<!--https://github.com/peerigon/modernizr-loader/issues/44-->

<script>
import Modernizr from 'modernizr'
import helpers from '../../utils/helpers'
export default {
  name: ' ImgWebp',
  data () {
    return {
      dataSrc: null
    }
  },
  props: {
    src: {
      type: String,
      required: true
    },
    alt: {
      type: String,
      required: false
    },
    altFormat: {
      type: String,
      required: false
    },
    height: {
      type: Number,
      required: false
    }
  },
  methods: {
    alternateSrc () {
      if (this.altFormat) return helpers.changeExtension(this.src, this.altFormat)
      else return helpers.changeExtension(this.src, 'jpeg')
    }
  },
  created () {
    Modernizr.on('webp', (result) => {
      if (result) this.dataSrc = this.src
      else this.dataSrc = this.alternateSrc()
    })
  }
}
</script>
