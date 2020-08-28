<template>
  <div>
    <div class="d-flex flex-column flex-sm-row">
      <b-button
        variant="outline-primary"
        class="mb-3"
        @click="showCreateForm"
        v-if="canCreate"
      >New role</b-button>
      <b-pagination
        class="mr-sm-0 mx-auto"
        v-model="currentPage"
        :total-rows="rows"
        :per-page="perPage"
        aria-controls
        first-number
        last-number
      ></b-pagination>
    </div>
    <b-table
      id="roles-table"
      hover
      responsive
      :selectable="canDelete || canUpdate"
      select-mode="single"
      :busy="loadingData"
      :items="roles"
      :fields="fields"
      :per-page="perPage"
      :current-page="currentPage"
      @row-clicked="showDetails"
      tbody-tr-class="text-truncate"
      sort-icon-left
    >
      <template v-slot:table-busy>
        <div class="text-center text-primary my-2">
          <b-spinner class="align-middle"></b-spinner>
        </div>
      </template>

      <template v-slot:row-details="role">
        <div class="d-flex justify-content-end">
          <b-button
            variant="outline-info"
            class="mr-2"
            @click="showEditForm(role.item)"
            v-if="canUpdate"
          >Edit</b-button>
          <b-button variant="outline-danger" @click="destroy(role.item.id)" v-if="canDelete">Delete</b-button>
        </div>
      </template>
    </b-table>
    <role-form-component
      v-if="canUpdate || canCreate"
      @createRole="createRole"
      :role="selectedRole"
      @updateRole="updateRole"
    ></role-form-component>
  </div>
</template>
<script>
import roleDataService from "../../services/role-data-service";
import { toastOptions, msgBoxOptions } from "../../services/toast-options";

export default {
  data() {
    return {
      selectedRole: null,
      loadingData: false,
      fields: [
        {
          key: "name",
          sortable: true,
          label: "Name",
        },
      ],
      roles: [],
      perPage: 50,
      currentPage: 1,
    };
  },
  methods: {
    showDetails(row) {
      row._showDetails = !row._showDetails;
    },
    showEditForm(role) {
      this.selectedRole = role;
      this.$bvModal.show("role-form-modal");
    },
    showCreateForm() {
      this.selectedRole = null;
      this.$bvModal.show("role-form-modal");
    },
    destroy(id) {
      this.$bvModal
        .msgBoxConfirm(
          "Are you sure you want to delete this role?",
          msgBoxOptions()
        )
        .then((value) => {
          if (value == true) {
            this.loadingData = true;
            roleDataService.delete(id).then((response) => {
              if (response.data.id == id) {
                this.roles.splice(
                  this.roles.findIndex((x) => x.id == id),
                  1
                );
                this.$bvToast.toast(
                  "Role was deleted successfully!",
                  toastOptions()
                );
              } else {
                this.$bvToast.toast(
                  "Something went wrong",
                  toastOptions("danger")
                );
              }
              this.loadingData = false;
            });
          }
        });
    },
    updateRole(role) {
      this.roles.splice(
        this.roles.findIndex((x) => x.id == role.id),
        1,
        role
      );
    },
    createRole(role) {
      this.roles.push(role);
    },
  },
  mounted: function () {
    this.loadingData = true;
    roleDataService.getAll().then((response) => {
      this.roles = response.data;
      this.loadingData = false;
    });
  },
  computed: {
    rows() {
      return this.roles.length;
    },
    canCreate() {
      return this.$can("role.create");
    },
    canUpdate() {
      return this.$can("role.update");
    },
    canDelete() {
      return this.$can("role.delete");
    },
  },
};
</script>
