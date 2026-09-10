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
            <VCard>
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-4">
                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Dokter Operator</h1>
                                    <VControl icon="feather:search">
                                        <AutoComplete v-model="input.dokterOperator" :suggestions="d_Pegawai"
                                            @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Cari nama Pegawai" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Perawat Asisten</h1>
                                    <VControl icon="feather:search">
                                        <AutoComplete v-model="input.perawatAsisten" :suggestions="d_Pegawai"
                                            @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Cari nama Pegawai" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Perawat Sirkuler</h1>
                                    <VControl icon="feather:search">
                                        <AutoComplete v-model="input.perawatSirkuler" :suggestions="d_Pegawai"
                                            @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Cari nama Pegawai" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Perawat Monitor</h1>
                                    <VControl icon="feather:search">
                                        <AutoComplete v-model="input.perawatMonitor" :suggestions="d_Pegawai"
                                            @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Cari nama Pegawai" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Radiografer / Teknisi</h1>
                                    <VControl icon="feather:search">
                                        <AutoComplete v-model="input.radiografer" :suggestions="d_Pegawai"
                                            @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Cari nama Pegawai" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Dokter Anestesi / Perawat Anestesi</h1>
                                    <VControl icon="feather:search">
                                        <AutoComplete v-model="input.dokterAnestasi" :suggestions="d_Pegawai"
                                            @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Cari nama Pegawai" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12">
                    <Fieldset :toggleable="true" legend="Prosedur Tindakan">
                    <div class="column is-12">
                                <div class="columns is-multiline">
                                    <div class="column is-4">
                                        <h1 style="font-weight:bold;">Jam Pasien Masuk ruangan CathLab</h1>
                                        <VDatePicker class="column is-7" v-model="input.jamMasuk" color="green" mode="time"
                                            is24hr>
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
                                    <div class="column is-4">
                                        <h1 style="font-weight:bold;">Jam Pasien Keluar ruangan CathLab</h1>
                                        <VDatePicker class="column is-7" v-model="input.jamKeluar" color="green" mode="time"
                                            is24hr>
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
                                <div class="column is-12 p-0">
                                <h1 style="font-weight:bold;"> Tanda-tanda Vital (saat masuk) </h1>
                            </div>
                                <div class="column is-12 p-0" v-for="(data) in tandaVital">
                                <h1 class="emr mb-3">{{ data.title }}</h1>
                                <div class="columns is-multiline pl-3 pr-3">
                                    <div class="column is-one-quarter" v-for="(detail) in data.value">
                                        <p>{{ detail.subTitle }}</p>
                                        <VField addons v-if="detail.type == 'textfiled'">
                                            <VControl expanded>
                                                <VInput type="text" class="input" v-model="input[detail.model]" />
                                            </VControl>
                                            <VControl class="field-addon-body">
                                                <VButton static>{{ detail.satuan }}</VButton>
                                            </VControl>
                                        </VField>

                                        <VField v-else-if="detail.type == 'textbox'">
                                            <VControl>
                                                <input v-model="input[detail.model]" class="input"
                                                    :placeholder="detail.subTitle + ' ...'" />
                                            </VControl>
                                        </VField>

                                        <VField v-else>
                                            <VControl raw subcontrol>
                                                <VCheckbox v-model="input[detail.model]" :true-value="detail.satuan"
                                                    :label="detail.satuan" color="primary" circle />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>

                            <div class="column is-12" v-for="(datas) in terapiOksigen">
                                <h1 style="font-weight: bold; margin-bottom: 1rem; margin-top: 1rem;">{{ datas.title }}</h1>
                            <div class="columns is-multiline">
                                <div class="column" v-for="(data) in datas.value">
                                    <VField v-if="data.type == 'checkBox'">
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                                :label="data.subTitle" class="p-0" color="primary" square />
                                        </VControl>
                                    </VField>
                                    <VField :label="data.subTitle" v-else-if="data.type == 'textBox'" class="p-0">
                                        <VControl raw subcontrol>
                                            <input v-model="input[data.model]" class="input p-0" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>

                        </div>

                        <div class="column is-12" v-for="(datas, i) in LokasiPungsi">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                            <div class="columns is-multiline">
                                <div class="column is-4" v-for="(data, b) in datas.value">
                                    <VField v-if="data.type == 'checkBox'">
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input[data.model + b]" :true-value="data.subTitle"
                                                :label="data.subTitle" class="p-0" color="primary" square />
                                        </VControl>
                                    </VField>
                                    <VField :label="data.subTitle" v-else-if="data.type == 'textBox'" class="p-0">
                                        <VControl raw subcontrol>
                                            <input v-model="input[data.model + b]" class="input p-0" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>

                        </div>
                        
                        <div class="column is-12" v-for="(datas, i) in AlatPasang">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                            <div class="columns is-multiline">
                                <div class="column pr-2" v-for="(data, b) in datas.value" :class="data.type=='checkBox' && data.subTitle == 'Sheath No' ? 'is-2': 'is-3'">
                                    <VField v-if="data.type == 'checkBox'">
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input[data.model + b]" :true-value="data.subTitle"
                                                :label="data.subTitle" class="p-0" color="primary" square />
                                        </VControl>
                                    </VField>
                                    <VField :label="data.subTitle" v-else-if="data.type == 'textBox'" class="p-0">
                                        <VControl raw subcontrol>
                                            <input v-model="input[data.model + b]" class="input p-0" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                            <div class="column is-12 p-0" v-for="(data) in tandaVital2">
                                <h1 class="emr mb-3">{{ data.title }}</h1>
                                <div class="columns is-multiline pl-3 pr-3">
                                    <div class="column is-one-quarter" v-for="(detail) in data.value">
                                        <p>{{ detail.subTitle }}</p>
                                        <VField addons v-if="detail.type == 'textfiled'">
                                            <VControl expanded>
                                                <VInput type="text" class="input" v-model="input[detail.model]" />
                                            </VControl>
                                            <VControl class="field-addon-body">
                                                <VButton static>{{ detail.satuan }}</VButton>
                                            </VControl>
                                        </VField>

                                        <VField v-else-if="detail.type == 'textbox'">
                                            <VControl>
                                                <input v-model="input[detail.model]" class="input"
                                                    :placeholder="detail.subTitle + ' ...'" />
                                            </VControl>
                                        </VField>

                                        <VField v-else>
                                            <VControl raw subcontrol>
                                                <VCheckbox v-model="input[detail.model]" :true-value="detail.satuan"
                                                    :label="detail.satuan" color="primary" circle />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-12" v-for="(datas, i) in ETT">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;"></h1>
                            <div class="columns is-multiline">
                                <div class="column is-2" v-for="(data, b) in datas.value">
                                    <VField v-if="data.type == 'checkBox'">
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input[data.model + b]" :true-value="data.subTitle"
                                                :label="data.subTitle" class="p-0" color="primary" square />
                                        </VControl>
                                    </VField>
                                    <VField :label="data.subTitle" v-else-if="data.type == 'textBox'" class="p-0">
                                        <VControl raw subcontrol>
                                            <input v-model="input[data.model + b]" class="input p-0" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>

                        </div>

                            

                        </div>

                
                        </Fieldset>
                        </div>
                        <div class="column is-12">
                    <Fieldset :toggleable="true" legend="Status Cairan">
                        <div class="column is-12 p-0" v-for="(data) in Intake">
                                <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ data.title }}</h1>
                                <div class="columns is-multiline pl-3 pr-3">
                                    <div class="column is-one-quarter" v-for="(detail) in data.value">
                                        <p>{{ detail.subTitle }}</p>
                                        <VField addons v-if="detail.type == 'textfiled'">
                                            <VControl expanded>
                                                <VInput type="text" class="input" v-model="input[detail.model]" />
                                            </VControl>
                                            <VControl class="field-addon-body">
                                                <VButton static>{{ detail.satuan }}</VButton>
                                            </VControl>
                                        </VField>

                                        <VField v-else-if="detail.type == 'textbox'">
                                            <VControl>
                                                <input v-model="input[detail.model]" class="input"
                                                    :placeholder="detail.subTitle + ' ...'" />
                                            </VControl>
                                        </VField>

                                        <VField v-else>
                                            <VControl raw subcontrol>
                                                <VCheckbox v-model="input[detail.model]" :true-value="detail.satuan"
                                                    :label="detail.satuan" color="primary" circle />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-12 p-0" v-for="(data) in Output">
                                <h1 style="font-weight: bold; margin-bottom: 1rem; margin-top: 1rem;">{{ data.title }}</h1>
                                <div class="columns is-multiline pl-3 pr-3">
                                    <div class="column is-one-quarter" v-for="(detail) in data.value">
                                        <p>{{ detail.subTitle }}</p>
                                        <VField addons v-if="detail.type == 'textfiled'">
                                            <VControl expanded>
                                                <VInput type="text" class="input" v-model="input[detail.model]" />
                                            </VControl>
                                            <VControl class="field-addon-body">
                                                <VButton static>{{ detail.satuan }}</VButton>
                                            </VControl>
                                        </VField>

                                        <VField v-else-if="detail.type == 'textbox'">
                                            <VControl>
                                                <input v-model="input[detail.model]" class="input"
                                                    :placeholder="detail.subTitle + ' ...'" />
                                            </VControl>
                                        </VField>

                                        <VField v-else>
                                            <VControl raw subcontrol>
                                                <VCheckbox v-model="input[detail.model]" :true-value="detail.satuan"
                                                    :label="detail.satuan" color="primary" circle />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-12 mt-3">
                            <table class="tg">
                                <thead>
                                    <tr>
                                        
                                        <td class="tg-0lax text-center" colspan="4">Terapi Farmakologi</td>

                                    </tr>
                                    <tr>
                                        <td class="tg-0lax">Nama Obat</td>
                                        <td class="tg-0lax">Dosis</td>
                                        <td class="tg-0lax">Rute</td>
                                        <td class="tg-0lax">#</td>

                                    </tr>
                                </thead>
                                <tbody v-for="(item, index) in input.details" :key="index">
                                    <tr>
                                        <td>
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" v-model="item.namaObat" placeholder="Nama Obat" />
                                                </VControl>
                                            </VField>
                                        </td>
                                        <td>
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" v-model="item.dosis" placeholder="Dosis" />
                                                </VControl>
                                            </VField>
                                        </td>

                                        <td style="width:25%">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" v-model="item.Rute" placeholder="Rute" />
                                                </VControl>
                                            </VField>
                                        </td>
                                        <td rowspan="2" style="width:13%;vertical-align: middle;">
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

                        </Fieldset>
                    </div>

                    <VCard class="border-card pink" style="margin-top: 2rem;">
                        <div class="column is-4">
                            <h1 class="mb-3" style="font-weight: bold;">Tanggal dan Jam</h1>
                                            <VField>
                                                <VDatePicker v-model="input.waktuDibuat" mode="dateTime" style="width: 100%"
                                                    trim-weeks :max-date="new Date()">
                                                    <template #default="{ inputValue, inputEvents }">
                                                        <VField>
                                                            <VControl icon="feather:calendar" fullwidth>
                                                                <VInput :value="inputValue" placeholder="Tanggal"
                                                                    v-on="inputEvents" />
                                                            </VControl>
                                                        </VField>
                                                    </template>
                                                </VDatePicker>
                                            </VField>
                        </div>
                <div class="column is-12">
                    <div class="columns is-multiline">

                        <div class="column is-4">
                            <div class="column is-6">
                                      <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan>
                                  </div>
                        <h1 style="font-weight: bold; margin-bottom: 1rem;">
                            Perawat CathLab
                        </h1>
                       
                        <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                            <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                                <AutoComplete v-model="input.perawat" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." 
                                    @item-select="setTandaTangan($event)" />
                            </VControl>
                        </VField>
                    </div>
             
                    <div class="column is-4" style="margin-left: auto;">
                        <div class="column is-6">
                                      <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan>
                                  </div>
                        <h1 style="font-weight: bold; margin-bottom: 1rem;">
                            Dokter Pengirim
                        </h1>
                        <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                            <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                                <AutoComplete v-model="input.dokterPengirim" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." 
                                    @item-select="setTandaTangan($event)" />
                            </VControl>
                        </VField>
                    </div>
                    </div>
                </div>
            </Vcard>

                </div>
            </VCard>
        </div>
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
import Fieldset from 'primevue/fieldset';
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import * as EMR from '../page-emr-plugins/cat-kep-intra-tindakan-cathlab'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let tandaVital = ref(EMR.tandaVital())
let terapiOksigen = ref(EMR.terapiOksigen())
let LokasiPungsi = ref(EMR.LokasiPungsi())
let AlatPasang = ref(EMR.AlatPasang())
let tandaVital2 = ref(EMR.tandaVital2())
let ETT = ref(EMR.ETT())
let Intake = ref(EMR.Intake())
let Output = ref(EMR.Output())

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

