<template>
    <section>
        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 mb-0">
                    <label class="title-page">Jasa Layanan Pagu</label>
                </div>
                <div class="column is-12 pt-3">
                    <div class="columns is-multiline">
                        <div class="column is-2">
                            <VDatePicker v-model="item.tglPelayanan" color="green" trim-weeks>
                                <template #default="{ inputValue, inputEvents }">
                                    <VField label="Tanggal Pelayanan">
                                        <VControl icon="feather:calendar">
                                            <VInput type="text" placeholder="Select a date" :value="inputValue"
                                                v-on="inputEvents" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-3">
                            <VField class="is-autocomplete-select" label="Kelompok Pasien">
                                <VControl icon="feather:search">
                                    <Multiselect mode="single" v-model="item.qkpasien" :options="d_KelompokPasien"
                                        placeholder="Pilih Kelompok" :searchable="true" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField class="is-autocomplete-select" label="Dokter">
                                <VControl icon="feather:search">
                                    <AutoComplete v-model="item.dokterfk" :suggestions="d_Dokter"
                                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="ketik Nama Dokter" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column">
                            <VField label="Cari">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.search" v-on:keyup.enter="fetchPagu"
                                        placeholder="Nama Layanan" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-1 btn-search mt-3">
                            <VIconButton color="success" icon="fas fa-search" @click="fetchPagu" :loading="loadSearch" />
                        </div>
                    </div>
                </div>
                <TabView class="tabview-custom " @tab-click="klikTab($event)">
                    <TabPanel>
                        <template #header>
                            <i class="fas fa-users mr-2" aria-hidden="true"></i>
                            <span>PAGU REMUNERASI</span>
                        </template>
                        <div v-if="activeTab == 0">
                            <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadData" />
                            <DataTable v-else :rows="10" :value="dataSource" :rowsPerPageOptions="[5, 10, 15,50,100,1000]"
                                :loading="loadSearch" class="p-datatable-sm" breakpoint="960px" selectionMode="single"
                                sortMode="multiple" v-model:expanded-rows="expandedRows" showGridlines
                                tableStyle="min-width: 30rem"
                                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                                <template #header>
                                    <div class="column pt-0 pb-0">
                                        <VButtons style="justify-content: space-between;">
                                            <VButton color="primary" @click="exportExcel(activeTab)" outlined
                                                icon="fas fa-file-excel">
                                                Export To Excel
                                            </VButton>
                                            <VButton color="primary" icon="fas fa-save" raised @click="savePagu"
                                                :loading="loadSave">Simpan Pagu
                                            </VButton>
                                        </VButtons>
                                    </div>
                                </template>
                                <Column field="tglpelayanan" header="TGL Pelayanan">
                                    <template #body="slotProps">
                                        {{ H.formatDateIndo(slotProps.data.tglpelayanan) }}
                                    </template>
                                </Column>
                                <Column field="namaproduk" header="Nama Pelayanan" />
                                <Column field="kelompokpasienformat" header="Kelompok Pasien" />
                                <Column field="hargasatuan" header="Harga Satuan" style="text-align:right">
                                    <template #body="slotProps">
                                        {{ H.formatRupiah(slotProps.data.hargasatuan, '') }}
                                    </template>
                                </Column>
                                <Column field="jasapelayanan" header="Jasa Pelayanan" style="text-align:right">
                                    <template #body="slotProps">
                                        {{ H.formatRupiah(slotProps.data.jasapelayanan, '') }}
                                    </template>
                                </Column>
                                <Column field="jumlah" header="JML" />
                                <Column field="jasa" header="Jasa Cito" style="text-align:right">
                                    <template #body="slotProps">
                                        {{ H.formatRupiah(slotProps.data.jasa, '') }}
                                    </template>
                                </Column>
                                <Column field="jasapelayanan" header="Total" style="text-align:right" sortable>
                                    <template #body="slotProps">
                                        {{ H.formatRupiah(slotProps.data.jasapelayanan, '') }}
                                    </template>
                                </Column>
                                <Column field="dokterpj" header="Dokter Pelaksana" />
                                <Column field="tipedokter" header="Tipe Jasa" />
                                <Column field="namaruangan" header="Ruangan" />
                                <Column field="totalklaim" header="Total Klaim" style="text-align:right">
                                    <template #body="slotProps">
                                        {{ H.formatRupiah(slotProps.data.totalklaim, '') }}
                                    </template>
                                </Column>
                                <Column field="totalbilling" header="Total Biling" style="text-align:right">
                                    <template #body="slotProps">
                                        {{ H.formatRupiah(slotProps.data.totalbilling, '') }}
                                    </template>
                                </Column>
                                <ColumnGroup type="footer">
                                    <Row>
                                        <Column footer="Total" tyle="min-width: 80px;" />
                                        <Column />
                                        <Column />
                                        <Column :footer="item.F_hargaSatuan"
                                            style="text-align:right;padding: 0.3rem 0.3rem 0 0.3rem;" />
                                        <Column :footer="item.F_jasapelayanan"
                                            style="text-align:right;padding: 0.3rem 0.3rem 0 0.3rem;" />
                                        <Column />
                                        <Column :footer="item.F_cito"
                                            style="text-align:right;padding: 0.3rem 0.3rem 0 0.3rem;" />
                                        <Column :footer="item.F_jasapelayanan"
                                            style="text-align:right;padding: 0.3rem 0.3rem 0 0.3rem;" />
                                        <Column :colspan="6" />
                                    </Row>
                                </ColumnGroup>
                            </DataTable>
                        </div>
                    </TabPanel>
                    <TabPanel>
                        <template #header>
                            <i class="fas fa-user-check mr-2" aria-hidden="true"></i>
                            <span>NILAI PAGU REMUNERASI</span>
                        </template>
                        <div v-if="activeTab == 1">
                            <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadData" />
                            <DataTable :rows="10" :rowsPerPageOptions="[5, 10, 15,50,100,1000]" v-else :loading="loadSearch"
                                class="p-datatable-sm" :value="dataSourceNilaiPagu" breakpoint="960px"
                                selectionMode="single" sortMode="multiple" v-model:expanded-rows="expandedRows"
                                showGridlines tableStyle="min-width: 30rem"
                                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                                <template #header>
                                    <div class="column pt-0 pb-0">
                                        <VButtons style="justify-content: space-between;">
                                            <VButton color="primary" @click="exportExcel(activeTab)" outlined
                                                icon="fas fa-file-excel">
                                                Export To Excel
                                            </VButton>
                                            <VButton color="primary" icon="fas fa-save" raised @click="savePagu"
                                                :loading="loadSave">Simpan Pagu
                                            </VButton>
                                        </VButtons>
                                    </div>
                                </template>
                                <Column field="tglpelayanan" header="TGL Pelayanan">
                                    <template #body="slotProps">
                                        {{ H.formatDateIndo(slotProps.data.tglpelayanan) }}
                                    </template>
                                </Column>
                                <Column field="namaproduk" header="Nama Pelayanan" />
                                <Column field="kelompokpasienformat" header="Kelompok Pasien" />
                                <Column field="namaruangan" header="Ruangan" />
                                <Column field="ccdireksi" header="Direksi" style="text-align:right">
                                    <template #body="slotProps">
                                        {{ H.formatRupiah(slotProps.data.ccdireksi, '') }}
                                    </template>
                                </Column>
                                <Column field="ccstaffdireksi" header="Struktural" style="text-align:right">
                                    <template #body="slotProps">
                                        {{ H.formatRupiah(slotProps.data.ccstaffdireksi, '') }}
                                    </template>
                                </Column>
                                <Column field="ccmanajemen" header="Casemix" style="text-align:right">
                                    <template #body="slotProps">
                                        {{ H.formatRupiah(slotProps.data.ccmanajemen, '') }}
                                    </template>
                                </Column>
                                <Column field="rcdokter" header="JPL" style="text-align:right">
                                    <template #body="slotProps">
                                        {{ H.formatRupiah(slotProps.data.rcdokter, '') }}
                                    </template>
                                </Column>
                                <Column field="rc" header="JPTL" style="text-align:right">
                                    <template #body="slotProps">
                                        {{ H.formatRupiah(slotProps.data.rc, '') }}
                                    </template>
                                </Column>
                                <Column field="postremun" header="Gabungan" style="text-align:right">
                                    <template #body="slotProps">
                                        {{ H.formatRupiah(slotProps.data.postremun, '') }}
                                    </template>
                                </Column>
                                <ColumnGroup type="footer">
                                    <Row>
                                        <Column footer="Total" tyle="min-width: 80px;" />
                                        <Column />
                                        <Column />
                                        <Column />
                                        <Column :footer="item.Ddireksi"
                                            style="text-align:right;padding: 0.3rem 0.3rem 0 0.3rem;" />
                                        <Column :footer="item.DStruktural"
                                            style="text-align:right;padding: 0.3rem 0.3rem 0 0.3rem;" />
                                        <Column :footer="item.DCasemix"
                                            style="text-align:right;padding: 0.3rem 0.3rem 0 0.3rem;" />
                                        <Column :footer="item.DJPL"
                                            style="text-align:right;padding: 0.3rem 0.3rem 0 0.3rem;" />
                                        <Column :footer="item.DJPTL"
                                            style="text-align:right;padding: 0.3rem 0.3rem 0 0.3rem;" />
                                        <Column :footer="item.DGabungan"
                                            style="text-align:right;padding: 0.3rem 0.3rem 0 0.3rem;" />
                                    </Row>
                                </ColumnGroup>
                            </DataTable>
                        </div>
                    </TabPanel>

                </TabView>
                <!-- <VPlaceload height="20rem" width="100%" class="mx-2" /> -->
            </VCard>
        </div>
    </section>
