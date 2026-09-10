<template>
    <section>
        <ConfirmDialog />
        <div class="columns is-multiline">
            <VCard style="padding-bottom: 0px">
                <div class="column c-title-x">
                    <h3 class="title is-5 mb-2 mr-1">{{ title }}</h3>
                </div>
                <div class="column is-12">
                    <div class="columns is-multiline">

                        <div class="column is-7">
                        </div>
                        <div class="column is-2 mt-4">
                            <VControl>
                                <VSwitchBlock v-model="item.flDetail" label="Detail" color="danger"  class="is-pulled-right mr-5"/>
                            </VControl>
                        </div>
                        <div class="column is-2 is-pulled-right">
                            <VField label="Periode">
                                <VControl class="prime-auto">
                                    <Calendar inputId="range" v-model="item.bulan" :manualInput="false" class="w-100 mb-4 "
                                        :showIcon="true" view="month" dateFormat="MM-yy" />
                                </VControl>
                            </VField>

                        </div>
                        <div class="column is-1 mt-5 ">
                            <VIconButton type="button" color="success" circle raised icon="fas fa-search"
                                @click="fetchData()" :loading="isLoading">
                            </VIconButton>
                        </div>

                        <div class="column is-12 mt-5-min">
                            <VCard class="card-round-1">

                                <DataTable v-model:filters="filtersTrans" :value="dataSource" paginator :rows="50"
                                    dataKey="id" filterDisplay="row" :rowsPerPageOptions="[5, 10, 25, 100, 1000]"
                                    :globalFilterFields="['noaccount', 'namaaccount']" :class="`p-datatable-small`">
                                    <template #header>
                                        <div class="columns is-multiline">
                                            <div class="column is-5">
                                                <VButton type="button" icon="pi pi-file-excel" class="mr-3" color="info"
                                                    outlined circle raised v-tooltip-prime="'Export'"
                                                    @click="exportExcel()">
                                                    Export Excel
                                                </VButton>

                                                <VButton type="button" icon="pi pi-times-circle" class="mr-3" color="danger"
                                                    :loading="isClosing2" outlined circle raised
                                                    @click="batalClosingJurnal()">
                                                    Batal Closing
                                                </VButton>


                                                <VButton type="button" icon="pi pi-save" class="mr-3" color="success" circle
                                                    :loading="isClosing" raised @click="closingJurnal()">
                                                    Closing Jurnal
                                                </VButton>

                                            </div>
                                            <div class="column is-3 is-offset-4">
                                                <VField>
                                                    <VControl icon="feather:search">
                                                        <input v-model="filtersTrans['global'].value"
                                                            v-on:keyup.enter="fetchData()" type="text"
                                                            class="input is-rounded" placeholder="Search" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </template>
                                    <template #empty style="text-align: center;"> No data found. </template>
                                    <ColumnGroup type="header">
                                        <Row>
                                            <Column :rowspan="2" header="No"></Column>
                                            <Column :rowspan="2" header="No Akun"></Column>
                                            <Column :rowspan="2" header="Nama Akun"></Column>
                                            <Column :colspan="2" header="Saldo Awal"></Column>
                                            <Column :colspan="2" header="Mutasi"></Column>
                                            <Column :colspan="2" header="Neraca Saldo"></Column>
                                            <Column :colspan="2" header="Jurnal Penyesuaian"></Column>
                                            <Column :colspan="2" header="Neraca Saldo stlh ADJ"></Column>
                                            <Column :colspan="2" header="Neraca"></Column>
                                            <Column :colspan="2" header="Laba / Rugi"></Column>
                                        </Row>
                                        <Row>
                                            <Column field="debetAwal" header="Debit"></Column>
                                            <Column field="kreditAwal" header="Kredit"></Column>
                                            <Column field="debetMutasi" header="Debit"></Column>
                                            <Column field="kreditMutasi" header="Kredit"></Column>
                                            <Column field="debetAkhir" header="Debit"></Column>
                                            <Column field="kreditAkhir" header="Kredit"></Column>

                                            <Column field="debetadj" header="Debit"></Column>
                                            <Column field="kreditadj" header="Kredit"></Column>

                                            <Column field="debetAkhiradj" header="Debit"></Column>
                                            <Column field="kreditAkhiradj" header="Kredit"></Column>

                                            <Column field="debetNeraca" header="Debit"></Column>
                                            <Column field="kreditNeraca" header="Kredit"></Column>

                                            <Column field="debetLabaRugi" header="Debit"></Column>
                                            <Column field="kreditLabaRugi" header="Kredit"></Column>
                                        </Row>
                                    </ColumnGroup>
                                    <Column field="no" frozen />
                                    <Column field="noaccount" frozen sortable />
                                    <Column field="namaaccount" frozen sortable />
                                    <Column field="debetAwal" sortable>
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(slotProps.data.debetAwal, '') }}
                                        </template>
                                    </Column>
                                    <Column field="kreditAwal" sortable>
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(slotProps.data.kreditAwal, '') }}
                                        </template>
                                    </Column>
                                    <Column field="debetMutasi" sortable>
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(slotProps.data.debetMutasi, '') }}
                                        </template>
                                    </Column>
                                    <Column field="kreditMutasi" sortable>
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(slotProps.data.kreditMutasi, '') }}
                                        </template>
                                    </Column>
                                    <Column field="debetAkhir">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(slotProps.data.debetAkhir, '') }}
                                        </template>
                                    </Column>
                                    <Column field="kreditAkhir">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(slotProps.data.kreditAkhir, '') }}
                                        </template>
                                    </Column>
                                    <Column field="debetadj">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(slotProps.data.debetadj, '') }}
                                        </template>
                                    </Column>
                                    <Column field="kreditadj">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(slotProps.data.kreditadj, '') }}
                                        </template>
                                    </Column>
                                    <Column field="debetAkhiradj">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(slotProps.data.debetAkhiradj, '') }}
                                        </template>
                                    </Column>
                                    <Column field="kreditAkhiradj">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(slotProps.data.kreditAkhiradj, '') }}
                                        </template>
                                    </Column>
                                    <Column field="debetNeraca">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(slotProps.data.debetNeraca, '') }}
                                        </template>
                                    </Column>
                                    <Column field="kreditNeraca">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(slotProps.data.kreditNeraca, '') }}
                                        </template>
                                    </Column>
                                    <Column field="debetLabaRugi">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(slotProps.data.debetLabaRugi, '') }}
                                        </template>
                                    </Column>
                                    <Column field="kreditLabaRugi">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(slotProps.data.kreditLabaRugi, '') }}
                                        </template>
                                    </Column>
                                </DataTable>

                            </VCard>
                        </div>
                        <div class="column is-4">
                            <div class="columns is-multiline mb-2">

                                <Divider align="center" type="dotted">
                                    <b>Saldo Awal</b>
                                </Divider>
                                <div class="column is-6">
                                    <VCardCustom :style="'padding:5px 25px'">
                                        <div class="label-status success">
                                            <i aria-hidden="true" class="fas fa-circle"></i>
                                            <span class="ml-1"> DEBIT</span>
                                        </div>
                                        <small class="text-bold-custom">{{
                                            H.formatRp(item.saldoAwalDebet,
                                                'Rp.')
                                        }}</small>
                                    </VCardCustom>
                                </div>
                                <div class="column is-6">
                                    <VCardCustom :style="'padding:5px 25px'">
                                        <div class="label-status success">
                                            <i aria-hidden="true" class="fas fa-circle"></i>
                                            <span class="ml-1"> KREDIT</span>
                                        </div>
                                        <small class="text-bold-custom">{{
                                            H.formatRp(item.saldoAwalKredit,
                                                'Rp.')
                                        }}</small>
                                    </VCardCustom>
                                </div>
                            </div>
                        </div>
                        <div class="column is-4">
                            <div class="columns is-multiline mb-2">
                                <Divider align="center" type="dotted">
                                    <b>Mutasi</b>
                                </Divider>
                                <div class="column is-6">
                                    <VCardCustom :style="'padding:5px 25px'">
                                        <div class="label-status warning">
                                            <i aria-hidden="true" class="fas fa-circle"></i>
                                            <span class="ml-1"> DEBIT</span>
                                        </div>
                                        <small class="text-bold-custom">{{
                                            H.formatRp(item.mutasiDebet,
                                                'Rp.')
                                        }}</small>
                                    </VCardCustom>
                                </div>
                                <div class="column is-6">
                                    <VCardCustom :style="'padding:5px 25px'">
                                        <div class="label-status warning">
                                            <i aria-hidden="true" class="fas fa-circle"></i>
                                            <span class="ml-1"> KREDIT</span>
                                        </div>
                                        <small class="text-bold-custom">{{
                                            H.formatRp(item.mutasiKredit,
                                                'Rp.')
                                        }}</small>
                                    </VCardCustom>
                                </div>
                            </div>
                        </div>
                        <div class="column is-4">
                            <div class="columns is-multiline mb-2">
                                <Divider align="center" type="dotted">
                                    <b>Neraca Saldo</b>
                                </Divider>
                                <div class="column is-6">
                                    <VCardCustom :style="'padding:5px 25px'">
                                        <div class="label-status danger">
                                            <i aria-hidden="true" class="fas fa-circle"></i>
                                            <span class="ml-1"> DEBIT</span>
                                        </div>
                                        <small class="text-bold-custom">{{
                                            H.formatRp(item.saldoAkhirDebet,
                                                'Rp.')
                                        }}</small>
                                    </VCardCustom>
                                </div>
                                <div class="column is-6">
                                    <VCardCustom :style="'padding:5px 25px'">
                                        <div class="label-status danger">
                                            <i aria-hidden="true" class="fas fa-circle"></i>
                                            <span class="ml-1"> KREDIT</span>
                                        </div>
                                        <small class="text-bold-custom">{{
                                            H.formatRp(item.saldoAkhirKredit,
                                                'Rp.')
                                        }}</small>
                                    </VCardCustom>
                                </div>
                            </div>
                        </div>
                        <div class="column is-4">
                            <div class="columns is-multiline mb-2">
                                <Divider align="center" type="dotted">
                                    <b>Jurnal Penyesuaian</b>
                                </Divider>
                                <div class="column is-6">
                                    <VCardCustom :style="'padding:5px 25px'">
                                        <div class="label-status info">
                                            <i aria-hidden="true" class="fas fa-circle"></i>
                                            <span class="ml-1"> DEBIT</span>
                                        </div>
                                        <small class="text-bold-custom">{{
                                            H.formatRp(item.mutasiDebetAdj,
                                                'Rp.')
                                        }}</small>
                                    </VCardCustom>
                                </div>
                                <div class="column is-6">
                                    <VCardCustom :style="'padding:5px 25px'">
                                        <div class="label-status info">
                                            <i aria-hidden="true" class="fas fa-circle"></i>
                                            <span class="ml-1"> KREDIT</span>
                                        </div>
                                        <small class="text-bold-custom">{{
                                            H.formatRp(item.mutasiKreditAdj,
                                                'Rp.')
                                        }}</small>
                                    </VCardCustom>
                                </div>
                            </div>
                        </div>
                        <div class="column is-4">
                            <div class="columns is-multiline mb-2">
                                <Divider align="center" type="dotted">
                                    <b>Neraca Stlh Penyesuaian </b>
                                </Divider>
                                <div class="column is-6">
                                    <VCardCustom :style="'padding:5px 25px'">
                                        <div class="label-status warning">
                                            <i aria-hidden="true" class="fas fa-circle"></i>
                                            <span class="ml-1"> DEBIT</span>
                                        </div>
                                        <small class="text-bold-custom">{{
                                            H.formatRp(item.saldoAkhirDebetAdj,
                                                'Rp.')
                                        }}</small>
                                    </VCardCustom>
                                </div>
                                <div class="column is-6">
                                    <VCardCustom :style="'padding:5px 25px'">
                                        <div class="label-status warning">
                                            <i aria-hidden="true" class="fas fa-circle"></i>
                                            <span class="ml-1"> KREDIT</span>
                                        </div>
                                        <small class="text-bold-custom">{{
                                            H.formatRp(item.saldoAkhirKreditAdj,
                                                'Rp.')
                                        }}</small>
                                    </VCardCustom>
                                </div>
                            </div>
                        </div>
                        <div class="column is-4">
                            <div class="columns is-multiline mb-2">
                                <Divider align="center" type="dotted">
                                    <b>Neraca </b>
                                </Divider>
                                <div class="column is-4">
                                    <VCardCustom :style="'padding:5px 25px'">
                                        <div class="label-status danger">
                                            <i aria-hidden="true" class="fas fa-circle"></i>
                                            <span class="ml-1"> DEBIT</span>
                                        </div>
                                        <small class="text-bold-custom">{{
                                            H.formatRp(item.saldoNeracaDebet,
                                                'Rp.')
                                        }}</small>
                                    </VCardCustom>
                                </div>
                                <div class="column is-4">
                                    <VCardCustom :style="'padding:5px 25px'">
                                        <div class="label-status danger">
                                            <i aria-hidden="true" class="fas fa-circle"></i>
                                            <span class="ml-1"> KREDIT</span>
                                        </div>
                                        <small class="text-bold-custom">{{
                                            H.formatRp(item.saldoNeracaKredit,
                                                'Rp.')
                                        }}</small>
                                    </VCardCustom>
                                </div>
                                <div class="column is-4">
                                    <VCardCustom :style="'padding:5px 25px'">
                                        <div class="label-status danger">
                                            <i aria-hidden="true" class="fas fa-circle"></i>
                                            <span class="ml-1"> Laba</span>
                                        </div>
                                        <small class="text-bold-custom">{{
                                            H.formatRp(item.saldoNeracaLaba,
                                                'Rp.')
                                        }}</small>
                                    </VCardCustom>
                                </div>
                            </div>
                        </div>
                        <div class="column is-4">
                            <div class="columns is-multiline mb-2">
                                <Divider align="center" type="dotted">
                                    <b>Laba / Rugi </b>
                                </Divider>
                                <div class="column is-4">
                                    <VCardCustom :style="'padding:5px 25px'">
                                        <div class="label-status primary">
                                            <i aria-hidden="true" class="fas fa-circle"></i>
                                            <span class="ml-1"> DEBIT</span>
                                        </div>
                                        <small class="text-bold-custom">{{
                                            H.formatRp(item.saldoLRaDebet,
                                                'Rp.')
                                        }}</small>
                                    </VCardCustom>
                                </div>
                                <div class="column is-4">
                                    <VCardCustom :style="'padding:5px 25px'">
                                        <div class="label-status primary">
                                            <i aria-hidden="true" class="fas fa-circle"></i>
                                            <span class="ml-1"> KREDIT</span>
                                        </div>
                                        <small class="text-bold-custom">{{
                                            H.formatRp(item.saldoLRKredit,
                                                'Rp.')
                                        }}</small>
                                    </VCardCustom>
                                </div>
                                <div class="column is-4">
                                    <VCardCustom :style="'padding:5px 25px'">
                                        <div class="label-status info">
                                            <i aria-hidden="true" class="fas fa-circle"></i>
                                            <span class="ml-1"> Laba</span>
                                        </div>
                                        <small class="text-bold-custom">{{
                                            H.formatRp(item.saldoLRLaba,
                                                'Rp.')
                                        }}</small>
                                    </VCardCustom>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </VCard>
        </div>
    </section>
