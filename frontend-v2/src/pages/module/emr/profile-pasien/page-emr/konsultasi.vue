<template>
    <section>
        <div class="columns is-multiline">
            <div class="column is-12">
                <div class="search-widget">
                    <div class="field">
                        <div class="columns is-multiline">
                            <div class="column is-2">
                                <VButton rounded icon="feather:plus" raised bold @click="add()" color="success" outlined
                                    :loading="isLoading" class="mr-2">Tambah </VButton>
                            </div>
                            <div class="column">
                                <VDatePicker v-model="item.periode" is-range color="pink" trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField addons>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                            </VControl>
                                            <VControl>
                                                <VButton static icon="feather:arrow-right" />
                                            </VControl>
                                            <VControl subcontrol icon="feather:calendar">
                                                <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </div>
                            <div class="column is-6">
                                <div class="control">
                                    <input type="text" v-model="filter" class="input" placeholder="Cari..." />
                                    <button class="searcv-button" type="button" :loading="isLoading"
                                        @click="loadRiwayat">
                                        <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <VCard>
                                    <DataTable :value="dataSourcefiltered" :paginator="true" :rows="10"
                                        :rowsPerPageOptions="[5, 10, 25]" class="p-datatable-customers"
                                        filterDisplay="menu"
                                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                                        :scrollable="true" :loading="isLoading" dataKey="norec">

                                        <Column :exportable="false" header="#" :style="{ width: '180px' }">
                                            <template #body="slotProps">
                                                <VIconButton type="button" icon="pi pi-pencil" class="mr-2" color="info"
                                                    circle outlined raised v-tooltip.top="'Edit '"
                                                    @click="edit(slotProps.data)">
                                                </VIconButton>
                                                <VIconButton type="button" icon="fas fa-trash" class="mr-2"
                                                    color="danger" circle outlined raised v-tooltip.top="'Hapus '"
                                                    @click="hapus(slotProps.data)"
                                                    :loading="slotProps.data.loadingHapus">
                                                </VIconButton>
                                                <VIconButton type="button" icon="fas fa-arrow-right" class="mr-2"
                                                    color="success" circle outlined raised v-tooltip.top="'Jawab '"
                                                    @click="jawab(slotProps.data)" style="display: none !important">
                                                </VIconButton>
                                            </template>
                                        </Column>
                                        <Column field="no" header="No" :style="{ width: '40px' }"> </Column>
                                        <Column field="tglorder" header="Tanggal" style="width:150px" :sortable="true">

                                        </Column>
                                        <Column field="ruanganasal" header="Ruang Asal" style="width:150px"></Column>
                                        <Column field="ruangantujuan" header="Ruang Tujuan" :sortable="true"
                                            style="width:150px">
                                        </Column>
                                        <!-- <Column field="namalengkap" header="Dokter" style="width:200px"></Column> -->
                                    </DataTable>
                                </VCard>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <Dialog v-model:visible="modalInput" modal header="Konsultasi" :style="{ width: '60vw' }" size="large">
            <div class="columns is-multiline">
                <div class="column is-4">
                    <VDatePicker class="pt-0 pb-0 pl-0" v-model="input.tanggal" color="green" trim-weeks
                        mode="dateTime">
                        <template #default="{ inputValue, inputEvents }" class="pb-0">
                            <VField>
                                <VLabel class="required-field">Tanggal</VLabel>
                                <VControl icon="feather:calendar">
                                    <VInput type="text" placeholder="Select a date" :value="inputValue"
                                        v-on="inputEvents" class="is-rounded" :disabled="disabledJawab" />
                                </VControl>
                            </VField>
                        </template>
                    </VDatePicker>
                </div>
                <div class="column is-4">
                    <VField class="is-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                        <VLabel class="required-field">Ruang Asal</VLabel>
                        <VControl icon="fas fa-bookmark" fullwidth class="prime-auto-select">
                            <Dropdown v-model="input.ruanganasal" :options="d_Ruangan" :optionLabel="'label'"
                                class="is-rounded" placeholder="Ruang Asal" style="width: 100%;" :filter="true"
                                showClear :disabled="disabledJawab" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField class="is-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                        <VLabel class="required-field">Ruangan Tujuan</VLabel>
                        <VControl icon="fas fa-bookmark" fullwidth class="prime-auto-select">
                            <Dropdown v-model="input.ruangantujuan" :options="d_Ruangan" :optionLabel="'label'"
                                class="is-rounded" placeholder="Ruang Tujuan" style="width: 100%;" :filter="true"
                                :disabled="disabledJawab" showClear @change="changeRuanganTujuan($event)" />
                        </VControl>
                    </VField>
                </div>
                <!-- <div class="column is-3">
                    <VField class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                        <VLabel class="required-field">Kelas</VLabel>
                        <VControl icon="fas fa-bookmark" fullwidth class="prime-auto-select">
                            <Dropdown v-model="input.kelas" :options="d_Kelas" :optionLabel="'label'"
                                class="is-rounded" placeholder="Kelas" style="width: 100%;" :filter="true"
                                :disabled="disabledJawab" showClear />
                        </VControl>
                    </VField>
                </div> -->
                <div class="column is-3" style="display: none !important">
                    <span style="font-weight: 500;">Kelas</span>
                    <VField class="is-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                        <VControl icon="feather:search">
                            <AutoComplete v-model="input.kelas" :suggestions="d_Kelas" @complete="fetchKelas($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik Nama Kelas"
                                class="is-rounded" />
                        </VControl>
                    </VField>
                </div>

                <!-- <div class="column is-3">
                    <VField class="is-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                        <VLabel>Dokter/Pegawai Medis </VLabel>
                        <VControl icon="fas fa-bookmark" fullwidth class="prime-auto-select">
                            <Multiselect mode="single" v-model="input.dokter" :options="d_Dokter"
                                placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off" />
                            <Dropdown v-model="input.dokter" :options="d_Dokter" :optionLabel="'label'"
                                placeholder="Dokter" style="width: 100%;" :filter="true" showClear
                                :disabled="disabledJawab" />
                            <AutoComplete v-model="input.dokter" :suggestions="d_Dokter"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Petugas ..."
                                class="mt-2 is-rounded" :disabled="disabledJawab" />
                        </VControl>
                    </VField>
                </div> -->
                <div class="column is-2" style="margin-top: 20px; display: none !important">
                    <VField>
                        <VControl>
                            <VSwitchBlock v-model="input.rawatBersama" label="Rawat Bersama" color="danger"
                                :disabled="disabledJawab" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2" style="margin-top: 20px; display: none !important">
                    <VField>
                        <VControl>
                            <VSwitchBlock v-model="input.konsultasi" label="Konsultasi" color="danger"
                                :disabled="disabledJawab" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4" style="display: none !important">
                    <VField>
                        <VLabel>Lain-lain</VLabel>
                        <VControl icon="feather:bookmark">
                            <input v-model="input.lainlain" type="text" class="input is-rounded"
                                placeholder="Lain-lain " :disabled="disabledJawab" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12" style="display: none !important">
                    <VField>
                        <VLabel>Keterangan</VLabel>
                        <VControl>
                            <VTextarea v-model="input.keterangan" rows="3" placeholder="Keterangan"
                                :disabled="disabledJawab">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12" v-if="disabledJawab" style="display: none !important">
                    <VField>
                        <VLabel>Jawaban</VLabel>
                        <VControl>
                            <VTextarea v-model="input.jawaban" rows="3" placeholder="Jawaban">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>
            </div>
            <template #footer>
                <VButton icon="feather:refresh-cw rem-100" light dark-outlined @click="kembaliKeun()">
                    Batal
                </VButton>
                <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                    @click="simpan()"> Simpan
                </VButton>
            </template>
        </Dialog>
    </section>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import FileUpload from 'primevue/fileupload';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
