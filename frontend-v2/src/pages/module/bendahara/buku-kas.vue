<template>
        <ConfirmDialog />
    <div class="business-dashboard hr-dashboard">
        <div class="columns is-multiline">
           
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <VCard>
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <h3 class="title is-5 mb-2 mr-1">Buku Penerimaan Kas </h3>
                                </div>
                                <!-- <div class="column is-4">
                                    <span>Periode</span>
                                    <VDatePicker v-model="item.periode" is-range color="pink" trim-weeks class="pt-2">
                                        <template #default="{ inputValue, inputEvents }">
                                            <VField addons>
                                                <VControl icon="feather:calendar">
                                                    <VInput :value="inputValue.start" class="input-calendar"
                                                        v-on="inputEvents.start" />
                                                </VControl>
                                                <VControl>
                                                    <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i>
                                                    </VButton>
                                                </VControl>
                                                <VControl icon="feather:calendar">
                                                    <VInput :value="inputValue.end" class="input-calendar"
                                                        v-on="inputEvents.end" />
                                                </VControl>
                                            </VField>
                                        </template>
                                    </VDatePicker>
                                </div> -->
                                <div class="column">
                                    <h1>Periode</h1>
                                    <div class="is-flex">
                                        <VField v-for="data in filters" :key="data.value" style="padding:0px;">
                                            <VControl raw subcontrol>
                                                <VCheckbox v-model="item.filter" class="pt-1 pb-1" :true-value="data.value"
                                                    :label="data.label" color="primary" circle />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                                <div class="column">
                                    <h1 class="mb-2">{{ item.filter == 'Bulan' ? 'Bulan' : 'Tanggal' }}</h1>
                                    <VField>
                                        <VControl class="prime-auto">
                                            <div v-if="item.filter == 'Bulan'">
                                                <Calendar inputId="range" v-model="item.bulan" selectionMode="single"
                                                    :manualInput="false" class="w-100" :showIcon="true" view="month"
                                                    dateFormat="mm/yy" />
                                            </div>
                                            <div v-else>
                                                <Calendar inputId="range" v-model="item.tanggal" selectionMode="range"
                                                    :manualInput="false" class="w-100" :showIcon="true"
                                                    :date-format="'yy-mm-dd'" />
                                            </div>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3 mt-1 mb-1">
                                    <VField>
                                        <VLabel> No. Histori </VLabel>
                                        <VControl>
                                            <VInput type="text" placeholder=" No Histori ..." autocomplete="off"
                                                v-model="item.nohistoris" v-on:keyup.enter="fetchDana()" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3 mt-1 mb-1">
                                    <VField>
                                        <VLabel> Keterangan</VLabel>
                                        <VControl>
                                            <VInput type="text" placeholder="Keterangan ..." autocomplete="off"
                                                v-model="item.keterangan" v-on:keyup.enter="fetchDana()" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-2" style="margin-left: auto; margin-top: 2rem;!important;">
                                    <VIconButton type="button" color="success" class="searcv-button" raised
                                        icon="fas fa-search" @click="cari()" :loading="isLoadingBtn">
                                    </VIconButton>
                                </div>
                            </div>

                        </VCard>
                    </div>
                    <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <VCard>
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <h3 class="title is-5 mb-2">Rekap
                                    </h3>
                                </div>
                            </div>
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <CardCountRev icon="/images/simrs/saldo-awal.png" straight
                                        :total="H.formatRupiah(item.saldolama, 'Rp.')" label="SALDO AWAL" />
                                </div>
                                <div class="column is-3">
                                    <CardCountRev icon="/images/simrs/penerimaaan.png" straight
                                        :total="H.formatRupiah(item.jumlahALL, 'Rp.')" label="PENERIMAAN" />
                                </div>
                                <div class="column is-3">
                                    <CardCountRev icon="/images/simrs/pengeluaran.png" straight
                                        :total="H.formatRupiah(item.jumlahKredit, 'Rp.')" label="PENGELUARAN" />
                                </div>
                                <div class="column is-3">
                                    <CardCountRev icon="/images/simrs/saldo-akhir.png" straight
                                        :total="H.formatRupiah(item.saldoAkhir, 'Rp.')" label="SALDO AKHIR" />
                                </div>
                            </div>
                        </VCard>
                    </div>

                </div>
            </div>
                    <div class="column is-12">
                        <div class="flex-list-inner">
                            <VCard>
                                <div class="columns is-multiline">
                                    <div class="column is-12 mt-3 mb-4">
                                        <VButton color="success" icon="feather:plus-circle" raised rounded
                                            @click="tambahPenerimaan()"> Penerimaan
                                        </VButton>

                                        <VButton color="warning" icon="feather:minus-circle" style="margin-left:20px;"
                                            raised rounded @click="tambahPengeluaran()">Pengeluaran
                                        </VButton>
                                        <!-- <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised
                                            @click="exportExcel()"> Export to Excel </VButton> -->

                                        <VIconButton type="button" color="info" circle raised icon="fas fa-file-excel"
                                            @click="exportExcel()" :loading="isLoading" class="ml-2 is-pulled-right"
                                            v-tooltip.bubble="'Export To Excel'">
                                            <i class="iconify" data-icon="feather:printer" aria-hidden="true"></i>
                                        </VIconButton>
                                    </div>


                                    <div class="column is-12" v-if="dataPenerimaan.loading">
                                        <div class="flex-list-inner mb-4">
                                            <div class="flex-table-item grid-item mb-4" v-for="key in 3" :key="key">
                                                <VFlexTableCell :column="{ grow: true, media: true }">
                                                    <VPlaceloadAvatar size="medium" />
                                                    <VPlaceloadText :lines="2" width="30%" last-line-width="20%"
                                                        class="mx-2" />
                                                </VFlexTableCell>
                                                <VFlexTableCell>
                                                    <VPlaceload width="100%" height="70px" class="mx-1 mt-2" />
                                                </VFlexTableCell>
                                                <VFlexTableCell>
                                                    <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
                                                </VFlexTableCell>
                                                <VFlexTableCell :column="{ align: 'end' }">
                                                    <VPlaceload width="10%" class="mx-1" />
                                                </VFlexTableCell>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-12" v-else-if="dataPenerimaan.length === 0">
                                        <VPlaceholderSection title="Data Tidak Ditemukan"
                                            subtitle="Silakan Pilih Tanggal Periode Registrasi." class="my-6">
                                            <template #image>
                                                <img class="light-image"
                                                    src="/@src/assets/illustrations/placeholders/search-4.svg" alt="" />
                                                <img class="dark-image"
                                                    src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                                    alt="" />
                                            </template>
                                        </VPlaceholderSection>
                                    </div>
                                    <div class="column is-12" v-else-if="dataPenerimaan.length > 0">
                                        <div class="columns is-multiline">
                                            <div class="column is-12">
                                                <DataTable :value="dataPenerimaan" :rows="10"
                                                    :rowsPerPageOptions="[5, 10, 15, 30, 50, 100, 1000]"
                                                    :loading="dataPenerimaan.loading" class="p-datatable-sm"
                                                    breakpoint="960px" selectionMode="single" sortMode="multiple"
                                                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                                    paginator
                                                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                                                    <template #header>
                                                        <div class="columns is-multiline">
                                                            <div class="column is-3">
                                                                <span> Saldo Awal : {{ H.formatRupiah(item.saldolama, 'Rp.')
                                                                }} </span>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    

                                                    <template #empty style="text-align: center;"> No data found.
                                                    </template>
                                                    
                                                    <Column :exportable="false" header="#" frozen>
                                                        <template #body="slotProps">
                                                            <VIconButton type="button" icon="fas fa-trash" class="mr-2"
                                                                color="danger" circle outlined raised
                                                                v-tooltip.top="'Hapus'"
                                                                @click="dialogHapus(slotProps.data)">
                                                            </VIconButton>
                                                            <VIconButton type="button" icon="fas fa-edit" class="mr-2"
                                                                color="info" circle outlined raised
                                                                v-tooltip.top="'Edit'"
                                                                @click="editPengeluaranBP(slotProps.data)" v-if="slotProps.data.debit == 0">
                                                            </VIconButton>
                                                            <VIconButton type="button" icon="fas fa-edit" class="mr-2"
                                                                color="primary" circle outlined raised
                                                                v-tooltip.top="'Edit'"
                                                                @click="editPenerimaanBP(slotProps.data)" v-if="slotProps.data.kredit == 0">
                                                            </VIconButton>
                                                        </template>
                                                    </Column>
                                                    <Column field="no" header="No" />
                                                    <Column field="tglStruk" header="Tanggal"  />
                                                    <Column field="nobukti" header="No. Bukti" 
                                                        sortable />
                                                    <Column field="nohistori" header="No. Histori" 
                                                        sortable />
                                                    <Column field="penyetor" header="Penyetor"  />
                                                    <Column field="jenisTransaksi" header="Jenis Transaksi"
                                                       />
                                                    <Column field="keterangan" header="Keterangan Transaksi"
                                                       />
                                                    <Column field="debit" header="Penerimaan (Debit)"
                                                        style="text-align: right;">
                                                        <template #body="slotProps">
                                                            {{ H.formatRupiah(slotProps.data.debit, 'Rp.') }}
                                                        </template>
                                                    </Column>
                                                    <Column field="kredit" header="Pengeluaran (Kredit)"
                                                        style="text-align: right;">
                                                        <template #body="slotProps">
                                                            {{ H.formatRupiah(slotProps.data.kredit, 'Rp.') }}
                                                        </template>
                                                    </Column>
                                                    <Column field="saldo" header="Saldo"
                                                        style="text-align: right;">
                                                        <template #body="slotProps">
                                                            {{ H.formatRupiah(slotProps.data.saldo, 'Rp.') }}
                                                        </template>
                                                    </Column>
                                                    <Column field="nontunai" header="Transfer"
                                                        style="width: 100px; text-align: right;" sortable>
                                                        <template #body="slotProps">
                                                            {{ H.formatRupiah(slotProps.data.nontunai, 'Rp.') }}
                                                        </template>
                                                    </Column>
                                                    <ColumnGroup type="footer">
                                                        <Row>
                                                            <Column :footer="'TOTAL'" :colspan="7" />
                                                            <Column :footer="H.formatRupiah(item.jumlahALL, 'Rp.')"
                                                            :colspan="2" style="text-align:right" />
                                                                <Column :footer="H.formatRupiah(item.jumlahKredit, 'Rp.')"
                                                                 style="text-align:right" />
                                                        </Row>
                                                    </ColumnGroup>
                                                </DataTable>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </VCard>
                        </div>

                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <Dialog v-model:visible="modalInput" modal header="Tambah Penerimaan" :style="{ width: '50vw' }">
        <div class="columns is-multiline">
            <div class="column is-4">
                <VDatePicker class="pt-0 pb-0 pl-0" v-model="item.tglbku" color="green" trim-weeks mode="dateTime"
                    :max-date="new Date()" is24hr>
                    <template #default="{ inputValue, inputEvents }" class="pb-0">
                        <VField>
                            <VLabel class="required-field">Tanggal</VLabel>
                            <VControl icon="feather:calendar">
                                <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </VField>
                    </template>
                </VDatePicker>
            </div>
            <div class="column is-4">
                <VField>
                    <VLabel>Sumber Dana</VLabel>
                    <VField class="is-autocomplete-select pt-2">
                        <VControl icon="feather:search">
                            <Multiselect mode="single" v-model="item.sumberdana" :options="d_sumberDana"
                                placeholder="Pilih data" :searchable="true" />
                        </VControl>
                    </VField>
                </VField>
            </div>
            <div class="column is-4">
                <VField>
                    <VLabel>No. Bukti</VLabel>
                    <VField class="is-autocomplete-select pt-2">
                        <VControl icon="feather:bookmark">
                            <input v-model="item.nobukti" type="text" class="input is-squared" placeholder="No. Bukti" />
                        </VControl>
                    </VField>
                </VField>
            </div>
            <div class="column is-6">
                <VField>
                    <VLabel>Kelompok Transaksi</VLabel>
                    <VField class="is-autocomplete-select pt-2">
                        <VControl icon="feather:search">
                            <Multiselect mode="single" v-model="item.kelompoktransaksi" :options="d_kelompokTransaksi"
                                placeholder="Pilih data" :searchable="true" />
                        </VControl>
                    </VField>
                </VField>
            </div>
            <div class="column is-6">
                <VField>
                    <VLabel>Keterangan Transaksi</VLabel>
                    <VField class="is-autocomplete-select pt-2">
                        <VControl icon="feather:bookmark">
                            <input v-model="item.keterangans" type="text" class="input is-squared"
                                placeholder="Keterangan Transaksi " />
                        </VControl>
                    </VField>
                </VField>
            </div>
            <div class="column is-6">
                <VField>
                    <VField label="Nominal">
                        <VControl class="prime-auto">
                            <VInput type="text" v-model="item.nominal" v-mask-currency v-on:input="changeNomi(item.nominal)" 
                                placeholder="Nominal" class="is-rounded" style="text-align: right;" />
                        </VControl>
                    </VField>
                </VField>
            </div>
            <div class="column is-6">
                <VField>
                    <VLabel>Terbilang</VLabel>
                    <VField class="is-autocomplete-select pt-2">
                        <VControl icon="feather:bookmark">
                            <input v-model="item.terbilang" type="text" class="input is-squared" disabled />
                        </VControl>
                    </VField>
                </VField>
            </div>

        </div>
        <template #footer>
            <VButton icon="feather:refresh-cw rem-100" light dark-outlined @click="kembaliKeun()">
                Batal
            </VButton>
            <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoadingBtn"
                @click="simpanBKU()"> Simpan
            </VButton>
        </template>
    </Dialog>
    <Dialog v-model:visible="modalPengeluaran" modal header="Tambah Pengeluaran" :style="{ width: '50vw' }">
        <div class="columns is-multiline">
            <div class="column is-4">
                <VDatePicker class="pt-0 pb-0 pl-0" v-model="item.tglbku" color="green" trim-weeks mode="dateTime"
                    :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }" class="pb-0">
                        <VField>
                            <VLabel class="required-field">Tanggal</VLabel>
                            <VControl icon="feather:calendar">
                                <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </VField>
                    </template>
                </VDatePicker>
            </div>
            <div class="column is-4">
                <VField>
                    <VLabel>Sumber Dana</VLabel>
                    <VField class="is-autocomplete-select pt-2">
                        <VControl icon="feather:search">
                            <Multiselect mode="single" v-model="item.sumberdana" :options="d_sumberDana"
                                placeholder="Pilih data" :searchable="true" />
                        </VControl>
                    </VField>
                </VField>
            </div>
            <div class="column is-4">
                <VField>
                    <VLabel>No. Bukti</VLabel>
                    <VField class="is-autocomplete-select pt-2">
                        <VControl icon="feather:bookmark">
                            <input v-model="item.nobukti" type="text" class="input is-squared" placeholder="No. Bukti" />
                        </VControl>
                    </VField>
                </VField>
            </div>
            <div class="column is-6">
                <VField>
                    <VLabel>Kelompok Transaksi</VLabel>
                    <VField class="is-autocomplete-select pt-2">
                        <VControl icon="feather:search">
                            <Multiselect mode="single" v-model="item.pengeluaran" :options="d_kelompokPengeluaran"
                                placeholder="Pilih data" :searchable="true" />
                        </VControl>
                    </VField>
                </VField>
            </div>
            <div class="column is-6">
                <VField>
                    <VLabel>Keterangan Transaksi</VLabel>
                    <VField class="is-autocomplete-select pt-2">
                        <VControl icon="feather:bookmark">
                            <input v-model="item.keterangans" type="text" class="input is-squared"
                                placeholder="Keterangan Transaksi " />
                        </VControl>
                    </VField>
                </VField>
            </div>
            <div class="column is-6">
                <VField>
                    <VField label="Nominal">
                        <VControl class="prime-auto">
                            <VInput type="text" v-model="item.nominal" v-on:input="changeNomi(item.nominal)" v-mask-currency
                                placeholder="Nominal" class="is-rounded" style="text-align: right;"/>
                        </VControl>
                    </VField>
                </VField>
            </div>
            <div class="column is-6">
                <VField>
                    <VLabel>Terbilang</VLabel>
                    <VField class="is-autocomplete-select pt-2">
                        <VControl icon="feather:bookmark">
                            <input v-model="item.terbilang" type="text" class="input is-squared" disabled />
                        </VControl>
                    </VField>
                </VField>
            </div>

        </div>
        <template #footer>
            <VButton icon="feather:refresh-cw rem-100" light dark-outlined @click="kembaliKeun()">
                Batal
            </VButton>
            <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoadingBtn"
                @click="simpanPengeluaran()"> Simpan
            </VButton>
        </template>
    </Dialog>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, reactive, watch } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import moment, { isDate } from 'moment'
