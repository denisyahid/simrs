<template>
    <div class="column is-12">
        <VCard style="padding-bottom: 0px">
            <div class="column c-title pt-2 mb-5">
                <label class="title-page">Laporan Pendapatan (Biaya) Lain - Lain</label>
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
                        <Fieldset legend="Query" :toggleable="true" :collapsed="true">
                            <div class="column is-12">
                                <VField>
                                    <VLabel class="required-field">Query</VLabel>
                                    <VControl>
                                        <VTextarea v-model="item.query" rows="20" placeholder="...">
                                        </VTextarea>
                                    </VControl>
                                </VField>
                            </div>
                        </Fieldset>
                    </div>
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <br />
                            <div class="column is-3">
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <VCard>
                                            <div class="columns is-multiline">
                                                <div class="column">
                                                    <label class="title-page">Rekap Pendapatan Lain - Lain</label>
                                                </div>
                                            </div>
                                            <div class="columns is-multiline" v-for="(cb, i) in arrGroup">
                                                <p class="subtitle" style="font-size: 14px; margin-left: 12px;"> <b> Jumlah Pendapatan Lain
                                                        -Lain : {{ H.formatRupiah(cb.jumlah_pendapatan_lain_lain, 'Rp.') }}</b></p>
                                                <div class="column is-12">
                                                    <CardCountRev icon="/images/simrs/saldo-awal.png" straight
                                                        :total="H.formatRupiah(cb.pend_apbn_lainnya, 'Rp.')"
                                                        label="Pendapatan APBN/RM  diluar belanja pegawai dan belanja modal" />
                                                </div>
                                                <div class="column is-12">
                                                    <CardCountRev icon="/images/simrs/penerimaaan.png" straight
                                                        :total="H.formatRupiah(cb.pendapatan_hibah, 'Rp.')"
                                                        label="Pendapatan Hibah" />
                                                </div>
                                                <div class="column is-12">
                                                    <CardCountRev icon="/images/simrs/pengeluaran.png" straight
                                                        :total="H.formatRupiah(cb.pendapatan_blu_lainnya, 'Rp.')"
                                                        label=" Pendapatan BLU" />
                                                </div>

                                            </div>
                                        </VCard>
                                    </div>

                                </div>
                            </div>

                            <div class="column is-8">
                                <div class="column is-12">

                                    <div class="column is-12" v-if="dataPasien.length === 0">
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
                                    <div class="column is-12" v-else-if="dataPasien.length > 0">
                                        <div class="columns is-multiline">

                                            <div class="column is-12">
                                                <DataTable v-model:filters="filters" :value="dataPasien" paginator
                                                    :rows="10" dataKey="no" filterDisplay="row"
                                                    :globalFilterFields="['kelompoktransaksi', 'kettransaksi', 'nonhistori']"
                                                    :class="`p-datatable-small`" showGridlines>

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
                                                    <Column field="bulan" header="Tanggal" />
                                                    <Column field="kelompoktransaksi" header="Jenis Pendapatan" />
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
//   import Accordion from 'primevue/accordion';
// import AccordionTab from 'primevue/accordiontab';
import Fieldset from 'primevue/fieldset';
useHead({
    title: 'Rekap Pendapatan Lain - Lain - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },

});
const arrGroup: any = ref([])
const item: any = ref({
    qBulan: [
        new Date(),
        new Date()
    ],
    query: `
    SELECT  sh.objectkelompoktransaksifk, kt.kelompoktransaksi, sc.noclosing, sh.nonhistori, sh.ketlainya, sh.namaperkiraan, sh.kettransaksi, sh.nobukti, sh.totalsetortarikdeposit as total,
	CASE WHEN spp.nosbm IS NULL THEN sbk.nosbk ELSE spp.nosbm END AS notransaksi, to_char( sh.tglsetortarikdeposit, 'yyyy-MM-dd' ) AS bulan, ap.asalproduk
	FROM strukhistori_t AS sh 
    INNER JOIN strukclosing_t AS sc ON sc.norec = sh.noclosing 
    LEFT JOIN strukbuktipenerimaan_t AS spp ON spp.noclosingfk = sc.norec 
			LEFT JOIN strukbuktipengeluaran_t AS sbk ON sbk.noclosingfk = sc.norec 
			INNER JOIN kelompoktransaksi_m AS kt ON kt.id = sc.objectkelompoktransaksifk
			LEFT JOIN asalproduk_m AS ap ON ap.id = sh.objectasalprodukhasilfk 
		WHERE
			sh.kdprofile = 1 
			AND sh.statusenabled = 't' 
			AND sh.objectkelompoktransaksifk IN (450, 451, 449)
			AND sh.tglsetortarikdeposit BETWEEN '$START_DATE' AND '$END_DATE'
		GROUP BY sh.objectkelompoktransaksifk,kt.kelompoktransaksi, sc.noclosing, sh.nonhistori, sh.ketlainya, sh.namaperkiraan, sh.kettransaksi, sh.nobukti, sh.totalsetortarikdeposit,
		ap.asalproduk, spp.nosbm, sbk.nosbk, sh.tglsetortarikdeposit, sc.tglclosing
		ORDER BY sc.tglclosing ASC
  `
})
const isLoading2: any = ref(false)
const dataKunjungan: any = ref([])
const dataPasien: any = ref([])
const isLoading = ref(false);


