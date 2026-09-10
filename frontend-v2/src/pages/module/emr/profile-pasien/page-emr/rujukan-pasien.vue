<style lang="scss">
// .table {
//     border-collapse: collapse;
//     width: 100%;
// }

// .table td { border: 1px solid black !important }

// .table th {
//     text-align: center !important;
//     border: 1px solid black !important;
// }

h1 {
    font-weight: bold !important
}
</style>
<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Rujukan Pasien</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate"
                                @kembaliKeun="kembaliKeun" isHideST></ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <div class="column">
                    <div class="columns is-multiline px-2">
                        <div class="column is-12 pb-0">
                            Kepada Yth.
                        </div>
                        <div class="column is-6">
                            <VField label="Teman Sejawat/Dr/Bag">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.yangTerhomat" />
                                </VControl>
                                <!-- <VControl class="prime-auto">
                                    <AutoComplete v-model="input.dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Dokter..." />
                                </VControl> -->
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField label="Di ">
                                <VInput v-model="input.tempatdokter" type="text"></VInput>
                            </VField>
                        </div>
                    </div>
                    <span class="text-muted px-2 text-small">
                        Dengan Hormat,
                    </span>
                    <br>
                    <span class="text-muted px-2 text-small">
                        Mohon pemeriksaan/perawatan/pengobatan lebih lanjut pasien :
                    </span>
                    <div class="columns is-multiline px-2 mt-4">
                        <div class="column is-4">
                            <VField label="Nama ">
                                <VInput v-model="input.namapasien" type="text"></VInput>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField label="Jenis Kelamin">
                                <VInput v-model="input.jeniskelamin" type="text"></VInput>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField label="Umur">
                                <VInput v-model="input.umur" type="text"></VInput>
                            </VField>
                        </div>
                    </div>
                    <div class="columns is-multiline px-2">
                        <div class="column is-12">
                            <VField label="Alamat">
                                <VTextarea rows="2" v-model="input.alamatpasien" type="text"></VTextarea>
                            </VField>
                        </div>
                    </div>
                    <div class="columns is-multiline px-2">
                        <div class="column is-12">
                            <VField label="Diagnosa Kerja">
                                <VTextarea rows="2" v-model="input.diagnosapasien" type="text"></VTextarea>
                            </VField>
                        </div>
                    </div>
                    <div class="columns is-multiline px-2">
                        <div class="column is-12">
                            <VField label="Riwayat Penyakit">
                                <VTextarea rows="2" v-model="input.riwayatpenyait" type="text"></VTextarea>
                            </VField>
                        </div>
                    </div>
                    <div class="columns is-multiline px-2">
                        <div class="column is-12">
                            <VField label="Pemeriksaan Fisik">
                                <VTextarea rows="2" v-model="input.pemeriksaanfisik"></VTextarea>
                            </VField>
                        </div>
                    </div>
                    <div class="columns is-multiline px-2">
                        <div class="column is-12">
                            <VField label="Penunjang">
                                <VTextarea rows="2" v-model="input.penunjang"></VTextarea>
                            </VField>
                        </div>
                    </div>
                    <div class="columns is-multiline px-2">
                        <div class="column is-12">
                            <VField label="Terapi/Prosedur/Intervensi yang telah diberikan">
                                <VTextarea v-model="input.prosedur" type="text" rows="2"></VTextarea>
                            </VField>
                        </div>
                    </div>
                    <div class="columns is-multiline px-2">
                        <div class="column is-12">
                            <VField label="Alasan Rujuk">
                                <VTextarea rows="2" v-model="input.alasanrujuk"></VTextarea>
                            </VField>
                        </div>
                    </div>
                    <div class="columns is-multiline px-2">
                        <div class="column is-12">
                            <VField label="Kebutuhan Pelayanan Lanjutan di RS Rujukan">
                                <VTextarea rows="2" v-model="input.pelayanan"></VTextarea>
                            </VField>
                        </div>
                    </div>
                    <div class="columns is-multiline px-2">
                        <div class="column is-6">
                            <VField>
                                <h1>Nama petugas yang menyetujui rujukan di RS yang dituju</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.petugas" />
                                </VControl>
                                <!-- <VControl class="prime-auto">
                                    <AutoComplete v-model="input.petugas" :suggestions="d_Pegawai"
                                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                        :field="'label'" placeholder="Cari Petugas..." />
                                </VControl> -->
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField label="No HP">
                                <VInput v-model="input.nohp" type="text"></VInput>
                            </VField>
                        </div>
                        <div class="column is-6"></div>
                        <div class="column is-6">
                            <h1>Garut</h1>
                            <VDatePicker v-model="input.tanggal" mode="datetime" trim-weeks>
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                            <div style="text-align: center;" class="pt-2">
                                <h1>Dokter yang merawat</h1>
                                <!-- <TandaTangan :elemenID="'TTDDokter'" :width="'150'" :height="'150'" class="dek" /> -->
                                <VControl class="prime-auto">
                                    <AutoComplete v-model="input.dokterYangMerawat" :suggestions="d_Dokter"
                                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                        :field="'label'" placeholder="Cari Dokter..." class="mt-2" />
                                </VControl>
                            </div>
                        </div>
                    </div>
                    <div class="columns is-multiline px-2">
                        <div class="column is-12 pt-0 pb-0">
                            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                        </div>
                        <div class="column is-4" style="text-align: center;">
                            <h1>Disetujui</h1>
                            <TandaTangan :elemenID="'TTDMenyetujui'" :width="'150'" :height="'150'" class="dek" />
                            <VControl class="mt-2">
                                <VInput type="text" class="input" v-model="input.pasienMenyetujui"
                                    placeholder="Nama..." />
                            </VControl>
                            <h1>Pasien/Penanggungjawab</h1>
                        </div>
                        <div class="column is-4" style="text-align: center;">
                            <h1>Diserahkan Oleh</h1>
                            <!-- <TandaTangan :elemenID="'TTDPegawaiSerah'" :width="'150'" :height="'150'" class="dek" /> -->
                            <VControl class="prime-auto mt-2">
                                <AutoComplete v-model="input.diserahkanOleh" :suggestions="d_Pegawai"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari Pegawai..." />
                                    <!-- <VInput type="text" class="input" v-model="input.diserahkanOleh"
                                    placeholder="Nama..." /> -->
                            </VControl>
                            <h1>Petugas</h1>
                        </div>
                        <div class="column is-4" style="text-align: center;">
                            <h1>Diterima Oleh</h1>
                            <TandaTangan :elemenID="'TTDPegawaiTerima'" :width="'150'" :height="'150'" class="dek" />
                            <VControl class="prime-auto mt-2">
                                <!-- <AutoComplete v-model="input.pegawaiterima" :suggestions="d_Pegawai"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari Pegawai..." /> -->
                                <VInput type="text" class="input" v-model="input.pegawaiterima"
                                    placeholder="Nama..." />
                            </VControl>
                            <h1>Petugas</h1>
                        </div>
                    </div>
                </div>

                <!-- form baru -->
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import * as H from '/@src/utils/appHelper'
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
// import DataTable from 'primevue/datatable'
// import Column from 'primevue/column'
// import InputText from 'primevue/inputtext';

