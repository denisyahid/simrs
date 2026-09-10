
<template>
  <div class="business-dashboard hr-dashboard">
    <div class="columns">
      <div class="column is-8">
        <div class="columns is-multiline">
          <!--Header-->
          <div class="column is-12">
            <div class="block-header">
             
              <!--left-->
              <div class="left">
                <img src="/images/avatars/label/satuan-besar.png" alt="" srcset=""
            style="max-width:120%; margin-top: -3rem; margin-left: -4rem;">
           
              </div>
              

              <!--Center-->
              <div class="center">
                <h3 class="block-heading">Master Data</h3>
                <p class="block-text">
                  Data master adalah data yang menjelaskan objek di sekitar proses bisnis
                </p>

                <VButton bold fullwidth dark-outlined raised @click="add()">Tambah Data Master</VButton>
              </div>

              <!--Right-->
              <div class="right">
                <h3 class="block-heading">Mapping Data</h3>
                <p class="block-text">
                  proses mencocokkan data yang berasal dari berbagai sumber ke skema database utama
                </p>

              </div>
            </div>
          </div>

          <!--Selector-->
          <div class="column is-12">
          </div>
          <div class="column is-12">
            <div class="recent-rookies">
              <div class="recent-rookies-header">
                <h3 class="dark-inverted">Mapping Data Master</h3>
                <VTag color="danger" label="Tag Label" rounded elevated> 6 Tabel</VTag>
              </div>

              <div class="columns user-grid user-grid-v4 is-flex-tablet-p">
                <!--Rookie-->
                <div class="column is-4">
                   
                  <div class="grid-item">
                    
                    <VAvatar
                      picture="/images/avatars/svg/room.svg"
                      size="large"
                    />
                  
                    <h3 class="dark-inverted"> Ruangan To Produk</h3>
                    <VTag color="purple" label="Tag Label" rounded elevated> {{ item.totalMapRuanganToProduk }}</VTag>
                    <div class="button-wrap has-text-centered">
                      <VButton color="info" RouterLink :to="{ name: 'module-sysadmin-mapping-ruangan-to-produk' }" raised rounded>Lihat Data</VButton>
                    </div>
                  </div>
                </div>
                <!--Rookie-->
                <div class="column is-4">
                  <div class="grid-item">
                    <VAvatar
                    picture="/images/avatars/svg/paket.svg"
                      size="large"
                    />
                    <h3 class="dark-inverted">Paket To Produk</h3>
                    <VTag color="purple" label="Tag Label" rounded elevated> {{ item.totalMapPaketToProduk }}</VTag>
                    <div class="button-wrap has-text-centered">
                        <VButton color="info" RouterLink :to="{ name: 'module-sysadmin-mapping-paket-to-produk' }" raised rounded>Lihat Data</VButton>
                    </div>
                  </div>
                </div>
                <!--Rookie-->
                <div class="column is-4">
                  <div class="grid-item">
                    <VAvatar
                    picture="/images/avatars/svg/class.svg"
                      size="large"
                    />
                    <h3 class="dark-inverted"> Ruangan To Kelas</h3>
                    <VTag color="purple" label="Tag Label" rounded elevated> {{ item.totalMapRuanganToKelas }}</VTag>
                    <div class="button-wrap has-text-centered">
                        <VButton color="info" RouterLink :to="{ name: 'module-sysadmin-mapping-ruangan-to-kelas' }" raised rounded>Lihat Data</VButton>
                    </div>
                  </div>
                </div>
                
              </div>
            </div>
          </div>
           <div class="column is-12">
            <div class="recent-rookies">
              <div class="columns user-grid user-grid-v4 is-flex-tablet-p">
                <!--Rookie-->
                <div class="column is-4">
                  <div class="grid-item">
                    <VAvatar
                      picture="/images/avatars/svg/user2.svg"
                      size="large"
                    />
                    <h3 class="dark-inverted">Loginuser To Ruangan</h3>
                    <VTag color="purple" label="Tag Label" rounded elevated> {{ item.totalMapLoginuserToRuangan }}</VTag>
                    <div class="button-wrap has-text-centered">
                        <VButton color="info" RouterLink :to="{ name: 'module-sysadmin-mapping-loginuser-to-ruangan' }" raised rounded>Lihat Data</VButton>
                    </div>
                  </div>
                </div>
                <!--Rookie-->
                <div class="column is-4">
                  <div class="grid-item">
                    
                    <VAvatar
                    picture="/images/avatars/svg/modap.svg"
                      size="large"
                    />
                    <h3 class="dark-inverted"> Loginuser To Modul Aplikasi</h3>
                    <VTag color="purple" label="Tag Label" rounded elevated> {{ item.totalMapLoginuserToModulAplikasi }}</VTag>
                    <div class="button-wrap has-text-centered">
                        <VButton color="info" RouterLink :to="{ name: 'module-sysadmin-mapping-loginuser-to-modulaplikasi' }" raised rounded>Lihat Data</VButton>
                    </div>
                  </div>
                </div>
                <!--Rookie-->
                <div class="column is-4">
                  <div class="grid-item">
                    <VAvatar
                    picture="/images/avatars/svg/jenispetugas.svg"
                      size="large"
                    />
                    <h3 class="dark-inverted">Jenis Petugas To Jenis Pegawai </h3>
                    <VTag color="purple" label="Tag Label" rounded elevated> {{ item.totalMapJenisPetugasToJenisPegawai }}</VTag>
                    <div class="button-wrap has-text-centered">
                        <VButton color="info" RouterLink :to="{ name: 'module-sysadmin-master-map-jenis-petugas-to-jenis-pegawai' }" raised rounded>Lihat Data</VButton>
                    </div>
                  </div>
                </div>
                
              </div>
            </div>
          </div>
        </div>
      </div>
