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
        <VCard>
            <h1 class="title-pst">Yang bertanda tangan dibawah ini :</h1>
            <div class="columns is-multiline p-3">
                <div class="column is-4">
                    <span class="label-pst">Nama</span>
                    <VField class="mt-3">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.nama" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <span class="label-pst">Jenis Kelamin</span>
                    <div class="columns is-multiline pt-4">
                        <div class="column">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.jnsKelamin" true-value="Laki-laki" class="p-0"
                                        label="Laki-laki" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.jnsKelamin" true-value="Perempuan" class="p-0"
                                        label="Perempuan" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-3">
                    <span class="label-pst">Tanggal Lahir</span>
                    <VDatePicker class="pt-3" v-model="input.tglLahir" color="green" trim-weeks mode="date"
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

            <div class="columns is-multiline p-3">
                <div class="column is-5">
                    <span class="label-pst">Alamat Lengkap</span>
                    <VField class="pt-3">
                        <VControl>
                            <VTextarea v-model="input.alamatLengkap" rows="2" placeholder="Alamat Lengkap ...">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <span class="label-pst">No. Telepone/HP</span>
                    <VField class="pt-3">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.noHp" />
                        </VControl>
                    </VField>
                </div>

                <div class="column is-4">
                    <span class="label-pst">Dalama hal ini bertindak atas nama atau untuk mewakili :</span>
                    <VField>
                        <VControl class="prime-auto">
                            <AutoComplete v-model="item.wali" :suggestions="d_Wali" @complete="fetchWali($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari ..." class="mt-2" />
                        </VControl>
                    </VField>
                </div>
            </div>

            <div class="column is-12">
                <h1 class="title-pst">Dengan ini menyatakan dengan sebenarnya bahwa pada :</h1>
                <div class="columns is-multiline p-3">
                    <div class="column is-4">
                        <span class="label-pst">Tanggal</span>
                        <VDatePicker class="column is-7" v-model="input.tglPernyataan" color="green" trim-weeks mode="date"
                            :max-date="new Date()">
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

                    <div class="column is-4">
                        <span class="label-pst">Tempat</span>
                        <VField class="mt-3">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.tempat" />
                            </VControl>
                        </VField>
                    </div>
                </div>

            </div>

            <div class="column is-12">
                <span class="label-pst">Telah Menerima Jenazah</span>
                <div class="columns is-multiline p-3">
                    <div class="column is-2" v-for="(data) in jenazah">
                        <VField>
                            <VControl raw subcontrol>
                                <VCheckbox v-model="input.terimaJenazah" :true-value="data" class="p-0" :label="data"
                                    color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="column is-5 pt-0">
                    <VField>
                        <VControl>
                            <VInput type="text" class="input" v-model="input.ketJenazah" />
                        </VControl>
                    </VField>
                </div>
            </div>

            <div class="columns is-multiline pb-0 pt-3 pl-5 pr-5">
                <div class="column" v-for="(data) in pengenalJenazah"
                    :class="data.label == 'Saya' || data.label == 'Nama Pasien' ? 'is-4' : 'is-3'">
                    <span class="label-pst">{{ data.label }}</span>
                    <VField class="pt-3" v-if="data.type == 'textBox'">
                        <VControl>
                            <VInput type="text" class="input" v-model="input[data.model]" />
                        </VControl>
                    </VField>
                    <VDatePicker v-else class="pt-3" v-model="input[data.model]" color="green" trim-weeks mode="date"
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

            <div class="column is-12 pt-0 pl-5 pr-5">
                <span class="label-pst">Alamat</span>
                <VField class="pt-3">
                    <VControl>
                        <VTextarea v-model="input.alamat" rows="2" placeholder="Alamat ...">
                        </VTextarea>
                    </VControl>
                </VField>
            </div>

            <div class="column is-12">
                <h1 class="title-pst">MENINGGAL</h1>
                <div class="columns is-multiline p-3">
                    <div class="column is-5">
                        <span class="label-pst">Tempat / Ruang</span>
                        <VField class="mt-3 column is-10 p-0">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.tempatMeninggal" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <span class="label-pst">Waktu Meninggal</span>
                        <VDatePicker class="pt-3" v-model="input.waktuMeninggal" color="green" trim-weeks mode="datetime"
                            :max-date="new Date()">
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
                </div>

                <div class="column is-12">
                    <span class="label-pst">Dari saudara/saudari :</span>
                    <div class="columns is-multiline p-3">
                        <div class="column is-6">
                            <span class="label-pst">Nama</span>
                            <VField class="mt-3 column is-10 p-0">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.namaSaudara" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <span class="label-pst">Jenis Kelamin</span>
                            <div class="columns is-multiline pt-3">
                                <div class="column is-3">
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input.jnsKlmnSaudara" true-value="laki-laki" class="p-0"
                                                label="Laki-laki" color="primary" circle />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input.jnsKlmnSaudara" true-value="laki-laki" class="p-0"
                                                label="Perempuan" color="primary" circle />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="columns is-multiline pl-3 pr-3">
                        <div class="column is-4">
                            <span class="label-pst">Pekerjaan / Bagian</span>
                            <VField class="pt-3">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.pekerjaan" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <span class="label-pst">Alamat</span>
                            <VField class="pt-3">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.alamatSaudara" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
            </div>

            <div class="column is-12">
                <h1 class="title-pst">Demikian pernyataan ini dibuat dengan rasa tanggung jawab untuk dapat dipergunakan
                    sebagaimana mestinya.</h1>
                <div clas="column is-12" style="padding:10px">
                    <span class="label-pst">Bandung</span>
                    <VDatePicker class="pt-3 column is-3" v-model="input.waktuDibuat" color="green" trim-weeks
                        mode="datetime" :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }" class="pb-0">
                            <VField>
                                <VControl icon="feather:calendar">
                                    <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                                        class="is-rounded_Z" />
                                </VControl>
                            </VField>
                        </template>
                    </VDatePicker>

                    <div class="columns is-multiline" style="justify-content: space-around;">
                        <div class="column is-4">
                            <TandaTangan :elemenID="'signaturPegawai'" :width="'180'" :height="'180'" class="dek" />
                            <div class="column pl-0 pr-0">
                                <span class="label-pst">Yang Menyerahkan</span>
                                <VField>
                                    <VControl class="prime-auto">
                                        <AutoComplete v-model="input.pegwaiPenyerah" :suggestions="d_Pegawai"  @item-select="setTandaTanganPegawai($event)"
                                            @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Cari Pegawai..." class="mt-2" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                        <div class="column is-4">
                            <TandaTangan :elemenID="'signaturePenerima'" :width="'180'" :height="'180'" class="dek" />
                            <div class="column pl-0 pr-0">
                                <span class="label-pst">Yang Menerima</span>
                                <VField class="pt-3">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.penerimaJenazah" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>

                    <div class="columns is-multiline" style="justify-content: space-around;">
                        <div class="column is-4">
                            <TandaTangan :elemenID="'signatureSaksiI'" :width="'180'" :height="'180'" class="dek" />
                            <div class="column pl-0 pr-0">
                                <span class="label-pst">Saksi I</span>
                                <VField class="pt-3">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.saksiI" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                        <div class="column is-4">
                            <TandaTangan :elemenID="'signatureSaksiII'" :width="'180'" :height="'180'" class="dek" />
                            <div class="column pl-0 pr-0">
                                <span class="label-pst">Saksi II</span>
                                <VField class="pt-3">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.saksiII" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </VCard>
    </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import * as EMR from '../page-emr-plugins/pernyataan-serah-terima-jenazah'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let jenazah = ref(EMR.jenazah())
