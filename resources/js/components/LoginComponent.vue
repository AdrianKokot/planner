<style lang="scss" scoped>
@import 'resources/sass/_variables';

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

.loader {
  display: flex;
  width: 100vw;
  height: 100vh;
  position: absolute;
  top: 0;
  left: 0;
  background-color: rgba(0,0,0,0.8);
  justify-content: center;
  align-items: center;
}
</style>

<template>
  <div class="d-flex justify-content-center align-items-center w-100" style="min-height: 100vh">
    <div class="col-lg-6 col-md-10 bg-white p-5">
      <h2 class="text-center pb-md-5 pt-md-3">Planner login</h2>
      <b-form @submit.prevent="onSubmit" class="px-md-5 pb-md-5">
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
          <b-form-invalid-feedback v-if="hasError('email', 'validCredentials')">Email address or password is invalid.</b-form-invalid-feedback>
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
    <div class="loader" v-show="isPending">
      <b-spinner label="Loading..." variant="primary"></b-spinner>
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
      isPending: false,
      invalidCredentials: false
    };
  },
  validations: {
    form: {
      email: {
        required,
        email,
        validCredentials: function() {
          const res = !this.invalidCredentials;
          if(this.invalidCredentials) this.invalidCredentials = false;
          return res;
        }
      },
      password: {
        required,
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
    hasError(field, errorName = 'required') {
      return this.$v.form[field].$dirty ? !this.$v.form[field][errorName] : null;
    },
    login () {
      this.isPending = true;
      this.$store
        .dispatch('login', {
          email: this.form.email,
          password: this.form.password
        })
        .then(() => {
          this.$router.push('/dashboard');
        })
        .catch(err => {
          this.invalidCredentials = true;
        })
        .finally(err => {
          this.isPending = false;
        });
    }
  },
};
</script>
