<style lang="scss">
h1 {
    font-weight: bold !important;
}

.is-4 {
    padding-bottom: 0.1vh !important;
}
</style>
<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>
                                {{ props.FORM_NAME }}
                                <span v-if="input.nosurat" style="color: darkslategray">
                                    <br>{{ input.nosurat }}
                                </span>
                            </h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate"
                                @kembaliKeun="kembaliKeun"></ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <div class="column">
                    <div class="columns is-multiline">
                        <div class="column is-12 buttons mb-0 mt-0 pb-1" style="margin:10px;vertical-align:middle">
                            <VButton type="button" rounded outlined color="primary" raised icon="feather:folder"
                                :loading="isLoading" @click="pilihTemplateFix(index)"> Pilih Template
                            </VButton>
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

                        <div class="column is-12 p-0">
                            <hr class="m-0">
                        </div>

                        <div class="column is-12 has-text-weight-bold mt-3 pb-0">
                            Yang bertanda tangan dibawah ini :
                        </div>

                        <div class="column is-4">
                            <span>Nama</span>
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.namaDokter" :suggestions="d_Dokter"
                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                            </VControl>
                        </div>

                        <div class="column is-4">
                            <span>Jabatan</span>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.jabatan" />
                            </VControl>
                        </div>

                        <div class="column is-12 has-text-weight-bold mt-3 pb-0">
                            Dengan ini menerangkan bahwa :
                        </div>

                        <div class="column is-4">
                            <span>Nama</span>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.namaPasien" />
                            </VControl>
                        </div>

                        <div class="column is-4">
                            <span>Umur</span>
                            <VControl>
                                <VInput type="text" class="input pt-0" v-model="input.umur" />
                            </VControl>
                        </div>

                        <div class="column is-4">
                            <span>Jenis Kelamin</span>
                            <Multiselect v-model="input.jenisKelamin" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_JenisKelamin" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>

                        <div class="column is-4">
                            <span>Pekerjaan</span>
                            <VControl>
                                <VInput type="text" class="input pt-0" v-model="input.pekerjaan" />
                            </VControl>
                        </div>

                        <div class="column is-4">
                            <span>Alamat</span>
                            <VControl>
                                <VTextarea v-model="input.alamat" rows="2" />
                            </VControl>
                        </div>

                        <div class="column is-12">
                            <span>Menerangkan dengan sesungguhnya bahwa yang tersebut di atas benar-benar</span>
                            <Multiselect v-model="input.menyatakan" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_Menyatakan" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                            <span>
                                Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat
                                dipergunakan seperlunya.
                            </span>
                        </div>

                        <div class="column is-12 pt-0 pb-0">
                            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                        </div>

                        <div class="column is-4 pt-6" style="margin-right: auto;margin-top: auto;">
                            <div style="text-align: center;">
                                <span style="font-weight: bold;">Tanda tangan yang diperiksa</span>
                                <br>
                                <TandaTangan :elemenID="'TTD_Pasien'" :width="'150'" :height="'150'" class="dek mt-2" />
                                <VControl>
                                    <VInput type="text" class="input mt-2" v-model="input.namaPasien" />
                                </VControl>
                            </div>
                        </div>

                        <div class="column is-4" style="margin-left: auto;">
                            <VField label="Bengkulu">
                                <VDatePicker v-model="input.tanggal" mode="datetime" trim-weeks :max-date="new Date()"
                                    is24hr>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </template>
                                </VDatePicker>
                            </VField>
                            <div style="text-align: center;">
                                <h1>Dokter Pemeriksa</h1>
                                <TandaTangan :elemenID="'TTD_Dokter'" :width="'150'" :height="'150'" class="dek mt-2" />
                                <VControl class="prime-auto mt-2">
                                    <AutoComplete v-model="input.dokter" :suggestions="d_Dokter"
                                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                        :field="'label'" />
                                </VControl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- form baru -->
            </div>
        </div>
    </div>

    <!-- <VModal :open="showModalTemplate" title="Riwayat" :noclose="true" size="large" actions="right"
    @close="showModalTemplate = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12 pt-0 pb-0">
          <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
          <div style="overflow-y:auto;" class="mt-1">
            <table class="tg table-tg" v-if="listTemplate.length > 0">
              <thead>
                <tr>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="15%">Tanggal Input</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="15%">Tanggal Registrasi</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="15%">No Registrasi</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="15%">No EMR</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="20%">Dokter</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="15%">Section</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="5%">#</td>
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
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="addRiwayat(resep)" color="info"
                      v-tooltip-prime.top="'Pilih'">
                    </VIconButton>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </form>
    </template>
