<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :ID="input.id" :isLoading="isLoading"
              @simpan="simpan" @kembaliKeun="kembaliKeun" isHideST></ButtonEmr>
          </div>
        </div>
      </div>
      <ConfirmDialog />
      <div class="column is-12">
        <div class="buttons is-flex" style="align-items: center;">
          <VButton type="button" rounded outlined color="info" raised icon="feather:file-text" :isLoading="isLoading"
            @click="pilihTemplate(index)"> Lihat Riwayat
          </VButton>
          <VButton type="button" rounded outlined color="danger" raised icon="feather:calendar" :isLoading="isLoading"
            @click="simpan(input.details.length - 1)"> Tutup Jadwal
          </VButton>
        </div>

        <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

        <div class="column is-12 columns">
          <div class="column is-5">
            <VField label="Diagnosa">
              <VControl>
                <VTextarea type="text" class="input" v-model="input.TBDiagnosa" :disabled="input.closing" />
              </VControl>
            </VField>
          </div>
          <div class="column is-5">
            <VField label="Permintaan Terapi">
              <VTextarea v-model="input.TAPermintaanTerapi" :disabled="input.closing"></VTextarea>
            </VField>
          </div>
        </div>
        <!-- <div v-if="props.registrasi.namaruangan == 'UNIT RADIOTERAPI'" class="column pt-0" style="overflow: auto;">
          <table class="table-rpo">
            <thead>
              <tr>
                <th class="th-rpo" width="15%" rowspan="2">Program</th>
                <th class="th-rpo" rowspan="2">Tanggal</th>
                <th class="th-rpo" colspan="2">TTD</th>
                <th class="th-rpo" rowspan="2" width="10%">#</th>
              </tr>
              <tr>
                <th class="th-rpo">Dokter</th>
                <th class="th-rpo">Terapis</th>
              </tr>
            </thead>
            <tbody v-for="(item, index) in input.details" :key="index">
              <tr>
                <td class="td-rpo">
                  <VField class="">
                    <VControl>
                      <VTextarea rows="6" v-model="item.program" :disabled="input.closing"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td class="td-rpo" style="text-align: center;">
                  <VDatePicker v-model="item.tanggal" mode="date" style="width: 100%;" :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents"
                            :disabled="input.closing" />
                        </VControl>
                      </VField>
                    </template>
</VDatePicker>
</td>
<td class="td-rpo">
  <VField class="pt-3">
    <VControl class="prime-auto">
      <AutoComplete v-model="item.parafDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
        :field="'label'" placeholder="Cari..." :disabled="input.closing" />
    </VControl>
  </VField>
</td>
<td class="td-rpo">
  <VField class="pt-3">
    <VControl class="prime-auto">
      <AutoComplete v-model="item.parafPegawai" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
        :field="'label'" placeholder="Cari..." :disabled="input.closing" />
    </VControl>
  </VField>
</td>
<td class="td-rpo" style="vertical-align: inherit">
  <div class="column">
    <VButtons style="justify-content:space-around">
      <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
        v-tooltip.bubble="'Tambah '">
      </VIconButton>
      <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
        @click="removeItem(index)" color="danger">
      </VIconButton>
    </VButtons>
  </div>