import * as H from '/@src/utils/appHelper'
import * as qzService from '/@src/utils/qzTrayService'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import CardCountRev from '/@src/components/partials/widgets/stat/CardCountRev.vue'
import Calendar from 'primevue/calendar';
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import Divider from 'primevue/divider';
import DataTable from 'primevue/datatable'
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import * as XLSX from "xlsx";
import Column from 'primevue/column'
import { useConfirm } from 'primevue/useconfirm'
import ConfirmDialog from 'primevue/confirmdialog'
import sleep from '/@src/utils/sleep'
useHead({
    title: 'Buku Penerimaan Kas -' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle('E-BKU')
useViewWrapper().setFullWidth(true)
const totalALL: any = ref(0)
const route = useRoute()
const router = useRouter()
const isLoading: any = ref(false)
const userSession = useUserSession()
const confirm = useConfirm()
let listColor: any = ref(Object.keys(useThemeColors()))
let dataPenerimaan: any = ref([])
let d_Ruangan: any = ref([])
let d_kelompokTransaksi: any = ref([])
let d_kelompokPengeluaran: any = ref([])
let d_sumberDana: any = ref([])
let d_jenis: any = ref([])
const isLoadingBtn = ref(false)
const modalInput = ref(false)
const modalPengeluaran = ref(false)
const selectView: any = ref()
const currentPage: any = ref({
    limit: 6,
    rows: 50
})

const filters: any = ref([
    {
        label: 'Per Bulan',
        value: 'Bulan',
        model: 'filter'
    },
    {
        label: 'Per Tanggal',
        value: 'Tanggal',
        model: 'filter'
    }
]);

const item: any = ref({
    periode: reactive({
        start: new Date(),
        end: new Date(),
    }),
    tglbku: new Date(),
    saldolama: 0,
    saldoAkhir: 0,
    jumlahKredit: 0,
    jumlahALL: 0,
    tanggal: [
        new Date(),
        new Date()
    ]
})

const fetchDana = async () => {
    dataPenerimaan.value = []
    dataPenerimaan.value.loading = true

    let startDate = '';
    let endDate = '';
    if (item.value.filter == 'Bulan') {
        const selectedDate = new Date(item.value.bulan);
        const firstDayOfMonth = new Date(selectedDate.getFullYear(), selectedDate.getMonth(), 1);
        const lastDayOfMonth = new Date(selectedDate.getFullYear(), selectedDate.getMonth() + 1, 0);
        const startDateString = firstDayOfMonth.toISOString();
        const endDateString = lastDayOfMonth.toISOString();
        startDate = H.formatDate(startDateString, 'YYYY-MM-DD 00:00');
        endDate = H.formatDate(endDateString, 'YYYY-MM-DD 23:59');
    } else {
        if (item.value.tanggal) {
            if (item.value.tanggal[0]) {
                startDate = H.formatDate(item.value.tanggal[0], 'YYYY-MM-DD 00:00')
            }
            if (item.value.tanggal[1]) {
                endDate = H.formatDate(item.value.tanggal[1], 'YYYY-MM-DD 23:59')
            } else {
                endDate = H.formatDate(item.value.tanggal[0], 'YYYY-MM-DD 23:59')
            }
        }
    }

    // let dari = `?dari=${moment(item.value.periode.start).format('YYYY-MM-DD')}`
    // let sampai = `&sampai=${moment(item.value.periode.end).format('YYYY-MM-DD')}`
    let nohistoris = item.value.nohistoris ? `&nohistoris=${item.value.nohistoris}` : ''
    let keterangan = item.value.keterangan ? `&keterangan=${item.value.keterangan}` : ''
    // let idPegawai = item.value.isdokter == true ? `&idPegawai=${useUserSession().getUser().pegawai.id}` : ''

    item.value.totalAll = 0
    await useApi().get(`bendahara/daftar-bku?tglAwal=${startDate}&tglAkhir=${endDate}`).then((response) => {
        response.data.forEach((element: any, i: any) => {
            element.no = i + 1
            // element.tglStruk = H.formatDateToLocalString(element.tglStruk)
        });

        dataPenerimaan.value.loading = false
        item.value.jumlahALL = response.jumlahD
        item.value.saldolama = response.saldolama
        item.value.saldoAkhir = response.saldoAkhir
        item.value.jumlahKredit = response.jumlahK
        dataPenerimaan.value = response.data

    })
}
const fetchDropdown = () => {
    useApi().get(
        `/bendahara/get-list-bayar`).then((response: any) => {
            d_sumberDana.value = response.asalproduk.map((e: any) => { return { label: e.asalproduk, value: e.id, default: e } })
            d_kelompokTransaksi.value = response.kelompoktransaksi.map((e: any) => { return { label: e.kelompoktransaksi, value: e.id, default: e } })
            d_kelompokPengeluaran.value = response.pengeluaran.map((e: any) => { return { label: e.pengeluaran, value: e.id, default: e } })
        })
}

const tambahPenerimaan = () => {
    item.value.penerimaan = true
    modalInput.value = true
}
const tambahPengeluaran = () => {
    modalPengeluaran.value = true
}
const isMozilla = () => {
    return navigator.userAgent.indexOf('Firefox') !== -1;
}

const changeNomi =async (e: any) => {
    if (isMozilla()) { await sleep(1000) }
    item.value.nominal = H.unFormatRupiah(item.value.nominal)
    item.value.terbilang = H.terbilang(parseFloat(item.value.nominal ));
}

const editPengeluaranBP = async (e: any) => {

    item.value.norec_sh = e.norec_sh
    item.value.nobukti = e.nobukti
    item.value.sumberdana = e.asalprodukfk
    item.value.pengeluaran = e.idJenisTransaksi
    item.value.nominal = e.kredit
    item.value.keterangans = e.keterangan
    item.value.tglbku = e.tglStruk
    modalPengeluaran.value = true
   
}

const editPenerimaanBP = async (e: any) => {
    
    item.value.norec_sh = e.norec_sh
    item.value.nobukti = e.nobukti
    item.value.sumberdana = e.asalprodukfk
    item.value.kelompoktransaksi = e.idJenisTransaksi
    item.value.nominal = e.debit
    item.value.keterangans = e.keterangan
    item.value.tglbku = e.tglStruk
    modalInput.value = true
   
}


const simpanBKU = async () => {
    if (!item.value.kelompoktransaksi) {
        H.alert('error', 'Kelompok Transaksi Harus Dipilih')
        return
    }
    if (!item.value.nominal) {
        H.alert('error', 'Nominal Penerimaan Harus Diisi')
        return
    }
    if (!item.value.sumberdana) {
        H.alert('error', 'Sumber Dana harus Diisi')
        return
    }
    if (!item.value.keterangans) {
        H.alert('error', 'Keterangan Transaksi Harus Diisi')
        return
    }
    let formData = {
        'norec_sh': item.value.norec_sh ? item.value.norec_sh : '',
        'norec_sc': item.value.norec_sc ? item.value.norec_sc : '',
        'nobukti': item.value.nobukti ? item.value.nobukti : null,
        'tglbku': moment(item.value.tglbku).format("YYYY-MM-DD HH:mm:ss"),
        'penerimaan': item.value.penerimaan,
        'totalSetor': item.value.nominal,
        'jenisTransaksi': item.value.kelompoktransaksi,
        'keterangan': item.value.keterangans ? item.value.keterangans : null,
        'kdperkiraan': item.value.kdperkiraan ? item.value.kdperkiraan : null,
        'sumberdana': item.value.sumberdana,
        'caraBayar': 1,
        'detailBank': 'KOSONG',
    }
    isLoadingBtn.value = true
    await useApi().post('/bendahara/save-bku', formData).then((r) => {
        isLoadingBtn.value = false
        clear()
        reload()
        modalInput.value = false
    }).catch((e: any) => {
        isLoadingBtn.value = false
    })

}

const simpanPengeluaran = async () => {
    if (!item.value.pengeluaran) {
        H.alert('error', 'Kelompok Transaksi Harus Dipilih')
        return
    }
    if (!item.value.nominal) {
        H.alert('error', 'Nominal Penerimaan Harus Diisi')
        return
    }
    if (!item.value.sumberdana) {
        H.alert('error', 'Sumber Dana harus Diisi')
        return
    }
    if (!item.value.keterangans) {
        H.alert('error', 'Keterangan Transaksi Harus Diisi')
        return
    }
    let formData = {
        'norec_sh': item.value.norec_sh ? item.value.norec_sh : '',
        'norec_sc': item.value.norec_sc ? item.value.norec_sc : '',
        'nobukti': item.value.nobukti ? item.value.nobukti : null,
        'tglbku': item.value.tglbku,
        'penerimaan': false,
        'totalSetor': item.value.nominal,
        'jenisTransaksi': item.value.pengeluaran,
        'keterangan': item.value.keterangans ? item.value.keterangans : null,
        'kdperkiraan': item.value.kdperkiraan ? item.value.kdperkiraan : null,
        'sumberdana': item.value.sumberdana,
        'caraBayar': 1,
        'detailBank': 'KOSONG',
    }
    isLoadingBtn.value = true
    await useApi().post('/bendahara/save-bku', formData).then((r) => {
        isLoadingBtn.value = false
        clear()
        reload()
        modalPengeluaran.value = false
    }).catch((e: any) => {
        isLoadingBtn.value = false
    })

}
const dialogHapus = (e: any) => {
    confirm.require({
        message: 'Apakah anda yakin menghapus data ini ?',
        header: 'Konfirmasi Hapus Data',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            deleteKas(e)
        },
        reject: () => { },
    })
}

const deleteKas = async (e: any) => {

    await useApi().post('/bendahara/delete-bku-bk', { 'norec_struk_histori': e.norec_sh }).then((response) => {
        fetchDana()
    }).catch((err: any) => {

    })

}
const reload = () => {
    fetchDana()
}
const clear = () => {
    delete item.value.penerimaan
    delete item.value.nobukti
    delete item.value.nominal
    delete item.value.terbilang
    delete item.value.kelompoktransaksi
    delete item.value.keterangans
    delete item.value.sumberdana
    delete item.value.terbilang

}

const cari = async () => {
    fetchDana()
}

const kembaliKeun = () => {
    clear()
    modalInput.value = false
    modalPengeluaran.value = false
}

const exportExcel = () => {
    const worksheet = XLSX.utils.aoa_to_sheet([

        ['BUKU KAS UMUM'],
        [],
        ['Tanggal', 'No. Bukti', 'No.Histori', 'Penyetor', 'Jenis Transaksi', 'Keterangan Transaksi', 'Penerimaan (Debit)', 'Pengeluaran (Kredit)', 'Saldo', 'Transfer'],
        ...dataPenerimaan.value.map((e: any) => [
            e.tglStruk,
            e.nobukti,
            e.nohistori,
            e.penyetor,
            e.jenisTransaksi,
            e.kettransaksi,
            parseFloat(e.debit) || 0,
            parseFloat(e.kredit) || 0,
            parseFloat(e.saldo) || 0,
            parseFloat(e.nontunai) || 0,
            '',
        ]),
        [],
        ['Total Penerimaan :', item.value.jumlahALL],
        ['Total Pengeluaran :', item.value.jumlahKredit],
        ['Saldo Awal :', item.value.saldolama],
        ['Saldo Akhir :', item.value.saldoAkhir],
    ]);

    const columnWidths = [
        { wch: 14 },
        { wch: 20 },
        { wch: 25 },
        { wch: 10 },
        { wch: 10 },
        { wch: 10 },
        { wch: 10 },
        { wch: 10 },
        { wch: 10 },
    ];
    worksheet['!cols'] = columnWidths;
    const cellRef = XLSX.utils.encode_cell({ r: 0, c: 0 });
    worksheet[cellRef] = { v: 'BUKU KAS UMUM', s: { alignment: { horizontal: 'center', vertical: 'center' } } };

    const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 0, c: 8 } };

    const mergeSubtitle1 = { s: { r: 1, c: 0 }, e: { r: 1, c: 8 } };

    worksheet['!merges'] = [mergeTitle, mergeSubtitle1];
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, 'data');

    const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
    saveAsExcelFile(excelBuffer, 'products');
}