let pengenalJenazah = ref(EMR.pengenalJenazah())


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
const d_Wali: any = ref([])
const d_Pegawai: any = ref([])
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref('PernyataanSerahTerimaJenasah') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    waktuDibuat: new Date()
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

                if (response[0].ttdPegawai) {
                    H.tandaTangan().set("signaturPegawai", response[0].ttdPegawai)
                }
                if (response[0].ttdPenerima) {
                    H.tandaTangan().set("signaturePenerima", response[0].ttdPenerima)
                }
                if (response[0].ttdSaksiI) {
                    H.tandaTangan().set("signatureSaksiI", response[0].ttdSaksiI)
                }
                if (response[0].ttdSaksiII) {
                    H.tandaTangan().set("signatureSaksiII", response[0].ttdSaksiII)
                }

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
    object.ttdPegawai = H.tandaTangan().get("signaturPegawai")
    object.ttdPenerima = H.tandaTangan().get("signaturePenerima")
    object.ttdSaksiI = H.tandaTangan().get("signatureSaksiI")
    object.ttdSaksiII = H.tandaTangan().get("signatureSaksiII")
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
    input.value.namaPasien = props.pasien.namapasien
    input.value.jenisKlmPasien = props.pasien.jeniskelamin
    input.value.nocm = props.pasien.nocm
    input.value.tglLahirPasien = props.pasien.tgllahir
}

const fetchWali = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/penanggungjawab_m?select=id,penanggungjawab&param_search=penanggungjawab&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Wali.value = response
    })
}

const fetchPegawai = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Pegawai.value = response
    })
}

const setTandaTanganPegawai = async (e: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signaturPegawai", element.ttd)
    } else {
      H.tandaTangan().set("signaturPegawai", '')
    }
  })
}


setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
.title-pst {
    font-weight: bold;
}

.label-pst {
    font-weight: 500;
}
</style>