</td>
</tr>
</tbody>
</table>
</div> -->
        <div class="column pt-0" style="overflow: auto;">
          <table class="table-rpo">
            <thead>
              <tr>
                <th class="th-rpo" width="15%" rowspan="2">Program</th>
                <th class="th-rpo" rowspan="2">Tanggal</th>
                <th class="th-rpo" colspan="3">TTD</th>
                <th class="th-rpo" rowspan="2" width="10%" v-if="input.closing == null">#</th>
              </tr>
              <tr>
                <th class="th-rpo">Pasien</th>
                <th class="th-rpo">Dokter</th>
                <th class="th-rpo">Terapis</th>
              </tr>
            </thead>
            <tbody v-for="(item, index) in input.details" :key="index">
              <tr>
                <td class="td-rpo">
                  <VField class="">
                    <VControl>
                      <VTextarea rows="6" v-model="item.program" :disabled="input.closing"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td class="td-rpo" style="text-align: center;">
                  <VDatePicker v-model="item.tanggal" mode="date" style="width: 100%;" :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents"
                            :disabled="input.closing" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </td>
                <td class="td-rpo" style="text-align: center;">
                  <TandaTangan :elemenID="`parafPasien_${index}`" :width="'150'" :height="'150'" class="dek" />
                </td>
                <td class="td-rpo">
                  <VField class="pt-3">
                    <VControl class="prime-auto">
                      <AutoComplete v-model="item.parafDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari..."
                        :disabled="input.closing" />
                    </VControl>
                  </VField>
                </td>
                <td class="td-rpo">
                  <VField class="pt-3">
                    <VControl class="prime-auto">
                      <AutoComplete v-model="item.parafPegawai" :suggestions="d_Pegawai"
                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari..."
                        :disabled="input.closing" />
                    </VControl>
                  </VField>
                </td>
                <td class="td-rpo" style="vertical-align: inherit" v-if="input.closing == null">
                  <div class="column">
                    <VButtons style="justify-content:space-around">
                      <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                        v-tooltip.bubble="'Tambah '">
                      </VIconButton>
                      <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                        @click="removeItem(index)" color="danger">
                      </VIconButton>
                    </VButtons>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <VModal :open="showModalTemplate" title="Riwayat" :noclose="true" size="large" actions="right"
          @close="showModalTemplate = false">
          <template #content>
            <form class="modal-form">
              <div class="column is-12 pt-0 pb-0">
                <p style="font-size:9pt;font-weight:bold;padding: 7px;">List Riwayat</p>
                <div style="overflow-y:auto;">
                  <table style="width: 100%;border: 1px solid black;" v-if="listTemplate.length > 0">
                    <thead>
                      <tr>
                        <td style="text-align: center;font-weight: bold;" width="15%">Tanggal Mulai
                          Input</td>
                        <!-- <td style="text-align: center;font-weight: bold;" width="15%">No
                          Registrasi</td> -->
                        <!-- <td style="text-align: center;font-weight: bold;" width="15%">No EMR
                        </td> -->
                        <!-- <td style="text-align: center;font-weight: bold;" width="20%">Dokter</td> -->
                        <!-- <td style="text-align: center;font-weight: bold;" width="20%">Ruangan
                        </td> -->
                        <td style="text-align: center;font-weight: bold;" width="20%">Status Jadwal
                        </td>
                        <td style="text-align: center;font-weight: bold;" width="10%">#</td>
                      </tr>
                    </thead>
                    <tbody v-for="dataR in listTemplate">
                      <tr style="border: 1px solid black;">
                        <td class="padding" style="text-align:center;vertical-align: middle;">
                          <span class="mb-2">{{ dataR.created_at }}</span><br>
                        </td>
                        <!-- <td class="padding" style="text-align:center;vertical-align: middle;">
                          <span class="mb-2">{{ dataR.registrasi.noregistrasi }}</span><br>
                        </td> -->
                        <!-- <td class="padding" style="text-align:center;vertical-align: middle;">
                          <span class="mb-2">{{ dataR.registrasi.namaruangan }}</span><br>
                        </td> -->
                        <td class="padding" style="text-align:center;vertical-align: middle;">
                          <VTag class="is-danger tag" v-if="dataR.closing == true">Tutup Jadwal</VTag>
                          <VTag class="is-info tag" v-else>Sedang Berlangsung</VTag>
                        </td>
                        <td class="padding" style="text-align:center;vertical-align: middle;">
                          <VIconButton type="button" raised circle icon="fas fa-search" @click="addTemplate(dataR)"
                            color="info" v-tooltip-prime.top="'Pilih'">
                          </VIconButton>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </form>
          </template>
        </VModal>
      </div>
    </div>
  </div>

</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount, onMounted, nextTick } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from "primevue/useconfirm"

