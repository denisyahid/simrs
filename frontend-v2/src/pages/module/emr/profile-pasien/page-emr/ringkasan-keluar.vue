<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Ringkasan Keluar</h3>
                        </div>
                        <div v-if="kelompokUser.toUpperCase().indexOf('REKAM') > -1">
                            <VButton type="button" rounded outlined color="dark" raised icon="feather:file"
                                @click="kodingDetail()"> Koding Detail
                            </VButton>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
                        </div>
                    </div>
                </div>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                    <h1 style="font-weight: bold;">Tanggal</h1>
                                    <VField>
                                        <VDatePicker v-model="input.tanggalKedatangan" mode="date" trim-weeks
                                            :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField>
                                                    <VControl icon="feather:calendar" fullwidth>
                                                        <VInput :value="inputValue" placeholder="Tanggal"
                                                            v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <h1 style="font-weight: bold;">Jam</h1>
                                    <VField>
                                        <VDatePicker v-model="input.jamKedatangan" color="green" mode="time" is24hr>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField>
                                                    <VControl icon="feather:clock">
                                                        <VInput class="input form-timepicker" :value="inputValue"
                                                            v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                                <div class="column is-5">
                                    <h1 style="font-weight: bold;">Dokter</h1>
                                    <VField>
                                        <VControl icon="" fullwidth class="prime-auto ">
                                            <AutoComplete v-model="input.dpjpUtama" :suggestions="d_Dokter"
                                                @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" placeholder="ketik untuk mencari..." />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12 pt-0">
                                    <div class="column pt-0 pl-0" style="overflow: auto;">
                                        <table class="table-rpo">
                                            <thead>
                                                <tr>
                                                    <th class="th-rpo" width="65%">Diagnosa Sekunder</th>
                                                    <th class="th-rpo" width="20%">Kode ICD</th>
                                                    <th class="th-rpo" width="15%">#</th>
                                                </tr>
                                            </thead>
                                            <tbody v-for="(item, index) in input.detailDS" :key="index">
                                                <tr>
                                                    <td class="td-rpo" style="width: 65%">
                                                        <VField>
                                                            <VControl>
                                                                <VTextarea rows="1" v-model="item.TADiagnosaSekunder">
                                                                </VTextarea>
                                                            </VControl>
                                                        </VField>
                                                    </td>
                                                    <td class="td-rpo" style="width: 20%">
                                                        <VControl>
                                                            <VInput type="text" class="input" v-model="item.TBicdDS" />
                                                        </VControl>
                                                    </td>
                                                    <td class="td-rpo p-0" style="width:15%;">
                                                        <div class="column">
                                                            <VButtons style="justify-content:space-around">
                                                                <VIconButton type="button" raised circle
                                                                    icon="feather:plus" @click="addNewItemDS()"
                                                                    color="info" v-tooltip.bubble="'Tambah '">
                                                                </VIconButton>
                                                                <VIconButton class="mt-1" v-if="index > 0" type="button"
                                                                    raised circle icon="feather:trash"
                                                                    @click="removeItemDS(index)" color="danger">
                                                                </VIconButton>
                                                            </VButtons>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="column is-12 pt-0">
                                    <div class="column pl-0 pt-0" style="overflow: auto;">
                                        <table class="table-rpo">
                                            <thead>
                                                <tr>
                                                    <th class="th-rpo" width="65%">Deskripsi Tindakan</th>
                                                    <th class="th-rpo" width="20%">Kode ICD</th>
                                                    <th class="th-rpo" width="15%">#</th>
                                                </tr>
                                            </thead>
                                            <tbody v-for="(item, index) in input.detailDT" :key="index">
                                                <tr>
                                                    <td class="td-rpo" style="width: 65%">
                                                        <VField>
                                                            <VControl>
                                                                <VTextarea rows="1" v-model="item.TADeskripsiTindakan">
                                                                </VTextarea>
                                                            </VControl>
                                                        </VField>
                                                    </td>
                                                    <td class="td-rpo" style="width: 20%">
                                                        <VControl>
                                                            <VInput type="text" class="input" v-model="item.TBicdDT" />
                                                        </VControl>
                                                    </td>
                                                    <td class="td-rpo p-0" style="width: 15%;">
                                                        <div class="column">
                                                            <VButtons style="justify-content:space-around">
                                                                <VIconButton type="button" raised circle
                                                                    icon="feather:plus" @click="addNewItemDT()"
                                                                    color="info" v-tooltip.bubble="'Tambah '">
                                                                </VIconButton>
                                                                <VIconButton class="mt-1" v-if="index > 0" type="button"
                                                                    raised circle icon="feather:trash"
                                                                    @click="removeItemDT(index)" color="danger">
                                                                </VIconButton>
                                                            </VButtons>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="column is-12 pt-0">
                                    <h1 style="font-weight: bold; margin-bottom: 10px;">Perlu Kontrol</h1>
                                    <div class="columns is-multiline">
                                        <div class="column is-4">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox" v-model="input.perlukontrol"
                                                        true-value="Ya" label="Ya" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-4">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox" v-model="input.perlukontrol"
                                                        true-value="Tidak" label="Tidak" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-4">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox" v-model="input.perlukontrol"
                                                        true-value="Rujuk Balik" label="Rujuk Balik" color="primary"
                                                        circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-6 columns is-multiline">
                            <div class="columns is-multiline column is-12 pb-0">
                                <div class="column is-1"></div>
                                <div class="column is-6">
                                    <h1 style="font-weight: bold; margin-bottom: 10px;" class="ml-3">Riwayat Keluar RS
                                    </h1>
                                    <div class="columns is-multiline">
                                        <div class="column is-12">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar"
                                                        true-value="Sembuh" label="Sembuh" color="primary" circle />
                                                </VControl>
                                            </VField>
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar"
                                                        true-value="Membaik" label="Membaik" color="primary" circle />
                                                </VControl>
                                            </VField>
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar"
                                                        true-value="Belum Sembuh" label="Belum Sembuh" color="primary"
                                                        circle />
                                                </VControl>
                                            </VField>
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar"
                                                        true-value="Tidak Ada Perkembangan"
                                                        label="Tidak Ada Perkembangan" color="primary" circle />
                                                </VControl>
                                            </VField>
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar"
                                                        true-value="Meninggal >= 48 Jam" label="Meninggal > 48 Jam"
                                                        color="primary" circle />
                                                </VControl>
                                            </VField>
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar"
                                                        true-value="Meninggal <= 48 Jam" label="Meninggal <= 48 Jam"
                                                        color="primary" circle />
                                                </VControl>
                                            </VField>
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar" true-value="DOA"
                                                        label="DOA" color="primary" circle />
                                                </VControl>
                                            </VField>
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar"
                                                        true-value="Rawat Inap" label="Rawat Inap" color="primary"
                                                        circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-4">
                                    <h1 style="font-weight: bold; margin-bottom: 10px;" class="ml-3">Status Keluar RS
                                    </h1>
                                    <div class="columns is-multiline">
                                        <div class="column is-12">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox" v-model="input.statuskeluar"
                                                        true-value="Belum Keluar RS" label="Belum Keluar RS"
                                                        color="primary" circle />
                                                </VControl>
                                            </VField>
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox" v-model="input.statuskeluar"
                                                        true-value="Diijinkan Pulang" label="Diijinkan Pulang"
                                                        color="primary" circle />
                                                </VControl>
                                            </VField>
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox" v-model="input.statuskeluar"
                                                        true-value="Pulang Paksa" label="Pulang Paksa" color="primary"
                                                        circle />
                                                </VControl>
                                            </VField>
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox" v-model="input.statuskeluar"
                                                        true-value="Dirujuk" label="Dirujuk" color="primary" circle />
                                                </VControl>
                                            </VField>
                                            <div v-if="input.statuskeluar == 'Dirujuk'">
                                                <h1>Tujuan</h1>
                                                <VControl>
                                                <VInput type="text" class="input" v-model="input.tujuan_skrs" />
                                                </VControl>
                                                <h1>Alasan</h1>
                                                <VControl>
                                                <VInput type="text" class="input" v-model="input.alasan_skrs" />
                                                </VControl>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-1" v-if="props.registrasi.namaruangan.trim().toUpperCase().indexOf('IGD') == -1"></div>
                            <div class="column is-11 pt-0" v-if="props.registrasi.namaruangan.trim().toUpperCase().indexOf('IGD') == -1">
                                <h1 style="font-weight: bold;" class="ml-3">Kondisi Saat Masuk</h1>
                                <VField>
                                    <VTextarea rows="2" v-model="input.TAKondisiSaatMasuk"></VTextarea>
                                </VField>
                            </div>
                            <div class="column is-1"></div>
                            <div class="column is-11 pt-0">
                                <h1 style="font-weight: bold;" class="ml-3">Diagnosis Primer</h1>
                                <VField>
                                    <VTextarea rows="2" v-model="input.TADiagnosisPrimer"></VTextarea>
                                </VField>
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="margin:0px;">

                <template v-if="input.perlukontrol && input.perlukontrol == 'Ya'">
                    <div class="column is-12" >
                        <Fieldset legend="Rencana Kontrol" style="margin-bottom: 10px" :toggleable="true">
                            <div class="column is-12">
                                <RencanaKontrol></RencanaKontrol>
                            </div>
                        </Fieldset>
                    </div>

                    <hr style="margin:0px;">
                </template>

                <div class="column is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-6">
                        <h1 style="font-weight:bold;">GCS</h1>
                        <div class="columns is-multiline">
                            <div class="column is-4">
                                <VField addons>
                                    <VControl class="field-addon-body">
                                        <VButton static>E</VButton>
                                    </VControl>
                                    <VControl expanded>
                                        <Multiselect v-model="input.gcse" :attrs="{ value }" placeholder="E"
                                            label="label" :options="d_gcse" :searchable="true" track-by="label"
                                            mode="single" autocomplete="off"
                                            style="border-radius:0px 4px 4px 0px;height:100%">
                                        </Multiselect>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField addons>
                                    <VControl class="field-addon-body">
                                        <VButton static>V</VButton>
                                    </VControl>
                                    <VControl expanded>
                                        <Multiselect v-model="input.gcsv" :attrs="{ value }" placeholder="V"
                                            label="label" :options="d_gcsv" :searchable="true" track-by="label"
                                            mode="single" autocomplete="off"
                                            style="border-radius:0px 4px 4px 0px;height:100%">
                                        </Multiselect>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField addons>
                                    <VControl class="field-addon-body">
                                        <VButton static>M</VButton>
                                    </VControl>
                                    <VControl expanded>
                                        <Multiselect v-model="input.gcsm" :attrs="{ value }" placeholder="M"
                                            label="label" :options="d_gcsm" :searchable="true" track-by="label"
                                            mode="single" autocomplete="off"
                                            style="border-radius:0px 4px 4px 0px;height:100%">
                                        </Multiselect>
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <h1 class="bold">Kesan Umum</h1>
                        <VField>
                            <VControl>
                                <Multiselect v-model="input.kesanUmum" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_keadaanumum" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off" style="height:100%">
                                </Multiselect>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3 pt-0">
                        <h1 class="bold">Nadi</h1>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" placeholder="Nadi" v-model="input.nadi" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>x/menit</VButton>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3 pt-0">
                        <h1 style="font-weight: bold;">RR</h1>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" placeholder="RR" v-model="input.nafas" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>x/menit</VButton>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3 pt-0">
                        <h1 style="font-weight: bold;">Suhu</h1>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" placeholder="Suhu" v-model="input.celcius" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>°C </VButton>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3 pt-0">
                        <h1 style="font-weight: bold;">Tekanan Darah</h1>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" placeholder="Tekanan Darah"
                                    v-model="input.tekananDarah" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>mmHg</VButton>
                            </VControl>
                        </VField>
                    </div>
                </div>

                <div class="column is-12">
                    <div class="columns">
                        <div class="column is-6">
                            <h1 class="bold">Anamnesis</h1>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.anamnesis" rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <h1 class="bold">Pemeriksaan Fisik lainnya yang ditemukan</h1>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.pemeriksaanfisik" rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>

                <div class="column is-12">
                    <div class="columns">
                        <div class="column is-6">
                            <h1 class="bold">Terapi</h1>
                            <div class="column is-12 p-0">
                                <VField>
                                    <VControl>
                                        <VTextarea v-model="input.intruksi" rows="3">
                                        </VTextarea>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-12">
                                <VButton color="info" rounded raised size="medium" @click="inputObat()"
                                    :loading="isLoading">
                                    Pilih Riwayat Obat
                                </VButton>
                            </div>
                        </div>
                        <div class="column is-6">
                            <h1 class="bold">Pemeriksaan Penunjang</h1>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.hasilpemeriksaanpenunjang" rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <VModal :open="showModalObat" title="Riwayat Obat" :noclose="true" size="large" actions="right"
        @close="showModalObat = false">
        <template #content>
            <DataTable :pt="{
                table: { style: 'min-width: 50rem; min-height: 10rem;' },
                column: {
                    bodycell: ({ state }) => ({
                        class: [{ 'pt-0 pb-0': state['d_editing'] }]
                    })
                }
            }" v-model:selection="ObatSelected" :value="listSIMRSLama" :metaKeySelection="metaKey" :rows="10" paginator
                tableStyle="min-width: 50rem" dataKey="no" :totalRecords="listSIMRSLama.length" responsiveLayout="stack"
                breakpoint="960px">
                <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                <Column field="namaobat" header="Nama" :sortable="true">
                    <template #body="slotProps">
                        <span>{{ slotProps.data.namaobat + ' - ' + slotProps.data.jenisobat }}</span>
                    </template>
                </Column>
                <Column field="noorder" header="No Resep" :sortable="true"></Column>
                <Column field="noregistrasi" header="No Registrasi" :sortable="true"></Column>
                <Column field="namalengkap" header="Dokter" :sortable="true" style="width: 150px;;"></Column>
                <Column field="tglorder" header="Tanggal" :sortable="true">
                    <template #body="slotProps">
                        <span>{{ H.formatDateToLocalString(slotProps.data.tglorder) }}</span>
                    </template>
                </Column>
            </DataTable>
        </template>
        <template #action>
            <VButton type="button" color="primary" raised @click="addObat_toInput()">
                Tambah
            </VButton>
        </template>
    </VModal>

    <VModal :open="showModalTemplate" title="Template CPPT" :noclose="true" size="large" actions="right"
        @close="showModalTemplate = false">
        <template #content>
            <form class="modal-form">
                <div class="column is-12 pt-0 pb-0">
                    <span style="font-size:9pt;font-weight:bold">List Template</span>
                    <div style="overflow-y:auto;" class="mt-1">
                        <table class="tg table-tg" v-if="listTemplate.length > 0">
                            <thead>
                                <tr>
                                    <td class="tg-0lax text-center" width="25%">Tanggal Input</td>
                                    <td class="tg-0lax text-center" width="25%">Tanggal Registrasi</td>
                                    <td class="tg-0lax text-center" width="25%">No Registrasi</td>
                                    <td class="tg-0lax text-center" width="20%">No EMR</td>
                                    <td class="tg-0lax text-center" width="20%">Dokter</td>
                                    <td class="tg-0lax text-center" width="20%">Section</td>
                                    <td class="tg-0lax text-center" width="5%">#</td>
                                </tr>
                            </thead>
                            <tbody v-for="resep in listTemplate">
                                <tr>
                                    <td style="width:25%;text-align:center">
                                        <span class="mb-2">{{ resep.created_at }}</span><br>
                                    </td>
                                    <td style="width:25%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                                    </td>
                                    <td style="width:25%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                                    </td>
                                    <td style="width:20%;text-align:center">
                                        <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                                    </td>
                                    <td style="width:20%;text-align:center">
                                        <span class="mb-2">{{ resep.dpjpUtama }}</span><br>
                                    </td>
                                    <td style="width:25%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                                    </td>
                                    <td style="width:5%;text-align:center">
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="addTemplate(resep)" color="info" v-tooltip-prime.top="'Pilih'">
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
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import { useConfirm } from "primevue/useconfirm"
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import moment from 'moment'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import RencanaKontrol from '../../../integrasi-sistem/rencana-kontrol.vue'

