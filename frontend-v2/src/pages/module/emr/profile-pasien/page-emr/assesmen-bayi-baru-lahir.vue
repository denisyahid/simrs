<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Assesmen Bayi Baru Lahir</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate"
                                @kembaliKeun="kembaliKeun" :isHideCetak="true"></ButtonEmr>
                        </div>
                    </div>
                </div>

                <VModal :open="showModalTemplate" title="Riwayat" :noclose="true" size="large" actions="right"
                    @close="showModalTemplate = false">
                    <template #content>
                        <form class="modal-form">
                            <div class="column is-12 pt-0 pb-0">
                                <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
                                <div style="overflow-y:auto;" class="mt-1">
                                    <table class="tg table-tg" v-if="listTemplate.length > 0">
                                        <thead>
                                            <tr>
                                                <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                                    width="15%">Tanggal Input</td>
                                                <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                                    width="15%">Tanggal Registrasi</td>
                                                <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                                    width="15%">No Registrasi</td>
                                                <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                                    width="15%">No EMR</td>
                                                <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                                    width="20%">Dokter</td>
                                                <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                                    width="15%">Section</td>
                                                <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                                    width="5%">#</td>
                                            </tr>
                                        </thead>
                                        <tbody v-for="resep in listTemplate">
                                            <tr>
                                                <td
                                                    style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                                    <span class="mb-2">{{ resep.created_at }}</span><br>
                                                </td>
                                                <td
                                                    style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                                    <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                                                </td>
                                                <td
                                                    style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                                    <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                                                </td>
                                                <td
                                                    style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                                    <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                                                </td>
                                                <td
                                                    style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                                    <span class="mb-2">{{ resep.dpjpUtama }}</span><br>
                                                </td>
                                                <td
                                                    style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                                    <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                                                </td>
                                                <td
                                                    style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                                    <VIconButton type="button" raised circle icon="fas fa-plus"
                                                        @click="addRiwayat(resep)" color="info"
                                                        v-tooltip-prime.top="'Pilih'">
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
                                                        @click="addTemplate(resep)" color="info"
                                                        v-tooltip-prime.top="'Pilih'">
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

                <!-- form baru -->

                <div class="column">
                    <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
                        <VButton type="button" rounded outlined color="primary" raised icon="feather:folder"
                            isLoading="false" @click="pilihTemplateFix(index)"> Pilih Template
                        </VButton>
                        <VButton type="button" rounded outlined color="info" raised icon="feather:file-text"
                            isLoading="false" @click="pilihTemplate(index)"> Pilih Riwayat
                        </VButton>
                    </div>

                    <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                    <div class="column is-12">
                        <h1><b>Nama Template</b>&emsp;&emsp;<span style="color:red">**Hanya diisi jika ingin membuat
                                template</span></h1>
                        <VField>
                            <VControl>
                                <VTextarea v-model="input.namatemplate" rows="1">
                                </VTextarea>
                            </VControl>
                        </VField>
                    </div>

                    <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">

                    <div class="columns is-multiline column pb-0">
                        <div class="column is-3 pt-1">
                            <h1>Tanggal</h1>
                            <VDatePicker v-model="input.tanggal" mode="dateTime" trim-weeks>
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" placeholder="Tanggal masuk..." v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-12 p-0">
                            <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
                        </div>
                        <div class="column is-12 pb-1 pt-1">
                            <h1>DATA SUBYEKTIF</h1>
                        </div>
                        <div class="column is-12 pt-0 pb-1">
                            <h1><i>1. Riwayat Prenatal</i></h1>
                        </div>
                        <div class="column is-2 pt-0">
                            <span>Anak Ke</span>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.AnakKe" />
                            </VControl>
                        </div>
                        <div class="column is-2 pt-0">
                            <span>Umur Kehamilan</span>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="text" class="input" v-model="input.UmurKehamilan" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>minggu</VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2 pt-0">
                            <span>Riwayat Pengobatan Ibu</span>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.RiwayatPengobatanIbu" />
                            </VControl>
                        </div>
                        <div class="column is-6 pt-0">
                            <span>Riwayat Penyakit Ibu</span>
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="DM" label="DM"
                                            v-model="input.DM_RPI" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Hipertensi"
                                            label="Hipertensi" v-model="input.Hipertensi_RPI" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Jantung"
                                            label="Jantung" v-model="input.Jantung_RPI" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="TBC" label="TBC"
                                            v-model="input.TBC_RPI" />
                                    </VControl>
                                </div>
                                <div class="column is-3 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="HEP B" label="HEP B"
                                            v-model="input.HEPB_RPI" />
                                    </VControl>
                                </div>
                                <div class="column is-3 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Asma" label="Asma"
                                            v-model="input.Asma_RPI" />
                                    </VControl>
                                </div>
                                <div class="column is-3 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="PMS" label="PMS"
                                            v-model="input.PMS_RPI" />
                                    </VControl>
                                </div>
                                <div class="column is-3 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Alergi" label="Alergi"
                                            v-model="input.Alergi_RPI" />
                                    </VControl>
                                </div>
                                <div class="column is-3 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Lainnya"
                                            label="Lainnya" v-model="input.Lainnya_RPI" />
                                    </VControl>
                                    <VControl v-if="input.Lainnya_RPI == 'Lainnya'">
                                        <VInput type="text" class="input" v-model="input.LainnyaDetail_RPI" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12 p-0">
                            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                        </div>
                        <div class="column is-12 pt-0 pb-1">
                            <h1><i>2. Intranatal</i></h1>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Diagnose Ibu</span>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.DiagnoseIbu" />
                            </VControl>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Tanggal Lahir & Pukul</span>
                            <VDatePicker v-model="input.tglLahir" mode="datetime" trim-weeks>
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-6 pt-0">
                            <span>Cara Bersalin</span>
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square
                                            true-value="Spontan Belakang Kepala" label="Spontan Belakang Kepala"
                                            v-model="input.SBK_CB" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Spontan Bracht"
                                            label="Spontan Bracht" v-model="input.SpontanBracht_CB" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Lovset mauriceau"
                                            label="Lovset mauriceau" v-model="input.LovsetMauriceau_CB" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Vacum" label="Vacum"
                                            v-model="input.Vacum_CB" />
                                    </VControl>
                                </div>
                                <div class="column is-3 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Forcep" label="Forcep"
                                            v-model="input.Forcep_CB" />
                                    </VControl>
                                </div>
                                <div class="column is-3 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="SC" label="SC"
                                            v-model="input.SC_CB" />
                                    </VControl>
                                </div>
                                <div class="column is-3 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Lainnya"
                                            label="Lainnya" v-model="input.Lainnya_CB" />
                                    </VControl>
                                    <VControl v-if="input.Lainnya_CB == 'Lainnya'">
                                        <VInput type="text" class="input" v-model="input.LainnyaDetail_CB" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Tali Pusat</span>
                            <Multiselect v-model="input.TaliPusat" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_TaliPusat" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3 pt-0">
                            <span>Placenta</span>
                            <Multiselect v-model="input.Placenta" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_Placenta" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                            <VControl v-if="input.Placenta === 'Kelainan'">
                                <VInput type="text" class="input" v-model="input.KelainanDetail"
                                    placeholder="Kelainan..." />
                            </VControl>
                        </div>
                    </div>

                    <div class="column p-0">
                        <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                    </div>

                    <div class="column is-12 pt-0 pb-1">
                        <h1><i>3. Faktor Resiko Infeksi</i></h1>
                    </div>

                    <div class="columns is-multiline m-0">
                        <div class="column is-4">
                            <div class="columns is-multiline">
                                <div class="column is-12 pt-0">
                                    <span>Mayor</span>
                                </div>
                                <div class="column is-4 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Ibu demam ≥ 38 °C"
                                            label="Ibu demam ≥ 38 °C" v-model="input.IbuDemam38c" />
                                    </VControl>
                                </div>
                                <div class="column is-4 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="KPD > 24 jam"
                                            label="KPD > 24 jam" v-model="input.KPD24Jam" />
                                    </VControl>
                                </div>
                                <div class="column is-4 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Ketuban jatuh"
                                            label="Ketuban jatuh" v-model="input.KetubanJatuh" />
                                    </VControl>
                                </div>
                                <div class="column is-4 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Koriomniotis"
                                            label="Koriomniotis" v-model="input.Koriomniotis" />
                                    </VControl>
                                </div>
                                <div class="column is-4 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Fetal distress"
                                            label="Fetal distress" v-model="input.FetalDistress" />
                                    </VControl>
                                </div>
                            </div>
                        </div>

                        <div class="column is-8">
                            <div class="columns is-multiline">
                                <div class="column is-12 pt-0">
                                    <span>Minor</span>
                                </div>
                                <div class="column is-3 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="KPD > 12 jam"
                                            label="KPD > 12 jam" v-model="input.KPD12Jam" />
                                    </VControl>
                                </div>
                                <div class="column is-3 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Asfiksia"
                                            label="Asfiksia" v-model="input.Asfiksia" />
                                    </VControl>
                                </div>
                                <div class="column is-3 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="BBLR" label="BBLR"
                                            v-model="input.BBLR" />
                                    </VControl>
                                </div>
                                <div class="column is-3 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="ISK" label="ISK"
                                            v-model="input.ISK" />
                                    </VControl>
                                </div>
                                <div class="column is-3 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="UK < 37 mg"
                                            label="UK < 37 mg" v-model="input.UK37mg" />
                                    </VControl>
                                </div>
                                <div class="column is-3 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Gemli" label="Gemli"
                                            v-model="input.Gemli" />
                                    </VControl>
                                </div>
                                <div class="column is-3 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Keputihan"
                                            label="Keputihan" v-model="input.Keputihan" />
                                    </VControl>
                                </div>
                                <div class="column is-3 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Ibu temp > 37 °C"
                                            label="Ibu temp > 37 °C" v-model="input.IbuTemp37c" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="column p-0">
                        <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                    </div>

                    <div class="column is-12 pt-0 pb-1">
                        <h1><i>4. Kebutuhan Komunikasi dan edukasi</i></h1>
                    </div>

                    <div class="columns is-multiline m-0">
                        <div class="column is-4 pt-0">
                            <span>Edukasi diberikan kepada</span>
                            <Multiselect v-model="input.EdukasiDiberikanKepada" :attrs="{ value }"
                                placeholder="--Pilih--" label="label" :options="d_EDK" :searchable="true"
                                track-by="label" mode="single" autocomplete="off">
                            </Multiselect>
                            <VControl v-if="input.EdukasiDiberikanKepada == 'Keluarga'">
                                <VInput type="text" class="input" v-model="input.EdukasiDiberikanKepadaKeluarga"
                                    placeholder="Hubungan dengan pasien" />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-0">
                            <span>Bicara</span>
                            <Multiselect v-model="input.Bicara" :attrs="{ value }" placeholder="--Pilih--" label="label"
                                :options="d_Bicara" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                            <VControl v-if="input.Bicara == 'Serangan awal gangguan bicara'">
                                <VInput type="text" class="input" v-model="input.BicaraDetail" placeholder="Kapan" />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-0">
                            <span>Bahasa Sehari-hari</span>
                            <div class="columns is-multiline m-0">
                                <div class="column is-6 pt-0 pl-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Indonesia"
                                            label="Indonesia" v-model="input.BahasaIndonesia" />
                                    </VControl>
                                    <Multiselect v-if="input.BahasaIndonesia == 'Indonesia'"
                                        v-model="input.BahasaIndonesiaDetail" :attrs="{ value }" placeholder="--Pilih--"
                                        label="label" :options="d_aktiPasif" :searchable="true" track-by="label"
                                        mode="single" autocomplete="off">
                                    </Multiselect>
                                </div>
                                <div class="column is-6 pt-0 pl-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Inggris"
                                            label="Inggris" v-model="input.BahasaInggris" />
                                    </VControl>
                                    <Multiselect v-if="input.BahasaInggris == 'Inggris'"
                                        v-model="input.BahasaInggrisDetail" :attrs="{ value }" placeholder="--Pilih--"
                                        label="label" :options="d_aktiPasif" :searchable="true" track-by="label"
                                        mode="single" autocomplete="off">
                                    </Multiselect>
                                </div>
                                <div class="column is-6 pt-0 pl-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Daerah" label="Daerah"
                                            v-model="input.BahasaDaerah" />
                                    </VControl>
                                    <VControl v-if="input.BahasaDaerah == 'Daerah'">
                                        <VInput type="text" class="input" v-model="input.BahasaDaerahDetail"
                                            placeholder="Jelaskan..." />
                                    </VControl>
                                </div>
                                <div class="column is-6 pt-0 pl-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Lain-lain"
                                            label="Lain-lain" v-model="input.BahasaLainlain" />
                                    </VControl>
                                    <VControl v-if="input.BahasaLainlain == 'Lain-lain'">
                                        <VInput type="text" class="input" v-model="input.BahasaLainlainDetail"
                                            placeholder="Jelaskan..." />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                        <div class="column is-4 pt-0">
                            <span>Perlu penterjemah</span>
                            <Multiselect v-model="input.PerluPenterjemah" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_tidakYa" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                            <VControl v-if="input.PerluPenterjemah == 2" class="mb-1">
                                <VInput type="text" class="input" v-model="input.PerluPenterjemahDetail"
                                    placeholder="Bahasa..." />
                            </VControl>
                            <span v-if="input.PerluPenterjemah == 2">Bahasa Isyarat</span>
                            <Multiselect v-model="input.BahasaIsyarat" :attrs="{ value }" placeholder="--Pilih--"
                                v-if="input.PerluPenterjemah == 2" label="label" :options="d_tidakYa" :searchable="true"
                                track-by="label" mode="single" autocomplete="off">
                            </Multiselect>
                            <VControl v-if="input.BahasaIsyarat == 2">
                                <VInput type="text" class="input" v-model="input.BahasaIsyaratDetail"
                                    placeholder="..." />
                            </VControl>
                        </div>
                        <div class="column is-8 pt-0">
                            <div class="columns is-multiline">
                                <div class="column is-12 pt-0">
                                    <span>Hambatan</span>
                                    <Multiselect v-model="input.Hambatan" :attrs="{ value }" placeholder="--Pilih--"
                                        label="label" :options="d_Hambatan" :searchable="true" track-by="label"
                                        mode="single" autocomplete="off">
                                    </Multiselect>
                                </div>
                                <div class="columns is-multiline m-0" v-if="input.Hambatan == 'Ya'">
                                    <div class="column is-4 pt-0" v-for="item in d_HambatanBelajar" :key="item.value">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square :true-value="item.value"
                                                :label="item.label" v-model="input[item.value + '_HB']" />
                                        </VControl>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-6 pt-0">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <span>Cara edukasi yang disukai</span>
                                </div>
                                <div class="column is-4 pt-0" v-for="item in d_CaraEdukasi" :key="item.value">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square :true-value="item.value"
                                            :label="item.label" v-model="input[item.value + '_CE']" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                        <div class="column is-6 pt-0">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <span>Kebutuhan Edukasi</span>
                                </div>
                                <div class="column is-4 pt-0" v-for="item in d_KebutuhanEdukasi" :key="item.value">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square :true-value="item.value"
                                            :label="item.label" v-model="input[item.value + '_KE']" />
                                    </VControl>
                                </div>
                                <div class="column is-8 p-0"></div>
                                <div class="column is-4 pt-0" v-if="input['Lain-lain_KE']">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.LainLainEdukasi"
                                            placeholder="Tuliskan lainnya..." />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12 pt-0 pb-0">
                        <hr style="border-top: 1px dashed red;background-color:white" class="mb-1">
                    </div>

                    <div class="column columns is-multiline pb-0">
                        <div class="columns is-multiline m-0">
                            <div class="column is-12 pb-1 pt-1">
                                <h1>DATA OBJEKTIF</h1>
                            </div>
                            <div class="column is-12 pb-0 pt-0">
                                <h1>1. Keadaan Umum</h1>
                            </div>
                            <div class="column is-3">
                                <span>Kondisi Saat Lahir</span>
                                <Multiselect v-model="input.KondisiSaatLahir" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_KondisiSaatLahir" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3">
                                <span>APGAR Score</span>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.APGAR_Score" />
                                </VControl>
                            </div>
                            <div class="column is-3">
                                <span>Gerak</span>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.Gerak" />
                                </VControl>
                            </div>
                            <div class="column is-3">
                                <span>Tangis</span>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.Tangis" />
                                </VControl>
                            </div>
                            <div class="column is-3 pt-0">
                                <span>Warna Kulit</span>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.WarnaKulit" />
                                </VControl>
                            </div>
                            <div class="column is-3 pt-0">
                                <span>HR</span>
                                <VField addons>
                                    <VControl style="width: 100%;">
                                        <VInput type="text" class="input" v-model="input.Nadi" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>x/mnt</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3 pt-0">
                                <span>Suhu</span>
                                <VField addons>
                                    <VControl style="width: 100%;">
                                        <VInput type="text" class="input" v-model="input.Suhu" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>°C</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3 pt-0">
                                <span>Respirasi</span>
                                <VField addons>
                                    <VControl style="width: 100%;">
                                        <VInput type="text" class="input" v-model="input.Respirasi" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>x/mnt</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3 pt-0">
                                <span>Saturasi O<sub>2</sub></span>
                                <VField addons>
                                    <VControl style="width: 100%;">
                                        <VInput type="text" class="input" v-model="input.SaturasiO2" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>%</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3 pt-0">
                                <span>Capilary Refill</span>
                                <Multiselect v-model="input.CapilaryRefill" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_CapilaryRefill" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-12 pt-0 pb-0">
                                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                            </div>
                            <div class="column is-12 pt-0 pb-0">
                                <h1>2. Ukuran anropometri</h1>
                            </div>
                            <div class="column is-3">
                                <span>Berat Badan</span>
                                <VField addons>
                                    <VControl style="width: 100%;">
                                        <VInput type="text" class="input" v-model="input.TBSBeratBadan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>gram</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <span>PB</span>
                                <VField addons>
                                    <VControl style="width: 100%;">
                                        <VInput type="text" class="input" v-model="input.TBS_PB" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>cm</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <span>LK</span>
                                <VField addons>
                                    <VControl style="width: 100%;">
                                        <VInput type="text" class="input" v-model="input.TBS_LK" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>cm</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <span>LD</span>
                                <VField addons>
                                    <VControl style="width: 100%;">
                                        <VInput type="text" class="input" v-model="input.TBS_LD" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>cm</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-12 pt-0 pb-0">
                                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                            </div>
                            <div class="column is-12 pt-0 pb-0">
                                <h1>3. Pemeriksaan Fisik</h1>
                            </div>
                            <div class="column is-3 pt-1">
                                <h1>Kepala</h1>
                                <Multiselect v-model="input.SKepala_PF" :attrs="{ value }"
                                    placeholder="--Pilih--" label="label" :options="d_kepala_PF"
                                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1" v-if="input.SKepala_PF == 8">
                                <h1>Lainnya</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBLainnya_Kepala" />
                                </VControl>
                            </div>
                            <div class="column is-3 pt-1">
                                <h1>UUB</h1>
                                <Multiselect v-model="input.Suub_PF" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_uub_PF" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1" v-if="input.Suub_PF == 4">
                                <h1>Lainnya</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBLainnya_uub" />
                                </VControl>
                            </div>
                            <div class="column is-3 pt-1">
                                <h1>Mata</h1>
                                <Multiselect v-model="input.SMata_PF" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_mata_PF" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1" v-if="input.SMata_PF == 5">
                                <h1>Lainnya</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBLainnya_Mata" />
                                </VControl>
                            </div>
                            <div class="column is-3 pt-1">
                                <h1>THT</h1>
                                <Multiselect v-model="input.Stht_PF" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_tht_PF" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1" v-if="input.Stht_PF == 5">
                                <h1>Lainnya</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBLainnya_tht" />
                                </VControl>
                            </div>
                            <div class="column is-3 pt-1">
                                <h1>Mulut</h1>
                                <Multiselect v-model="input.SMulut_PF" :attrs="{ value }"
                                    placeholder="--Pilih--" label="label" :options="d_mulut_PF"
                                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1" v-if="input.SMulut_PF == 5">
                                <h1>Mukosa Warna</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBMukosaWarna_Mulut" />
                                </VControl>
                            </div>
                            <div class="column is-3 pt-1" v-if="input.SMulut_PF == 7">
                                <h1>Lainnya</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBLainnya_Mulut" />
                                </VControl>
                            </div>
                            <div class="column is-3 pt-1">
                                <h1>Thorax</h1>
                                <Multiselect v-model="input.SThorax_PF" :attrs="{ value }"
                                    placeholder="--Pilih--" label="label" :options="d_thorax_PF"
                                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1" v-if="input.SThorax_PF == 4">
                                <h1>Lainnya</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBLainnya_Thorax" />
                                </VControl>
                            </div>
                            <div class="column is-3 pt-1">
                                <h1>Abdomen</h1>
                                <Multiselect v-model="input.SAbdomen_PF" :attrs="{ value }"
                                    placeholder="--Pilih--" label="label" :options="d_abdomen_PF"
                                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1" v-if="input.SAbdomen_PF == 4">
                                <h1>Lainnya</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBLainnya_Abdomen" />
                                </VControl>
                            </div>
                            <div class="column is-3 pt-1" v-if="input.SAbdomen_PF == 5">
                                <h1>Kelainan</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBKelainan_Abdomen" />
                                </VControl>
                            </div>
                            <div class="column is-3 pt-1">
                                <h1>Tali Pusat</h1>
                                <Multiselect v-model="input.STaliPusat_PF" :attrs="{ value }"
                                    placeholder="--Pilih--" label="label" :options="d_talipusat_PF"
                                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <h1>Punggung</h1>
                                <Multiselect v-model="input.SPunggung_PF" :attrs="{ value }"
                                    placeholder="--Pilih--" label="label" :options="d_punggung_PF"
                                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1" v-if="input.SPunggung_PF == 4">
                                <h1>Lainnya</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBLainnya_Punggung" />
                                </VControl>
                            </div>
                            <div class="column is-3 pt-1">
                                <h1>Genetalia, kelainan</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBKelainan_Genetalia" />
                                </VControl>
                            </div>
                            <div class="column is-3 pt-1">
                                <h1>Anus</h1>
                                <Multiselect v-model="input.SAnus_PF" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_adatidakada" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <h1>Ekstremitas</h1>
                                <Multiselect v-model="input.SEkstremitas_PF" :attrs="{ value }"
                                    placeholder="--Pilih--" label="label" :options="d_ekstremitas_PF"
                                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1" v-if="input.SEkstremitas_PF == 4">
                                <h1>Lainnya</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBLainnya_Ekstremitas" />
                                </VControl>
                            </div>
                            <div class="column is-3 pt-1">
                                <h1>Kulit</h1>
                                <Multiselect v-model="input.SKulit_PF" :attrs="{ value }"
                                    placeholder="--Pilih--" label="label" :options="d_kulit_PF"
                                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1" v-if="input.SKulit_PF == 1">
                                <h1>Turgor</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBTurgor_Kulit" />
                                </VControl>
                            </div>
                            <div class="column is-3 pt-1" v-if="input.SKulit_PF == 8">
                                <h1>Lainnya</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBLainnya_Kulit" />
                                </VControl>
                            </div>
                            <div class="column is-12 pt-0 pb-0">
                                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                            </div>
                            <div class="column is-12 pt-0 pb-0">
                                <h1>4. Penilaian Nyeri Neonatus</h1>
                            </div>
                            <div class="column is-12 pt-0 pb-0" style="font-size: large;"><i>Fisik</i></div>
                            <div class="column is-3 pt-0">
                                <span>Postus/tonus</span>
                                <Multiselect v-model="input.PostusTonus_PNN" :options="d_PostusTonus" label="label"
                                    track-by="label" mode="single" />
                            </div>
                            <div class="column is-3 pt-0">
                                <span>Pola Tidur</span>
                                <Multiselect v-model="input.PolaTidur_PNN" :options="d_PolaTidur" label="label"
                                    track-by="label" mode="single" />
                            </div>
                            <div class="column is-3 pt-0">
                                <span>Ekspresi</span>
                                <Multiselect v-model="input.Ekspresi_PNN" :options="d_Ekspresi" label="label"
                                    track-by="label" mode="single" />
                            </div>
                            <div class="column is-3 pt-0">
                                <span>Tangis</span>
                                <Multiselect v-model="input.Tangis_PNN" :options="d_Tangis" label="label"
                                    track-by="label" mode="single" />
                            </div>
                            <div class="column is-3 pt-0">
                                <span>Warna</span>
                                <Multiselect v-model="input.Warna_PNN" :options="d_Warna" label="label" track-by="label"
                                    mode="single" />
                            </div>

                            <div class="column is-12 pt-0 pb-0" style="font-size: large;"><i>Fisiologis</i></div>
                            <div class="column is-3 pt-0">
                                <span>Laju Napas</span>
                                <Multiselect v-model="input.LajuNapas_PNN" :options="d_LajuNapas" label="label"
                                    track-by="label" mode="single" />
                            </div>
                            <div class="column is-3 pt-0">
                                <span>Denyut Jantung</span>
                                <Multiselect v-model="input.DenyutJantung_PNN" :options="d_DenyutJantung" label="label"
                                    track-by="label" mode="single" />
                            </div>
                            <div class="column is-3 pt-0">
                                <span>Saturasi</span>
                                <Multiselect v-model="input.Saturasi_PNN" :options="d_Saturasi" label="label"
                                    track-by="label" mode="single" />
                            </div>
                            <div class="column is-3 pt-0">
                                <span>Tekanan Darah</span>
                                <Multiselect v-model="input.TekananDarah_PNN" :options="d_TekananDarah" label="label"
                                    track-by="label" mode="single" />
                            </div>

                            <div class="column is-12 pt-0 pb-0" style="font-size: large;"><i>Persepsi Perawat</i></div>
                            <div class="column is-3 pt-0">
                                <span>Persepsi Nyeri</span>
                                <Multiselect v-model="input.PersepsiPerawat_PNN" :options="d_PersepsiPerawat"
                                    label="label" track-by="label" mode="single" />
                            </div>
                            <div class="column is-12 pt-0 pb-0"></div>
                            <div class="column is-9 pt-0">
                                Intervenasi yang diperlukan :<br>
                                &lt; 5: Pemberian kenyamanan keperawatan<br>
                                &lt; 5: Paracetamol<br>
                                &gt; 10: NCM, paracetamol, narkotik
                            </div>
                            <div class="column is-3 pt-0" style="margin-left: auto;">
                                <span>Total Skor</span>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TotalSkor_PNN" />
                                </VControl>
                            </div>
                            <div class="column is-12 pt-0 pb-0">
                                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                            </div>
                            <div class="column is-12 pt-0 pb-0">
                                <h1>Kesimpulan data penunjang</h1>
                            </div>
                            <div class="column is-6 pt-0">
                                <span>1. Laboratorium</span>
                                <VField>
                                    <VTextarea rows="2" v-model="input.Laboratorium"></VTextarea>
                                </VField>
                            </div>
                            <div class="column is-6 pt-0">
                                <span>2. Radiologi</span>
                                <VField>
                                    <VTextarea rows="2" v-model="input.Radiologi"></VTextarea>
                                </VField>
                            </div>
                            <div class="column is-12 pt-0 pb-0">
                                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                            </div>
                            <div class="column is-12 pt-0 pb-0">
                                <h1>Diagnosis/Analisis</h1>
                                <VField>
                                    <VTextarea rows="2" v-model="input.Diagnosis"></VTextarea>
                                </VField>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12 pt-0 pb-0">
                        <hr style="border-top: 1px dashed red;background-color:white" class="mb-1">
                    </div>
                    <div class="column is-12 pt-0">
                        <table class="tg">
                            <thead>
                                <tr>
                                    <th style="width: 10%;">#</th>
                                    <th style="width: 20%;">Tanggal & Jam</th>
                                    <th style="width: 20%;">Profesi</th>
                                    <th style="width: 30%;">Penatalaksanaan</th>
                                    <th style="width: 20%;">Paraf & Nama Terang</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in input.details" :key="index">
                                    <td style="vertical-align: inherit">
                                        <div class="column">
                                            <VButtons style="justify-content:space-around">
                                                <VIconButton type="button" raised circle icon="feather:plus"
                                                    @click="addNewItem()" color="info" v-tooltip.bubble="'Tambah '">
                                                </VIconButton>
                                                <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle
                                                    icon="feather:trash" @click="removeItem(index)" color="danger">
                                                </VIconButton>
                                            </VButtons>
                                        </div>
                                    </td>
                                    <td style="vertical-align: inherit;">
                                        <VDatePicker v-model="item.tanggal" mode="datetime" trim-weeks>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" v-on="inputEvents" />
                                                </VControl>
                                            </template>
                                        </VDatePicker>
                                    </td>
                                    <td style="vertical-align: inherit;">
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.Profesi" />
                                        </VControl>
                                    </td>
                                    <td style="vertical-align: inherit;">
                                        <VField>
                                            <VTextarea rows="2" v-model="item.Penatalaksanaan"></VTextarea>
                                        </VField>
                                    </td>
                                    <td style="vertical-align: inherit;text-align: center;">
                                        <TandaTangan :elemenID="`TTD_${index}`" :width="'150'" :height="'150'"
                                            class="dek" />
                                        <VControl class="prime-auto">
                                            <AutoComplete v-model="item.Paraf" :suggestions="d_Pegawai"
                                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" class="mt-2" />
                                        </VControl>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import * as H from '/@src/utils/appHelper'
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount, nextTick } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import ImgDraw from '../page-emr-plugins/img-draw.vue'

