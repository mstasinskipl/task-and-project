import Vue from "vue";
import store from "@/store/store";
import VueRouter from "vue-router";
import Login from "./components/auth/Login";

Vue.use(VueRouter);

const routes = [
  {
    path: "/login",
    name: "login",
    component: Login,
    beforeEnter(to, from, next) {
      if (store.state.auth.token) {
        next(false);
      } else {
        next();
      }
    }
  },
  {
    path: "/",
    name: "dashboard",
    meta: { requiresAuth: true },
    component: () =>
      import( "./components/Dashboard")
  },
  {
    path: "/task-list/:projectId",
    name: "taskList",
    meta: { requiresAuth: true },
    component: () =>
        import( "./components/TaskList")
  }
];

const router = new VueRouter({
  mode: "history",
  routes
});

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem("token");
  if (to.matched.some(record => record.meta.requiresAuth) && !token) {
    next({ path: "/login", query: { redirect: to.fullPath } });
  } else {
    next(); // make sure to always call next()!
  }
});

export default router;
