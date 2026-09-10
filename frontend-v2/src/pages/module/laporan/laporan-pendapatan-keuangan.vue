<template>
    <div class="column is-12">
        <VCard style="padding-bottom: 0px">
            <div class="column c-title pt-2 mb-5">
                <label class="title-page">Laporan Pendapatan Keuangan </label>
            </div>
            <div class="column is-12 p-0">
                <div class="columns is-multiline">
                    <div class="column is-4">
                        <VField label="Periode">
                            <VControl class="prime-auto">
                                <Calendar inputId="range" v-model="item.qBulan" selectionMode="range"
                                    :manualInput="false" class="w-100 mb-4 is-rounded" :showIcon="true"
                                    date-format="yy-mm-dd" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column mt-5">
                        <VButton type="button" icon="feather:search" color="primary" raised :loading="isLoading"
                            @click="fetchData()">
                            Cari
                        </VButton>
                        <VButton type="button" icon="feather:send" color="info" raised :loading="isLoading2"
                            @click="kirimData()" class="ml-2">
                            Kirim Data
                        </VButton>
                    </div>

                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <br />
                            <div class="column is-4">

                                <Card>

                                    <template #title>PENDAPATAN KEUANGAN</template>

                                    <template #content v-for="(cb, i) in arrGroup">

                                        <p class="subtitle" style="font-size: 14px;"> Periode : {{
                                    H.formatMonthOnly(cb.bulan) }} -> TOTAL : <b style="float: right;"> {{
                                    H.formatRupiah(cb.jumlah,
                                        'Rp.') }}</b></p>

                                        <span class="subtitle" style="font-size: 14px;"> <b>Pendapatan Keuangan
                                            </b></span>
                                        <br />
                                        <span class="subtitle  ml-3" style="font-size: 14px; margin-bottom: -1px;">a.
                                            Pendapatan Bunga Bank
                                            : <span style="float: right;"> {{ H.formatRupiah(cb.pendapatan_bunga_bank, 'Rp.')
                                                }}</span></span>
                                        <br />
                                        <span class="subtitle  ml-3" style="font-size: 14px; margin-bottom: -1px;">b.
                                            Deposito
                                            : <span style="float: right;"> {{ H.formatRupiah(cb.deposito, 'Rp.')
                                                }}</span></span>
                                        <br />
                                        <span class="subtitle  ml-3" style="font-size: 14px; margin-bottom: -1px;">b.
                                            Pend Denda & Lain-Lain
                                            : <span style="float: right;"> {{ H.formatRupiah(cb.pend_lainnya, 'Rp.')
                                                }}</span></span>
                                        <br />
                                        <p class="subtitle" style="font-size: 14px;"> </p>
                                        <span class="subtitle" style="font-size: 14px;"> <b>Jumlah Pendapatan Keuangan :</b>
                                            <span style="float: right;"> {{ H.formatRupiah(cb.pendapatan_bunga_bank
                                    + cb.deposito + cb.pend_lainnya, 'Rp.')
                                                }}</span></span>
                                        <br />
                                        <p class="subtitle" style="font-size: 14px;"> </p>
                                        <span class="subtitle" style="font-size: 14px;"> <b> Biaya Keuangan
                                            </b></span>
                                        <br />
                                        <span class="subtitle  ml-3" style="font-size: 14px; margin-bottom: -1px;">a.
                                            Biaya Bunga Bank
                                            : <span style="float: right;"> {{ H.formatRupiah(cb.biaya_bunga_bank, 'Rp.')
                                                }}</span></span>
                                        <br />
                                        <p class="subtitle" style="font-size: 14px;"> </p>
                                        <span class="subtitle" style="font-size: 14px;"> <b>Jumlah Biaya Keuangan :</b>
                                            <span style="float: right;"> {{ H.formatRupiah(cb.biaya_bunga_bank, 'Rp.')}}</span></span>
                                        <br />

                                        <p class="subtitle" style="font-size: 14px;"> </p>
                                        <span class="subtitle" style="font-size: 14px;"> <b> Pendapatan (Biaya) Lain-lain
                                            </b></span>
                                        <br />
                                        <span class="subtitle  ml-3" style="font-size: 14px; margin-bottom: -1px;">a.
                                            Pendapatan APBN/RM  diluar belanja pegawai dan belanja modal
                                            : <span style="float: right;"> {{ H.formatRupiah(cb.pend_apbn_lainnya, 'Rp.')
                                                }}</span></span>
                                        <br />
                                        <span class="subtitle  ml-3" style="font-size: 14px; margin-bottom: -1px;">b.
                                            Pendapatan Hibah
                                            : <span style="float: right;"> {{ H.formatRupiah(cb.pendapatan_hibah, 'Rp.')
                                                }}</span></span>
                                        <br />
                                        <span class="subtitle  ml-3" style="font-size: 14px; margin-bottom: -1px;">c.
                                            Pendapatan BLU lainnya (contoh Sewa Gedung/Ruang/Lahan)
                                            : <span style="float: right;"> {{ H.formatRupiah(cb.pendapatan_blu_lainnya, 'Rp.')
                                                }}</span></span>
                                        <br />
                                       
                                        <p class="subtitle" style="font-size: 14px;"> </p>
                                        <span class="subtitle" style="font-size: 14px;"> <b>Jumlah Pendapatan lain-lain :</b>
                                            <span style="float: right;"> {{ H.formatRupiah(cb.pend_apbn_lainnya + cb.pendapatan_hibah + cb.pendapatan_blu_lainnya , 'Rp.')}}</span></span>
                                        <br />



                                      

                                    </template>
                                </Card>
                            </div>
                            <div class="column is-8">
                                <div class="column is-12">
                                    <div class="columns is-multiline">

                                        <div class="column is-12">
                                            <DataTable v-model:filters="filters" :value="dataPasien" paginator
                                                :rows="10" dataKey="no" filterDisplay="row"
                                                :globalFilterFields="['notransaksi', 'namaproduk', 'ketlainya', 'jenis']"
                                                :class="`p-datatable-small`" showGridlines stripedRows
                                                :rowsPerPageOptions="[5, 10, 25, 50, 100, 1000]">

                                                <template #header>
                                                    <div class="flex justify-content-between">
                                                        <span class="p-input-icon-left">

                                                            <InputText v-model="filters['global'].value"
                                                                placeholder="Cari Data" />
                                                        </span>
                                                        <VButton type="button" icon="pi pi-file-excel" class="mr-3"
                                                            v-tooltip-prime="'Export'"
                                                            @click="exportExcel(dataPasien, 'LapKunjungan')"
                                                            color="primary">
                                                            Export Excel
                                                        </VButton>
                                                    </div>
                                                    <div
                                                        class="flex flex-wrap align-items-center justify-content-between gap-2">

                                                    </div>
                                                </template>

                                                <template #empty style="text-align: center;"> No data found.
                                                </template>
                                                <Column field="no" header="No"
                                                    style="width: 50px; text-align: center;" />
                                                <Column field="tanggal" header="Tanggal" />
                                                <Column field="transaksi" header="Jenis Pendapatan" />
                                                <Column field="kettransaksi" header="Keterangan Pendapatan" />
                                                <Column field="nonhistori" header="Nomor Histori" />

                                                <Column field="total" header="Total Pendapatan"
                                                    style="text-align:right">

                                                    <template #body="slotProps">
                                                        {{ H.formatRupiah(slotProps.data.total, 'Rp.') }}
                                                    </template>
                                                </Column>
                                            </DataTable>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </VCard>
    </div>
