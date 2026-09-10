<template>
    <ConfirmDialog />
    <div class="form-layout is-stacked-2" >

        <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3>Diagnosis</h3>
                    </div>
                    <div class="right">
                        <div class="buttons">
                            <!-- <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                Kembali
                            </VButton> -->
                            <!-- <VButton type="submit" color="primary" raised icon="feather:save" :loading="isLoading"
                                @click="simpan()"> Save
                            </VButton> -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="form-body p-2">
                <div class="business-dashboard hr-dashboard">
                    <div class="columns is-multiline">
                        <div class="column is-12" v-if="isLoadingPasien">
                            <PlaceloadHeader class="m-3" />
                        </div>
                        <div class="column is-12" v-if="!isLoadingPasien">
                            <HeadPasien :pasien="pasien" class="m-3" />
                        </div>
                    </div>
                </div>

            </div> -->
        </div>
        <div class="columns is-multiline mt-2 ml-1 mr-1">
            <VCard>
                <VTabs slider centered selected="icd10" :tabs="[
                    { label: 'ICD 10', value: 'icd10' },
                    { label: 'ICD 9', value: 'icd9' }]">
                    <template #tab="{ activeValue }">
                        <div v-if="activeValue === 'icd10'">
                            <div class="columns is-multiline p-1">
                                <div class="column is-3 is-offset-9 "
                                    style="margin-top:-20px !important;margin-bottom: -15px  !important;">
                                    <VButton type="button" color="info" raised rounded icon="feather:plus-circle"
                                        class="is-pulled-right mr-3 mt-0 mb-0" @click="showModal()"> Tambah
                                    </VButton>
                                </div>
                                <div class="column is-12">


                                    <!-- <div class="column is-2" v-if="dataSourceX.loading">
                                        <div class="list-view list-view-v1">
                                            <div class="list-view-inner">
                                                <div v-for="key in 10" :key="key" class="list-view-item mb-10">
                                                    <VAvatarStack class="is-pushed-mobile">
                                                        <VPlaceload class="mx-2 h-hidden-tablet-p mt-3" />
                                                        <VPlaceloadAvatar size="small" class="mx-1" />
                                                    </VAvatarStack>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="column is-12" v-if="dataSourceX.loading">
                                        <div class="list-view list-view-v1">
                                            <div class="list-view-inner">
                                                <!--Item-->
                                                <div v-for="key in 5" :key="key" class="list-view-item">
                                                    <VPlaceloadWrap>
                                                        <VPlaceloadAvatar size="medium" />
                                                        <VPlaceloadText last-line-width="60%" class="mx-2" />
                                                        <VPlaceload class="mx-2" disabled />
                                                        <VPlaceload class="mx-2 h-hidden-tablet-p" />
                                                        <VPlaceload class="mx-2 h-hidden-tablet-p" />
                                                        <VPlaceload class="mx-2" />
                                                    </VPlaceloadWrap>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-12" v-else>
                                        <div class="timeline-wrapper" v-if="dataSourceX.length > 0">
                                            <div class="timeline-header"></div>
                                            <div class="timeline-wrapper-inner pt-0">
                                                <div class="timeline-container">
                                                    <div class="timeline-item is-unread " v-for="(items, index)  in dataSourceX" :key="items.norec">

                                                        <div class="date">
                                                            <span>{{ H.formatDateIndo(items.tglinputdiagnosa) }}</span>
                                                        </div>
                                                        <div :class="'dot is-' + listColor[index + 1]"></div>

                                                        <div class="content-wrap is-grey">
                                                            <div class="content-box ">
                                                                <div class="status"></div>
                                                                <VIconBox size="medium" :color="listColor[index + 1]"
                                                                    rounded>
                                                                    <i class="lnir lnir-diagnosis" aria-hidden="true"></i>
                                                                </VIconBox>
                                                                <div class="box-text" style="width:70%">
                                                                    <div class="meta-text">
                                                                        <p>
                                                                            <span>{{ items.kddiagnosa ? items.kddiagnosa + ' - ' + items.namadiagnosa  : ''}}</span>

                                                                        </p>

                                                                        <!-- <br /> -->
                                                                        <table style="width:100%">
                                                                          <tr>
                                                                                <td class="font-labels" width="15%">
                                                                                    Jenis</td>
                                                                                <td class="font-labels">:</td>
                                                                                <td class="font-values" width="80%">{{items.jenisdiagnosa }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="font-labels" width="15%">
                                                                                    Keterangan</td>
                                                                                <td class="font-labels">:</td>
                                                                                <td class="font-values" width="80%">{{items.keterangan }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="font-labels">
                                                                                    Penginput</td>
                                                                                <td class="font-labels">:</td>
                                                                                <td class="font-values">{{items.namalengkap }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="font-labels">
                                                                                    Kasus</td>
                                                                                <td class="font-labels">:</td>
                                                                                <td class="font-values">{{
                                                                                    items.iskasuslama == true ? 'lama' :
                                                                                    (items.iskasusbaru == true ? 'baru' :
                                                                                        '')
                                                                                }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="font-labels">
                                                                                    No Registrasi </td>
                                                                                <td class="font-labels">:</td>
                                                                                <td class="font-values">{{
                                                                                    items.noregistrasi }}</td>
                                                                            </tr>
                                                                        </table>

                                                                    </div>
                                                                </div>
                                                                <div class="box-end" style="width:30%">
                                                                    <div class="columns is-multiline">
                                                                        <div class="column is-12">
                                                                            <div class="status is-pulled-right mt-2 ml-2">
                                                                            </div>
                                                                            <VTag :label="items.jenisdiagnosa"
                                                                                color="warning" class="is-pulled-right"
                                                                                rounded />
                                                                        </div>

                                                                        <div class="column is-6 mt-3">
                                                                            <b> {{ items.namaruangan }}</b>
                                                                        </div>
                                                                        <div class="column is-6">
                                                                            <VIconButton icon="feather:edit"
                                                                                @click="editItems(items)" color="warning"
                                                                                raised circle class="mr-2">
                                                                            </VIconButton>
                                                                            <VIconButton icon="feather:trash"
                                                                                @click="DialogConfirm(items)" color="danger"
                                                                                raised circle>
                                                                            </VIconButton>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="load-more-wrap has-text-centered p-1 mb-3">
                                                    <VField class="ml-6">
                                                        <VLabel>Terdapat {{ dataSourceX.length }} data.
                                                        </VLabel>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                        <VCard radius="rounded" class="mt-2" v-else-if="dataSourceX.length == 0">
                                            <VPlaceholderPage :title="H.assets().notFound"
                                                :subtitle="H.assets().notFoundSubtitle" larger>
                                                <template #image>
                                                    <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                                                    <img class="dark-image"
                                                        src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                                        alt="" />
                                                </template>
                                            </VPlaceholderPage>
                                        </VCard>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div v-if="activeValue === 'icd9'">
                            <div class="columns is-multiline p-1">
                                <div class="column is-3 is-offset-9 "
                                    style="margin-top:-20px !important;margin-bottom: -15px  !important;">
                                    <VButton type="button" color="info" raised rounded icon="feather:plus-circle"
                                        class="is-pulled-right mr-3 mt-0 mb-0" @click="showModal9()"> Tambah
                                    </VButton>
                                </div>
                                <div class="column is-10" v-if="dataSourceIX.loading">
                                    <div class="list-view list-view-v1">
                                        <div class="list-view-inner">
                                            <!--Item-->
                                            <div v-for="key in 5" :key="key" class="list-view-item">
                                                <VPlaceloadWrap>
                                                    <VPlaceloadAvatar size="medium" />
                                                    <VPlaceloadText last-line-width="60%" class="mx-2" />
                                                    <VPlaceload class="mx-2" disabled />
                                                    <VPlaceload class="mx-2 h-hidden-tablet-p" />
                                                    <VPlaceload class="mx-2 h-hidden-tablet-p" />
                                                    <VPlaceload class="mx-2" />
                                                </VPlaceloadWrap>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12" v-else>
                                    <div class="timeline-wrapper" v-if="dataSourceIX.length > 0">
                                        <div class="timeline-header"></div>
                                        <div class="timeline-wrapper-inner pt-0">
                                            <div class="timeline-container">
                                                <div class="timeline-item is-unread "
                                                    v-for="(items, index)  in dataSourceIX" :key="items.norec">

                                                    <div class="date">
                                                        <span>{{ H.formatDateIndo(items.tglinputdiagnosa) }}</span>
                                                    </div>
                                                    <div :class="'dot is-' + listColor[index + 1]"></div>

                                                    <div class="content-wrap is-grey">
                                                        <div class="content-box ">
                                                            <div class="status"></div>
                                                            <VIconBox size="medium" :color="listColor[index + 1]" rounded>
                                                                <i class="lnir lnir-dna" aria-hidden="true"></i>
                                                            </VIconBox>

                                                            <div class="box-text" style="width:70%">
                                                                <div class="meta-text">
                                                                    <p>
                                                                        <span>{{ items.kddiagnosatindakan + ' - ' +
                                                                            items.namadiagnosatindakan }}</span>
                                                                    </p>
                                                                    <!-- <VTag :label="items.jenisdiagnosa" color="purple"
                                                                        rounded />
                                                                    <br /> -->
                                                                    <table style="width:100%">
                                                                        <tr>
                                                                            <td class="font-labels" width="15%">
                                                                                Keterangan</td>
                                                                            <td class="font-labels">:</td>
                                                                            <td class="font-values" width="80%">{{
                                                                                items.keterangantindakan }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="font-labels">
                                                                                Penginput</td>
                                                                            <td class="font-labels">:</td>
                                                                            <td class="font-values">{{
                                                                                items.namalengkap }}</td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="font-labels">
                                                                                No Registrasi </td>
                                                                            <td class="font-labels">:</td>
                                                                            <td class="font-values">{{
                                                                                items.noregistrasi }}</td>
                                                                        </tr>
                                                                    </table>

                                                                </div>
                                                            </div>
                                                            <div class="box-end" style="width:30%">
                                                                <div class="columns is-multiline">

                                                                    <div class="column is-6 mt-3">
                                                                        <b> {{ items.namaruangan }}</b>
                                                                    </div>
                                                                    <div class="column is-6">
                                                                        <VIconButton icon="feather:edit"
                                                                            @click="editItemsIX(items)" color="warning"
                                                                            raised circle class="mr-2">
                                                                        </VIconButton>
                                                                        <VIconButton icon="feather:trash"
                                                                            @click="DialogConfirmIX(items)" color="danger"
                                                                            raised circle>
                                                                        </VIconButton>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="load-more-wrap has-text-centered p-1 mb-3">
                                                <VField class="ml-6">
                                                    <VLabel>Terdapat {{ dataSourceIX.length }} data.
                                                    </VLabel>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>
                                    <VCard radius="rounded" class="mt-2" v-if="dataSourceIX.length === 0">
                                        <VPlaceholderPage :title="H.assets().notFound"
                                            :subtitle="H.assets().notFoundSubtitle" larger>
                                            <template #image>
                                                <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                                                <img class="dark-image"
                                                    src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                                    alt="" />
                                            </template>
                                        </VPlaceholderPage>
                                    </VCard>
                                </div>
                            </div>
                        </div>
                    </template>
                </VTabs>
            </VCard>
        </div>
        <VModal :open="modalInput" title="Diagnosis ICD 10" :noclose="false" size="big" actions="right"
            @close="modalInput = false">
            <template #content>
                <form class="modal-form">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <VCard>
                                <div class="columns is-multiline p-1">

                                    <div class="column is-3">
                                        <VField label="Tanggal">
                                            <VDatePicker v-model="item.tglpelayanan" mode="dateTime" style="width: 100%;">
                                                <template #default="{ inputValue}">
                                                    <VField>
                                                        <VControl icon="feather:calendar" fullwidth>
                                                            <VInput :value="inputValue" placeholder="Tanggal"
                                                                 class="is-rounded" disabled />
                                                        </VControl>
                                                    </VField>
                                                </template>
                                            </VDatePicker>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField label="Jenis " class="is-rounded-select is-autocomplete-select"
                                            v-slot="{ id }">
                                            <VControl icon="feather:list" fullwidth>
                                                <Multiselect mode="single" v-model="item.jenisDiagnosis10"
                                                    :options="d_JenisDiagnosa" placeholder="Pilih data" :searchable="true"
                                                    :attrs="{ id }" autocomplete="off" />


                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-5">
                                        <VField label="Diagnosis ICD 10 " class="is-rounded-select  is-autocomplete-select"
                                            v-slot="{ id }">
                                            <VControl icon="lnir lnir-diagnosis" fullwidth>

                                                <Multiselect mode="single" v-model="item.diagnosa10"
                                                    placeholder="Pilih data" :searchable="true" :filter-results="false"
                                                    :min-chars="0" :resolve-on-load="true" :delay="0" :options="async function (query: any) {
                                                        return await fetchDiagnosa10(query)
                                                    }" autocomplete="off" />


                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3">
                                        <VField>
                                            <VControl>
                                                <VRadio v-model="item.isKasusBaru" :value="'baru'" label="Kasus Baru"
                                                    name="isKasusBaru" color="primary" id="isKasusBaru" />
                                                <VRadio v-model="item.isKasusBaru" :value="'lama'" label="Kasus Lama"
                                                    name="isKasusLama" color="danger" id="isKasusLama" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-9">
                                        <VField label="Keterangan">
                                            <VControl>
                                                <VTextarea class="textarea is-rounded" v-model="item.keterangan10" rows="3"
                                                    placeholder="Keterangan diagnosis (jika belum tahu kodenya) ..."
                                                    autocomplete="off" autocapitalize="off" spellcheck="true" />
                                            </VControl>
                                        </VField>
                                    </div>

                                </div>
                            </VCard>
                        </div>
                    </div>
                </form>
            </template>
            <template #action>
                <VButton icon="feather:plus" @click="simpanICD10()" :loading="isLoading" color="primary" raised>Simpan
                </VButton>
            </template>
        </VModal>

        <VModal :open="modalInput9" title="Diagnosis ICD 9" :noclose="false" size="big" actions="right"
            @close="modalInput9 = false">
            <template #content>
                <form class="modal-form">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <VCard>
                                <div class="columns is-multiline p-1">

                                    <div class="column is-3">
                                        <VField label="Tanggal">
                                            <VDatePicker v-model="item.tglpelayanan" mode="dateTime" style="width: 100%;">
                                                <template #default="{ inputValue, inputEvents }" :max-date="new Date()">
                                                    <VField>
                                                        <VControl icon="feather:calendar" fullwidth>
                                                            <VInput :value="inputValue" placeholder="Tanggal"
                                                                class="is-rounded" v-on="inputEvents" disabled />
                                                        </VControl>
                                                    </VField>
                                                </template>
                                            </VDatePicker>
                                        </VField>
                                    </div>

                                    <div class="column is-5">
                                        <VField label="Diagnosis ICD 9 " class="is-rounded-select  is-autocomplete-select"
                                            v-slot="{ id }">
                                            <VControl icon="lnir lnir-diagnosis" fullwidth>

                                                <Multiselect mode="single" v-model="item.diagnosa9" placeholder="Pilih data"
                                                    :searchable="true" :filter-results="false" :min-chars="0"
                                                    :resolve-on-load="true" :delay="0" :options="async function (query: any) {
                                                        return await fetchDiagnosa9(query)
                                                    }" autocomplete="off" />
                                            </VControl>
                                        </VField>
                                    </div>

                                    <div class="column is-4">
                                        <VField label="Keterangan">
                                            <VControl>
                                                <VTextarea class="textarea is-rounded" v-model="item.keterangan9" rows="3"
                                                    placeholder="Keterangan diagnosis (jika belum tahu kodenya) ..."
                                                    autocomplete="off" autocapitalize="off" spellcheck="true" />
                                            </VControl>
                                        </VField>
                                    </div>

                                </div>
                            </VCard>
                        </div>
                    </div>
                </form>
            </template>
            <template #action>
                <VButton icon="feather:plus" @click="simpanICD9()" :loading="isLoading" color="primary" raised>Simpan
                </VButton>
            </template>
        </VModal>
    </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useToaster } from '/@src/composable/toaster'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'

useHead({
    title: 'Diagnosis - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pasien_daftar as string
const props = defineProps({
    registrasi: {
        type: Object as PropType<any>,
    },
    pasien: {
        type: Object as PropType<any>,
    },
})
const isLoadingPasien: any = ref(false)
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: props.registrasi.norec_apd,
    RUANGAN_LAST: props.registrasi.objectruanganlastfk,
    hargasatuan: 0,
    tglpelayanan: new Date(),
    totalHarga: 0,
    registrasi: props.registrasi
})
const confirm = useConfirm()
const activeValue = ref('icd10')
const d_JenisDiagnosa: any = ref([])
const d_Diagnosa: any = ref([])
const pasien: any = ref({})
const { y } = useWindowScroll()
const dataSourceX: any = ref([])
const dataSourceIX: any = ref([])
const listColor: any = ref(Object.keys(useThemeColors()))
const isStuck = computed(() => { return y.value > 30 })
const modalInput: any = ref(false)
const modalInput9: any = ref(false)
const isLoading: any = ref(false)
const isDetail: any = ref([false])
function pasienByID(id: any) {
    riwayatDiagnosa10()
    riwayatDiagnosa9()
    // isLoadingPasien.value = true
    // useApi().get(
    //     `/diagnosa/header-pasien?nocmfk=${id}&norec_pd=${item.NOREC_PD}`).then((response: any) => {
    //         pasien.value = response.pasien
    //         item.NOREC_APD = response.last_registrasi.norec_apd
    //         item.RUANGAN_LAST = response.last_registrasi.objectruanganlastfk
    //         item.registrasi = response.last_registrasi
    //         isLoadingPasien.value = false
    //         riwayatDiagnosa10()
    //         riwayatDiagnosa9()
    //     })
}


function dropdownList() {
    useApi().get(
        `/diagnosa/list-dropdown`).then((response: any) => {
            d_JenisDiagnosa.value = response.jenisdiagnosa.map((e: any) => { return { label: e.jenisdiagnosa, value: e.id, default: e } })

        })
}


function showModal() {
    clearInput()
    item.tglpelayanann = new Date()
    modalInput.value = true
}
function showModal9() {
    clearInput()
    item.tglpelayanann = new Date()
    modalInput9.value = true

}

async function editItems(e: any) {
    clearInput()
    item.NOREC_DIAGNOSA10 = e.norec_diagnosapasien
    item.isKasusBaru = e.iskasusbaru == true ? 'baru' : (e.iskasuslama == true ? 'lama' : null)
    item.keterangan10 = e.keterangan
    // fetchDiagnosa10(e.kddiagnosa)
    // const response = await useApi().get(
    //     `/diagnosa/diagnosa-x-paging?name= ${e.kddiagnosa}&limit=1`)
    // d_Diagnosa.value = response.diagnosa.map((item: any) => {
    //     return { value: item.id, label: item.kddiagnosa + ' - ' + item.namadiagnosa, default: item }
    // })

    item.diagnosa10 = e.objectdiagnosafk
    item.jenisDiagnosis10 = e.objectjenisdiagnosafk
    item.tglpelayanan = new Date(e.tglinputdiagnosa)
    modalInput.value = true
}
async function editItemsIX(e: any) {
    clearInput()
    item.NOREC_DIAGNOSA9 = e.norec_diagnosapasien
    item.keterangan9 = e.keterangantindakan
    // fetchDiagnosa9(e.kddiagnosatindakan)
    item.diagnosa9 = e.objectdiagnosatindakanfk
    item.tglpelayanan = new Date(e.tglinputdiagnosa)
    modalInput9.value = true
}
function hapusItems(e: any) {
    useApi().post(
        `/diagnosa/delete-diagnosa-x`, { norec: e.norec_diagnosapasien }).then((response: any) => {
            isLoading.value = false
            riwayatDiagnosa10()
        }).catch((e: any) => {
            isLoading.value = false
        })
}
function hapusItemsIX(e: any) {
    useApi().post(
        `/diagnosa/delete-diagnosa-ix`, { norec: e.norec_diagnosapasien }).then((response: any) => {
            isLoading.value = false
            riwayatDiagnosa9()
        }).catch((e: any) => {
            isLoading.value = false
        })
}

const DialogConfirm = (e: any) => {
    confirm.require({
        message: 'Apakah anda yakin menghapus data ini ?',
        header: 'Konfirmasi Hapus Data',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            hapusItems(e)

        },
        reject: () => { },
    })
}

const DialogConfirmIX = (e: any) => {
    confirm.require({
        message: 'Apakah anda yakin menghapus data ini ?',
        header: 'Konfirmasi Hapus Data',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            hapusItemsIX(e)

        },
        reject: () => { },
    })
}


