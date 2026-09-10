<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/asesmen-awal.scss';

.v-avatar.is-medium.active {
    padding: 3px;
    background: var(--success);
    display: inline-table !important;
}

.p-fieldset-legend {
    margin-left: 14px;
}

.p-fieldset .p-fieldset-content {
    background: none;
}

// .p-fieldset.p-component{
//     border-left: ;
// }

table.assesment {
    border-collapse: collapse;
    width: 100%;
}


.assesment th {
    text-align: center !important;
    border-bottom: 1px solid black;
    // border: 1px solid black;
}

.assesment th,
.assesment td {
    padding: 8px;
    vertical-align: middle !important;
}

hr {
    background-color: hsl(0deg 6.81% 88.68%);
    border: none;
    display: block;
    height: 2px;
    margin: 1rem 0;
}
</style>
<style lang="scss">
.table-fro {
    width: 100%;
    border: 1px solid black;
}

.th-fro,
.td-fro {
    padding: 7px;
    border: 1px solid black;
    vertical-align: inherit;
}

.setFRO-center {
    text-align: center !important;
}

.p-fieldset-legend {
    margin-left: 15px;
}

.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 150%;
}

.tg td {
    // border-color: var(--fade-grey-dark-2);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg th {
    // border-color: var(--fade-grey-dark-3);
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
</style>

<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Ikhtisar Perawatan Pasien HIV & Terapi Antiretroviral</h3>
                        </div>
                        <div class="right">
                            <div class="buttons">
                                <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                    Kembali
                                </VButton>
                                <!-- <VButton type="button" rounded outlined color="warning"
                                    :disabled="NOREC_EMRPASIEN == undefined" raised icon="lnir lnir-printer"
                                    @click="print()"> Cetak
                                </VButton> -->
                                <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                    :loading="isLoading || isLoadingPasien" @click="simpan()"> Simpan
                                </VButton>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- form baru -->
                <div class="column is-12 pb-0">
                    <div class="columns is-multiline">
                        <div class="column is-12" style="text-align: center;">
                            <label style="font-weight: bold;">
                                DATA IDENTITAS PASIEN
                            </label>
                        </div>
                        <div class="column is-3">
                            <h1>Nomor Registrasi</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBNoregis" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <h1>NIK</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBNIK" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <h1>Jenis Kelamin</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBJenisKelamin" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <h1>Nama Ibu Kandung</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBNamaIbuKandung" />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-0">
                            <h1>Tanggal Lahir / Umur</h1>
                            <div class="columns">
                                <div class="column is-6">
                                    <VDatePicker v-model="input.DTanggalLahir" mode="date" trim-weeks
                                        :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" v-on="inputEvents" />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </div>
                                <div class="column is-6">
                                    <VField addons>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBUmur" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>Tahun</VButton>
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-4 pt-0">
                            <h1>Nama Pengawas Minum Obat (PMO)</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBNamaPMO" />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-0">
                            <h1>Hubungannya Dengan Pasien</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBHubungannyaDenganPasien" />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-0">
                            <h1>Alamat dan no. Telp. PMO</h1>
                            <VField>
                                <VTextarea rows="2" v-model="input.TAAlamatTeleponPMO"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-4 pt-0">
                            <h1>Tanggal Konfirmasi Tes HIV +</h1>
                            <VDatePicker v-model="input.DTglKonfirmasiTesHIV" mode="date" trim-weeks>
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-4 pt-0">
                            <h1>Tempat</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBTempat" />
                            </VControl>
                        </div>
                        <div class="column is-12 pt-0">
                            <h1><i>Entry Point</i></h1>
                        </div>
                        <div class="column is-12 pt-0">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="KIA" label="KIA"
                                    v-model="input.CBKIA_EP" />
                            </VControl><br>
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Rawat Jalan"
                                    label="Rawat Jalan (TB, Anak, Penyakit Dalam, IMS)" v-model="input.CBRJ_EP" />
                            </VControl>
                            <VControl v-if="input.CBRJ_EP == 'Rawat Jalan'">
                                <h1>Lainnya</h1>
                                <VInput type="text" class="input" v-model="input.TBLainnyaRJ_EP" />
                            </VControl><br>
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Rawat Inap" label="Rawat Inap"
                                    v-model="input.CBRawatInap_EP" />
                            </VControl><br>
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Praktek Swasta"
                                    label="Praktek Swasta" v-model="input.CBPS_EP" />
                            </VControl><br>
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Jangkauan"
                                    label="Jangkauan (Penasun, WPS, LSL)" v-model="input.CBJangkauan_EP" />
                            </VControl>
                            <VControl v-if="input.CBJangkauan_EP == 'Jangkauan'">
                                <h1>Lainnya</h1>
                                <VInput type="text" class="input" v-model="input.TBLainnyaJangkauan_EP" />
                            </VControl><br>
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="LSM" label="LSM"
                                    v-model="input.CBLSM_EP" />
                            </VControl><br>
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Datang Sendiri"
                                    label="Datang Sendiri" v-model="input.CBDS_EP" />
                            </VControl><br>
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Lainnya" label="Lainnya"
                                    v-model="input.CBLainnya_EP" />
                            </VControl>
                            <VControl v-if="input.CBLainnya_EP == 'Lainnya'">
                                <h1>Uraikan</h1>
                                <VInput type="text" class="input" v-model="input.TBLainnyaUraikan_EP" />
                            </VControl><br>
                            <label>(Ceklis salah satu untuk yang sesuai, sementara pilihan lainnya diuraikan</label>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12" style="text-align: center; font-size: large;">
                            <label class="mb-3" style="font-weight: bold;">
                                RIWAYAT PRIBADI <span style="font-weight: normal; font-size: small;"><br>(Pilih salah
                                    satu)</span>
                            </label>
                        </div>
                        <div class="column is-6">
                            <div class="columns is-multiline" style="margin-top: 10px;">
                                <div class="column is-12" style="text-align: center;">
                                    <label>Pendidikan</label>
                                </div>
                                <div class="column is-3">
                                    <VCheckbox class="fontcheckbox" v-model="input.CBtidakSekolah"
                                        true-value="Tidak Sekolah" label="Tidak Sekolah" color="primary" circle /><br>
                                </div>
                                <div class="column is-3">
                                    <VCheckbox class="fontcheckbox" v-model="input.CBsd" true-value="SD" label="SD"
                                        color="primary" circle /><br>
                                </div>
                                <div class="column is-3">
                                    <VCheckbox class="fontcheckbox" v-model="input.CBsmp" true-value="SMP" label="SMP"
                                        color="primary" circle /><br>
                                </div>
                                <div class="column is-3">
                                    <VCheckbox class="fontcheckbox" v-model="input.CBsmu" true-value="SMU" label="SMU"
                                        color="primary" circle /><br>
                                </div>
                                <div class="column is-3">
                                    <VCheckbox class="fontcheckbox" v-model="input.CBa_or_p" true-value="Akademi/PT"
                                        label="Akademi/PT" color="primary" circle /><br>
                                </div>
                            </div>
                        </div>
                        <div class="column is-6">
                            <div class="columns is-multiline" style="margin-top: 10px;">
                                <div class="column is-12" style="text-align: center;">
                                    <label>Faktor Risiko</label>
                                </div>
                                <div class="column is-3">
                                    <VCheckbox class="fontcheckbox" v-model="input.CBsvb"
                                        true-value="Seks Vaginal Beresiko" label="Seks Vaginal Beresiko" color="primary"
                                        circle /><br>
                                </div>
                                <div class="column is-3">
                                    <VCheckbox class="fontcheckbox" v-model="input.CBsab"
                                        true-value="Seks Anal Beresiko" label="Seks Anal Beresiko" color="primary"
                                        circle /><br>
                                </div>
                                <div class="column is-3">
                                    <VCheckbox class="fontcheckbox" v-model="input.CBperinatal" true-value="Perinatal"
                                        label="Perinatal" color="primary" circle /><br>
                                </div>
                                <div class="column is-3">
                                    <VCheckbox class="fontcheckbox" v-model="input.CBtranfusiDarah"
                                        true-value="Tranfusi Darah" label="Tranfusi Darah" color="primary" circle /><br>
                                </div>
                                <div class="column is-3">
                                    <VCheckbox class="fontcheckbox" v-model="input.CBnapzaSuntik"
                                        true-value="Napza Suntik" label="Napza suntik" color="primary" circle /><br>
                                </div>
                                <div class="column is-3">
                                    <VCheckbox class="fontcheckbox" v-model="input.CBlainLain" true-value="Lain-lain"
                                        label="Lain-lain" color="primary" circle /><br>
                                </div>
                                <div class="column is-4">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="heightinput input" placeholder="Uraikan.."
                                                v-model.number="input.TBlainlain" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="column is-12">
                    <div class="column is-multiline" style="padding: 10px; padding-top: 0px">
                        <div class="column is-12" style="text-align: center; font-size: large;">
                            <label class="mb-3" style="font-weight: bold;">
                                Riwayat Keluarga / Mitra Seksual / Mitra Penasun
                                <span style="font-weight: normal; font-size: small;"><br>(Pilih salah satu)</span>
                            </label>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline" style="margin-top: 10px;">
                                <div class="column is-12">
                                    <label>Status Pernikahan</label>
                                </div>
                                <div class="column is-4">
                                    <VCheckbox class="fontcheckbox" v-model="input.CBmenikah" true-value="Menikah"
                                        label="Menikah" color="primary" circle /><br>
                                </div>
                                <div class="column is-4">
                                    <VCheckbox class="fontcheckbox" v-model="input.CBbMenikah"
                                        true-value="Belum Menikah" label="Belum Menikah" color="primary" circle /><br>
                                </div>
                                <div class="column is-4">
                                    <VCheckbox class="fontcheckbox" v-model="input.CBjadu" true-value="Janda/Duda"
                                        label="Janda/Duda" color="primary" circle /><br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="column is-12">
                    <div class="column is-multiline" style="padding: 10px; padding-top: 0px">
                        <div class="column is-12" style="text-align: center; font-size: large;">
                            <label class="mb-3" style="font-weight: bold;">
                                Riwayat Terapi Antiretroviral
                            </label>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline" style="margin-top: 10px;">
                                <div class="column is-4">
                                    <label>Pernah menerima ART?</label>
                                    <div class="columns is-multiline" style="margin-top: 0px;">
                                        <div class="column is-6">
                                            <VCheckbox class="fontcheckbox" v-model="input.CBnerimaArt" true-value="Ya"
                                                label="Ya" color="primary" circle />
                                        </div>
                                        <div class="column is-6">
                                            <VCheckbox class="fontcheckbox" v-model="input.CBnerimaArt"
                                                true-value="Tidak" label="Tidak" color="primary" circle />
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-4">
                                    <label>Jika ya : </label>
                                    <div class="columns is-multiline" style="margin-top: 0px;">
                                        <div class="column is-4">
                                            <VCheckbox class="fontcheckbox" v-model="input.CBppia" true-value="PPIA"
                                                label="PPIA" color="primary" circle />
                                        </div>
                                        <div class="column is-4">
                                            <VCheckbox class="fontcheckbox" v-model="input.CBart" true-value="ART"
                                                label="ART" color="primary" circle />
                                        </div>
                                        <div class="column is-4">
                                            <VCheckbox class="fontcheckbox" v-model="input.CBppp" true-value="PPP"
                                                label="PPP" color="primary" circle />
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-4">
                                    <label>Tempat ART dulu : </label>
                                    <div class="columns is-multiline" style="margin-top: 0px;">
                                        <div class="column is-4">
                                            <VCheckbox class="fontcheckbox" v-model="input.CBrsPem" true-value="RS Pem"
                                                label="RS Pem" color="primary" circle />
                                        </div>
                                        <div class="column is-4">
                                            <VCheckbox class="fontcheckbox" v-model="input.CBrsSwasta"
                                                true-value="RS Swasta" label="RS Swasta" color="primary" circle />
                                        </div>
                                        <div class="column is-4">
                                            <VCheckbox class="fontcheckbox" v-model="input.CBpkm" true-value="PKM"
                                                label="PKM" color="primary" circle />
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-3">
                                    <label>Nama, dosis ARV & lama penggunaannya : </label>
                                </div>
                                <div class="column is-4">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="heightinput input"
                                                v-model.number="input.TBDosisARV" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="column is-12">
                    <div class="column is-multiline" style="padding: 10px; padding-top: 0px">
                        <div class="column is-12" style="text-align: center; font-size: large;">
                            <label class="mb-3" style="font-weight: bold;">
                                Pemeriksaan Klinis dan Laboratorium
                            </label>
                        </div>
                        <div class="column is-12">
                            <div class="column is-12 p-2">
                                <div class="columns is-multiline">
                                    <div class="column" style="overflow: auto;">
                                        <table class="tg">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th style="text-align: center;vertical-align: middle;">Tanggal
                                                    </th>
                                                    <th style="text-align: center;vertical-align: middle;">
                                                        Stand.<br>WHO</th>
                                                    <th style="text-align: center;vertical-align: middle;">BB</th>
                                                    <th style="text-align: center;vertical-align: middle;">
                                                        Status Fungsional<br>
                                                        1 = Kerja<br>
                                                        2 = Ambulator<br>
                                                        3 = Baring
                                                    </th>
                                                    <th style="text-align: center;vertical-align: middle;">
                                                        Jumlah CD4 (CD4% pd anak-anak)
                                                    </th>
                                                    <th style="text-align: center;vertical-align: middle;">
                                                        Lain-lain
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(row, rowIndex) in detailPemeriksaanKdanL" :key="rowIndex">
                                                    <td width="200px" v-for="(item, itemIndex) in row.child"
                                                        :key="itemIndex">
                                                        <VField style="padding:0px 10px;" v-if="item.type == 'tanggal'">
                                                            <VControl icon="feather:calendar">
                                                                <VInput
                                                                    v-model="input['tanggalPKL_' + rowIndex + '_' + itemIndex]"
                                                                    type="text" placeholder="Tanggal" class="input" />
                                                            </VControl>
                                                        </VField>
                                                        <VField style="padding:0px 10px;" v-if="item.type == 'textbox'">
                                                            <VControl>
                                                                <VInput
                                                                    v-model="input['textboxPKL_' + rowIndex + '_' + itemIndex]"
                                                                    class="input"></VInput>
                                                            </VControl>
                                                        </VField>
                                                        <VField style="padding:0px 10px;" v-if="item.type == 'text'">
                                                            <span>{{ item.caption }}</span>
                                                        </VField>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <Fieldset :toggleable="true" legend="Nama Paduan ART Orisinal">
                    <div class="column is-multiline" style="padding: 10px; padding-top: 0px">
                        <div class="column is-12">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="paduanART1"
                                    label="1- TDF+3TC(FTC)+EFV" v-model="input.paduanART1" />
                            </VControl>
                        </div>
                        <div class="column is-12">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="paduanART2"
                                    label="2- TDF+3TC(FTC)+NVP" v-model="input.paduanART2" />
                            </VControl>
                        </div>
                        <div class="column is-12">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="paduanART3"
                                    label="3- AZT+3TC+EFV" v-model="input.paduanART3" />
                            </VControl>
                        </div>
                        <div class="column is-12">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="paduanART4"
                                    label="4- AZT+3TC+NVP" v-model="input.paduanART4" />
                            </VControl>
                        </div>
                        <div class="column is-12">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="paduanART5"
                                    label="5- TDF+3TC(FTC)+DTG" v-model="input.paduanART5" />
                            </VControl>
                        </div>
                        <div class="column is-4">
                            <VControl>
                                <VInput v-model="input.TBnpao" class="input" placeholder="5..."></VInput>
                            </VControl>
                        </div>
                    </div>
                </Fieldset>
                <div class="column is-12">
                    <div class="column is-multiline" style="padding: 10px; padding-top: 0px">
                        <div class="column is-12" style="text-align: center; font-size: large;">
                            <label class="mb-3" style="font-weight: bold;">
                                Terapi Antiretroviral (ART)
                            </label>
                        </div>
                        <div class="column is-12 p-2">
                            <div class="columns is-multiline">
                                <div class="column" style="overflow: auto;">
                                    <table class="tg">
                                        <thead>
                                            <tr>
                                                <th style="text-align: center;vertical-align: middle;">Tanggal </th>
                                                <th style="text-align: center;vertical-align: middle;">Substitusi
                                                </th>
                                                <th style="text-align: center;vertical-align: middle;"><i>Switch</i>
                                                </th>
                                                <th style="text-align: center;vertical-align: middle;">Stop</th>
                                                <th style="text-align: center;vertical-align: middle;">Restart</th>
                                                <th style="text-align: center;vertical-align: middle;">Alasan</th>
                                                <th style="text-align: center;vertical-align: middle;">Nama Paduan Baru
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(row, rowIndex) in detailTerapiAntiretroviral" :key="rowIndex">
                                                <td width="200px" v-for="(item, itemIndex) in row.child"
                                                    :key="itemIndex">
                                                    <VField style="padding:0px 10px;" v-if="item.type == 'tanggal'">
                                                        <VDatePicker
                                                            v-model="input['tanggalTA_' + rowIndex + '_' + itemIndex]"
                                                            mode="date" style="width: 100%" trim-weeks
                                                            :max-date="new Date()">
                                                            <template #default="{ inputValue, inputEvents }">
                                                                <VField>
                                                                    <VControl icon="feather:calendar" fullwidth>
                                                                        <VInput :value="inputValue"
                                                                            placeholder="Tanggal" v-on="inputEvents" />
                                                                    </VControl>
                                                                </VField>
                                                            </template>
                                                        </VDatePicker>
                                                    </VField>
                                                    <VField style="padding:0px 10px;" v-if="item.type == 'textbox'">
                                                        <VControl>
                                                            <VInput
                                                                v-model="input['textboxTA_' + rowIndex + '_' + itemIndex]"
                                                                class="input"></VInput>
                                                        </VControl>
                                                    </VField>
                                                    <VField style="padding:0px 10px;" v-if="item.type == 'text'">
                                                        <span>{{ item.caption }}</span>
                                                    </VField>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="column is-12" style="margin-top: 10px;">
                                    <span><b>Alasan SUBTITUSI/<i>SWITCH</i></b></span>
                                </div>
                                <div class="column is-4">
                                    <VControl>
                                        <VCheckbox v-model="input.CBToksisitas" class="pt-1 pb-1 " true-value="true"
                                            label="1. Toksisitas/efek samping" color="primary" square />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <VControl>
                                        <VCheckbox v-model="input.CBhamil" class="pt-1 pb-1 " true-value="true"
                                            label="2. Hamil" color="primary" square />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <VControl>
                                        <VCheckbox v-model="input.CBresikoHamil" class="pt-1 pb-1 " true-value="true"
                                            label="3. Risiko Hamil" color="primary" square />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <VControl>
                                        <VCheckbox v-model="input.CBtbBaru" class="pt-1 pb-1 " true-value="true"
                                            label="4. TB Baru" color="primary" square />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <VControl>
                                        <VCheckbox v-model="input.CBaob" class="pt-1 pb-1 " true-value="true"
                                            label="5. Ada obat baru" color="primary" square />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <VControl>
                                        <VCheckbox v-model="input.CBsoh" class="pt-1 pb-1 " true-value="true"
                                            label="6. Stok obat habis" color="primary" square />
                                    </VControl>
                                </div>
                                <div class="column is-6">
                                    <VControl>
                                        <div class="columns is-multiline">
                                            <div class="column is-6">
                                                <VCheckbox v-model="input.CBalasanLain" class="pt-1 pb-1 "
                                                    true-value="true" label="7. Alasan lain" color="primary" square />
                                            </div>
                                            <div class="column is-6">
                                                <VInput type="text" class="heightinput input" placeholder="Uraikan.."
                                                    v-model.number="input.TBuraikanAL" />
                                            </div>
                                        </div>
                                    </VControl>
                                </div>
                                <div class="column is-12" style="margin-top: 10px;">
                                    <span><b>Alasan hanya untuk/<i>SWITCH</i></b></span>
                                </div>
                                <div class="column is-4">
                                    <VControl>
                                        <VCheckbox v-model="input.CBgpsk" class="pt-1 pb-1 " true-value="true"
                                            label="8. Gagal pengobatan secara klinis" color="primary" square />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <VControl>
                                        <VCheckbox v-model="input.CBgi" class="pt-1 pb-1 " true-value="true"
                                            label="9. Gagal imunologis" color="primary" square />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <VControl>
                                        <VCheckbox v-model="input.CBgv" class="pt-1 pb-1 " true-value="true"
                                            label="10. Gagal virologis" color="primary" square />
                                    </VControl>
                                </div>
                                <div class="column is-12" style="margin-top: 10px;">
                                    <span><b>Alasan STOP</b></span>
                                </div>
                                <div class="column is-4">
                                    <VControl>
                                        <VCheckbox v-model="input.CBToksisitas2" class="pt-1 pb-1 " true-value="true"
                                            label="1. Toksisitas/efek samping" color="primary" square />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <VControl>
                                        <VCheckbox v-model="input.CBhamil2" class="pt-1 pb-1 " true-value="true"
                                            label="2. Hamil" color="primary" square />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <VControl>
                                        <VCheckbox v-model="input.CBgp" class="pt-1 pb-1 " true-value="true"
                                            label="3. Gagal pengobatan" color="primary" square />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <VControl>
                                        <VCheckbox v-model="input.CBab" class="pt-1 pb-1 " true-value="true"
                                            label="4. Adherens buruk" color="primary" square />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <VControl>
                                        <VCheckbox v-model="input.CBs_or_m" class="pt-1 pb-1 " true-value="true"
                                            label="5. Sakit/MRS" color="primary" square />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <VControl>
                                        <VCheckbox v-model="input.CBsoh2" class="pt-1 pb-1 " true-value="true"
                                            label="6. Stok obat habis" color="primary" square />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <VControl>
                                        <VCheckbox v-model="input.CBkb" class="pt-1 pb-1 " true-value="true"
                                            label="7. Kekurangan biaya" color="primary" square />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <VControl>
                                        <VCheckbox v-model="input.CBkpl" class="pt-1 pb-1 " true-value="true"
                                            label="8. Keputusan pasien lainnya" color="primary" square />
                                    </VControl>
                                </div>
                                <div class="column is-6">
                                    <VControl>
                                        <div class="columns is-multiline">
                                            <div class="column is-6">
                                                <VCheckbox v-model="input.CBalasanLain2" class="pt-1 pb-1 "
                                                    true-value="true" label="9. Lain-lain" color="primary" square />
                                            </div>
                                            <div class="column is-6">
                                                <VInput type="text" class="heightinput input" placeholder="Uraikan.."
                                                    v-model.number="input.TBuraikanAL2" />
                                            </div>
                                        </div>
                                    </VControl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="columns is-multiline is-12" style="padding: 10px; padding-top: 0px">
                    <div class="column is-12" style="text-align: center; font-size: large; margin-bottom: 10px">
                        <label class="mb-3" style="font-weight: bold;">
                            Pengobatan TB selama perawatan HIV
                        </label>
                    </div>
                    <div class="columns is-multiline column is-4">
                        <div class="column is-12" style="text-align: center;">
                            <span style="font-weight: bold;">Klasifikasi TB (pilih)</span>
                        </div>
                        <div class="column is-12">
                            <VControl>
                                <VCheckbox v-model="input.CBtbParu" class="pt-1 pb-1" true-value="TB Paru"
                                    label="TB Paru" color="primary" square />
                            </VControl>
                        </div>
                        <div class="column is-6">
                            <VControl>
                                <VCheckbox v-model="input.CBtbExtraParu" class="pt-1 pb-1" true-value="TB Ekstra Paru"
                                    label="TB Ekstra Paru" color="primary" square />
                            </VControl>
                        </div>
                        <div class="column is-6">
                            <VControl>
                                <VInput v-model="input.TBlokasiEP" class="input" placeholder="Lokasi...">
                                </VInput>
                            </VControl>
                        </div>
                    </div>
                    <div class="columns is-multiline column is-4">
                        <div class="column is-12" style="text-align: center;">
                            <span style="font-weight: bold;">Tipe TB</span>
                        </div>
                        <div class="column is-6">
                            <VControl>
                                <VCheckbox v-model="input.CBtipeBaru" class="pt-1 pb-1" true-value="Baru"
                                    label="1. Baru" color="primary" square />
                            </VControl>
                        </div>
                        <div class="column is-6">
                            <VControl>
                                <VCheckbox v-model="input.CBtipeKambuh" class="pt-1 pb-1" true-value="Kambuh"
                                    label="2. Kambuh" color="primary" square />
                            </VControl>
                        </div>
                        <div class="column is-6">
                            <VControl>
                                <VCheckbox v-model="input.CBtipeDefault" class="pt-1 pb-1" true-value="Default"
                                    label="3. Default" color="primary" square />
                            </VControl>
                        </div>
                        <div class="column is-6">
                            <VControl>
                                <VCheckbox v-model="input.CBtipeGagal" class="pt-1 pb-1" true-value="Gagal"
                                    label="4. Gagal" color="primary" square />
                            </VControl>
                        </div>
                    </div>
                    <div class="columns is-multiline column is-4">
                        <div class="column is-12" style="text-align: center;">
                            <span style="font-weight: bold;">Paduan TB</span>
                        </div>
                        <div class="column is-6">
                            <VControl>
                                <VCheckbox v-model="input.CBkategori1" class="pt-1 pb-1" true-value="Kategori I"
                                    label="1. Kategori I" color="primary" square />
                            </VControl>
                        </div>
                        <div class="column is-6">
                            <VControl>
                                <VCheckbox v-model="input.CBkategori2" class="pt-1 pb-1" true-value="Kategori II"
                                    label="2. Kategori II" color="primary" square />
                            </VControl>
                        </div>
                        <div class="column is-6">
                            <VControl>
                                <VCheckbox v-model="input.CBkategori3" class="pt-1 pb-1" true-value="Kategori Anak"
                                    label="3. Kategori Anak" color="primary" square />
                            </VControl>
                        </div>
                        <div class="column is-6">
                            <VControl>
                                <VCheckbox v-model="input.CBkategori4" class="pt-1 pb-1"
                                    true-value="Kategori OAT ini 2 (MDR)" label="4. OAT ini 2 (MDR)" color="primary"
                                    square />
                            </VControl>
                        </div>
                    </div>
                    <div class="column is-12 columns is-multiline">
                        <div class="column is-12" style="text-align: center;">
                            <span style="font-weight: bold;">Tempat Pengobatan TB</span>
                        </div>
                        <div class="column is-12 columns is-multiline">
                            <div class="column is-4">
                                <VField label="Kabupaten">
                                    <VControl>
                                        <VInput type="text" class="input" placeholder="" v-model="input.CB4500000" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField label="Nama sarana kesehatan">
                                    <VControl>
                                        <VInput type="text" class="input" placeholder="" v-model="input.CB4500001" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField label="No Reg. TB Kabupaten/Kota">
                                    <VControl>
                                        <VInput type="text" class="input" placeholder="" v-model="input.CB4500002" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-6">
                                <VField label="Tgl mulai terapi TB">
                                    <VDatePicker v-model="input.CB4500003" mode="date" trim-weeks
                                        :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" placeholder="Tanggal mulai"
                                                    v-on="inputEvents" />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </VField>
                            </div>
                            <div class="column is-6">
                                <VField label="Tgl selesai terapi TB">
                                    <VDatePicker v-model="input.CB4500004" mode="date" trim-weeks
                                        :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" placeholder="Tanggal selesai"
                                                    v-on="inputEvents" />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </VField>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="columns is-multiline" style="padding: 10px; padding-top: 0px">
                    <div class="column is-12" style="text-align: center; font-size: large; margin-bottom: 10px">
                        <label class="mb-3" style="font-weight: bold;">
                            Indikasi Inisiasi ART
                            <span style="font-weight: normal; font-size: small;"><br>(Pilih salah
                                satu)</span>
                        </label>
                    </div>
                    <div class="columns is-multiline column is-12">
                        <div class="column is-3">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Penasun" label="Penasun"
                                        v-model="input.CB4500005" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="WPS" label="WPS"
                                        v-model="input.CB4500006" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="LSL" label="LSL"
                                        v-model="input.CB4500007" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Waria" label="Waria"
                                        v-model="input.CB4500008" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Pasien Ko-Infeksi TB-HIV"
                                        label="Pasien Ko-Infeksi TB-HIV" v-model="input.CB4500009" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square
                                        true-value="Pasien Ko-Infeksi Hepatitis B-HIV"
                                        label="Pasien Ko-Infeksi Hepatitis B-HIV" v-model="input.CB4500010" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square
                                        true-value="Lainnya (CD4<350/Stadium klinis 3 atau 4/Ibu hamil)"
                                        label="Lainnya (CD4<350/Stadium klinis 3 atau 4/Ibu hamil)"
                                        v-model="input.CB4500011" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="columns is-multiline" style="padding: 10px; padding-top: 0px">
                    <div class="column is-12" style="text-align: center; font-size: large; margin-bottom: 10px">
                        <label class="mb-3" style="font-weight: bold;">
                            Ikhtisar <i>Follow-up</i> Perawatan Pasien HIV dan Terapi Antiretroviral (ART)
                        </label>
                    </div>
                    <div class="column is-12" style="overflow: auto;">
                        <table class="tg" style="width: 3500px">
                            <thead>
                                <tr>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="3">#
                                    </th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="3">Tanggal Kunjungan
                                        (Follow-Up)
                                    </th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="3">Rencana Tanggal
                                        Kunjungan y.a.d
                                    </th>
                                    <th style="text-align: center;vertical-align: middle;" colspan="3">
                                        Pasien Rujuk Masuk</th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="3"><b>BB</b> (kg) &
                                        TB untuk anak
                                    </th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="3"><b>Status
                                            Fungsional</b><br>1.
                                        Kerja<br>2. Ambulatori<br>3. Baring</th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="3">Stad.<br>WHO</th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="3">Hamil (Y/T) atau
                                        metode KB</th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="3">Infeksi
                                        Oportunistik <br><br>
                                        (Lihat Petunjuk Kode)</th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="3">Obat untuk IO
                                    </th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="3">Status TB</th>
                                    <th style="text-align: center;vertical-align: middle;" colspan="2">Pengobatan
                                        Pencegahan</th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="3">Obat ARV dan
                                        Dosis yang Diberikan
                                    </th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="3">Sisa obat ARV
                                        Sebelumnya<br><br>(dalam tablet)</th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="3">Adherence
                                        ART<br>1.
                                        (&gt;95%)<br>2. (80-95%)<br>3. (&lt;80%)</th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="3">Efek Samping
                                        ART<br><br>(Lihat
                                        petunjuk dan Kode)</th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="3">Jumlah CD4</th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="3">Hasil Lab</th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="1"><b>Diberikan
                                            Kondom</b><br>Y/T/TT<br>*TT = Tidak Tersedia</th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="3">Rujuk Ke
                                        Spesialis atau MRS</th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="3">Akhir
                                        <i>Follow-UP</i><br><br>(Lihat petunjuk dan Kode)
                                    </th>
                                </tr>
                                <tr>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="2">Y/T</th>
                                    <th style="text-align: center;vertical-align: middle;" colspan="2">
                                        Jika Y,
                                    </th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="2">
                                        PPK Y/T
                                    </th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="2">
                                        PP INH Y/T
                                    </th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="2">Jika Y, Tulis
                                        Jumlah-nya</th>
                                </tr>
                                <tr>
                                    <th style="text-align: center;vertical-align: middle;">
                                        Dengan ART Y/T
                                    </th>
                                    <th style="text-align: center;vertical-align: middle;">
                                        Nama Klinik Sebelumnya
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(row, rowIndex) in detailPPHaTA" :key="rowIndex">
                                    <td style="vertical-align: inherit">
                                        <VButtons style="justify-content:space-around">
                                            <VIconButton type="button" raised circle icon="feather:plus"
                                                @click="addNewItem()" color="info" v-tooltip.bubble="'Tambah '">
                                            </VIconButton>
                                            <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle
                                                icon="feather:trash" @click="removeItem(index)" color="danger">
                                            </VIconButton>
                                        </VButtons>
                                    </td>
                                    <td width="200px" v-for="(item, itemIndex) in row.child" :key="itemIndex">
                                        <VField style="padding:0px 10px;" v-if="item.type == 'tanggal'">
                                            <VDatePicker v-model="input['tanggalPPHaTA_' + rowIndex + '_' + itemIndex]"
                                                mode="date" style="width: 100px" trim-weeks :max-date="new Date()">
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
                                        <VField style="padding:0px 10px;" v-if="item.type == 'textbox'">
                                            <VControl>
                                                <VInput v-model="input['textboxPPHaTA_' + rowIndex + '_' + itemIndex]"
                                                    class="input">
                                                </VInput>
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr v-for="(input, index) in input.details" :key="index">
                                    <td style="vertical-align: inherit">
                                        <VButtons style="justify-content:space-around">
                                            <VIconButton type="button" raised circle icon="feather:plus"
                                                @click="addNewItem()" color="info" v-tooltip.bubble="'Tambah '">
                                            </VIconButton>
                                            <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle
                                                icon="feather:trash" @click="removeItem(index)" color="danger">
                                            </VIconButton>
                                        </VButtons>
                                    </td>
                                    <td>
                                        <VField style="padding:0px 10px;">
                                            <VDatePicker v-model="input.tanggalPPHaTA" mode="date" style="width: 100px"
                                                trim-weeks :max-date="new Date()">
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
                                    </td>
                                    <td>
                                        <VField style="padding:0px 10px;">
                                            <VControl>
                                                <VInput v-model="input.textboxPPHaTA1" class="input" style="width: 100px">
                                                </VInput>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField style="padding:0px 10px;">
                                            <VControl>
                                                <VInput v-model="input.textboxPPHaTA2" class="input" style="width: 100px">
                                                </VInput>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField style="padding:0px 10px;">
                                            <VControl>
                                                <VInput v-model="input.textboxPPHaTA3" class="input" style="width: 100px">
                                                </VInput>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField style="padding:0px 10px;">
                                            <VControl>
                                                <VInput v-model="input.textboxPPHaTA4" class="input" style="width: 100px">
                                                </VInput>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField style="padding:0px 10px;">
                                            <VControl>
                                                <VInput v-model="input.textboxPPHaTA5" class="input" style="width: 100px">
                                                </VInput>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField style="padding:0px 10px;">
                                            <VControl>
                                                <VInput v-model="input.textboxPPHaTA6" class="input" style="width: 100px">
                                                </VInput>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField style="padding:0px 10px;">
                                            <VControl>
                                                <VInput v-model="input.textboxPPHaTA7" class="input" style="width: 100px">
                                                </VInput>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField style="padding:0px 10px;">
                                            <VControl>
                                                <VInput v-model="input.textboxPPHaTA8" class="input" style="width: 100px">
                                                </VInput>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField style="padding:0px 10px;">
                                            <VControl>
                                                <VInput v-model="input.textboxPPHaTA9" class="input" style="width: 100px">
                                                </VInput>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField style="padding:0px 10px;">
                                            <VControl>
                                                <VInput v-model="input.textboxPPHaTA10" class="input" style="width: 100px">
                                                </VInput>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField style="padding:0px 10px;">
                                            <VControl>
                                                <VInput v-model="input.textboxPPHaTA11" class="input" style="width: 100px">
                                                </VInput>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField style="padding:0px 10px;">
                                            <VControl>
                                                <VInput v-model="input.textboxPPHaTA12" class="input" style="width: 100px">
                                                </VInput>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField style="padding:0px 10px;">
                                            <VControl>
                                                <VInput v-model="input.textboxPPHaTA13" class="input" style="width: 100px">
                                                </VInput>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField style="padding:0px 10px;">
                                            <VControl>
                                                <VInput v-model="input.textboxPPHaTA14" class="input" style="width: 100px">
                                                </VInput>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField style="padding:0px 10px;">
                                            <VControl>
                                                <VInput v-model="input.textboxPPHaTA15" class="input" style="width: 100px">
                                                </VInput>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField style="padding:0px 10px;">
                                            <VControl>
                                                <VInput v-model="input.textboxPPHaTA16" class="input" style="width: 100px">
                                                </VInput>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField style="padding:0px 10px;">
                                            <VControl>
                                                <VInput v-model="input.textboxPPHaTA17" class="input" style="width: 100px">
                                                </VInput>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField style="padding:0px 10px;">
                                            <VControl>
                                                <VInput v-model="input.textboxPPHaTA18" class="input" style="width: 100px">
                                                </VInput>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField style="padding:0px 10px;">
                                            <VControl>
                                                <VInput v-model="input.textboxPPHaTA19" class="input" style="width: 100px">
                                                </VInput>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField style="padding:0px 10px;">
                                            <VControl>
                                                <VInput v-model="input.textboxPPHaTA20" class="input" style="width: 100px">
                                                </VInput>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField style="padding:0px 10px;">
                                            <VControl>
                                                <VInput v-model="input.textboxPPHaTA21" class="input" style="width: 100px">
                                                </VInput>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField style="padding:0px 10px;">
                                            <VControl>
                                                <VInput v-model="input.textboxPPHaTA22" class="input" style="width: 100px">
                                                </VInput>
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="column is-12 columns is-multiline" style="margin-top:15px">
                        <div class="column is-6">
                            <span><b><i>Petunjuk dan Kode : </i></b></span><br>
                            <span><b>Tanggal : </b>Tulis tanggal kunjungan yang sebenarnya sejak kunjungan pertama
                                perawatan
                                HIV</span><br><br>

                            <span><b>Infeksi Oportunistik : Tuliskan ≥ </b>1 kode - Kandidiasis (K); Diare
                                cryptosporidia (D);
                                Meningitis
                                cryptocococal (Cr);
                                Pneumonia Pneumocystis (PCP); Cytomegalovirus (CMV); Penicilliosis (P); Herpes
                                zoster (Z);
                                Herpessimpleks (S);
                                Toxoplasmosis (T); Hepatitis (H); Lain-lain-uraikan</span><br><br>

                            <span><b>Status TB : </b>Tdk ada gejala/tanda TB; 2. Suspek TB (rujuk ke klinik DOTS atau
                                pemeriksaan
                                sputum); 3. Dalam terapi TB 4. Tidak dilakukan skrining</span><br><br>

                            <span><b>PPk : </b>Pengobatan Pencegahan dengan Kotrimoksazol</span><br><br>

                            <span><b>INH : </b>Pengobatan Pencegahan dengan INH (isoniazid)</span><br><br>
                        </div>
                        <div class="column is-6">
                            <span><b>Adherence ART : </b>Periksalah adherence dgn menanyakan apakah pasien melupakan
                                dosis obat.
                                Tuliskan perkiraan
                                tingkat adherence, misainya (dosis 2 kali sehari)1 (>85%) artinya &lt; 3 dosis lupa
                                diminum
                                dalam 30 har; 2 (80-95%) artinya 3-12 dosis lupa diminum dalam 30 hari; 3 (&lt; 80%)
                                arinya>12 dosis lupa diminum dalam
                                30 hari</span><br><br>

                            <span><b>Efek Samping : </b>Tuliskan ≥ 1 kode — R = Ruam kulit; Mua = Mual; Mun = Muntah; D
                                = Diare;
                                N = Neuropati;Ikt=Ikterus;
                                An = Anemi;
                                LI = Lelah; SK = Sakit kepala; Dem = Demam; Hip = Hipersensitifitas; Dep = Depresi;
                                P = Pankreatitis;
                                Lip = Lipodistrofi;
                                Ngan = Mengantuk; Ln = Lain-lain- Uraikan</span><br><br>

                            <span><b>Akhir Follow Up : </b>Tuliskan kode- M (jika pasien meninggal -> tulis tanggal
                                meninggal), LFU
                                (jika pasien >3 bulan tidak
                                datang ke layanan -> tulis tanggal kunjungan terakhir), atau RK (jika pasien dirujuk
                                keluar -> tulis
                                tanggal rujuk keluar dan
                                nama klinik barunya).</span><br><br>
                        </div>
                    </div>
                </div>

                <!-- form baru -->
            </div>
        </div>

    </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
// import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import * as EMR from '../page-emr-plugins/Ikhtisar-PPH&TA'

// Loopingan
let detailPemeriksaanKdanL = ref(EMR.detailPemeriksaanKdanL())
let detailTerapiAntiretroviral = ref(EMR.detailTerapiAntiretroviral())
let detailPPHaTA = ref(EMR.detailPPHaTA())

// Judul
useHead({
    title: 'Ikhtisar Perawatan Pasien HIV - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
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
const route = useRoute()
const pasien: any = ref({})
const d_pegawai: any = ref([])
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
    airway: [],
    disability: []
})
const COLLECTION: any = ref('IkhtisarPerawatanPasienHIVdanTerapiAntiretroviral') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    TJamKeluar: new Date(),
    details: [{
        no: 1,
    }],
})

