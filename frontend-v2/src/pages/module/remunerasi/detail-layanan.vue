<template>
    <section>
        <div class="columns is-multiline pl-2 pr-2">
            <div class="column is-8">
                <VCard>
                    <div class="columns is-multiline c-title pt-2 mb-0">
                        <div class="column">
                            <CardCountRev icon="/images/simrs/icon-search.png" straight
                                :total="isAngka ? H.formatRupiah(item.c_total, '') : '******'" label="TOTAL"
                                @click="setFilter('')" />
                        </div>
                        <div class="column">
                            <CardCountRev icon="/images/simrs/icon-send.png" straight
                                :total="isAngka ? H.formatRupiah(item.c_jkn, '') : '******'" label="JKN"
                                @click="setFilter('jkn')" />
                        </div>
                        <div class="column">
                            <CardCountRev icon="/images/simrs/icon-registrasi.png" straight
                                :total="isAngka ? H.formatRupiah(item.c_reg, '') : '******'" label="REGULER"
                                @click="setFilter('reguler')" />
                        </div>
                        <div class="column">
                            <CardCountRev icon="/images/simrs/icon-antrian.png" straight
                                :total="isAngka ? H.formatRupiah(item.c_eks, '') : '******'" label="EXECUTIVE"
                                @click="setFilter('executive')" />
                        </div>
                    </div>
                </VCard>
            </div>
            <div class="column">
                <VCard style="padding-bottom: 0px">
                    <div class="column c-title pt-2 mb-0">
                        <label class="title-page">Pencarian</label>
                    </div>
                    <div class="column is-12 pt-3">
                        <div class="columns is-multiline">
                            <div class="column is-10">
                                <VField>
                                    <VControl class="prime-auto">
                                        <Calendar inputId="range" v-model="item.filterTgl" selectionMode="range"
                                            :manualInput="false" class="w-100 mb-4" :showIcon="true"
                                            date-format="dd-mm-yy" />
                                    </VControl>
                                </VField>
                                <!-- <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField addons>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                            </VControl>
                                            <VControl>
                                                <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i>
                                                </VButton>
                                            </VControl>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker> -->
                            </div>
                            <!-- <div class="column is-2">
                            <VField class=" is-rounded-select is-autocomplete-select">

                                <VControl icon="feather:search" class="prime-auto">
                                    <Dropdown v-model="item.jenis" :options="d_jenis" :optionLabel="'jenis'" class="is-rounded"
                                        placeholder="Jenis" :optionValue="'jenis'" style="width: 100%;" :filter="true"
                                        appendTo="body" showClear />
                                </VControl>
                            </VField>
                        </div> -->
                            <div class="column btn-search pt-0">
                                <VIconButton color="success" icon="fas fa-search" @click="fetchData"
                                    :loading="loadSearch" />
                            </div>
                        </div>
                    </div>
                </VCard>
            </div>
        </div>

        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 mb-0">
                    <div class="column is-10 p-0">
                        <label class="title-page">Jasa Layanan Dokter {{ NAMA_DOKTER != undefined ? NAMA_DOKTER
                            : H.namaPegawai() }} </label>
                    </div>
                </div>

                <div class="column is-12">

                    <div class="columns is-multiline">
                        <div class="column is-2">
                            <VControl icon="feather:search">
                                <input v-model="filters" class="input custom-text-filter" placeholder="Search..." />
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <VField class=" is-rounded-select is-autocomplete-select">
                                <VControl icon="feather:search" class="prime-auto">
                                    <Dropdown v-model="item.namakegiatan" :options="d_tindakan"
                                        :optionLabel="'kegiatankelompok'" class="is-rounded" placeholder="Kegiatan"
                                        :optionValue="'kegiatankelompok'" style="width: 100%;" :filter="true"
                                        appendTo="body" showClear @change="setFilter2(item.namakegiatan)" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField class=" is-rounded-select is-autocomplete-select">
                                <VControl icon="feather:search" class="prime-auto">
                                    <Dropdown v-model="item.kunjinstalasi" :options="d_instalasi"
                                        :optionLabel="'kunjinstalasi'" class="is-rounded" placeholder="Instalasi"
                                        :optionValue="'kunjinstalasi'" style="width: 100%;" :filter="true" appendTo="body"
                                        showClear @change="setFilter2(item.kunjinstalasi)" />
                                </VControl>
                            </VField>
                        </div>



                        <div class="column">
                            <VControl class="is-pulled-right">
                                <VSwitchBlock v-model="isAngka" label="Menampilkan Angka" color="danger" />
                            </VControl>
                        </div>
                    </div>
                    <!-- <VControl icon="feather:search">
                            <Multiselect mode="single" v-model="item.jenis" :options="d_Jenis" placeholder="Pilih Dokter"
                                :searchable="true" />
                        </VControl> -->

                    <DataTable :value="dataSourcefiltered" :rows="10" :rowsPerPageOptions="[5, 10, 15, 30, 50, 100, 1000]"
                        :loading="loadSearch" class="p-datatable-sm" breakpoint="960px" selectionMode="single"
                        sortMode="multiple" v-model:expanded-rows="expandedRows" tableStyle="min-width: 30rem" showGridlines
                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                        <template #empty style="text-align: center;"> No data found. </template>
                        <Column field="no" header="No" />
                        <Column field="namapasien" header="Nama Pasien" />
                        <Column field="norm" header="No RM" />
                        <Column field="tgl" header="Tanggal" />
                        <Column field="ruangan" header="Ruangan" />
                        <Column field="jenis" header="Jenis" />
                        <Column field="namakegiatan" header="Nama Kegiatan" />
                        <Column field="kegiatankelompok" header="Kegiatan Kelompok" />
                        <Column field="kelompokstafmedis" header="KSM" />
                        <Column field="kunjinstalasi" header="Instalasi" />
                        <Column field="jasa" header="Jasa">
                            <template #body="slotProps">
                                {{ isAngka ? H.formatRupiah(slotProps.data.jasa, '') : '******' }}
                            </template>
                        </Column>

                    </DataTable>
                </div>
                <div class="column is-12">
                    <Divider />
                    <span style="font-size:12px"> Catatan :</span><br>
                    <!-- -e remun ini masih dalam tahap pengembangan simrs. dalam masa ujicoba ini apabila ada ketidakcocokan
                    data maka segera disampaikan kepada : -Dokter Mala 085885979844
                    -Dokter Triana 081280243890

                    -apabila ada ketidaksesuaian data maka akan dilakukan perhitungan ulang -->
                    <span style="font-size:12px">• E-Remun ini masih dalam tahap pengembangan simrs. dalam masa ujicoba ini
                        apabila ada ketidakcocokan data maka segera disampaikan kepada : </span><br>
                    <!-- <span class="pl-2" style="font-size:12px">- Dokter Mala 085885979844</span><br> -->
                    <span class="pl-2" style="font-size:12px">- Tim Developer Transmedic</span><br>
                    <!-- <span class="pl-2" style="font-size:12px">- Dokter Triana 081280243890</span><br> -->
                    <span style="font-size:12px">• Apabila ada ketidaksesuaian data maka akan dilakukan
                        perhitungan ulang.</span><br>

                    <!-- <span style="font-size:12px">• ⁠Perhitungan remunerasi ini apabila terdapat kesalahan, dapat dilakukan
                        perbaikan sebagaimana
                        mestinya.</span><br> -->

                    <!-- <span style="font-size:12px">• ⁠Koreksi atau Informasi lebih lanjut hub : Official ISIMRS Help Desk Hp :
                        +6285885979844</span> -->


                </div>
            </VCard>
        </div>

    </section>
