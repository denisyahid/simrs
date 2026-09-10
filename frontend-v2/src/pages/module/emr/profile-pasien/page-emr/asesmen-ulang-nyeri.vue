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
              <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                @click="simpan()"> Simpan
              </VButton>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  
  <div class="columns is-multiline p-2">
    <VPlaceload height="37rem" width="100%" class="mx-2" v-if="loadData" />
    <div class="column is-12" v-else>
      <VCard>
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-4">
              <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Dokter</h1>
              <VControl class="prime-auto">
                <AutoComplete v-model="input.dokterRawat" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
              </VControl>

              <!-- <VField>
                <VControl>
                  <VInput type="text" class="input" placeholder="" v-model="input.dokterRawat" />
                </VControl>
              </VField> -->
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Diagnosa </h1>
              <VField>
                <VTextarea rows="2" v-model="input.diagnosa"></VTextarea>
              </VField>
              <!-- <VField>
                <VControl>
                  <AutoComplete v-model="input.diagnosa" :suggestions="d_Diagnosa" @complete="fetchDiagnosa($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Diagnosa" />
                </VControl>
              </VField> -->
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Ruangan </h1>
              <VField>
                <VControl>
                  <AutoComplete v-model="input.namaruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Ruangan" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
        <div class="column is-12">
          <!-- <VAvatar size="big" picture="/images/emr/wongBaker.png" color="primary" squared bordered /> -->
          <Image src="/images/emr/wongBaker.png" alt="Image" width="600" class="center" preview />
        </div>
        <div class="columns is-multiline p-2">
        <div class="column is-2">
            <button @click="tambahKolom" class="button is-primary">Tambah Kolom</button>
          </div>
        </div>
        <div class="columns is-multiline">

          <div class="column is-12" style="width: 100%;overflow: auto;">
            <table class="tg" style="width: 200%;">
              <tr>
                <th rowspan="2" style="width:150px;position:sticky;left:0;z-index:2;background-color: aliceblue;text-align: center;">
                  Pengkajian Nyeri</th>

                <th rowspan="2" style="width: 100px;left:0px;z-index:2;background-color: aliceblue;text-align: center;">
                  Skor</th>

                <th :colspan="jumlahImdex" class="text-left bg-th" style="width:50px;text-align: center;">Tanggal</th>
              </tr>
              <tr>

                <th class="bg-th" v-for="index in jumlahImdex">
                  <VDatePicker v-model="input.tanggal[index]" color="green" trim-weeks :input="'YYYY-MM-DD HH-MM'"
                    mode="datetime">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar">
                          <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                            class="is-rounded_Z input-30" style="width:200px" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </th>
              </tr>
              <tr>
                <td class="bg-colatas ">Tidak Nyeri</td>
                <td class="bg-colatas3">0 - 1</td>

                <td class="tg-0lax" style="text-align:center" v-for="index in jumlahImdex">
                  <Checkbox v-model="input.tidakNyeri[index]" :binary="true"
                    @change="setSkor(input.tidakNyeri[index], index, 0)" />

                </td>
              </tr>
              <tr>
                <td class="bg-colatas">Sedikit Nyeri</td>

                <td class="bg-colatas3">2 - 3</td>

                <td class="tg-0lax" style="text-align:center" v-for="index in jumlahImdex">
                  <Checkbox v-model="input.sedikitNyeri[index]" :binary="true"
                    @change="setSkor(input.sedikitNyeri[index], index, 2)" />

                </td>
              </tr>
              <tr>
                <td class="bg-colatas">Cukup Nyeri</td>

                <td class="bg-colatas3">4-5</td>

                <td class="tg-0lax" style="text-align:center" v-for="index in jumlahImdex">
                  <Checkbox v-model="input.cukupNyeri[index]" :binary="true"
                    @change="setSkor(input.cukupNyeri[index], index, 4)" />

                </td>
              </tr>
              <tr>
                <td class="bg-colatas">Lumayan Nyeri</td>

                <td class="bg-colatas3">6 - 7</td>

                <td class="tg-0lax" style="text-align:center" v-for="index in jumlahImdex">
                  <Checkbox v-model="input.lumayanNyeri[index]" :binary="true"
                    @change="setSkor(input.lumayanNyeri[index], index, 6)" />

                </td>
              </tr>
              <tr>
                <td class="bg-colatas">Sangat Nyeri</td>

                <td class="bg-colatas3">8 - 9</td>

                <td class="tg-0lax" style="text-align:center" v-for="index in jumlahImdex">
                  <Checkbox v-model="input.sangatNyeri[index]" :binary="true"
                    @change="setSkor(input.sangatNyeri[index], index, 8)" />

                </td>
              </tr>
              <tr>
                <td class="bg-colatas">Berat Nyeri</td>

                <td class="bg-colatas3">10</td>

                <td class="tg-0lax" style="text-align:center" v-for="index in jumlahImdex">
                  <Checkbox v-model="input.nyeriBerat[index]" :binary="true"
                    @change="setSkor(input.nyeriBerat[index], index, 10)" />

                </td>
              </tr>

              <tr>
                <th class="bg-colatas">Total Skor</th>

                <td class="bg-colatas3"></td>

                <td class="tg-0lax" style="text-align:center" v-for="index in jumlahImdex">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.totalSkor[index]" placeholder="Total Skor"
                        class="is-rounded_Z input-30" />
                    </VControl>
                  </VField>
                </td>
              </tr>
              <tr>
                <th class="bg-colatas">Nama</th>

                <td class="bg-colatas3"></td>

                <td class="tg-0lax" v-for="index in jumlahImdex">
                  <!-- {{ index }} -->
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:search">
                      <AutoComplete v-model="input['penangungJawab' + index]" :suggestions="d_Dokter"
                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik nama" />
                    </VControl>
                  </VField>
                </td>
                
                <!-- <td class="tg-0lax">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:search">
                      <AutoComplete v-model="input.penangungJawab2" :suggestions="d_Dokter"
                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik nama" />
                    </VControl>
                  </VField>
                </td>
                <td class="tg-0lax">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:search">
                      <AutoComplete v-model="input.penangungJawab3" :suggestions="d_Dokter"
                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik nama" />
                    </VControl>
                  </VField>
                </td>
                <td class="tg-0lax">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:search">
                      <AutoComplete v-model="input.penangungJawab4" :suggestions="d_Dokter"
                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik nama" />
                    </VControl>
                  </VField>
                </td>
                <td class="tg-0lax">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:search">
                      <AutoComplete v-model="input.penangungJawab5" :suggestions="d_Dokter"
                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik nama" />
                    </VControl>
                  </VField>
                </td>
                <td class="tg-0lax">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:search">
                      <AutoComplete v-model="input.penangungJawab6" :suggestions="d_Dokter"
                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik nama" />
                    </VControl>
                  </VField>
                </td>
                <td class="tg-0lax">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:search">
                      <AutoComplete v-model="input.penangungJawab7" :suggestions="d_Dokter"
                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik nama" />
                    </VControl>
                  </VField>
                </td>
                <td class="tg-0lax">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:search">
                      <AutoComplete v-model="input.penangungJawab8" :suggestions="d_Dokter"
                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik nama" />
                    </VControl>
                  </VField>
                </td>
                <td class="tg-0lax">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:search">
                      <AutoComplete v-model="input.penangungJawab9" :suggestions="d_Dokter"
                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik nama" />
                    </VControl>
                  </VField>
                </td>
                <td class="tg-0lax">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:search">
                      <AutoComplete v-model="input.penangungJawab10" :suggestions="d_Dokter"
                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik nama" />
                    </VControl>
                  </VField>
                </td> -->
              </tr>
              <tr>
                <th class="bg-colatas">Nama Perawat</th>
                <td class="bg-colatas3"></td>
                <td class="tg-0lax" v-for="index in jumlahImdex">
                  <!-- {{ index }} -->
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:search">
                      <AutoComplete v-model="input['perawat' + index]" :suggestions="d_Petugas"
                        @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik nama" />
                    </VControl>
                  </VField>
                </td>
              </tr>
            </table>

          </div>


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
import AutoComplete from 'primevue/autocomplete';
import Checkbox from 'primevue/checkbox';
import Image from 'primevue/image';

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const user = useUserSession().getUser().pegawai;
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
const loadData: any = ref(true)
const jumlahImdex = ref(10)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('AsesmenUlangNyeri') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  tanggal: [],
  tidakNyeri: [],
  sedikitNyeri: [],
  cukupNyeri: [],
  lumayanNyeri: [],
  sangatNyeri: [],
  nyeriBerat: [],

  totalSkor: [],

  detailsTglSkor: [{
    no: 1,
    tgl: new Date(),
  }]
})
const d_Dokter: any = ref([])
const d_Petugas: any = ref([])
const d_Ruangan: any = ref([])
const d_Diagnosa: any = ref([])
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const fetchDiagnosa = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/diagnosa_m?select=kddiagnosa,namadiagnosa&param_search=kddiagnosa&query=${filter.query}&limit=10`)
  d_Diagnosa.value = response
}
const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=id&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}
const loadRiwayat = async () => {
  // 🔥 Definisikan keysToCheck terlebih dahulu
  const keysToCheck = ['tanggal', 'tidakNyeri', 'sedikitNyeri', 'cukupNyeri', 'lumayanNyeri', 'sangatNyeri', 'nyeriBerat', 'totalSkor', 'penangungJawab','perawat'];

  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`);

  if (response.length) {
    input.value = { ...input.value, ...response[0] };

    if (NOREC_EMRPASIEN.value === '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk;
    }

    // 🔥 Ambil jumlah maksimum indeks dari semua field
    let maxIndex = 0;

    keysToCheck.forEach((key) => {
      if (input.value[key]) {
        const keyIndexes = Object.keys(input.value[key])
          .map(k => parseInt(k))
          .filter(n => !isNaN(n)); // Pastikan hanya angka yang diambil

        if (keyIndexes.length) {
          maxIndex = Math.max(maxIndex, ...keyIndexes);
        }
      }
    });

    // 🔥 Update jumlah kolom (minimal tetap 10)
    jumlahImdex.value = Math.max(maxIndex, jumlahImdex.value, 10);
  } else {
    setAutoFill();
  }

  // 🔥 Pastikan semua kolom memiliki data default agar tidak undefined
  for (let i = 1; i <= jumlahImdex.value; i++) {
    keysToCheck.forEach((key) => {
      if (!input.value[key]) {
        input.value[key] = {}; // Buat objek jika belum ada
      }
      if (!input.value[key][i]) {
        input.value[key][i] = key === 'tanggal' ? null : ''; // Set default
      }
    });
  }
};