const route = useRoute()
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_Pegawai: any = ref([])
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref('CatatanKeperawatanIntraTindakanCathlab') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    jamMasuk: new Date,
    jamKeluar: new Date,
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
const loadRiwayat = async () => {
    // if (NOREC_EMRPASIEN.value == '') return
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
            if (response.length) {
                input.value = response[0] //set ke inputan 
                if (response[0].tandaTanganPerawat) {
                    H.tandaTangan().set("signature_1", response[0].tandaTanganPerawat)
                }
                if (response[0].tandaTanganDokter) {
                    H.tandaTangan().set("signature_2", response[0].tandaTanganDokter)
                }
              
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                }
             
            }
}
const fetchPegawai = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_Pegawai.value = response
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    object.tandaTanganPerawat = H.tandaTangan().get("signature_1")
    object.tandaTanganDokter = H.tandaTangan().get("signature_2")
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

const getDataExist = async () => {
    await useApi().get(
        `emr/get-data-exist?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    ).then((response) => {

        input.value.tekananDarah = response.tekananDarah ? response.tekananDarah :''
        input.value.pernapasan = response.pernapasan ? response.pernapasan : ''
        input.value.nadi = response.nadi ? response.nadi : ''
    })
}
getDataExist()

const addNewItem = () => {
    input.value.details.push({
        no: input.value.details[input.value.details.length - 1].no + 1,
        tgl: new Date(),
    });
}
const removeItem = (index: any) => {
    input.value.details.splice(index, 1)
}

const kembaliKeun = () => {
    window.history.back()
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
    H.tandaTangan().set("signature_2", response.ttd2)
    input.value.tandaTanganDokter = response.ttd2
  }else{
    H.tandaTangan().set("signature_2", '')
  }
}
setView()

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
<style lang="scss">
.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
}

.tg td {
    border-color: var(--fade-grey-dark-2);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg th {
    border-color: var(--fade-grey-dark-3);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: normal;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg .tg-0lax {
    text-align: left;
    vertical-align: top
}
</style>