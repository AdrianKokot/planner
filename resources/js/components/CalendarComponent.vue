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
    <FullCalendar ref="fullCalendar" :options="calendarOptions" />
    <event-details-component :event="selectedEvent" @deleteEvent="deleteEvent"></event-details-component>
    <event-form-component v-if="canCreate || canUpdate" :date="selectedDate" @createEvent="createEvent" :event="selectedEvent" @updateEvent="updateEvent"></event-form-component>
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
      loadingData: false,
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
            eventDataService.getAll({start: fetchInfo.startStr, end: fetchInfo.endStr}).then((response) => {
              successCallback(response.data);
            });
          },
        ],
      },
    };
  },
  methods: {
    updateEvent: function(event) {
      const calendarEvent = this.$refs.fullCalendar.getApi().getEventById(event.id);
      calendarEvent.setStart(event.start);
      calendarEvent.setEnd(event.end);
      calendarEvent.setProp('title', event.title);
      calendarEvent.setProp('backgroundColor', event.color);
      calendarEvent.setProp('borderColor', event.color);
      calendarEvent.setExtendedProp('description', event.description);
      this.selectedEvent = calendarEvent;
    },
    deleteEvent: function(event) {
      const calendarEvent = this.$refs.fullCalendar.getApi().getEventById(event.id);
      calendarEvent.remove();
      this.selectedEvent = null;
    },
    createEvent: function(event) {
      this.$refs.fullCalendar.getApi().addEvent(event);
      this.selectedEvent = null;
    },
    handleDateClick: function (dateClickInfo) {
      this.selectedEvent = null;
      this.selectedDate = new Date(dateClickInfo.date);
      this.$bvModal.show('event-form-modal');
    },
    handleEventClick: function (eventClickInfo) {
      this.selectedEvent = eventClickInfo.event;
      this.$bvModal.show("event-details-modal");
    },
    handleLoading: function(isLoading) {
      this.loadingData = isLoading;
    }
  },
  computed: {
    canCreate() {
      return this.$can("user_event.create");
    },
    canUpdate() {
      return this.$can("user_event.update");
    },
    canDelete() {
      return this.$can("user_event.delete");
    },
  }
};
</script>
