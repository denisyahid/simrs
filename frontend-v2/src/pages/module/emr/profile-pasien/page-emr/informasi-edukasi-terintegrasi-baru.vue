<template>
    <div class="form-layout is-stacked-2" style="
    width: 100%;
    max-width: none;">
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
            <VCard>
                <div class="columns is-multiline">

                    <div class="column is-12" style="overflow-x:auto;">
                        <table class="tc">
                            <thead>
                                <tr>
                                    <td class="tg-0lax text-center" rowspan="2">Tanggal/Jam</td>
                                    <td class="tg-0lax text-center" colspan="6">CATATAN EDUKASI</td>

                                    <td class="tg-0lax text-center" rowspan="2">#</td>
                                </tr>
                                <tr>
                                    <td class="tg-0lax">Edukasi yang diberikan </td>
                                    <td class="tg-0lax">Nama Pasien / Wali yang diedukasi </td>
                                    <td class="tg-0lax">Metode Edukasi</td>
                                    <td class="tg-0lax">Respon</td>
                                    <td class="tg-0lax">Durasi Pemberian Edukasi</td>
                                    <td class="tg-0lax text-center">Nama Edukator</td>
                                    <td class="tg-0lax text-center">Ruangan</td>
                                </tr>
                            </thead>
                            <tbody v-for="(item, index) in input.details" :key="index">
                                <tr>
                                    <td style="width:15%">
                                        <VDatePicker v-model="item.tgl" mode="datetime" trim-weeks >
                                            <template #default="{ inputValue, inputEvents }">
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" v-on="inputEvents" />
                                                </VControl>
                                            </template>
                                        </VDatePicker>
                                    </td>

                                    <td>
                                        <div class="column is-12">

                                            <div class="columns is-multiline pl-3">
                                                <div class="column is-12" v-for="(data, i) in edukasi">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data.model + i]"
                                                                :true-value="data.nama" :label="data.nama" class="p-0"
                                                                color="primary" square />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-10">
                                                    <VField>
                                                        <VControl>
                                                           <VTextarea v-model="input.ketLainnya" rows="5" placeholder="Keterangan Lainnya">
                                                </VTextarea>
                                                        </VControl>

                                                    </VField>
                                                </div>
                                            </div>

                                        </div>

                                    </td>
                                    <td>
                                        <div class="column is-10">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" class="input" placeholder="Nama Pasien"
                                                        v-model="input.namaPasien" />
                                                </VControl>

                                            </VField>
                                        </div>
                                        <div class="column is-10">
                                            <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                <VControl icon="fa:users" fullwidth class="prime-auto ">
                                                    <AutoComplete v-model="input.hubunganPenjamin" :suggestions="d_Hubungan"
                                                        @complete="fetchHubungan($event)" :optionLabel="'label'"
                                                        :dropdown="true" :minLength="3" :appendTo="'body'"
                                                        :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                        placeholder="ketik untuk mencari..." />
                                                </VControl>
                                            </VField>

                                        </div>
                                        <div class="column is-6">
                                            <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'">
                                            </TandaTangan>
                                        </div>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea v-model="item.A" rows="5" placeholder="Analysis...">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea v-model="item.P" rows="5" placeholder="Planning...">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VTextarea v-model="item.durasiPemberianEdukasi" rows="5" placeholder="Durasi Pemberian Edukasi...">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl class="prime-auto">

                                                <AutoComplete v-model="item.dokterRawatBersama" :suggestions="d_Dokter"
                                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                    :field="'label'" placeholder="Verifikasi DPJP..." class="mt-2" />
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td style="width:15%">
                                        <VField>
                                            <VControl class="prime-auto">
                                                <AutoComplete v-model="ruangan" :suggestions="d_Ruangan"
                                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true"
                                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                    :field="'label'" placeholder="pilih ruangan" class="mt-2" />
                                            </VControl>

                                        </VField>
                                    </td>
                                    <td rowspan="2" style="width:7%;vertical-align: middle;">
                                        <div class="columns is-multiline">
                                            <div class="column is-6">
                                                <VIconButton type="button" raised circle icon="feather:plus"
                                                    @click="addNewItem()" color="info" v-tooltip.bubble="'Tambah '">
                                                </VIconButton>
                                            </div>
                                            <div class="column is-6 ml-3-min">
                                                <VIconButton v-if="index > 0" type="button" raised circle
                                                    icon="feather:trash" @click="removeItem(index)" color="danger">
                                                </VIconButton>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </VCard>
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
import Dropdown from 'primevue/dropdown';
import Calendar from 'primevue/calendar';
import * as EMR from '../page-emr-plugins/informasi-edukasi-terintegrasi-baru'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let edukasi = ref(EMR.edukasi())

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

const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const selectView: any = ref()
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    details: [{
        no: 1,
        tgl: new Date(),
    }]
})
const d_Dokter: any = ref([])
let d_Ruangan: any = ref([])
const d_Hubungan: any = ref([])

const fetchHubungan = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/hubungankeluarga_m?select=id,hubungankeluarga&param_search=hubungankeluarga&query=${filter.query}&limit=10`)
    d_Hubungan.value = response
}
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}
const loadRiwayat = () => {

    // if (NOREC_EMRPASIEN.value == '') return
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

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.tandaTanganPasien = H.tandaTangan().get("signature_1")
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
const fetchDokter = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_Dokter.value = response
}

const fetchRuangan = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Ruangan.value = response
  })
}

const addNewItem = () => {
    // if (listItem.value[listItem.value.length - 1].tgl == null) {
    //     H.alert('error', 'Tanggal di pilih')
    //     return
    // }
    input.value.details.push({
        no: input.value.details[input.value.details.length - 1].no + 1,
        tgl: new Date(),
    });
}
const removeItem = (index: any) => {
    input.value.details.splice(index, 1)
}
function changeView(e: any) {
    selectView.value = e
}
setView()
loadRiwayat()
</script>

<style lang="scss">
#signature {
  border: double 3px transparent;
  border-radius: 5px;
  background-image: linear-gradient(white, white),
    radial-gradient(circle at top left, #4bc5e8, #9f6274);
  background-origin: border-box;
  background-clip: content-box, border-box;
}

.container {
  width: "100%";
  padding: 8px 16px;
}

.buttons {
  display: flex;
  gap: 8px;
  justify-content: center;
  margin-top: 8px;
}
.tc {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100% !important;
    overflow-x: scroll;
}

.tc td {
    border-color: var(--fade-grey-dark-2);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
    min-width: 250px;
}

.tc th {
    border-color: var(--fade-grey-dark-3);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: normal;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
    min-width: 250px;
}

.tc .tg-0lax {
    text-align: left;
    vertical-align: top
}
</style>