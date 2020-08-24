<style lang="scss" scoped>
::v-deep #event-form-modal___BV_modal_header_ {
  display: block;
  padding: 0;
  border: none;
}
</style>
<template>
  <b-modal id="event-form-modal" @show="onShow()" @hidden="onHide()">
    <template v-slot:modal-header="{ close }" modal>
      <div class="modal-header" :style="{backgroundColor: 'var(--' + color + ')', color: 'white'}">
        <h4 class="m-0" v-if="!isCreateForm">Edit event</h4>
        <h4 class="m-0" v-if="isCreateForm">New event</h4>
        <b-button-close
          size="sm"
          class="m-0 p-0"
          :style="{ color: 'white', transition: '.3s'}"
          @click="close()"
        >&times;</b-button-close>
      </div>
    </template>
    <b-form @submit.prevent="onSubmit">
      <b-form-group label="Title" label-for="title-input">
        <b-input
          id="title-input"
          type="text"
          v-model="$v.title.$model"
          :state="$v.title.$dirty ? !$v.title.$error : null"
        ></b-input>
        <b-form-invalid-feedback v-if="!$v.title.required">Title is required.</b-form-invalid-feedback>
        <b-form-invalid-feedback
          v-if="!$v.title.maxLength"
        >Title can be max {{$v.title.$params.maxLength.max}} characters long.</b-form-invalid-feedback>
      </b-form-group>

      <b-form-group label="Event start" label-for="start-input">
        <b-input
          id="start-input"
          type="datetime-local"
          v-model="$v.start.$model"
          :state="$v.start.$dirty ? !$v.start.$error : null"
        ></b-input>
        <b-form-invalid-feedback v-if="!$v.start.required">Start date and time is required.</b-form-invalid-feedback>
        <b-form-invalid-feedback
          v-if="!$v.start.dateValidator"
        >Start date and time is not valid date and time.</b-form-invalid-feedback>
      </b-form-group>

      <b-form-group label="Event end" label-for="end-input">
        <b-input
          id="end-input"
          type="datetime-local"
          v-model="$v.end.$model"
          :state="$v.end.$dirty ? !$v.end.$error : null"
        ></b-input>
        <b-form-invalid-feedback v-if="!$v.end.required">End date and time is required.</b-form-invalid-feedback>
        <b-form-invalid-feedback
          v-if="!$v.end.dateValidator"
        >End date and time is not valid date and time.</b-form-invalid-feedback>
      </b-form-group>

      <b-form-group label="Color" label-for="color-input">
        <b-form-select
          id="color-input"
          v-model="$v.color.$model"
          :options="colors"
          :state="$v.color.$dirty ? !$v.color.$error : null"
        ></b-form-select>
        <b-form-invalid-feedback v-if="!$v.color.required">Color is required.</b-form-invalid-feedback>
      </b-form-group>

      <b-form-group label="Description" label-for="description-input">
        <b-form-textarea
          id="description-input"
          v-model="$v.description.$model"
          :state="$v.description.$dirty ? !$v.description.$error : null"
          placeholder
          rows="3"
          max-rows="6"
        ></b-form-textarea>
        <b-form-invalid-feedback
          v-if="!$v.description.maxLength"
        >Description can be max {{$v.description.$params.maxLength.max}} characters long.</b-form-invalid-feedback>
      </b-form-group>
    </b-form>

    <template v-slot:modal-footer="{ cancel }">
      <b-button variant="outline-info" @click.prevent="submit()" :disabled="$v.$invalid">{{isCreateForm ? 'Create' : 'Save'}}</b-button>
      <b-button variant="outline-secondary" @click="cancel()">Cancel</b-button>
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
import { validationMixin } from "vuelidate";
import { required, maxLength } from "vuelidate/lib/validators";
import eventDataService from "../../services/event-data-service";
import toastOptions from "../../services/toast-options";
import DateTimeConverter from "../../services/date-time-converter";

const dateValidator = (value) =>
  /([0-9]{4}-(0[0-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])T([0-1][0-9]|2[0-4]):([0-5][0-9]))/.test(
    value
  );

export default {
  mixins: [validationMixin],
  props: ["event", "date"],
  data() {
    return {
      showOverlay: false,
      isCreateForm: false,
      colors: [
        { value: "primary", text: "Default" },
        { value: "blue", text: "Blue" },
        { value: "indigo", text: "Indigo" },
        { value: "purple", text: "Purple" },
        { value: "pink", text: "Pink" },
        { value: "red", text: "Red" },
        { value: "orange", text: "Orange" },
        { value: "teal", text: "Teal" },
        { value: "cyan", text: "Cyan" },
      ],
      color: null,
      title: "",
      start: null,
      end: null,
      description: "",
    };
  },
  methods: {
    onShow: function () {
      this.showOverlay = true;
      setTimeout(() => {
        if (this.event != null) {
          this.isCreateForm = false;
          this.title = this.event.title;
          this.start = DateTimeConverter.convertToDateTimeString(this.event.start);
          this.end = DateTimeConverter.convertToDateTimeString(this.event.end);
          this.description =
            this.event.extendedProps != null
              ? this.event.extendedProps.description ?? ""
              : "";
          this.color = this.event.backgroundColor.slice(6, -1);
        } else if (this.date != null) {
          this.start = DateTimeConverter.convertToDateTimeString(this.date);
          this.end = DateTimeConverter.convertToDateTimeString(this.date);
          this.color = "primary";
          this.isCreateForm = true;
        }
        this.showOverlay = false;
      }, 10);
    },
    onHide: function () {
      this.isCreateForm = false;
      this.title = "";
      this.start = DateTimeConverter.convertToDateTimeString(new Date());
      this.end = DateTimeConverter.convertToDateTimeString(new Date());
      this.description = "";
      this.color = "default";
      this.$v.$reset();
    },
    submit: function () {
      this.onSubmit();
    },
    onSubmit: function () {
      this.$v.$touch();
      if (!this.$v.$invalid) {
        const body = {
          id: this.isCreateForm ? null : this.event.id,
          title: this.title,
          start: this.start,
          end: this.end,
          description: this.description,
          color: `var(--${this.color})`,
        };

        this.showOverlay = true;
        if (this.isCreateForm) {
          eventDataService.create(body).then((response) => {
            if (response.data.id != null) {
              this.$emit("createEvent", response.data);
              console.log(response.data);
              this.$bvModal.hide("event-form-modal");

              this.$bvToast.toast(
                "Event was added successfully!",
                toastOptions()
              );
            } else {
              this.$bvToast.toast(
                "Something went wrong",
                toastOptions("danger")
              );
              this.showOverlay = false;
            }
          });
        } else {
          eventDataService.update(this.event.id, body).then((response) => {
            if (response.data.id == this.event.id) {
              this.$emit("updateEvent", body);
              this.$bvModal.hide("event-form-modal");
              this.$bvToast.toast(
                "Event was saved successfully!",
                toastOptions()
              );
            } else {
              this.$bvToast.toast(
                "Something went wrong",
                toastOptions("danger")
              );
              this.showOverlay = false;
            }
          });
        }
      }
    },
  },
  validations: {
    title: {
      required,
      maxLength: maxLength(50),
    },
    start: {
      required,
      dateValidator,
    },
    end: {
      required,
      dateValidator,
    },
    description: {
      maxLength: maxLength(255),
    },
    color: {
      required,
    },
  }
};
</script>