useHead({ title: 'Ringkasan Keluar - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const route = useRoute()
const NOREC_EMRPASIEN: any = ref('')
const confirm = useConfirm();
const pasien: any = ref({})
const { y } = useWindowScroll()
const showModalTemplate: any = ref(false)
const isLoadingPasien: any = ref(false)
const loadData: any = ref(true)
const isLoading = ref(false)
const ObatSelected: any = ref()
const showModalObat: any = ref(false);
const COLLECTION: any = ref('RingkasanKeluar') //table mongodb
const listTemplate: any = ref([])
const d_Dokter: any = ref([])
const listSIMRSLama: any = ref([])
const router = useRouter()
const kelompokUser = route.query.kelompokuser ?? useUserSession().getUser().kelompokUser.kelompokUser

//? Array
const d_gcse: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }])
const d_gcsv: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }])
const d_gcsm: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }, { value: 6, label: '6' }])
const d_keadaanumum: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Sedang' }, { value: 3, label: 'Buruk' }])

//? Array Inputan
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
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: props.registrasi.norec_apd,
    RUANGAN_LAST: props.registrasi.objectruanganlastfk,
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    date: {
        tanggal: new Date,
        jam: new Date
    },
    airway: [],
    disability: []
})
const input: any = ref({
    detailDS: [{ no: 1, }],
    detailDT: [{ no: 1, }],
    waktuTataLaksana: new Date,
    waktuKontrol: new Date,
    jamKedatangan: new Date,
    jamAsesmenAwal: new Date,
})

