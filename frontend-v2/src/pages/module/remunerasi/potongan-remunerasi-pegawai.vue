<template>
  <ConfirmDialog />

  <VCard>
    <div class="columns column c-title">
      <h3 class="title is-5 mb-2 mr-1">Potongan Remunerasi Pegawai</h3>
    </div>

    <div class="columns is-multiline">
      <div class="column is-8">
        <div class="user-grid-toolbar">
          <VControl icon="feather:search">
            <input v-model="filters" class="input" placeholder="Search..." />
          </VControl>
          <div class="column">
            <VControl class="is-pulled-right">
              <VSwitchBlock v-model="isAktif" label="Aktif" color="danger" checked />
            </VControl>
          </div>
        </div>

        <div class="user-grid user-grid-v2">
          <DataTable :value="dataSourcefiltered" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
            :loading="isLoadData" class="p-datatable-sm"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
            <Column field="no" header="#"></Column>
            <Column field="namalengkap" header="Pegawai" :sortable="true"></Column>
            <Column field="potpersen" header="Potongan %"></Column>
            <Column field="remunfixed" header="Potongan Fixed"></Column>
            <Column field="jenispagu" header="Jenis Pagu"></Column>
            <Column :exportable="false" header="Action" style="text-align: center">
              <template #body="slotProps">
                <VIconButton type="button" icon="fas fa-ellipsis-v" class="mr-2" color="warning" circle outlined raised
                  v-tooltip.top="'Aksi'" @click="toggle($event, slotProps.data)">
                </VIconButton>
                <OverlayPanel ref="op">
                  <VButton type="button" icon="fas fa-phone-alt" class="mr-2" light circle outlined color="info" raised
                    @click="edit(slotProps.data)">
                    Edit
                  </VButton>
                  <VButton type="button" icon="fas fa-trash" class="mr-2" color="danger" :loading="loadDelete"
                    @click="DialogConfirm(slotProps.data)" circle outlined raised> Hapus
                  </VButton>
                </OverlayPanel>
              </template>
            </Column>
          </DataTable>
        </div>
      </div>

      <div class="column is-4">
        <img src="/images/avatars/svg/keluar.svg" alt="" srcset=""
          style="max-width: 32%; margin-left: 16rem; margin-top: -1rem" />
        <VCard>
          <div class="columns is-multiline">
            <div class="column is-12">
              <VField class="is-rounded-select is-autocomplete-select" label="Pegawai">
                <VControl icon="feather:search" class="prime-auto-select">
                  <AutoComplete v-model="item.pegawaifk" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pegawai..." />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                <VLabel>Jenis Pagu</VLabel>
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.jenisPagu" :options="d_JenisPagu" placeholder="Pilih data"
                    :searchable="true" :attrs="{ id }" @select="choiceJenisPagu(item.jenisPagu)" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                <VLabel>Detil Jenis Pagu</VLabel>
                <VControl icon="feather:search" :loading="isLoading">
                  <Multiselect mode="single" v-model="item.detailJenisPagu" :options="d_DetailJenisPagu"
                    placeholder="Pilih data" :searchable="true" :attrs="{ id }" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField>
                <VLabel>Potongan</VLabel>
                <VControl>
                  <input v-model="item.potongan" class="input" placeholder="Potongan" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField>
                <VLabel>Potongan Fix</VLabel>
                <VControl>
                  <input v-model="item.potonganfix" class="input" placeholder="Potongan Fix" />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-12">
            <VButton @click="save()" :loading="isLoadingButton" type="button"
              :icon="item.id ? 'feather:edit' : 'feather:save'" class="is-fullwidth mr-3"
              :color="item.id ? 'info' : 'success'" raised>
              {{ item.id ? 'Update Data' : 'Simpan Data' }}
            </VButton>
            <VButton v-if="item.id" @click="clear()" type="button" icon="feather:x-circle"
              class="is-fullwidth is-outlined is-warning mt-3" raised>
              Batal Edit
            </VButton>
            <!-- <VButton @click="save()" :loading="isLoadingButton" type="button" icon="feather:save"
              class="is-fullwidth mr-3" color="success" raised> Simpan
            </VButton> -->
          </div>
        </VCard>
      </div>
    </div>

  </VCard>
</template>

