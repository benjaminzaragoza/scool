<template>
    <div>
        <canvas id="bar" ref="bar"></canvas>
    </div>
</template>

<script>
import Chart from 'chart.js'

export default {
  name: 'BarComponent',
  props: {
    labels: {
      type: Array,
      required: true
    },
    data: {
      type: Array,
      required: true
    },
    colors: {
      type: Array,
      required: true
    },
    title: {
      type: String,
      default: 'Bar graph'
    }
  },
  mounted () {
    let ctx = this.$refs.bar.getContext('2d')
    let densityData = {
      label: this.title,
      data: this.data,
      backgroundColor: [
        '#F7464A',
        '#46BFBD'
      ]
    }
    let config = {
      type: 'bar',
      data: {
        labels: this.labels,
        datasets: [densityData]
      },
      options: {
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true
            }
          }]
        }
      }
    }
    new Chart(ctx, config)
  }
}
</script>