function clearInput() {
    delete item.NOREC_DIAGNOSA9
    delete item.NOREC_DIAGNOSA10
    delete item.keterangan9
    delete item.diagnosa9
    delete item.keterangan10
    delete item.diagnosa10
    delete item.jenisDiagnosis10

    modalInput.value = false
    modalInput9.value = false
}
function kembaliKeun() {
    window.history.back()
}
async function fetchDiagnosa10(filter: any) {
    let query = ''
    if (filter) {
        query = filter.toLowerCase()
    }
    const response = await useApi().get(
        `/diagnosa/diagnosa-x-paging?name=${query}&limit=10`)

    return response.diagnosa.map((item: any) => {
        return { value: item.id, label: item.kddiagnosa + ' - ' + item.namadiagnosa, default: item }
    })
}
async function fetchDiagnosa9(filter: any) {
    let query = ''
    if (filter) {
        query = filter.toLowerCase()
    }
    const response = await useApi().get(
        `/diagnosa/diagnosa-ix-paging?name=${query}&limit=10`)

    return response.diagnosatindakan.map((item: any) => {
        return { value: item.id, label: item.kddiagnosatindakan + ' - ' + item.namadiagnosatindakan, default: item }
    })
}
async function simpanICD9() {
    if (!item.tglpelayanan) {
        useToaster().error('Tgl  harus di isi')
        return
    }

    if (!item.diagnosa9) {
        useToaster().error('Diagnosis harus di isi')
        return
    }

    let json = {
        'diagnosapasien': {
            'norec': item.NOREC_DIAGNOSA9 ? item.NOREC_DIAGNOSA9 : '',
            'noregistrasifk': item.NOREC_APD,
            'tglregistrasi': item.registrasi.tglregistrasi,
            'keterangantindakan': item.keterangan9 ? item.keterangan9 : null,

        },
        'detaildiagnosapasien': {
            'objectdiagnosatindakanfk': item.diagnosa9,
            'tglinputdiagnosa': H.formatDate(item.tglpelayanan, 'YYYY-MM-DD HH:mm:ss'),
            'noregistrasi': item.registrasi.noregistrasi
        }
    }
    isLoading.value = true
    await useApi().post(
        `/diagnosa/save-diagnosa-ix`, json).then((response: any) => {
            isLoading.value = false
            modalInput9.value = false
            riwayatDiagnosa9()
        }).catch((e: any) => {
            isLoading.value = false
        })
}