useHead({ title: 'Rujukan Pasien - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const COLLECTION: any = ref('RujukanPasien') // table mongodb
const NOREC_EMRPASIEN: any = ref('')
const route = useRoute()
const router = useRouter()
const { y } = useWindowScroll()
const user = useUserSession().getUser().pegawai;
const isStuck = computed(() => { return y.value > 30 })
const isLoading = ref(false)
const pasien: any = ref({})
const input: any = ref({})
const dataTTD: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
// const listTemplate: any = ref([])
// const showModalTemplate: any = ref(false)
// const listTemplateFix: any = ref([])
// const showModalTemplateFix: any = ref(false)

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
        FORM_NAME: 'Rujukan Pasien',
        FORM_URL: 'rujukan-pasien',
        COLLECTION: 'RujukanPasien',
    }
)
function convertText(e) {
    switch (e) {
        case 1:
            return 'Baik'
            break;
        case 2:
            return 'Sedang'
            break;
        case 3:
            return 'Buruk'
            break;
        default:
            break;
    }
}
const loadRiwayat = async () => {
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    if (response.length) {
        if (response[0].pegawaiterima != null && response[0].pegawaiterima.label != undefined && response[0].pegawaiterima.label != null) {
            response[0].pegawaiterima = response[0].pegawaiterima.label
        }
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        dataTTD.value = response[0]
        H.tandaTangan().set("TTDDokter", dataTTD.value.TTDDokter);
        H.tandaTangan().set("TTDMenyetujui", dataTTD.value.TTDMenyetujui);
        H.tandaTangan().set("TTDPegawaiSerah", dataTTD.value.TTDPegawaiSerah);
        H.tandaTangan().set("TTDPegawaiTerima", dataTTD.value.TTDPegawaiTerima);
    } else {
        input.value.namapasien = props.pasien.namapasien
        input.value.jeniskelamin = props.pasien.jeniskelamin
        input.value.umur = props.pasien.umur
        input.value.alamatpasien = props.pasien.alamatlengkap
        input.value.nohp = props.pasien.nohp
        input.value.tanggal = new Date()
        const response_AsmedIGD = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=AsesmenAwalMedisGawatDarurat" + `&field=TADiagnosis,TArpp,details,perlukontrol,riwayatkeluar,statuskeluar,TAKeluhanUtama,TARiwayatPenyakitDahulu,TARiwayatPenggunaanObat,TARiwayatVaksin,TARPS,CBisiMOI,TAMOI,isalergi,alergi_tidak_diketahui,CBAlergiObat,TBAlergiObat,CBAlergiMakanan,TBAlergiMakanan,CBAlergiLainnya,TBAlergiLainnya,TAKepalaSG,TBAnemisMata,TBIkterusMata,TBRefleksPupilMata,TBOedemaPalpebraeMata,TBTonsilTHT,TBPharingTHT,TBTelingaTHT,TBHidungTHT,TBBibirTHT,TBLainnyaTHT,TBJVPLeher,TBPembesaranKelenjarLeher,CBKakuKudukLeher,CBSimetrisThoraks,CBAsimetrisThoraks,TBSimetrisORAsimetrisThoraks,TBRetraksiThoraks,TBS1S2Cor,CBRegulerCor,CBIregulerCor,TBMurmurCor,TBLainLainCor,TBRonchiPulmo,TBWheezingPulmo,TBVesikulerPulmo,TBLainnyaPulmo,CBSouffleAbdomen,CBDistensiAbdomen,CBMeteorismusAbdomen,CBNormalPeristaltik,CBMeningkatPeristaltik,CBMenurunPeristaltik,CBAscitesPeristaltik,TBNyeriTekanLokasiPeristaltik,TBHeparPeristaltik,TBLienPeristaltik,CBHangatExtremitas,CBDinginExtremitas,TBOdemaExtremitas,TBLainlainExtremitas,TBLainlainSG,keadaanumum,TBeGCS,TBvGCS,TBmGCS,TBcelciusTTV,TBRespirasiTTV,TBberatBadanTTV,TBNadiTTV,TBtekananDarahTTV,TBnsao2TTV,TBtinggiBadanTTV`)
        if (response_AsmedIGD != null) {
            let text = ''
            let text2 = ''
            if (response_AsmedIGD.details != null) {
                response_AsmedIGD.details.forEach((item) => {
                    text += `Daftar Masalah : ${item.TAdaftarMasalah ? item.TAdaftarMasalah : ''}\n`;
                    text += `Rencana Intervensi : ${item.TArencanaIntervensi ? item.TArencanaIntervensi : ''}\n`;
                    text += `Target : ${item.TAtarget ? item.TAtarget : ''}\n`;
                });
                input.value.prosedur = text
            }

            text2 += 'Tanda-tanda Vital \n'
            text2 += response_AsmedIGD.keadaanumum ? `Keadaan Umum : ${convertText(response_AsmedIGD.keadaanumum)}\n` : ''
            text2 += response_AsmedIGD.TBcelciusTTV ? `Suhu : ${response_AsmedIGD.TBcelciusTTV} °C\n` : ''
            text2 += response_AsmedIGD.TBNadiTTV ? `Nadi : ${response_AsmedIGD.TBNadiTTV} x/mnt\n` : ''
            text2 += response_AsmedIGD.TBRespirasiTTV ? `Pernafasan : ${response_AsmedIGD.TBRespirasiTTV} x/mnt\n` : ''
            text2 += response_AsmedIGD.TBtekananDarahTTV ? `Tekanan Darah : ${response_AsmedIGD.TBtekananDarahTTV} mmHg\n` : ''
            text2 += response_AsmedIGD.TBnsao2TTV ? `SPO2 : ${response_AsmedIGD.TBnsao2TTV} %\n` : ''
            text2 += response_AsmedIGD.TBeGCS ? `GCS : E ${response_AsmedIGD.TBeGCS} V ${response_AsmedIGD.TBvGCS ? response_AsmedIGD.TBvGCS : ''} M ${response_AsmedIGD.TBmGCS ? response_AsmedIGD.TBmGCS : ''} \n` : ''

            text2 += '\nStatus Generalis \n'
            text2 += response_AsmedIGD.TAKepalaSG ? `Kepala : ${response_AsmedIGD.TAKepalaSG}\n` : 'Kepala : -\n'
            text2 += 'Mata \n'
            text2 += response_AsmedIGD.TBAnemisMata ? `Anemis : ${response_AsmedIGD.TBAnemisMata}\n` : 'Anemis : -'
            text2 += response_AsmedIGD.TBIkterusMata ? `Ikterus : ${response_AsmedIGD.TBIkterusMata}\n` : 'Ikterus : -'
            text2 += response_AsmedIGD.TBRefleksPupilMata ? `Refleks Pupil : ${response_AsmedIGD.TBRefleksPupilMata}\n` : 'Refleks Pupil : -'
            text2 += response_AsmedIGD.TBOedemaPalpebraeMata ? `Oedema Palpebrae : ${response_AsmedIGD.TBOedemaPalpebraeMata}\n` : 'Oedema Palpebrae : -'
            text2 += 'THT \n'
            text2 += response_AsmedIGD.TBTonsilTHT ? `Tonsil : ${response_AsmedIGD.TBTonsilTHT}\n` : 'Tonsil : -\n'
            text2 += response_AsmedIGD.TBPharingTHT ? `Pharing : ${response_AsmedIGD.TBPharingTHT}\n` : 'Pharing : -\n'
            text2 += response_AsmedIGD.TBTelingaTHT ? `Telinga : ${response_AsmedIGD.TBTelingaTHT}\n` : 'Telinga : -\n'
            text2 += response_AsmedIGD.TBHidungTHT ? `Hidung : ${response_AsmedIGD.TBHidungTHT}\n` : 'Hidung : -\n'
            text2 += response_AsmedIGD.TBBibirTHT ? `Bibir : ${response_AsmedIGD.TBBibirTHT}\n` : 'Bibir : -\n'
            text2 += response_AsmedIGD.TBLainnyaTHT ? `Lainnya : ${response_AsmedIGD.TBLainnyaTHT}\n` : 'Lainnya : -\n'
            text2 += 'Leher \n'
            text2 += response_AsmedIGD.TBJVPLeher ? `JVP : ${response_AsmedIGD.TBJVPLeher}\n` : 'JVP : -\n'
            text2 += response_AsmedIGD.TBPembesaranKelenjarLeher ? `Pembesaran Kelenjar : ${response_AsmedIGD.TBPembesaranKelenjarLeher}\n` : 'Pharing : -\n'
            text2 += response_AsmedIGD.CBKakuKudukLeher ? `Kaku Duduk : Ya\n` : 'Kaku Duduk : Tidak\n'
            text2 += `Thoraks : ${response_AsmedIGD.CBSimetrisThoraks ? response_AsmedIGD.CBSimetrisThoraks : (response_AsmedIGD.CBAsimetrisThoraks ? response_AsmedIGD.CBAsimetrisThoraks : '')}, ${response_AsmedIGD.TBSimetrisORAsimetrisThoraks ? response_AsmedIGD.TBSimetrisORAsimetrisThoraks : ''} \n`
            text2 += response_AsmedIGD.TBRetraksiThoraks ? `Retraksi : ${response_AsmedIGD.TBRetraksiThoraks}\n` : 'Retraksi : -\n'
            text2 += 'Cor \n'
            text2 += response_AsmedIGD.TBS1S2Cor ? `S1, S2 : ${response_AsmedIGD.TBS1S2Cor}, ${response_AsmedIGD.CBRegulerCor ? response_AsmedIGD.CBRegulerCor : (response_AsmedIGD.CBIregulerCor ? response_AsmedIGD.CBIregulerCor : '')} \n` : `S1, S2 : -, ${(response_AsmedIGD.CBIregulerCor ? response_AsmedIGD.CBIregulerCor : '')}\n`
            text2 += response_AsmedIGD.TBMurmurCor ? `Murmur : ${response_AsmedIGD.TBMurmurCor}\n` : 'Murmur : -\n'
            text2 += response_AsmedIGD.TBLainLainCor ? `Lain-lain : ${response_AsmedIGD.TBLainLainCor}\n` : 'Lain-lain : -\n'
            text2 += 'Pulmo \n'
            text2 += response_AsmedIGD.TBRonchiPulmo ? `Ronchi : ${response_AsmedIGD.TBRonchiPulmo}\n` : 'Ronchi : -\n'
            text2 += response_AsmedIGD.TBWheezingPulmo ? `Wheezing : ${response_AsmedIGD.TBWheezingPulmo}\n` : 'Wheezing : -\n'
            text2 += response_AsmedIGD.TBVesikulerPulmo ? `Vesikuler : ${response_AsmedIGD.TBVesikulerPulmo}\n` : 'Vesikuler : -\n'
            text2 += response_AsmedIGD.TBLainnyaPulmo ? `Lainnya : ${response_AsmedIGD.TBLainnyaPulmo}\n` : 'Lainnya : -\n'
            text2 += `Abdomen : ${response_AsmedIGD.CBSouffleAbdomen ? response_AsmedIGD.CBSouffleAbdomen + ',' : ''}${response_AsmedIGD.CBDistensiAbdomen ? response_AsmedIGD.CBDistensiAbdomen + ',' : ''}${response_AsmedIGD.CBMeteorismusAbdomen ? response_AsmedIGD.CBMeteorismusAbdomen : ''} \n`
            text2 += `Peristaltik : ${response_AsmedIGD.CBNormalPeristaltik ? response_AsmedIGD.CBNormalPeristaltik + ',' : ''}${response_AsmedIGD.CBMeningkatPeristaltik ? response_AsmedIGD.CBMeningkatPeristaltik + ',' : ''}${response_AsmedIGD.CBMenurunPeristaltik ? response_AsmedIGD.CBMenurunPeristaltik + ',' : ''}${response_AsmedIGD.CBAscitesPeristaltik ? response_AsmedIGD.CBAscitesPeristaltik : ''} \n`
            text2 += response_AsmedIGD.TBNyeriTekanLokasiPeristaltik ? `Nyeri tekan lokasi : ${response_AsmedIGD.TBNyeriTekanLokasiPeristaltik}\n` : 'Nyeri tekan lokasi : -\n'
            text2 += response_AsmedIGD.TBHeparPeristaltik ? `Hepar : ${response_AsmedIGD.TBHeparPeristaltik}\n` : 'Hepar : -\n'
            text2 += response_AsmedIGD.TBLienPeristaltik ? `Lien : ${response_AsmedIGD.TBLienPeristaltik}\n` : 'Lien : -\n'
            text2 += `Extrimitas : ${response_AsmedIGD.CBHangatExtremitas ? response_AsmedIGD.CBHangatExtremitas + ',' : ''}${response_AsmedIGD.CBDinginExtremitas ? response_AsmedIGD.CBDinginExtremitas : ''} \n`
            text2 += response_AsmedIGD.TBOdemaExtremitas ? `Odema : ${response_AsmedIGD.TBOdemaExtremitas}\n` : 'Odema : -\n'
            text2 += response_AsmedIGD.TBLainlainExtremitas ? `Lain-lain : ${response_AsmedIGD.TBLainlainExtremitas}\n` : 'Lain-lain : -\n'
            text2 += `Lain-lain : ${response_AsmedIGD.TBLainlainSG ? response_AsmedIGD.TBLainlainSG : ''}`

            input.value.pemeriksaanfisik = text2
            input.value.diagnosapasien = response_AsmedIGD.TADiagnosis ? response_AsmedIGD.TADiagnosis : ''
            input.value.riwayatpenyait = response_AsmedIGD.TARPS ? response_AsmedIGD.TARPS : ''
            input.value.penunjang = response_AsmedIGD.TArpp ? response_AsmedIGD.TArpp : ''
            // input.value.diagnosapasien = response_AsmedIGD.TArpp
        }
    }
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    object.nocm = pasien.value.nocm

    object["TTDDokter"] = H.tandaTangan().get("TTDDokter");
    object["TTDMenyetujui"] = H.tandaTangan().get("TTDMenyetujui");
    object["TTDPegawaiSerah"] = H.tandaTangan().get("TTDPegawaiSerah");
    object["TTDPegawaiTerima"] = H.tandaTangan().get("TTDPegawaiTerima");
    object.pasien = H.setObjectPasien(pasien.value)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    if (object.hasOwnProperty('namatemplate')) {
        delete object.namatemplate
    }
    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'url_form': route.name,
        'name_form': 'Rujukan Pasien',
        'jenis_emr': 'asesmen_medis',
        'data': object
    }

    isLoading.value = true
    useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
        isLoading.value = false
        NOREC_EMRPASIEN.value = response.norec_emr
        input.value.id = response.id;
        loadRiwayat();
    }).catch((e: any) => {
        isLoading.value = false
    })
}
const fetchPasien = () => {
    pasien.value = props.pasien
    pasien.value.registrasi = props.registrasi
    NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}