</template>
<script setup lang="ts">
import { ref, computed, reactive, watch } from 'vue'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Sidebar from 'primevue/sidebar';
import Dropdown from 'primevue/dropdown';
import MultiSelect from 'primevue/multiselect';
import { useRoute, useRouter } from 'vue-router'
import * as H from '/@src/utils/appHelper'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ProgressBar from 'primevue/progressbar';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import InputText from 'primevue/inputtext';
import { FilterMatchMode } from 'primevue/api'
import { useApi } from '/@src/composable/useApi'
import Panel from 'primevue/panel';
import Dialog from 'primevue/dialog';
import Badge from 'primevue/badge';
import FileUpload from 'primevue/fileupload';
import Calendar from 'primevue/calendar';
import moment from 'moment';
import sleep from '/@src/utils/sleep'
import Divider from 'primevue/divider';
import Fieldset from 'primevue/fieldset';
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import * as XLSX from "xlsx";
import { useUserSession } from '/@src/stores/userSession'
import Button from 'primevue/button';
const title = 'Neraca Lajur'
useHead({
    title: title + ' - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const jmlFilter: any = ref(0)
const isLoadingUpload: any = ref(false)
const totalSize = ref(0);
const totalSizePercent = ref(0);
const confirm = useConfirm();
const modalFilter: any = ref(false)
const isLoading: any = ref(false)
const route = useRoute()
const router = useRouter()
const dataSource: any = ref([])
const isClosing2: any = ref(false)
const isClosing: any = ref(false)
const filtersTrans = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS }, });
const filtersDetail = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS }, });
const dataDetailJurnal: any = ref([])
const dataPopUp: any = ref([])
const data2: any = ref([])
const isLoadingpop: any = ref(false)
const modalJurnalPost: any = ref(false)
const isPostingJurnal: any = ref(false)
const valueProgress: any = ref(0)
const dataExcel: any = ref({})
const modalJurnalEntry: any = ref(false)
const item: any = reactive({
    qFilterTgl: [
        new Date(),
        new Date()
    ],
    ttlDebet: 0,
    ttlKredit: 0,
    mutasiDebetAdj: 0,
    mutasiKreditAdj: 0,
    flDetail: false,
    saldoAkhirDebetAdj: 0,
    saldoAkhirKreditAdj: 0,

    saldoNeracaDebet: 0,
    saldoNeracaKredit: 0,

    saldoLRaDebet: 0,
    saldoLRKredit: 0,

    saldoNeracaLaba: 0,
    saldoLRLaba: 0,

    bulan: new Date()

})
const currentPage: any = ref({
    limit: 20
})


