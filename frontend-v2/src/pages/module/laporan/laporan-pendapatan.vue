
<template>
    <div class="column is-12">
        <VCard style="padding-bottom: 0px">
            <div class="column c-title pt-2 mb-5">
                <label class="title-page">Laporan Pendapatan</label>
            </div>
            <div class="column is-12 p-0">
                <div class="columns is-multiline">
                    <div class="column is-4">
                        <VField label="Periode">
                            <VControl class="prime-auto">
                                <Calendar inputId="range" selectionMode="range" v-model="item.qBulan" :manualInput="false"
                                    class="w-100  " :showIcon="true" view="month" dateFormat="MM-yy" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column mt-5">
                        <VButton type="button" icon="feather:search" color="primary" raised :loading="isLoading"
                            @click="fetchData()">
                            Cari
                        </VButton>
                    </div>
                    <div class="column is-12">
                        <div class="column is-12" v-if="dataKunjungan.loading">
                            <div class="flex-list-inner mb-4">
                                <div class="flex-table-item grid-item mb-4" v-for="key in 3" :key="key">
                                    <VFlexTableCell :column="{ grow: true, media: true }">
                                        <VPlaceloadAvatar size="medium" />
                                        <VPlaceloadText :lines="2" width="30%" last-line-width="20%" class="mx-2" />
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
                        <div class="column is-12" v-else-if="dataKunjungan.length === 0">
                            <VPlaceholderSection title="Data Tidak Ditemukan"
                                subtitle="Silakan Pilih Tanggal Periode Registrasi." class="my-6">
                                <template #image>
                                    <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg"
                                        alt="" />
                                    <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                        alt="" />
                                </template>
                            </VPlaceholderSection>
                        </div>
                        <div class="column is-12" v-else-if="dataKunjungan.length > 0">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <DataTable :value="dataKunjungan" tableStyle="min-width: 50rem" scrollable
                                        :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]" :loading="isLoading"
                                        showGridlines>
                                        <template #header>
                                            <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                                                <span class="text-xl text-900 font-bold">Laporan Pendapatan</span>
                                                <!-- <VButton color="info" icon="fas fa-paste" raised rounded
                                                        style="margin-left:20px;" @click="collectTagihan()"> Collecting
                                                        Tagihan
                                                    </VButton> -->
                                            </div>
                                        </template>

                                        <template #empty style="text-align: center;"> No data found. </template>
                                        <ColumnGroup type="header">
                                            <Row>
                                                <Column :rowspan="3" header="No" frozen style="min-width: 50px;"></Column>
                                                <Column :rowspan="3" header="Bulan" frozen style="min-width: 150px;">
                                                </Column>
                                                <Column :colspan="2" header="Pendapatan JKN">
                                                </Column>
                                                <Column :colspan="6" header="Pendapatan Non JKN Eksekutif"></Column>
                                                <Column :colspan="6" header="Pendapatan Non JKN Reguler"></Column>


                                            </Row>
                                            <Row>
                                                <Column :colspan="1" header="Rawat Inap" style="min-width: 150px;"></Column>
                                                <Column :colspan="1" header="Rawat Jalan" style="min-width: 150px;">
                                                </Column>
                                                <Column :colspan="3" header="Rawat Inap" style="min-width: 150px;"></Column>
                                                <Column :colspan="3" header="Rawat Jalan" style="min-width: 150px;">
                                                </Column>
                                                <Column :colspan="3" header="Rawat Inap" style="min-width: 150px;"></Column>
                                                <Column :colspan="3" header="Rawat Jalan" style="min-width: 150px;">
                                                </Column>
                                            </Row>
                                            <Row>
                                                <Column header="JKN" style="min-width: 150px;"></Column>
                                                <Column header="JKN" style="min-width: 150px;"></Column>
                                                <column header="Asuransi" style="min-width: 150px;"></column>
                                                <column header="Jaminan Perusahaan" style="min-width: 150px;"></column>
                                                <column header="Pembayaran Mandiri" style="min-width: 150px;"></column>
                                                <column header="Asuransi" style="min-width: 150px;"></column>
                                                <column header="Jaminan Perusahaan" style="min-width: 150px;"></column>
                                                <column header="Pembayaran Mandiri" style="min-width: 150px;"></column>
                                                <column header="Asuransi" style="min-width: 150px;"></column>
                                                <column header="Jaminan Perusahaan" style="min-width: 150px;"></column>
                                                <column header="Pembayaran Mandiri" style="min-width: 150px;"></column>
                                                <column header="Asuransi" style="min-width: 150px;"></column>
                                                <column header="Jaminan Perusahaan" style="min-width: 150px;"></column>
                                                <column header="Pembayaran Mandiri" style="min-width: 150px;"></column>
                                            </Row>
                                        </ColumnGroup>
                                        <Column field="no" frozen />
                                        <Column field="bulan" frozen sortable />
                                        <Column field="jkn_ranap" style="text-align: right;">
                                            <template #body="slotProps">
                                                {{ H.formatRupiah(slotProps.data.jkn_ranap, 'Rp.') }}
                                            </template>
                                        </Column>
                                        <Column field="jkn_rajal" style="text-align: right;">
                                            <template #body="slotProps">
                                                {{ H.formatRupiah(slotProps.data.jkn_rajal, 'Rp.') }}
                                            </template>
                                        </Column>
                                        <Column field="Eks_Asuransi_ranap" style="text-align: right;">
                                            <template #body="slotProps">
                                                {{ H.formatRupiah(slotProps.data.Eks_Asuransi_ranap, 'Rp.') }}
                                            </template>
                                        </Column>
                                        <Column field="Eks_perusahaan_ranap" style="text-align: right;">
                                            <template #body="slotProps">
                                                {{ H.formatRupiah(slotProps.data.Eks_perusahaan_ranap, 'Rp.') }}
                                            </template>
                                        </Column>
                                        <Column field="Eks_umum_ranap" style="text-align: right;">
                                            <template #body="slotProps">
                                                {{ H.formatRupiah(slotProps.data.Eks_umum_ranap, 'Rp.') }}
                                            </template>
                                        </Column>
                                        <Column field="Eks_Asuransi_rajal" style="text-align: right;">
                                            <template #body="slotProps">
                                                {{ H.formatRupiah(slotProps.data.Eks_Asuransi_rajal, 'Rp.') }}
                                            </template>
                                        </Column>
                                        <Column field="Eks_perusahaan_rajal" style="text-align: right;">
                                            <template #body="slotProps">
                                                {{ H.formatRupiah(slotProps.data.Eks_perusahaan_rajal, 'Rp.') }}
                                            </template>
                                        </Column>
                                        <Column field="Eks_umum_rajal" style="text-align: right;">
                                            <template #body="slotProps">
                                                {{ H.formatRupiah(slotProps.data.Eks_umum_rajal, 'Rp.') }}
                                            </template>
                                        </Column>
                                        <Column field="Reg_Asuransi_ranap" style="text-align: right;">
                                            <template #body="slotProps">
                                                {{ H.formatRupiah(slotProps.data.Reg_Asuransi_ranap, 'Rp.') }}
                                            </template>
                                        </Column>
                                        <Column field="Reg_perusahaan_ranap" style="text-align: right;">
                                            <template #body="slotProps">
                                                {{ H.formatRupiah(slotProps.data.Reg_perusahaan_ranap, 'Rp.') }}
                                            </template>
                                        </Column>
                                        <Column field="Reg_umum_ranap" style="text-align: right;">
                                            <template #body="slotProps">
                                                {{ H.formatRupiah(slotProps.data.Reg_umum_ranap, 'Rp.') }}
                                            </template>
                                        </Column>
                                        <Column field="Reg_Asuransi_rajal" style="text-align: right;">
                                            <template #body="slotProps">
                                                {{ H.formatRupiah(slotProps.data.Reg_Asuransi_rajal, 'Rp.') }}
                                            </template>
                                        </Column>
                                        <Column field="Reg_perusahaan_rajal" style="text-align: right;">
                                            <template #body="slotProps">
                                                {{ H.formatRupiah(slotProps.data.Reg_perusahaan_rajal, 'Rp.') }}
                                            </template>
                                        </Column>
                                        <Column field="Reg_umum_rajal" style="text-align: right;">
                                            <template #body="slotProps">
                                                {{ H.formatRupiah(slotProps.data.Reg_umum_rajal, 'Rp.') }}
                                            </template>
                                        </Column>

                                    </DataTable>
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
useHead({
    title: 'Laporan Pendapatan - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
    qBulan: [
        new Date()
    ],
})
const dataKunjungan: any = ref([])
const isLoading = ref(false);
const fetchData = async () => {

    let tglAwal = H.formatDate(item.value.qBulan[0], "YYYY-MM-01")
    let mont = item.value.qBulan[0]
    let last = new Date(mont.getFullYear(), mont.getMonth() + 1, 0).getDate();
    let tglAkhir = H.formatDate(item.value.qBulan[0], "YYYY-MM-" + last)
    if (item.value.qBulan.length == 2 && item.value.qBulan[1] != null) {
        mont = item.value.qBulan[1]
        last = new Date(mont.getFullYear(), mont.getMonth() + 1, 0).getDate();
        tglAkhir = H.formatDate(item.value.qBulan[1], "YYYY-MM-" + last)
    }
    item.value.tglAwal = new Date(tglAwal)
    item.value.tglAkhir = new Date(tglAkhir)


    isLoading.value = true
    await useApi().get(`mkko/lap-jml-pendapatan?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}`).then((response) => {
        response.data.forEach((element: any, i: any) => {
            element.no = i + 1
            element.Reg_umum_rajal = parseFloat(element.Reg_umum_rajal).toFixed(2)
            element.bulan = H.formatMonthOnly(element.bulan)
        });

        isLoading.value = false
        dataKunjungan.value = response.data

    })

}
const exportExcel = (data: any, filename: any) => {
    H.exportExcel(data, filename)
}
fetchData()

</script>
  
<style lang="scss">
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
}</style>
  