const addNewItem = () => {
    input.value.details.push({
        no: input.value.details[input.value.details.length - 1].no + 1,
    });
}

const removeItem = (index: any) => {
    input.value.details.splice(index, 1)
}

const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
function calculateAge(birthdate) {
    const today = new Date();
    const birthDate = new Date(birthdate);
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }

    return age;
}
const loadRiwayat = async () => {
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
    } else {
        input.value.TBNoregis = props.registrasi.noregistrasi
        input.value.TBNIK = props.pasien.noidentitas
        input.value.TBJenisKelamin = props.pasien.jeniskelamin
        input.value.DTanggalLahir = props.pasien.tgllahir
        input.value.TBUmur = calculateAge(props.pasien.tgllahir)
        input.value.TAAlamatTeleponPMO = `Alamat : ${props.pasien.alamatlengkap}\nNomor Telepon : ${props.pasien.nohp}`
    }
}
const simpan = async () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    object.nocm = pasien.value.nocm

    object.pasien = H.setObjectPasien(pasien.value)
    object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)

    if (object.hasOwnProperty('namatemplate')) {
        delete object.namatemplate
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
    console.log(json)

    isLoading.value = true
    await useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
        isLoading.value = false
        loadRiwayat()
    }).catch((e: any) => {
        isLoading.value = false
    })

    // console.log(resultValue)
}
const kembaliKeun = () => {
    window.history.back()
}
const fetchPasien = () => {
    pasien.value = props.pasien
    pasien.value.registrasi = props.registrasi
    NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
    console.log(norec_emr)
}
// const getDataExist = async () => {
//     await useApi().get(`emr/get-data-exist?nocmfk=${ID_PASIEN}`).then((response) => {
//         input.value.beratBadan = response.beratBadan ? response.beratBadan : ''
//         input.value.tinggiBadan = response.tinggiBadan ? response.tinggiBadan : ''
//         input.value.IMT = response.IMT ? response.IMT : response.IMT
//         input.value.lingkarPerut = response.lingkarPerut ? response.lingkarPerut : ''
//         input.value.tekananDarah = response.tekananDarah ? response.tekananDarah : ''
//         input.value.nadi = response.nadi ? response.nadi : ''
//         input.value.suhu = response.suhu ? response.suhu : ''
//         input.value.pernapasan = response.pernapasan ? response.pernapasan : ''
//     })
// }
const print = async () => {
    H.printBlade(`emr/cetak/${COLLECTION.value}?emrpasienfk=${NOREC_EMRPASIEN.value}`)
}

onBeforeMount(async () => {
    try {
        await loadRiwayat()
        let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
        if (cache) input.value = cache

        if (!input.value.details || !Array.isArray(input.value.details)) {
            input.value.details = [{ no: 1 }];
        }
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


// getDataExist()
fetchPasien()

</script>