const confirm = useConfirm();
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
const jenisPegawai = useUserSession().getUser().pegawai.jenisPegawai.jenispegawai.trim()
const user = useUserSession().getUser().pegawai;
console.log(props.registrasi.namaruangan)

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
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const d_Obat: any = ref([])
const dataTTD: any = ref([])
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('JadwalKunjunganRehabDanFisio') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  details: [{
    no: 1,
    closing: false
  }],
  tglDibuat: new Date()
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = async () => {
  return new Promise(async (resolve) => {
    let tglRegis = props.registrasi?.tglregistrasi ? H.formatDate(props.registrasi.tglregistrasi, 'YYYY-MM-DD') : '';
    await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&tglregis=${tglRegis}`).then(async (response: any) => {
      if (response.length) {
        const index = response.findIndex(item => item.closing == null || !item.hasOwnProperty('closing'));
        if (index !== -1) {
          input.value = response[index];
          // input.value = response[0];
        }
        if (input.value != null) {
          if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
          }
          dataTTD.value = response[0]
          for (let i = 0; i < input.value.details.length; i++) {
            await nextTick();//test bawa di sini
            const fieldName = `parafPasien_${i}`;
            H.tandaTangan().set(fieldName, dataTTD.value[fieldName]);
          }
        }
        resolve(true);
      } else {
        await setAutoFill()
        resolve(true);
      }
    })
    await fetchDetailCPPT()
  })
}

async function setTTD() {
  // if(input.value.details > 0) {
  // for (let i = 0; i < input.value.details.length; i++) {
  //   const fieldName = `parafPasien_${i}`;
  //   console.log("paraf", fieldName);
  //   H.tandaTangan().set(`parafPasien_${i}`, input.value[fieldName]);
  // }
  // }
  let i = 0;
  while (dataTTD.value[`parafPasien_${i}`] !== undefined) {
    await nextTick();
    H.tandaTangan().set(`parafPasien_${i}`, dataTTD.value[`parafPasien_${i}`]);
    i++;
    console.log('masuk while', 'parafPasien_' + i)
  }
}

const setAutoFill = async () => {
  input.value.details.forEach((element: any) => {
    element.tanggal = new Date();
    if (kelompokUser == 'dokter') { element.parafDokter = { label: user.namaLengkap, value: user.id } }
    if (jenisPegawai == 'Fisiotheraphy') { element.parafPegawai = { label: user.namaLengkap, value: user.id } }
  });
}

function showConfirmDialog() {
  return new Promise((resolve) => {
    confirm.require({
      message: 'Apakah anda yakin ingin menutup jadwal ?',
      header: 'Tutup Jadwal',
      icon: 'pi pi-info-circle',
      acceptClass: 'p-button-danger',
      accept: () => {
        resolve(true);
      },
      reject: () => {
        resolve(false);
      },
    });
  });
}

const simpan = async (closing) => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  // for (const element of object.details) {
  //   if (element.parafDokter == null) {
  //     H.alert('error', 'Nama Dokter belum diisi!');
  //     return;
  //   }
  //   if (element.parafPegawai == null) {
  //     H.alert('error', 'Nama Terapis belum diisi!');
  //     return;
  //   }
  // }
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  for (let i = 0; i <= input.value.details.length; i++) {
    object[`parafPasien_${i}`] = H.tandaTangan().get(`parafPasien_${i}`);
  }
  if (closing != null) {
    const confirmed = await showConfirmDialog();
    if (confirmed) {
      H.alert('success', 'Tutup Jadwal Berhasil');
      object.details[closing].closing = true;
      object.closing = true;
    }
  }
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
  useApi().post(`/emr/simpan-emr`, json).then(async (response: any) => {
    if (closing != null) {
      listTemplate.value = []
      Object.keys(input.value).forEach(key => {
        input.value[key] = null;
      });
      input.value = {
        details: [{
          no: 1,
          closing: false
        }],
      }
      for (let i = 0; i < input.value.details.length; i++) {
        const fieldName = `parafPasien_${i}`;
        H.tandaTangan().set(`parafPasien_${i}`, input.value[fieldName]);
      }
      await fetchDetailCPPT()
    }
    isLoading.value = false
    NOREC_EMRPASIEN.value = response.norec_emr
    await loadRiwayat()
    setTTD()
  }).catch((e: any) => {
    isLoading.value = false
  })
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&query=${filter.query}&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}
const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const print = async () => {
  H.printBlade(`emr/cetak/${COLLECTION.value}?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&pdf=true`)
}

const addNewItem = () => {
  let newItem: any = {}
  // if (kelompokUser == 'dokter') {
  //   newItem = {
  //     no: input.value.details[input.value.details.length - 1].no + 1,
  //     tanggal: new Date(),
  //     parafDokter: { label: user.namaLengkap, value: user.id },
  //     closing: false
  //   }
  // }
  // if (jenisPegawai == 'Fisiotheraphy') {
  //   newItem = {
  //     no: input.value.details[input.value.details.length - 1].no + 1,
  //     tanggal: new Date(),
  //     parafPegawai: { label: user.namaLengkap, value: user.id },
  //     closing: false
  //   }
  // }
  newItem = {
    no: input.value.details[input.value.details.length - 1].no + 1,
    program: input.value.details[input.value.details.length - 1].program,
    tanggal: new Date(),
    parafDokter: input.value.details[input.value.details.length - 1].parafDokter,
    closing: false
  }
  input.value.details.push(newItem);
}
const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
}

// const pilihTemplate = async (index: any) => {
//   isLoading.value = true
//   useApi().get(
//     `/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
//       isLoading.value = false
//       if (responselast.length) {
//         listTemplate.value = responselast //set ke inputan
//         // dataTTD.value = responselast;

//         for (let i = 0; i < input.value.details.length; i++) {
//           await nextTick(); //pake nextTick
//           const fieldName = `parafPasien_${i}`;
//           H.tandaTangan().set(fieldName, dataTTD.value[fieldName]);
//         }
//         showModalTemplate.value = true
//       } else {
//         H.alert('warning', 'Data tidak ada')
//       }
//     })
// }

// const addTemplate = (response: any) => {
//   input.value = response
//   if (response.length) {
//     dataTTD.value = response
//     // for (let i = 0; i <= response.details.length; i++) {
//     //   let fieldName = `parafPasien_${i}`;
//     //   H.tandaTangan().set(fieldName, dataTTD.value[fieldName]);
//     // }
//     for (let i = 0; i < input.value.details.length; i++) {
//       await nextTick();//test bawa di sini
//       const fieldName = `parafPasien_${i}`;
//       H.tandaTangan().set(fieldName, dataTTD.value[fieldName]);
//     }
//   }
//   input.value.namatemplate = null
//   showModalTemplate.value = false
// }
const pilihTemplate = async (index: any) => {
  try {
    isLoading.value = true;

    const responselast = await useApi().get(
      `/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`
    );

    isLoading.value = false;

    if (responselast.length) {
      listTemplate.value = responselast; // Set ke inputan

      for (const data of responselast) {
        await nextTick();

        input.value.details.forEach((_, i) => {
          const fieldName = `parafPasien_${i}`;

          if (dataTTD.value && data[fieldName]) {
            H.tandaTangan().set(fieldName, data[fieldName]);
          }
        });
      }

      showModalTemplate.value = true;
    } else {
      H.alert('warning', 'Data tidak ada');
    }
  } catch (error) {
    isLoading.value = false;
    console.error('Error fetching template:', error);
    H.alert('error', 'Terjadi kesalahan saat mengambil data');
  }
}

const addTemplate = async (response: any) => {
  input.value = response;

  if (response.length) {
    dataTTD.value = response;
    console.log(dataTTD.value)

    // for (let i = 0; i < input.value.details.length; i++) {
    //   await nextTick(); //pake nextTick
    //   const fieldName = `parafPasien_${i}`;
    //   H.tandaTangan().set(fieldName, dataTTD.value[fieldName]);
    // }

    let i = 0;
    while (dataTTD.value[`parafPasien_${i}`] !== undefined) {
      await nextTick();
      H.tandaTangan().set(`parafPasien_${i}`, dataTTD.value[`parafPasien_${i}`]);
      i++;
      console.log('masuk while', i)
    }

  }

  input.value.namatemplate = null;
  showModalTemplate.value = false;
}

const fetchDetailCPPT = async (index: any) => {
  await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=CPPTDetail&flag=dokter&ruangan=" + props.registrasi.namaruangan + "&field=S,O,A,P").then((responses) => {
    if (responses != null) {
      input.value.TBDiagnosa = responses.A
      input.value.TAPermintaanTerapi = responses.P
      isLoading.value = false
      console.log('cppt', responses)
    } else {
      console.log('Data CPPT Detail Kosong')
      isLoading.value = false
    }
  })
}

onMounted(async () => {
  try {
    await setView();
    await loadRiwayat().then((s) => {
      setTTD();
    })
  } catch (error) {

  }
})


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
table {
  width: 100% !important;
  border-collapse: collapse;
}

td {
  border: 1px solid black;
}

.padding {
  padding: 3px !important;
}

.table-rpo {
  width: 100%;
  border: 1px solid black;
}

.th-rpo,
.td-rpo {
  padding: 7px;
  border: 1px solid black;
  vertical-align: inherit;
}

.th-rpo {
  text-align: center !important;
}
</style>
