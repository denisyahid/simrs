<template>
    <section>
        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 mb-0">
                    <div class="column is-10 p-0">
                        <label for="">LAPORAN REGISTRASI RAWAT INAP</label>
                    </div>
                </div>

                <VPlaceload height="20rem" width="100%" class="mx-2 mt-4" v-if="loadData" />
                <DataTable v-else :rows="50" :value="dataSource"  :loading="loadSearch" :rowsPerPageOptions="[5, 10, 15, 25, 50]"
                    class="p-datatable-sm mt-4" breakpoint="960px" selectionMode="single" sortMode="multiple" showGridlines
                    tableStyle="min-width: 50rem" 
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                    <template #header>
                        <div class="columns is-multiline">
                            <div class="column is-2" style="margin-top:23px">
                                <VButton color="primary" @click="exportExcel()" outlined icon="fas fa-file-excel">
                                    Export To Excel
                                </VButton>
                            </div>
                            <div class="column is-10">
                                <div class="columns is-multiline" style="justify-content: right;">
                                    <div class="column is-4">
                                        <VField label="Periode" style="margin-bottom: 6px;" />
                                        <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField addons>
                                                    <VControl icon="feather:calendar">
                                                        <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                                    </VControl>
                                                    <VControl>
                                                        <VButton static><i class="fas fa-arrow-right"
                                                                aria-hidden="true"></i>
                                                        </VButton>
                                                    </VControl>
                                                    <VControl icon="feather:calendar">
                                                        <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </div>
                                    <div class="column is-2 pb-0" style="margin-top: -7px;">
                                        <VField label="Ruangan">
                                            <VControl class="prime-auto">
                                                <AutoComplete v-model="item.ruangan" :suggestions="d_Ruangan" :optionLabel="'label'"
                                                @complete="fetchRuangan($event)" :dropdown="true" :minLength="3" :appendTo="'body'"
                                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ruangan..." class="mt-2" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-2 pb-0" style="margin-top: -7px;">
                                        <VField label="Kelompok pasien">
                                            <VControl class="prime-auto">
                                                <AutoComplete v-model="item.kelompokpasien" :suggestions="d_KelompokPasien"
                                                :optionLabel="'label'" @complete="fetchKelompokPasien($event)" :dropdown="true" :minLength="3"
                                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                placeholder="Kelompok Pasien..." class="mt-2" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-2 pb-0" style="margin-top: -7px;">
                                        <VField label="Dokter">
                                            <VControl class="prime-auto">
                                                <AutoComplete v-model="item.dokter" :suggestions="d_Dokter"
                                                :optionLabel="'label'" @complete="fetchDokter($event)" :dropdown="true" :minLength="3"
                                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                placeholder="Nama Dokter..." class="mt-2" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-2 pb-0" style="margin-top: -7px;">
                                        <VField label="Jenis kelamin">
                                            <VControl class="prime-auto">
                                                <AutoComplete v-model="item.jeniskelamin" :suggestions="d_JK"
                                                :optionLabel="'label'" @complete="fetchJenisKelamin($event)" :dropdown="true" :minLength="3"
                                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                placeholder="Jenis Kelamin..." class="mt-2" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-2 pb-0" style="margin-top: -20px;">
                                        <VField label="Kelas">
                                            <VControl class="prime-auto">
                                                <AutoComplete v-model="item.kelasrawat" :suggestions="d_KelasAll"
                                                :optionLabel="'label'" @complete="fetchKelas($event)" :dropdown="true" :minLength="3"
                                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                placeholder="Kelas..." class="mt-2" />
                                            </VControl>
                                        </VField>
                                    </div>
                                     <div class="column is-1 btn-search mt-3" style="justify-content:center">
                                        <VIconButton color="success" icon="fas fa-search" @click="fetchData"
                                            :loading="loadSearch" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <Column header="No" bodyClass="text-center" >
                        <template #body="slotProps">
                            {{ slotProps.index + 1 }}
                        </template>
                    </Column>
                    <Column field="noregistrasi" header="NO REG" style="min-width: 150px" />
                    <Column field="nocm" header="NRM" style="min-width: 100px"/>
                    <Column field="tglregistrasi" header="TGL MRS" :sortable="true" style="min-width: 100px"/>
                    <Column field="jamregistrasi" header="Jam MRS" :sortable="true" style="min-width: 100px"/>
                    <Column field="kelas" header="Kelas Perawatan" style="min-width: 200px"/>
                    <Column field="kamar" header="No.Kamar" style="min-width: 200px"/>
                    <Column field="namaruangan" header="RUANGAN" style="min-width: 200px"/>
                    <Column field="namapasien" header="NAMA PASIEN" style="min-width: 200px"/>
                    <Column field="penanggungjawab" header="PENANGGUNG" style="min-width: 200px"/>
                    <Column field="umur" header="UMUR" style="min-width: 50px"/>
                    <Column field="jeniskelamin" header="SEX" style="min-width: 100px"/>
                    <Column field="name" header="KEWARGANEGARAAN" style="min-width: 100px"/>
                    <Column field="alamatlengkap" header="ALAMAT" style="min-width: 200px"/>
                    <Column field="statuspasien" header="KNJ" :sortable="true" style="min-width: 100px">
                    <template #body="slotProps">
                        <VTag class="ml-4" color="primary" rounded>{{ slotProps.data.statuspasien }}</VTag>
                    </template>
                    </Column>
                    <Column field="namadokter" header="DOKTER MERAWAT" style="min-width: 200px"/>
                    <Column field="status" header="Type Pasien" style="min-width: 200px"/>
                    <Column field="namarekanan" header="Rekanan" style="min-width: 200px"/>
                    <Column field="jenispeserta" header="STATUS" style="min-width: 200px"/>
                    <Column field="namaicd10primer" header="DIAGNOSA MASUK" style="min-width: 200px"/>
                    <Column field="namaicd10primer" header="DIAGNOSA AKHIR UTAMA" style="min-width: 200px"/>
                    <Column field="namaicd10sekunder" header="DIAGNOSA AKHIR SEKUNDER" style="min-width: 200px"/>
                </DataTable>
            </VCard>
        </div>
    </section>