const getLastDayOfMonth = (year, month) => {
    // Create a Date object set to the next month's first day
    let firstDayOfNextMonth = new Date(year, month, 1);

    // Subtract one day to get the last day of the current month
    let lastDayOfMonth = new Date(firstDayOfNextMonth - 1);

    return lastDayOfMonth.getDate();
}
const fetchData = async () => {


    var bulan = H.formatDate(item.bulan, "YYYY-MM")
    var tglAwal1 = bulan + "-01"
    var tglAkhir1 = bulan + "-" + getLastDayOfMonth(bulan.substr(0, 4), bulan.substr(5, 2))

    isLoading.value = true
    const dat = await useApi().get(
        '/akuntansi/get-data-trial-balance-rev?tglAwal=' + tglAwal1
        + '&tglAkhir=' + tglAkhir1
        + "&fldetail=" + (item.flDetail ? item.flDetail : 'false')

    )

    isLoading.value = false

    var saldoAwalDebet = 0
    var saldoAwalKredit = 0
    var mutasiDebet = 0
    var mutasiKredit = 0
    var saldoAkhirDebet = 0
    var saldoAkhirKredit = 0
    var saldoAkhirDebetAdj = 0
    var saldoAkhirKreditAdj = 0
    let debetAkhiradj = 0
    let kreditAkhiradj = 0

    let sDebetAkhir = 0
    let sKreditAkhir = 0
    let sDebetAkhirAdj = 0
    let sKreditAkhirAdj = 0
    let mutasiDebetAdj = 0
    let mutasiKreditAdj = 0
    let NoAccount = ''

    var saldoNeracaDebet = 0
    var saldoNeracaKredit = 0
    var saldoLRaDebet = 0
    var saldoLRKredit = 0
    for (var i = 0; i < dat.length; i++) {
        dat[i].no = i + 1
        dat[i].debetAwal = parseFloat(dat[i].debetAwal)
        dat[i].kreditAwal = parseFloat(dat[i].kreditAwal)
        dat[i].debetMutasi = parseFloat(dat[i].debetMutasi)
        dat[i].kreditMutasi = parseFloat(dat[i].kreditMutasi)
        sDebetAkhir = parseFloat(dat[i].debetAwal) + parseFloat(dat[i].debetMutasi)
        sKreditAkhir = parseFloat(dat[i].kreditAwal) + parseFloat(dat[i].kreditMutasi)
        if (sDebetAkhir > sKreditAkhir) {
            dat[i].debetAkhir = sDebetAkhir - sKreditAkhir
            dat[i].kreditAkhir = 0
        } else {
            dat[i].debetAkhir = 0
            dat[i].kreditAkhir = sKreditAkhir - sDebetAkhir
        }


        sDebetAkhirAdj = parseFloat(sDebetAkhir) + parseFloat(dat[i].debetadj)
        sKreditAkhirAdj = parseFloat(sKreditAkhir) + parseFloat(dat[i].kreditadj)
        if (sDebetAkhirAdj > sKreditAkhirAdj) {
            dat[i].debetAkhiradj = sDebetAkhirAdj - sKreditAkhirAdj
            dat[i].kreditAkhiradj = 0
        } else {
            dat[i].debetAkhiradj = 0
            dat[i].kreditAkhiradj = sKreditAkhirAdj - sDebetAkhirAdj
        }

        NoAccount = dat[i].noaccount;
        dat[i].debetNeraca = 0
        dat[i].kreditNeraca = 0
        if (NoAccount.substring(0, 1) == '1' || NoAccount.substring(0, 1) == '2' || NoAccount.substring(0, 1) == '3') {
            dat[i].debetNeraca = dat[i].debetAkhiradj
            dat[i].kreditNeraca = dat[i].kreditAkhiradj
        }
        dat[i].debetLabaRugi = 0
        dat[i].kreditLabaRugi = 0
        if (NoAccount.substring(0, 1) == '4' || NoAccount.substring(0, 1) == '5' || NoAccount.substring(0, 1) == '6') {
            dat[i].debetLabaRugi = dat[i].debetAkhiradj
            dat[i].kreditLabaRugi = dat[i].kreditAkhiradj
        }


        saldoAwalDebet = saldoAwalDebet + parseFloat(dat[i].debetAwal)
        saldoAwalKredit = saldoAwalKredit + parseFloat(dat[i].kreditAwal)
        mutasiDebet = mutasiDebet + parseFloat(dat[i].debetMutasi)
        mutasiKredit = mutasiKredit + parseFloat(dat[i].kreditMutasi)
        saldoAkhirDebet = saldoAkhirDebet + parseFloat(dat[i].debetAkhir)
        saldoAkhirKredit = saldoAkhirKredit + parseFloat(dat[i].kreditAkhir)

        mutasiDebetAdj = mutasiDebetAdj + parseFloat(dat[i].debetadj)
        mutasiKreditAdj = mutasiKreditAdj + parseFloat(dat[i].kreditadj)
        saldoAkhirDebetAdj = saldoAkhirDebetAdj + parseFloat(dat[i].debetAkhiradj)
        saldoAkhirKreditAdj = saldoAkhirKreditAdj + parseFloat(dat[i].kreditAkhiradj)

        saldoNeracaDebet = saldoNeracaDebet + parseFloat(dat[i].debetNeraca)
        saldoNeracaKredit = saldoNeracaKredit + parseFloat(dat[i].kreditNeraca)
        saldoLRaDebet = saldoLRaDebet + parseFloat(dat[i].debetLabaRugi)
        saldoLRKredit = saldoLRKredit + parseFloat(dat[i].kreditLabaRugi)
    }

    dataSource.value = dat
    item.saldoAwalDebet = saldoAwalDebet
    item.saldoAwalKredit = saldoAwalKredit

    item.mutasiDebet = mutasiDebet
    item.mutasiKredit = mutasiKredit

    item.saldoAkhirDebet = saldoAkhirDebet
    item.saldoAkhirKredit = saldoAkhirKredit

    item.mutasiDebetAdj = mutasiDebetAdj
    item.mutasiKreditAdj = mutasiKreditAdj

    item.saldoAkhirDebetAdj = saldoAkhirDebetAdj
    item.saldoAkhirKreditAdj = saldoAkhirKreditAdj

    item.saldoNeracaDebet = saldoNeracaDebet
    item.saldoNeracaKredit = saldoNeracaKredit

    item.saldoLRaDebet = saldoLRaDebet
    item.saldoLRKredit = saldoLRKredit

    item.saldoNeracaLaba = saldoNeracaDebet - saldoNeracaKredit
    item.saldoLRLaba = saldoLRKredit - saldoLRaDebet


}

