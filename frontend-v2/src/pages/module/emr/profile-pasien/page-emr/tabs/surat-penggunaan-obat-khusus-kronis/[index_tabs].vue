<style lang="scss">
h1 {
    font-weight: bold !important;
}
</style>
<template>
    <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3>Surat Penggunaan Obat Khusus Kronis<br>Halaman ke-{{ route.params.index_tabs }}</h3>
                    </div>
                    <div class="right">
                        <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :ID="input.id"
                            :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate"
                            @kembaliKeun="kembaliKeun"></ButtonEmr>
                    </div>
                </div>
                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-1 mb-1">
                <div style="text-align: center;font-size: large;font-weight: bold;">
                    <VTag :class="isSave ? 'has-background-success' : 'has-background-danger'"
                        style="color:white;width: 100%;font-size: large;">
                        {{ isSave ? 'Form Sudah Tersimpan / Data Sudah Ada' : 'Form Belum Tersimpan' }}
                    </VTag>
                </div>
            </div>

            <!-- form baru -->
            <div class="column">
                <div class="columns is-multiline">
                    <div class="column is-12 buttons mb-0 mt-0 pb-1" style="margin:10px;vertical-align:middle">
                        <VButton type="button" rounded outlined color="primary" raised icon="feather:folder"
                            :isLoading="isLoading" @click="pilihTemplateFix(index)"> Pilih Template
                        </VButton>
                        <VButton type="button" rounded outlined color="info" raised icon="feather:file-text"
                            :loading="isLoading" @click="pilihTemplate(index)"> Pilih Riwayat
                        </VButton>
                    </div>

                    <div class="column is-12 pt-0 pb-0">
                        <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
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

                    <div class="column is-12 pt-0 pb-0">
                        <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                    </div>

                    <div class="column is-12 pb-0 pt-1">
                        <h1>Saya yang bertanda tangan dibawah ini :</h1>
                    </div>
                    <div class="column is-4">
                        <span>Nama</span>
                        <VControl>
                            <VInput type="text" class="input" v-model="input.NamaPetugas" />
                        </VControl>
                    </div>
                    <div class="column is-4">
                        <span>Spesialis</span>
                        <VField>
                            <VControl>
                                <AutoComplete v-model="input.SpesialisPetugas" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="ketik untuk mencari ruangan..." />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <span>Jabatan</span>
                        <VControl>
                            <VInput type="text" class="input" v-model="input.JabatanPetugas" />
                        </VControl>
                    </div>
                    <div class="column is-12 pt-0 pb-0">
                        <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                    </div>
                    <div class="column is-12 pb-0 pt-1">
                        <h1>Menerangkan bahwa penderita :</h1>
                    </div>
                    <div class="column is-3">
                        <span>Nomor RM</span>
                        <VControl>
                            <VInput type="text" class="input" v-model="input.RMPasien" />
                        </VControl>
                    </div>
                    <div class="column is-3">
                        <span>Nama</span>
                        <VControl>
                            <VInput type="text" class="input" v-model="input.NamaPasien" />
                        </VControl>
                    </div>
                    <div class="column is-3">
                        <span>Tanggal Lahir</span>
                        <VDatePicker v-model="input.TanggalLahirPasien" mode="date" trim-weeks>
                            <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                            </template>
                        </VDatePicker>
                    </div>
                    <div class="column is-3">
                        <span>Umur</span>
                        <VControl>
                            <VInput type="text" class="input" v-model="input.UmurPasien" />
                        </VControl>
                    </div>
                    <div class="column is-3 pt-0">
                        <span>Jenis Kelamin</span>
                        <VControl>
                            <VInput type="text" class="input" v-model="input.JenisKelaminPasien" />
                        </VControl>
                    </div>
                    <div class="column is-3 pt-0">
                        <span>Alamat</span>
                        <VField>
                            <VTextarea rows="1" v-model="input.AlamatPasien"></VTextarea>
                        </VField>
                    </div>
                    <div class="column is-3 pt-0">
                        <span>Diagnosa</span>
                        <VField>
                            <VTextarea rows="1" v-model="input.DiagnosaPasien"></VTextarea>
                        </VField>
                    </div>
                    <div class="column is-12 pt-0 pb-0">
                        <span>Memang benar membutuhkan obat : </span>
                        <VField>
                            <VTextarea rows="1" v-model="input.MembutuhkanObat"></VTextarea>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <span>Selama</span>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" v-model="input.DurasiObat" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>Hari</VButton>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <span>Dosis</span>
                        <VField>
                            <VTextarea rows="2" v-model="input.DosisObat" placeholder="... X ..."></VTextarea>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <span>Alasan Pemberian</span>
                        <VField>
                            <VTextarea rows="2" v-model="input.AlasanPemberianObat"></VTextarea>
                        </VField>
                    </div>
                    <div class="column is-12 pt-0">
                        <span>Demikian surat ini kami sampaikan , untuk dapat dipergunakan sebagai mana
                            mestinya.</span>
                    </div>
                    <div class="column is-12 pt-0 pb-0">
                        <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                    </div>
                    <div class="column is-6 p-0"></div>
                    <div class="column is-6 pb-0" style="margin-left: auto;text-align: center;">
                        <span>Garut</span>
                        <div class="is-flex" style="justify-content: center;">
                            <VDatePicker v-model="input.tanggal" mode="datetime" trim-weeks style="width: 50%;">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                    </div>
                    <div class="column is-6 pt-0" style="text-align: center;">
                        <span>Mengetahui<br><b>Kepala Instalasi Farmasi</b></span><br>
                        <TandaTangan :elemenID="'TTDKepalaInstalasiFarmasi'" :width="'150'" :height="'150'"
                            class="dek" />
                        <div class="is-flex" style="justify-content: center;">
                            <VControl class="mt-2" style="width: 50%;">
                                <VInput type="text" class="input" v-model="input.KepalaInstalasiFarmasi" />
                            </VControl>
                        </div>
                    </div>
                    <div class="column is-6 pt-0" style="text-align: center;">
                        <br><span><b>Dokter Yang Merawat</b></span><br>
                        <TandaTangan :elemenID="'TTDDokter'" :width="'150'" :height="'150'" class="dek" />
                        <div class="is-flex" style="justify-content: center;">
                            <VControl class="prime-auto mt-2" style="width: 50%;">
                                <AutoComplete v-model="input.Dokter" :suggestions="d_Dokter"
                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Dokter..." />
                            </VControl>
                        </div>
                    </div>
                </div>
            </div>
            <!-- form baru -->

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
                                    <td class="tg-0lax text-center" width="5%">#</td>
                                    <td class="tg-0lax text-center" width="25%">Tanggal Input</td>
                                    <td class="tg-0lax text-center" width="25%">Tanggal Registrasi</td>
                                    <td class="tg-0lax text-center" width="25%">No Registrasi</td>
                                    <td class="tg-0lax text-center" width="20%">No EMR</td>
                                    <td class="tg-0lax text-center" width="20%">Dokter</td>
                                    <td class="tg-0lax text-center" width="20%">Section</td>
                                </tr>
                            </thead>
                            <tbody v-for="resep in listTemplate">
                                <tr>
                                    <td style="width:5%;text-align:center">
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="addTemplate(resep)" color="info" v-tooltip-prime.top="'Pilih'">
                                        </VIconButton>
                                    </td>
                                    <td style="width:25%;text-align:center">
                                        <span class="mb-2">{{ resep.created_at }}</span><br>
                                    </td>
                                    <td style="width:25%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                                    </td>
                                    <td style="width:25%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                                    </td>
                                    <td style="width:20%;text-align:center">
                                        <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                                    </td>
                                    <td style="width:20%;text-align:center">
                                        <span class="mb-2">{{ resep.dpjpUtama }}</span><br>
                                    </td>
                                    <td style="width:25%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
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
import ButtonEmr from '../../../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../../../page-emr-plugins/tanda-tangan.vue'
import { FilterMatchMode } from 'primevue/api';
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import InputText from 'primevue/inputtext';

