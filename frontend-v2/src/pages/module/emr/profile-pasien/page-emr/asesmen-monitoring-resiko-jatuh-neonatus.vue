<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3> {{ props.FORM_NAME }}</h3>
                    </div>
                    <div class="right">
                        <div class="buttons">
                            <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                Kembali
                            </VButton>
                            <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                :loading="isLoading" @click="simpan()"> Simpan
                            </VButton>
                        </div>
                    </div>
                </div>
            </div>

        </div>
            <div class="form-outer" style="margin-top:15px">

                <!-- form baru -->

                <div class="column">
                    <div class="columns is-multiline">

                        <div class="column is-12 pt-0 pb-0">
                            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                        </div>

                        <div class="column is-8 is-flex"
                            style="font-weight: bold;font-size: large;align-items: center;">
                           Asesmen dan Monitoring Risiko Jatuh Neonatus
                        </div>
                        <div class="column is-4" style="text-align: center;">
                            <VButton type="button" rounded color="dark" class="mb-3"> Tambah Kolom
                            </VButton>
                            <VButtons style="justify-content:space-around">
                                <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()"
                                    color="info" v-tooltip.bubble="'Tambah '">
                                </VIconButton>
                                <VIconButton class="mt-1" v-if="input.details.length > 1" type="button" raised circle
                                    icon="feather:trash" @click="removeItem(index)" color="danger">
                                </VIconButton>
                            </VButtons>
                        </div>
                        <div class="column is-3 pr-0">
                            <table class="tg3" style="width: 100% !important;">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;vertical-align: middle;">TINDAKAN
                                            PENCEGAHAN</th>
                                    </tr>
                                    <tr>
                                        <th colspan="2"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1. Terpasang tanda risiko jatuh pada box/incubator </td>
                                    </tr>
                                    <tr>
                                        <td>2. Dekatkan box bayi dengan ibu </td>
                                    </tr>
                                    <tr>
                                        <td>3. Pastikan lantai dan alas kaki tidak licin</td>
                                    </tr>
                                    <tr>
                                        <td>4. Orientasi ruangan pada orangtua/keluarga</td>
                                    </tr>
                                    <tr>
                                        <td>5. Pastikan selalu ada pendamping</td>
                                    </tr>
                                    <tr>
                                        <td>6. Kontrol rutin oleh perawat/bidan</td>
                                    </tr>
                                    <tr>
                                        <td>7. Bila dirawat dalam incubator pastikan semua jendela terkunci</td>
                                    </tr>
                                    <tr>
                                        <td>8. Edukasi orang tua/keluarga</td>
                                    </tr>
                                    <tr>
                                        <td><b>Catatan</b></td>
                                    </tr>
                                    <tr>
                                        <td>EVALUASI <br>Apakah terjadi insiden jatuh?</td>
                                    </tr>
                                    <tr>
                                        <td>Nama petugas</td>
                                    </tr>
                                    <tr>
                                        <td height="150px">Tanda tangan petugas</td>
                                    </tr>
                                </tbody>
                            </table>
                            
                        </div>
                       
                        <div class="column is-9 pl-0" style="overflow: auto;">
                            <table>
                                <tr>
                                    <td v-for="(data, index) in paginatedDetails" :key="index" class="p-0">
                                        <table class="tg3" style="width: 100% !important;">
                                            <tr>
                                                <th style="font-weight: bold;">Tanggal & Jam</th>
                                            </tr>
                                            <tr>
                                                <th style="background-color: lightgray !important;">
                                                    <VDatePicker v-model="data['tgl_MonitoringEvaluasi']"
                                                        mode="datetime" is24hr>
                                                        <template #default="{ inputValue, inputEvents }">
                                                            <VControl icon="feather:calendar" fullwidth>
                                                                <VInput :value="inputValue" v-on="inputEvents" />
                                                            </VControl>
                                                        </template>
                                                    </VDatePicker>
                                                </th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="data['RJR_1']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJR_1']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="data['RJR_2']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJR_2']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="data['RJR_3']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJR_3']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="data['RJR_4']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJR_4']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="data['RJR_5']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJR_5']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="data['RJR_6']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJR_6']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="data['RJR_7']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJR_7']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="data['RJR_8']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJR_8']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Untuk pernyataan item 1-8 diisi dengan tulisan ya atau tidak</b></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="data['RJR_9']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJR_9']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns is-9" style="margin-left: 10px;">
                                                        <VField>
                                                            <VControl class="prime-auto">
                                                                <AutoComplete v-model="data['Petugas']" :suggestions="d_Petugas"
                                                                    @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true"
                                                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                                    :field="'label'" placeholder="Cari ..." />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="column is-12">
                                                        <div class="column" style="text-align:center;">
                                                            <TandaTangan 
                                                            :elemenID="`TTDPetugas_${index + (currentPage - 1) * perPage}`"
                                                            :width="'150'" 
                                                            :height="'150'" 
                                                            class="dek" 
                                                            />
                                                            {{ index + (currentPage - 1) * perPage }}
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="column is-3 pl-0"></div>
                        <div class="column is-9 pl-0 pagination">
                            <div class="column is-12 is-flex">
                                <div class="column is-3 pl-0">
                                    <button class="button is-link is-focused" @click="goToFirstPage" :disabled="currentPage === 1">◀◀</button>
                                </div>
                                <div class="column is-3 pl-0">
                                    <button class="button is-link is-focused" @click="prevPage" :disabled="currentPage === 1">◀ Sebelumnya</button>
                                </div>
                                
                                <span>Halaman {{ currentPage }} dari {{ totalPages }}</span>

                                <div class="column is-3 pl-0">
                                    <button class="button is-link is-focused" @click="nextPage" :disabled="currentPage === totalPages">Berikutnya ▶</button>
                                </div>
                                <div class="column is-3 pl-0">
                                    <button class="button is-link is-focused" @click="goToLastPage" :disabled="currentPage === totalPages">▶▶</button>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="column is-12 pb-0">
                            <span><b>Catatan: Untuk pernyataan item 1-8 diisi dengan tulisan ya atau tidak</b></span>
                        </div> -->
                        

                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount, watchEffect, nextTick } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';


