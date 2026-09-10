<script setup lang="ts">
import { h, computed, defineComponent, ref, reactive, onMounted, defineAsyncComponent } from 'vue'
import { RouterLink, useRoute, useRouter } from 'vue-router'
import { useApi } from '/@src/composable/useApi'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import * as H from '/@src/utils/appHelper'
import Calendar from 'primevue/calendar';
import TEMRDetail from './t-emr-detail.vue';
const router = useRouter()
const isLoading = ref(false)
const modalFilterWaktu = ref(false)
const modalEMR = ref(false)
const nameform = ref('')
const props = defineProps({
    listemr: Array,
    headerpasienData: Array,
    nopeserta: String,
    nocmfk: String
})
const namappkRumahSakit = ref('')
const item: any = reactive({
  tglkontrol: new Date(),
})
const dataSurkon = ref([])
const filters = ref('')
const filteredData = computed(() => {
  if (!filters.value) {
    return props.listemr
  } else {
    return props.listemr.filter((item) => {
      return (
        item.namaemr.match(new RegExp(filters.value, 'i'))
      )
    })
  }
})
const init = () => {
  useApi().get(
    `general/ppk-bpjs`
  ).then((response) => {
    namappkRumahSakit.value = response.BPJS_namaPPKRujukan
  })
}

const getsurkon = async () => {
    if(!item.tglkontrol) {
        item.tglkontrol = new Date()
    }

    const bulan = H.formatDate(item.tglkontrol,'MM');
    const tahun = H.formatDate(item.tglkontrol,'YYYY');
    try {
        dataSurkon.value = []
        const jsonantrean = {
            "url": `RencanaKontrol/ListRencanaKontrol/Bulan/${bulan}/Tahun/${tahun}/Nokartu/${props.nopeserta}/filter/2`,
            "method": "GET",
            "data": null
        }
        isLoading.value = true
        const resantrean = await useApi().postNoMessage(`/bridging/bpjs/tools`, jsonantrean)
        isLoading.value = false
        modalFilterWaktu.value = false
        if(resantrean.metaData.code == 200) {
            dataSurkon.value = resantrean.response.list
        }
    } catch(error) {
        isLoading.value = false
        modalFilterWaktu.value = false
        console.log(error)
    }
}
const cetakSurkon = async (e: any) => {
  let json = {
    "url": `Peserta/nokartu/${e.noKartu}/tglSEP/${H.formatDate(new Date(), 'YYYY-MM-DD')}`,
    "method": "GET",
    "data": null
  }
  isLoading.value = true
  let response = await useApi().postBPJS('/bridging/bpjs/tools', json)
  isLoading.value = false
  let nosuratkontrol = e.noSuratKontrol
  let tglrencanakontrol = e.tglRencanaKontrol
  let txttglentrirencanakontrol = e.tglTerbitKontrol
  let noka = e.noKartu
  let nama = e.nama
  let tgllahir = response.response.peserta.tglLahir

  let namaPoliTujuan = e.namaPoliTujuan
  let jeniskelamin = response.response.peserta.sex
  let jnsKontrol = e.jnsKontrol
  let namaDokter = e.namaDokter
  let kddx = '-'
  let nmdpjpsepasal = '-';// e.namaDokter ? e.namaDokter : '-'
  let iddok = 'null'
  let dxawal = '-'

  if (e.noSepAsalKontrol != null) {
    let json = {
      "url": "sep/" + e.noSepAsalKontrol,
      "method": "GET",
      "data": null
    }
    useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
      if (x.metaData.code == 200) {
        dxawal = x.response.diagnosa

        cetakBladeSKDP(nosuratkontrol, tglrencanakontrol, txttglentrirencanakontrol, noka,
          nama, tgllahir, namappkRumahSakit.value, namaPoliTujuan, jeniskelamin, dxawal,
          jnsKontrol, kddx, namaDokter, nmdpjpsepasal, iddok);


      } else {
        H.alert('error', x.metaData.message);
      }
    })

  } else {
    dxawal = '-'
    cetakBladeSKDP(nosuratkontrol, tglrencanakontrol, txttglentrirencanakontrol, noka,
      nama, tgllahir, namappkRumahSakit.value, namaPoliTujuan, jeniskelamin, dxawal,
      jnsKontrol, kddx, namaDokter, nmdpjpsepasal, iddok);

  }
}
const cetakBladeSKDP = (nosuratkontrol, tglrencanakontrol, txttglentrirencanakontrol, noka,
  nama, tgllahir, namappkRumahSakit, namaPoliTujuan, jeniskelamin, dxawal, jnsKontrol, kddx, namaDokter, nmdpjpsepasal, iddok) => {

  H.printBlade('emr/cetak-spri?nosuratkontrol='
    + nosuratkontrol + '&tglrencanakontrol=' + tglrencanakontrol + '&txttglentrirencanakontrol=' + txttglentrirencanakontrol
    + '&noka=' + noka
    + '&tgllahir=' + tgllahir
    + '&namappkRumahSakit=' + namappkRumahSakit
    + '&namaPoliTujuan=' + namaPoliTujuan
    + '&jeniskelamin=' + jeniskelamin
    + '&dxawal=' + dxawal
    + '&jnsKontrol=' + jnsKontrol
    + '&kddx=' + kddx
    + '&namaDokter=' + namaDokter
    + '&nmdpjpsepasal=' + nmdpjpsepasal
    + '&iddok=' + iddok
    + '&nama=' + nama);
}
const showModalFilterWaktu = async () => {
  modalFilterWaktu.value = true
}
const openEMR = (e: any) => {
  // H.printBlade(`emr/cetak/${e.table}?noregistrasi=${e.noregistrasi}&pdf=true`);
  let query: any = {
    nocmfk: props.nocmfk,
    norec_pasien_daftar: e.noregistrasifk,
    norec_pd: e.noregistrasifk,
    norec_apd: e.norec_apd,
    norec_emr: e.emrpasienfk,
  }
  router.push({
    name: `module-klaim-asuransi-detail-klaim-bpjs`,
    query: query
  })
  nameform.value = e.url_form
  modalEMR.value = true
}

