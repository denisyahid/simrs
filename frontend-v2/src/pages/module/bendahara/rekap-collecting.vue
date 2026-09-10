
<template>
    <ConfirmDialog />
    <div class="column">
        <VCard>
            <div class="column is-12">
                <div class="search-widget">
                    <div class="field">
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <h3 class="title is-5 mb-2 mr-1">Daftar Rekap Collecting Hutang Supplier</h3>
                            </div>
                            <div class="column is-3">
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
                            </div>
                            <div class="column is-3">
                                <span>Nama Supplier</span>
                                <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                                    <VControl icon="feather:search">
                                        <AutoComplete v-model="item.rekanan" :suggestions="d_Rekanan"
                                            @complete="fetchRekanan($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Supplier" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                                <span>Status</span>
                                <VField class="is-autocomplete-select pt-2">
                                    <VControl icon="feather:search">
                                        <Multiselect mode="single" v-model="item.status" :options="d_Setor"
                                            placeholder="Pilih data" :searchable="true" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column" style="margin-top: 25px; margin-left: auto:  !important;">
                                <VIconButton type="button" color="success" class="searcv-button" raised icon="fas fa-search"
                                    @click="cari()" :loading="isLoadingBtn">
                                </VIconButton>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </VCard>
    </div>

    <div class="column">
        <VCard>
            <div class="columns is-multiline">
                <!-- <div class="column is-3" style="margin-top:10px">
                    <VCardCustom :style="'padding:5px 15px;margin:0;background:#fafafa'">
                        <div :class="'label-status'">
                            <i aria-hidden="true" class="fas fa-circle"></i>
                            <span class="ml-1">Total Tagihan</span>
                        </div>
                        <small class="text-bold-custom">{{ H.formatRp(item.totalTagihan, 'Rp. ')
                        }}</small>
                    </VCardCustom>
                </div>
                <div class="column is-3" style="margin-top:10px">
                    <VCardCustom :style="'padding:5px 15px;margin:0;background:#fafafa'">
                        <div :class="'label-status'">
                            <i aria-hidden="true" class="fas fa-circle"></i>
                            <span class="ml-1">Total Sudah Dibayar</span>
                        </div>
                        <small class="text-bold-custom">{{ H.formatRp(item.totalbayar, 'Rp. ')
                        }}</small>
                    </VCardCustom>
                </div>
                <div class="column is-3" style="margin-top:10px">
                    <VCardCustom :style="'padding:5px 15px;margin:0;background:#fafafa'">
                        <div :class="'label-status'">
                            <i aria-hidden="true" class="fas fa-circle"></i>
                            <span class="ml-1">Sisa Tagihan</span>
                        </div>
                        <small class="text-bold-custom">{{ H.formatRp(item.sisaHutang, 'Rp. ')
                        }}</small>
                    </VCardCustom>
                </div> -->
                <!-- <div class="column is-8">
                    <div class="column is-12">
                        <VButton color="success" icon="fas fa-print" raised rounded style="margin-left:20px;"
                            @click="cetakLapHarian()"> Laporan Penerimaan Harian
                        </VButton>

                        <VButton color="warning" icon="fas fa-print" style="margin-left:20px;" raised rounded>
                            Laporan Penerimaan Per Transaksi
                        </VButton>
                    </div>
                </div> -->
            </div>
            <DataTable :value="dataSource" :rows="5" :rowsPerPageOptions="[5, 10, 15]" :loading="isLoading"
                class="p-datatable-sm" responsiveLayout="stack" breakpoint="960px" selectionMode="single"
                sortMode="multiple" showGridlines v-model:expanded-rows="expandedRows"
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                tableStyle="min-width: 50rem" scrollable>
                <Column :exportable="false" header="#" style="min-width: 150px" frozen>
                    <template #body="slotProps">
                        <VIconButton type="button" icon="feather:x" class="mr-2" color="danger" circle outlined raised
                            v-tooltip.bottom="'Batal Collect'" @click="batalCollect(slotProps.data)">
                        </VIconButton>
                        <VIconButton type="button" icon="fas fa-sticky-note" class="mr-2" color="info" circle outlined
                            raised v-tooltip.top="'Detail'" @click="detail(slotProps.data)">
                        </VIconButton>
                        <VIconButton type="button" icon="fas fa-arrow-right" class="mr-2" color="success" circle outlined
                            raised v-tooltip.left="'Bayar '" @click="bayarTagihan(slotProps.data)">
                        </VIconButton>

                    </template>

                </Column>
                <Column expander header="Detail" style="width: 5rem" frozen />
                <!-- <Column field="no" header="No"></Column> -->
                <Column field="tanggalcollecting" header="Tgl Collecting" style="min-width: 200px" frozen></Column>
                <Column field="nocollecting" header="No. Collecting" style="min-width: 150px" frozen></Column>
                <Column field="namarekanan" header="No. Rekanan" style="min-width: 150px" frozen></Column>
                <Column field="namalengkap" header="Petugas Collecting" style="min-width: 150px"></Column>
                <Column field="status" header="Status" style="min-width: 100px;">
                    <template #body="slotProps">
                        <Tag :value="slotProps.data.status" :severity="getLabel(slotProps.data.status)" />
                    </template>
                </Column>
                <Column field="totalharga" header="Total" style="min-width: 150px">
                    <template #body="slotProps">
                        {{ H.formatRp(slotProps.data.totalharga, 'Rp. ') }}
                    </template>
                </Column>
                <Column field="totalppn" header="Total PPN" style="min-width: 150px">
                    <template #body="slotProps">
                        {{ H.formatRp(slotProps.data.totalppn, 'Rp. ') }}
                    </template>
                </Column>
                <Column field="totaldiskon" header="Diskon" style="min-width: 150px">
                    <template #body="slotProps">
                        {{ H.formatRp(slotProps.data.totaldiskon, 'Rp. ') }}
                    </template>
                </Column>
                <Column field="totaltagihan" header="Sub Total" style="min-width: 150px">
                    <template #body="slotProps">
                        {{ H.formatRp(slotProps.data.totaltagihan, 'Rp. ') }}
                    </template>
                </Column>

                <Column field="nosbk" header="No. Bukti Bayar" style="min-width: 150px"></Column>
                <Column field="totalsudahdibayar" header="Total Dibayar" style="min-width: 150px">
                    <template #body="slotProps">
                        {{ H.formatRp(slotProps.data.totalsudahdibayar, 'Rp. ') }}
                    </template>
                </Column>
                <Column field="sisahutang" header="Total Belum Dibayar" style="min-width: 150px">
                    <template #body="slotProps">
                        {{ H.formatRp(slotProps.data.sisahutang, 'Rp. ') }}
                    </template>
                </Column>

                <template #expansion="slotProps">
                    <div class="p-3">
                        <DataTable :value="slotProps.data.details" :rows="10" showGridlines class="p-datatable-sm"
                            responsiveLayout="stack" breakpoint="960px" sortMode="multiple">
                            <Column field="no" header="No" />
                            <Column field="nostruk" header="No. Terima" />
                            <Column field="tglstruk" header="Tgl Terima" />
                            <Column field="nofaktur" header="No. Dokumen/Faktur" />
                            <Column field="tgljatuhtempo" header="Tgl Jatuh Tempo" />
                            <Column field="total" header="Total">
                                <template #body="slotProps">
                                    {{ H.formatRp(slotProps.data.total, 'Rp. ') }}
                                </template>
                            </Column>
                            <Column field="totalppn" header="Total PPN">
                                <template #body="slotProps">
                                    {{ H.formatRp(slotProps.data.totalppn, 'Rp. ') }}
                                </template>
                            </Column>
                            <Column field="totaldiskon" header="Diskon">
                                <template #body="slotProps">
                                    {{ H.formatRp(slotProps.data.totaldiskon, 'Rp. ') }}
                                </template>
                            </Column>
                            <Column field="totaltagihan" header="Sub Total">
                                <template #body="slotProps">
                                    {{ H.formatRp(slotProps.data.totaltagihan, 'Rp. ') }}
                                </template>
                            </Column>
                        </DataTable>
                    </div>

                </template>
            </DataTable>
        </VCard>
    </div>
    <Dialog v-model:visible="modalInput" modal header="Detail Tagihan" :style="{ width: '80vw' }">
        <div class="columns is-multiline">

            <div class="column is-12">
                <DataTable :value="dataDetail" tableStyle="min-width: 50rem" :paginator="true" :rows="10"
                    :rowsPerPageOptions="[5, 10, 25]" :loading="isLoading" showGridlines>
                    <template #header>
                        <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                            <span class="text-xl text-900 font-bold">Rincian Pembayaran</span>
                        </div>
                    </template>
                    <Column field="no" header="No" frozen></Column>
                    <Column field="tglsbk" header="Tanggal Bayar" style="min-width: 200px" frozen></Column>
                    <Column field="nosbk" header="No. Bukti Bayar" style="min-width: 200px;"></Column>
                    <Column field="pembayaranke" header="Pembayaran Ke" style="min-width: 100px; text-align:center;"></Column>
                    <Column field="totaldibayar" header="Total Bayar" style="min-width: 150px">
                        <template #body="slotProps">
                            {{ H.formatRp(slotProps.data.totaldibayar, 'Rp. ') }}
                        </template>
                    </Column>
                    <Column field="totaldibayarsebelumnya" header="Total Dibayar Sebelumnya" style="min-width: 150px">
                        <template #body="slotProps">
                            {{ H.formatRp(slotProps.data.totaldibayarsebelumnya, 'Rp. ') }}
                        </template>
                    </Column>
                    <Column field="totalsisahutang" header="Total Sisa Hutang" style="min-width: 150px">
                        <template #body="slotProps">
                            {{ H.formatRp(slotProps.data.totalsisahutang, 'Rp. ') }}
                        </template>
                    </Column>
                    <Column field="keterangan" header="Keterangan" style="min-width: 200px"></Column>
                    <Column field="namapegawaipembayar" header="Petugas Pembayar" style="min-width: 200px"></Column>
                    <Column field="namabank" header="Bank Tujuan" style="min-width: 200px"></Column>
                    <Column field="norekeningtujuan" header="Rekening Tujuan" style="min-width: 200px"></Column>
                    <Column field="namapegawaipenerima" header="Rekening A/N" style="min-width: 200px"></Column>
                   

                </DataTable>
            </div>

        </div>

    </Dialog>

    <Dialog v-model:visible="modalBayar" modal header="Pembayaran Tagihan" :style="{ width: '60vw' }">
        <div class="columns is-multiline">
            <div class="column is-3">
                <VDatePicker class="pt-0 pb-0 pl-0" v-model="item.tglbayar" color="green" trim-weeks mode="dateTime"
                    :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }" class="pb-0">
                        <VField>
                            <VLabel class="required-field">Tanggal Pembayaran</VLabel>
                            <VControl icon="feather:calendar">
                                <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </VField>
                    </template>
                </VDatePicker>
            </div>
            <div class="column is-3">
                <VField>
                    <VLabel>No. Collecting</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.nocollecting" type="text" class="input is-rounded" disabled />
                    </VControl>
                </VField>
            </div>

            <div class="column is-3">
                <VField class="is-autocomplete-select pt-2">
                    <VLabel class="required-field">Cara Bayar</VLabel>
                    <VControl icon="feather:search">
                        <Multiselect mode="single" v-model="item.caraBayar" :options="d_caraBayar" placeholder="Pilih data"
                            :searchable="true" />
                    </VControl>
                </VField>
            </div>
            <div class="column is-6">
                <VField>
                    <VLabel>Deskripsi Transaksi</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.deskripsiTransaksi" type="text" class="input is-rounded" disabled />
                    </VControl>
                </VField>
            </div>
            <div class="column is-6">
                <VField>
                    <VLabel>Uraian Transaksi</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.uraianTransaksi" type="text" class="input is-rounded" disabled />
                    </VControl>
                </VField>
            </div>
            <div class="column is-4">
                <VField>
                    <VField label="Biaya Admin">
                        <VControl class="prime-auto">
                            <VInput type="text" v-model="item.nominal" v-on:input="changeNomi(item.nominal)" v-mask-currency
                                placeholder="Nominal" class="is-rounded" />
                        </VControl>
                    </VField>
                </VField>
            </div>
            <div class="column is-4">
                <VField>
                    <VLabel>Terbilang</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.terbilang" type="text" class="input is-rounded" disabled />
                    </VControl>
                </VField>
            </div>
            <div class="column is-4">
                <VField>
                    <VLabel>Total Sudah Dibayar</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.totalbayarawal" type="text" class="input is-rounded" disabled />
                    </VControl>
                </VField>
            </div>
            <div class="column is-6">
                <VField>
                    <VLabel>Total Tagihan</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.tagihan" type="text" class="input is-rounded" disabled />
                    </VControl>
                </VField>
            </div>
            <div class="column is-6">
                <VField>
                    <VLabel>Tagihan Terbilang</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.terbilangTagihan" type="text" class="input is-rounded" disabled />
                    </VControl>
                </VField>
            </div>
            <div class="column is-6" v-if="item.totalbayar != null">
                <VField>
                    <VLabel class="required-field">Total Bayarss</VLabel>
                    <VControl class="prime-auto">
                        <VInput type="text" v-model="item.nominalBayar" v-on:input="changeBayar(item.nominalBayar)"
                            placeholder="Nominal" class="is-rounded" />
                    </VControl>
                </VField>
            </div>
            <div class="column is-6" v-else>
                <VField>
                    <VLabel class="required-field">Total Bayar</VLabel>
                    <VControl class="prime-auto">
                        <VInput type="text" v-model="item.nominalBayar" v-on:input="changeBayar(item.nominalBayar)"
                            placeholder="Nominal" class="is-rounded" />
                    </VControl>
                </VField>
            </div>

            <div class="column is-6">
                <VField>
                    <VLabel>Terbilang</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.terbilangBayar" type="text" class="input is-rounded" disabled />
                    </VControl>
                </VField>
            </div>
            <Divider />

            <div class="column is-4">
                <VField>
                    <VLabel>Nama Bank</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.namabank" type="text" class="input is-rounded" />
                    </VControl>
                </VField>
            </div>
            <div class="column is-4">
                <VField>
                    <VLabel>Nomor Rekening</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.norek" type="text" class="input is-rounded" />
                    </VControl>
                </VField>
            </div>
            <div class="column is-4">
                <VField>
                    <VLabel>Nama Pemilik Rekening</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.namapemilik" type="text" class="input is-rounded" />
                    </VControl>
                </VField>
            </div>
        </div>
        <template #footer>
            <VButton icon="feather:refresh-cw rem-100" light dark-outlined @click="kembaliKeun()">
                Batal
            </VButton>
            <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoadingBtn"
                @click="simpanBayar()"> Bayar
            </VButton>
        </template>
    </Dialog>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useUserSession } from '/@src/stores/userSession'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import * as H from '/@src/utils/appHelper'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { useToaster } from '/@src/composable/toaster'