</VModal> -->

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
            }" v-model:filters="filtersTemplate" :value="listTemplateFix" :metaKeySelection="false" :rows="10"
                paginator tableStyle="min-width: 50rem" dataKey="no" :totalRecords="listTemplateFix.length"
                :globalFilterFields="['namatemplate', 'registrasi.namaruangan']" responsiveLayout="stack"
                breakpoint="960px">
                <template #header>
                    <div class="columns is-multiline">
                        <div class="column is-8">
                            <VField>
                                <InputText v-model="filtersTemplate['global'].value"
                                    placeholder="Search Nama Template" />
                            </VField>
                        </div>
                        <div class="column is-4 is-flex" style="justify-content: center;">
                            <VControl>
                                <VSwitchBlock v-model="isAlltemplate" color="success" label="Semua Template" />
                            </VControl>
                        </div>
                    </div>
                </template>
                <template #empty> No templates found. </template>
                <template #loading>
                    <img src="/images/other/loadingspin.gif" alt="Loading..." width="100" />
                    <p style="color:white">Loading data, please wait...</p>
                </template>
                <Column headerStyle="width: 8rem">
                    <template #body="slotProps">
                        <VButtons>
                            <VIconButton color="danger" light raised circle icon="lucide:x"
                                @click="deleteTemplate(slotProps.data.id)" v-if="!isAlltemplate"
                                v-tooltip-prime.top="'Hapus'" />
                            <VIconButton type="button" raised circle icon="fas fa-plus"
                                @click="addTemplate(slotProps.data)" color="info" v-tooltip-prime.top="'Pilih'">
                            </VIconButton>
                            <VIconButton type="button" raised circle icon="fas fa-pencil-alt"
                                @click="editTemplate(slotProps.data)" color="info" v-tooltip-prime.top="'Edit'"
                                v-if="!isAlltemplate">
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
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import InputText from 'primevue/inputtext';
import { FilterMatchMode } from 'primevue/api';