onMounted(() => {
    init()
    getsurkon()
})
</script>

<template>
  <div class="all-projects">
    <div class="projects-toolbar">
        <VField raw>
            <VControl icon="feather:search">
                <VInput placeholder="Search Emr..." v-model="filters" />
            </VControl>
        </VField>
    </div>

    <h3 class="section-heading">List Data EMR</h3>

    <div class="columns is-multiline project-grid is-flex-tablet-p is-half-tablet-p" v-if="filteredData.length > 0">
      <div class="column is-one-fifth" v-for="item in filteredData" @mouseover="item.hover = true"  @mouseout="item.hover = false">
        <a class="project-grid-item" v-tooltip="item.namaemr" @click="openEMR(item)">
            <VIconBox size="medium" color="success" class="project-avatar">
                <i :class="{'lnil lnil-folder-alt': item.hover, 'lnil lnil-folder': !item.hover }" aria-hidden="true"></i>
            </VIconBox>
          <h3 class="text-ellipsis">{{ item.namaemr }}</h3>
          <p>{{ item.noemr }}</p>
        </a>
      </div>
    </div>
    <div class="columns is-multiline project-grid is-flex-tablet-p is-half-tablet-p" v-else>
      <div class="column is-12">
        <a class="project-grid-item">
            <VIconBox size="medium" color="success" class="project-avatar">
                <i :class="'lnil lnil-magnifier'" aria-hidden="true"></i>
            </VIconBox>
          <h3 class="text-ellipsis">Kosong</h3>
          <p>Tidak ada inputan EMR</p>
        </a>
      </div>
    </div>

    <div class="columns">
      <div class="column is-12">
        <DataTable :value="dataSurkon" class="p-datatable-sm" breakpoint="960px" sortMode="multiple"
        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
        <template #empty> Rencana Kontrol / SPRI Tidak Tersedia. </template>
        <template #header>
            <label class="has-text-weight-bold">Rencana Kontrol / SPRI</label>
            <VTag @click="showModalFilterWaktu()" color="primary" rounded elevated class="is-pulled-right" style="cursor:pointer">Filter Tgl {{
                    item.tglkontrol == undefined ? H.formatDate(item.tglkontrol, 'MMMM-YYYY') : H.formatDate(item.tglkontrol, 'MMMM-YYYY')
                    }} <i class="fas fa-filter ml-3" aria-hidden="true"></i></VTag>
        </template>
        <Column header="#" frozen style="min-width: 30px">
            <template #body="{ data }">
                <VIconButton color="info" circle icon="feather:printer" class="mr-2" v-tooltip="`Cetak`" @click="cetakSurkon(data)" :loading="isLoading" />
            </template>
        </Column>
        <Column field="nama" header="Peserta"></Column>
        <Column field="noSuratKontrol" header="No Surat"></Column>
        <Column field="namaJnsKontrol" header="Jenis"></Column>
        <Column field="tglRencanaKontrol" header="Tgl Kontrol"></Column>
        <Column field="namaDokter" header="Dokter"></Column>
        <Column field="namaPoliTujuan" header="Poli Tujuan"></Column>
        <Column field="terbitSEP" header="Terbis SEP">
            <template #body="{ data }">
                <VTag color="primary" :label="data.terbitSEP" curved outlined v-if="data.terbitSEP == 'Sudah'" />
                <VTag color="danger" :label="data.terbitSEP" curved outlined v-else />
            </template>
        </Column>
        </DataTable>
      </div>
    </div>
  </div>
  <VModal :open="modalFilterWaktu" title="Filter Periode" :noclose="true" size="small" actions="right"
  @close="modalFilterWaktu = false">
      <template #content>
      <form class="modal-form">
          <div class="columns">
              <div class="column is-12">
                  <VField class=" is-rounded-select is-autocomplete-select">
                      <VLabel>Periode</VLabel>
                      <VControl>
                          <Calendar v-model="item.tglkontrol" view="month" dateFormat="mm/yy" style="width: 100%" />
                      </VControl>
                  </VField>
              </div>
          </div>
      </form>
      </template>
      <template #action>
      <VButton icon="feather:search" @click="getsurkon()" :loading="isLoading" color="primary" raised>
          Filter</VButton>
      </template>
  </VModal>
  <VModal :open="modalEMR" title="EMR" :noclose="true" size="big" actions="right"
  @close="modalEMR = false">
    <template #content>
      <TEMRDetail :nameform="nameform" :pasien="headerpasienData.pasien" :alamat="headerpasienData.alamat[0]" :registrasi="headerpasienData.registrasi[0]" v-if="modalEMR" />
    </template>
  </VModal>
