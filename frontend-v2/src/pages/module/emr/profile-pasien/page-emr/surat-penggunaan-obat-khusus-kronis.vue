<style lang="scss">
h1 {
    font-weight: bold !important;
}
</style>
<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Surat Penggunaan Obat Khusus Kronis</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :ID="input.id"
                                :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate"
                                @kembaliKeun="kembaliKeun" isHideST></ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->
                <div class="column">
                    <div class="columns is-multiline">
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
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
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
                                <VTextarea rows="2" v-model="input.AlasanPemberianObat" ></VTextarea>
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
                                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="Dokter..." />
                                </VControl>
                            </div>
                        </div>
                    </div>
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
const loadRiwayat = async () => {
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        dataTTD.value = response[0]
        H.tandaTangan().set("TTDKepalaInstalasiFarmasi", dataTTD.value.TTDKepalaInstalasiFarmasi)
        H.tandaTangan().set("TTDDokter", dataTTD.value.TTDDokter)
    } else {
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
    }
}


const simpan = () => {
    // Validasi
    // const validasi = /^.+X.+$/;
    // if (!validasi.test(input.value.DosisObat)) {
    //     H.alert('error', 'Dosis harus dalam format ...X...');
    //     return;
    // }

    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    if (object.hasOwnProperty('namatemplate')) {
        delete object.namatemplate
    }
    object['TTDKepalaInstalasiFarmasi'] = H.tandaTangan().get("TTDKepalaInstalasiFarmasi");
    object['TTDDokter'] = H.tandaTangan().get("TTDDokter");
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
    useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
        isLoading.value = false
        loadRiwayat();
    }).catch((e: any) => {
        isLoading.value = false
    })
}
const fetchPegawai = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => { d_Pegawai.value = response })
}
const fetchDokter = async (filter: any) => {
    await useApi().get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
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

        // Simpan cache saat berpindah halaman (kecuali ke profile-pasien)
        if (to.name !== 'profile-pasien') {
            H.cacheEMR().set(cacheKey, input.value);
            console.log(`Cache disimpan untuk ${cacheKey}`);
        }

        // Hapus cache hanya jika tujuan adalah 'profile-pasien'
        if (to.name === 'profile-pasien') {
            H.cacheEMR().remove(cacheKey);
            console.log(`Cache dihapus karena berpindah ke profile-pasien: ${cacheKey}`);
        }

    } catch (error) {
        console.error('Error saat menyimpan/menghapus cache:', error);
    }
    next();
});
</script>