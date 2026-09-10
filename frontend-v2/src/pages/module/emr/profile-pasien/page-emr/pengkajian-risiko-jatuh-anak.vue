<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header" style="margin-bottom: 10px">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Pengkajian Risiko Jatuh Anak-Anak (Skala Humpty Dumpty)</h3>
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
                        <div class="column is-8 is-flex"
                            style="font-weight: bold;font-size: large;align-items: center;">

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
                                            style="text-align: center;vertical-align: middle;width: 100%;font-weight: bold !important;">
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
                                    <td colspan="2">Total Skor</td>
                                </tr>
                                <tr>
                                    <td colspan="2">Keterangan</td>
                                </tr>
                                <tr>
                                    <td>Resiko Rendah</td>
                                    <td style="text-align: center;">7-11</td>
                                </tr>
                                <tr>
                                    <td>Resiko Tinggi</td>
                                    <td style="text-align: center;">≥ 12</td>
                                </tr>

                                <tr>
                                    <td colspan="2">Nama/Paraf</td>
                                </tr>
                            </table>
                        </div>
                        <div class="column is-7 pl-0" style="overflow: auto;">
                            <table>
                                <tr>
                                    <td v-for="(data, index) in input.details" :key="index" class="p-0">
                                        <table class="table" style="width: 100% !important;">
                                            <tr>
                                                <td style="font-weight: bold; width: 200px; ">Tanggal & Jam</td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: lightgray !important;">
                                                    <VDatePicker v-model="data['tgl_SM']" mode="datetime" is24hr>
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
                                                        <VCheckbox class="p-0" color="primary" square true-value="4"
                                                            label="" v-model="data['Usia']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="3"
                                                            label="" v-model="data['Usia']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="2"
                                                            label="" v-model="data['Usia']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="1"
                                                            label="" v-model="data['Usia']" />
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
                                                            label="" v-model="data['Jenikelamin']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="1"
                                                            label="" v-model="data['Jenikelamin']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: lightgray !important;"></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="4"
                                                            label="" v-model="data['diagnosa']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol style="height: 59px;">
                                                        <VCheckbox class="p-0" color="primary" square true-value="3"
                                                            label="" v-model="data['diagnosa']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="2"
                                                            label="" v-model="data['diagnosa']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="1"
                                                            label="" v-model="data['diagnosa']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: lightgray !important;"></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="3"
                                                            label="" v-model="data['gkognitif']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="2"
                                                            label="" v-model="data['gkognitif']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="1"
                                                            label="" v-model="data['gkognitif']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: lightgray !important;"></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="4"
                                                            label="" v-model="data['faktorlingkungan']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="3"
                                                            label="" v-model="data['faktorlingkungan']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="2"
                                                            label="" v-model="data['faktorlingkungan']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="1"
                                                            label="" v-model="data['faktorlingkungan']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: lightgray !important;"></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="3"
                                                            label="" v-model="data['respon']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="2"
                                                            label="" v-model="data['respon']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="1"
                                                            label="" v-model="data['respon']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: lightgray !important;"></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="3"
                                                            label="" v-model="data['penggunaanobat']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="2"
                                                            label="" v-model="data['penggunaanobat']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="1"
                                                            label="" v-model="data['penggunaanobat']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>{{ data['TotalSM'] ? data['TotalSM'] : 0 }}</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl>
                                                        <VInput type="text" class="input"
                                                            v-model="data['Keterangan']" />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="7-11"
                                                            label="" v-model="data['Resiko']" disabled />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value=">=12"
                                                            label="" v-model="data['Resiko']" disabled />
                                                    </VControl>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <VControl class="prime-auto">
                                                        <AutoComplete v-model="data['Petugas']" :suggestions="d_Petugas"
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
                            <table class="tg3" style="width: 100% !important;">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;vertical-align: middle;width: 20%">
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
                                        <td rowspan="12" style="text-align: center;">RESIKO RENDAH SKOR 7 - 11</td>
                                        <td>1. Orientasikan pasien pada lingkungan kamar/bangsal.</td>
                                    </tr>
                                    <tr>
                                        <td>2. Edukasi pasien dan keluarga tentang strategi pencegahan riskio jatuh.</td>
                                    </tr>
                                    <tr>
                                        <td>3. Pastikan rem tempat tidur terkunci</td>
                                    </tr>
                                    <tr>
                                        <td>4. Pastikan pagar tempat tidur terpasang pada semua sisi</td>
                                    </tr>
                                    <tr>
                                        <td>5. Pastikan bel pasien terjangkau</td>
                                    </tr>
                                    <tr>
                                        <td>6. singkirkan barang yang berbahaya iterutam pada malam hari
                                            (kursi tambahan dan lain-lain)
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>7. MInta persetujuan pasien agar lampu malam tetap menyala karena lingkungan masih asing</td>
                                    </tr>
                                    <tr>
                                        <td>8. Pastikan alat bantu jalan dalam jangakauan (bila menggunakan).</td>
                                    </tr>
                                    <tr>
                                        <td>9. Pastikan alas kaki tidak licin.</td>
                                    </tr>
                                    <tr>
                                        <td>10. Pastikan kebutuhan pribadi dalam jangakauan.</td>
                                    </tr>
                                    <tr>
                                        <td>11. Tempatkan meja pasien dengan baik agar tidak menghalangi.</td>
                                    </tr>
                                    <tr>
                                        <td>12. Tempatkan pasien sesuai dengan tinggi badannya.</td>
                                    </tr>
                                    <tr>
                                        <td rowspan="17" style="text-align: center;">RESIKO JATUH TINGGI ≥ 12</td>
                                        <td>1. Orientasikan pasien dan keluarga pada lingkungan kamar/bangsal</td>
                                    </tr>
                                    <tr>
                                        <td>2. Pastikan rem tempat tidur terkunci.</td>
                                    </tr>
                                    <tr>
                                        <td>3. Pastikan pagar tempat tidur terpasang pada semua sisi.</td>
                                    </tr>
                                    <tr>
                                        <td>4. Pastikan bel terjangkau.</td>
                                    </tr>
                                    <tr>
                                        <td>5. Singkirkan barang yang berbahaya terutama pada malam hari (kursi tambahan dan lain-lain)</td>
                                    </tr>
                                    <tr>
                                        <td>6 Minta persetujuan pasien dan keluarga agar lampu malam tetap menyala karena lingkungan masih asing.</td>
                                    </tr>
                                    <tr>
                                        <td>7. Pastikan alat bantu jalan dalam jangkauan (bila menggunakan)</td>
                                    </tr>
                                    <tr>
                                        <td>8. Pastikan alas khak tidak licin.</td>
                                    </tr>
                                    <tr>
                                        <td>9. Pastikan kebutuhan pritiadi dalam jangkauan.</td>
                                    </tr>
                                    <tr>
                                        <td>10. Tempatkan meja pasin dengan baik agar tidak menghalangi.</td>
                                    </tr>
                                    <tr>
                                        <td>11. Tempatkan pasien sesuai dengan tinggi badannya</td>
                                    </tr>
                                    <tr>
                                        <td>12. Pasang penanda risiko jatuh di luar kamar / di brankard / di atas tempat tidur</td>
                                    </tr>
                                    <tr>
                                        <td>13. Minta agar keluarga pasien segera memencet bel bila perlu bantuan atau segera menghubungi petugas </td>
                                    </tr>
                                    <tr>
                                        <td>14. Awasi atau bantu sebagian ADL pasien </td>
                                    </tr>
                                    <tr>
                                        <td>15. Cepat menganggapi bel / keluhan pasien .</td>
                                    </tr>
                                    <tr>
                                        <td>16. Review kembali obat-obatan yang berisiko.</td>
                                    </tr>
                                    <tr>
                                        <td>17. Beritahu pasien dari keluarga agar mobilisasi secara bertahap : perlahan lahan sebelum berdiri.</td>
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
                                    <td v-for="(data, index) in input.details2" :key="index" class="p-0">
                                        <table class="tg3" style="width: 100% !important;">
                                            <tr>
                                                <th style="font-weight: bold; width: 200px;">Tanggal & Jam</th>
                                            </tr>
                                            <tr>
                                                <th style="background-color: lightgray !important;">
                                                    <VDatePicker v-model="data['tgl_MonitoringEvaluasi']"
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
                                                                    v-model="data['RJR_1']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJR_1']" />
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
                                                                    v-model="data['RJR_2']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJR_2']" />
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
                                                                    v-model="data['RJR_3']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJR_3']" />
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
                                                                    v-model="data['RJR_4']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJR_4']" />
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
                                                                    v-model="data['RJR_5']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJR_5']" />
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
                                                                    v-model="data['RJR_6']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJR_6']" />
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
                                                                    v-model="data['RJR_7']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJR_7']" />
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
                                                                    v-model="data['RJR_8']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJR_8']" />
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
                                                                    v-model="data['RJR_9']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJR_9']" />
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
                                                                    v-model="data['RJR_10']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJR_10']" />
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
                                                                    v-model="data['RJR_11']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJR_11']" />
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
                                                                    v-model="data['RJR_12']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJR_12']" />
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
                                                                    v-model="data['RJT_1']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJT_1']" />
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
                                                                    v-model="data['RJT_2']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJT_2']" />
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
                                                                    v-model="data['RJT_3']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJT_3']" />
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
                                                                    v-model="data['RJT_4']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJT_4']" />
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
                                                                    v-model="data['RJT_5']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJT_5']" />
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
                                                                    v-model="data['RJT_6']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJT_6']" />
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
                                                                    v-model="data['RJT_7']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJT_7']" />
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
                                                                    v-model="data['RJT_8']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJT_8']" />
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
                                                                    v-model="data['RJT_9']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJT_9']" />
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
                                                                    v-model="data['RJT_10']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJT_10']" />
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
                                                                    v-model="data['RJT_11']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJT_11']" />
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
                                                                    v-model="data['RJT_12']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJT_12']" />
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
                                                                    v-model="data['RJT_13']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJT_13']" />
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
                                                                    v-model="data['RJT_14']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJT_14']" />
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
                                                                    v-model="data['RJT_15']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJT_15']" />
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
                                                                    v-model="data['RJT_16']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJT_16']" />
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
                                                                    v-model="data['RJT_17']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['RJT_17']" />
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
                                                                    v-model="data['TerjadiInsidenJatuh']" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="data['TerjadiInsidenJatuh']" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <VControl class="prime-auto">
                                                        <AutoComplete v-model="data['petugas']" :suggestions="d_Petugas"
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
                                    2. Pada kolom evaluasi, berilah tanda (✓) pada pilihan ya atau tidak<br>

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
        FORM_NAME: '',
        FORM_URL: '',
        COLLECTION: '',
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
    disability: []
})
const COLLECTION: any = ref('PengkajianResikoJatuhAnak') //table mongodb
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
const PSM: any = ref([
    {
        nama: 'USIA', rowspan: 4, dmodel: 'DUsia',
        detail: [
            { type: 'checkbox', label: 'a. Dibawah 3 tahun', model: 'usia', value: 4 },
            { type: 'checkbox', label: 'b. 3- 7 tahun', model: 'usia', value: 3 },
            { type: 'checkbox', label: 'c. 7 - 13 tahun', model: 'usia', value: 2 },
            { type: 'checkbox', label: 'd. > 13 tahun', model: 'usia', value: 1 }
        ]
    },
    {
        nama: 'JENIS KELAMIN', rowspan: 2, dmodel: 'DJeniskelamin',
        detail: [
            { type: 'checkbox', label: 'a. Laki-laki', model: 'jeniskelamin', value: 2 },
            { type: 'checkbox', label: 'b. Perempuan', model: 'jeniskelamin', value: 1 },

        ]
    },
    {
        nama: 'DIAGNOSA', rowspan: 3, dmodel: 'DDiagnosa',
        detail: [
            { type: 'checkbox', label: 'a. Diagnosa Neurologi', model: 'diagnosa', value: 4 },
            { type: 'checkbox', label: 'b. Perubahan dalam oksigen (Masalah Saluran Nafas, Dehidrasi, Anemia, Anoreksia, Sinkop / sakit kepala, dll)', model: 'diagnosa', value: 3 },
            { type: 'checkbox', label: 'c. Kelainan Psikis / Perilaku', model: 'diagnosa', value: 2 },
            { type: 'checkbox', label: 'd. Diagnosa Lain', model: 'diagnosa', value: 1 }
        ]
    },
    {
        nama: 'GANGGUAN KOGNITIF', rowspan: 4, dmodel: 'DGkognitif',
        detail: [
            { type: 'checkbox', label: 'a. Tidak sadar terhadap keterbatasan dirinya', model: 'gkognitif', value: 3 },
            { type: 'checkbox', label: 'b. Lupa keterbatasan', model: 'gkognitif', value: 2 },
            { type: 'checkbox', label: 'c. mengetahui kemampuan diri ', model: 'gkognitif', value: 1 },
        ]
    },
    {
        nama: 'FAKTOR LINGKUNGAN', rowspan: 5, dmodel: 'DFaktorlingkungan',
        detail: [
            { type: 'checkbox', label: 'a. Riwayat Jatuh dari tempat tidur saaat bayi-anak', model: 'faktorlingkungan', value: 4 },
            { type: 'checkbox', label: 'b. Pasien menggunakan alat bantu atau box atau mebel', model: 'faktorlingkungan', value: 3 },
            { type: 'checkbox', label: 'c. pasien berada di tempat tidur', model: 'faktorlingkungan', value: 2 },
            { type: 'checkbox', label: 'd. Diluar ruang rawat', model: 'faktorlingkungan', value: 1 }
        ]
    },
    {
        nama: 'RESPON TERHADAP OPERASI / OBAT', rowspan: 4, dmodel: 'DRespon',
        detail: [
            { type: 'checkbox', label: 'a. Dalam 24 jam', model: 'respon', value: 3 },
            { type: 'checkbox', label: 'b. Dalam 48 jam riwayat jatuh', model: 'respon', value: 2 },
            { type: 'checkbox', label: 'c. > 48 jam', model: 'respon', value: 1 },
        ]
    },
    {
        nama: 'PENGGUNAAN OBAT', rowspan: 6, dmodel: 'DPenggunaanobat',
        detail: [
            { type: 'checkbox', label: 'Bermacam-macam obat yang digunakan: obat sedatif(kecuali pasien ICU yang menggunakan obat sedasi dan paralisis), hipnotik, barbiturat, fenotiazin, antidepresan, laksans / diuretika, narkotik', model: 'penggunaanobat', value: 3 },
            { type: 'checkbox', label: 'Salah Satu Pengobatan Diatas', model: 'penggunaanobat', value: 2 },
            { type: 'checkbox', label: 'Penggunaan Obat Lainnya', model: 'penggunaanobat', value: 1 },
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
        label: "RESIKO JATUH TINGGI (2) \n (PROTOKOL 1,2)",
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
        label: "RESIKO JATUH SANGAT TINGGI (2) \n (PROTOKOL 1,2,3)",
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
    { value: 2, label: 'b) CC (Change of Condition)' },
    { value: 3, label: 'c) WT (On Ward Transfer)' },
    { value: 4, label: 'd) DC (Discharge)' },
    { value: 5, label: 'e) ES (Every Shift)' },

])
const input: any = ref({
    details: [{
        no: 1,
        tgl_SM: new Date(),
        Petugas: { label: user.namaLengkap, value: user.id }
    }],
    details2: [{
        no: 1,
        tgl_MonitoringEvaluasi: new Date(),
        petugas: { label: user.namaLengkap, value: user.id }
    }],
})

const fetchDokter = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`).then((response) => {
        d_Dokter.value = response
    })
}

const loadRiwayat = async () => {
    // isLoading.value = true
    // let histori = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    let histori = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`)
    // console.log(histori);
    // return;
    if (histori.length) {
        input.value = histori[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = histori[0].emrpasienfk
        }
        // dataTTD.value = response[0]
        // H.tandaTangan().set("ttdPegawai", dataTTD.value.ttdPegawai)
    }
    isLoading.value = false
}
const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

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
        'url_form': route.name,
        'name_form': 'Pengkajian Resiko Jatuh Anak-Anak (Skala Humpty Dumpty)',
        'jenis_emr': 'asesmen_medis',
        'data': object
    }
    console.log(json)

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
}
const addNewItem2 = () => {
    input.value.details2.push({
        no: input.value.details2[input.value.details2.length - 1].no + 1,
        tgl_SM: new Date(),
        petugas: { label: user.namaLengkap, value: user.id }
    });
}
const removeItem2 = (index: any) => {
    let urut = input.value.details2.length - 1
    input.value.details2.splice(urut, 1)
}


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
        let Jenikelamin = parseFloat(a['Jenikelamin'] ?? 0)
        let diagnosa = parseFloat(a['diagnosa'] ?? 0)
        let gkognitif = parseFloat(a['gkognitif'] ?? 0)
        let faktorlingkungan = parseFloat(a['faktorlingkungan'] ?? 0)
        let respon = parseFloat(a['respon'] ?? 0)
        let penggunaanobat = parseFloat(a['penggunaanobat'] ?? 0)

        total = usia + Jenikelamin + diagnosa + gkognitif + faktorlingkungan + respon + penggunaanobat
        if (!isNaN(total)) {
            a['TotalSM'] = total
        } else {
            a['TotalSM'] = 0
        }

        if (a['TotalSM'] <= 11 && a['TotalSM'] >= 7) {
            a['Resiko'] = '7-11'
        } else if (a['TotalSM'] >= 12) {
            a['Resiko'] = '>=12'
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
   table-layout: fixed;
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
     table-layout: fixed;
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
