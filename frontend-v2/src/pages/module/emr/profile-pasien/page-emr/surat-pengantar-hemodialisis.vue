<style lang="scss">
h1 {
    font-weight: bold;
}
</style>

<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header" style="margin-bottom: 10px">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Surat Pengantar Hemodialisis (Travelling Dialisis)</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate"
                                @kembaliKeun="kembaliKeun"></ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <div class="column is-12 buttons mb-0 mt-0 pt-0 pb-0" style="margin:10px;vertical-align:middle">
                    <VButton type="button" rounded outlined color="primary" raised icon="feather:folder"
                        :isLoading="isLoading" @click="pilihTemplateFix(index)"> Pilih Template
                    </VButton>
                </div>

                <hr class="m-0">

                <div class="column is-12">
                    <h1>Nama Template&emsp;&emsp;
                        <span style="color:red">**Hanya diisi jika ingin membuat template</span>
                    </h1>
                    <VField>
                        <VControl>
                            <VTextarea v-model="input.namatemplate" rows="1">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>

                <div class="column is-12 columns is-multiline">
                    <div class="column is-3">Nama Pasien :</div>
                    <div class="column is-9">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.TBNamaPasien" />
                        </VControl>
                    </div>

                    <div class="column is-3">Umur :</div>
                    <div class="column is-9 columns mb-0 pb-0">
                        <div class="column is-6 columns">
                            <div class="column is-6">
                                <VField addons>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBSTahun" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>Tahun</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-6">
                                <VField addons>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBSBulan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>Bulan</VButton>
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                        <div class="column is-6">
                            <VField addons label="Tgl Lahir">
                                <VDatePicker v-model="input.DTanggalLahir" mode="date" trim-weeks
                                    :max-date="new Date()">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                    </div>

                    <div class="column is-3 pt-0">Jenis Kelamin :</div>
                    <div class="column is-9 pt-0">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.TBJenisKelamin" />
                        </VControl>
                    </div>

                    <div class="column is-3">Alamat :</div>
                    <div class="column is-9">
                        <VField>
                            <VTextarea rows="2" v-model="input.TAAlamat"></VTextarea>
                        </VField>
                    </div>
                    <div class="column is-3"></div>
                    <div class="column is-9 columns mb-0 pb-0">
                        <div class="column is-6 columns">
                            <div class="column is-6">
                                <VField label="RT" addons>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBRT" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-6">
                                <VField label="RW" addons>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBRW" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                        <div class="column is-6">
                            <VField label="Kota" addons style="text-align: center;">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBKota" />
                                </VControl>
                            </VField>
                        </div>
                    </div>

                    <div class="column is-3">Diagnosis :</div>
                    <div class="column is-9 columns is-multiline mb-0 pb-0">
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="ESRD" label="ESRD"
                                    v-model="input.CBESRD" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Acut on CKD"
                                    label="Acut on CKD" v-model="input.CBAcutOnCKD" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Pre Op" label="Pre Op"
                                    v-model="input.CBPreOp" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Lainnya" label=""
                                    v-model="input.CBLainnyaDiagnosis" />
                            </VControl>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBLainnyaDiagnosis" />
                            </VControl>
                        </div>
                    </div>

                    <div class="column is-3">Hemodialisis Pertama :</div>
                    <div class="column is-9">
                        <VField addons>
                            <VDatePicker v-model="input.DHemodialisisPertama" mode="datetime" trim-weeks
                                :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </VField>
                    </div>

                    <div class="column is-3">Hemodialisis Terakhir :</div>
                    <div class="column is-9">
                        <VField addons>
                            <VDatePicker v-model="input.DHemodialisisTerakhir" mode="datetime" trim-weeks
                                :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </VField>
                    </div>

                    <div class="column is-3">Prekuensi Hemodialisis :</div>
                    <div class="column is-9 columns is-multiline">
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="1x/mgg" label="1x/mgg"
                                    v-model="input.CB1xmgg" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="2x/mgg" label="2x/mgg"
                                    v-model="input.CB2xmgg" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="3x/mgg" label="3x/mgg"
                                    v-model="input.CB3xmgg" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Lainnya" label=""
                                    v-model="input.CBLainnyaPH" />
                            </VControl>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBLainnyaPH" />
                            </VControl>
                        </div>
                    </div>

                    <div class="column is-3">Mesin Hemodialisis :</div>
                    <div class="column is-9 columns is-multiline">
                        <div class="column is-6">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Fresinius" label="Fresinius"
                                    v-model="input.CBFresinius" />
                            </VControl>
                        </div>
                        <div class="column is-6">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="NIPRO" label="NIPRO"
                                    v-model="input.CBNIPRO" />
                            </VControl>
                        </div>
                    </div>

                    <div class="column is-3">Dialises :</div>
                    <div class="column is-9">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.TBDialises" />
                        </VControl>
                    </div>

                    <div class="column is-3">Jenis Dialisat :</div>
                    <div class="column is-9 columns is-multiline">
                        <div class="column is-6">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Bicarbonat" label="Bicarbonat"
                                    v-model="input.CBBicarbonat" />
                            </VControl>
                        </div>
                        <div class="column is-6">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Acide" label="Acide"
                                    v-model="input.CBAcide" />
                            </VControl>
                        </div>
                    </div>

                    <div class="column is-3">Kecepatan Aliran Dialisat (QD) :</div>
                    <div class="column is-9 columns is-multiline">
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="500cc/mnt" label="500cc/mnt"
                                    v-model="input.CB500ccQD" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="300cc/mnt" label="300cc/mnt"
                                    v-model="input.CB300ccQD" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="KAD" label=""
                                    v-model="input.CABKADQD" />
                            </VControl>
                            <VControl style="margin-top: 5px">
                                <VInput type="text" class="input" v-model="input.TBKADQD" />
                            </VControl>
                        </div>
                    </div>

                    <div class="column is-3">Kecepatan Aliran Darah (QB) :</div>
                    <div class="column is-9 columns is-multiline">
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="150cc/mnt" label="150cc/mnt"
                                    v-model="input.CB150ccQB" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="150-200cc/mnt"
                                    label="150-200cc/mnt" v-model="input.CB150200ccQB" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="200-250cc/mnt"
                                    label="200-250cc/mnt" v-model="input.CB200250ccQB" />
                            </VControl>
                        </div>
                    </div>

                    <div class="column is-3">Lama Hemodialisis (TD) :</div>
                    <div class="column is-9 columns is-multiline">
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="2 - 3 Jam" label="2 - 3 Jam"
                                    v-model="input.CB2to3jamTD" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="3 - < 4 Jam"
                                    label="3 - < 4 Jam" v-model="input.CB3to4jamTD" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="4,5 Jam" label="4,5 Jam"
                                    v-model="input.CB4setengahJamTD" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="5 Jam" label="5 Jam"
                                    v-model="input.CB5JamTD" />
                            </VControl>
                        </div>
                        <div class="column is-9">
                            <VField addons>
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Lama Hemodialisis Jam"
                                        label="" v-model="input.CBLamaHemodalisisTD" />
                                </VControl>
                                <VControl style="margin-top: 5px">
                                    <VInput type="text" class="input" v-model="input.TBHemodialisisTD" />
                                </VControl>
                            </VField>
                        </div>
                    </div>

                    <div class="column is-3">Heparinisasi :</div>
                    <div class="column is-9 columns is-multiline">
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Dosis Standar"
                                    label="Dosis Standar" v-model="input.CBDosisStandarH" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Dosis Minimal"
                                    label="Dosis Minimal" v-model="input.CBDosisMinimalH" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="LMWH" label="LMWH"
                                    v-model="input.CBLMWH" />
                            </VControl>
                            <VControl style="margin-top: 5px">
                                <VInput type="text" class="input" v-model="input.TBLMWH" />
                            </VControl>
                        </div>
                    </div>

                    <div class="column is-3">
                        <span>Akses Vaskular</span>
                    </div>
                    <div class="column is-9">
                        <div class="columns is-multiline">
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Femoral" label="Femoral"
                                        v-model="input.Femoral" />
                                </VControl>
                            </div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="AV-Shunt" label="AV-Shunt"
                                        v-model="input.AVShunt" />
                                </VControl>
                            </div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Subclavia Catheter"
                                        label="Subclavia Catheter" v-model="input.SubclaviaCatheter" />
                                </VControl>
                            </div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Jugular Catheter"
                                        label="Jugular Catheter" v-model="input.JugularCatheter" />
                                </VControl>
                            </div>
                        </div>
                    </div>

                    <div class="column is-3">HbsAg :</div>
                    <div class="column is-9 columns is-multiline">
                        <div class="column is-6">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Positif" label="Positif"
                                    v-model="input.CBHbsAg" />
                            </VControl>
                        </div>
                        <div class="column is-6">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Negatif" label="Negatif"
                                    v-model="input.CBHbsAg" />
                            </VControl>
                        </div>
                    </div>

                    <div class="column is-3">Anti HCV :</div>
                    <div class="column is-9 columns is-multiline">
                        <div class="column is-6">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Positif" label="Positif"
                                    v-model="input.CBAntiHCV" />
                            </VControl>
                        </div>
                        <div class="column is-6">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Negatif" label="Negatif"
                                    v-model="input.CBAntiHCV" />
                            </VControl>
                        </div>
                    </div>

                    <div class="column is-3">Anti HIV :</div>
                    <div class="column is-9 columns is-multiline">
                        <div class="column is-6">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Positif" label="Positif"
                                    v-model="input.CBAntiHIV" />
                            </VControl>
                        </div>
                        <div class="column is-6">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Negatif" label="Negatif"
                                    v-model="input.CBAntiHIV" />
                            </VControl>
                        </div>
                    </div>

                    <div class="column is-3">Penyulit HD :</div>
                    <div class="column is-9">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.TBPenyulitHD" />
                        </VControl>
                    </div>

                    <div class="column is-3">Berat Badan Kering :</div>
                    <div class="column is-9">
                        <VField addons>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBSBeratBadanKering" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>Kg</VButton>
                            </VControl>
                        </VField>
                    </div>

                    <div class="column is-3">Tekanan Darah :</div>
                    <div class="column is-9 columns is-multiline">
                        <div class="column is-6">
                            <VField label="Sistolik">
                                <VField addons>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBSSistolikTD" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>mmHg</VButton>
                                    </VControl>
                                </VField>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField label="Diastolik">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBDiastolik" />
                                </VControl>
                            </VField>
                        </div>
                    </div>

                    <div class="column is-3">Hasil Laboratorium Terakhir :</div>
                    <div class="column is-9 columns is-multiline">
                        <div class="column is-3">
                            <VField label="Hb" addons style="text-align: center;">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBHBHLT" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField label="Ureum" addons style="text-align: center;">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBUreumHLT" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField label="Kreatinin" addons style="text-align: center;">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBKreatininHLT" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField label="Natrium" addons style="text-align: center;">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBNatriumHLT" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField label="Kalium" addons style="text-align: center;">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBKaliumHLT" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField label="Albumin" addons style="text-align: center;">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBAlbuminHLT" />
                                </VControl>
                            </VField>
                        </div>
                    </div>

                    <!-- <div class="column is-3">Hasil Laboratorium Terakhir :</div>
                    <div class="column is-9">
                        <VField addons>
                            <VDatePicker v-model="input.DTHLT" mode="datetime" trim-weeks :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </VField>
                    </div> -->

                    <div class="column is-3">
                        <span>Transfusi Darah Terakhir</span>
                    </div>
                    <div class="column is-9">
                        <VField addons>
                            <VDatePicker v-model="input.TransfusiDarahTerakhir" mode="datetime" trim-weeks>
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </VField>
                    </div>

                    <div class="column is-3">
                        <span>Obat-obatan</span>
                    </div>
                    <div class="column is-9">
                        <VField>
                            <VTextarea rows="2" v-model="input.ObatObatan"></VTextarea>
                        </VField>
                    </div>

                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-4" style="text-align: center;margin-left: auto;">
                                <span>Garut</span><br>
                                <VDatePicker v-model="input.tanggal" mode="datetime" trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </template>
                                </VDatePicker><br>
                                <span style="font-weight: bold;">Dokter Yang Merawat</span><br>
                                <TandaTangan :elemenID="'TTDDokter'" :width="'150'" :height="'150'" class="dek" />
                                <VControl class="prime-auto mt-2">
                                    <AutoComplete v-model="input.DokterYangMerawat" :suggestions="d_Dokter"
                                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                        :field="'label'" />
                                </VControl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- form baru -->
            </div>
        </div>
    </div>

    <VModal :open="showModalTemplateFix" title="Template" :noclose="true" size="large" actions="right"
        @close="showModalTemplateFix = false">
        <template #content>
            <form class="modal-form">
                <div class="column is-12 pt-0 pb-0">
                    <span style="font-size:9pt;font-weight:bold">List Template</span>
                    <div style="overflow-y:auto;" class="mt-1">
                        <table style="border: 1px solid black;" v-if="listTemplateFix.length > 0">
                            <thead>
                                <tr>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="5%">No</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="15%">Tanggal Dibuat</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="20%">Nama Ruangan</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="25%">Nama Template</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="15%">#</td>
                                </tr>
                            </thead>
                            <tbody v-for="resep in listTemplateFix">
                                <tr>
                                    <td
                                        style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.no }}</span><br>
                                    </td>
                                    <td
                                        style="width:15%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.created_at }}</span><br>
                                    </td>
                                    <td
                                        style="width:20%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                                    </td>
                                    <td
                                        style="width:25%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.namatemplate }}</span><br>
                                    </td>
                                    <td
                                        style="width:15%;text-align:center;border:1px solid black;vertical-align: middle;padding: 3px;">
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
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'