useHead({ title: `${props.FORM_NAME} - ` + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const idTemplate: any = ref('');
const route = useRoute()
const router = useRouter()
const { y } = useWindowScroll()
const user = useUserSession().getUser().pegawai;
const isStuck = computed(() => { return y.value > 30 })
const isLoading = ref(false)
const checkTemplate: any = ref(false)
const isAlltemplate: any = ref(false);
const pasien: any = ref({})
const input: any = ref({})
// const dataTTD: any = ref([])
// const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
// const listTemplate: any = ref([])
// const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const filtersTemplate = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS } });

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

const setAutoFill = async () => {
    let d = input.value
    let ps = props.pasien

    // Pasien
    d.namaPasien = ps.namapasien
    d.umur = calculateAge(ps.tgllahir)
    d.jenisKelamin = ps.jeniskelamin
    d.pekerjaan = ps.pekerjaan
    d.alamat = ps.alamatlengkap

    d.tanggal = new Date()
    d.namaDokter = { label: props.registrasi.dokter, value: props.registrasi.iddokter }
    d.dokter = { label: props.registrasi.dokter, value: props.registrasi.iddokter }
}

const loadRiwayat = async () => {
    isLoading.value = true
    await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then(async (response: any) => {
        if (response.length) {
            let res = response[0]
            input.value = res //set ke inputan
            if (NOREC_EMRPASIEN.value == '') {
                NOREC_EMRPASIEN.value = res.emrpasienfk
            }
            H.tandaTangan().set("TTD_Dokter", res.TTD_Dokter)
            H.tandaTangan().set("TTD_Pasien", res.TTD_Pasien)
        } else {
            await setAutoFill()
        }
    }).catch((e: any) => {
        console.log(e)
        H.alert('error', 'Terjadi kesalahan saat mengambil data')
    }).finally(() => {
        isLoading.value = false
    });
}

const simpan = async () => {
    if (checkTemplate.value == true) {
        H.alert('warning', 'Simpan template ya, bukan simpan data :)')
        return;
    }

    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    object['TTD_Dokter'] = H.tandaTangan().get("TTD_Dokter");
    object['TTD_Pasien'] = H.tandaTangan().get("TTD_Pasien");
    object.pasien = H.setObjectPasien(props.pasien)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    delete object.namatemplate
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
    useApi().post(`/emr/simpan-emr-surket`, json).then((response: any) => {
        loadRiwayat();
    }).catch((e: any) => {
        console.log(e)
    }).finally(() => {
        isLoading.value = false
    });
}

// const fetchPegawai = async (filter: any) => {
//   await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => { d_Pegawai.value = response })
// }
const fetchDokter = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`).then((response) => { d_Dokter.value = response })
}

function calculateAge(birthdate) {
    const today = new Date();
    const birthDate = new Date(birthdate);

    let years = today.getFullYear() - birthDate.getFullYear();
    let months = today.getMonth() - birthDate.getMonth();
    let days = today.getDate() - birthDate.getDate();

    // Adjust days and months if necessary
    if (days < 0) {
        months--;
        const previousMonth = new Date(today.getFullYear(), today.getMonth(), 0);
        days += previousMonth.getDate();
    }

    if (months < 0) {
        years--;
        months += 12;
    }

    return `${years} Tahun ${months} Bulan ${days} Hari`;
}

// Batas Normal Function
var bn_rk = 1;
var bn_rkl = 1;
var bn_pf = 1;
var bn_ptht = 1;
var bn_pm = 1;
var bn_pgm = 1;
var bn_pp = 1;
var bn_pkk = 1;
var bn_pkb = 1;
var bn_pl = 1;
var bn_pc = 1;
var bn_pr = 1;

function BN_RK() {
    let d = input.value
    if (bn_rk == 1) {
        d.keluhanUtama = 'TIDAK ADA'
        d.riwayatPenyakit = 'TIDAK ADA'
        d.riwayatPenyakitDahulu = 'TIDAK ADA'
        bn_rk = bn_rk - 1;
        return bn_rk;
    } else {
        d.keluhanUtama = undefined;
        d.riwayatPenyakit = undefined;
        d.riwayatPenyakitDahulu = undefined;
        bn_rk = bn_rk + 1
        return bn_rk;
    }
}
function BN_RKL() {
    let d = input.value
    if (bn_rkl == 1) {
        d.keluargaAlergi = 'TIDAK ADA';
        d.keluargaAsthma = 'TIDAK ADA';
        d.keluargaKanker = 'TIDAK ADA';
        d.keluargaDiabetes = 'TIDAK ADA';
        d.keluargaJantung = 'TIDAK ADA';
        d.keluargaHipertensi = 'TIDAK ADA';
        d.keluargaGinjal = 'TIDAK ADA';
        bn_rkl = bn_rkl - 1;
        return bn_rkl;
    } else {
        d.keluargaAlergi = undefined;
        d.keluargaAsthma = undefined;
        d.keluargaKanker = undefined;
        d.keluargaDiabetes = undefined;
        d.keluargaJantung = undefined;
        d.keluargaHipertensi = undefined;
        d.keluargaGinjal = undefined;
        bn_rkl = bn_rkl + 1
        return bn_rkl;
    }
}
function BN_PF() {
    let d = input.value
    if (bn_pf == 1) {
        d.kepalaLeher = 'DALAM BATAS NORMAL';
        d.dada = 'DALAM BATAS NORMAL';
        d.jantung = 'DALAM BATAS NORMAL';
        d.perut = 'DALAM BATAS NORMAL';
        d.hati = 'DALAM BATAS NORMAL';
        d.limpa = 'DALAM BATAS NORMAL';
        d.ginjal = 'DALAM BATAS NORMAL';
        d.lainLain = 'DALAM BATAS NORMAL';
        d.tulangPunggung = 'DALAM BATAS NORMAL';
        d.anggotaGerak = 'DALAM BATAS NORMAL';
        d.refleks = 'DALAM BATAS NORMAL';
        d.sensibilitas = 'DALAM BATAS NORMAL';
        d.alatKelamin = 'DALAM BATAS NORMAL';
        d.anus = 'DALAM BATAS NORMAL';
        bn_pf = bn_pf - 1;
        return bn_pf;
    } else {
        d.kepalaLeher = undefined;
        d.dada = undefined;
        d.jantung = undefined;
        d.perut = undefined;
        d.hati = undefined;
        d.limpa = undefined;
        d.ginjal = undefined;
        d.lainLain = undefined;
        d.tulangPunggung = undefined;
        d.anggotaGerak = undefined;
        d.refleks = undefined;
        d.sensibilitas = undefined;
        d.alatKelamin = undefined;
        d.anus = undefined;
        bn_pf = bn_pf + 1
        return bn_pf;
    }
}
function BN_PM() {
    let d = input.value
    if (bn_pm == 1) {
        d.visusKanan = 'TIDAK DILAKUKAN';
        d.visusKiri = 'TIDAK DILAKUKAN';
        d.koreksi = 'TIDAK DILAKUKAN';
        d.anteriorSegment = 'TIDAK DILAKUKAN';
        d.funduscopy = 'TIDAK DILAKUKAN';
        d.butaWarna = 'TIDAK DILAKUKAN';
        bn_pm = bn_pm - 1;
        return bn_pm;
    } else {
        d.visusKanan = undefined;
        d.visusKiri = undefined;
        d.koreksi = undefined;
        d.anteriorSegment = undefined;
        d.funduscopy = undefined;
        d.butaWarna = undefined;
        bn_pm = bn_pm + 1
        return bn_pm;
    }
}
function BN_PTHT() {
    let d = input.value
    if (bn_ptht == 1) {
        d.telinga = 'TIDAK DILAKUKAN';
        d.hidung = 'TIDAK DILAKUKAN';
        d.tenggorokan = 'TIDAK DILAKUKAN';
        d.lainLain_tht = 'TIDAK DILAKUKAN';
        bn_ptht = bn_ptht - 1;
        return bn_ptht;
    } else {
        d.telinga = undefined;
        d.hidung = undefined;
        d.tenggorokan = undefined;
        d.lainLain_tht = undefined;
        bn_ptht = bn_ptht + 1
        return bn_ptht;
    }
}
function BN_PGM() {
    let d = input.value
    if (bn_pgm == 1) {
        d.gigi = 'TIDAK DILAKUKAN';
        d.gusi = 'TIDAK DILAKUKAN';
        d.lidah = 'TIDAK DILAKUKAN';
        d.gigiBerlubang = 'TIDAK DILAKUKAN';
        d.lainLain_gigiMulut = 'TIDAK DILAKUKAN';
        bn_pgm = bn_pgm - 1;
        return bn_pgm;
    } else {
        d.gigi = undefined;
        d.gusi = undefined;
        d.lidah = undefined;
        d.gigiBerlubang = undefined;
        d.lainLain_gigiMulut = undefined;
        bn_pgm = bn_pgm + 1
        return bn_pgm;
    }
}
function BN_PP() {
    let d = input.value
    if (bn_pp == 1) {
        d.spirometri = 'TIDAK DILAKUKAN';
        d.hasil_paru = 'TIDAK DILAKUKAN';
        bn_pp = bn_pp - 1;
        return bn_pp;
    } else {
        d.spirometri = undefined;
        d.hasil_paru = undefined;
        bn_pp = bn_pp + 1
        return bn_pp;
    }
}
function BN_PKK() {
    let d = input.value
    if (bn_pkk == 1) {
        d.hasil_kulitKelamin = 'TIDAK DILAKUKAN';
        bn_pkk = bn_pkk - 1;
        return bn_pkk;
    } else {
        d.hasil_kulitKelamin = undefined;
        bn_pkk = bn_pkk + 1
        return bn_pkk;
    }
}
function BN_PKB() {
    let d = input.value
    if (bn_pkb == 1) {
        d.hasil_bedah = 'TIDAK DILAKUKAN';
        bn_pkb = bn_pkb - 1;
        return bn_pkb;
    } else {
        d.hasil_bedah = undefined;
        bn_pkb = bn_pkb + 1
        return bn_pkb;
    }
}
function BN_PL() {
    let d = input.value
    if (bn_pl == 1) {
        d.nomorLaboratorium = 'TIDAK DILAKUKAN';
        d.hasilLaboratorium = 'TIDAK DILAKUKAN';
        bn_pl = bn_pl - 1;
        return bn_pl;
    } else {
        d.nomorLaboratorium = undefined;
        d.hasilLaboratorium = undefined;
        bn_pl = bn_pl + 1
        return bn_pl;
    }
}
function BN_PC() {
    let d = input.value
    if (bn_pc == 1) {
        d.ekg = 'TIDAK DILAKUKAN';
        d.hasilEkg = 'TIDAK DILAKUKAN';
        d.echoCardiography = 'TIDAK DILAKUKAN';
        d.hasilEcho = 'TIDAK DILAKUKAN';
        d.treadmillTest = 'TIDAK DILAKUKAN';
        d.hasilTreadmill = 'TIDAK DILAKUKAN';
        bn_pc = bn_pc - 1;
        return bn_pc;
    } else {
        d.ekg = undefined;
        d.hasilEkg = undefined;
        d.echoCardiography = undefined;
        d.hasilEcho = undefined;
        d.treadmillTest = undefined;
        d.hasilTreadmill = undefined;
        bn_pc = bn_pc + 1
        return bn_pc;
    }
}
function BN_PR() {
    let d = input.value
    if (bn_pr == 1) {
        d.noRontgentThorak = 'TIDAK DILAKUKAN';
        d.kesanRontgentThorak = 'TIDAK DILAKUKAN';
        d.noUsg = 'TIDAK DILAKUKAN';
        d.kesanUsg = 'TIDAK DILAKUKAN';
        bn_pr = bn_pr - 1;
        return bn_pr;
    } else {
        d.noRontgentThorak = undefined;
        d.kesanRontgentThorak = undefined;
        d.noUsg = undefined;
        d.kesanUsg = undefined;
        bn_pr = bn_pr + 1
        return bn_pr;
    }
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

watch(isAlltemplate, (newValue) => {
    pilihTemplateFix()
})

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
        checkTemplate.value = false
        isAlltemplate.value = false
        input.value.namatemplate = null
        input.value.id = ''
    }).catch((e: any) => {
        isLoading.value = false
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
    isAlltemplate.value = false
    input.value.namatemplate = dt.namatemplate;
    checkTemplate.value = true
}
const addTemplate = (response: any) => {
    // Set TTD
    H.tandaTangan().set("TTD_Dokter", response.TTD_Dokter)

    input.value = response
    delete input.value['id']
    input.value.namatemplate = null
    showModalTemplateFix.value = false
    isAlltemplate.value = false
    H.alert('info', 'Template berhasil ditambahkan')
}

const pilihTemplateFix = async (index: any) => {
    let allTemplate = isAlltemplate.value ? `&isAll=true` : ''
    isLoading.value = true
    useApi().get(`/emr/get-emr-template?collection=${COLLECTION.value}${allTemplate}`).then((responselast: any) => {
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

// ===== ARRAY =====
const d_JenisKelamin: any = ref([
    { value: 'Laki-laki', label: 'Laki-laki' },
    { value: 'Perempuan', label: 'Perempuan' }
])
const d_Menyatakan: any = ref([
    { value: 'Tidak Bertato, Tidak Memiliki Bekas Tato, dan Tidak Bertindik', label: 'Tidak Bertato, Tidak Memiliki Bekas Tato, dan Tidak Bertindik' },
    { value: 'Tidak Bertato, Bertindik', label: 'Tidak Bertato, Bertindik' },
    { value: 'Bertato, Tidak Bertindik', label: 'Bertato, Tidak Bertindik' },
    { value: 'Memiliki Bekas Tato', label: 'Memiliki Bekas Tato' }
])
// const d_tidakYa: any = ref([
//     { value: 1, label: 'Tidak' },
//     { value: 2, label: 'Ya' }
// ])
// const d_yaTidak: any = ref([
//     { value: 1, label: 'Ya' },
//     { value: 2, label: 'Tidak' }
// ])
// const d_tidakAda_ada: any = ref([
//     { value: 1, label: 'Tidak' },
//     { value: 2, label: 'Ya' }
// ])
</script>