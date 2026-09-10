<template>
    <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3> {{ props.FORM_NAME }}</h3>
                    </div>
                    <div class="right">
                        <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                            @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="column is-12">
        <VCard>
            <div class="column is-6">
                <h1 style="font-weight: bold; margin-bottom: 1rem;"> Tanggal Tindakan</h1>
                <VField>
                    <VDatePicker v-model="input.tanggal" mode="dateTime" style="width: 100%" trim-weeks
                        :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }">
                            <VField>
                                <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                </VControl>
                            </VField>
                        </template>
                    </VDatePicker>

                </VField>

            </div>
            <div class="column is-12">
                <Fieldset :toggleable="true" legend="SIGN IN">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Sebelum Masuk Ruangan Tindakan Cathlab</h1>
                    <h1 style="font-weight: bold;">Konfirmasi / Verifikasi</h1>
                    <div class="column is-12" v-for="(datas) in signIn">
                        <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                        <div class="columns is-multiline">
                            <div class="column is-2 pt-0 pl-6" v-for="(data) in datas.value">
                                <VField>
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                            :label="data.subTitle" class="p-0" color="primary" square />
                                    </VControl>
                                </VField>

                            </div>
                        </div>

                    </div>
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-2">
                                <h1 style="font-weight: bold; margin-top: 1rem;"> SIGN IN Pukul:</h1>
                            </div>
                            <div class="column is-5">
                                <VDatePicker v-model="input.jam" color="green" mode="time" is24hr>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VControl>
                                                <VInput class="input form-timepicker" :value="inputValue"
                                                    v-on="inputEvents" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6 ">
                        <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan>
                        <div class="column is-12">
                            <h1 class="p-0" style="font-weight: bold;">Perawat Pre & Post</h1>
                            <VField>
                                <VControl class="prime-auto">
                                    <AutoComplete v-model="input.perawat" :suggestions="d_pegawai"
                                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="Perawat..." class="mt-2" @item-select="setTandaTangan($event)" />
                                </VControl>
                            </VField>
                        </div>

                    </div>


                </Fieldset>
            </div>
            <div class="column is-12">
                <Fieldset :toggleable="true" legend="TIME OUT">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Sebelum Puncture Vascular atau Isisi</h1>
                    <h1 style="font-weight: bold;">Konfirmasi</h1>
                    <div class="column is-12" v-for="(datas) in timeOut">
                        <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                        <div class="columns is-multiline">
                            <div class="column is-2 pt-0 pl-6" v-for="(data) in datas.value">
                                <VField>
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                            :label="data.subTitle" class="p-0" color="primary" square />
                                    </VControl>
                                </VField>

                            </div>
                        </div>

                    </div>
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-2">
                                <h1 style="font-weight: bold; margin-top: 1rem;"> TIME OUT Pukul::</h1>
                            </div>
                            <div class="column is-5">
                                <VDatePicker v-model="input.jamTimeOut" color="green" mode="time" is24hr>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VControl>
                                                <VInput class="input form-timepicker" :value="inputValue"
                                                    v-on="inputEvents" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </div>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                    <div class="column is-6">
                        <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan>
                        <div class="column is-6">
                            <h1 class="p-0" style="font-weight: bold;">Perawat Monitor</h1>
                            <VField>
                                <VControl class="prime-auto">
                                    <AutoComplete v-model="input.perawatMonitor" :suggestions="d_pegawai"
                                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="Perawat..." class="mt-2" @item-select="setTandaTangan($event)" />
                                </VControl>
                            </VField>
                        </div>

                    </div>
                    <div class="column is-6">
                        <TandaTangan :elemenID="'signature_3'" :width="'180'" :height="'180'"></TandaTangan>
                        <div class="column is-6">
                            <h1 class="p-0" style="font-weight: bold;">Dokter</h1>
                            <VField>
                                <VControl class="prime-auto">
                                    <AutoComplete v-model="input.dokter" :suggestions="d_pegawai"
                                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="Dokter..." class="mt-2" @item-select="setTandaTangan($event)" />
                                </VControl>
                            </VField>
                        </div>

                    </div>
                </div>


                </Fieldset>
            </div>
            <div class="column is-12">
                <Fieldset :toggleable="true" legend="SIGN OUT">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Sebelum Meninggalkan Ruang Tindakan</h1>
                    <h1 style="font-weight: bold;">Konfirmasi</h1>
                    <div class="column is-12" v-for="(datas) in signOut">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                    <div class="columns is-multiline">
                        <div class="column is-2" v-for="(data) in datas.value">
                            <VField v-if="data.type == 'checkBox'">
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                        :label="data.subTitle" class="p-0" color="primary" square />
                                </VControl>
                            </VField>
                            <VField :label="data.subTitle" v-else-if="data.type == 'textBox'" style="margin-top: -1rem;">
                                <VControl raw subcontrol>
                                    <input v-model="input[data.model]" class="input p-0" />
                                </VControl>
                            </VField>
                        </div>
                    </div>

                </div>   
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-2">
                                <h1 style="font-weight: bold; margin-top: 1rem;"> SIGN OUT Pukul:</h1>
                            </div>
                            <div class="column is-5">
                                <VDatePicker v-model="input.jamSO" color="green" mode="time" is24hr>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VControl>
                                                <VInput class="input form-timepicker" :value="inputValue"
                                                    v-on="inputEvents" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6 ">
                        <TandaTangan :elemenID="'signature_4'" :width="'180'" :height="'180'"></TandaTangan>
                        <div class="column is-12">
                            <h1 class="p-0" style="font-weight: bold;">Perawat Monitor</h1>
                            <VField>
                                <VControl class="prime-auto">
                                    <AutoComplete v-model="input.perawatMon" :suggestions="d_pegawai"
                                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="Perawat..." class="mt-2" @item-select="setTandaTangan($event)" />
                                </VControl>
                            </VField>
                        </div>

                    </div>


                </Fieldset>
            </div>
        </VCard>
    </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import Fieldset from 'primevue/fieldset';
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import * as EMR from '../page-emr-plugins/cathlab-safety-checklist'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let signIn = ref(EMR.signIn())
let timeOut = ref(EMR.timeOut())
let signOut = ref(EMR.signOut())

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