</template>

<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive } from 'vue'
import { useRouter, useRoute, RouterLink } from 'vue-router';
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import DataTable from 'primevue/datatable'
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import AutoComplete from 'primevue/autocomplete';
import * as H from '/@src/utils/appHelper'
import * as XLSX from "xlsx";
import * as XLSXStyle from 'xlsx-js-style';
import Column from 'primevue/column'
import { useViewWrapper } from '/@src/stores/viewWrapper'

// app.directive('tooltip', Tooltip);

useHead({
    title: 'Jasa Layanan Pagu - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
    aktif: true,
    isKK: false,
    tglPelayanan: new Date(),
})

const activeTab = ref(0);
const dataSource: any = ref([])
const dataSourceNilaiPagu: any = ref([])
const expandedRows = ref();
let d_KelompokPasien: any = ref([])
let d_Dokter: any = ref([])
let loadSearch: any = ref(false)
let loadSave: any = ref(false)
let loadData: any = ref(true)
let IsBayar: any = ref(false)

const fetchPagu = async () => {

    let search = item.value.search ? `&search=${item.value.search}` : ''
    let kpId = item.value.qkpasien ? `&kpId=${item.value.qkpasien}` : ''
    let dokter = item.value.dokterfk ? `&dokterfk=${item.value.dokterfk.value}` : ''
    item.value.THargaSatuan = 0
    item.value.TJasapelayanan = 0
    item.value.Tcito = 0

    item.value.TDireksi = 0
    item.value.TStruktural = 0
    item.value.TCasemix = 0
    item.value.TJPL = 0
    item.value.TJPTL = 0
    item.value.TGabungan = 0
    loadSearch.value = true
    await useApi().get(`remunerasi/get-pagu-remunerasi?tglpelayanan=${H.formatDate(item.value.tglPelayanan, 'YYYY-MM-DD')}${kpId}${dokter}${search}`).then((response: any) => {
        response.data1.forEach((element: any, i: any) => {
            element.no = i + 1
            item.value.tglLayanan = H.formatDateIndoSimple(element.tglpelayanan)
            item.value.THargaSatuan = parseFloat(element.hargasatuan) + parseFloat(item.value.THargaSatuan)
            item.value.TJasapelayanan = parseFloat(element.jasapelayanan) + item.value.TJasapelayanan
            item.value.Tcito = parseFloat(element.jasa) + item.value.Tcito
        });
        dataSource.value = response.data1
        response.data2.forEach((element: any, i: any) => {
            element.no = i + 1
            item.value.TDireksi = parseFloat(element.ccdireksi) + parseFloat(item.value.TDireksi)
            item.value.TStruktural = parseFloat(element.ccstaffdireksi) + parseFloat(item.value.TStruktural)
            item.value.TCasemix = parseFloat(element.ccmanajemen) + item.value.TCasemix
            item.value.TJPL = parseFloat(element.rcdokter) + item.value.TJPL
            item.value.TJPTL = parseFloat(element.rc) + item.value.TJPTL
            item.value.TGabungan = parseFloat(element.postremun) + item.value.TGabungan
        })
        dataSourceNilaiPagu.value = response.data2
        IsBayar.value = response.isbayar
    })
    loadSearch.value = false
    loadData.value = false
    item.value.F_hargaSatuan = 'Rp' + H.formatRupiah(H.roundToDecimal(item.value.THargaSatuan, 2), '')
    item.value.F_jasapelayanan = 'Rp' + H.formatRupiah(H.roundToDecimal(item.value.TJasapelayanan, 2), '')
    item.value.F_cito = 'Rp' + H.formatRupiah(H.roundToDecimal(item.value.Tcito, 2), '')

    // convert to Desimal
    item.value.Ddireksi = 'Rp' + H.formatRupiah(H.roundToDecimal(item.value.TDireksi, 2), '')
    item.value.DStruktural = 'Rp' + H.formatRupiah(H.roundToDecimal(item.value.TStruktural, 2), '')
    item.value.DCasemix = 'Rp' + H.formatRupiah(H.roundToDecimal(item.value.TCasemix, 2), '')
    item.value.DJPL = 'Rp' + H.formatRupiah(H.roundToDecimal(item.value.TJPL, 2), '')
    item.value.DJPTL = 'Rp' + H.formatRupiah(H.roundToDecimal(item.value.TJPTL, 2), '')
    item.value.DGabungan = 'Rp' + H.formatRupiah(H.roundToDecimal(item.value.TGabungan, 2), '')
}

