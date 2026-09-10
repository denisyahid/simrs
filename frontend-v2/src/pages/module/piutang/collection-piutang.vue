<template>
  <div class="column is-12">
    <VCard>
      <div class="column c-title pt-2 mb-3">
        <label class="title-page">Pencarian</label>
      </div>
      <div class="search-widget">
        <div class="field">
          <div class="columns is-multiline">
            <div class="column is-4">
              <span>Periode</span>
              <VDatePicker v-model="item.qFilterTgl" is-range color="pink" trim-weeks class="pt-2">
                <template #default="{ inputValue, inputEvents }">
                  <VField addons>
                    <VControl icon="feather:calendar">
                      <VInput :value="inputValue.start" class="input-calendar" v-on="inputEvents.start" />
                    </VControl>
                    <VControl>
                      <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                    </VControl>
                    <VControl icon="feather:calendar">
                      <VInput :value="inputValue.end" class="input-calendar" v-on="inputEvents.end" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-4">
              <span>Kelompok Pasien</span>
              <VField class="mt-2 is-rounded-select is-autocomplete-select">
                <VControl icon="feather:search" class="prime-auto-select">
                  <AutoComplete v-model="item.kelompokpasien" :suggestions="d_KelompokPasien"
                    @complete="fetchKelompokPasien($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                    placeholder="Ketik untuk mencari..." />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <span>Jenis Penjamin</span>
              <VField class="mt-2 is-rounded-select is-autocomplete-select">
                <VControl icon="feather:search" class="prime-auto-select">
                  <AutoComplete v-model="item.rekanan" :suggestions="d_Rekanan" @complete="fetchRekanan($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ketik untuk mencari..." />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <span>Nomer Registrasi</span>
              <VField>
                <VInput placeholder="No Registrasi..." class="mt-1" v-model="item.noregistrasi"></VInput>
              </VField>
            </div>
            <div class="column is-3">
              <span>No MR</span>
              <VField>
                <VInput placeholder="No Mr..." class="mt-1" v-model="item.nocm"></VInput>
              </VField>
            </div>
            <div class="column is-3">
              <span>Nama Pasien</span>
              <VField>
                <VInput placeholder="Nama Pasien..." class="mt-1" v-model="item.namaPasien"></VInput>
              </VField>
            </div>
            <div class="column mt-5" style="margin-left: auto:  !important;">
              <VIconButton type="button" color="success" class="mt-1" raised icon="fas fa-search" @click="fetchData()"
                :loading="isPlaceLoad">
              </VIconButton>
            </div>
          </div>
        </div>
      </div>
    </VCard>
  </div>
  <div class="column is-12">
    <VCard>
      <div class="column c-title pt-2 mb-3">
        <label class="title-page">Daftar Piutang Pasien</label>
      </div>
      <div class="column" v-if="isLoading">
        <VPlaceloadWrap v-for="data in 5">
          <VPlaceload class="mx-2 mb-3" />
          <VPlaceload class="mx-2" />
        </VPlaceloadWrap>
      </div>
      <DataTable v-else :value="dataSource" class="p-datatable-sm" :loading="isLoading" :paginator="true" :rows="10"
        :rowsPerPageOptions="[5, 10, 25]" scrollable
        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
        <template #header>
          <div class="flex flex-wrap align-items-center justify-content gap-2">
            <VControl raw subcontrol style="margin-top:-10px">
              <VCheckbox v-model="item.checkAll" label="#" color="info" @change="checkedAll(item.checkAll)"
                :value="item.checkAll" />
            </VControl>
          </div>
        </template>
        <Column :exportable="false" header="#" style="text-align: center;">
          <template #body="slotProps">
            <VControl raw subcontrol>
              <VCheckbox v-model="modelCheck[slotProps.data.noRec]" :value="slotProps.data" color="info" square
                @change.stop="checkedItems()" />
            </VControl>
          </template>
        </Column>
        <Column field="noRegistrasi" header="No Registrasi" frozen :sortable="true" style="min-width: 150px"></Column>
        <Column field="tglTransaksi" header="Tanggal" frozen :sortable="true" style="min-width: 100px"></Column>
        <Column field="namaPasien" header="Nama" frozen :sortable="true" style="min-width: 200px"></Column>
        <Column field="jenisPasien" header="Kelompok Pasien" :sortable="true" style="min-width: 200px"></Column>
        <Column field="rekanan" header="Penjamin" :sortable="true" style="min-width: 200px"></Column>
        <Column field="totalBilling" header="Total Tagihan" :sortable="true" style="min-width: 200px"></Column>
        <Column field="totaltidakdiklaim" header="Total Tidak Diklaim" :sortable="true" style="min-width: 200px">
        </Column>
        <Column field="totalKlaim" header="Total Klaim" :sortable="true" style="min-width: 200px"></Column>
        <Column field="totalBayar" header="Total Bayar" :sortable="true" style="min-width: 200px"></Column>
        <Column field="sisautang" header="Sisa Belum Dibayar" :sortable="true" style="min-width: 200px"></Column>
        <Column field="umur" header="Umur" :sortable="true" style="min-width: 200px"></Column>
        <Column field="status" header="Status" :sortable="true" style="min-width: 200px"></Column>
        <Column field="noposting" header="No Collect" :sortable="true" style="min-width: 200px"></Column>
      </DataTable>
    </VCard>
  </div>
  <div class="column is-12">
    <VCard>
      <div class="column c-title pt-2 mb-3">
        <label class="title-page">Daftar Collecting</label>
        <div v-if="listChecked.length == 0">
        </div>
        <div v-else class="column">
          <div class="columns is-multiline">
            <div class="column is-2">
              <VField>
                <VControl class="prime-auto">
                  <div>
                    <VInput v-model="filterd" placeholder="Cari..." class="is-rounded"></VInput>
                  </div>
                </VControl>
              </VField>
            </div>
          </div>
          <DataTable class="p-datatable-sm" :value="dataSourcefilter" :loading="isLoading"
            :rowsPerPageOptions="[5, 10, 25]" scrollable
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
            <Column :exportable="false" header="#" style="width:8rem">
              <template #body="slotProps">
                <Button icon="pi pi-trash" class="p-button-rounded p-button-danger" @click="hapusRow(slotProps.data)" />
              </template>
            </Column>
            <Column field="no" header="No" frozen></Column>
            <Column field="noRegistrasi" header="No Registrasi" frozen :sortable="true" style="min-width: 150px">
            </Column>
            <Column field="namaPasien" header="Nama" frozen :sortable="true" style="min-width: 200px"></Column>
            <Column field="jenisPasien" header="Jenis Penjamin" :sortable="true" style="min-width: 200px"></Column>
            <Column field="rekanan" header="Nama Rekanan" :sortable="true" style="min-width: 200px"></Column>
            <Column field="totalKlaim" header="Total Klaim" :sortable="true" style="min-width: 200px"></Column>
          </DataTable>
        </div>
      </div>
      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-3">
            <VCardCustom :style="'padding:5px 25px'">
              <div class="label-status success">
                <i aria-hidden="true" class="fas fa-circle"></i>
                <span class="ml-1">TOTAL PASIEN</span>
              </div>
              <small class="text-bold-custom h-100">{{ totalPasien }}</small>
            </VCardCustom>
          </div>
          <div class="column is-3">
            <VCardCustom :style="'padding:5px 25px'">
              <div class="label-status warning">
                <i aria-hidden="true" class="fas fa-circle"></i>
                <span class="ml-1">TOTAL KLAIM</span>
              </div>
              <small class="text-bold-custom h-100">{{ listChecked.length > 0 ? H.formatRp(totalKlaim, 'RP') : 0
                }}</small>
            </VCardCustom>
          </div>
        </div>
      </div>
      <div class="column is-12">
        <div class="columns is-multiline" style="align-items:right">
          <div class="column is-10">
          </div>
          <div class="column is-1">
            <VButton icon="lnir lnir-arrow-left rem-100" @click="kembali()" light dark-outlined>Kembali</VButton>
          </div>
          <div class="column is-1">
            <VButton icon="feather:save" @click="Save()" :loading="isLoadingSave" color="info">Simpan</VButton>
          </div>
        </div>
      </div>
    </VCard>
  </div>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router';
