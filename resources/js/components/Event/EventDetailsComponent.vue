<style lang="scss" scoped>
::v-deep #event-details-modal___BV_modal_header_ {
  display: block;
  padding: 0;
  border: none;
}
</style>
<template>
  <b-modal id="event-details-modal">
    <template v-slot:modal-header="{ close }" modal>
      <div class="modal-header text-white" :style="{backgroundColor: eventBackgroundColor}">
        <h4 class="m-0">{{title}}</h4>
        <b-button-close
          size="sm"
          class="m-0 p-0 text-white"
          style="transition: .3s"
          @click="close()"
        >&times;</b-button-close>
      </div>
    </template>

    <div class="py-2 px-3">
      <div class="font-weight-bolder">Event period</div>
      <div>{{ eventTime }}</div>
    </div>
    <div class="py-2 px-3" v-if="description != ''">
      <div class="font-weight-bolder">Description</div>
      <div>{{ description }}</div>
    </div>
    <div class="py-2 px-3" v-if="expenses != null">
      <div class="font-weight-bolder">Connected expenses</div>
      <ul>
        <li
          v-for="expense of expenses"
          :key="expense.id"
        >{{ expense.name }} - {{ Number(expense.amount).toLocaleString() }} {{ expense.currency }}</li>
      </ul>
    </div>

    <template v-slot:modal-footer="{ cancel }">
      <b-button variant="outline-info" @click="showEditForm()">Edit</b-button>
      <b-button variant="outline-danger" @click="destroy()">Delete</b-button>
      <b-button variant="outline-secondary" @click="cancel()">Close</b-button>
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
import eventDataService from "../../services/event-data-service";
import toastOptions from "../../services/toast-options";
import DateTimeConverter from "../../services/date-time-converter";

export default {
  data() {
    return {
      showOverlay: false,
    };
  },
  methods: {
    showEditForm: function () {
      this.$bvModal.show("event-form-modal");
    },
    destroy: function () {
      this.showOverlay = true;
      eventDataService.delete(this.event.id).then((response) => {
        if (response.data.id == this.event.id) {
          this.$emit("deleteEvent", this.event);
          this.$bvModal.hide("event-details-modal");
          this.$bvToast.toast(
            "Event was deleted successfully!",
            toastOptions()
          );
        } else {
          this.$bvToast.toast("Something went wrong", toastOptions("danger"));
        }
        this.showOverlay = false;
      });
    },
  },
  computed: {
    expenses: function () {
      return this.event != null && this.event.extendedProps.expenses
        ? this.event.extendedProps.expenses
        : null;
    },
    eventBackgroundColor: function () {
      return this.event != null ? this.event.backgroundColor : "transparent";
    },
    title: function () {
      return this.event != null ? this.event.title : "";
    },
    description: function () {
      return this.event != null && this.event.extendedProps.description != null
        ? this.event.extendedProps.description
        : "";
    },
    eventTime: function () {
      return DateTimeConverter.eventTime(this.event);
    },
  },
  props: ["event"],
};
</script>