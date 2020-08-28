<template>
  <b-modal
    id="transaction-form-modal"
    @show="onShow()"
    @hidden="onHide()"
    :title="isCreateForm ? 'New transaction' : 'Edit transaction'"
    scrollable
  >
    <b-form @submit.prevent="onSubmit">
      <b-form-group label="Name" label-for="name-input">
        <b-input
          id="name-input"
          type="text"
          v-model="$v.name.$model"
          :state="$v.name.$dirty ? !$v.name.$error : null"
        ></b-input>
        <b-form-invalid-feedback v-if="!$v.name.required">Name is required.</b-form-invalid-feedback>
        <b-form-invalid-feedback
          v-if="!$v.name.maxLength"
        >Name can be max {{$v.name.$params.maxLength.max}} characters long.</b-form-invalid-feedback>
      </b-form-group>

      <b-form-group label="Transaction type" label-for="type-input">
        <b-form-select
          id="type-input"
          v-model="$v.type.$model"
          :options="types"
          :state="$v.type.$dirty ? !$v.type.$error : null"
          value-field="id"
          text-field="name"
        ></b-form-select>
        <b-form-invalid-feedback v-if="!$v.type.required">Type is required.</b-form-invalid-feedback>
      </b-form-group>

      <b-form-group label="Amount" label-for="amount-input">
        <b-input
          id="amount-input"
          type="number"
          v-model="$v.amount.$model"
          :state="$v.amount.$dirty ? !$v.amount.$error : null"
        ></b-input>
        <b-form-invalid-feedback v-if="!$v.amount.required">Amount is required.</b-form-invalid-feedback>
        <b-form-invalid-feedback v-if="!$v.amount.minValue">Amount must be greater than 0.</b-form-invalid-feedback>
      </b-form-group>

      <b-form-group label="Date" label-for="date-input">
        <b-input
          id="date-input"
          type="date"
          v-model="$v.date.$model"
          :state="$v.date.$dirty ? !$v.date.$error : null"
        ></b-input>
        <b-form-invalid-feedback v-if="!$v.date.required">Date is required.</b-form-invalid-feedback>
      </b-form-group>

      <b-form-group
        label="Category"
        label-for="category-input"
        v-if="isIncome"
      >
        <b-form-select
          id="category-input"
          v-model="$v.category.$model"
          :options="categories"
          :state="$v.category.$dirty ? !$v.category.$error : null"
          value-field="id"
          text-field="name"
        ></b-form-select>
        <b-form-invalid-feedback v-if="!$v.category.required">Category is required.</b-form-invalid-feedback>
      </b-form-group>

      <b-form-group
        label="Pin to event"
        label-for="event-input"
        v-if="isIncome"
        description="Event is optional."
      >
        <b-form-select
          id="event-input"
          v-model="$v.event.$model"
          :state="$v.event.$dirty ? !$v.event.$error : null"
        >
          <b-form-select-option :value="event.id" v-for="event in events" :key="event.id">
            <strong>{{event.title}}</strong>
            - {{eventTime(event)}}
          </b-form-select-option>
        </b-form-select>
      </b-form-group>
    </b-form>

    <template v-slot:modal-footer="{ cancel }">
      <b-button
        variant="outline-info"
        @click.prevent="submit()"
        :disabled="$v.$invalid"
      >{{isCreateForm ? 'Create' : 'Save'}}</b-button>
      <b-button variant="outline-secondary" @click="cancel()">Cancel</b-button>
    </template>
    <b-overlay
      no-wrap
      :show="showOverlay"
      :variant="'light'"
      spinner-variant="primary"
      :opacity=".85"
      :blur="'2px'"
    ></b-overlay>
  </b-modal>
</template>
<script>
import { validationMixin } from "vuelidate";
import { required, maxLength, requiredIf, minValue } from "vuelidate/lib/validators";
import eventDataService from "../../services/event-data-service";
import { toastOptions } from "../../services/toast-options";
import balanceDataService from "../../services/balance-data-service";
import transactionTypeDataService from "../../services/transaction-type-data-service";
import transactionCategoryDataService from "../../services/transaction-category-data-service";
import DateTimeConverter from "../../services/date-time-converter";

export default {
  mixins: [validationMixin],
  props: ["transaction"],
  data() {
    return {
      showOverlay: false,
      isCreateForm: false,
      events: [],
      types: [],
      categories: [],
      name: new Date().toISOString().split("T")[0],
      date: null,
      event: null,
      type: null,
      amount: 0,
      category: null,
    };
  },
  methods: {
    eventTime: function (event) {
      return DateTimeConverter.eventTime(event);
    },
    onShow: function () {
      this.showOverlay = true;
      setTimeout(() => {
        if (this.transaction != null) {
          this.isCreateForm = false;
          this.name = this.transaction.name;
          this.type = this.transaction.transfer_type_id;
          this.category = this.transaction.transfer_category_id;
          this.amount = this.transaction.amount;
          this.date = this.transaction.created_at.split(' ')[0];
          this.event = this.transaction.event_id;
        } else {
          this.isCreateForm = true;
          this.onHide();
        }
        this.showOverlay = false;
      }, 10);
    },
    onHide: function () {
      this.name = "";
      this.date = new Date().toISOString().split("T")[0];
      this.event = null;
      this.type = null;
      this.amount = 0;
      this.category = null;
      this.$v.$reset();
    },
    submit: function () {
      this.onSubmit();
    },
    onSubmit: function () {
      this.$v.$touch();
      if (!this.$v.$invalid) {
        const body = {
          id: this.isCreateForm ? null : this.transaction.id,
          event_id: this.event,
          name: this.name,
          created_at: this.date,
          transfer_type_id: this.type,
          amount: this.amount,
          transfer_category_id: this.category,
        };

        this.showOverlay = true;
        if (this.isCreateForm) {
          balanceDataService.create(body).then((response) => {
            if (response.status == 200) {
              this.$emit("createTransaction", response.data);

              this.$bvModal.hide("transaction-form-modal");

              this.$bvToast.toast(
                "Transaction was created successfully!",
                toastOptions()
              );
            } else {
              this.$bvToast.toast(
                "Something went wrong",
                toastOptions("danger")
              );
            }
            this.showOverlay = false;
          });
        } else {
          balanceDataService.update(body.id, body).then((response) => {
            if (response.data.id == this.transaction.id) {
              this.$emit("updateTransaction", response.data);
              this.$bvModal.hide("transaction-form-modal");
              this.$bvToast.toast(
                "Transaction was updated successfully!",
                toastOptions()
              );
            } else {
              this.$bvToast.toast(
                "Something went wrong",
                toastOptions("danger")
              );
              this.showOverlay = false;
            }
          });
        }
      }
    },
  },
  validations: {
    name: {
      required,
      maxLength: maxLength(255),
    },
    type: {
      required,
    },
    category: {
      requiredIf: requiredIf(function () {
        return this.isIncome;
      }),
    },
    date: {
      required,
    },
    amount: {
      required,
      minValue: minValue(0.01)
    },
    event: {},
  },
  mounted: function () {
    eventDataService.getAll().then((res) => {
      this.events = res.data;
    });
    transactionCategoryDataService.getAll().then((res) => {
      this.categories = res.data;
    });
    transactionTypeDataService.getAll().then((res) => {
      this.types = res.data;
    });
  },
  computed: {
    isIncome: function() {
      return this.type != null && this.type != this.types.find(x => x.name == 'income').id;
    }
  }
};
</script>
