<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Asesmen Awal Keperawatan Pasien Rawat Jalan</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <div class="column is-12" style="margin-top: 30px;">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                    <h1 class="mb-3" style="font-weight: bold;">Tanggal Kedatangan</h1>
                                    <VField>
                                        <VDatePicker v-model="input.kebtanggalKedatangan" mode="date" trim-weeks
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
                                <div class="column is-4">
                                    <h1 class="mb-3" style="font-weight: bold;">Jam Kedatangan</h1>
                                    <VField>
                                        <VDatePicker v-model="input.kebjamKedatangan" mode="time" style="width: 100%"
                                            trim-weeks :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField>
                                                    <VControl icon="feather:calendar" fullwidth>
                                                        <VInput :value="inputValue" placeholder="Jam"
                                                            v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <h1 class="mb-3" style="font-weight: bold;">Jam Asesmen Awal</h1>
                                    <VField>
                                        <VDatePicker v-model="input.kebjamAsesmenAwal" mode="time" style="width: 100%"
                                            trim-weeks :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField>
                                                    <VControl icon="feather:calendar" fullwidth>
                                                        <VInput :value="inputValue" placeholder="Jam"
                                                            v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                            </div>

                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <h1 class="mb-3 emr">Pilih Template</h1>
                                    <VField>
                                        <VControl>
                                            <input v-model="input.kebtemplate" class="heightinput input" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <h1 class="mb-3 emr">Pilih Riwayat</h1>
                                    <VField>
                                        <VControl>
                                            <input v-model="input.kebriwayat" class="heightinput input" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="column is-12" style="margin-top: -10px;">
                    <div class="column is-12 pl-0 pr-0">
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <div class="columns is-multiline">
                                        <div class="column is-4 mt-auto mb-auto pl-0">
                                            <h1 style="font-weight: bold;">Rujukan
                                            </h1>
                                        </div>
                                        <div class="column is-4 mt-auto mb-auto">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kebrujukan"
                                                        true-value="YA" label="Ya" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-4 mt-auto mb-auto">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kebrujukan"
                                                        true-value="TIDAK" label="Tidak" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12" v-if="input.kebrujukan == 'YA'">
                                    <div class="columns is-multiline">
                                        <div class="column is-2 pl-0">
                                            <h3 style="font-weight: bold;">
                                                Dari
                                            </h3>
                                        </div>
                                        <div class="column is-10">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" class="heightinput input"
                                                        placeholder="Ket Rujukan"
                                                        v-model.number="input.kebketrujukan" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-6" v-else-if="input.kebrujukan == 'TIDAK'">
                                    <div class="columns is-multiline">
                                        <div class="column is-4 mt-auto mb-auto">
                                            <h1 style="font-weight: bold;">Kedatangan
                                            </h1>
                                        </div>
                                        <div class="column is-4 mt-auto mb-auto">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.kebrujuklanjutan" @change="" true-value="SENDIRI"
                                                        label="Sendiri" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-4 mt-auto mb-auto">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.kebrujuklanjutan" true-value="DIANTARA"
                                                        label="Diantar" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12"
                                    v-if="input.kebrujukan == 'TIDAK' && input.kebrujuklanjutan == 'DIANTAR'">
                                    <div class="columns is-multiline">
                                        <div class="column is-2 mt-auto mb-auto pl-0">
                                            <h3 style="font-weight: bold;">
                                                Diantar Oleh
                                            </h3>
                                        </div>
                                        <div class="column is-10">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" class="heightinput input"
                                                        placeholder="Diantar Oleh"
                                                        v-model.number="input.kebketrujukan" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <br>
                <hr><br>

                <div class="column is-12" style="margin-top: -20px;">
                    <div class="column is-12 pl-0 pr-0">
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-6" fullwidth>
                                    <h1 class="mb-3 emr">ALLOANAMNESIS</h1>
                                </div>
                                <div class="column is-6">
                                    <VField horizontal>
                                        <VControl fullwidth>
                                            <VSelect v-model="input.kebpilihanallo" class="is-rounded">
                                                <VOption v-for="(value, k) in dropdownAllo" :value="value">
                                                    {{ value }}
                                                </VOption>
                                            </VSelect>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VField v-if="input.kebpilihanallo == 'Lainnya'">
                                        <VControl>
                                            <VTextarea v-model="input.keballoanamnesis"
                                                placeholder="Ketik Alloanamnesis Lainnya" rows="3">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr><br>

                <div class="column is-12" style="margin-top: -20px;">
                    <div class="column is-12 pl-0 pr-0">
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12 mt-auto">
                                    <h1 class="mb-5 emr mt-auto">ANAMNESIS</h1>
                                    <div class="columns is-multiline">
                                        <div class="column is-6">
                                            <h1 class="mb-3 emr">Keluhan Utama</h1>
                                            <VField>
                                                <VControl>
                                                    <VTextarea v-model="input.keluhanutama" rows="3">
                                                    </VTextarea>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <h1 class="mb-3 emr">Riwayat penyakit sekarang</h1>
                                            <VField>
                                                <VControl>
                                                    <VTextarea v-model="input.riwayatpenyakit" rows="3">
                                                    </VTextarea>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <h1 class="mb-3 emr">Riwayat penyakit terdahulu</h1>
                                            <VField>
                                                <VControl>
                                                    <VTextarea v-model="input.riwayatpenyakitdahulu" rows="3">
                                                    </VTextarea>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <h1 class="mb-3 emr">Riwayat pengobatan</h1>
                                            <VField>
                                                <VControl>
                                                    <VTextarea v-model="input.riwayatpengobatan" rows="3">
                                                    </VTextarea>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12">
                                            <h1 class="mb-3 emr">Riwayat penyakit keluarga</h1>
                                            <VField>
                                                <VControl>
                                                    <VTextarea v-model="input.riwayatpenyakitkeluarga" rows="3">
                                                    </VTextarea>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <!-- <div class="column is-12">
                                            <h1 class="mb-3 emr">Riwayat alergi</h1>
                                            <VField>
                                                <VControl>
                                                    <VTextarea v-model="input.riwayatalergi" rows="3">
                                                    </VTextarea>
                                                </VControl>
                                            </VField>
                                        </div> -->
                                        <div class="column is-12">
                                            <h1 class="mb-3 emr">Riwayat alergi</h1>
                                            <VField vertical>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.isalergi"
                                                        true-value="YA" label="Ya" color="primary" circle />
                                                </VControl>
                                            </VField>
                                            <VField vertical>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.isalergi"
                                                        true-value="TIDAK" label="Tidak" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12" v-if="input.isalergi == 'YA'">
                                            <h1 class="mb-3 emr">Jenis alergi</h1>
                                            <VField>
                                                <VControl>
                                                    <VTextarea v-model="input.riwayatalergi" placeholder="Jelaskan..."
                                                        rows="3">
                                                    </VTextarea>
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr><br>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <h1 class="mb-3 emr">STATUS FISIK:</h1>
                                </div>
                                <div class="column is-6">

                                    <div class="columns is-multiline">
                                        <div class="column is-2 mt-auto mb-auto">
                                            <h3 style="font-weight: bold;">
                                                Keadaan Umum
                                            </h3>
                                        </div>
                                        <div class="column is-3 mt-auto mb-auto">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.keadaanumumobgyn" true-value="Baik" label="Baik"
                                                        color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-4 mt-auto mb-auto">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.keadaanumumobgyn" true-value="Sedang"
                                                        label="Sedang" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-3 mt-auto mb-auto">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.keadaanumumobgyn" true-value="Buruk"
                                                        label="Buruk" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                    <!-- <VField class="is-autocomplete-select">
                                        <VControl icon="feather:search">
                                            <AutoComplete v-model="input.keadaanumumobgyn" :suggestions="d_allo"
                                                :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                placeholder="--Pilih--" />
                                        </VControl>
                                    </VField> -->
                                </div>
                                <div class="column is-6">
                                    <div class="columns is-multiline">
                                        <div class="column is-2 mt-auto mb-auto pl-0">
                                            <h1 style="font-weight: bold;">GCS
                                            </h1>
                                        </div>
                                        <div class="column is-3">
                                            <VField addons>
                                                <VControl class="field-addon-body">
                                                    <VButton static>E</VButton>
                                                </VControl>
                                                <VControl expanded>
                                                    <VInput type="text" class="input" placeholder="E"
                                                        v-model.number="input.gcse" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-3">
                                            <VField addons>
                                                <VControl class="field-addon-body">
                                                    <VButton static>V</VButton>
                                                </VControl>
                                                <VControl expanded>
                                                    <VInput type="text" class="input" placeholder="V"
                                                        v-model.number="input.gcsv" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-3">
                                            <VField addons>
                                                <VControl class="field-addon-body">
                                                    <VButton static>M</VButton>
                                                </VControl>
                                                <VControl expanded>
                                                    <VInput type="text" class="input" placeholder="M"
                                                        v-model.number="input.gcsm" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-4">
                                            <h1 style="font-weight: bold;">Tekanan Darah</h1>
                                            <VField addons>
                                                <VControl expanded>
                                                    <VInput type="text" class="input" placeholder="Tekanan Darah"
                                                        v-model="input.tekananDarahObgyn" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>mmHg</VButton>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-4">
                                            <h1 style="font-weight: bold;">Nadi</h1>
                                            <VField addons>
                                                <VControl expanded>
                                                    <VInput type="text" class="input" placeholder=""
                                                        v-model="input.nadiObgyn" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>x/menit</VButton>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-4">
                                            <h1 style="font-weight: bold;">Respirasi</h1>
                                            <VField addons>
                                                <VControl expanded>
                                                    <VInput type="text" class="input" placeholder=""
                                                        v-model="input.nafasObgyn" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>x/menit</VButton>
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-6">
                                            <h1 style="font-weight: bold;">Suhu</h1>
                                            <VField addons>
                                                <VControl expanded>
                                                    <VInput type="text" class="input" placeholder=""
                                                        v-model="input.celciusObgyn" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>°C </VButton>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <h1 style="font-weight: bold;">SaO2</h1>
                                            <VField addons>
                                                <VControl expanded>
                                                    <VInput type="text" class="input" placeholder=""
                                                        v-model="input.sao2Obgyn" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>%</VButton>
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-6">
                                            <h1 style="font-weight: bold;">Berat Badan</h1>
                                            <VField addons>
                                                <VControl expanded>
                                                    <VInput type="text" class="input" placeholder="Berat Badan"
                                                        v-model="input.beratbadanObgyn" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>kg</VButton>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <h1 style="font-weight: bold;">Tinggi Badan</h1>
                                            <VField addons>
                                                <VControl expanded>
                                                    <VInput type="text" class="input" placeholder="Tinggi Badan"
                                                        v-model="input.tinggibadanObgyn" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>cm</VButton>
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <br>
                <hr><br>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <h1 class="mb-3 emr">ASSESMEN NYERI:</h1>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                    <h1 class="mb-3 emr">Skala nyeri</h1>
                                    <VField addons>
                                        <VControl expanded>
                                            <VInput type="text" class="heightinput input" placeholder=""
                                                v-model="input.skalanyeri" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <h1 class="mb-3 emr">Lokasi</h1>
                                    <VField addons>
                                        <VControl expanded>
                                            <VInput type="text" class="heightinput input" placeholder=""
                                                v-model="input.lokasi" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <h1 class="mb-3 emr">Lama Nyeri</h1>
                                    <VField addons>
                                        <VControl expanded>
                                            <VInput type="text" class="heightinput input" placeholder=""
                                                v-model="input.lamanyeri" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-6">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <h1 class="emr">Frekuensi Nyeri</h1>
                                </div>
                                <div class="column is-12">
                                    <VField horizontal>
                                        <VControl>
                                            <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.frekuensinyeri"
                                                true-value="JARANG" label="Jarang" color="primary" circle />
                                        </VControl>
                                    </VField>
                                    <VField horizontal>
                                        <VControl>
                                            <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.frekuensinyeri"
                                                true-value="HILANG TIMBUL" label="Hilang Timbul" color="primary"
                                                circle />
                                        </VControl>
                                    </VField>
                                    <VField horizontal>
                                        <VControl>
                                            <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.frekuensinyeri"
                                                true-value="TERUS MENERUS" label="Terus Menerus" color="primary"
                                                circle />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-6">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <h1 class="emr">Kualitas Nyeri</h1>
                                </div>
                            </div>
                            <div class="column is-12">
                                <VField vertical>
                                    <VControl>
                                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kualitasnyeri"
                                            true-value="TUMPUL" label="Tumpul" color="primary" circle />
                                    </VControl>
                                </VField>
                                <VField vertical>
                                    <VControl>
                                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kualitasnyeri"
                                            true-value="TAJAM" label="Tajam" color="primary" circle />
                                    </VControl>
                                </VField>
                                <VField vertical>
                                    <VControl>
                                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kualitasnyeri"
                                            true-value="PANAS/TERBAKAR" label="Panas/Terbakar" color="primary" circle />
                                    </VControl>
                                </VField>
                                <VField vertical>
                                    <VControl>
                                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kualitasnyeri"
                                            true-value="LAINNYA" label="Lain-lain" color="primary" circle />
                                        <VInput type="text" class="input" placeholder="Ketik Lainnya"
                                            v-model="input.kualitasnyerilain" v-if="input.kualitasnyeri == 'LAINNYA'" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                        <div class="column is-6">
                            <h1 class="mb-3 emr">Faktor yang memperberat</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="text" class="heightinput input" placeholder=""
                                        v-model="input.memperberat" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <h1 class="mb-3 emr">Faktor yang meringankan nyeri</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="text" class="heightinput input" placeholder=""
                                        v-model="input.meringankan" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>

                <br>
                <hr><br>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <h1 class="mb-3 emr">KONDISI PSIKOLOGI, SOSIAL, EKONOMI DAN SPIRITUAL</h1>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <h1 class="mb-12 emr">Gangguan Psikologis</h1>
                            <VField vertical class="mt-4">
                                <VControl raw subcontrol>
                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.gangguanpsikologis"
                                        true-value="TIDAK ADA" label="Tidak ada" color="primary" circle />
                                </VControl>
                                <VControl raw subcontrol>
                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.gangguanpsikologis"
                                        true-value="GELISAH" label="Gelisah" color="primary" circle />
                                </VControl>
                                <VControl raw subcontrol>
                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.gangguanpsikologis"
                                        true-value="TAKUT" label="Takut" color="primary" circle />
                                </VControl>
                                <VControl raw subcontrol>
                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.gangguanpsikologis"
                                        true-value="SEDIH" label="Sedih" color="primary" circle />
                                </VControl>
                                <VControl raw subcontrol>
                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.gangguanpsikologis"
                                        true-value="RENDAH DIRI" label="Rendah diri" color="primary" circle />
                                </VControl>
                                <VControl raw subcontrol>
                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.gangguanpsikologis"
                                        true-value="ACUH" label="Acuh tak acuh" color="primary" circle />
                                </VControl>
                                <VControl raw subcontrol>
                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.gangguanpsikologis"
                                        true-value="MUDAH TERSINGGUNG" label="Mudah tersinggung" color="primary"
                                        circle />
                                </VControl>
                                <VControl raw subcontrol>
                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.gangguanpsikologis"
                                        true-value="MENARIK DIRI" label="Menarik diri" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                        <!-- <div class="column is-2">
                            <h1 class="mb-12 emr">Status Pernikahan</h1>
                            <VField class="is-autocomplete-select">
                                <VControl icon="feather:search">
                                    <AutoComplete v-model="input.statuspernikahan" :suggestions="d_allo"
                                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="--Pilih--" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <h1 style="font-weight: bold;">Menikah</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="text" class="input" placeholder="" v-model="input.menikah" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>kali</VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <h1 style="font-weight: bold;">Umur pertama kali menikah</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="text" class="input" placeholder="" v-model="input.pertamamenikah" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>Tahun</VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <h1 style="font-weight: bold;">Kawin dengan suami 1</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="text" class="input" placeholder="" v-model="input.suami1" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>Tahun</VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <h1 style="font-weight: bold;">ke 2,3</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="text" class="input" placeholder="" v-model="input.suami2" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>Tahun</VButton>
                                </VControl>
                            </VField>
                        </div> -->
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <h1 class="mb-12 emr">Masalah perkawinan</h1>
                                </div>
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-4">
                                            <VField horizontal>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.masalahperkawinan" true-value="TIDAK ADA"
                                                        label="Tidak ada" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-4">
                                            <VField horizontal>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.masalahperkawinan" true-value="ADA" label="Ada"
                                                        color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-4" v-if="input.masalahperkawinan == 'ADA'">
                                            <VField horizontal label="Jelaskan">
                                                <VControl>
                                                    <VInput type="text" class="heightinput input" placeholder=""
                                                        v-model="input.ketmasalahperkawinan" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <h1 class="mb-12 emr">Mengalami Kekerasan Fisik</h1>
                                </div>
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-4">
                                            <VField horizontal>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.kekerasanfisik" true-value="TIDAK ADA"
                                                        label="Tidak ada" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-4">
                                            <VField horizontal>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.kekerasanfisik" true-value="ADA" label="Ada"
                                                        color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-4" v-if="input.kekerasanfisik == 'ADA'">
                                            <VField horizontal label="Jelaskan">
                                                <VControl>
                                                    <VInput type="text" class="heightinput input" placeholder=""
                                                        v-model="input.ketkekerasanfisik" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <h1 class="mb-3 emr">Keyakinan dan nilai pribadi</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="text" class="heightinput input" placeholder=""
                                        v-model="input.keyakinanpribadi" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <h1 class="mb-12 emr">Pembiayaan kesehatan</h1>
                                </div>
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-6">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.pembiayaankesehatan"
                                                        true-value="BIAYA SENDIRI/KELUARGA"
                                                        label="Biaya Sendiri/Keluarga" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField horizontal>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.pembiayaankesehatan" true-value="ASURANSI"
                                                        label="Asuransi lainnya" color="primary" circle />
                                                </VControl>
                                                <VControl class="mt-auto mb-auto"
                                                    v-if="input.pembiayaankesehatan == 'ASURANSI'">
                                                    <VInput type="text" class="heightinput input"
                                                        placeholder="ketik asuransi lainnya"
                                                        v-model="input.ketpembiayaankesehatan" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <!-- <div class="column is-4" v-if="input.kekerasanfisik == 'ADA'">
                                            <VField horizontal label="Jelaskan">
                                                <VControl>
                                                    <VInput type="text" class="heightinput input" placeholder=""
                                                        v-model="input.ketkekerasanfisik" />
                                                </VControl>
                                            </VField>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <h1 class="mb-3 emr">Kebiasaan adat istiadat yang memengaruhi kesehatan</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="text" class="heightinput input" placeholder=""
                                        v-model="input.adatistiadat" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <h1 class="mb-12 emr">Perlu rohaniawan</h1>
                                </div>
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-4">
                                            <VField horizontal>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.rohaniawan"
                                                        true-value="YA" label="Ya" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-4">
                                            <VField horizontal>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.rohaniawan"
                                                        true-value="TIDAK" label="Tidak" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="column is-3">
                            <h1 class="mb-12 emr">Mengalami kekerasan fisik</h1>
                            <VField class="is-autocomplete-select">
                                <VControl icon="feather:search">
                                    <AutoComplete v-model="input.kekerasanfisik" :suggestions="d_allo"
                                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="--Pilih--" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <h1 class="mb-3 emr">Jelaskan</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="text" class="heightinput input" placeholder=""
                                        v-model="input.ketkekerasanfisik" />
                                </VControl>
                            </VField>
                        </div> -->
                        <!-- <div class="column is-3">
                            <h1 class="mb-3 emr">Keyakinan dan nilai pribadi</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="text" class="heightinput input" placeholder=""
                                        v-model="input.keyakinanpribadi" />
                                </VControl>
                            </VField>
                        </div> -->
                        <!-- <div class="column is-3">
                            <h1 class="mb-12 emr">Pembiayaan kesehatan</h1>
                            <VField class="is-autocomplete-select">
                                <VControl icon="feather:search">
                                    <AutoComplete v-model="input.pembiayaankesehatan" :suggestions="d_allo"
                                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="--Pilih--" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <h1 class="mb-3 emr">Kebiasaan adat istiadat yang memengaruhi kesehatan</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="text" class="heightinput input" placeholder=""
                                        v-model="input.adatistiadat" />
                                </VControl>
                            </VField>
                        </div> -->
                        <!-- <div class="column is-3">
                            <h1 class="mb-12 emr">Dukungan sosial dari</h1>
                            <VField class="is-autocomplete-select">
                                <VControl icon="feather:search">
                                    <AutoComplete v-model="input.dukungansosial" :suggestions="d_allo"
                                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="--Pilih--" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <h1 class="mb-12 emr">Kebiasaan ibu</h1>
                            <VField class="is-autocomplete-select">
                                <VControl icon="feather:search">
                                    <AutoComplete v-model="input.kebiasaanibu" :suggestions="d_allo"
                                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="--Pilih--" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <h1 class="mb-12 emr">Perlu rohaniawan</h1>
                            <VField class="is-autocomplete-select">
                                <VControl icon="feather:search">
                                    <AutoComplete v-model="input.rohaniawan" :suggestions="d_allo"
                                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="--Pilih--" />
                                </VControl>
                            </VField>
                        </div> -->
                    </div>
                </div>

                <br>
                <hr><br>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <h1 class="mb-3 emr">SKRINNING NUTRISI</h1>
                                </div>
                                <div class="column is-12">
                                    <table class="table is-borderless is-fullwidth">
                                        <thead>
                                            <tr>
                                                <th scope="col" colspan="3">
                                                    <span class="is-pulled-right mr-3">
                                                        Skor
                                                    </span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="width: 5%">1.</td>
                                                <td style="width: 85%">
                                                    <p>
                                                        Apakah pasien mengalami penurunan berat badan yang tidak
                                                        direncanakan
                                                        selama 6 bulan terakhir?
                                                    </p>
                                                </td>
                                                <td style="width: 10%; text-align: center;"></td>
                                            </tr>
                                            <tr>
                                                <td style="width: 5%"></td>
                                                <td style="width: 85%">
                                                    <VField>
                                                        <VControl>
                                                            <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                                v-model="input.penurunanbb" true-value="TIDAK"
                                                                label="Tidak" color="primary" circle />
                                                        </VControl>
                                                    </VField>
                                                </td>
                                                <td style="width: 10%; text-align: center;">0</td>
                                            </tr>
                                            <tr>
                                                <td style="width: 5%"></td>
                                                <td style="width: 85%">
                                                    <VField>
                                                        <VControl>
                                                            <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                                v-model="input.penurunanbb" true-value="TIDAK YAKIN"
                                                                label="Tidak yakin (ada tanda: baju menjadi lebih longgar)"
                                                                color="primary" circle />
                                                        </VControl>
                                                    </VField>
                                                </td>
                                                <td style="width: 10%; text-align: center;">2</td>
                                            </tr>
                                            <tr>
                                                <td style="width: 5%"></td>
                                                <td style="width: 85%">
                                                    <VField>
                                                        <VControl>
                                                            <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                                v-model="input.penurunanbb" true-value="YA"
                                                                label="Ya, bila ya berapa penurunan berat badan"
                                                                color="primary" circle />
                                                        </VControl>
                                                    </VField>
                                                </td>
                                                <td style="width: 10%; text-align: center;"></td>
                                            </tr>
                                            <tr>
                                                <td style="width: 5%"></td>
                                                <td style="width: 85%">
                                                    <VField>
                                                        <VControl>
                                                            <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                                v-model="input.penurunanbb" true-value="1-5 KG"
                                                                label="1-5 kg" color="primary" circle />
                                                        </VControl>
                                                    </VField>
                                                </td>
                                                <td style="width: 10%; text-align: center;">1</td>
                                            </tr>
                                            <tr>
                                                <td style="width: 5%"></td>
                                                <td style="width: 85%">
                                                    <VField>
                                                        <VControl>
                                                            <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                                v-model="input.penurunanbb" true-value="6-10 KG"
                                                                label="6-10 kg" color="primary" circle />
                                                        </VControl>
                                                    </VField>
                                                </td>
                                                <td style="width: 10%; text-align: center;">2</td>
                                            </tr>
                                            <tr>
                                                <td style="width: 5%"></td>
                                                <td style="width: 85%">
                                                    <VField>
                                                        <VControl>
                                                            <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                                v-model="input.penurunanbb" true-value="11-15 KG"
                                                                label="11-15 kg" color="primary" circle />
                                                        </VControl>
                                                    </VField>
                                                </td>
                                                <td style="width: 10%; text-align: center;">3</td>
                                            </tr>
                                            <tr>
                                                <td style="width: 5%"></td>
                                                <td style="width: 85%">
                                                    <VField>
                                                        <VControl>
                                                            <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                                v-model="input.penurunanbb"
                                                                true-value="LEBIH DARI 15 KG" label="> 15 kg"
                                                                color="primary" circle />
                                                        </VControl>
                                                    </VField>
                                                </td>
                                                <td style="width: 10%; text-align: center;">4</td>
                                            </tr>
                                            <tr>
                                                <td style="width: 5%">2.</td>
                                                <td style="width: 85%">
                                                    <p>
                                                        Apakah terjadi penurunan nafsu makan?
                                                    </p>
                                                </td>
                                                <td style="width: 10%; text-align: center;"></td>
                                            </tr>
                                            <tr>
                                                <td style="width: 5%"></td>
                                                <td style="width: 85%">
                                                    <VField>
                                                        <VControl>
                                                            <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                                v-model="input.penurunannafsu" true-value="YA"
                                                                label="Ya" color="primary" circle />
                                                        </VControl>
                                                    </VField>
                                                </td>
                                                <td style="width: 10%; text-align: center;">1</td>
                                            </tr>
                                            <tr>
                                                <td style="width: 5%"></td>
                                                <td style="width: 85%">
                                                    <VField>
                                                        <VControl>
                                                            <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                                v-model="input.penurunannafsu" true-value="TIDAK"
                                                                label="Tidak" color="primary" circle />
                                                        </VControl>
                                                    </VField>
                                                </td>
                                                <td style="width: 10%; text-align: center;">0</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4">
                                                    <VField horizontal label="Total Skor" class="is-pulled-right">
                                                        <VControl expanded>
                                                            <VInput type="text" class="heightinput input" placeholder=""
                                                                v-model="input.nilai" />
                                                        </VControl>
                                                    </VField>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>

                                </div>
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-12">
                                            <h1 class="mb-12 emr">Pasien dengan diagnosa khusus?</h1>
                                        </div>
                                        <div class="column is-12">
                                            <div class="columns is-multiline">
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl>
                                                            <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                                v-model="input.diagnosakhusus" true-value="YA"
                                                                label="Ya" color="primary" circle />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl>
                                                            <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                                v-model="input.diagnosakhusus" true-value="TIDAK"
                                                                label="Tidak" color="primary" circle />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-12">
                                            <h1 class="mb-12 emr">Nilai</h1>
                                        </div>
                                        <div class="column is-12">
                                            <div class="columns is-multiline">
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl>
                                                            <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                                v-model="input.nilai"
                                                                true-value="RISIKO RENDAH (MST 0-1)"
                                                                label="Risiko rendah (MST 0-1)" color="primary"
                                                                circle />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl>
                                                            <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                                v-model="input.nilai"
                                                                true-value="RISIKO SEDANG (MST 2-3)"
                                                                label="Risiko sedang (MST 2-3)" color="primary"
                                                                circle />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl>
                                                            <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                                v-model="input.nilai"
                                                                true-value="RISIKO TINGGI (MST 4-5)"
                                                                label="Risiko tinggi (MST 4-5)" color="primary"
                                                                circle />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <br>
                <hr><br>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <h1 class="mb-3 emr">STATUS FUNGSIONAL</h1>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                    <div class="columns is-multiline">
                                        <div class="column is-12">
                                            <h1 class="emr">Mengontrol BAB</h1>
                                        </div>
                                        <div class="column is-12">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.kualitasnyeri" true-value="INKONTINEN"
                                                        label="Inkontinen/ tidak teratur (perlu enema)" color="primary"
                                                        circle />
                                                </VControl>
                                            </VField>
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.kualitasnyeri" true-value="KADANG INKONTINEN"
                                                        label="Kadang inkontinen (1x seminggu)" color="primary"
                                                        circle />
                                                </VControl>
                                            </VField>
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.kualitasnyeri" true-value="KONTINEN TERATUR"
                                                        label="Kontinen teratur" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-4">
                                    <div class="columns is-multiline">
                                        <div class="column is-12">
                                            <h1 class="emr">Mengontrol BAK</h1>
                                        </div>
                                        <div class="column is-12">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.mengontrolbak"
                                                        true-value="INKONTINEN DAN TIDAK TERKONTROL"
                                                        label="Inkontinen dan Tidak Terkontrol" color="primary"
                                                        circle />
                                                </VControl>
                                            </VField>
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.mengontrolbak" true-value="KADANG INKONTINEN"
                                                        label="Kadang Inkontinen (max 1x24jam)" color="primary"
                                                        circle />
                                                </VControl>
                                            </VField>
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.mengontrolbak" true-value="MANDIRI"
                                                        label="Mandiri" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-4">
                                    <div class="columns is-multiline">
                                        <div class="column is-12">
                                            <h1 class="emr">Membersihkan Diri</h1>
                                        </div>
                                        <div class="column is-12">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.bersihdiri"
                                                        true-value="BUTUH PERTOLONGAN ORANG LAIN"
                                                        label="Butuh pertolongan orang lain" color="primary" circle />
                                                </VControl>
                                            </VField>
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.bersihdiri"
                                                        true-value="MANDIRI" label="Mandiri" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                    <div class="columns is-multiline">
                                        <div class="column is-12">
                                            <h1 class="emr">Penggunaan Toilet</h1>
                                        </div>
                                        <div class="column is-12">
                                            <VField vertical>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.toilet"
                                                        true-value="TERGANTUNG PERTOLONGAN ORANG LAIN"
                                                        label="Tergantung pertolongan orang lain" color="primary"
                                                        circle />
                                                </VControl>
                                            </VField>
                                            <VField vertical>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.toilet"
                                                        true-value="PERLU PERTOLONGAN PADA BEBERAPA AKTIFITAS"
                                                        label="Perlu pertolongan pada beberapa aktivitas"
                                                        color="primary" circle />
                                                </VControl>
                                            </VField>
                                            <VField vertical>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.toilet"
                                                        true-value="MANDIRI" label="Mandiri" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-4">
                                    <div class="columns is-multiline">
                                        <div class="column is-12">
                                            <h1 class="emr">Makan</h1>
                                        </div>
                                        <div class="column is-12">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.makan"
                                                        true-value="TIDAK MAMPU" label="Tidak Mampu" color="primary"
                                                        circle />
                                                </VControl>
                                            </VField>
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.makan"
                                                        true-value="PERLU SESEORANG MENOLONG MEMOTONG MAKANAN"
                                                        label="Perlu seseorang menolong memotong makanan"
                                                        color="primary" circle />
                                                </VControl>
                                            </VField>
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.makan"
                                                        true-value="MANDIRI" label="Mandiri" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-4">
                                    <div class="columns is-multiline">
                                        <div class="column is-12">
                                            <h1 class="emr">Berpindah dari tempat tidur</h1>
                                        </div>
                                        <div class="column is-12">
                                            <VField vertical>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.berpindahtt" true-value="TIDAK MAMPU"
                                                        label="Tidak mampu" color="primary" circle />
                                                </VControl>
                                            </VField>
                                            <VField vertical>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.berpindahtt"
                                                        true-value="PERLU BANYAK BANTUAN UNTUK DUDUK"
                                                        label="Perlu banyak bantuan untuk duduk (2 orang)"
                                                        color="primary" circle />
                                                </VControl>
                                            </VField>
                                            <VField vertical>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.berpindahtt" true-value="BANTUAN 1 ORANG"
                                                        label="Bantuan 1 orang" color="primary" circle />
                                                </VControl>
                                            </VField>
                                            <VField vertical>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.berpindahtt" true-value="MANDIRI" label="Mandiri"
                                                        color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                    <div class="columns is-multiline">
                                        <div class="column is-12">
                                            <h1 class="emr">Mobilisasi / Berjalan</h1>
                                        </div>
                                        <div class="column is-12">
                                            <VField vertical>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.mobilisasi"
                                                        true-value="TIDAK MAMPU" label="Tidak mampu" color="primary"
                                                        circle />
                                                </VControl>
                                            </VField>
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.mobilisasi"
                                                        true-value="DENGAN KURSI RODA" label="Dengan kursi roda"
                                                        color="primary" circle />
                                                </VControl>
                                            </VField>
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.mobilisasi"
                                                        true-value="BANTUAN 1 ORANG" label="Bantuan 1 Orang"
                                                        color="primary" circle />
                                                </VControl>
                                            </VField>
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.mobilisasi"
                                                        true-value="MANDIRI" label="Mandiri" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-4">
                                    <div class="columns is-multiline">
                                        <div class="column is-12">
                                            <h1 class="emr">Berpakaian</h1>
                                        </div>
                                        <div class="column is-12">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.berpakaian"
                                                        true-value="TERGANTUNG ORANG LAIN" label="Tergantung orang lain"
                                                        color="primary" circle />
                                                </VControl>
                                            </VField>
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.berpakaian"
                                                        true-value="SEBAGIAN DIBANTU" label="Sebagian dibantu"
                                                        color="primary" circle />
                                                </VControl>
                                            </VField>
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.berpakaian"
                                                        true-value="MANDIRI" label="Mandiri" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-4">
                                    <div class="columns is-multiline">
                                        <div class="column is-12">
                                            <h1 class="emr">Naik turun tangga</h1>
                                        </div>
                                        <div class="column is-12">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.tangga"
                                                        true-value="TIDAK MAMPU" label="Tidak mampu" color="primary"
                                                        circle />
                                                </VControl>
                                            </VField>
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.tangga"
                                                        true-value="BUTUH PERTOLONGAN" label="Butuh pertolongan"
                                                        color="primary" circle />
                                                </VControl>
                                            </VField>
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.tangga"
                                                        true-value="MANDIRI" label="Mandiri" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                    <div class="columns is-multiline">
                                        <div class="column is-12">
                                            <h1 class="emr">Mandi</h1>
                                        </div>
                                        <div class="column is-12">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.mandi"
                                                        true-value="TERGANTUNG ORANG LAIN" label="Tergantung orang lain"
                                                        color="primary" circle />
                                                </VControl>
                                            </VField>
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.mandi"
                                                        true-value="MANDIRI" label="Mandiri" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-4">
                                    <div class="columns is-multiline">
                                        <div class="column is-12">
                                            <h1 class="emr">Nilai</h1>
                                        </div>
                                        <div class="column is-12">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" class="input" placeholder=""
                                                        v-model="input.nilaimandi" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <br>
                <hr><br>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <h1 class="mb-3 emr">ASESMEN RISIKO JATUH</h1>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-12">
                                            <div class="column is-12">
                                                <h1 class="mb-12 emr">Perhatikan cara duduk pasien saat akan duduk di
                                                    kursi. Apakah
                                                    pasien tampak
                                                    tidak seimbang (sempoyongan/limbung)?</h1>
                                            </div>
                                        </div>
                                        <div class="column is-12">
                                            <VField vertical>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.caraduduk"
                                                        true-value="YA" label="Ya" color="primary" circle />
                                                </VControl>
                                            </VField>
                                            <VField vertical>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.caraduduk"
                                                        true-value="TIDAK" label="Tidak" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-12">
                                            <div class="column is-12">
                                                <h1 class="mb-12 emr">Apakah pasien memegang pinggiran kursi atau meja
                                                    atau benda
                                                    lain sebagai
                                                    penopang saat akan duduk?</h1>
                                            </div>
                                        </div>
                                        <div class="column is-12">
                                            <VField vertical>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kursi"
                                                        true-value="YA" label="Ya" color="primary" circle />
                                                </VControl>
                                            </VField>
                                            <VField vertical>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kursi"
                                                        true-value="TIDAK" label="Tidak" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-12">
                                            <div class="column is-12">
                                                <h1 class="mb-12 emr">
                                                    Hasil
                                                </h1>
                                            </div>
                                        </div>
                                        <div class="column is-12">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.hasiljatuh"
                                                        true-value="TIDAK BERISIKO"
                                                        label="Tidak Berisiko (tidak ditemukan a dan b)" color="primary"
                                                        circle />
                                                </VControl>
                                            </VField>
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.hasiljatuh"
                                                        true-value="RISIKO RENDAH"
                                                        label="Risiko Rendah (a atau b ditemukan)" color="primary"
                                                        circle />
                                                </VControl>
                                            </VField>
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.hasiljatuh"
                                                        true-value="RISIKO TINGGI"
                                                        label="Risiko Tinggi (a dan b ditemukan)" color="primary"
                                                        circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-12">
                                            <div class="column is-12">
                                                <h1 class="mb-12 emr">
                                                    Tindakan
                                                </h1>
                                            </div>
                                        </div>
                                        <div class="column is-12">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.hasiljatuh"
                                                        true-value="TIDAK ADA TINDAKAN" label="Tidak ada tindakan"
                                                        color="primary" circle />
                                                </VControl>
                                            </VField>
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.hasiljatuh"
                                                        true-value="EDUKASI" label="Edukasi" color="primary" circle />
                                                </VControl>
                                            </VField>
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.hasiljatuh"
                                                        true-value="PASANG PENANDA RISIKO JATUH"
                                                        label="Pasang penanda risiko jatuh" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <br>
                <hr><br>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <h1 class="mb-3 emr">RIWAYAT PENGGUNAAN OBAT</h1>
                                    <VField>
                                        <VControl>
                                            <VTextarea v-model="input.riwayatobat" rows="3">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <br>
                <hr><br>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <h1 class="mb-3 emr">DIAGNOSA KEPERAWATAN</h1>
                                    <VCheckbox class="fontcheckbox" v-model="input.nyeriakut"
                                        true-value="Nyeri akut b/d kondisi fisik" label="Nyeri akut b/d kondisi fisik"
                                        color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.bersihan"
                                        true-value="Bersihan jalan nafas tidak efektif b/d alergi jalan nafas, adanya eksudat di jalan nafas / sekresi tertahan"
                                        label="Bersihan jalan nafas tidak efektif b/d alergi jalan nafas, adanya eksudat di jalan nafas / sekresi tertahan"
                                        color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.risikojantung"
                                        true-value="Risiko /Penurunan curah jantung b/d anomaly jantung / peningkatan beban kerja ventrikel"
                                        label="Risiko /Penurunan curah jantung b/d anomaly jantung / peningkatan beban kerja ventrikel"
                                        color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.risikokekurangan"
                                        true-value="Risiko / Kekurangan Volume Cairan b/d kehilangan volume cairan secara aktif"
                                        label="Risiko / Kekurangan Volume Cairan b/d kehilangan volume cairan secara aktif"
                                        color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.kurangpengetahuan"
                                        true-value="Kurang pengetahuan tentang penyakit, rencana tindakan dan pengobatan b/d kurang terpajannya informasi"
                                        label="Kurang pengetahuan tentang penyakit, rencana tindakan dan pengobatan b/d kurang terpajannya informasi"
                                        color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.ansietas"
                                        true-value="Ansietas b/d krisis situasi, kebutuhan yang tidak terpenuhi"
                                        label="Ansietas b/d krisis situasi, kebutuhan yang tidak terpenuhi"
                                        color="primary" circle />
                                    <br>
                                    <VCheckbox class="fontcheckbox" v-model="input.integritas"
                                        true-value="Risiko gangguan integritas kulit"
                                        label="Risiko gangguan integritas kulit" color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.kelebihanvolume"
                                        true-value="Kelebihan volume cairan b/d asupan cairan berlebihan"
                                        label="Kelebihan volume cairan b/d asupan cairan berlebihan" color="primary"
                                        circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.kesiapan"
                                        true-value="Kesiapan meningkatkan status kesehatan"
                                        label="Kesiapan meningkatkan status kesehatan" color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.ketidakefektifan"
                                        true-value="Ketidakefektifan pemeliharaan kesehatan b/d hambatan kognitif"
                                        label="Ketidakefektifan pemeliharaan kesehatan b/d hambatan kognitif"
                                        color="primary" circle />
                                    <br>
                                    <VCheckbox class="fontcheckbox" v-model="input.hambatan"
                                        true-value="Hambatan mobilitas fisik b/d intoleran aktivitas"
                                        label="Hambatan mobilitas fisik b/d intoleran aktivitas" color="primary"
                                        circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.diareakut"
                                        true-value="Diare akut b/d mal absorbsi, peningkatan motilitas usus"
                                        label="Diare akut b/d mal absorbsi, peningkatan motilitas usus" color="primary"
                                        circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.nausea"
                                        true-value="Nausea b/d biofisik, psikologis, pemberian kemotherapi, pemberian steroid"
                                        label="Nausea b/d biofisik, psikologis, pemberian kemotherapi, pemberian steroid"
                                        color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.kadarglukosa"
                                        true-value="Risiko Ketidakstabilan kadar Glukosa Darah b/d Kurang pengetahuan tentang menejemen diabetes, Asupan diet, Pemantauan glukosa darah tidak adekuat"
                                        label="Risiko Ketidakstabilan kadar Glukosa Darah b/d Kurang pengetahuan tentang menejemen diabetes, Asupan diet, Pemantauan glukosa darah tidak adekuat"
                                        color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.hipertermia"
                                        true-value="Hipertermia b/d kekurangan cairan, proses infeksi, gangguan termoregulasi"
                                        label="Hipertermia b/d kekurangan cairan, proses infeksi, gangguan termoregulasi"
                                        color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.fungsigigi"
                                        true-value="Gangguan fungsi gigi" label="Gangguan fungsi gigi" color="primary"
                                        circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.jaringankeras"
                                        true-value="Gangguan jaringan keras gigi" label="Gangguan jaringan keras gigi"
                                        color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.jaringanlunak"
                                        true-value="Gangguan jaringan lunak dan pendukung gigi"
                                        label="Gangguan jaringan lunak dan pendukung gigi" color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.estetika"
                                        true-value="Gangguan estetika" label="Gangguan estetika" color="primary"
                                        circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.sensori"
                                        true-value="Gangguan persepsi sensori" label="Gangguan persepsi sensori"
                                        color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.riwayatjatuh"
                                        true-value="Risiko jatuh b/d riwayat terjatuh/usia lebih dari 65th / menggunakan alat bantu (walker, tongkat, kursi roda) / sulit penglihatan"
                                        label="Risiko jatuh b/d riwayat terjatuh/usia lebih dari 65th / menggunakan alat bantu (walker, tongkat, kursi roda) / sulit penglihatan"
                                        color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.polaasi"
                                        true-value="Pola pemberian ASI tidak efektif b/d ketidakefektifan pemeliharaan kesehatan"
                                        label="Pola pemberian ASI tidak efektif b/d ketidakefektifan pemeliharaan kesehatan"
                                        color="primary" circle /><br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <br>
                <hr><br>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <h1 class="mb-3 emr">RENCANA KEPERAWATAN</h1>
                                    <VCheckbox class="fontcheckbox" v-model="input.istirahatkan"
                                        true-value="Istirahatkan pasien pada posisi yang nyaman dalam batas yang ditoleransi oleh pasien"
                                        label="Istirahatkan pasien pada posisi yang nyaman dalam batas yang ditoleransi oleh pasien"
                                        color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.berikaninfo"
                                        true-value="Berikan informasi tentang nyeri meliputi penyebab, lamanya nyeri berlangsung, faktor yang dapat memperburuk atau meredakan nyeri"
                                        label="Berikan informasi tentang nyeri meliputi penyebab, lamanya nyeri berlangsung, faktor yang dapat memperburuk atau meredakan nyeri"
                                        color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.bantupasien"
                                        true-value="Bantu pasien mengidentifikasi tindakan memenuhi kebutuhan rasa nyaman yang telah berhasil dilakukan oleh pasien"
                                        label="Bantu pasien mengidentifikasi tindakan memenuhi kebutuhan rasa nyaman yang telah berhasil dilakukan oleh pasien"
                                        color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.observasi"
                                        true-value="Observasi tanda-tanda vital" label="Observasi tanda-tanda vital"
                                        color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.ajarkan"
                                        true-value="Ajarkan teknik nonfarmakologis seperti : Relaksasi napas dalam/otot progesif, Distraksi, kompres hangat/dingin, terapi music, massage punggung"
                                        label="Ajarkan teknik nonfarmakologis seperti : Relaksasi napas dalam/otot progesif, Distraksi, kompres hangat/dingin, terapi music, massage punggung"
                                        color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.monitor"
                                        true-value="Monitor Frekuensi nafas pasien/ status oksigen pasien"
                                        label="Monitor Frekuensi nafas pasien/ status oksigen pasien" color="primary"
                                        circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.posisikan"
                                        true-value="Posisikan pasien untuk memaksimalkan ventilasi (head up/semifowler)"
                                        label="Posisikan pasien untuk memaksimalkan ventilasi (head up/semifowler)"
                                        color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.latihanbatuk"
                                        true-value="Latihan teknik batuk efektif" label="Latihan teknik batuk efektif"
                                        color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.chest"
                                        true-value="Lakukan chest fisioterapi sesuai indikasi/bila perlu"
                                        label="Lakukan chest fisioterapi sesuai indikasi/bila perlu" color="primary"
                                        circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.berikie"
                                        true-value="Beri KIE tentang tanda-tanda penurunan curah jantung"
                                        label="Beri KIE tentang tanda-tanda penurunan curah jantung" color="primary"
                                        circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.latihrentang"
                                        true-value="Latih rentang pergerakan aktif/pasif untuk memperbaiki kekuatan dan daya tahan otot"
                                        label="Latih rentang pergerakan aktif/pasif untuk memperbaiki kekuatan dan daya tahan otot"
                                        color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.edukasi"
                                        true-value="Edukasi untuk memberikan kompres dengan air biasa/ hangat"
                                        label="Edukasi untuk memberikan kompres dengan air biasa/ hangat"
                                        color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.kaji"
                                        true-value="Kaji dan dokumentasi frekuensi, warna, konsistensi, jumlah (ukuran) feces, turgor kulit dan kondisi mukosa mulut sebagai indikator dehidrasi"
                                        label="Kaji dan dokumentasi frekuensi, warna, konsistensi, jumlah (ukuran) feces, turgor kulit dan kondisi mukosa mulut sebagai indikator dehidrasi"
                                        color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.sarankan"
                                        true-value="Sarankan menghindari makanan yang mengandung lactose, makan makanan yang rendah serat, tinggi kalori dan tinggi protein"
                                        label="Sarankan menghindari makanan yang mengandung lactose, makan makanan yang rendah serat, tinggi kalori dan tinggi protein"
                                        color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.imunisasi"
                                        true-value="Lakukan manajemen imunisasi/vaksinasi"
                                        label="Lakukan manajemen imunisasi/vaksinasi" color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.dukungan"
                                        true-value="Beri dudkungan dalam mengambil keputusan"
                                        label="Beri dudkungan dalam mengambil keputusan" color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.kontrol"
                                        true-value="Edukasi dan sarankan untuk kontrol sebagai upaya meningkatkan status kesehatan pasien"
                                        label="Edukasi dan sarankan untuk kontrol sebagai upaya meningkatkan status kesehatan pasien"
                                        color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.kaji"
                                        true-value="Kaji integritas kulit" label="Kaji integritas kulit" color="primary"
                                        circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.ajarkanteknik"
                                        true-value="Ajarkan teknik nonfarmakologis seperti : Relaksasi napas dalam/ otot progresif, Distraksi, Kompres hangat/ dingin, Terapi music, Massage punggung"
                                        label="Ajarkan teknik nonfarmakologis seperti : Relaksasi napas dalam/ otot progresif, Distraksi, Kompres hangat/ dingin, Terapi music, Massage punggung"
                                        color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.identifikasi"
                                        true-value="Identifikasi level cemas pada pasien"
                                        label="Identifikasi level cemas pada pasien" color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.cemas"
                                        true-value="Berikan pengetahuan yang adekuat tentang penyakit yang diderita untuk mengurangi cemas"
                                        label="Berikan pengetahuan yang adekuat tentang penyakit yang diderita untuk mengurangi cemas"
                                        color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.prosedur"
                                        true-value="Jelaskan semua posedur, termasuk beberapa pengalaman sebelum prosedur"
                                        label="Jelaskan semua posedur, termasuk beberapa pengalaman sebelum prosedur"
                                        color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.dekatipasien"
                                        true-value="Dekati pasien untuk memberikan rasa aman dan mengurangi rasa takut"
                                        label="Dekati pasien untuk memberikan rasa aman dan mengurangi rasa takut"
                                        color="primary" circle /><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.dengarkan"
                                        true-value="Dengarkan pasien dengan penuh perhatian"
                                        label="Dengarkan pasien dengan penuh perhatian" color="primary" circle /><br>
                                </div>
                            </div>
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
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import * as EMR from '../page-emr-plugins/asesmen-awal-keper-rj'

