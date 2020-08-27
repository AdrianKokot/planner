<style lang="scss" scoped>
.badge {
  font-size: 0.8rem;
  display: inline;
  padding-right: 0.5rem;
  padding-left: 0.5rem;
}
</style>
<template>
  <div>
    <div class="d-flex flex-column flex-sm-row">
      <b-button variant="outline-primary" class="mb-3" @click="showCreateForm" v-if="canCreate">New category</b-button>
      <b-pagination
        class="mr-sm-0 mx-auto"
        v-model="currentPage"
        :total-rows="rows"
        :per-page="perPage"
        aria-controls="categories-table"
        first-number
        last-number
      ></b-pagination>
    </div>
    <b-table
      id="categories-table"
      hover
      responsive
      :selectable="canDelete || canUpdate"
      select-mode="single"
      :busy="loadingData"
      :items="categories"
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

      <template v-slot:cell(color)="data">
        <span
          class="badge text-white text-capitalize"
          :style="{backgroundColor: data.value}"
        >{{ data.value != 'var(--primary)' ? data.value.slice(6,-1) : 'default' }}</span>
      </template>

      <template v-slot:row-details="cat">
        <div class="d-flex justify-content-end">
          <b-button
            variant="outline-info"
            class="mr-2"
            @click="showEditForm(cat.item)"
            v-if="canUpdate"
          >Edit</b-button>
          <b-button variant="outline-danger" @click="deleteCategory(cat.item.id)" v-if="canDelete">Delete</b-button>
        </div>
      </template>
    </b-table>
    <transaction-category-form-component
      v-if="canUpdate || canCreate"
      @createCategory="createCategory"
      :category="transactionCategory"
      @updateCategory="updateCategory"
    ></transaction-category-form-component>
  </div>
</template>
<script>
import transactionCategoryDataService from "../../services/transaction-category-data-service";
import { toastOptions, msgBoxOptions } from "../../services/toast-options";

export default {
  data() {
    return {
      transactionCategory: null,
      loadingData: false,
      fields: [
        {
          key: "name",
          sortable: true,
          label: "Name",
        },
        {
          key: "color",
          sortable: true,
          label: "Color",
        },
      ],
      categories: [],
      perPage: 50,
      currentPage: 1,
    };
  },
  methods: {
    showDetails(row) {
      row._showDetails = !row._showDetails;
    },
    showEditForm(cat) {
      this.transactionCategory = cat;
      this.$bvModal.show("transaction-category-form-modal");
    },
    showCreateForm() {
      this.transactionCategory = null;
      this.$bvModal.show("transaction-category-form-modal");
    },
    deleteCategory(id) {
      this.$bvModal
        .msgBoxConfirm(
          "Are you sure you want to delete this category?",
          msgBoxOptions()
        )
        .then((value) => {
          if (value == true) {
            this.loadingData = true;
            transactionCategoryDataService.delete(id).then((response) => {
              if (response.data.id == id) {
                this.categories.splice(
                  this.categories.findIndex((x) => x.id == id),
                  1
                );
                this.$bvToast.toast(
                  "Category was deleted successfully!",
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
    updateCategory(cat) {
      this.categories.splice(
        this.categories.findIndex((x) => x.id == cat.id),
        1,
        cat
      );
    },
    createCategory(cat) {
      this.categories.push(cat);
    },
  },
  mounted: function () {
    this.loadingData = true;
    transactionCategoryDataService.getAll().then((response) => {
      this.categories = response.data;
      this.loadingData = false;
    });
  },
  computed: {
    rows() {
      return this.categories.length;
    },
    canCreate() {
      return this.$can("transfer_category.create");
    },
    canUpdate() {
      return this.$can("transfer_category.update");
    },
    canDelete() {
      return this.$can("transfer_category.delete");
    },
  },
};
</script>