import Dialog from 'primevue/dialog';
import AutoComplete from 'primevue/autocomplete';
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import moment from 'moment'
import Tag from 'primevue/tag';
import Divider from 'primevue/divider';
import sleep from '/@src/utils/sleep'
useHead({
    title: 'Rekap Collecting Suplier - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const item: any = ref({
    periode: reactive({
        start: new Date(),
        end: new Date(),
    }),
    tglsetor: new Date(),
    tglbayar: new Date(),
    totalbayarawal: 0,
    tglCollect: new Date(),
})

const listColor: any = ref(Object.keys(useThemeColors()))


const route = useRoute()
const { y } = useWindowScroll()

const dataSource = ref([])
const dataBayar = ref([])
const dataDetail = ref([])
const d_Rekanan = ref([])
const modalInput = ref(false)
const modalBayar = ref(false)
const modalConfirm = ref(false)
const modelCheck: any = ref([]);
const isLoadingBtn = ref(false)
const isLoadingBB = ref(false)
const isLoading = ref(false)
const expandedRows = ref();
const d_caraBayar: any = ref([])
const d_caraSetor: any = ref([])
const confirm = useConfirm()
const modalCollect = ref(false)

const d_Setor = [
    {
        label: 'BELUM LUNAS',
        value: '1',
    },
    {
        label: 'LUNAS',
        value: '2',
    },
]