</template>

<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive, computed } from 'vue'
import { useRouter, useRoute, RouterLink } from 'vue-router';
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import DataTable from 'primevue/datatable'
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import * as H from '/@src/utils/appHelper'
import Dialog from 'primevue/dialog';
import Column from 'primevue/column'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Dropdown from 'primevue/dropdown';
import Divider from 'primevue/divider';
// app.directive('tooltip', Tooltip);
import Calendar from 'primevue/calendar';
useHead({
    title: 'Detail Jasa Layanan Dokter - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle('E-Remun')
useViewWrapper().setFullWidth(true)
let NAMA_DOKTER = useRoute().query.dok as string
let DARI = useRoute().query.dari as string
let SAMPAI = useRoute().query.sampai as string
const d_jenis: any = ref([
    { jenis: 'JKN' },
    { jenis: 'REGULER' },
    { jenis: 'EXECUTIVE' }
])
const d_instalasi: any = ref([
    { kunjinstalasi: 'Instalasi Rawat Jalan', },
    { kunjinstalasi: 'Instalasi Rawat Inap', },
    { kunjinstalasi: 'BOUGENVILLE DIAG. CENTER', },
    { kunjinstalasi: 'Cath Lab', },
    { kunjinstalasi: 'Hemodialisa', },
    { kunjinstalasi: 'Instalasi Bedah Sentral', },
    { kunjinstalasi: 'Instalasi Griya Husada', },
    { kunjinstalasi: 'Instalasi Radiologi Terpadu' },
    { kunjinstalasi: 'Instalasi Laboratorium', },
    { kunjinstalasi: 'Instalasi Rawat Darurat', },
    { kunjinstalasi: 'Instalasi Rehabilitasi Medik', },
    { kunjinstalasi: 'Khemoterapi', },

])
const d_tindakan: any = ref([])
const item: any = ref({
    aktif: true,
    isKK: false,
    filterTgl: [
        DARI ? new Date(DARI) : new Date('2023-11-01'),
        SAMPAI ? new Date(SAMPAI) : new Date('2023-11-30'),
    ],
    c_total: 0,
    c_jkn: 0,
    c_reg: 0,
    c_eks: 0,
})

const router = useRouter()
const confirm = useConfirm()
const activeTab = ref(0);
const dataSource: any = ref([])
const detailResep: any = ref([])
const op = ref();
const selected: any = ref({})
const modalDetail: any = ref(false)
const modalEdit: any = ref(false)
const expandedRows = ref();
let d_KelompokPasien: any = ref([])
let loadSearch: any = ref(false)
let isLoading: any = ref(false)
let isAngka: any = ref(false)

const filters = ref('')
const dataSourcefiltered = computed(() => {
    if (!filters.value) {
        return dataSource.value
    }
    return dataSource.value.filter((items: any) => {
        return (
            items.namapasien.match(new RegExp(filters.value, 'i')) ||
            items.norm.match(new RegExp(filters.value, 'i')) ||
            items.jenis.match(new RegExp(filters.value, 'i')) ||
            items.kegiatankelompok.match(new RegExp(filters.value, 'i')) ||
            items.kelompokstafmedis.match(new RegExp(filters.value, 'i')) ||
            items.kunjinstalasi.match(new RegExp(filters.value, 'i'))

        )
    })
    // return dataSource.value.filter((items: any) => {
    //     return (items.namapasien.match(new RegExp(filters.value, 'i')))
    // })
})

const setFilter = (e: any) => {
    filters.value = e
}

const jenis = ref([
    'JKN', ''
])

const fetchData = async () => {

    let namadokter = NAMA_DOKTER ? NAMA_DOKTER : H.namaPegawai()
    let jenis = item.value.jenis ? item.value.jenis : ''
    let namakegiatan = item.value.namakegiatan ? item.value.namakegiatan : ''
    let kunjinstalasi = item.value.kunjinstalasi ? item.value.kunjinstalasi : ''
    loadSearch.value = true
    item.value.c_total = 0
    item.value.c_jkn = 0
    item.value.c_reg = 0
    item.value.c_eks = 0
    let dari = ''
    let sampai = ''
    if (item.value.filterTgl[0]) {
        dari = H.formatDate(item.value.filterTgl[0], 'YYYY-MM-DD 00:00')
    }
    if (item.value.filterTgl[1]) {
        sampai = H.formatDate(item.value.filterTgl[1], 'YYYY-MM-DD 23:59')
    } else {
        sampai = H.formatDate(item.value.filterTgl[0], 'YYYY-MM-DD 23:59')
    }
    await useApi().get(`remunerasi/get-data-dokter?dari=${dari}&sampai=${sampai}&namadokter=${namadokter}&jenis=${jenis}&namakegiatan=${namakegiatan}&kunjinstalasi=${kunjinstalasi}`).then((response) => {
        loadSearch.value = false
        response.forEach((element: any, i: any) => {
            element.no = i + 1
            element.tgl = H.formatDateToLocalString(element.tanggal)
            item.value.c_total = parseFloat(element.jasa) + item.value.c_total
            if (element.jenis == 'JKN')
                item.value.c_jkn = parseFloat(element.jasa) + item.value.c_jkn
            if (element.jenis == 'REGULER')
                item.value.c_reg = parseFloat(element.jasa) + item.value.c_reg
            if (element.jenis == 'EXECUTIVE')
                item.value.c_eks = parseFloat(element.jasa) + item.value.c_eks
        });
        dataSource.value = response
    });
}


const fetchDrop = async () => {
    let dari = ''
    let sampai = ''
    if (item.value.filterTgl[0]) {
        dari = H.formatDate(item.value.filterTgl[0], 'YYYY-MM-DD 00:00')
    }
    if (item.value.filterTgl[1]) {
        sampai = H.formatDate(item.value.filterTgl[1], 'YYYY-MM-DD 23:59')
    } else {
        sampai = H.formatDate(item.value.filterTgl[0], 'YYYY-MM-DD 23:59')
    }
    let namadokter = NAMA_DOKTER ? NAMA_DOKTER : H.namaPegawai()
    let jenis = item.value.jenis ? item.value.jenis : ''
    let namakegiatan = item.value.namakegiatan ? item.value.namakegiatan : ''
    d_tindakan.value = await useApi().get(`remunerasi/get-data-dokter-tindakan?dari=${dari}&sampai=${sampai}&namadokter=${namadokter}`)
}
const setFilter2 = (e: any) => {
    fetchData()
}
fetchData()
fetchDrop()
// const fetchPagu = async () => {

//     // let namapegawai = item.value.search ? `? search = ${ item.value.search }` : ''
//     let kpId = item.value.qkpasien ? `& kpId=${ item.value.qkpasien }` : ''
//     await useApi().get(`remunerasi / get - pagu - remunerasi ? tglpelayanan = ${ H.formatDate(item.value.tglPelayanan, 'YYYY-MM-DD') }${ kpId }`).then((response) => {
//         dataSource.value = response
//     })
// }

// const fetchKelompokPasien = async () => {
//     useApi().get('remunerasi/get-combo-idx').then((response) => {
//         d_KelompokPasien.value = response.kelompokpasien.map((e: any) => {
//             return { label: e.kelompokpasien, value: e.id }
//         })
//     })
// }


// const klikTab = (e: any) => {
//     activeTab.value = e.index
// }


// fetchKelompokPasien()
// fetchPagu()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';

.title-page {
    position: relative;
    font-size: 17px;
    display: block;
    margin-bottom: 3px;
    margin-top: 0px;
    font-weight: 600;
}

.btn-search {
    display: flex;
    align-items: center;
}

.control.has-icon.prime-auto .form-icon {
    top: 0px;
}
</style>
