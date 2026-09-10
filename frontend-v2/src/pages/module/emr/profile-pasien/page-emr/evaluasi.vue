<template>
    <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3> {{ props.FORM_NAME }}</h3>
                    </div>
                    <div class="right">
                        <div class="buttons">
                            <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                Kembali
                            </VButton>
                            <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                :loading="isLoading" @click="simpan()"> Simpan
                            </VButton>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="columns is-multiline p-2">

        <div class="column is-12">
            <table class="tg">
                <thead>
                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <h1 class="emr pb-3" style="font-weight: bold;">Evaluasi Keperawatan</h1>
                        </div>
                        <div class="column is-6" style="text-align:end">
                            <VButton color="info" icon="feather:plus" elevated  @click="addNewItem()"> Tambah Evaluasi </VButton>
                        </div>
                    </div>

                </thead>
                <tbody v-for="(item, index) in input.details" :key="index">
                    <tr>
                        <td style="width:80%">
                            <div class="column is-12">
                                <div class="columns is-multiline">
                                    <div class="columns pt-3 pb-0">
                                        <div class="column is-12">
                                            <VField>
                                                <div class="field is-flex is-align-items-center">
                                                    <div class="mr-4" style="flex: 2;">
                                                        <label class="label">Tanggal Evaluasi</label>
                                                        <VDatePicker v-model="input.tglregistrasi" mode="dateTime" style="width: 100%;" trim-weeks :max-date="new Date()">
                                                            <template #default="{ inputValue, inputEvents }">
                                                                <VField>
                                                                    <VControl icon="feather:calendar" fullwidth>
                                                                        <VInput :value="inputValue" placeholder="Tanggal Masuk RS" v-on="inputEvents" />
                                                                    </VControl>
                                                                </VField>
                                                            </template>
                                                        </VDatePicker>
                                                    </div>
                                                    <div style="flex: 1;">
                                                        <label class="label">NOMOR DX:</label>
                                                        <VField>
                                                            <VControl>
                                                                <VTextarea v-model="nomor" rows="1"></VTextarea>
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                            </VField>
                                        </div>
                                    </div>

                                    <div class="column is-5">
                                        <h1 style="font-weight: bold; margin-bottom: 1rem;">Pegawai</h1>
                                        <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                            <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                                                <AutoComplete v-model="item.pegawai" :suggestions="d_pegawai"
                                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                    :field="'label'" placeholder="ketik untuk mencari..." />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-12">
                                <h1 style="font-weight: bold;"> S : </h1>
                                <VField>
                                    <VControl>
                                        <VTextarea v-model="item.S" rows="2">
                                        </VTextarea>
                                    </VControl>
                                </VField>
                                <h1 style="font-weight: bold;"> O : </h1>
                                <VField>
                                    <VControl>
                                        <VTextarea v-model="item.O" rows="2">
                                        </VTextarea>
                                    </VControl>
                                </VField>
                                <h1 style="font-weight: bold;"> A : </h1>
                                <VField>
                                    <VControl>
                                        <VTextarea v-model="item.A" rows="2" >
                                        </VTextarea>
                                    </VControl>
                                </VField>
                                <h1 style="font-weight: bold;"> P : </h1>
                                <VField>
                                    <VControl>
                                        <VTextarea v-model="item.P" rows="2" >
                                        </VTextarea>
                                    </VControl>
                                </VField>
                            </div>

                        </td>
                        <td rowspan="2" style="width:7%;vertical-align: middle;">
                            <div class="columns is-multiline">
                                <!-- <div class="column is-6">
                                    <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()"
                                        color="info" v-tooltip.bubble="'Tambah '">
                                    </VIconButton>
                                </div> -->
                                <div class="column is-6">
                                    <VIconButton v-if="index > 0" type="button" raised circle icon="feather:trash"
                                        @click="removeItem(index)" color="danger">
                                    </VIconButton>
                                </div>
                            </div>
                        </td>
                    </tr>


                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const props = withDefaults(
    defineProps<{
        pasien?: any
        registrasi?: any
        FORM_NAME?: string
        FORM_URL?: string
    }>(),
    {
        pasien: {},
        registrasi: {},
        FORM_NAME: '',
        FORM_URL: '',
    }
)
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref('Evaluasi') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const d_pegawai: any = ref([])
const input: any = ref({
    details: [{
        no: 1,
        tgl: new Date(),
    }]
})
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}
const loadRiwayat = () => {
    input.value.tglregistrasi = props.registrasi.tglregistrasi
    useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
            if (response.length) {
                input.value = response[0] //set ke inputan 
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                }
            }
        })
}
const fetchDokter = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_pegawai.value = response
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'url_form': props.FORM_URL,
        'name_form': props.FORM_NAME,
        'jenis_emr': 'asesmen_medis',
        'data': object
    }
    isLoading.value = true
    useApi().post(
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const kembaliKeun = () => {
    window.history.back()
}
const addNewItem = () => {
    // if (listItem.value[listItem.value.length - 1].tgl == null) {
    //     H.alert('error', 'Tanggal di pilih')
    //     return
    // }
    input.value.details.unshift({
        no: input.value.details[input.value.details.length - 1].no + 1,
        tgl: new Date(),
    });
}
const removeItem = (index: any) => {
    input.value.details.splice(index, 1)
}
setView()
loadRiwayat()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';


.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
}

.tg td {
    border-color: var(--fade-grey-dark-2);
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg th {
    border-color: var(--fade-grey-dark-3);

    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: normal;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg .tg-0lax {
    text-align: center;
    vertical-align: top
}
</style>