const currentPage: any = ref({
    limit: 5,
    rows: 50
})

currentPage.value.page = computed(() => {
    try {
        return Number.parseInt(route.query.page as string) || 1
    } catch { }
    return 1
})

const fetchData = async () => {
    isLoading.value = true;
    let tglAwal = `?tglAwal=${moment(item.value.periode.start).format('YYYY-MM-DD')}`
    let tglAkhir = `&tglAkhir=${moment(item.value.periode.end).format('YYYY-MM-DD')}`

    let rekanan = item.value.rekanan ? `&rekanan=${item.value.rekanan.value}` : ''

    item.value.sisaHutang = 0
    await useApi().get(`bendahara/list-collecting-suplier${tglAwal}${tglAkhir}`).then((response: any) => {
        response.daftar.forEach((element: any, i: any) => {
            expandedRows.value = element.details.forEach((data: any, i: any) => {
                data.no = i + 1
            })
            element.no = i + 1
        });
        isLoading.value = false;
        dataSource.value = response.daftar
    })
}

const fetchDropdown = () => {
    useApi().get(
        `/bendahara/get-list-bayar`).then((response: any) => {
            d_caraBayar.value = response.carabayar.map((e: any) => { return { label: e.carabayar, value: e.id, default: e } })
            d_caraSetor.value = response.carasetor.map((e: any) => { return { label: e.carasetor, value: e.id, default: e } })
        })
}

