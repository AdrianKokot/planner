<template>
  <div>
    <b-table
      id="logs-table"
      striped
      hover
      responsive
      selectable
      select-mode="single"
      :busy="loadingData"
      :items="logs"
      :fields="fields"
      :per-page="perPage"
      :current-page="currentPage"
      @row-clicked="showDetails"
    >
      <template v-slot:table-busy>
        <div class="text-center text-primary my-2">
          <b-spinner class="align-middle"></b-spinner>
        </div>
      </template>

      <template
        v-slot:cell(log_at)="data"
      >{{ (new Date(data.value)).toLocaleString('pl', {day: '2-digit', month: '2-digit', year:'numeric', hour: '2-digit', minute:'2-digit'}) }}</template>

      <template v-slot:cell(log_title)="data">
        <span v-html="data.value"></span>
      </template>

      <template v-slot:cell(user_name)="data">
        <span v-html="data.value"></span>
      </template>

      <template v-slot:row-details="event">
        <div class="py-2 px-3" v-if="event.item.event_end == null">
          <div class="font-weight-bolder">Event was deleted.</div>
        </div>
        <div v-else>
          <div class="py-2 px-3">
            <div class="font-weight-bolder">Event period</div>
            <div>{{ eventTime({start: event.item.event_start, end: event.item.event_end}) }}</div>
          </div>
          <div class="py-2 px-3" v-if="event.item.event_description != ''">
            <div class="font-weight-bolder">Description</div>
            <div>{{ event.item.event_description }}</div>
          </div>
        </div>
        <div class="py-2 px-3" v-if="event.item.log_description!=''">
          <div class="font-weight-bolder">Log details</div>
          <div v-html="event.item.log_description"></div>
        </div>
      </template>
    </b-table>
    <b-pagination
      v-model="currentPage"
      :total-rows="rows"
      :per-page="perPage"
      aria-controls="logs-table"
      first-number
      last-number
    ></b-pagination>
  </div>
</template>
<script>
import logDataService from "../services/log-data-service";
import DateTimeConverter from "../services/date-time-converter";

export default {
  data() {
    return {
      loadingData: false,
      fields: [
        {
          key: "log_at",
          sortable: true,
          label: "Log date",
        },
        {
          key: "log_title",
          sortable: true,
          label: "Log title",
        },
        {
          key: "user_name",
          sortable: true,
          label: "User",
        },
      ],
      logs: [],
      perPage: 50,
      currentPage: 1,
    };
  },
  methods: {
    eventTime(event) {
      return DateTimeConverter.eventTime(event);
    },
    showDetails(row) {
      row._showDetails = !row._showDetails;
    },
  },
  mounted: function () {
    this.loadingData = true;
    logDataService.getAll().then((response) => {
      this.logs = response.data;
      this.loadingData = false;
    });
  },
  computed: {
    rows() {
      return this.logs.length;
    },
  },
};
</script>