const saveAsExcelFile = (buffer: any, fileName: string) => {
    let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
    let EXCEL_EXTENSION = '.xlsx';
    const data: Blob = new Blob([buffer], {
        type: EXCEL_TYPE
    });
    // window.open(_url, EXCEL_EXTENSION).focus();
    const desiredFileName = 'buku-kas-umum' + EXCEL_EXTENSION;
    const link = document.createElement('a');
    link.href = window.URL.createObjectURL(data);
    link.download = desiredFileName;
    link.click();
    window.URL.revokeObjectURL(link.href);
}



fetchDana()
fetchDropdown()
</script>
  
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/custom/config';

.user-grid-v2 {
    .columns {
        margin-left: -0.5rem !important;
        margin-right: -0.5rem !important;
        margin-top: -0.5rem !important;
    }

    .column {
        padding: 0.5rem !important;
    }

    .grid-item {
        @include vuero-s-card;

        text-align: center;

        >.v-avatar {
            display: block;
            margin: 0 auto 4px;
        }

        h3 {
            font-family: var(--font-alt);
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-text);
        }

        p {
            font-size: 0.85rem;
        }

        .people {
            display: flex;
            justify-content: center;
            padding: 8px 0 30px;

            .v-avatar {
                margin: 0 4px;
            }
        }

        .buttons {
            display: flex;
            justify-content: space-between;

            .button {
                width: calc(50% - 4px);
                color: var(--light-text);

                &:hover,
                &:focus {
                    border-color: var(--fade-grey-dark-4);
                    color: var(--primary);
                    box-shadow: var(--light-box-shadow);
                }
            }
        }
    }

    .grid-item-wrap {
        border: 1px solid var(--fade-grey-dark-3);
        border-radius: var(--radius-large);
        transition: all 0.3s; // transition-all test

        .grid-item-head {
            background: #fafafa;
            border-radius: var(--radius-large) 6px 0 0;
            padding: 20px;

            .flex-head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 12px;

                .meta {
                    span {
                        display: flex;

                        &:first-child {
                            font-family: var(--font-alt);
                            font-weight: 600;
                            font-size: 0.95rem;
                            color: var(--dark-text);
                        }

                        &:nth-child(2) {
                            font-size: 0.9rem;
                            color: var(--light-text);
                        }
                    }
                }

                .status-icon {
                    height: 28px;
                    width: 28px;
                    min-width: 28px;
                    border-radius: var(--radius-rounded);
                    border: 1px solid var(--fade-grey-dark-3);
                    display: flex;
                    align-items: center;
                    justify-content: center;

                    &.is-success {
                        background: var(--success);
                        border-color: var(--success);
                        color: var(--white);
                    }

                    &.is-warning {
                        background: var(--orange);
                        border-color: var(--orange);
                        color: var(--white);
                    }

                    &.is-danger {
                        background: var(--danger);
                        border-color: var(--danger);
                        color: var(--white);
                    }

                    i {
                        font-size: 8px;
                    }
                }
            }

            .buttons {
                display: flex;
                justify-content: space-between;
                margin-bottom: 0;

                .button,
                .v-button {
                    width: calc(50% - 4px);
                    color: var(--light-text);
                    margin-bottom: 0;

                    &:hover,
                    &:focus {
                        border-color: var(--fade-grey-dark-4);
                        color: var(--primary);
                        box-shadow: var(--light-box-shadow);
                    }
                }
            }
        }

        .grid-item {
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border: none;
        }
    }
}

