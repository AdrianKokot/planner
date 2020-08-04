<style lang="scss" scoped>

</style>

<template>
  <div class="bg-white">
    <button class="btn btn-secondary" @click="logout">Logout</button>
  </div>
</template>

<script>
export default {
  data() {
    return {
      data: "hej"
    };
  },
  methods: {
    logout() {
      this.$store.dispatch('logout');
    }
  },
};
</script>
