<script>
import { Bar, mixins } from 'vue-chartjs'
const { reactiveProp } = mixins

export default {
  props: ['categories', 'transactions'],
  // mixins: [reactiveProp],
  extends: Bar,
  data: () => ({
    chartdata: {
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
      datasets: []
    },
    options: {
      responsive: true,
      maintainAspectRatio: false
    }
  }),

  mounted () {
    let trs = this.categories.map(x => x.name).reduce((item, value) => {
      item.push({label: value, data: [0,0,0,0,0,0,0,0,0,0,0,0], backgroundColor: ''});
      return item;
    }, []);

    const style = getComputedStyle(document.body);

    trs = this.transactions.filter(x=>new Date(x.created_at).getFullYear() === new Date().getFullYear()).map(x => { return {category: x.transfer_category_name, color: x.transfer_category_color, data: x.amount, month: new Date(x.created_at).getMonth()} }).reduce((item, value) => {

      let idx = item.findIndex(x=>x.label == value.category);

      item[idx].backgroundColor = style.getPropertyValue(value.color.slice(4,-1));
      item[idx].data[value.month] += parseFloat(value.data);

      return item;
    }, trs).filter(x => !x.data.every(x => x == 0));

    this.chartdata.datasets = trs;
    this.renderChart(this.chartdata, this.options)
  }
}
</script>

<style>
</style>
