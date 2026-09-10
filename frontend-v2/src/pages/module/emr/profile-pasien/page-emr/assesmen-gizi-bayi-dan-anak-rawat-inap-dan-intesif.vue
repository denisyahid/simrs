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
              @simpan="simpan" @kembaliKeun="kembaliKeun" isHideST isHideCetak></ButtonEmr>
          </div>
        </div>
      </div>

      <div class="columns is-multiline p-2">
        <!-- daftar-pemberian-obat-infus-pasien -->
        <!-- form emr -->
        <div class="column is-12">
          <Vcard>
            <div class="columns is-multiline">
              <div class="column is-12">
                <Fieldset :toggleable="true" legend="ANTROPOMETRI">
                  <div class="column is-12" v-for="(data) in ANTROPOMETRI">
                    <h1>{{ data.title }}</h1>
                    <div class="columns is-multiline pl-3 pr-3">
                      <div class="column pt-0 is-one-quarter" v-for="(detail) in data.value">
                        <p>{{ detail.subTitle }}</p>
                        <VField addons v-if="detail.type == 'textfiled'">
                          <VControl>
                            <VInput type="text" class="input" v-model="input[detail.model]" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>{{ detail.satuan }}</VButton>
                          </VControl>
                        </VField>

                        <VField v-else-if="detail.type == 'textbox'">
                          <VControl>
                            <input v-model="input[detail.model]" class="input" placeholder="..." />
                          </VControl>
                        </VField>

                        <VField v-else>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input[detail.model]" :true-value="detail.satuan" :label="detail.satuan"
                              color="primary" circle />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                </Fieldset>
                <Fieldset :toggleable="true" legend="BIOKIMIA">
                  <div class="column is-12" v-for="(data) in BIOKIMIA">
                    <h1>{{ data.title }}</h1>
                    <div class="columns is-multiline pl-3 pr-3">
                      <div class="column pt-0 is-one-quarter" v-for="(detail) in data.value">
                        <p>{{ detail.subTitle }}</p>
                        <VField addons v-if="detail.type == 'textfiled'">
                          <VControl>
                            <VInput type="text" class="input" v-model="input[detail.model]" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>{{ detail.satuan }}</VButton>
                          </VControl>
                        </VField>

                        <VField v-else-if="detail.type == 'textbox'">
                          <VControl>
                            <input v-model="input[detail.model]" class="input" placeholder="..." />
                          </VControl>
                        </VField>

                        <VField v-else>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input[detail.model]" :true-value="detail.satuan" :label="detail.satuan"
                              color="primary" circle class="p-0" />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                </Fieldset>
                <Fieldset :toggleable="true" legend="KLINIS">
                  <div class="column is-12 p-1" v-for="(data) in KLINIS">
                    <h1>{{ data.title }}</h1>
                    <div class="columns is-multiline pl-3 pr-3">
                      <div class="column p-2 is-one-quarter" v-for="(detail) in data.value">
                        <div v-if="detail.type == 'textfiled'">
                          <h1>{{ detail.subTitle }}</h1>
                          <div class="column p-0">
                            <VField addons>
                              <VControl>
                                <VInput type="text" class="input" v-model="input[detail.model]" />
                              </VControl>
                              <VControl class="field-addon-body">
                                <VButton static>{{ detail.satuan }}</VButton>
                              </VControl>
                            </VField>
                          </div>
                        </div>

                        <VField v-else-if="detail.type == 'textbox'">
                          <h1>{{ detail.subTitle }}</h1>
                          <VControl>
                            <input v-model="input[detail.model]" class="input" placeholder="..." />
                          </VControl>
                        </VField>

                        <VField v-else>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input[detail.model]" :true-value="detail.subTitle"
                              :label="detail.subTitle" color="primary" circle class="p-0" />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                </Fieldset>
                <Fieldset :toggleable="true" legend="DIETARY: Food Recall 24jam">
                  <div class="column is-12 p-1" v-for="(data) in DIETARY">
                    <h1>{{ data.title }}</h1>
                    <div class="columns is-multiline pl-3 pr-3">
                      <div class="column pt-0 is-one-quarter" v-for="(detail) in data.value">
                        <div v-if="detail.type == 'textfiled'">
                          <h1>{{ detail.subTitle }}</h1>
                          <div class="column p-0">
                            <VField addons>
                              <VControl>
                                <VInput type="text" class="input" v-model="input[detail.model]" />
                              </VControl>
                              <VControl class="field-addon-body">
                                <VButton static>{{ detail.satuan }}</VButton>
                              </VControl>
                            </VField>
                          </div>
                        </div>

                        <VField v-else-if="detail.type == 'textbox'">
                          <h1>{{ detail.subTitle }}</h1>
                          <VControl>
                            <input v-model="input[detail.model]" class="input" placeholder="..." />
                          </VControl>
                        </VField>

                        <VField v-else>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input[detail.model]" :true-value="detail.subTitle"
                              :label="detail.subTitle" color="primary" circle class="p-0" />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                  <div class="columns is-multiline mt-0 ml-0">
                    <div class="column is-3">
                      <p>Asupan makan oral di rumah</p>
                      <div class="columns is-multiline" style="margin-top: -5px">
                        <div class="column is-6">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="80% (Kurang)" label="80% (Kurang)"
                              v-model="input.kurang_oral" />
                          </VControl>
                          <!-- <VControl expanded>
                            <input type="checkbox" v-model="input.kurang_oral" id="checkbox-kurang-oral" />
                            <label for="checkbox-kurang-oral"><span>&lt; 80% (kurang)</span></label>
                          </VControl> -->
                        </div>
                        <div class="column is-6">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="80% (Baik)" label="80% (Baik)"
                              v-model="input.baik_oral" />
                          </VControl>
                          <!-- <VControl expanded>
                            <input type="checkbox" v-model="input.baik_oral" id="checkbox-baik-oral" />
                            <label for="checkbox-baik-oral"><span>&gt;= 80% (baik)</span></label>
                          </VControl> -->
                        </div>
                      </div>
                    </div>
                    <div class="column is-3">
                      <p>Asupan makan awal di rumah sakit</p>
                      <div class="columns is-multiline" style="margin-top: -5px">
                        <div class="column is-6">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="80% (Kurang)" label="80% (Kurang)"
                              v-model="input.kurang_awal" />
                          </VControl>
                        </div>
                        <div class="column is-6">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="80% (Baik)" label="80% (Baik)"
                              v-model="input.baik_awal" />
                          </VControl>
                        </div>
                      </div>
                    </div>
                    <div class="column is-3">
                      <p>Pantangan makanan</p>
                      <div class="columns is-multiline" style="margin-top: -5px">
                        <div class="column is-6">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Tidak Ada" label="Tidak Ada"
                              v-model="input.TidakAda_PM" />
                          </VControl>
                        </div>
                        <div class="column is-6">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Ada" label="Ada"
                              v-model="input.Ada_PM" />
                          </VControl>
                        </div>
                      </div>
                    </div>
                    <div class="column is-12 pt-1">
                      <p>Alergi Makanan</p>
                      <div class="columns is-multiline" style="margin-top: -5px">
                        <div class="column is-2">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Tidak ada" label="Tidak ada"
                              v-model="input.TidakAda_AM" />
                          </VControl>
                        </div>
                        <div class="column is-2">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Telur" label="Telur"
                              v-model="input.Telur_AM" />
                          </VControl>
                        </div>
                        <div class="column is-2">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Susu Sapi" label="Susu Sapi"
                              v-model="input.SusuSapi_AM" />
                          </VControl>
                        </div>
                        <div class="column is-2">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Kacang Kedelai/Tanah"
                              label="Kacang Kedelai/Tanah" v-model="input.KacangKedelaiTanah_AM" />
                          </VControl>
                        </div>
                        <div class="column is-2">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Gluten/Gandum"
                              label="Gluten/Gandum" v-model="input.GlutenGandum_AM" />
                          </VControl>
                        </div>
                        <div class="column is-2">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Udang" label="Udang"
                              v-model="input.Udang_AM" />
                          </VControl>
                        </div>
                        <div class="column is-2 pt-0">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Ikan" label="Ikan"
                              v-model="input.Ikan_AM" />
                          </VControl>
                        </div>
                        <div class="column is-2 pt-0">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Hazelnut/Almond"
                              label="Hazelnut/Almond" v-model="input.HazelnutAlmond_AM" />
                          </VControl>
                        </div>
                        <div class="column is-2 pt-0">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Lain-lain" label="Lain-lain"
                              v-model="input.Lainlain_AM" />
                          </VControl>
                        </div>
                      </div>
                    </div>
                  </div>
                </Fieldset>
                <Fieldset :toggleable="true" legend="Diagnosa Klinis">
                  <VField>
                    <VTextarea v-model="input.diagnosa_klinis" class="input" placeholder="Diagnosa klinis.." rows="2" />
                  </VField>
                </Fieldset>
                <Fieldset :toggleable="true" legend="Diagnosa Nutrisi">
                  <div class="column is-12 p-1" v-for="(data) in DIAGNOSIS_NUTRISI">
                    <h1>{{ data.title }}</h1>
                    <div class="columns is-multiline pl-3 pr-3">
                      <div class="column p-2 is-one-quarter" v-for="(detail) in data.value">
                        <div v-if="detail.type == 'textfiled'">
                          <h1>{{ detail.subTitle }}</h1>
                          <div class="column p-0">
                            <VField addons>
                              <VControl>
                                <VInput type="text" class="input" v-model="input[detail.model]" />
                              </VControl>
                              <VControl class="field-addon-body">
                                <VButton static>{{ detail.satuan }}</VButton>
                              </VControl>
                            </VField>
                          </div>
                        </div>

                        <VField v-else-if="detail.type == 'textbox'">
                          <h1>{{ detail.subTitle }}</h1>
                          <VControl>
                            <input v-model="input[detail.model]" class="input" placeholder="..." />
                          </VControl>
                        </VField>

                        <VField v-else>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input[detail.model]" :true-value="detail.subTitle"
                              :label="detail.subTitle" color="primary" circle class="p-0" />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                </Fieldset>
                <Fieldset :toggleable="true" legend="KEBUTUHAN NUTRISI">
                  <div class="column is-12 p-1" v-for="(data) in KEBUTUHAN_NUTRISI">
                    <h1>{{ data.title }}</h1>
                    <div class="columns is-multiline pl-3 pr-3">
                      <div class="column p-2 is-one-quarter" v-for="(detail) in data.value">
                        <div v-if="detail.type == 'textfiled'">
                          <h1>{{ detail.subTitle }}</h1>
                          <div class="column p-0">
                            <VField addons>
                              <VControl>
                                <VInput type="text" class="input" v-model="input[detail.model]" />
                              </VControl>
                              <VControl class="field-addon-body">
                                <VButton static>{{ detail.satuan }}</VButton>
                              </VControl>
                            </VField>
                          </div>
                        </div>

                        <VField v-else-if="detail.type == 'textbox'">
                          <h1>{{ detail.subTitle }}</h1>
                          <VControl>
                            <input v-model="input[detail.model]" class="input" placeholder="..." />
                          </VControl>
                        </VField>

                        <VField v-else-if="detail.type == 'checkbox'">
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input[detail.model]" :true-value="detail.subTitle"
                              :label="detail.subTitle" color="primary" circle class="p-0" />
                          </VControl>
                        </VField>

                        <div v-else></div>
                      </div>
                    </div>
                  </div>
                </Fieldset>
                <Fieldset :toggleable="true" legend="JENIS NUTRISI">
                  <div class="column is-12 p-1" v-for="(data) in JENIS_NUTRISI">
                    <h1>{{ data.title }}</h1>
                    <div class="columns is-multiline pl-3 pr-3">
                      <div class="column p-2 is-one-quarter" v-for="(detail) in data.value">
                        <div v-if="detail.type == 'textfiled'">
                          <h1>{{ detail.subTitle }}</h1>
                          <div class="column p-0">
                            <VField addons>
                              <VControl>
                                <VInput type="text" class="input" v-model="input[detail.model]" />
                              </VControl>
                              <VControl class="field-addon-body">
                                <VButton static>{{ detail.satuan }}</VButton>
                              </VControl>
                            </VField>
                          </div>
                        </div>

                        <VField v-else-if="detail.type == 'textbox'">
                          <h1>{{ detail.subTitle }}</h1>
                          <VControl>
                            <input v-model="input[detail.model]" class="input" placeholder="..." />
                          </VControl>
                        </VField>

                        <VField v-else-if="detail.type == 'checkbox'">
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input[detail.model]" :true-value="detail.subTitle"
                              :label="detail.subTitle" color="primary" circle class="p-0" />
                          </VControl>
                        </VField>

                        <div v-else></div>
                      </div>
                    </div>
                  </div>
                </Fieldset>
                <Fieldset :toggleable="true" legend="CARA PEMBERIAN">
                  <div class="column is-12 p-1" v-for="(data) in CARA_PEMBERIAN">
                    <h1>{{ data.title }}</h1>
                    <div class="columns is-multiline pl-3 pr-3">
                      <div class="column p-2 is-one-quarter" v-for="(detail) in data.value">
                        <div v-if="detail.type == 'textfiled'">
                          <h1>{{ detail.subTitle }}</h1>
                          <div class="column p-0">
                            <VField addons>
                              <VControl>
                                <VInput type="text" class="input" v-model="input[detail.model]" />
                              </VControl>
                              <VControl class="field-addon-body">
                                <VButton static>{{ detail.satuan }}</VButton>
                              </VControl>
                            </VField>
                          </div>
                        </div>

                        <VField v-else-if="detail.type == 'textbox'">
                          <h1>{{ detail.subTitle }}</h1>
                          <VControl>
                            <input v-model="input[detail.model]" class="input" placeholder="..." />
                          </VControl>
                        </VField>

                        <VField v-else-if="detail.type == 'checkbox'">
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input[detail.model]" :true-value="detail.subTitle"
                              :label="detail.subTitle" color="primary" circle class="p-0" />
                          </VControl>
                        </VField>

                        <div v-else></div>
                      </div>
                    </div>
                  </div>
                </Fieldset>
                <Fieldset :toggleable="true" legend="EDUKASI/KONSULTASI GIZI">
                  <VField>
                    <VTextarea v-model="input.edukasi_konsultasi_gizi" class="input" :placeholder="'Isi edukasi...'"
                      rows="2" />
                  </VField>
                </Fieldset>
                <Fieldset :toggleable="true" legend="MONITORING">
                  <div class="column is-12 p-1" v-for="(data) in MONITORING">
                    <h1>{{ data.title }}</h1>
                    <div class="columns is-multiline pl-3 pr-3">
                      <div class="column p-2 is-one-quarter" v-for="(detail) in data.value">
                        <div v-if="detail.type == 'textfiled'">
                          <h1>{{ detail.subTitle }}</h1>
                          <div class="column p-0">
                            <VField addons>
                              <VControl>
                                <VInput type="text" class="input" v-model="input[detail.model]" />
                              </VControl>
                              <VControl class="field-addon-body">
                                <VButton static>{{ detail.satuan }}</VButton>
                              </VControl>
                            </VField>
                          </div>
                        </div>

                        <VField v-else-if="detail.type == 'textbox'">
                          <h1>{{ detail.subTitle }}</h1>
                          <VControl>
                            <input v-model="input[detail.model]" class="input" placeholder="..." />
                          </VControl>
                        </VField>

                        <VField v-else-if="detail.type == 'checkbox'">
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input[detail.model]" :true-value="detail.subTitle"
                              :label="detail.subTitle" color="primary" circle class="p-0" />
                          </VControl>
                        </VField>

                        <div v-else></div>
                      </div>
                    </div>
                  </div>
                </Fieldset>
              </div>
            </div>
            <div class="column is-12 pt-0 pb-0">
              <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
            </div>
            <div class="columns">
              <div class="column is-4" style="margin-left: auto;">
                <VField label="Garut">
                  <VDatePicker v-model="input.tanggal" mode="datetime" trim-weeks>
                    <template #default="{ inputValue, inputEvents }">
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                      </VControl>
                    </template>
                  </VDatePicker>
                </VField>
                <div class="column pt-0" style="text-align:center;">
                  <h1>Tanda Tangan</h1>
                  <TandaTangan :elemenID="'TTDPetugas'" :width="'150'" :height="'150'" class="dek" />
                  <VControl class="prime-auto">
                    <AutoComplete v-model="input.petugas" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                  </VControl>
                </div>
              </div>
            </div>
          </Vcard>
        </div>
      </div>
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
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'

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
    COLLECTION: 'AssesmenGiziBayiRawatInap',
  }
)
const user = useUserSession().getUser().pegawai;
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const dataTTD: any = ref([]);
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const loadRiwayat = () => {
  useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
    if (response.length) {
      input.value = response[0] //set ke inputan
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
      dataTTD.value = response[0]
      H.tandaTangan().set("TTDPetugas", dataTTD.value.TTDPetugas)
    } else {
      let d = input.value
      d.tanggal = new Date()
      d.petugas = { label: user.namaLengkap, value: user.id }
    }
  })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  if (object.hasOwnProperty('namatemplate')) {
    delete object.namatemplate
  }
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  object["TTDPetugas"] = H.tandaTangan().get("TTDPetugas");
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
  useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
    isLoading.value = false
    loadRiwayat()
  }).catch((e: any) => {
    isLoading.value = false
  })
}