const savePagu = async () => {
    if(IsBayar.value){
        H.alert('error','Pagu Sudah Dibayar')
        return
    }
    loadSave.value = true
    let tglAwal = H.formatDate(item.value.tglPelayanan, 'YYYY-MM-DD 00:00:00')
    let tglAkhir = H.formatDate(item.value.tglPelayanan, 'YYYY-MM-DD 23:59:59')
    let a = 0
    let b = 0
    let c = 0
    let e = 0
    let f = 0
    let g = 0
    dataSourceNilaiPagu.value.forEach((element: any) => {
        a = a + parseFloat(element.postremun)
        b = b + parseFloat(element.rcdokter)
        c = c + parseFloat(element.rc)
        e = e + parseFloat(element.ccdireksi)
        f = f + parseFloat(element.ccstaffdireksi)
        g = g + parseFloat(element.ccmanajemen)
    });
    let data1 = {
        periodeawal: tglAwal,
        periodeakhir: tglAkhir,
        postremun: a,
        rcdokter: b,
        rc: c,
        ccdireksi: e,
        ccstaffdireksi: f,
        ccmanajemen: g
    }
    var objSave =
    {
        head: data1,
        data: dataSourceNilaiPagu.value
    }

    await useApi().post('remunerasi/save-remunerasi', objSave)
    loadSave.value = false

}

