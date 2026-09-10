<template>
  <section>
    <ConfirmDialog />
    <div class="columns is-multiline">
      <VCard radius="rounded" style="padding-bottom: 0px">
        <div class="column c-title pt-2 mb-5">
          <div class="columns ">
            <h3 class="title is-5 mb-2 mr-1 p-3">Surat Keterangan Lahir</h3>
          </div>
        </div>
        <div class="columns is-multiline pb-4">
          <div class="column is-12 mt-3">
            <div class="columns is-multiline" style="justify-content: flex-end;">
              <div class="column is-6 pb-0 pl-6">
                <VField label="Periode" style="margin-bottom: 6px;" />
                <VDatePicker v-model="item.qFilterTgl" is-range color="pink" locale="id" trim-weeks>
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
              </div>
              <div class="column is-5 pb-0">
                <VField label="Cari" class="is-pulled-right justify-content-end">
                  <VInput v-model="filters.global.value" placeholder="Keyword Search" style="width:300px" />
                </VField>
              </div>
              <div class="column is-1 mt-5">
                <VIconButton color="success" icon="fas fa-search" class="mt-1" @click="cariRiwayat()"
                  :loading="isLoading" />
              </div>
            </div>
          </div>
          <div class="column is-12 mt-3">
            <VButton rounded color="primary" class="ml-5" icon="fas fa-plus" raised bold @click="openModalAdd($event)"
              :loading="isLoading">
              Tambah Data
            </VButton>

            <VButton rounded color="info" class="ml-5" icon="fas fa-book-medical" raised bold
              @click="openModalCariPasien($event)" :loading="isLoading">
              Cari Pasien
            </VButton>
          </div>
        </div>
        <div class="column" v-if="isLoading">
          <VPlaceloadWrap v-for="s in 25">
            <VPlaceload class="mx-2 mb-3" />
            <VPlaceload class="mx-2" />
          </VPlaceloadWrap>
        </div>
        <div class="column is-12" v-else-if="dataLahir.length === 0">
          <div class="update-item is-dark-bordered-12 " style="display: block;">
            <div class="search-results-wrapper">
              <div class="search-results-body ">
                <div class="page-placeholder">
                  <div class="placeholder-content">
                    <img class="light-image" style=" max-width: 340px;" :src="H.assets().iconNotFound_rev" alt="" />
                    <img class="dark-image" style=" max-width: 340px;" :src="H.assets().iconNotFound_rev" alt="" />
                    <h3>{{ H.assets().notFound }}</h3>
                    <p class="is-larger">
                      {{ H.assets().notFoundSubtitle }}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="column is-12" v-else>
          <DataTable :value="dataLahir" class="p-datatable-sm" :paginator="true" :rows="10"
            :rowsPerPageOptions="[5, 10, 25, 50, 100, 150, 200]" scrollable
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines
            v-model:filters="filters" filterDisplay="menu">
            <template #empty> {{ H.assets().notFound }}</template>
            <Column header="Action">
              <template #body="slotProps">
                <div class="columns is-multiline">
                  <div class="column">
                    <button type="button" aria-hidden="false" class="button is-outlined is-raised is-info mr-2"
                      @click="detailData(slotProps.data)">
                      <span class="icon"><i aria-hidden="true" class="fas fa-eye"></i></span>
                    </button>
                    <button type="button" aria-hidden="false" class="button is-outlined is-raised is-warning mr-2"
                      @click="editData(slotProps.data)">
                      <span class="icon"><i aria-hidden="true" class="lnil lnil-pencil"></i></span>
                    </button>
                    <button type="button" aria-hidden="false" class="button is-outlined is-raised is-danger mr-2"
                      @click="deleteData(slotProps.data)">
                      <span class="icon"><i aria-hidden="true" class="lnil lnil-trash"></i></span>
                    </button>
                    <button type="button" aria-hidden="false" class="button is-outlined is-raised is-primary mr-2"
                      @click="cetakSurat(slotProps.data)">
                      <span class="icon"><i aria-hidden="true" class="fas fa-print"></i></span>
                    </button>
                  </div>
                </div>
              </template>
            </Column>
            <Column field="noskl" header="No SKL"></Column>
            <Column field="tanggal" header="Tanggal"></Column>
            <Column field="jam" header="Jam"></Column>
            <Column field="norm" header="NRM"></Column>
            <Column field="nama" header="Nama Ibu"></Column>
          </DataTable>
        </div>
      </VCard>
    </div>

    <VModal is="form" :open="modalAddData"
      :title="input.action == 'create' ? 'Tambah Keterangan Lahir' : 'Ubah Keterangan Lahir'" size="medium"
      actions="right" @submit.prevent="modalAddData = false" @close="modalAddData = false">
      <template #content>
        <div class="modal-form">
          <!-- <div class="columns is-multiple">
                  <div class="column is-6">
                      <VField vertical label="Nama Istri / Ibu *" required
                          class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                          <VControl icon="fas fa-user-alt" fullwidth class="prime-auto ">
                              <AutoComplete v-model="input.pasienRM" :suggestions="listPasien"
                              @complete="getPasien($event)" :optionLabel="'nocm'" :dropdown="true" :minLength="4"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'display'"
                              placeholder="Ketik Nama Istri" @change="handlerIstri($event)"/>
                          </VControl>
                      </VField>
                  </div>
                  <div class="column is-12">
                      <VField vertical label="Nama Suami / Bapak">
                          <VControl icon="fas fa-user-alt" fullwidth>
                              <VInput placeholder="Ketik Nama Suami" v-model="input.namaayah" :disabled="disabledInput" />
                          </VControl>
                      </VField>
                  </div>
              </div> -->
          <!-- <div class="columns is-multiple">
                  <div class="column is-6">
                      <VField vertical label="Nama Anak">
                          <VControl icon="fas fa-baby" fullwidth>
                              <VInput placeholder="Ketik Nama Anak" v-model="input.namaanak" />
                          </VControl>
                      </VField>
                      <VField vertical label="Nama Istri / Ibu">
                          <VControl icon="fas fa-user-alt" fullwidth>
                              <VInput placeholder="Ketik Nama Ibu" v-model="input.namaayah" :disabled="disabledInput" />
                          </VControl>
                      </VField>
                  </div>
                  <div class="column is-6">
                      <VField vertical label="Pekerjaan Istri / Ibu">
                          <VControl icon="fas fa-building" fullwidth>
                              <VInput placeholder="Ketik Pekerjaan" v-model="input.pekerjaan" />
                          </VControl>
                      </VField>
                  </div>
              </div> -->
          <!-- <div class="columns is-multiple">
                      <VField vertical label="Nama Istri / Ibu">
                          <VControl icon="fas fa-user-alt" fullwidth>
                              <VInput placeholder="Ketik Nama Suami" v-model="input.namaibu" :disabled="disabledInput" />
                          </VControl>
                      </VField>
                  </div>
              </div> -->
          <div class="columns is-multiple">
            <div class="column is-6">
              <VField vertical label="Tanggal Lahir Anak">
                <VControl class="prime-auto">
                  <Calendar iconDisplay="input" id="calendar-24h" v-model="input.tglLahir" showTime showIcon
                    dateFormat="yy-mm-dd" hourFormat="24" class="w-100" showButtonBar />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField vertical label="Jenis Kelamin Anak *" required class="is-rounded-select_Z  is-autocomplete-select"
                v-slot="{ id }">
                <VControl icon="fas fa-book-medical" fullwidth class="prime-auto ">
                  <AutoComplete v-model="input.jenisKelamin" :suggestions="listKelamin" @complete="getJenisKelamin()"
                    :dropdown="true" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'jeniskelamin'"
                    placeholder="Pilih Jenis Kelamin" />
                  <!-- <Dropdown v-model="input.poliKontrol" :options="d_Subspesialis" :optionLabel="'namaPoli'"
                              placeholder="Sub/Spesialis" style="width: 100%;" :filter="true" @change="changeSpe(input.poliKontrol)" /> -->
                </VControl>
              </VField>
            </div>
          </div>
          <div class="columns is-multiple">
            <div class="column is-6">
              <VField vertical label="Tinggi Anak *">
                <VControl icon="fas fa-arrows-alt-v" fullwidth>
                  <VInput type="number" placeholder="Ketik Tinggi (cm)" v-model="input.tinggianak" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField vertical label="Berat Anak *">
                <VControl icon="fas fa-weight" fullwidth>
                  <VInput type="number" placeholder="Ketik Berat (gram)" v-model="input.beratanak" />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="columns is-multiple">
            <div class="column is-12">
              <VField vertical label="No RM Ibu" required class="is-rounded-select_Z  is-autocomplete-select"
                v-slot="{ id }">
                <VControl icon="fas fa-book-medical" fullwidth class="prime-auto ">
                  <VInput placeholder="Ketik No RM Ibu" v-model="input.normIbu" />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="columns is-multiple">
            <div class="column is-12">
              <VField vertical label="Dokter Penolong *" required class="is-rounded-select_Z  is-autocomplete-select"
                v-slot="{ id }">
                <VControl icon="fas fa-book-medical" fullwidth class="prime-auto ">
                  <AutoComplete v-model="input.dokterPenolong" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                    :dropdown="true" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                    placeholder="Pilih Dokter Penolong" />
                  <!-- <Dropdown v-model="input.poliKontrol" :options="d_Subspesialis" :optionLabel="'namaPoli'"
                              placeholder="Sub/Spesialis" style="width: 100%;" :filter="true" @change="changeSpe(input.poliKontrol)" /> -->
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </template>
      <template #action>
        <VButton type="submit" color="primary" raised :loading="isSubmit" @click="submitData()">
          Kirim
        </VButton>
      </template>
    </VModal>

    <VModal :open="modalDetailData" size="medium" :noclose="false" title="Detail Reservasi" actions="right"
      @close="modalDetailData = false">
      <template #content>
        <div class="columns is-multiline">
          <div class="column is-12">
            <h3 class="title is-5 mb-2">Nomor Surat Keterangan Lahir : {{ detail.noskl }}</h3>
          </div>
          <div class="column is-12 no-padding-bottom">
            <h3 class="title is-5 mb-2">Nama Istri</h3>
          </div>
          <div class="column is-12 no-padding-top">
            <p>{{ detail.namasuami }}</p>
          </div>
          <!-- <div class="column is-12 no-padding-bottom">
                <h3 class="title is-5 mb-2">Nama Suami</h3>
              </div>
              <div class="column is-12 no-padding-top">
                <p>{{ detail.namasuami }}</p>
              </div> -->
          <div class="column is-12 no-padding-bottom">
            <h3 class="title is-5 mb-2">Nama Anak</h3>
          </div>
          <div class="column is-12 no-padding-top">
            <p>{{ detail.namaanak }}</p>
          </div>
          <div class="column is-12 no-padding-bottom">
            <h3 class="title is-5 mb-2">Nomor Ruang Medis</h3>
          </div>
          <div class="column is-12 no-padding-top">
            <p>{{ detail.norm }}</p>
          </div>
          <div class="column is-12 no-padding-bottom">
            <h3 class="title is-5 mb-2">Dilahirkan Pada</h3>
          </div>
          <div class="column is-12 no-padding-top">
            <p>{{ H.formatDateToLocalString(detail.tanggal) }}</p>
          </div>
          <div class="column is-12 no-padding-bottom">
            <h3 class="title is-5 mb-2">Berat Bayi</h3>
          </div>
          <div class="column is-12 no-padding-top">
            <p>{{ detail.berat }}</p>
          </div>
          <div class="column is-12 no-padding-bottom">
            <h3 class="title is-5 mb-2">Tinggi Bayi</h3>
          </div>
          <div class="column is-12 no-padding-top">
            <p>{{ detail.tinggi }}</p>
          </div>
          <div class="column is-12 no-padding-bottom">
            <h3 class="title is-5 mb-2">Nomor Registrasi</h3>
          </div>
          <div class="column is-12 no-padding-top">
            <p>{{ detail.noregistrasi }}</p>
          </div>

        </div>
      </template>
    </VModal>

    <VModal is="form" :open="modalCariPasien" size="large" title="Cari Pasien" actions="right"
      @submit.prevent="modalCariPasien = false" @close="modalCariPasien = false">
      <template #content>
        <div class="column is-12">
          <DataTable :value="dataSource" class="p-datatable-sm" :loading="isPlaceLoad" :paginator="true" :rows="rows"
            :totalRecords="totalRecords.value" :rowsPerPageOptions="[5, 10, 25, 50]" scrollable
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines @page="onPageChange($event)">
            <template #empty> {{ H.assets().notFound }}</template>
            <template #header>
              <div class="columns is-multiline pb-3">
                <div class="column is-10">
                  <div class="columns is-multiline" style="justify-content: flex-end;">
                    <div class="column is-3 pb-0">
                      <VField label="Cari">
                        <VInput v-model="item.namapasien" placeholder="Keyword Search" style="width:300px" />
                      </VField>
                    </div>
                    <div class="column is-1 mt-5">
                      <VIconButton color="success" icon="fas fa-search" class="mt-1" @click="fetchPasien"
                        :loading="isPlaceLoad" />
                    </div>
                  </div>
                </div>
              </div>
            </template>
            <Column field="namapasien" header="Nama Pasien"></Column>
            <Column field="tgllahir" header="Tanggal Lahir"></Column>
            <Column field="jeniskelamin" header="Jenis Kelamin"></Column>
            <Column field="nocm" header="No RM"></Column>
            <Column field="alamatlengkap" header="Alamat Pasien"></Column>
          </DataTable>
        </div>
      </template>
    </VModal>

  </section>
