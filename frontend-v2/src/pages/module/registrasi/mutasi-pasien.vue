<template>
    <ConfirmDialog group="confirm">
    <template #message="slotProps">
        <div style="width:500px;height:300px;">
            <table style="width:100%;height:100%;border-collapse: collapse">
            <tr>
                <td style="text-align:center;vertical-align:middle">
                <i :class="slotProps.message.icon" style="font-size:125px;text-align:center;color:#FDDA0D"></i>
                </td>
            </tr>
            <tr>
                <td style="padding:7px;padding-bottom:0px;text-align:center">
                <p style="font-size:large;">
                    Apakah anda yakin dengan data berikut? <br><br> 
                    - Status Naik Kelas: <b>{{item.isNaikKelas ? '✓' : 'X'}}</b> <br> 
                    - Status Titip Kelas: <b>{{item.isKelasTitip ? '✓' : 'X'}}</b> <br> 
                    - Status Rawat Gabung: <b>{{item.israwatgabung ? '✓' : 'X'}}</b>
                </p>
                </td>
            </tr>
            </table>
        </div>
    </template>
    </ConfirmDialog>
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
                        <h3>{{ NOREC_PD != undefined ? 'Mutasi Pasien' : 'Mutasi Pasien' }}</h3>
                    </div>
                    <div class="right">
                        <div class="buttons">
                          <VDropdown icon="feather:more-vertical" spaced right v-if="item.NOREC_PD" class="mt-1-min mr-2"
                            v-tooltip.bubble="'CETAK'">
                            <template #content>
                              <a @click="cetakLabel(item)" role="menuitem" class="dropdown-item is-media">
                                <div class="icon">
                                  <i class="fas fa-print" aria-hidden="true"></i>
                                </div>
                                <div class="meta">
                                  <span>Label Pasien</span>
                                  <span>Cetak Label</span>
                                </div>
                              </a>
                              <a @click="cetakIdentitasPasien(item)" role="menuitem" class="dropdown-item is-media">
                                <div class="icon">
                                  <i class="fas fa-print" aria-hidden="true"></i>
                                </div>
                                <div class="meta">
                                  <span>Identitas Pasien</span>
                                  <span>Cetak Identitas</span>
                                </div>
                              </a>
                            </template>
                          </VDropdown>
                            <VButton v-if="item.NOREC_PD && item.kelompokpasien != item.idKelompokPasienUMUM"
                                icon="lnir lnir-plus rem-100" light dark-outlined @click="asuransi()">
                                Asuransi
                            </VButton>
                            <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="cancelRegistrasi()">
                                Batal
                            </VButton>
                            <VButton type="button" color="primary" rounded outlined raised icon="feather:save"
                                :loading="isLoading" @click="saveMutasi()"> Simpan </VButton>
                        </div>
                    </div>
                </div>
            </div>

            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-8">
                        <div class="form-body">

                        <div class="form-section is-grey">
                            <div class="form-section-header">
                                <div class="left">

                                </div>
                                <div class="right">

                                </div>
                            </div>

                            <div class="form-section-inner is-horizontal">

                                <VField horizontal label=" Tanggal">
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
                                <VField horizontal label="&nbsp;">
                                    <VControl>
                                        <VSwitchBlock v-model="item.israwatgabung" label="Rawat Gabung" color="danger"/>
                                    </VControl> &emsp;&emsp;&emsp;
                                    <!-- <VRadio v-model="item.isKelasTitip" :value="true" label="Kelas Titip" name="isKelasTitip" square
                                        color="primary" style="margin-top: 5px; margin-left: 30px; font-size: 0.9rem;" /> -->
                                </VField>
                                <VField horizontal label="&nbsp;" style="margin-top: -60px; margin-left: 180px;">
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="item.isKelasTitip" label="Kelas Titip" color="info"/>
                                    </VControl>
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="item.isNaikKelas" label="Naik Kelas" color="warning"/>
                                    </VControl>
                                </VField>
                                <VField horizontal label="Ruangan" class="is-rounded-select_Z  is-autocomplete-select"
                                    v-slot="{ id }">
                                    <VControl icon="feather:home" fullwidth>
                                        <Multiselect mode="single" v-model="item.ruangan" :options="d_Ruangan"
                                            placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                                            @select="changeRuang(item.ruangan)" />
                                    </VControl>
                                </VField>
                                <!-- Awal namanya kelas rawat, diganti jadi kelas kamar supaya tidak keliru, karena ngambil harganya dari kelasrawat(kelas titip) -->
                                <VField horizontal label="Kelas Kamar"
                                    class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                    <VField v-slot="{ id }">
                                    <VControl icon="feather:layers" fullwidth>
                                        <Multiselect mode="single" v-model="item.kelasRawat" :options="d_Kelas" placeholder="Pilih data"
                                            :searchable="true" :attrs="{ id }" autocomplete="off" :loading="isLoadingTT"
                                            @select="changeKelas(item.kelasRawat)" />
                                    </VControl>
                                    </VField>
                                    <VField v-slot="{ id }" v-if="item.kelasRawat" horizontal label="Kelas Ditanggung/Rawat">
                                    <VControl icon="feather:layers" fullwidth>
                                        <Multiselect mode="single" v-model="item.kelas" :options="d_KelasDefault"
                                        placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                                        :loading="isLoadingTT" />
                                    </VControl>
                                    </VField>
                                </VField>
                                <VField v-if="item.kelas" horizontal label="Kamar"
                                    class="is-rounded-select_Z  is-autocomplete-select">
                                    <VField v-slot="{ id }">
                                        <VControl icon="fas fa-hospital-alt">
                                            <Multiselect mode="single" v-model="item.kamar" :options="d_Kamar"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                                                :loading="isLoadingTT" @select="changeKamar(item.kamar)" />
                                        </VControl>
                                    </VField>
                                    <VField v-slot="{ id }" subcontrol v-if="item.kamar">
                                        <VControl icon="fas fa-bed">
                                            <Multiselect mode="single" v-model="item.bed" :options="d_TempatTidur"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                                                :loading="isLoadingTT" />
                                        </VControl>
                                    </VField>
                                </VField>

                                <VField horizontal label="Asal Rujukan" class="is-rounded-select_Z  is-autocomplete-select"
                                    v-slot="{ id }">
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
                                        <!-- <Multiselect mode="single" v-model="item.dokter" placeholder="Pilih data" :searchable="true"
                                            :filter-results="false" :min-chars="0" :resolve-on-load="false" :delay="0" :options="async function (query: any) {
                                                                                                            return await fetchDokter(query)
                                                                                                        }" autocomplete="off" /> -->
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
                    <div class="column is-4">
                        <UIWidget class="search-widget">
                            <template #body>
                                <div class="field">
                                    <div class="control">
                                        <input v-model="item.filters" class="input custom-text-filter"
                                            placeholder="Cari Ruagan / Kelas / Kamar" />
                                        <button class="searcv-button" @click="fetchDaftarKamar()">
                                            <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </UIWidget>
                        <div class="column border-custom mb-2 mt-5-min">
                            <span style="font-weight: bold; font-size: 16px; font-family: var(--font-alt);">Daftar Kamar {{
                                                item.namaruangan ? item.namaruangan : '' }}
                            </span>
                        </div>
                        <div class="tile-grid tile-grid-v2">
                            <!--List Empty Search Placeholder -->
                            <VPlaceholderPage :class="[dataKamar.length !== 0 && 'is-hidden']"
                                title="We couldn't find any matching results." subtitle="Too bad. Looks like we couldn't find any matching results for the
                                search terms you've entered. Please try different search terms or
                                criteria." larger>
                                <template #image>
                                    <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png"
                                        alt="" />
                                    <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                        alt="" />
                                </template>
                            </VPlaceholderPage>

                            <!--Tile Grid v1-->
                            <div name="list" tag="div" class="columns is-multiline">
                                <!--Grid item-->
                                <div class="columns is-multiline p-2" style="max-height:500px;overflow: auto;">
                                    <div v-for="item in dataKamar" :key="item.id" class="column is-12">
                                        <div class="tile-grid-item">
                                            <div class="tile-grid-item-inner">
                                                <VAvatar size="small" picture="/images/avatars/svg/roo.png" color="primary"
                                                    squared bordered />
                                                <div class="meta">
                                                    <span class="dark-inverted">{{ item.namaruangan }}</span>
                                                    <span>{{ item.namakamar }}</span>
                                                    <span>{{ item.namakelas }}</span>
                                                </div>
                                                <!-- <VTag :label="item.kosong + ' Terpakai'" color="danger" rounded /> -->
                                                <VTag :label="item.isi + ' Tersedia'"
                                                    :color="item.isi == 0 ? 'danger' : 'success'" style="margin-left: auto; font-size: 14px;"
                                                    rounded /><br><br>

                                                <VTag :label="'Bed: ' + item.bed"
                                                    color="primary" style="margin-left: auto; font-size: 14px; margin-top: 10px; white-space: pre-wrap; height: auto"
                                                    rounded />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- <VModal :open="modalAsuransi" title="Surat Eligibilitas Peserta" :noclose="false" size="big" actions="right"
    @close="modalAsuransi = false">
    <template #content>
        <PemakaianAsuransi></PemakaianAsuransi>
    </template>
    <template #action>
      <VButton icon="feather:save" @click="saveTglPelayanan()" :loading="isLoadingPop" color="primary" raised>Simpan
      </VButton>
                                                                                        </template>
                                                                                      </VModal> -->
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
import InfoPasien from '/@src/pages/include/info-pasien.vue'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from "primevue/useconfirm"