const getLabel = (status: any) => {
    switch (status) {
        case 'BELUM BAYAR':
            return 'danger';
        case 'LUNAS':
            return 'success';
        case 'BAYAR SEBAGIAN':
            return 'warning';
    }
}

const fetchDetailTagihan = async (norec: any) => {
    await useApi().get(`bendahara/riwayat-bayar-collect?norec=${norec}`).then((response) => {
        response.forEach((element: any, i: any) => {
            element.no = i + 1
        });
        dataDetail.value = response
    })
}

const isMozilla = () => {
    return navigator.userAgent.indexOf('Firefox') !== -1;
}

const changeNomi =async (e: any) => {
    if (isMozilla()) { await sleep(1000) }
    item.value.nominal = H.unFormatRupiah(e)

    item.value.terbilang = H.terbilang(parseFloat(item.value.nominal));
}

const changeBayar =async (e: any) => {
    if (isMozilla()) { await sleep(1000) }
    item.value.nominalBayar = H.unFormatRupiah(e)
    item.value.terbilangBayar = H.terbilang((parseFloat(item.value.nominalBayar)));
}

const detailRekanan = async (rekananfk: any) => {
    const response = await useApi().get(`bendahara/detail-rekanan-tagihan?idrekanan=${rekananfk}`)
}