const filtersTemplate = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS } });
useHead({ title: 'Surat Penggunaan Obat Khusus Kronis - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let d_Ruangan: any = ref([])
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const route = useRoute()
const router = useRouter()
const { y } = useWindowScroll()
const user = useUserSession().getUser().pegawai;
const isStuck = computed(() => { return y.value > 30 })
const isLoading = ref(false)
const pasien: any = ref({})
const input: any = ref({})
const dataTTD: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const idTemplate: any = ref('');
const checkTemplate: any = ref(false)
const isSave: any = ref(false)
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
function calculateAge(birthdate) {
    const today = new Date();
    const birthDate = new Date(birthdate);
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }

    return age;
}

function setAutoFill() {
    let d = input.value
    d.NamaPetugas = props.registrasi.dokter
    d.RMPasien = props.pasien.nocm
    d.NamaPasien = props.pasien.namapasien
    d.TanggalLahirPasien = props.pasien.tgllahir
    d.UmurPasien = calculateAge(props.pasien.tgllahir)
    d.JenisKelaminPasien = props.pasien.jeniskelamin
    d.AlamatPasien = props.pasien.alamatlengkap
    d.SpesialisPetugas = props.registrasi.namaruangan
    d.tanggal = new Date()
    d.Dokter = { label: props.registrasi.dokter, value: props.registrasi.iddokter }
    d.KepalaInstalasiFarmasi = 'RIZKI DANIEL, S.Farm., Apt'
    d.JabatanPetugas = 'DPJP'

    if (route.params.index_tabs > 1) {
        let rouutename = route.name + '-' + (route.params.index_tabs - 1)
        let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${rouutename}`)
        if (cache) {
            d.SpesialisPetugas = cache.SpesialisPetugas
            d.JabatanPetugas = cache.JabatanPetugas
            d.DiagnosaPasien = cache.DiagnosaPasien
            d.AlasanPemberianObat = cache.AlasanPemberianObat
        }
    }
}

const loadRiwayat = async () => {
    let tabs = route.params.index_tabs < 1 ? route.params.index_tabs - 1 : 1
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&index_tabs=${route.params.index_tabs}`)
    let check = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&index_tabs=${tabs}&check_first_tab=true`)
    if (response.length && check.length != 0) {
        isSave.value = true
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        dataTTD.value = response[0]
        H.tandaTangan().set("TTDKepalaInstalasiFarmasi", dataTTD.value.TTDKepalaInstalasiFarmasi)
        H.tandaTangan().set("TTDDokter", dataTTD.value.TTDDokter)
    } else {
        if (check.length == 0 && route.params.index_tabs != 1) {
            H.alert('warning', 'Halaman sebelumnnya belum disimpan!');
        }
        isSave.value = false
        setAutoFill()
    }
}

const simpan = () => {
    // Validasi
    // const validasi = /^.+X.+$/;
    // if (!validasi.test(input.value.DosisObat)) {
    //     H.alert('error', 'Dosis harus dalam format ...X...');
    //     return;
    // }
    if (checkTemplate.value == true) {
        H.alert('warning', 'Simpan template ya, bukan simpan data :)')
        return;
    }


    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    object['TTDKepalaInstalasiFarmasi'] = H.tandaTangan().get("TTDKepalaInstalasiFarmasi");
    object['TTDDokter'] = H.tandaTangan().get("TTDDokter");
    object.pasien = H.setObjectPasien(props.pasien)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    delete object.namatemplate
    if (route.params.index_tabs) {
        object.index_tabs = parseInt(route.params.index_tabs)
    }
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
    useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
        isLoading.value = false
        checkTemplate.value = false
        loadRiwayat();
    }).catch((e: any) => {
        isLoading.value = false
    })
}
const fetchPegawai = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => { d_Pegawai.value = response })
}
const fetchDokter = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`).then((response) => {
        d_Dokter.value = response
    })
}
const fetchRuangan = async (filter: any) => {
    const response = await useApi().get(`/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
    d_Ruangan.value = response
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
        let rouutename = from?.name;
        let indexTabs = route.params.index_tabs;
        let cacheKey = `TAB~${props.registrasi.noregistrasi}~${rouutename}~${indexTabs}`;

        if (to.name !== 'profile-pasien') {
            H.cacheEMR().set(cacheKey, input.value);
            console.log(`Cache disimpan untuk ${cacheKey}`);
        }

        if (to.name === 'profile-pasien') {
            H.cacheEMR().remove(cacheKey);
            console.log(`Cache dihapus karena berpindah ke profile-pasien: ${cacheKey}`);
        }

    } catch (error) {
        console.error('Error saat menyimpan/menghapus cache:', error);
    }
    next();
});


// Load Index
watch(
    () => route.params.index_tabs,
    (newValue, oldValue) => {
        input.value = {}
        input.value.DTttd = new Date()
        loadRiwayat()
        let rouutename = route.name + '-' + route.params.index_tabs
        let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${rouutename}`)
        if (cache) {
            input.value = cache
        }
    })
watch(
    () => input.value,
    (newValue, oldValue) => {
        let rouutename = route.name + '-' + route.params.index_tabs
        H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
        let timeout = null;
        if (timeout) {
            clearTimeout(timeout);
        }
        timeout = setTimeout(() => {
            H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, newValue);
        }, 500);
    }, { deep: true }
)

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
    delete dt['_id']
    H.alert('info', 'Silahkan ubah data dan Simpan Template Kembali');
    input.value = dt;
    idTemplate.value = dt.id;
    showModalTemplateFix.value = false;
    input.value.namatemplate = dt.namatemplate;
    checkTemplate.value = true
}

const addTemplate = (response: any) => {
    input.value = response
    delete input.value['id']
    delete input.value['_id']
    input.value.namatemplate = null
    input.value.Dokter = { label: props.registrasi.dokter, value: props.registrasi.iddokter }
    showModalTemplateFix.value = false
    showModalTemplate.value = false
    H.alert('info', 'Template berhasil ditambahkan')
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
</script>