<template>
  <article>
    <header class="bg-gray-300 border-b p-5">
      <h1 class="uppercase">Dashboard</h1>
    </header>
    <div class="p-5">
      <p class="text-blue-500 mb-2">You are logged as: {{ user.name }} Profile type: {{ user.type }}</p>
      <button
        type="button"
        class="bg-blue-400 hover:bg-blue-500 trans text-white rounded px-4 py-1 text-sm focus:outline-none focus:shadow-outline"
        @click="logout"
      >Logout</button>
    </div>
    <project-list></project-list>
  </article>
</template>

<script>
import { mapGetters } from "vuex";
import ProjectList from "@/components/ProjectList";

export default {
  name: "Dashboard",
  components: {ProjectList},
  computed: {
    ...mapGetters("auth", ["authUser", "loggedIn"])
  },
  created() {
    this.user = JSON.parse(window.localStorage.getItem('user'));
  },
  data: function () {
    return {
      user: null
    }
  },
  methods: {
    logout() {
      this.$store.dispatch("auth/logout");
    }
  }
};
</script>
