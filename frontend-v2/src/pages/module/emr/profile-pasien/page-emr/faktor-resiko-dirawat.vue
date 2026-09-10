<template>
    <div class="columns is-multiline" v-if="!modalInput">
        <div class="column is-12">
            <div class="search-widget">
                <div class="field">
                    <div class="columns is-multiline">
                        <div class="column is-2">
                            <VButton rounded icon="feather:plus" raised bold @click="add()" color="success" outlined
                                :loading="isLoading" class="mr-2">Tambah </VButton>
                        </div>
                        <div class="column is-10">
                            <div class="control">
                                <input type="text" v-model="filter" class="input" placeholder="Cari..." />
                                <button class="searcv-button" type="button" :loading="isLoading" @click="loadRiwayat">
                                    <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <div class="column is-12">


            <div class="columns is-multiline mt-5" v-if="isLoading">
                <VPlaceloadText :lines="1" class="p-2" />
                <div class="column is-12" v-for="key in 2" :key="key">
                    <VPlaceloadWrap>
                        <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                        <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                        <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                        <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                    </VPlaceloadWrap>
                </div>
            </div>
            <div class="columns is-multiline" v-else>
                <div class="column is-12" v-if="filteredList.length">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <UIWidget class="calendar-widget" v-for="(items, index) in filteredList" :key="items.id">
                                <template #header>
                                    <div class="widget-toolbar">
                                        <div class="left">
                                            <h3><span class="is-dark-text">Author :</span> {{ items.user_input ?
                                                items.user_input.namalengkap
                                                : '' }}</h3>

                                        </div>
                                        <div class="right">
                                            <VIconButton circle icon="feather:edit" raised bold @click="edit(items)"
                                                color="info" outlined class="mr-2"> </VIconButton>
                                            <VIconButton circle icon="feather:trash" raised bold @click="remove(items)"
                                                color="danger" outlined :loading="items.loadingHapus"> </VIconButton>

                                        </div>
                                    </div>
                                </template>
                                <template #body>
                                    <VCard class="p-0" v-if="items.ett" style="border-color: var(--success) !important">
                                        <span class="title-emr ml-3">ETT</span>
                                        <div class="columns is-multiline pt-2 pl-4 pr-4 pb-4 ">
                                            <template v-for="its in items.list_ETT ">
                                                <div class="column is-2 p-1" v-if="its.pemeriksaan != undefined">
                                                    <div class="sender-blockx">
                                                        <div class="exerptx">
                                                            <h5 :style="'color:var(--primary) '">
                                                                <i aria-hidden="true" class="fas fa-check"></i>
                                                                <span
                                                                    v-tooltip-prime.bottom="its.pemeriksaan">{{ its.pemeriksaan
                                                                    }}</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </VCard>
                                    <VCard class="p-0 mt-2" v-if="items.cvl" style="border-color: var(--info) !important">
                                        <span class="title-emr ml-3">CVL</span>
                                        <div class="columns is-multiline pt-2 pl-4 pr-4 pb-4 ">
                                            <template v-for="its in items.list_CVL ">
                                                <div class="column is-2 p-1" v-if="its.pemeriksaan != undefined">
                                                    <div class="sender-blockx">
                                                        <div class="exerptx">
                                                            <h5 :style="'color:var(--primary) '">
                                                                <i aria-hidden="true" class="fas fa-check"></i>
                                                                <span
                                                                    v-tooltip-prime.bottom="its.pemeriksaan">{{ its.pemeriksaan
                                                                    }}</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </VCard>
                                    <VCard class="p-0 mt-2" v-if="items.ivl"
                                        style="border-color: var(--warning) !important">
                                        <span class="title-emr ml-3">IVL</span>
                                        <div class="columns is-multiline pt-2 pl-4 pr-4 pb-4 ">
                                            <template v-for="its in items.list_IVL ">
                                                <div class="column is-2 p-1" v-if="its.pemeriksaan != undefined">
                                                    <div class="sender-blockx">
                                                        <div class="exerptx">
                                                            <h5 :style="'color:var(--primary) '">
                                                                <i aria-hidden="true" class="fas fa-check"></i>
                                                                <span
                                                                    v-tooltip-prime.bottom="its.pemeriksaan">{{ its.pemeriksaan
                                                                    }}</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </VCard>
                                    <VCard class="p-0 mt-2" v-if="items.uc" style="border-color: var(--danger) !important">
                                        <span class="title-emr ml-3">UC</span>
                                        <div class="columns is-multiline pt-2 pl-4 pr-4 pb-4 ">
                                            <template v-for="its in items.list_UC ">
                                                <div class="column is-2 p-1" v-if="its.pemeriksaan != undefined">
                                                    <div class="sender-blockx">
                                                        <div class="exerptx">
                                                            <h5 :style="'color:var(--primary) '">
                                                                <i aria-hidden="true" class="fas fa-check"></i>
                                                                <span
                                                                    v-tooltip-prime.bottom="its.pemeriksaan">{{ its.pemeriksaan
                                                                    }}</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </VCard>
                                    <VCard class="p-0 mt-2" v-if="items.tc"
                                        style="border-color: var(--secondary) !important">
                                        <span class="title-emr ml-3">TC</span>
                                        <div class="columns is-multiline pt-2 pl-4 pr-4 pb-4 ">
                                            <template v-for="its in items.list_TC ">
                                                <div class="column is-2 p-1" v-if="its.pemeriksaan != undefined">
                                                    <div class="sender-blockx">
                                                        <div class="exerptx">
                                                            <h5 :style="'color:var(--primary) '">
                                                                <i aria-hidden="true" class="fas fa-check"></i>
                                                                <span
                                                                    v-tooltip-prime.bottom="its.pemeriksaan">{{ its.pemeriksaan
                                                                    }}</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </VCard>
                                    <VCard class="p-0 mt-2" v-if="items.op">
                                        <span class="title-emr ml-3">OP</span>
                                        <div class="columns is-multiline pt-2 pl-4 pr-4 pb-4 ">
                                            <template v-for="its in items.list_OP ">
                                                <div class="column is-2 p-1" v-if="its.pemeriksaan != undefined">
                                                    <div class="sender-blockx">
                                                        <div class="exerptx">
                                                            <h5 :style="'color:var(--primary) '">
                                                                <i aria-hidden="true" class="fas fa-check"></i>
                                                                <span
                                                                    v-tooltip-prime.bottom="its.pemeriksaan">{{ its.pemeriksaan
                                                                    }}</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </VCard>
                                    <VTag rounded :label="items.noemr" outlined color="orange" class="mr-2 mt-2" />
                                    <VTag rounded :label="H.formatDateIndoSimple(items.created_at)" outlined
                                        class="is-pulled-right mt-2" />
                                    <!-- <small class="is-tanggal">28 minutes ago</small> -->
                                </template>
                            </UIWidget>
                        </div>
                        <!-- <div class="column is-6">
                            <VCard style="border-radius: 16px;">
                                <Chart type="line" :data="chartData" :options="chartOptions" :height="300"
                                    class="h-30rem" />
                            </VCard>
                        </div> -->
                    </div>
                </div>
                <div class="column is-12" v-else>
                    <div class="page-placeholder">
                        <div class="placeholder-content">
                            <img class="light-image" style=" max-width: 340px;" :src="H.assets().iconNotFound_rev" alt="" />
                            <img class="dark-image" style=" max-width: 340px;" :src="H.assets().iconNotFound_rev" alt="" />
                            <h3>{{ H.assets().notFound }}</h3>
                            <p class="is-larger">
                                {{ H.assets().notFoundSubtitle }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-if="modalInput">
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3> {{ props.FORM_NAME }}</h3>
                        </div>
                        <div class="right">
                            <div class="right">
                                <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                    :isLoading="isLoading" @simpan="simpan" @kembaliKeun="kembaliKeun" :isHideCetak="true">
                                </ButtonEmr>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="columns is-multiline p-2">
            <div class="column is-12">
                <VCard>
                    <div class="columns is-multiline">
                        <div class="column is-3">
                            <VDatePicker class="pt-3 pb-0 pl-0" v-model="input.tanggal" color="green" trim-weeks
                                mode="dateTime" :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }" class="pb-0">
                                    <VField>
                                        <VLabel class="required">Tanggal</VLabel>
                                        <VControl icon="feather:calendar">
                                            <VInput type="text" placeholder="Select a date" :value="inputValue"
                                                v-on="inputEvents" class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-3">
                            <VField>
                                <VLabel class="required">Petugas</VLabel>
                                <VControl class="prime-auto">
                                    <AutoComplete v-model="input.petugas" :suggestions="d_Pegawai"
                                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="Petugas..." class="mt-2" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                        </div>
                        <div class="column is-4">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <VField>
                                        <VControl>
                                            <VSwitchBlock v-model="input.ett" label="ETT" color="danger"
                                                @change="changeSwitch(input.ett, 'ETT')" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <DataTable :value="list_ETT" v-model:selection="selected_ETT" dataKey="id" scrollable>
                                        <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                                        <Column field="pemeriksaan" header="Pemeriksaan"> </Column>
                                    </DataTable>
                                </div>
                            </div>
                        </div>
                        <div class="column is-4">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <VField>
                                        <VControl>
                                            <VSwitchBlock v-model="input.cvl" label="CVL" color="danger"
                                                @change="changeSwitch(input.cvl, 'CVL')" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <DataTable :value="list_CVL" v-model:selection="selected_CVL" dataKey="id" scrollable>
                                        <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                                        <Column field="pemeriksaan" header="Pemeriksaan"></Column>
                                    </DataTable>
                                </div>
                            </div>
                        </div>
                        <div class="column is-4">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <VField>
                                        <VControl>
                                            <VSwitchBlock v-model="input.ivl" label="IVL" color="danger"
                                                @change="changeSwitch(input.ivl, 'IVL')" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <DataTable :value="list_IVL" v-model:selection="selected_IVL" dataKey="id" scrollable>
                                        <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                                        <Column field="pemeriksaan" header="Pemeriksaan"> </Column>
                                    </DataTable>
                                </div>
                            </div>
                        </div>
                        <div class="column is-4">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <VField>
                                        <VControl>
                                            <VSwitchBlock v-model="input.uc" label="UC" color="danger"
                                                @change="changeSwitch(input.uc, 'UC')" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <DataTable :value="list_UC" v-model:selection="selected_UC" dataKey="id" scrollable>
                                        <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                                        <Column field="pemeriksaan" header="Pemeriksaan"> </Column>
                                    </DataTable>
                                </div>
                            </div>
                        </div>
                        <div class="column is-4">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <VField>
                                        <VControl>
                                            <VSwitchBlock v-model="input.tc" label="TC" color="danger"
                                                @change="changeSwitch(input.tc, 'TC')" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <DataTable :value="list_TC" v-model:selection="selected_TC" dataKey="id" scrollable>
                                        <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                                        <Column field="pemeriksaan" header="Pemeriksaan"> </Column>
                                    </DataTable>
                                </div>
                            </div>
                        </div>
                        <div class="column is-4">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <VField>
                                        <VControl>
                                            <VSwitchBlock v-model="input.op" label="OP" color="danger"
                                                @change="changeSwitch(input.op, 'OP')" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <DataTable :value="list_OP" v-model:selection="selected_OP" dataKey="id" scrollable>
                                        <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                                        <Column field="pemeriksaan" header="Pemeriksaan"> </Column>
                                    </DataTable>
                                </div>
                            </div>
                        </div>

                    </div>
                </VCard>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import { followersList } from '/@src/data/widgets/ui/followers'
