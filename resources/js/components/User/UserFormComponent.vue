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
          @input="onChange('email')"
          v-model="$v.email.$model"
          :state="$v.email.$dirty ? !$v.email.$error : null"
        ></b-input>
        <b-form-invalid-feedback v-if="!$v.email.required">Email is required.</b-form-invalid-feedback>
        <b-form-invalid-feedback v-if="!$v.email.email">Email is not valid.</b-form-invalid-feedback>
        <b-form-invalid-feedback v-if="!$v.email.alreadyTaken">Email has been already taken.</b-form-invalid-feedback>
        <b-form-invalid-feedback
          v-if="!$v.email.maxLength"
        >Email can be max {{$v.email.$params.maxLength.max}} characters long.</b-form-invalid-feedback>
      </b-form-group>

      <b-form-group label="Password" label-for="password-input" :description="isCreateForm ? null : 'If you don\'t want to change the password, leave this field empty.'">
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

      <b-form-group label="Password confirmation" label-for="password_confirmation-input">
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

      <b-form-group label="User role" label-for="role-input">
        <b-form-select
          id="role-input"
          v-model="$v.role.$model"
          :options="roles"
          :state="$v.role.$dirty ? !$v.role.$error : null"
          value-field="name"
          text-field="name"
        ></b-form-select>
        <b-form-invalid-feedback v-if="!$v.role.required">role is required.</b-form-invalid-feedback>
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
import { toastOptions } from "../../services/toast-options";
import roleDataService from "../../services/role-data-service";

export default {
  mixins: [validationMixin],
  props: ["user"],
  data() {
    return {
      errors: [],
      showOverlay: false,
      isCreateForm: false,
      roles: [],
      name: "",
      role: "",
      email: "",
      password: "",
      password_confirmation: "",
    };
  },
  methods: {
    onChange: function(field) {
      if (this.errors[field] != null) {
        this.errors[field] = null;
      }
    },
    onShow: function () {
      this.showOverlay = true;
      setTimeout(() => {
        if (this.user != null) {
          this.isCreateForm = false;
          this.name = this.user.name;
          this.role = this.user.roles[0].name;
          this.email = this.user.email;
        } else {
          this.isCreateForm = true;
          this.onHide();
        }
        this.showOverlay = false;
      }, 10);
    },
    onHide: function () {
      this.name = "";
      this.role = "";
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
          password: this.password,
          password_confirmation: this.password_confirmation,
          email: this.email,
          name: this.name,
          role: this.role,
        };

        this.showOverlay = true;
        if (this.isCreateForm) {
          userDataService.create(body).then((response) => {
            if(response.status == 422) {

              this.errors = response.data.errors;

            } else if (response.status == 200) {

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
            }
            this.showOverlay = false;
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
    email: {
      required,
      maxLength: maxLength(255),
      email,
      alreadyTaken: function () { return this.errors.email != "The email has already been taken."}
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
    role: {
      required,
    },
  },
  mounted: function() {
    roleDataService.getAll().then(roles => {
      this.roles = roles.data;
    });
  }
};
</script>