const fetchDokter = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
        d_Dokter.value = response
    })
}

const fetchKelompokPasien = async () => {
    useApi().get('remunerasi/get-combo-idx').then((response) => {
        d_KelompokPasien.value = response.kelompokpasien.map((e: any) => {
            return { label: e.kelompokpasien, value: e.id }
        })
    })
}


const klikTab = (e: any) => {
    activeTab.value = e.index
}

const exportExcel = (e: any) => {
    const workbook = XLSX.utils.book_new();
    let paguRemunerasi = [
        ['Pagu Remunerasi'],
        [],
        ['NO', 'TANGGAL', 'NAMA PELAYANAN', 'HARGA SATUAN', 'JASA PELAYANAN',
            'JUMLAH', 'JASA CITO', 'TOTAL', 'DOKTER PELAKSANA', 'TIPE JASA', 'RUANGAN', 'TOTAL KLAIM', 'TOTAL TARIF'],
        ...dataSource.value.map((e: any) => [
            e.no,
            e.tglpelayanan,
            e.namaproduk,
            e.hargasatuan ? H.roundToDecimal(parseFloat(e.hargasatuan), 2) : 0,
            e.jasapelayanan ? H.roundToDecimal(parseFloat(e.jasapelayanan), 2) : 0,
            e.jumlah ? parseFloat(e.jumlah) : 0,
            e.jasa ? H.roundToDecimal(parseFloat(e.jasa), 2) : 0,
            e.jasapelayanan ? H.roundToDecimal(parseFloat(e.jasapelayanan), 2) : 0,
            e.dokterpj,
            e.tipedokter,
            e.namaruangan,
            e.totalklaim ? H.roundToDecimal(parseFloat(e.totalklaim), 2) : 0,
            e.totalbilling ? H.roundToDecimal(parseFloat(e.totalbilling), 2) : 0,
        ]),
        ['', 'TOTAL', '',
            H.roundToDecimal(parseFloat(item.value.THargaSatuan), 2),
            H.roundToDecimal(parseFloat(item.value.TJasapelayanan), 2),
            '',
            H.roundToDecimal(parseFloat(item.value.Tcito), 2),
            H.roundToDecimal(parseFloat(item.value.TJasapelayanan), 2),
            '', '', '', '', ''
        ]

    ];

    let nilaiPagu = [
        ['Nilai Pagu Remunerasi'],
        [],
        ['NO', 'TANGGAL', 'NAMA PELAYANAN', 'RUANGAN', 'DIREKSI',
            'STRUKTURAL', 'CASEMIX', 'JPL', 'JPTL', '', 'GABUNGAN'],
        ...dataSourceNilaiPagu.value.map((e: any) => [
            e.no,
            e.tglpelayanan,
            e.namaproduk,
            e.namaruangan,
            e.ccdireksi ? H.roundToDecimal(parseFloat(e.ccdireksi), 2) : 0,
            e.ccstaffdireksi ? H.roundToDecimal(parseFloat(e.ccstaffdireksi), 2) : 0,
            e.ccmanajemen ? H.roundToDecimal(parseFloat(e.ccmanajemen), 2) : 0,
            e.rcdokter ? H.roundToDecimal(parseFloat(e.rcdokter), 2) : 0,
            e.rc ? H.roundToDecimal(parseFloat(e.rc), 2) : 0,
            e.postremun ? H.roundToDecimal(parseFloat(e.postremun), 2) : 0,
        ]),
        ['', 'TOTAL', '', '',
            H.roundToDecimal(parseFloat(item.value.TDireksi), 2),
            H.roundToDecimal(parseFloat(item.value.TStruktural), 2),
            H.roundToDecimal(parseFloat(item.value.TCasemix), 2),
            H.roundToDecimal(parseFloat(item.value.TJPL), 2),
            H.roundToDecimal(parseFloat(item.value.TJPTL), 2),
            H.roundToDecimal(parseFloat(item.value.TGabungan), 2),
        ]

    ];

    let worksheet
    let title
    if (e == 0) {
        worksheet = XLSX.utils.aoa_to_sheet(paguRemunerasi);
        title = 'Pagu Remunerasi.xlsx'
    }
    if (e == 1) {
        worksheet = XLSX.utils.aoa_to_sheet(nilaiPagu);
        title = 'Nilai Pagu Remunerasi.xlsx'
    }

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

    // atur lebar column
    let columnWidths
    if (e == 0) {
        columnWidths = [5, 13, 18, 18, 18, 18, 18, 18, 18, 18];
    } else {
        columnWidths = [5, 13, 20, 20, 18, 18, 18, 18];
    }

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
    let mergeTitle
    if (e == 0) {
        mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 9 } };
    } else {
        mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 7 } };
    }
    worksheet['!merges'] = [mergeTitle];

    XLSX.utils.book_append_sheet(workbook, worksheet, 'PAGU', true);
    XLSXStyle.writeFile(workbook, title);
}



fetchKelompokPasien()
fetchPagu()

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
}</style>
