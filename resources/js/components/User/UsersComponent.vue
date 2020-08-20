<template>
  <div>
    <b-table
      id="users-table"
      hover
      responsive
      selectable
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
          <b-button variant="outline-info" class="mr-2" @click="showEditForm(user.item)">Edit</b-button>
          <b-button variant="outline-danger" @click="destroy(user.item.id)">Delete</b-button>
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
  </div>
</template>
<script>
import userDataService from "../../services/user-data-service";
import toastOptions from "../../services/toast-options";

export default {
  data() {
    return {
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
    showEditForm(user) { console.log(user) },
    destroy(id) {
      this.loadingData = true;
      userDataService.delete(id).then(response => {
        if(response.data.id == id) {
          this.users.splice(this.users.findIndex(x => x.id == id), 1);
          this.$bvToast.toast(
            "User was deleted successfully!",
            toastOptions()
          );
        } else {
          this.$bvToast.toast("Something went wrong", toastOptions("danger"));
        }
        this.loadingData = false;
      })
     },
  },
  mounted: function () {
    this.loadingData = true;
    userDataService.getAll().then((response) => {
      this.users = response.data;
      this.loadingData = false;
    });
  },
  computed: {
    rows() {
      return this.users.length;
    },
  },
};
</script>
