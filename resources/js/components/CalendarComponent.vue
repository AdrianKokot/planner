<template>
  <section class="px-4 pt-3 pb-5">
    <FullCalendar :options="calendarOptions" />
  </section>
</template>
<script>
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import listPlugin from '@fullcalendar/list';
import interactionPlugin from "@fullcalendar/interaction";

export default {
  components: {
    FullCalendar, // make the <FullCalendar> tag available
  },
  data() {
    return {
      calendarOptions: {
        plugins: [dayGridPlugin, interactionPlugin, listPlugin],
        initialView: "dayGridMonth",
        dayMaxEvents: true,
        dateClick: this.handleDateClick,
        eventDisplay: 'block',
        // displayEventTime: true,
        // displayEventEnd: false,
        eventTimeFormat: {
          hour: 'numeric',
          minute: '2-digit',
          meridiem: 'short'
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
    handleDateClick: function (arg) {
      alert("date click! " + arg.dateStr);
    },
  },
};
</script>