// Judul
useHead({
    title: 'Asesmen dan Monitoring Resiko Jatuh Dewasa - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const d_keadaanumum: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Sedang' }, { value: 3, label: 'Buruk' }])
const user = useUserSession().getUser().pegawai;
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
const fetchPetugas = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&limit=10&query=${filter.query}`).then((response) => { d_Petugas.value = response })
}
const dataTTD: any = ref([])
const route = useRoute()
const pasien: any = ref({})
const d_Petugas: any = ref([])
const loadData: any = ref(true)
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: props.registrasi.norec_apd,
    RUANGAN_LAST: props.registrasi.objectruanganlastfk,
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    date: {
        tanggal: new Date,
        jam: new Date
    },
    filter: '',
    airway: [],
    disability: []
})
const COLLECTION: any = ref('AsesmenMonitoringResikoJatuhNeonatus') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const { y } = useWindowScroll()
const router = useRouter()
const isStuck = computed(() => {
    return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const alertMid = ref(false);
const d_Dokter: any = ref([])
const nilaiKajian: number = 0;

// const row: any = ref({
//     header: [
//         title: 
//     ]
// })
const d_Catatan: any = ref([
    { value: 1, label: 'a) PF (Post Falls)' },
    { value: 2, label: 'b) CC (Change of Condition)' },
    { value: 3, label: 'c) WT (On Ward Transfer)' },
    { value: 4, label: 'd) DC (Discharge)' },
    { value: 5, label: 'e) ES (Every Shift)' },

])
const input: any = ref({
    details: [{
        no: 1,
        tgl_MonitoringEvaluasi: new Date(),
        petugas: { label: user.namaLengkap, value: user.id }
    }],
})

const fetchDokter = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`).then((response) => {
        d_Dokter.value = response
    })
}

const perPage = ref(10); 
const currentPage = ref(1);
const totalPages = computed(() => Math.ceil(input.value.details.length / perPage.value));
const cacheTandaTangan = ref({});

const paginatedDetails = computed(() => {
  const start = (currentPage.value - 1) * perPage.value;
  return input.value.details.slice(start, start + perPage.value);
});

//Fungsi untuk menyimpan tanda tangan yang sedang ditampilkan di halaman aktif ke cache
const saveActivePageToCache = () => {
    const startIndex = (currentPage.value - 1) * perPage.value;
    const endIndex = startIndex + perPage.value;

    for (let i = startIndex; i < endIndex; i++) {
        let fieldName = `TTDPetugas_${i}`;
        cacheTandaTangan.value[fieldName] = H.tandaTangan().get(fieldName) || '';
    }
    console.log(`Saved active page ${currentPage.value} to cache:`, cacheTandaTangan.value);
};

