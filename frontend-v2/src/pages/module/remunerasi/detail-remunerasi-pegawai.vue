<template>
    <section>
        <!-- <div class="column is-12">
            <VCard style="padding-bottom: 0px">
                <div class="column c-title pt-2 mb-0">
                    <label class="title-page">Pencarian</label>
                </div>
                <div class="column is-12 pt-3">
                    <div class="columns is-multiline">
                        <div class="column is-3">
                            <VField label="Periode Pagu" style="margin-bottom: 6px;" />
                            <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
                                <template #default="{ inputValue, inputEvents }">
                                    <VField addons>
                                        <VControl icon="feather:calendar">
                                            <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                        </VControl>
                                        <VControl>
                                            <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                                        </VControl>
                                        <VControl icon="feather:calendar">
                                            <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-2">
                            <VField class="is-rounded-select is-autocomplete-select" label="Pegawai">
                                <VControl icon="feather:search" class="prime-auto-select">
                                    <AutoComplete v-model="item.qpegawai" :suggestions="d_Pegawai"
                                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="Nama Pegawai" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField class="is-rounded-select is-autocomplete-select" label="Ruangan">
                                <VControl icon="feather:search" class="prime-auto-select">
                                    <AutoComplete v-model="item.qruangan" :suggestions="d_Ruangan"
                                        @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="Nama Ruangan" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField>
                                <VLabel>No Closing</VLabel>
                                <VControl>
                                    <input v-model="item.noclosing" class="input" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2 mt-4">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="item.isKelompokPenghasil" :value="true"
                                        :label="'Kelompok Penghasil'" color="info" square
                                        :class="item.cekAll == true ? 'is-solid' : ''" />
                                </VControl>
                            </VField>
                        </div>

                        <div class="column btn-search mt-3">
                            <VIconButton color="success" icon="fas fa-search" @click="fetchData" :loading="loadSearch" />
                        </div>
                    </div>
                </div>
            </VCard>
        </div> -->

        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 mb-0">
                    <div class="columns p-2">
                        <div class="column is-10">
                            <label class="title-page">Detail Remunerasi Pegawai</label>
                            <label style="font-family:serif">{{ PEGAWAI }}</label>
                        </div>
                    </div>
                </div>

                <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadSearch" />
                <DataTable v-else :rows="5" :value="dataSource" :rowsPerPageOptions="[5, 10, 15]" class="p-datatable-sm  mt-5"
                    breakpoint="960px" selectionMode="single" sortMode="multiple" v-model:expanded-rows="expandedRows"
                    showGridlines tableStyle="min-width: 30rem"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                    <template #header>
                        <div class="column pt-0 pb-0">
                            <VButton color="primary" @click="exportExcel(activeTab)" outlined icon="fas fa-file-excel">
                                Export To Excel
                            </VButton>
                        </div>
                    </template>
                    <Column field="tglpelayanan" header="Tanggal" />
                    <Column field="nocm" header="No RM" />
                    <Column field="noregistrasi" header="No Registrasi" />
                    <Column field="namapasien" header="Nama Pasien" />
                    <Column field="namaruangan" header="Ruangan" />
                    <Column field="namaproduk" header="Nama Layanan" />
                    <Column field="jumlah" header="Qty" />
                    <Column field="iscito" header="Cito" />
                    <Column field="hargasatuan" header="Tarif Layanan" style="text-align:right">
                        <template #body="slotProps">
                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.hargasatuan), 2), '') }}
                        </template>
                    </Column>
                    <Column field="jenispagunilai" header="Jasa Remun" style="text-align:right">
                        <template #body="slotProps">
                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.jenispagunilai), 2), '') }}
                        </template>
                    </Column>
                    <Column field="detailjenispagu" header="Pagu" />
                    <Column header="Action" style="text-align:center">
                        <template #body="slotProps">
                            <VIconButton type="button" icon="fas fa-search-plus" class="mr-3" color="info" circle outlined
                                raised v-tooltip.top="'Rincian'" @click="detailRincian(slotProps.data)">
                            </VIconButton>
                        </template>
                    </Column>
                    <ColumnGroup type="footer">
                        <Row>
                            <Column footer="Total" style="padding: 0.3rem 0.3rem 0 0.3rem" />
                            <Column />
                            <Column />
                            <Column />
                            <Column />
                            <Column />
                            <Column />
                            <Column />
                            <Column :footer="item.FtarifLayanan" style="padding: 0.3rem 0.3rem 0 0.3rem;text-align:right" />
                            <Column :footer="item.FtjRemun" style="padding: 0.3rem 0.3rem 0 0.3rem;text-align:right" />
                            <Column />
                            <Column />
                        </Row>
                    </ColumnGroup>
                </DataTable>
            </VCard>
        </div>
    </section>

    <VModal :open="modalDetail" title="Perincian Komponen Harga" size="large" @close="modalDetail = false" actions="right"
        :cancel-label="'Close'">
        <template #content>
            <div class="columns is-multiline pb-1">
                <div class="column is-4">
                    <p style="font-weight:600;font-family: var(--font-alt);color: var(--dark-text);">Tgl Pelayanan</p>
                    <span class="mt-1">{{ SourceRemun != null ? SourceRemun.tglpelayanan : item.tgLayanan }}</span>
                </div>
                <div class="column is-4">
                    <p style="font-weight:600;font-family: var(--font-alt);color: var(--dark-text);">Nama Pelayanan</p>
                    <span class="mt-1">{{ SourceRemun != null ? SourceRemun.namaproduk : '-' }}</span>
                </div>
                <div class="column is-4">
                    <p style="font-weight:600;font-family: var(--font-alt);color: var(--dark-text);">No Registrasi</p>
                    <span class="mt-1">{{ SourceRemun != null ? SourceRemun.noregistrasi : item.noregistrasi }}</span>
                </div>
            </div>
            <div class="columns is-multiline pb-1">
                <div class="column is-4">
                    <p style="font-weight:600;font-family: var(--font-alt);color: var(--dark-text);">Ruangan</p>
                    <span class="mt-1">{{ SourceRemun != null ? SourceRemun.namaruangan : item.ruangan}}</span>
                </div>
                <div class="column is-4">
                    <p style="font-weight:600;font-family: var(--font-alt);color: var(--dark-text);">Total Klaim</p>
                    <span class="mt-1">Rp. {{ SourceRemun != null ? H.formatRupiah(SourceRemun.totalklaim, '') : '0'}}</span>
                </div>
                <div class="column is-4">
                    <p style="font-weight:600;font-family: var(--font-alt);color: var(--dark-text);">Total Billing</p>
                    <span class="mt-1">Rp. {{ SourceRemun != null ? H.formatRupiah(SourceRemun.totalbilling, '') : '0' }}</span>
                </div>
            </div>
            <div class="columns is-multiline pb-1">
                <div class="column is-4">
                    <p style="font-weight:600;font-family: var(--font-alt);color: var(--dark-text);">Harga Satuan</p>
                    <span class="mt-1">Rp. {{ SourceRemun != null ? H.formatRupiah(SourceRemun.jasapelayanan, '') : '0' }}</span>
                </div>
                <div class="column is-4">
                    <p style="font-weight:600;font-family: var(--font-alt);color: var(--dark-text);">Harga Proporsi</p>
                    <span class="mt-1">Rp. {{ SourceRemun != null ? H.formatRupiah(SourceRemun.jaspel, '') : '0' }}</span>
                </div>
                <div class="column is-4">
                    <p style="font-weight:600;font-family: var(--font-alt);color: var(--dark-text);">Persen Jasa Pelayanan
                        (%)</p>
                    <span class="mt-1">{{ item.persenJaspel }}</span>
                </div>
            </div>
            <div class="columns is-multiline pb-1">
                <div class="column is-4">
                    <p style="font-weight:600;font-family: var(--font-alt);color: var(--dark-text);">Jasa Proporsi</p>
                    <span class="mt-1">Rp. {{ SourceRemun != null ? H.formatRupiah(SourceRemun.jaspelproporsi, '') : '0' }}</span>
                </div>
                <div class="column is-4">
                    <p style="font-weight:600;font-family: var(--font-alt);color: var(--dark-text);">Jasa Pagu</p>
                    <span class="mt-1">Rp. {{ H.formatRupiah(item.jasaPagu, '') }}</span>
                </div>
                <div class="column is-4">
                    <p style="font-weight:600;font-family: var(--font-alt);color: var(--dark-text);">Total Akhir</p>
                    <span class="mt-1">Rp. {{ H.formatRupiah(item.jenisPgNilai, '') }}</span>
                </div>
            </div>
        </template>
    </VModal>