<!---->
      <div class="column is-4">
        <!--Widget-->
        <UIWidget class="search-widget">
          <template #body>
            <div class="field">
              <div class="control">
                <input v-model="filters" class="input custom-text-filter" placeholder="Cari Master Data..." />
                <button class="searcv-button">
                  <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
                </button>
              </div>
            </div>
          </template>
        </UIWidget>
       
        <div class="recent-rookies">
              <div class="recent-rookies-header">
                <h3 class="dark-inverted">Data Master</h3>
                <VTag color="danger" label="Tag Label" rounded elevated> {{ dataSource.total }} Tabel</VTag>
              </div>
              </div>
        <div class="tile-grid tile-grid-v2">
       
      <!--List Empty Search Placeholder -->
      <VPlaceholderPage
        :class="[dataSourcefiltered.length !== 0 && 'is-hidden']"
        title="We couldn't find any matching results."
        subtitle="Too bad. Looks like we couldn't find any matching results for the
          search terms you've entered. Please try different search terms or
          criteria."
        larger
      >
        <template #image>
          <img
            class="light-image"
            src="/@src/assets/illustrations/placeholders/search-4.svg"
            alt=""
          />
          <img
            class="dark-image"
            src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
            alt=""
          />
        </template>
      </VPlaceholderPage>

      <!--Tile Grid v1-->
      
      <TransitionGroup name="list" tag="div" class="columns is-multiline">
        <!--Grid item-->
        <div class="columns is-multiline p-2" style="max-height:550px;overflow: auto;">
        <div v-for="item in dataSourcefiltered" :key="item.id" class="column is-12" >
          <div class="tile-grid-item" >
            <RouterLink :to="{ name: item.link }"> 
            <div class="tile-grid-item-inner">
            
              <VAvatar size="medium" picture="/images/avatars/svg/datamaster.svg" color="primary" squared bordered />
              
              <div class="meta">
                <span class="dark-inverted">{{ item.label }}</span>
            
                  <span>{{ item.table_name }}</span>
              </div>
              <VTag style="margin-left:auto" color="primary" label="Tag Label" rounded elevated> {{ item.rows_n }}</VTag>
           
            </div>
          </RouterLink>
          </div>
        </div>
      </div>
      </TransitionGroup>
    </div>
        

        <!--Widget-->
       
      </div>
    </div>
 
  </div>
  <VModal :open="modalInput" title="Tambah Data Master" actions="right" @close="modalInput = false">
      <template #content>
        <form class="modal-form">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VField label="Nama Data Master">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.label" placeholder="Nama Data Master" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField label="Nama Tabel Master">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.tablename" placeholder="Nama Tabel Master" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField label="Link URL Tabel Master">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.link" placeholder="Alamat URL Tabel Master" class="is-rounded" />
                </VControl>
              </VField>
            </div>

          </div>
        </form>
      </template>
      <template #action>
        <VButton icon="feather:plus" @click="save()" :loading="isLoadingTT" color="primary" raised>Simpan</VButton>
      </template>
    </VModal>