const kodingDetail = () => {
    router.push({
        name: 'module-inacbgs-koding-detail',
        query: {
            page: route.query.pageKD ? route.query.pageKD : null,
            noregistrasi: props.registrasi.noregistrasi,
            norec_pd: props.registrasi.norec_pd,
            nocmfk: props.registrasi.nocmfk
        }
    }).then(() => {
        window.location.reload();
    })
}

//? Function
const addNewItemDS = () => {
    input.value.detailDS.push({ no: input.value.detailDS[input.value.detailDS.length - 1].no + 1, });
}
const removeItemDS = (index: any) => {
    input.value.detailDS.splice(index, 1)
}
const addNewItemDT = () => {
    input.value.detailDT.push({ no: input.value.detailDT[input.value.detailDT.length - 1].no + 1, });
}
const removeItemDT = (index: any) => { input.value.detailDT.splice(index, 1) }
const isStuck = computed(() => { return y.value > 30 })
const fetchDokter = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`).then((response) => {
        d_Dokter.value = response
    })
}
// const loadRiwayat = async () => {
//     isLoading.value = true
//     let responsex = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
//     if (responsex.length) {
//         input.value = responsex[0] //set ke inputan
//         if (NOREC_EMRPASIEN.value == '') {
//             NOREC_EMRPASIEN.value = responsex[0].emrpasienfk
//         }
//         isLoading.value = false
//         H.alert('info', 'Data berhasil dimuat')
//     }
//     else {
//         await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=AsesmenMedisRawatJalan" + "&field=tanggalKedatangan,jamKedatangan,gcse,gcsv,gcsm,keadaanumum,tekananDarah,nadi,nafas,celcius,anamnesis,hasilpemeriksaanpenunjang,intruksiAsesmen,riwayatkeluar,statuskeluar,perlukontrol").then((response) => {
//             if (response != null) {
//                 response.detailDS = [{ no: 1 }];
//                 response.detailDT = [{ no: 1 }];
//                 input.value = response;
//                 input.value.dpjpUtama = props.registrasi.dokter
//                 for (let y = 0; y < d_keadaanumum.value.length; y++) {
//                     const elements = d_keadaanumum.value[y];
//                     if (elements.value == response.keadaanumum) {
//                         input.value.kesanUmum = elements.value;
//                         input.value.pemeriksaanfisik = 'Keadaan Umum : ' + elements.label + ', \n TD: ' + response.tekananDarah + ', \n PR: ' + response.nadi + ', \n RR: ' + response.nafas + ', \n Suhu: ' + response.celcius + ', \n SaO2: ' + response.sao2
//                     }
//                 }
//                 H.alert('info', 'Data berhasil dimuat')
//                 isLoading.value = false
//             } else {
//                 // H.alert('warning', 'Data Asesmen Medis Tidak Ada!')
//                 useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=CatatanPerkembanganPasienTerintegrasi" + "&field=created_at,gcse,gcsv,gcsm,keadaanumumobgyn,tekananDarah,nadi,nafas,celcius,riwayatkeluar,statuskeluar,perlukontrol").then((responsex) => {
//                     if (responsex != null) {
//                         responsex.detailDS = [{ no: 1 }];
//                         responsex.detailDT = [{ no: 1 }];
//                         input.value = responsex
//                         input.value.tanggalKedatangan = H.formatDate(responsex.created_at, 'YYYY-MM-DD')
//                         input.value.jamKedatangan = responsex.created_at
//                         input.value.dpjpUtama = props.registrasi.dokter
//                         for (let z = 0; z < d_keadaanumum.value.length; z++) {
//                             const elementz = d_keadaanumum.value[z];
//                             if (elementz.value == responsex.keadaanumumobgyn) {
//                                 input.value.kesanUmum = elementz
//                                 input.value.pemeriksaanfisik = 'Keadaan Umum : ' + elementz.label + ', \n TD: ' + responsex.tekananDarah + ', \n PR: ' + responsex.nadi + ', \n RR: ' + responsex.nafas + ', \n Suhu: ' + responsex.celcius + ', \n SaO2: ' + responsex.sao2
//                             }
//                         }
//                         H.alert('info', 'Data berhasil dimuat')
//                         isLoading.value = false
//                     } else {
//                         H.alert('warning', 'Data CPPT Tidak Ada!')
//                         isLoading.value = false
//                     }
//                 })
//                 useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=CPPTDetail&flag=dokter" + "&field=S,O,P").then((responses) => {
//                     if (responses != null) {
//                         // response.detailDS = [{ no: 1 }];
//                         // response.detailDT = [{ no: 1 }];
//                         input.value.anamnesis = responses.S
//                         input.value.intruksi = responses.P
//                         isLoading.value = false
//                     } else {
//                         console.log('Data CPPT Detail Kosong')
//                         isLoading.value = false
//                     }
//                 })
//             }
//         })
//     }
// }
const loadRiwayat = async () => {
    try {
        isLoading.value = true;

        // Fetch initial data
        const responsex = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`);

        if (responsex.length) {
            processInitialResponse(responsex[0]);
        } else {
            await fetchAutoFillData();
        }
    } catch (error) {
        console.error("Error loading data:", error);
        H.alert('error', 'Terjadi kesalahan saat memuat data');
    } finally {
        isLoading.value = false;
    }
};

