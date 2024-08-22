<template>
  <canvas ref="chart"></canvas>
</template>

<script>
import Chart from "chart.js/auto";

export default {
  props: ["records"],
  mounted() {
    this.chartContext = new Chart(this.$refs.chart, this.config);
  },
  watch: {
    records: {
      handler(newValue, oldValue) {
        // Note: `newValue` will be equal to `oldValue` here
        // on nested mutations as long as the object itself
        // hasn't been replaced.
        this.chartContext.update();
      },
      deep: true
    }
  },
  data() {
    return {
      chartContext : null,
      config: {
        type: "line",
        data: {
          labels: this.records.labels,
          datasets: [
            {
              label: "Income",
              backgroundColor: "rgb(255, 99, 132)",
              borderColor: "rgb(255, 99, 132)",
              data: this.records.data,
            },
          ],
        },
        options: {
          plugins: {
            title: {
              display: true,
              text: "Last 5 months",
            },
            tooltip: {
              mode: "index",
              intersect: false,
              callbacks: {
                label: function (context) {
                  let label = context.dataset.label || "";

                  if (label) {
                    label += ": ";
                  }
                  if (context.parsed.y !== null) {
                    label += accounting.formatMoney(
                      context.parsed.y,
                      currencyConfig.number
                    );
                  }
                  return label;
                },
              },
            },
          },
          responsive: true,

          hover: {
            mode: "nearest",
            intersect: true,
          },
          scales: {
            x: {
              title: {
                display: true,
                text: "Months",
              },
            },
            y: {
              title: {
                display: true,
                text: "Profit",
              },
              ticks: {
                // Include a dollar sign in the ticks
                callback: function (value, index, ticks) {
                  return accounting.formatMoney(value, currencyConfig.number);
                },
              },
            },
          },
        },
      },
    };
  },
};
</script>