useHead({ title: 'Assesmen Bayi Baru Lahir - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const route = useRoute()
const user = useUserSession().getUser().pegawai;
const pasien: any = ref({})
const dataTTD: any = ref([])
const d_Pegawai: any = ref([])
const loadData: any = ref(true)
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const isLoading = ref(false)
const isAktive = ref()
const router = useRouter()
const selectedRegistrasi: any = ref({})
const isRemoveTAB: any = ref(false)
const TAB_ACTIVE: any = ref('Dashboard');
const TAB_URL = ref('')
const TAB_ACTIVE_ROUTER: any = ref(null)
const TAB_ROUTER_DEFAULT = ref('module-emr-profile-pasien-page-emr-not-found')
const COLLECTION: any = ref('AssesmenBayiBaruLahir') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})

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
const item: any = reactive({})
const input: any = ref({
    tanggal: new Date(),
    tglLahir: new Date(),
    details: [{
        no: 1,
        tanggal: new Date()
    }],
})
const loadRiwayat = async () => {
    isLoading.value = true
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    isLoading.value = false
    if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        dataTTD.value = response[0]
        for (let i = 0; i < input.value.details.length; i++) {
            await nextTick();
            const fieldName = `TTD_${i}`;
            H.tandaTangan().set(fieldName, dataTTD.value[fieldName]);
        }
    } else {
        input.value.DDPerawat = { label: user.namaLengkap, value: user.id }
    }
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    for (let i = 0; i <= input.value.details.length; i++) {
        object[`TTD_${i}`] = H.tandaTangan().get(`TTD_${i}`);
    }
    if (object.hasOwnProperty('namatemplate')) {
        delete object.namatemplate
    }
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
        loadRiwayat();
    }).catch((e: any) => {
        isLoading.value = false
    })
}
const fetchPegawai = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => {
        d_Pegawai.value = response
    })
}

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

