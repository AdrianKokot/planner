<style lang="scss" scoped>
::v-deep #event-form-modal___BV_modal_header_ {
  display: block;
  padding: 0;
  border: none;
}
</style>
<template>
  <b-modal
    id="user-form-modal"
    @show="onShow()"
    @hidden="onHide()"
    :title="isCreateForm ? 'New user' : 'Edit user'"
  >
    <b-form @submit.prevent="onSubmit">
      <b-form-group label="Name" label-for="name-input">
        <b-input
          id="name-input"
          type="text"
          v-model="$v.name.$model"
          :state="$v.name.$dirty ? !$v.name.$error : null"
        ></b-input>
        <b-form-invalid-feedback v-if="!$v.name.required">Name is required.</b-form-invalid-feedback>
        <b-form-invalid-feedback
          v-if="!$v.name.maxLength"
        >Name can be max {{$v.name.$params.maxLength.max}} characters long.</b-form-invalid-feedback>
      </b-form-group>

      <b-form-group label="Email" label-for="email-input">
        <b-input
          id="email-input"
          type="email"
          v-model="$v.email.$model"
          :state="$v.email.$dirty ? !$v.email.$error : null"
        ></b-input>
        <b-form-invalid-feedback v-if="!$v.email.required">Email is required.</b-form-invalid-feedback>
        <b-form-invalid-feedback v-if="!$v.email.email">Email is not valid.</b-form-invalid-feedback>
        <b-form-invalid-feedback
          v-if="!$v.email.maxLength"
        >Email can be max {{$v.email.$params.maxLength.max}} characters long.</b-form-invalid-feedback>
      </b-form-group>

      <b-form-group label="Password" label-for="password-input" v-if="isCreateForm">
        <b-input
          id="password-input"
          type="password"
          v-model="$v.password.$model"
          :state="$v.password.$dirty ? !$v.password.$error : null"
        ></b-input>
        <b-form-invalid-feedback v-if="!$v.password.required">Password is required.</b-form-invalid-feedback>
        <b-form-invalid-feedback
          v-if="!$v.password.minLength"
        >Password must be minumum {{$v.password.$params.minLength.min}} characters long.</b-form-invalid-feedback>
        <b-form-invalid-feedback
          v-if="!$v.password.maxLength"
        >Password can be max {{$v.password.$params.maxLength.max}} characters long.</b-form-invalid-feedback>
      </b-form-group>

      <b-form-group label="Password confirmation" label-for="password_confirmation-input" v-if="isCreateForm">
        <b-input
          id="password_confirmation-input"
          type="password"
          v-model="$v.password_confirmation.$model"
          :state="$v.password_confirmation.$dirty ? !$v.password_confirmation.$error : null"
        ></b-input>
        <b-form-invalid-feedback
          v-if="!$v.password_confirmation.required"
        >Password confirmation is required.</b-form-invalid-feedback>
        <b-form-invalid-feedback
          v-if="!$v.password_confirmation.sameAsPassword"
        >Passwords must be identical.</b-form-invalid-feedback>
      </b-form-group>

      <b-form-group label="User group" label-for="group-input">
        <b-form-select
          id="group-input"
          v-model="$v.group.$model"
          :options="groups"
          :state="$v.group.$dirty ? !$v.group.$error : null"
          value-field="id"
          text-field="name"
        ></b-form-select>
        <b-form-invalid-feedback v-if="!$v.group.required">Group is required.</b-form-invalid-feedback>
      </b-form-group>
    </b-form>

    <template v-slot:modal-footer="{ cancel }">
      <b-button
        variant="outline-info"
        @click.prevent="submit()"
        :disabled="$v.$invalid"
      >{{isCreateForm ? 'Create' : 'Save'}}</b-button>
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
import {
  required,
  maxLength,
  minLength,
  email,
  sameAs,
  requiredIf,
} from "vuelidate/lib/validators";
import userDataService from "../../services/user-data-service";
import toastOptions from "../../services/toast-options";
import roleDataService from "../../services/role-data-service";

export default {
  mixins: [validationMixin],
  props: ["user"],
  data() {
    return {
      showOverlay: false,
      isCreateForm: false,
      // TODO get groups from api
      groups: [],
      name: "",
      group: "",
      email: "",
      password: "",
      password_confirmation: "",
    };
  },
  methods: {
    onShow: function () {
      this.showOverlay = true;
      setTimeout(() => {
        if (this.user != null) {
          this.isCreateForm = false;
          this.name = this.user.name;
          this.group = this.user.group;
          this.email = this.user.email;
        } else {
          this.isCreateForm = true;
          this.onHide();
        }
        this.showOverlay = false;
        console.log('isCreateForm:', this.isCreateForm);
      }, 10);
    },
    onHide: function () {
      this.name = "";
      this.group = "";
      this.email = "";
      this.password = "";
      this.password_confirmation = "";
      this.$v.$reset();
    },
    submit: function () {
      this.onSubmit();
    },
    onSubmit: function () {
      this.$v.$touch();
      if (!this.$v.$invalid) {
        const body = {
          id: this.isCreateForm ? null : this.user.id,
          password: this.isCreateForm ? this.password : null,
          password_confirmation: this.isCreateForm
            ? this.password_confirmation
            : null,
          email: this.email,
          name: this.name,
          group: this.group,
        };

        this.showOverlay = true;
        if (this.isCreateForm) {
          userDataService.create(body).then((response) => {
            if (response.data.id != null) {
              this.$emit("createUser", response.data);

              this.$bvModal.hide("user-form-modal");

              this.$bvToast.toast(
                "User was created successfully!",
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
          userDataService.update(body.id, body).then((response) => {
            if (response.data.id == this.user.id) {
              this.$emit("updateUser", body);
              this.$bvModal.hide("user-form-modal");
              this.$bvToast.toast(
                "User was updated successfully!",
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
    name: {
      required,
      maxLength: maxLength(255),
    },
    // TODO check if email is already taken
    email: {
      required,
      maxLength: maxLength(255),
      email,
    },
    password: {
      required: requiredIf(function () {
        return this.isCreateForm;
      }),
      minLength: minLength(8),
      maxLength: maxLength(32),
    },
    password_confirmation: {
      required: requiredIf(function () {
        return this.isCreateForm;
      }),
      sameAsPassword: sameAs("password"),
    },
    group: {
      required,
    },
  },
  mounted: function() {
    roleDataService.getAll().then(roles => {
      this.groups = roles.data;
    });
  }
};
</script>
