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
        <b-tab title="Calendar" lazy>
          <calendar-component></calendar-component>
        </b-tab>
        <b-tab title="Budget" lazy>Budget will be here</b-tab>
      </b-tabs>
    </section>
  </div>
</template>

<script>
export default {
  data() {
    return {
      data: "hej",
    };
  },
  methods: {
    logout() {
      this.$store.dispatch("logout");
    },
  },
};
</script>
