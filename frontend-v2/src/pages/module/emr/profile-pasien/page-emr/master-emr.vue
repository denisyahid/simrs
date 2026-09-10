<template>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3> {{ props.FORM_NAME }}</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="props.isLoading" @simpan="$emit('simpan')"
              @simpanTemplate="$emit('simpanTemplate')" @kembaliKeun="kembaliKeun" :isHideST="isHideTemplate"></ButtonEmr>
            </div>
          </div>
        </div>

      </div>
    </div>


    <div class="column">
      <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
          <VButton type="button" rounded outlined color="primary" raised icon="feather:folder"
              :loading="isLoading" @click="pilihTemplateFix(index)" v-if="!isHideTemplate"> Pilih Template
          </VButton>
          <VButton type="button" rounded outlined color="info" raised icon="feather:file-text"
            :loading="isLoading" @click="pilihTemplate(index)"> Pilih Riwayat
          </VButton>
      </div>

      <hr>

      <div class="column is-12" v-if="!isHideTemplate">
          <h1><b>Nama Template</b>&emsp;&emsp;<span style="color:red">**Hanya diisi jika ingin membuat
                  template</span></h1>
          <VField>
              <VControl>
                  <VInput v-model="input.namatemplate" />
              </VControl>
          </VField>
      </div>

      <hr>

      <slot name="content"></slot>
    </div>

    <VModal :open="showModalTemplate" title="Riwayat" :noclose="true" size="large" actions="right"
        @close="showModalTemplate = false">
        <template #content>
            <form class="modal-form">
                <div class="column is-12 pt-0 pb-0">
                    <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
                    <div style="overflow-y:auto;" class="mt-1">
                        <table class="tg table-tg" v-if="listTemplate.length > 0">
                            <thead>
                                <tr>
                                    <td class="tg-0lax text-center" width="15%">Tanggal Input</td>
                                    <td class="tg-0lax text-center" width="15%">Tanggal Registrasi</td>
                                    <td class="tg-0lax text-center" width="15%">No Registrasi</td>
                                    <td class="tg-0lax text-center" width="15%">No EMR</td>
                                    <td class="tg-0lax text-center" width="20%">Dokter</td>
                                    <td class="tg-0lax text-center" width="15%">Section</td>
                                    <td class="tg-0lax text-center" width="5%">#</td>
                                </tr>
                            </thead>
                            <tbody v-for="resep in listTemplate">
                                <tr>
                                    <td style="width:15%;text-align:center">
                                        <span class="mb-2">{{ resep.created_at }}</span><br>
                                    </td>
                                    <td style="width:15%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                                    </td>
                                    <td style="width:15%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                                    </td>
                                    <td style="width:15%;text-align:center">
                                        <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                                    </td>
                                    <td style="width:20%;text-align:center">
                                        <span class="mb-2">{{ resep.dpjpUtama }}</span><br>
                                    </td>
                                    <td style="width:15%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                                    </td>
                                    <td style="width:5%;text-align:center">
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="addTemplate(resep)" color="info" v-tooltip-prime.top="'Pilih'">
                                        </VIconButton>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </template>
    </VModal>


    <VModal :open="showModalTemplate" title="Riwayat" :noclose="true" size="large" actions="right"
    @close="isAlltemplate = false; showModalTemplate = false">
      <template #content>
        <DataTable
            :pt="{
                table: { style: 'min-width: 50rem; min-height: 10rem;' },
                column: {
                    bodycell: ({ state }) => ({
                        class: [{ 'pt-0 pb-0': state['d_editing'] }]
                    })
                }
            }"
            v-model:filters="filtersTemplate"
            :value="listTemplate"
            :metaKeySelection="false"
            :rows="8"
            :loading="isLoading"
            paginator
            tableStyle="min-width: 50rem"
            dataKey="no"
            :totalRecords="listTemplate.length"
            :globalFilterFields="['namatemplate', 'registrasi.namaruangan']"
            responsiveLayout="stack" breakpoint="960px"
            >
            <template #header>
                <div class="columns is-multiline">
                    <div class="column is-8">
                        <VField>
                            <InputText v-model="filtersTemplate['global'].value" placeholder="Search" />
                        </VField>
                    </div>
                    <div class="column is-4">
                      <VField>
                          <VControl>
                              <VSwitchBlock v-model="isAlltemplate" color="success" label="Semua Riwayat" />
                          </VControl>
                      </VField>
                    </div>
                </div>
            </template>
            <template #empty> No customers found. </template>
            <template #loading>
                <img src="/images/other/loadingspin.gif" alt="Loading..." width="100"/>
                <p style="color:white">Loading data, please wait...</p>
            </template>
            <Column field="created_at" header="Tanggal" :sortable="true">
                <template #body="slotProps">
                    <span>{{ H.formatDateToLocalString(slotProps.data.created_at) }}</span>
                </template>
            </Column>
            <Column field="registrasi.noregistrasi" header="No Registrasi" :sortable="true"></Column>
            <Column field="noemr" header="No EMR" :sortable="true"></Column>
            <Column field="registrasi.namaruangan" header="Nama Ruangan" :sortable="true">
                <template #body="slotProps">
                    {{ slotProps.data.registrasi.namaruangan }}
                </template>
            </Column>
            <Column field="user_input.namalengkap" header="Petugas" :sortable="true"></Column>
            <Column field="alasan" header="Alasan" :sortable="true" style="display: none !important"></Column>

            <Column headerStyle="width: 8rem">
                <template #body="slotProps">
                  <VButtons>
                    <!-- <VIconButton color="danger" light raised circle icon="lucide:x" @click="viewRiwayat(slotProps.data._id.$oid)"
                    v-if="!isAlltemplate" v-tooltip-prime.top="'View'"/> -->
                    <VIconButton type="button" raised circle icon="fas fa-plus"
                        @click="addTemplate(slotProps.data)" color="info" v-tooltip-prime.top="'Pilih'">
                    </VIconButton>
                    <button type="button" aria-hidden="false"
                      class="button is-outlined is-raised is-primary"
                      @click="viewRiwayat(slotProps.data)">
                      <span class="icon"><i aria-hidden="true" class="fas fa-eye"></i></span>
                    </button>
                    <button type="button" aria-hidden="false"
                      class="button is-outlined is-raised is-primary"
                      @click="printRiwayat(slotProps.data)">
                      <span class="icon"><i aria-hidden="true" class="fas fa-print"></i></span>
                    </button>
                  </VButtons>
                </template>
            </Column>
        </DataTable>
      </template>
    </VModal>


    <VModal :open="showModalTemplateFix" title="Template" :noclose="true" size="large" actions="right"
    @close="isAlltemplate = false; showModalTemplateFix = false">
    <template #content>
      <DataTable
          :pt="{
              table: { style: 'min-width: 50rem; min-height: 10rem;' },
              column: {
                  bodycell: ({ state }) => ({
                      class: [{ 'pt-0 pb-0': state['d_editing'] }]
                  })
              }
          }"
          v-model:filters="filtersTemplate"
          :value="listTemplateFix"
          :metaKeySelection="false"
          :rows="8"
          :loading="isLoading"
          paginator
          tableStyle="min-width: 50rem"
          dataKey="no"
          :totalRecords="listTemplateFix.length"
          :globalFilterFields="['namatemplate', 'registrasi.namaruangan']"
          responsiveLayout="stack" breakpoint="960px"
          >
          <template #header>
              <div class="columns is-multiline">
                  <div class="column is-8">
                      <VField>
                          <InputText v-model="filtersTemplate['global'].value" placeholder="Search Nama Template" />
                      </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                        <VControl>
                            <VSwitchBlock v-model="isAlltemplate" color="success" label="Semua Template" />
                        </VControl>
                    </VField>
                  </div>
              </div>
          </template>
          <template #empty> No customers found. </template>
          <template #loading>
              <img src="/images/other/loadingspin.gif" alt="Loading..." width="100"/>
              <p style="color:white">Loading data, please wait...</p>
          </template>
          <Column field="created_at" header="Tanggal" :sortable="true">
              <template #body="slotProps">
                  <span>{{ H.formatDateToLocalString(slotProps.data.created_at) }}</span>
              </template>
          </Column>
          <Column field="_id.$oid" header="Id" :sortable="true" style="display: none !important"></Column>
          <Column field="namatemplate" header="Nama Template" :sortable="true"></Column>
          <Column field="user_input.namalengkap" header="Petugas" :sortable="true"></Column>
          <Column field="alasan" header="Alasan" :sortable="true" style="display: none !important"></Column>
          <Column field="registrasi.namaruangan" header="Nama Ruangan" :sortable="true">
              <template #body="slotProps">
                  {{ slotProps.data.registrasi.namaruangan }}
              </template>
          </Column>
          <Column headerStyle="width: 8rem">
              <template #body="slotProps">
                <VButtons>
                  <VIconButton color="danger" light raised circle icon="fas fa-trash" @click="deleteTemplate(slotProps.data._id.$oid)"
                  v-if="!isAlltemplate" v-tooltip-prime.top="'Hapus'"/>
                  <VIconButton type="button" raised circle icon="fas fa-plus"
                      @click="addTemplate(slotProps.data)" color="info" v-tooltip-prime.top="'Pilih'">
                  </VIconButton>
                </VButtons>
              </template>
          </Column>
      </DataTable>
    </template>
  </VModal>
