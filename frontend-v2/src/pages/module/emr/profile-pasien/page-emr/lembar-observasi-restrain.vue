<template>
    <div class="form-layout is-stacked-3">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3> {{ props.FORM_NAME }}</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate"
                @kembaliKeun="kembaliKeun" isHideCetak></ButtonEmr>
            </div>
          </div>
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
                        <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;" width="15%">Tanggal Input</td>
                        <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;" width="15%">Tanggal Registrasi</td>
                        <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;" width="15%">No Registrasi</td>
                        <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;" width="15%">No EMR</td>
                        <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;" width="20%">Dokter</td>
                        <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;" width="15%">Section</td>
                        <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;" width="5%">#</td>
                      </tr>
                    </thead>
                    <tbody v-for="resep in listTemplate">
                      <tr>
                        <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                          <span class="mb-2">{{ resep.created_at }}</span><br>
                        </td>
                        <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                          <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                        </td>
                        <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                          <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                        </td>
                        <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                          <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                        </td>
                        <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                          <span class="mb-2">{{ resep.dpjpUtama }}</span><br>
                        </td>
                        <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                          <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                        </td>
                        <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                          <VIconButton type="button" raised circle icon="fas fa-plus" @click="addRiwayat(resep)"
                            color="info" v-tooltip-prime.top="'Pilih'">
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
              :globalFilterFields="['namatemplate', 'registrasi.namaruangan']" responsiveLayout="stack"
              breakpoint="960px">
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
                    <VIconButton color="danger" light raised circle icon="lucide:x"
                      @click="deleteTemplate(slotProps.data.id)" v-if="!isAlltemplate" v-tooltip-prime.top="'Hapus'" />
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(slotProps.data)"
                      color="info" v-tooltip-prime.top="'Pilih'">
                    </VIconButton>
                    <VIconButton type="button" raised circle icon="fas fa-pencil-alt"
                      @click="editTemplate(slotProps.data)" color="info" v-tooltip-prime.top="'Edit'"
                      v-if="!isAlltemplate">
                    </VIconButton>
                  </VButtons>
                </template>
              </Column>
              <Column field="namatemplate" header="Nama" :sortable="true"></Column>
              <!-- <Column field="registrasi.namaruangan" header="Nama Ruangan" :sortable="true">
                <template #body="slotProps">
                  {{ slotProps.data.registrasi.namaruangan }}
                </template>
              </Column> -->
              <Column field="created_at" header="Tanggal" :sortable="true">
                <template #body="slotProps">
                  <span>{{ H.formatDateToLocalString(slotProps.data.created_at) }}</span>
                </template>
              </Column>
            </DataTable>
          </template>
        </VModal>

        <div class="column is-12">
          <VCard>
            <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
              <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" isLoading="false"
                @click="pilihTemplateFix(index)"> Pilih Template
              </VButton>
              <VButton type="button" rounded outlined color="info" raised icon="feather:file-text" isLoading="false"
                @click="pilihTemplate(index)"> Pilih Riwayat
              </VButton>
            </div>
  
            <div class="column is-12 pt-0 pb-0">
              <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
            </div>
  
            <div class="column is-12">
              <div class="columns">
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
              </div>
            </div>

            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-3">
                  <span style="font-weight: 500;">Tanggal MRS :</span>
                  <VField class="pt-3">
                    <VDatePicker v-model="input.tanggalMasukRS" mode="datetime" trim-weeks :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:calendar" fullwidth>
                                <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </template>
                    </VDatePicker>
                  </VField>
                </div>
                <div class="column is-6">
                </div>
                <div class="column is-3">
                  <span style="font-weight: 500;">Ruangan :</span>
                  <VField class="pt-3">
                    <VControl class="prime-auto">
                      <AutoComplete v-model="input.ruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                        :field="'label'" placeholder="Cari Ruangan..." />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
    
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-9">
                    <span style="font-weight: 500;">Diagnosa :</span>
                    <VField class="pt-3">
                      <VControl>
                          <VInput type="text" class="input" v-model="input.diagnosa" />
                      </VControl>
                    </VField>
                </div>  
                <div class="column is-3">
                    <span style="font-weight: 500;">No. Bed :</span>
                    <VField class="pt-3">
                      <VControl>
                          <VInput type="text" class="input" v-model="input.noBed" />
                      </VControl>
                    </VField>
                </div>  
              </div>
            </div>
            <div class="table-container" style="overflow: auto;">
              <table class="table is-bordered" style="width: auto !important; border-collapse: collapse; border:1px solid black;">
                <tr>
                  <th rowspan="3" class="has-text-centered" style="vertical-align:middle; min-width:175px;">
                    Waktu <br>
                    <span class="is-size-7">
                      (Tanggal & Jam)
                    </span>
                  </th>
                  <th rowspan="3" class="has-text-centered" style="vertical-align:middle; min-width:200px;">
                    Perawat Jaga <br>
                    <span class="is-size-7">
                      (Nama & Paraf)
                    </span>
                  </th>
                  <th rowspan="3" class="has-text-centered" style="vertical-align:middle; min-width:100px;">Tipe Restrain</th>
                  <th rowspan="1" colspan="6" class="has-text-centered" style="vertical-align:middle;">Status Fisik</th>
                  <th rowspan="1" colspan="3" class="has-text-centered" style="vertical-align:middle; min-width:300px;">Status Mental</th>
                  <th rowspan="3" class="has-text-centered" style="vertical-align:middle; min-width:100px;">Tindakan</th>
                  <th rowspan="3" class="has-text-centered" style="vertical-align:middle; min-width:50px;">#</th>
                </tr>
                <tr>
                  <th rowspan="1" colspan="3" class="has-text-centered" style="vertical-align:middle; min-width:360px;">Sirkulisasi Distal</th>
                  <th rowspan="2" class="has-text-centered" style="vertical-align:middle; min-width:100px;">Aktivitas Motorik</th>
                  <th rowspan="2" class="has-text-centered" style="vertical-align:middle; min-width:100px;">Pernafasan</th>
                  <th rowspan="2" class="has-text-centered" style="vertical-align:middle; min-width:100px;">Keadaan Umum</th>
                  <th rowspan="2" class="has-text-centered" style="vertical-align:middle; min-width:100px;">Orientasi</th>
                  <th rowspan="2" class="has-text-centered" style="vertical-align:middle; min-width:100px;">Kontak Verbal</th>
                  <th rowspan="2" class="has-text-centered" style="vertical-align:middle; min-width:100px;">Perilaku</th>
                </tr>
                <tr>
                  <th rowspan="1" colspan="1" class="has-text-centered" style="vertical-align:middle; min-width:120px;">Kulit</th>
                  <th rowspan="1" colspan="1" class="has-text-centered" style="vertical-align:middle; min-width:120px;">Pulsasi</th>
                  <th rowspan="1" colspan="1" class="has-text-centered" style="vertical-align:middle; min-width:120px;">Cap. refill</th>
                </tr>
                <tr v-for="(item, index) in input.details" :key="index">
                  <td>
                      <VDatePicker 
                      v-model="item.waktu" 
                      color="green" 
                      mode="datetime" 
                      is24hr
                    >
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VControl>
                            <!-- Use the inputValue and inputEvents to display both date and time -->
                            <VInput 
                              class="input form-timepicker" 
                              :value="inputValue" 
                              v-on="inputEvents" 
                            />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </td>
                  <td>
                    <VField>
                      <VControl class="prime-auto">
                          <AutoComplete v-model="input.perawatJaga" :suggestions="d_Pegawai"
                              @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                              class="mt-2" />
                      </VControl>
                    </VField>
                  </td>
                  <td>
                    <VField>
                      <VControl>
                        <VTextarea v-model="item.tipeRestrain" rows="2" placeholder="" />
                      </VControl>
                    </VField>
                  </td>
                  <td>
                    <VField>
                      <VControl>
                        <VTextarea v-model="item.kulit" rows="2" placeholder="" />
                      </VControl>
                    </VField>
                  </td>
                  <td>
                    <VField>
                      <VControl>
                        <VTextarea v-model="item.pulsasi" rows="2" placeholder="" />
                      </VControl>
                    </VField>
                  </td>
                  <td>
                    <VField>
                      <VControl>
                        <VTextarea v-model="item.caprefill" rows="2" placeholder="" />
                      </VControl>
                    </VField>
                  </td>
                  <td>
                    <VField>
                      <VControl>
                        <VTextarea v-model="item.aktivitasMotorik" rows="2" placeholder="" />
                      </VControl>
                    </VField>
                  </td>
                  <td>
                    <VField>
                      <VControl>
                        <VTextarea v-model="item.pernapasan" rows="2" placeholder="" />
                      </VControl>
                    </VField>
                  </td>
                  <td>
                    <VField>
                      <VControl>
                        <VTextarea v-model="item.keadaanUmum" rows="2" placeholder="" />
                      </VControl>
                    </VField>
                  </td>
                  <td>
                    <VField>
                      <VControl>
                        <VTextarea v-model="item.orientasi" rows="2" placeholder="" />
                      </VControl>
                    </VField>
                  </td>
                  <td>
                    <VField>
                      <VControl>
                        <VTextarea v-model="item.kontakVerbal" rows="2" placeholder="" />
                      </VControl>
                    </VField>
                  </td>
                  <td>
                    <VField>
                      <VControl>
                        <VTextarea v-model="item.perilaku" rows="2" placeholder="" />
                      </VControl>
                    </VField>
                  </td>
                  <td>
                    <VField>
                      <VControl>
                        <VTextarea v-model="item.tindakan" rows="2" placeholder="" />
                      </VControl>
                    </VField>
                  </td>
                  <td style="vertical-align: inherit;">
                    <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem(index)" color="info"
                      v-tooltip.bubble="'Tambah '">
                    </VIconButton>
                    <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                      @click="removeItem(index)" color="danger">
                    </VIconButton>
                  </td>
                </tr>
              </table>
            </div>
          </VCard>
        </div>

        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-3">
                <VCard>
                  <b><span>Tipe restrain :</span></b> <br>
                  <span>1 = 4 points restrain (lengan dan kaki)</span> <br>
                  <span>2 = Restrain lainnya :</span> <br>
                  <VControl class="pt-2">
                      <VInput type="text" class="input" v-model="input.restrainLainnya" />
                  </VControl> <br> 
                  <span>Kunci :</span> <br>
                  <span>N = Normal (lihat data normal")</span> <br>
                  <span>AN = Abnormal (beri catatan singkat)</span> <br>
                  <span>NC = Not Checked (beri catatan singkat)</span> <br>
                </VCard>
              </div>
            <div class="column is-4">
              <VCard>
                <b><span>*daftar data normal, meliputi :</span></b> <br>
                <b><span>Kulit</span></b> <span> : hangat, tidak ada lesi; Pulpasi; teratur dan kuat</span> <br>
                <b><span>Cap. refill</span></b> <span> : kurang dari 4 detik, tidak bengkak;</span> <br>
                <b><span>Pernapasan</span></b> <span> : tidak ada obstruksi</span> <br>
                <b><span>Aktivitas Motorik</span></b> <span> : mampu menggenggam, membuka kepalan, menyentuh jempol dan kelingking, dan ekstensi-fleksi kaki;</span> <br>
                <b><span>Orientasi</span></b> <span> : kepada orang, tempat, dan waktu;</span> <br>
                <b><span>Kontak Verbal</span></b> <span> : jelas dan sesuai;</span> <br>
                <b><span>Perilaku</span></b> <span> : sesuai dengan efek</span> <br>
              </VCard>
            </div>
            <div class="column is-5">
              <VCard>
                <div class="column pt-0">
                  <h1 style="font-weight: bold;" class="has-text-centered">Perawat Penanggung Jawab Shift,</h1>
                  <VField class="pt-3 has-text-centered">
                    <TandaTangan :elemenID="'ttdPerawatShift'" :width="'150'" :height="'150'" class="dek" />
                  </VField>
                  <VField class="pt-3">
                    <VControl class="prime-auto">
                      <AutoComplete v-model="input.perawatShift" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                        :field="'label'" placeholder="Cari Perawat..." />
                    </VControl>
                  </VField>
                </div>

                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                <div class="column pt-0">
                  <VField label="Catatan">
                      <VTextarea rows="3" v-model="input.catatan"></VTextarea>
                  </VField>
                </div>
              </VCard>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { useWindowScroll } from '@vueuse/core'
  import { useApi } from '/@src/composable/useApi'
  import { h, reactive, ref, computed, defineComponent, watch, onMounted, nextTick } from 'vue'
  import { useRoute, useRouter } from 'vue-router'
  import { useHead } from '@vueuse/head'
  import * as H from '/@src/utils/appHelper'
  import ButtonEmr from '../page-emr-plugins/button-emr.vue'
  import AutoComplete from 'primevue/autocomplete';
  // import Fieldset from 'primevue/fieldset';
  import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
  import { useViewWrapper } from '/@src/stores/viewWrapper'
  import { useThemeColors } from '/@src/composable/useThemeColors'
  import { useUserSession } from '/@src/stores/userSession'
  import { FilterMatchMode } from 'primevue/api';
  import InputText from 'primevue/inputtext';
  import Column from 'primevue/column'
  import DataTable from 'primevue/datatable'
  
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
      COLLECTION: 'LembarObservasiPasienDenganRestrain',
    }
  )
  const { y } = useWindowScroll()
  const isStuck = computed(() => { return y.value > 30 })
  const isLoading: any = ref(false)
  const d_Ruangan: any = ref([])
  const d_Pegawai: any = ref([])
  const d_Dokter: any = ref([])
  const filtersTemplate = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS } });
  const listTemplate: any = ref([])
  const listTemplateFix: any = ref([])
  const showModalTemplate: any = ref(false)
  const showModalTemplateFix: any = ref(false)
  const idTemplate: any = ref('');
  const checkTemplate: any = ref(false)
  const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
  })
  const COLLECTION: any = ref(props.COLLECTION) //table mongodb
  const NOREC_EMRPASIEN: any = ref('')
  const dataTTD: any = ref([]);
  const input: any = ref({
    tglDibuat : new Date,
    details: [{
      no: 1,
      waktu: new Date
    }]
  })
  const setView = () => {
    useHead({
      title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
  }
  const loadRiwayat = async () => {
  try {
    const response = await useApi().get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    );

    if (response.length) {
      input.value = response[0]; // Set to input
      if (NOREC_EMRPASIEN.value === '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk;
      }
      dataTTD.value = response[0];
      await nextTick(() => {
        H.tandaTangan().set("ttdPerawatShift", dataTTD.value.ttdPerawatShift);
      });
    } else {
      setAutoFill();
    }
  } catch (error) {
    console.error("Error loading data:", error);
  }
};

  
  const setAutoFill = async () => {
    input.value.tanggalMasukRS = props.registrasi.tglregistrasi;
    input.value.ruangan = { value: props.registrasi.objectruanganfk, label: props.registrasi.namaruangan }
  };

  const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
  
    let object: any = {}
  
    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    object['ttdPerawatShift'] = H.tandaTangan().get("ttdPerawatShift");
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
      }).catch((e: any) => {
        isLoading.value = false
      })
  }
  
  const simpanTemplate = () => {
  if (!input.value.namatemplate) {
    H.alert('warning', "Nama Template wajib diisi")
    console.log()
    return;
  }
  let ID = idTemplate.value ? idTemplate.value : ''
  let object: any = {}

  object = input.value
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
    input.value.namatemplate = null
    delete object.namatemplate;
    delete object['_id'];
    delete object.pasien;
    delete object.registrasi;
  }).catch((e: any) => {
    isLoading.value = false
  })
}
const pilihTemplate = async (index: any) => {
  isLoading.value = true
  useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
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
  const skipKeys = ['id', '_id', 'namatemplate','tanggalMasukRS','ruangan','diagnosa','noBed']; // Keys to be skipped

  for (const key in response) {
    if (!skipKeys.includes(key)) {
      input.value[key] = response[key]; // Only update allowed keys
    }
  }
  showModalTemplateFix.value = false
  H.alert('info', 'Template berhasil ditambahkan')
}

