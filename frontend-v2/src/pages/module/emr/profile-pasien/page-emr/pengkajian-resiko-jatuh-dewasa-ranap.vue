<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header" style="margin-bottom: 10px">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Pengkajian Resiko Jatuh Dewasa</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate"
                                @kembaliKeun="kembaliKeun" :isHideCetak="true" :isHideST="true">
                            </ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <div class="column">
                    <div class="columns is-multiline">
                        <div class="column is-12 is-flex">
                            <VField>
                                <VLabel>Periode</VLabel>
                                <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField addons>
                                            <VControl icon="feather:calendar">
                                            <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                            </VControl>
                                            <VControl>
                                            <VButton static icon="feather:arrow-right" />
                                            </VControl>
                                            <VControl subcontrol icon="feather:calendar">
                                            <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </VField>
                            <VIconButton type="button" raised circle icon="lucide:check-square" @click="loadRiwayat()"
                                color="success" v-tooltip.bubble="'filter'" class="my-auto ml-5">
                            </VIconButton>
                        </div>
                        <div class="column is-8 is-flex"
                            style="font-weight: bold;font-size: large;align-items: center;">
                            Pengkajian Skala Morse
                        </div>
                        <div class="column is-4" style="text-align: center;">
                            <VButton type="button" rounded color="dark" class="mb-3"> Tambah Kolom
                            </VButton>
                            <VButtons style="justify-content:space-around">
                                <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()"
                                    color="info" v-tooltip.bubble="'Tambah '">
                                </VIconButton>
                                <VIconButton class="mt-1" v-if="input.details.length > 1" type="button" raised circle
                                    icon="feather:trash" @click="removeItem(index)" color="danger">
                                </VIconButton>
                            </VButtons>
                        </div>
                        <div class="column is-5 pr-0">
                            <table class="tg2" style="width: 100% !important;">
                                <thead>
                                    <tr>
                                        <th
                                            style="text-align: center;vertical-align: middle;width: 70%;font-weight: bold !important;">
                                            ITEM PENILAIAN
                                        </th>
                                        <th
                                            style="text-align: center;vertical-align: middle;width: 30%;font-weight: bold !important;">
                                            SKOR</th>
                                    </tr>
                                </thead>
                                <tbody v-for="data in PSM">
                                    <tr>
                                        <td style="font-weight: bold;background-color:lightgray" colspan="2">{{
                                            data.nama }}
                                        </td>
                                    </tr>
                                    <tr v-for="(item, index) in data.detail" :key="index">
                                        <td style="text-align: left;">{{ item.label }}</td>
                                        <td style="text-align: center;">{{ parseFloat(item.value) }}</td>
                                    </tr>
                                </tbody>
                                <tr>
                                    <td colspan="2">Total Skala Morse</td>
                                </tr>
                                <!-- <tr>
                                    <td colspan="2">Evaluasi</td>
                                </tr> -->
                                <tr>
                                    <td colspan="2">Keterangan</td>
                                </tr>
                                <tr>
                                    <td>Resiko Rendah</td>
                                    <td style="text-align: center;">0-7</td>
                                </tr>
                                <tr>
                                    <td>Resiko Sedang</td>
                                    <td style="text-align: center;">8-13</td>
                                </tr>
                                <tr>
                                    <td>Resiko Tinggi</td>
                                    <td style="text-align: center;">≥ 14</td>
                                </tr>
                                <tr>
                                    <td colspan="2">Nama/Paraf</td>
                                </tr>
                            </table>
                        </div>
                        <div class="column is-7 pl-0" style="overflow: auto;">
                            <table>
                                <tr>
                                    <td v-for="(data, index) in dataSourceFiltered" :key="index" class="p-0">
                                        <table class="table" style="width: 12rem !important;">
                                            <tr>
                                                <td style="font-weight: bold;">Tanggal & Jam</td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: lightgray !important;">
                                                    <VDatePicker v-model="input.details[data['originalIndex']]['tgl_SM']" mode="datetime" is24hr>
                                                        <template #default="{ inputValue, inputEvents }">
                                                            <VControl icon="feather:calendar" fullwidth>
                                                                <VInput :value="inputValue" v-on="inputEvents" />
                                                            </VControl>
                                                        </template>
                                                    </VDatePicker>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="0"
                                                            label="" v-model="input.details[data['originalIndex']]['Usia']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="1"
                                                            label="" v-model="input.details[data['originalIndex']]['Usia']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="2"
                                                            label="" v-model="input.details[data['originalIndex']]['Usia']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: lightgray !important;"></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="0"
                                                            label="" v-model="input.details[data['originalIndex']]['DefisitSensoris']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="1"
                                                            label="" v-model="input.details[data['originalIndex']]['DefisitSensoris']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="01"
                                                            label="" v-model="input.details[data['originalIndex']]['DefisitSensoris']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="2"
                                                            label="" v-model="input.details[data['originalIndex']]['DefisitSensoris']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="02"
                                                            label="" v-model="input.details[data['originalIndex']]['DefisitSensoris']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="3"
                                                            label="" v-model="input.details[data['originalIndex']]['DefisitSensoris']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: lightgray !important;"></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="0"
                                                            label="" v-model="input.details[data['originalIndex']]['Aktifitas']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="2"
                                                            label="" v-model="input.details[data['originalIndex']]['Aktifitas']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="3"
                                                            label="" v-model="input.details[data['originalIndex']]['Aktifitas']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: lightgray !important;"></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="0"
                                                            label="" v-model="input.details[data['originalIndex']]['RiwayatJatuh']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="1"
                                                            label="" v-model="input.details[data['originalIndex']]['RiwayatJatuh']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="2"
                                                            label="" v-model="input.details[data['originalIndex']]['RiwayatJatuh']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="3"
                                                            label="" v-model="input.details[data['originalIndex']]['RiwayatJatuh']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: lightgray !important;"></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="0"
                                                            label="" v-model="input.details[data['originalIndex']]['Kognisi']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="1"
                                                            label="" v-model="input.details[data['originalIndex']]['Kognisi']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="2"
                                                            label="" v-model="input.details[data['originalIndex']]['Kognisi']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="3"
                                                            label="" v-model="input.details[data['originalIndex']]['Kognisi']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="03"
                                                            label="" v-model="input.details[data['originalIndex']]['Kognisi']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: lightgray !important;"></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="1"
                                                            label="" v-model="input.details[data['originalIndex']]['Pengobatan']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="2"
                                                            label="" v-model="input.details[data['originalIndex']]['Pengobatan']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="02"
                                                            label="" v-model="input.details[data['originalIndex']]['Pengobatan']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="002"
                                                            label="" v-model="input.details[data['originalIndex']]['Pengobatan']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: lightgray !important;"></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="0"
                                                            label="" v-model="input.details[data['originalIndex']]['Mobilitas']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="1"
                                                            label="" v-model="input.details[data['originalIndex']]['Mobilitas']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="2"
                                                            label="" v-model="input.details[data['originalIndex']]['Mobilitas']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="3"
                                                            label="" v-model="input.details[data['originalIndex']]['Mobilitas']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="4"
                                                            label="" v-model="input.details[data['originalIndex']]['Mobilitas']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="04"
                                                            label="" v-model="input.details[data['originalIndex']]['Mobilitas']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: lightgray !important;"></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="0"
                                                            label="" v-model="input.details[data['originalIndex']]['PolaBABK']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="1"
                                                            label="" v-model="input.details[data['originalIndex']]['PolaBABK']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="2"
                                                            label="" v-model="input.details[data['originalIndex']]['PolaBABK']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="3"
                                                            label="" v-model="input.details[data['originalIndex']]['PolaBABK']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: lightgray !important;"></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="2"
                                                            label="" v-model="input.details[data['originalIndex']]['Komorbiditas']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="3"
                                                            label="" v-model="input.details[data['originalIndex']]['Komorbiditas']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="03"
                                                            label="" v-model="input.details[data['originalIndex']]['Komorbiditas']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>{{ data['TotalSM'] ? data['TotalSM'] : 0 }}</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <Multiselect v-model="input.details[data['originalIndex']]['Evaluasi']" :attrs="{ value }"
                                                        placeholder="--Pilih--" label="label" :options="d_Catatan"
                                                        :searchable="true" track-by="label" mode="single"
                                                        autocomplete="off">
                                                    </Multiselect>
                                                </td>
                                            </tr>
                                            <!-- <tr>
                                                <td>
                                                    <VControl>
                                                        <VInput type="text" class="input"
                                                            v-model="data['Keterangan']" />
                                                    </VControl>
                                                </td>
                                            </tr> -->
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="0-7"
                                                            label="" v-model="input.details[data['originalIndex']]['Resiko']" disabled />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="8-13"
                                                            label="" v-model="input.details[data['originalIndex']]['Resiko']" disabled />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="≥ 14"
                                                            label="" v-model="input.details[data['originalIndex']]['Resiko']" disabled />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl class="prime-auto">
                                                        <AutoComplete v-model="input.details[data['originalIndex']]['Petugas']" :suggestions="d_Petugas"
                                                            @complete="fetchPetugas($event)" :optionLabel="'label'"
                                                            :dropdown="true" :minLength="3" :appendTo="'body'"
                                                            :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                            placeholder="Cari Pegawai..." />
                                                    </VControl>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="column is-12 pt-0 pb-0">
                            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                        </div>

                        <div class="column is-8 is-flex"
                            style="font-weight: bold;font-size: large;align-items: center;">
                            Monitoring dan Evaluasi Pelaksanaan Protokol Pemantauan Risiko Jatuh
                        </div>
                        <div class="column is-4" style="text-align: center;">
                            <VButton type="button" rounded color="dark" class="mb-3"> Tambah Kolom
                            </VButton>
                            <VButtons style="justify-content:space-around">
                                <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem2()"
                                    color="info" v-tooltip.bubble="'Tambah '">
                                </VIconButton>
                                <VIconButton class="mt-1" v-if="input.details2.length > 1" type="button" raised circle
                                    icon="feather:trash" @click="removeItem2(index)" color="danger">
                                </VIconButton>
                            </VButtons>
                        </div>
                        <div class="column is-6 pr-0">
                            <table class="tg34" style="width: 100% !important;">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;vertical-align: middle;">
                                            PROTOKOL
                                        </th>
                                        <th style="text-align: center;vertical-align: middle;">TINDAKAN
                                            PENCEGAHAN</th>
                                    </tr>
                                    <tr>
                                        <th colspan="2"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td rowspan="10" style="text-align: center;">STANDAR (1) RESIKO RENDAH</td>
                                        <td>Orientasikan pasien pada lingkungan kamar/bangsal.</td>
                                    </tr>
                                    <tr>
                                        <td>Pastikan rem tempat tidur terkunci.</td>
                                    </tr>
                                    <tr>
                                        <td>Pastikan bel pasien terjangkau</td>
                                    </tr>
                                    <tr>
                                        <td>Singkirkan barang yang berbahaya terutama pada malam hari (kursi
                                            tambahan dan
                                            lain-lain.)</td>
                                    </tr>
                                    <tr>
                                        <td>Minta persetujuan pasien agar lampu malam tetap menyala karena
                                            lingkungan masih
                                            asing.</td>
                                    </tr>
                                    <tr>
                                        <td>Pastikan alat bantu jalan dalam jangkauan (bila menggunakan).</td>
                                    </tr>
                                    <tr>
                                        <td>Pastikan alat kaki tidak licin.</td>
                                    </tr>
                                    <tr>
                                        <td>Pastikan kebutuhan pribadi dalam jangkauan.</td>
                                    </tr>
                                    <tr>
                                        <td>Tempatkan meja pasien dengan baik agar tidak menghalangi.</td>
                                    </tr>
                                    <tr>
                                        <td>Tempatkan pasien sesuai dengan tinggi badannya.</td>
                                    </tr>

                                    <tr>
                                        <!-- <td rowspan="8" style="text-align: center;">RESIKO JATUH TINGGI (2) \n
                                            (PROTOKOL
                                            1,2)</td> -->
                                            <td rowspan="8" style="text-align: center;">RESIKO JATUH SEDANG</td>
                                        <td>Pasang gelang kuning dan penanda/symbul resiko jatuh di luar
                                            kamar/diatas tempat
                                            tidur pasien.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Menjelaskan kepada pasien dan keluarga kemungkinan risiko jatuh dan
                                            tindakan
                                            pencegahan risiko
                                            jatuh.</td>
                                    </tr>
                                    <tr>
                                        <td>Minta agar pasien segera menekan bel bila perlu bantuan.</td>
                                    </tr>
                                    <tr>
                                        <td>Awasi atau bantu sebagian ADL pasien.</td>
                                    </tr>
                                    <tr>
                                        <td>Cepat menanggapi bel.</td>
                                    </tr>
                                    <tr>
                                        <td>Review kembali obat obatan yang beresiko.</td>
                                    </tr>
                                    <tr>
                                        <td>Beritahu agar mobilisasi secara bertahap; duduk perlahan sebelum
                                            berdiri.</td>
                                    </tr>
                                    <tr>
                                        <td>Pasang penanda risiko jatuh diluar kamar.</td>
                                    </tr>

                                    <tr>
                                        <!-- <td rowspan="5" style="text-align: center;">RESIKO JATUH SANGAT TINGGI (2)
                                            \n
                                            (PROTOKOL 1,2,3)</td> -->
                                          <td rowspan="8" style="text-align: center;">RESIKO JATUH TINGGI</td>
                                        <td>Kaji kebutuhan BAB/BAK secara teratur tiap 2-3jam.</td>
                                    </tr>
                                    <tr>
                                        <td>Kolaborasi dengan fisioterapi/case manager.</td>
                                    </tr>
                                    <tr>
                                        <td>Bila memungkinkan pindahkan pasien dekat nurse station.</td>
                                    </tr>
                                    <tr>
                                        <td>Kaji kebutuhan dengan menggunakan pagar tempat tidur.</td>
                                    </tr>
                                    <tr>
                                        <td>Orientasikan ulang bila perlu.</td>
                                    </tr>
                                    <tr>
                                        <td>Evaluasi</td>
                                        <td>Apakah terjadi insiden jatuh?</td>
                                    </tr>
                                    <tr>
                                        <td>Paraf</td>
                                        <td>Nama Petugas</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="column is-6 pl-0" style="overflow: auto;">
                            <table>
                                <tr>
                                    <td v-for="(data, index) in dataSourceFiltered2" :key="index" class="p-0">
                                        <table class="tg34" style="width: 12rem !important;">
                                            <tr>
                                                <th style="font-weight: bold;">Tanggal & Jam</th>
                                            </tr>
                                            <tr>
                                                <th style="background-color: lightgray !important;">
                                                    <VDatePicker v-model="input.details2[data['originalIndex']]['tgl_MonitoringEvaluasi']"
                                                        mode="datetime" is24hr>
                                                        <template #default="{ inputValue, inputEvents }">
                                                            <VControl icon="feather:calendar" fullwidth>
                                                                <VInput :value="inputValue" v-on="inputEvents" />
                                                            </VControl>
                                                        </template>
                                                    </VDatePicker>
                                                </th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="input.details2[data['originalIndex']]['RJR_1']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="input.details2[data['originalIndex']]['RJR_1']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="input.details2[data['originalIndex']]['RJR_2']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="input.details2[data['originalIndex']]['RJR_2']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="input.details2[data['originalIndex']]['RJR_3']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="input.details2[data['originalIndex']]['RJR_3']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="input.details2[data['originalIndex']]['RJR_4']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="input.details2[data['originalIndex']]['RJR_4']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="input.details2[data['originalIndex']]['RJR_5']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="input.details2[data['originalIndex']]['RJR_5']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="input.details2[data['originalIndex']]['RJR_6']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="input.details2[data['originalIndex']]['RJR_6']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="input.details2[data['originalIndex']]['RJR_7']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="input.details2[data['originalIndex']]['RJR_7']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="input.details2[data['originalIndex']]['RJR_8']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="input.details2[data['originalIndex']]['RJR_8']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="input.details2[data['originalIndex']]['RJR_9']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="input.details2[data['originalIndex']]['RJR_9']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="input.details2[data['originalIndex']]['RJR_10']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="input.details2[data['originalIndex']]['RJR_10']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="input.details2[data['originalIndex']]['RJT_1']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="input.details2[data['originalIndex']]['RJT_1']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="input.details2[data['originalIndex']]['RJT_2']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="input.details2[data['originalIndex']]['RJT_2']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="input.details2[data['originalIndex']]['RJT_3']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="input.details2[data['originalIndex']]['RJT_3']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="input.details2[data['originalIndex']]['RJT_4']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="input.details2[data['originalIndex']]['RJT_4']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="input.details2[data['originalIndex']]['RJT_5']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="input.details2[data['originalIndex']]['RJT_5']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="input.details2[data['originalIndex']]['RJT_6']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="input.details2[data['originalIndex']]['RJT_6']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="input.details2[data['originalIndex']]['RJT_7']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="input.details2[data['originalIndex']]['RJT_7']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="input.details2[data['originalIndex']]['RJT_8']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="input.details2[data['originalIndex']]['RJT_8']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="input.details2[data['originalIndex']]['RJST_1']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="input.details2[data['originalIndex']]['RJST_1']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="input.details2[data['originalIndex']]['RJST_2']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="input.details2[data['originalIndex']]['RJST_2']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="input.details2[data['originalIndex']]['RJST_3']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="input.details2[data['originalIndex']]['RJST_3']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="input.details2[data['originalIndex']]['RJST_4']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="input.details2[data['originalIndex']]['RJST_4']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="input.details2[data['originalIndex']]['RJST_5']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="input.details2[data['originalIndex']]['RJST_5']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="columns">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="input.details2[data['originalIndex']]['TerjadiInsidenJatuh']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="input.details2[data['originalIndex']]['TerjadiInsidenJatuh']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl class="prime-auto">
                                                        <AutoComplete v-model="input.details2[data['originalIndex']]['petugas']" :suggestions="d_Petugas"
                                                            @complete="fetchPetugas($event)" :optionLabel="'label'"
                                                            :dropdown="true" :minLength="3" :appendTo="'body'"
                                                            :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                            class="mt-2" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="column is-12 columns is-multiline">
                            <div class="column is-3">
                                <span>Nama Petugas</span>
                                <VField>
                                    <VControl class="prime-auto">
                                        <AutoComplete v-model="input.petugasmonitor" :suggestions="d_Petugas"
                                            @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Cari Pegawai..." />
                                    </VControl>
                                </VField>
                            </div>

                            <div class="column is-12 pb-0">
                                <span>Catatan : </span>
                            </div>
                            <div class="column is-12 pb-0">
                                <span>
                                    1. Pada item monitoring, berikan ya apabila monitoring dilakukan dan jawaban
                                    tidak
                                    apabila monitoring
                                    tidak
                                    &nbsp;dilakukan pada kolom tanggal sesuai skor risiko jatuh pasien. <br>
                                    2. Pada kolom evaluasi, berilah tanda (v) pada pilihan ya atau tidak<br>
                                    <Multiselect v-model="input.SCatatan" :attrs="{ value }" placeholder="--Pilih--"
                                        label="label" :options="d_Catatan" :searchable="true" track-by="label"
                                        mode="single" autocomplete="off">
                                    </Multiselect>
                                </span>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount, watchEffect } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';


