<template>
  <ConfirmDialog />
  <div class="business-dashboard hr-dashboard">
    <div class="columns is-multiline">
      <div class="column is-7 mt-3">
        <div class="columns is-multiline">
          <div class="block-header" style="background-color: #215E43;height: 20rem;">
            <div class="column is-6" style="padding-top: 0.2rem">
              <img src="/images/avatars/label/dashboard/ipsrp.png">
            </div>
            <div class="column is-6" style="padding-top: 4rem;">
              <span style="color:#F7F7F7"><i class="fas fa-dolly mr-3" aria-hidden="true" style="color:#F7F7F7"></i>
                Instalasi Pemeliharaan Sarana Rumah Sakit
              </span>
              <h3 class="pt-3 pb-1 title-dash">
                IPSRS
              </h3>
              <span style="color:#F7F7F7">Selamat Datang , {{ H.namaPegawai() }}</span>
            </div>
          </div>
        </div>
      </div>
      <div class="column is-5">
        <div class="dashboard-card is-gauge" style="height: 20rem;">
          <div class="column border-custom">
            <span style="font-weight: bold; font-size: 15px">Permintaan Non Medis
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="columns" style="margin-top:3rem">
    <div class="column is-7 p-0">
      <VCard style="height: 58px;" />
      <VTabs class="slider-tri" slider selected="permintaan" :tabs="[
                { label: 'Permintaan', value: 'permintaan' },
                { label: 'Penerimaan', value: 'penerimaan' },
              ]" style="margin-top: -57px;padding:5px">
        <template #tab="{ activeValue }">
          <p v-if="activeValue === 'permintaan'">
            <VCard class="card-order-permitaan">
              <VButton color="primary" RouterLink :to="{ name: 'module-ipsrs-rencana-usulan-permintaan-barang' }" raised
                style="left: 35.2rem;top: -2.5rem;padding: 15px;font-size: 14px;"><i class="fas fa-plus mr-3 p-0"
                  aria-hidden="true"></i>Usulan Baru</VButton>
              <div class="search-menu" style="margin-bottom : 1rem;margin-top:-14px">
                <VDatePicker v-model="item.qrangeDate" is-range color="pink" trim-weeks :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField addons>
                      <VControl icon="feather:calendar">
                        <VInput :value="inputValue.start" v-on="inputEvents.start" />
                      </VControl>
                      <VControl>
                        <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                      </VControl>
                      <VControl icon="feather:calendar">
                        <VInput :value="inputValue.end" v-on="inputEvents.end" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
                <div class="search-location" style="padding-right: 0px; margin-right: -15px;">
                  <i class="iconify" data-icon="feather:code"></i>
                  <input type="text" placeholder="No Order" v-model="item.noorder" style="margin-right:20px" />
                </div>
                <VButton color="primary" raised class="search-button" @click="fetchPermintaan()"
                  style="height:57px;color:whitesmoke;font-size: 14px;">
                  Cari Data </VButton>
              </div>
            </VCard>
          <div v-if="dataOrder.loading">
            <div v-for="key in 4" :key="key" class="column is-12">
              <VCard>
                <div class="tile-grid-item">
                  <div class="tile-grid-item-inner placeload-wrap">
                    <div class="columns">
                      <div class="column is-1">
                        <VPlaceloadAvatar rounded="sm" />
                      </div>
                      <div class="column">
                        <div class="column mb-4 pt-0">
                          <VPlaceload class="mx-2" width="30%" />
                        </div>
                        <div class="columns pl-5">
                          <VPlaceload class="mx-2" width="30%" />
                          <VPlaceload class="mx-2" width="30%" />
                          <VPlaceload class="mx-2" width="30%" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </VCard>
            </div>
          </div>
          </p>
        </template>
      </VTabs>
    </div>
  </div>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { useApi } from '/@src/composable/useApi'
import { ref, reactive, computed, watch } from 'vue'
import { useConfirm } from 'primevue/useconfirm'
import SpeedDial from 'primevue/speeddial';
import ConfirmDialog from 'primevue/confirmdialog'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import moment from 'moment'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'

useHead({
  title: 'Dashboard IPSRP - Transmedic',
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const confirm = useConfirm()
const d_Ruangan: any = ref([])
const userLogin = useUserSession().getUser()
const dataOrder: any = ref([])
const item: any = reactive({
  qrangeDate: {
    start: new Date(),
    end: new Date()
  },
  datePenerimaan: {
    start: new Date(),
    end: new Date()
  },
  dateDistribusi: {
    start: new Date(),
    end: new Date()
  },
})

const fetchPermintaan = async () => {

}
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/dashboard/ipsrp.scss';
@import '/@src/scss/module/dashboard/rawat-jalan.scss';

.slider-tri {
  .tabs-inner {
    margin-right: 26rem !important;
  }
}
</style>
