<style lang="scss" scoped>
@import "resources/sass/_variables";

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
  box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
  border-radius: 3px;
}

.d-flex.w-100 {
  min-height: 100vh;
}
</style>

<template>
  <div class="d-flex justify-content-center align-items-center w-100 container-md">
    <b-overlay class="col-lg-6 col-md-10 bg-white p-5"
      id="overlay-background"
      :show="isPending"
      :variant="'light'"
      spinner-variant="primary"
      :opacity=".85"
      :blur="'2px'"
    >
      <h2 class="text-center pb-md-5 pt-md-3">Planner login</h2>
      <b-form @submit.prevent="onSubmit" class="px-md-5 pb-md-5">
        <b-form-group label="Email address" label-for="email-input">
          <b-input
            id="email-input"
            type="email"
            placeholder="jon.doe@mail.com"
            v-model="$v.form.email.$model"
            :state="validateState('email')"
          ></b-input>
          <b-form-invalid-feedback v-if="hasError('email')">Email address is required.</b-form-invalid-feedback>
          <b-form-invalid-feedback v-if="hasError('email', 'email')">Email is not valid.</b-form-invalid-feedback>
        </b-form-group>

        <b-form-group label="Password" label-for="password-input">
          <b-input
            id="password-input"
            type="password"
            placeholder="*************"
            v-model="$v.form.password.$model"
            :state="validateState('password')"
          ></b-input>
          <b-form-invalid-feedback v-if="hasError('password')">Password is required.</b-form-invalid-feedback>
          <b-form-invalid-feedback
            v-if="hasError('password', 'validCredentials')"
          >Email address or password is invalid.</b-form-invalid-feedback>
        </b-form-group>

        <b-button type="submit" variant="primary" class="w-100" :disabled="$v.form.$invalid">Login</b-button>
      </b-form>
    </b-overlay>
  </div>
</template>

<script>
import { validationMixin } from "vuelidate";
import { required, email } from "vuelidate/lib/validators";

export default {
  beforeCreate: function () {
    document.body.classList.add('bg-bbraun-green');
  },
  destroyed: function() {
    document.body.classList.remove('bg-bbraun-green');
  },
  mixins: [validationMixin],
  data() {
    return {
      form: {
        email: "",
        password: "",
      },
      isPending: false,
      invalidCredentials: false,
    };
  },
  validations: {
    form: {
      email: {
        required,
        email,
      },
      password: {
        required,
        validCredentials: function () {
          const res = !this.invalidCredentials;
          if (this.invalidCredentials) this.invalidCredentials = false;
          return res;
        },
      },
    },
  },
  methods: {
    onSubmit(evt) {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      } else {
        this.login();
      }
    },
    validateState(name) {
      const { $dirty, $error } = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    hasError(field, errorName = "required") {
      return this.$v.form[field].$dirty
        ? !this.$v.form[field][errorName]
        : null;
    },
    login() {
      this.isPending = true;
      this.$store
        .dispatch("login", {
          email: this.form.email,
          password: this.form.password,
        })
        .then(() => {
          this.$router.push("/dashboard");
        })
        .catch((err) => {
          this.invalidCredentials = true;
        })
        .finally((err) => {
          this.isPending = false;
        });
    },
  },
};
</script>
