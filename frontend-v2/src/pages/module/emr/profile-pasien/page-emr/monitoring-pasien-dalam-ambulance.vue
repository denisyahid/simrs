<style lang="scss">
// .table {
//     border-collapse: collapse;
//     width: 100%;
// }

// .table td { border: 1px solid black !important }

// .table th {
//     text-align: center !important;
//     border: 1px solid black !important;
// }

.td-po {
    vertical-align: middle;
}

.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 150%;
}

h1 {
    font-weight: bold !important
}
</style>
<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Monitoring Pasien Dalam Ambulance</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate"
                                @kembaliKeun="kembaliKeun" isHideST isHideCetak></ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <div class="column is-12">
                    <div class="column is-12">
                        <div class="column is-3">
                            <VField label="Tanggal :">
                                <VDatePicker v-model="input.tanggal" mode="datetime" trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                    </div>
                    <div class="column is-12">
                        <div class="column is-12">
                            <VField label="Jenis Ambulan :">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.jenisAmbulan" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                    <div class="column is-12">
                        <div class="column is-4">
                            <VField label="Petugas :">
                                <VControl class="prime-auto">
                                    <AutoComplete v-model="input.petugas" :suggestions="d_Pegawai"
                                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        class="mt-2" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>

                <div class="column is-12">
                    <div class="column is-12">
                      <div class="columns" style="overflow: auto;" >
                        <table class="tg" border="1">
                          <thead>
                            <tr>
                                <th style="text-align:center; white-space:pre-line; width:100px;">
                                    #
                                </th>
                                <th style="text-align:center; white-space:pre-line; width:150px;">
                                Jam
                                </th>
                                <th style="text-align:center; white-space:pre-line; width:150px;">
                                TD <br />
                                (mmHg)
                                </th>
                                <th style="text-align:center; white-space:pre-line; width:150px;">
                                Nadi <br />
                                (x/mnt)
                                </th>
                                <th style="text-align:center; white-space:pre-line; width:150px;">
                                Ritme
                                </th>
                                <th style="text-align:center; white-space:pre-line; width:150px;">
                                Resep <br />
                                (x/mnt)
                                </th>
                                <th style="text-align:center; white-space:pre-line; width:150px;">
                                Suhu <br />
                                (°C)
                                </th>
                                <th style="text-align:center; white-space:pre-line; width:150px;">
                                Sat O<sub>2</sub>
                                </th>
                                <th style="text-align:center; white-space:pre-line; width:150px;">
                                GCS <br />
                                (Ex Vx Mx)
                                </th>
                                <th style="text-align:center; white-space:pre-line; width:150px;">
                                Ukuran Pupil <br />
                                (mm)
                                </th>
                                <th style="text-align:center; white-space:pre-line; width:150px;">
                                Reaksi Pupil
                                </th>
                            </tr>
                          </thead>
                          <tbody v-for="(input, index) in input.details" :key="index">
                            <tr>
                            <td class="td-rpo" style="vertical-align: middle;">
                                <VButtons style="justify-content:space-around">
                                    <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()"
                                    color="info" v-tooltip.bubble="'Tambah '">
                                    </VIconButton>
                                    <VIconButton class="mt-1" v-if="index > 11" type="button" raised circle icon="feather:trash"
                                    @click="removeItem(index)" color="danger">
                                    </VIconButton>
                                </VButtons>
                                </td>
                              <td class="td-po">
                                <div class="pb-0">
                                  <VField>
                                    <VControl>
                                        <VDatePicker v-model="input.jam" mode="time" is24hr>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VControl icon="feather:clock" fullwidth>
                                                    <VInput :value="inputValue" v-on="inputEvents" />
                                                </VControl>
                                            </template>
                                        </VDatePicker>
                                    </VControl>
                                  </VField>
                                </div>
                              </td>
                              <td class="td-po">
                                <div class="pb-0">
                                  <VField>
                                    <VControl>
                                      <VTextarea rows="2" v-model="input.tekananDarah"
                                        :disabled="paramRiwayat">
                                      </VTextarea>
                                    </VControl>
                                  </VField>
                                </div>
                              </td>
                              <td class="td-po">
                                <div class="pb-0">
                                  <VField>
                                    <VControl>
                                      <VTextarea rows="2" v-model="input.nadi" :disabled="paramRiwayat">
                                      </VTextarea>
                                    </VControl>
                                  </VField>
                                </div>
                              </td>
                              <td class="td-po">
                                <div class="pb-0">
                                  <VField>
                                    <!-- <VControl>
                                      <VTextarea rows="2" v-model="input.ritme" :disabled="paramRiwayat">
                                      </VTextarea>
                                    </VControl> -->
                                    <div class="columns m-0">
                                      <div class="column is-6 p-1">
                                        <VControl raw subcontrol>
                                          <VCheckbox class="p-0" color="primary" square true-value="R" label="R"
                                            v-model="input.ritme1" />
                                        </VControl>
                                      </div>
                                      <div class="column is-6 p-1">
                                        <VControl raw subcontrol>
                                          <VCheckbox class="p-0" color="primary" square true-value="IRR" label="IRR"
                                            v-model="input.ritme2" />
                                        </VControl>
                                      </div>
                                    </div>
                                  </VField>
                                </div>
                              </td>
                              <td class="td-po">
                                <div class="pb-0">
                                  <VField>
                                    <VControl>
                                      <VTextarea rows="2" v-model="input.resep" :disabled="paramRiwayat">
                                      </VTextarea>
                                    </VControl>
                                  </VField>
                                </div>
                              </td>
                              <td class="td-po">
                                <div class="pb-0">
                                  <VField>
                                    <VControl>
                                      <VTextarea rows="2" v-model="input.suhu" :disabled="paramRiwayat">
                                      </VTextarea>
                                    </VControl>
                                  </VField>
                                </div>
                              </td>
                              <td class="td-po">
                                <div class="pb-0">
                                  <VField>
                                    <VControl>
                                      <VTextarea rows="2" v-model="input.saturasi" :disabled="paramRiwayat">
                                      </VTextarea>
                                    </VControl>
                                  </VField>
                                </div>
                              </td>
                              <td class="td-po">
                                <div class="pb-0">
                                  <VField>
                                    <VControl>
                                      <VTextarea rows="2" v-model="input.gcs" :disabled="paramRiwayat">
                                      </VTextarea>
                                    </VControl>
                                  </VField>
                                </div>
                              </td>
                              <td class="td-po">
                                <div class="pb-0">
                                  <VField>
                                    <VControl>
                                      <VTextarea rows="2" v-model="input.ukuranPupil" :disabled="paramRiwayat">
                                      </VTextarea>
                                    </VControl>
                                  </VField>
                                </div>
                              </td>
                              <td class="td-po">
                                <div class="pb-0">
                                  <VField>
                                    <VControl>
                                      <VTextarea rows="2" v-model="input.reaksiPupil" :disabled="paramRiwayat">
                                      </VTextarea>
                                    </VControl>
                                  </VField>
                                </div>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>

                  <div class="column is-12">
                    <VField label="Catatan :">
                        <VTextarea rows="4" v-model="input.catatan"></VTextarea>
                    </VField>
                  </div>

                <!-- form baru -->
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import * as H from '/@src/utils/appHelper'
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
// import DataTable from 'primevue/datatable'
// import Column from 'primevue/column'
// import InputText from 'primevue/inputtext';