const nextPage = async () => {
    if (currentPage.value < totalPages.value) {
        saveActivePageToCache(); // Simpan tanda tangan halaman aktif sebelum pindah
        currentPage.value++;
        await updateSignatures();
    }
};

const prevPage = async () => {
    if (currentPage.value > 1) {
        saveActivePageToCache();
        currentPage.value--;
        await updateSignatures();
    }
};

const goToFirstPage = async () => {
    saveActivePageToCache();
    currentPage.value = 1;
    await updateSignatures();
};

const goToLastPage = async () => {
    saveActivePageToCache();
    currentPage.value = totalPages.value;
    await updateSignatures();
};

const updateSignatures = async () => {
    await nextTick(); // Pastikan DOM sudah diperbarui

    const startIndex = (currentPage.value - 1) * perPage.value;
    const endIndex = startIndex + perPage.value;

    for (let i = startIndex; i < endIndex; i++) {
        let fieldName = `TTDPetugas_${i}`;

        // Ambil dari cache terlebih dahulu
        if (cacheTandaTangan.value[fieldName]) {
            H.tandaTangan().set(fieldName, cacheTandaTangan.value[fieldName]);
        } 
        // Jika tidak ada di cache, ambil dari API
        else if (dataTTD.value[fieldName]) {
            H.tandaTangan().set(fieldName, dataTTD.value[fieldName]);
        } 
        // Jika tidak ada, kosongkan
        else {
            H.tandaTangan().set(fieldName, '');
        }
    }
};

//Simpan SEMUA tanda tangan dari SEMUA halaman sebelum menyimpan ke server
const saveAllPagesToCache = () => {
    for (let i = 0; i < input.value.details.length; i++) {
        let fieldName = `TTDPetugas_${i}`;
        cacheTandaTangan.value[fieldName] = H.tandaTangan().get(fieldName) || '';
    }
    console.log("Cached all signatures:", cacheTandaTangan.value);
};

watch(currentPage, async () => {
  await nextTick(); 
  console.log(`Berpindah ke halaman ${currentPage.value}`);
});

//Saat load data, pastikan tanda tangan dari API masuk ke cache
const loadRiwayat = async () => {
    await useApi()
        .get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
        .then(async (response) => {
            if (response.length) {
                input.value = response[0];

                if (!NOREC_EMRPASIEN.value) {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk;
                }

                dataTTD.value = response[0];

                // Simpan tanda tangan dari API ke cache
                for (let i = 0; i < input.value.details.length; i++) {
                    let fieldName = `TTDPetugas_${i}`;
                    if (dataTTD.value[fieldName]) {
                        cacheTandaTangan.value[fieldName] = dataTTD.value[fieldName];
                    }
                }

                await nextTick();
                updateSignatures();
            } else {
                setAutoFill();
            }
        })
        .catch((error) => {
            console.error("Error loading riwayat:", error);
        });
};

//Simpan SEMUA tanda tangan dari cache ke server, termasuk yang ada di halaman aktif
const simpan = () => {
    saveActivePageToCache(); // Simpan tanda tangan di halaman aktif ke cache sebelum menyimpan

    let ID = input.value.id || '';
    let object = { ...input.value };

    // Simpan semua tanda tangan
    for (let key in cacheTandaTangan.value) {
        object[key] = cacheTandaTangan.value[key] || '';
    }

    object.nocm = pasien.value.nocm;
    object.pasien = H.setObjectPasien(pasien.value);
    object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi);

    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'url_form': route.name,
        'name_form': 'Asesmen & Monitoring Resiko Jatuh Neonatus',
        'jenis_emr': 'asesmen_medis',
        'data': object
    };

    console.log("Saving to server:", json);

    isLoading.value = true;
    useApi().post(`/emr/simpan-emr`, json)
        .then(async (response) => {
            isLoading.value = false;
            NOREC_EMRPASIEN.value = response.norec_emr;
            cacheTandaTangan.value = {}; // Kosongkan cache setelah tersimpan
            await loadRiwayat();
        })
        .catch((e) => {
            isLoading.value = false;
            console.error("Error saving signatures:", e);
        });
};