</template>
<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive, computed } from 'vue'
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import DataTable from 'primevue/datatable'
import Dropdown from 'primevue/dropdown';
import AutoComplete from 'primevue/autocomplete';
import ColumnGroup from 'primevue/columngroup';
import Row from 'primevue/row';
import * as XLSX from "xlsx";
import * as XLSXStyle from 'xlsx-js-style';
import * as H from '/@src/utils/appHelper'
import Column from 'primevue/column'
import { useViewWrapper } from '/@src/stores/viewWrapper'

// app.directive('tooltip', Tooltip);

useHead({
    title: 'Laporan Registrasi Rawat Inap - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
    aktif: true,
    isKK: false,
    tglPelayanan: new Date(),
    filterTgl: reactive({
        start: new Date(),
        end: new Date(),
    }),
})

const confirm = useConfirm()
const dataSource: any = ref([])
const modalDetail: any = ref(false)
const modalEdit: any = ref(false)
const d_KelompokPasien: any = ref([])
const d_JK: any = ref([])
const sortBy = ref("tgljamregistrasi"); 
const sortOrder = ref("asc"); 
// let d_KelompokPasien: any = ref([])
let d_Ruangan: any = ref([])
let d_Dokter: any = ref([])
let d_KelasAll: any = ref([])
let loadSearch: any = ref(false)
let loadData: any = ref(true)
let loadSave: any = ref(false)
let isLoading: any = ref(false)

const fetchData = async () => {
    let tglAwal = H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
    let tglAkhir = H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
    let ruanganfk = item.value.ruangan ? `&ruanganId=${item.value.ruangan.value}` : ''
    let kelompokpasienfk = item.value.kelompokpasien ? `&kpid=${item.value.kelompokpasien.value}` : ''
    let dokterfk = item.value.dokter ? `&dokter=${item.value.dokter.value}` : ''
    let jeniskelaminfk = item.value.jeniskelamin ? `&jeniskelamin=${item.value.jeniskelamin.value}` : ''
    let kelasfk = item.value.kelasrawat ? `&kelas=${item.value.kelasrawat.value}` : ''

    item.value.Ttotal = 0
    loadSearch.value = true
    await useApi().get(`laporan/registrasi-ranap?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}${ruanganfk}${kelompokpasienfk}${dokterfk}${jeniskelaminfk}${kelasfk}`).then((response) => {
        dataSource.value = response.data.sort(
                (a, b) => new Date(a.tgljamregistrasi).getTime() - new Date(b.tgljamregistrasi).getTime()
            );
    })
    loadData.value = false
    loadSearch.value = false
}

