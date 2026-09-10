<template>
 <MasterEMR :isTTD="true" :fieldTTD="'peralihanDPJP'" @simpan="simpan()" @simpanTemplate="simpanTemplate()" :ID_PASIEN="ID_PASIEN"
    :NOREC_PD="NOREC_PD" :norec_emr="norec_emr" :input="input" :FORM_NAME="props.FORM_NAME"
    :FORM_URL="props.FORM_URL" :registrasi="props.registrasi" :pasien="props.pasien"
    :COLLECTION="props.COLLECTION" ref="masterRef" :isLoading="isLoading">
    <template #content>
        <VCard>
            <div class="column is-12">
                <VField label="Tanggal">
                    <VDatePicker v-model="input.tanggal" mode="date" style="width: 100%; padding-top:10px" trim-weeks>
                        <template #default="{ inputValue, inputEvents }">
                            <VField style="margin-bottom: 0.70rem;">
                                <VControl class="prime-auto" icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                </VControl>
                            </VField>
                        </template>
                    </VDatePicker>
                </VField>
            </div>
            <div class="column is-12">
                <VField label="Status :">
                    <VControl>
                        <VRadio
                            v-model="input.status"
                            value="Umum"
                            label="Umum"
                            name="status"
                            color="primary"
                            square
                        />
                        <VRadio
                            v-model="input.status"
                            value="BPJS"
                            label="BPJS"
                            name="status"
                            color="primary"
                            square
                        />
                        <VRadio
                            v-model="input.status"
                            value="IKS"
                            label="IKS"
                            name="status"
                            color="primary"
                            square
                        />
                        <VRadio
                            v-model="input.status"
                            value="Lainnya"
                            label="Lainnya"
                            name="status"
                            color="primary"
                            square
                        />
                        <VInput
                            v-model="input.statusdetail"
                            type="text"
                            style="width: auto !important;"
                            v-if="input.status && input.status == 'Lainnya'"
                        />
                    </VControl>
                </VField>
            </div>
            <div class="column is-12" v-for="(dt,index) in input.detail">
                <div class="columns is-multiline">
                    <div class="column is-10">
                        <VField label="Diagnosa Medis :">
                            <VControl>
                                <VInput type="text" v-model="input.detail[index].value"></VInput>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2 mt-5">
                        <VButtons>
                            <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                                v-tooltip.bubble="'Tambah '">
                            </VIconButton>
                            <VIconButton v-if="index > 0" class="mt-1" type="button" raised circle icon="feather:trash"
                                @click="removeItem(index)" color="danger">
                            </VIconButton>
                        </VButtons>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <table style="width: 100%;" border="1">
                    <tr>
                        <td>
                            <div class="column is-12" style="text-align: center;">
                                <h1>DPJP</h1>
                            </div>
                            <div class="column is-12">
                                <VField>
                                <VControl class="prime-auto">
                                    <AutoComplete v-model="input.dpjp" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari DPJP..." />
                                </VControl>
                            </VField>
                            </div>
                        </td>
                        <td>
                            <!-- <div class="column is-12" style="text-align: center;">
                                <h1> Tanda tangan</h1>
                            </div>
                            <div class="column is-12" style="text-align: center;">
                                <TandaTangan :elemenID="'TTDDpjp'" :width="'150'" :height="'150'" class="dek" />
                            </div> -->
                            <img :src="'https://api.qrserver.com/v1/create-qr-code/?size=70x70&data=' + (input.dpjp ? input.dpjp.label : '-')"><br>
                        </td>
                    </tr>
                </table>
            </div>
        </VCard>
        <!-- Rawat Bersama -->
        <VCard class="mt-5">
            <table class="table is-striped is-fullwidth">
                <thead>
                    <tr>
                        <th class="" style="text-align: left;">Rawat Bersama</th>
                        <th class="" style="text-align: left;">Tanggal Mulai</th>
                        <th class="" style="text-align: left;">Tanggal Akhir</th>
                        <th class="" style="text-align: left;" width="17%">#</th>
                    </tr>
                </thead>
                <tbody v-for="(dokter, index) in input.rawatbersama">
                    <tr>
                        <td class="td-popri">
                            <VField>
                                <VControl class="prime-auto">
                                    <AutoComplete v-model="input.rawatbersama[index].dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari DPJP..." />
                                </VControl>
                            </VField>
                        </td>
                        <td class="td-popri">
                            <VField>
                                <VDatePicker v-model="input.rawatbersama[index].tglAwal" mode="date" style="width: 100%; padding-top:10px" trim-weeks :max-date="new Date()">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField style="margin-bottom: 0.70rem;">
                                            <VControl class="prime-auto" icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </td>
                        <td class="td-popri">
                            <VField>
                                <VDatePicker v-model="input.rawatbersama[index].tglAkhir" mode="date" style="width: 100%; padding-top:10px" trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField style="margin-bottom: 0.70rem;">
                                            <VControl class="prime-auto" icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </td>
                        <td>
                            <VButtons>
                                <VIconButton type="button" raised circle icon="feather:plus" @click="addNewDPJP()" color="info"
                                    v-tooltip.bubble="'Tambah '">
                                </VIconButton>
                                <VIconButton v-if="index > 0" class="mt-1" type="button" raised circle icon="feather:trash"
                                    @click="removeDPJP(index)" color="danger">
                                </VIconButton>
                            </VButtons>
                        </td>
                    </tr>
                </tbody>
            </table>
        </VCard>
        <!-- Peralihan DPJP -->
        <VCard class="mt-5">
            <table class="table is-striped is-fullwidth">
                <thead>
                    <tr>
                        <th class="" style="text-align: left;">DPJP Peralihan</th>
                        <th class="" style="text-align: left;">Tanda Tangan</th>
                        <th class="" style="text-align: left;" width="17%">#</th>
                    </tr>
                </thead>
                <tbody v-for="(dpjp, index) in input.peralihanDPJP">
                    <tr>
                        <td class="td-popri">
                            <VField>
                                <VControl class="prime-auto">
                                    <AutoComplete v-model="input.peralihanDPJP[index].dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari DPJP..." />
                                </VControl>
                            </VField>
                        </td>
                        <td class="td-popri">
                            <!-- <TandaTangan :elemenID="'ttd_'+dpjp.no" :width="'150'" :height="'150'" class="dek" /> -->
                        </td>
                        <td>
                            <VButtons>
                                <VIconButton type="button" raised circle icon="feather:plus" @click="addNewPeralihan()" color="info"
                                    v-tooltip.bubble="'Tambah '">
                                </VIconButton>
                                <VIconButton v-if="index > 0" class="mt-1" type="button" raised circle icon="feather:trash"
                                    @click="removePeralihan(index)" color="danger">
                                </VIconButton>
                            </VButtons>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="column is-12 mt-5">
                <VField label="Tanggal">
                    <VDatePicker v-model="input.tanggal" mode="date" style="width: 100%; padding-top:10px" trim-weeks>
                        <template #default="{ inputValue, inputEvents }">
                            <VField style="margin-bottom: 0.70rem;">
                                <VControl class="prime-auto" icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                </VControl>
                            </VField>
                        </template>
                    </VDatePicker>
                </VField>
            </div>
            <div class="column is-12 mt-5">
                <VField label="Alasan">
                    <VTextarea
                        :valueTemplate="input.alasan"
                        v-model="input.alasan"
                        rows="4"
                        placeholder=""
                    />
                </VField>
            </div>
            <div class="columns is-multiline">
                <div class="column is-6 mt-3">
                    <VField label="Pemohon Pasien">
                        <TandaTangan :elemenID="'ttd_pasien'" :width="'150'" :height="'150'" class="dek" />
                    </VField>
                </div>
                <div class="column is-6 mt-3">
                    <VField label="DPJP Lama">
                        <TandaTangan :elemenID="'ttd_dpjpLama'" :width="'150'" :height="'150'" class="dek" />
                    </VField>
                </div>
            </div>
        </VCard>
    </template>
 </MasterEMR>