const fetchPasien = () => {
    pasien.value = props.pasien
    pasien.value.registrasi = props.registrasi
    NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
    console.log(norec_emr)
}

const kembaliKeun = () => {
    window.history.back()
}

const addNewItem = () => {
    input.value.details.push({
        no: input.value.details[input.value.details.length - 1].no + 1,
        tgl_SM: new Date(),
        Petugas: { label: user.namaLengkap, value: user.id }
    });
}
const removeItem = (index: any) => {
    let urut = input.value.details.length - 1
    input.value.details.splice(urut, 1)
}



onBeforeMount(async () => {
    try {
        await loadRiwayat()
        let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
        if (cache) input.value = cache
        loadData.value = false
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

var dat = input.value
watchEffect(() => {
    let total = 0;
    input.value.details.forEach((a, index) => {
        let usia = parseFloat(a['Usia'] ?? 0)
        let defisitSensoris = parseFloat(a['DefisitSensoris'] ?? 0)
        let aktifitas = parseFloat(a['Aktifitas'] ?? 0)
        let riwayatJatuh = parseFloat(a['RiwayatJatuh'] ?? 0)
        let kognisi = parseFloat(a['Kognisi'] ?? 0)
        let pengobatan = parseFloat(a['Pengobatan'] ?? 0)
        let mobilitas = parseFloat(a['Mobilitas'] ?? 0)
        let polababk = parseFloat(a['PolaBABK'] ?? 0)
        let komorbiditas = parseFloat(a['Komorbiditas'] ?? 0)

        total = usia + defisitSensoris + aktifitas + riwayatJatuh + kognisi + pengobatan + mobilitas + polababk + komorbiditas
        if (!isNaN(total)) {
            a['TotalSM'] = total
        } else {
            a['TotalSM'] = 0
        }

        if (a['TotalSM'] <= 7 && a['TotalSM'] >= 0) {
            a['Resiko'] = '0-7'
        } else if (a['TotalSM'] >= 8 && a['TotalSM'] <= 13) {
            a['Resiko'] = '8-13'
        } else if (a['TotalSM'] >= 14) {
            a['Resiko'] = '≥ 14'
        } else {
            a['Resiko'] = undefined
        }
    });
});

// watch(() => [input.value.jumlahNilaiSN], ([a]) => {
//     a = a ?? 0;
//     if (a <= 7) {
//         input.value.nilairesiko = 'Risiko rendah 0-7'
//     } else if (a <= 13) {
//         input.value.nilairesiko = 'Risiko sedang 8-13'
//     } else if (a <= 14) {
//         input.value.nilairesiko = 'Risiko sangat tinggi > 13'
//     }
// });

fetchPasien();

</script>

<style lang="scss">
.table {
    border-collapse: collapse;
    width: 100% !important;
}

.table td {
    height: 4rem !important;
    text-align: center !important;
    border: 1px solid black !important;
    // border-left: none !important;
}

label {
    color: black !important;
}

.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 150% !important;
}

.tg2 {
    border-collapse: collapse;
    border-spacing: 0;
    width: 120% !important;
}

.tg2 td {
    height: 4rem !important;
    border: 1px solid black !important;
    vertical-align: middle !important;
    padding: 5px !important;
}

.tg2 th {
    height: 4rem !important;
    border: 1px solid black !important;
    vertical-align: middle !important;
    padding: 5px !important;
    text-align: center !important;
}

.tg3 {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100% !important;
}

.tg3 td {
    height: 4rem !important;
    border: 1px solid black !important;
    vertical-align: middle !important;
    padding: 5px !important;
}

.tg3 th {
    text-align: center !important;
    height: 4rem !important;
    border: 1px solid black !important;
    vertical-align: middle !important;
    padding: 5px !important;
}

.tg td {
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg th {
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: normal;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg .tg-0lax {
    text-align: left;
    vertical-align: middle
}

hr {
    background-color: hsl(0deg 6.81% 88.68%);
    border: none;
    display: block;
    height: 2px;
    margin: 0px;
}

.alert-nobg {
    background: none !important;
    height: 5em;
    width: 5em;
}
.pagination-controls {
  display: flex;
  justify-content: space-between;
  margin-top: 10px;
}
button {
  padding: 5px 10px;
  background-color: #007bff;
  color: white;
  border: none;
  cursor: pointer;
}

button:disabled {
  background-color: #cccccc;
}
</style>