.is-dark {
    .user-grid {
        .grid-item {
            @include vuero-card--dark;
        }
    }

    .user-grid-v2 {
        .grid-item-wrap {
            border-color: var(--dark-sidebar-light-12);

            .grid-item-head {
                background: var(--dark-sidebar-light-4);
            }
        }
    }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
    .user-grid-v2 {
        .columns {
            display: flex;

            .column {
                min-width: 50% !important;
            }
        }
    }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
    .user-grid-v2 {
        .columns {
            .column {
                min-width: 33.3% !important;
            }
        }
    }
}



.is-dark {
    .options {
        .option {
            .indicator {
                background: var(--primary);
            }

            input {
                &:checked {
                    ~.indicator {
                        transform: scale(1);
                    }

                    ~.option-inner {
                        border-color: var(--primary) !important;

                        i {
                            color: var(--primary);
                        }
                    }
                }
            }

            .option-inner {
                background-color: var(--dark-sidebar-light-2) !important;
                border-color: var(--dark-sidebar-light-12) !important;

                h4 {
                    color: var(--dark-dark-text);
                }
            }
        }
    }
}

.options {
    width: 100%;
    display: block;
    flex-wrap: wrap;
    margin-left: -0.5rem;
    margin-right: -0.5rem;

    .option {
        position: relative;
        // width: calc(33.3% - 1rem);
        margin: 0.5rem;

        &:focus-within {
            border-radius: 4px;
            outline-offset: var(--accessibility-focus-outline-offset);
            outline-width: var(--accessibility-focus-outline-width);
            outline-style: var(--accessibility-focus-outline-style);
            outline-color: var(--accessibility-focus-outline-color);
        }

        input {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            z-index: 1;
            opacity: 0;
            cursor: pointer;

            &:checked {
                ~.indicator {
                    transform: scale(1);
                }

                ~.option-inner {
                    border-color: var(--primary);
                    box-shadow: var(--light-box-shadow);

                    i {
                        color: var(--primary);
                    }
                }
            }
        }

        .indicator {
            position: absolute;
            top: 1rem;
            right: 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 20px;
            width: 20px;
            color: var(--white);
            background: var(--primary);
            border-radius: 50%;
            transform: scale(0);
            transition: transform 0.3s;

            svg {
                height: 14px;
                width: 14px;
                stroke-width: 3px;
            }
        }

        .option-inner {
            padding: 1.5rem;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            transition: border 0.3s, box-shadow 0.3s;

            h4 {
                color: var(--dark-text);
                font-weight: 600;
                font-family: var(--font-alt);
            }

            p {
                font-size: 0.9rem;
            }

            i {
                font-size: 2.25rem;
                color: var(--light-text);
                margin-bottom: 0.25rem;
            }
        }
    }
}

