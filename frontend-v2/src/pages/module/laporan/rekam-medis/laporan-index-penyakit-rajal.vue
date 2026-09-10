<template>
    <section>
        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 mb-0">
                    <div class="column is-10 p-0">
                        <label for="">LAPORAN INDEX RAWAT JALAN</label>
                    </div>
                </div>

                <VPlaceload height="20rem" width="100%" class="mx-2 mt-4" v-if="loadData" />
                <DataTable v-else :rows="25" :value="dataSource" :loading="loadSearch" :rowsPerPageOptions="[10, 15,25, 50]"
                    class="p-datatable-sm mt-4" breakpoint="960px" selectionMode="single" sortMode="multiple" showGridlines
                    tableStyle="min-width: 30rem"
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
                    <Column field="nocm" header="No RM"  style="min-width: 100px"/>
                    <Column field="namapasien" header="Nama Pasien"  style="min-width: 200px" />
                    <Column field="alamatlengkap" header="Alamat"  style="min-width: 200px" />
                    <Column field="name" header="Kewarganegaraan"  style="min-width: 200px" />
                    <Column field="noidentitas" header="No Identitas"  style="min-width: 200px"/>
                    <Column field="nmprovider" header="Asal rujukan"  style="min-width: 200px"/>
                    <Column field="nosep" header="No SEP"  style="min-width: 200px"/>
                    <Column field="namaruangan" header="Ruangan"  style="min-width: 200px"/>
                    <Column field="umur" header="Umur"  style="min-width: 200px"/>
                    <Column field="statuskunjungan" header="Kunjungan"  style="min-width: 100px"/>
                    <Column field="kasus" header="Kasus"  style="min-width: 100px"/>
                    <Column field="jeniskelamin" header="Jenis Kelamin"  style="min-width: 100px" />
                    <Column field="tglmasuk" header="Tgl Masuk"  style="min-width: 100px" />
                    <Column field="jammasuk" header="Jam Masuk"  style="min-width: 100px" />
                    <Column field="" header="Tgl Keluar"  style="min-width: 100px"/>
                    <Column field="namakelas" header="Kelas Rawat"  style="min-width: 100px" />
                    <Column field="lamahari" header="Lama Dirawat"  style="min-width: 50px" />
                    <Column field="" header="Pasien Keluar"  style="min-width: 100px"/>
                    <Column field="jumlah07" header="Umur 0-7" bodyStyle="text-align:center"/>
                    <Column field="jumlah828" header="Umur 8-28" bodyStyle="text-align:center"/>
                    <Column field="jumlahkurang1" header="Umur < 1" bodyStyle="text-align:center"/>
                    <Column field="jumlah14" header="Umur 1-4" bodyStyle="text-align:center"/>
                    <Column field="jumlah59" header="Umur 5-9" bodyStyle="text-align:center"/>
                    <Column field="jumlah1014" header="Umur 10-14" bodyStyle="text-align:center"/>
                    <Column field="jumlah1519" header="Umur 15-19" bodyStyle="text-align:center"/>
                    <Column field="jumlah2044" header="Umur 20-44" bodyStyle="text-align:center"/>
                    <Column field="jumlah4554" header="Umur 45-54" bodyStyle="text-align:center"/>
                    <Column field="jumlah5559" header="Umur 55-59" bodyStyle="text-align:center"/>
                    <Column field="jumlah6069" header="Umur 60-69" bodyStyle="text-align:center"/>
                    <Column field="jumlah70" header="Umur 70" bodyStyle="text-align:center"/>
                    <Column field="kelompokpasien" header="Jenis Pasien" />
                    <Column field="kodeicd10primer" header="Kode ICD" />
                    <Column field="namaicd10primer" header="Nama ICD" />
                    <Column field="kodeicd10sekunder_1" header="Kode ICD Sekunder 1"></Column>
                    <Column field="namaicd10sekunder_1" header="Nama ICD Sekunder 1"></Column>
                    <Column field="kodeicd10sekunder_2" header="Kode ICD Sekunder 2"></Column>
                    <Column field="namaicd10sekunder_2" header="Nama ICD Sekunder 2"></Column>
                    <Column field="kodeicd10sekunder_3" header="Kode ICD Sekunder 3"></Column>
                    <Column field="namaicd10sekunder_3" header="Nama ICD Sekunder 3"></Column>
                    <Column field="kodeicd10sekunder_4" header="Kode ICD Sekunder 4"></Column>
                    <Column field="namaicd10sekunder_4" header="Nama ICD Sekunder 4"></Column>
                    <Column field="kodeicd10sekunder_5" header="Kode ICD Sekunder 5"></Column>
                    <Column field="namaicd10sekunder_5" header="Nama ICD Sekunder 5"></Column>
                    <Column field="kodeicd10sekunder_6" header="Kode ICD Sekunder 6"></Column>
                    <Column field="namaicd10sekunder_6" header="Nama ICD Sekunder 6"></Column>
                    <Column field="kodeicd9_1" header="Kode ICD9CM 1"></Column>
                    <Column field="namaicd9_1" header="Nama ICD9CM 1"></Column>
                    <Column field="kodeicd9_2" header="Kode ICD9CM 2"></Column>
                    <Column field="namaicd9_2" header="Nama ICD9CM 2"></Column>
                    <Column field="kodeicd9_3" header="Kode ICD9CM 3"></Column>
                    <Column field="namaicd9_3" header="Nama ICD9CM 3"></Column>
                    <Column field="kodeicd9_4" header="Kode ICD9CM 4"></Column>
                    <Column field="namaicd9_4" header="Nama ICD9CM 4"></Column>
                    <Column field="kodeicd9_5" header="Kode ICD9CM 5"></Column>
                    <Column field="namaicd9_5" header="Nama ICD9CM 5"></Column>
                    <Column field="kodeicd9_6" header="Kode ICD9CM 6"></Column>
                    <Column field="namaicd9_6" header="Nama ICD9CM 6"></Column>
                    <Column field="dokter" header="DOKTER"  style="min-width: 150px"/>
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
    title: 'Laporan Index Rawat Jalan - ' + import.meta.env.VITE_PROJECT,
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
    let tglAwal = H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
    let tglAkhir = H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
    let ruanganfk = item.value.ruangan ? `&ruanganId=${item.value.ruangan.value}` : ''
    let kelompokpasienfk = item.value.kelompokpasien ? `&kpid=${item.value.kelompokpasien.value}` : ''
    let dokterfk = item.value.dokter ? `&dokter=${item.value.dokter.value}` : ''
    let jeniskelaminfk = item.value.jeniskelamin ? `&jeniskelamin=${item.value.jeniskelamin.value}` : ''

    item.value.Ttotal = 0
    loadSearch.value = true
    await useApi().get(`laporan/index-penyakit?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}${ruanganfk}${kelompokpasienfk}${dokterfk}${jeniskelaminfk}`).then((response) => {
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


const exportExcel = () => {
    const workbook = XLSX.utils.book_new();
    const worksheet = XLSX.utils.aoa_to_sheet([
        ['Laporan Index Rawat Jalan'],
        [],
        [
            'No', 'No RM', 'Nama Pasien', 'Alamat','Kewarganegaraan', 'No Identitas', 'Asal rujukan','No SEP','Ruangan',
            'Umur','Kunjungan','Kasus','Jenis Kelamin','Tgl Masuk','Jam Masuk', 'Tgl Keluar',
            'Kelas Rawat','Lama Dirawat','Pasien Keluar', 'Umur 0-7','Umur 8-28','Umur < 1','Umur 1-4','Umur 5-9','Umur 10-14','Umur 15-19', 'Umur 20-44','UMur 45-54','Umur 55-59','Umur 60-69','Umur 70',
            'Jenis Pasien','Kode ICD','Nama ICD',
            'Kode ICD Sekunder 1','Nama ICD Sekunder 1',
            'Kode ICD Sekunder 2','Nama ICD Sekunder 2',
            'Kode ICD Sekunder 3','Nama ICD Sekunder 3',
            'Kode ICD Sekunder 4','Nama ICD Sekunder 4',
            'Kode ICD Sekunder 5','Nama ICD Sekunder 5',
            'Kode ICD Sekunder 6','Nama ICD Sekunder 6',
            'Kode ICD9CM 1',
            'Nama ICD9CM 1',
            'Kode ICD9CM 2',
            'Nama ICD9CM 2',
            'Kode ICD9CM 3',
            'Nama ICD9CM 3',
            'Kode ICD9CM 4',
            'Nama ICD9CM 4',
            'Kode ICD9CM 5',
            'Nama ICD9CM 5',
            'Kode ICD9CM 6',
            'Nama ICD9CM 6',
            'Dokter'],
        ...dataSource.value.map((e: any,  index: number) => [
            index + 1,
            e.nocm,
            e.namapasien,
            e.alamatlengkap,
            e.name,
            e.noidentitas,
            e.nmprovider,
            e.nosep,
            e.namaruangan,
            e.umur,
            e.statuskunjungan,
            e.kasus,
            e.jeniskelamin,
            e.tglmasuk,
            e.jammasuk,
            '',
            e.namakelas,
            e.lamahari,
            '',
            e.jumlah07,
            e.jumlah828,
            e.jumlahkurang1,
            e.jumlah14,
            e.jumlah59,
            e.jumlah1014,
            e.jumlah1519,
            e.jumlah2044,
            e.jumlah4554,
            e.jumlah5559,
            e.jumlah6069,
            e.jumlah70,
            e.kelompokpasien,
            e.kodeicd10primer,
            e.namaicd10primer,
            e.kodeicd10sekunder_1,
            e.namaicd10sekunder_1,
            e.kodeicd10sekunder_2,
            e.namaicd10sekunder_2,
            e.kodeicd10sekunder_3,
            e.namaicd10sekunder_3,
            e.kodeicd10sekunder_4,
            e.namaicd10sekunder_4,
            e.kodeicd10sekunder_5,
            e.namaicd10sekunder_5,
            e.kodeicd10sekunder_6,
            e.namaicd10sekunder_6,
            e.kodeicd9_1,
            e.namaicd9_1,
            e.kodeicd9_2,
            e.namaicd9_2,
            e.kodeicd9_3,
            e.namaicd9_3,
            e.kodeicd9_4,
            e.namaicd9_4,
            e.kodeicd9_5,
            e.namaicd9_5,
            e.kodeicd9_6,
            e.namaicd9_6,
            e.dokter,
        ]),
    ]);
    // Mendefinisikan style untuk header(centered)
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

    // Mendefinisikan range header
    const headerRange = XLSX.utils.decode_range(worksheet['!ref']);
    for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
        const headerCell = XLSX.utils.encode_cell({ r: 2, c: col });
        worksheet[headerCell].s = headerStyle;
    }

    const columnWidths = [10, 10, 30, 30, 20,20, 20, 20, 10,10,10,18, 10,10,10,18, 10,,10, 10, 15, 10, 10, 10, 10, 10,10,10,18, 10,10,10,20, 20, 20, 20, 20,20, 20, 20, 20, 20, 20,20,18,18, 10,10,10,20, 20,20,20,20,20,20,20,20,20,20];

    for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
        worksheet['!cols'] = worksheet['!cols'] || [];
        worksheet['!cols'][col] = { wch: columnWidths[col] };
    }

    // Centering the text in cell A1
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

    // Menggabungkan dua baris pertama
    const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 10 } };
    worksheet['!merges'] = [mergeTitle];

    XLSX.utils.book_append_sheet(workbook, worksheet, 'Laporan Index Rawat Jalan', true);
    XLSXStyle.writeFile(workbook, 'Laporan Index Rawat Jalan.xlsx');
}

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
