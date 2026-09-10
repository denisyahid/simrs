<template>
    <ConfirmDialog />
    <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3>Ordotogram</h3>
                    </div>
                    <div class="right">
                        <div class="buttons">
                            <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="back()">
                                Kembali
                            </VButton>
                            <VButton type="submit" color="primary" raised icon="feather:save" :loading="isLoadBtnSave"
                                @click="simpan()"> Save
                            </VButton>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <VPlaceload height="37rem" width="100%" class="mx-2" v-if="loadData" />
        <VCard>

            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-3">
                        <h1 style="font-weight: bold;" class="mb-2">Tanggal & waktu Insiden</h1>
                        <VField>
                            <VDatePicker v-model="item.waktuKejadian" mode="dateTime" style="width: 100%" trim-weeks
                                :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </VField>
                    </div>

                    <div class="column is-5">
                        <h1 style="font-weight: bold;" class="mb-2">Keselamatan</h1>
                        <VField class=" is-autocomplete-select">
                            <VControl icon="feather:search">
                                <Multiselect mode="single" v-model="item.keselamatanfk" :options="d_Keselamatan"
                                    placeholder="Pilih Keselamatan" :searchable="true"
                                    @select="getInit(item.keselamatanfk)" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <h1 style="font-weight: bold;" class="mb-2">Jenis Keselamatan</h1>
                        <VField>
                            <VControl icon="feather:search">
                                <Dropdown v-model="item.jeniskeselamatanfk" :options="d_JenisKeselamatan"
                                    optionLabel="label" placeholder="Jenis Keselamatan"
                                    style="width: 100%; font-weight:bold" :filter="true" disabled appendTo="body" />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>

            <div class="column is-12 pt-0">
                <h1 style="font-weight: bold;" class="mb-2">Insiden</h1>
                <VField>
                    <VControl>
                        <VTextarea v-model="item.insiden" rows="3">
                        </VTextarea>
                    </VControl>
                </VField>
            </div>

            <div class="column is-12">
                <h1 style="font-weight: bold;" class="mb-2">Jenis Insiden</h1>
                <div class="columns is-multiline">
                    <div class="column is-4" v-for="(jenis, i) in sourceJenisKeselamatan">
                        <VField>
                            <VControl raw subcontrol>
                                <VCheckbox v-model="item.jenis" :true-value="jenis.id" :label="jenis.jeniskesalamatan"
                                    class="p-0" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <h1 style="font-weight: bold;" class="mb-2">Orang Pertama Yang Melaporkan Insiden</h1>
                <div class="columns is-multiline">
                    <div class="column is-6" v-for="(pelopor, i) in listPelapor">
                        <VField>
                            <VControl raw subcontrol>
                                <VCheckbox v-model="item.pelopor" :true-value="pelopor.id" :label="pelopor.nama" class="p-0"
                                    color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <h1 style="font-weight: bold;" class="mb-2">Lokasi Insiden</h1>
                <VField>
                    <VControl>
                        <VTextarea v-model="item.lokasiInsiden" rows="3">
                        </VTextarea>
                    </VControl>
                </VField>
            </div>
            <div class="column is-12">
                <h1 style="font-weight: bold;" class="mb-2">Insiden Menyangkut Pasien</h1>
                <div class="columns is-multiline">
                    <div class="column is-6" v-for="(insiden, i) in listInsidenPasien">
                        <VField>
                            <VControl raw subcontrol>
                                <VCheckbox v-model="item.insidenPasien" :true-value="insiden.id" :label="insiden.nama"
                                    class="p-0" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <h1 style="font-weight: bold;" class="mb-2">Insiden terjadi pada pasien : ( jiwa dan sub spesialisasnya)
                </h1>
                <div class="columns is-multiline">
                    <div class="column is-6" v-for="(jiwa, i) in listJiwa">
                        <VField>
                            <VControl raw subcontrol>
                                <VCheckbox v-model="item.jiwa" :true-value="jiwa.id" :label="jiwa.nama" class="p-0"
                                    color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <h1 style="font-weight: bold;" class="mb-2">Unit Kerja Penyebab</h1>
                <VField>
                    <VControl>
                        <VTextarea v-model="item.unitKerjaPenyebab" rows="3">
                        </VTextarea>
                    </VControl>
                </VField>
            </div>
            <div class="column is-12">
                <h1 style="font-weight: bold;" class="mb-2">Akibat Insiden Terhadap Pasien</h1>
                <div class="columns is-multiline">
                    <div class="column is-6" v-for="(akibatInsiden, i) in listAkibatInsiden">
                        <VField>
                            <VControl raw subcontrol>
                                <VCheckbox v-model="item.akibatInsiden" :true-value="akibatInsiden.id"
                                    :label="akibatInsiden.nama" class="p-0" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <h1 style="font-weight: bold;" class="mb-2">Tindakan yang Dilakukan Segera Setelah Kejadian, dan hasilnya
                </h1>
                <VField>
                    <VControl>
                        <VTextarea v-model="item.setelahKejadian" rows="3">
                        </VTextarea>
                    </VControl>
                </VField>
            </div>
            <div class="column is-12">
                <h1 style="font-weight: bold;" class="mb-2">Apakah Kejadian yang sama pernah terjadi di Unit Kerja Lain?
                </h1>
                <div class="columns is-multiline">
                    <div class="column is-4" v-for="(akibatKejadian, i) in listAkibatKejadian">
                        <VField>
                            <VControl raw subcontrol>
                                <VCheckbox v-model="item.akibatKejadian" :true-value="akibatKejadian.id"
                                    :label="akibatKejadian.nama" class="p-0" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <h1 style="font-weight: bold;" class="mb-2">Tindakan dilakukan oleh</h1>
                <div class="columns is-multiline">
                    <div class="column is-4" v-for="(pelakuTindakan, i) in listPelakuTindakan">
                        <VField>
                            <VControl raw subcontrol>
                                <VCheckbox v-model="item.pelakuTindakan" :true-value="pelakuTindakan.id"
                                    :label="pelakuTindakan.nama" class="p-0" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <h1 style="font-weight: bold;" class="mb-2">Kapan dan Langkah apa yang telah diambil pada Unit kerja
                    Tersebut
                    untuk mencegah terulangnya kejadiannya yang sama</h1>
                <VField>
                    <VControl>
                        <VTextarea v-model="item.pencegah" rows="3">
                        </VTextarea>
                    </VControl>
                </VField>
            </div>

            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-6">
                        <h1 style="font-weight: bold;" class="mb-2">Pembuat Laporan</h1>
                        <VField>
                            <VControl class="prime-auto">
                                <AutoComplete v-model="item.pembuatLaporan" :suggestions="d_Pegawai"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari Pegawai..." />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <h1 style="font-weight: bold;" class="mb-2">Tanggal Laporan</h1>
                        <VField>
                            <VDatePicker v-model="item.tglLaporan" mode="dateTime" style="width: 100%" trim-weeks
                                :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </VField>
                    </div>
                </div>
            </div>

            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-6">
                        <h1 style="font-weight: bold;" class="mb-2">Penerima Laporan (Ka.RU/Ka.Ins)</h1>
                        <VField>
                            <VControl class="prime-auto">
                                <AutoComplete v-model="item.penerimaLaporan" :suggestions="d_Pegawai"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari Pegawai..." />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <h1 style="font-weight: bold;" class="mb-2">Tanggal Terima</h1>
                        <VField>
                            <VDatePicker v-model="item.tglTerima" mode="dateTime" style="width: 100%" trim-weeks
                                :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </VField>
                    </div>
                </div>
            </div>

            <div class="column is-12">
                <h1 style="font-weight: bold;" class="mb-2">Grading Risiko Kejadian (Diisi oleh atasan pelapor)</h1>
                <div class="columns is-multiline">
                    <div class="column is-3" v-for="(gradingResiko, i) in listGradingResiko">
                        <VField>
                            <VControl raw subcontrol>
                                <VCheckbox v-model="item.gradingResiko" :true-value="gradingResiko.id"
                                    :label="gradingResiko.nama" class="p-0" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>

        </VCard>
    </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Dropdown from 'primevue/dropdown';
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useToaster } from '/@src/composable/toaster'
import AutoComplete from 'primevue/autocomplete';
import sleep from '/@src/utils/sleep'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'