.user-grid-v2 .grid-item-wrap .grid-item-head .flex-head .meta span:nth-child(3) {
    font-size: 0.9rem;
    color: var(--light-text);
}

.h-toggle {
    margin-top: 15px;
    height: 30px;
    text-align: center;
    margin-right: 0;
}

.h-toggle .toggler .inactive {
    background: var(--white);
    border-color: var(--light-text);
    color: var(--light-text);
    opacity: 1;
    z-index: 1;
}

.h-toggle .toggler .active {
    background: var(--white);
    border-color: var(--success);
    color: var(--success);
    opacity: 0;
    z-index: 0;
}

.inbox-widget-2 .sender-block .exerpt h5 {
    color: var(--danger) !important;
}

.media-flex-center .flex-meta span:nth-child(2),
.media-flex-center .flex-meta>a:nth-child(2) {

    color: var(--dark);
    font-weight: bold;
}

.snack .snack-text {
    font-size: 0.8rem;
}

.s-card .card-inner {
    padding-top: 0.75rem;
}

.media-flex-center .flex-meta span:first-child,
.media-flex-center .flex-meta>a:first-child {
    text-overflow: ellipsis;
    overflow: hidden;
    width: 250px;
    height: 1.2rem;
    white-space: nowrap;
}

.hr-dashboard {
    .block-header {
        display: flex;
        border-radius: 16px;
        padding: 50px;
        background: var(--primary);
        font-family: var(--font);
        box-shadow: var(--primary-box-shadow);

        .left,
        .right {
            width: 30%;
        }

        .center {
            display: flex;
            flex-direction: column;
            width: 40%;
            padding-right: 30px;
            margin-right: 30px;
            border-right: 1px solid var(--primary-light-10);

            .block-text {
                margin-bottom: 16px;
            }

            .candidates {
                margin-top: auto;

                >.v-avatar {
                    margin-right: 10px;
                }

                button {
                    height: 40px;
                    width: 40px;
                    display: inline-flex;
                    justify-content: center;
                    align-items: center;
                    border-radius: 10px;
                    background: var(--white);
                    color: var(--light-text);
                    border: none;
                    cursor: pointer;
                    transition: all 0.3s; // transition-all test

                    svg {
                        height: 18px;
                        width: 18px;
                    }
                }
            }
        }

        .left {
            display: flex;
            justify-content: center;
            align-items: center;

            .current-user {
                .v-avatar {
                    margin-bottom: 1rem;
                }

                h3 {
                    font-family: var(--font-alt);
                    font-weight: 700;
                    font-size: 1.8rem;
                    color: var(--white);
                    line-height: 1.2;
                }
            }
        }

        .right {
            display: flex;
            flex-direction: column;

            .button {
                margin-top: auto;
            }
        }

        .block-heading {
            font-family: var(--font-alt);
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--white);
            margin-bottom: 4px;
        }

        .block-text {
            font-family: var(--font);
            font-size: 0.9rem;
            color: var(--white);
            margin-bottom: 16px;
        }

        .header-meta {
            margin-left: 0;
            padding-right: 30px;

            h3 {
                color: var(--smoke-white);
                font-family: var(--font-alt);
                font-weight: 700;
                font-size: 1.3rem;
                max-width: 280px;
            }

            p {
                font-weight: 400;
                color: var(--smoke-white-dark-2);
                margin-bottom: 16px;
                max-width: 320px;
            }

            .action-link {
                span {
                    font-size: 0.8rem;
                    text-transform: uppercase;
                    margin-right: 6px;
                }

                i {
                    font-size: 12px;
                }
            }
        }
    }

    .tabs-wrapper.is-slider .tabs,
    .tabs-wrapper-alt.is-slider .tabs {
        position: relative;
        background: var(--fade-grey-light-2);
        border: 1px solid var(--fade-grey);
        max-width: 300px;
        height: 35px;
        border-bottom: none;

    }

    .search-menu {
        height: 56px;
        white-space: nowrap;
        display: flex;
        flex-shrink: 0;
        align-items: center;
        background-color: white;
        border-radius: 8px;
        width: 100%;
        padding-left: 0.75rem;

        >div:not(:last-of-type) {
            border-right: 1px solid var(--search-border-color);
        }

        .search-bar {
            height: 55px;
            width: 100%;
            position: relative;
            display: flex;
            align-items: center;
            padding-right: 1.5rem;

            .field {
                width: 100%;
            }

            .multiselect-tags {
                padding-left: 2.5rem;
            }
        }

        .search-location,
        .search-job,
        .search-salary {
            display: flex;
            align-items: center;
            width: 50%;
            font-size: 14px;
            font-weight: 500;
            padding: 0 25px;
            height: 100%;
            font-family: var(--font);

            input {
                width: 100%;
                height: 90%;
                display: block;
                font-family: var(--font);
                color: var(--input-color);
                background-color: transparent;
                border: none;
            }

            svg {
                margin-right: 0.5rem;
                width: 18px;
                color: var(--primary);
                flex-shrink: 0;
            }
        }

        .search-button {
            background-color: var(--primary);
            min-width: 100px;
            height: 56px;
            border: none;
            font-weight: 500;
            font-family: var(--font);
            padding: 0 1rem;
            border-radius: 0 0.75rem 0.75rem 0;
            color: white;
            cursor: pointer;
            margin-left: auto;
        }
    }

    .search-widget {
        flex: 1;
        display: inline-block;
        width: 100%;
        padding: 12px;
        background-color: var(--white);
        border-radius: 16px;
        border: 1px solid var(--fade-grey-dark-3);
        transition: all 0.3s;
    }

    .feed-settings {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 0;

        h3 {
            font-family: var(--font-alt);
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-text);
        }

        .button {
            font-size: 0.8rem;
            border-radius: 8px;
            margin-right: 4px;

            &.is-selected {
                background: var(--primary);
                color: var(--white);
                border-color: var(--primary);
                box-shadow: var(--primary-box-shadow);
            }
        }
    }

    .side-text {
        h3 {
            font-family: var(--font-alt);
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-text);
            margin-bottom: 8px;
        }

        p {
            font-size: 0.95rem;
            margin-bottom: 8px;
        }

        .action-link {
            font-size: 0.9rem;
        }
    }

    .recent-rookies {
        .recent-rookies-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;

            h3 {
                font-family: var(--font-alt);
                font-size: 1.1rem;
                font-weight: 600;
                color: var(--dark-text);
            }
        }

        .user-grid {
            &.user-grid-v4 {
                .grid-item {
                    @include vuero-l-card;
                }
            }
        }
    }
}