import { tagList1, tagList2 } from '/@src/data/widgets/ui/tagList'
import { tabs } from '/@src/data/widgets/ui/tabList'
import { iconList } from '/@src/data/widgets/ui/menuList'
import { trendWidgetChartOptions } from '/@src/data/widgets/charts/trendWidgetChart'
import { onceImageErrored } from '/@src/utils/via-placeholder'
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useConfirm } from 'primevue/useconfirm'
import { useThemeColors } from '/@src/composable/useThemeColors'
import Dialog from 'primevue/dialog';
import Chart from 'primevue/chart';
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import AutoComplete from 'primevue/autocomplete';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';                   // optional
import Checkbox from 'primevue/checkbox';
import * as EMR from '../page-emr-plugins/kper'
useHead({
    title: 'Bundle Hais - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pasien_daftar as string

const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const props = withDefaults(
    defineProps<{
        pasien?: any
        registrasi?: any
        FORM_NAME?: string
        FORM_URL?: string
        COLLECTION?: string
    }>(),
    {
        pasien: {},
        registrasi: {},
        FORM_NAME: '',
        FORM_URL: '',
        COLLECTION: '',
    }
)
const modalInput: any = ref(false)
const isLoading: any = ref(false)
const listColor: any = ref(Object.keys(useThemeColors()))
const chartData = ref();
const chartOptions = ref();
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: props.registrasi.norec_apd,
    RUANGAN_LAST: props.registrasi.objectruanganlastfk,
    registrasi: props.registrasi
})
const COLLECTION: any = ref('FaktorResikoDirawat')
const COLLECTION_HEAD: any = ref('FaktorResikoDirawatHead')
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    tanggal: new Date()
})
const listData: any = ref({})
const selected_ETT: any = ref([])
const selected_CVL: any = ref([])
const selected_IVL: any = ref([])
const selected_UC: any = ref([])
const selected_TC: any = ref([])
const selected_OP: any = ref([])

