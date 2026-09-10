<script setup lang="ts">
import { computed, ref, watch, reactive } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import type { VAvatarProps, VAvatarColor } from '/@src/components/base/avatar/VAvatar.vue'
import type { UserPopover } from '/@src/models/users'
import { popovers } from '/@src/data/users/userPopovers'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { usePanels } from '/@src/stores/panels'
import { useStorage } from '@vueuse/core'
import * as H from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
export type Menu = Model.Global.Menu
export type NavbarTheme = 'default' | 'colored' | 'fade'
export type SubnavId =
  | 'closed'
  | 'home'
  | 'layouts'
  | 'elements'
  | 'components'
  | 'search'

const colorPasien: any = ['h-yellow', 'info', 'h-purple', 'danger', 'success', 'warning']
const avatarColor: any = [

  'success'
  , 'info'
  , 'warning'
  , 'danger'
  , 'h-purple'
  , 'h-orange'
  , 'h-blue'
  , 'h-green'
  , 'h-red'
  , 'h-yellow'
]
const props: any = withDefaults(
  defineProps<{
    theme?: NavbarTheme
    nowrap?: boolean
  }>(),
  {
    theme: 'default',
  }
)
const isLoading: any = ref(false)
const viewWrapper: any = useViewWrapper()
viewWrapper.setFullWidth(true)
// console.log(viewWrapper.isFullWidth)
const panels: any = usePanels()
const route: any = useRoute()
const filter: any = ref('')
const isMobileSidebarOpen: any = ref(false)
const activeMobileSubsidebar: any = ref('layouts')
const activeSubnav: any = ref<SubnavId>('closed')
let resultP = reactive([])
const filteredPasiens: any = ref([])
const namaProfile = useUserSession().getProfile().namaexternal
const router = useRouter()

const asyncPasien = async (q: any) => {
  isLoading.value = true
  const response = await useApi().get(`/general/pasien-registrasi?query=${q}`)
  let datas = []
  if (response.length > 0) {
    let i = 0
    for (let x = 0; x < response.length; x++) {
      const element = response[x];
      let ini = element.namapasien.split(' ')
      let init = element.namapasien.substr(0, 1)
      if (ini.length > 1) {
        init = init + ini[1].substr(0, 1)
      }
      element.initials = init
      element.avatar = ''
      element.color = avatarColor[i]
      i++
      if (i >= 11) {
        i = 0
      }
      datas.push(element)
    }
  }
  isLoading.value = false
  return datas
}

const toggleSubnav = (subnav: SubnavId) => {
  if (activeSubnav.value === subnav) {
    activeSubnav.value = 'closed'
  } else {
    activeSubnav.value = subnav
  }
}
const toggleSubnavMobile = (subnav: SubnavId) => {

  if (activeMobileSubsidebar.value === subnav) {
    activeMobileSubsidebar.value = 'closed'
  } else {
    activeMobileSubsidebar.value = subnav
  }
  console.log(activeMobileSubsidebar.value);

}

const getAvatarData = (user: any): VAvatarProps => {
  return {
    picture: user.avatar,
    initials: user.initials,
    color: user.color as VAvatarColor,
  }
}


const gotoLink = (name: any) => {
  router.push({
    name: name,
  })
}
const showForm = (e: any) => {

  H.cacheHelper().set('xxx_cache_menu', undefined)
  router.replace({
    name: 'module-emr-profile-pasien',
    query: {
      nocmfk: e.id,
      norec_pd: e.norec,
      isfull: 'true'
    }
  })
}
const toggleEnter = (params: any) => {
  router.push({
    name: 'module-registrasi-list-pasien',
    query: {
      norm: params,
    },
  })
}
const toggleDashboard = () => {
  const user = useUserSession().getUser()
  let lockRoute = H.cacheHelper().get('lockedRoute');
  if (user.kelompokUser.menu != null) {
    if(lockRoute != null || lockRoute != undefined) {
      router.push({
        name: lockRoute,
      })
    }else {
      router.push({
        name: user.kelompokUser.menu,
      })
    }
  } else {
    router.push({
      name: 'module-dashboard-registrasi',
    })
  }

}
let timerId :any =ref(null) ;
watch(
  () => filter.value,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      if (!newValue) {
        return []
      }
      if (newValue.length <= 3) return
      isLoading.value = true
      if (timerId.value) {
        clearTimeout(timerId.value);
      }
      timerId.value =
      setTimeout(function() {
          useApi().get(`/general/pasien-registrasi?query=${newValue}`).then((response: any) => {
            let datas = []
            if (response.length > 0) {
              let i = 0
              for (let x = 0; x < response.length; x++) {
                const element = response[x];
                let ini = element.namapasien.split(' ')
                let init = element.namapasien.substr(0, 1)
                if (ini.length > 1) {
                  init = init + ini[1].substr(0, 1)
                }
                element.initials = init
                element.avatar = ''
                element.color = avatarColor[i]
                i++
                if (i >= 11) {
                  i = 0
                }
                datas.push(element)
              }
            }
            isLoading.value = false
            filteredPasiens.value = datas
          }).catch((e: any) => {
            isLoading.value = false
          })
      }, 2000);
    }
  }
)