</template>

<script setup lang="ts">
import  MasterEMR from './master-emr.vue'
import { useRoute, useRouter } from 'vue-router'
import { useApi } from '/@src/composable/useApi'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const masterRef = ref(null)
const dataPasien = '';
const d_Dokter: any = ref([]);
const dataTTD: any = ref([]);
const NOREC_EMRPASIEN: any = ref('')
const isLoading: any = ref(false);
const COLLECTION: any = ref(props.COLLECTION) //table mongodb

const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}
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
const input: any = ref({
    detail: [
        {
            no: 1,
            value: ''
        }
    ],
    rawatbersama: [
        {
            no: 1,
            dokter: '',
            tglAwal: '',
            tglAkhir: ''
        }
    ],
    peralihanDPJP: [
        {
            no: 1,
            dokter: '',
            ttd: ''
        }
    ]
})



const fetchDokter = async (filter: any) => {
    d_Dokter.value = await H.fetchDokter(filter);
}

const setAutoFill = async () => {
    console.log(props.registrasi,'DATAAAAAA');

    input.value.rawatbersama[0].dokter = props.registrasi.dokterrawatbersama
    const response_AsmedRajal = await useApi().get(`emr/auto-fill?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=AsesmenMedisRawatJalan&field=TADiagnosa`);
    if (response_AsmedRajal) {
        input.value.detail[0].value = response_AsmedRajal.TADiagnosa;
    }
};