// Judul
useHead({
    title: 'Pengkajian Resiko Jatuh Dewasa - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const d_keadaanumum: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Sedang' }, { value: 3, label: 'Buruk' }])
const user = useUserSession().getUser().pegawai;
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
        FORM_NAME: 'Pengkajian Risiko Jatuh Dewasa',
        FORM_URL: 'pengkajian-resiko-jatuh-dewasa-ranap',
        COLLECTION: 'PengkajianResikoJatuhDewasa',
    }
)
const fetchPetugas = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&limit=10&query=${filter.query}`).then((response) => { d_Petugas.value = response })
}
const dataTTD: any = ref([])
const route = useRoute()
const pasien: any = ref({})
const d_Petugas: any = ref([])
const loadData: any = ref(true)
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
    filter: '',
    airway: [],
    disability: [],
    filterTgl: reactive({
        start: new Date(),
        end: new Date(),
    }),

})
  
const COLLECTION: any = ref('PengkajianResikoJatuhDewasa') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const { y } = useWindowScroll()
const router = useRouter()
const isStuck = computed(() => {
    return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const alertMid = ref(false);
const d_Dokter: any = ref([])
const nilaiKajian: number = 0;
const detailsLama = ref([]);
const PSM: any = ref([
    {
        nama: 'USIA', rowspan: 3, dmodel: 'DUsia',
        detail: [
            { type: 'checkbox', label: 'Kurang dari 60 tahun', model: 'usia', value: 0 },
            { type: 'checkbox', label: 'Lebih dari 60 tahun', model: 'usia', value: 1 },
            { type: 'checkbox', label: 'Lebih dari 80 tahun', model: 'usia', value: 2 }
        ]
    },
    {
        nama: 'DEFISIT SENSORIS', rowspan: 6, dmodel: 'DDefisit',
        detail: [
            { type: 'checkbox', label: 'Kacamata bukan bifokal', model: 'defisit', value: 0 },
            { type: 'checkbox', label: 'Kacamata bifokal', model: 'defisit', value: 1 },
            { type: 'checkbox', label: 'Gangguan pendengaran', model: 'defisit', value: '01' },
            { type: 'checkbox', label: 'Kacamata multifokal', model: 'defisit', value: 2 },
            { type: 'checkbox', label: 'Katarak/glaukoma', model: 'defisit', value: '02' },
            { type: 'checkbox', label: 'Hampir tidak melihat/buta', model: 'defisit', value: 3 },
        ]
    },
    {
        nama: 'AKTIFITAS', rowspan: 3, dmodel: 'DAktifitas',
        detail: [
            { type: 'checkbox', label: 'Mandiri', model: 'aktifitas', value: 0 },
            { type: 'checkbox', label: 'ADL dibantu sebagian', model: 'aktifitas', value: 2 },
            { type: 'checkbox', label: 'ADL dibantu penuh', model: 'aktifitas', value: 3 }
        ]
    },
    {
        nama: 'RIWAYAT JATUH', rowspan: 4, dmodel: 'DRiwayatJatuh',
        detail: [
            { type: 'checkbox', label: 'Tidak pernah', model: 'riwayatJatuh', value: 0 },
            { type: 'checkbox', label: 'Jatuh < 1 tahun', model: 'riwayatJatuh', value: 1 },
            { type: 'checkbox', label: 'Jatuh < 1 bulan', model: 'riwayatJatuh', value: 2 },
            { type: 'checkbox', label: 'Jatuh pada saat dirawat sekarang', model: 'riwayatJatuh', value: 3 }
        ]
    },
    {
        nama: 'KOGNISI', rowspan: 5, dmodel: 'DKognisi',
        detail: [
            { type: 'checkbox', label: 'Orientasi Baik', model: 'kognisi', value: 0 },
            { type: 'checkbox', label: 'Kesulitan mengerti perintah', model: 'kognisi', value: 1 },
            { type: 'checkbox', label: 'Gangguan memori', model: 'kognisi', value: 2 },
            { type: 'checkbox', label: 'Kebingungan', model: 'kognisi', value: 3 },
            { type: 'checkbox', label: 'Disorientasi', model: 'kognisi', value: '03' }
        ]
    },
    {
        nama: 'PENGOBATAN DAN PENGGUNAAN ALAT KESEHATAN', rowspan: 4, dmodel: 'DPengobatan',
        detail: [
            { type: 'checkbox', label: '> 4 jenis pengobatan', model: 'pengobatan', value: 1 },
            { type: 'checkbox', label: 'Antihipertensi/hipoglikemik/Antidepresan', model: 'pengobatan', value: 2 },
            { type: 'checkbox', label: 'Sedatif/Psikotropika/Narkotika', model: 'pengobatan', value: '02' },
            { type: 'checkbox', label: 'Infus/epidural/spinal/dower cather/traksi', model: 'pengobatan', value: 2 }
        ]
    },
    {
        nama: 'MOBILITAS', rowspan: 6, dmodel: 'DMobilitas',
        detail: [
            { type: 'checkbox', label: 'Mandiri', model: 'mobilitas', value: 0 },
            { type: 'checkbox', label: 'Menggunakan alat bantu berpindah', model: 'mobilitas', value: 1 },
            { type: 'checkbox', label: 'Koordinasi/keseimbangan buruk', model: 'mobilitas', value: 2 },
            { type: 'checkbox', label: 'Dibantu sebagian', model: 'mobilitas', value: 3 },
            { type: 'checkbox', label: 'Dibantu penuh/bed rest/nurse assist', model: 'mobilitas', value: 4 },
            { type: 'checkbox', label: 'Lingkungan dg banyak furniture', model: 'mobilitas', value: '04' }
        ]
    },
    {
        nama: 'POLA BAB/BAK', rowspan: 4, dmodel: 'DPolaBABK',
        detail: [
            { type: 'checkbox', label: 'Teratur', model: 'polababk', value: 0 },
            { type: 'checkbox', label: 'Inkontinensia urine/feses', model: 'polababk', value: 1 },
            { type: 'checkbox', label: 'Nokturia', model: 'polababk', value: 2 },
            { type: 'checkbox', label: 'Urgensi/frekuensi', model: 'polababk', value: 3 }
        ]
    },
    {
        nama: 'KOMORBIDITAS', rowspan: 4, dmodel: 'DKomorbiditas',
        detail: [
            { type: 'checkbox', label: 'Diabetes/penyakit jantung/stroke/ISK, dll', model: 'komorbiditas', value: 2 },
            { type: 'checkbox', label: 'Ganggung syaraf pusat/parkinson', model: 'komorbiditas', value: 3 },
            { type: 'checkbox', label: 'Pasca bedah 0 - 24 jam', model: 'komorbiditas', value: '03' }
        ]
    },
])
const rowMonitor: any = ref([
    {
        label: "STANDAR (1) RESIKO RENDAH",
        detail: [
            { type: 'text', caption: 'Orientasikan pasien pada lingkungan kamar/bangsal.' },
            { type: 'text', caption: 'Pastikan rem tempat tidur terkunci.' },
            { type: 'text', caption: 'Pastikan bel pasien terjangkau.' },
            { type: 'text', caption: 'Singkirkan barang yang berbahaya terutama pada malam hari (kursi tambahan dan lain-lain.)' },
            { type: 'text', caption: 'Minta persetujuan pasien agar lampu malam tetap menyala karena lingkungan masih asing.' },
            { type: 'text', caption: 'Pastikan alat bantu jalan dalam jangkauan (bila menggunakan).' },
            { type: 'text', caption: 'Pastikan alat kaki tidak licin.' },
            { type: 'text', caption: 'Pastikan kebutuhan pribadi dalam jangkauan.' },
            { type: 'text', caption: 'Tempatkan meja pasien dengan baik agar tidak menghalangi.' },
            { type: 'text', caption: 'Tempatkan pasien sesuai dengan tinggi badannya.' }
        ]
    },
    {
        // label: "RESIKO JATUH TINGGI (2) \n (PROTOKOL 1,2)",
        label: "RESIKO JATUH SEDANG",
        detail: [
            { type: 'text', caption: 'Pasang gelang kuning dan penanda/symbul resiko jatuh di luar kamar/diatas tempat tidur pasien.' },
            { type: 'text', caption: 'Menjelaskan kepada pasien dan keluarga kemungkinan risiko jatuh dan tindakan pencegahan risiko jatuh.' },
            { type: 'text', caption: 'Minta agar pasien segera menekan bel bila perlu bantuan.' },
            { type: 'text', caption: 'Awasi atau bantu sebagian ADL pasien.' },
            { type: 'text', caption: 'Cepat menanggapi bel.' },
            { type: 'text', caption: 'Review kembali obat obatan yang beresiko.' },
            { type: 'text', caption: 'Beritahu agar mobilisasi secara bertahap; duduk perlahan sebelum berdiri.' },
            { type: 'text', caption: 'Pasang penanda risiko jatuh diluar kamar.' }
        ]
    },
    {
        // label: "RESIKO JATUH SANGAT TINGGI (2) \n (PROTOKOL 1,2,3)",
        label: "RESIKO JATUH TINGGI",
        detail: [
            { type: 'text', caption: 'Kaji kebutuhan BAB/BAK secara teratur tiap 2-3jam.' },
            { type: 'text', caption: 'Kolaborasi dengan fisioterapi/case manager.' },
            { type: 'text', caption: 'Bila memungkinkan pindahkan pasien dekat nurse station.' },
            { type: 'text', caption: 'Kaji kebutuhan dengan menggunakan pagar tempat tidur.' },
            { type: 'text', caption: 'Orientasikan ulang bila perlu.' }
        ]
    }
])
// const row: any = ref({
//     header: [
//         title:
//     ]
// })
const d_Catatan: any = ref([
    { value: 1, label: 'a) PF (Post Falls)' },
    // { value: 2, label: 'b) CC (Change of Condition)' },
    { value: 3, label: 'c) WT (On Ward Transfer)' },
    { value: 4, label: 'd) DC (Discharge)' },
    { value: 5, label: 'e) ES (Every Shift)' },
    { value: 6, label: 'IA (Initial Assesment)' },
])
const input: any = ref({
    details: [{
        no: 1,
        tgl_SM: new Date(),
        Petugas: { label: user.namaLengkap, value: user.id },
        isDelete: false
    }],
    details2: [{
        no: 1,
        tgl_MonitoringEvaluasi: new Date(),
        petugas: { label: user.namaLengkap, value: user.id },
        isDelete: false
    }],
})

const fetchDokter = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`).then((response) => {
        d_Dokter.value = response
    })
}