const riwayatPembayaran = async (nostruk: any) => {
    await useApi().get(`bendahara/get-riwayat-bayar?nostruk=${nostruk}`).then((response) => {
        response.data.forEach((element: any, i: any) => {
            element.no = i + 1
        });
        dataBayar.value = response.data

    })
}

const detail = (e: any) => {

    fetchDetailTagihan(e.norec)

    modalInput.value = true
}

const bayarTagihan = (e: any) => {

    fetchDetailTagihan(e.norec)
    detailRekanan(e.rekananfk)

    item.value.norec = e.norec
    item.value.sisaHutang = 0

    item.value.nocollecting = e.nocollecting
    item.value.nostruk = e.nostruk

    item.value.totalbayarawal = e.totalsudahdibayar
    item.value.tagihan = H.unFormatRupiah(parseFloat(e.totaltagihan))
    item.value.terbilangTagihan = H.terbilang(item.value.tagihan);
    item.value.deskripsiTransaksi = 'PEMBAYARAN TAGIHAN SUPLIER A/N' + e.namarekanan
    item.value.uraianTransaksi = 'PEMBAYARAN TAGIHAN SUPLIER'

    modalBayar.value = true
}

const kembaliKeun = () => {
    modalBayar.value = false
}
const simpanBayar = async () => {
    // debugger
    let json = {
        'sbk': {
            'nostruk': item.value.norec,
            'nosbk': item.value.nosbk ? item.value.nosbk : '',
            'carabayar': item.value.caraBayar,
            'kelompoktransaksi': 107,
            'keteranganlainnya': item.value.deskripsiTransaksi,
            'tagihan': item.value.tagihan,
            'totalbayar': item.value.nominalBayar,
            'tglsbk': moment(item.value.tglbayar).format('YYYY-MM-DD HH:mm'),
            'bankrekanan': item.value.namabank ? item.value.namabank : '',
            'rekeningrekanan': item.value.norek ? item.value.norek : '',
            'pemilikrekanan': item.value.namapemilik ? item.value.namapemilik : '',
            'sisautang': item.value.sisaHutang ? item.value.sisaHutang : 0,
            'keterangan': item.value.deskripsiTransaksi,
            'biayaadmin': item.value.nominal ? item.value.nominal : 0

        }

    }
    isLoadingBtn.value = true
    await useApi().post(
        `bendahara/save-bayar-collecting`, json).then((response: any) => {
            isLoadingBtn.value = false
            modalBayar.value = false
            fetchData()
        }).catch((e: any) => {
            isLoadingBtn.value = false
        })
}




