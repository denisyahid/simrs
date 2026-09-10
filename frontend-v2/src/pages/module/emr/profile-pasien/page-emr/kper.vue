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
                                    <VCard class="p-0" v-if="items.rajal && items.rajal_true">
                                        <span class="title-emr ml-3">Rawat Jalan</span>
                                        <div class="columns is-multiline pt-2 pl-4 pr-4 pb-4 ">
                                            <template v-for="its in items.rajal ">
                                                <div class="column is-3 p-1"
                                                    v-if="its.ya != undefined || its.tidak != undefined">
                                                    <div class="sender-blockx">
                                                        <div class="exerptx">
                                                            <h5
                                                                :style="'color:var(--' + (its.ya != undefined && its.ya == true ? 'primary' : (its.tidak != undefined && its.tidak == true ? 'danger' : '')) + ')'">
                                                                <i aria-hidden="true" class="fas fa-circle"></i>
                                                                <span v-tooltip-prime.bottom="its.namakper">{{
                                                                    its.namakper
                                                                }}</span>
                                                            </h5>
                                                            <small>{{ its.ya != undefined && its.ya == true ? 'Ya' : (
                                                                its.tidak != undefined && its.tidak == true ? 'Tidak' : '')
                                                            }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </VCard>
                                    <VCard class="p-0 mt-2" v-if="items.igd && items.igd_true">
                                        <span class="title-emr ml-3">IGD</span>
                                        <div class="columns is-multiline pt-2 pl-4 pr-4 pb-4 ">
                                            <template v-for="its in items.igd">
                                                <div class="column is-3 p-1"
                                                    v-if="its.ya != undefined || its.tidak != undefined">
                                                    <div class="sender-blockx">
                                                        <div class="exerptx">
                                                            <h5
                                                                :style="'color:var(--' + (its.ya != undefined && its.ya == true ? 'primary' : (its.tidak != undefined && its.tidak == true ? 'danger' : '')) + ')'">
                                                                <i aria-hidden="true" class="fas fa-circle"></i>
                                                                <span v-tooltip-prime.bottom="its.namakper">{{
                                                                    its.namakper
                                                                }}</span>
                                                            </h5>
                                                            <small>{{ its.ya != undefined && its.ya == true ? 'Ya' : (
                                                                its.tidak != undefined && its.tidak == true ? 'Tidak' : '')
                                                            }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </VCard>
                                    <VCard class="p-0 mt-2" v-if="items.ranap && items.ranap_true">
                                        <span class="title-emr ml-3">Rawat Inap</span>
                                        <div class="columns is-multiline pt-2 pl-4 pr-4 pb-4 ">
                                            <template v-for="its in items.ranap">
                                                <div class="column is-3 p-1"
                                                    v-if="its.ya != undefined || its.tidak != undefined">
                                                    <div class="sender-blockx">
                                                        <div class="exerptx">
                                                            <h5
                                                                :style="'color:var(--' + (its.ya != undefined && its.ya == true ? 'primary' : (its.tidak != undefined && its.tidak == true ? 'danger' : '')) + ')'">
                                                                <i aria-hidden="true" class="fas fa-circle"></i>
                                                                <span v-tooltip-prime.bottom="its.namakper">{{
                                                                    its.namakper
                                                                }}</span>
                                                            </h5>
                                                            <small>{{ its.ya != undefined && its.ya == true ? 'Ya' : (
                                                                its.tidak != undefined && its.tidak == true ? 'Tidak' : '')
                                                            }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </VCard>



                                    <VTag rounded :label="items.noemr" outlined color="orange" class="mr-2" />
                                    <VTag rounded :label="H.formatDateIndoSimple(items.created_at)" outlined
                                        class="is-pulled-right" />
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
                        <div class="column is-12">
                            <TabView>
                                <TabPanel header="Rawat Inap">
                                    <DataTable :value="listKperRanap" rowGroupMode="subheader" groupRowsBy="kelompok"
                                        sortMode="single" sortField="kelompok" :sortOrder="1" scrollable
                                        scrollHeight="400px" tableStyle="min-width: 50rem">
                                        <Column field="kelompok" header="Kelompok"></Column>
                                        <Column field="namakper" header="Nama Keperawatan" style="min-width: 200px">
                                        </Column>
                                        <Column field="ya" header="Ya" style="min-width: 100px">
                                            <template #body="slotProps">
                                                <Checkbox v-model="slotProps.data.ya" :binary="true" />
                                            </template>
                                        </Column>
                                        <Column field="tidak" header="Tidak" style="min-width: 100px">
                                            <template #body="slotProps">
                                                <Checkbox v-model="slotProps.data.tidak" :binary="true" />
                                            </template>
                                        </Column>
                                        <template #groupheader="slotProps">
                                            <div class="flex align-items-center gap-2">
                                                <span style="font-weight:bold">{{ slotProps.data.kelompok }}</span>
                                            </div>
                                        </template>
                                    </DataTable>
                                </TabPanel>
                                <TabPanel header="Rawat Jalan">
                                    <DataTable :value="listKperRajal" rowGroupMode="subheader" groupRowsBy="kelompok"
                                        sortMode="single" sortField="kelompok" :sortOrder="1" scrollable
                                        scrollHeight="400px" tableStyle="min-width: 50rem">
                                        <Column field="kelompok" header="Kelompok"></Column>
                                        <Column field="namakper" header="Nama Keperawatan" style="min-width: 200px">
                                        </Column>
                                        <Column field="ya" header="Ya" style="min-width: 100px">
                                            <template #body="slotProps">
                                                <Checkbox v-model="slotProps.data.ya" :binary="true" />
                                            </template>
                                        </Column>
                                        <Column field="tidak" header="Tidak" style="min-width: 100px">
                                            <template #body="slotProps">
                                                <Checkbox v-model="slotProps.data.tidak" :binary="true" />
                                            </template>
                                        </Column>
                                        <template #groupheader="slotProps">
                                            <div class="flex align-items-center gap-2">
                                                <span style="font-weight:bold">{{ slotProps.data.kelompok }}</span>
                                            </div>
                                        </template>
                                    </DataTable>
                                </TabPanel>
                                <TabPanel header="IGD">
                                    <DataTable :value="listKperIgd" rowGroupMode="subheader" groupRowsBy="kelompok"
                                        sortMode="single" sortField="kelompok" :sortOrder="1" scrollable
                                        scrollHeight="400px" tableStyle="min-width: 50rem">
                                        <Column field="kelompok" header="Kelompok"></Column>
                                        <Column field="namakper" header="Nama Keperawatan" style="min-width: 200px">
                                        </Column>
                                        <Column field="ya" header="Ya" style="min-width: 100px">
                                            <template #body="slotProps">
                                                <Checkbox v-model="slotProps.data.ya" :binary="true" />
                                            </template>
                                        </Column>
                                        <Column field="tidak" header="Tidak" style="min-width: 100px">
                                            <template #body="slotProps">
                                                <Checkbox v-model="slotProps.data.tidak" :binary="true" />
                                            </template>
                                        </Column>
                                        <template #groupheader="slotProps">
                                            <div class="flex align-items-center gap-2">
                                                <span style="font-weight:bold">{{ slotProps.data.kelompok }}</span>
                                            </div>
                                        </template>
                                    </DataTable>
                                </TabPanel>

                            </TabView>
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
    title: 'KPER- ' + import.meta.env.VITE_PROJECT,
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
const listKperRajal = ref(EMR.listKperRajal())
const listKperIgd = ref(EMR.listIgd())
const listKperRanap = ref(EMR.listKperRanap())

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
const COLLECTION: any = ref('KPER')
const COLLECTION_HEAD: any = ref('KPERHead')
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    tanggal: new Date()
})
const dataSourceIAD: any = ref([])
const dataSourceISK: any = ref([])
const dataSourceVAP: any = ref([])
const dataSourceIDO: any = ref([])
const dataSourcePHE: any = ref([])
const d_Pegawai = ref([])
const filter: any = ref('')
const listVital: any = ref([])
const filteredList = computed(() => {
    if (!filter.value) {
        return listVital.value
    }

    return listVital.value.filter((items: any) => {
        return (
            items.user_input.namalengkap.match(new RegExp(filter.value, 'i'))
        )
    })
})


const loadRiwayat = () => {
    isLoading.value = true
    // if (NOREC_EMRPASIEN.value == '') return
    useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${item.NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=`).then((response: any) => {
            isLoading.value = false
            if (response.length) {
                for (var z = response.length - 1; z >= 0; z--) {
                    const elementZ = response[z];
                    if (elementZ.rajal) {
                        let stat = false
                        for (var i = elementZ.rajal.length - 1; i >= 0; i--) {
                            const element = elementZ.rajal[i];
                            if (element.ya != undefined || element.tidak != undefined) {
                                stat = true
                            }
                        }
                        elementZ.rajal_true = stat
                    }
                    if (elementZ.igd) {
                        let stat = false
                        for (var i = elementZ.igd.length - 1; i >= 0; i--) {
                            const element = elementZ.igd[i];
                            if (element.ya != undefined || element.tidak != undefined) {
                                // elementZ.ISK.splice(i, 1);
                                stat = true
                            }
                        }
                        elementZ.igd_true = stat
                    }
                    if (elementZ.ranap) {
                        let stat = false
                        for (var i = elementZ.ranap.length - 1; i >= 0; i--) {
                            const element = elementZ.ranap[i];
                            if (element.ya != undefined || element.tidak != undefined) {
                                // elementZ.VAP.splice(i, 1);
                                stat = true
                            }
                        }
                        elementZ.ranap_true = stat
                    }

                }

                listVital.value = response


            }
        })
}

const add = () => {
    input.value = {
        tanggal: new Date()
    }
    modalInput.value = true
    // loadMasterBundle()
    listKperRajal.value = EMR.listKperRajal()
    listKperIgd.value = EMR.listIgd()
    listKperRanap.value = EMR.listKperRanap()
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

    object.rajal = listKperRajal.value
    object.igd = listKperIgd.value
    object.ranap = listKperRanap.value

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
    if (e.rajal) {
        listKperRajal.value = e.rajal
    }
    if (e.igd) {
        listKperIgd.value = e.igd
    }
    if (e.ranap) {
        listKperRanap.value = e.ranap
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
        listVital.value.forEach((element: any, i: any) => {
            if (e.id == element.id) {
                listVital.value.splice(i, 1);
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
watch(
    () => [
        input.value.beratBadan,
        input.value.tinggiBadan],
    (value) => {
        let txtFirstNumberValue: any = input.value.beratBadan;
        let txtSecondNumberValue: any = input.value.tinggiBadan;
        let result: any = parseFloat(txtFirstNumberValue) / (parseFloat(txtSecondNumberValue) / 100
            * parseFloat(txtSecondNumberValue) / 100);

        input.value.IMT = parseFloat(result).toFixed(2)
        if (isNaN(input.value.IMT)) {
            input.value.IMT = 0
        }
    }
)
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
