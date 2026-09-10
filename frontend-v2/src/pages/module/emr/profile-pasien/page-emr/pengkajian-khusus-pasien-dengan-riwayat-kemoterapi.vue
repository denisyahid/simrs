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
                            <VButton type="button" rounded outlined :disabled="!NOREC_EMRPASIEN ? true : false"
                                color="warning" raised icon="lnir lnir-printer" > Cetak
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
                <div class="column is-12" v-for="(datas) in dataPengkajian">
                    <h1>{{ datas.title }}</h1>
                    <div class="columns is-multiline">
                        <div class="column pb-0" v-for="(data) in datas.value"
                            :class="data == 'Ya (dalam minggu/bulan/tahun)' ? 'is-4' : 'is-3'">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[datas.model]" class="pb-0" :true-value="data" :label="data"
                                        color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                    <div class="column is-8 pb-0 pt-0" v-if="datas.optional.model">
                        <VField class="mt-2">
                            <VControl>
                                <VInput type="text" class="input" v-model="input[datas.optional.model]" />
                            </VControl>
                        </VField>
                    </div>
                </div>

                <div class="column is-10">
                    <h1 class="mb-3">Diagnosa Perawat</h1>
                    <VField class="pl-3 pr-3">
                        <VControl>
                            <VTextarea v-model="input.diagnosaPerawat" rows="3" placeholder="Diagnosa Perawat ...">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>

                <div class="column is-3 pb-0">
                    <h1>Tanggal dan Jam</h1>
                    <VDatePicker class="p-3 pb-0" v-model="input.tanggal" color="green" trim-weeks mode="dateTime"
                        :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }" class="pb-0">
                            <VField>
                                <VControl icon="feather:calendar">
                                    <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                                        class="is-rounded_Z" />
                                </VControl>
                            </VField>
                        </template>
                    </VDatePicker>
                </div>
                <div class="column is-3 pt-0">
                    <TandaTangan :elemenID="'signature'" :width="'180'" :height="'180'" style="margin-left: 21px;" />
                </div>
                <div class="column is-4">
                    <h1 class="mb-3">Perawat</h1>
                    <VField>
                        <VControl class="prime-auto">
                            <AutoComplete v-model="input.perawat" :suggestions="d_perawat" @complete="fetchPerawat($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Perawat..." class="mt-2"
                                @item-select="setTandaTangan($event)" />
                        </VControl>
                    </VField>
                </div>
            </VCard>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { reactive, ref, computed,} from 'vue'
import { useRoute,} from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete';
import { useUserSession } from '/@src/stores/userSession'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import * as EMR from '../page-emr-plugins/pengkajian-khusus-pasien-dengan-riwayat-kemoterapi'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let dataPengkajian = ref(EMR.pengkajian())
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
const d_perawat: any = ref([])
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref('PengkajianKhususPasienDenganRiwayatKemoterapi') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    tanggal: new Date()
})
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
            if (response.length > 0) {
                input.value = response[0] //set ke inputan
                if (response[0].tandaTanganPerawat) {
                    H.tandaTangan().set("signature", response[0].tandaTanganPerawat)
                }

                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                } else { }
            }
        })
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    object.tandaTanganPerawat = H.tandaTangan().get("signature")
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


const fetchPerawat = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_perawat.value = response
    })
}

const setTandaTangan = async (e: any) => {
    await useApi().get(`/emr/tanda-tangan/${e.value.value}`).then((response: any) => {
        if (response != null) {
            H.tandaTangan().set("signature", response.ttd)
            input.value.tandaTanganPerawat = response.ttd
        } else {
            H.tandaTangan().set("signature", '')
        }
    })

}

setView()
loadRiwayat()
</script>

<style lang="scss">
h1 {
    font-weight: bold;
}
</style>