const processInitialResponse = (data) => {
    input.value = data;
    if (!NOREC_EMRPASIEN.value) {
        NOREC_EMRPASIEN.value = data.emrpasienfk;
    }
    H.alert('info', 'Data berhasil dimuat');
};

const fetchAutoFillData = async () => {
    try {
        let ruangan = props.registrasi.namaruangan.toUpperCase().trim()
        let collection = ''
        let field = ''
        if (ruangan.indexOf('IGD') > -1) {
            collection = 'AsesmenAwalMedisGawatDarurat'
            field = 'DtanggalForm,HjamKedatangan,HjamAW,TADiagnosis,riwayatkeluar,statuskeluar,perlukontrol,TBeGCS,TBvGCS,TBmGCS,keadaanumum,TBtekananDarahTTV,TBNadiTTV,TBRespirasiTTV,TBcelciusTTV,TAInstruksi,TArpp,TAMOI,TARiwayatVaksin,TARiwayatPenggunaanObat,TARiwayatPenyakitDahulu,TARPS,isalergi,TBAlergiObat,TBAlergiMakanan,TBAlergiLainnya,device,details'
        } else {
            collection = 'AsesmenMedisRawatJalan'
            field = 'tanggalKedatangan,jamKedatangan,gcse,gcsv,gcsm,keadaanumum,tekananDarah,nadi,nafas,celcius,anamnesis,hasilpemeriksaanpenunjang,intruksiAsesmen,riwayatkeluar,statuskeluar,perlukontrol'
        }

        const response = await useApi().get(`emr/auto-fill?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${collection}&field=${field}`);

        if (response) {
            processAutoFillResponse(response, 'keadaanumum', ruangan);
        } else {
            await fetchFallbackData();
        }
    } catch (error) {
        console.error("Error fetching auto-fill data:", error);
    }
};

