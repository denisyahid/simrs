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

    <div class="column is-12">
        <Fieldset :toggleable="true" legend="Identifikasi Pasien">
            <div class="columns is-multiline pt-3 pl-3 pr-3">
                <div class="column is-3">
                    <span class="label-sr">No Rujukan</span>
                    <VField class="pt-3">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.noRujukan" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <span class="label-sr">No RM</span>
                    <VField class="pt-3">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.norm" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-6">
                    <span class="label-sr">Nama Pasien</span>
                    <VField class="pt-3">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.namaPasien" />
                        </VControl>
                    </VField>
                </div>

                <div class="column is-6">
                    <span class="label-sr">NIK</span>
                    <VField class="pt-3">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.NIK" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-6">
                    <span class="label-sr">No. JKN</span>
                    <VField class="pt-3">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.jkn" />
                        </VControl>
                    </VField>
                </div>

                <div class="column is-6 pb-0">
                    <span class="label-sr">Jenis Kelamin</span>
                    <div class="columns is-multiline p-3">
                        <div class="column is-4">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.jnsKelamin" true-value="Laki-laki" class="p-0"
                                        label="Laki-laki" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.jnsKelamin" true-value="Perempuan" class="p-0"
                                        label="Perempuan" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>

                <div class="column is-6 pb-0">
                    <span class="label-sr">Tempat Lahir</span>
                    <VField class="pt-3">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.tempatLhr" />
                        </VControl>
                    </VField>
                </div>

            </div>

            <div class="column is-12 pt-0">
                <span class="label-sr">Alamat</span>
                <VField class="pt-3">
                    <VControl>
                        <VTextarea v-model="input.alamat" rows="2" placeholder="Alamat...">
                        </VTextarea>
                    </VControl>
                </VField>
            </div>
        </Fieldset>
    </div>

    <div class="column is-12">
        <Fieldset :toggleable="true" legend="Rujukan Pasien">
            <div class="columns is-multiline pl-3 pr-3 pt-3">
                <div class="column is-6">
                    <span class="label-sr">Jenis Rujukan</span>
                    <div class="columns is-multiline p-3">
                        <div class="column is-4" v-for="(jenis) in jnsRujukan">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.jnsRujukan" :true-value="jenis" class="p-0" :label="jenis"
                                        color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>

                <div class="column is-4">
                    <span class="label-pst">Tanggal Rujukan</span>
                    <VDatePicker class="pt-3" v-model="input.tglRujukan" color="green" trim-weeks mode="datetime"
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
            </div>

            <div class="columns is-multiline pl-3 pr-3 pt-3 pt-0">
                <div class="column is-6 pt-0">
                    <span class="label-sr">Faskes Tujuan</span>
                    <VField class="pt-3">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.faskesTujuan" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-6 pt-0">
                    <span class="label-sr">Alasan Rujukan</span>
                    <VField class="pt-3">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.alasanRujukan" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <span class="label-sr">Alasan Tambahan</span>
                    <VField class="mt-3 column is-10 p-0">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.alasanTambahan" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <span class="label-sr">Diagnosa</span>
                    <VField>
                        <VControl class="prime-auto">
                            <AutoComplete v-model="item.dokterPemeriksa" :suggestions="d_Diagnosa"
                                @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                placeholder="Cari Diagnosa ..." class="mt-3" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <span class="label-sr">Dokter</span>
                    <VField>
                        <VControl class="prime-auto">
                            <AutoComplete v-model="input.dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Dokter Pemeriksa..."
                                class="mt-3" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <span class="label-sr">Petugas Perujuk</span>
                    <VField>
                        <VControl class="prime-auto">
                            <AutoComplete v-model="input.petugasPerujuk" :suggestions="d_Pegawai"
                                @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                placeholder="Cari Petugas Perujuk..." class="mt-3" />
                        </VControl>
                    </VField>
                </div>
            </div>
        </Fieldset>
    </div>

    <div class="column is-12">
        <Fieldset :toggleable="true" legend="Kondisi Umum">
            <div class="columns is-multiline pt-3 pl-3 pr-3">
                <div class="column is-4" v-for="(data) in keadaanUmum">
                    <span class="label-sr">{{ data.title }}</span>
                    <VField class="pt-3">
                        <VControl>
                            <VTextarea v-model="input[data.model]" rows="2">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>
            </div>

            <div class="columns is-multiline pt-3 pl-3 pr-3">
                <div class="column is-4" v-for="(data) in kesadaran">
                    <span class="label-sr">{{ data.title }}</span>
                    <div class="columns is-multiline p-3">
                        <div class="column" v-for="(pilihan) in data.detail ">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[pilihan.model]" :true-value="pilihan.label" class="p-0"
                                        :label="pilihan.label" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column">
                    <VField class="mt-5">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.ketKesadaran" />
                        </VControl>
                    </VField>
                </div>
            </div>

            <div class="columns is-multiline pt-3 pl-3 pr-3" style="justify-content: space-between;">
                <div class="column is-2" v-for="(data) in keadaanUmum2">
                    <span class="label-sr">{{ data.title }}</span>
                    <VField class="mt-3">
                        <VControl>
                            <VInput type="text" class="input" v-model="input[data.model]" />
                        </VControl>
                    </VField>
                </div>
            </div>

            <div class="column is-12">
                <span class="label-sr">Nyeri</span>
                <div class="columns is-multiline pt-3 pl-4 pr-4">
                    <div class="column is-3" v-for="(data) in nyeri">
                        <VField>
                            <VControl raw subcontrol>
                                <VCheckbox v-model="input[data.model]" :true-value="data.label" class="p-0"
                                    :label="data.label" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                </div>

                <div class="column pt-0">
                    <VControl>
                        <VTextarea v-model="input.ketNyeri" rows="2">
                        </VTextarea>
                    </VControl>
                </div>
            </div>
        </Fieldset>
    </div>

    <div class="column is-12">
        <Fieldset :toggleable="true" legend="Penunjang">
            <div class="columns is-multiline pt-3 pl-3 pr-3">
                <div class="column is-4" v-for="(data) in penunjang">
                    <span class="label-sr">{{ data.label }}</span>
                    <VField class="pt-3">
                        <VControl>
                            <VTextarea v-model="input[data.model]" rows="2">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>
            </div>
        </Fieldset>
    </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import * as EMR from '../page-emr-plugins/surat-rujukan'
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import Fieldset from 'primevue/fieldset';

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let jnsRujukan = ref(EMR.JSRujukan())
let keadaanUmum = ref(EMR.keadaanUmum_1())
let keadaanUmum2 = ref(EMR.keadaanUmum_2())
let kesadaran = ref(EMR.kesadaran())
let nyeri = ref(EMR.nyeri())
let penunjang = ref(EMR.penunjang())

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
const d_Diagnosa: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])


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
    tglRujukan: new Date()
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
    // object['Gambar'] = H.tandaTangan().get("Gambar");
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
const setAutoFill = async () => {
  input.value.norm = props.pasien.nocm
  input.value.namaPasien = props.pasien.namapasien
  input.value.nik = props.pasien.nik
  input.value.jnsKelamin = props.pasien.jeniskelamin
  input.value.alamat = props.pasien.alamatlengkap

  await useApi().get(
        "emr/auto-fill?nocmfk=" + ID_PASIEN +
        "&collection=VitalSign" +
        "&field=tekananDarah,pernapasan,suhu,nadi,SPO2"
    ).then((response) => {
        input.value.tekananDarah = response.tekananDarah
        input.value.pernapasan = response.pernapasan
        input.value.frekuensiNadi = response.nadi
        input.value.suhu = response.suhu
        input.value.saturasiOksigen = response.SPO2
    })
}

const fetchPetugas = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_Pegawai.value = response
}

const fetchDiagnosa = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/diagnosa_m?select=kddiagnosa,namadiagnosa&param_search=kddiagnosa&query=${filter.query}&limit=10`)
    d_Diagnosa.value = response
}

const fetchDokter = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
        d_Dokter.value = response
    })
}


setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
.p-fieldset-legend {
    margin-left: 15px;
}

.label-sr {
    font-weight: 500;
}
</style>

