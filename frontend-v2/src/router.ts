import { createRouter as createClientRouter, createWebHistory } from 'vue-router'

/**
 * routes are generated using vite-plugin-pages
 * each .vue files located in the ./src/pages are registered as a route
 * @see https://github.com/hannoeru/vite-plugin-pages
 */
import routes from 'pages-generated'

/**
 * Here is how a simple route is generated:
 * import { RouteRecordRaw } from 'vue-router'
 *
 * const routes: RouteRecordRaw = [{
 *    component: () => import('/src/pages/wizard-1.vue'),
 *    name: 'wizard-v1',
 *    path: '/wizard-v1',
 *    props: true,
 *    meta: {
 *      requiresAuth: true
 *    },
 * }]
 *
 * Here is how nested routes are generated:
 * import { RouteRecordRaw } from 'vue-router'
 *
 * const routes: RouteRecordRaw = [{
 *    component: () => import('/src/pages/auth.vue'),
 *    path: '/auth',
 *    props: true,
 *    children: [
 *      {
 *        component: () => import('/src/pages/auth/login-1.vue'),
 *        name: 'auth-login-1',
 *        path: 'login-1',
 *        props: true
 *      },
 *    ],
 * }]
 *
 * Uncomment the line below to view the generated routes
 */
// console.log(routes)

export function createRouter() {
  const router = createClientRouter({
    /**
     * Jika aplikasi di-serve di sub-folder (mis. http://localhost/simrs/),
     * set env VITE_BASE_PATH=/simrs/ saat dev/build — `import.meta.env.BASE_URL`
     * otomatis berisi '/simrs/' sehingga router ikut menyesuaikan.
     * Secara default (BASE_URL === '/') aplikasi berjalan di root.
     */
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
  })
  return router
}