</template>
<script setup lang=ts>
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import { useThemeColors } from '/@src/composable/useThemeColors'
import * as H from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Calendar from 'primevue/calendar';
import Dropdown from 'primevue/dropdown';
import { FilterMatchMode } from 'primevue/api'
import InputText from 'primevue/inputtext';
import sleep from '/@src/utils/sleep'
import moment from 'moment'
import AutoComplete from 'primevue/autocomplete';
import { onMounted } from 'vue'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from "primevue/useconfirm"

useHead({
  title: 'Surat Keterangan Lahir - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)


const route = useRoute();
const confirm = useConfirm();

const d_Dokter: any = ref([]);
const isLoading: any = ref(false);
const disabledInput: any = ref(false);
const modalAddData: any = ref(false);
const modalCariPasien: any = ref(false);
const rows = ref(10);
const rowsPerPage = ref();
const totalRecords = ref(0);
const currentPage = ref(1);
const dataSource = ref([]);
const modalDetailData: any = ref(false);
const modalDeleteRiwayat: any = ref(false);
const isSubmit: any = ref(false);
const dataTotal: any = ref();
const detail: any = ref({});
const parameters: any = {
  nocmfk: route.query.nocmfk,
  noregis: route.query.noregis,
  nrm: route.query.nrm
}
const item: any = ref({
  qFilterTgl: reactive({
    start: new Date(),
    end: new Date()
  }),
  norec: null,
  namapasien: '',
});
const filters: any = ref({
  'global': { value: null, matchMode: FilterMatchMode.CONTAINS },
})


let dataLahir: any = ref([]);
let input: any = ref({});
let listPasien: any = ref([]);
let listKelamin: any = ref([]);
let isPlaceLoad: any = ref(false)
let loadSearch: any = ref(false)
let loadData: any = ref(false)

onMounted(() => {
  if (parameters.nocmfk == undefined || parameters.noregis == undefined || parameters.nrm == undefined) {
    window.location.href = `/404${route.fullPath}`
  }
  fetchData();
})

const cariRiwayat = () => {
  fetchData();
}

const openModalAdd = () => {
  // input = {};
  input.value.action = 'create';
  disabledInput.value = false;
  modalAddData.value = true;
}

const openModalCariPasien = () => {
  modalCariPasien.value = true;
  fetchPasien();
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const fetchData = async () => {
  dataLahir.loading = true;
  isLoading.value = true;
  let startDate = moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
  let endDate = moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')

  let params: string = `nocmfk=${parameters.nocmfk}&noregis=${parameters.noregis}&dari=${startDate}&sampai=${endDate}`
  let urlApi: string = `laporan/get-laporan-lahir?${params}`
  await useApi().get(urlApi)
    .then((response) => {
      console.log(response);
      if (response.status == 200) {
        response.data.forEach((element: any, i: any) => {
          element.no = i + 1
          element.jam = moment(element.tanggal).format('HH:mm')
          element.tanggal = moment(element.tanggal).format('YYYY-MM-DD')
        });
        dataLahir.value = response.data
        dataTotal.value = response.total
      }

      isLoading.value = false
      dataLahir.loading = false;

    }).catch((e: any) => {
      isLoading.value = false
      dataLahir.loading = false;
    })
  dataLahir.loading = false;
  isLoading.value = false;
}

const fetchPasien = async () => {
  isPlaceLoad.value = true;

  const page = Number(currentPage.value);
  const rowsPerPages = Number(rows.value);
  const pasien = `&namapasien=${item.value.namapasien}`;

  try {
    const response = await useApi().get(
      `/laporan/cari-ibu?page=${page}&rows=${rowsPerPages}${pasien}`
    );

    dataSource.value = response.data;
    totalRecords.value = response.total;

    console.log("Total Records:", totalRecords.value);
    console.log("Data Source:", dataSource.value);

  } catch (error) {
    console.error("Error fetching data:", error);
  } finally {
    isPlaceLoad.value = false;
  }
}

const onPageChange = (event) => {
  currentPage.value = Number(event.page) + 1;
  rowsPerPage.value = Number(event.rows);
  fetchPasien();
}

const getPasien = async (filter: any) => {
  if (!filter.query) return
  if (filter.query.length < 4) return

  let url = `/laporan/get-pasien-istri?search=${encodeURI(filter.query)}`;

  await useApi()
    .get(url)
    .then((res: any) => {
      console.log(res);
      listPasien.value = res;
    })
}

const getJenisKelamin = async () => {
  let url = `/laporan/get-jeniskelamin`;

  await useApi()
    .get(url)
    .then((res: any) => {
      console.log(res);
      listKelamin.value = res;
    })
}

const submitData = async () => {
  isSubmit.value = true;
  console.log("INPUT DATA", input);
  let json = {
    nocmanak: parameters.nocmfk,
    namasuami: input.value.namaayah,
    namaibu: input.value.namaibu,
    noregis: parameters.noregis,
    jeniskelamin: input.value.jenisKelamin.id,
    dokterPenolong: input.value.dokterPenolong.label,
    normIbu: input.value.normIbu,
    namaanak: input.value.namaanak,
    pekerjaan: input.value.pekerjaan,
    normanak: parameters.nrm,
    tinggianak: input.value.tinggianak,
    beratanak: input.value.beratanak,
    tglLahir: moment(input.value.tglLahir).format('YYYY-MM-DD HH:mm:ss')
  }

  let url: string = '';
  if (input.value.action == "create") {
    url = `/laporan/create-laporan-lahir`;
  } else {
    url = `/laporan/update-laporan-lahir/${item.norec}`
  }
  await useApi()
    .post(url, json)
    .then((res: any) => {
      if (res.status == 201 || res.status == 200) {
        isSubmit.value = false;
        modalAddData.value = false;
        fetchData();
      }
    })
    .catch((e: any) => {
      isSubmit.value = false;
      console.log(e)
    })
  isSubmit.value = false;
}

const cetakSurat = (e: any) => {
  // H.printBlade('laporan/cetak-laporan-lahir?noregis=' + e.noregistrasifk + '&nocmfk=' + e.nocmfk + '&norm=' + e.norm)
  H.printBlade('laporan/cetak-laporan-lahir?norec=' + e.norec)
}

const handlerIstri = (e: any) => {
  if (e.value.namaayah != null) {
    input.value.namaayah = e.value.namaayah;
    disabledInput.value = true;
  } else {
    input.value.namaayah = "";
    disabledInput.value = false;
  }
}

const editData = (value: any) => {
  modalAddData.value = true;
  disabledInput.value = true;
  item.norec = value.norec
  input.value = {
    jenisKelamin: {
      id: value.objekjeniskelaminfk,
      jeniskelamin: value.jeniskelamin
    },
    normIbu: value.normIbu,
    namaibu: value.namaibu,
    namaayah: value.namasuami,
    namaanak: value.namaanak,
    pekerjaan: value.pekerjaan,
    beratanak: value.berat,
    tinggianak: value.tinggi,
    tglLahir: moment(value.tanggal + " " + value.jam).format("YYYY-MM-DD HH:mm"),
    action: 'edit'
  }
}

const deleteData = (value: any) => {
  let url: string = `/laporan/delete-laporan-lahir/${value.norec}`;

  confirm.require({
    message: 'Apakah Anda yakin ingin menghapus data?',
    header: 'Hapus Surat Keterangan Lahir',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      useApi()
        .post(url)
        .then((res: any) => {
          if (res.status == 200) {
            isLoading.value = true
            fetchData();
          }
        })
        .catch((e: any) => {
          isLoading.value = false
          console.log(e)
        })
    },
    reject: () => {
    },
  })
}

const detailData = (value: any) => {
  modalDetailData.value = true;
  detail.value = {
    noskl: value.noskl,
    nama: value.nama,
    namasuami: value.namasuami,
    namaanak: value.namaanak,
    norm: value.norm,
    tanggal: value.tanggal,
    berat: value.berat,
    tinggi: value.tinggi,
    noregistrasi: value.noregistrasifk
  }
}

watch(item, (newValue, oldValue) => {
  fetchPasien();
})

</script>
<style lang="scss">
.p-datatable-wrapper {
  min-height: 400px;
  height: inherit
}
</style>