</template>

<script setup lang="ts">
import { useRoute, useRouter} from 'vue-router'
import { todoList3, todoList4 } from '/@src/data/widgets/list/todoList'
import { ref, computed, watch, reactive } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import { useToaster } from '/@src/composable/toaster'
import { datatableV3 } from '/@src/data/layouts/datatable-v3'
import { onceImageErrored } from '/@src/utils/via-placeholder'

import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
const themeColors = useThemeColors()
const userLogin = useUserSession().getUser()
const total = ref(0)
const router = useRouter()
const modalInput = ref(false)
const item: any = ref({
  aktif: true
})

const currentPage: any = ref({
  limit: 5,
  rows: 50,
})
let dataSource: any = ref([])
let isLoading: any = ref(false)
let isLoadingTT: any = ref(false)
const filters = ref('')
const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return dataSource.value
  }

  return dataSource.value.filter((item: any) => {
    return (
      item.label.match(new RegExp(filters.value, 'i'))
    )
  })
})
const route = useRoute()
isLoading.value = false

function add() {
  clear()
  modalInput.value = true
}

function clear() {
item.value.id = ''
item.value.label = ''
item.value.tablename = ''
item.value.link = ''

modalInput.value = false
}

async function save() {
    if (!item.value.label) {
      useToaster().error('Nama Master harus di isi')
      return
    }
    if (!item.value.tablename) {
      useToaster().error('Tabel Master harus di isi')
      return
    }
    if (!item.value.link) {
      useToaster().error('Link Master harus di isi')
      return
    }
    var objSave =
    {
      'listmaster': {
        'id': item.value.id ? item.value.id : '',
        'label': item.value.label,
        'link': item.value.link,
        'tablename': item.value.tablename,
        'statusenabled': item.value.statusenabled ? item.value.statusenabled : null,
      }
    }
    isLoadingTT.value = true
    await useApi().post(
      `dashboard/save-master-list`, objSave).then((response: any) => {
        isLoadingTT.value = false
        clear()
        fetchDataMater()
      }, (error) => {
        isLoadingTT.value = false
        // console.log(error)
      })
  }

async function fetchData() {
  await useApi().get(
    `/dashboard/dashboard-sysadmin`).then((response: any) => {
      item.value = response
    })
}

async function fetchDataMater() {
  isLoading = true
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = offset * limit - limit
  let rows: any = currentPage.value.rows
  let Link = ''
  let Label =''

  if (item.link) Link = '&link' + item.link
  if (item.label) Label = '&label' + item.label

  const response = await useApi().get(
    '/dashboard/dashboard-masterdata?'+
    Link + Label 
  )
  isLoading = false
  for (let x = 0; x < response.data.length; x++) {
    const element = response.data[x];
    element.no = x + 1
  }
  dataSource.value = response.data
  dataSource.value.total = response.data.length
}

fetchData ()
fetchDataMater()


</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