const fetchRekanan = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/rekanan_m?select=id,namarekanan&param_search=namarekanan&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Rekanan.value = response
    })
}





const cari = () => {
    fetchData()
}

const clear = () => {

}

watch(
    () => item.value.nominalBayar,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            item.value.nominalBayar = newValue
        }
        item.value.sisaHutang = item.value.tagihan - parseFloat(newValue);
    }
)

const batalCollect = (e: any) => {
    if(e.status == 'LUNAS'){
        H.alert('warning','Tagihan Sudah Dibayar')
        return
    }
    confirm.require({
        message: 'Apakah anda yakin membatalkan Collecting Tagihan ?',
        header: 'Konfirmasi Batal Collecting Tagihan',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            deleteCollect(e)
        },
        reject: () => { },
    })
}

const deleteCollect = async (e: any) => {

    await useApi().post('/bendahara/delete-collect-sup', { 'norec': e.norec , 'strukcollectingfk': e.norec }).then((response) => {
        fetchData()
    }).catch((err: any) => {

    })

}






fetchData()
fetchDropdown()



watch(currentPage.value, () => {
    fetchData()
})



</script>
  
  
  
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/timeline-css';



.fs-075 {
    font-size: 0.9rem;
}


.is-navbar {
    .form-layout {
        margin-top: 30px;
    }
}

