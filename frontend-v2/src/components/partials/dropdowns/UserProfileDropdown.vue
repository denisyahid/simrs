<template>
  <VDropdown right spaced class="user-dropdown profile-dropdown">
    <template #button="{ toggle }">
      <a tabindex="0" class="is-trigger dropdown-trigger" aria-haspopup="true" @keydown.space.prevent="toggle"
        @click="toggle">
        <VAvatar :picture="icon" />
      </a>
    </template>

    <template #content>
      <div class="dropdown-head">
        <VAvatar size="large" :picture="icon" />

        <div class="meta">
          <span>{{ user.pegawai.namaLengkap }}</span>
          <span>{{ user.pegawai.jenisPegawai ? user.pegawai.jenisPegawai.jenispegawai : '-' }}</span>
        </div>
      </div>

      <a role="menuitem" class="dropdown-item is-media" @click="showProfile(item)">
        <div class="icon">
          <i aria-hidden="true" class="lnil lnil-user-alt"></i>
        </div>
        <div class="meta">
          <span>Profile</span>
          <span>View your profile</span>
        </div>
      </a>

      <hr class="dropdown-divider" />
      <!-- <a href="#" role="menuitem" class="dropdown-item is-media">
        <div class="icon">
          <i aria-hidden="true" class="lnil lnil-briefcase"></i>
        </div>
        <div class="meta">
          <span>Projects</span>
          <span>All my projects</span>
        </div>
      </a>

      <a href="#" role="menuitem" class="dropdown-item is-media">
        <div class="icon">
          <i aria-hidden="true" class="lnil lnil-users-alt"></i>
        </div>
        <div class="meta">
          <span>Team</span>
          <span>Manage your team</span>
        </div>
      </a>

      <hr class="dropdown-divider" /> -->

      <a href="#" role="menuitem" class="dropdown-item is-media">
        <div class="icon">
          <i aria-hidden="true" class="lnil lnil-cog"></i>
        </div>
        <div class="meta">
          <span>Settings</span>
          <span>Account settings</span>
        </div>
      </a>

      <hr class="dropdown-divider" />

      <div class="dropdown-item is-button">
        <VButton class="logout-button" icon="feather:log-out" color="primary" role="menuitem" raised fullwidth
          @click="logout">
          Logout
        </VButton>
      </div>
    </template>
  </VDropdown>
</template>
<script setup lang="ts">
import { useUserSession } from '/@src/stores/userSession'
import { ref, computed, watch, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useStorage } from '@vueuse/core'
const user = useUserSession().getUser()
const router = useRouter()
const item: any = ref({})


function logout() {
  useStorage('user_session', '')
  let colorWindow = window.localStorage.getItem('color-schema');
  useUserSession().logoutUser()
  window.localStorage.clear();
  window.localStorage.setItem('color-schema', colorWindow);
  router.push({
    name: 'auth-login',
  })
  window.location.href = '/auth/login'
}
const icon = user.pegawai.jenisKelamin_id && user.pegawai.jenisKelamin_id == 1 ? '/images/avatars/svg/vuero-2.svg' : '/images/avatars/svg/vuero-4.svg'

const showProfile = async (e: any) => {
  router.push({
    name: 'module-sysadmin-detail-pegawai',
    query: {
      id:  user.pegawai.id
    },
  })
}


</script>
