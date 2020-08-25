<style lang="scss" scoped>
@import "resources/sass/_variables";

section {
  padding-top: 80px;
}

</style>

<template>
  <div>
    <nav class="fixed-top bg-bbraun-green py-3">
      <div class="container-md px-3 d-flex justify-content-between">
        <h4 class="text-white p-0 m-0">
          Planner
        </h4>
        <button class="btn text-white p-0 m-0" @click="logout">Logout</button>
      </div>
    </nav>

    <section class="pb-5">
      <b-tabs content-class="mt-3" nav-wrapper-class="border-0" active-nav-item-class="border-bottom border-primary" no-nav-style>
        <b-tab title="Start">
          <h4 class="text-center">Welcome to planner!</h4>
          <p class="text-center">Click one of above tabs to navigate.</p>
        </b-tab>
        <b-tab title="Calendar" lazy v-if="$can('user_event.read')">
          <calendar-component></calendar-component>
        </b-tab>
        <b-tab title="Balance" lazy v-if="$can('user_income.read') || $can('user_expense.read')">
          <balance-component></balance-component>
        </b-tab>
        <b-tab title="Logs" lazy v-if="$can('log.read')">
          <logs-component></logs-component>
        </b-tab>
        <b-tab title="Users" lazy v-if="$can('user.read')">
          <users-component></users-component>
        </b-tab>
        <b-tab title="Roles" lazy v-if="$can('role.read')">
          <roles-component></roles-component>
        </b-tab>
      </b-tabs>
    </section>
  </div>
</template>

<script>
export default {
  methods: {
    logout() {
      this.$store.dispatch("logout");
    }
  }
};
</script>
