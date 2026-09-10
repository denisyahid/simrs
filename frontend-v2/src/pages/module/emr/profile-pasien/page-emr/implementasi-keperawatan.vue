<template>
  <ConfirmDialog />
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header" v-if="!hideButtons">
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


      <div class="column is-12"
        v-if="props.registrasi.namaruangan.toUpperCase().indexOf('NUKLIR') > -1 && !hideButtons">
        <div class="buttons is-flex" style="align-items: center;">
          <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" :loading="isLoading"
            @click="pilihTemplateFix(index)">
            Pilih Template
          </VButton>
        </div>
      </div>

      <div class="column is-12 p-0"
        v-if="props.registrasi.namaruangan.toUpperCase().indexOf('NUKLIR') > -1 && !hideButtons">
        <hr class="m-0">
      </div>

      <div class="column is-12"
        v-if="props.registrasi.namaruangan.toUpperCase().indexOf('NUKLIR') > -1 && !hideButtons">
        <h1><b>Nama Template</b>&emsp;&emsp;<span style="color:red">**Hanya diisi jika ingin membuat
            template</span></h1>
        <VField>
          <VControl>
            <VTextarea v-model="input.namatemplate" rows="1">
            </VTextarea>
          </VControl>
        </VField>
      </div>

      <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1"
        v-if="props.registrasi.namaruangan.toUpperCase().indexOf('NUKLIR') > -1">

      <div class="columns is-multiline column is-12 pb-0 m-0">
        <div class="column is-9">
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

        <div class="column is-3 is-flex is-align-items-center">
          <VButtons style="justify-content:space-around" v-if="filterData.length == 0">
            <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem(input.details.length - 1)"
              color="info" v-tooltip.bubble="'Tambah '">
            </VIconButton>
          </VButtons>
        </div>
      </div>

      <div class="column is-12 pt-0 pb-0">
        <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
      </div>

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
                <!-- <th class="th-rpo" style="background-color: lightgray !important;" width="5%">Handover</th> -->
              </tr>
            </thead>
            <tbody v-for="(item, index) in filterData" :key="index">
              <tr>
                <td class="td-pri" style="text-align: center;">
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
                <td class="td-pri">
                  <VField class="">
                    <VControl>
                      <VTextarea rows="2" v-model="item.nodx"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td class="td-pri">
                  <VField class="">
                    <VControl>
                      <VTextarea rows="2" v-model="item.tindakankeperawatan"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td class="td-pri">
                  <VField class="">
                    <VControl>
                      <VTextarea rows="2" v-model="item.evaluasi"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td class="td-pri">
                  <VField class="pt-3">
                    <VControl class="prime-auto">
                      <AutoComplete v-model="item.paraf" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari..."
                        @item-select="setTandaTanganPegawai($event)" />
                    </VControl>
                  </VField>
                </td>
                <td class="td-pri" style="vertical-align: inherit">
                  <div class="column">
                    <VButtons style="justify-content:space-around">
                      <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem(index)"
                        color="info" v-tooltip.bubble="'Tambah '">
                      </VIconButton>
                      <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                        @click="removeItem(index)" color="danger">
                      </VIconButton>
                    </VButtons>
                  </div>
                </td>
                <!-- <td class="td-pri" style="vertical-align: inherit">
                  <div class="column">
                    <VButtons style="justify-content:space-around">
                      <VIconButton type="button" raised circle icon="feather:plus" @click="addHandover(index)"
                        color="info" v-tooltip.bubble="'Handover'">
                      </VIconButton>
                    </VButtons>
                  </div>
                </td> -->
              </tr>
              <tr>
                <td class="td-pri is-12" colspan="7">
                  <div class="column is-12 is-multiline columns">
                    <div class="column is-6">
                      <VField class="pt-3" label="Pemberi Handover">
                        <VControl class="prime-auto">
                          <AutoComplete v-model="item.pemberiHandover" :suggestions="d_Pegawai"
                            @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari..." />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-6">
                      <VField class="pt-3" label="Penerima Handover">
                        <VControl class="prime-auto">
                          <AutoComplete v-model="item.penerimaHandover" :suggestions="d_Pegawai"
                            @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari..." />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>

          </table>
          <VField style="display: none;">
            <VControl>
              <VCheckbox v-model="isHandover" label="Handover" class="p-0" color="primary" square true-value="Handover"
                @change="Handover" />
            </VControl>
          </VField>
          <div class="column columns is-multiline" v-if="showHandover">
            <div class="column is-6 pt-0 pl-0">
              <VField class="pt-3" label="Pemberi Handover">
                <VControl class="prime-auto">
                  <AutoComplete v-model="pemberiHandover" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari..." />
                </VControl>
              </VField>
            </div>
            <div class="column is-6 pt-0 pl-0">
              <VField class="pt-3" label="Penerima Handover">
                <VControl class="prime-auto">
                  <AutoComplete v-model="penerimaHandover" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari..." />
                </VControl>
              </VField>
            </div>
          </div>
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
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount, } from 'vue'
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
import { useConfirm } from "primevue/useconfirm"
import ConfirmDialog from 'primevue/confirmdialog'

