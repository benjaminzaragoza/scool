<template>
    <div>
        <canvas id="donut" ref="donut"></canvas>
    </div>
</template>

<script>
  import Chart from 'chart.js'
  import palette from 'google-palette'

  export default {
    name: 'DonutComponent',
    data () {
      return {
        internalColors: []
      }
    },
    props: {
      labels: {
        type: Array,
        required: true
      },
      title: {
        type: String,
        default: 'Donut graph'
      },
      data: {
        type: Array,
        required: true
      },
      colors: {
        type: Array,
        required: false
      }
    },
    mounted () {
      if (this.colors) {
        this.internalColors = this.colors
      } else {
        this.internalColors = palette('mpn65', this.data.length).map(hex => '#' + hex)
      }
      let ctx = this.$refs.donut.getContext('2d')
      let config = {
        type: 'doughnut',
        data: {
          datasets: [{
            data: this.data,
            backgroundColor: this.internalColors
          }],
          labels: this.labels
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          title: {
            display: true,
            text: this.title
          }
        }
      }
      new Chart(ctx, config)
    }
  }
</script>