</template>

<style lang="scss">
@import '/@src/scss/abstracts/all';

.all-projects {
  .section-heading {
    font-family: var(--font-alt);
    font-size: 0.85rem;
    text-transform: uppercase;
    color: var(--light-text);
    margin-bottom: 1rem;
  }

  .project-grid {
    margin-bottom: 1.5rem;

    .project-grid-item {
      @include vuero-s-card;

      text-align: center;
      box-shadow: none;

      &:hover,
      &:focus {
        border-color: var(--fade-grey-dark-6);
        box-shadow: var(--light-box-shadow);

        .project-avatar {
          filter: grayscale(0);
          opacity: 1;
        }
      }

      .project-avatar {
        // display: block;
        height: 40px;
        width: 40px;
        border-radius: 10px;
        margin: 0 auto 10px;
        filter: grayscale(1);
        opacity: 0.6;
        transition: all 0.3s; // transition-all test
      }

      h3 {
        font-size: 0.95rem;
        font-family: var(--font-alt);
        font-weight: 600;
        color: var(--dark-text);
      }

      p {
        font-size: 0.9rem;
        margin-bottom: 10px;
      }
    }
  }
}

.text-ellipsis {
  max-width: '140px';
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}

.is-dark {
  .all-projects {
    .project-grid {
      .project-grid-item {
        @include vuero-card--dark;

        h3 {
          color: var(--dark-dark-text);
        }
      }
    }
  }
}

@media only screen and (max-width: 767px) {
  .all-projects {
    .all-projects-header {
      flex-direction: column;

      .header-item {
        width: 100%;
        border-right: none;
        border-bottom: 1px solid var(--fade-grey-dark-3);
        padding: 16px 0;

        &:last-child {
          border-bottom: none;
        }
      }
    }

    .projects-card-grid {
      .grid-item {
        .bottom-section {
          flex-wrap: wrap;

          .foot-block {
            &:first-child {
              width: 100%;
              margin: 0;
              text-align: center;
              padding: 16px 0;

              .developers {
                justify-content: center;

                .v-avatar {
                  margin: 0 4px;
                }
              }
            }

            &:not(:first-child) {
              width: 33.3%;
              text-align: center;
              margin: 0;
            }
          }
        }
      }
    }

    .illustration-header {
      > img {
        display: none !important;
      }

      .header-stats {
        .stats-inner {
          flex-wrap: wrap;

          .stat-item {
            width: 50%;
            margin: 0;
            padding: 16px 0;
          }
        }
      }
    }

    .project-minimal-grid {
      .grid-header {
        .filter {
          display: none;
        }
      }
    }
  }

  .text-ellipsis {
    max-width: '40px';
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .all-projects {
    .illustration-header {
      > img {
        display: none !important;
      }

      .header-stats {
        .stats-inner {
          width: 100%;

          .stat-item {
            width: 25%;
          }
        }
      }
    }

    .project-minimal-grid {
      .grid-body {
        display: flex;

        .column {
          min-width: 50%;
        }
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
  .all-projects {
    .illustration-header {
      .header-stats {
        .stats-inner {
          .stat-item {
            width: 25%;
          }
        }
      }
    }

    .recent-projects {
      .project-box {
        h3 {
          font-size: 1.1rem;
        }
      }
    }

    .project-minimal-grid {
      .grid-body {
        .grid-item {
          .item-title {
            padding: 30px 0;

            h3 {
              font-size: 1.5rem;
            }
          }
        }
      }
    }

    .projects-card-grid {
      .grid-item {
        .bottom-section {
          margin-top: 0.75rem;

          .foot-block {
            &:first-child {
              display: none;
            }
          }
        }
      }
    }
  }
}

.projects-toolbar {
  display: flex;
  align-items: center;
  margin-bottom: 20px;

  .avatar-stack {
    margin-left: 16px;
  }

  .button {
    margin-left: auto;
    min-width: 140px;
  }
}

.is-dark {
  .projects-toolbar {
    .avatar-stack {
      .avatar {
        border-color: var(--dark-sidebar-light-3);
      }
    }
  }
}

@media only screen and (max-width: 767px) {
  .projects-toolbar {
    .avatar-stack {
      display: none;
    }

    .v-button {
      min-width: 110px;
    }
  }
}
</style>
