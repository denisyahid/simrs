<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpanTemplate="simpanTemplate" @simpan="simpan" @kembaliKeun="kembaliKeun" isHideCetak></ButtonEmr>
          </div>
        </div>
      </div>


      <div class="column is-12">
        <div class="buttons is-flex" style="align-items: center;">
          <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" :loading="isLoading"
            @click="pilihTemplateFix(index)">
            Pilih Template
          </VButton>
        </div>
      </div>

      <div class="column is-12 p-0">
        <hr class="m-0">
      </div>

      <div class="column is-12">
        <h1><b>Nama Template</b>&emsp;&emsp;<span style="color:red">**Hanya diisi jika ingin membuat
            template</span></h1>
        <VField>
          <VControl>
            <VTextarea v-model="input.namatemplate" rows="1">
            </VTextarea>
          </VControl>
        </VField>
      </div>

      <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

      <div class="column is-12">
        <div class="column" style="overflow: auto;">
          <table class="table-rpo">
            <thead>
              <tr>
                <th class="th-rpo" style="background-color: lightgray !important;" width="20%">JAM/TGL</th>
                <th class="th-rpo" style="background-color: lightgray !important;" width="15%">NO DX</th>
                <th class="th-rpo" style="background-color: lightgray !important;" width="20%">TINDAKAN KEPERAWATAN
                </th>
                <th class="th-rpo" style="background-color: lightgray !important;" width="20%">EVALUASI</th>
                <th class="th-rpo" style="background-color: lightgray !important;" width="20%">PARAF & NAMA TERANG
                </th>
                <th class="th-rpo" style="background-color: lightgray !important;" width="5%">#</th>
              </tr>
            </thead>
            <tbody v-for="(item, index) in input.details" :key="index">
              <tr>
                <td class="td-rpo" style="text-align: center;">
                  <VDatePicker v-model="item.tgltindakan" mode="dateTime" style="width: 100%;" is24hr>
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </td>
                <td class="td-rpo">
                  <VField class="">
                    <VControl>
                      <VTextarea rows="2" v-model="item.nodx"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td class="td-rpo">
                  <VField class="">
                    <VControl>
                      <VTextarea rows="2" v-model="item.tindakankeperawatan"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td class="td-rpo">
                  <VField class="">
                    <VControl>
                      <VTextarea rows="2" v-model="item.evaluasi"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td class="td-rpo">
                  <VField class="pt-3">
                    <VControl class="prime-auto">
                      <AutoComplete v-model="item.paraf" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari..."
                        @item-select="setTandaTanganPegawai($event)" />
                    </VControl>
                  </VField>
                </td>
                <td class="td-rpo" style="vertical-align: inherit">
                  <div class="column">
                    <VButtons style="justify-content:space-around">
                      <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                        v-tooltip.bubble="'Tambah '">
                      </VIconButton>
                      <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                        @click="removeItem(index)" color="danger">
                      </VIconButton>
                    </VButtons>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <VModal :open="showModalTemplateFix" title="Template" :noclose="true" size="large" actions="right"
    @close="isAlltemplate = false; showModalTemplateFix = false">
    <template #content>
      <DataTable :pt="{
        table: { style: 'min-width: 50rem; min-height: 10rem;' },
        column: {
          bodycell: ({ state }) => ({
            class: [{ 'pt-0 pb-0': state['d_editing'] }]
          })
        }
      }" v-model:filters="filtersTemplate" :value="listTemplateFix" :metaKeySelection="false" :rows="10" paginator
        tableStyle="min-width: 50rem" dataKey="no" :totalRecords="listTemplateFix.length"
        :globalFilterFields="['namatemplate', 'registrasi.namaruangan']" responsiveLayout="stack" breakpoint="960px">
        <template #header>
          <div class="columns is-multiline">
            <div class="column is-8">
              <VField>
                <InputText v-model="filtersTemplate['global'].value" placeholder="Search Nama Template" />
              </VField>
            </div>
            <div class="column is-4"></div>
          </div>
        </template>
        <template #empty> No customers found. </template>
        <template #loading>
          <img src="/images/other/loadingspin.gif" alt="Loading..." width="100" />
          <p style="color:white">Loading data, please wait...</p>
        </template>
        <Column headerStyle="width: 8rem">
          <template #body="slotProps">
            <VButtons>
              <VIconButton color="danger" light raised circle icon="lucide:x" @click="deleteTemplate(slotProps.data.id)"
                v-if="!isAlltemplate" v-tooltip-prime.top="'Hapus'" />
              <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(slotProps.data)"
                color="info" v-tooltip-prime.top="'Pilih'">
              </VIconButton>
            </VButtons>
          </template>
        </Column>
        <Column field="namatemplate" header="Nama" :sortable="true"></Column>
        <Column field="created_at" header="Tanggal" :sortable="true">
          <template #body="slotProps">
            <span>{{ H.formatDateToLocalString(slotProps.data.created_at) }}</span>
          </template>
        </Column>
      </DataTable>
    </template>
  </VModal>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteUpdate, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import InputText from 'primevue/inputtext';
import { FilterMatchMode } from 'primevue/api';
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
// import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'

const filtersTemplate = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS } });
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

