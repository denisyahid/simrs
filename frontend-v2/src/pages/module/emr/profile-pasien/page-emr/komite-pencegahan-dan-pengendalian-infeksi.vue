<style lang="scss">
table {
    border-collapse: collapse !important;
    width: 100% !important;
    border: 1px solid black !important;
}

th {
    height: 3rem !important;
    text-align: left !important;
    vertical-align: middle !important;
}

td {
    height: 3rem !important;
    text-align: center !important;
    vertical-align: middle !important;
    padding: 3px !important;
    padding-bottom: 1px !important;
    padding-top: 1px !important;
}

.bt {
    border-bottom: 1px solid black !important;
}

.bl {
    border-left: 1px solid black !important;
}
</style>
<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Komite Pencegahan & Pengendalian Infeksi UPT RSUD Bali Mandara Provinsi Bali<br>
                                Formulir
                                Pengumpulan Data Surveilans Infeksi Daerah Operasi</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate"
                                @kembaliKeun="kembaliKeun" isHideST></ButtonEmr>
                        </div>
                    </div>
                </div>

                <div class="columns is-multiline p-2">
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
                                <VButton rounded icon="feather:plus" raised bold
                                @click="addUpload()" color="warning" outlined
                                :loading="isLoading" class="mr-2">Upload</VButton>
                                <VButton rounded icon="feather:eye" raised bold @click="previewBerkas()" color="purple"
                                    outlined :loading="isLoading" class="mr-2">Lihat Foto</VButton>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- form baru -->
                <div class="column">
                    <div class="columns is-multiline">
                        <div class="column is-3">
                            <span>Tanggal MRS</span>
                            <VDatePicker v-model="input.tglMRS" mode="datetime" trim-weeks>
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-3">
                            <span>Lama Operasi</span>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.lamaOperasi"
                                    placeholder="Isi jam/menit" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <span>Operasi Karena Trauma</span>
                            <Multiselect v-model="input.operasiKarenaTrauma" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_yaTidak" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3">
                            <span>Tanggal Operasi</span>
                            <VDatePicker v-model="input.tglOperasi" mode="date" trim-weeks>
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Jenis Operasi</span>
                            <Multiselect v-model="input.jenisOperasi" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_jenisOperasi" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Ruang Operasi</span>
                            <Multiselect v-model="input.ruangOperasi" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_ruangOperasi" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Berat Badan</span>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.beratBadan" />
                            </VControl>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Kualifikasi Dokter Bedah</span>
                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Spesialis"
                                            label="Spesialis" v-model="input.spesialis_KDB" />
                                    </VControl>
                                </div>
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Associate Specialist"
                                            label="Associate Specialist" v-model="input.associateSpesialis_KDB" />
                                    </VControl>
                                </div>
                                <div class="column is-6 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Konsultan"
                                            label="Konsultan" v-model="input.konsultan_KDB" />
                                    </VControl>
                                </div>
                                <div class="column is-6 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Lain-lain"
                                            label="Lain-lain" v-model="input.lainLain_KDB" />
                                    </VControl>
                                    <VControl v-if="input.lainLain_KDB == 'Lain-lain'">
                                        <VInput type="text" class="input" v-model="input.lainLainDetail_KDB" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                        <div class="column is-6 pt-0">
                            <span>Prosedur Operasi</span>
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Sectio Caesaria"
                                            label="Sectio Caesaria" v-model="input.sectioCaesaria_PO" />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Appendictomy"
                                            label="Appendictomy" v-model="input.appendictomy_PO" />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square
                                            true-value="Abdominal hysterectomy" label="Abdominal hysterectomy"
                                            v-model="input.abdominalHysterectomy_PO" />
                                    </VControl>
                                </div>
                                <div class="column is-4 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="ORIF" label="ORIF"
                                            v-model="input.orif_PO" />
                                    </VControl>
                                </div>
                                <div class="column is-4 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Explorasi CBD"
                                            label="Explorasi CBD" v-model="input.explorasiCBD_PO" />
                                    </VControl>
                                </div>
                                <div class="column is-4 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Lain-lain"
                                            label="Lain-lain" v-model="input.lainLain_PO" />
                                    </VControl>
                                    <VControl v-if="input.lainLain_PO == 'Lain-lain'">
                                        <VInput type="text" class="input" v-model="input.lainLainDetail_PO" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Diagnosa</span>
                            <VField>
                                <VTextarea rows="2" v-model="input.diagnosa"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Multiprosedur dengan insisi yang sama</span>
                            <Multiselect v-model="input.multiProsedur" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_yaTidak" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>ASA Score</span>
                            <Multiselect v-model="input.asaScore" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_asaScore" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Klasifikasi Luka</span>
                            <Multiselect v-model="input.klasifikasiLuka" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_klasifikasiLuka" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-12 pt-0 pb-0">
                            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                        </div>
                        <div class="column is-12">
                            <span style="font-weight: bold;font-size: large;">PRE OP</span>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Suhu Pasien</span>
                            <Multiselect v-model="input.suhuPasien" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_suhuPasien" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Albumin</span>
                            <VField addons>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.albumin" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>g/dl</VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Gula Darah</span>
                            <Multiselect v-model="input.gulaDarah" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_gulaDarah" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Steroid Jangka Panjang</span>
                            <Multiselect v-model="input.steroidJangkaPanjang" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_yaTidak" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Merokok</span>
                            <Multiselect v-model="input.merokok" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_yaTidak" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-6 pt-0">
                            <span>Penyakit Saat Ini</span>
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="DM" label="DM"
                                            v-model="input.dm_PSI" />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="GGK" label="GGK"
                                            v-model="input.ggk_PSI" />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Sepsis" label="Sepsis"
                                            v-model="input.sepsis_PSI" />
                                    </VControl>
                                </div>
                                <div class="column is-4 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Hipertensi"
                                            label="Hipertensi" v-model="input.hipertensi_PSI" />
                                    </VControl>
                                </div>
                                <div class="column is-4 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="NA" label="NA"
                                            v-model="input.na_PSI" />
                                    </VControl>
                                </div>
                                <div class="column is-4 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Lain-lain"
                                            label="Lain-lain" v-model="input.lainLain_PSI" />
                                    </VControl>
                                    <VControl v-if="input.lainLain_PSI == 'Lain-lain'">
                                        <VInput type="text" class="input" v-model="input.lainLainDetail_PSI" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Radioterapi Sebelumnya</span>
                            <Multiselect v-model="input.radioterapi" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_yaTidak" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Screening MRSA</span>
                            <Multiselect v-model="input.screeningMRSA" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_yaTidak" :searchable="true" track-by="label" mode="single"
                                autocomplete="off" class="mb-1">
                            </Multiselect>
                            <span>Hasil : (+) / (-)</span>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Pencukuran</span>
                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Clipper"
                                            label="Clipper" v-model="input.clipper_Pencukuran" />
                                    </VControl>
                                </div>
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Silet" label="Silet"
                                            v-model="input.silet_Pencukuran" />
                                    </VControl>
                                </div>
                                <div class="column is-6 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="NA" label="NA"
                                            v-model="input.na_Pencukuran" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Waktu Pencukuran</span>
                            <VDatePicker v-model="input.waktuPencukuran" mode="time" is24hr>
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:clock" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Mechanical Bowel</span>
                            <Multiselect v-model="input.mechanicalBowel" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_yaTidak" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-4 pt-0">
                            <span>Mandi Sebelum Operasi</span>
                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square
                                            true-value="Chlorhexidine bodywash" label="Chlorhexidine bodywash"
                                            v-model="input.chlorhexidine_MSO" />
                                    </VControl>
                                </div>
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Sabun Lain"
                                            label="Sabun Lain" v-model="input.sabunLain_MSO" />
                                    </VControl>
                                </div>
                                <div class="column is-6 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="NA" label="NA"
                                            v-model="input.na_MSO" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                        <div class="column is-4 pt-0">
                            <span>Penyakit Infeksi Lain</span>
                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Infeksi Kulit"
                                            label="Infeksi Kulit" v-model="input.infeksiKulit_PIL" />
                                    </VControl>
                                </div>
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Infeksi Mulut/Gigi"
                                            label="Infeksi Mulut/Gigi" v-model="input.infeksiMulutGigi_PIL" />
                                    </VControl>
                                </div>
                                <div class="column is-6 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Infeksi Mata"
                                            label="Infeksi Mata" v-model="input.infeksiMata_PIL" />
                                    </VControl>
                                </div>
                                <div class="column is-6 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Infeksi THT"
                                            label="Infeksi THT" v-model="input.infeksiTHT_PIL" />
                                    </VControl>
                                </div>
                                <div class="column is-6 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Infeksi Paru"
                                            label="Infeksi Paru" v-model="input.infeksiParu_PIL" />
                                    </VControl>
                                </div>
                                <div class="column is-6 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Infeksi GI Tract"
                                            label="Infeksi GI Tract" v-model="input.infeksiGITract_PIL" />
                                    </VControl>
                                </div>
                                <div class="column is-6 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Lain-lain"
                                            label="Lain-lain" v-model="input.lainLain_PIL" />
                                    </VControl>
                                    <VControl v-if="input.lainLain_PIL == 'Lain-lain'">
                                        <VInput type="text" class="input" v-model="input.lainLainDetail_PIL" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                        <div class="column is-4 pt-0">
                            <span>Profilaksis</span>
                            <Multiselect v-model="input.profilaksis" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_yaTidak" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                            <div class="columns" v-if="input.profilaksis == 'Ya'">
                                <div class="column is-4">
                                    <span>Nama Obat</span>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.namaObat_Profilaksis" />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <span>Dosis</span>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.dosis_Profilaksis" />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <span>Diberikan Jam</span>
                                    <VDatePicker v-model="input.diberikanJam" mode="time" is24hr>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:clock" fullwidth>
                                                <VInput :value="inputValue" v-on="inputEvents" />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12 pt-0 pb-0">
                            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                        </div>
                        <div class="column is-8 pt-0">
                            <span style="font-weight: bold;">ASA Scoring</span>
                            <div class="columns m-0">
                                <div class="column is-6 p-0">
                                    1. Pasien tidak ada kelainan sistemik selain yang akan dioperasi.<br>
                                    2. Pasien ada gangguan sistemik ringan.<br>
                                    3. Pasien ada gangguan sistemik sedang/berat - ada gangguan aktivitas.
                                </div>
                                <div class="column is-6 p-0">
                                    4. Pasien ada gangguan sistemik berat dan mengancam jiwa.<br>
                                    5. Pasien ada gangguan berat, dilakukan / tidak dilakukan tindakan dapat meninggal
                                    dalam 24 jam.
                                </div>
                            </div>
                        </div>
                        <div class="column is-4 pt-0">
                            <div class="columns m-0">
                                <div class="column is-6" style="text-align: center;">
                                    <span>Perawat Pre OP</span>
                                    <TandaTangan :elemenID="'TTDPerawatPreOP'" :width="'150'" :height="'150'"
                                        class="dek" />
                                    <VControl class="prime-auto">
                                        <AutoComplete v-model="input.perawatPreOP" :suggestions="d_Pegawai"
                                            @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" class="mt-2" />
                                    </VControl>
                                </div>
                                <div class="column is-6" style="text-align: center;">
                                    <span>Perawat Intra OP</span>
                                    <TandaTangan :elemenID="'TTDPerawatIntraOP'" :width="'150'" :height="'150'"
                                        class="dek" />
                                    <VControl class="prime-auto">
                                        <AutoComplete v-model="input.perawatIntraOP" :suggestions="d_Pegawai"
                                            @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" class="mt-2" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12 pt-0 pb-0">
                            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                        </div>
                        <div class="column is-12">
                            <span style="font-weight: bold;font-size: large;">DURANTE OP</span>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Sirkulasi Udara OK</span>
                            <VField addons>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.sirkulasiUdaraOK" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>x/jam</VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Kondisi Pintu OK</span>
                            <Multiselect v-model="input.kondisiPintuOK" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_kondisiPintuOK" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Kelembapan Ruang OK</span>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.kelembapanRuangOK" />
                            </VControl>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Tekanan Udara</span>
                            <Multiselect v-model="input.tekananUdara" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_plusMinus" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Jamur AC</span>
                            <Multiselect v-model="input.jamurAC" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_plusMinus" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Drain</span>
                            <Multiselect v-model="input.drain" :attrs="{ value }" placeholder="--Pilih--" label="label"
                                :options="d_drain" :searchable="true" track-by="label" mode="single" autocomplete="off">
                            </Multiselect>
                            <VControl v-if="input.drain == 'Ya'">
                                <VInput type="text" class="input" v-model="input.drainJenis" placeholder="Jenis..." />
                            </VControl>
                        </div>
                        <div class="column is-6 pt-0">
                            <span>Antibiotik Tambahan Saat OP</span>
                            <Multiselect v-model="input.antibiotikTambahan" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_yaTidak" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                            <div class="columns" v-if="input.antibiotikTambahan == 'Ya'">
                                <div class="column is-4">
                                    <span>Nama Obat</span>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.namaObat_ATSOP" />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <span>Dosis</span>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.dosis_ATSOP" />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <span>Diberikan Jam</span>
                                    <VDatePicker v-model="input.diberikanJam_ATSOP" mode="time" is24hr>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:clock" fullwidth>
                                                <VInput :value="inputValue" v-on="inputEvents" />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </div>
                            </div>
                        </div>
                        <div class="column is-6 pt-0">
                            <span>Disinfeksi Kulit</span>
                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Chlorhexidine"
                                            label="Chlorhexidine" v-model="input.chlorhexidine" />
                                    </VControl>
                                </div>
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Alkohol 70%"
                                            label="Alkohol 70%" v-model="input.alkohol70" />
                                    </VControl>
                                </div>
                                <div class="column is-6 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Povidone iodine"
                                            label="Povidone iodine" v-model="input.povidoneIodine" />
                                    </VControl>
                                </div>
                                <div class="column is-6 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Lain-lain"
                                            label="Lain-lain" v-model="input.lainLain_DK" />
                                    </VControl>
                                    <VControl v-if="input.lainLain_DK == 'Lain-lain'">
                                        <VInput type="text" class="input" v-model="input.lainLainDetail_DK" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Suhu Ruang</span>
                            <VField addons>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.suhuRuang" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>°C</VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Implant</span>
                            <Multiselect v-model="input.implant" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_implant" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                            <VControl v-if="input.implant == 'Ya'">
                                <VInput type="text" class="input" v-model="input.implantJenis" placeholder="Jenis..." />
                            </VControl>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Sterilisasi CSSD</span>
                            <Multiselect v-model="input.sterilisasiCSSD" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_yaTidak" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Jumlah Staf</span>
                            <VField addons>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.jumlahStaf" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>Orang</VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Indikator Instrumen/Alat Steril</span>
                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Internal"
                                            label="Internal" v-model="input.internal_IIAS" />
                                    </VControl>
                                </div>
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="External"
                                            label="External" v-model="input.external_IIAS" />
                                    </VControl>
                                </div>
                                <div class="column is-6 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Tidak ada"
                                            label="Tidak ada" v-model="input.tidakAda_IIAS" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12 pt-0 pb-0">
                            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12 pt-0 pb-0">
                                    <span style="font-weight: bold;font-size: large;">POST OP</span>
                                </div>
                                <div class="column is-4 pl-0 pb-0" style="text-align: center;">
                                    <VButton type="button" rounded color="dark" class="mb-3"> Tambah Kolom
                                    </VButton>
                                    <VButtons style="justify-content:space-around">
                                        <VIconButton type="button" raised circle icon="feather:plus"
                                            @click="addNewItem()" color="info" v-tooltip.bubble="'Tambah '">
                                        </VIconButton>
                                        <VIconButton class="mt-1" v-if="input.details.length > 1" type="button" raised
                                            circle icon="feather:trash" @click="removeItem(index)" color="danger">
                                        </VIconButton>
                                    </VButtons>
                                </div>
                                <div class="column is-8 pt-0 pb-0 is-flex"
                                    style="align-items: center;font-weight: bold;font-size: large;">
                                    Beri tanda "✔" sesuai tindakan dan gejala. Beri tanda "0" jika tidak ditemukan
                                    gejala
                                </div>
                                <div class="column is-4 pr-0">
                                    <table style="border: 1px solid black !important;">
                                        <tr>
                                            <th class="bt" style="text-align: center !important;">Post OP hari ke-</th>
                                        </tr>
                                        <tr>
                                            <th class="bt">Rawat Luka</th>
                                        </tr>
                                        <tr>
                                            <th class="bt">Dressing : Transparan</th>
                                        </tr>
                                        <tr>
                                            <th class="bt" style="text-align: center !important;">Hypavix</th>
                                        </tr>
                                        <tr>
                                            <th class="bt">Buang cairan/membuka drain</th>
                                        </tr>
                                        <tr>
                                            <th class="bt" style="background-color: lightgray !important;">Aff drain
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="bt">Angkat jahitan</th>
                                        </tr>
                                        <tr>
                                            <th class="bt">Antibiotik</th>
                                        </tr>
                                        <tr>
                                            <th class="bt">Keluar RS</th>
                                        </tr>
                                        <tr>
                                            <th class="bt">Kontrol poli</th>
                                        </tr>
                                        <tr>
                                            <th class="bt" style="text-align: center !important;">Indetifikasi IDO</th>
                                        </tr>
                                        <tr>
                                            <th class="bt">Nyeri lokal dan sakit</th>
                                        </tr>
                                        <tr>
                                            <th class="bt">Demam (≥ 38°C)</th>
                                        </tr>
                                        <tr>
                                            <th class="bt">Kemerahan</th>
                                        </tr>
                                        <tr>
                                            <th class="bt">Drainase purulen / pus</th>
                                        </tr>
                                        <tr>
                                            <th class="bt">Bengkak terlokalisir</th>
                                        </tr>
                                        <tr>
                                            <th class="bt">Kuman pada kultur pus</th>
                                        </tr>
                                        <tr>
                                            <th class="bt">Ada abses saat re-operasi</th>
                                        </tr>
                                        <tr>
                                            <th class="bt">Diagnosa Dokter : IDO</th>
                                        </tr>
                                    </table>
                                </div>
                                <div class="column is-8 pl-0" style="overflow: auto;">
                                    <table style="width: auto !important;">
                                        <tr>
                                            <td v-for="(data, index) in input.details" :key="index" class="p-0">
                                                <table style="width: 8rem !important;border: none !important">
                                                    <tr>
                                                        <td class="bt bl">{{ index + 1 }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt bl">
                                                            <Multiselect v-model="data.rawatLuka" :attrs="{ value }"
                                                                placeholder="-Pilih-" label="label" :options="d_postOP"
                                                                :searchable="true" track-by="label" mode="single"
                                                                autocomplete="off">
                                                            </Multiselect>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt bl">
                                                            <Multiselect v-model="data.dressingTransparan"
                                                                :attrs="{ value }" placeholder="-Pilih-" label="label"
                                                                :options="d_postOP" :searchable="true" track-by="label"
                                                                mode="single" autocomplete="off">
                                                            </Multiselect>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt bl">
                                                            <Multiselect v-model="data.hypavix" :attrs="{ value }"
                                                                placeholder="-Pilih-" label="label" :options="d_postOP"
                                                                :searchable="true" track-by="label" mode="single"
                                                                autocomplete="off">
                                                            </Multiselect>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt bl">
                                                            <Multiselect v-model="data.buangCairan" :attrs="{ value }"
                                                                placeholder="-Pilih-" label="label" :options="d_postOP"
                                                                :searchable="true" track-by="label" mode="single"
                                                                autocomplete="off">
                                                            </Multiselect>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt bl">
                                                            <Multiselect v-model="data.affDrain" :attrs="{ value }"
                                                                placeholder="-Pilih-" label="label" :options="d_postOP"
                                                                :searchable="true" track-by="label" mode="single"
                                                                autocomplete="off">
                                                            </Multiselect>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt bl">
                                                            <Multiselect v-model="data.angkatJahitan" :attrs="{ value }"
                                                                placeholder="-Pilih-" label="label" :options="d_postOP"
                                                                :searchable="true" track-by="label" mode="single"
                                                                autocomplete="off">
                                                            </Multiselect>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt bl">
                                                            <Multiselect v-model="data.antibiotik" :attrs="{ value }"
                                                                placeholder="-Pilih-" label="label" :options="d_postOP"
                                                                :searchable="true" track-by="label" mode="single"
                                                                autocomplete="off">
                                                            </Multiselect>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt bl">
                                                            <Multiselect v-model="data.keluarRS" :attrs="{ value }"
                                                                placeholder="-Pilih-" label="label" :options="d_postOP"
                                                                :searchable="true" track-by="label" mode="single"
                                                                autocomplete="off">
                                                            </Multiselect>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt bl">
                                                            <Multiselect v-model="data.kontrolPoli" :attrs="{ value }"
                                                                placeholder="-Pilih-" label="label" :options="d_postOP"
                                                                :searchable="true" track-by="label" mode="single"
                                                                autocomplete="off">
                                                            </Multiselect>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt bl">
                                                            <Multiselect v-model="data.indetifikasiIDO"
                                                                :attrs="{ value }" placeholder="-Pilih-" label="label"
                                                                :options="d_postOP" :searchable="true" track-by="label"
                                                                mode="single" autocomplete="off">
                                                            </Multiselect>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt bl">
                                                            <Multiselect v-model="data.nyeriLokal" :attrs="{ value }"
                                                                placeholder="-Pilih-" label="label" :options="d_postOP"
                                                                :searchable="true" track-by="label" mode="single"
                                                                autocomplete="off">
                                                            </Multiselect>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt bl">
                                                            <Multiselect v-model="data.demam" :attrs="{ value }"
                                                                placeholder="-Pilih-" label="label" :options="d_postOP"
                                                                :searchable="true" track-by="label" mode="single"
                                                                autocomplete="off">
                                                            </Multiselect>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt bl">
                                                            <Multiselect v-model="data.kemerahan" :attrs="{ value }"
                                                                placeholder="-Pilih-" label="label" :options="d_postOP"
                                                                :searchable="true" track-by="label" mode="single"
                                                                autocomplete="off">
                                                            </Multiselect>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt bl">
                                                            <Multiselect v-model="data.drainase" :attrs="{ value }"
                                                                placeholder="-Pilih-" label="label" :options="d_postOP"
                                                                :searchable="true" track-by="label" mode="single"
                                                                autocomplete="off">
                                                            </Multiselect>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt bl">
                                                            <Multiselect v-model="data.bengkak" :attrs="{ value }"
                                                                placeholder="-Pilih-" label="label" :options="d_postOP"
                                                                :searchable="true" track-by="label" mode="single"
                                                                autocomplete="off">
                                                            </Multiselect>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt bl">
                                                            <Multiselect v-model="data.kumanPadaKultur"
                                                                :attrs="{ value }" placeholder="-Pilih-" label="label"
                                                                :options="d_postOP" :searchable="true" track-by="label"
                                                                mode="single" autocomplete="off">
                                                            </Multiselect>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt bl">
                                                            <Multiselect v-model="data.adaAbses" :attrs="{ value }"
                                                                placeholder="-Pilih-" label="label" :options="d_postOP"
                                                                :searchable="true" track-by="label" mode="single"
                                                                autocomplete="off">
                                                            </Multiselect>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt bl">
                                                            <Multiselect v-model="data.diagnosaDokter"
                                                                :attrs="{ value }" placeholder="-Pilih-" label="label"
                                                                :options="d_postOP" :searchable="true" track-by="label"
                                                                mode="single" autocomplete="off">
                                                            </Multiselect>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td style="vertical-align: top !important;" class="bl">
                                                <table style="width: 20rem !important;border: none !important">
                                                    <tr>
                                                        <td class="bt">Keterangan (Isi info penting / Beri tanda ) ✔
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt">
                                                            <VControl>
                                                                <VInput type="text" class="input"
                                                                    v-model="input.ketRawatLuka" />
                                                            </VControl>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt">
                                                            <VControl>
                                                                <VInput type="text" class="input"
                                                                    v-model="input.ketDressing" />
                                                            </VControl>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt">
                                                            <VControl>
                                                                <VInput type="text" class="input"
                                                                    v-model="input.ketHypavix" />
                                                            </VControl>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt">
                                                            <Multiselect v-model="input.ketBuangCairan"
                                                                :attrs="{ value }" placeholder="--Pilih--" label="label"
                                                                :options="d_buangCairan" :searchable="true"
                                                                track-by="label" mode="single" autocomplete="off">
                                                            </Multiselect>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt">
                                                            <div class="columns m-0">
                                                                <div class="column is-6 p-0">
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox class="p-0" color="primary" square
                                                                            true-value="Perawat" label="Perawat"
                                                                            v-model="input.affPerawat" />
                                                                    </VControl>
                                                                </div>
                                                                <div class="column is-6 p-0">
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox class="p-0" color="primary" square
                                                                            true-value="Dokter" label="Dokter"
                                                                            v-model="input.affDokter" />
                                                                    </VControl>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt">
                                                            <VControl>
                                                                <VInput type="text" class="input"
                                                                    v-model="input.ketAngkatJahitan" />
                                                            </VControl>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt">
                                                            <VControl>
                                                                <VInput type="text" class="input"
                                                                    v-model="input.ketAntibiotik" />
                                                            </VControl>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt">
                                                            <VControl>
                                                                <VInput type="text" class="input"
                                                                    v-model="input.ketKeluarRS" />
                                                            </VControl>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt">
                                                            <VControl>
                                                                <VInput type="text" class="input"
                                                                    v-model="input.ketKontrolPoli" />
                                                            </VControl>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt">
                                                            <VControl>
                                                                <VInput type="text" class="input"
                                                                    v-model="input.ketIndetifikasiIDO" />
                                                            </VControl>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt">
                                                            <VControl>
                                                                <VInput type="text" class="input"
                                                                    v-model="input.ketNyeriLokal" />
                                                            </VControl>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt">
                                                            <VControl>
                                                                <VInput type="text" class="input"
                                                                    v-model="input.ketDemam" />
                                                            </VControl>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt">
                                                            <VControl>
                                                                <VInput type="text" class="input"
                                                                    v-model="input.ketKemerahan" />
                                                            </VControl>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt">
                                                            <VControl>
                                                                <VInput type="text" class="input"
                                                                    v-model="input.ketDrainase" />
                                                            </VControl>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt">
                                                            <VControl>
                                                                <VInput type="text" class="input"
                                                                    v-model="input.ketBengkak" />
                                                            </VControl>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt">
                                                            <VControl>
                                                                <VInput type="text" class="input"
                                                                    v-model="input.ketKuman" />
                                                            </VControl>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt">
                                                            <VControl>
                                                                <VInput type="text" class="input"
                                                                    v-model="input.ketAdaAbses" />
                                                            </VControl>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bt">
                                                            <VControl>
                                                                <VInput type="text" class="input"
                                                                    v-model="input.ketDiagnosaDokter" />
                                                            </VControl>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="column is-12 pt-0 pb-0">
                                    <hr style="border-top: 1px dashed lightgray;background-color:white"
                                        class="mt-0 mb-1">
                                </div>
                                <div class="column is-12 pt-0 pb-0">
                                    <b>BILA TERJADI INFEKSI</b>, Beri tanda √ pada kotak yang sesuai
                                </div>
                                <div class="column is-4">
                                    <span>Jenis Lokasi Infeksi</span>
                                    <div class="columns is-multiline">
                                        <div class="column is-6">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Superfisial"
                                                    label="Superfisial" v-model="input.superfisial" />
                                            </VControl>
                                        </div>
                                        <div class="column is-6">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Dalam (Fascia/Otot)" label="Dalam (Fascia/Otot)"
                                                    v-model="input.dalamFasciaOtot" />
                                            </VControl>
                                        </div>
                                        <div class="column is-6 pt-0">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Organ/Rongga"
                                                    label="Organ/Rongga" v-model="input.organRongga" />
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-8">
                                    <span>Lokasi Spesifik Untuk Infeksi Organ / Rongga</span>
                                    <div class="columns is-multiline">
                                        <div class="column is-4">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Sal Gastrointestinal" label="Sal Gastrointestinal"
                                                    v-model="input.salGastro" />
                                            </VControl>
                                        </div>
                                        <div class="column is-4">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Intra-Abdominal" label="Intra-Abdominal"
                                                    v-model="input.intraAbdominal" />
                                            </VControl>
                                        </div>
                                        <div class="column is-4">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Sendi/Bursa"
                                                    label="Sendi/Bursa" v-model="input.sendiBursa" />
                                            </VControl>
                                        </div>
                                        <div class="column is-4 pt-0">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Vaginal Cuff"
                                                    label="Vaginal Cuff" v-model="input.vaginalCuff" />
                                            </VControl>
                                        </div>
                                        <div class="column is-4 pt-0">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Sal.genital perempuan" label="Sal.genital perempuan"
                                                    v-model="input.salGenitalPerempuan" />
                                            </VControl>
                                        </div>
                                        <div class="column is-4 pt-0">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Endokardium"
                                                    label="Endokardium" v-model="input.endokardium" />
                                            </VControl>
                                        </div>
                                        <div class="column is-4 pt-0">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Peri/miokradium" label="Peri/miokradium"
                                                    v-model="input.periMiokradium" />
                                            </VControl>
                                        </div>
                                        <div class="column is-4 pt-0">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Lain-lain"
                                                    label="Lain-lain" v-model="input.lainLain_LSUIOR" />
                                            </VControl>
                                            <VControl v-if="input.lainLain_LSUIOR == 'Lain-lain'">
                                                <VInput type="text" class="input"
                                                    v-model="input.lainLainDetail_LSUIOR" />
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12 pt-0 pb-0">
                                    <hr style="border-top: 1px dashed lightgray;background-color:white"
                                        class="mt-0 mb-1">
                                </div>
                                <div class="column is-12 pt-1 pb-1">
                                    <span style="font-weight: bold;font-size: large;">Definisi Tingkat Kontaminasi
                                        Daerah Operasi</span>
                                </div>
                                <div class="column is-6 pt-0">
                                    <span>
                                        1. Bersih: Luka operasi tidak infeksi, tidak ada inflamasi dan tidak membuka
                                        traktus
                                        respiratorius/orofaring, traktus gastrointestinal/biliar, traktus
                                        genitourinarius dimana kasus luka
                                        operasi ini ditutup secara primer serta sistem drainase tertutup.<br>
                                        2. Bersih Terkontaminasi: Luka operasi yang memasuki / membuka traktus
                                        respiratorius, pencernaan/biliar,
                                        appendiks, vagina dan orofaring.
                                    </span>
                                </div>
                                <div class="column is-6 pt-0">
                                    <span>
                                        3. Terkontaminasi: Luka operasi yang membuka semua sistem t ovarium dan nyata
                                        terjadi pencemaran
                                        (perforasi) baru dan lul insisi vana akut &lt; 6 iam inflamasi non purulen.<br>
                                        4. Luka kotor: Luka traumatik &gt; 6 jam dengan hilangnya jaringan infeksi atau
                                        perforasi viseral.
                                    </span>
                                </div>
                                <div class="column is-12 pt-0 pb-0">
                                    <hr style="border-top: 1px dashed lightgray;background-color:white"
                                        class="mt-0 mb-1">
                                </div>
                                <div class="column is-12 pt-1 pb-1">
                                    <span style="font-weight: bold;font-size: large;">Catatan</span>
                                </div>
                                <div class="column is-12 pt-0">
                                    <span>
                                        1. Kolom PRE OP diisi oleh perawat ruangan<br>
                                        2. Kolom DURANTE diisi oleh perawat OK<br>
                                        3. Kolom POST OPS diisi oleh perawat ruangan. Jika px kontrol ke poliklinik,
                                        diisi oleh perawat
                                        poliklinik<br>
                                        4. Bila Pasien pulang, formulir ini dikumpulkan pada IPCLN (Infection Prevention
                                        and Control Link Nurse)
                                        di masing-masing unit
                                    </span>
                                </div>
                                <div class="column is-12 pt-0 pb-0">
                                    <hr style="border-top: 1px dashed lightgray;background-color:white"
                                        class="mt-0 mb-1">
                                </div>
                                <div class="column is-4" style="text-align: center;">
                                    <span>Dokter DPJP/PPI</span><br>
                                    <TandaTangan :elemenID="'TTDDokterDPJP'" :width="'150'" :height="'150'"
                                        class="dek" />
                                    <VControl class="prime-auto">
                                        <AutoComplete v-model="input.dokterDPJP" :suggestions="d_Dokter"
                                            @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" class="mt-2" />
                                    </VControl>
                                </div>
                                <div class="column is-4"></div>
                                <div class="column is-4" style="text-align: center;">
                                    <span>IPCN</span><br>
                                    <TandaTangan :elemenID="'TTDIPCN'" :width="'150'" :height="'150'" class="dek" />
                                    <VControl>
                                        <VInput type="text" class="input mt-2" v-model="input.ipcn" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- form baru -->
            </div>
        </div>
    </div>

    <VModal :open="modalBerkasPreview" title="Lihat Foto" :noclose="true" size="large" actions="right"
        @close="modalBerkasPreview = false">
        <template #content>
            <BerkasPasienView :data="dataSource" @edit="edit" @hapus="hapus" @lihat="lihat" :hide="false">
            </BerkasPasienView>
        </template>
    </VModal>

    <VModal :open="modalInput" title="Upload Foto" :noclose="true" size="medium" actions="right"
        @close="modalInput = false">
        <template #content>
            <div class="columns is-multiline">
                <div class="column is-12">
                    <VField>
                        <VLabel class="required-field">Author</VLabel>
                        <VControl>
                            <VInput type="text" class="input" v-model="item.author" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <VField class="is-rounded-select is-autocomplete-select mt-0 pt-0" v-slot="{ id }">
                        <VLabel class="required-field">File</VLabel>
                        <VControl icon="fas fa-sticky-note" fullwidth class="prime-auto-select">
                            <Dropdown v-model="item.namafile" :options="d_Berkas" :optionLabel="'label'"
                                class="is-rounded" placeholder="File" style="width: 100%;" :filter="true" showClear />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <VField>
                        <VLabel class="required-field">Nama</VLabel>
                        <VControl icon="feather:bookmark">
                            <input v-model="item.nama" type="text" class="input is-rounded" placeholder="Nama " />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <VField>
                        <VLabel>Keterangan</VLabel>
                        <VControl>
                            <VTextarea v-model="item.keterangan" rows="3" placeholder="Keterangan">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <FileUpload v-model="filePasien" mode="basic" name="demo" accept="image/jpeg,image/png"
                        @upload="onUpload" outlined
                        style=" background-color: transparent; color: var(--danger); border: 1px solid;"
                        :chooseLabel="filePasien ? filePasien.name : 'Unggah'" @select="onSelect($event)"
                        class="is-rounded w-100" />
                </div>
            </div>
        </template>
        <template #action>
            <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                @click="simpanFile()"> Simpan
            </VButton>
        </template>
    </VModal>
</template>
<script setup lang="ts">
import * as H from '/@src/utils/appHelper'
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue';
import Dropdown from 'primevue/dropdown';
import FileUpload from 'primevue/fileupload';
import BerkasPasienView from './berkas-pasien-preview.vue'
// import DataTable from 'primevue/datatable'
// import Column from 'primevue/column'
// import InputText from 'primevue/inputtext';

useHead({ title: 'Komite Pencegahan & Pengendalian Infeksi - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const COLLECTION: any = ref('KomitePencegahanPengendalianInfeksi') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const route = useRoute()
const router = useRouter()
const { y } = useWindowScroll()
const user = useUserSession().getUser().pegawai;
const isStuck = computed(() => { return y.value > 30 })
const isLoading = ref(false)
const pasien: any = ref({})
const input: any = ref({
    details: [{
        no: 1,
    }],
})
const dataTTD: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const modalInput: any = ref(false)
const modalBerkasPreview: any = ref(false)
const d_Berkas: any = ref([])
const dataSource: any = ref([])
const filePasien: any = ref()
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
// const listTemplate: any = ref([])
// const showModalTemplate: any = ref(false)
// const listTemplateFix: any = ref([])
// const showModalTemplateFix: any = ref(false)

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
const loadRiwayat = async () => {
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        dataTTD.value = response[0]
        H.tandaTangan().set("TTDPerawatPreOP", dataTTD.value.TTDPerawatPreOP)
        H.tandaTangan().set("TTDPerawatIntraOP", dataTTD.value.TTDPerawatIntraOP)
        H.tandaTangan().set("TTDDokterDPJP", dataTTD.value.TTDDokterDPJP)
        H.tandaTangan().set("TTDIPCN", dataTTD.value.TTDIPCN)
    } else {
        let d = input.value
        d.tglMRS = new Date()
        d.tglOperasi = new Date()
        d.waktuPencukuran = new Date()
        d.diberikanJam = new Date()
        d.diberikanJam_ATSOP = new Date()
        // input.value.DD = { label: user.namaLengkap, value: user.id }
    }
}

const loadRiwayatBerkas = async () => {
    isLoading.value = true
    let param = `nocm=${props.pasien.nocm}&noregistrasi=${props.registrasi.noregistrasi}&dokumen=${52}`;
    await useApi().get(`/emr/berkas-pasien?${param}`).then((response: any) => {
        isLoading.value = false
        dataSource.value = response.data
    })
}

const onSelect = async (filez: any) => {
    const file = filez.files[0];
    filePasien.value = file
}

const edit = async (e: any) => {
    item.author = e.author
    item.norec = e.norec
    item.keterangan = e.deskripsi
    item.nama = e.nama
    d_Berkas.value.forEach((element: any) => {
        if (e.objectberkaspasien == element.id) {
            item.namafile = element
        }
    });
    let path = 'berkaspasien/' + e.nocm + '/' + e.namafile
    let file = await H.getFileBE(path);

    filePasien.value = file
    filePasien.value.name = e.namafile
    modalInput.value = true
}
const hapus = async (e: any) => {
    e.loadingHapus = true
    await useApi().post(`/emr/hapus-berkas-pasien`, { 'norec': e.norec, }).then((response: any) => {
        e.loadingHapus = false
        loadRiwayatBerkas()
    })
}

const lihat = async (e: any) => {
    H.openFile('berkaspasien/' + e.nocm + '/' + e.namafile);
}

const simpanFile = async () => {
    if (!item.namafile) {
        H.alert('error', 'Jenis File harus di isi')
        return
    }
    if (!item.nama) {
        H.alert('error', 'Nama harus di isi')
        return
    }
    if (!filePasien.value) {
        H.alert('error', 'File harus di unggah')
        return
    }
    const formData = new FormData()
    formData.append('filePasien', filePasien.value)
    formData.append('norec', item.norec ? item.norec : '')
    formData.append('noregistrasi', props.registrasi.noregistrasi)
    formData.append('nocm', props.pasien.nocm)
    formData.append('norec_apd', props.registrasi.norec_apd)
    formData.append('namafile', item.namafile.label)
    formData.append('keterangan', item.keterangan ? item.keterangan : null)
    formData.append('objectberkaspasien', item.namafile.value)
    formData.append('nama', item.nama)
    formData.append('author', item.author)
    // formData.append('halaman', parseInt(route.params.index_tabs))
    isLoading.value = true
    await useApi().post('/emr/simpan-berkas-pasien-old', formData).then((r) => {
        isLoading.value = false
        loadRiwayatBerkas()
        modalInput.value = false
    }).catch((e: any) => {
        isLoading.value = false
    })
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    object.nocm = pasien.value.nocm
    if (object.hasOwnProperty('namatemplate')) {
        delete object.namatemplate
    }
    object['TTDPerawatPreOP'] = H.tandaTangan().get("TTDPerawatPreOP");
    object['TTDPerawatIntraOP'] = H.tandaTangan().get("TTDPerawatIntraOP");
    object['TTDDokterDPJP'] = H.tandaTangan().get("TTDDokterDPJP");
    object['TTDIPCN'] = H.tandaTangan().get("TTDIPCN");
    object.pasien = H.setObjectPasien(props.pasien)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'url_form': route.name,
        'name_form': 'Komite Pencegahan & Pengendalian infeksi',
        'jenis_emr': 'asesmen_medis',
        'data': object
    }

    isLoading.value = true
    useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
        isLoading.value = false
        loadRiwayat();
    }).catch((e: any) => {
        isLoading.value = false
    })
}
const fetchPasien = () => {
    pasien.value = props.pasien
    pasien.value.registrasi = props.registrasi
}
const fetchPegawai = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => { d_Pegawai.value = response })
}
const fetchDokter = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`).then((response) => { d_Dokter.value = response })
}

const fetchJenisFile = async (filter: any) => {
    const response = await useApi().get(`/emr/dropdown/berkaspasien_m?select=id,nama`)
    d_Berkas.value = response.filter(item => item.label.toLowerCase().includes('blanko')).map(item => ({
        value: item.value,
        label: item.label
    }));
}

const addNewItem = () => {
    let newItem: any = {}
    newItem = {
        no: input.value.details[input.value.details.length - 1].no + 1
    }
    input.value.details.push(newItem);
}
const removeItem = (index: any) => {
    let urut = input.value.details.length - 1
    input.value.details.splice(urut, 1)
}

const addUpload = () => {
    modalInput.value = true
    item.author = user.namaLengkap
}
const previewBerkas = async () => {
    isLoading.value = true
    await loadRiwayatBerkas()
    isLoading.value = false
    modalBerkasPreview.value = true;
}

onBeforeMount(async () => {
    try {
        await loadRiwayat()
        await fetchPasien()
        let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
        if (cache) input.value = cache
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

// ===== ARRAY =====
const d_yaTidak: any = ref([
    { value: 'Ya', label: 'Ya' },
    { value: 'Tidak', label: 'Tidak' }
])
const d_jenisOperasi: any = ref([
    { value: 'Elektif', label: 'Elektif' },
    { value: 'Darurat', label: 'Darurat' }
])
const d_suhuPasien: any = ref([
    { value: '≥ 38°C', label: '≥ 38°C' },
    { value: '< 38°C', label: '< 38°C' }
])
const d_gulaDarah: any = ref([
    { value: '> 200', label: '> 200' },
    { value: '≤ 200', label: '≤ 200' }
])
const d_kondisiPintuOK: any = ref([
    { value: 'Baik', label: 'Baik' },
    { value: 'Tidak', label: 'Tidak' }
])
const d_drain: any = ref([
    { value: 'Ya', label: 'Ya' },
    { value: 'NA', label: 'NA' }
])
const d_implant: any = ref([
    { value: 'Ya', label: 'Ya' },
    { value: 'NA', label: 'NA' }
])
const d_buangCairan: any = ref([
    { value: 'Tertutup', label: 'Tertutup' },
    { value: 'Terbuka', label: 'Terbuka' }
])
const d_postOP: any = ref([
    { value: '✔', label: '✔' },
    { value: '0', label: '0' }
])
const d_plusMinus: any = ref([
    { value: '(+)', label: '(+)' },
    { value: '(-)', label: '(-)' }
])
const d_klasifikasiLuka: any = ref([
    { value: 'Bersih', label: 'Bersih' },
    { value: 'Bersih Terkontaminasi', label: 'Bersih Terkontaminasi' },
    { value: 'Terkontaminasi', label: 'Terkontaminasi' },
    { value: 'Kotor', label: 'Kotor' }
])
const d_ruangOperasi: any = ref([
    { value: '1', label: '1' },
    { value: '2', label: '2' },
    { value: '3', label: '3' },
    { value: '4', label: '4' },
    { value: '5', label: '5' }
])
const d_asaScore: any = ref([
    { value: '1', label: '1' },
    { value: '2', label: '2' },
    { value: '3', label: '3' },
    { value: '4', label: '4' },
    { value: '5', label: '5' }
])

fetchJenisFile()
</script>