.user-grid {
    .columns {
        margin-left: -0.5rem !important;
        margin-right: -0.5rem !important;
        margin-top: -0.5rem !important;
    }

    .column {
        padding: 0.5rem !important;
    }

    .grid-item {
        position: relative;
        @include vuero-s-card;

        text-align: center;

        &:hover,
        &:focus {
            .button-wrap {
                >div {
                    a {
                        opacity: 1;
                        pointer-events: all;
                    }
                }
            }
        }

        .dropdown {
            position: absolute;
            top: 10px;
            right: 10px;
            text-align: left;
        }

        >.v-avatar {
            display: block;
            margin: 0 auto 4px;
        }

        h3 {
            font-family: var(--font-alt);
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-text);
        }

        p {
            font-size: 0.85rem;
        }

        .button-wrap {
            margin: 20px 0 0;

            .v-button {
                width: 100%;
                max-width: 140px;
                margin: 0 auto;
            }

            >div {
                margin: 6px 0 0;

                a {
                    opacity: 0;
                    pointer-events: none;
                    color: var(--light-text);
                    font-weight: 500;
                    font-size: 0.9rem;
                    transition: opacity 0.3s, color 0.3s;

                    &:hover,
                    &:focus {
                        color: var(--primary);
                    }
                }
            }
        }
    }
}

.user-grid .grid-item h3 {
    font-family: var(--font-alt);
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--dark-text);
}

.is-dark {
    .user-grid {
        .grid-item {
            @include vuero-card--dark;
        }
    }

    .hr-dashboard {
        .block-header {
            background: var(--dark-sidebar);
            box-shadow: none;

            .center {
                border-color: var(--dark-sidebar-light-10);

                .candidates {
                    button {
                        background: var(--dark-sidebar-light-10);
                        border: 1px solid transparent;
                        transition: all 0.3s; // transition-all test

                        &:hover {
                            border-color: var(--primary);

                            svg {
                                color: var(--primary);
                            }
                        }
                    }
                }
            }
        }

        .feed-settings {
            .button {
                &.is-selected {
                    background: var(--primary) !important;
                    border-color: var(--primary) !important;
                    box-shadow: var(--primary-box-shadow) !important;
                    color: var(--white) !important;
                }
            }
        }

        .recent-rookies {
            .user-grid {
                &.user-grid-v4 {
                    .grid-item {
                        @include vuero-card--dark;
                    }
                }
            }
        }
    }
}

