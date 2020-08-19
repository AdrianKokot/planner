<template>
  <b-overlay
    class="px-4 pt-3 pb-5"
    id="overlay-background"
    :show="loadingData"
    :variant="'light'"
    spinner-variant="primary"
    :opacity=".85"
    :blur="'2px'"
  >
    <b-table striped hover :items="logs" :fields="fields">
      <template v-slot:cell(log_at)="data">
        {{ (new Date(data.value)).toLocaleString('pl') }}
      </template>

      <template v-slot:cell(log_title)="data">
        <span v-html="data.value"></span>
      </template>

      <template v-slot:cell(user_name)="data">
        <span v-html="data.value"></span>
      </template>
    </b-table>
  </b-overlay>
</template>
<script>
import logDataService from "../services/log-data-service";

export default {
  data() {
    return {
      loadingData: false,
      fields: [
        {
          key: "log_at",
          sortable: true,
          label: 'Log date'
        },
        {
          key: "log_title",
          sortable: true,
          label: 'Log title'
        },
        {
          key: "user_name",
          sortable: true,
          label: 'User'
        }
      ],
      logs: null,
    };
  },
  methods: {},
  mounted: function () {
    this.loadingData = true;
    logDataService.getAll().then((response) => {
      this.logs = response.data;
      this.loadingData = false;
    });
  },
};
</script>