useHead({ title: 'Surat Pengantar Hemodialisis - ' + import.meta.env.VITE_PROJECT, })
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
const newDate = new Date();
const input: any = ref({})
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const dataTTD: any = ref([])
const route = useRoute()
const pasien: any = ref({})
const d_Dokter: any = ref([]);
const loadData: any = ref(true)
const item: any = reactive({})
const COLLECTION: any = ref('SuratPengantarHemodialisis') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
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
    isLoading.value = true
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    isLoading.value = false

    if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        H.tandaTangan().set("TTDDokter", input.value.TTDDokter)
    } else {
        await setAutofill()
    }
}

function setAutofill() {
    let d = input.value
    d.Dokter = { label: props.registrasi.dokter, value: props.registrasi.iddokter }
    d.TBNamaPasien = props.pasien.namapasien
    d.DTanggalLahir = props.pasien.tgllahir
    d.TBSTahun = calculateAge(props.pasien.tgllahir)
    d.TBJenisKelamin = props.pasien.jeniskelamin
    d.TAAlamat = props.pasien.alamatlengkap
    d.TBAgama = props.pasien.agama
    d.DokterYangMerawat = { label: props.registrasi.dokter, value: props.registrasi.iddokter }
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}
    object = input.value
    if (object.hasOwnProperty('namatemplate')) {
        delete object.namatemplate
    }
    object['TTDDokter'] = H.tandaTangan().get("TTDDokter");
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
    useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
        isLoading.value = false
        loadRiwayat()
    }).catch((e: any) => {
        isLoading.value = false
    })
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