</template>

<script setup lang="ts">
  import { useWindowScroll } from '@vueuse/core'
  import { useApi } from '/@src/composable/useApi'
  import { h, reactive, ref, computed, defineComponent, watch, onMounted, defineExpose } from 'vue'
  import { useRoute, useRouter } from 'vue-router'
  import * as H from '/@src/utils/appHelper'
  import ButtonEmr from '../page-emr-plugins/button-emr.vue'
  import { useThemeColors } from '/@src/composable/useThemeColors'
  import { useUserSession } from '/@src/stores/userSession'
  import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
  import { FilterMatchMode } from 'primevue/api';
  import DataTable from 'primevue/datatable';
  import Column from 'primevue/column'

  // const emit = defineEmits<{
  //   (e: 'simpan'): void,
  //   }>()
    const emits = defineEmits(['update:input', 'simpan', 'simpanTemplate', 'kembaliKeun', 'update:isHideTemplate', 'addTemplate'])

  const props = withDefaults(
    defineProps<{
      pasien?: any
      registrasi?: any
      FORM_NAME?: string
      FORM_URL?: string
      COLLECTION?: string
      isTTD?: boolean
      fieldTTD?: string
      NOREC_PD: string
      norec_emr: string
      ID_PASIEN: string
      input: any
      isLoading: boolean
      isHideTemplate: boolean
      simpanTemplate?: any
      simpan?: any,
      addTemplate?: any
    }>(),
    {
      pasien: {},
      registrasi: {},
      FORM_NAME: '',
      FORM_URL: '',
      COLLECTION: '',
      isTTD: false,
      fieldTTD: '',
      NOREC_PD: '',
      norec_emr: '',
      ID_PASIEN: '',
      input: {},
      isLoading: false,
      isHideTemplate: false,
      simpanTemplate: {},
      simpan: {},
      addTemplate: {},
    }
  )