.list-view-v1 {
    .list-view-item {
        @include vuero-r-card;

        margin-bottom: 16px;
        padding: 16px;

        .list-view-item-inner {
            display: flex;
            align-items: center;

            .meta-left {
                margin-left: 16px;

                h3 {
                    font-family: var(--font-alt);
                    color: var(--dark-text);
                    font-weight: 600;
                    font-size: 1rem;
                    line-height: 1;
                }

                >span:not(.tag) {
                    font-size: 0.9rem;
                    color: var(--light-text);

                    svg {
                        height: 12px;
                        width: 12px;
                    }
                }
            }

            .meta-right {
                margin-left: auto;
                display: flex;
                justify-content: flex-end;
                align-items: center;

                .tags {
                    margin-right: 30px;
                    margin-bottom: 0;

                    .tag {
                        margin-bottom: 0;
                    }
                }

                .stats {
                    display: flex;
                    align-items: center;
                    margin-right: 30px;

                    .stat {
                        display: flex;
                        align-items: center;
                        flex-direction: column;
                        text-align: center;
                        color: var(--light-text);

                        >span {
                            font-family: var(--font);

                            &:first-child {
                                font-size: 1.2rem;
                                font-weight: 600;
                                color: var(--dark-text);
                                line-height: 1.4;
                            }

                            &:nth-child(2) {
                                text-transform: uppercase;
                                font-family: var(--font-alt);
                                font-size: 0.75rem;
                            }
                        }

                        svg {
                            height: 16px;
                            width: 16px;
                        }

                        i {
                            font-size: 1.4rem;
                        }
                    }

                    .separator {
                        height: 25px;
                        width: 2px;
                        border-right: 1px solid var(--fade-grey-dark-3);
                        margin: 0 16px;
                    }
                }

                .network {
                    display: flex;
                    justify-content: flex-end;
                    align-items: center;
                    min-width: 145px;

                    >span {
                        font-family: var(--font);
                        font-size: 0.9rem;
                        color: var(--light-text);
                        margin-left: 6px;
                    }
                }

                .dropdown {
                    margin-left: 30px;
                }
            }
        }
    }
}

.is-dark {
    .list-view-v1 {
        .list-view-item {
            @include vuero-card--dark;

            .list-view-item-inner {
                .meta-left {
                    h3 {
                        color: var(--dark-dark-text) !important;
                    }
                }

                .meta-right {
                    .stats {
                        .stat {
                            span {
                                &:first-child {
                                    color: var(--dark-dark-text);
                                }
                            }
                        }

                        .separator {
                            border-color: var(--dark-sidebar-light-16) !important;
                        }
                    }
                }
            }
        }
    }
}

.list-view-v3 {
    .list-view-item {
        @include vuero-r-card;

        margin-bottom: 16px;
        padding: 16px;

        .list-view-item-inner {
            display: flex;
            align-items: center;

            >img {
                width: 100%;
                max-width: 60px;
                min-width: 60px;
                max-height: 60px;
                min-height: 60px;
                border-radius: var(--radius-rounded);
                border: 1px solid var(--fade-grey);
            }

            .meta-left {
                margin-left: 16px;

                h3 {
                    font-family: var(--font-alt);
                    color: var(--dark-text);
                    font-weight: 500;
                    font-size: 1.1rem;
                    line-height: 1;
                }

                >span:not(.tag) {
                    font-size: 0.9rem;
                    color: var(--light-text);

                    svg {
                        position: relative;
                        top: 1px;
                        height: 12px;
                        width: 12px;
                    }

                    .icon-separator {
                        position: relative;
                        top: -3px;
                        font-size: 5px;
                        color: var(--light-text);
                        padding: 0 8px;
                    }

                    .iconify {
                        margin-right: 0.25rem;
                    }
                }
            }

            .meta-right {
                margin-left: auto;
                display: flex;
                align-items: center;
                justify-content: flex-end;

                .buttons {
                    margin-bottom: 0;
                    margin-right: 10px;
                }
            }
        }
    }
}

.illustration-header-2 {
    display: flex;
    align-items: center;
    padding: 10px;
    border-radius: 16px;
    background: var(--primary-dark-24);
    font-family: var(--font);
    box-shadow: var(--primary-box-shadow);

    .header-image {
        position: relative;
        height: 175px;
        width: 320px;

        img {
            position: absolute;
            top: -20px;
            left: -40px;
            display: block;
            pointer-events: none;
        }
    }

    .header-meta {
        margin-left: 0;
        padding-right: 30px;

        h3 {
            color: var(--smoke-white);
            font-family: var(--font-alt);
            font-weight: 700;
            font-size: 1.3rem;
            max-width: 280px;
        }

        p {
            font-weight: 400;
            color: var(--smoke-white-dark-2);
            margin-bottom: 16px;
            max-width: 320px;
        }

        .action-link {
            span {
                font-size: 0.8rem;
                text-transform: uppercase;
                margin-right: 6px;
            }

            i {
                font-size: 12px;
            }
        }
    }
}

