import { START_LOCATION } from 'vue-router'
import { definePlugin } from '/@src/app'
import { useUserSession } from '/@src/stores/userSession'
import { useNotyf } from '/@src/composable/useNotyf'
// import { useToast } from 'primevue/usetoast'
/**
 * Here we are setting up two router navigation guards
 * (note that we can have multiple guards in multiple plugins)
 *
 * We can add meta to pages either by declaring them manualy in the
 * routes declaration (see /@src/router.ts)
 * or by adding a <route> tag into .vue files (see /@src/pages/sidebar/dashboards.ts)
 *
 * <route lang="yaml">
 * meta:
 *   requiresAuth: true
 * </route>
 *
 * <script setup lang="ts">
 *  // TS script
 * </script>
 *
 * <template>
 *  // HTML content
 * </template>
 */
export default definePlugin(({ router, api, pinia }) => {
  router.beforeEach(async (to, from) => {
    const userSession = useUserSession(pinia)
    // const notyf = useNotyf()
    // const toast = useToast()
    if (from === START_LOCATION && userSession.isLoggedIn) {
      // 1. If the name is not set, it means we are navigating to the first page
      // and we are logged in, so we should check user information from the server
      try {
        // Do api request call to retreive user profile.
        // Note that the api is provided with json-server
        // const { data: user } = await api.get('/api/users/me')
        // userSession.setUser(user)
        const userSessions = useUserSession()
        let data = JSON.parse(userSessions.dataUser)
        // toast.add({ severity: 'success', summary: 'Info', detail: `Welcome back, ${data.pegawai.namaLengkap}`, life: 3000, group: 'br' })
        // notyf.success(`Welcome back, ${data.pegawai.namaLengkap}`)
      } catch (err) {
        // delete stored token if it fails
        userSession.logoutUser()
        // toast.add({ severity: 'error', summary: 'Info', detail: `Your session is invalid`, life: 3000, group: 'br' })
        // notyf.error('Your session is invalid')

        if (to.meta.requiresAuth) {
          // redirect the user somewhere
          return {
            // Will follow the redirection set in /@src/pages/auth/index.vue
            name: 'auth',
            // save the location we were at to come back later
            query: { redirect: to.fullPath },
          }
        }
      }
    } else if (to.meta.requiresAuth && !userSession.isLoggedIn) {
      // 2. If the requires auth via requiresAuth meta, check if user is logged in
      // if not, redirect to login page.
      // notyf.error({
      //   message: 'Anda harus login untuk akses ke bagian ini',
      //   duration: 7000,
      // })
      // toast.add({ severity: 'error', summary: 'Info', detail: `Anda harus login untuk akses ke bagian ini`, life: 7000, group: 'br' })

      return {
        // Will follow the redirection set in /@src/pages/auth/index.vue
        name: 'auth',
        // save the location we were at to come back later
        query: { redirect: to.fullPath },
      }
    }
  })
})