useHead({
    title: 'Mutasi Pasien - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let NOREC_APD = useRoute().query.norec_apd as string
let NOREGISTRASI = "";

const cetakIdentitasPasien = async (e: any) => {
  H.printBlade(`dashboard/registrasi/cetak-identitas-pasien?pdf=true&noregistrasi=${e.NOREGISTRASI}`, 'TRACER GANJIL', 1)
}
const cetakLabelPrev = async (e: any) => {
  modalJumlahLabel.value = true
}
const cetakLabel = async (e: any) => {
  H.printBlade(`dashboard/registrasi/cetak-label-pasien?pdf=true&noregistrasi=${item.NOREGISTRASI}`, 'LABEL PASIEN', 1);
}


const item: any = reactive({
    tglregistrasi: new Date(),
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: NOREC_APD != undefined ? NOREC_APD : '',
    idKelompokPasienBPJS: [],
    idKelompokPasienUMUM: null,
    filterTgl: new Date(),
    NOREGISTRASI: NOREGISTRASI != undefined ? NOREGISTRASI : '',
    israwatgabung: false
})

const confirm = useConfirm();
const pasien: any = ref({})
const d_Ruangan: any = ref([])
const d_AsalRujukan: any = ref([])
const d_KelompokPasien: any = ref([])
const d_JenisPelayanan: any = ref([])
let dataKamar: any = ref([])
const d_Rekanan: any = ref([])
const d_Dokter: any = ref([])
const d_Kelas: any = ref([])
const d_Kamar: any = ref([])
const d_KelasDefault: any = ref([])
const d_TempatTidur: any = ref([])
const isLoading: any = ref(false)
const isLoadingTT: any = ref(false)
const isLoadingPasien: any = ref(false)
const modalAsuransi: any = ref(false)
let sourceRuangan: any = ref([])
const { y } = useWindowScroll()
const router = useRouter()
const isStuck = computed(() => {
    return y.value > 30
})

const fetchDetail = async () => {
    let ruanganid = ''
    if (sourceRuangan.value != undefined) {
        let itemsRuang = []
        sourceRuangan.value.forEach((element: any) => {
            itemsRuang = [...new Set([...itemsRuang, element.value])]
        });
        ruanganid = `ruanganfk=${itemsRuang}`
    }

    let tgl = item.filterTgl ? `&tgl=${H.formatDate(item.filterTgl, 'YYYY-MM-DD')}` : ''

    dataKamar.value = []
    isLoading.value = true
    const response = await useApi().get(`/dashboard/detail-rencana-mutasi?${ruanganid}${tgl}&limit=10`)
    isLoading.value = false
    dataKamar.value = response.data
    console.log(dataKamar)
    item.value.totalRencanaMutasi = response.totalRencanaMutasi
    item.value.totalBelumMutasi = response.totalBelumMutasi
    item.value.totalSudahMutasi = response.totalSudahMutasi
    item.value.totalDitolak = response.totalDitolak

}

function checkedTitip(e: any) {
    console.log(item.value.isKelasTitip)
}

const fetchDaftarKamar = async () => {
    let namakamar = ''
    if (item.filters != undefined) {
        namakamar = item.filters
    }

    dataKamar.value = []

    const response = await useApi().get(
        '/dashboard/detail-rencana-mutasi?namakamar=' + namakamar + '&limit=10'
    )
    dataKamar.value = response.data
    item.value.totalRencanaMutasi = response.totalRencanaMutasi
    item.value.totalBelumMutasi = response.totalBelumMutasi
    item.value.totalSudahMutasi = response.totalSudahMutasi
    item.value.totalDitolak = response.totalDitolak
}

const pasienByID = (id: any) => {
    isLoadingPasien.value = true
    useApi().get(
        `/registrasi/head-mutasi?nocmfk=${id}&norec_pd=${item.NOREC_PD}`).then((response: any) => {
            pasien.value = response.pasien
            item.NOREC_PD = response.last_registrasi.norec_pd
            item.NOREC_APD = response.last_registrasi.norec_apd
            item.RUANGAN_LAST = response.last_registrasi.objectruanganlastfk
            d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
            d_AsalRujukan.value = response.asalrujukan.map((e: any) => { return { label: e.asalrujukan, value: e.id, default: e } })
            d_KelompokPasien.value = response.kelompokpasien.map((e: any) => { return { label: e.kelompokpasien, value: e.id, default: e } })
            d_KelasDefault.value = response.kelas.map((e: any) => { return { label: e.namakelas, value: e.id, default: e } })
            d_JenisPelayanan.value = response.jenispelayanan
            item.idKelompokPasienBPJS = response.idKelompokPasienBPJS
            item.idKelompokPasienUMUM = response.idKelompokPasienUMUM
            // item.jenispelayanan = response.jenispelayanan[0].id
            for (let x = 0; x < response.asalrujukan.length; x++) {
                const element = response.asalrujukan[x];
                if (element.asalrujukan.toLowerCase() == 'datang sendiri') {
                    item.asalrujukan = element.id
                    break
                }
            }

            let dataRegistrasi = response.registrasi[0]
            item.jenispelayanan = dataRegistrasi.jenispelayananfk
            item.kelompokpasien = dataRegistrasi.objectkelompokpasienlastfk
            changeKelompok(item.kelompokpasien)
            item.rekanan = dataRegistrasi.objectrekananfk
            // response.jenispelayanan.forEach(element => {
            //     if(element.id == dataRegistrasi.jenispelayananfk){
            //         item.jenispelayanan = element.id
            //     }else{
            //         console.log('ddd')
            //     }
            // });
            console.log(item.rekanan)
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
// const changeSwitch = (e: any) => {
//     delete item.ruangan
//     d_Ruangan.value = []
//     if (e == true) { d_Ruangan.value = d_RuanganRI.value } else { d_Ruangan.value = d_RuanganRI.value }
// }
const changeRuang = (e: any) => {
    item.kelas = null
    item.kelasRawat = null
    item.kamar = null
    item.bed = null
    if (e) {
        if (item.ruangan != null) {
            setKelas(e)
        }
    }
}
const setKelas = (e: any) => {
    isLoadingTT.value = true
    d_Kelas.value = []
    useApi().get(
        `/registrasi/list-kelas-mutasi?id=${e}`)
        .then((response: any) => {
            isLoadingTT.value = false
            item.kelas = response[0].id
            d_Kelas.value = response.map((e: any) => { return { label: e.namakelas, value: e.id, default: e } })
        })
        .catch((error: any) => { isLoadingTT.value = false })
}
const changeKelas = (e: any) => {
    d_Kamar.value = []
    delete item.kamar
    let isRG = ''
    if (item.israwatgabung) { isRG = '&isRG=true' }
    if (e && item.ruangan) {
        isLoadingTT.value = true
        useApi().get(
            `/registrasi/list-kamar-mutasi?id=${e}&idRuangan=${item.ruangan}&isRG=${item.israwatgabung}`)
            .then((response: any) => {
                isLoadingTT.value = false
                d_Kamar.value = response.map((e: any) => { return { label: e.namakamar, value: e.id, default: e } })
            })
            .catch((error: any) => { isLoadingTT.value = false })
    }

}
const changeKamar = (e: any) => {
    d_TempatTidur.value = []
    if (e) {
        for (let x = 0; x < d_Kamar.value.length; x++) {
            const element = d_Kamar.value[x];
            if (element.value == e) {
                d_TempatTidur.value = element.default.details.map((e: any) => { return { label: e.reportdisplay, value: e.id, default: e } })
            }
        }
    }
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
            `/registrasi/penjamin-mutasi?id=${e}`)
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


const saveMutasi = async () => {
    console.log(item.isKelasTitip)
    if (!item.tglregistrasi) { H.alert('warning', 'Tgl Registrasi harus di isi'); return }
    if (!item.ruangan) { H.alert('warning', 'Ruangan harus di isi'); return }
    if (!item.asalrujukan) { H.alert('warning', 'Asal Rujukan  harus di isi'); return }
    if (!item.kelompokpasien) { H.alert('warning', 'Pembiayaan harus di isi'); return }
    if (!item.jenispelayanan) { H.alert('warning', 'Tipe Layanan harus di isi'); return }
    if (!item.kelas) { H.alert('warning', 'Kelas harus di isi'); return }
    if (!item.kelasRawat) { H.alert('warning', 'Kelas rawat harus di isi'); return }
    if (!item.kamar) { H.alert('warning', 'Kamar harus di isi'); return }
    if (!item.bed && !item.israwatgabung) { H.alert('warning', 'Bed harus di isi'); return }

    let json = {
        'pasiendaftar': {
            'norec_pd': item.NOREC_PD,
            'nocmfk': ID_PASIEN,
            'objectruangantujuanfk': item.ruangan,
            'asalrujukanfk': item.asalrujukan,
            'jenispelayananfk': item.jenispelayanan,
            'objectkelompokpasienlastfk': item.kelompokpasien,
            'objectpegawaifk': item.dokter ? item.dokter.id : null,
            // 'objectkelasfk': item.kelas ? item.kelas : null,
            // 'objectkelasrawatfk': item.kelasRawat ? item.kelasRawat : null,
            'objectkelasfk': item.kelasRawat ? item.kelasRawat : null,
            'objectkelasrawatfk': item.kelas ? item.kelas : null,
            'iskelastitip': item.isKelasTitip ? item.isKelasTitip : null,
            'isnaikkelas': item.isNaikKelas ? item.isNaikKelas : null,
            'norec': item.NOREC_PD ? item.NOREC_PD : '',
            'tglregistrasi': H.formatDate(item.tglregistrasi, 'YYYY-MM-DD HH:mm:ss'),
            'objectruanganlastfk': item.ruangan,
            'keteranganasalrujukan': item.keteranganasalrujukan ? item.keteranganasalrujukan : null,
            'objectpegawairawatbersamafk': item.dokterRawatBersama ? item.dokterRawatBersama.id : null,
            'israwatinap': item.isRawatInap ? item.isRawatInap : false,
            'catatan': item.catatan ? item.catatan : null,
            'statuspasien': item.statuspasien ? item.statuspasien : 'LAMA',//item.statuspasien ? item.statuspasien : 'LAMA',
            'objectrekananfk': item.rekanan != undefined ? item.rekanan : null,
            'nocm': pasien.value.nocm,
            'namapasien': pasien.value.namapasien,
        },
        'antrianpasiendiperiksa': {
            'norec_apd': item.NOREC_APD,
            'noregistrasifk' : item.NOREC_PD,
            'objectruanganasalfk': item.RUANGAN_LAST,
            'objectruangantujuanfk' : item.ruangan,
            'objectasalrujukanfk' : item.asalrujukan,
            'tglregistrasi': H.formatDate(item.tglregistrasi, 'YYYY-MM-DD HH:mm:ss'),
            // 'objectkelasfk': item.kelas,
            // 'objectkelasrawatfk': item.kelasRawat,
            'objectkelasfk': item.kelasRawat,
            'objectkelasrawatfk': item.kelas,
            'objectkamarfk': item.kamar,
            'objectbedfk' : item.bed,
            'objectpegawaifk' : item.dokter ? item.dokter.id : null,
            'israwatgabung': item.israwatgabung ? item.israwatgabung : null
        }
    }
    console.log(json)
    confirm.require({
        header: 'Konfirmasi Hak Kelas & Hak Rawat Inap Pasien',
        group: 'confirm',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: async () => {
            isLoading.value = true
            await useApi().post(`/registrasi/save-mutasi`, json).then((response: any) => {
            isLoading.value = false
            // item.NOREC_PD = response.last_registrasi.norec_pd
            // item.NOREC_APD = response.last_registrasi.norec_apd
            item.NOREGISTRASI = response.registrasi.apd.noregistrasi
            for (let xx = 0; xx < item.idKelompokPasienBPJS.length; xx++) {
                const element = item.idKelompokPasienBPJS[xx];
                if (element == item.kelompokpasien) {
                    tambahAsuransi(item.NOREC_PD)
                }
            }
            }).catch((e: any) => {
                if (e.message == 'Bed Sudah Terisi, Silakan Pilih Bed Lain') {
                    changeKelas(item.kelasRawat)
                }
                isLoading.value = false
                console.clear()
                console.log(e)
        })},
        reject: () => { },
    })
}
const fetchDokter = async (filter: any) => {
    if (!filter.query) {

    }

    const response = await useApi().get(
        `/registrasi/dokter-mutasi?name=${filter.query}&limit=10`)
    d_Dokter.value = response.dokter
    // return response.dokter.map((item: any) => {
    //     return { value: item.id, label: item.namalengkap, default: item }
    // })
}
const tambahAsuransi = (norec_pd: any) =>{
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
pasienByID(ID_PASIEN)
fetchDetail()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
</style>