watch(
  () => route.fullPath,
  () => {
    activeSubnav.value = 'closed'
    isMobileSidebarOpen.value = false
  }
)


let listMenu = useStorage('list_menu', [])
const api = useApi()
// if (listMenu.value.length == 0) {
await api.get(
  `/general/menu/list-menu?idUser=${useUserSession().getUser().id}`
).then((response: any) => {
  listMenu.value = response
}, (error) => {
  listMenu.value = []
  console.log(error)
})
// }
</script>
<template>
  <div class="navbar-layout">
    <div class="app-overlay"></div>

    <!-- Mobile navigation -->
    <MobileNavbar :is-open="isMobileSidebarOpen" @toggle="isMobileSidebarOpen = !isMobileSidebarOpen">
      <template #brand>
        <RouterLink :to="{ name: 'index' }" class="navbar-item is-brand">
          <AnimatedLogoMobile />
        </RouterLink>

        <div class="brand-end">
          <NotificationsMobileDropdown />
          <UserProfileDropdown />
        </div>
      </template>
    </MobileNavbar>

    <!-- Mobile sidebar links -->
    <MobileSidebar :is-open="isMobileSidebarOpen" @toggle="isMobileSidebarOpen = !isMobileSidebarOpen">
      <template #links>
        <!-- <li>
          <a :class="[activeMobileSubsidebar === 'dashboard' && 'is-active']" tabindex="0"
            @keydown.space.prevent="toggleDashboard()" @click="toggleDashboard()">
            <i aria-hidden="true" class="iconify" data-icon="feather:activity"></i>
          </a>
        </li> -->
        <li>
          <a :class="[activeMobileSubsidebar === 'layouts' && 'is-active']" tabindex="0"
            @keydown.space.prevent="toggleSubnavMobile('layouts')" @click="toggleSubnavMobile('layouts')">
            <i aria-hidden="true" class="iconify" data-icon="feather:grid"></i>
          </a>
        </li>
        <!-- <li :class="[activeMobileSubsidebar === 'elements' && 'is-active']" tabindex="0"
          @keydown.space.prevent="activeMobileSubsidebar = 'elements'" @click="activeMobileSubsidebar = 'elements'">
          <a>
            <i aria-hidden="true" class="iconify" data-icon="feather:box"></i>
          </a>
        </li>
        <li :class="[activeMobileSubsidebar === 'components' && 'is-active']" tabindex="0"
          @keydown.space.prevent="activeMobileSubsidebar = 'components'" @click="activeMobileSubsidebar = 'components'">
          <a>
            <i aria-hidden="true" class="iconify" data-icon="feather:cpu"></i>
          </a>
        </li>
        <li>
          <RouterLink :to="{ name: 'messaging-v1' }">
            <i aria-hidden="true" class="iconify" data-icon="feather:message-circle"></i>
          </RouterLink>
        </li> -->
      </template>

      <template #bottom-links>
        <li>
          <a tabindex="0" @keydown.space.prevent="toggleSubnavMobile('search')" @click="toggleSubnavMobile('search')">
            <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
          </a>
        </li>
        <li>
          <a  @click="toggleDashboard()">
            <i aria-hidden="true" class="iconify" data-icon="feather:activity"></i>
          </a>
        </li>
      </template>
    </MobileSidebar>

    <!-- Mobile subsidebar links -->
    <Transition name="slide-x">
      <LayoutsMobileSubsidebar v-if="isMobileSidebarOpen && activeMobileSubsidebar === 'layouts'" />
      <DashboardsMobileSubsidebar v-else-if="isMobileSidebarOpen && activeMobileSubsidebar === 'dashboard'" />
      <ComponentsMobileSubsidebar v-else-if="isMobileSidebarOpen && activeMobileSubsidebar === 'components'" />
      <ElementsMobileSubsidebar v-else-if="isMobileSidebarOpen && activeMobileSubsidebar === 'elements'" />
      <div class="mobile-subsidebar" v-else-if="isMobileSidebarOpen && activeMobileSubsidebar === 'search'" >
        <div class="inner">
          <div class="sidebar-title">
            <h3>Search</h3>
          </div>
          <ul class="submenu">
            <li>
              <div class="centered-search">
                <div class="field">
                  <div class="control has-icon">
                    <VField>
                      <VControl>
                        <VInput v-model="filter" type="text" class="input is-rounded search-input"
                          placeholder="Search menu..." :loading="isLoading" @keydown.enter.prevent="toggleEnter(filter)" />
                      </VControl>
                    </VField>

                    <div class="form-icon">

                      <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
                    </div>
                  </div>
                </div>
            </div>
            </li>
          </ul>
        </div>
      </div>
    </Transition>

    <!-- Desktop navigation -->
    <Navbar :theme="props.theme">
      <template #title>
        <!-- <RouterLink :to="{ name: 'index' }" class="brand"> -->
        <div class="brand" @click="toggleDashboard">
          <AnimatedLogoJMT width="38" height="38" />
        </div>
        <!-- </RouterLink> -->

        <div class="separator"></div>
        <LogoRS width="38" height="38" class="mr-3" />
        <!-- <ProjectsQuickDropdown /> -->
        <div>
          <h1 class="title is-5 mt-0 ml-3 mb-0">{{ namaProfile }}</h1>
          <p class="title is-4  mt-0 ml-3">{{ viewWrapper.pageTitle }}</p>
        </div>
      </template>

      <template #toolbar>
        <Toolbar class="desktop-toolbar">
          <ToolbarNotification />

          <a class="toolbar-link right-panel-trigger" tabindex="0" @keydown.space.prevent="panels.setActive('activity')"
            @click="panels.setActive('activity')">
            <i aria-hidden="true" class="iconify" data-icon="feather:grid"></i>
          </a>
        </Toolbar>

        <LayoutSwitcher />
        <UserProfileDropdown right />
      </template>


      <template #links>
        <div class="centered-links" :class="[activeSubnav === 'search' && 'is-hidden']">
          <!-- <a :class="[
            (activeSubnav === 'home' || route.path.startsWith('/navbar/dashboards')) &&
            'is-active',
          ]" class="centered-link centered-link-toggle" tabindex="0" @keydown.space.prevent="toggleSubnav('home')"
            @click="toggleSubnav('home')">
            <i aria-hidden="true" class="iconify" data-icon="feather:activity"></i>
            <span>Dashboards</span>
          </a> -->

          <a :class="[
      (activeSubnav === 'home' || route.path.startsWith('/navbar/dashboards')) &&
      'is-active',
    ]" class="centered-link centered-link-toggle" tabindex="0" @keydown.space.prevent="toggleDashboard()"
            @click="toggleDashboard()">
            <i aria-hidden="true" class="iconify" data-icon="feather:activity"></i>
            <span>Dashboards</span>
          </a>

          <a :class="[
      (activeSubnav === 'layouts' || route.path.startsWith('/navbar/layouts')) &&
      'is-active',
    ]" class="centered-link centered-link-toggle" tabindex="0" @keydown.space.prevent="toggleSubnav('layouts')"
            @click="toggleSubnav('layouts')">
            <i aria-hidden="true" class="iconify" data-icon="feather:grid"></i>
            <span>Navigation</span>
          </a>
          <!-- <a
            :class="[activeSubnav === 'elements' && 'is-active']"
            class="centered-link centered-link-toggle"
            tabindex="0"
            @keydown.space.prevent="toggleSubnav('elements')"
            @click="toggleSubnav('elements')"
          >
            <i aria-hidden="true" class="iconify" data-icon="feather:box"></i>
            <span>Elements</span>
          </a>-->
          <!-- <a :class="[activeSubnav === 'components' && 'is-active']" class="centered-link centered-link-toggle"
            tabindex="0">
            <RouterLink :to="{ name: 'module-registrasi-pasien-lama' }">
              <i aria-hidden="true" class="iconify" data-icon="feather:cpu"></i>
              <span>Components</span>
            </RouterLink>

          </a> -->
          <a class="centered-link centered-link-search" tabindex="0" @keydown.space.prevent="toggleSubnav('search')"
            @click="toggleSubnav('search')">
            <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
            <span>Search</span>
          </a>
        </div>

        <div class="centered-search" :class="[activeSubnav !== 'search' && 'is-hidden']">
          <div class="field">
            <div class="control has-icon">
              <VField>
                <VControl>
                  <VInput v-model="filter" type="text" class="input is-rounded search-input"
                    placeholder="Search pasien..." :loading="isLoading" @keydown.enter.prevent="toggleEnter(filter)" />
                </VControl>
              </VField>

              <div class="form-icon">

                <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
              </div>
              <div class="form-icon is-right" tabindex="0" @keydown.space.prevent="toggleSubnav('search')"
                @click="toggleSubnav('search')">
                <div class="loader is-loading" v-if="isLoading"></div>
                <i aria-hidden="true" class="iconify" data-icon="feather:x"></i>
              </div>
              <div v-if="filteredPasiens.length > 0" class="search-results has-slimscroll is-active">
                <div v-for="pasien in filteredPasiens" :key="pasien.id" class="search-result is-clickable"
                  @click="showForm(pasien)">
                  <!-- <VAvatar v-bind="getAvatarData(pasien)" /> -->
                  <VAvatar size="small" :color="pasien.color" :initials="pasien.initials" />
                  <div class="meta">
                    <span>{{ pasien.namapasien }}</span>
                    <span>{{ pasien.nocm }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>
      <template #subnav>
        <div  :class="[
          !(activeSubnav === 'closed' || activeSubnav === 'search') && 'is-active',
        ]" class="navbar-subnavbar">
          <DashboardsSubnav :class="[activeSubnav === 'home' && 'is-active']" />

          <LayoutsSubnav :class="[activeSubnav === 'layouts' && 'is-active']"/>

          <ElementsSubnav :class="[activeSubnav === 'elements' && 'is-active']" />

          <ComponentsSubnav :class="[activeSubnav === 'components' && 'is-active']" />

        </div>
      </template>
    </Navbar>

    <LanguagesPanel />
    <ActivityPanel />
    <TaskPanel />

    <VViewWrapper top-nav>
      <VPageContentWrapper :fullWidth="viewWrapper.isFullWidth">
        <template v-if="props.nowrap">
          <slot></slot>
        </template>
        <VPageContent v-else class="is-relative">
          <div class="is-navbar-lg">
            <div class="page-title has-text-centered">
              <!-- Mobile Page Title -->
              <div class="title-wrap">
                <h1 class="title is-4">{{ viewWrapper.pageTitle }}</h1>
              </div>

              <Toolbar class="mobile-toolbar">
                <!-- <ToolbarNotification /> -->

                <a class="toolbar-link right-panel-trigger" tabindex="0"
                  @keydown.space.prevent="panels.setActive('activity')" @click="panels.setActive('activity')">
                  <i aria-hidden="true" class="iconify" data-icon="feather:grid"></i>
                </a>
              </Toolbar>
            </div>

            <slot></slot>
          </div>
        </VPageContent>
      </VPageContentWrapper>
    </VViewWrapper>
  </div>
  <div class="overlay-menu" v-if="activeSubnav != 'closed'"  @keydown.space.prevent="toggleSubnav('closed')"
         @click="toggleSubnav('closed')"></div>
</template>
<style lang="scss">
.overlay-menu {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100%;
    z-index: 98;
}
</style>