</template>

<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete';
import { useThemeColors } from '/@src/composable/useThemeColors'
import * as H from '/@src/utils/appHelper'
import { formatRp } from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import moment from 'moment'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Calendar from 'primevue/calendar';
import Dropdown from 'primevue/dropdown';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import * as XLSX from "xlsx";
import Card from 'primevue/card';
import { FilterMatchMode } from 'primevue/api'
import InputText from 'primevue/inputtext';
import Fieldset from 'primevue/fieldset';
useHead({
    title: 'Rekap Pendapatan Keuangan - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },

});
const arrGroup: any = ref([{
    'bulan': new Date(),
    'pendapatan_bunga_bank': 0,
    'deposito': 0,
    'pend_lainnya': 0,
    'pend_apbn_lainnya': 0,
    'pendapatan_hibah': 0,
    'pendapatan_blu_lainnya': 0,
    'biaya_bunga_bank': 0,
    'jumlah': 0,
}
])
const item: any = ref({
    qBulan: [
        new Date(),
        new Date()
    ],

})
const isLoading2: any = ref(false)
const dataKunjungan: any = ref([])
const dataPasien: any = ref([])
const isLoading = ref(false);


const fetchData = async () => {
    let tglAwal = H.formatDate(item.value.qBulan[0], "YYYY-MM-DD 00:00:00")
    let tglAkhir = H.formatDate(item.value.qBulan[1] ? item.value.qBulan[1] : item.value.qBulan[0], "YYYY-MM-DD 23:59:59")

    isLoading.value = true
    await useApi().get(`mkko/lap-pendapatan-keuangan?dari=${tglAwal}&sampai=${tglAkhir}`).then((response) => {
        response.detail.forEach((element: any, i: any) => {
            element.no = i + 1
            // element.Reg_umum_rajal = parseFloat(element.Reg_umum_rajal).toFixed(2)
        });


        isLoading.value = false
        // dataKunjungan.value = response.data
        dataPasien.value = response.detail
        if (response.data.length)
        arrGroup.value = response.data;//groupMonth(  dataKunjungan.value )

    })

}
const groupMonth = (result: any) => {
    let sama = false

    let arrGroup: any = [];
    for (let i = 0; i < result.length; i++) {
        sama = false
        for (let x = 0; x < arrGroup.length; x++) {
            if (arrGroup[x].bulan.substr(0, 7) == result[i].bulan.substr(0, 7)) {
                arrGroup[x].pendapatan_bunga_bank = arrGroup[x].pendapatan_bunga_bank + result[i].pendapatan_bunga_bank
                arrGroup[x].deposito = arrGroup[x].deposito + result[i].deposito
                arrGroup[x].pend_lainnya = arrGroup[x].pend_lainnya + result[i].pend_lainnya
                arrGroup[x].pend_apbn_lainnya = arrGroup[x].pend_apbn_lainnya + result[i].pend_apbn_lainnya
                arrGroup[x].pendapatan_hibah = arrGroup[x].pendapatan_hibah + result[i].pendapatan_hibah
                arrGroup[x].pendapatan_blu_lainnya = arrGroup[x].pendapatan_blu_lainnya + result[i].pendapatan_blu_lainnya
                arrGroup[x].biaya_bunga_bank = arrGroup[x].biaya_bunga_bank + result[i].biaya_bunga_bank
            }
        }
        if (sama == false) {

            arrGroup.push(result[i])
        }
    }

    return arrGroup
}
const kirimData = async () => {
    if (dataKunjungan.value.length == 0) {
        H.alert('error', 'Data belum ada')
        return
    }

    isLoading2.value = true
    for (let index = 0; index < dataKunjungan.value.length; index++) {
        const rekap = dataKunjungan.value[index];
        await useApi().post(`mkko/api-integrate`,
            {
                url: 'keuangan',
                method: 'POST',
                data: {
                    "tanggal": rekap.bulan,
                    "detail": {
                        "pendapatan_rs": {
                            "outpatient_revenue": {
                                "pasien_jkn": {
                                    "jkn_reguler": rekap.jkn_rajal,
                                    "jkn_naikkelas": 0
                                },
                                "pasien_non_jkn_eksekutif": {
                                    "asuransi": rekap.Eks_Asuransi_rajal,
                                    "jaminan_perusahaan": rekap.Eks_perusahaan_rajal,
                                    "pembayaran_mandiri": rekap.Eks_umum_rajal,
                                },
                                "pasien_non_jkn_reguler": {
                                    "asuransi": rekap.Reg_Asuransi_rajal,
                                    "jaminan_perusahaan": rekap.Reg_perusahaan_rajal,
                                    "pembayaran_mandiri": rekap.Reg_umum_rajal,
                                }
                            },
                            "inpatient_revenue": {
                                "pasien_jkn": {
                                    "jkn_reguler": rekap.jkn_ranap,
                                    "jkn_naikkelas": 0
                                },
                                "pasien_non_jkn_eksekutif": {
                                    "asuransi": rekap.Eks_Asuransi_ranap,
                                    "jaminan_perusahaan": rekap.Eks_perusahaan_ranap,
                                    "pembayaran_mandiri": rekap.Eks_umum_ranap,
                                },
                                "pasien_non_jkn_reguler": {
                                    "asuransi": rekap.Reg_Asuransi_ranap,
                                    "jaminan_perusahaan": rekap.Reg_perusahaan_ranap,
                                    "pembayaran_mandiri": rekap.Reg_umum_ranap,
                                }
                            },
                            "pendapatan_layanan_lain": rekap.pendapatan_lain,
                        },
                        "rba_pendapatan": "0",
                        "beban_pokok_pendapatan": {
                            "beban_pegawai": "0"
                        },
                        "beban_administrasi_umum": {
                            "beban_barang_jasa": "0",
                            "beban_pemeliharaan": "0",
                            "beban_perjalanan_dinas": "0",
                            "beban_penyisihan_piutang_tak_tertagih": "0"
                        },
                        "beban_persediaan": {
                            "beban_persediaan_farmasi": 0,
                            "beban_persediaan_non_farmasi": 0
                        },
                        "beban_penyusutan_dan_amortisasi": "0",
                        "surplus_defisit_usaha": "0",
                        "depresiasi_amortisasi": "0",
                        "EBITDA": "0",
                        "beban_pegawai": "0",
                        "EBITDA_plus_beban_pegawai": "0",
                        "pendapatan_keuangan": {
                            "pendapatan_bunga_bank": "0",
                            "deposito": "0",
                            "pend_lainnya": "0",
                            "jumlah_pendapatan_keuangan": "0"
                        },
                        "biaya_keuangan": "0",
                        "pendapatan_biaya_lain_lain": {
                            "pend_apbn_lainnya": "0",
                            "pendapatan_hibah": "0",
                            "pendapatan_blu_lainnya": "0",
                            "jumlah_pendapatan_lain_lain": "0"
                        },
                        "surplus_usaha_sebelum_pajak": "0",
                        "manfaat_beban_pajak": "0",
                        "surplus_bersih": "0"

                    }
                }
            })
    }
    isLoading2.value = false
}
const exportExcel = (data: any, filename: any) => {
    H.exportExcel(data, filename)
}
fetchData()

