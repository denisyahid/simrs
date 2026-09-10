<template>
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
    </div>

    <div class="column is-12">
        <VCard>
            <div class="column is-8">
                <h1 class="mb-3 bold">Riwayat Penyakit</h1>
                <VField>
                    <VControl>
                        <VTextarea v-model="item.rencanaTindakan" rows="2" placeholder="Riwayat Penyakit ...">
                        </VTextarea>
                    </VControl>
                </VField>
            </div>
            <div class="columns is-multiline pt-3 pr-3 pl-3">
                <div class="column is-3">
                    <VField label="Tinggi Badan"></VField>
                    <VField addons>
                        <VControl expanded>
                            <VInput type="text" class="input" placeholder="Tinggi Badan" v-model="item.tinggiBadan" />
                        </VControl>
                        <VControl class="field-addon-body">
                            <VButton static>Cm</VButton>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <VField label="Berat Badan"></VField>
                    <VField addons>
                        <VControl expanded>
                            <VInput type="text" class="input" placeholder="Berat Badan" v-model="item.beratBadan" />
                        </VControl>
                        <VControl class="field-addon-body">
                            <VButton static>Kg</VButton>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <VField label="Tekanan Darah"></VField>
                    <VField addons>
                        <VControl expanded>
                            <VInput type="text" class="input" placeholder="Tekanan Darah" v-model="item.tekananDarah" />
                        </VControl>
                        <VControl class="field-addon-body">
                            <VButton static>mmHG</VButton>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <VField label="Nadi"></VField>
                    <VField addons>
                        <VControl expanded>
                            <VInput type="text" class="input" placeholder="Nadi" v-model="item.nadi" />
                        </VControl>
                        <VControl class="field-addon-body">
                            <VButton static>x/mnt</VButton>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <VField label="Suhu"></VField>
                    <VField addons>
                        <VControl expanded>
                            <VInput type="text" class="input" placeholder="Suhu" v-model="item.suhu" />
                        </VControl>
                        <VControl class="field-addon-body">
                            <VButton static>°C </VButton>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <VField label="Pernapasan"></VField>
                    <VField addons>
                        <VControl expanded>
                            <VInput type="text" class="input" placeholder="Pernapasan" v-model="item.pernapasan" />
                        </VControl>
                        <VControl class="field-addon-body">
                            <VButton static>x/mnt</VButton>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <VField label="SPO2"></VField>
                    <VField addons>
                        <VControl expanded>
                            <VInput type="text" class="input" placeholder="SPO2" v-model="item.SPO2" />
                        </VControl>
                        <VControl class="field-addon-body">
                            <VButton static>%</VButton>
                        </VControl>
                    </VField>
                </div>
            </div>
            <div class="columns is-multiline pl-4 pr-4">
                <div class="column is-6 p-2">
                    <h1 class="mb-3 bold">Pemeriksaan Fisik Lainnya</h1>
                    <VField>
                        <VControl>
                            <VTextarea v-model="item.pemeriksaanFisikLain" rows="2"
                                placeholder="Pemeriksaan Fisik Lainnya ...">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-6 p-2">
                    <h1 class="mb-3 bold">Pemeriksa Penunjang</h1>
                    <VField>
                        <VControl>
                            <VTextarea v-model="item.pemeriksaPenunjang" rows="2" placeholder="Pemeriksa Penunjang ...">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>
            </div>
            <div class="columns is-multiline pl-4 pr-4 pt-2">
                <div class="column is-6 p-2">
                    <h1 class="mb-3 bold">Terapi Pengobatan</h1>
                    <VField>
                        <VControl>
                            <VTextarea v-model="item.terapiPengobatan" rows="2" placeholder="Terapi Pengobatan ...">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-6 p-2">
                    <h1 class="mb-3 bold">Hasil Konsultasi</h1>
                    <VField>
                        <VControl>
                            <VTextarea v-model="item.hasilKonsultasi" rows="2" placeholder="Hasil Konsultasi ...">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>
            </div>

            <div class="column is-12">
                <h1 class="mb-3 bold">Diagnosa</h1>
                <table class="table-pri">
                    <thead>
                        <tr class="tr-pri">
                            <th class="th-pri" width="2%" style="vertical-align: inherit;">NO</th>
                            <th class="th-pri" style="vertical-align:inherit;text-align: center;">Jenis Diagnosa</th>
                            <th class="th-pri" style="vertical-align:inherit;text-align: center;">Diagnosa Dokter</th>
                            <th class="th-pri" style="vertical-align:inherit;text-align: center;">Diagnosa ICD 10</th>
                            <th class="th-pri" rowspan="2" style="vertical-align:inherit;text-align: center;" width="2%">#
                            </th>
                        </tr>
                    </thead>
                    <tbody v-for="(items,index) in listDiagnosa" :key="index">
                        <tr class="tr-pri">
                            <td class="td-pri">{{ items.no }}</td>
                            <td class="td-pri">
                                <div class="column p-1">
                                    <VField>
                                        <VControl class="prime-auto">
                                            <AutoComplete v-model="item.jenisDiagnosa" :suggestions="d_JenisDiagnosa"
                                                @complete="fetchJenisDiagnosa($event)" :optionLabel="'label'"
                                                :dropdown="true" :minLength="3" :appendTo="'body'"
                                                :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                placeholder="Cari Jenis Diagnosa..." class="mt-2" />
                                        </VControl>
                                    </VField>
                                </div>
                            </td>
                            <td class="td-pri">
                                <div class="column p-1">
                                    <VField>
                                        <VControl icon="feather:bookmark">
                                            <VInput type="number" v-model="item.diagnosaDokter"
                                                placeholder="Diagnosa Dokter" class="is-rounded" />
                                        </VControl>
                                    </VField>
                                </div>
                            </td>
                            <td class="td-pri">
                                <div class="column p-1">
                                    <VField>
                                        <VControl class="prime-auto">
                                            <AutoComplete v-model="item.diagnosaIcd10" :suggestions="d_Diagnosa"
                                                @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" placeholder="Cari Diagnosa ICD 10 ..." class="mt-2" />
                                        </VControl>
                                    </VField>
                                </div>
                            </td>
                            <td class="td-pri" style="vertical-align: inherit;">
                                <div class="column">
                                    <VIconButton type="button" raised circle icon="feather:plus" @click="add(item)"
                                        color="info" v-tooltip.bubble="'Tambah '">
                                    </VIconButton>
                                    <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle
                                        icon="feather:trash" @click="remove(index)" color="danger">
                                    </VIconButton>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