</template>
<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive } from 'vue'
import { useRouter, useRoute, RouterLink } from 'vue-router';
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import DataTable from 'primevue/datatable'
import AutoComplete from 'primevue/autocomplete';
import OverlayPanel from 'primevue/overlaypanel';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import * as XLSX from "xlsx";
import * as XLSXStyle from 'xlsx-js-style';
import * as H from '/@src/utils/appHelper'
import Column from 'primevue/column'
import { useViewWrapper } from '/@src/stores/viewWrapper'

// app.directive('tooltip', Tooltip);

useHead({
    title: 'Daftar Remunerasi Pegawai - ' + import.meta.env.VITE_PROJECT,
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

const NOCLOSING = useRoute().query.noclosing as string
const IDDOKTER = useRoute().query.iddokter as string
const JENIS = useRoute().query.klmpenghasil as string
const PEGAWAI = useRoute().query.namapegawai as string

const router = useRouter()
const confirm = useConfirm()
const activeTab = ref(0);
const dataSource: any = ref([])
const SourceRemun: any = ref([])
const dataSourceNilaiPagu: any = ref([])
const detailResep: any = ref([])
const op = ref();
const selected: any = ref({})
const modalDetail: any = ref(false)
const modalEdit: any = ref(false)
const expandedRows = ref();
let d_Pegawai: any = ref([])
let d_Ruangan: any = ref([])
let loadSearch: any = ref(false)
let loadSave: any = ref(false)
let isLoading: any = ref(false)

const fetchData = async () => {

    item.value.TtarifLayanan = 0
    item.value.TjasaRemun = 0

    await useApi().get(`remunerasi/get-detail-remun-pegawai?IdDokter=${IDDOKTER}&noclosing=${NOCLOSING}&klmpenghasil=${JENIS}`).then((response) => {
        response.forEach((element: any, i: any) => {
            element.no = i + 1
            item.value.TtarifLayanan = parseFloat(element.hargasatuan) + item.value.TtarifLayanan
            item.value.TjasaRemun = parseFloat(element.jenispagunilai) + item.value.TjasaRemun
        });

        item.value.FtarifLayanan = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.TtarifLayanan, 2), '')
        item.value.FtjRemun = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.TjasaRemun, 2), '')
        dataSource.value = response
    })
}

