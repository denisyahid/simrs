<script setup lang="ts">
import { defineComponent, onMounted, onBeforeMount, reactive, ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'

import { useDarkmode } from '/@src/stores/darkmode'
import { useUserSession } from '/@src/stores/userSession'
import { useApi } from '/@src/composable/useApi'
import { useNotyf } from '/@src/composable/useNotyf'
import { useToaster } from '/@src/composable/toaster'
import { useToast } from 'primevue/usetoast'
import sleep from '/@src/utils/sleep'
import axios from 'axios'
import { boolean } from 'zod'
import { useStorage } from '@vueuse/core'
import Password from 'primevue/password'
export type UserData = Record<string, any> | null
type StepId = 'login' | 'forgot-password'
const step = ref<StepId>('login')
const Project = import.meta.env.VITE_PROJECT
const isLoading = ref(false)
const darkmode = useDarkmode()
const router = useRouter()
const route = useRoute()
const notif = useToaster()
const api = useApi()
const toast = useToast()
let apiS
const redirect = route.query.redirect as string
const namaUser = ref('')
const kataSandi = ref('')
let error = ref('')
let isError = ref(false)
const userSession = useUserSession()
const listMenu = useStorage('list_menu', [])
listMenu.value = []
const isMobile = window.innerWidth <= 768;
const showLogin:any = ref(false)
declare const grecaptcha: any;
const wCaptcha = ref(false)
var widgetCaptcha = null;

const validDomains = [
  'simrsbm.transmedic.co.id',
  'simrsbm-test.baliprov.go.id',
  'simrsbm.baliprov.go.id',
];

onBeforeMount(() => {
  if (validDomains.some(domain => window.location.hostname.includes(domain))) {
    wCaptcha.value = true
  } else {
    wCaptcha.value = false
  }
})

const capcayni = () => {
  if (validDomains.some(domain => window.location.hostname.includes(domain))) {
    window.onloadTurnstileCallback = () => {
      if (!isMobile || showLogin.value) {
        renderCatpcha();
      }
    };
    const script = document.createElement('script');
    script.src = `https://challenges.cloudflare.com/turnstile/v0/api.js?onload=onloadTurnstileCallback`;
    script.async = true;
    script.defer = true;
    document.head.appendChild(script);
  }
}
onMounted(() => {
  capcayni()
})

const showMyLogin = () => {
  showLogin.value = true
  setTimeout(() => {
    capcayni()
  }, 1000);
}

useHead({
  title: 'Auth Login - ' + import.meta.env.VITE_PROJECT,
})

const renderCatpcha = () => {
  widgetCaptcha = turnstile.render("#icloudfare-captcha", {
    sitekey: "0x4AAAAAAAy3VAyuhqa7rIyq",
    callback: function (tokenCh) {
      console.log('token ' + tokenCh);
      sessionStorage.setItem('tokenCapcay', tokenCh);
    },
  });
}

const handleLogin = async() => {
  isError.value = false
  error.value = ''
  if (namaUser.value == '') {
    error.value = 'Username Required'
    isError.value = true
    return
  }
  if (kataSandi.value == '') {
    error.value = 'Password Required'
    isError.value = true
    return
  }

  const tokenCapcay = sessionStorage.getItem('tokenCapcay');

  if (!isLoading.value) {
    isLoading.value = true
    notif.dismissAll()
    let token = ''
    try {
      token = await grecaptcha.execute( import.meta.env.VITE_CAPTCHA_SITEKEY, { action: 'submit' })
    } catch (error) {
      console.log(error)
    }
    apiS = axios.create({
      baseURL: import.meta.env.VITE_API_BASE_URL,
    })
    apiS
      .post('auth/login', {
        namaUser: namaUser.value,
        kataSandi: kataSandi.value,
        token: token,
        tokenCapcay: tokenCapcay
      })
      .then(({ data }) => {
        isLoading.value = false

        if (data.metaData.code == 200) {

          let res = data.response
          if(res.data.kelompokUser.kelompokUser == 'viewer'){
            toast.add({ severity: 'error', summary: 'Info', detail: `Anda hanya memiliki akses LIHAT DATA saja`, life: 10000, group: 'br' })
          }else{
            toast.add({ severity: 'success', summary: 'Info', detail: `Welcome back, ${res.data.pegawai.namaLengkap}`, life: 3000, group: 'br' })
          }

          // notif.success('Welcome back, ' + res.data.pegawai.namaLengkap)
          userSession.setUser(res.data)
          userSession.setToken(res.token)
          userSession.setUserData(res.data)


          if (redirect && redirect != '/') {
            router.push(redirect)
          } else {
            router.push({
              name: res.data.kelompokUser.menu != null ? res.data.kelompokUser.menu : 'app',
            })
          }


        } else {
          if (widgetCaptcha !== undefined && widgetCaptcha !== null) {
            turnstile.reset(widgetCaptcha);
          }
          notif.error(data.metaData.message)
        }
      })
      .catch((err) => {
        isLoading.value = false
        if (widgetCaptcha !== undefined && widgetCaptcha !== null) {
          turnstile.reset(widgetCaptcha);
        }
        let message =
          typeof err.response !== 'undefined' ? err.response.data.metaData.message : err.message
        notif.error(message)
      })
  }
}
const changeUser = (e: any) => {
  if (e != '') {
    error.value = ''
    isError.value = false
  }
}

</script>

<template>

  <div class="modern-login">
    <!-- <vue-recaptcha ref="recaptcha" :sitekey="'0x4AAAAAAAy3VAyuhqa7rIyq'"></vue-recaptcha>
    <button @click="getToken">Get reCAPTCHA Token</button> -->
    <div class="underlay h-hidden-mobile h-hidden-tablet-p"></div>

    <div class="columns is-gapless is-vcentered" v-if="!isMobile || showLogin">
      <div class="column is-relative is-8 h-hidden-mobile h-hidden-tablet-p">
        <div class="hero is-fullheight is-image">
          <div class="hero-body">
            <div class="container">
              <div class="columns">
                <div class="column">
                  <img class="hero-image" src="/@src/assets/illustrations/login/login.svg" alt="" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="column is-4 is-relative">
        <RouterLink :to="{ name: 'index' }" class="top-logo">
          <!-- <AnimatedLogoJMT width="60px" height="60px" /> -->
          <AnimatedLogoJMT />
          <!-- <AnimatedLogo width="38px" height="38px" /> -->
        </RouterLink>

        <label class="dark-mode ml-auto" tabindex="0"
          @keydown.space.prevent="(e) => (e.target as HTMLLabelElement).click()">
          <input type="checkbox" :checked="!darkmode.isDark" @change="darkmode.onChange" />
          <span></span>
        </label>
        <div class="is-form">
          <div class="hero-body">
            <div class="form-text" :class="[step !== 'login' && 'is-hidden']">
              <h2>{{ Project }}</h2>
              <p>Login to your Account</p>
            </div>
            <div class="form-text" :class="[step === 'login' && 'is-hidden']">
              <h2>Recover Account</h2>
              <p>Reset your account password.</p>
            </div>
            <form :class="[step !== 'login' && 'is-hidden']" class="login-wrapper" @submit.prevent="handleLogin">
              <!-- <VMessage color="primary">
                <div>
                  <strong class="pr-1">email:</strong>
                  <span>john.doe@cssninja.io</span>
                </div>
                <div>
                  <strong class="pr-1">password:</strong>
                  <span>ada.lovelace</span>
                </div>
              </VMessage> -->
              <VMessage color="danger" v-show="isError">{{ error }}</VMessage>
              <VField>
                <VControl icon="lnil lnil-envelope autv-icon">
                  <VLabel class="auth-label">Username</VLabel>
                  <VInput type="text" autocomplete="current-password" v-model="namaUser"
                    @input="changeUser($event.target.value)" />
                </VControl>
              </VField>
              <VField>
                <VControl icon="lnil lnil-lock-alt autv-icon" >
                  <VLabel class="auth-label">Password</VLabel>
                 <!--  <VInput type="password" autocomplete="current-password" v-model="kataSandi"
                    @input="changeUser($event.target.value)" />
                    -->
                    <Password v-model="kataSandi" toggleMask   placeholder=""  class="is-rounded w-100 is-login-pass" 
                    inputStyle=" padding-top: 14px;
                    height: 60px;
                    border-radius: 10px;
                    padding-left: 55px;
                    transition: all 0.3s;"
                    @input="changeUser($event.target.value)"/>
                </VControl>
              </VField>

              <VField v-if="wCaptcha">
                <div id="captcha_show">
                  <div id="icloudfare-captcha" class="cf-turnstile"></div>
                </div>
              </VField>

              <VField>
                <VControl class="is-flex">
                  <VLabel raw class="remember-toggle">
                    <VInput raw type="checkbox" />

                    <span class="toggler">
                      <span class="active">
                        <i aria-hidden="true" class="iconify" data-icon="feather:check"></i>
                      </span>
                      <span class="inactive">
                        <i aria-hidden="true" class="iconify" data-icon="feather:circle"></i>
                      </span>
                    </span>
                  </VLabel>
                  <VLabel raw class="remember-me">Remember Me</VLabel>
                  <a tabindex="0" @keydown.space.prevent="step = 'forgot-password'" @click="step = 'forgot-password'">
                    Forgot Password?
                  </a>
                </VControl>
              </VField>

              <div class="button-wrap has-help">
                <VButton id="login-button" icon="feather:arrow-right" :loading="isLoading" color="primary" type="submit"
                  size="big" rounded raised bold>
                  Confirm
                </VButton>
                <!-- <span>
                  Or
                  <RouterLink :to="{ name: 'auth-signup-1' }">Create</RouterLink>
                  an account.
                </span> -->
              </div>
            </form>

            <form :class="[step !== 'forgot-password' && 'is-hidden']" class="login-wrapper" @submit.prevent>
              <p class="recover-text">
                Enter your email and click on the confirm button to reset your password.
                We'll send you an email detailing the steps to complete the procedure.
              </p>

              <VField>
                <VControl icon="lnil lnil-envelope autv-icon">
                  <VLabel class="auth-label">Email Address</VLabel>
                  <VInput type="email" autocomplete="current-password" />
                </VControl>
              </VField>
              <div class="button-wrap">
                <VButton color="white" size="big" lower rounded @click="step = 'login'">
                  Cancel
                </VButton>
                <VButton color="primary" size="big" type="submit" lower rounded solid @click="step = 'login'">
                  Confirm
                </VButton>
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
    <div class="columns is-gapless is-vcentered" v-if="isMobile && !showLogin">
      <div class="column ">
        <div class="hero is-image" style="margin-top:50px;">
          <div class="hero-body">
            <div class="container">
              <div class="columns">
                <div class="column">
                  <img class="hero-image" src="/@src/assets/illustrations/login/login.svg" alt="" style="
    width: 100%;
    max-width: 100%;"/>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="column is-4 is-relative">
        <RouterLink :to="{ name: 'index' }" class="top-logo">
          <!-- <AnimatedLogoJMT width="60px" height="60px" /> -->
          <AnimatedLogoJMT />
          <!-- <AnimatedLogo width="38px" height="38px" /> -->
        </RouterLink>

        <label class="dark-mode ml-auto" tabindex="0"
          @keydown.space.prevent="(e) => (e.target as HTMLLabelElement).click()">
          <input type="checkbox" :checked="!darkmode.isDark" @change="darkmode.onChange" />
          <span></span>
        </label>
        <div class="is-form">
          <div class="hero-body">
            <div class="form-text text-center " style="
    margin-top: -40px;
    margin-bottom: 20px;" :class="[step !== 'login' && 'is-hidden']">
              <h2>{{ Project }}</h2>

            </div>
            <div class="button-wrap has-help">
              <VButton id="login-button" icon="feather:arrow-right" @click="showLogin = true" :loading="isLoading" class="w-100" color="primary"
                type="submit" size="big" rounded raised bold>
                Login To Your Account
              </VButton>

            </div>



          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.modern-login {
  position: relative;
  background: var(--white);
  min-height: 100vh;

  .column {
    &.is-relative {
      position: relative;
    }
  }

  .hero {
    &.has-background-image {
      position: relative;

      .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #5d4298 !important;
        opacity: 0.6;
      }
    }
  }

  .underlay {
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    width: 66.6%;
    height: 100%;
    background: #fdfdfd;
    z-index: 0;
  }

  .dark-mode {
    position: absolute;
    top: -64px;
    right: 38px;
    transform: scale(0.6);
    z-index: 2;
  }

  .top-logo {
    position: absolute;
    top: -70px;
    left: 0;
    right: 0;
    margin: 0 auto;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1;

    img {
      display: block;
      width: 100%;
      max-width: 50px;
      margin: 0 auto;
    }

    svg {
      height: 50px;
      width: 50px;
    }
  }

  .is-image {
    position: relative;
    border-right: 1px solid var(--fade-grey);

    .hero-image {
      position: relative;
      z-index: 2;
      display: block;
      margin: -80px auto 0;
      max-width: 60%;
      width: 60%;
    }
  }

  .is-form {
    position: relative;
    max-width: 420px;
    margin: 0 auto;

    form {
      animation: fadeInLeft 0.5s;
    }

    .form-text {
      padding: 0 20px;
      animation: fadeInLeft 0.5s;

      h2 {
        font-family: var(--font-alt);
        font-weight: 400;
        font-size: 2rem;
        color: var(--primary);
      }

      p {
        color: var(--muted-grey);
        margin-top: 10px;
      }
    }

    .recover-text {
      font-size: 0.9rem;
      color: var(--dark-text);
    }

    .login-wrapper {
      padding: 30px 20px;

      .control {
        position: relative;
        width: 100%;
        margin-top: 16px;

        .input {
          padding-top: 14px;
          height: 60px;
          border-radius: 10px;
          padding-left: 55px;
          transition: all 0.3s; // transition-all test

          &:focus {
            background: var(--fade-grey-light-6);
            border-color: var(--placeholder);

            ~.auth-label,
            ~.autv-icon i {
              color: var(--muted-grey);
            }
          }
        }

        .error-text {
          color: var(--danger);
          font-size: 0.8rem;
          display: none;
          padding: 2px 6px;
        }

        .auth-label {
          position: absolute;
          top: 6px;
          left: 55px;
          font-size: 0.8rem;
          color: var(--dark-text);
          font-weight: 500;
          z-index: 2;
          transition: all 0.3s; // transition-all test
        }

        .autv-icon,
        :deep(.autv-icon) {
          position: absolute;
          top: 0;
          left: 0;
          height: 60px;
          width: 60px;
          display: flex;
          justify-content: center;
          align-items: center;
          font-size: 24px;
          color: var(--placeholder);
          transition: all 0.3s;
        }

        &.has-validation {
          .validation-icon {
            position: absolute;
            top: 0;
            right: 0;
            height: 60px;
            width: 60px;
            display: none;
            justify-content: center;
            align-items: center;

            .icon-wrapper {
              height: 20px;
              width: 20px;
              display: flex;
              justify-content: center;
              align-items: center;
              border-radius: var(--radius-rounded);

              svg {
                height: 10px;
                width: 10px;
                stroke-width: 3px;
                color: var(--white);
              }
            }

            &.is-success {
              .icon-wrapper {
                background: var(--success);
              }
            }

            &.is-error {
              .icon-wrapper {
                background: var(--danger);
              }
            }
          }

          &.has-success {
            .validation-icon {
              &.is-success {
                display: flex;
              }

              &.is-error {
                display: none;
              }
            }
          }

          &.has-error {
            .input {
              border-color: var(--danger);
            }

            .error-text {
              display: block;
            }

            .validation-icon {
              &.is-error {
                display: flex;
              }

              &.is-success {
                display: none;
              }
            }
          }
        }

        &.is-flex {
          display: flex;
          align-items: center;

          a {
            display: block;
            margin-left: auto;
            color: var(--muted-grey);
            font-weight: 500;
            font-size: 0.9rem;
            transition: color 0.3s;

            &:hover,
            &:focus {
              color: var(--primary);
            }
          }

          .remember-me {
            font-size: 0.9rem;
            color: var(--muted-grey);
            font-weight: 500;
          }
        }
      }

      .button-wrap {
        margin: 40px 0;

        &.has-help {
          display: flex;
          align-items: center;

          >span {
            margin-left: 12px;
            font-family: var(--font);

            a {
              color: var(--primary);
              font-weight: 500;
              padding: 0 2px;
            }
          }
        }

        .button {
          height: 46px;
          width: 140px;
          margin-left: 6px;

          &:first-child {
            &:hover {
              opacity: 0.8;
            }
          }
        }
      }
    }
  }
}