const loadRiwayat = async () => {
  // if (NOREC_EMRPASIEN.value == '') return
  await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
    if (response.length) {
      input.value = response[0] //set ke inputan
      console.log(input.value);
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
      dataTTD.value = response[0]
      H.tandaTangan().set('TTDDpjp', dataTTD.value.TTDDpjp)
      H.tandaTangan().set('ttd_pasien', dataTTD.value.ttd_pasien)
      H.tandaTangan().set('ttd_dpjpLama', dataTTD.value.ttd_dpjpLama)

    }
    else {
      setAutoFill()
    }
  })
}


const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    console.log("Pasien",props.pasien);
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    object['TTDDpjp'] = H.tandaTangan().get('TTDDpjp')
    object['ttd_pasien'] = H.tandaTangan().get('ttd_pasien')
    object['ttd_dpjpLama'] = H.tandaTangan().get('ttd_dpjpLama')

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
        loadRiwayat();
    }).catch((e: any) => {
        isLoading.value = false
    })
}

const simpanTemplate = () => {
    if(!input.value.namatemplate) {
    H.alert('warning', "Nama Template wajib diisi")
    return;
  }
let ID = input.id ? input.id : ''

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
  `/emr/simpan-emr-template`, json).then((response: any) => {
    isLoading.value = false
    input.value.namatemplate =  null
  }).catch((e: any) => {
    isLoading.value = false
  })
}

const addNewItem = () => {
  input.value.detail.push({
    no: input.value.detail[input.value.detail.length - 1].no + 1,
  });
}
const removeItem = (index: any) => {
  input.value.detail.splice(index, 1)
}

const addNewDPJP = () => {
  input.value.rawatbersama.push({
    no: input.value.rawatbersama[input.value.rawatbersama.length - 1].no + 1,
  });
}
const removeDPJP = (index: any) => {
  input.value.rawatbersama.splice(index, 1)
}

const addNewPeralihan = () => {
  input.value.peralihanDPJP.push({
    no: input.value.peralihanDPJP[input.value.peralihanDPJP.length - 1].no + 1,
  });
}
const removePeralihan = (index: any) => {
  input.value.peralihanDPJP.splice(index, 1)
}





setView()
fetchDokter();
setAutoFill();
loadRiwayat();

</script>

<style lang="scss">
.text-bold {
    font-weight: bold;
}
</style>