const simpanTemplate = () => {
    if (!input.value.namatemplate) {
        H.alert('warning', "Nama Template wajib diisi")
        console.log()
        return;
    }
    let ID = input.id ? input.id : ''
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

    useApi().post(`/emr/simpan-emr-template`, json).then((response: any) => {
        isLoading.value = false
        input.value.namatemplate = null
    }).catch((e: any) => {
        isLoading.value = false
    })
}
const pilihTemplate = async (index: any) => {
    isLoading.value = true
    useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
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
    delete input.value['id']
    delete input.value['_id']
    input.value.namatemplate = null
    showModalTemplateFix.value = false
    H.alert('info', 'Template berhasil ditambahkan')
}

const addRiwayat = (response: any) => {
    input.value = response //set ke inputan
    delete input.value.namatemplate;
    delete input.value['_id'];
    showModalTemplate.value = false
    showModalTemplateFix.value = false
}

const pilihTemplateFix = async (index: any) => {
    isLoading.value = true
    useApi().get(`/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
        isLoading.value = false
        if (responselast.length) {
            for (var x = 0; x < responselast.length; x++) {
                responselast[x].no = x + 1
                responselast[x].id = ''
            }
            listTemplateFix.value = responselast //set ke inputan
            showModalTemplateFix.value = true
        } else {
            H.alert('warning', 'Data tidak ada')
        }
    })
}

const fetchDokter = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`).then((response) => {
        d_Dokter.value = response;
    });
};
</script>