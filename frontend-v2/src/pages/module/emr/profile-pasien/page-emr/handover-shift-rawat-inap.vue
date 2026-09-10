<template>
    <ConfirmDialog />
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3> Handover Shift Rawat Inap</h3>
                        </div>
                        <div class="right">
                            <div class="buttons">
                                <!-- <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                    Kembali
                                </VButton> -->
                                <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                    :loading="isLoading" @click="simpanHandover()"> Simpan
                                </VButton>
                                <VButton v-if="showBatal == true" type="button" rounded outlined color="info" raised icon="feather:save"
                                    :loading="isLoading" @click="batalEdit()"> Batal Edit
                                </VButton>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-body p-2" v-if="!props.pasien">
                    <div class="business-dashboard hr-dashboard">
                        <div class="columns is-multiline">
                            <div class="column is-12" v-if="isLoading">
                                <PlaceloadHeader class="m-3" />
                            </div>
                            <div class="column is-12" v-if="!isLoading">
                                <HeadPasien :pasien="pasien" class="m-3" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form class="form-layout is-separate">
            <div class="form-outer">
                <div class="form-body">
                    <div class="columns is-multiline">
                        <div class="column is-12 ">
                            <div class="form-section pl-0 pl-3 pr-3 pb-0 mb-0">
                                <VCard>
                                    <div class="tabs-wrapper" :class="['tab-naver']">
                                        <div class="tabs-inner">
                                            <div class="tabs is-boxed">
                                                <ul>
                                                    <li v-for="(tab, key) in tabs" :key="key"
                                                        :class="[activeValue === tab.value && 'is-active']">
                                                        <slot name="tab-link" :active-value="activeValue" :tab="tab"
                                                            :index="key" :toggle="toggle">
                                                            <a tabindex="0" @keydown.space.prevent="toggle(tab.value)"
                                                                @click="toggle(tab.value)">
                                                                <VIcon v-if="tab.icon" :icon="tab.icon" />
                                                                <span>
                                                                    <slot name="tab-link-label" :active-value="activeValue"
                                                                        :tab="tab" :index="key">
                                                                        {{ tab.label }}
                                                                    </slot>
                                                                </span>
                                                            </a>
                                                        </slot>
                                                    </li>
                                                    <li v-if="sliderClass" class="tab-naver"></li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="tab-content is-active">
                                            <Transition :name="'fade-fast'" mode="out-in">
                                                <slot name="tab" :active-value="activeValue"></slot>
                                            </Transition>
                                        </div>
                                    </div>
                                    <!-- <VTabs type="boxed" selected="order" :tabs="[
                                  { label: 'Order', value: 'order' },
                                  { label: 'Riwayat', value: 'riw' },
                                ]">

                                </VTabs> -->
                                </VCard>
                                <!-- <TabView v-model:activeIndex="active">
                                <TabPanel>
                                    <template #header>
                                        <i class="pi pi-calendar mr-2"></i>
                                        <span> Order</span>
                                    </template>

                                </TabPanel>
                                <TabPanel>
                                    <template #header>
                                        <i class="pi pi-user mr-2"></i>
                                        <span> Riwayat</span>
                                    </template>

                                </TabPanel>
                            </TabView> -->
                            </div>
                        </div>
                        <div class="column is-12 mt-0 " v-if="activeValue == 1">
                            <div class="columns is-multiline p-2">

                                <div class="column is-12 has-text-centered is-size-3">
                                  Serah Terima Pasien
                                </div>

                                <div class="column is-12 mb-6">
                                  <div class="columns is-multiline">
                                    <div class="column is-6">
                                        <div class="column is-12 has-text-centered is-size-5">
                                            <span class="label-apas">Pemberi Informasi</span>
                                        </div>
                                        <div class="columns is-multiline">
                                            <div class="column is-2">
                                            </div>
                                            <div class="column is-3 mt-2">
                                              Tanggal dan Jam :
                                            </div>
                                            <div class="column is-5">
                                                <VDatePicker v-model="inputHandover.tanggalPemberi" mode="datetime" trim-weeks>
                                                    <template #default="{ inputValue, inputEvents }">
                                                        <VControl icon="feather:calendar" fullwidth>
                                                            <VInput :value="inputValue" v-on="inputEvents" />
                                                        </VControl>
                                                    </template>
                                                </VDatePicker>
                                            </div>
                                        </div>
                                        <div class="columns is-multiline">
                                            <div class="column is-2">
                                            </div>
                                            <div class="column is-3 mt-2">
                                              Nama :
                                            </div>
                                            <div class="column is-5">
                                                <VControl class="prime-auto">
                                                    <AutoComplete v-model="inputHandover.petugasPemberi" :suggestions="d_Petugas"
                                                        @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                        class="mt-2" />
                                                </VControl>
                                            </div>
                                        </div>
                                        <div class="columns is-multiline">
                                          <div class="column is-2">
                                          </div>
                                          <div class="column is-3 mt-2">
                                            Paraf :
                                          </div>
                                          <div class="column is-5">
                                            <TandaTangan :elemenID="'TTDpetugasPemberi'" :width="'150'" :height="'150'" class="dek" />
                                          </div>
                                        </div>
                                    </div>
                                    <div class="column is-6">
                                        <div class="column is-12 has-text-centered is-size-5">
                                            <span class="label-apas">Penerima Informasi</span>
                                        </div>
                                        <div class="columns is-multiline">
                                            <div class="column is-2">
                                            </div>
                                            <div class="column is-3 mt-2">
                                              Tanggal dan Jam :
                                            </div>
                                            <div class="column is-5">
                                                <VDatePicker v-model="inputHandover.tanggalPenerima" mode="datetime" trim-weeks>
                                                    <template #default="{ inputValue, inputEvents }">
                                                        <VControl icon="feather:calendar" fullwidth>
                                                            <VInput :value="inputValue" v-on="inputEvents" />
                                                        </VControl>
                                                    </template>
                                                </VDatePicker>
                                            </div>
                                        </div>
                                        <div class="columns is-multiline">
                                            <div class="column is-2">
                                            </div>
                                            <div class="column is-3 mt-2">
                                              Nama :
                                            </div>
                                            <div class="column is-5">
                                                <VControl class="prime-auto">
                                                    <AutoComplete v-model="inputHandover.petugasPenerima" :suggestions="d_Petugas"
                                                        @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                        class="mt-2" />
                                                </VControl>
                                            </div>
                                        </div>
                                        <div class="columns is-multiline">
                                          <div class="column is-2">
                                          </div>
                                          <div class="column is-3 mt-2">
                                            Paraf :
                                          </div>
                                          <div class="column is-5">
                                            <TandaTangan :elemenID="'TTDpetugasPenerima'" :width="'150'" :height="'150'" class="dek" />
                                          </div>
                                        </div>
                                    </div>
                                  </div>
                                </div>

                              </div>
                        </div>
                        <div class="column is-12 mr-6" v-if="activeValue == 2">
                          <TRiwayatHandoverShiftRanap v-if="listRiwayat" title="" straight class="list-widget-v3"
                              :items="listRiwayat" @editItems="editItems"
                              @hapusItems="DialogConfirm" squared
                              colored :isLoading="isLoading">
                          </TRiwayatHandoverShiftRanap>  
                        </div>
                  </div>
                </div>
            </div>
        </form>
    </div>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, nextTick } from 'vue'
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import { useConfirm } from "primevue/useconfirm"
import AutoComplete from 'primevue/autocomplete'
import MultiSelect from 'primevue/multiselect'
import * as EMR from '../page-emr-plugins/lembaran-penyiaran-radioterapi'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import Fieldset from 'primevue/fieldset'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import TRiwayatHandoverShiftRanap from '../t-riwayat-handover-shift-rawat-inap.vue'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
const listRiwayat = ref([])

