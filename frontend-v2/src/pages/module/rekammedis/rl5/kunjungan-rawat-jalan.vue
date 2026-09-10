<template>
  <VCard>
    <div>
      <h3 class="title is-5 mb-2 mr-1">Kunjungan Rawat Jalan</h3>
    </div>
    <div class="columns">
      <div class="column is-8">
        <div class="tile-grid tile-grid-v1 mt-5">
          <VPlaceholderPage v-if="dataSource.length == 0" title="Data Tidak Ditemukan."
            subtitle="Silakan Pilih Tanggal dan Ruangan untuk melihat Data" larger>
            <template #image>
              <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
              <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
            </template>
          </VPlaceholderPage>
          <div class="columns is-multiline" v-else-if="isLoading">
            <div class="column is-6" v-for="data in 2">
              <div class="tile-grid-item">
                <VPlaceloadWrap>
                  <VPlaceloadAvatar size="medium" />
                  <VPlaceloadText last-line-width="60%" class="mx-2" />
                  <VPlaceload class="mx-2" disabled />
                </VPlaceloadWrap>
              </div>
            </div>
          </div>
          <TransitionGroup name="list" tag="div" class="columns is-multiline" v-else>
            <div class="column is-6" v-for="(data, i) in dataSource">
              <div class="tile-grid-item">
                <div class="tile-grid-item-inner">
                  <VIconBox size="medium" :color="listColor[i]" rounded>
                    <i class="fas fa-users"></i>
                  </VIconBox>

                  <div class="meta">
                    <span class="dark-inverted mb-2">{{ data.jumlahkunjungan }}</span>
                    <span class="dark-inverted">{{ data.namaruangan }}</span>

                  </div>
                </div>
              </div>
            </div>
          </TransitionGroup>
        </div>

      </div>
      <div class="column is-4">
        <div class="columns is-multiline">
          <div class="column is-6">
            <h3 class="title is-5 mb-2 mr-1">Filters</h3>
          </div>
          <div class="column is-6">
            <a @click="clearFilter()" type="button" class="is-pulled-right mr-3" color="info" outlined raised>
              Clear All
            </a>
          </div>
          <div class="column is-12">
            <VField label="Periode">
              <VDatePicker v-model="item.periode" is-range color="pink" trim-weeks>

                <template #default="{ inputValue, inputEvents }">
                  <VField addons>
                    <VControl icon="feather:calendar">
                      <VInput :value="inputValue.start" v-on="inputEvents.start" />
                    </VControl>
                    <VControl>
                      <VButton static icon="feather:arrow-right" />
                    </VControl>
                    <VControl subcontrol icon="feather:calendar">
                      <VInput :value="inputValue.end" v-on="inputEvents.end" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>
          <div class="column is-12">
            <span>Departement</span>
            <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
              <VControl icon="feather:search">
                <AutoComplete v-model="item.departemenfk" :suggestions="d_Departement"
                  @complete="fecthDepartemen($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Departemen" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <span>Ruangan</span>
            <VField class="is-rounded-select is-autocomplete-select">
              <VControl icon="feather:search" class="prime-auto-select">
                <AutoComplete v-model="item.ruanganfk" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                  :optionLabel="'namaruangan'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'namaruangan'" placeholder=" Ruangan Asal" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <span>Kelompok Pasien</span>
            <VField class="is-rounded-select is-autocomplete-select">
              <VControl icon="feather:search" class="prime-auto-select">
                <AutoComplete v-model="item.kelompokpasienfk" :suggestions="d_KelompokPasien"
                  @complete="fetchKelompokPasien($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder=" Ruangan Asal" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VField class="is-autocomplete-select pt-3" v-slot="{ id }">
              <VButton color="warning" @click="exportExcel()" outlined icon="fas fa-file-excel">Export</VButton>
            </VField>
          </div>
          <div class="column is-12">
            <VButton @click="fetchData()" :loading="isLoading" type="button" icon="feather:search"
              class="is-fullwidth mr-3" color="info" raised>
              Apply Filters
            </VButton>
          </div>
        </div>
      </div>
    </div>
  </VCard>
</template>

