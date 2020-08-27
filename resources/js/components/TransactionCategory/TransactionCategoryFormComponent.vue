<template>
  <b-modal id="transaction-category-form-modal" @show="onShow()" @hidden="onHide()"
    :title="isCreateForm ? 'New category' : 'Edit category'">
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

      <b-form-group label="Color" label-for="color-input">
        <b-form-select
          id="color-input"
          v-model="$v.color.$model"
          :options="colors"
          :state="$v.color.$dirty ? !$v.color.$error : null"
        ></b-form-select>
        <b-form-invalid-feedback v-if="!$v.color.required">Color is required.</b-form-invalid-feedback>
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
import transactionCategoryDataService from "../../services/transaction-category-data-service";
import { toastOptions } from "../../services/toast-options";

export default {
  mixins: [validationMixin],
  props: ["category"],
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
      name: ""
    };
  },
  methods: {
    onShow: function () {
      this.showOverlay = true;
      setTimeout(() => {
        if (this.category != null) {
          this.isCreateForm = false;
          this.name = this.category.name;
          this.color = this.category.color.slice(6, -1);
        } else {
          this.onHide();
        }
        this.showOverlay = false;
      }, 10);
    },
    onHide: function () {
      this.isCreateForm = true;
      this.name = "";
      this.color = "primary";
      this.$v.$reset();
    },
    submit: function () {
      this.onSubmit();
    },
    onSubmit: function () {
      this.$v.$touch();
      if (!this.$v.$invalid) {
        const body = {
          id: this.isCreateForm ? null : this.category.id,
          name: this.name,
          color: `var(--${this.color})`,
        };
        console.log('body', body);

        this.showOverlay = true;
        if (this.isCreateForm) {
          transactionCategoryDataService.create(body).then((response) => {
            console.log(response);
            if (response.data.id != null) {
              this.$emit("createCategory", response.data);
              console.log(response.data);
              this.$bvModal.hide("transaction-category-form-modal");

              this.$bvToast.toast(
                "Category was added successfully!",
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
          transactionCategoryDataService.update(this.category.id, body).then((response) => {
            if (response.data.id == this.category.id) {
              this.$emit("updateCategory", body);
              this.$bvModal.hide("transaction-category-form-modal");
              this.$bvToast.toast(
                "Category was saved successfully!",
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
    color: {
      required,
    },
  }
};
</script>