</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

.p-card .p-card-title {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.c-title {
    margin-left: -21px;
    padding-top: 21px;
    padding-top: 18px;
    margin-top: -21px;
    border-top-left-radius: 11px;
    border-left: solid hsl(19deg 100% 75% / 72%) 3px;
    padding-bottom: 0px;
    margin-bottom: 2rem;
}

.title-page {
    position: relative;
    font-size: 17px;
    display: block;
    margin-bottom: 3px;
    margin-top: 8px;
    font-weight: 600;
}

.btn-search {
    display: flex;
    align-items: center;
    margin-top: 14px;
}

// .title-page {
//   font-weight: 600;
//   font-size: 18px;
// }

.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
}

.tg td {
    border-color: var(--fade-grey-dark-2);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
    vertical-align: middle;
}

.tg th {
    border-color: var(--fade-grey-dark-3);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: normal;
    padding: 10px 5px;
    word-break: normal;
    vertical-align: middle;
    text-align: center !important;
}

.tg .tg-0lax {
    text-align: left;
    vertical-align: top
}

.tile-grid {
    .columns {
        margin-left: -0.5rem !important;
        margin-right: -0.5rem !important;
        margin-top: -0.5rem !important;
    }

    .column {
        padding: 0.5rem !important;
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
                width: 200px;
                height: 200px;
                min-width: 200px;
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
                        font-size: 1rem;
                    }

                    &:nth-child(2) {
                        display: flex;
                        align-items: center;

                        span {
                            display: inline-block;
                            color: var(--light-text);
                            font-size: 0.8rem;
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
</style>