.hr-dashboard {
  .block-header {
    display: flex;
    border-radius: 16px;
    padding: 50px;
    background: var(--primary);
    font-family: var(--font);
    box-shadow: var(--primary-box-shadow);

    .left,
    .right {
      width: 30%;
    }

    .center {
      display: flex;
      flex-direction: column;
      width: 40%;
      padding-right: 30px;
      margin-right: 30px;
      border-right: 1px solid var(--primary-light-10);

      .block-text {
        margin-bottom: 16px;
      }

      .candidates {
        margin-top: auto;

        > .v-avatar {
          margin-right: 10px;
        }

        button {
          height: 40px;
          width: 40px;
          display: inline-flex;
          justify-content: center;
          align-items: center;
          border-radius: 10px;
          background: var(--white);
          color: var(--light-text);
          border: none;
          cursor: pointer;
          transition: all 0.3s; // transition-all test

          svg {
            height: 18px;
            width: 18px;
          }
        }
      }
    }

    .left {
      display: flex;
      justify-content: center;
      align-items: center;

      .current-user {
        .v-avatar {
          margin-bottom: 1rem;
        }

        h3 {
          font-family: var(--font-alt);
          font-weight: 700;
          font-size: 1.8rem;
          color: var(--white);
          line-height: 1.2;
        }
      }
    }

    .right {
      display: flex;
      flex-direction: column;

      .button {
        margin-top: auto;
      }
    }

    .block-heading {
      font-family: var(--font-alt);
      font-weight: 600;
      font-size: 1.1rem;
      color: var(--white);
      margin-bottom: 4px;
    }

    .block-text {
      font-family: var(--font);
      font-size: 0.9rem;
      color: var(--white);
      margin-bottom: 16px;
    }

    .header-meta {
      margin-left: 0;
      padding-right: 30px;

      h3 {
        color: var(--smoke-white);
        font-family: var(--font-alt);
        font-weight: 700;
        font-size: 1.3rem;
        max-width: 280px;
      }

      p {
        font-weight: 400;
        color: var(--smoke-white-dark-2);
        margin-bottom: 16px;
        max-width: 320px;
      }

      .action-link {
        span {
          font-size: 0.8rem;
          text-transform: uppercase;
          margin-right: 6px;
        }

        i {
          font-size: 12px;
        }
      }
    }
  }

  .feed-settings {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 0;

    h3 {
      font-family: var(--font-alt);
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--dark-text);
    }

    .button {
      font-size: 0.8rem;
      border-radius: 8px;
      margin-right: 4px;

      &.is-selected {
        background: var(--primary);
        color: var(--white);
        border-color: var(--primary);
        box-shadow: var(--primary-box-shadow);
      }
    }
  }

  .side-text {
    h3 {
      font-family: var(--font-alt);
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--dark-text);
      margin-bottom: 8px;
    }

    p {
      font-size: 0.95rem;
      margin-bottom: 8px;
    }

    .action-link {
      font-size: 0.9rem;
    }
  }

  .recent-rookies {
    .recent-rookies-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;

      h3 {
        font-family: var(--font-alt);
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--dark-text);
      }
    }

    .user-grid {
      &.user-grid-v4 {
        .grid-item {
          @include vuero-l-card;
        }
      }
    }
  }
}

.tile-grid {
  .columns {
    margin-left: -0.5rem !important;
    margin-right: -0.5rem !important;
    margin-top: -0.5rem !important;
  }

  .column {
    padding: 0.5rem !important;
  }
}

