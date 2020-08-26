<template>
  <div>
    <div class="text-right mb-2">
      <b-button variant="outline-primary" @click="showCreateForm" v-if="canCreate">Create user</b-button>
    </div>
    <b-table
      id="users-table"
      hover
      responsive
      :selectable="canDelete || canUpdate"
      select-mode="single"
      :busy="loadingData"
      :items="users"
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

      <template v-slot:row-details="user">
        <div>
          <div class="py-2 px-3">
            <div class="font-weight-bolder">Name</div>
            <div>{{ user.item.name }}</div>
          </div>
          <div class="py-2 px-3">
            <div class="font-weight-bolder">Email</div>
            <div>{{ user.item.email }}</div>
          </div>
        </div>
        <div class="d-flex justify-content-end">
          <b-button
            variant="outline-info"
            class="mr-2"
            @click="showEditForm(user.item)"
            v-if="canUpdate"
          >Edit</b-button>
          <b-button variant="outline-danger" @click="destroy(user.item.id)" v-if="canDelete">Delete</b-button>
        </div>
      </template>
    </b-table>
    <b-pagination
      v-model="currentPage"
      :total-rows="rows"
      :per-page="perPage"
      aria-controls="users-table"
      first-number
      last-number
    ></b-pagination>
    <user-form-component
      v-if="canUpdate || canCreate"
      @createUser="createUser"
      :user="selectedUser"
      @updateUser="updateUser"
    ></user-form-component>
  </div>
</template>
<script>
import userDataService from "../../services/user-data-service";
import { toastOptions, msgBoxOptions } from "../../services/toast-options";

export default {
  data() {
    return {
      selectedUser: null,
      loadingData: false,
      fields: [
        {
          key: "name",
          sortable: true,
          label: "Name",
        },
        {
          key: "email",
          sortable: true,
          label: "Email",
        },
      ],
      users: [],
      perPage: 50,
      currentPage: 1,
    };
  },
  methods: {
    showDetails(row) {
      row._showDetails = !row._showDetails;
    },
    showEditForm(user) {
      this.selectedUser = user;
      this.$bvModal.show("user-form-modal");
    },
    showCreateForm() {
      this.selectedUser = null;
      this.$bvModal.show("user-form-modal");
    },
    destroy(id) {
      this.$bvModal
        .msgBoxConfirm(
          "Are you sure you want to delete this user?",
          msgBoxOptions()
        )
        .then((value) => {
          if (value == true) {
            this.loadingData = true;
            userDataService.delete(id).then((response) => {
              if (response.data.id == id) {
                this.users.splice(
                  this.users.findIndex((x) => x.id == id),
                  1
                );
                this.$bvToast.toast(
                  "User was deleted successfully!",
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
    updateUser(user) {
      this.users.splice(
        this.users.findIndex((x) => x.id == user.id),
        1,
        user
      );
    },
    createUser(user) {
      this.users.push(user);
    },
  },
  mounted: function () {
    this.loadingData = true;
    userDataService.getAll().then((response) => {
      console.log(response.data);
      this.users = response.data;
      this.loadingData = false;
    });
  },
  computed: {
    rows() {
      return this.users.length;
    },
    canCreate() {
      return this.$can("user.create");
    },
    canUpdate() {
      return this.$can("user.update");
    },
    canDelete() {
      return this.$can("user.delete");
    },
  },
};
</script>