const dataSourceFiltered = computed(() => {
  let tglAwal = H.formatDate(item.filterTgl?.start ?? new Date(), 'YYYY-MM-DD')
  let tglAkhir = H.formatDate(item.filterTgl?.end ?? new Date(), 'YYYY-MM-DD')
  if (!item.filterTgl?.start && !item.filterTgl?.end) {
    return input.value.details.map((item: any, index: number) => ({
      ...item,
      originalIndex: index,
    }));
  }
  if(!input.value.details && input.value.details == undefined) {
    input.value.details = [];
    input.value.details.push({
        no: 1,
        tgl_SM: new Date(),
        Petugas: { label: user.namaLengkap, value: user.id },
    });
  }

  return input.value.details
  .map((item, index) => ({
    ...item,
    originalIndex: index,
  }))
  .filter((items) => {
    let format = H.formatDate(items.tgl_SM, 'YYYY-MM-DD')
    return format >= tglAwal && format <= tglAkhir
  })
});

const dataSourceFiltered2 = computed(() => {
    let tglAwal = H.formatDate(item.filterTgl.start, 'YYYY-MM-DD')
    let tglAkhir = H.formatDate(item.filterTgl.end, 'YYYY-MM-DD')
    if (!item.filterTgl?.start && !item.filterTgl?.end) {
        return input.value.details2.map((item: any, index: number) => ({
            ...item,
            originalIndex: index,
        }));
    }

    if(!input.value.details2 && input.value.details2 == undefined) {
        input.value.details2 = [];
        input.value.details2.push({
            no: 1,
            tgl_MonitoringEvaluasi: new Date(),
            petugas: { label: user.namaLengkap, value: user.id },
        })
    }

    return input.value.details2
    .map((item, index) => ({
        ...item,
        originalIndex: index
    }))
    .filter((item) => {
        let format = H.formatDate(item.tgl_MonitoringEvaluasi, 'YYYY-MM-DD')
        return format >= tglAwal && format <= tglAkhir
    })

})

