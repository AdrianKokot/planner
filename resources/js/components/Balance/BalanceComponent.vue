<style lang="scss" scoped>
.badge {
  font-size: .8rem;
  display: inline;
  padding-right: .5rem;
  padding-left: .5rem;
}
</style>
<template>
  <div>
    <b-table
      hover
      responsive
      :selectable="true"
      select-mode="single"
      :busy="loadingData"
      :items="balance"
      :fields="fields"
      :per-page="perPage"
      :current-page="currentPage"
      @row-clicked="showDetails"
      tbody-tr-class="text-truncate"
      sort-icon-left
    >
      <template
        v-slot:cell(created_at)="data"
      >{{ (new Date(data.value)).toLocaleString('pl', {day: '2-digit', month: '2-digit', year:'numeric', hour: '2-digit', minute:'2-digit'}) }}</template>

      <template v-slot:cell(amount)="data">
        <span :class="data.item.transfer_type_name == 'income' ? 'text-success' : 'text-danger'">
          <span>{{ data.item.transfer_type_name == 'income' ? '+' : '-'}}</span>
          {{ parseFloat(data.value).toLocaleString('pl') }} PLN
        </span>
      </template>

      <template v-slot:cell(transfer_category_name)="data">
        <span class="badge text-white" :style="{backgroundColor: data.item.transfer_category_color}">{{ data.value }}</span>
      </template>

      <template v-slot:table-busy>
        <div class="text-center text-primary my-2">
          <b-spinner class="align-middle"></b-spinner>
        </div>
      </template>

      <template v-slot:row-details="transaction">
        <pre>
          {{transaction.item}}
        </pre>
      </template>
    </b-table>
    <b-pagination
      v-model="currentPage"
      :total-rows="rows"
      :per-page="perPage"
      aria-controls
      first-number
      last-number
    ></b-pagination>
  </div>
</template>
<script>
import toastOptions from "../../services/toast-options";
import balanceDataService from "../../services/balance-service";

export default {
  data() {
    return {
      loadingData: false,
      fields: [
        {
          key: "created_at",
          sortable: true,
          label: "Date",
        },
        {
          key: "name",
          sortable: true,
          label: "Name",
        },
        {
          key: "amount",
          sortable: true,
          label: "Amount",
        },
        {
          key: "transfer_category_name",
          sortable: true,
          label: "Category",
        },
      ],
      balance: [],
      perPage: 10,
      currentPage: 1,
    };
  },
  methods: {
    showDetails(row) {
      row._showDetails = !row._showDetails;
    },
    destroy(id) {
      this.loadingData = true;
      balanceDataService.delete(id).then((response) => {
        if (response.data.id == id) {
          this.balance.splice(
            this.balance.findIndex((x) => x.id == id),
            1
          );
          this.$bvToast.toast(
            "Transaction was deleted successfully!",
            toastOptions()
          );
        } else {
          this.$bvToast.toast("Something went wrong", toastOptions("danger"));
        }
        this.loadingData = false;
      });
    },
    updateTransaction(transaction) {
      this.balance.splice(
        this.balance.findIndex((x) => x.id == transaction.id),
        1,
        transaction
      );
    },
    createTransaction(transaction) {
      this.balance.push(transaction);
    },
  },
  mounted: function () {
    this.loadingData = true;

    balanceDataService.getAll().then((response) => {
      console.log(response.data);
      this.balance = response.data;
      this.loadingData = false;
    });
  },
  computed: {
    rows() {
      return this.balance.length;
    },
    canCreateIncome() {
      return this.$can("user_income.create");
    },
    canCreateExpense() {
      return this.$can("user_expense.create");
    },
    canUpdateIncome() {
      return this.$can("user_income.update");
    },
    canUpdateExpense() {
      return this.$can("user_expense.update");
    },
    canDeleteIncome() {
      return this.$can("user_income.delete");
    },
    canDeleteExpense() {
      return this.$can("user_expense.delete");
    },
  },
};
</script>
