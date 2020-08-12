<template>
  <b-modal id="edit-event-modal" title="Edit event" header-class="align-items-center">
    <p>{{event}}</p>

    <template v-slot:modal-footer="{ cancel }">
      <b-button variant="outline-info" @click="update()">
        Save
      </b-button>
      <b-button variant="outline-secondary" @click="cancel()">
        Cancel
      </b-button>
    </template>
  </b-modal>
</template>
<script>
export default {
  methods: {
    update: function() {
      console.log(this.event);
    }
  },
  computed: {
  },
  props: [
    'event'
  ]
};
</script>