const exportExcel = () => {
    let judul = 'Trial Balance'
    let column1 = ['', '', 'Saldo Awal', '', 'Mutasi', '', 'Saldo Akhir', '']
    let column = ['No Akun', 'Nama Akun', 'Debit', 'Kredit', 'Debit', 'Kredit', 'Debit', 'Kredit']
    const worksheet = XLSX.utils.aoa_to_sheet([

        [judul],
        [],
        column1,
        column,
        ...dataSource.value.map((e: any) => [
            e.noaccount,
            e.namaaccount,
            parseFloat(e.debetAwal).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"),
            parseFloat(e.kreditAwal).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"),
            parseFloat(e.debetMutasi).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"),
            parseFloat(e.kreditMutasi).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"),
            parseFloat(e.debetAkhir).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"),
            parseFloat(e.kreditAkhir).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"),

            '',
        ]),
        [],
        ['Saldo Awal Debit :', 'Rp. ' + item.saldoAwalDebet],
        ['Saldo Awal Kredit :', 'Rp. ' + item.saldoAwalKredit],
        ['Mutasi Debit :', 'Rp. ' + item.mutasiDebet],
        ['Mutasi Kredit :', 'Rp. ' + item.mutasiKredit],
        ['Saldo Akhir Debit :', 'Rp. ' + item.saldoAkhirDebet],
        ['Saldo Akhir Kredit :', 'Rp. ' + item.saldoAkhirKredit],

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
    worksheet[cellRef] = { v: judul, s: { alignment: { horizontal: 'center', vertical: 'center' } } };

    const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 0, c: 8 } };

    const mergeSubtitle1 = { s: { r: 1, c: 0 }, e: { r: 1, c: 8 } };
    worksheet['!merges'] = [mergeTitle, mergeSubtitle1];
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, 'data');

    const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
    H.saveAsExcelFile(excelBuffer, 'neracasaldo');
}