const props = withDefaults(
  defineProps<{
    pasien?: any
    registrasi?: any
    FORM_NAME?: string
    FORM_URL?: string
    COLLECTION?: string
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: '',
    FORM_URL: '',
    COLLECTION: '',
  }
)
const route = useRoute()
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const idTemplate: any = ref('');
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_Pegawai: any = ref([])
const d_Obat: any = ref([])
const user = useUserSession().getUser().pegawai;
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('ImplementasiKeperawatanNuklir') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  details: [{
    no: 1,
    tgltindakan: new Date(),
    paraf: { label: user.namaLengkap, value: user.id }
  }],
  tglDibuat: new Date()
})
useHead({ title: 'Implementasi Keperawatan' + ' - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const loadRiwayat = () => {
  useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then(async (response: any) => {
    if (response.length) {
      input.value = response[0] //set ke inputan
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
      if (response[0].ttdKeluarga) {
        H.tandaTangan().set("signatureKeluarga", response[0].ttdKeluarga)
      }
      if (response[0].ttdPetugas) {
        H.tandaTangan().set("signaturePetugas", response[0].ttdPetugas)
      }
    } else {
      const ttv = await useApi().get(`emr/get-data-exist?nocmfk=${ID_PASIEN}`)
      let data = '';
      if (ttv != null) {
        data += ttv.GCSe ? `GCS E : ${ttv.GCSe}\n` : '';
        data += ttv.GCSv ? `GCS V : ${ttv.GCSv}\n` : '';
        data += ttv.GCSm ? `GCS M : ${ttv.GCSm}\n` : '';
        data += ttv.tekananDarah ? `Tekanan Darah : ${ttv.tekananDarah}\n` : '';
        data += ttv.nadi ? `Nadi : ${ttv.nadi}\n` : '';
        data += ttv.pernapasan ? `Respirasi : ${ttv.pernapasan}\n` : '';
        data += ttv.suhu ? `Suhu : ${ttv.suhu}\n` : '';
        data += ttv.SPO2 ? `SpO2 : ${ttv.SPO2}\n` : '';
        data += ttv.beratBadan ? `Berat Badan : ${ttv.beratBadan}\n` : '';
        data += ttv.tinggiBadan ? `Tinggi Badan : ${ttv.tinggiBadan}\n` : '';

        input.value.details[0].evaluasi = data;
      }
    }
  })
}

const simpanTemplate = () => {
  if (input.value.namatemplate == null) {
    H.alert('warning', 'Isi nama template terlebih dahulu!')
    return;
  }
  let ID = idTemplate.value ? idTemplate.value : ''
  let object: any = {}

  object = input.value
  object.nocm = props.pasien.nocm
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': props.FORM_URL,
    'name_form': props.FORM_NAME,
    'jenis_emr': 'asesmen_medis',
    'data': object
  }
  isLoading.value = true

  useApi().post(`/emr/simpan-emr-template`, json).then((response: any) => {
    isLoading.value = false
    checkTemplate.value = false
    input.value.namatemplate = null
    delete object.namatemplate;
    delete object['_id'];
    delete object.nocm;
    delete object.pasien;
    delete object.registrasi;
    object.id = '';
  }).catch((e: any) => {
    isLoading.value = false
  })
}

const simpan = async () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.ttdKeluarga = H.tandaTangan().get("signatureKeluarga")
  object.ttdPetugas = H.tandaTangan().get("signaturePetugas")
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': props.FORM_URL,
    'name_form': props.FORM_NAME,
    'jenis_emr': 'asesmen_medis',
    'data': object
  }
  isLoading.value = true
  useApi().post(
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
      loadRiwayat();
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const setTandaTanganPegawai = async (e: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signaturePetugas", element.ttd)
    } else {
      H.tandaTangan().set("signaturePetugas", '')
    }
  })
}

const addNewItem = () => {
  input.value.details.unshift({
    no: input.value.details[input.value.details.length - 1].no + 1,
    tgltindakan: new Date(),
  });
}
const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
}

const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi().get(`/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
    isLoading.value = false
    if (responselast.length) {
      for (var x = 0; x < responselast.length; x++) {
        responselast[x].no = x + 1
      }
      listTemplateFix.value = responselast //set ke inputan
      showModalTemplateFix.value = true
    } else {
      H.alert('warning', 'Data tidak ada')
    }
  })
}

const addTemplate = (response: any) => {
  response.details.forEach((e) => {
    e.tgltindakan = null;
  });
  input.value = response //set ke inputan
  delete input.value.namatemplate;
  delete input.value['_id'];
  input.value['id'] = ''
  showModalTemplateFix.value = false
}

const deleteTemplate = (idTemplate) => {
  isLoading.value = true
  let json = {
    'id': idTemplate,
    'collection': COLLECTION.value
  }
  useApi().post(`/emr/hapus-template`, json).then((response: any) => {
    if (response.status !== 500) {
      isLoading.value = false;
      isAlltemplate.value = false;
      H.alert('sucess', response.message);
      pilihTemplateFix();
    } else {
      H.alert('danger', response.message);
    }
  }).catch((e: any) => {
    isLoading.value = false
    H.alert('danger', e);
  })
  showModalTemplateFix.value = false;
}

onBeforeMount(async () => {
  try {
    await loadRiwayat()
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
    if (cache) input.value = cache
  } catch (error) {
    console.error('Error mount cache TAB EMR:', error);
  }
});
onBeforeRouteLeave((to, from, next) => {
  try {
    let rouutename = from?.name
    H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
  } catch (error) {
    console.error('Error leave cache TAB EMR:', error);
  }
  next();
});
</script>

<style lang="scss">
.table-rpo {
  width: 100%;
  border: 1px solid;
}

.th-rpo,
.td-rpo {
  padding: 7px;
  border: 1px solid black;
  vertical-align: inherit;
}

.th-rpo {
  text-align: center !important;
}

.p-fieldset-legend {
  margin-left: 15px;
}
</style>
