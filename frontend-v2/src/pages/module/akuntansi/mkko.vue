<template>
  <section>
    <VCard>
      <span class="title-emr ml-3">MKKO RUMAH SAKIT</span>
      <Accordion :multiple="false" :activeIndex="activeTabAcc"  @tab-click="klikTab2($event)">
        <AccordionTab header="Real & Budget">
          <TargetMkko  v-if="activeTabAcc == 0"/>
        </AccordionTab>
        <AccordionTab header="Asumsi Utama">
          <div class="columns is-multiline"  v-if="activeTabAcc == 1">
            <div class="column is-12">
              <TabView class="tabview-custom mt-3" :scrollable="true" @tab-click="klikTab($event)">
                <TabPanel>
                  <template #header>
                    <span>BOR LOS TOI</span>
                  </template>
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <BorLosToi v-if="activeTab == 0" />
                    </div>
                  </div>
                </TabPanel>
                <TabPanel>
                  <template #header>
                    <span>Rekap Kunjungan Berdasarkan Tipe Pasien</span>
                  </template>
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <LapKunjunganTipe v-if="activeTab == 1" />
                    </div>
                  </div>

                </TabPanel>
                <TabPanel>
                  <template #header>
                    <span>Laporan Inpatient Re-admission Rate</span>
                  </template>
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <LapKunjunganKembali v-if="activeTab == 2" />
                    </div>
                  </div>
                </TabPanel>

                <TabPanel>
                  <template #header>
                    <span>Laporan Jumlah Tindakan Operasi</span>
                  </template>
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <LapJumlahOperasi v-if="activeTab == 3" />
                    </div>
                  </div>

                </TabPanel>
                <TabPanel>
                  <template #header>
                    <span>Laporan Jumlah Pegawai berdasarkan Jenis Pegawai</span>
                  </template>
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <LapJumlahPegawai v-if="activeTab == 4"/>
                    </div>
                  </div>


                </TabPanel>
              </TabView>
            </div>
          </div>
        </AccordionTab>
        <AccordionTab header="Income Statement">
          <LapPendapatan v-if="activeTabAcc == 2" />
        </AccordionTab>
        <AccordionTab header="Cash Flow Statement">
         <LapCashflow v-if="activeTabAcc == 3" />
        </AccordionTab>
        <AccordionTab header="Balance Sheet">
          <p class="m-0">
            <LapBalanceSheet  v-if="activeTabAcc == 4"/>
          </p>
        </AccordionTab>
        <AccordionTab header="Working Capital">
          <p class="m-0">
            <LapAging  v-if="activeTabAcc == 5"/>
          </p>
        </AccordionTab>
        <AccordionTab header="Matriks Operasional Lainnya">
          <p class="m-0">
            <LapWaktu  v-if="activeTabAcc == 6"/>
          </p>
        </AccordionTab>

      </Accordion>

    </VCard>
  </section>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import * as H from '/@src/utils/appHelper'
import { formatRp } from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import moment from 'moment'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Calendar from 'primevue/calendar';
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import TabView from 'primevue/tabview';
import AutoComplete from 'primevue/autocomplete';
import TabPanel from 'primevue/tabpanel';
import BorLosToi from '../laporan/laporan-borlostoi.vue';
import LapKunjunganTipe from '../laporan/laporan-pasien-by-kelompok.vue';
import LapJumlahOperasi from '../laporan/laporan-jumlah-operasi.vue';
import LapKunjunganKembali from '../laporan/laporan-readminisi.vue';
import LapJumlahPegawai from '../laporan/laporan-jumlah-pegawai.vue';
import LapPendapatan from '../laporan/laporan-pendapatan-rs.vue';
import LapPendapatanLain from '../laporan/laporan-pendapatan-lain.vue';
import LapPendapatanKeuangan from '../laporan/laporan-pendapatan-keuangan.vue';
import LapCashflow from '../laporan/lap-cashflow.vue';
import Accordion from 'primevue/accordion';
import AccordionTab from 'primevue/accordiontab';
import TargetMkko from './target-mkko.vue'
import LapBeban from './lap-beban.vue';
import LapBalanceSheet from './lap-balance-sheet.vue';
import LapWaktu from '../laporan/laporan-waktu-tunggu.vue';
import LapAging from '../laporan/laporan-aging.vue';


useHead({
  title: 'MKKO - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const activeTab: any = ref()

const activeTabPendapat: any = ref()
const activeTabAcc: any = ref(0)
// const activeTabAccc: any = ref(0)

const klikTab = (e: any) => {
  activeTab.value = e.index
}
const klikTab2 = (e: any) => {
  activeTabAcc.value = e.index
  if ( activeTabAcc.value  == 1) {
      activeTab.value = 0
    }
    if ( activeTabAcc.value  == 2) {
      activeTabPendapat.value = 0
    }
 
}
const klikTab3 = (e: any) => {
  activeTabPendapat.value = e.index
  // if ( activeTab.value  == 1) {
  //   activeTabPendapat.value = 0
  //   }
}
// watch(
//   () => activeTabAccc.value,
//   (newValue, oldValue) => {
//     if (newValue == 1) {
//       activeTab.value = 1
//     }
//   }
// )

</script>
<style lang="scss">
// @import '/@src/scss/module/akuntansi/mkko';
.p-tabview .p-tabview-panels {
  background: var(--background-grey);
  padding: 1rem;
  border: 0 none;
  color: #495057;
  border-bottom-right-radius: 3px;
  border-bottom-left-radius: 3px;
}
</style>