const closingJurnal = () => {
    confirm.require({
        message: 'Close Jurnal bulan "' + H.formatDate(item.bulan, "MMMM YYYY") + '"',
        header: 'Konfirmasi',
        icon: 'pi pi-check-circle',
        acceptClass: 'p-button-success',
        accept: () => {
            var norec_tea = item.norecSaldo
            if (item.norecSaldo == undefined) {
                norec_tea = '-'
            }
            var tgltgl = H.formatDate(item.bulan, 'YYYYMM');
            var objSave =
            {
                ym: tgltgl,
                data: dataSource.value
            }
            isClosing.value = true
            useApi().post(
                `/akuntansi/save-data-closing-jurnal`, objSave).then((response: any) => {
                    isClosing.value = false
                }).catch((e) => {
                    isClosing.value = false
                })
        },
        reject: () => { },
    })
}
const batalClosingJurnal = () => {
    confirm.require({
        message: 'Batal Close Jurnal bulan "' + H.formatDate(item.bulan, "MMMM YYYY") + '"',
        header: 'Konfirmasi',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            var norec_tea = item.norecSaldo
            if (item.norecSaldo == undefined) {
                norec_tea = '-'
            }
            var tgltgl = H.formatDate(item.bulan, 'YYYYMM');
            var objSave =
            {
                ym: tgltgl,
                data: dataSource.value
            }
            isClosing2.value = true
            useApi().post(
                `/akuntansi/save-batal-closing-jurnal`, objSave).then((response: any) => {
                    isClosing2.value = false
                }).catch((e) => {
                    isClosing2.value = false
                })
        },
        reject: () => { },
    })
}
fetchData()

</script>
<style lang="scss"></style>
  