// import { Notification } from '/@src/models/notification'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pasien_daftar as string
let NOREC = ''

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
useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const filePasien: any = ref()
const modalInput: any = ref(false)
const isLoading: any = ref(false)
const item: any = ref({
    periode: reactive({
        start: new Date(),
        end: new Date(),
    }),
})
const input: any = reactive({ tanggal: new Date() })
const d_Ruangan: any = ref([])
const d_Dokter: any = ref([])
const d_Kelas: any = ref([])
const filter: any = ref('')
const dataSource: any = ref([])
const isClosedPasien: any = ref(false);
const disabledJawab = ref(false)
const dataSourcefiltered = computed(() => {
    if (!filter.value) {
        return dataSource.value
    }
    return dataSource.value.filter((items: any) => {
        return (
            // items.ruanganasal.match(new RegExp(filter.value, 'i')),
            items.ruangantujuan.match(new RegExp(filter.value, 'i'))
            // items.namalengkap.match(new RegExp(filter.value, 'i'))
        )
    })
})

const loadDrop = async () => {
    d_Ruangan.value = await useApi().get(`emr/dropdown/custom/get-ruangan`);
    d_Kelas.value = await useApi().get(`emr/dropdown/kelas_m?select=id,namakelas`)
}

const fetchPegawai = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&limit=10`
    ).then((response) => {
        d_Dokter.value = response
    })
}
const loadRiwayat = () => {
    isLoading.value = true
    let dari = `&tglAwal=${H.formatDate(item.value.periode.start, 'YYYY-MM-DD')}`
    let sampai = `&tglAkhir=${H.formatDate(item.value.periode.end, 'YYYY-MM-DD')}`
    useApi().get(
        `/emr/get-order-konsul?nocm=${props.pasien.nocm}&noregistrasi=${props.registrasi.noregistrasi}${dari}${sampai}`).then((response: any) => {
            isLoading.value = false
            for (let x = 0; x < response.data.length; x++) {
                const element = response.data[x];
                element.no = x + 1
                element.loadingnsu = false
            }
            dataSource.value = response.data
        })
}

const add = async () => {
    disabledJawab.value = false
    isLoading.value = true;
    input.tanggal = new Date()
    NOREC = ''
    d_Ruangan.value.forEach((element) => {
        if (element.value == props.registrasi.objectruanganlastfk) {
            input.ruanganasal = element
        }
    });
    await fetchKelas({ query: 'NON KELAS' })
    if (d_Kelas.value.length) {
        input.kelas = d_Kelas.value[0]
    }
    isLoading.value = false;
    modalInput.value = true
}
const onSelect = async (filez: any) => {
    const file = filez.files[0];
    if (file.size > 1000000) {
        H.alert('error', 'Maksimal file size adalah 1 MB')
        return
    }
    if (file.type != "application/pdf" && (file.type.indexOf('image/') > -1) == false) {
        H.alert('error', 'File yang diizinkan dalam bentuk format PDF/Image')
        return;
    }
    filePasien.value = file
}
const kembaliKeun = () => {
    modalInput.value = false
    input = {
        tanggal: new Date()
    }
}
async function statusClosingPasien(key: any) {
    const response = await useApi().get(`general/get-status-close?key=${key}`);
    console.log("SCPasien", response)
    let closingPelayanaData = {
        key: key,
        status: response.status,
    };
    H.cacheHelper().set('status_closing', closingPelayanaData);
    if (response.status == true) {
        isClosedPasien.value = true;
        H.alert("warning", 'Pelayanan yang sudah di Closing tidak bisa di ubah !');
        return;
    }
}
const simpan = async () => {
    if(isClosedPasien.value == true){
        H.alert("warning", 'Pelayanan yang sudah di Closing tidak bisa di ubah !');
        return;
    }
    if (!input.tanggal) {
        H.alert('error', 'Tanggal harus di isi')
        return
    }
    if (!input.ruanganasal) {
        H.alert('error', 'Ruang Asal harus di isi')
        return
    }
    if (!input.ruangantujuan) {
        H.alert('error', 'Ruang Tujuan harus di isi')
        return
    }
    // if (!input.dokter) {
    //     H.alert('error', 'Dokter harus di isi')
    //     return
    // }
    if (!input.kelas) {
        H.alert('error', 'Kelas Konsultasi Harus di isi')
        return
    }
    if (input.ruangantujuan.objectdepartemenfk == 16) {
        H.alert('error', 'Silahkan transfer melalui menu pindah kamar / mutasi rawat inap');
        return;
    }

    let object = {
        "norec_pd": props.registrasi.norec_pd,
        "asalRujukanfk": input.ruanganasal.objectdepartemenfk == 16 ? 30 : 23, //? 30 Rawat Inap, 23 Poliklinik
        "norec": NOREC,
        //"noAntrian": dataSource.value.length + 1,
        "dokterfk": input.dokter,
        "tanggalKonsul": input.tanggal,
        "objectruanganasalfk": input.ruanganasal.value,
        "objectruangantujuan": input.ruangantujuan.value,
        "kelasfk": input.kelas.value,
    }
    isLoading.value = true
    await useApi().post('registrasi/simpan-pasien-konsul', object).then((response) => {
        isLoading.value = false
        modalInput.value = false
        loadRiwayat()
        sendNotification(response)
        // modalInput.value = true
    }).catch((err) => {
        isLoading.value = false
    })

}

const changeRuanganTujuan = (e: any) => {
    useApi().get(`sysadmin/master-jadwal-dokter?objectruanganfk=${e.value.value}&tanggal=${H.formatDate(new Date(), 'YYYY-MM-DD')}`).then((response) => {
        d_Dokter.value = response.data.map((e: any) => { return { label: e.namalengkap, value: e.objectpegawaifk, default: e } })
    })
}

const edit = async (e: any) => {
    disabledJawab.value = false
    NOREC = e.norec
    input.tanggal = new Date(e.tglorder)
    input.kelas = { value: 6, label: 'NON KELAS' }
    console.log(e)
    d_Ruangan.value.forEach((element: any) => {
        if (element.value == e.objectruanganfk) {
            input.ruanganasal = element
        }
        if (element.value == e.objectruangantujuanfk) {
            input.ruangantujuan = element
        }
    });

    d_Dokter.value.forEach((element: any) => {
        if (element.value == e.pegawaifk) {
            input.dokter = element
        }
    });
    input.konsultasi = e.konsultasi ? e.konsultasi : false
    input.lainlain = e.lainlain
    input.rawatBersama = e.rawatbersama ? e.rawatbersama : false
    input.keterangan = e.keteranganorder
    input.jawaban = e.keteranganlainnya

    modalInput.value = true
}
const jawab = async (e: any) => {
    edit(e)
    disabledJawab.value = true
}
const hapus = async (e: any) => {
    e.loadingHapus = true

    await useApi().post(`/emr/hapus-order-konsul`, {
        'norec': e.norec,
        'noregistrasi': props.registrasi.noregistrasi,
        'ruangantujuan': e.ruangantujuan,
        'nocm': props.pasien.nocm,
        'namapasien': props.pasien.namapasien,
    }).then((response: any) => {
        e.loadingHapus = false
        loadRiwayat()
    })
}
const fetchKelas = async (filter: any) => {
    await useApi().get(`emr/dropdown/kelas_m?select=id,namakelas&param_search=namakelas&query=${filter.query}&limit=10`).then((response) => {
        d_Kelas.value = response
    })
}
const sendNotification = (e) => {
    let ruanganAsal = ''
    let ruanganTujuan = ''
    d_Ruangan.value.forEach((element: any) => {
        if (element.value == input.ruanganasal.value) {
            ruanganAsal = element.label
        }
        if (element.value == input.ruangantujuan.value) {
            ruanganTujuan = element.label
        }
    });

    let body = {
        norec: e.data.norec,
        judul: 'Order Konsul #' + e.data.noorder,
        jenis: 'Konsultasi',
        pesanNotifikasi: `Pasien DiKonsultasikan ke ${ruanganTujuan}`,
        idRuanganAsal: input.ruanganasal.value,
        idRuanganTujuan: input.ruangantujuan.value,
        ruanganAsal: ruanganAsal,
        ruanganTujuan: ruanganTujuan,
        kelompokUser: null,
        idKelompokUser: null,
        idPegawai: input.dokter.value,//H.pegawaiLogin().id,
        namapegawai: input.dokter.label,//H.pegawaiLogin().id,
        dataArray: [],
        urlForm: 'module-registrasi-daftar-konsultasi',
        params: null,
        group: 'pegawai',
        namaFungsiFrontEnd: null,
        tgl: e.data.tglorder,
        tgl_string: H.formatDateIndoSimple(e.data.tglorder),
    }
    H.sendSocket("sendNotification", body);
}

onMounted(async() => {
    await statusClosingPasien(NOREC_PD);
    loadDrop()
    loadRiwayat()
    fetchPegawai()
});
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

.tile-grid {
    .columns {
        margin-left: -0.5rem !important;
        margin-right: -0.5rem !important;
        margin-top: -0.5rem !important;
    }

    .column {
        padding: 0.5rem !important;
    }
}

.is-dark {
    .tile-grid {
        .tile-grid-item {
            @include vuero-card--dark;
        }
    }
}

.tile-grid-v1 {
    .tile-grid-item {
        @include vuero-s-card;

        border-radius: 14px;
        padding: 16px;

        .tile-grid-item-inner {
            display: flex;
            align-items: center;

            .meta {
                margin-left: 10px;
                line-height: 1.2;

                span {
                    display: block;
                    font-family: var(--font);

                    &:first-child {
                        color: var(--dark-text);
                        font-family: var(--font-alt);
                        font-weight: 600;
                        font-size: 1rem;
                    }

                    &:nth-child(2) {
                        color: var(--light-text);
                        font-size: 0.9rem;
                    }
                }
            }

            .dropdown {
                position: relative;
                margin-left: auto;
            }
        }
    }
}
</style>
