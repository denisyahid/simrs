<template>
    <section>
        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 mb-0">
                    <div class="column is-10 p-0">
                        <label for="">LAPORAN REGISTER RAWAT JALAN</label>
                    </div>
                </div>

                <VPlaceload height="20rem" width="100%" class="mx-2 mt-4" v-if="loadData" />
                <DataTable v-else :rows="25" :value="dataSource" :loading="loadSearch" :rowsPerPageOptions="[10,15,25,50]"
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
                                    <!-- <div class="column is-2 pb-0">
                                        <VField label="Status Pasien" class="is-rounded-select is-autocomplete-select
                                                    mt-0 pt-0" v-slot="{ id }">
                                            <VControl icon="fas fa-credit-card" fullwidth class="prime-auto-select">
                                                <Dropdown v-model="item.qtatuspasien" :options="d_StatusPasien" :optionLabel="'status'"
                                                    class="is-rounded" placeholder="Status Pasien" style="width: 100%;" :filter="true"
                                                    showClear />
                                            </VControl>
                                        </VField>
                                    </div> -->
                                     <div class="column is-1 btn-search mt-3" style="justify-content:center">
                                        <VIconButton color="success" icon="fas fa-search" @click="fetchData"
                                            :loading="loadSearch" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <Column header="No" bodyClass="text-center">
                        <template #body="slotProps">
                            {{ slotProps.index + 1 }}
                        </template>
                    </Column>
                    <Column bodyStyle="text-align:center" field="tglregistrasi" header="Tanggal" style="min-width: 100px" />
                    <Column bodyStyle="text-align:center" field="jamregistrasi" header="Jam" style="min-width: 100px" />
                    <Column bodyStyle="text-align:center" field="statuspasien" header="Kunjungan" style="min-width: 60px" />
                    <Column bodyStyle="text-align:center" field="noregistrasi" header="NO REG" style="min-width: 150px" />
                    <Column bodyStyle="text-align:center" field="nocm" header="No RM" style="min-width: 160px" />
                    <!-- <Column bodyStyle="text-align:center" field="nosep" header="No SEP" style="min-width: 160px" /> -->
                    <Column bodyStyle="text-align:center" field="namapasien" header="Nama Pasien" style="min-width: 200px"/>
                    <Column bodyStyle="text-align:center" field="alamatlengkap" header="Alamat" style="min-width: 200px" />
                    <Column bodyStyle="text-align:center" field="jeniskelamin" header="Jenis Kelamin" style="min-width: 100px"/>
                    <Column bodyStyle="text-align:center" field="umur" header="Umur" style="min-width: 50px"/>
                    <Column bodyStyle="text-align:center" field="status" header="Status" style="min-width: 100px"/>
                    <Column bodyStyle="text-align:center" field="namarekanan" header="Penjamin" style="min-width: 100px"/>
                    <Column bodyStyle="text-align:center" field="nobpjs" header="No Kartu" style="min-width: 160px"/>
                    <Column bodyStyle="text-align:center" field="namapoli" header="Ruangan" style="min-width: 200px"/>
                    <Column bodyStyle="text-align:center" field="namadokter" header="Dokter" style="min-width: 300px"/>
                    <Column bodyStyle="text-align:center" field="name" header="Kewarganegaraan" style="min-width: 200px"/>
                    <Column bodyStyle="text-align:center" field="namadiagnosa" header="Diagnosa" style="min-width: 200px"/>
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
    title: 'Laporan Registrasi Rawat Jalan - ' + import.meta.env.VITE_PROJECT,
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
    // let statuspasienfk = item.value.qstatuspasien ? `&statuspasien=${item.value.qstatuspasien.value}` : ''


    item.value.Ttotal = 0
    loadSearch.value = true
    await useApi().get(`laporan/registrasi-rajal?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}${ruanganfk}${kelompokpasienfk}${dokterfk}${jeniskelaminfk}`).then((response) => {
        dataSource.value = response.data.sort(
                (a, b) => new Date(a.tgljamregistrasi).getTime() - new Date(b.tgljamregistrasi).getTime()
            );
    })
    loadData.value = false
    loadSearch.value = false
}

const fetchRuangan = async (filter: any) => {
  await useApi()
    .get(
      `emr/dropdown/ruangan_m?select=id,namaruangan,objectdepartemenfk&param_search=namaruangan&query=${filter.query}&limit=10`
    )
    .then((response) => {
      // Filter data untuk objectdepartemenfk === 18
      const filteredResponse = response.filter(
        (ruangan: any) => ruangan.objectdepartemenfk === 18
      );
      d_Ruangan.value = filteredResponse;
      console.log(filteredResponse);
    });
};


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
// const d_StatusPasien: any = ref([
//     {
//         label: "LAMA",
//         status: "LAMA"
//     },
//     {
//         label: "BARU",
//         status: "BARU"
//     }
// ])

const exportExcel = () => {
    const workbook = XLSX.utils.book_new();
    const worksheet = XLSX.utils.aoa_to_sheet([
        ['Laporan Registrasi Rawat Jalan'],
        [],
        ['NO','Tanggal','Jam', 'KUNJUNGAN', 'NO REG', 'NO RM','Nama Pasien', 'Alamat','Jenis Kelamin','Umur','Status','Penjamin','No Kartu','Ruangan','Dokter','Kewarganegaraan','Diagnosa'],
        ...dataSource.value.map((e: any, index: number) => [
            index + 1,
            e.tglregistrasi,
            e.jamregistrasi,
            e.statuspasien,
            e.noregistrasi,
            e.nocm,
            e.namapasien,
            e.alamatlengkap,
            e.jeniskelamin,
            e.umur,
            e.status,
            e.namarekanan,
            // e.jenispeserta,
            e.nobpjs,
            e.namapoli,
            e.namadokter,
            e.name,
            e.namadiagnosa,
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

    const columnWidths = [10, 10, 15, 10,10, 10, 10, 10, 10,10,10,18,10,20,45,30,20,20,20];

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
    const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 15 } };
    worksheet['!merges'] = [mergeTitle];

    XLSX.utils.book_append_sheet(workbook, worksheet, 'Laporan Registrasi', true);
    XLSXStyle.writeFile(workbook, 'Laporan Registrasi Rawat Jalan.xlsx');
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