const fetchRuangan = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Ruangan.value = response
  })
}

const fetchKelompokPasien = async (filter: any) => {
  await useApi().get(`emr/dropdown/kelompokpasien_m?select=id,kelompokpasien&param_search=kelompokpasien&query=${filter.query}&limit=10`
  ).then((response) => {
    d_KelompokPasien.value = response
  })
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const fetchJenisKelamin = async () => {
  const response = await useApi().get(
    `/emr/dropdown/jeniskelamin_m?select=id,jeniskelamin&param_search=&`)
  d_JK.value = response
}

const fetchKelas = async () => {
    await useApi().get(
        `emr/dropdown/kelas_m?select=id,namakelas`
    ).then((response) => {
        d_KelasAll.value = response.map((e: any) => { return { label: e.label, value: e.value, default: e } })
    })
}


const exportExcel = () => {
    // Salin dan urutkan data sesuai dengan kolom yang sedang disortir di tabel
    const sortedData = [...dataSource.value].sort((a, b) => {
        return new Date(a.tglregistrasi) - new Date(b.tglregistrasi); // Sorting ASC berdasarkan tglregistrasi
    });

    const workbook = XLSX.utils.book_new();
    const worksheet = XLSX.utils.aoa_to_sheet([
        ['Laporan Registrasi Rawat Inap'],
        [],
        ['NO','NO REG', 'NRM', 'MRS','Jam MRS', 'Kelas Perawatan','No.Kamar','RUANGAN', 'NAMA PASIEN', 'PENANGGUNG','UMUR','SEX','KEWARGANEGARAAN','ALAMAT','KNJ','DOKTER MERAWAT','Type Pasien','Rekanan','STATUS','DIAGNOSA MASUK','DIAGNOSA AKHIR UTAMA','DIAGNOSA AKHIR SEKUNDER'],
        ...sortedData.map((e: any, index: number) => [
            index + 1,
            e.noregistrasi,
            e.nocm,
            e.tglregistrasi,
            e.jamregistrasi,
            e.kelas,
            e.kamar,
            e.namaruangan,
            e.namapasien,
            e.penanggungjawab,
            e.umur,
            e.jeniskelamin,
            e.name,
            e.alamatlengkap,
            e.statuspasien,
            e.namadokter,
            e.status,
            e.namarekanan,
            e.jenispeserta,
            e.namaicd10primer,
            e.namaicd10primer,
            e.namaicd10sekunder,
        ]),
    ]);

    // Style header tetap sama dengan yang sudah Anda buat
    const headerStyle = {
        alignment: {
            horizontal: 'center',
            vertical: 'center'
        },
        font: {
            color: { rgb: 'FFFFFF' }
        },
        fill: { fgColor: { rgb: '807C7C' } }
    };

    const headerRange = XLSX.utils.decode_range(worksheet['!ref']);
    for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
        const headerCell = XLSX.utils.encode_cell({ r: 2, c: col });
        worksheet[headerCell].s = headerStyle;
    }

    const columnWidths = [10, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20];

    for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
        worksheet['!cols'] = worksheet['!cols'] || [];
        worksheet['!cols'][col] = { wch: columnWidths[col] };
    }

    const titleCell = XLSX.utils.encode_cell({ r: 0, c: 0 });
    worksheet[titleCell].s = {
        alignment: {
            horizontal: 'center',
            vertical: 'center'
        },
        font: {
            bold: true,
            sz: 18
        }
    };

    const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 14 } };
    worksheet['!merges'] = [mergeTitle];

    XLSX.utils.book_append_sheet(workbook, worksheet, 'Laporan Registrasi', true);
    XLSXStyle.writeFile(workbook, 'Laporan Registrasi Rawat Inap.xlsx');
};


const sortedData = computed(() => {
    return [...dataSource.value].sort((a, b) => {
        if (sortBy.value === "tgljamregistrasi") {
            return sortOrder.value === "asc"
                ? new Date(a.tgljamregistrasi) - new Date(b.tgljamregistrasi)
                : new Date(b.tgljamregistrasi) - new Date(a.tgljamregistrasi);
        }
        return 0;
    });
});
const updateSort = (field: string) => {
    if (sortBy.value === field) {
        sortOrder.value = sortOrder.value === "asc" ? "desc" : "asc";
    } else {
        sortBy.value = field;
        sortOrder.value = "asc";
    }
};



fetchData()

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
</style>