useHead({ title: 'Implementasi Keperawatan' + ' - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

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
    hideButtons?: boolean
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: '',
    FORM_URL: '',
    COLLECTION: '',
    hideButtons: false,
  }
)
const isHandover: any = ref('')
const route = useRoute()
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const confirm = useConfirm();
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
  selectedMenu: [false],
  qFilterTgl: {
    start: new Date(new Date().setDate(new Date().getDate() - 3)),
    end: new Date()
  }
})
const COLLECTION: any = ref('ImplementasiKeperawatan') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  details: [{
    no: 1,
    tgltindakan: new Date(),
    paraf: { label: user.namaLengkap, value: user.id },
    handOver: false
  }],
  handoverDetails: [],
  tglDibuat: new Date()
})
const showHandover: any = ref(false)
function Handover() {
  if (isHandover.value == 'Handover') {
    showHandover.value = true
  } else {
    showHandover.value = false
  }
}
const handoverDetails = ref<any[]>([]);
const addHandover = (index: any) => {

}

const pemberiHandover = computed({
  get: () => input.value.details[0]?.pemberiHandover || null,
  set: (val) => {
    input.value.details = input.value.details.map(detail => ({
      ...detail,
      pemberiHandover: val
    }));
  }
});

const penerimaHandover = computed({
  get: () => input.value.details[0]?.penerimaHandover || null,
  set: (val) => {
    input.value.details = input.value.details.map(detail => ({
      ...detail,
      penerimaHandover: val
    }));
  }
});

const filterData = computed(() => {
  let start = new Date(item.qFilterTgl.start);
  start.setHours(0, 0, 0, 0);

  let end = new Date(item.qFilterTgl.end);
  end.setHours(23, 59, 59, 999);

  const filtered = input.value.details.filter((detail) => {
    const detailDate = new Date(detail.tgltindakan);
    return detailDate >= start && detailDate <= end;
  });

  return filtered;
});

const loadRiwayat = async () => {
  isLoading.value = true
  await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then(async (response: any) => {
    const ttv = await useApi().get(`emr/get-data-exist-semua?norec_pd=${NOREC_PD}`);
    isLoading.value = false
    if (response.length) {
      input.value = response[0];
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk;
      }
      if (response[0].ttdKeluarga) {
        H.tandaTangan().set("signatureKeluarga", response[0].ttdKeluarga);
      }
      if (response[0].ttdPetugas) {
        H.tandaTangan().set("signaturePetugas", response[0].ttdPetugas);
      }
    } else {
      if (Array.isArray(ttv)) {
        if (!Array.isArray(input.value.details)) {
          input.value.details = [];
        }

        ttv.forEach((dataItem: any, index: number) => {
          let data = '';
          data += dataItem.GCSe ? `GCS E : ${dataItem.GCSe}\n` : '';
          data += dataItem.GCSv ? `GCS V : ${dataItem.GCSv}\n` : '';
          data += dataItem.GCSm ? `GCS M : ${dataItem.GCSm}\n` : '';
          data += dataItem.tekananDarah ? `Tekanan Darah : ${dataItem.tekananDarah}\n` : '';
          data += dataItem.nadi ? `Nadi : ${dataItem.nadi}\n` : '';
          data += dataItem.pernapasan ? `Respirasi : ${dataItem.pernapasan}\n` : '';
          data += dataItem.suhu ? `Suhu : ${dataItem.suhu}\n` : '';
          data += dataItem.SPO2 ? `SpO2 : ${dataItem.SPO2}\n` : '';
          data += dataItem.beratBadan ? `Berat Badan : ${dataItem.beratBadan}\n` : '';
          data += dataItem.tinggiBadan ? `Tinggi Badan : ${dataItem.tinggiBadan}\n` : '';

          if (!input.value.details[index]) {
            input.value.details[index] = { evaluasi: '' };
          }
          input.value.details[index].evaluasi = data;
          input.value.details[index].tgltindakan = dataItem.tanggal;
          input.value.details[index].paraf = dataItem.user_input.namalengkap;
        });
      } else {
        console.error("Data ttv bukan array:", ttv);
      }
    }
  }).catch(error => {
    console.error("Error saat memuat riwayat:", error);
  });
};

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
const resetForm = () => {
  input.value.pemberiHandover = ''
  input.value.penerimaHandover = ''
}

const simpan = async () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  if (object.hasOwnProperty('namatemplate')) {
    delete object.namatemplate
  }
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
      resetForm();
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

const addNewItem = (e: any) => {
  input.value.details.unshift({
    no: input.value.details[input.value.details.length - 1].no + 1,
    tgltindakan: new Date(),
    paraf: { label: user.namaLengkap, value: user.id }
  });
}
const removeItem = (index: number) => {
  confirm.require({
    message: 'Apakah anda yakin ingin menghapus kolom?',
    header: 'Hapus Kolom',
    icon: 'pi pi-exclamation-triangle',
    acceptClass: 'p-button-danger',
    accept: () => {
      input.value.details.splice(index, 1);
    },
    reject: () => {
      console.log('Penghapusan dibatalkan');
    }
  });
};

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