async function simpanICD10() {
    if (!item.tglpelayanan) {
        useToaster().error('Tgl  harus di isi')
        return
    }
    if (!item.jenisDiagnosis10) {
        useToaster().error('Jenis Diagnosis harus di isi')
        return
    }
    if (!item.diagnosa10) {
        useToaster().error('Diagnosis harus di isi')
        return
    }

    let json = {
        'diagnosapasien': {
            'norec': item.NOREC_DIAGNOSA10 ? item.NOREC_DIAGNOSA10 : '',
            'noregistrasifk': item.NOREC_APD,
            'tglregistrasi': item.registrasi.tglregistrasi,
            'ketdiagnosis': item.keterangan10 ? item.keterangan10 : null,
            'iskasusbaru': item.isKasusBaru == 'baru' ? true : null,
            'iskasuslama': item.isKasusBaru == 'lama' ? true : null,
        },
        'detaildiagnosapasien': {
            'objectdiagnosafk': item.diagnosa10,
            'tglinputdiagnosa': H.formatDate(item.tglpelayanan, 'YYYY-MM-DD HH:mm:ss'),
            'objectjenisdiagnosafk': item.jenisDiagnosis10,
            'noregistrasi': item.registrasi.noregistrasi
        },
        'pasien' : {
        'nocm': props.pasien.nocm,
        'namapasien': props.pasien.namapasien,
        'noregistrasi': props.registrasi.noregistrasi,
        }

    }

    isLoading.value = true
    await useApi().post(
        `/diagnosa/save-diagnosa`, json).then((response: any) => {
            isLoading.value = false
            modalInput.value = false
            riwayatDiagnosa10()
        }).catch((e: any) => {
            isLoading.value = false
        })
}

async function riwayatDiagnosa10() {
    // dataSourceX.value = []
    dataSourceX.value.loading = true
    await useApi().get(
        `/diagnosa/riwayat-diagnosa-x?norec_pd=${item.registrasi.norec_pd}&nocmfk=${ID_PASIEN}`).then((response: any) => {
            dataSourceX.value.loading = false
            dataSourceX.value = response.data
        }).catch((e: any) => {
            dataSourceX.value.loading = false
        })

}
async function riwayatDiagnosa9() {
    // dataSourceIX.value = []
    dataSourceIX.value.loading = true
    await useApi().get(
        `/diagnosa/riwayat-diagnosa-ix?norec_pd=${item.registrasi.norec_pd}&nocmfk=${ID_PASIEN}`).then((response: any) => {
            dataSourceIX.value.loading = false
            dataSourceIX.value = response.data
        }).catch((e: any) => {
            dataSourceIX.value.loading = false
        })

}

console.log(props.pasien);

pasienByID(ID_PASIEN)
dropdownList()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/input-diagnosis';


</style>
