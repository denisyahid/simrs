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
                            @simpan="simpan" @kembaliKeun="kembaliKeun" isHideCetak isHideST></ButtonEmr>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="column is-12">
        <VCard>
            <TabView :scrollable="true" class="skuy">
                <TabPanel header="SEBELUM INDUKSI ANESTESI SIGN IN">
                    <div class="column is-12">
                      <VCard>
                        <div class="column is-12" v-for="(datas) in signIn">
                            <h1 class="emr">{{ datas.title }}</h1>
                            <div class="columns is-multiline">
                                <div class="column" :class="`is-${data.column}`" v-for="(data) in datas.value">
                                    <VField v-if="data.type == 'textarea'">
                                        <VControl>
                                            <VTextarea v-model="input[data.model]" rows="3">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                    <VField v-if="data.type == 'checkBox'">
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input[data.model]" square :true-value="data.subTitle"
                                                :label="data.subTitle" class="pb-0" color="primary" circle />
                                        </VControl>
                                    </VField>
                                    <VField v-else-if="data.type == 'label'">
                                      <div v-html="data.label" class="mt-2">
                
                                      </div>
                                    </VField>
                                    <VField v-else-if="data.type == 'kosong'">
                                    </VField>
                                    <VField v-else-if="data.type == 'dateTime'">
                                      <VDatePicker v-model="input[data.model]" mode="datetime" trim-weeks :max-date="new Date()">
                                          <template #default="{ inputValue, inputEvents }">
                                              <VControl icon="feather:calendar" fullwidth>
                                                  <VInput :value="inputValue" v-on="inputEvents" />
                                              </VControl>
                                          </template>
                                      </VDatePicker>
                                    </VField>
                                    <VField v-if="data.type == 'checkBoxText'">
                                      <div class="columns is-multiline">
                                        <div class="column" :class="`is-${data.column1}`">
                                          <VControl raw subcontrol>
                                              <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                                  :label="data.subTitle" class="pb-0" color="primary" circle />
                                          </VControl>
                                        </div>
                                        <div class="column" :class="`is-${data.column2}`">
                                          <VField>
                                              <VControl>
                                                  <VInput type="text" class="input" v-model="input[data.model2]" />
                                              </VControl>
                                          </VField>
                                        </div>
                                      </div>
                                    </VField>
                                    <VField v-else-if="data.type == 'textBox'" class="p-0">
                                      <div class="columns is-multiline">
                                        <div class="column mt-2" :class="`is-${data.column1}`">
                                          {{ data.subTitle }}
                                        </div>
                                        <div class="column" :class="`is-${data.column2}`">
                                          <VControl raw subcontrol>
                                              <input v-model="input[data.model]" class="input p-0" />
                                          </VControl>
                                        </div>
                                      </div>
                                    </VField>
                                </div>
                            </div>    
                        </div>
                        <div class="column is-12">
                          <table class="table is-bordered is-fullwidth">
                            <tr>
                                <th class="has-text-centered">
                                    TIM
                                </th>
                                <th class="has-text-centered">
                                    NAMA
                                </th>
                                <th class="has-text-centered">
                                    TTD
                                </th>
                            </tr>
                            <tr>
                                <td class="has-text-left">
                                    Dr. Anastesi
                                </td>
                                <td class="has-text-centered">
                                    <div class="column is-12">
                                      <VControl class="prime-auto">
                                          <AutoComplete v-model="input.dokterAnastesiSignIn" :suggestions="d_Dokter"
                                              @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                              class="mt-2" />
                                      </VControl>
                                    </div>
                                </td>
                                <td class="has-text-centered">
                                    <TandaTangan :elemenID="'dokterAnastesiSignInTTD'" :width="'150'" :height="'150'" class="dek" />
                                </td>
                            </tr>
                            <tr>
                                <td class="has-text-left">
                                    Perawat Sirkuler
                                </td>
                                <td class="has-text-centered">
                                    <div class="column is-12">
                                      <VControl class="prime-auto">
                                          <AutoComplete v-model="input.perawatSirkulerSignIn" :suggestions="d_Pegawai"
                                              @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                              class="mt-2" />
                                      </VControl>
                                    </div>
                                </td>
                                <td class="has-text-centered">
                                    <TandaTangan :elemenID="'perawatSirkulerSignInTTD'" :width="'150'" :height="'150'" class="dek" />
                                </td>
                            </tr>
                            <tr>
                                <td class="has-text-left">
                                    Perawat Anestesi
                                </td>
                                <td class="has-text-centered">
                                    <div class="column is-12">
                                      <VControl class="prime-auto">
                                          <AutoComplete v-model="input.perawatAnestesiSignIn" :suggestions="d_Pegawai"
                                              @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                              class="mt-2" />
                                      </VControl>
                                    </div>
                                </td>
                                <td class="has-text-centered">
                                    <TandaTangan :elemenID="'perawatAnestesiSignInTTD'" :width="'150'" :height="'150'" class="dek" />
                                </td>
                            </tr>
                          </table>
                        </div>
                      </VCard>
                    </div>
                </TabPanel>
                <TabPanel header="SEBELUM INSISI TIME OUT">
                    <div class="column is-12">
                        <VCard>
                            <div class="column is-12" v-for="(datas) in timeOut">
                                <h1 class="emr">{{ datas.title }}</h1>
                                <div class="columns is-multiline">
                                    <div class="column" :class="`is-${data.column}`" v-for="(data) in datas.value">
                                        <VField v-if="data.type == 'textarea'">
                                            <VControl>
                                                <VTextarea v-model="input[data.model]" rows="3">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                        <VField v-if="data.type == 'checkBox'">
                                            <VControl raw subcontrol>
                                                <VCheckbox v-model="input[data.model]" square :true-value="data.subTitle"
                                                    :label="data.subTitle" class="pb-0" color="primary" circle />
                                            </VControl>
                                        </VField>
                                        <VField v-else-if="data.type == 'label'">
                                          <div v-html="data.label" class="mt-2">
                    
                                          </div>
                                        </VField>
                                        <VField v-else-if="data.type == 'kosong'">
                                        </VField>
                                        <VField v-else-if="data.type == 'dateTime'">
                                          <VDatePicker v-model="input[data.model]" mode="datetime" trim-weeks :max-date="new Date()">
                                              <template #default="{ inputValue, inputEvents }">
                                                  <VControl icon="feather:calendar" fullwidth>
                                                      <VInput :value="inputValue" v-on="inputEvents" />
                                                  </VControl>
                                              </template>
                                          </VDatePicker>
                                        </VField>
                                        <VField v-if="data.type == 'checkBoxText'">
                                          <div class="columns is-multiline">
                                            <div class="column" :class="`is-${data.column1}`">
                                              <VControl raw subcontrol>
                                                  <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                                      :label="data.subTitle" class="pb-0" color="primary" circle />
                                              </VControl>
                                            </div>
                                            <div class="column" :class="`is-${data.column2}`">
                                              <VField>
                                                  <VControl>
                                                      <VInput type="text" class="input" v-model="input[data.model2]" />
                                                  </VControl>
                                              </VField>
                                            </div>
                                          </div>
                                        </VField>
                                        <VField v-else-if="data.type == 'textBox'" class="p-0">
                                          <div class="columns is-multiline">
                                            <div class="column mt-2" :class="`is-${data.column1}`">
                                              {{ data.subTitle }}
                                            </div>
                                            <div class="column" :class="`is-${data.column2}`">
                                              <VControl raw subcontrol>
                                                  <input v-model="input[data.model]" class="input p-0" />
                                              </VControl>
                                            </div>
                                          </div>
                                        </VField>
                                    </div>
                                </div>    
                            </div>
                            <div class="column is-12">
                              <table class="table is-bordered is-fullwidth">
                                <tr>
                                    <th class="has-text-centered">
                                        TIM
                                    </th>
                                    <th class="has-text-centered">
                                        NAMA
                                    </th>
                                    <th class="has-text-centered">
                                        TTD
                                    </th>
                                </tr>
                                <tr>
                                    <td class="has-text-left">
                                        Perawat Sirkuler
                                    </td>
                                    <td class="has-text-centered">
                                        <VControl class="prime-auto">
                                            <AutoComplete v-model="input.TOPerawatSirkuler" :suggestions="d_Pegawai"
                                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                class="mt-2" />
                                        </VControl>
                                    </td>
                                    <td class="has-text-centered">
                                        <TandaTangan :elemenID="'TOPerawatSirkulerTTD'" :width="'150'" :height="'150'" class="dek" />
                                    </td>
                                </tr>

                              </table>
                            </div>
                        </VCard>
                      </div>
                </TabPanel>
                <TabPanel header="SEBELUM PENUTUPAN LUKA OPERASI SIGN OUT">
                    <div class="column is-12">
                        <VCard>
                            <div class="column is-12" v-for="(datas) in signOut">
                                <h1 class="emr">{{ datas.title }}</h1>
                                <div class="columns is-multiline">
                                    <div class="column" :class="`is-${data.column}`" v-for="(data) in datas.value">
                                        <VField v-if="data.type == 'textarea'">
                                            <VControl>
                                                <VTextarea v-model="input[data.model]" rows="3">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                        <VField v-if="data.type == 'checkBox'">
                                            <VControl raw subcontrol>
                                                <VCheckbox v-model="input[data.model]" square :true-value="data.subTitle"
                                                    :label="data.subTitle" class="pb-0" color="primary" circle />
                                            </VControl>
                                        </VField>
                                        <VField v-else-if="data.type == 'label'">
                                          <div v-html="data.label" class="mt-2">
                    
                                          </div>
                                        </VField>
                                        <VField v-else-if="data.type == 'kosong'">
                                        </VField>
                                        <VField v-else-if="data.type == 'dateTime'">
                                          <VDatePicker v-model="input[data.model]" mode="datetime" trim-weeks :max-date="new Date()">
                                              <template #default="{ inputValue, inputEvents }">
                                                  <VControl icon="feather:calendar" fullwidth>
                                                      <VInput :value="inputValue" v-on="inputEvents" />
                                                  </VControl>
                                              </template>
                                          </VDatePicker>
                                        </VField>
                                        <VField v-if="data.type == 'checkBoxText'">
                                          <div class="columns is-multiline">
                                            <div class="column" :class="`is-${data.column1}`">
                                              <VControl raw subcontrol>
                                                  <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                                      :label="data.subTitle" class="pb-0" color="primary" circle />
                                              </VControl>
                                            </div>
                                            <div class="column" :class="`is-${data.column2}`">
                                              <VField>
                                                  <VControl>
                                                      <VInput type="text" class="input" v-model="input[data.model2]" />
                                                  </VControl>
                                              </VField>
                                            </div>
                                          </div>
                                        </VField>
                                        <VField v-else-if="data.type == 'textBox'" class="p-0">
                                          <div class="columns is-multiline">
                                            <div class="column mt-2" :class="`is-${data.column1}`">
                                              {{ data.subTitle }}
                                            </div>
                                            <div class="column" :class="`is-${data.column2}`">
                                              <VControl raw subcontrol>
                                                  <input v-model="input[data.model]" class="input p-0" />
                                              </VControl>
                                            </div>
                                          </div>
                                        </VField>
                                    </div>
                                </div>    
                            </div>
                            <div class="column is-12">
                              <table class="table is-bordered is-fullwidth">
                                <tr>
                                    <th class="has-text-centered">
                                        TIM
                                    </th>
                                    <th class="has-text-centered">
                                        NAMA
                                    </th>
                                    <th class="has-text-centered">
                                        TTD
                                    </th>
                                </tr>
                                <tr>
                                    <td class="has-text-left">
                                        Dr. Bedah Operator
                                    </td>
                                    <td class="has-text-centered">
                                        <VControl class="prime-auto">
                                            <AutoComplete v-model="input.SODokterBedahOperator" :suggestions="d_Dokter"
                                                @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                class="mt-2" />
                                        </VControl>
                                    </td>
                                    <td class="has-text-centered">
                                        <TandaTangan :elemenID="'SODokterBedahOperatorTTD'" :width="'150'" :height="'150'" class="dek" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="has-text-left">
                                        Asisten Operator
                                    </td>
                                    <td class="has-text-centered">
                                        <VControl class="prime-auto">
                                            <AutoComplete v-model="input.SOAsistenOperator" :suggestions="d_Pegawai"
                                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                class="mt-2" />
                                        </VControl>
                                    </td>
                                    <td class="has-text-centered">
                                        <TandaTangan :elemenID="'SOAsistenOperatorTTD'" :width="'150'" :height="'150'" class="dek" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="has-text-left">
                                        Perawat Instrumen
                                    </td>
                                    <td class="has-text-centered">
                                        <VControl class="prime-auto">
                                            <AutoComplete v-model="input.SOPerawatInstrumen" :suggestions="d_Pegawai"
                                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                class="mt-2" />
                                        </VControl>
                                    </td>
                                    <td class="has-text-centered">
                                        <TandaTangan :elemenID="'SOPerawatInstrumenTTD'" :width="'150'" :height="'150'" class="dek" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="has-text-left">
                                        Dr. Anestesi
                                    </td>
                                    <td class="has-text-centered">
                                        <VControl class="prime-auto">
                                            <AutoComplete v-model="input.SODokterAnestesi" :suggestions="d_Dokter"
                                                @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                class="mt-2" />
                                        </VControl>
                                    </td>
                                    <td class="has-text-centered">
                                        <TandaTangan :elemenID="'SODokterAnestesiTTD'" :width="'150'" :height="'150'" class="dek" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="has-text-left">
                                        Perawat Sirkuler
                                    </td>
                                    <td class="has-text-centered">
                                        <VControl class="prime-auto">
                                            <AutoComplete v-model="input.SOPerawatSirkuler" :suggestions="d_Pegawai"
                                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                class="mt-2" />
                                        </VControl>
                                    </td>
                                    <td class="has-text-centered">
                                        <TandaTangan :elemenID="'SOPerawatSirkulerTTD'" :width="'150'" :height="'150'" class="dek" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="has-text-left">
                                        Perawat Anestesi
                                    </td>
                                    <td class="has-text-centered">
                                        <VControl class="prime-auto">
                                            <AutoComplete v-model="input.SOPerawatAnestesi" :suggestions="d_Pegawai"
                                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                class="mt-2" />
                                        </VControl>
                                    </td>
                                    <td class="has-text-centered">
                                        <TandaTangan :elemenID="'SOPerawatAnestesiTTD'" :width="'150'" :height="'150'" class="dek" />
                                    </td>
                                </tr>
                              </table>
                            </div>
                        </VCard>
                      </div>
                </TabPanel>
            </TabView>

        </VCard>
    </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/signin-timeout-signout-fix'


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
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_Dokter: any = ref([])
const d_Pegawai: any = ref([])
const dataTTD: any = ref([])  
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref('ChecklistKeselamatanPasienOperasi') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    tanggalDiberikan: new Date(),
    tglVerifikasi: new Date(),
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
                dataTTD.value = response[0]
                H.tandaTangan().set("dokterAnastesiSignInTTD", dataTTD.value.dokterAnastesiSignInTTD)
                H.tandaTangan().set("perawatSirkulerSignInTTD", dataTTD.value.perawatSirkulerSignInTTD)
                H.tandaTangan().set("perawatAnestesiSignInTTD", dataTTD.value.perawatAnestesiSignInTTD)
                H.tandaTangan().set("TOPerawatSirkulerTTD", dataTTD.value.TOPerawatSirkulerTTD)
                H.tandaTangan().set("SODokterBedahOperatorTTD", dataTTD.value.SODokterBedahOperatorTTD)
                H.tandaTangan().set("SOAsistenOperatorTTD", dataTTD.value.SOAsistenOperatorTTD)
                H.tandaTangan().set("SOPerawatInstrumenTTD", dataTTD.value.SOPerawatInstrumenTTD)
                H.tandaTangan().set("SODokterAnestesiTTD", dataTTD.value.SODokterAnestesiTTD)
                H.tandaTangan().set("SOPerawatSirkulerTTD", dataTTD.value.SOPerawatSirkulerTTD)
                H.tandaTangan().set("SOPerawatAnestesiTTD", dataTTD.value.SOPerawatAnestesiTTD)
            }
        })
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object['dokterAnastesiSignInTTD'] = H.tandaTangan().get("dokterAnastesiSignInTTD");
    object['perawatSirkulerSignInTTD'] = H.tandaTangan().get("perawatSirkulerSignInTTD");
    object['perawatAnestesiSignInTTD'] = H.tandaTangan().get("perawatAnestesiSignInTTD");
    object['TOPerawatSirkulerTTD'] = H.tandaTangan().get("TOPerawatSirkulerTTD");
    object['SODokterBedahOperatorTTD'] = H.tandaTangan().get("SODokterBedahOperatorTTD");
    object['SOAsistenOperatorTTD'] = H.tandaTangan().get("SOAsistenOperatorTTD");
    object['SOPerawatInstrumenTTD'] = H.tandaTangan().get("SOPerawatInstrumenTTD");
    object['SODokterAnestesiTTD'] = H.tandaTangan().get("SODokterAnestesiTTD");
    object['SOPerawatSirkulerTTD'] = H.tandaTangan().get("SOPerawatSirkulerTTD");
    object['SOPerawatAnestesiTTD'] = H.tandaTangan().get("SOPerawatAnestesiTTD");
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
    input.value.tanggalLahirPasien = props.pasien.tgllahir
}
const fetchPegawai = async (filter: any) => {
  
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
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