.remember-toggle {
  width: 65px;
  display: block;
  position: relative;
  cursor: pointer;
  font-size: 22px;
  user-select: none;
  transform: scale(0.9);

  input {
    position: absolute;
    opacity: 0;
    cursor: pointer;

    &:checked~.toggler {
      border-color: var(--primary);

      .active,
      .inactive {
        transform: translateX(100%) rotate(360deg);
      }

      .active {
        opacity: 1;
      }

      .inactive {
        opacity: 0;
      }
    }
  }

  .toggler {
    position: relative;
    display: block;
    height: 34px;
    width: 61px;
    border: 2px solid var(--placeholder);
    border-radius: 100px;
    transition: all 0.3s; // transition-all test

    .active,
    .inactive {
      position: absolute;
      top: 2px;
      left: 2px;
      height: 26px;
      width: 26px;
      border-radius: var(--radius-rounded);
      background: black;
      display: flex;
      justify-content: center;
      align-items: center;
      transform: translateX(0) rotate(0);
      transition: all 0.3s ease;

      svg {
        color: var(--white);
        height: 14px;
        width: 14px;
        stroke-width: 3px;
      }
    }

    .inactive {
      background: var(--placeholder);
      border-color: var(--placeholder);
      opacity: 1;
      z-index: 1;
    }

    .active {
      background: var(--primary);
      border-color: var(--primary);
      opacity: 0;
      z-index: 0;
    }
  }
}