const fetchData = async () => {
    let tglAwal = H.formatDate(item.value.qBulan[0], "YYYY-MM-DD")
    let tglAkhir = H.formatDate(item.value.qBulan[1] ? item.value.qBulan[1] : item.value.qBulan[0], "YYYY-MM-DD")

    item.value.tglAwal = new Date(tglAwal)
    item.value.tglAkhir = new Date(tglAkhir)

    let queryParam = item.value.query
    queryParam = queryParam.replaceAll('$START_DATE', tglAwal + ' 00:00:00')
    queryParam = queryParam.replaceAll('$END_DATE', tglAkhir + ' 23:59:59')
    isLoading.value = true
    await useApi().post(`mkko/lap-jml-pendapatan-lain`, {
        query: queryParam
    }).then((response) => {
        response.detail.forEach((element: any, i: any) => {
            element.no = i + 1
            // element.Reg_umum_rajal = parseFloat(element.Reg_umum_rajal).toFixed(2)
        });


        isLoading.value = false
        dataKunjungan.value = response.data
        dataPasien.value = response.detail

        arrGroup.value = groupMonth(dataKunjungan.value)

    })

}
const groupMonth = (result: any) => {
    let sama = false

    let arrGroup: any = [];
    for (let i = 0; i < result.length; i++) {
        sama = false
        for (let x = 0; x < arrGroup.length; x++) {
            if (arrGroup[x].bulan.substr(0, 7) == result[i].bulan.substr(0, 7)) {
                arrGroup[x].pendapatan_blu_lainnya = arrGroup[x].pendapatan_blu_lainnya + result[i].pendapatan_blu_lainnya
                arrGroup[x].pendapatan_hibah = arrGroup[x].pendapatan_hibah + result[i].pendapatan_hibah
                arrGroup[x].pend_apbn_lainnya = arrGroup[x].pend_apbn_lainnya + result[i].pend_apbn_lainnya
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
                                    "jkn_reguler": "0",
                                    "jkn_naikkelas": "0"
                                },
                                "pasien_non_jkn_eksekutif": {
                                    "asuransi": "0",
                                    "jaminan_perusahaan": "0",
                                    "pembayaran_mandiri": "0",
                                },
                                "pasien_non_jkn_reguler": {
                                    "asuransi": "0",
                                    "jaminan_perusahaan": "0",
                                    "pembayaran_mandiri": "0",
                                }
                            },
                            "inpatient_revenue": {
                                "pasien_jkn": {
                                    "jkn_reguler": "0",
                                    "jkn_naikkelas": "0"
                                },
                                "pasien_non_jkn_eksekutif": {
                                    "asuransi": "0",
                                    "jaminan_perusahaan": "0",
                                    "pembayaran_mandiri": "0",
                                },
                                "pasien_non_jkn_reguler": {
                                    "asuransi": "0",
                                    "jaminan_perusahaan": "0",
                                    "pembayaran_mandiri": "0",
                                }
                            },
                            "pendapatan_layanan_lain": "0",
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
                            "pend_apbn_lainnya": rekap.pend_apbn_lainnya,
                            "pendapatan_hibah": rekap.pendapatan_hibah,
                            "pendapatan_blu_lainnya": rekap.pendapatan_blu_lainnya,
                            "jumlah_pendapatan_lain_lain": rekap.jumlah_pendapatan_lain_lain
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

.span-css {
    font-size: 0.85rem;
    color: var(--light-text);
    font-family: var(--font);
    width: 300px;
    white-space: nowrap;
    overflow: hidden !important;
    text-overflow: ellipsis;
}
</style>