<script setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, computed } from 'vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import ConfirmDialog from 'primevue/confirmdialog'
import AutoComplete from 'primevue/autocomplete';
import OverlayPanel from 'primevue/overlaypanel';
import { useHead } from '@vueuse/head'
import { useConfirm } from 'primevue/useconfirm'
import { useToaster } from '/@src/composable/toaster'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { watch } from 'vue'
useHead({
  title: 'Potongan Remunerasi Pegawai - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({ isAktif: true })
const modalDetail = ref(false)
const op = ref();
const isAktif = ref(true)
const d_Pegawai = ref([])
let d_JenisPagu: any = ref([])
let d_DetailJenisPagu: any = ref([])
const confirm = useConfirm()

let dataSource: any = ref([])
const selectView: any = ref()
const selected: any = ref()
selectView.value = 'grid'
let isLoading: any = ref(false)
let isLoadData: any = ref(false)
let isLoadingButton: any = ref(false)
let loadDelete: any = ref(false)

const filters = ref('')

const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return dataSource.value
  }
  return dataSource.value.filter((items: any) => {
    return items.namalengkap.match(new RegExp(filters.value, 'i'))
  })
})

const getDataPotongan = async () => {
  isLoadData.value = true
  await useApi().get('sysadmin/get-data-potongan-remun').then((response) => {
    response.forEach((element: any, i: any) => {
      element.no = i + 1
    });

    dataSource.value = response
  })

  isLoadData.value = false
}

const save = async () => {

  if (!item.value.pegawaifk.value) {
    H.alert('error', 'Pegawai tidak boleh kosong')
    return
  }
  if (!item.value.jenisPagu) {
    H.alert('error', 'Jenis Pagu tidak boleh kosong')
    return
  }
  if (!item.value.detailJenisPagu) {
    H.alert('error', 'Detail Jenis Pagu tidak boleh kosong')
    return
  }
  if (!item.value.potongan) {
    H.alert('error', 'Potongan tidak boleh kosong')
    return
  }
  isLoadingButton.value = true
  let obj = {
    id: item.value.id ? item.value.id : '',
    statusenabled: true,
    objectpegawaifk: item.value.pegawaifk.value,
    potpersen: item.value.potongan,
    remunfixed: item.value.potonganfix ? item.value.potonganfix : null,
    objectjenispagufk: item.value.jenisPagu,
    objectdetailjenispagufk: item.value.detailJenisPagu,
  }

  await useApi().post('sysadmin/save-potongan-remun', obj).then((response) => {
    clear()
    getDataPotongan()
  })
  isLoadingButton.value = false
}

const DialogConfirm = (e: any) => {
  confirm.require({
    message: 'Apakah anda serius menghapus data ini ?',
    header: 'Konfirmasi Hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      deleteRow(e.id)
    },
    reject: () => { },
  })
}

const deleteRow = async (e: any) => {
  loadDelete.value = true
  await useApi().post('sysadmin/delete-potongan-remun', { 'id': e }).then((response) => {
    getDataPotongan()
  })
  loadDelete.value = false
}

const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const dataCombo = async () => {
  await useApi().get('remunerasi/get-combo-idx').then((response) => {
    d_JenisPagu.value = response.jenispagu.map((e: any) => {
      return { label: e.jenispagu, value: e.id }
    })
  })
}

const choiceJenisPagu = async (e: any) => {
  isLoading.value = true
  await useApi().get(`remunerasi/get-pegawai-by-jenis-pagu?jpid=${e}`).then((response: any) => {
    d_DetailJenisPagu.value = response.detailjenispagu.map((e: any) => {
      return { label: e.detailjenispagu, value: e.id }
    })
  })
  isLoading.value = false
}

const toggle = (event: any, e: any) => {
  console.log(event)
  op.value.toggle(event);
  selected.value = e
}

const edit = (e: any) => {
  choiceJenisPagu(e.objectjenispagufk)
  item.value.id = e.id
  item.value.pegawaifk = { label: e.namalengkap, value: e.objectpegawaifk }
  item.value.jenisPagu = e.objectjenispagufk
  item.value.detailJenisPagu = e.objectdetailjenispagufk
  item.value.potongan = e.potpersen
  item.value.potonganfix = e.remunfixed
}

const clear = () => {
  delete item.value.id
  delete item.value.pegawaifk
  delete item.value.jenisPagu
  delete item.value.detailJenisPagu
  delete item.value.potongan
  delete item.value.potonganfix
}

dataCombo()
getDataPotongan()

// watch(isAktif, () => {
//   fetchData()
// })

</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';
</style>
