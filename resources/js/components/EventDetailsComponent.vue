<style lang="scss" scoped>
::v-deep #event-details-modal___BV_modal_header_ {
  display: block;
  padding: 0;
  border: none;
}
</style>
<template>
  <b-modal id="event-details-modal" :header-class="null">
    <template v-slot:modal-header="{ close }" modal>
      <div class="modal-header" :style="{backgroundColor: eventBackgroundColor, color: eventTextColor}">
        <h5 class="m-0">{{title}}</h5>
        <b-button-close size="sm" class="m-0 p-0" :style="{ color: eventTextColor, transition: '.3s'}" @click="close()">
          &times;
        </b-button-close>
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
        <li v-for="expense of expenses" :key="expense.id">{{ expense.name }} - {{ Number(expense.amount).toLocaleString() }} {{ expense.currency }}</li>
      </ul>
    </div>

    <template v-slot:modal-footer="{ cancel }">
      <!-- <b-button size="sm" variant="success" @click="ok()">
        OK
      </b-button> -->
      <b-button variant="outline-secondary" @click="cancel()">
        Close
      </b-button>
      <!-- <b-button size="sm" variant="outline-secondary" @click="hide('edit')">
        Forget it
      </b-button> -->
    </template>
  </b-modal>
</template>
<script>
export default {
  data() {
    return {
      timeStringLocale: 'en-UK',

      localeTimeStringOptions: ['en-UK', {timeZone: 'Europe/Berlin', hour12: true, hour: '2-digit', minute: '2-digit'}]
    };
  },
  methods: {
    // handleDateClick: function (arg) {
    //   alert("date click! " + arg.dateStr);
    // },
    convertToTimeString: (date) => (new Date(date)).toLocaleTimeString('en-UK', {timeZone: 'Europe/Berlin', hour12: false, hour: '2-digit', minute: '2-digit'}),
    convertToDateString: (date) => (new Date(date)).toLocaleDateString('eu', {timeZone: 'Europe/Berlin'})
  },
  computed: {
    expenses: function() { return this.event != null && this.event.extendedProps.expenses ? this.event.extendedProps.expenses : null; },
    eventTextColor: function() { return this.event != null ? this.event.textColor : 'black'},
    eventBackgroundColor: function() { return this.event != null ? this.event.backgroundColor : 'transparent'},
    title: function() { return this.event != null ? this.event.title : ''; },
    description: function() { return this.event != null && this.event.extendedProps.description != null ? this.event.extendedProps.description : '' },
    eventTime: function() {
      if (this.event != null) {
        const timesAndDates = {
          timeStart: this.convertToTimeString(this.event.start),
          timeEnd: this.convertToTimeString(this.event.end),
          dateStart: this.convertToDateString(this.event.start),
          dateEnd: this.convertToDateString(this.event.end)
        }

        if (timesAndDates.dateStart != timesAndDates.dateEnd) {
          return timesAndDates.timeStart
                  + ' '
                  + timesAndDates.dateStart
                  + ' - '
                  + timesAndDates.timeEnd
                  + ' '
                  + timesAndDates.dateEnd;
        } else {
          return timesAndDates.timeStart
                  + ' - '
                  + timesAndDates.timeEnd
                  + ', '
                  + timesAndDates.dateStart;
        }
      }
      return '';
    }
  },
  props: [
    'event'
  ]
};
</script>
