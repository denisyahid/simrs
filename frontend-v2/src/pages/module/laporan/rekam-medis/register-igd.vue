<template>
    <section>
        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 mb-0">
                    <div class="column is-10 p-0">
                        <label for="">LAPORAN REGISTER IGD</label>
                    </div>
                </div>

                <VPlaceload height="20rem" width="100%" class="mx-2 mt-4" v-if="loadData" />
                <DataTable v-else :rows="20" :value="dataSource" :loading="loadSearch" :rowsPerPageOptions="[5, 10, 20, 50]"
                    class="p-datatable-sm mt-4" breakpoint="960px" selectionMode="single" sortMode="multiple" showGridlines
                    tableStyle="min-width: 30rem"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" scrollable scrollHeight="600px">
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
                                    <!-- <div class="column is-2 pb-0" style="margin-top: -7px;">
                                        <VField label="Ruangan">
                                            <VControl class="prime-auto">
                                                <AutoComplete v-model="item.ruangan" :suggestions="d_Ruangan" :optionLabel="'label'"
                                                @complete="fetchRuangan($event)" :dropdown="true" :minLength="3" :appendTo="'body'"
                                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ruangan..." class="mt-2" />
                                            </VControl>
                                        </VField>
                                    </div> -->
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
                                     <div class="column is-1 btn-search mt-3" style="justify-content:center">
                                        <VIconButton color="success" icon="fas fa-search" @click="fetchData"
                                            :loading="loadSearch" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <ColumnGroup type="header">
                        <Row>
                            <Column header="No" :rowspan="2" bodyClass="text-center">
                            </Column>
                            <Column header="Tanggal" :rowspan="2" style="min-width: 100px"></Column>
                            <Column header="Jam" :rowspan="2" style="min-width: 100px"></Column>
                            <Column header="NRM" :rowspan="2" style="min-width: 100px"></Column>
                            <Column header="Pasien" :rowspan="2" style="min-width: 200px"></Column>
                            <Column header="Alamat" :rowspan="2" style="min-width: 200px"></Column>
                            <Column header="Tipe Pasien" :rowspan="2" style="min-width: 160px"></Column>
                            <Column header="Penjamin" :rowspan="2" style="min-width: 140px"></Column>
                            <Column header="Sex" :rowspan="2" style="min-width: 100px"></Column>
                            <Column header="Umur" :rowspan="2" style="min-width: 50px"></Column>
                            <Column header="ECW" :rowspan="2" style="min-width: 50px"></Column>
                            <Column header="B/L" :rowspan="2" style="min-width: 50px"></Column>
                            <Column header="Diagnosa" :rowspan="2" style="min-width: 200px"></Column>
                            <Column header="Diagnosa II" :rowspan="2" style="min-width: 200px"></Column>
                            <Column header="Konsul Dokter" :rowspan="2" style="min-width: 300px"></Column>
                            <Column header="Kecelakaan" :colspan="3"></Column>
                            <Column header="Tindak Lanjut" :colspan="3"></Column>
                            <Column header="DOA" :rowspan="2"></Column>
                            <Column header="Kasus" :colspan="2"></Column>
                            <Column header="Dokter Jaga" :rowspan="2" style="min-width: 300px"></Column>
                            <Column header="Dirujuk Ke" :rowspan="2" style="min-width: 180px"></Column>
                            <Column header="Rujuk Dari" :rowspan="2" style="min-width: 180px"></Column>
                            <Column header="Kewarganegaraan" :rowspan="2" style="min-width: 160px"></Column>
                        </Row>
                        <Row>
                            <Column header="Lln" style="min-width: 50px"></Column>
                            <Column header="RT" style="min-width: 50px"></Column>
                            <Column header="Krj" style="min-width: 50px"></Column>
                            <Column header="Plg" style="min-width: 50px"></Column>
                            <Column header="Mrs" style="min-width: 50px"></Column>
                            <Column header="Mati" style="min-width: 50px"></Column>
                            <Column header="E" style="min-width: 50px"></Column>
                            <Column header="FE" style="min-width: 50px"></Column>
                        </Row>
                    </ColumnGroup>
                    <Column bodyStyle="text-align:center">
                        <template #body="slotProps">
                            {{ slotProps.index + 1 }}
                        </template>
                    </Column>
                    <Column bodyStyle="text-align:center" field="tglregistrasi" />
                    <Column bodyStyle="text-align:center" field="jamregistrasi"  />
                    <Column bodyStyle="text-align:center" field="nocm"  />
                    <Column bodyStyle="text-align:center" field="namapasien"  />
                    <Column bodyStyle="text-align:center" field="alamatlengkap"  />
                    <Column bodyStyle="text-align:center" field="status" />
                    <Column bodyStyle="text-align:center" field="namarekanan"  />
                    <Column bodyStyle="text-align:center" field="jeniskelamin"  />
                    <Column bodyStyle="text-align:center" field="umur"  />
                    <Column bodyStyle="text-align:center" field=""  />
                    <Column bodyStyle="text-align:center" field="statuspasien" />
                    <Column bodyStyle="text-align:center" field="diagnosa1" header="Diagnosa" />
                    <Column bodyStyle="text-align:center" field="diagnosa2" header="Diagnosa II" />
                    <Column bodyStyle="text-align:center" field="namadokter" header="Konsul Dokter" />
                    <Column bodyStyle="text-align:center" field="" />
                    <Column bodyStyle="text-align:center" field="" />
                    <Column bodyStyle="text-align:center" field="" />
                    <Column bodyStyle="text-align:center" field="plg" />
                    <Column bodyStyle="text-align:center" field="mrs" />
                    <Column bodyStyle="text-align:center" field="mati" />
                    <Column bodyStyle="text-align:center" field="doa" />
                    <Column bodyStyle="text-align:center" field="E" />
                    <Column bodyStyle="text-align:center" field="FE" />
                    <Column bodyStyle="text-align:center" field="dokterjaga" />
                    <Column bodyStyle="text-align:center" field="" />
                    <Column bodyStyle="text-align:center" field="" />
                    <Column bodyStyle="text-align:center" field="name" />
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
import Dropdown from 'primevue/dropdown';
import AutoComplete from 'primevue/autocomplete';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';  
import Row from 'primevue/row';                   
import * as XLSX from "xlsx";
import * as XLSXStyle from 'xlsx-js-style';
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'