import { useConfirm } from 'primevue/useconfirm'
import { useThemeColors } from '/@src/composable/useThemeColors'
import Dialog from 'primevue/dialog';
import Chart from 'primevue/chart';
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import AutoComplete from 'primevue/autocomplete';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';                   // optional
import Checkbox from 'primevue/checkbox';

useHead({
    title: 'Resume Medis - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pasien_daftar as string
let listColor: any = ref(Object.keys(useThemeColors()))

const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
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

const modalInput: any = ref(false)
const isLoading: any = ref(false)
const d_Diagnosa = ref([])
const d_DiagnosaTindakan = ref([])
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const item: any = ref({})
const dataSourceRiwayat: any = ref([])
const listData: any = ref({})
const d_Pegawai = ref([])
const d_JenisDiagnosa = ref([])
const filter: any = ref('')
const listGrid: any = ref([])
const filteredList = computed(() => {
    if (!filter.value) {
        return listGrid.value
    }

    return listGrid.value.filter((items: any) => {
        return (
            items.user_input.namalengkap.match(new RegExp(filter.value, 'i'))
        )
    })
})



// const loadRiwayat = () => {
//     dataSourceRiwayat.value.loading = true
//     useApi().get(
//         `/emr/get-resume-medis?nocm=${props.pasien.nocm}&noregistrasi=${props.registrasi.noregistrasi}`).then((response: any) => {
//             isLoading.value = false
//             for (let x = 0; x < response.data.length; x++) {
//                 const element = response.data[x]
//                 element.no = x + 1
//             }
//             dataSourceRiwayat.value = response.data
//             dataSourceRiwayat.value.loading = false

//             // input.value.kddiagnosismasuk = {
//             //     label : response.data[0].namadiagnosa2,
//             //     value : response.data[0].kddiagnosa2
//             // }
//             // input.value.kddiagnosisawal = {
//             //     label : response.data[0].namadiagnosa2,
//             //     value : response.data[0].kddiagnosa2
//             // }

//         })
// }

const add = (e:any) => {
    console.log(e)
    listDiagnosa.value.push({
        no : listDiagnosa.value[listDiagnosa.value.length - 1].no + 1,
        jenisdiagnosafk : e.jenisDiagnosa.value,
        jenisdiagnosafk : e.diagnosaDokter.value,
    })
    console.log(item.value.listDiagnosa)
}
const remove = (index: any) => {
  item.value.listDiagnosa.splice(index, 1)
}



const kembaliKeun = () => {
    modalInput.value = false
    input.value = {
        tanggal: new Date()
    }
}
const simpan = async () => {
    let formData = {
        'norec': input.value.norec != undefined ? input.value.norec : '',
        'noregistrasifk': props.registrasi.norec_apd,
        'diagnosismasuk': input.value.diagnosismasuk,
        'kddiagnosismasuk': input.value.kddiagnosismasuk.value,
        'diagnosisawal': input.value.diagnosisawal,
        'kddiagnosisawal': input.value.kddiagnosisawal.value,
        'namadiagnosatambahan': input.value.namadiagnosatambahan.label ? input.value.namadiagnosatambahan.label : null,
        'tindakanprosedur': input.value.tindakanprosedur,
        'alasandirawat': input.value.alasandirawat,
        'ringkasanriwayatpenyakit': input.value.ringkasanriwayatpenyakit,
        'tglkontrolpoli': H.formatDate(input.value.tglkontrolpoli, 'YYYY-MM-DD HH:mm') ? input.value.tglkontrolpoli : null,
        'pemeriksaanpenunjang': input.value.pemeriksaanpenunjang,
        'terapi': input.value.terapi,
        'hasilkonsultasi': input.value.hasilkonsultasi,
        'kondisiwaktukeluar': input.value.kondisiwaktukeluar,
        'instruksianjuran': input.value.instruksianjuran,
        'pengobatandilanjutkan': input.value.pengobatanlanjutan,
        'rumahsakittujuan': input.value.rumahsakittujuan,
        'pemeriksaanfisik': input.value.pemeriksaanfisik,
    }
    isLoading.value = true
    await useApi().post('/emr/simpan-resume', formData).then((r) => {
        isLoading.value = false
        loadRiwayat()
    }).catch((e: any) => {
        isLoading.value = false
    })

}
const edit = async (e: any) => {

    input.value.norec = e.norec
    input.value.diagnosisawal = e.diagnosisawal
    input.value.kddiagnosismasuk = e.kddiagnosismasuk
    input.value.kddiagnosisawal = e.kddiagnosisawal
    input.value.namadiagnosatambahan = e.namadiagnosatambahan
    input.value.tindakanprosedur = e.tindakanprosedur
    input.value.alasandirawat = e.alasandirawat
    input.value.ringkasanriwayatpenyakit = e.ringkasanriwayatpenyakit
    input.value.tglkontrolpoli = e.tglkontrolpoli
    input.value.pemeriksaanpenunjang = e.pemeriksaanpenunjang
    input.value.terapi = e.terapi
    input.value.hasilkonsultasi = e.hasilkonsultasi
    input.value.kondisiwaktukeluar = e.kondisiwaktukeluar
    input.value.instruksianjuran = e.instruksianjuran
    input.value.pengobatandilanjutkan = e.pengobatandilanjutkan
    input.value.rumahsakittujuan = e.rumahsakittujuan
    input.value.pemeriksaanfisik = e.pemeriksaanfisik

    modalInput.value = true
}

const hapus = async (e: any) => {
    e.loadingHapus = true
    await useApi().post(
        `/emr/hapus-resume-medis`, {
        'norec': e.norec,
        // 'noregistrasi': props.registrasi.noregistrasi,
        // 'nocm': props.pasien.nocm,
        // 'namapasien': props.pasien.namapasien,
    }).then((response: any) => {
        e.loadingHapus = false
        loadRiwayat()
    })
}
const fetchPegawai = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Pegawai.value = response
    })
}
const fetchDiagnosa = async (filter: any) => {
    let query = filter ? `&query=${filter.query}&limit=10` : ''
    const response = await useApi().get(
        `/emr/dropdown/diagnosa_m?select=kddiagnosa,namadiagnosa&param_search=kddiagnosa${query}`)
    d_Diagnosa.value = response
}
const fetchDiagnosaTindakan = async (filter: any) => {
    let query = filter ? `&query=${filter.query}&limit=10` : ''
    const response = await useApi().get(
        `/emr/dropdown/diagnosatindakan_m?select=kddiagnosatindakan,namadiagnosatindakan&param_search=kddiagnosatindakan${query}`)
    d_DiagnosaTindakan.value = response
}