const activeValue: any = ref(1)
const selectedTabs: any = ref()
let jenisTindakanRadioterapi = ref(EMR.jenisTindakanRadioterapi())
let JenisKelamin = ref(EMR.JenisKelamin())
let energy = ref(EMR.energy())
let accessories: any = ref(EMR.accessories())
let posisiMeja: any = ref(EMR.formField())
const user = useUserSession().getUser().pegawai
const tabs: any = ref([
    { label: 'Handover Shift Rawat Inap', value: 1, icon: 'fas fa-file' },
    { label: 'Riwayat', value: 2, icon: 'fas fa-list' }
]);
const emit = defineEmits<{
    (e: 'update:selected', value: string): void
}>()

function toggle(value: string) {
    activeValue.value = value
}

watch(
    () => selectedTabs,
    (value) => {
        activeValue.value = value
    }
)

watch(activeValue, (value: any) => {
    emit('update:selected', value)
})
watch(
    () => activeValue.value,
    (value) => {
        if (value == 2) {
          pilihTemplate()
        }
    }
)

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
const RiwayatPsikososial: any = ref([
  { label: 'Baik', value: 'Baik' },
  { label: 'Tidak Baik', value: 'Tidak Baik' },
])

const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const isLoading: any = ref(false)
const confirm = useConfirm();
const i: any = ref(false)
const isLoadingVitalSign: any = ref(false)
const dataTTD: any = ref([])
const d_Perawat: any = ref([])
const d_Dokter: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
})
const COLLECTION: any = ref('HandoverShiftRawatInap') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  tanggalKontrol: new Date(),
  tanggal: new Date(),
})
const inputHandover: any = ref({
})
const route = useRoute()
const setView = () => {
  useHead({
    title: 'Handover Shift Rawat Inap' + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const pilihTemplate = async (index: any) => {
  isLoading.value = true
  useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
    isLoading.value = false
    if (responselast.length) {
      listRiwayat.value = responselast //set ke inputan
    } else {
      H.alert('warning', 'Data tidak ada')
      listRiwayat.value = responselast //set ke inputan
    }
  })
}

const batalEdit = () => {
  inputHandover.value = {}
  H.tandaTangan().clear('TTDpetugasPemberi');
  H.tandaTangan().clear('TTDpetugasPenerima');
  showBatal.value = false;
}
const simpanHandover = () => {
  let ID = inputHandover.value.id ? inputHandover.value.id : ''
  let object: any = {}

  object = inputHandover.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  object['TTDpetugasPemberi'] = H.tandaTangan().get('TTDpetugasPemberi')
  object['TTDpetugasPenerima'] = H.tandaTangan().get('TTDpetugasPenerima')
  let json = {
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: route.name,
    name_form: 'Handover Shift Rawat Inap',
    jenis_emr: 'asesmen_medis',
    data: object,
  }
  isLoading.value = true
  useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
    isLoading.value = false
    showBatal.value = false;
    H.tandaTangan().clear('TTDpetugasPemberi');
    H.tandaTangan().clear('TTDpetugasPenerima');
    NOREC_EMRPASIEN.value = response.norec_emr
    inputHandover.value = {}
  })
    .catch((e: any) => {
      isLoading.value = false
    })
}

