<style lang="scss"></style>

<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Surat Keterangan Sehat Paru</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @kembaliKeun="kembaliKeun"
                                :isHideCetakWNA="false" isHideST></ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->
                <div class="column">
                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <h2>Nama Pasien</h2>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.namaPasien" />
                            </VControl>
                        </div>
                        <div class="column is-6">
                            <h2>Umur</h2>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.umurPasien" />
                            </VControl>
                        </div>
                        <div class="column is-6 pt-1">
                            <h2>Jenis Kelamin</h2>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.jenisKelaminPasien" />
                            </VControl>
                        </div>
                        <div class="column is-6 pt-1">
                            <h2>Pekerjaan</h2>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.pekerjaanPasien" />
                            </VControl>
                        </div>
                        <div class="column is-6 pt-1">
                            <h2>Alamat</h2>
                            <VField>
                                <VTextarea rows="2" v-model="input.alamatPasien"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-6 pt-1">
                            <h2>Dokter</h2>
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter"
                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                            </VControl>
                        </div>
                        <div class="column is-12 pt-1">
                            Setelah dilakukan Pemeriksaan Kesehatan Paru, dengan kesimpulan :<br>
                            Kondisi Paru :
                            <div class="columns">
                                <div class="column is-6">
                                    <Multiselect v-model="input.kondisiParu" :attrs="{ value }" placeholder="--Pilih--"
                                        label="label" :options="d_kondisiParu" :searchable="true" track-by="label"
                                        mode="single" autocomplete="off">
                                    </Multiselect>
                                </div>
                                <div class="column is-6">
                                    <VControl v-if="input.kondisiParu == 'Tidak Sehat/Abnormal'">
                                        <VInput type="text" class="input" v-model="input.KP_Abnormal"
                                            placeholder="....." />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12 pt-1">
                            Saran / Suggestions :
                            <VControl>
                                <VInput type="text" class="input" v-model="input.saran" />
                            </VControl>
                        </div>
                        <div class="column is-12 pt-0">
                            Demikian Surat Keterangan ini dibuat dengan sebenar-benarnya untuk keperluan sebagaimana
                            mestinya.
                        </div>
                    </div>
                </div>
                <!-- form baru -->

            </div>
        </div>

    </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import moment from 'moment'

useHead({ title: 'Surat Keterangan Sehat Paru - ' + import.meta.env.VITE_PROJECT, })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
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
const pasien: any = ref({})
const d_Ruangan: any = ref([])
const d_Dokter: any = ref([])
const d_Diagnosa: any = ref([])
const loadData: any = ref(true)
const item: any = reactive({})
const COLLECTION: any = ref('SuratKeteranganSehatParu') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({});
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading = ref(false)
const isAktive = ref()
const loadRiwayat = async () => {
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
    } else {
        let d = input.value
        d.namaPasien = props.pasien.namapasien
        d.umurPasien = calculateAge(props.pasien.tgllahir)
        d.jenisKelaminPasien = props.pasien.jeniskelamin
        d.pekerjaanPasien = props.pasien.pekerjaan
        d.alamatPasien = props.pasien.alamatlengkap
        d.kondisiParu = 'Sehat/Normal'
        d.DDDokter = props.registrasi.dokter
    }
}
const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    object.nocm = pasien.value.nocm
    object.pasien = H.setObjectPasien(props.pasien)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'url_form': route.name,
        'name_form': 'Surat Keterangan Sehat Paru',
        'jenis_emr': 'asesmen_medis',
        'data': object
    }

    isLoading.value = true
    useApi().post(`/emr/simpan-emr-surket`, json).then((response: any) => {
        isLoading.value = false
        loadRiwayat()
    }).catch((e: any) => {
        isLoading.value = false
    })
}
const fetchDokter = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`).then((response) => {
        d_Dokter.value = response
    })
}
const fetchPasien = () => {
    pasien.value = props.pasien
    pasien.value.registrasi = props.registrasi
    // NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}
function calculateAge(birthdate) {
    const today = new Date();
    const birthDate = new Date(birthdate);
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    return age;
}
onBeforeMount(async () => {
    try {
        await loadRiwayat()
        await fetchPasien()
        let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
        if (cache) input.value = cache
        loadData.value = false
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
const d_kondisiParu: any = ref([
    { value: 'Sehat/Normal', label: 'Sehat/Normal' },
    { value: 'Tidak Sehat/Abnormal', label: 'Tidak Sehat/Abnormal' }
])
</script>