import { ref, computed, watch, reactive } from 'vue';
import DataTable from 'primevue/datatable';
import { useViewWrapper } from '/@src/stores/viewWrapper';
import Column from 'primevue/column';
import { useHead } from '@vueuse/head';
import * as H from '/@src/utils/appHelper';
import AutoComplete from 'primevue/autocomplete';
import moment from 'moment';
import { useApi } from '/@src/composable/useApi';
import Calendar from 'primevue/calendar';
import Button from 'primevue/button'
useHead({
  title: 'Collection Piutang - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const isLoading: boolean = ref(false);
const isLoadingSave: boolean = ref(false);
const isLoadingTT: boolean = ref(false);
const dataSource: any = ref([]);
const totalPasien: any = ref(0);
const totalKlaim: any = ref(0);
const d_Rekanan: any = ref([]);
const d_KelompokPasien: any = ref([]);
const modelCheck: any = ref([]);
const listChecked: any = ref([]);
const noposting: any = ref('');
const router = useRouter();
let start = useRoute().query.start as string
let end = useRoute().query.end as string
let key = useRoute().query.key as string
const item: any = ref({
  qFilterTgl: {
    start: start ? new Date(start) : new Date(),
    end: end ? new Date(end) : new Date()
  },
  tglCollectPiutang: new Date(),
});


function checkedAll(e: any) {
  totalPasien.value = 0;
  totalKlaim.value = 0;
  modelCheck.value = [];
  listChecked.value = [];

  if (e) {
    dataSource.value.forEach((item: any) => {
      if (item.status !== 'Collecting' && item.status !== 'Lunas') {
        listChecked.value.push(item);
        modelCheck.value[item.noRec] = true;
      }
    });
  }

  totalPasien.value = listChecked.value.length;
  listChecked.value.forEach((value: any, index: number) => {
    value.no = index + 1;
    totalKlaim.value += Number(value.klaim);
  });
}

function checkedItems() {
  totalPasien.value = 0;
  totalKlaim.value = 0;
  const objectKeys = Object.keys(modelCheck.value);
  for (let x = 0; x < objectKeys.length; x++) {
    const element = objectKeys[x];

    if (modelCheck.value[element] === true) {
      const checkedItem = dataSource.value.find(item => item.noRec === element);
      if (checkedItem.status == 'Collecting') {
        modelCheck.value[element] = false;
        H.alert('warning', 'Pasien sudah diCollection !');
        return;
      }
      if (checkedItem.status == 'Lunas') {
        H.alert('warning', 'Pasien sudah Lunas!');
        modelCheck.value[element] = false;
        return;
      }
      if (checkedItem.noposting) {
        H.alert('warning', 'Pasien Sudah TerCollecting Sebelumnya!');
        modelCheck.value[element] = false;
        return;
      }
      if (checkedItem && !listChecked.value.some(item => item.noRec === element)) {
        listChecked.value.push(checkedItem);
      }
    } else {
      listChecked.value = listChecked.value.filter(item => item.noRec !== element);
    }
  }
  totalPasien.value = listChecked.value.length;
  listChecked.value.map((value: any, index: number) => {
    value.no = index + 1;
    totalKlaim.value += Number(value.klaim);
  })
}
function convertToNumber(currencyString: string): number {
  const numericString = currencyString.replace(/[^\d]/g, '');
  const result = parseInt(numericString, 10);
  return isNaN(result) ? 0 : result;
}


const fetchKelompokPasien = async (filter: any) => {
  await useApi().get(`emr/dropdown/kelompokpasien_m?select=id,kelompokpasien&param_search=kelompokpasien&query=${filter.query}&limit=10`
  ).then((response) => {
    d_KelompokPasien.value = response
  })
}
const fetchRekanan = async (filter: any) => {
  await useApi().get(`emr/dropdown/rekanan_m?select=id,namarekanan&param_search=namarekanan&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Rekanan.value = response
  })
}

const fetchData = async () => {
  isLoading.value = true
  let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
  let kelompokpasien = item.value.kelompokpasien ? `&kelompokpasienfk=${item.value.kelompokpasien.value}` : ''
  let rekanan = item.value.rekanan ? `&rekananfk=${item.value.rekanan.value}` : ''
  let nama = item.value.namaPasien ? `&namaPasien=${item.value.namaPasien}` : ''
  let noregistrasi = item.value.noregistrasi ? `&noregistrasi=${item.value.noregistrasi}` : ''
  let nocm = item.value.nocm ? `&nocm=${item.value.nocm}` : ''

  await useApi().get(`piutang/daftar-piutang-layanan?${tglAwal}${tglAkhir}${kelompokpasien}${nama}${noregistrasi}${nocm}&status=1`).then((response: any) => {
    response.forEach((element: any, i: any) => {
      element.no = i + 1,
        element.tglTransaksi = moment(element.tglTransaksi).format('DD-MMM-YYYY'),
        element.totalBilling = H.formatRp(element.totalBilling, 'Rp.'),
        element.totaltidakdiklaim = H.formatRp(element.totaltidakdiklaim, 'Rp.'),
        element.klaim = element.totalKlaim,
        element.totalKlaim = H.formatRp(element.totalKlaim, 'Rp.'),
        element.totalBayar = H.formatRp(element.totalBayar, 'Rp.'),
        element.sisautang = H.formatRp(element.sisautang, 'Rp.')
    });
    dataSource.value = response
  })
  isLoading.value = false
}
const Save = async () => {
  if (listChecked.value.length == 0) {
    H.alert('warning', 'Belum ada data yang di pilih !')
    return
  }
  if (!item.value.tglCollectPiutang) {
    H.alert('warning', 'Tanggal Collecting Belum Diisi!')
    return
  }
  console.log(JSON.stringify(listChecked.value));
  var dataObjPost = {};
  var dataObjloop = {};
  var arrObjPembayaran = [];
  var penjamin = '';
  if (item.value.penjamin) {
    penjamin = item.value.penjamin
  }
  for (var i = 0; i < listChecked.value.length; i++) {
    dataObjloop = {
      norec: listChecked.value[i].noRec,
      totalKlaim: listChecked.value[i].klaim
    };
    arrObjPembayaran.push(dataObjloop)
  }
  dataObjPost = {
    idPenjamin: penjamin,
    nopostings: noposting.value,
    tglcollecting: moment(new Date()).format('YYYY-MM-DD HH:mm'),
    strukPenjamin: arrObjPembayaran
  }
  isLoadingSave.value = true;
  await useApi().post(
    `/piutang/collecting-piutang-layanan`, dataObjPost).then((response: any) => {
      isLoadingSave.value = false
      kembali();
    }).catch((e: any) => {
      isLoadingSave.value = false
    })
}
const loadCollectionPiutang = async () => {
  let chacePeriode = H.cacheHelper().get('periodeTransaksiPencatatanPiutangDaftarLayanan');
  if (chacePeriode != undefined) {
    var key = chacePeriode.key;
    if (key == 'no_posting') {
      let noposting = chacePeriode.noPosting;
      await useApi().get(`piutang/collecting-piutang?key=${key}&no_posting=${noposting}`).then((response: any) => {
        var total = parseInt(0);
        var jumlahPasien = parseInt(0);
        response.forEach((element: any, i: any) => {
          total = total + parseInt(element.totalKlaim);
          jumlahPasien += jumlahPasien
          element.rekanan = element.namarekanan
          element.umur = element.umur
          element.sisa = element.totalKlaim
          element.no = i + 1
          element.klaim = element.totalKlaim
        });
        totalKlaim.value = total;
        totalPasien.value = jumlahPasien;
        listChecked.value = response
        item.value.qFilterTgl.start = new Date(response[0].tglTransaksi);
        item.value.qFilterTgl.end = new Date(response[0].tglTransaksi);
        item.value.rekanan = { value: response[0].rknid, label: response[0].namarekanan }
        item.value.kelompokpasien = { value: response[0].kpid, label: response[0].jenisPasien }
      })
    } else if (key == 'bpjs_klaim_inacbgs') {
      let txtFileName = chacePeriode.fileName;
      await useApi().get(`piutang/collecting-piutang?key=${key}&fileName=${txtFileName}`).then((response: any) => {
        var total = parseInt(0);
        var jumlahPasien = parseInt(0);
        response.forEach((element: any, i: any) => {
          total = total + parseInt(element.totalKlaim);
          jumlahPasien += jumlahPasien
          element.rekanan = element.namarekanan
          element.umur = element.umur
          element.sisa = element.totalKlaim
          element.no = i + 1
          element.klaim = element.totalKlaim
        });
        totalKlaim.value = total;
        totalPasien.value = jumlahPasien;
        listChecked.value = response
        item.value.qFilterTgl.start = new Date(response[0].tglTransaksi);
        item.value.qFilterTgl.end = new Date(response[0].tglTransaksi);
        if (response[0].rknid) {
          item.value.rekanan = { value: response[0].rknid, label: response[0].namarekanan }
        }
        if (response[0].kpid) {
          item.value.kelompokpasien = { value: response[0].kpid, label: response[0].jenisPasien }
        }
      })
    } else if (key == 'bpjs_klaim_bpjs_api') {
      let nofpk = chacePeriode.nofpk;
      let noRegistrasi = chacePeriode.noRegistrasi ? chacePeriode.noRegistrasi : '';
      let namaPasien = chacePeriode.namaPasien ? chacePeriode.namaPasien : '';
      await useApi().get(`piutang/collecting-piutang?key=${key}&nofpk=${nofpk}&namaPasien=${namaPasien}&noRegistrasi=${noRegistrasi}`).then((response: any) => {
        var total = parseInt(0);
        var jumlahPasien = parseInt(0);
        response.forEach((element: any, i: any) => {
          total = total + parseInt(element.totalKlaim);
          jumlahPasien += jumlahPasien
          element.rekanan = element.namarekanan
          element.umur = element.umur
          element.sisa = element.totalKlaim
          element.no = i + 1
          element.klaim = element.totalKlaim
        });
        totalKlaim.value = total;
        totalPasien.value = jumlahPasien;
        listChecked.value = response
        item.value.qFilterTgl.start = new Date(response[0].tglTransaksi);
        item.value.qFilterTgl.end = new Date(response[0].tglTransaksi);
        if (response[0].rknid) {
          item.value.rekanan = { value: response[0].rknid, label: response[0].namarekanan }
        }
        if (response[0].kpid) {
          item.value.kelompokpasien = { value: response[0].kpid, label: response[0].jenisPasien }
        }
      })
    } else {
      if (chacePeriode.start) {
        item.value.qFilterTgl.start = new Date(chacePeriode.start) || new Date();
      }
      if (chacePeriode.end) {
        item.value.qFilterTgl.end = new Date(chacePeriode.end) || new Date();
      }
      if (chacePeriode.idRekanan && chacePeriode.namarekanan) {
        item.value.rekanan = {
          label: chacePeriode.idRekanan,
          value: chacePeriode.namarekanan
        }
      }
      if (chacePeriode.idKelompokpasien && chacePeriode.kelompokpasien) {
        item.value.kelompokpasien = {
          label: chacePeriode.idKelompokpasien,
          value: chacePeriode.kelompokpasien
        }
      }
      if (chacePeriode.namaPasien) {
        item.value.namaPasien = chacePeriode.namaPasien;
      }
      if (chacePeriode.noregistrasi) {
        item.value.noregistrasi = chacePeriode.noregistrasi;
      }
    }
  }
  if (key != 'bpjs_klaim_inacbgs') {
    fetchData();
  }
}
const hapusRow = (element: any) => {
  const index = listChecked.value.findIndex((checkedItem: any) => checkedItem.noRec === element.noRec);
  if (index !== -1) {
    listChecked.value.splice(index, 1);
    totalPasien.value = listChecked.value.length;
    totalKlaim.value -= Number(element.klaim);
  }

}
const filterd: any = ref("");
const dataSourcefilter = computed(() => {
  if (!filterd.value) {
    return listChecked.value;
  }
  return listChecked.value.filter((element: any) => {
    return (
      (!filterd.value ||
        (element.noRegistrasi && element.noRegistrasi.match(new RegExp(filterd.value, 'i'))) ||
        (element.namaPasien && element.namaPasien.match(new RegExp(filterd.value, 'i'))))
    );
  });
});
loadCollectionPiutang();
const kembali = () => {
  window.history.back()
}
</script>
<style lang="scss">
.search-widget {
  flex: 1;
  display: inline-block;
  width: 100%;
  padding: 12px;
  background-color: var(--white);
  border-radius: 16px;
  border: 1px solid var(--fade-grey-dark-3);
  transition: all 0.3s;
}

.title-page {
  position: relative;
  font-size: 17px;
  display: block;
  margin-bottom: 3px;
  margin-top: 8px;
  font-weight: 600;
}

.btn-search {
  display: flex;
  align-items: center;
  margin-top: 14px;
}

.c-title {
  margin-left: -21px;
  padding-top: 21px;
  padding-top: 18px;
  margin-top: -21px;
  border-top-left-radius: 11px;
  border-left: solid hsl(19deg 100% 75% / 72%) 3px;
  padding-bottom: 0px;
  margin-bottom: 2rem;
}

.field>label {
  color: hsl(0deg, 0%, 4%) !important;
}
</style>