useHead({
    title: 'Asesmen Awal - ' + import.meta.env.VITE_PROJECT,
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

const COLLECTION: any = ref('AsesmenAwalKeperawatanPasienRawatJalan') //table mongodb

const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()

let listHipertensi: any = ref(EMR.hipertensi())
let listDiabetes: any = ref(EMR.diabetes())
let listDyslipidemia: any = ref(EMR.dyslipidemia())
let listDuaPilihan: any = ref(EMR.duaPilihan())
let listAgama: any = ref(EMR.agama())
let listStatus: any = ref(EMR.status())
let listKeluarga: any = ref(EMR.keluarga())
let listTempatTinggal: any = ref(EMR.tempatTinggal())
let listPsikologis: any = ref(EMR.psikologis())
let listMore: any = ref(EMR.more())
let listImageNyeri: any = ref(EMR.imgNyeri())
let listSkoringNyeri: any = ref(EMR.skoringNyeri())
let resikoNutrisional: any = ref(EMR.resikoNutrisional())
let fungsionalPertama: any = ref(EMR.fungsionalPertama())
let listRangeNilaiPoin: any = ref(EMR.nilaiPoin())
let listDESCNilai: any = ref(EMR.descNilai())
let pertanyaanA: any = ref(EMR.pertanyaanA())
let pertanyaanB: any = ref(EMR.pertanyaanB())
let pertanyaanC: any = ref(EMR.pertanyaanC())
let dropdownAllo: any = ref([
    "Suami/Istri",
    "Orang tua",
    "Anak",
    "Lainnya"
])

const loadRiwayat = async () => {
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
    }
}

const simpan = () => {

    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    if (input.kebrujukan.value == 'TIDAK') {
        if (input.kebrujuklanjutan.value == 'DIANTAR') {
            input.kebketrujukan.value = input.kebketrujukan.value;
        }
    }

    if (input.kebpilihanallo.value == 'Lainnya') {
        input.kebpilihanallo.value = input.keballoanamnesis.value;
    }

    if (input.kualitasnyeri.value == 'LAINNYA') {
        input.kualitasnyeri.value = input.kualitasnyerilain.value
    }

    if (input.pembiayaankesehatan.value == 'ASURANSI') {
        input.pembiayaankesehatan.value = input.ketpembiayaankesehatan.value
    }

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
    console.log(json)

    isLoading.value = true
    useApi().post(
        `/emr/simpan-emr`, json).then((response: any) => {
            isLoading.value = false
            // NOREC_EMRPASIEN.value = response.norec_emr
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

const fetchDokter = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_pegawai.value = response
    })
}

const skor = (e: any, i: any) => {

    let listSkor = listSkoringNyeri.value.detail

    listSkor.forEach((element: any) => {
        if (element.descNilai == e.descNilai) {
            input.value.skoringNyeri = e.descNilai
        }
    });
    isAktive.value = i

}

const getDataExist = async () => {
    await useApi().get(`emr/get-data-exist?nocmfk=${ID_PASIEN}`).then((response) => {

        if (response != null || response != undefined) {
            input.value.beratBadan = response.beratBadan ? response.beratBadan : ''
            input.value.tinggiBadan = response.tinggiBadan ? response.tinggiBadan : ''
            input.value.IMT = response.IMT ? response.IMT : response.IMT
            input.value.lingkarPerut = response.lingkarPerut ? response.lingkarPerut : ''
            input.value.tekananDarah = response.tekananDarah ? response.tekananDarah : ''
            input.value.nadi = response.nadi ? response.nadi : ''
            input.value.suhu = response.suhu ? response.suhu : ''
            input.value.pernapasan = response.pernapasan ? response.pernapasan : ''
        }
    })
}

const handlerRujukanChange = (val: any) => {
    console.log(val);
    if (val === "YA") {

    }
}

const print = async () => {
    H.printBlade(`emr/cetak-asesmen-keper-rj?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
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


getDataExist()
fetchPasien()

watch(() => [
    input.value.penurunanBB,
    input.value.penurunanNafsuMakan,
], () => {

    let poin1 = input.value.penurunanBB ? parseInt(input.value.penurunanBB.poin) : 0
    let poin2 = input.value.penurunanNafsuMakan ? parseInt(input.value.penurunanNafsuMakan.poin) : 0

    const total = poin1 + poin2
    input.value.totalNilaiMST = total

})

</script>


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

.table.is-borderless {
    border: none !important;
    background-color: transparent;
}

.table.is-borderless th,
tr,
td {
    border: none !important;
    background-color: transparent !important;
}

// tr:hover {
//     background-color: #f5f5f5;
// }</style>