<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, reactive, watch } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import moment from 'moment'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import * as XLSX from "xlsx"
import * as XLSXStyle from 'xlsx-js-style'
import AutoComplete from 'primevue/autocomplete'
useHead({
  title: 'RL5.2 Kunjungan Rawat Jalan' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = reactive({
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
})
const isLoading: any = ref(false)
const dataSource: any = ref([])
const d_Departement: any = ref([])
const d_Ruangan: any = ref([])
const d_KelompokPasien: any = ref([])
let listColor: any = ref(Object.keys(useThemeColors()))
const fetchData = async () => {
  isLoading.value = true;
  let dept = item.departemenfk ? item.departemenfk.value : ''
  let ruangan = item.ruanganfk ? item.ruanganfk.id : ''
  let tglAwal = moment(item.periode.start).format("YYYY-MM-DD 00:00:00");
  let tglAkhir = moment(item.periode.end).format("YYYY-MM-DD 23:59:59");
  await useApi().get(`/laporan/get-laporan-rl5b?departemenfk=${dept}&ruanganfk=${ruangan}&tglAwal=${tglAwal}&tglAkhir=${tglAkhir}`).then((response: any) => {
    isLoading.value = false;
    dataSource.value = response.data
  })
}
const fecthDepartemen = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/departemen_m?select=id,namadepartemen&param_search=namadepartemen&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Departement.value = response
  })
}
const fetchRuangan = async (filter: any) => {
  let dept = item.departemenfk ? item.departemenfk.value : ''
  const response = await useApi().get(`/rekammedis/get-ruangan-by-departement?idDepartement=${dept}&ruangan=${filter.query}&limit=10`)
  d_Ruangan.value = response.ruangan

}
const fetchKelompokPasien = async (filter: any) => {
  await useApi().get(`emr/dropdown/kelompokpasien_m?select=id,kelompokpasien&param_search=kelompokpasien&query=${filter.query}&limit=10`
  ).then((response) => {
    d_KelompokPasien.value = response
  })
}
const exportExcel = () => {
  const workbook = XLSX.utils.book_new();
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['Laporan RL 5.2 - Pengunjung Rawat Jalan'],
    [],
    ['NO', 'Jenis Kegiataan', 'Jumlah'],
    ...dataSource.value.map((e: any, index: number) => [
      index + 1,
      e.namaruangan,
      e.jumlahkunjungan
    ]),
  ]);

  // Defining style for the header (centered)
  const headerStyle = {
    alignment: {
      horizontal: 'center',
      vertical: 'center',
    },
    font: {
      color: { rgb: 'FFFFFF' },
      bold: true,
    },
    fill: { fgColor: { rgb: '807C7C' } },
  };

  // Applying header style
  const headerRange = XLSX.utils.decode_range(worksheet['!ref']);
  for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
    const headerCell = XLSX.utils.encode_cell({ r: 2, c: col });
    worksheet[headerCell].s = headerStyle;
  }

  // Setting column widths
  const columnWidths = [5, 40, 20];

  for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
    worksheet['!cols'] = worksheet['!cols'] || [];
    worksheet['!cols'][col] = { wch: columnWidths[col] };
  }

  // Centering the text in cell A1
  const titleCell = XLSX.utils.encode_cell({ r: 0, c: 0 });
  worksheet[titleCell].s = {
    alignment: {
      horizontal: 'center',
      vertical: 'center',
    },
    font: {
      bold: true,
      sz: 18,
    },
  };

  // Merging the first two rows
  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 2 } };
  worksheet['!merges'] = [mergeTitle];

  XLSX.utils.book_append_sheet(workbook, worksheet, 'Laporan RL 5.2 - Pengunjung Rawat Jalan', true);

  XLSXStyle.writeFile(workbook, 'Laporan RL 5.2 - Pengunjung Rawat Jalan.xlsx');
}
const clearFilter = () => {
  delete item.ruanganfk
  delete item.departemenfk
  delete item.kelompokpasienfk
}
fetchData();
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

.tile-grid-v1 {
  .tile-grid-item {
    @include vuero-s-card;

    border-radius: 14px;
    padding: 16px;

    .tile-grid-item-inner {
      display: flex;
      align-items: center;

      .meta {
        margin-left: 10px;
        line-height: 1.2;

        span {
          display: block;
          font-family: var(--font);

          &:first-child {
            color: var(--dark-text);
            font-family: var(--font-alt);
            font-weight: 600;
            font-size: 1rem;
          }

          &:nth-child(2) {
            color: var(--light-text);
            font-size: 0.9rem;
          }
        }
      }

      .dropdown {
        position: relative;
        margin-left: auto;
      }
    }
  }
}

.is-dark {
  .tile-grid {
    .tile-grid-item {
      @include vuero-card--dark;
    }
  }
}
</style>