const removeItem = (index: any) => {
    input.value.details.splice(index, 1)
}
const addNewItem = () => {
    let newItem: any = {}
    newItem = { no: input.value.details[input.value.details.length - 1].no + 1, tanggal: new Date() }
    input.value.details.push(newItem);
}

watch(
    () => input.value,
    (newVal) => {
        let total = 0;
        total += parseFloat(newVal.PostusTonus_PNN ?? 0);
        total += parseFloat(newVal.PolaTidur_PNN ?? 0);
        total += parseFloat(newVal.Ekspresi_PNN ?? 0);
        total += parseFloat(newVal.Tangis_PNN ?? 0);
        total += parseFloat(newVal.Warna_PNN ?? 0);
        total += parseFloat(newVal.LajuNapas_PNN ?? 0);
        total += parseFloat(newVal.DenyutJantung_PNN ?? 0);
        total += parseFloat(newVal.Saturasi_PNN ?? 0);
        total += parseFloat(newVal.TekananDarah_PNN ?? 0);
        total += parseFloat(newVal.PersepsiPerawat_PNN ?? 0);
        input.value.TotalSkor_PNN = total;
    },
    { deep: true }
);



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

// ===== ARRAY =====
const d_HambatanBelajar: any = ref([
    { value: "Bahasa", label: "Bahasa" },
    { value: "Pendengaran", label: "Pendengaran" },
    { value: "Hilang memori", label: "Hilang memori" },
    { value: "Motivasi buruk", label: "Motivasi buruk" },
    { value: "Masalah penglihatan", label: "Masalah penglihatan" },
    { value: "Cemas", label: "Cemas" },
    { value: "Emosi", label: "Emosi" },
    { value: "Kesulitan bicara", label: "Kesulitan bicara" },
    { value: "Tidak ada partisipasi belajar", label: "Tidak ada partisipasi belajar" },
    { value: "Secara fisiologi tidak mampu belajar", label: "Secara fisiologi tidak mampu belajar" }
]);
const d_CaraEdukasi: any = ref([
    { value: "Menulis", label: "Menulis" },
    { value: "Audio – visual / gambar", label: "Audio – visual / gambar" },
    { value: "Diskusi", label: "Diskusi" },
    { value: "Membaca", label: "Membaca" },
    { value: "Mendengar", label: "Mendengar" },
    { value: "Demonstrasi", label: "Demonstrasi" }
]);
const d_KebutuhanEdukasi: any = ref([
    { value: "Proses penyakit", label: "Proses penyakit" },
    { value: "Support/ psikologi", label: "Support/ psikologi" },
    { value: "Pengobatan/ tindakan", label: "Pengobatan/ tindakan" },
    { value: "Terapi/Obat", label: "Terapi/Obat" },
    { value: "Nutrisi", label: "Nutrisi" },
    { value: "Lain-lain", label: "Lain-lain" }
]);
const d_Perlu_RPP: any = ref([
    { value: 1, label: 'Usia lanjut (> 60 tahun) dengan gangguan daya ingat Bayi BBLR,' },
    { value: 2, label: 'Keterbatasan / gangguan mobilitas' },
    { value: 3, label: 'Memerlukan pertolongan untuk melanjutkan terapi dan perawatan terus menerus' },
    { value: 4, label: 'Memerlukan bantuan melakukan kegiatan sehari-hari' }
])
const d_perluTidakPerlu: any = ref([
    { value: 1, label: 'Perlu' },
    { value: 2, label: 'Tidak Perlu' }
])
const d_tidakAda: any = ref([
    { value: 1, label: 'Tidak' },
    { value: 2, label: 'Ada' }
])
const d_adaTidak: any = ref([
    { value: 1, label: 'Ada' },
    { value: 2, label: 'Tidak' }
])
const d_tidakYa: any = ref([
    { value: 1, label: 'Tidak' },
    { value: 2, label: 'Ya' }
])
const d_yaTidak: any = ref([
    { value: 1, label: 'Ya' },
    { value: 2, label: 'Tidak' }
])
const d_tidakAda_ada: any = ref([
    { value: 1, label: 'Tidak ada' },
    { value: 2, label: 'Ada' }
])
const d_ada_tidakAda: any = ref([
    { value: 1, label: 'Ada' },
    { value: 2, label: 'Tidak ada' }
])
const d_tempatRujukan: any = ref([
    { value: 1, label: 'Rumah Sakit' },
    { value: 2, label: 'Puskesmas' },
    { value: 3, label: 'dr.' },
    { value: 4, label: 'Lainnya' }
]);
const d_kualitasNadi: any = ref([
    { value: 1, label: 'Kuat' },
    { value: 2, label: 'Lemah' },
    { value: 3, label: 'Tidak teraba' }
]);
const d_penurunanbb: any = ref([
    { value: 0, label: 'Tidak' },
    { value: 2, label: 'Tidak Yakin' }
]);
const d_polaNafas: any = ref([
    { value: 1, label: 'Tidak Teratur' },
    { value: 2, label: 'Teratur' }
]);
const d_penurunannafsu: any = ref([
    { value: 1, label: 'Ya' },
    { value: 0, label: 'Tidak' }
]);
const d_penurunanbbYa: any = ref([
    { value: 1, label: '1-5 kg' },
    { value: 2, label: '6-10 kg' },
    { value: 3, label: '11-15 kg' },
    { value: 4, label: '>15 kg' }
]);
const d_Kesadaran: any = ref([
    { value: 1, label: 'Composmentis' },
    { value: 2, label: 'Apatis' },
    { value: 3, label: 'Somnolen' },
    { value: 4, label: '>Somnolen' },
    { value: 5, label: 'Coma' }
]);
const d_mengontrolbab: any = ref([
    { value: 0, label: 'Inkontinen/tidak teratur (perlu enema)' },
    { value: 1, label: 'Kadang inkontinen (1xseminggu)' },
    { value: 2, label: 'Kontinen teratur' }
]);
const d_mengontrolbak: any = ref([
    { value: 0, label: 'Inkontinen/pakai kateter dan tidak terkontrol' },
    { value: 1, label: 'Kadang inkontinen (max 1x24 jam)' },
    { value: 2, label: 'Mandiri' }
]);
const d_bersihdiri: any = ref([
    { value: 0, label: 'Butuh pertolongan orang lain' },
    { value: 1, label: 'Mandiri' }
]);
const d_toilet: any = ref([
    { value: 0, label: 'Tergantung pertolongan orang lain' },
    { value: 1, label: 'Perlu pertolongan pada beberapa aktivitas terapi dan dapat mengerjakan sendiri beberapa aktivitas lain' },
    { value: 2, label: 'Mandiri' }
]);
const d_makan: any = ref([
    { value: 0, label: 'Tidak mampu' },
    { value: 1, label: 'Perlu seseorang menolong memotong makanan' },
    { value: 2, label: 'Mandiri' }
]);
const d_berpindahtt: any = ref([
    { value: 0, label: 'Tidak Mampu' },
    { value: 1, label: 'Perlu banyak bantuan untuk bisa duduk (2 orang)' },
    { value: 2, label: 'Bantuan 1 orang' },
    { value: 3, label: 'Mandiri' }
]);
const d_mobilisasi: any = ref([
    { value: 0, label: 'Tidak Mampu' },
    { value: 1, label: 'Dengan kursi roda' },
    { value: 2, label: 'Bantuan 1 orang' },
    { value: 3, label: 'Mandiri' }
]);
const d_berpakaian: any = ref([
    { value: 0, label: 'Tergantung orang lain' },
    { value: 1, label: 'Sebagian dibantu (misal mengancing baju)' },
    { value: 2, label: 'Mandiri' }
]);
const d_tangga: any = ref([
    { value: 0, label: 'Tidak Mampu' },
    { value: 1, label: 'Butuh Pertolongan' },
    { value: 2, label: 'Mandiri' }
]);
const d_mandi: any = ref([
    { value: 0, label: 'Teragantung orang lain' },
    { value: 1, label: 'Mandiri' }
]);
const d_keadaanumum: any = ref([
    { value: 1, label: 'Baik' },
    { value: 2, label: 'Sedang' },
    { value: 3, label: 'Lemah' },
    { value: 4, label: 'Jelek' }
])
const d_rujukan: any = ref([
    { value: 1, label: 'Ya' },
    { value: 2, label: 'Tidak' },
    { value: 3, label: 'Datang Sendiri' },
    { value: 4, label: 'Diantar' }
])
const d_allo: any = ref([
    { value: 1, label: 'Suami/Istri' },
    { value: 2, label: 'Orang Tua' },
    { value: 3, label: 'Anak' },
    { value: 4, label: 'Pasien' },
    { value: 5, label: 'Lainnya' }
])
const d_kualitasNyeri_AN: any = ref([
    { value: 1, label: 'Tumpul' },
    { value: 2, label: 'Tajam' },
    { value: 3, label: 'Panas/terbakar' },
    { value: 4, label: 'Lain-lain' }
])
const d_frekuensiNyeri_AN: any = ref([
    { value: 1, label: 'Jarang' },
    { value: 2, label: 'Hilang timbul' },
    { value: 3, label: 'Terus menerus' }
])
const d_kondisiPsikologis: any = ref([
    { value: 1, label: 'Gelisah' },
    { value: 2, label: 'Takut' },
    { value: 3, label: 'Sedih' },
    { value: 4, label: 'Rendah diri' },
    { value: 5, label: 'Acuh tak acuh' },
    { value: 6, label: 'Mudah tersinggung' },
    { value: 7, label: 'Menarik diri' }
])
const d_statusPernikahan: any = ref([
    { value: 1, label: 'Singel' },
    { value: 2, label: 'Menikah' },
    { value: 3, label: 'Bercerai' }
])
const d_pembiayaanKesehatan: any = ref([
    { value: 1, label: 'Biaya sendiri/keluarga' },
    { value: 2, label: 'Asuransi lainnya' }
])
const d_dukunganSosial: any = ref([
    { value: 1, label: 'Suami' },
    { value: 2, label: 'Orang tua' },
    { value: 3, label: 'Keluarga' },
    { value: 4, label: 'Lainnya' }
])
const d_kebiasaanIbu: any = ref([
    { value: 1, label: 'Merokok' },
    { value: 2, label: 'Minum alkohol' },
    { value: 3, label: 'Lainnya' }
])
const d_riwayatG: any = ref([
    { value: 1, label: 'Tidak ada' },
    { value: 2, label: 'Ada' }
])
const d_rpp = ref([
    { value: 1, label: 'Perlu' },
    { value: 2, label: 'Tidak Perlu' }
]);
// === Bikin Sendiri ===
const d_teraturSiklus: any = ref([
    { value: 1, label: 'Teratur' },
    { value: 2, label: 'Tidak Teratur' }
])
const d_ANC: any = ref([
    { value: 1, label: 'Dokter Kandungan' },
    { value: 2, label: 'Dokter Umum' },
    { value: 3, label: 'Bidan' },
    { value: 4, label: 'Lainnya' }
])
const d_frekuensi: any = ref([
    { value: 1, label: '1x' },
    { value: 2, label: '2x' },
    { value: 3, label: '3x' },
    { value: 4, label: '4x' }
])
const d_KSH: any = ref([
    { value: 1, label: 'Mual' },
    { value: 2, label: 'Muntah' },
    { value: 3, label: 'Perdarahan' },
    { value: 4, label: 'Pusing' },
    { value: 5, label: 'Sakit Kepala' },
    { value: 6, label: 'Lainnya' }
])
const d_RPK: any = ref([
    { value: 1, label: 'Hipertensi' },
    { value: 2, label: 'HIV' },
    { value: 3, label: 'Kencing Manis' },
    { value: 4, label: 'Jantung' },
    { value: 5, label: 'Jiwa' },
    { value: 6, label: 'Varises' },
    { value: 7, label: 'Lain-lain' },
])
const d_masalahPerkemihan: any = ref([
    { value: 1, label: 'Retensi Urine' },
    { value: 2, label: 'Inkontinensia Urine' },
    { value: 3, label: 'Dialysis' },
    { value: 4, label: 'Lainnya' }
])
const d_warnaUrine: any = ref([
    { value: 1, label: 'Kuning Jernih' },
    { value: 2, label: 'Keruh' },
    { value: 3, label: 'Kemerahan' }
]);
const d_masalahDefekasi: any = ref([
    { value: 1, label: 'Stoma' },
    { value: 2, label: 'Atresia ani' },
    { value: 3, label: 'Konstipasi' },
    { value: 4, label: 'Diare' },
    { value: 5, label: 'Inkontinensia alvi' },
    { value: 6, label: 'Lainnya' }
]);
const d_warnaFaeces: any = ref([
    { value: 1, label: 'Kuning' },
    { value: 2, label: 'Kecoklatan' },
    { value: 3, label: 'Kehitaman' },
    { value: 4, label: 'Perdarahan' }
]);
const d_masalahPernikahan: any = ref([
    { value: 1, label: 'Tidak ada' },
    { value: 2, label: 'Ada' }
])
const d_mengalamiKekerasanFisik: any = ref([
    { value: 1, label: 'Tidak ada' },
    { value: 2, label: 'Ada' }
])
const d_Konjungtiva: any = ref([
    { value: 1, label: 'Merah muda' },
    { value: 2, label: 'Pucat' }
])
const d_Sklera: any = ref([
    { value: 1, label: 'Normal' },
    { value: 2, label: 'Ikterus' },
    { value: 3, label: 'Lain-lain' }
])
const d_BentukLeher: any = ref([
    { value: 1, label: 'Normal' }
])
const d_BentukDada: any = ref([
    { value: 1, label: 'Simetris' }
])
const d_iramaNafas: any = ref([
    { value: 1, label: 'Reguler' },
    { value: 2, label: 'Irreguler' }
])
const d_akral: any = ref([
    { value: 1, label: 'Hangat' },
    { value: 2, label: 'Dingin' }
])
const d_jenisNafas: any = ref([
    { value: 1, label: 'Dyspnea' },
    { value: 2, label: 'Kusmaul' },
    { value: 3, label: 'Cyene Stoke' }
])
const d_suaraNafas: any = ref([
    { value: 1, label: 'Normal' },
    { value: 2, label: 'Wheezing' },
    { value: 3, label: 'Ronchi' }
])
const d_Pergerakan: any = ref([
    { value: 1, label: 'Aktif' },
    { value: 2, label: 'Pasif' }
])
const d_kekuatanOtot: any = ref([
    { value: 1, label: 'Kuat' },
    { value: 2, label: 'Lemah' }
])
const d_crt: any = ref([
    { value: 1, label: '≤ 2detik' },
    { value: 2, label: '≥ 2detik' }
])
const d_warnaKulit: any = ref([
    { value: 1, label: 'Normal' },
    { value: 2, label: 'Ikterus' },
    { value: 3, label: 'Sianosis' },
    { value: 4, label: 'Pucat' }
])
const d_membranMukosa: any = ref([
    { value: 1, label: 'Lembab' },
    { value: 2, label: 'Kering' },
    { value: 3, label: 'Stomatitis Turgor Kulit' },
])
const d_turgorKulit: any = ref([
    { value: 1, label: 'Elastis' },
    { value: 2, label: 'Lambat' }
])
const d_DVT: any = ref([
    { value: 1, label: 'Bengkak' },
    { value: 2, label: 'Nadi bagian distal tidak teraba' }
])
const d_Penglihatan: any = ref([
    { value: 1, label: 'Baik' },
    { value: 2, label: 'Gangguan' },
    { value: 3, label: 'Buta Warna' }
])
const d_Pendengaran: any = ref([
    { value: 1, label: 'Baik' },
    { value: 2, label: 'Kurang Baik' },
    { value: 3, label: 'Alat bantu dengar' }
])
const d_Pengecapan: any = ref([
    { value: 1, label: 'Baik' },
    { value: 2, label: 'Kurang Baik' }
])
const d_Penghidu: any = ref([
    { value: 1, label: 'Baik' },
    { value: 2, label: 'Kurang Baik' }
])
const d_Perasa: any = ref([
    { value: 1, label: 'Baik' },
    { value: 2, label: 'Kurang Baik' }
])
const d_KemampuanBicara: any = ref([
    { value: 1, label: 'Baik' },
    { value: 2, label: 'Pelo' },
    { value: 3, label: 'Lainnya' }
])
const d_KemampuanMembaca: any = ref([
    { value: 1, label: 'Baik' },
    { value: 2, label: 'Buta Aksara' }
])
const d_NutrisiSusu: any = ref([
    { value: 1, label: 'ASI' },
    { value: 2, label: 'Sufor' },
    { value: 3, label: 'Lainnya' }
])
const d_KebiasaanMakan: any = ref([
    { value: 1, label: 'Reguler' },
    { value: 2, label: 'Tidak Reguler' },
    { value: 3, label: 'Diet' }
])
const d_Makanan: any = ref([
    { value: 1, label: 'Disukai' },
    { value: 2, label: 'Tidak Disukai' }
])
const d_JumlahMinum: any = ref([
    { value: 1, label: '< 8 Gelas' },
    { value: 2, label: '≥ 8 Gelas' }
])
const d_RutinitasTidur: any = ref([
    { value: 1, label: 'Rutin' },
    { value: 2, label: 'Tidak' },
    { value: 3, label: 'Lama' }
])
const d_WaktuTidur: any = ref([
    { value: 1, label: '< 6 Jam' },
    { value: 2, label: '6-8 Jam' },
    { value: 3, label: '> 8 Jam' }
])
const d_KualitasTidur: any = ref([
    { value: 1, label: 'Baik' },
    { value: 2, label: 'Sering Terbangun' }
])
const d_KesulitanBAB: any = ref([
    { value: 1, label: 'Tidak ada' },
    { value: 2, label: 'Ada darah' },
    { value: 3, label: 'Ada' }
])
const d_WarnaBAK: any = ref([
    { value: 1, label: 'Kekuningan' },
    { value: 2, label: 'Bening' },
    { value: 3, label: 'Kemerahan' },
    { value: 4, label: 'Seperti Teh' }
])
const d_KesulitanBAK: any = ref([
    { value: 1, label: 'Disuria' },
    { value: 2, label: 'Nokturia' },
    { value: 3, label: 'Inkontinensia' },
    { value: 4, label: 'Oliguria' },
    { value: 5, label: 'Unuria' }
])
const d_TaliPusat: any = ref([
    { value: 'Segar', label: 'Segar' },
    { value: 'Layu', label: 'Layu' },
    { value: 'Simpul', label: 'Simpul' }
])
const d_Placenta: any = ref([
    { value: 'Klasifikasi', label: 'Klasifikasi' },
    { value: 'Kelainan', label: 'Kelainan' }
]);
const d_EDK: any = ref([
    { value: 'Pasien', label: 'Pasien' },
    { value: 'Keluarga', label: 'Keluarga' }
]);
const d_Bicara: any = ref([
    { value: 'Normal', label: 'Normal' },
    { value: 'Serangan awal gangguan bicara', label: 'Serangan awal gangguan bicara' }
]);
const d_aktiPasif: any = ref([
    { value: 'Aktif', label: 'Aktif' },
    { value: 'Pasif', label: 'Pasif' }
]);
const d_Hambatan: any = ref([
    { value: 'Tidak ditemukan hambatan', label: 'Tidak ditemukan hambatan' },
    { value: 'Ya', label: 'Ya' }
]);
const d_KondisiSaatLahir: any = ref([
    { value: 'Segera Menangis', label: 'Segera Menangis' },
    { value: 'Tidak segera menangis', label: 'Tidak segera menangis' }
]);
const d_CapilaryRefill: any = ref([
    { value: '<2', label: '<2' },
    { value: '>2', label: '>2' }
]);
const d_kepala_PF: any = ref([
    { value: 1, label: 'Simetris' },
    { value: 2, label: 'Asimetris' },
    { value: 3, label: 'Cephal hematoma' },
    { value: 4, label: 'Caput succedanium' },
    { value: 5, label: 'Anencepali' },
    { value: 6, label: 'Microcepali' },
    { value: 7, label: 'Hydrocephalus' },
    { value: 8, label: 'Lainnya' },
])
const d_uub_PF: any = ref([
    { value: 1, label: 'Datar' },
    { value: 2, label: 'Cembung' },
    { value: 3, label: 'Cekung' },
    { value: 4, label: 'Lainnya' }
])
const d_mata_PF: any = ref([
    { value: 1, label: 'Normal' },
    { value: 2, label: 'Anemia' },
    { value: 3, label: 'Ikterus' },
    { value: 4, label: 'Sekret' },
    { value: 5, label: 'Lainnya' }
])
const d_tht_PF: any = ref([
    { value: 1, label: 'Normal' },
    { value: 2, label: 'NCH' },
    { value: 3, label: 'Sianosis' },
    { value: 4, label: 'Sekret' },
    { value: 5, label: 'Lainnya' }
])
const d_mulut_PF: any = ref([
    { value: 1, label: 'Normal' },
    { value: 2, label: 'Labioschizis' },
    { value: 3, label: 'Labiopalatoszis' },
    { value: 4, label: 'Labiogenatopalatoschizis' },
    { value: 5, label: 'Mukosa Warna' },
    { value: 6, label: 'Reflek Hisap' },
    { value: 7, label: 'Lainnya' }
])
const d_thorax_PF: any = ref([
    { value: 1, label: 'Normal' },
    { value: 2, label: 'Retraksi' },
    { value: 3, label: 'Bronchos' },
    { value: 4, label: 'Lainnya' }
])
const d_abdomen_PF: any = ref([
    { value: 1, label: 'Normal' },
    { value: 2, label: 'Distensi' },
    { value: 3, label: 'Bising usus' },
    { value: 4, label: 'Lainnya' },
    { value: 5, label: 'Kelainan' }
])
const d_talipusat_PF: any = ref([
    { value: 1, label: 'Segar' },
    { value: 2, label: 'Layu' },
    { value: 3, label: 'Simpul' }
])
const d_punggung_PF: any = ref([
    { value: 1, label: 'Normal' },
    { value: 2, label: 'Spina biflida' },
    { value: 3, label: 'Gibus' },
    { value: 4, label: 'Lainnya' }
])
const d_ekstremitas_PF: any = ref([
    { value: 1, label: 'Simetris' },
    { value: 2, label: 'Asimetris' },
    { value: 3, label: 'Reflek morro +/-' },
    { value: 4, label: 'Lainnya' }
])
const d_adatidakada: any = ref([
    { value: 1, label: 'Ada' },
    { value: 2, label: 'Tidak Ada' }
])
const d_kulit_PF: any = ref([
    { value: 1, label: 'Turgor' },
    { value: 2, label: 'Kutis marmorata' },
    { value: 3, label: 'Sianosis' },
    { value: 4, label: 'Ikterus +/- krammer' },
    { value: 5, label: 'Perdarahan' },
    { value: 6, label: 'Hematoma' },
    { value: 7, label: 'Sklerema' },
    { value: 8, label: 'Lainnya' },
])
const d_PostusTonus = ref([
    { value: 2, label: "Fleksi dan/atau kaku/tegang" },
    { value: 1, label: "Ekstensi" }
]);