const fetchFallbackData = async () => {
    try {
        const responsex = await useApi().get(`emr/auto-fill?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=CatatanPerkembanganPasienTerintegrasi&field=created_at,gcse,gcsv,gcsm,keadaanumumobgyn,tekananDarah,nadi,nafas,celcius,riwayatkeluar,statuskeluar,perlukontrol`);

        if (responsex) {
            processAutoFillResponse(responsex, 'keadaanumumobgyn');
        } else {
            H.alert('warning', 'Data CPPT Tidak Ada!');
        }

        const responses = await useApi().get(`emr/auto-fill?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=CPPTDetail&flag=dokter&field=S,O,P,A`);
        if (responses) {
            input.value.TAKondisiSaatMasuk = responses.S;
            input.value.anamnesis = responses.S;
            input.value.intruksi = responses.P;
            input.value.TADiagnosisPrimer = responses.A;
        } else {
            console.log('Data CPPT Detail Kosong');
        }
    } catch (error) {
        console.error("Error fetching fallback data:", error);
    }
};

const processAutoFillResponse = (response, key, ruangan) => {
    response.detailDS = [{ no: 1 }];
    response.detailDT = [{ no: 1 }];
    input.value = response;
    input.value.dpjpUtama = props.registrasi.dokter;

    for (const element of d_keadaanumum.value) {
        if (element.value === response[key]) {
            input.value.kesanUmum = element.value;
            input.value.pemeriksaanfisik = `Keadaan Umum : ${element.label}, \n TD: ${response.tekananDarah}, \n PR: ${response.nadi}, \n RR: ${response.nafas}, \n Suhu: ${response.celcius}, \n SaO2: ${response.sao2}`;
            break;
        }
    }

    if (ruangan == 'IGD') {
        let d = input.value
        let r = response
        let riwayatAlergi = 'Riwayat Alergi : '
        let fisik = ''
        let datax = ''
        let jenisAlergi = [];
        let text = ''

        fisik += r.TBcelciusTTV ? `Suhu : ${r.TBcelciusTTV} °C\n` : 'Suhu : -\n'
        fisik += r.TBNadiTTV ? `Nadi : ${r.TBNadiTTV} x/mnt\n` : 'Nadi : -\n'
        fisik += r.TBRespirasiTTV ? `Pernafasan : ${r.TBRespirasiTTV} x/mnt\n` : 'Pernafasan : -\n'
        fisik += r.TBtekananDarahTTV ? `Tekanan Darah : ${r.TBtekananDarahTTV} mmHg\n` : 'Tekanan Darah : -n\n'
        fisik += r.tinggiBadanTTV ? `Tinggi Badan : ${r.tinggiBadanTTV} Cm\n` : 'Tinggi Badan : -\n'
        fisik += r.beratBadanTTV ? `Berat Badan : ${r.beratBadanTTV} Kg\n` : 'Berat Badan : -\n'

        if (r.device == 'Device') {
            fisik += r.keteranganSAO2 ? `SPO2 : ${r.keteranganSAO2} %\n` : ''
        } else {
            fisik += r.TBnsao2TTV ? `SPO2 : ${r.TBnsao2TTV} %\n` : ''
        }

        if (r.isalergi === 'YA') {
        if (r.CBAlergiObat) {
            jenisAlergi.push(`Obat: ${r.TBAlergiObat || '-'}\n`);
        }
        if (r.CBAlergiMakanan) {
            jenisAlergi.push(`Makanan: ${r.TBAlergiMakanan || '-'}\n`);
        }
        if (r.CBAlergiLainnya) {
            jenisAlergi.push(`Lainnya: ${r.TBAlergiLainnya || '-'}\n`);
        }
            riwayatAlergi += `Riwayat Alergi : ${jenisAlergi.length > 0 ? jenisAlergi.join(', ') : '-'}`;
        } else if (r.isalergi === 'LAINNYA') {
            riwayatAlergi += `Riwayat Alergi : ${r.alergi_tidak_diketahui || '-'}`;
        } else if (r.isalergi === 'TIDAK') {
            riwayatAlergi += `Riwayat Alergi : Tidak Ada`;
        }

        datax += r.TARPS ? `Riwayat Penyakit Sekarang : ${r.TARPS}\n` : 'Riwayat Penyakit Sekarang : -\n'
        datax += r.TARiwayatPenyakitDahulu ? `Riwayat Penyakit Dahulu : ${r.TARiwayatPenyakitDahulu}\n` : 'Riwayat Penyakit Dahulu : -\n'
        datax += r.TARiwayatPenggunaanObat ? `Riwayat Penggunaan Obat : ${r.TARiwayatPenggunaanObat}\n` : 'Riwayat Pengobatan : -\n'
        datax += riwayatAlergi + '\n';
        datax += r.TARiwayatVaksin ? `Riwayat Vaksin : ${r.TARiwayatVaksin}\n` : 'Riwayat Vaksin : -\n'
        datax += r.TAMOI ? `MOI : ${r.TAMOI}\n` : 'MOI : -\n'

        d.waktuTataLaksana = r.DtanggalForm;
        d.waktuKontrol = r.DtanggalForm;
        d.jamKedatangan = r.HjamKedatangan;
        d.jamAsesmenAwal = r.HjamAW;
        d.riwayatkeluar = r.riwayatkeluar;
        d.statuskeluar = r.statuskeluar;
        d.perlukontrol = r.perlukontrol;
        d.tanggalKedatangan = r.DtanggalForm;
        d.TAKondisiSaatMasuk = '';
        d.TADiagnosisPrimer = r.TADiagnosis ?? null;
        d.gcse = r.TBeGCS;
        d.gcsv = r.TBvGCS;
        d.gcsm = r.TBmGCS;
        d.nadi = r.TBNadiTTV;
        d.nafas = r.TBRespirasiTTV;
        d.celcius = r.TBcelciusTTV;
        d.tekananDarah = r.TBtekananDarahTTV ?? '';
        d.anamnesis = datax;
        d.pemeriksaanfisik = fisik;
        if (r.details != null) {
            r.details.forEach((item) => {
                text += `Rencana Intervensi : ${item.TArencanaIntervensi ? item.TArencanaIntervensi : ''}\n`;
            });
        }
        text += `Intruksi : ${r.TAInstruksi ? r.TAInstruksi : ''}\n`;
        d.intruksi = text
        d.hasilpemeriksaanpenunjang = r.TArpp ?? '';
    }

    H.alert('info', 'Data berhasil dimuat');
};

