<template>
    <div class="finance-dashboard stock-dashboard">
        <div class="columns is-multiline">

            <div class="column is-12">
                <VCard radius="rounded">
                    <div class="columns column">
                        <a v-if="props.hideRiwayat == true" @click="() => { emits('showRiwayat') }"
                            class="is-expandeds mr-2" style="margin-top:-5px;">
                            <i class="iconify" data-icon="feather:arrow-right" aria-hidden="true"> </i>
                        </a>
                        <h3 class="title is-5 mb-2 mr-1">Asesmen Awal </h3>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-12  p-0">
                            <VButton rounded color="success" class="mr-2 is-pulled-right" icon="feather:file-text" raised
                                bold @click="() => { emits('billingPasien') }">
                                Billing Tagihan
                            </VButton>

                            <VIconButton circle class="mr-2 is-pulled-right" icon="fas fa-expand-arrows-alt" raised bold
                                v-if="props.hideRiwayat == true" @click="() => { emits('showRiwayat') }"
                                v-tooltip.bubble="'Perkecil Tampilan EMR'">
                            </VIconButton>
                            <VIconButton circle class="mr-2 is-pulled-right" icon="fas fa-expand" raised bold
                                v-if="props.hideRiwayat == false" @click="() => { emits('hiddenRiwayat') }"
                                v-tooltip.bubble="'Perbesar Tampilan EMR'">
                            </VIconButton>
                            <VIconButton circle class="mr-2 is-pulled-right" icon="feather:refresh-cw" raised bold
                                @click="() => { emits('reloadRiwayat') }" :loading="props.isLoadingRiwayat"
                                v-tooltip.bubble="'Perbaharui Data EMR'">
                            </VIconButton>


                            <VIconButton circle color="success" class="mr-2 is-pulled-right" icon="fas fa-search" raised
                                bold @click="fetchAsesmen()">
                            </VIconButton>
                            <VButton rounded color="info" class="mr-2 is-pulled-right" icon="feather:plus" raised bold
                                @click="tambahAsesmen()">
                                Tambah Assesmen
                            </VButton>

                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline h-350">
                                <div class="column is-8">
                                    <VCard class="mb-5-min" style="background-color: #E9ECEF;">
                                        <div class="columns is-multiline">
                                            <div class="column is-8">
                                            </div>
                                        </div>
                                    </VCard>
                                </div>
                                <div class="column is-12 mt-5-min" v-if="dataAsesmen.length == 0">
                                    <VCard>
                                        <div class="columns is-multiline">
                                            <div class="column is-12">
                                                <div class="page-placeholder">
                                                    <div class="placeholder-content">
                                                        <img class="light-image" style=" max-width: 340px;"
                                                            :src="H.assets().iconNotFound_rev" alt="" />
                                                        <img class="dark-image" style=" max-width: 340px;"
                                                            :src="H.assets().iconNotFound_rev" alt="" />
                                                        <h3>{{ H.assets().notFound }}</h3>
                                                        <p class="is-larger">
                                                            {{ H.assets().notFoundSubtitle }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </VCard>
                                </div>
                                <div class="column is-12" v-else>
                                    <div class="is-raised s-card bb-1 mt-0 mb-2 p-4 bg-grey" v-for="items in dataAsesmen"
                                        :key="items.norec">
                                        <div class="card-head">
                                            <VIconButton class="mr-2" icon="fas fa-notes-medical" color="danger" raised
                                                bold>
                                            </VIconButton>
                                            <VBlock :title="items.namalengkap" :subtitle="items.noemr" center>
                                                <VTag color="green" :label="items.noregistrasi" class="mt-1" outlined />

                                                <template #action>
                                                    <VIconButton class="is-rounded is-small mr-2" circle outlined
                                                        @click="editEMR(items)" icon="feather:edit" color="info" raised
                                                        bold>
                                                    </VIconButton>
                                                    <VIconButton class="is-rounded is-small mr-2" circle outlined
                                                        @click="hapusEMR(items)" :loading="items.loadingHapus"
                                                        icon="feather:trash" color="danger" raised bold>
                                                    </VIconButton>
                                                    <VIconButton class="is-rounded is-small mr-2" circle outlined
                                                        :loading="items.loading"
                                                        :icon="'fas ' + (!items.isdetail ? 'fa-chevron-down' : 'fa-chevron-up')"
                                                        color="dark" raised bold @click="klikDetail(items)">
                                                    </VIconButton>
                                                    <VButton class="is-rounded is-small " circle outlined color="warning"
                                                        raised bold>
                                                        {{ H.formatDateIndoSimple(items.tglemr) }}
                                                    </VButton>
                                                </template>
                                            </VBlock>
                                        </div>
                                        <div class="card-inner" v-if="items.isdetail">
                                            <div class="columns is-multiline">

                                                <div class="column is-3">
                                                    <div class="pt-1 pl-3 pr-3">
                                                        <i aria-hidden="true" class="fas fa-circle bullet"></i> <span
                                                            class="txt-diagnosa mt-2">Anamnesis</span>
                                                        <table class="w-100 tb-order mt-1">
                                                            <tr>
                                                                <td class="txt-1">Keluhan Utama</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="txt-2">
                                                                    <VCard class="p-2" style="height:100px">
                                                                        {{ items.Anamnesis ? items.Anamnesis.keluhanUtama :
                                                                            ''
                                                                        }}
                                                                    </VCard>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="txt-1">Riwayat Penyakit</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="txt-2">
                                                                    <VCard class="p-2" style="height:100px">
                                                                        {{
                                                                            items.Anamnesis ? items.Anamnesis.riwayatPenyakit :
                                                                            ''
                                                                        }}
                                                                    </VCard>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="txt-1">Riwayat Alergi </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="txt-2">
                                                                    <VCard class="p-2" style="height:100px">
                                                                        {{ items.Anamnesis ? (items.Anamnesis.riwayatAlergi
                                                                            ?
                                                                            items.Anamnesis.riwayatAlergi.label : '') : '' }}
                                                                    </VCard>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="txt-1">Riwayat Pengobatan </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="txt-2">
                                                                    <VCard class="p-2" style="height:100px">
                                                                        {{ items.Anamnesis ?
                                                                            items.Anamnesis.riwayatPengobatan : '' }}
                                                                    </VCard>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="column is-9">
                                                    <div class="pt-1 pl-3 pr-3">
                                                        <i aria-hidden="true" class="fas fa-circle bullet"></i>
                                                        <span class="txt-diagnosa mt-2"> Pemeriksaan Psikologi, Ekonomi
                                                            sosial & Spiritual :</span>

                                                        <div class="columns is-multiline">
                                                            <div class="column is-4">
                                                                <span class="txt-1">Status Psikologi </span>
                                                                <VCard class="p-2" style="height:50px">
                                                                    {{ items.PemeriksaanPsikologis ?
                                                                        (items.PemeriksaanPsikologis.jenisPsikologi ?
                                                                            items.PemeriksaanPsikologis.jenisPsikologi.label : '') :
                                                                        '' }}
                                                                </VCard>
                                                            </div>
                                                            <div class="column is-4">
                                                                <span class="txt-1">Sosial Ekonomi </span>
                                                                <VCard class="p-2" style="height:50px">
                                                                    {{ items.PemeriksaanPsikologis ?
                                                                        items.PemeriksaanPsikologis.sosialEkonomi :
                                                                        '' }}
                                                                </VCard>
                                                            </div>
                                                            <div class="column is-4">
                                                                <span class="txt-1"> Spiritual </span>
                                                                <VCard class="p-2" style="height:50px">
                                                                    {{ items.PemeriksaanPsikologis ?
                                                                        items.PemeriksaanPsikologis.spiritual : '' }}
                                                                </VCard>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="pt-1 pl-3 pr-3">
                                                        <i aria-hidden="true" class="fas fa-circle bullet"></i>
                                                        <span class="txt-diagnosa mt-2"> Pemeriksaan Fisik : </span>
                                                        <div class="columns is-multiline">
                                                            <div class="column is-3">
                                                                <span class="txt-1">Keaadaan Umum </span>
                                                                <div class="s-card">
                                                                    <ApexChart id="apex-chart-17" :height="270"
                                                                        :type="'bar'" :series="chartKeadaanUmum.series"
                                                                        :options="chartKeadaanUmum">
                                                                    </ApexChart>

                                                                </div>
                                                            </div>
                                                            <div class="column is-6">
                                                                <span class="txt-1">Vital Sign </span>
                                                                <div class="columns is-multiline">
                                                                    <div class="column is-6">
                                                                        <div
                                                                            class="stat-widget followers-stat-widget-v1 p-2">
                                                                            <div class="follow-block">
                                                                                <div class="follow-count">
                                                                                    <span class="dark-inverted vital">
                                                                                        Tinggi Badan
                                                                                    </span>
                                                                                    <span class="vital-value">{{
                                                                                        items.PemeriksaanFisik ?
                                                                                        items.PemeriksaanFisik.tinggiBadan
                                                                                        : '' }}<span
                                                                                            class="vital-satuan">Cm</span>
                                                                                    </span>
                                                                                </div>
                                                                                <a href="#" class="go-icon">
                                                                                    <img
                                                                                        src="/images/simrs/tinggibadan.png">
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="column is-6">
                                                                        <div
                                                                            class="stat-widget followers-stat-widget-v1 p-2">
                                                                            <div class="follow-block">
                                                                                <div class="follow-count">
                                                                                    <span class="dark-inverted vital">
                                                                                        Berat Badan
                                                                                    </span>
                                                                                    <span class="vital-value">{{
                                                                                        items.PemeriksaanFisik ?
                                                                                        items.PemeriksaanFisik.beratBadan
                                                                                        : '' }}<span
                                                                                            class="vital-satuan">Kg</span>
                                                                                    </span>
                                                                                </div>
                                                                                <a href="#" class="go-icon">
                                                                                    <img src="/images/simrs/beratbadan.png">
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="column is-12">
                                                                        <div
                                                                            class="stat-widget followers-stat-widget-v1 p-2">
                                                                            <div class="follow-block">
                                                                                <div class="follow-count">
                                                                                    <span class="dark-inverted vital">
                                                                                        Tekanan Darah
                                                                                    </span>
                                                                                    <div>
                                                                                        <img
                                                                                            src="/images/simrs/tekanandarah.png">
                                                                                        <span class="vital-value ml-5"
                                                                                            style="display:inline;">{{
                                                                                                items.PemeriksaanFisik ?
                                                                                                items.PemeriksaanFisik.tekananDarah
                                                                                                : '' }}<span
                                                                                                class="vital-satuan">mmHG</span>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="column is-12">
                                                                        <div
                                                                            class="stat-widget followers-stat-widget-v1 p-2">
                                                                            <div class="follow-block">
                                                                                <div class="follow-count">
                                                                                    <span class="dark-inverted vital">
                                                                                        Denyut Nadi
                                                                                    </span>
                                                                                    <div>
                                                                                        <img src="/images/simrs/denyut.png">
                                                                                        <span class="vital-value ml-5"
                                                                                            style="display:inline;">{{
                                                                                                items.PemeriksaanFisik ?
                                                                                                items.PemeriksaanFisik.nadi
                                                                                                : '' }}<span
                                                                                                class="vital-satuan">BPM</span>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="column is-6">
                                                                        <div
                                                                            class="stat-widget followers-stat-widget-v1 p-2">
                                                                            <div class="follow-block">
                                                                                <div class="follow-count">
                                                                                    <span class="dark-inverted vital">
                                                                                        Suhu
                                                                                    </span>
                                                                                    <span class="vital-value">{{
                                                                                        items.PemeriksaanFisik ?
                                                                                        items.PemeriksaanFisik.suhu
                                                                                        : '' }}<span class="vital-satuan">°C
                                                                                        </span>
                                                                                    </span>
                                                                                </div>
                                                                                <a href="#" class="go-icon">
                                                                                    <img src="/images/simrs/suhu.png">
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="column is-6">
                                                                        <div
                                                                            class="stat-widget followers-stat-widget-v1 p-2">
                                                                            <div class="follow-block">
                                                                                <div class="follow-count">
                                                                                    <span class="dark-inverted vital">
                                                                                        Napas
                                                                                    </span>
                                                                                    <span class="vital-value">{{
                                                                                        items.PemeriksaanFisik ?
                                                                                        items.PemeriksaanFisik.pernapasan
                                                                                        : '-' }}<span
                                                                                            class="vital-satuan">XPM</span>
                                                                                    </span>
                                                                                </div>
                                                                                <a href="#" class="go-icon">
                                                                                    <img src="/images/simrs/nafas.png">
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="column is-12">
                                                                        <div
                                                                            class="stat-widget followers-stat-widget-v1 p-2">
                                                                            <div class="follow-block">
                                                                                <div class="follow-count">
                                                                                    <span class="dark-inverted vital">
                                                                                        SPO2
                                                                                    </span>
                                                                                    <div>
                                                                                        <img src="/images/simrs/spo.png">
                                                                                        <span class="vital-value ml-5"
                                                                                            style="display:inline;">{{
                                                                                                items.PemeriksaanFisik ?
                                                                                                items.PemeriksaanFisik.SPO2
                                                                                                : '-' }}<span
                                                                                                class="vital-satuan">%</span>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="column is-6">
                                                                        <div
                                                                            class="stat-widget followers-stat-widget-v1 p-2">
                                                                            <div class="follow-block">
                                                                                <div class="follow-count">
                                                                                    <span class="dark-inverted vital">
                                                                                        IMT
                                                                                    </span>
                                                                                    <span class="vital-value">{{
                                                                                        items.PemeriksaanFisik ?
                                                                                        items.PemeriksaanFisik.IMT
                                                                                        : '-' }}<span
                                                                                            class="vital-satuan"></span>
                                                                                    </span>
                                                                                </div>
                                                                                <a href="#" class="go-icon">
                                                                                    <img src="/images/simrs/imt.png">
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="column is-6">
                                                                        <div
                                                                            class="stat-widget followers-stat-widget-v1 p-2">
                                                                            <div class="follow-block">
                                                                                <div class="follow-count">
                                                                                    <span class="dark-inverted vital">
                                                                                        Lingkar Perut
                                                                                    </span>
                                                                                    <span class="vital-value">{{
                                                                                        items.PemeriksaanFisik ?
                                                                                        items.PemeriksaanFisik.lingkarPerut
                                                                                        : '-' }}<span
                                                                                            class="vital-satuan">Cm</span>
                                                                                    </span>
                                                                                </div>
                                                                                <a href="#" class="go-icon">
                                                                                    <img
                                                                                        src="/images/simrs/lingkarperut.png">
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- <div class="column is-12">
                                                                        <span class="txt-1">Lain-Lain </span>
                                                                        <VCard class="p-2">

                                                                        </VCard>
                                                                    </div> -->
                                                                </div>

                                                            </div>
                                                            <div class="column is-3">
                                                                <span class="txt-1"> Anatomi Tubuh </span>
                                                                <VCard class="p-2">
                                                                    <img v-if="props.pasien.jeniskelamin.toUpperCase() == 'LAKI-LAKI'"
                                                                        src="/images/simrs/cowok.png">
                                                                    <img v-if="props.pasien.jeniskelamin.toUpperCase() == 'PEREMPUAN'"
                                                                        src="/images/simrs/cewek.png">
                                                                </VCard>
                                                            </div>
                                                        </div>

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

        </div>
        <VModal :open="modalInput" title="Action" :noclose="false" size="medium" actions="right"
            @close="modalInput = false">
            <template #content>
                <form class="modal-form">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <UIWidget class="search-widget">
                                <template #body>
                                    <div class="field">
                                        <div class="control">
                                            <input type="text" v-model="filterMenu" class="input" placeholder="Search..." />
                                            <button class="searcv-button" type="button">
                                                <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </UIWidget>
                            <ListWidgetSingle title="List Menu" rounded class="list-widget-v1">
                                <div class="list-menu">
                                    <div v-for="items in filteredMenu" :key="items.name"
                                        class="inner-list-item media-flex-center is-clickable" @click="showMenu(items)">
                                        <VIconBox :color="items.color">
                                            <i aria-hidden="true" :class="items.icon"></i>
                                        </VIconBox>
                                        <div class="flex-meta is-light">
                                            <a href="#">{{ items.name }}</a>
                                            <span>{{ items.desc }}</span>
                                        </div>
                                        <div class="flex-end">
                                            <a class="go-icon is-up">
                                                <i aria-hidden="true" class="iconify" data-icon="feather:chevron-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </ListWidgetSingle>
                        </div>
                    </div>
                </form>
            </template>

        </VModal>
    </div>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, PropType, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import KosongSearch from '/@src/pages/include/kosong-search.vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import StatusCheck from '/@src/pages/include/status-check.vue'
import { useConfirm } from "primevue/useconfirm";
import ApexChart from 'vue3-apexcharts'
import { avatarList, avatarListCreative } from '/@src/data/widgets/ui/avatarList'
import {
    todoList1,
    todoList2,
    todoList3,
    todoList4,
} from '/@src/data/widgets/list/todoList'
import { products } from '/@src/data/widgets/ui/productList'
import { userList } from '/@src/data/widgets/list/userList'
import { listActionEMR } from '/@src/data/module/hard/action_emr'
import { boolean } from 'zod'
import TLabView from './t-lab-view.vue'
import TStatusPojokKanan from './t-status-pojok-kanan.vue'
const emits = defineEmits<{
    (e: 'showRiwayat'): void,
    (e: 'hiddenRiwayat'): void,
    (e: 'reloadRiwayat'): void,
    (e: 'billingPasien'): void
}>()
const props = defineProps({
    registrasi: {
        type: Object as PropType<any>,
    },
    pasien: {
        type: Object as PropType<any>,
    },
    riwayatAsesmen: {
        type: Object as PropType<any>,
    },
    hideRiwayat: {
        type: Boolean
    },
    isLoadingRiwayat: {
        type: Boolean
    },
    isPasienAktif: {
        type: Boolean
    },
})


// const props = withDefaults(defineProps<VTEmrDetailProps>(), {
//     registrasi: () => { },
// })\
const confirm = useConfirm();
const themeColors = useThemeColors()
const isDetail: any = ref([true])
const router = useRouter()
const route = useRoute()
const modalInput = ref(false)
const listColor: any = ref(Object.keys(themeColors))
const isEmpty = ref(true)
const dataAsesmen: any = ref([])
isEmpty.value = props.riwayatAsesmen.isEmpty
isDetail.value[1] = true
const listMenu = ref(listActionEMR)
const filterMenu: any = ref('')
const filteredMenu = computed(() => {
    if (!filterMenu.value) {
        return listMenu.value
    }

    return listMenu.value.filter((items) => {
        return (
            items.name.match(new RegExp(filterMenu.value, 'i'))
        )
    })
})
const chartKeadaanUmum: any = ref({
    series: [
        {
            name: 'Spaceships',
            data: [2, 1, 2, 3, 4, 1],
        },
    ],
    chart: {
        type: 'bar',
        height: 280,
        toolbar: {
            show: false,
        },
    },
    colors: [themeColors.green, themeColors.accent, themeColors.blue],
    plotOptions: {
        bar: {
            horizontal: false,
        },
    },
    title: {
        text: '',
        align: 'left',
    },
    dataLabels: {
        enabled: false,
    },
    xaxis: {
        categories: [
            'Sadar baik/alert   ',
            'Respon dengan kata-kata / voice',
            'Respon nyeri',
            'Pasien Tidak sadar',
            'Gelisah atau bingung',
            'Acute confessional states',
        ],
    },
})

const tambah = () => {
    modalInput.value = true
}
const showMenu = (e: any) => {
    router.push({
        name: e.form,
        query: {
            norec_pd: props.registrasi.norec,
            nocmfk: props.registrasi.nocmfk,
        }
    })
}

const tambahAsesmen = () => {
    router.push({
        name: 'module-emr-asesmen-awal',
        query: {
            norec_pd: props.registrasi.norec,
            nocmfk: props.registrasi.nocmfk,
        }
    })
}
const fetchAsesmen = async () => {
    dataAsesmen.value = []
    let params = `&nocmfk=${props.registrasi.nocmfk}&norec_pd=${props.registrasi.norec}`
    await useApi().get(
        `/emr/riwayat-emr?jenis_emr=asesmenawal${params}`).then((response: any) => {
            response.Anamnesis = {}
            response.PemeriksaanFisik = {}
            response.PemeriksaanPsikologis = {}
            dataAsesmen.value = response
        })
}
const klikDetail = async (e: any) => {
    e.isdetail = !e.isdetail
    if (e.isdetail) {
        e.loading = true
        await useApi().get(
            `/emr/riwayat-emr-detail?norec=${e.norec}&collection=Anamnesis,PemeriksaanFisik,PemeriksaanPsikologis`).then((response: any) => {
                e.Anamnesis = response.Anamnesis ? response.Anamnesis : {}
                e.PemeriksaanFisik = response.PemeriksaanFisik ? response.PemeriksaanFisik : {}
                e.PemeriksaanPsikologis = response.PemeriksaanPsikologis ? response.PemeriksaanPsikologis : {}
                e.loading = false
            })
    }
}
const editEMR = (e: any) => {
    router.push({
        name: 'module-emr-asesmen-awal',
        query: {
            norec_pd: props.registrasi.norec,
            nocmfk: props.registrasi.nocmfk,
            norec_emr: e.norec,
        }
    })
}
const hapusEMR = async (e: any) => {
    e.loadingHapus = true
    // confirm.require({
    //     group: 'positionDialog',
    //     message: H.alertHapus(),
    //     header: 'Info ',
    //     icon: 'pi pi-info-circle',
    //     acceptClass: 'p-button-danger',
    //     position: 'top',
    //     accept: async () => {
    await useApi().post(
        `/emr/hapus-emr`, {
        'norec': e.norec,
        'collection': 'Anamnesis,PemeriksaanFisik,PemeriksaanPsikologi'
    }).then((response: any) => {
        e.loadingHapus = false

        dataAsesmen.value.forEach((element: any, i: any) => {
            if (e.norec == element.norec) {
                dataAsesmen.value.splice(i, 1);
            }
        });
    })
    //     },
    //     reject: () => {
    //     }
    // });

}
onMounted(async () => {
    await fetchAsesmen()
})
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/emr-detail';

.vital {
    color: #A2A5B9 !important;
    font-size: 0.75rem !important;
}

.vital-value {
    color: #283252 !important;
    font-weight: bold;
    font-size: 0.8rem !important;
}

.vital-satuan {
    margin-left: 5px;
    color: #A2A5B9 !important;
    font-size: 0.8rem !important;
    display: inline !important;
}

.stat-widget {
    &.followers-stat-widget-v1 {
        .follow-block {
            display: flex;
            align-items: center;

            .follow-icon {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 50px;
                width: 50px;
                min-width: 50px;
                border-radius: var(--radius-rounded);
                background: var(--white);
                color: var(--primary);
                border: 1px solid var(--fade-grey-dark-3);
                box-shadow: var(--light-box-shadow);

                &.is-squared {
                    border-radius: 10px;
                }

                &.is-primary {
                    background: var(--primary);
                    border-color: var(--primary);
                    box-shadow: var(--primary-box-shadow);
                    color: var(--smoke-white);
                }

                svg {
                    height: 18px;
                    width: 18px;
                }

                i {
                    font-size: 18px;
                }
            }

            .follow-count {
                margin-left: 12px;

                span {
                    display: block;

                    &:first-child {
                        font-family: var(--font-alt);
                        font-size: 1.15rem;
                        font-weight: 600;
                        color: var(--dark-text);
                    }

                    &:nth-child(2) {
                        font-size: 0.95rem;
                        color: var(--light-text);
                    }
                }
            }

            .go-icon {
                position: relative;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 36px;
                width: 36px;
                border-radius: var(--radius-rounded);
                background: var(--widget-grey);
                color: var(--light-text);
                margin-left: auto;

                &.is-squared {
                    border-radius: 10px;
                }

                svg {
                    height: 16px;
                    width: 16px;
                    stroke-width: 3px;
                }
            }
        }
    }
}

.is-dark {
    .stat-widget {
        @include vuero-card--dark;

        &.followers-stat-widget-v1 {
            .follow-block {
                .follow-icon:not(.is-primary) {
                    background: var(--dark-sidebar-light-2);
                    border-color: var(--dark-sidebar-light-12);
                    color: var(--primary);
                }

                .follow-icon {
                    &.is-primary {
                        border-color: var(--primary);
                        background: var(--primary);
                    }
                }
            }

            .go-icon {
                background: var(--dark-sidebar-light-2);
                border: 1px solid var(--dark-sidebar-light-12);
            }
        }
    }
}
</style>