const fetchJenisDiagnosa = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/jenisdiagnosa_m?select=id,jenisdiagnosa&param_search=jenisdiagnosa&query=${filter.query}&limit=10`)
    d_JenisDiagnosa.value = response
}

const print = () => {
    H.printBlade(`report/resum-medis?pdf=false`)
}

// loadMaster()
// loadRiwayat()
</script>
<style  lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/custom/timeline-css';

.table-pri {
    width: 100% !important;
    border-collapse: collapse !important;
}

.table-pri,
.tr-pri,
.th-pri,
.td-pri {
    border: 1.6px solid black !important;
}

.th-pri,
.td-pri {
    padding: 8px !important;
}

.list-widget {
    @include vuero-l-card;

    &.is-straight {
        @include vuero-s-card;
    }

    ul {
        li {
            &:not(:last-child) {
                margin-bottom: 12px;
            }

            a {
                font-family: var(--font);
                display: flex;
                justify-content: space-between;
                color: var(--light-text);

                &:hover,
                &:focus {
                    color: var(--primary);
                }
            }
        }
    }
}

.is-dark {
    .list-widget {
        @include vuero-card--dark;

        ul {
            li {
                a {
                    &:hover {
                        color: var(--primary);
                    }
                }
            }
        }
    }
}

.widget-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;

    .left {
        display: flex;
        align-items: center;
    }

    .center {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .right {
        display: flex;
        align-items: center;
        justify-content: flex-end;

        .tag {
            font-family: var(--font);
        }

        .right-icon {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 32px;
            width: 32px;
            min-width: 32px;
            border-radius: var(--radius-rounded);
            color: var(--light-text-light-12);
            transition: all 0.3s; // transition-all test

            &.has-indicator {
                &::after {
                    content: '';
                    position: absolute;
                    top: 3px;
                    right: 4px;
                    height: 10px;
                    width: 10px;
                    border-radius: var(--radius-rounded);
                    background: var(--secondary);
                    border: 1.8px solid var(--white);
                }
            }

            svg {
                height: 18px;
                width: 18px;
                transition: stroke 0.3s;
            }
        }
    }

    h3 {
        font-family: var(--font-alt);
        font-size: 0.9rem;
        color: var(--dark-text);
        font-weight: 600;

        &.is-bigger {
            font-size: 1rem;
        }
    }

    .action-icon {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 32px;
        width: 32px;
        min-width: 32px;
        border-radius: var(--radius-rounded);
        color: var(--light-text-light-12);
        transition: all 0.3s; // transition-all test

        svg {
            height: 18px;
            width: 18px;
            transition: stroke 0.3s;
        }
    }
}

.is-dark {
    .widget-toolbar {
        h3 {
            color: var(--dark-dark-text);
        }

        .right {
            .right-icon {
                &.has-indicator {
                    &::after {
                        border-color: var(--dark-sidebar-light-6);
                    }
                }
            }
        }
    }
}

small.is-tanggal {
    color: var(--light-text);
}

.is-dark-text {
    color: var(--light-text);
}

.timeline-wrapper .timeline-wrapper-inner .timeline-container .timeline-item[data-v-4206d2a0]::before {
    content: none;
}

.box-text-1 {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-left: 12px;
    flex-grow: 2;

    .meta-text {
        line-height: 1.2;

        p {
            color: var(--light-text-dark-10);

            span {
                font-family: var(--font-alt);
                color: var(--dark-text);
                font-weight: 600;
            }

            a {
                color: var(--primary);
            }

            .tag {
                position: relative;
                top: -1px;
                font-weight: 500;
                line-height: 1.8;
                height: 1.8em;
                margin: 0 2px;
            }
        }

        >span {
            color: var(--light-text);
            font-size: 0.9rem;
        }
    }
}

.box-end {
    margin-left: auto;

    .v-avatar {
        margin: 0 2px;
    }
}

.cc {
    text-overflow: ellipsis;
    text-wrap: wrap;
}
</style>