const pilihTemplate = async (index: any) => {
    isLoading.value = true
    useApi().get(
        `/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
            isLoading.value = false
            if (responselast.length) {
                listTemplate.value = responselast //set ke inputan
                showModalTemplate.value = true
            } else {
                H.alert('warning', 'Data tidak ada')
            }
        })
}
const addTemplate = (response: any) => {
    input.value = response
}
const simpan = () => {
    if (!input.value.TADiagnosisPrimer || input.value.TADiagnosisPrimer && input.value.TADiagnosisPrimer.replace(/\s/g, "").length < 4) {
        H.alert('error', 'Diagnosis Primer, ' + 'diisi minimal 4 karakter');
        return;
    }
    if (!input.value.anamnesis || input.value.anamnesis && input.value.anamnesis.replace(/\s/g, "").length < 4) {
        H.alert('error', 'Anamnesis, ' + 'diisi minimal 4 karakter');
        return;
    }
    if (!input.value.intruksi || input.value.intruksi && input.value.intruksi.replace(/\s/g, "").length < 4) {
        H.alert('error', 'Intruksi, ' + 'diisi minimal 4 karakter');
        return;
    }

    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    input.value.sumber = "RingkasanKeluar";
    object = input.value
    object.nocm = pasien.value.nocm
    object.pasien = H.setObjectPasien(pasien.value)
    object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'url_form': props.FORM_URL,
        'name_form': props.FORM_NAME,
        'jenis_emr': 'asesmen_medis',
        'data': object
    }
    // console.log(json)

    isLoading.value = true
    useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
        isLoading.value = false
        loadRiwayat()
    }).catch((e: any) => {
        isLoading.value = false
    })
}
const kembaliKeun = () => {
    window.history.back()
}
const print = async () => {
    H.printBlade(`emr/cetak/${COLLECTION.value}?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&pdf=true&noregistrasi=${props.registrasi.noregistrasi}`)
}

//? Obat Modal
const inputObat = async () => {
    if (listSIMRSLama.value.length > 0) {
        ObatSelected.value = [];
        showModalObat.value = true;
    } else {
        listSIMRSLama.value = []
        let lokal = false;
        let riwayat1 = []
        isLoading.value = true
        let responseX = await useApi().get(`/farmasi/riwayat-order-resep?norec_pd=${NOREC_PD}`)
        isLoading.value = false
        let nomor = 0;
        if (responseX.length > 0) {
            for (let x = 0; x < responseX.length; x++) {
                const element = responseX[x];
                for (let d = 0; d < element.details.length; d++) {
                    nomor++;
                    const detail = element.details[d];
                    riwayat1.push({
                        'no': nomor,
                        'namalengkap': element.namalengkap,
                        'noregistrasi': element.noregistrasi,
                        'noorder': element.noorder,
                        'tglorder': moment(element.tglorder).format('DD-MM-YYYY'),
                        'namaobat': detail.namaproduk,
                        'jenisobat': detail.jeniskemasan,
                        'simslama': true,
                    })
                }
            }
            listSIMRSLama.value = riwayat1
            showModalObat.value = true;
        } else {
            H.alert('warning', 'Pasien belum mempunyai riwayat obat')
        }
    }
    // console.log(listSIMRSLama.value)
}
const addObat_toInput = (event) => {
    // console.log("obat selected", ObatSelected)
    let inputss = input.value.intruksi == undefined ? '' : input.value.intruksi;
    if (ObatSelected.value.length > 0) {
        ObatSelected.value.forEach((obt, ind) => {
            inputss += ` # ${obt.namaobat} `
        })
    }
    input.value.intruksi = inputss
    showModalObat.value = false;
}
const fetchPasien = () => {
    pasien.value = props.pasien
    pasien.value.registrasi = props.registrasi
    NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}

onBeforeMount(async () => {
    try {
        await loadRiwayat()
        await fetchPasien()
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
.bold {
    font-weight: bold;
}

.fontcheckbox {
    font-size: 12.5px;
    color: black;
}

hr {
    background-color: hsl(0deg 6.81% 88.68%);
    border: none;
    display: block;
    height: 2px;
    margin: 1rem 0;
}

.table-rpo {
    width: 100%;
    border: 1px solid;
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
