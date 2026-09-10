<template>
    <div>
        <div class="form-layout is-stacked ">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Tagihan Non Layanan</h3>
                        </div>
                        <div class="right">
                            <div class="buttons">
                                <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembali()">
                                    Kembali
                                </VButton>
                                <VButton v-if="item.DETAIL == false" type="button" rounded outlined color="primary"
                                    raised icon="feather:save" :loading="isLoading" @click="simpan()"> Simpan
                                </VButton>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-body p-2 ">
                    <div class="business-dashboard hr-dashboard">

                        <div class="stepper-form">
                            <div class="form-sections-2">
                                <img src="/images/avatars/label/validasi.png" slt="image status keluar" style="
    margin-left: 12px;
    margin-top: -150px;
    margin-bottom: -34px;
    filter: opacity(0.8);
    max-width: 41%;">
                                <Transition name="fade-slow">
                                    <div id="form-step-1" class="form-section is-active">
                                        <h3 class="form-section-title">
                                            <span>Info Pelanggan</span>
                                            <button type="button" class="help-button" @keydown.space.prevent="currentHelp === 1 ? (currentHelp = -1) : (currentHelp = 1)
                                                " @click="currentHelp === 1 ? (currentHelp = -1) : (currentHelp = 1)">
                                                <i aria-hidden="true" class="iconify"
                                                    data-icon="feather:help-circle"></i>
                                            </button>
                                        </h3>

                                        <div class="form-section-inner">
                                            <div class="columns is-multiline">
                                                <div class="column is-6 pt-0 pb-0">
                                                    <VField>
                                                        <VLabel class="required-field">Tanggal</VLabel>
                                                        <VDatePicker v-model="item.tglstruk" mode="dateTime"
                                                            style="width: 100%;">
                                                            <template #default="{ inputValue, inputEvents }">
                                                                <VField>
                                                                    <VControl icon="feather:calendar" fullwidth>
                                                                        <VInput :value="inputValue"
                                                                            placeholder="Tanggal" v-on="inputEvents"
                                                                            :disabled="item.DETAIL" />
                                                                    </VControl>
                                                                </VField>
                                                            </template>
                                                        </VDatePicker>
                                                    </VField>
                                                </div>
                                                <div class="column is-6 pt-0 pb-0">
                                                    <VField>
                                                        <VLabel class="required-field">No Telepon</VLabel>
                                                        <VControl icon="feather:phone">
                                                            <VInput type="text" placeholder="No Telepon"
                                                                v-model="item.noteleponfaks" :disabled="item.DETAIL" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-6 pt-0 pb-0">
                                                    <VField>
                                                        <VLabel>No CM</VLabel>
                                                        <VControl icon="feather:book">
                                                            <VInput type="text" placeholder="Enter Untuk Cek Data"
                                                                v-model="item.nocm" @keyup.enter="cariPasien(item.nocm)"
                                                                :disabled="item.DETAIL" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-6 pt-0 pb-0">
                                                    <VField>
                                                        <VLabel class="required-field">Nama Pelanggan</VLabel>
                                                        <VControl icon="feather:user">
                                                            <VInput type="text" placeholder="Nama Pelanggan"
                                                                v-model="item.namapasien_klien"
                                                                :disabled="item.DETAIL" />
                                                        </VControl>
                                                    </VField>
                                                </div>

                                                <div class="column is-6 pt-0 pb-0">
                                                    <VField label="Jenis Transaksi" class=" is-autocomplete-select"
                                                        v-slot="{ id }">
                                                        <VControl icon="fas fa-credit-card" fullwidth
                                                            class="prime-auto-select">
                                                            <Dropdown v-model="item.kelompokTransaksi"
                                                                :options="d_KelompokTransaksi"
                                                                :optionLabel="'kelompoktransaksi'"
                                                                placeholder="Jenis Transaksi" style="width: 100%;"
                                                                :filter="true" :showClear="true"
                                                                :disabled="item.DETAIL" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-6 pt-0 pb-0">
                                                    <VField label="Jenis Pembiayaan" class=" is-autocomplete-select"
                                                        v-slot="{ id }">
                                                        <VControl icon="fas fa-calculator" fullwidth
                                                            class="prime-auto-select">
                                                            <Dropdown v-model="item.kelompokpasien"
                                                                :options="d_KelompokPasien"
                                                                :optionLabel="'kelompokpasien'"
                                                                placeholder="Jenis Pembiayaan" style="width: 100%;"
                                                                :filter="true"
                                                                @change="changeKelompok(item.kelompokpasien)"
                                                                :showClear="true" :disabled="item.DETAIL" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-6 pt-0 pb-0">
                                                    <VField v-if="item.kelompokpasien" label="Penjamin"
                                                        class="is-rounded-select_Z  is-autocomplete-select"
                                                        v-slot="{ id }">
                                                        <VControl icon="feather:command" fullwidth>
                                                            <!-- <Dropdown v-model="item.rekanan"
                                                                :options="d_Rekanan" :optionLabel="'kelompokpasien'"
                                                                placeholder="Penjamin " style="width: 100%;"
                                                                :filter="true"
                                                                :showClear="true" /> -->
                                                            <Multiselect mode="single" v-model="item.rekanan"
                                                                :options="d_Rekanan" placeholder="Pilih data"
                                                                :searchable="true" :attrs="{ id }" autocomplete="off"
                                                                :loading="isLoading" :disabled="item.DETAIL" />
                                                        </VControl>
                                                    </VField>
                                                </div>

                                                <div class="column is-11" v-if="!item.DETAIL">
                                                    <VField>
                                                        <VControl>
                                                            <button type="button" class="input-button">
                                                                <i aria-hidden="true" class="iconify"
                                                                    data-icon="feather:plus"></i>
                                                                <span>Tambah Layanan</span>

                                                            </button>
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-11" v-if="item.DETAIL">
                                                    <VField>
                                                        <VControl>
                                                            <button type="button" class="input-button">
                                                                <i aria-hidden="true" class="iconify"
                                                                    data-icon="feather:plus"></i>
                                                                <span>Detail Layanan</span>

                                                            </button>
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-1">
                                                    <div class="participants">
                                                        <Tippy class="has-help-cursor" interactive
                                                            placement="top-start">
                                                            <VAvatar color="danger" :initials="listItem.length" style="    width: 40px;
    min-width: 40px;
    height: 40px;
    object-fit: cover;" />
                                                        </Tippy>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="column is-6">
                                            </div>
                                            <div class="fieldset  is-horizontal" v-if="!item.DETAIL">
                                                <VField>
                                                    <VLabel class="mt-1">Dokter</VLabel>
                                                    <VControl class="prime-auto">
                                                    <AutoComplete v-model="item.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                                        :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" style="width: 74%;"/>
                                                    </VControl>
                                                </VField>
                                                <VField grouped>
                                                    <VLabel class="required-field mt-1">Pelayanan</VLabel>
                                                    <VControl expanded>
                                                        <AutoComplete v-if="!item.isFreeText" v-model="item.pelayanan"
                                                            :suggestions="d_Pelayanan" @complete="fetchLayanan($event)"
                                                            :optionLabel="'namaproduk'" :dropdown="true" :minLength="3"
                                                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                            forceSelection :field="'namaproduk'"
                                                            placeholder="ketik untuk mencari Pelayanan..."
                                                            @item-select="changeLayanan($event)">
                                                            <template #item="slotProps">
                                                                <div class="flex align-items-center country-item">
                                                                    <i class="fas fa-layer-group mr-2 mt-1"
                                                                        aria-hidden="true"></i>
                                                                    <table class="tb-custom">
                                                                        <tr>
                                                                            <td> {{ slotProps.item.namaproduk }}</td>
                                                                            <td width="10%"> {{
                                                                                H.formatRp(slotProps.item.harga, 'Rp.')
                                                                                }}</td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </template>
                                                        </AutoComplete>

                                                        <VInput v-else type="text" v-model="item.pelayananText"
                                                            placeholder="Masukan Nama Pelayanan" />

                                                    </VControl>
                                                    <VControl subcontrol>
                                                        <VSwitchBlock v-model="item.isFreeText" label="Tagihan Bebas"
                                                            color="danger" />
                                                        <!-- <VSwitchSegment label-true="Tagihan Bebas"   @click="changeTag(item.isFreeText)"  v-model="item.isFreeText" label-false="Master Tarif" /> -->
                                                    </VControl>
                                                </VField>
                                                <VField grouped>
                                                    <VLabel class="required-field mt-1">Jumlah</VLabel>
                                                    <VControl expanded>

                                                        <InputNumber inputId="minmax-buttons" v-model="item.jumlah"
                                                            mode="decimal" showButtons :min="1" />
                                                        <!-- <VInput type="number" v-model="item.jumlah" placeholder="Jumlah" /> -->
                                                    </VControl>
                                                    <VControl subcontrol>
                                                        <!-- <VCheckbox> Primary </VCheckbox> -->
                                                    </VControl>
                                                </VField>
                                                <VField grouped>
                                                    <VLabel class="required-field mt-1">Harga</VLabel>
                                                    <VControl expanded icon="fas fa-calculator">
                                                        <VInput type="text" v-model="item.hargasatuanRp"
                                                            placeholder="Harga" :disabled="!item.isFreeText"
                                                            v-mask-currency
                                                            v-on:input="changeNomi(item.hargasatuanRp)" />

                                                    </VControl>
                                                    <VControl subcontrol></VControl>
                                                </VField>
                                                <VField grouped>
                                                    <VLabel class="required-field mt-1">Total</VLabel>
                                                    <VControl expanded icon="fas fa-file-invoice-dollar">
                                                        <VInput type="text" v-model="item.totalRp" placeholder="Total"
                                                            disabled />
                                                    </VControl>
                                                    <VControl subcontrol></VControl>
                                                </VField>
                                                <VField grouped>
                                                    <VControl expanded>
                                                        <VTextarea class="textarea" v-model="item.keterangan" rows="2"
                                                            placeholder="Keterangan ..." autocomplete="off"
                                                            autocapitalize="off" spellcheck="true" />
                                                    </VControl>
                                                    <VControl subcontrol></VControl>
                                                </VField>
                                                <VField>
                                                    <div class="buttons mt-5-min">
                                                        <VButton icon="lnir lnir-plus rem-100  " @click="addNewItem()">
                                                            {{ item.no == undefined ? 'Tambah' : 'Ubah' }}
                                                        </VButton>
                                                    </div>
                                                </VField>
                                            </div>
                                        </div>

                                        <div class="form-section-output" v-if="listItem.length > 0">
                                            <div class="output  c-title-2" v-for="(items, index) in listItem"
                                                :key="index">
                                                <i aria-hidden="true" class="fas fa-layer-group mr-2"></i>
                                                <span class="is-fullwidth mt-2">{{ items.namaproduk }}
                                                    <div>
                                                        <table class="is-fullwidth tb-custom mb-2">
                                                            <tr>
                                                                <td width="10%" style="  padding: 0;">
                                                                    <span class="td-label-xxx">Jumlah </span>
                                                                </td>
                                                                <td style="  padding: 0;" width="10px"> : </td>
                                                                <td style="  padding: 0;">
                                                                    <span class="td-label-xxx">{{ items.jumlah }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="  padding: 0;" width="10%">
                                                                    <span class="td-label-xxx">Harga </span>
                                                                </td>
                                                                <td style="  padding: 0;" width="10px"> : </td>
                                                                <td style="  padding: 0;">
                                                                    <span class="td-label-xxx">{{
                                                                        H.formatRp(items.harga,
                                                                        'Rp.') }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding: 0;" width="10%">
                                                                    <span class="td-label-xxx">Total </span>
                                                                </td>
                                                                <td style="padding: 0;" width="10px"> : </td>
                                                                <td style="padding: 0;">
                                                                    <span class="td-label-xxx">{{
                                                                        H.formatRp(items.total,
                                                                        'Rp.') }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding: 0;" width="10%">
                                                                    <span class="td-label-xxx">Dokter </span>
                                                                </td>
                                                                <td style="padding: 0;" width="10px"> : </td>
                                                                <td style="padding: 0;">
                                                                    <span class="td-label-xxx">{{items.DDDokter != undefined && items.DDDokter != null ? items.DDDokter.label : '-'}}</span>
                                                                </td>
                                                            </tr>
                                                        </table>

                                                    </div>
                                                </span>

                                                <div class="action is-pulled-right" v-if="!item.DETAIL">
                                                    <VIconButton icon="feather:edit-2" @click="editItem(items)" />
                                                </div>
                                                <div class="action" style="margin-left:-0;" v-if="!item.DETAIL">
                                                    <VIconButton icon="feather:trash-2" @click="removeItem(index)" />
                                                </div>
                                            </div>
                                            <div class="columns is-multiline mb-2">
                                                <div class="column is-5 is-offset-7 mt-2" style="margin-top:-10px">
                                                    <VCardCustom
                                                        :style="'padding:5px 25px;margin:0;background:#fafafa'">
                                                        <div :class="'label-status danger'">
                                                            <i aria-hidden="true" class="fas fa-circle"></i>
                                                            <span class="ml-1">TOTAL</span>
                                                        </div>
                                                        <small class="text-bold-custom" style=" font-size: 1.5rem;">{{
                                                            H.formatRp(item.totalAll,
                                                                'Rp.') }}</small>
                                                    </VCardCustom>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-section-output" v-else>
                                            <VCard custom="card-grey">
                                                <div class=" search-results-wrapper">
                                                    <div class="search-results-body ">
                                                        <div class="page-placeholder">
                                                            <div class="placeholder-content">
                                                                <img class="light-image" style=" max-width: 140px;"
                                                                    :src="H.assets().iconNotFound_rev" alt="" />
                                                                <img class="dark-image" style=" max-width: 140px;"
                                                                    :src="H.assets().iconNotFound_rev" alt="" />
                                                                <h3>{{ H.assets().notFound }}</h3>
                                                                <p class="is-larger">
                                                                    {{ H.assets().notFoundSubtitle }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </VCard>
                                        </div>
                                    </div>
                                </Transition>
                            </div>

                            <div class="form-stepper">
                                <ul v-if="currentHelp === -1" class="steps is-vertical is-thin is-short">
                                    <li id="step-segment-0" :class="[currentStep === 0 && 'is-active']"
                                        class="steps-segment" tabindex="0" @keydown.space.prevent="currentStep >= 0 && scrollTo('#form-step-0', 800, { offset: -20 })
                                            "
                                        @click.prevent="currentStep >= 0 && scrollTo('#form-step-0', 800, { offset: -20 })">
                                        <a href="#" class="steps-marker"></a>
                                        <div class="steps-content">
                                            <p class="step-number">1.</p>
                                            <p class="step-info"> Info Pelanggan</p>
                                        </div>
                                    </li>
                                    <li v-if="!item.DETAIL" id="step-segment-1"
                                        :class="[currentStep === 1 && 'is-active']" class="steps-segment" tabindex="0"
                                        @keydown.space.prevent="currentStep >= 1 && scrollTo('#form-step-1', 800, { offset: -20 })
                                            "
                                        @click.prevent="currentStep >= 1 && scrollTo('#form-step-1', 800, { offset: -20 })">
                                        <a href="#" class="steps-marker"></a>
                                        <div class="steps-content">
                                            <p class="step-number">2.</p>
                                            <p class="step-info">Tambah Layanan</p>
                                        </div>
                                    </li>
                                    <li id="step-segment-2" :class="[currentStep === 2 && 'is-active']"
                                        class="steps-segment" tabindex="0" @keydown.space.prevent="currentStep >= 2 && scrollTo('#form-step-2', 800, { offset: -20 })
                                            "
                                        @click.prevent="currentStep >= 2 && scrollTo('#form-step-2', 800, { offset: -20 })">
                                        <a href="#" class="steps-marker"></a>
                                        <div class="steps-content">
                                            <p class="step-number">{{ item.DETAIL == true ? '2.' : '3.' }}</p>
                                            <p class="step-info">Data Layanan</p>
                                        </div>
                                    </li>

                                </ul>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { useApi } from '/@src/composable/useApi'
import { nextTick, ref, computed, reactive } from 'vue'
import VueScrollTo from 'vue-scrollto'
import { useWindowScroll } from '@vueuse/core'
import { useToaster } from '/@src/composable/toaster'
import sleep from '/@src/utils/sleep'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Dropdown from 'primevue/dropdown';
import AutoComplete from 'primevue/autocomplete';
import * as H from '/@src/utils/appHelper'
import { watch } from 'vue'
import InputNumber from 'primevue/inputnumber';
useHead({
    title: 'Tagihan Non Layanan - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

let NOREC_SP = useRoute().query.norec_sp as string
let DETAIL = useRoute().query.detail as string
// let isLoadingNIKS = ref(false)
const router = useRouter()
// const notyf = useNotyf()
const { scrollTo } = VueScrollTo
const currentStep = ref(0)
const isLoading = ref(false)
const currentHelp = ref(-1)
const d_KelompokTransaksi = ref([])
const d_KelompokPasien = ref([])
const d_Rekanan = ref([])
const d_Pelayanan = ref([])
const taxType = ref('')
const isHide = ref(true)
const { y } = useWindowScroll()
const listItem: any = ref([])
const d_Dokter: any = ref([])
const isStuck = computed(() => {
    return y.value > 30
})

const item: any = reactive({
    tglstruk: new Date(),
    jumlah: 1,
    totalAll: 0,
    DETAIL: DETAIL != undefined ? true : false
})

const fetchDokter = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`
    ).then((response) => {
        d_Dokter.value = response
    })
}
const loadEdit = async (NOREC_SP: any) => {
    if (NOREC_SP == undefined) return
    await useApi().get(
        `/kasir/tagihan-non-layanan?norec_sp=${NOREC_SP}`)
        .then((response: any) => {
            item.tglstruk = new Date(response.strukpelayanan.tglstruk)
            item.keteranganlainnya = response.strukpelayanan.keteranganlainnya
            item.namapasien_klien = response.strukpelayanan.namapasien_klien
            item.noteleponfaks = response.strukpelayanan.noteleponfaks
            d_KelompokTransaksi.value.forEach((e: any) => {
                if (e.id == response.strukpelayanan.objectkelompoktransaksifk) {
                    item.kelompokTransaksi = e
                    return
                }
            });
            d_KelompokPasien.value.forEach((e: any) => {
                if (e.id == response.strukpelayanan.objectkelompokpasienfk) {
                    item.kelompokpasien = e
                    changeKelompok(item.kelompokpasien)
                    return
                }
            });
            d_Rekanan.value.forEach((e: any) => {
                if (e.id == response.strukpelayanan.objectrekananfk) {
                    item.rekanan = e
                    return
                }
            });
            for (let x = 0; x < response.strukpelayanandetail.length; x++) {
                const element = response.strukpelayanandetail[x];
                element.DDDokter = { label: element.dokter, value: element.id_dokter }
                if (element.namaproduk_freetext != null) {
                    element.freetext = true
                    element.namaproduk = element.namaproduk_freetext
                }

            }

            listItem.value = response.strukpelayanandetail
            countTotalAll()
        })
}
function cariPasien(e) {
    isLoading.value = true;
    useApi()
        .get('emr/get-pasien-by-nocm?nocm=' + e)
        .then((response) => {
            if (!response[0]) {
                H.alert('error', "DATA PASIEN TIDAK ADA");
            } else {
                const pasienData = response[0];
                item.namapasien_klien = pasienData.namapasien;
            }
        })
        .finally(() => {
            isLoading.value = false;
        });
}

const validateStep = async () => {
    if (currentStep.value === 4) {
        if (isLoading.value) {
            return
        }

        isLoading.value = true
        notyf.success('Your shipment is successfully stored!')
        await sleep(1000)

        router.push({
            name: 'sidebar-dashboards',
        })
        return
    }

    isLoading.value = true
    await sleep(400)
    currentStep.value += 1

    nextTick(() => {
        scrollTo(`#form-step-${currentStep.value}`, 1000)
        isLoading.value = false
    })
}
const loadDropdown = async () => {
    await useApi().get(
        `/kasir/tagihan-non-layanan/dropdown`)
        .then((response: any) => {
            item.pelayananBebas = response.idPelayananLainnyaNonLayanan
            d_KelompokPasien.value = response.kelompokpasien
            d_KelompokTransaksi.value = response.kelompoktransaksi

            d_KelompokTransaksi.value.forEach((e: any) => {
                if (e.kelompoktransaksi == 'PELAYANAN IN PASIEN') {
                    item.kelompokTransaksi = e
                    return
                }
            });
        })
}
const changeKelompok = async (e: any) => {
    d_Rekanan.value = []

    delete item.rekanan
    if (e) {
        isLoading.value = true
        await useApi().get(
            `/kasir/tagihan-non-layanan/penjamin-by-kelompokpasien?id=${e.id}`)
            .then((response: any) => {
                isLoading.value = false
                if (response.length > 0) {
                    d_Rekanan.value = response.map((e: any) => { return { label: e.namarekanan, value: e.id, default: e } })
                    if (response.length == 1) {
                        item.rekanan = response[0].id
                    } else {
                        // if (item.idKelompokPasienBPJS == e) {
                        for (let z = 0; z < d_Rekanan.value.length; z++) {
                            const element: any = d_Rekanan.value[z];
                            if (element.label.toLowerCase().indexOf('kesehatan') > -1) {
                                item.rekanan = element.value
                                break
                            }
                        }
                        // }
                    }
                }
            })
            .catch((error: any) => { isLoading.value = false })
    }
}
const fetchLayanan = async (filter: any) => {
    if (!filter.query) {

    }
    const response = await useApi().get(
        `/kasir/tagihan-non-layanan/pelayanan?namaproduk=${filter.query}&limit=10`)
    d_Pelayanan.value = response.data
}
const changeLayanan = (e: any) => {
    item.hargasatuan = e.value.harga
    item.hargasatuanRp = H.formatRp(item.hargasatuan, '')
    countTotal()
}

const addNewItem = () => {
    if (item.isFreeText) {
        if (!item.pelayananText) {
            useToaster().error('Pelayanan harus di isi')
            return
        }
        if (!item.hargasatuan) {
            useToaster().error('Harga harus di isi')
            return
        }
    } else {
        if (!item.pelayanan) {
            useToaster().error('Pelayanan harus di isi')
            return
        }
    }

    let nomor = 0
    if (listItem.value.length == 0) {
        nomor = 1
    } else {
        nomor = listItem.value.length + 1
    }
    let data: any = {};
    if (item.no != undefined) {
        for (let x = 0; x < listItem.value.length; x++) {
            const element = listItem.value[x];
            if (element.no == item.no) {
                data.no = item.no
                data.produkfk = item.isFreeText ? item.pelayananBebas : item.pelayanan.id
                data.namaproduk = item.isFreeText ? item.pelayananText : item.pelayanan.namaproduk
                data.jumlah = parseFloat(item.jumlah)
                data.qtyoranglast = item.qtyPerOrang ? parseFloat(item.qtyPerOrang) : 0
                data.harga = parseFloat(item.hargasatuan)
                data.total = item.total
                data.keterangan = item.keterangan ? item.keterangan : null
                data.DDDokter = item.DDDokter ? item.DDDokter : null
                data.freetext = item.isFreeText ? item.isFreeText : false
                listItem.value[x] = data;
            }
        }
    } else {
        data = {
            no: nomor,
            produkfk: item.isFreeText ? item.pelayananBebas : item.pelayanan.id,
            namaproduk: item.isFreeText ? item.pelayananText : item.pelayanan.namaproduk,
            jumlah: parseFloat(item.jumlah),
            qtyoranglast: item.qtyPerOrang ? parseFloat(item.qtyPerOrang) : 0,
            harga: parseFloat(item.hargasatuan),
            keterangan: item.keterangan ? item.keterangan : null,
            DDDokter: item.DDDokter ? item.DDDokter : null,
            total: item.total,
            freetext: item.isFreeText ? item.isFreeText : false
        }
        listItem.value.push(data)
    }
    clearInput()
    countTotalAll()
}
const countTotal = () => {

    item.total = parseFloat(item.jumlah) * parseFloat(item.hargasatuan)
    item.totalRp = H.formatRp((isNaN(item.total) ? 0 : item.total), 'Rp.')
}
const countTotalAll = () => {
    item.totalAll = 0
    for (let i = 0; i < listItem.value.length; i++) {
        const element = listItem.value[i];
        item.totalAll = item.totalAll + parseFloat(element.total)
    }
}
const clearInput = () => {
    delete item.pelayanan
    delete item.pelayananText
    delete item.jumlah
    item.jumlah = 1
    delete item.no
    delete item.qtyPerOrang
    delete item.hargasatuan
    delete item.keterangan
    delete item.total
    delete item.hargasatuanRp
    delete item.totalRp
    delete item.DDDokter
}
const removeItem = (index: any) => {
    listItem.value.splice(index, 1)
    countTotalAll()
}
const editItem = async (e: any) => {
    item.no = e.no
    item.isFreeText = e.freetext
    if (e.freetext) {
        item.pelayananText = e.namaproduk
        item.hargasatuanRp = H.formatRupiah(e.harga, '')
        changeNomi(item.hargasatuanRp)
    } else {
        item.pelayanan = { id: e.produkfk, namaproduk: e.namaproduk, harga: e.harga }
        await changeLayanan({
            value: item.pelayanan
        })
        item.hargasatuan = e.harga
    }


    item.keterangan = e.keterangan
    item.total = e.total
    item.DDDokter = e.DDDokter

    item.jumlah = e.jumlah
    item.qtyoranglast = e.qtyoranglast

}
const simpan = async () => {
    if (!item.tglstruk) {
        useToaster().error('Tgl Pelayanan harus di isi')
        return
    }
    if (!item.namapasien_klien) {
        useToaster().error('Nama Pelayanan harus di isi')
        return
    }
    if (!item.noteleponfaks) {
        useToaster().error('No Telepon harus di isi')
        return
    }
    if (!item.kelompokTransaksi) {
        useToaster().error('Jenis Transaksi harus di isi')
        return
    }
    if (listItem.value.length == 0) {
        useToaster().error('Data Layanan harus di isi')
        return
    }
    let json = {
        norec: NOREC_SP ? NOREC_SP : '',
        objectkelompoktransaksifk: item.kelompokTransaksi.id,
        keteranganlainnya: item.keteranganlainnya ? item.keteranganlainnya : null,
        objectkelompokpasienfk: item.kelompokpasien != undefined ? item.kelompokpasien.id : null,
        objectrekananfk: item.rekanan != undefined ? item.rekanan : null,
        namapasien_klien: item.namapasien_klien,
        noteleponfaks: item.noteleponfaks,
        tglstruk: H.formatDate(item.tglstruk, 'YYYY-MM-DD HH:mm:ss'),
        totalharusdibayar: parseFloat(item.totalAll),
        details: listItem.value

    }
    isLoading.value = true
    await useApi().post(`/kasir/tagihan-non-layanan/simpan`, json)
        .then((response: any) => {
            isLoading.value = false
            router.push({
                name: 'module-kasir-pembayaran-tagihan',
                query: {
                    nocmfk: response.nocmfk ?? '',
                    norec_sp: response.norec,
                    pageFrom: 'tagihanNonLayanan'
                },
            })
        }).catch((e: any) => {
            isLoading.value = false
        })
}
const kembali = () => {
    window.history.back()
}
const changeNomi = (e: any) => {
    item.hargasatuan = H.unFormatRupiah(e)
    countTotal()
}


watch(
    () => item.jumlah,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            countTotal()
        }
    }
)
watch(
    () => item.isFreeText,
    (newValue, oldValue) => {
        if (newValue != oldValue) {

        }
    }
)

loadDropdown()
loadEdit(NOREC_SP)
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/module/kasir/tagihan-non-layanan';
</style>