const exportExcel = (e: any) => {
    const workbook = XLSX.utils.book_new();
    const worksheet = XLSX.utils.aoa_to_sheet([
        ['Daftar Remunerasi Pegawai'],
        [],
        ['NO', 'NO CLOSING', 'TGL AWAL', 'TGL AKHIR', 'NAMA PEGAWAI', 'SK PERTAMA', 'GOLONGAN',
            'RUANGAN / BAGIAN', 'NPWP', 'NIP', 'NO REKENING', 'NAMA REKENING', 'TOTAL REMUNERASI'],
        ...dataSource.value.map((e: any) => [
            e.no,
            e.noclosing,
            e.tglawal,
            e.tglakhir,
            e.namakaryawan,
            e.skpertamamasukrs,
            e.golongan,
            e.ruangankerja,
            e.npwp,
            e.nip,
            e.nomorrekening,
            e.namarekening,
            e.total ? H.roundToDecimal(parseFloat(e.total), 2) : 0,
        ]),
        ['', 'TOTAL', '', '', '', '', '', '', '', '', '', '',
            H.roundToDecimal(parseFloat(item.value.TtotalRemunerasi), 2),
        ]
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

    const columnWidths = [5, 13, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18];

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
    const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 12 } };
    worksheet['!merges'] = [mergeTitle];

    XLSX.utils.book_append_sheet(workbook, worksheet, 'Remunerasi Pegawai', true);
    XLSXStyle.writeFile(workbook, 'Daftar Remunerasi Pegawai.xlsx');
}

const detailRincian = async (e: any) => {
    console.log(e)
    await useApi().get(`remunerasi/get-rincian-detail-remun-pegawai?kpid=${e.kpid}&jpid=${e.jpid}&norec_pp=${e.norec_pp}`).then((response: any) => {
        SourceRemun.value = response.remun
        item.value.tgLayanan = e.tglpelayanan
        item.value.noregistrasi = e.noregistrasi
        item.value.ruangan = e.namaruangan
        item.value.jenisPgNilai = e.jenispagunilai
        item.value.persenJaspel = response.persenJaspel
        item.value.jasaPagu = response.pagu.jenispagunilai
        modalDetail.value = true
    })
}

const fetchPegawai = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Pegawai.value = response
    })
}

const fetchRuangan = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Ruangan.value = response
    })
}

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