// app.directive('tooltip', Tooltip);

useHead({
    title: 'Laporan Register IGD - ' + import.meta.env.VITE_PROJECT,
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
let loadSearch: any = ref(false)
let loadData: any = ref(true)
let loadSave: any = ref(false)
let isLoading: any = ref(false)

const fetchData = async () => {
    let tglAwal = H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD 00:00')
    let tglAkhir = H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD 23:59')
    // let ruanganfk = item.value.ruangan ? `&ruanganId=${item.value.ruangan.value}` : ''
    let kelompokpasienfk = item.value.kelompokpasien ? `&kpid=${item.value.kelompokpasien.value}` : ''
    let dokterfk = item.value.dokter ? `&dokter=${item.value.dokter.value}` : ''
    let jeniskelaminfk = item.value.jeniskelamin ? `&jeniskelamin=${item.value.jeniskelamin.value}` : ''

    item.value.Ttotal = 0
    loadSearch.value = true
    await useApi().get(`laporan/registrasi-igd?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}${kelompokpasienfk}${dokterfk}${jeniskelaminfk}`).then((response) => {
        dataSource.value = response.data.sort(
                (a, b) => new Date(a.tgljamregistrasi).getTime() - new Date(b.tgljamregistrasi).getTime()
            );
    })
    loadData.value = false
    loadSearch.value = false
}

// const fetchRuangan = async (filter: any) => {
//   await useApi().get(
//     `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
//   ).then((response) => {
//     d_Ruangan.value = response
//   })
// }

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


const exportExcel = () => {
    const workbook = XLSX.utils.book_new();
    const worksheetData = [
        ['Laporan Register IGD'], // Title
        [], // Empty row
        [
            'NO', 'Tanggal', 'Jam', 'NRM', 'Pasien', 'Alamat', 'Tipe Pasien', 
            'Penjamin', 'Sex', 'Umur', 'ECW', 'B/L', 'Diagnosa', 
            'Diagnosa II', 'Konsul Dokter', 'Kecelakaan', null, null, 
            'Tindak Lanjut', null, null, 'DOA', 'Kasus', null, 
            'Dokter Jaga', 'Dirujuk Ke', 'Rujuk Dari','Kewarganegaraan'
        ],
        [
            null, null, null, null, null, null, null, null, null, null, null, 
            null, null, null, null, 'Lln', 'RT', 'Krj', 'Plg', 'Mrs', 'Mati', 
            null, 'E', 'FE', null, null, null
        ],
        ...dataSource.value.map((e, index) => [
            index + 1, e.tglregistrasi, e.jamregistrasi, e.nocm, 
            e.namapasien, e.alamatlengkap, e.status, e.namarekanan, e.jeniskelamin, 
            e.umur, "", e.statuspasien, e.diagnosa1, e.diagnosa2, e.namadokter, "", "", "", e.plg, e.mrs, e.mati, e.doa, e.E, e.FE, e.dokterjaga, "", "",e.name
        ]),
    ];

    const worksheet = XLSX.utils.aoa_to_sheet(worksheetData);

    // Define styles
    const headerStyle = {
        alignment: { horizontal: 'center', vertical: 'center' },
        font: { color: { rgb: 'FFFFFF' }, bold: true },
        fill: { fgColor: { rgb: '807C7C' } }
    };

    const titleStyle = {
        alignment: { horizontal: 'center', vertical: 'center' },
        font: { bold: true, sz: 18 }
    };

    // Apply title style
    const titleCell = XLSX.utils.encode_cell({ r: 0, c: 0 });
    worksheet[titleCell].s = titleStyle;

    // Apply header styles
    const headerRange = XLSX.utils.decode_range(worksheet['!ref']);
    for (let row = 2; row <= 3; row++) {
        for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
            const cell = XLSX.utils.encode_cell({ r: row, c: col });
            if (worksheet[cell]) {
                worksheet[cell].s = headerStyle;
            }
        }
    }

    // Column widths
    worksheet['!cols'] = [
        { wch: 10 }, { wch: 15 }, { wch: 10 }, { wch: 10 }, { wch: 20 },
        { wch: 30 }, { wch: 15 }, { wch: 15 }, { wch: 10 }, { wch: 10 },
        { wch: 10 }, { wch: 10 }, { wch: 25 }, { wch: 25 }, { wch: 20 },
        { wch: 10 }, { wch: 10 }, { wch: 10 }, { wch: 10 }, { wch: 10 },
        { wch: 10 }, { wch: 10 }, { wch: 10 }, { wch: 10 }, { wch: 20 },
        { wch: 20 }, { wch: 20 }, { wch: 20 }
    ];

    // Merge cells
    worksheet['!merges'] = [
        { s: { r: 0, c: 0 }, e: { r: 1, c: 26 } }, // Title
        { s: { r: 2, c: 0 }, e: { r: 3, c: 0 } }, // NO
        { s: { r: 2, c: 1 }, e: { r: 3, c: 1 } }, // Tanggal
        { s: { r: 2, c: 2 }, e: { r: 3, c: 2 } }, // Jam
        { s: { r: 2, c: 3 }, e: { r: 3, c: 3 } }, // NRM
        { s: { r: 2, c: 4 }, e: { r: 3, c: 4 } }, // Pasien
        { s: { r: 2, c: 5 }, e: { r: 3, c: 5 } }, // Alamat
        { s: { r: 2, c: 6 }, e: { r: 3, c: 6 } }, // Tipe Pasien
        { s: { r: 2, c: 7 }, e: { r: 3, c: 7 } }, // Penjamin
        { s: { r: 2, c: 8 }, e: { r: 3, c: 8 } }, // Sex
        { s: { r: 2, c: 9 }, e: { r: 3, c: 9 } }, // Umur
        { s: { r: 2, c: 10 }, e: { r: 3, c: 10 } }, // ECW
        { s: { r: 2, c: 11 }, e: { r: 3, c: 11 } }, // B/L
        { s: { r: 2, c: 12 }, e: { r: 3, c: 12 } }, // Diagnosa
        { s: { r: 2, c: 13 }, e: { r: 3, c: 13 } }, // Diagnosa II
        { s: { r: 2, c: 14 }, e: { r: 3, c: 14 } }, // Konsul Dokter
        { s: { r: 2, c: 15 }, e: { r: 2, c: 17 } }, // Kecelakaan
        { s: { r: 2, c: 18 }, e: { r: 2, c: 20 } }, // Tindak Lanjut
        { s: { r: 2, c: 21 }, e: { r: 3, c: 21 } }, // DOA
        { s: { r: 2, c: 22 }, e: { r: 2, c: 23 } }, // Kasus
        { s: { r: 2, c: 24 }, e: { r: 3, c: 24 } }, // Dokter Jaga
        { s: { r: 2, c: 25 }, e: { r: 3, c: 25 } }, // Dirujuk Ke
        { s: { r: 2, c: 26 }, e: { r: 3, c: 26 } }, // Rujuk Dari
        { s: { r: 2, c: 27 }, e: { r: 3, c: 27 } }, // Rujuk Dari
    ];

    XLSX.utils.book_append_sheet(workbook, worksheet, 'Laporan Register');
    XLSXStyle.writeFile(workbook, 'Laporan Register IGD.xlsx');
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