const list_ETT: any = ref([])
const list_CVL: any = ref([])
const list_IVL: any = ref([])
const list_UC: any = ref([])
const list_TC: any = ref([])
const list_OP: any = ref([])
const d_Pegawai = ref([])
const filter: any = ref('')
const listGrid: any = ref([])
const filteredList = computed(() => {
    if (!filter.value) {
        return listGrid.value
    }

    return listGrid.value.filter((items: any) => {
        return (
            items.user_input.namalengkap.match(new RegExp(filter.value, 'i'))
        )
    })
})

const loadMaster = () => {
    useApi().get(
        `/emr/master-faktor-resiko`).then((response: any) => {
            listData.value = response
        })
}
const changeSwitch = (e: any, jenis: any) => {

    if (e == true) {
        if (jenis == 'ETT') {
            list_ETT.value = listData.value.ett
        }
        if (jenis == 'CVL') {
            list_CVL.value = listData.value.cvl
        }
        if (jenis == 'IVL') {
            list_IVL.value = listData.value.ivl
        }
        if (jenis == 'UC') {
            list_UC.value = listData.value.uc
        }
        if (jenis == 'TC') {
            list_TC.value = listData.value.tc
        }
        if (jenis == 'OP') {
            list_OP.value = listData.value.op
        }
    } else {
        if (jenis == 'ETT') {
            list_ETT.value = []
        }
        if (jenis == 'CVL') {
            list_CVL.value = []
        }
        if (jenis == 'IVL') {
            list_IVL.value = []
        }
        if (jenis == 'UC') {
            list_UC.value = []
        }
        if (jenis == 'TC') {
            list_TC.value = []
        }
        if (jenis == 'OP') {
            list_OP.value = []
        }
    }
}
const loadRiwayat = () => {
    isLoading.value = true
    // if (NOREC_EMRPASIEN.value == '') return
    useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${item.NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=`).then((response: any) => {
            isLoading.value = false
            if (response.length) {
                listGrid.value = response
            }
        })
}

const add = () => {
    input.value = {
        tanggal: new Date()
    }
    modalInput.value = true

    list_ETT.value = []
    list_CVL.value = []
    list_IVL.value = []
    list_UC.value = []
    list_TC.value = []
    list_OP.value = []

    selected_ETT.value = []
    selected_CVL.value = []
    selected_IVL.value = []
    selected_UC.value = []
    selected_TC.value = []
    selected_OP.value = []

}
const kembaliKeun = () => {
    modalInput.value = false
    input.value = {
        tanggal: new Date()
    }
}
const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)

    object.list_ETT = selected_ETT.value
    object.list_CVL = selected_CVL.value
    object.list_IVL = selected_IVL.value
    object.list_UC = selected_UC.value
    object.list_TC = selected_TC.value
    object.list_OP = selected_OP.value

    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'collection_head': COLLECTION_HEAD.value,
        'url_form': props.FORM_URL,
        'name_form': props.FORM_NAME,
        'data': object
    }
    isLoading.value = true
    useApi().post(
        `/emr/simpan-bundle-hais`, json).then((response: any) => {
            isLoading.value = false
            loadRiwayat()
            modalInput.value = false
        }).catch((e: any) => {
            isLoading.value = false
        })

}
const edit = (e: any) => {
    input.value = e
    if (e.ett) {
        list_ETT.value = listData.value.ett
        selected_ETT.value = e.list_ETT
    }
    if (e.cvl) {
        list_CVL.value = listData.value.cvl
        selected_CVL.value = e.list_CVL
    }
    if (e.ivl) {
        list_IVL.value = listData.value.ivl
        selected_IVL.value = e.list_IVL
    }

    if (e.uc) {
        list_UC.value = listData.value.uc
        selected_UC.value = e.list_UC
    }
    if (e.tc) {
        list_TC.value = listData.value.tc
        selected_TC.value = e.list_TC
    }
    if (e.op) {
        list_OP.value = listData.value.op
        selected_OP.value = e.list_OP
    }


    NOREC_EMRPASIEN.value = e.emrpasienfk
    modalInput.value = true
}
const remove = async (e: any) => {
    e.loadingHapus = true

    await useApi().post(
        `/emr/hapus-bundle-hais`, {
        'norec': e.emrpasienfk,
        'collection': COLLECTION.value,
        'collection_head': COLLECTION_HEAD.value,
    }).then((response: any) => {
        e.loadingHapus = false
        listGrid.value.forEach((element: any, i: any) => {
            if (e.id == element.id) {
                listGrid.value.splice(i, 1);
            }
        });
    })
}
const fetchPegawai = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Pegawai.value = response
    })
}

onMounted(() => {

});
loadMaster()
loadRiwayat()
</script>
<style  lang="scss">
@import '/@src/scss/abstracts/all';

.list-widget {
    @include vuero-l-card;

    &.is-straight {
        @include vuero-s-card;
    }

    ul {
        li {
            &:not(:last-child) {
                margin-bottom: 12px;
            }

            a {
                font-family: var(--font);
                display: flex;
                justify-content: space-between;
                color: var(--light-text);

                &:hover,
                &:focus {
                    color: var(--primary);
                }
            }
        }
    }
}

.is-dark {
    .list-widget {
        @include vuero-card--dark;

        ul {
            li {
                a {
                    &:hover {
                        color: var(--primary);
                    }
                }
            }
        }
    }
}

.widget-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;

    .left {
        display: flex;
        align-items: center;
    }

    .center {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .right {
        display: flex;
        align-items: center;
        justify-content: flex-end;

        .tag {
            font-family: var(--font);
        }

        .right-icon {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 32px;
            width: 32px;
            min-width: 32px;
            border-radius: var(--radius-rounded);
            color: var(--light-text-light-12);
            transition: all 0.3s; // transition-all test

            &.has-indicator {
                &::after {
                    content: '';
                    position: absolute;
                    top: 3px;
                    right: 4px;
                    height: 10px;
                    width: 10px;
                    border-radius: var(--radius-rounded);
                    background: var(--secondary);
                    border: 1.8px solid var(--white);
                }
            }

            svg {
                height: 18px;
                width: 18px;
                transition: stroke 0.3s;
            }
        }
    }

    h3 {
        font-family: var(--font-alt);
        font-size: 0.9rem;
        color: var(--dark-text);
        font-weight: 600;

        &.is-bigger {
            font-size: 1rem;
        }
    }

    .action-icon {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 32px;
        width: 32px;
        min-width: 32px;
        border-radius: var(--radius-rounded);
        color: var(--light-text-light-12);
        transition: all 0.3s; // transition-all test

        svg {
            height: 18px;
            width: 18px;
            transition: stroke 0.3s;
        }
    }
}

.is-dark {
    .widget-toolbar {
        h3 {
            color: var(--dark-dark-text);
        }

        .right {
            .right-icon {
                &.has-indicator {
                    &::after {
                        border-color: var(--dark-sidebar-light-6);
                    }
                }
            }
        }
    }
}

small.is-tanggal {
    color: var(--light-text);
}

.is-dark-text {
    color: var(--light-text);
}
</style>