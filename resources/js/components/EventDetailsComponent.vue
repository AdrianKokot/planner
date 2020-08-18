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
  </b-modal>
</template>
<script>
import eventDataService from "../services/event-data-service";

export default {
  methods: {
    showEditForm: function () {
      this.$bvModal.show("edit-event-modal");
    },
    destroy: function () {
      eventDataService.delete(this.event.id).then((response) => {
        if (response.data.id == this.event.id) {
          this.$emit("deleteEvent", this.event);
          this.$bvModal.hide("event-details-modal");
        }
        // TODO success / failure toast
      });
    },
    convertToTimeString: (date) =>
      new Date(date).toLocaleTimeString("en-UK", {
        timeZone: "Europe/Berlin",
        hour12: false,
        hour: "2-digit",
        minute: "2-digit",
      }),
    convertToDateString: (date) =>
      new Date(date).toLocaleDateString("pl", { timeZone: "Europe/Berlin" }),
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
      if (this.event != null) {
        const tdObj = {
          timeStart: this.convertToTimeString(this.event.start),
          timeEnd: this.convertToTimeString(this.event.end),
          dateStart: this.convertToDateString(this.event.start),
          dateEnd: this.convertToDateString(this.event.end),
        };

        return tdObj.dateStart != tdObj.dateEnd
          ? `${tdObj.timeStart} ${tdObj.dateStart} - ${tdObj.timeEnd} ${tdObj.dateEnd}`
          : `${tdObj.timeStart} - ${tdObj.timeEnd}, ${tdObj.dateStart}`;
      }
      return "";
    },
  },
  props: ["event"],
};
</script>
