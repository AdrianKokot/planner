<template>
  <section class="px-4 pt-3 pb-5">
    <FullCalendar :options="calendarOptions" />
    <event-details-component :event="selectedEvent"></event-details-component>
  </section>
</template>
<script>
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import listPlugin from '@fullcalendar/list';
import interactionPlugin from "@fullcalendar/interaction";

export default {
  components: {
    FullCalendar
  },
  data() {
    return {
      selectedEvent: null,
      selectedDate: null,
      calendarOptions: {
        plugins: [dayGridPlugin, interactionPlugin, listPlugin],
        initialView: "dayGridMonth",
        dayMaxEvents: true,
        eventClick: this.handleEventClick,
        dateClick: this.handleDateClick,
        eventDisplay: 'block',
        eventTimeFormat: {
          hour: '2-digit',
          minute: '2-digit',
          hour12: false
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
            console.log(fetchInfo);
            axios.get("http://localhost:8000/api/events").then((response) => {
              successCallback(response.data);
            });
          },
        ],
      },
    };
  },
  methods: {
    handleDateClick: function (dateClickInfo ) {
      // this.selectedDate = dateClickInfo.date;
      // this.$bvModal.show('new-event-modal');
    },
    handleEventClick: function (eventClickInfo) {
      this.selectedEvent = eventClickInfo.event;
      this.$bvModal.show('event-details-modal');
    }
  },
};
</script>