@media only screen and (max-width: 767px) {
  .modern-login {
    .top-logo {
      top: 30px;
    }

    .dark-mode {
      top: 36px;
      right: 44px;
    }

    .is-form {
      padding-top: 100px;
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .modern-login {
    .top-logo {
      svg {
        height: 60px;
        width: 60px;
      }
    }

    .dark-mode {
      top: -58px;
      right: 30%;
    }

    .columns {
      display: flex;
      height: 100vh;
    }
  }
}

/* ==========================================================================
Dark mode
========================================================================== */

.is-dark {
  .modern-login {
    background: var(--dark-sidebar);

    .underlay {
      background: var(--dark-sidebar-light-10);
    }

    .is-image {
      border-color: var(--dark-sidebar-light-10);
    }

    .is-form {
      .form-text {
        h2 {
          color: var(--primary);
        }
      }

      .login-wrapper {
        .control {
          &.is-flex {
            a:hover {
              color: var(--primary);
            }
          }

          .input {
            background: var(--dark-sidebar-light-4);

            &:focus {
              border-color: var(--primary);

              ~.autv-icon {
                i {
                  color: var(--primary);
                }
              }
            }
          }

          .auth-label {
            color: var(--light-text);
          }
        }

        .button-wrap {
          &.has-help {
            span {
              color: var(--light-text);

              a {
                color: var(--primary);
              }
            }
          }
        }
      }
    }
  }

  .remember-toggle {
    input {
      &:checked+.toggler {
        border-color: var(--primary);

        >span {
          background: var(--primary);
        }
      }
    }

    .toggler {
      border-color: var(--dark-sidebar-light-12);

      >span {
        background: var(--dark-sidebar-light-12);
      }
    }
  }
}
</style>