async function editItems(e: any) {
    isLoading.value = true;
    
    // console.log(e);
    // return

    await useApi().get(`/emr/get-emr?nocmfk=${e.pasien.nocmfk}&norec_pd=${e.registrasi.norec_pd}&collection=HandoverShiftRawatInap&emrpasienfk=${e.emrpasienfk}`).then((response: any) => {
      if (response.length) {
        inputHandover.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        dataTTD.value = response[0]
        activeValue.value = 1
        isLoading.value = false;
        showBatal.value = true;
        nextTick(() => {
        H.tandaTangan().set("TTDpetugasPemberi", dataTTD.value.TTDpetugasPemberi || "");
        H.tandaTangan().set("TTDpetugasPenerima", dataTTD.value.TTDpetugasPenerima || "");
      });
      } else {
        activeValue.value = 1
        isLoading.value = false;
      }
    })

}
const DialogConfirm = (e: any) => {
    confirm.require({
        message: 'Apakah anda serius menghapus data ini ?',
        header: 'Konfirmasi Hapus Data',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            hapusItems(e)

        },
        reject: () => { },
    })
}
function hapusItems(e: any) {
    useApi()
      .post(`/emr/hapus-emr`, {
        'norec': e.emrpasienfk,
        'collection': 'HandoverShiftRawatInap',
      })
      .then((response: any) => {
        pilihTemplate()
        isLoading.value = false
      })
}


const fetchDokter = async (filter: any) => {
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    )
    .then((response) => {
      d_Dokter.value = response
    })
}

const showBatal: any = ref([])
const d_Ruangan: any = ref([])
const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  )
  d_Ruangan.value = response
}

const d_Petugas = ref([]);

const fetchPetugas = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Petugas.value = response
  })
}

setView()
</script>