const fetchPegawai = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => {
    d_Pegawai.value = response
  })
}
// const fetchPegawai = async (filter: any) => {

//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
//   ).then((response) => {
//     d_Pegawai.value = response
//   })
// }


// const fetchDokter = async (filter: any) => {
//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
//   ).then((response) => {
//     d_Dokter.value = response
//   })
// }

setView()
loadRiwayat()



let ANTROPOMETRI = ref([
  {
    "title": "",
    "value": [
      {
        "subTitle": "Berat badan lahir",
        "model": "beratBadanlahir",
        "type": "textfiled",
        "satuan": "gr"
      },
      {
        "subTitle": "Panjang badan lahir",
        "model": "panjangBadanLahir",
        "type": "textfiled",
        "satuan": "cm"
      },
      {
        "subTitle": "Berat badan sekarang",
        "model": "beratBadanSekarang",
        "type": "textfiled",
        "satuan": "gr"
      },
      {
        "subTitle": "Berat badan ideal",
        "model": "beratBadanIdeal",
        "type": "textfiled",
        "satuan": "gr"
      },
      {
        "subTitle": "Panjang badan (<2tahun)",
        "model": "panjangBadan(<2thn)",
        "type": "textfiled",
        "satuan": "cm"
      },
      {
        "subTitle": "Tinggi Badan (>2tahun)",
        "model": "tinggiBadan(>2tahun)",
        "type": "textfiled",
        "satuan": "cm"
      },
      {
        "subTitle": "Lingkar Kepala",
        "model": "lingkarKepala",
        "type": "textfiled",
        "satuan": "cm"
      },
      {
        "subTitle": "Lingkar Lengan Atas",
        "model": "lingkarLenganAtas",
        "type": "textfiled",
        "satuan": "cm",
      },
      {
        "subTitle": "Berat Badan/Umur",
        "model": "beratBadanUmur",
        "type": "textfiled",
      },
      {
        "subTitle": "Tinggi Badan/Umur",
        "model": "tinggiBadanUmur",
        "type": "textfiled",
      },
      {
        "subTitle": "Berat Badan/Tinggi Badan",
        "model": "beratBadanTinggiBadan",
        "type": "textfiled",
      },
      {
        "subTitle": "Lingkar lengan atas/Umur",
        "model": "lingkarLenganAtasUmur",
        "type": "textfiled",
      },
      {
        "subTitle": "Indek Masa Tubuh/Umur",
        "model": "indekMasaTubuhUmur",
        "type": "textfiled",
      },
    ],
  },

])
let BIOKIMIA = ref([
  {
    "title": "",
    "value": [
      {
        "subTitle": "Hb",
        "model": "hb",
        "type": "textfiled",
        "satuan": "gr/dl"
      },
      {
        "subTitle": "Limfosit",
        "model": "limfosit",
        "type": "textfiled",
        "satuan": "%"
      },
      {
        "subTitle": "Albumin",
        "model": "albumin",
        "type": "textfiled",
        "satuan": "g/dl"
      },
      {
        "subTitle": "Natrium",
        "model": "natrium",
        "type": "textfiled",
        "satuan": "g/dl"
      },
      {
        "subTitle": "Gula Darah",
        "model": "gulaDarah",
        "type": "textfiled",
        "satuan": "g/dl"
      },
      {
        "subTitle": "Keton Urin",
        "model": "ketonUrin)",
        "type": "textfiled",
      },
      {
        "subTitle": "Kalium",
        "model": "kalium",
        "type": "textfiled",
        "satuan": "g/dl"
      },
      {
        "subTitle": "Clorida",
        "model": "clorida",
        "type": "textfiled",
        "satuan": "g/dl",
      },
      {
        "subTitle": "Anion gap",
        "model": "anionGap",
        "type": "textfiled",
      },
      {
        "subTitle": "Lainnya",
        "model": "lainnyaBiokimia",
        "type": "textfiled",
      },
    ],
  },

])
let KLINIS = ref([
  {
    "title": "",
    "value": [
      {
        "subTitle": "Mual",
        "model": "mual",
        "type": "checkbox"
      },
      {
        "subTitle": "Muntah",
        "model": "muntah",
        "type": "checkbox"
      },
      {
        "subTitle": "Diare",
        "model": "diare",
        "type": "checkbox"
      },
      {
        "subTitle": "Sembelit",
        "model": "sembelit",
        "type": "checkbox"
      },
      {
        "subTitle": "Kesulitan mengunyah",
        "model": "kesulitanMengunyah",
        "type": "checkbox"
      },
      {
        "subTitle": "Kesulitan menelan",
        "model": "kesulitanMenelan",
        "type": "checkbox"
      },
      {
        "subTitle": "Mikrosefali",
        "model": "mikrosefail",
        "type": "checkbox"
      },
      {
        "subTitle": "Flag sign",
        "model": "falgSign",
        "type": "checkbox"
      },
      {
        "subTitle": "Old man face",
        "model": "oldManFace",
        "type": "checkbox"
      },
      {
        "subTitle": "Moon face",
        "model": "moonFace",
        "type": "checkbox"
      },
      {
        "subTitle": "Anemia",
        "model": "anemia",
        "type": "checkbox"
      },
      {
        "subTitle": "Bitot spot",
        "model": "bitotSpot",
        "type": "checkbox"
      },
      {
        "subTitle": "Papil lidah atropi",
        "model": "papilLidahAtropi",
        "type": "checkbox"
      },
      {
        "subTitle": "Iga gambang",
        "model": "igaGambang",
        "type": "checkbox"
      },
      {
        "subTitle": "Pembesar hati",
        "model": "pembesarHati",
        "type": "checkbox"
      },
      {
        "subTitle": "Baggy pant",
        "model": "baggyPant",
        "type": "checkbox"
      },
      {
        "subTitle": "Edema kaki",
        "model": "edemakaki",
        "type": "checkbox"
      },
      {
        "subTitle": "Lemak sub kutan sedikit",
        "model": "lemakSubKutanSedikit",
        "type": "checkbox"
      },
      {
        "subTitle": "Lainnya",
        "model": "lainnyaKlinis",
        "type": "textbox"
      },
    ],
  },

])
let DIETARY = ref([
  {
    "title": "",
    "value": [
      {
        "subTitle": "ASI",
        "model": "asi",
        "type": "textbox",
      },
      {
        "subTitle": "Makan biasa/nasi",
        "model": "makanBiasanasi",
        "type": "textbox",
      },
      {
        "subTitle": "Makanan pokok",
        "model": "makananPokok",
        "type": "textbox",
      },
      {
        "subTitle": "Lauk",
        "model": "lauk",
        "type": "textbox",
      },
      {
        "subTitle": "Sayur",
        "model": "sayur",
        "type": "textbox",
      },
      {
        "subTitle": "Buah",
        "model": "buah",
        "type": "textbox",
      },
      {
        "subTitle": "Bubur susu",
        "model": "buburSusu",
        "type": "checkbox",
      },
    ],
  },

])
let DIAGNOSIS_NUTRISI = ref([
  {
    "title": "",
    "value": [
      {
        "subTitle": "Gagal tumbuh",
        "model": "gagalTumbuh_DN",
        "type": "checkbox"
      },
      {
        "subTitle": "Malnutrisi berat",
        "model": "malmutrisiBerat",
        "type": "checkbox"
      },
      {
        "subTitle": "Kwashiorkor",
        "model": "kwashiorkor",
        "type": "checkbox"
      },
      {
        "subTitle": "Perawakan pendek",
        "model": "perawakanPendek",
        "type": "checkbox"
      },
      {
        "subTitle": "Malnutrisi sedang",
        "model": "malnutrisiSedang",
        "type": "checkbox"
      },
      {
        "subTitle": "Marasmus",
        "model": "marasmus",
        "type": "checkbox"
      },
      {
        "subTitle": "Obese",
        "model": "obese",
        "type": "checkbox"
      },
      {
        "subTitle": "Malnutrisi ringan",
        "model": "malnutrisiRingan",
        "type": "checkbox"
      },
      {
        "subTitle": "Kwashiorkor-Marasmus",
        "model": "kwashiorkorMarasmus",
        "type": "checkbox"
      },
      {
        "subTitle": "Gizi baik",
        "model": "giziBaik",
        "type": "checkbox"
      },
      {
        "subTitle": "Lainnya",
        "model": "lainnyaNutrisi",
        "type": "textbox"
      },
    ],
  },

])
let KEBUTUHAN_NUTRISI = ref([
  {
    "title": "",
    "value": [
      {
        "subTitle": "Energi",
        "model": "energi",
        "type": "textfiled",
        "satuan": "Kkal"
      },
      {
        "subTitle": "Protein",
        "model": "protein",
        "type": "textfiled",
        "satuan": "gr"
      },
      {
        "subTitle": "Cairan",
        "model": "cairan",
        "type": "textfiled",
        "satuan": "ml"
      },
      {
        "subTitle": "Densitas",
        "model": "densitas",
        "type": "textfiled",
      },
      {
        "subTitle": "Lemak",
        "model": "Lemak_KN",
        "type": "textfiled",
        "satuan": "gram"
      },
      {
        "subTitle": "Karbohidrat",
        "model": "Karbohidrat_KN",
        "type": "textfiled",
        "satuan": "gram"
      },
      {
        "subTitle": "Lainnya",
        "model": "Lainnya_KN",
        "type": "textbox"
      },
    ],
  },

])
let JENIS_NUTRISI = ref([
  {
    "title": "",
    "value": [
      {
        "subTitle": "Asi",
        "model": "asi_jenis_nutrisi",
        "type": "checkbox",
      },
      {
        "subTitle": "Nasi",
        "model": "nasi_jenis_nutrisi",
        "type": "checkbox",
      },
      {
        "subTitle": "Nasi Tim",
        "model": "nasi_tim_jenis_nutrisi",
        "type": "checkbox",
      },
      {
        "subTitle": "Bubur",
        "model": "bubur_jenis_nutrisi",
        "type": "checkbox",
      },
      {
        "subTitle": "Bubur Saring",
        "model": "bubur_saring_jenis_nutrisi",
        "type": "checkbox",
      },
      {
        "subTitle": "Bubur Susu",
        "model": "bubur_susu_jenis_nutrisi",
        "type": "checkbox",
      },
      {
        "subTitle": "Formula parenteral",
        "model": "formula_parenteral_jenis_nutrisi",
        "type": "checkbox",
      },
      {},
      {
        "subTitle": "Formula Standar",
        "model": "formula_standar_jenis_nutrisi",
        "type": "textfiled",
        "satuan": "klokalori"
      },
      {
        "subTitle": "Formula Khusus",
        "model": "formula_khusus_jenis_nutrisi",
        "type": "textfiled",
        "satuan": "klokalori"
      },
    ],
  },

])
let CARA_PEMBERIAN = ref([
  {
    "title": "",
    "value": [
      {
        "subTitle": "Oral",
        "model": "oral_cara_pemberian",
        "type": "checkbox",
      },
      {
        "subTitle": "Enteral",
        "model": "enteral_cara_pemberian",
        "type": "checkbox",
      },
      {
        "subTitle": "Parenteral",
        "model": "parenteral_cara_pemberian",
        "type": "checkbox",
      },
      {
        "subTitle": "Perifer",
        "model": "perifer_cara_pemberian",
        "type": "checkbox",
      },
      {
        "subTitle": "Sentral",
        "model": "sentral_cara_pemberian",
        "type": "checkbox",
      },
    ],
  },

])
let MONITORING = ref([
  {
    "title": "",
    "value": [
      {
        "subTitle": "Gagal tumbuh",
        "model": "gagalTumbuh",
        "type": "checkbox"
      },
      {
        "subTitle": "Reefeeding syndrome",
        "model": "reefeeding_monitoring",
        "type": "checkbox"
      },
      {
        "subTitle": "Overfeeding syndrome",
        "model": "Overfeeding_monitoring",
        "type": "checkbox"
      },
      {
        "subTitle": "Asupan makan",
        "model": "asupan_makan_monitoring",
        "type": "checkbox"
      },
      {
        "subTitle": "Biokimia",
        "model": "biokimia_monitoring",
        "type": "textbox"
      },
      {
        "subTitle": "Berat badan",
        "model": "berat_badan_monitoring",
        "type": "textbox"
      },
    ],
  },

])
</script>

<style lang="scss">
.table-fro {
  width: 100%;
  border: 1px solid black;
}

.th-fro,
.td-fro {
  padding: 7px;
  border: 1px solid black;
  vertical-align: inherit;
}

.setFRO-center {
  text-align: center !important;
}

.p-fieldset-legend {
  margin-left: 15px;
}
</style>