.is-dark {
    .list-view-v3 {
        .list-view-item {
            @include vuero-card--dark;

            .list-view-item-inner {
                >img {
                    border-color: var(--dark-sidebar-light-12);
                }

                .meta-left {
                    h3 {
                        color: var(--dark-dark-text) !important;
                    }
                }

                .meta-right {
                    .buttons {
                        .button {
                            &:nth-child(2) {
                                background: var(--dark-sidebar-light-2);
                                border-color: var(--dark-sidebar-light-8);
                                color: var(--dark-dark-text);
                                transition: color 0.3s, background-color 0.3s, border-color 0.3s,
                                    height 0.3s, width 0.3s;

                                &:hover,
                                &:focus {
                                    border-color: var(--primary);
                                    color: var(--primary);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

.tile-grid-v2 {
    .tile-grid-item {
        @include vuero-s-card;

        border-radius: 14px;
        padding: 16px;
        cursor: pointer;

        &:hover,
        &:focus {
            border-color: var(--primary);
            box-shadow: var(--light-box-shadow);
        }

        .tile-grid-item-inner {
            display: flex;
            align-items: center;

            >img {
                display: block;
                width: 50px;
                height: 50px;
                min-width: 50px;
            }

            .meta {
                margin-left: 10px;
                line-height: 1.4;

                span {
                    display: block;
                    font-family: var(--font);

                    &:first-child {
                        color: var(--dark-text);
                        font-family: var(--font-alt);
                        font-weight: 600;
                        font-size: 0.8rem;
                    }

                    &:nth-child(2) {
                        display: flex;
                        align-items: center;

                        span {
                            display: inline-block;
                            color: var(--light-text);
                            font-size: 0.5rem;
                            font-weight: 400;
                        }

                        .icon-separator {
                            position: relative;
                            font-size: 4px;
                            color: var(--light-text);
                            padding: 0 6px;
                        }
                    }
                }
            }

            .dropdown {
                margin-left: auto;
            }
        }
    }
}

.is-dark {
    .tile-grid {
        .tile-grid-item {
            @include vuero-card--dark;
        }
    }

    .tile-grid-v2 {
        .tile-grid-item {
            @include vuero-card--dark;

            &:hover,
            &:focus {
                border-color: var(--primary) !important;
            }
        }
    }
}

.speeddial-tooltip-demo .p-speeddial-direction-up.speeddial-left {
    left: 0;
    bottom: 0;
}

.speeddial-tooltip-demo .p-speeddial-direction-up.speeddial-right {
    right: 0;
    bottom: 0;
}


.speeddial-delay-demo .p-speeddial-direction-up {
    left: calc(50% - 2rem);
    bottom: 0;
}

.speeddial-mask-demo .p-speeddial-direction-up {
    right: 0;
    bottom: 0;
}


@media only screen and (min-width: 900px) {
    .illustration-header-2.large-screen {
        min-height: 235px;
    }

    .illustration-header-2 .header-image {
        width: 370px;
    }

    .illustration-header-2 {
        .header-image {
            img {
                top: -35px
            }
        }
    }
}

@media only screen and (max-width: 767px) {
    .hr-dashboard {
        .block-header {
            flex-direction: column;
            padding: 30px;

            .left,
            .center,
            .right {
                width: 100%;
            }

            .left {
                justify-content: flex-start;
                margin-bottom: 20px;
            }

            .center {
                padding-right: 0;
                margin-right: 0;
                border-right: none;
                margin-bottom: 20px;
            }
        }

        .feed-settings {
            flex-direction: column;

            h3 {
                margin-bottom: 16px;
            }
        }
    }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
    .hr-dashboard {
        .block-header {
            padding: 40px;
        }

        .side-text {
            display: none;
        }
    }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
    .hr-dashboard {
        .block-header {
            padding: 40px;

            .left {
                .current-user {
                    h3 {
                        font-size: 1.5rem;
                    }
                }
            }

            .center {
                .candidates {
                    .v-avatar {
                        &:nth-child(3) {
                            display: none;
                        }
                    }
                }
            }
        }

        .column {
            &.is-7 {
                &.is-offset-1 {
                    margin-left: 2% !important;
                    width: 64.3333% !important;
                }
            }
        }
    }
}

.f-text .multiselect-single-label-text {
    color: var(--white);
}

.multiselect.f-text .multiselect-search {
    background: var(--primary-dark-24);
}

.p-button {
    background: var(--placeholder);

    border: 1px solid var(--placeholder);
}

.jobs-dashboard {
    display: flex;
    flex-direction: column;
    margin: 0 auto;
    overflow: hidden;

    .jobs-dashboard-wrapper {
        width: 100%;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        scroll-behavior: smooth;
        overflow: auto;
    }

    .search-menu {
        height: 56px;
        white-space: nowrap;
        display: flex;
        flex-shrink: 0;
        align-items: center;
        background-color: var(--header-bg-color);
        border-radius: 8px;
        width: 100%;
        padding-left: 0.75rem;

        >div:not(:last-of-type) {
            border-right: 1px solid var(--search-border-color);
        }

        .search-bar {
            height: 55px;
            width: 100%;
            position: relative;
            display: flex;
            align-items: center;
            padding-right: 1.5rem;

            .field {
                width: 100%;
            }

            .multiselect-tags {
                padding-left: 2.5rem;
            }
        }

        .search-location,
        .search-job,
        .search-salary {
            display: flex;
            align-items: center;
            width: 50%;
            font-size: 14px;
            font-weight: 500;
            padding: 0 25px;
            height: 100%;
            font-family: var(--font);

            input {
                width: 100%;
                height: 100%;
                display: block;
                font-family: var(--font);
                color: var(--input-color);
                background-color: transparent;
                border: none;
            }

            svg {
                margin-right: 0.5rem;
                width: 18px;
                color: var(--primary);
                flex-shrink: 0;
            }
        }

        .search-button {
            background-color: var(--primary);
            min-width: 120px;
            height: 55px;
            border: none;
            font-weight: 500;
            font-family: var(--font);
            padding: 0 1rem;
            border-radius: 0 0.75rem 0.75rem 0;
            color: var(--button-color);
            cursor: pointer;
            margin-left: auto;
        }
    }

    .main-container {
        display: flex;
        flex-grow: 1;
        // padding-top: 2rem;
        padding-top: 0;

        .search-type {
            // width: 270px;
            width: 100%;
            display: flex;
            flex-direction: column;
            height: 100%;
            flex-shrink: 0;
        }

        .alert {
            background-color: var(--widget-grey);
            padding: 1.75rem;
            border-radius: 8px;

            .alert-title {
                font-size: 1rem;
                font-family: var(--font-alt);
                font-weight: 600;
                color: var(--dark-text);
                margin-bottom: 0.75rem;
            }

            .alert-subtitle {
                font-size: 13px;
                font-family: var(--font);
                color: var(--subtitle-color);
                margin-bottom: 1.5rem;
            }

            input {
                border-radius: 6px;
            }
        }

        .job-time {
            // padding-top: 1.75rem;
            padding-top: 0;


            .job-time-title {
                font-size: 0.95rem;
                font-family: var(--font-alt);
                font-weight: 600;
                color: var(--dark-text);
            }

            .type-container {
                display: flex;
                align-items: center;
                color: var(--subtitle-color);
                font-size: 13px;

                label {
                    font-size: 0.75rem;
                    margin-left: 2px;
                    display: flex;
                    align-items: center;
                    cursor: pointer;
                }

                +.type-container {
                    margin-top: 10px;
                }

                .job-number {
                    margin-left: auto;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 25px;
                    min-width: 25px;
                    background-color: var(--white);
                    color: var(--subtitle-color);
                    font-size: 0.8rem;
                    font-family: var(--font);
                    font-weight: 500;
                    padding: 0 0.25rem;
                    border-radius: 50rem;
                }
            }
        }

        .searched-jobs {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            padding-left: 2.5rem;
        }

        .searched-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;

            .searched-count {
                font-family: var(--font-alt);
                font-size: 1rem;
                font-weight: 600;
                color: var(--dark-text);
            }
        }

        .job-cards {
            padding-top: 20px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-column-gap: 1.5rem;
            grid-row-gap: 1.5rem;

            @media screen and (max-width: 1212px) {
                grid-template-columns: repeat(2, 1fr);
            }

            @media screen and (max-width: 930px) {
                grid-template-columns: repeat(1, 1fr);
            }
        }

        .job-card {
            @include vuero-l-card;

            cursor: pointer;
            transition: 0.2s;

            &:hover,
            &:focus {
                transform: translateY(-5px);
            }

            .job-card-header {
                // display: flex;
                // align-items: flex-start;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .job-card-logo {
                width: 80px;
                height: 80px;
                margin-right: -40px;
            }

            .job-card-title {
                font-family: var(--font-alt);
                font-weight: 600;
                color: var(--dark-text);
                margin-bottom: 0.75rem;
                display: flex;
                justify-content: center;
                align-items: center;

                max-height: 42px;
                overflow: hidden;
                -webkit-box-orient: vertical;
                -webkit-line-clamp: 1;
                display: -webkit-box;
                text-align: center;
            }

            .job-card-subtitle {
                color: var(--subtitle-color);
                font-family: var(--font);
                font-size: 0.95rem;
                line-height: 1.6em;
                // margin-bottom: 1rem;
                margin-top: -5px;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .job-card-buttons {
                margin-top: 1rem;

                .buttons {
                    justify-content: space-between;

                    .v-button {
                        width: 48%;
                    }
                }
            }
        }
    }
}

.is-dark {
    .jobs-dashboard {
        .job-card {
            @include vuero-card--dark;
        }

        .main-container {
            .alert {
                @include vuero-card--dark;
            }

            .job-time {
                .job-number {
                    background: var(--dark-sidebar-light-2);
                }
            }
        }
    }
}

@media screen and (max-width: 620px) {
    .job-cards {
        grid-template-columns: repeat(1, 1fr);
    }
}

@media screen and (max-width: 730px) {
    .job-cards {
        grid-template-columns: repeat(2, 1fr);
    }
}

.user-grid-v2 .grid-item-wrap .grid-item-head.is-registrasi {
    background: var(--success) !important
}

.control.has-icon.prime-auto .form-icon {
    top: 0px;
}
</style>
  