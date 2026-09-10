import axios, { AxiosInstance } from 'axios'
import { useUserSession } from '/@src/stores/userSession'
// import { useNotyf } from '/@src/composable/useNotyf'
import { useToaster } from '/@src/composable/toaster'
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useStorage } from '@vueuse/core'

// const notyf = useNotyf()

// import * as modules from '/@src/pages/module.vue'
let api: AxiosInstance
let success_Code = [200, 201, 202, 203, 204]
export default {
  setup() {

  }
}
export function createApi() {
  // Here we set the base URL for all requests made to the api
  api = axios.create({
    baseURL: import.meta.env.VITE_API_BASE_URL,
  })

  // We set an interceptor for each request to
  // include Bearer token to the request if user is logged in
  api.interceptors.request.use((config: any) => {
    const userSession = useUserSession()

    if (userSession.isLoggedIn) {
      config.headers = {
        ...config.headers,
        // Authorization: `Bearer ${userSession.token}`,
        token: userSession.token,
      }
    }

    return config
  })
  api.interceptors.response.use(
    (response: any) => {
      if (response.config.method == 'post') {
        if (success_Code.includes(response.status)) {
          if (response.data.metaData != undefined) {
            if (success_Code.includes(response.data.metaData.code) && (response.metaData.message != 'Token Tidak Valid' || response.metaData.message != 'Sesi anda telah berakhir, silahkan login kembali')) {
              notyf.success(response.data.metaData.message)

            } else {
              notyf.error(response.data.metaData.message)
              // checkLogin(response.data.metaData.message)
            }
          } else {
            notyf.success('Sukses')
          }
        } else {
          notyf.error(response.statusText)
        }
        response.data = response.data.response
      } else {
        if (success_Code.includes(response.status)) {
          if (!success_Code.includes(response.data.metaData.code)) {
            notyf.error(response.data.message)
          }
          response.data = response.data.response
        } else {
          notyf.error(response.statusText)
        }
      }
      return response
    },
    (error: any) => {
      if (error.response.data.metaData != undefined) {
        notyf.error(error.response.data.metaData.message)
      } else {
        notyf.error(error.response.statusText)
      }
      return error
    }
  )
  return api
}
const isSimpan = ref(false)
export function useApi() {

  const toaster = useToaster()
  let isLoading = false
  let apiS = axios.create({
    baseURL: import.meta.env.VITE_API_BASE_URL,
  })
  const router = useRouter()
  const logout = () => {
    useStorage('user_session', '')
    useUserSession().logoutUser()
    router.push({
      name: 'auth-login',
    })
    // window.location.href =  window.location.host + '/auth/login'

  }
  // if (!api) {
  //   createApi()
  // }
  // return api

  return {
    getHeader: () => { },
    get: (url: any) => {
      // console.log(modules.isLoaderActive)
      apiS.interceptors.request.use((config: any) => {
        const userSession = useUserSession()
        if (userSession.isLoggedIn) {
          config.headers = {
            ...config.headers,
            // Authorization: `Bearer ${userSession.token}`,
            token: userSession.token,
          }
        }
        return config
      })
      return apiS
        .get(url)
        .then(({ data }) => {
          isLoading = false
          
          if (success_Code.includes(data.metaData.code)) {
            return data.response
          } else {
            toaster.error(data.metaData.message)
            checkLogin(data.metaData.message)
            // notyf.error(data.metaData.message)
          }
        })
        .catch((err) => {
          let message = '';
          if(err.response && err.response.data) {
            message =
              typeof err.response.data.metaData !== 'undefined' ? err.response.data.metaData.message : err.message
            if (err.response.data.metaData && err.response.data.metaData.code == 401) {
              // logout()
              toaster.error(message)
              checkLogin(message)
            }
          }else {
            message = 'Terjadi kesalahan';
          }

          toaster.error(message)
          // notyf.error(message)
          return Promise.reject(message)
        })
        .finally(() => (isLoading = false))
    },
    post: (url: any, dataSave: any) => {
      apiS.interceptors.request.use((config: any) => {
        const userSession = useUserSession()
        if (userSession.isLoggedIn) {
          config.headers = {
            ...config.headers,
            // Authorization: `Bearer ${userSession.token}`,
            token: userSession.token,
          }
        }
        return config
      })


      // if( useUserSession().getUser() != null && useUserSession().getUser().kelompokUser.kelompokUser =='viewer'){
      //   toaster.error('Anda tidak memiliki hak akses merubah data')
      //   return
      // }
      isSimpan.value = true
      return apiS
        .post(url, dataSave)
        .then(({ data }) => {
          if (success_Code.includes(data.metaData.code)) {
            if(data.response.message != 'hide'){
               toaster.success(data.metaData.message)
            }
             return data.response
          } else {
            toaster.error(data.metaData.message)
            checkLogin(data.metaData.message)
            // notyf.error(data.metaData.message)
          }
        })
        .catch((err) => {
          let message =
            typeof err.response !== 'undefined'
              ? err.response.status == 404 || err.response.status == 500
                ? err.response.statusText
                : err.response.data.metaData.message
              : err.message
          toaster.error(message)
          // notyf.error(message)
          if (err.response && err.response.data.metaData.code == 401) {
            // logout()
          }
          isSimpan.value = false
          return Promise.reject(
            err.response !== 'undefined' ? err.response.data.response : err.message
          )
          // throw err
        })
        .finally(() => (isSimpan.value = false))
    },
    postBPJS: (url: any, dataSave: any) => {
      apiS.interceptors.request.use((config: any) => {
        const userSession = useUserSession()
        if (userSession.isLoggedIn) {
          config.headers = {
            ...config.headers,
            // Authorization: `Bearer ${userSession.token}`,
            token: userSession.token,
          }
        }
        return config
      })
      isSimpan.value = true
      return apiS
        .post(url, dataSave)
        .then(({ data }) => {
          return data
        })
        .catch((err) => {
          isSimpan.value = false
          if (err.response && err.response.data.metaData.code == 401) {
            // logout()
          }
          return Promise.reject(
            err.response
          )
          // throw err
        })
        .finally(() => (isSimpan.value = false))
    },
    postNoMessage: (url: any, dataSave: any) => {
      apiS.interceptors.request.use((config: any) => {
        const userSession = useUserSession()
        if (userSession.isLoggedIn) {
          config.headers = {
            ...config.headers,
            // Authorization: `Bearer ${userSession.token}`,
            token: userSession.token,
          }
        }
        return config
      })
      isSimpan.value = true
      return apiS
        .post(url, dataSave)
        .then(({ data }) => {
          return data
        })
        .catch((err) => {
          isSimpan.value = false
          if (err.response && err.response.data.metaData.code == 401) {
            toaster.error(err.response.data.metaData.message)
            checkLogin(err.response.data.metaData.message)
          }
          return Promise.reject(
            err.response
          )
          // throw err
        })
        .finally(() => (isSimpan.value = false))
    },
    postSATUSEHAT: (url: any, dataSave: any) => {
      apiS.interceptors.request.use((config: any) => {
        const userSession = useUserSession()
        if (userSession.isLoggedIn) {
          config.headers = {
            ...config.headers,
            // Authorization: `Bearer ${userSession.token}`,
            token: userSession.token,
          }
        }
        return config
      })
      isSimpan.value = true
      return apiS
        .post(url, dataSave)
        .then(({ data }) => {
          if (data.resourceType == "OperationOutcome") {

            toaster.error(JSON.stringify(data.issue))
            return data
          } else {
            toaster.success('Ok')
            return data
          }
        })
        .catch((err) => {

          toaster.error(err.response.data.message)

          isSimpan.value = false
          return Promise.reject(
            err.response !== 'undefined' ? err.response.data.response : err.message
          )
          // throw err
        })
        .finally(() => (isSimpan.value = false))
    },

  }
}
export function checkLogin(message: any) {
  const toaster = useToaster()
  console.log("Masuk Check Login");
  if (message == 'Token Tidak Valid' || message == 'Sesi anda telah berakhir, silahkan login kembali') {
    toaster.error('Sesi anda habis')
    // notyf.error('Your session is invalid')
    useStorage('user_session', '')
    useUserSession().logoutUser()
    useRouter().push({
      name: 'auth-login',
    })
    // window.location.href =  window.location.host + '/auth/login'
    return
  }
}
