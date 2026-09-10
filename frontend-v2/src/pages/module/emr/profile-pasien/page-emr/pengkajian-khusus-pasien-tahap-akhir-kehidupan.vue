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
                                color="warning" raised icon="lnir lnir-printer"> Cetak
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
                <div class="column is-6">
                    <h1>Informasi diperoleh dari</h1>
                    <VField class="mt-3">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.diperolehInformasi" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <TabView :scrollable="true" class="skuy">
                        <TabPanel header="A. ASESMEN MEDIK">
                            <VCard class="border-card pink">
                                <h1>A. ASESMEN MEDIK</h1>
                                <div class="column is-4">
                                    <span style="font-weight: 500;">Tanggal Pengkajian</span>
                                    <VDatePicker class="pt-3 pb-0 pl-0" v-model="input.tanggalMedik" color="green"
                                        trim-weeks mode="dateTime" :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }" class="pb-0">
                                            <VField>
                                                <VControl icon="feather:calendar">
                                                    <VInput type="text" placeholder="Select a date" :value="inputValue"
                                                        v-on="inputEvents" class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </template>
                                    </VDatePicker>
                                </div>
                                <div class="column is-12" v-for="(datas) in dataPengkajian">
                                    <h1>{{ datas.title }}</h1>
                                    <div class="column is-12" v-for="(data) in datas.detail">
                                        <span style="font-weight: 500;">{{ data.subTitle }}</span>
                                        <div class="columns is-multiline p-3">
                                            <div class="column is-4 pb-0 " v-for="(pilihan, i) in data.value">
                                                <VField>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox v-model="input[pilihan.model]" class="p-0"
                                                            :true-value="pilihan.value" :label="pilihan.label"
                                                            color="primary" circle />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                        <div class="column is-6 pt-0 pb-0" v-if="data.optional.model">
                                            <VField class="mt-2">
                                                <VControl>
                                                    <VInput type="text" class="input"
                                                        v-model="input[data.optional.model]" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-12 pl-3">
                                    <h1>3. Diagnosa / Assesment</h1>
                                    <div class="column is-8">
                                        <VField>
                                            <VControl>
                                                <VTextarea v-model="input.diagnosa" rows="3"
                                                    placeholder="Diagnosa / Assesment ...">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>

                                <div class="column is-12 pl-4">
                                    <h1>3. Perencanaan</h1>
                                    <div class="column is-8">
                                        <VField>

                                            <VControl>
                                                <VTextarea v-model="input.perencanaan" rows="3"
                                                    placeholder="Perencanaan ...">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </VCard>
                        </TabPanel>
                        <TabPanel header="B. ASESMEN KEPERAWATAN">
                            <VCard class="border-card pink">
                                <h1>B. ASESMEN KEPERAWATAN</h1>
                                <div class="column is-4">
                                    <span style="font-weight: 500;">Tanggal Pengkajian</span>
                                    <VDatePicker class="pt-3 pb-0 pl-0" v-model="input.tanggalKeperawatan" color="green"
                                        trim-weeks mode="dateTime" :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }" class="pb-0">
                                            <VField>
                                                <VControl icon="feather:calendar">
                                                    <VInput type="text" placeholder="Select a date" :value="inputValue"
                                                        v-on="inputEvents" class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </template>
                                    </VDatePicker>
                                </div>

                                <div class="column is-12" v-for="(datas) in dataPengkajianKeper">
                                    <h1>{{ datas.title }}</h1>
                                    <div class="column is-12" v-for="(data) in datas.detail">
                                        <span style="font-weight: 500;">{{ data.subTitle }}</span>
                                        <div class="columns is-multiline" :class="data.subTitle ? ' mt-2' : ''">
                                            <div class="column" v-for="(pilihan,i) in data.value" :class="pilihan.label == 'Ya, Oleh' || pilihan.label == 'Ya' ||
                                                pilihan.label == 'Donasi organ'  ? 'is-2 pr-0' : 'is-4'">
                                                <VField>
                                                    <VControl raw subcontrol v-if="pilihan.type == 'checkBox'">
                                                        <VCheckbox v-model="input[pilihan.model+i]" class="p-0"
                                                            :true-value="pilihan.value" :label="pilihan.label"
                                                            color="primary" circle />
                                                    </VControl>
                                                    <VControl v-else-if="pilihan.type == 'textBox'">
                                                        <VInput type="text" class="input" v-model="input[pilihan.model]" />
                                                    </VControl>
                                                    <VControl v-else>
                                                        <VTextarea v-model="input[pilihan.model]" rows="2"
                                                            placeholder="Pesan Terakhir ...">
                                                        </VTextarea>
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </VCard>
                        </TabPanel>
                    </TabView>
                </div>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <div class="column is-6 pt-0">
                                <TandaTangan :elemenID="'signatureDokter'" :width="'180'" :height="'180'"
                                    style="margin-left: 21px;" />
                            </div>
                            <div class="column is-8">
                                <h1 class="mb-3">Dokter</h1>
                                <VField>
                                    <VControl class="prime-auto">
                                        <AutoComplete v-model="input.dokter" :suggestions="d_Dokter"
                                            @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Dokter..." class="mt-2"
                                            @item-select="setTandaTanganDokter($event)" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                        <div class="column is-6">
                            <div class="column is-6 pt-0">
                                <TandaTangan :elemenID="'signaturePerawat'" :width="'180'" :height="'180'"
                                    style="margin-left: 21px;" />
                            </div>
                            <div class="column is-8">
                            <h1 class="mb-3">Perawat</h1>
                                <VField>
                                    <VControl class="prime-auto">
                                        <AutoComplete v-model="input.perawat" :suggestions="d_Perawat"
                                            @complete="fetchPerawat($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Perawat..." class="mt-2"
                                            @item-select="setTandaTanganPerawat($event)" />
                                    </VControl>
                                </VField>
                        </div>
                        </div>
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
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import AutoComplete from 'primevue/autocomplete';
import { useUserSession } from '/@src/stores/userSession'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import * as EMR from '../page-emr-plugins/pengkajian-khusus-pasien-tahap-akhir-kehidupan'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let dataPengkajian = ref(EMR.asesmenMedik())
let dataPengkajianKeper = ref(EMR.asesmenKeperawatan())
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
const d_Dokter: any = ref([])
const d_Perawat: any = ref([])
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref('AsesmenKhususPasienTahapAkhirKehidupan') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    tanggalMedik: new Date(),
    tanggalKeperawatan: new Date(),
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
                    H.tandaTangan().set("signaturePerawat", response[0].tandaTanganPerawat)
                }
                if (response[0].tandaTanganDokter) {
                    H.tandaTangan().set("signatureDokter", response[0].tandaTanganPerawat)
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
    object.tandaTanganPerawat = H.tandaTangan().get("signaturePerawat")
    object.tandaTanganDokter = H.tandaTangan().get("signatureDokter")
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
        d_Perawat.value = response
    })
}

const fetchDokter = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
        d_Dokter.value = response
    })
}

const setTandaTanganDokter = async (e: any) => {
    await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element)=>{
      if(element){
        H.tandaTangan().set("signatureDokter", element.ttd)
      }else{
        H.tandaTangan().set("signatureDokter", '')
      }
    })
}
const setTandaTanganPerawat = async (e: any) => {
    await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element)=>{
      if(element){
        H.tandaTangan().set("signaturePerawat", element.ttd)
      }else{
        H.tandaTangan().set("signaturePerawat", '')
      }
    })
}


setView()
loadRiwayat()
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

h1 {
    font-weight: bold;
}

.p-tabview .p-tabview-panels {
    padding-left: 0px;
    padding-right: 0px;
}
</style>
