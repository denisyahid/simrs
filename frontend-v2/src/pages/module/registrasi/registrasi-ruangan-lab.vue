

<template>
    <div class="form-layout is-stacked">

        <div class="business-dashboard hr-dashboard">
            <div class="columns is-multiline">
                <div class="column is-12" v-if="isLoadingPasien">
                    <div class="block-green">
                        <div class="flex-list-inner mb-4">
                            <div class="flex-table-item grid-item mb-4" v-for="key in 1" :key="key">
                                <VFlexTableCell :column="{ grow: true, media: true }">
                                    <VPlaceloadAvatar size="medium" />
                                    <VPlaceloadText :lines="2" width="30%" last-line-width="20%" class="mx-2" />
                                </VFlexTableCell>
                                <VFlexTableCell>
                                    <VPlaceload width="100%" height="70px" class="mx-1 mt-2" />
                                </VFlexTableCell>
                                <VFlexTableCell>
                                    <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
                                </VFlexTableCell>
                                <VFlexTableCell :column="{ align: 'end' }">
                                    <VPlaceload width="10%" class="mx-1" />
                                </VFlexTableCell>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-12" v-if="!isLoadingPasien">
                    <div class="block-header">
                        <div class="left">
                            <div class="current-user">
                                <VAvatar size="medium"
                                    :picture="pasien.jeniskelamin ==
                                        'PEREMPUAN' ? '/images/avatars/svg/vuero-4.svg' : '/images/avatars/svg/vuero-1.svg'" squared />
                                <h3>{{ pasien.namapasien }}</h3>
                            </div>
                        </div>
                        <div class="center">
                            <div class="columns">
                                <div class="column">
                                    <h4 class="block-heading">No RM</h4>
                                    <p class="block-text"> {{ pasien.nocm }}</p>
                                    <h4 class="block-heading">Tgl Lahir </h4>
                                    <p class="block-text"> {{ pasien.tgllahir }}</p>
                                </div>
                                <div class="column">
                                    <h4 class="block-heading">NIK </h4>
                                    <p class="block-text"> {{ pasien.noidentitas }}</p>
                                    <h4 class="block-heading">Kelamin</h4>
                                    <p class="block-text"> {{ pasien.jeniskelamin }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="right">
                            <div class="columns">
                                <div class="column">
                                    <h4 class="block-heading">No HP</h4>
                                    <p class="block-text">{{ pasien.nohp }}</p>
                                    <h4 class="block-heading">Alamat</h4>
                                    <p class="block-text">{{ pasien.alamatlengkap }}
                                    </p>
                                </div>
                                <div class="column">
                                    <h4 class="block-heading">Umur</h4>
                                    <VTag color="purple" :label="pasien.umur" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="column is-12" >
                     <InfoPasien></InfoPasien>
                </div> -->
            </div>
        </div>

        <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3>{{ NOREC_PD != undefined ? 'Edit Registrasi' : 'Registrasi' }}</h3>
                    </div>
                    <div class="right">
                        <div class="buttons">
                            <VDropdown icon="feather:more-vertical" spaced right v-if="item.NOREC_PD" class="mt-1-min mr-2">
                                <template #content>

                                    <!-- <hr class="dropdown-divider" /> -->
                                    <a role="menuitem" @click="cetakSEP(item)" class="dropdown-item is-media">
                                        <div class="icon">
                                            <i aria-hidden="true" class="lnil lnil-printer"></i>
                                        </div>
                                        <div class="meta">
                                            <span>Cetak SEP</span>
                                            <span>Cetak Surat Elegibilitas </span>
                                        </div>
                                    </a>
                                    <a @click="cetakBuktiPendaftaran(item)" role="menuitem" class="dropdown-item is-media">
                                        <div class="icon">
                                            <i class="fas fa-print" aria-hidden="true"></i>
                                        </div>
                                        <div class="meta">
                                            <span>Poli Antrian</span>
                                            <span>Cetak Nomor</span>
                                        </div>
                                    </a>
                                    <a @click="cetakLabel(item)" role="menuitem" class="dropdown-item is-media">
                                        <div class="icon">
                                            <i class="fas fa-print" aria-hidden="true"></i>
                                        </div>
                                        <div class="meta">
                                            <span>Label Pasien</span>
                                            <span>Cetak Label</span>
                                        </div>
                                    </a>

                                    <a @click="cetakkartuPasien(item)" role="menuitem" class="dropdown-item is-media">
                                        <div class="icon">
                                            <i class="fas fa-print" aria-hidden="true"></i>
                                        </div>
                                        <div class="meta">
                                            <span>Kartu Pasien</span>
                                            <span>Cetak Kartu</span>
                                        </div>
                                    </a>
                                </template>
                            </VDropdown>
                            <VButton v-if="item.NOREC_PD && item.kelompokpasien != item.idKelompokPasienUMUM"
                                icon="lnir lnir-plus rem-100" light color="info" outlined @click="asuransi()">
                                Asuransi
                            </VButton>
                            <VButton v-if="item.NOREC_PD"
                                icon="lnir lnir-plus rem-100" light color="warning" outlined @click="saveTransaksi()">
                                Input Tindakan
                            </VButton>
                            <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="cancelRegistrasi()">
                                Batal
                            </VButton>
                            <VButton type="button" color="primary" rounded outlined raised icon="feather:save"
                                :loading="isLoading" @click="saveRegistrasi()"> Simpan </VButton>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-body">

                <div class="form-section is-grey">
                    <div class="form-section-header">
                        <div class="left">
                            <!-- <h3>Detail</h3> -->
                        </div>
                        <div class="right">

                        </div>
                    </div>

                    <div class="form-section-inner is-horizontal">

                        <VField class="required-field" horizontal label=" Tanggal">
                            <VDatePicker v-model="item.tglregistrasi" mode="dateTime" style="width: 100%;">
                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </VField>

                        <!-- <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel class="required-field">Ruangan</VLabel>
                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.gudang" :options="d_RuanganV" optionLabel="label" class="is-rounded"
                                    placeholder="Pilih Ruangan" style="width: 100%;" :filter="true" />
                            </VControl>
                        </VField> -->

                        <VField horizontal label="Ruangan" class="is-rounded-select_Z  is-autocomplete-select"
                            v-slot="{ id }">
                            <VControl icon="feather:home" fullwidth>
                                <Multiselect mode="single" v-model="item.ruangan" :options="d_RuanganV"
                                    placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off" />
                            </VControl>
                        </VField>
                        <!-- <VField horizontal label="Ruangan" class="is-rounded-select_Z  is-autocomplete-select"
                            v-slot="{ id }">
                            <VControl icon="feather:home" fullwidth>
                                <Multiselect mode="single" v-model="item.ruangan" :options="d_Ruangan"
                                    placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                                    />
                            </VControl>
                        </VField> -->



                        <VField horizontal label="Asal Rujukan" class="is-rounded-select_Z  is-autocomplete-select"
                            v-slot="{ id }" required-field>
                            <VControl icon="feather:git-merge" fullwidth>
                                <Multiselect mode="single" v-model="item.asalrujukan" :options="d_AsalRujukan"
                                    placeholder="Pilih data" :searchable="true" autocomplete="off" :attrs="{ id }"
                                    track-by="value" />
                            </VControl>
                        </VField>
                        <VField horizontal label="Pembiayaan" class="is-rounded-select_Z  is-autocomplete-select"
                            v-slot="{ id }">
                            <VControl icon="fas fa-calculator" fullwidth>
                                <Multiselect mode="single" v-model="item.kelompokpasien" :options="d_KelompokPasien"
                                    placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                                    @select="changeKelompok(item.kelompokpasien)" />
                            </VControl>
                        </VField>
                        <VField v-if="item.kelompokpasien" horizontal label="Penjamin"
                            class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                            <VControl icon="feather:command" fullwidth>
                                <Multiselect mode="single" v-model="item.rekanan" :options="d_Rekanan"
                                    placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                                    :loading="isLoadingTT" />
                            </VControl>
                        </VField>
                        <VField horizontal label="Tipe Layanan">
                            <VControl>
                                <VRadio v-model="item.jenispelayanan" v-for="items of d_JenisPelayanan" :key="items.id"
                                    :value="items.id" :label="items.jenispelayanan" name="{{items.id}}" color="primary" />
                                <!-- <VRadio v-model="item.jenispelayanan" value="Eksekutif" label="Eksekutif" color="primary" /> -->
                            </VControl>

                        </VField>

                        <VField horizontal label="Dokter" class="is-rounded-select_Z  is-autocomplete-select"
                            v-slot="{ id }">
                            <VControl icon="fa:user-md" fullwidth class="prime-auto ">

                                <AutoComplete v-model="item.dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                    :optionLabel="'namalengkap'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                    :loadingIcon="'pi pi-spinner'" :field="'namalengkap'" placeholder="ketik nama Dokter" />


                            </VControl>
                        </VField>
                        <VField horizontal label="Catatan">
                            <VControl fullwidth>
                                <VTextarea class="textarea" v-model="item.catatan" rows="4"
                                    placeholder="catatan registrasi (optional) ..." autocomplete="off" autocapitalize="off"
                                    spellcheck="true" />

                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="form-section is-grey">
                    <div class="form-section-inner is-horizontal">


                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete';
import Dropdown from 'primevue/dropdown';
import InfoPasien from '/@src/pages/include/info-pasien.vue'
useHead({
    title: 'Registrasi - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let NOREC_APD = useRoute().query.norec_apd as string
let NOREC_RESERVASI = useRoute().query.norec_online as string
let RESERVASI = {
    'norec_online': useRoute().query.norec_online as string,
    'ruangan': useRoute().query.ruangan as string,
    'dokter': useRoute().query.dokter as string,
    'dokter_name': useRoute().query.dokter_name as string,
    'kelompok': useRoute().query.kelompok as string,
    'tanggalreservasi': useRoute().query.tanggalreservasi as string,
}
const item: any = reactive({
    tglregistrasi: new Date(),
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: NOREC_APD != undefined ? NOREC_APD : '',
    idKelompokPasienBPJS: [],
    idKelompokPasienUMUM: null
})

const pasien: any = ref({})
const d_Ruangan: any = ref([])
const d_RuanganRI: any = ref([])
const d_RuanganRJ: any = ref([])
const d_RuanganV: any = ref([])
const d_AsalRujukan: any = ref([])
const d_KelompokPasien: any = ref([])
const d_JenisPelayanan: any = ref([])
const d_Rekanan: any = ref([])
const d_Dokter: any = ref([])
const d_Kelas: any = ref([])
const d_Kamar: any = ref([])
const d_TempatTidur: any = ref([])
const isLoading: any = ref(false)
const isLoadingTT: any = ref(false)
const isLoadingPasien: any = ref(false)
const modalAsuransi: any = ref(false)
const { y } = useWindowScroll()
const router = useRouter()
const isStuck = computed(() => {
    return y.value > 30
})

const pasienByID = (id: any) => {
    isLoadingPasien.value = true
    let paramsEdit = ''
    if (item.NOREC_PD != '' && item.NOREC_APD != '') {
        paramsEdit = `&norec_pd=${item.NOREC_PD}&norec_apd=${item.NOREC_APD}`
    }
    useApi().get(
        `/registrasi/pasien-registrasi?id=${id}${paramsEdit}`).then((response: any) => {
            pasien.value = response.pasien
            d_AsalRujukan.value = response.asalrujukan.map((e: any) => { return { label: e.asalrujukan, value: e.id, default: e } })
            d_KelompokPasien.value = response.kelompokpasien.map((e: any) => { return { label: e.kelompokpasien, value: e.id, default: e } })
            d_JenisPelayanan.value = response.jenispelayanan
            item.idKelompokPasienBPJS = response.idKelompokPasienBPJS
            item.idKelompokPasienUMUM = response.idKelompokPasienUMUM
            item.jenispelayanan = response.jenispelayanan[0].id
            for (let x = 0; x < response.asalrujukan.length; x++) {
                const element = response.asalrujukan[x];
                if (element.asalrujukan.toLowerCase() == 'datang sendiri') {
                    item.asalrujukan = element.id
                    break
                }
            }
            if (response.registrasi == null) {
                if (NOREC_RESERVASI != undefined) {
                    setFromReservasi(RESERVASI)
                }
            } else {
                let regis = response.registrasi
                if (ID_PASIEN == regis.nocmfk) {
                    setFromRegistrasi(regis)
                }
            }
            isLoadingPasien.value = false
        })

}


const setFromReservasi = (e: any) => {
    item.tglregistrasi = new Date(e.tanggalreservasi)
    item.ruangan = e.ruangan
    item.kelompokpasien = e.kelompok
    changeKelompok(item.kelompokpasien)
    item.dokter = { id: e.dokter, namalengkap: e.dokter_name }
}
const setFromRegistrasi = (regis: any) => {
    d_RuanganV.value = d_RuanganV.value
    item.tglregistrasi = new Date(regis.tglregistrasi)
    item.ruangan = regis.objectruanganlastfk
    item.asalrujukan = regis.asalrujukanfk
    item.kelompokpasien = regis.objectkelompokpasienlastfk
    changeKelompok(item.kelompokpasien)
    item.rekanan = regis.objectrekananfk
    item.jenispelayanan = regis.jenispelayananfk
    if (regis.objectpegawaifk != null)
        item.dokter = { id: regis.objectpegawaifk, namalengkap: regis.dokter }
    item.objectkelasfk = regis.kelas
    item.isRawatInap = regis.israwatinap
    item.catatan = regis.catatan
    item.statuspasien = regis.statuspasien
    item.kamar = regis.objectkamarfk
    item.bed = regis.nobed
}


const cancelRegistrasi = () => {
    window.history.back()
}

const changeKelompok = async (e: any) => {
    d_Rekanan.value = []
    for (let x = 0; x < item.idKelompokPasienBPJS.length; x++) {
        const element = item.idKelompokPasienBPJS[x];
        if (e == element) {
            if (pasien.value.nobpjs == null || pasien.value.nobpjs == '') {
                H.alert('warning', 'NO BPJS anda masih kosong, harap lengkapi data')
            } else if (pasien.value.nobpjs.length != 13) {
                H.alert('warning', 'NO BPJS anda tidak sesuai, harap sesuaikan data ')
            }
        }
    }

    delete item.rekanan
    if (e) {
        isLoadingTT.value = true
        await useApi().get(
            `/registrasi/penjamin-by-kelompokpasien?id=${e}`)
            .then((response: any) => {
                isLoadingTT.value = false
                if (response.length > 0) {
                    d_Rekanan.value = response.map((e: any) => { return { label: e.namarekanan, value: e.id, default: e } })
                    if (response.length == 1) {
                        item.rekanan = response[0].id
                    } else {
                        if (item.idKelompokPasienBPJS == e) {
                            for (let z = 0; z < d_Rekanan.value.length; z++) {
                                const element = d_Rekanan.value[z];
                                if (element.label.toLowerCase().indexOf('kesehatan') > -1) {
                                    item.rekanan = element.value
                                    break
                                }
                            }
                        }
                    }
                }
            })
            .catch((error: any) => { isLoadingTT.value = false })
    }

}


const saveRegistrasi = async () => {
    if (!item.tglregistrasi) { H.alert('warning', 'Tgl Registrasi harus di isi'); return }
    if (!item.ruangan) { H.alert('warning', 'Ruangan harus di isi'); return }
    if (!item.asalrujukan) { H.alert('warning', 'Asal Rujukan  harus di isi'); return }
    if (!item.kelompokpasien) { H.alert('warning', 'Pembiayaan harus di isi'); return }
    if (!item.jenispelayanan) { H.alert('warning', 'Tipe Layanan harus di isi'); return }
    if (item.isRawatInap == true) {
        if (!item.kelas) { H.alert('warning', 'Kelas harus di isi'); return }
        if (!item.kamar) { H.alert('warning', 'Kamar harus di isi'); return }
        if (!item.bed) { H.alert('warning', 'Bed harus di isi'); return }
    }
    let json = {
        'pasiendaftar': {
            'norec': item.NOREC_PD ? item.NOREC_PD : '',
            'nocmfk': ID_PASIEN,
            'tglregistrasi': H.formatDate(item.tglregistrasi, 'YYYY-MM-DD HH:mm:ss'),
            'objectruanganlastfk': item.ruangan,
            'asalrujukanfk': item.asalrujukan,
            'objectkelompokpasienlastfk': item.kelompokpasien,
            'jenispelayananfk': item.jenispelayanan,
            'objectpegawaifk': item.dokter ? item.dokter.id : null,
            'objectkelasfk': item.kelas ? item.kelas : null,
            'israwatinap': item.isRawatInap ? item.isRawatInap : false,
            'catatan': item.catatan ? item.catatan : null,
            'statuspasien': item.statuspasien ? item.statuspasien : 'LAMA',
            'objectrekananfk': item.rekanan != undefined ? item.rekanan : null,
            'nocm': pasien.value.nocm,
            'namapasien': pasien.value.namapasien,
            'antrianpasienregistrasifk': NOREC_RESERVASI ? NOREC_RESERVASI : null,
        },
        'antrianpasiendiperiksa': {
            'norec': item.NOREC_APD ? item.NOREC_APD : '',
            'objectkamarfk': item.kamar ? item.kamar : null,
            'nobed': item.bed ? item.bed : null,
        }
    }
    isLoading.value = true
    await useApi().post(
        `/registrasi/save-registrasi`, json).then((response: any) => {
            isLoading.value = false
            item.NOREC_PD = response.registrasi.pd.norec
            item.NOREC_APD = response.registrasi.apd.norec
            item.REGISTRASI = response.registrasi
            saveAdminAuto(response.registrasi.pd.norec, response.registrasi.apd.norec, pasien.value.objectkebangsaanfk)
            for (let xx = 0; xx < item.idKelompokPasienBPJS.length; xx++) {
                const element = item.idKelompokPasienBPJS[xx];
                if (element == item.kelompokpasien) {
                    // tambahAsuransi(item.NOREC_PD)
                }
            }
        }).catch((e: any) => {
            isLoading.value = false
            console.clear()
            console.log(e)
        })
}

const saveAdminAuto = (norec_pd: any, norec_apd: any, kebangsaan: any) => {
  let json = {
    norec: norec_pd,
    norec_apd: norec_apd,
    objectkebangsaanfk: kebangsaan
  }
  useApi().postNoMessage(
    `/registrasi/save-adminsitrasi`, json).then((response: any) => { })
}

const saveTransaksi = async () => {

  // let json = {
  //   pasiendaftar: {
  //     'norec_pd': item.NOREC_PD,
  //     'tglregistrasi': item.REGISTRASI.pd.tglregistrasi,
  //     'objectkelasfk': item.REGISTRASI.pd.objectkelasfk,
  //     'noregistrasifk': item.REGISTRASI.pd.noregistrasi,

  //   },
  //   antrianpasiendiperiksa: {
  //     'norec_apd': item.REGISTRASI.apd.norec,
  //     'objectruangantujuanfk': item.REGISTRASI.apd.objectruanganfk,
  //     'objectruanganlastfk': null
  //   }
  // }

  // isLoading.value = true
  // await useApi()
  //   .postNoMessage(`/laboratorium/save-penunjang`, json)
  //   .then((response: any) => {
  //     isLoading.value = false
  //     goTo()
  //   })
  //   .catch((e: any) => {
  //     isLoading.value = false
  //   })
  goTo();
}
const goTo = () => {
 let bindData = item.REGISTRASI
  router.push({
    name: 'module-emr-tindakan',
    query: {
      nocmfk: bindData.pd.nocmfk,
      norec_pasien_daftar: bindData.pd.norec,
      norec_apd: bindData.apd.norec_apd
    },

  })
}
const fetchDokter = async (filter: any) => {
    if (!filter.query) {
    }
    const response = await useApi().get(
        `/registrasi/dokter-paging?name=${filter.query}&limit=10`)
    d_Dokter.value = response.dokter
}

const fetchRuangan = async () => {
    await useApi().get(`/registrasi/pilihan-ruangan`).then((response) => {
        d_RuanganV.value = response.map((e: any) => {
            return { label: e.namaruangan, value: e.id }
        })
    })
}
const tambahAsuransi = (norec_pd: any) => {
    router.push({
        name: 'module-registrasi-pemakaian-asuransi',
        query: {
            norec_pd: norec_pd,
            nocmfk: pasien.value.nocmfk,
            //   norec_apd: norec_apd
        }
    })
    // modalAsuransi.value = true
}
const asuransi = () => {
    router.push({
        name: 'module-registrasi-pemakaian-asuransi',
        query: {
            norec_pd: item.NOREC_PD,
            nocmfk: pasien.value.nocmfk,
        }
    })
}
const cetakSEP = (e: any) => {
    H.printBlade('registrasi/pemakaian-asuransi/sep?norec_pd=' + e.NOREC_PD + "&pdf=true");
}

const cetakLabel = async (e: any) => {
    console.log(e.NOREC_RESERVASI)
    H.printBlade(`dashboard/registrasi/cetak-label-pasien?norec_pd=${e.NOREC_PD}`);
}

const cetakkartuPasien = async (e: any) => {
    H.printBlade(`dashboard/registrasi/cetak-kartu-pasien?norec_pd=${e.NOREC_PD}`);
}

const cetakBuktiPendaftaran = (e: any) => {
    console.log(NOREC_PD)
    H.printBlade(`report/bukti-pendaftaran?noregistrasi=${e.NOREC_PD}&norec_pd=${e.NOREC_PD}`);
}

fetchRuangan()
pasienByID(ID_PASIEN)
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';

</style>