useHead({ title: 'Monitoring Pasien Dalam Ambulance - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const COLLECTION: any = ref('MonitoringPasienDalamAmbulance') // table mongodb
const NOREC_EMRPASIEN: any = ref('')
const route = useRoute()
const router = useRouter()
const { y } = useWindowScroll()
const user = useUserSession().getUser().pegawai;
const isStuck = computed(() => { return y.value > 30 })
const isLoading = ref(false)
const pasien: any = ref({})
const input: any = ref({
    details: [
        {
    no: 1,
    jam: new Date(),
    ritme: "- R\n- IRR",
    ukuranPupil: "- Ka ⌀\n- KI ⌀",
    reaksiPupil: "- Ka :\n- Ki :",
  },
        {
    no: 2,
    jam: new Date(),
  },
        {
    no: 3,
    jam: new Date(),
  },
        {
    no: 4,
    jam: new Date(),
  },
        {
    no: 5,
    jam: new Date(),
  },
        {
    no: 6,
    jam: new Date(),
  },
        {
    no: 7,
    jam: new Date(),
  },
        {
    no: 8,
    jam: new Date(),
  },
        {
    no: 9,
    jam: new Date(),
  },
        {
    no: 10,
    jam: new Date(),
  },
        {
    no: 11,
    jam: new Date(),
  },
        {
    no: 12,
    jam: new Date(),
  },
],
})
const dataTTD: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
// const listTemplate: any = ref([])
// const showModalTemplate: any = ref(false)
// const listTemplateFix: any = ref([])
// const showModalTemplateFix: any = ref(false)

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
        FORM_NAME: 'Monitoring Pasien Dalam Ambulance',
        FORM_URL: 'monitoring-pasien-dalam-ambulance',
        COLLECTION: 'MonitoringPasienDalamAmbulance',
    }
)
const loadRiwayat = async () => {
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        dataTTD.value = response[0]
    } else {
    }
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    object.nocm = pasien.value.nocm

    object.pasien = H.setObjectPasien(pasien.value)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    if (object.hasOwnProperty('namatemplate')) {
        delete object.namatemplate
    }
    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'url_form': route.name,
        'name_form': 'Monitoring Pasien Dalam Ambulance',
        'jenis_emr': 'asesmen_medis',
        'data': object
    }

    isLoading.value = true
    useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
        isLoading.value = false
        // NOREC_EMRPASIEN.value = response.norec_emr
        // input.value.id = response.id;
        loadRiwayat();
    }).catch((e: any) => {
        isLoading.value = false
    })
}
const fetchPasien = () => {
    pasien.value = props.pasien
    pasien.value.registrasi = props.registrasi
    NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}
const fetchPegawai = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => { d_Pegawai.value = response })
}
const fetchDokter = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`).then((response) => { d_Dokter.value = response })
}

onBeforeMount(async () => {
    try {
        await loadRiwayat()
        await fetchPasien()
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

const addNewItem = () => {
  const currentTime = new Date()
  input.value.details.push({
    no: input.value.details[input.value.details.length - 1]?.no + 1 || 1, // Increment no or start at 1 if empty
    jam: currentTime,
  });
};


const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
}

</script>