const addRiwayat = (response: any) => {
  input.value = response //set ke inputan
  delete input.value.namatemplate;
  delete input.value['_id'];
  showModalTemplate.value = false
  showModalTemplateFix.value = false
}

const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi().get(`/emr/get-emr-template?collection=${COLLECTION.value}&isAll=true`).then((responselast: any) => {
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
const editTemplate = async (dt: any) => {
  if (!dt) return;
  H.alert('info', 'Silahkan ubah data dan Simpan Template Kembali');
  input.value = dt;
  idTemplate.value = dt.id;
  showModalTemplateFix.value = false;
  input.value.namatemplate = dt.namatemplate;
  checkTemplate.value = true
}
  const fetchRuangan = async (filter: any) => {
    const response = await useApi().get(
      `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
    d_Ruangan.value = response
  }
  
  const fetchPegawai = async (filter: any) => {
  
    await useApi().get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
      d_Pegawai.value = response
    })
  }
  
  const fetchDokter = async (filter: any) => {
      await useApi().get(
          `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
      ).then((response) => {
          d_Dokter.value = response
      })
  }
  
  const addNewItem = (index: any) => {
  input.value.details.splice(index, 0, {
    no: index + 1,
  });

  // Update the "no" for all subsequent items
  for (let i = index + 1; i < input.value.details.length; i++) {
    input.value.details[i].no = i + 1;
  }
};
  const removeItem = (index: any) => {
    input.value.details.splice(index, 1)
  }
  
  const kembaliKeun = () => {
    window.history.back()
  }
  setView()
  setAutoFill()
  loadRiwayat()
  </script>
  
  
  <style lang="scss">
  </style>
  