<template>
  <b-modal
  size="md"
    scrollable
    id="role-form-modal"
    @show="onShow()"
    @hidden="onHide()"
    :title="isCreateForm ? 'New role' : 'Edit role'"
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

      <b-form-group label="Permissions" class="stacked checkboxes">
        <div v-for="(permissionGroup, permissionName) in permissions" :key="permissionName" class="mb-3">
          <div style="text-transform: capitalize" class="font-weight-bolder">{{permissionName}}</div>
          <div class="ml-3">
            <b-form-checkbox
              v-model="checkedPermissions"
              name="permissions"
              v-for="perm in permissionGroup"
              :key="perm"
              :value="perm"
              inline
            >
              {{perm.split('.')[1]}}
            </b-form-checkbox>
          </div>
        </div>
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
import { required, maxLength, minLength } from "vuelidate/lib/validators";
import toastOptions from "../../services/toast-options";
import roleDataService from "../../services/role-data-service";
import permissionDataService from "../../services/permission-data-service";

export default {
  mixins: [validationMixin],
  props: ["role"],
  data() {
    return {
      showOverlay: false,
      isCreateForm: false,
      name: "",
      permissions: [],
      checkedPermissions: [],
    };
  },
  validations: {
    name: {
      required,
      maxLength: maxLength(50),
    },
  },
  methods: {
    onShow: function () {
      this.showOverlay = true;
      setTimeout(() => {
        if (this.role != null) {
          roleDataService.get(this.role.id).then(res => {
            this.checkedPermissions = res.data.permissions.map(x => x.name);
            this.showOverlay = false;
          });
          this.isCreateForm = false;
          this.name = this.role.name;
        } else {
          this.isCreateForm = true;
          this.onHide();
          this.showOverlay = false;
        }

      }, 10);
    },
    onHide: function () {
      this.name = "";
      this.checkedPermissions = [];
      this.$v.$reset();
    },
    submit: function () {
      this.onSubmit();
    },
    onSubmit: function () {
      this.$v.$touch();
      if (!this.$v.$invalid) {
        console.log(this.role);
        const body = {
          id: this.isCreateForm ? null : this.role.id,
          name: this.name,
          permissions: this.checkedPermissions,
        };

        console.log(body);

        this.showOverlay = true;
        if (this.isCreateForm) {
          roleDataService.create(body).then((response) => {
            console.log(response);
            if (response.data.id != null) {
              this.$emit("createRole", response.data);

              this.$bvModal.hide("role-form-modal");

              this.$bvToast.toast(
                "Role was created successfully!",
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
          roleDataService.update(body.id, body).then((response) => {
            console.log(response);
            if (response.data.id == this.role.id) {
              this.$emit("updateRole", body);
              this.$bvModal.hide("role-form-modal");
              this.$bvToast.toast(
                "Role was updated successfully!",
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
  mounted: function () {
    permissionDataService.getAll().then((response) => {
      this.permissions = _.groupBy(response.data, function(perm) {
          return perm.split('.')[0]
        });
      console.log('permissions', this.permissions);
    });
  },
};
</script>
