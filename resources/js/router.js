import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

import { default as DashboardComponent } from './components/DashboardComponent';
import { default as LoginComponent } from './components/LoginComponent';

export const routes = [
  {
    path: '/',
    redirect: '/dashboard'
  },
  {
    path: '/dashboard',
    component: DashboardComponent,
    meta: {
      auth: true
    }
  },
  {
    path: '/login',
    component: LoginComponent
  }
];

const router = new VueRouter({
  mode: 'history',
  routes
});

router.beforeEach((to, from, next) => {
  const loggedIn = localStorage.getItem('user')

  if (to.matched.some(record => record.meta.auth) && !loggedIn) {
    next('/login')
    return
  }
  next()
});

export default router;