.tile-grid-v2 {
  .tile-grid-item {
    @include vuero-s-card;

    border-radius: 14px;
    padding: 16px;
    cursor: pointer;

    &:hover,
    &:focus {
      border-color: var(--primary);
      box-shadow: var(--light-box-shadow);
    }

    .tile-grid-item-inner {
      display: flex;
      align-items: center;

      > img {
        display: block;
        width: 50px;
        height: 50px;
        min-width: 50px;
      }

      .meta {
        margin-left: 10px;
        line-height: 1.4;

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
            display: flex;
            align-items: center;

            span {
              display: inline-block;
              color: var(--light-text);
              font-size: 0.8rem;
              font-weight: 400;
            }

            .icon-separator {
              position: relative;
              font-size: 4px;
              color: var(--light-text);
              padding: 0 6px;
            }
          }
        }
      }

      .dropdown {
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

  .tile-grid-v2 {
    .tile-grid-item {
      @include vuero-card--dark;

      &:hover,
      &:focus {
        border-color: var(--primary) !important;
      }
    }
  }
}
.user-grid {
  .columns {
    margin-left: -0.5rem !important;
    margin-right: -0.5rem !important;
    margin-top: -0.5rem !important;
  }

  .column {
    padding: 0.5rem !important;
  }

  .grid-item {
    position: relative;
    @include vuero-s-card;

    text-align: center;

    &:hover,
    &:focus {
      .button-wrap {
        > div {
          a {
            opacity: 1;
            pointer-events: all;
          }
        }
      }
    }

    .dropdown {
      position: absolute;
      top: 10px;
      right: 10px;
      text-align: left;
    }

    > .v-avatar {
      display: block;
      margin: 0 auto 4px;
    }

    h3 {
      font-family: var(--font-alt);
      font-size: 0.85rem;
      font-weight: 600;
      color: var(--dark-text);
    }

    p {
      font-size: 0.7rem;
    }

    .button-wrap {
      margin: 20px 0 0;

      .v-button {
        width: 100%;
        max-width: 120px;
        margin: 0 auto;
      }

      > div {
        margin: 6px 0 0;

        a {
          opacity: 0;
          pointer-events: none;
          color: var(--light-text);
          font-weight: 500;
          font-size: 0.8rem;
          transition: opacity 0.3s, color 0.3s;

          &:hover,
          &:focus {
            color: var(--primary);
          }
        }
      }
    }
  }
}

.is-dark {
  .user-grid {
    .grid-item {
      @include vuero-card--dark;
    }
  }

  .hr-dashboard {
    .block-header {
      background: var(--dark-sidebar);
      box-shadow: none;

      .center {
        border-color: var(--dark-sidebar-light-10);

        .candidates {
          button {
            background: var(--dark-sidebar-light-10);
            border: 1px solid transparent;
            transition: all 0.3s; // transition-all test

            &:hover {
              border-color: var(--primary);

              svg {
                color: var(--primary);
              }
            }
          }
        }
      }
    }

    .feed-settings {
      .button {
        &.is-selected {
          background: var(--primary) !important;
          border-color: var(--primary) !important;
          box-shadow: var(--primary-box-shadow) !important;
          color: var(--white) !important;
        }
      }
    }

    .recent-rookies {
      .user-grid {
        &.user-grid-v4 {
          .grid-item {
            @include vuero-card--dark;
          }
        }
      }
    }
  }
}

@media only screen and (max-width: 767px) {
  .hr-dashboard {
    .block-header {
      flex-direction: column;
      padding: 30px;

      .left,
      .center,
      .right {
        width: 100%;
      }

      .left {
        justify-content: flex-start;
        margin-bottom: 20px;
      }

      .center {
        padding-right: 0;
        margin-right: 0;
        border-right: none;
        margin-bottom: 20px;
      }
    }

    .feed-settings {
      flex-direction: column;

      h3 {
        margin-bottom: 16px;
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .hr-dashboard {
    .block-header {
      padding: 40px;
    }

    .side-text {
      display: none;
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
  .hr-dashboard {
    .block-header {
      padding: 40px;

      .left {
        .current-user {
          h3 {
            font-size: 1.5rem;
          }
        }
      }

      .center {
        .candidates {
          .v-avatar {
            &:nth-child(3) {
              display: none;
            }
          }
        }
      }
    }

    .column {
      &.is-7 {
        &.is-offset-1 {
          margin-left: 2% !important;
          width: 64.3333% !important;
        }
      }
    }
  }
}
</style>