const filtersTemplate = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS }
});
let ID_PASIEN = props.ID_PASIEN
let NOREC_PD = props.NOREC_PD
let norec_emr = props.norec_emr
const isAktive = ref()
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const isAlltemplate: any = ref(false);
const alertMid = ref(false);
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false);
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref(norec_emr)
const peraturan:any = ref(false);
const input = ref(props.input)
const dataTTD: any = ref([])
let dataKirim: any = ref();
const loadRiwayat = async () => {
  // if (NOREC_EMRPASIEN.value == '') return
  return new Promise((resolve, rejects) => {
    console.log("PROPS DATA", props)
    useApi().get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
        if (response.length) {
          input.value = response[0] //set ke inputan
          if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
          }
          if(props.isTTD) {
            if(response[0][props.fieldTTD] && Array.isArray(response[0][props.fieldTTD]) ) {
              // dataTTD.value = response[0][props.fieldTTD];
              response[0][props.fieldTTD].forEach(element => {
                H.tandaTangan().set("ttd_"+element.no, element.ttd);
              });
            }else {
              dataTTD.value = response[0][props.fieldTTD];
              H.tandaTangan().set("dataTTD", dataTTD.value);
            }
          }

          return resolve(response[0])
        }else {
          return resolve(null)
        }
      })
  })
}

