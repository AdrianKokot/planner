<style lang="scss" scoped>
.badge {
  font-size: 0.8rem;
  display: inline;
  padding-right: 0.5rem;
  padding-left: 0.5rem;
}
</style>
<template>
  <div>
    <div class="d-flex flex-column flex-sm-row">
      <b-button
        variant="outline-primary"
        class="mb-3"
        @click="showCreateForm"
        v-if="canCreateExpense || canCreateIncome"
      >New transaction</b-button>
      <b-form-select
        v-model="timePeriod"
        class="ml-sm-2 bg-transparent w-auto mb-3 text-primary border-primary"
        :options="timePeriodOptions"
        @change="onPeriodChange"
      ></b-form-select>
      <b-pagination
        class="mr-sm-0 mx-auto"
        v-model="currentPage"
        :total-rows="rows"
        :per-page="perPage"
        aria-controls="balance-table"
        first-number
        last-number
      ></b-pagination>
    </div>
    <b-table
      id="balance-table"
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
      class="mb-0"
      sort-icon-left
    >
      <template
        v-slot:cell(created_at)="data"
      >{{ dateString(data.value) }}</template>

      <template v-slot:cell(amount)="data">
        <span :class="data.item.transfer_type_name == 'income' ? 'text-success' : 'text-danger'">
          <span>{{ data.item.transfer_type_name == 'income' ? '+' : '-'}}</span>{{ parseFloat(data.value).toLocaleString('pl') }} PLN
        </span>
      </template>

      <template v-slot:cell(transfer_category_name)="data">
        <span
          class="badge text-white"
          :style="{backgroundColor: data.item.transfer_category_color}"
        >{{ data.value }}</span>
      </template>

      <template v-slot:table-busy>
        <div class="text-center text-primary my-2">
          <b-spinner class="align-middle"></b-spinner>
        </div>
      </template>

      <template v-slot:row-details="transaction">
        <div>
          <div class="py-2 px-3">
            <div class="font-weight-bolder">Date</div>
            <div>{{ dateString(transaction.item.created_at) }}</div>
          </div>
          <div class="py-2 px-3">
            <div class="font-weight-bolder">Name</div>
            <div>{{ transaction.item.name }}</div>
          </div>
          <div class="py-2 px-3">
            <div class="font-weight-bolder">Amount</div>
            <div>{{ parseFloat(transaction.item.amount).toLocaleString('pl') }} PLN</div>
          </div>
          <div class="py-2 px-3" v-if="transaction.item.event_title != null">
            <div class="font-weight-bolder">Connected Event</div>
            <div><strong>{{ transaction.item.event_title }}</strong></div>
          </div>
        </div>
        <div class="d-flex justify-content-end">
          <b-button
            variant="outline-info"
            class="mr-2"
            @click="showEditForm(transaction.item)"
            v-if="transaction.item.transaction_category_name == 'income' ? canUpdateIncome : canUpdateExpense"
          >Edit</b-button>
          <b-button
            variant="outline-danger"
            @click="deleteTransaction(transaction.item.id)"
            v-if="transaction.item.transaction_category_name == 'income' ? canDeleteIncome : canDeleteExpense"
          >Delete</b-button>
        </div>
      </template>
    </b-table>
    <div class="border-top text-center py-3" v-if="currBalance !=null">
      <span class="mr-1 font-weight-bolder">Current balance: </span>
      <span :class="{'text-danger': currBalance < 0, 'text-success': currBalance > 0}">{{ parseFloat(currBalance).toLocaleString('pl') }} PLN</span>
    </div>
    <h5 class="mt-5 text-center" v-if="loadedChartData && !loadingData">Last year expenses statistic</h5>
    <expenses-chart-component v-if="loadedChartData && !loadingData" :transactions="balance" :categories="categories"></expenses-chart-component>
    <transaction-form-component
      :transaction="selectedTransaction"
      @createTransaction="createTransaction"
      @updateTransaction="updateTransaction"
    ></transaction-form-component>
  </div>
</template>
<script>
import { toastOptions, msgBoxOptions } from "../../services/toast-options";
import balanceDataService from "../../services/balance-data-service";
import DateTimeConverter from '../../services/date-time-converter';
import transactionCategoryDataService from '../../services/transaction-category-data-service';

export default {
  data() {
    return {
      selectedTransaction: null,
      loadingData: true,
      timePeriodOptions: [
        { value: 1, text: "last month" },
        { value: 6, text: "last half year" },
        { value: 12, text: "last year" },
        { value: 70, text: "last 5 years" },
      ],
      timePeriod: 12,
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
      currBalance: null,
      perPage: 10,
      currentPage: 1,
      categories: [],
      loadedChartData: false
    };
  },
  methods: {
    dateString(date) {
      return DateTimeConverter.convertToDateString(date);
    },
    showEditForm(transaction) {
      this.selectedTransaction = transaction;
      this.$bvModal.show("transaction-form-modal");
    },
    showCreateForm() {
      this.selectedTransaction = null;
      this.$bvModal.show("transaction-form-modal");
    },
    loadBalance() {
      this.currBalance = null;
      balanceDataService.getAll({only: 'balance'}).then(res => {
        this.currBalance = res.data;
      })
    },
    onPeriodChange() {
      this.loadingData = true;
      const date = new Date();
      balanceDataService
        .getAll({
          start: new Date(
            date.setMonth(date.getMonth() - this.timePeriod)
          ).toISOString(),
        })
        .then((response) => {
          console.log(response.data);
          this.balance = response.data;
          this.loadingData = false;
        });
    },
    showDetails(row) {
      row._showDetails = !row._showDetails;
    },
    deleteTransaction(id) {
      this.$bvModal
        .msgBoxConfirm(
          "Are you sure you want to delete this transaction?",
          msgBoxOptions()
        )
        .then((value) => {
          if (value == true) {
            this.loadingData = true;
            balanceDataService.delete(id).then((response) => {
              if (response.data.id == id) {
                this.loadBalance();
                this.balance.splice(
                  this.balance.findIndex((x) => x.id == id),
                  1
                );
                this.$bvToast.toast(
                  "Transaction was deleted successfully!",
                  toastOptions()
                );
              } else {
                this.$bvToast.toast(
                  "Something went wrong",
                  toastOptions("danger")
                );
              }
              this.loadingData = false;
            });
          }
        });
    },
    updateTransaction(transaction) {
      this.loadBalance();
      this.balance.splice(
        this.balance.findIndex((x) => x.id == transaction.id),
        1,
        transaction
      );
    },
    createTransaction(transaction) {
      this.loadBalance();
      this.balance.push(transaction);
    },
  },
  mounted: function () {
    this.onPeriodChange();
    transactionCategoryDataService.getAll().then(res => {
      this.categories = res.data;
      this.loadedChartData = true;
    });
    this.loadBalance();
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