.form-layout {
    // max-width: 740px;
    margin: 0 auto;

    &.is-separate {
        // max-width: 1040px;

        .form-outer {
            background: none;
            border: none;

            .form-body {
                display: flex;

                .form-section {
                    flex-grow: 2;
                    padding: 10px;
                    width: 50%;

                    .form-section-inner {
                        @include vuero-s-card;

                        padding: 40px;

                        &.has-padding-bottom {
                            padding-bottom: 60px;
                            height: 100%;
                        }

                        >h3 {
                            font-family: var(--font-alt);
                            font-size: 1.2rem;
                            font-weight: 600;
                            color: var(--dark-text);
                            margin-bottom: 30px;
                        }

                        .columns {
                            .column {
                                padding-top: 0.25rem;
                                padding-bottom: 0.25rem;
                            }
                        }

                        .radio-boxes {
                            display: flex;
                            justify-content: space-between;
                            margin-left: -8px;
                            margin-right: -8px;

                            .radio-box {
                                position: relative;
                                width: calc(50% - 16px);
                                margin: 8px;

                                &:focus-within {
                                    border-radius: 3px;
                                    outline-offset: var(--accessibility-focus-outline-offset);
                                    outline-width: var(--accessibility-focus-outline-width);
                                    outline-style: var(--accessibility-focus-outline-style);
                                    outline-color: var(--primary);
                                }

                                input {
                                    position: absolute;
                                    top: 0;
                                    left: 0;
                                    height: 100%;
                                    width: 100%;
                                    opacity: 0;
                                    cursor: pointer;

                                    &:checked {
                                        +.radio-box-inner {
                                            background: var(--primary);
                                            border-color: var(--primary);
                                            box-shadow: var(--primary-box-shadow);

                                            .fee,
                                            p {
                                                color: var(--smoke-white);
                                            }
                                        }
                                    }
                                }

                                .radio-box-inner {
                                    background: var(--white);
                                    border: 1px solid var(--fade-grey-dark-3);
                                    text-align: center;
                                    border-radius: var(--radius);
                                    font-family: var(--font);
                                    font-weight: 600;
                                    font-size: 0.9rem;
                                    transition: color 0.3s, background-color 0.3s, border-color 0.3s,
                                        height 0.3s, width 0.3s;
                                    padding: 30px 20px;

                                    .fee {
                                        font-family: var(--font);
                                        font-weight: 700;
                                        color: var(--dark-text);
                                        font-size: 2.4rem;
                                        line-height: 1;

                                        span {
                                            &::after {
                                                content: '$';
                                                position: relative;
                                                top: -10px;
                                                font-size: 1.5rem;
                                            }
                                        }
                                    }

                                    p {
                                        font-family: var(--font-alt);
                                    }
                                }
                            }
                        }

                        .control {
                            >p {
                                padding-top: 12px;

                                >span {
                                    display: block;
                                    font-size: 0.9rem;

                                    span {
                                        font-weight: 500;
                                        color: var(--dark-text);
                                    }
                                }
                            }
                        }
                    }

                    .form-section-outer {
                        .checkboxes {
                            padding: 16px 0;

                            .checkbox {
                                padding: 0;
                                font-size: 0.9rem;
                            }
                        }

                        .button-wrap {
                            .button {
                                min-height: 60px;
                                font-size: 1.05rem;
                                font-weight: 600;
                                font-family: var(--font-alt);
                            }
                        }
                    }
                }
            }
        }
    }
}