const printRiwayat = (response: any) => {
  H.printBladeSelf(`emr/cetak/${props.COLLECTION}?pdf=true&emrpasienfk=${response.emrpasienfk}`)
}

const viewRiwayat = (response: any) => {
  H.printBladeSelf(`emr/cetak/${props.COLLECTION}?pdf=false&emrpasienfk=${response.emrpasienfk}`)
}

const simpanTemplate = () => {
  if(!input.value.namatemplate) {
    H.alert('warning', "Nama Template wajib diisi")
    return;
  }
    let ID = input.id ? input.id : ''
    let object: any = {}

    object = input.value
    object.nocm = pasien.value.nocm

    object.pasien = H.setObjectPasien(pasien.value)
    object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
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
        `/emr/simpan-emr-template`, json).then((response: any) => {
            isLoading.value = false
            input.value.namatemplate = null
        }).catch((e: any) => {
            isLoading.value = false
        })
}

const pilihTemplate = async (index: any) => {
    isLoading.value = true
    useApi().get(
        `/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
            isLoading.value = false
            if (responselast.length) {
                listTemplate.value = responselast //set ke inputan
                showModalTemplate.value = true
            } else {
                H.alert('warning', 'Data tidak ada')
            }
        })
}

const addTemplate = (response: any) => {
    // console.log('response alasan', response.alasan)
    // console.log('props alasan', props.input.alasan)
    // watchEffect(() => {
    //   props.input = response;
    // });
    // emits('update:props.input', response)
    emits('update:input', response);
    // props.input = response;
    // console.log('hasil props alasan', props.input)
    console.log(JSON.stringify(props.input, null, 2))

    props.input.namatemplate = null;
    delete props.input._id
    isAlltemplate.value = false;
    showModalTemplateFix.value = false;
    H.alert('success', 'Berhasil ditambahkan');
}

const deleteTemplate = (idTemplate) => {
  console.log(idTemplate)
  isLoading.value = true
  let json = {
    'id': idTemplate,
    'collection': COLLECTION.value
  }
  useApi().post(
    `/emr/hapus-template`, json).then((response: any) => {
      if(response.status !== 500) {
        isLoading.value = false
        isAlltemplate.value = false;
        H.alert('sucess', response.message);
        pilihTemplateFix();
      }else {
        H.alert('danger', response.message);
      }
    }).catch((e: any) => {
      isLoading.value = false
      H.alert('danger', e);
    })
}

const pilihTemplateFix = async (index: any) => {
    isLoading.value = true
    useApi().get(
    `/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
        isLoading.value = false
        console.log(responselast)
        if (responselast.length) {
            for (var x = 0; x < responselast.length; x++) {
                responselast[x].no = x + 1
                responselast[x].id = ''
            }
            listTemplateFix.value = responselast //set ke inputan
            showModalTemplateFix.value = true
        } else {
            H.alert('warning', 'Data tidak ada')
        }
    })
}

const kembaliKeun = () => {
  window.history.back()
}

// function allData() {
//   return {
//     norec_EMR: NOREC_EMRPASIEN,
//     dataInput: input,
//     dataInput2: dataKirim,
//   }
//   // return NOREC_EMRPASIEN;
//   // console.log("data ada")
// }

watch(
  () => props.input,
  (newVal) => {
    input.value = newVal
  },
  { deep: true, immediate: true }
)

function allData() {
  loadRiwayat()
  // console.log("All data function triggered!");
}

defineExpose({
  loadRiwayat
})


</script>

<style lang="scss">
.table-popri {
  width: 150%;
  border: 1px solid black;
}

.th-popri {
  text-align: center !important;
}

.th-popri,
.td-popri {
  padding: 7px;
  border: 1px solid black;
  vertical-align: inherit;
}

.setpopri-center {
  text-align: center !important;
}

.p-fieldset-legend {
  margin-left: 15px;
}
</style>