const route = useRoute()
const { y } = useWindowScroll()
const d_pegawai: any = ref([])
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
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
    tanggal: new Date(),
    jam: new Date(),
    jamTimeOut: new Date(),
    jamSO: new Date()
})
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}
const loadRiwayat = async () => {
   
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
            if (response.length) {
                input.value = response[0] //set ke inputan
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                }
                if (response[0].tandaTanganPerawat) {
                    H.tandaTangan().set("signature_1", response[0].tandaTanganPerawat)
                }
                if (response[0].tandaTanganPerawat2) {
                    H.tandaTangan().set("signature_2", response[0].tandaTanganPerawat2)
                }
                if (response[0].tandaTanganDokter) {
                    H.tandaTangan().set("signature_3", response[0].tandaTanganDokter)
                }
                if (response[0].tandaTanganPerawat3) {
                    H.tandaTangan().set("signature_4", response[0].tandaTanganPerawat3)
                }
            }
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
    object.tandaTanganPerawat = H.tandaTangan().get("signature_1")
    object.tandaTanganPerawat2 = H.tandaTangan().get("signature_2")
    object.tandaTanganDokter = H.tandaTangan().get("signature_3")
    object.tandaTanganPerawatMonitor = H.tandaTangan().get("signature_4")
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
const setAutoFill = async () => {

}
const setTandaTangan = async(e:any) => {
  const response = await useApi().get(
        `/emr/tanda-tangan/${e.value.value}`)
  if (response!= null) {
    H.tandaTangan().set("signature_1", response.ttd)
    input.value.tandaTanganPerawat = response.ttd
  }else{
    H.tandaTangan().set("signature_1", '')
  }
  if (response!= null) {
    H.tandaTangan().set("signature_2", response.ttd)
    input.value.tandaTanganPerawat2 = response.ttd
  }else{
    H.tandaTangan().set("signature_2", '')
  }
  if (response!= null) {
    H.tandaTangan().set("signature_3", response.ttd)
    input.value.tandaTanganDokter = response.ttd
  }else{
    H.tandaTangan().set("signature_3", '')
  }
  if (response!= null) {
    H.tandaTangan().set("signature_4", response.ttd)
    input.value.tandaTanganPerawat4 = response.ttd
  }else{
    H.tandaTangan().set("signature_4", '')
  }
}

setView()
setAutoFill()


onBeforeMount(async () => {
    try {
        await loadRiwayat()
        let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
        if (cache) input.value = cache
    } catch (error) {
        console.error('Error mount cache TAB EMR:', error);
    }
});

onBeforeRouteLeave((to, from, next) => {
    try {
        let rouutename = from?.name
        H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
    } catch (error) {
        console.error('Error leave cache TAB EMR:', error);
    }
    next();
});
</script>