.is-dark {
    .form-layout {
        &.is-separate {
            .form-outer {
                background: none !important;

                .form-body {
                    .form-section {
                        .form-section-inner {
                            @include vuero-card--dark;

                            >h3 {
                                color: var(--dark-dark-text);
                            }

                            .radio-boxes {
                                .radio-box {
                                    input:checked+.radio-box-inner {
                                        background: var(--primary);
                                        border-color: var(--primary);
                                        box-shadow: var(--primary-box-shadow);

                                        .fee,
                                        p {
                                            color: var(--smoke-white);
                                        }
                                    }

                                    .radio-box-inner {
                                        background: var(--dark-sidebar-light-2);
                                        border-color: var(--dark-sidebar-light-12);

                                        .fee {
                                            color: var(--dark-dark-text);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

@media only screen and (max-width: 767px) {
    .form-layout {
        &.is-separate {
            .form-outer {
                .form-body {
                    padding-left: 0;
                    padding-right: 0;
                    flex-direction: column;

                    .form-section {
                        width: 100%;

                        .form-section-inner {
                            padding: 30px;
                        }
                    }
                }
            }
        }
    }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
    .form-layout {
        &.is-separate {
            .form-outer {
                .form-body {
                    padding-left: 0;
                    padding-right: 0;

                    // flex-direction: column;

                    .form-section {
                        // width: 100%;

                        .form-section-inner {
                            padding: 30px;
                        }
                    }
                }
            }
        }
    }
}

.all-projects {
    .all-projects-header {
        display: flex;
        padding: 20px;
        background: var(--white);
        border: 1px solid var(--fade-grey-dark-3);
        border-radius: var(--radius-large);
        margin-bottom: 1.5rem;

        .header-item {
            width: 25%;
            border-right: 1px solid var(--fade-grey-dark-3);

            &:last-child {
                border-right: none;
            }

            .item-inner {
                text-align: center;

                .lnil,
                .lnir {
                    font-size: 2.2rem;
                    margin-bottom: 6px;
                    color: var(--primary);
                }

                span {
                    display: block;
                    font-family: var(--font);
                    font-weight: 600;
                    font-size: 1.4rem;
                    color: var(--dark-text);
                }

                p {
                    font-family: var(--font-alt);
                }
            }
        }
    }

    .projects-card-grid {
        .grid-item {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 220px;
            padding: 20px;
            background: var(--white);
            border: 1px solid var(--fade-grey-dark-3);
            border-radius: var(--radius-large);

            .top-section {
                .head {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 8px;

                    h3 {
                        font-size: 1rem;
                        font-family: var(--font-alt);
                        color: var(--dark-text);
                        font-weight: 600;
                    }
                }

                .body {
                    p {
                        font-family: var(--font);
                        color: var(--light-text);
                    }
                }
            }

            .bottom-section {
                display: flex;

                .foot-block {
                    margin-right: 30px;

                    .heading {
                        font-family: var(--font-alt);
                        font-size: 0.75rem;
                        color: var(--light-text-dark-22);
                    }

                    >p {
                        padding-top: 5px;
                    }

                    .developers {
                        display: flex;

                        .v-avatar {
                            margin-right: 6px;
                        }
                    }
                }
            }
        }
    }
}

.heading {
    font-family: var(--font-alt);
    font-size: 0.75rem;
    color: var(--light-text-dark-22);
}

.is-dark {
    .all-projects {
        .all-projects-header {
            background: var(--dark-sidebar-light-6);
            border-color: var(--dark-sidebar-light-12);

            .header-item {
                border-color: var(--dark-sidebar-light-18);

                span {
                    color: var(--dark-dark-text);
                }

                i {
                    color: var(--primary) !important;
                }
            }
        }

        .projects-card-grid {
            .grid-item {
                background: var(--dark-sidebar-light-6);
                border-color: var(--dark-sidebar-light-12);

                .top-section {
                    .head {
                        h3 {
                            color: var(--dark-dark-text);
                        }
                    }
                }

                .bottom-section {
                    .foot-block {
                        .heading {
                            color: var(--light-text-dark-12);
                        }
                    }
                }
            }
        }
    }
}

.tabs-wrapper.is-slider .tabs,
.tabs-wrapper-alt.is-slider .tabs {
    max-width: 30% !important;
}
</style>
  