const fetchPegawai = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => { d_Pegawai.value = response })
}
const fetchDokter = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`).then((response) => { d_Dokter.value = response })
}

onBeforeMount(async () => {
    try {
        await loadRiwayat()
        await fetchPasien()
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

// const simpanTemplate = () => {
//     let ID = input.id ? input.id : ''
//     let object: any = {}

//     object = input.value
//     object.nocm = pasien.value.nocm

//     object.pasien = H.setObjectPasien(pasien.value)
//     object.registrasi = H.setObjectRegistrasi(props.registrasi)
//     let json = {
//         'id': ID,
//         'norec_emr': NOREC_EMRPASIEN.value,
//         'collection': COLLECTION.value,
//         'url_form': props.FORM_URL,
//         'name_form': props.FORM_NAME,
//         'jenis_emr': 'asesmen_medis',
//         'data': object
//     }
//     isLoading.value = true

//     useApi().post(`/emr/simpan-emr-template`, json).then((response: any) => {
//         isLoading.value = false
//     }).catch((e: any) => {
//         isLoading.value = false
//     })
// }
// const pilihTemplate = async (index: any) => {
//     isLoading.value = true
//     useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
//         isLoading.value = false
//         if (responselast.length) {
//             listTemplate.value = responselast //set ke inputan
//             showModalTemplate.value = true
//         } else {
//             H.alert('warning', 'Data tidak ada')
//         }
//     })
// }
// const addTemplate = (response: any) => {
//     input.value = response //set ke inputan
//     input.value.namatemplate = null
// }
// const pilihTemplateFix = async (index: any) => {
//     isLoading.value = true
//     useApi().get(`/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
//         isLoading.value = false
//         if (responselast.length) {
//             for (var x = 0; x < responselast.length; x++) {
//                 responselast[x].no = x + 1
//                 responselast[x].id = ''
//             }
//             listTemplateFix.value = responselast //set ke inputan
//             showModalTemplateFix.value = true
//         } else {
//             H.alert('warning', 'Data tidak ada')
//         }
//     })
// }

// ===== ARRAY =====
// const d_perluTidakPerlu: any = ref([
//     { value: 1, label: 'Perlu' },
//     { value: 2, label: 'Tidak Perlu' }
// ])
// const d_tidakAda: any = ref([
//     { value: 1, label: 'Tidak' },
//     { value: 2, label: 'Ada' }
// ])
// const d_tidakYa: any = ref([
//     { value: 1, label: 'Tidak' },
//     { value: 2, label: 'Ya' }
// ])
// const d_yaTidak: any = ref([
//     { value: 1, label: 'Ya' },
//     { value: 2, label: 'Tidak' }
// ])
// const d_tidakAda_ada: any = ref([
//     { value: 1, label: 'Tidak' },
//     { value: 2, label: 'Ya' }
// ])
</script>