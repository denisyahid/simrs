<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>{{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr
              :NOREC_EMRPASIEN="NOREC_EMRPASIEN"
              :COLLECTION="COLLECTION"
              :isLoading="isLoading"
              @simpan="simpan"
              @kembaliKeun="kembaliKeun"
            ></ButtonEmr>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="column">
    <VCard>
      <div class="columns is-multiline">
        <div class="column">
          <span>Ruangan</span>
          <VField>
            <VControl class="prime-auto">
              <AutoComplete
                v-model="input.poli"
                :suggestions="d_Ruangan"
                @complete="fetchRuangan($event)"
                :optionLabel="'label'"
                :dropdown="true"
                :minLength="3"
                :appendTo="'body'"
                :loadingIcon="'pi pi-spinner'"
                :field="'label'"
                placeholder="Ketik untuk mencari ..."
                class="mt-2"
              />
            </VControl>
          </VField>
        </div>
        <div class="column">
          <span>Tanggal Operasi</span>
          <VDatePicker
            v-model="input.tanggalOperasi"
            mode="dateTime"
            style="width: 100%"
            trim-weeks
            :max-date="new Date()"
          >
            <template #default="{ inputValue, inputEvents }">
              <VField class="pt-3">
                <VControl icon="feather:calendar" fullwidth>
                  <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div>
        <!-- <div class="column">
          <span>Dokter DPJP</span>
          <VField class="is-autocomplete-select pt-3" v-slot="{ id }">
            <VControl icon="feather:search">
              <AutoComplete v-model="input.dokterDPJP" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik Nama Dokter" />
            </VControl>
          </VField>
        </div> -->
        <div class="column">
          <span>Diagnosa</span>
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="input.Diagnosa" />
            </VControl>
          </VField>
        </div>
        <div class="column">
          <span>Tindakan</span>
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="input.tindakan" />
            </VControl>
          </VField>
        </div>
      </div>
    </VCard>
    <VCard>
      <div class="column is-multiline columns">
        <div class="column">
          <span>Dokter Operator</span>
          <VField class="is-autocomplete-select pt-3" v-slot="{ id }">
            <VControl icon="feather:search">
              <AutoComplete
                v-model="input.dokterDPJP"
                :suggestions="d_Dokter"
                @complete="fetchDokter($event)"
                :optionLabel="'label'"
                :dropdown="true"
                :minLength="3"
                :appendTo="'body'"
                :loadingIcon="'pi pi-spinner'"
                :field="'label'"
                placeholder="ketik Nama Dokter"
              />
            </VControl>
          </VField>
        </div>
        <div class="column">
          <span>Perawat Instrument</span>
          <VField class="is-autocomplete-select pt-3" v-slot="{ id }">
            <VControl icon="feather:search">
              <AutoComplete
                v-model="input.perawatInstrument"
                :suggestions="d_Perawat"
                @complete="fetchPerawat($event)"
                :optionLabel="'label'"
                :dropdown="true"
                :minLength="3"
                :appendTo="'body'"
                :loadingIcon="'pi pi-spinner'"
                :field="'label'"
                placeholder="ketik Nama Dokter"
              />
            </VControl>
          </VField>
        </div>
        <div class="column">
          <span>Perawat Sirkuler</span>
          <VField class="is-autocomplete-select pt-3" v-slot="{ id }">
            <VControl icon="feather:search">
              <AutoComplete
                v-model="input.perawatSirkuler"
                :suggestions="d_Perawat"
                @complete="fetchPerawat($event)"
                :optionLabel="'label'"
                :dropdown="true"
                :minLength="3"
                :appendTo="'body'"
                :loadingIcon="'pi pi-spinner'"
                :field="'label'"
                placeholder="ketik Nama Dokter"
              />
            </VControl>
          </VField>
        </div>
      </div>
    </VCard>
  </div>

  <div class="column">
    <Fieldset :toggleable="true" legend="Implant">
      <div class="column">
        <table class="table-pi">
          <thead>
            <tr>
              <th class="th-pi">No</th>
              <th class="th-pi">Nama</th>
              <th class="th-pi">Jenis Implant</th>
              <th class="th-pi">Nomor Seri</th>
              <th class="th-pi">Jumlah</th>
              <th class="th-pi">Keterangan</th>
              <th class="th-pi">#</th>
            </tr>
          </thead>
          <tbody v-for="(item, index) in input.implants" :key="index">
            <tr>
              <td class="td-pi">
                <span>{{ item.no }}</span>
              </td>
              <td class="td-pi">
                <VField>
                  <VControl>
                    <VInput type="text" v-model="item.nama" />
                  </VControl>
                </VField>
              </td>
              <td class="td-pi">
                <VField>
                  <VControl>
                    <VInput type="text" v-model="item.jenisImplant" />
                  </VControl>
                </VField>
              </td>
              <td class="td-pi">
                <VField>
                  <VControl>
                    <VInput type="text" v-model="item.nomorSeri" />
                  </VControl>
                </VField>
              </td>
              <td class="td-pi">
                <VField>
                  <VControl>
                    <VInput type="text" v-model="item.jumlah" />
                  </VControl>
                </VField>
              </td>
              <td class="td-pi">
                <VField>
                  <VControl>
                    <VInput type="text" v-model="item.keterangan" />
                  </VControl>
                </VField>
              </td>
              <td class="td-pi">
                <VButtons style="justify-content: space-around">
                  <VIconButton
                    type="button"
                    raised
                    circle
                    icon="feather:plus"
                    @click="addNewItem()"
                    color="info"
                    v-tooltip.bubble="'Tambah '"
                  >
                  </VIconButton>
                  <VIconButton
                    class="mt-1"
                    v-if="index > 0"
                    type="button"
                    raised
                    circle
                    icon="feather:trash"
                    @click="removeItem(index)"
                    color="danger"
                  >
                  </VIconButton>
                </VButtons>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </Fieldset>
  </div>

  <div class="column">
    <Fieldset :toggleable="true" legend="Penempelan Barcode">
      <div class="column">
        <div class="card">
          <FileUpload
            name="demo2[]"
            @select="onAdvancedUpload2($event)"
            :multiple="false"
            accept="image/*"
            :maxFileSize="1000000"
          >
            <template #empty>
              <p>Drag and drop files to here to upload.</p>
            </template>
          </FileUpload>
        </div>
      </div>
      <!-- <div class="column">
        <div div="card">
          <FileUpload name="demo[]" :custom-upload="true" @uploader="onAdvancedUpload2($event)" :multiple="true"
            accept="image/*" :maxFileSize="10000000">
            <template #empty>
              <p>Drag and drop files to here to upload.</p>
            </template>
          </FileUpload>
        </div>
      </div> -->
      <hr style="border-top: 1px dashed red; background-color: white" class="mt-0 mb-1" />
      <div class="column mt-5">
        <div class="columns is-multiline">
          <div
            v-for="(file, index) of uploadedFiles"
            :key="file.name + file.type + file.size"
            class="column is-4"
          >
            <div>
              <img role="presentation" :alt="file.name" :src="file" class="shadow-2" />
            </div>
          </div>
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
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete'
import Fieldset from 'primevue/fieldset'
import FileUpload from 'primevue/fileupload'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import { useToast } from 'primevue/usetoast'
const toast = useToast()
const uploadedFiles = ref([])

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
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const isLoading: any = ref(false)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
// const images: any = ref()

const input: any = ref({
  images: [],
  implants: [
    {
      no: 1,
    },
  ],
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
  useApi()
    .get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    )
    .then((response: any) => {
      if (response.length) {
        uploadedFiles.value = response[0].images
        input.value = response[0]
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      }
    })
}
const fetchObat = async (filter: any) => {
  await useApi()
    .get(`emr/get-obat?filter=${filter.query}`)
    .then((response) => {
      d_Obat.value = response
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  console.log(object)
  debugger
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: props.FORM_URL,
    name_form: props.FORM_NAME,
    jenis_emr: 'asesmen_medis',
    data: object,
  }
  isLoading.value = true
  useApi()
    .post(`/emr/simpan-emr`, json)
    .then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}

const addNewItem = () => {
  input.value.implants.push({
    no: input.value.implants[input.value.implants.length - 1].no + 1,
  })
}
const removeItem = (index: any) => {
  input.value.implants.splice(index, 1)
}

// const onAdvancedUpload = (e: any) => {

//   const reader = new FileReader();
//   reader.readAsDataURL(e.files[0]);
//   reader.onload = async () => {
//     const encodedFile = reader.result.split(",")[1];

//     let formData = new FormData();
//     formData.append("image", e.files[0]);

//     useApi().post('emr/simpan-emr', formData).then((response) => {
//       console.log(response)
//     })
//   }
// }
const onAdvancedUpload = async (e: any) => {
  const reader = new FileReader()
  reader.onload = function () {
    const encodedFile = 'data:image/png;base64,' + reader.result.split(',')[1]
    if (input.value.images.length > 0) {
      input.value.images.push(encodedFile)
    } else {
      input.value.images = [encodedFile]
    }
  }

  reader.readAsDataURL(e.files[0])
}

const onAdvancedUpload2 = async (e: any) => {
  const reader = new FileReader()
  reader.onload = function () {
    input.value.images.push(reader.result)
  }
  reader.readAsDataURL(e.files[0])
}

// const fetchPegawai = async (filter: any) => {

//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
//   ).then((response) => {
//     d_Pegawai.value = response
//   })
// }

const fetchDokter = async (filter: any) => {
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    )
    .then((response) => {
      d_Dokter.value = response
    })
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {}

const d_Ruangan = ref([])
const d_Perawat = ref([])

const fetchRuangan = async (filter: any) => {
  await useApi()
    .get(
      `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&&settingdatafix=objectdepartemenfk,kdDepartemenRawatJalanFix&limit=10`
    )
    .then((response) => {
      d_Ruangan.value = response
    })
}
const fetchPerawat = async (filter: any) => {
  // let data = filter.query ? filter.query : filter
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    )
    .then((response) => {
      d_Perawat.value = response
    })
}
setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
// .set-center {
//   text-align: center !important;
// }

.table-pi {
  width: 100%;
  border: 1px solid black;
}

.th-pi,
.td-pi {
  border: 1px solid black;
  padding: 7px;
}

.p-fieldset-legend {
  margin-left: 15px;
}
</style>
