<template>
  <div class="column is-12">
    <VCard>
      <h1 style="font-weight:bold;margin-bottom:1rem">Detail Collecting Piutang</h1>
      <div class="column is-12">
        <div class="search-widget">
          <div class="column is-12 p-0">
            <div class="columns is-multiline">
              <div class="column">
                <div class="content">
                  <div class="content-balance">
                    <VField>
                      <VLabel>No Collect</VLabel>
                      <p class="block-text">{{ input.noCollecting }}</p>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column">
                <div class="content">
                  <div class="content-balance">
                    <VField>
                      <VLabel>Nama Collector</VLabel>
                      <p class="block-text">{{ input.namacollector }}</p>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column">
                <div class="content">
                  <div class="content-balance">
                    <VField>
                      <VLabel>Tanggal Collect</VLabel>
                      <p class="block-text">{{ input.tglCollectPiutang }}</p>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column">
                <div class="content">
                  <div class="content-balance">
                    <VField>
                      <VLabel>Nama Rekanan</VLabel>
                      <VTag color="success" :label="item.namarekanan"></VTag>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-3">
                <VField label="Tampilan">
                  <Multiselect v-model="selectView" :attrs="{ id }" placeholder="Select View" label="name"
                    :options="d_View" :searchable="true" track-by="name" mode="single" @select="changeView(selectView)"
                    autocomplete="off">
                  </Multiselect>
                </VField>
              </div>
            </div>
          </div>
        </div>
      </div>
    </VCard>
  </div>
  <div class="column is-12">
    <VCard>
      <h1 style="font-weight:bold;margin-bottom:1rem">Daftar Piutang Pasien</h1>
      <div v-if="selectView == 'list'">
        <DataTable class="p-datatable-sm" :value="dataSource" :loading="isLoading" :rowsPerPageOptions="[5, 10, 25]"
          scrollable
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
          <Column field="no" header="No" frozen></Column>
          <Column field="tglTransaksi" style="min-width: 200px" header="Tgl Registrasi" frozen></Column>
          <Column field="noRegistrasi" style="min-width: 100px" header="No Registrasi"></Column>
          <Column field="namaPasien" style="min-width: 200px" header="Nama Pasien"></Column>
          <Column field="totalKlaim" style="min-width: 200px" header="Total Verify"></Column>
          <Column field="tarifinacbgs" style="min-width: 200px" header="Total Klaim"></Column>
          <Column field="tarifselisihklaim" style="min-width: 200px" header="Selisih Klaim"></Column>
          <Column field="totalBayar" style="min-width: 200px" header="Total Bayar"></Column>
          <Column field="sisa" style="min-width: 150px" header="Selisih Piutang"></Column>
          <Column field="keterangan" style="min-width: 150px" header="Keterangan"></Column>
          <Column field="status" style="min-width: 150px" header="Status"></Column>
        </DataTable>
      </div>
      <div v-else class="columns is-multiline">
        <div class="column is-12">
          <div class="list-view list-view-v1" style="max-height:550px;overflow: auto; min-height: 400px;">
            <VPlaceholderPage :class="[dataSource.length !== 0 && 'is-hidden']" title="Data Tidak di Temukan."
              subtitle="Silakan filter pencarian di tanggal lain" larger>
              <template #image>
                <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
              </template>
            </VPlaceholderPage>
            <div class="list-view-inner">
              <TransitionGroup name="list-complete" tag="div">
                <div v-for="item in dataSource" :key="item.norec" class="list-view-item">
                  <div class="list-view-item-inner is-clickable">
                    <VAvatar size="small" picture="/images/avatars/svg/orang.svg" squarred />
                    <div class="meta-left">
                      <h3>{{ item.namaPasien }}</h3>
                      <span>
                        <span>No Registrasi : {{ item.noRegistrasi }} </span>
                        <br>
                        <span>Tgl : {{ item.tglTransaksi }} </span>
                      </span>

                    </div>
                    <div class="meta-right">
                      <div class="stats">
                        <div class="stat">
                          <span>{{ item.totalKlaim }}</span>
                          <span>Total Verify</span>
                        </div>
                        <div class="separator"></div>
                        <div class="stat">
                          <span>{{ item.tarifinacbgs }}</span>
                          <span>Total Klaim</span>
                        </div>
                        <div class="separator"></div>
                        <div class="stat">
                          <span> {{ item.tarifselisihklaim }}</span>
                          <span>Selisih Klaim</span>
                        </div>
                      </div>
                    </div>
                    <div class="tags">
                      <VTag style="width: 80px;" :label="item.status" color="info" rounded elevated />
                    </div>
                    <VDropdown icon="feather:more-vertical" spaced right class="is-pulled-right mb-5">
                      <template #content>
                        <a role="menuitem" @click="cetakTagihan(item.noPosting)" class="dropdown-item is-media">
                          <div class="icon">
                            <i aria-hidden="true" class="lnir lnir-printer"></i>
                          </div>
                          <div class="meta">
                            <span>Cetak Tagihan</span>
                          </div>
                        </a>
                        <a role="menuitem" @click="cetakSurat(item.noRec)" class="dropdown-item is-media">
                          <div class="icon">
                            <i aria-hidden="true" class="lnir lnir-printer"></i>
                          </div>
                          <div class="meta">
                            <span>Cetak Surat</span>
                          </div>
                        </a>
                        <a role="menuitem" @click="cetakKwitasiTagihan(item.noRec)" class="dropdown-item is-media">
                          <div class="icon">
                            <i aria-hidden="true" class="lnir lnir-printer"></i>
                          </div>
                          <div class="meta">
                            <span>Cetak Kwitansi</span>
                          </div>
                        </a>
                        <a role="menuitem" @click="cetakSuratRekapitulasi(item.noPosting)" class="dropdown-item is-media">
                          <div class="icon">
                            <i aria-hidden="true" class="lnir lnir-printer"></i>
                          </div>
                          <div class="meta">
                            <span>Cetak Surat Rekapitulasi</span>
                          </div>
                        </a>
                        <a role="menuitem" @click="cetakSuratRekapitulasiKwitansi(item)" class="dropdown-item is-media">
                          <div class="icon">
                            <i aria-hidden="true" class="lnir lnir-printer"></i>
                          </div>
                          <div class="meta">
                            <span>Cetak Surat Rekapitulasi Kwitansi</span>
                          </div>
                        </a>
                        <a role="menuitem" @click="bayarTagihan(item)" class="dropdown-item is-media">
                          <div class="icon">
                            <i aria-hidden="true" class="lnir lnir-checkmark-circle"></i>
                          </div>
                          <div class="meta">
                            <span>Bayar Tagihan</span>
                          </div>
                        </a>
                        <hr class="dropdown-divider" />
                        <a role="menuitem" @click="batalCollect(item.noPosting)" class="dropdown-item is-media">
                          <div class="icon">
                            <i aria-hidden="true" class="lnil lnil-trash"></i>
                          </div>
                          <div class="meta">
                            <span>Batal Collect</span>
                          </div>
                        </a>
                      </template>
                    </VDropdown>
                  </div>
                </div>
              </TransitionGroup>
            </div>
          </div>
        </div>
        <div class="column is-12">
          <div class="columns mx-5 justify-content-end">
            <div class="column is-1">
              <div>
                <VButton icon="lnir lnir-arrow-left" @click="Kembali()" light dark-outlined>Kembali</VButton>
              </div>
            </div>
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
useHead({
  title: 'Detail Collection Piutang - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT);
useViewWrapper().setFullWidth(true);

const input: any = ref({});
const item: any = reactive({});
const dataSource: any = ref([]);
const isLoading: any = ref(false);
const selectView: any = ref();
const router = useRouter();
selectView.value = 'grid'
const d_View = [
  {
    name: 'Grid View',
    value: 'grid',
    icon: 'fas fa-id-card-alt',
  },
  {
    name: 'List View',
    value: 'list',
    icon: 'fas fa-list',
  },
]

const fetchData = async () => {
  let posting = useRoute().query.posting as string
  isLoading.value = true;
  await useApi().get(`piutang/collected-piutang-layanan/${posting}`).then((response: any) => {
    if (response) {
      item.noRecSKP = response[0].norec_skp;
      item.namarekanan = response[0].namarekanan;
      item.idRekanan = response[0].rknid;
      item.jenisPasien = response[0].jenisPasien;
    }
    response.forEach((element: any, i: any) => {
      element.no = i + 1,
        element.tglTransaksi = moment(element.tglTransaksi).format('DD-MMM-YYYY hh:mm:ss'),
        element.totalKlaim = H.formatRp(element.totalKlaim, 'Rp.'),
        element.tarifinacbgs = H.formatRp(element.tarifinacbgs, 'Rp.'),
        element.totalBayar = H.formatRp(element.totalBayar, 'Rp.'),
        element.tarifselisihklaim = H.formatRp(element.tarifselisihklaim, 'Rp.'),
        element.sisa = H.formatRp(element.sisa, 'Rp.')
    });
    input.value.noCollecting = response[0].noPosting
    input.value.namacollector = response[0].collector
    input.value.tglCollectPiutang = response[0].tglTransaksi
    input.value.namarekanan = response[0].namarekanan
    dataSource.value = response
  })
  isLoading.value = false
}
fetchData();
const batalCollect = async (noPosting: any) => {
  let status = false;
  dataSource.value.forEach((element: any, index: number) => {
    if (element.status == 'Lunas') {
      status = true;
      return;
    }
  })
  if (status) {
    H.alert('warning', 'Collecting sudah lunas, tidak dapat di batalkan!');
    return;
  }
  item.isLoadingSave = true;

  await useApi().post(
    `/piutang/batal-collected-piutang-layanan?noposting=${noPosting}`).then((response: any) => {
      item.isLoadingSave = false
      toCollection();
    }).catch((e: any) => {
      item.isLoadingSave = false
    })
}

const toCollection = () => {
  router.push({
    name: 'module-piutang-daftar-pencatatan-piutang-collection',
  })
}

const bayarTagihan = (data: any) => {
  router.push({
    name: 'module-piutang-pembayaran-piutang-kasir',
    query: {
      posting: data.noPosting
    }
  })
}
const cetakKwitasiTagihan = (norec: any) => {
  H.printBlade(`report/cetak-kwitansi-tagihan?norec=${norec}`)
}
const cetakSuratRekapitulasi = (noPosting: any) => {
  H.printBlade(`report/rekapitulasi-tagihan-asuransi?noposting=${noPosting}`)
}
const cetakSuratRekapitulasiKwitansi = async (data: any) => {
  if (!item.noRecSKP) {
    var objSave = {
      "norec": '',
      "noposting": item.noPostingC,
      "totaltagihan": parseFloat(item.TotalTagihanKlaim),
      "namarekanan": item.namarekanan,
      "rknid": item.idRekanan,
      "jenispasien": item.jenisPasien
    }
    await useApi().post(
      `/piutang/save-nomor-kwitansi-piutang`, objSave).then((response: any) => {
        H.printBlade(`report/bukti-kwitansi-piutang?norec=${response.norec}&tglregistrasi=${data.tglTransaksi}`)
      })
  } else {
    H.printBlade(`report/bukti-kwitansi-piutang?norec=${item.noRecSKP}&tglregistrasi=${data.tglTransaksi}`)
  }
}
const cetakTagihan = (noPosting: any) => {
  H.printBlade(`report/cetak-tagihan-piutang?noPosting=${noPosting}`)
}
const cetakSurat = (norec: any) => {
  H.printBlade(`report/cetak-surat-piutang?norec=${norec}`)
}
const Kembali = () => {
  window.history.back()
}
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/module/piutang/piutang';

.form-layout .form-outer .form-body {
  padding: 0;
}

.content {
  .content-balance {
    .field {
      .label {
        font-family: var(--font-alt);
        font-weight: 600;
        font-size: 1rem;
        height: auto !important;
        color: var(--black) !important;
        margin-bottom: 4px;
      }

      p {
        font-family: var(--font-alt);
        font-weight: 600;
        font-size: 0.8rem;
        color: var(--black) !important;
        margin-bottom: 4px;
        height: auto !important;
      }
    }
  }
}

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
</style>