const d_PolaTidur = ref([
    { value: 2, label: "Agitasi atau lemas" },
    { value: 0, label: "Relaks" }
]);

const d_Ekspresi = ref([
    { value: 2, label: "Meringis" },
    { value: 1, label: "Mengerutkan dahi" }
]);

const d_Tangis = ref([
    { value: 2, label: "Ya" },
    { value: 0, label: "Tidak" }
]);

const d_Warna = ref([
    { value: 2, label: "Pucat: kehitaman atau kemerahan" },
    { value: 0, label: "Merah muda" }
]);

const d_LajuNapas = ref([
    { value: 2, label: "Apne" },
    { value: 1, label: "Takipne" }
]);

const d_DenyutJantung = ref([
    { value: 2, label: "Fluktuasi" },
    { value: 1, label: "Takikardi" }
]);

const d_Saturasi = ref([
    { value: 2, label: "Desaturasi" },
    { value: 0, label: "Normal" }
]);

const d_TekananDarah = ref([
    { value: 2, label: "Hipo/Hipertensi" },
    { value: 0, label: "Normal" }
]);

const d_PersepsiPerawat = ref([
    { value: 2, label: "Ada nyeri" },
    { value: 0, label: "Tidak nyeri" }
]);
</script>


<style lang="scss">
h1 {
    font-weight: bold !important;
}

.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
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
    text-align: center !important;
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: bold;
    overflow: hidden;
    background-color: aquamarine;
    vertical-align: middle;
    padding: 10px 5px;
    word-break: normal;
}
</style>