const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

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
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const kembaliKeun = () => {
  window.history.back()
}

// Fungsi untuk menambah index
const tambahKolom = () => {
  jumlahImdex.value += 1;
};

const addNewItem = () => {

  input.value.detailsTglSkor.push({
    no: input.value.detailsTglSkor[input.value.detailsTglSkor.length - 1].no + 1,
    tgl: new Date(),
  });
}
const removeItem = (index: any) => {
  input.value.detailsTglSkor.splice(index, 1)
}

const fetchDokter = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`).then((response) => {
    d_Dokter.value = response
  })
}

const fetchPetugas = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Petugas.value = response
    })
}

const setSkor = (e: any, index: any, skor: any) => {
  if (input.value.totalSkor[index] < 0) {
    input.value.totalSkor[index] = 0
  }
  if (e) {
    input.value.totalSkor[index] = parseFloat(input.value.totalSkor[index] ? input.value.totalSkor[index] : 0) + skor
  } else {
    input.value.totalSkor[index] = parseFloat(input.value.totalSkor[index] ? input.value.totalSkor[index] : 0) - skor
  }

}
const setAutoFill = async () => {
  input.value.dokterRawat = { label: user.namaLengkap, value: user.id }
  input.value.namaruangan = props.registrasi.namaruangan

}
setView()

onBeforeMount(async () => {
  try {
    await loadRiwayat()
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

  // font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg tr {
  height: 20px;
}

.tg th {
  border-color: var(--fade-grey-dark-3);
  border-style: solid;
  border-width: 1px;
  vertical-align: middle;
  // font-size: 14px;
  font-weight: bold;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg .tg-0lax {
  text-align: left;
  vertical-align: top
}

.input-30 {
  height: 30px;
}

.bg-colatas {
  position: sticky;
  background-color: aliceblue;
  left: 0;
  z-index: 2;
}

.bg-colatas2 {
  position: sticky;
  background-color: aliceblue;
  left: 150px;
  z-index: 2;
}

.bg-colatas3 {

  background-color: aliceblue;
  left: 0px;
  z-index: 2;
}


.bg-col {
  position: sticky;
  background-color: aliceblue;
  left: 0;
  z-index: 2;
}

.bg-col2 {
  position: sticky;
  background-color: aliceblue;
  left: 57px;
  z-index: 2;
}

.bg-col3 {
  position: sticky;
  background-color: aliceblue;
  left: 357px;
  z-index: 2;
}



.bg-th {
  text-align: center;
  background-color: #dedfe2d3;
}
</style>