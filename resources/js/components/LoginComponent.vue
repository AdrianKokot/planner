<style lang="scss" scoped>
@import 'resources/sass/_variables';
.position-absolute {
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.form-control {
  border-width: 0 0 1px 0;
  border-radius: 0;
}

.form-control:focus:not(.is-invalid) {
  border-color: $bbraunGreen;
}

.form-control:focus,
.form-control.is-invalid {
  outline-width: 0 0 1px 0;
  border-width: 0 0 1px 0;
  box-shadow: none;
}

.d-flex > .bg-white {
  box-shadow: 0px 0px 5px rgba(0,0,0,0.3);
  border-radius: 3px;
}
</style>

<template>
  <div class="d-flex justify-content-center align-items-center w-100" style="min-height: 100vh">
    <div class="col-lg-6 col-md-10 bg-white p-5">
      <h2 class="text-center pb-md-5 pt-md-3 font-weight-bold">Login</h2>
      <b-form @submit="onSubmit" class="px-md-5 pb-md-5">
        <b-form-group>
          <label for="email-input">Email address</label>
          <b-input
            placeholder="jon.doe@mail.com"
            v-model="$v.form.email.$model"
            type="email"
            :state="validateState('email')"
            id="email-input"
          ></b-input>
          <b-form-invalid-feedback v-if="hasError('email')">Email address is required.</b-form-invalid-feedback>
          <b-form-invalid-feedback v-if="hasError('email', 'email')">Email is not valid.</b-form-invalid-feedback>
        </b-form-group>

        <b-form-group>
          <label for="password-input">Password</label>
          <b-input
            placeholder="*************"
            v-model="$v.form.password.$model"
            type="password"
            :state="validateState('password')"
            id="password-input"
          ></b-input>
          <b-form-invalid-feedback v-if="hasError('password')">Password is required.</b-form-invalid-feedback>
        </b-form-group>

        <b-button type="submit" variant="primary" class="w-100" :disabled="$v.form.$invalid">Login</b-button>
      </b-form>
    </div>
  </div>
</template>

<script>
import { validationMixin } from "vuelidate";
import { required, email } from "vuelidate/lib/validators";

export default {
  mixins: [validationMixin],
  data() {
    return {
      form: {
        email: "",
        password: "",
      },
    };
  },
  validations: {
    form: {
      email: {
        required,
        email
      },
      password: {
        required,
      },
    },
  },
  methods: {
    onSubmit(evt) {
      evt.preventDefault();
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      alert(JSON.stringify(this.form));
    },
    validateState(name) {
      const { $dirty, $error } = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    hasError(field, errorName = 'required') {
      return this.$v.form[field].$dirty ? !this.$v.form[field][errorName] : null;
    }
  },
};
</script>
