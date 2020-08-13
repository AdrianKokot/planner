<template>
  <b-overlay
    class="px-4 pt-3 pb-5"
    id="overlay-background"
    :show="loadingEvents"
    :variant="'light'"
    spinner-variant="primary"
    :opacity=".85"
    :blur="'2px'"
  >
    <FullCalendar :options="calendarOptions" />
    <event-details-component :event="selectedEvent"></event-details-component>
    <edit-event-component :event="selectedEvent"></edit-event-component>
  </b-overlay>
</template>
<script>
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import listPlugin from "@fullcalendar/list";
import interactionPlugin from "@fullcalendar/interaction";
import eventDataService from "../services/event-data-service";

export default {
  components: {
    FullCalendar,
  },
  data() {
    return {
      loadingEvents: false,
      selectedEvent: null,
      selectedDate: null,
      calendarOptions: {
        loading: this.handleLoading,
        plugins: [dayGridPlugin, interactionPlugin, listPlugin],
        initialView: "dayGridMonth",
        dayMaxEvents: true,
        eventClick: this.handleEventClick,
        dateClick: this.handleDateClick,
        eventDisplay: "block",
        eventTimeFormat: {
          hour: "2-digit",
          minute: "2-digit",
          hour12: false,
        },
        headerToolbar: {
          left: "today",
          center: "title",
          right: "prev,next",
        },
        footerToolbar: {
          center: "dayGridMonth,dayGridWeek,dayGridDay,listWeek",
        },
        firstDay: 1,
        selectable: true,
        eventBackgroundColor: "var(--primary)",
        eventBorderColor: "var(--primary)",
        eventTextColor: "white",
        eventSources: [
          function (fetchInfo, successCallback, failureCallback) {
            eventDataService.getAll(fetchInfo.startStr, fetchInfo.endStr).then((response) => {
              successCallback(response.data);
            });
          },
        ],
      },
    };
  },
  methods: {
    handleDateClick: function (dateClickInfo) {
      // this.selectedDate = dateClickInfo.date;
      // this.$bvModal.show('new-event-modal');
    },
    handleEventClick: function (eventClickInfo) {
      this.selectedEvent = eventClickInfo.event;
      this.$bvModal.show("event-details-modal");
    },
    handleLoading: function(isLoading) {
      this.loadingEvents = isLoading;
    }
  },
};
</script>