useHead({
    title: 'Odontogram - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pasien_daftar as string
let NOREC_APD = useRoute().query.norec_apd as string

const isLoadingPasien: any = ref(false)
const isTrueUE: any = ref(true)

const props = withDefaults(
    defineProps<{
        pasien?: any
        registrasi?: any
        FORM_NAME?: string
        FORM_URL?: string
    }>(),
    {
        pasien: {},
        registrasi: {},
        FORM_NAME: '',
        FORM_URL: '',
    }
)

const confirm = useConfirm()
const item: any = ref({
    waktuKejadian: new Date()
})
const d_JenisDiagnosa: any = ref([])
const d_Diagnosa: any = ref([])
const pasien: any = ref({})
const { y } = useWindowScroll()
const d_Keselamatan: any = ref([])
const d_Pegawai: any = ref([])
const d_JenisKeselamatan: any = ref([])
const sourceJenisKeselamatan: any = ref([])
const listColor: any = ref(Object.keys(useThemeColors()))
const isStuck = computed(() => { return y.value > 30 })
const loadData: any = ref(true)
const isLoadBtnSave: any = ref(false)
const activeStatus: any = ref()
const isDetail: any = ref([false])

const listPelapor = [
    { "id": 1, "nama": "Karyawan : Dokter / Perawat / Petugas Lainnya" },
    { "id": 2, "nama": "Keluarga / Pendamping Pasien" },
    { "id": 3, "nama": "Pasien" },
    { "id": 4, "nama": "Pengunjung" },
    { "id": 5, "nama": "Lain-lain" }
];

const listInsidenPasien = [
    { "id": 1, "nama": "Pasien Rawat Inap" },
    { "id": 2, "nama": "Pasien Rawat Jalan" },
    { "id": 3, "nama": "Pasien IGD" },
    { "id": 4, "nama": " Lain-lain" },
];

const listJiwa = [
    { "id": 1, "nama": "Anak Remaja" },
    { "id": 2, "nama": "Napza" },
    { "id": 3, "nama": "Dewasa" },
    { "id": 4, "nama": "Lansia" },
    { "id": 5, "nama": "GMO" },
    { "id": 6, "nama": "ELektromedik" },
    { "id": 7, "nama": "  Lain-lain" },
];

const listAkibatInsiden = [
    { "id": 1, "nama": "Kematian" },
    { "id": 2, "nama": "Cedera Irreversibe / Cedera Berat" },
    { "id": 3, "nama": "Cedera Reversibel / Cedera Sedang" },
    { "id": 4, "nama": "Cedera Ringan" },
    { "id": 5, "nama": "Tidak Ada Cedera" },
]
const listAkibatKejadian = [
    { "id": 1, "nama": "Ya" },
    { "id": 2, "nama": "Tidak" },
]
const listPelakuTindakan = [
    { "id": 1, "nama": "Tim : Terdiri" },
    { "id": 2, "nama": "Dokter" },
    { "id": 3, "nama": "Petugas Lainnya" },
    { "id": 4, "nama": "Perawat" },
]
const listGradingResiko = [
    { "id": 1, "nama": "BIRU" },
    { "id": 2, "nama": "HIJAU" },
    { "id": 3, "nama": "KUNING" },
    { "id": 4, "nama": "MERAH" },
]

const fetchRiwayat = () => {
    loadData.value = false
}

const simpan = async () => {

    if(!item.value.jeniskeselamatanfk){
        H.alert('error','Keselamatan Tidak Boleh Kosong')
        return
    }
    if(!item.value.waktuKejadian){
        H.alert('error','Waktu Kejadian Tidak Boleh Kosong')
        return
    }
    if(!item.value.insiden){
        H.alert('error','Insiden Tidak Boleh Kosong')
        return
    }
    if (!item.value.pembuatLaporan) {
        H.alert('error', 'Pembuat Laporan Tidak Boleh Kosong')
        return
    }
    if(!item.value.tglLaporan){
        H.alert('error','Tanggal Laporan Tidak Boleh Kosong')
        return
    }
    if(!item.value.tglTerima){
        H.alert('error','Tanggal Terima Laporan Tidak Boleh Kosong')
        return
    }
    if(!item.value.penerimaLaporan){
        H.alert('error','Penerima Laporan Tidak Boleh Kosong')
        return
    }

    let pasien = props.pasien
    let registrasi = props.registrasi
    isLoadBtnSave.value = true

    let objSave = {
        'data': {
            norec : item.value.norec ? item.value.norec : '',
            nocm: pasien.nocm,
            namapasien: pasien.namapasien,
            tglahir: pasien.tgllahir,
            ruanganfk: registrasi.objectruanganfk,
            umur: pasien.umur,
            jeniskelaminfk: pasien.objectjeniskelaminfk,
            penanggungbiayapasienfk: registrasi.objectkelompokpasienlastfk,
            tglmasuk: registrasi.tglregistrasi,
            tglinsiden: H.formatDate(item.value.waktuKejadian, 'YYYY-MM-DD HH:mm:ss'),
            insiden: item.value.insiden ? item.value.insiden : null,
            jenisinsiden: item.value.jenis ? item.value.jenis : null,
            pelaporinsiden: item.value.pelopor ? item.value.pelopor : null,
            tempatinsiden: item.value.lokasiInsiden ? item.value.lokasiInsiden : null,
            insidenterjadi: item.value.insidenPasien ? item.value.insidenPasien : null,
            jiwa: item.value.jiwa ? item.value.jiwa : null,
            unitterkait: item.value.unitKerjaPenyebab ? item.value.unitKerjaPenyebab : null,
            akibatinsiden: item.value.akibatInsiden ? item.value.akibatInsiden : null,
            penanganan: item.value.setelahKejadian ? item.value.setelahKejadian : null,
            dilakukanoleh: item.value.pelakuTindakan ? item.value.pelakuTindakan : null,
            kejadiansama: item.value.akibatKejadian ? item.value.akibatKejadian : null,
            langkahpenanganan: item.value.pencegah ? item.value.pencegah : null,
            pembuatlaporan: item.value.pembuatLaporan ? item.value.pembuatLaporan.label : null,
            tgllapor: H.formatDate(item.value.tglLaporan, 'YYYY-MM-DD HH:mm:ss'),
            penerimalaporan: item.value.penerimaLaporan ? item.value.penerimaLaporan.label : null,
            tglterima: H.formatDate(item.value.tglTerima, 'YYYY-MM-DD HH:mm:ss'),
            grading: item.value.gradingResiko ? item.value.gradingResiko : null,
            insidenkeselamatanfk: item.value.jeniskeselamatanfk.value,
            noregistrasifk: registrasi.norec_pd,
        }
    }

    await useApi().post('pmkp/simpan-laporan-insiden-internal',objSave).then((response)=>{
        isLoadBtnSave.value = false
    })
}

const fetchDropdown = async () => {
    let response = await useApi().get(`/pmkp/get-data-combo-pmkp`)
    d_Keselamatan.value = response.insidenkeselamtanpasien.map((e: any) => {
        return { label: e.keselamatan, value: e }
    })
    d_JenisKeselamatan.value = response.jeniskeselamatan.map((e: any) => {
        return { label: e.jeniskesalamatan, value: e.id }
    })
    sourceJenisKeselamatan.value = response.jeniskeselamatan
}

const fetchPegawai = async (filter: any) => {

    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Pegawai.value = response
    })
}

const getInit = (e: any) => {
    d_JenisKeselamatan.value.forEach((element: any) => {
        if (element.value == e.jeniskesalamatanfk) {
            item.value.jeniskeselamatanfk = element
        }
    });
}

const back = () => {
    window.history.back()
}


fetchDropdown()
fetchRiwayat()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
</style>