const loadRiwayat = async () => {
    // isLoading.value = true
    console.log("item.value.filterTGL", item)
    let tglAwal = H.formatDate(item.filterTgl.start, 'YYYY-MM-DD')
    let tglAkhir = H.formatDate(item.filterTgl.end, 'YYYY-MM-DD')
    const histori = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`);
    if (histori.length) {
        input.value = histori[0];
        // let newInput = structuredClone(histori[0]);
        // detailsLama.value = structuredClone(histori[0]);
        // if (NOREC_EMRPASIEN.value === '') {
        //     NOREC_EMRPASIEN.value = histori[0].emrpasienfk;
        // }
        // if(newInput.details.length > 0) {
        //     let ft = newInput.details.filter((val) => {
        //         let format = H.formatDate(val['tgl_SM'], 'YYYY-MM-DD')
        //         return format >= tglAwal && format <= tglAkhir
        //     })
        //     if(ft.length == 0) {
        //         ft.push({
        //             no: newInput.details[newInput.details.length - 1].no + 1,
        //             tgl_SM: new Date(),
        //             Petugas: { label: user.namaLengkap, value: user.id },
        //             isDelete: false
        //         });
        //     }
        //     newInput.details = ft;
        //     // newInput.details.push(ft);
        // }
        // if(newInput.details2.length > 0) {
        //     let ft = newInput.details2.filter((val) => {
        //         let format = H.formatDate(val['tgl_MonitoringEvaluasi'], 'YYYY-MM-DD')
        //         return format >= tglAwal && format <= tglAkhir
        //     })
        //     if(ft.length == 0) {
        //         if (!newInput.details2) {
        //             newInput.details2 = [];
        //         }
        //         const lastItem = newInput.details2[newInput.details2.length - 1] || { no: 0 };
        //         ft.push({
        //             no: lastItem.no + 1,
        //             tgl_MonitoringEvaluasi: new Date(),
        //             petugas: { label: user.namaLengkap, value: user.id },
        //             isDelete: false
        //         });
        //     }
        //     // console.log("ft2 ada", ft);
        //     // newInput.details2.push(ft);
        //     newInput.details2 = ft;
        // }
        // input.value = newInput;
    }
    isLoading.value = false;
};

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    // if(detailsLama.value.length > 0) {
    //     detailsLama.value.details.forEach(detailLama => {
    //         const detailBaru = input.value.details.find(detailBaru => detailBaru.no === detailLama.no);
    
    //         if (detailBaru) {
    //             detailBaru.tgl_SM = detailBaru.tgl_SM;
    //             detailBaru.Petugas = detailBaru.Petugas;
    //         }else {
    //             input.value.details.push(detailLama);
    //         }
    //     })
    //     detailsLama.value.details2.forEach(detailLama => {
    //         const detailBaru = input.value.details2.find(detailBaru => detailBaru.no === detailLama.no);
    
    
    //         if (detailBaru) {
    //             detailBaru.tgl_MonitoringEvaluasi = detailBaru.tgl_MonitoringEvaluasi;
    //             detailBaru.petugas = detailBaru.petugas;
    //         }else {
    //             input.value.details2.push(detailLama);
    //         }
    //     })
        
    // }

    input.value.details.sort((a, b) => a.no - b.no);
    input.value.details2.sort((a, b) => a.no - b.no);

    // return
    object = input.value
    // object['ttdPegawai'] = H.tandaTangan().get("ttdPegawai");
    // object['TTDDokterPemeriksa'] = H.tandaTangan().get("TTDDokterPemeriksa");
    object.nocm = pasien.value.nocm

    object.pasien = H.setObjectPasien(pasien.value)
    object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'url_form': "pengkajian-resiko-jatuh-dewasa-ranap",
        'name_form': "Pengkajian Risiko Jatuh Dewasa",
        'jenis_emr': 'asesmen_medis',
        'data': object
    }
    // console.log(json)
    

    isLoading.value = true
    useApi().post(`/emr/simpan-emr`, json).then(async (response: any) => {
        isLoading.value = false
        NOREC_EMRPASIEN.value = response.norec_emr
        await loadRiwayat()
    }).catch((e: any) => {
        isLoading.value = false
    })
}
const fetchPasien = () => {
    pasien.value = props.pasien
    pasien.value.registrasi = props.registrasi
    NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
    console.log(norec_emr)
}

const addNewItem = () => {
    input.value.details.push({
        no: input.value.details[input.value.details.length - 1].no + 1,
        tgl_SM: new Date(),
        Petugas: { label: user.namaLengkap, value: user.id }
    });
}
const removeItem = (index: any) => {
    let urut = input.value.details.length - 1
    input.value.details.splice(urut, 1)
        // input.value.details.forEach((detail, currentIndex) => {
        //     detail.isDelete = (currentIndex === urut);
        // });
}
const addNewItem2 = () => {
    if (!input.value.details2) {
        input.value.details2 = [];
    }
    const lastItem = input.value.details2[input.value.details2.length - 1] || { no: 0 };
    input.value.details2.push({
        no: lastItem.no + 1,
        tgl_MonitoringEvaluasi: new Date(),
        petugas: { label: user.namaLengkap, value: user.id }
    });
}

// const removeItem2 = (index: any) => {
//     let urut = input.value.details2.length - 1
//     input.value.details2.splice(urut, 1)
// }

const removeItem2 = (index) => {
    let urut = input.value.details2.length - 1
    // input.value.details2.forEach((detail, currentIndex) => {
    //     detail.isDelete = (currentIndex === urut);
    // });
    if (input.value.details2 || input.value.details2.length > index) {
        input.value.details2.splice(index, 1);
        // input.value.details2.forEach((detail, currentIndex) => {
        //     detail.isDelete = (currentIndex === index);
        // });
        // input.value.details2[index]['isDelete'] = true;
    }
};


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

var dat = input.value
watchEffect(() => {
    let total = 0;
    input.value.details.forEach((a, index) => {
        let usia = parseFloat(a['Usia'] ?? 0)
        let defisitSensoris = parseFloat(a['DefisitSensoris'] ?? 0)
        let aktifitas = parseFloat(a['Aktifitas'] ?? 0)
        let riwayatJatuh = parseFloat(a['RiwayatJatuh'] ?? 0)
        let kognisi = parseFloat(a['Kognisi'] ?? 0)
        let pengobatan = parseFloat(a['Pengobatan'] ?? 0)
        let mobilitas = parseFloat(a['Mobilitas'] ?? 0)
        let polababk = parseFloat(a['PolaBABK'] ?? 0)
        let komorbiditas = parseFloat(a['Komorbiditas'] ?? 0)

        total = usia + defisitSensoris + aktifitas + riwayatJatuh + kognisi + pengobatan + mobilitas + polababk + komorbiditas
        if (!isNaN(total)) {
            a['TotalSM'] = total
        } else {
            a['TotalSM'] = 0
        }

        if (a['TotalSM'] <= 7 && a['TotalSM'] >= 0) {
            a['Resiko'] = '0-7'
        } else if (a['TotalSM'] >= 8 && a['TotalSM'] <= 13) {
            a['Resiko'] = '8-13'
        } else if (a['TotalSM'] >= 14) {
            a['Resiko'] = '≥ 14'
        } else {
            a['Resiko'] = undefined
        }
    });
});

// watch(() => [input.value.jumlahNilaiSN], ([a]) => {
//     a = a ?? 0;
//     if (a <= 7) {
//         input.value.nilairesiko = 'Risiko rendah 0-7'
//     } else if (a <= 13) {
//         input.value.nilairesiko = 'Risiko sedang 8-13'
//     } else if (a <= 14) {
//         input.value.nilairesiko = 'Risiko sangat tinggi > 13'
//     }
// });

fetchPasien();

</script>

<style lang="scss">
.table {
    border-collapse: collapse;
    width: 100% !important;
}

.table td {
    height: 4rem !important;
    text-align: center !important;
    border: 1px solid black !important;
    // border-left: none !important;
}

label {
    color: black !important;
}

.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 150% !important;
}

.tg2 {
    border-collapse: collapse;
    border-spacing: 0;
    width: 120% !important;
}

.tg2 td {
    height: 4rem !important;
    border: 1px solid black !important;
    vertical-align: middle !important;
    padding: 5px !important;
}

.tg2 th {
    height: 4rem !important;
    border: 1px solid black !important;
    vertical-align: middle !important;
    padding: 5px !important;
    text-align: center !important;
}

.tg3 {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100% !important;
}

.tg3 td {
    height: 4rem !important;
    border: 1px solid black !important;
    vertical-align: middle !important;
    padding: 5px !important;
}

.tg3 th {
    text-align: center !important;
    height: 4rem !important;
    border: 1px solid black !important;
    vertical-align: middle !important;
    padding: 5px !important;
}

.tg34 {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100% !important;
}

.tg34 td {
    height: 6rem !important;
    border: 1px solid black !important;
    vertical-align: middle !important;
    padding: 5px !important;
}

.tg34 th {
    text-align: center !important;
    height: 6rem !important;
    border: 1px solid black !important;
    vertical-align: middle !important;
    padding: 5px !important;
}

.tg td {
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg th {
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: normal;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg .tg-0lax {
    text-align: left;
    vertical-align: middle
}

hr {
    background-color: hsl(0deg 6.81% 88.68%);
    border: none;
    display: block;
    height: 2px;
    margin: 0px;
}

.alert-nobg {
    background: none !important;
    height: 5em;
    width: 5em;
}
</style>
