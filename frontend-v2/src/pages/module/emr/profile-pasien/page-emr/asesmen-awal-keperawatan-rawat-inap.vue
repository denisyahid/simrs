<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Asesmen Awal Keperawatan Pasien Rawat Inap</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate"
                                @kembaliKeun="kembaliKeun" :isHideCetak="true"></ButtonEmr>
                        </div>
                    </div>
                </div>

                <Dialog v-model:visible="modalRencanaPP" maximizable modal header="Asesmen Awal Medis Gawat Darurat" :style="{ width: '70vw' }">
                    <RencanaPP :nocmfk="props.registrasi.nocmfk" :norec_pd="props.registrasi.norec_pd"
                    :norec_apd="props.registrasi.norec_apd" :pasien="props.pasien" :registrasi="props.registrasi"/>
                    <template #footer>
                      <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="modalRencanaPP = false; isLoading = false">
                        Tutup
                      </VButton>
                    </template>
                  </Dialog>

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
                                                <td class="tg-0lax text-center" width="15%">Tanggal Input</td>
                                                <td class="tg-0lax text-center" width="15%">Tanggal Registrasi</td>
                                                <td class="tg-0lax text-center" width="15%">No Registrasi</td>
                                                <td class="tg-0lax text-center" width="15%">No EMR</td>
                                                <td class="tg-0lax text-center" width="20%">Dokter</td>
                                                <td class="tg-0lax text-center" width="15%">Section</td>
                                                <td class="tg-0lax text-center" width="5%">#</td>
                                            </tr>
                                        </thead>
                                        <tbody v-for="resep in listTemplate">
                                            <tr>
                                                <td style="width:15%;text-align:center">
                                                    <span class="mb-2">{{ resep.created_at }}</span><br>
                                                </td>
                                                <td style="width:15%;text-align:center">
                                                    <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                                                </td>
                                                <td style="width:15%;text-align:center">
                                                    <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                                                </td>
                                                <td style="width:15%;text-align:center">
                                                    <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                                                </td>
                                                <td style="width:20%;text-align:center">
                                                    <span class="mb-2">{{ resep.dpjpUtama }}</span><br>
                                                </td>
                                                <td style="width:15%;text-align:center">
                                                    <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                                                </td>
                                                <td style="width:5%;text-align:center">
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

                <VModal :open="showModalTemplateFix" title="Template" :noclose="true" size="large" actions="right"
                    @close="showModalTemplateFix = false">
                    <template #content>
                        <form class="modal-form">
                            <div class="column is-12 pt-0 pb-0">
                                <span style="font-size:9pt;font-weight:bold">List Template</span>
                                <div style="overflow-y:auto;" class="mt-1">
                                    <table class="tg table-tg" v-if="listTemplateFix.length > 0">
                                        <thead>
                                            <tr>
                                                <td class="tg-0lax text-center" width="5%">No</td>
                                                <td class="tg-0lax text-center" width="15%">Tanggal Dibuat</td>
                                                <td class="tg-0lax text-center" width="20%">Nama Ruangan</td>
                                                <td class="tg-0lax text-center" width="25%">Nama Template</td>
                                                <td class="tg-0lax text-center" width="15%">#</td>
                                            </tr>
                                        </thead>
                                        <tbody v-for="resep in listTemplateFix">
                                            <tr>
                                                <td style="width:5%;text-align:center">
                                                    <span class="mb-2">{{ resep.no }}</span><br>
                                                </td>
                                                <td style="width:15%;text-align:center">
                                                    <span class="mb-2">{{ resep.created_at }}</span><br>
                                                </td>
                                                <td style="width:20%;text-align:center">
                                                    <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                                                </td>
                                                <td style="width:25%;text-align:center">
                                                    <span class="mb-2">{{ resep.namatemplate }}</span><br>
                                                </td>
                                                <td style="width:15%;text-align:center">
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

                <!-- pop up renpra -->
                <div class="form-header-inner pt-3">
                    <div class="left">
                        <Dialog v-model:visible="showRenpra" maximizable modal header="Rencana Keperawatan" :style="{ width: '70vw' }">
                            <div class="column is-12" v-for="(renpra, index) in renpra.details" :key="index">
                                <div class="columns is-multiline">
                                    <div class="column is-12 pb-0">
                                        <VButtons style="justify-content:end">
                                            <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItemRenpra()" color="info"
                                            v-tooltip.bubble="'Tambah '">
                                            </VIconButton>
                                            <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                                            @click="removeItemRenpra(index)" color="danger">
                                            </VIconButton>
                                        </VButtons>
                                        <div class="columns is-multiline">
                                            <div class="column is-12">
                                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                                    <h1 style="font-weight: bold;" class="mb-3 emr">RENCANA KEPERAWATAN RAWAT INAP :</h1>
                                                <VControl>
                                                    <Multiselect v-model="renpra.rencanaKeperawatanRawatInap" placeholder="--Pilih--"
                                                    label="label" :options="RencanaKeperawatanRawatInap" :searchable="true" track-by="label"
                                                    mode="single" autocomplete="off">
                                                    </Multiselect>
                                                </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <template #footer>
                            <VButton icon="feather:save" class="rem-100 mr-4" color="info" @click="updateRenpra()" :loading="isLoading">
                                Simpan
                            </VButton>
                            <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="showRenpra = false">
                                Tutup
                            </VButton>
                            <!-- <VButton type="button" rounded outlined color="primary" class="ml-2" raised icon="feather:save"
                                :loading="isLoading" @click="simpanReal"> Simpan
                            </VButton> -->
                            </template>
                        </Dialog>
                    </div>
                </div>

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

                    <div class="column columns pb-0">
                        <div class="column is-6">
                            <h1>Tanggal Masuk</h1>
                            <VDatePicker v-model="input.DTanggalMasuk" mode="dateTime" trim-weeks
                                :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" placeholder="Tanggal masuk..." v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-6">
                            <h1>Tanggal Asesmen Awal</h1>
                            <VDatePicker v-model="input.DTanggalAsesmenAwal" mode="dateTime" trim-weeks
                                :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" placeholder="Tanggal asesmen awal..."
                                            v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                    </div>

                    <div class="columns is-multiline column pt-0 pb-0">
                        <div class="column is-4 pt-1">
                            <h1>Rujukan</h1>
                            <Multiselect v-model="input.SRujukan" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_rujukan" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-4 pt-1" v-if="input.SRujukan == 1">
                            <h1>Dari :</h1>
                            <Multiselect v-model="input.STempatRujukan" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_tempatRujukan" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-4 pt-1" v-if="input.SRujukan == 4">
                            <h1>&nbsp;</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBDiantar"
                                    placeholder="Diantar oleh..." />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-1" v-if="input.STempatRujukan == 1 && input.SRujukan == 1">
                            <h1>Rumah Sakit</h1>
                            <VControl>
                                <VInput type="text" class="input" placeholder="Rumah sakit..."
                                    v-model="input.TBRujuk_RS" />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-1" v-if="input.STempatRujukan == 2 && input.SRujukan == 1">
                            <h1>Puskesmas</h1>
                            <VControl>
                                <VInput type="text" class="input" placeholder="Puskesmas..."
                                    v-model="input.TBRujuk_Puskesmas" />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-1" v-if="input.STempatRujukan == 3 && input.SRujukan == 1">
                            <h1>dr.</h1>
                            <VControl>
                                <VInput type="text" class="input" placeholder="dr..." v-model="input.TBRujuk_dr" />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-1" v-if="input.STempatRujukan == 4 && input.SRujukan == 1">
                            <h1>Lainnya</h1>
                            <VControl>
                                <VInput type="text" class="input" placeholder="Lainnya..."
                                    v-model="input.TBRujuk_Lainnya" />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-1">
                            <h1>Dx.rujukan</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBDx_rujukan" />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-1">
                            <h1>Alloanamnesis</h1>
                            <VField class="is-autocomplete-select" v-slot="{ id }">
                                <VControl icon="feather:search">
                                    <Multiselect v-model="input.kebpilihanallo" :attrs="{ value }"
                                        placeholder="--Pilih--" label="label" :options="d_allo" :searchable="true"
                                        track-by="label" mode="single" autocomplete="off">
                                    </Multiselect>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4 pt-1" v-if="input.kebpilihanallo == 4">
                            <h1>Lainnya</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBLainnya_Allo"
                                    placeholder="Lainnya..." />
                            </VControl>
                        </div>
                    </div>

                    <div class="columns is-multiline column pb-0">
                        <div class="column is-12 pt-0 pb-0">
                            <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
                        </div>
                        <div class="column is-12">
                            <h1>ANAMNESIS</h1>
                        </div>
                        <div class="column is-6">
                            <h1>Keluhan Utama :</h1>
                            <VField>
                                <VTextarea rows="2" v-model="input.TAKeluhanUtama"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <h1>Riwayat Penyakit Sekarang :</h1>
                            <VField>
                                <VTextarea rows="2" v-model="input.TARiwayatPenyakitSekarang"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-6 pt-1">
                            <h1>Riwayat Penyakit Dahulu :</h1>
                            <VField>
                                <VTextarea rows="2" v-model="input.TARiwayatPenyakitDahulu"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-6 pt-1">
                            <h1>Riwayat Pengobatan :</h1>
                            <VField>
                                <VTextarea rows="2" v-model="input.TARiwayatPenyakitPengobatan"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-6 pt-1">
                            <h1>Riwayat Penyakit Keluarga :</h1>
                            <VField>
                                <VTextarea rows="2" v-model="input.TARiwayatPenyakitKeluarga"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-12 pt-1">
                            <div class="columns">
                                <div class="column is-4">
                                    <h1>Riwayat Alergi</h1>
                                    <Multiselect v-model="input.SRiwayatAlergi" :attrs="{ value }"
                                        placeholder="--Pilih--" label="label" :options="d_riwayatG" :searchable="true"
                                        track-by="label" mode="single" autocomplete="off">
                                    </Multiselect>
                                </div>
                                <div class="column is-8" v-if="input.SRiwayatAlergi == 2">
                                    <div class="columns">
                                        <div class="column is-4">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Obat"
                                                    label="Obat" v-model="input.CBAlergiObat" />
                                            </VControl>
                                            <VControl>
                                                <VInput type="text" class="input" v-model="input.TBAlergiObat"
                                                    placeholder="Alergi obat..." />
                                            </VControl>
                                        </div>
                                        <div class="column is-4">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Makanan"
                                                    label="Makanan" v-model="input.CBAlergiMakanan" />
                                            </VControl>
                                            <VControl>
                                                <VInput type="text" class="input" v-model="input.TBAlergiMakanan"
                                                    placeholder="Alergi makanan..." />
                                            </VControl>
                                        </div>
                                        <div class="column is-4">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Lainnya"
                                                    label="Lainnya" v-model="input.CBAlergiLainnya" />
                                            </VControl>
                                            <VControl>
                                                <VInput type="text" class="input" v-model="input.TBAlergiLainnya"
                                                    placeholder="Alergi..." />
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="column pt-0 pb-0">
                        <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                    </div>

                    <div class="column columns is-multiline">
                        <div class="columns is-multiline m-0">
                            <div class="column is-12 pb-0">
                                <h1>Status Fisik</h1>
                            </div>
                            <div class="column is-3 pb-0">
                                <h1>Keadaan Umum</h1>
                                <Multiselect v-model="input.SKeadaanUmum" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_keadaanumum" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-5">
                                <h1>GCS</h1>
                                <div class="columns">
                                    <div class="column is-4">
                                        <VField addons>
                                            <VControl class="field-addon-body">
                                                <VButton static>E</VButton>
                                            </VControl>
                                            <VControl expanded>
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.GCSe"
                                                        maxLength="1" />
                                                </VControl>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField addons>
                                            <VControl class="field-addon-body">
                                                <VButton static>V</VButton>
                                            </VControl>
                                            <VControl expanded>
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.GCSv"
                                                        maxLength="1" />
                                                </VControl>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField addons>
                                            <VControl class="field-addon-body">
                                                <VButton static>M</VButton>
                                            </VControl>
                                            <VControl expanded>
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.GCSm"
                                                        maxLength="1" />
                                                </VControl>
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-4"></div>
                            <div class="column is-3">
                                <h1>Tekanan Darah</h1>
                                <VField addons>
                                    <VControl style="width: 100%;">
                                        <VInput type="text" class="input" v-model="input.TBStekananDarah" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>mmHg</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <h1>Nadi</h1>
                                <VField addons>
                                    <VControl style="width: 100%;">
                                        <VInput type="text" class="input" v-model="input.TBSnadi" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>x/mnt</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <h1>Respirasi</h1>
                                <VField addons>
                                    <VControl style="width: 100%;">
                                        <VInput type="text" class="input" v-model="input.TBSrespirasi" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>x/mnt</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <h1>Suhu</h1>
                                <VField addons>
                                    <VControl style="width: 100%;">
                                        <VInput type="text" class="input" v-model="input.TBSsuhu" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>°C</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <h1>SaO<sub>2</sub></h1>
                                <VField addons>
                                    <VControl style="width: 100%;">
                                        <VInput type="text" class="input" v-model="input.TBSSaO2" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>%</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <h1>Berat Badan</h1>
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
                                <h1>Tinggi Badan</h1>
                                <VField addons>
                                    <VControl style="width: 100%;">
                                        <VInput type="text" class="input" v-model="input.TBStinggiBadan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>cm</VButton>
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12 pt-0 pb-0">
                        <hr style="border-top: 1px dashed red;background-color:white" class="mb-1">
                    </div>

                    <div class="column">
                        <div class="columns is-multiline">
                            <div class="column is-12 pb-0">
                                <h1>ASESMEN NYERI</h1>
                            </div>
                            <div class="column is-4">
                                <h1>skala nyeri (NRS/WBS/FLACC) dan (BPS/NPA)</h1>
                                <div class="column columns is-12 is-multiline">
                                  <div class="column is-12">
                                    <Multiselect v-model="input.SkalaNyeri" :attrs="{ label }" placeholder="--Pilih--"
                                        label="label" :options="d_skalanyeri" :searchable="true" track-by="label" mode="single"
                                        autocomplete="off" >
                                    </Multiselect>
                                  </div>
                                </div>
                                <VControl>
                                    <h1>Input skor nyeri</h1>
                                    <VInput type="text" class="input" v-model="input.TBSkalaNyeri_AN" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <h1>Lokasi</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBLokasi_AN" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <h1>Frekuensi nyeri</h1>
                                <Multiselect v-model="input.SFrekuensiNyeri_AN" :attrs="{ value }"
                                    placeholder="--Pilih--" label="label" :options="d_frekuensiNyeri_AN"
                                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-4">
                                <h1>Lama nyeri</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBLamaNyeri_AN" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <h1>Kualitas nyeri</h1>
                                <Multiselect v-model="input.SKualitasNyeri_AN" :attrs="{ value }"
                                    placeholder="--Pilih--" label="label" :options="d_kualitasNyeri_AN"
                                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-4" v-if="input.SKualitasNyeri_AN == 4">
                                <h1>&nbsp;</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBLamaNyeriLainLain_AN"
                                        placeholder="Lain-lain..." />
                                </VControl>
                            </div>
                            <div class="column is-6">
                                <h1>Faktor yang memperberat</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBFaktorYangMemperberat_AN" />
                                </VControl>
                            </div>
                            <div class="column is-6">
                                <h1>Faktor yang meringankan nyeri</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBFaktorYangMeringankan_AN" />
                                </VControl>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12 pt-0 pb-0">
                        <hr style="border-top: 1px dashed red;background-color:white" class="mb-1">
                    </div>

                    <div class="column">
                        <div class="columns is-multiline">
                            <div class="column is-12 pb-0">
                                <h1>KONDISI PSIKOLOGI, SOSIAL, EKONOMI DAN SPIRITUAL</h1>
                            </div>
                            <div class="column is-4">
                                <h1>Gangguan Psikologis</h1>
                                <Multiselect v-model="input.SKondisiPsikologis" :attrs="{ value }"
                                    placeholder="--Pilih--" label="label" :options="d_kondisiPsikologis"
                                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-4">
                                <h1>Masalah Perkawinan</h1>
                                <Multiselect v-model="input.SMasalahPernikahan" :attrs="{ value }"
                                    placeholder="--Pilih--" label="label" :options="d_masalahPernikahan"
                                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-4" v-if="input.SMasalahPernikahan == 2">
                                <h1>Jelaskan</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBMasalahPernikahan" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <h1>Mengalami kekerasan fisik</h1>
                                <Multiselect v-model="input.SMengalamiKekerasanFisik" :attrs="{ value }"
                                    placeholder="--Pilih--" label="label" :options="d_mengalamiKekerasanFisik"
                                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-4" v-if="input.SMengalamiKekerasanFisik == 2">
                                <h1>Jelaskan</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBMengalamiKekerasanFisik" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <h1>Keyakinan dan nilai pribadi</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBKeyakinanDanNilaiPribadi" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <h1>Pembiayaan Kesehatan</h1>
                                <Multiselect v-model="input.SPembiayaanKesehatan" :attrs="{ value }"
                                    placeholder="--Pilih--" label="label" :options="d_pembiayaanKesehatan"
                                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-4" v-if="input.SPembiayaanKesehatan == 2">
                                <h1>&nbsp;</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBAsuransiLainnya"
                                        placeholder="Asuransi..." />
                                </VControl>
                            </div>
                        </div>
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <h1>Kebiasaan adat istiadat yang mempengaruhi kesehatan</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBKebiasaanAdatIstiadat" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <h1>Perlu rohaniawan</h1>
                                <Multiselect v-model="input.SPerluRohaniawan" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_yaTidak" :searchable="true" track-by="label" mode="single"
                                    autocomplete="off">
                                </Multiselect>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12 pt-0 pb-0">
                        <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
                    </div>

                    <div class="column">
                        <div class="columns is-multiline">
                            <div class="column is-12 pb-0">
                                <h1>ASESMEN KEBUTUHAN INFORMASI DAN EDUKASI</h1>
                            </div>
                            <div class="column">
                                <h1>Lihat pada form kebutuhan informasi dan edukasi</h1>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12 pt-0 pb-0">
                        <hr style="border-top: 1px dashed red;background-color:white" class="mb-1">
                    </div>

                    <div class="column">
                        <div class="columns is-multiline">
                            <div class="column is-12 pb-0">
                                <h1>SKRINNING NUTRISI</h1>
                            </div>
                            <div class="column">
                                <div class="columns is-multiline">
                                    <div class="column is-4">
                                        <h1>Penurunan BB 6 bulan terakhir?</h1>
                                        <VField class="is-autocomplete-select">
                                            <VControl>
                                                <Multiselect v-model="input.penurunanbb" :attrs="{ value }"
                                                    placeholder="--Pilih--" label="label" :options="d_penurunanbb"
                                                    :searchable="true" track-by="label" mode="single"
                                                    autocomplete="off">
                                                </Multiselect>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <h1>Ya, bila ya berapa penurunan berat badan</h1>
                                        <VField class="is-autocomplete-select">
                                            <VControl>
                                                <Multiselect v-model="input.penurunanbbYa" :attrs="{ value }"
                                                    placeholder="--Pilih--" label="label" :options="d_penurunanbbYa"
                                                    :searchable="true" track-by="label" mode="single"
                                                    autocomplete="off">
                                                </Multiselect>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <h1>Terjadi penurunan nafsu makan?</h1>
                                        <VField class="is-autocomplete-select">
                                            <VControl>
                                                <Multiselect v-model="input.penurunannafsu" :attrs="{ value }"
                                                    placeholder="--Pilih--" label="label" :options="d_penurunannafsu"
                                                    :searchable="true" track-by="label" mode="single"
                                                    autocomplete="off">
                                                </Multiselect>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-8"></div>
                                    <div class="column is-4 pt-0">
                                        <h1>Nilai</h1>
                                        <VField addons>
                                            <VControl expanded>
                                                <VInput type="text" class="heightinput input" placeholder=""
                                                    v-model="input.nilaiSkrining" disabled />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-12 pt-0 pb-0">
                                        <div class="column is-12 pt-0 pb-0">
                                            <h1>Pasien dengan diagnosa khusus?</h1>
                                        </div>
                                        <div class="column is-4 columns pt-0">
                                            <div class="column is-6">
                                                <VField>
                                                    <VControl>
                                                        <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                            v-model="input.diagnosakhusus" true-value="YA" label="Ya"
                                                            color="primary" circle />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-6">
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
                                    <div class="column is-12 pt-0">
                                        <div class="column is-12 pt-0">
                                            <h1>Nilai</h1>
                                        </div>
                                        <div class="column is-12 pt-0 columns is-multiline">
                                            <div class="column is-4 p-0">
                                                <VField>
                                                    <VControl>
                                                        <VCheckbox class="fontcheckbox" v-model="input.nilai"
                                                            true-value="RISIKO RENDAH (MST 0-1)"
                                                            label="Risiko rendah (MST 0-1)" color="primary" circle
                                                            disabled />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-4 p-0">
                                                <VField>
                                                    <VControl>
                                                        <VCheckbox class="fontcheckbox" v-model="input.nilai"
                                                            true-value="RISIKO SEDANG (MST 2-3)"
                                                            label="Risiko sedang (MST 2-3)" color="primary" circle
                                                            disabled />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-4 p-0">
                                                <VField>
                                                    <VControl>
                                                        <VCheckbox class="fontcheckbox" v-model="input.nilai"
                                                            true-value="RISIKO TINGGI (MST 4-5)"
                                                            label="Risiko tinggi (MST 4-5)" color="primary" circle
                                                            disabled />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12 pt-0 pb-0">
                        <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
                    </div>

                    <div class="column">
                        <div class="columns is-multiline">
                            <div class="column is-12 pb-0">
                                <h1>STATUS FUNGSIONAL</h1>
                            </div>
                            <div class="column">
                                <div class="columns is-multiline">
                                    <div class="column is-3">
                                        <h1>Mengontrol BAB</h1>
                                        <VField class="is-autocomplete-select">
                                            <VControl>
                                                <Multiselect v-model="input.mengontrolbab" :attrs="{ value }"
                                                    placeholder="--Pilih--" label="label" :options="d_mengontrolbab"
                                                    :searchable="true" track-by="label" mode="single"
                                                    autocomplete="off">
                                                </Multiselect>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3">
                                        <h1>Mengontrol BAK</h1>
                                        <VField class="is-autocomplete-select">
                                            <VControl>
                                                <Multiselect v-model="input.mengontrolbak" :attrs="{ value }"
                                                    placeholder="--Pilih--" label="label" :options="d_mengontrolbak"
                                                    :searchable="true" track-by="label" mode="single"
                                                    autocomplete="off">
                                                </Multiselect>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3">
                                        <h1>Membersihkan diri</h1>
                                        <VField class="is-autocomplete-select">
                                            <VControl>
                                                <Multiselect v-model="input.bersihdiri" :attrs="{ value }"
                                                    placeholder="--Pilih--" label="label" :options="d_bersihdiri"
                                                    :searchable="true" track-by="label" mode="single"
                                                    autocomplete="off">
                                                </Multiselect>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3">
                                        <h1>Penggunaan toilet</h1>
                                        <VField class="is-autocomplete-select">
                                            <VControl>
                                                <Multiselect v-model="input.toilet" :attrs="{ value }"
                                                    placeholder="--Pilih--" label="label" :options="d_toilet"
                                                    :searchable="true" track-by="label" mode="single"
                                                    autocomplete="off">
                                                </Multiselect>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3 pt-0">
                                        <h1>Makan</h1>
                                        <VField class="is-autocomplete-select">
                                            <VControl>
                                                <Multiselect v-model="input.makan" :attrs="{ value }"
                                                    placeholder="--Pilih--" label="label" :options="d_makan"
                                                    :searchable="true" track-by="label" mode="single"
                                                    autocomplete="off">
                                                </Multiselect>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3 pt-0">
                                        <h1>Berpindah dari tempat tidur</h1>
                                        <VField class="is-autocomplete-select">
                                            <VControl>
                                                <Multiselect v-model="input.berpindahtt" :attrs="{ value }"
                                                    placeholder="--Pilih--" label="label" :options="d_berpindahtt"
                                                    :searchable="true" track-by="label" mode="single"
                                                    autocomplete="off">
                                                </Multiselect>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3 pt-0">
                                        <h1>Mobilisai / Berjalan</h1>
                                        <VField class="is-autocomplete-select">
                                            <VControl>
                                                <Multiselect v-model="input.mobilisasi" :attrs="{ value }"
                                                    placeholder="--Pilih--" label="label" :options="d_mobilisasi"
                                                    :searchable="true" track-by="label" mode="single"
                                                    autocomplete="off">
                                                </Multiselect>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3 pt-0">
                                        <h1>Berpakaian</h1>
                                        <VField class="is-autocomplete-select">
                                            <VControl>
                                                <Multiselect v-model="input.berpakaian" :attrs="{ value }"
                                                    placeholder="--Pilih--" label="label" :options="d_berpakaian"
                                                    :searchable="true" track-by="label" mode="single"
                                                    autocomplete="off">
                                                </Multiselect>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3 pt-0">
                                        <h1>Naik turun tangga</h1>
                                        <VField class="is-autocomplete-select">
                                            <VControl>
                                                <Multiselect v-model="input.tangga" :attrs="{ value }"
                                                    placeholder="--Pilih--" label="label" :options="d_tangga"
                                                    :searchable="true" track-by="label" mode="single"
                                                    autocomplete="off">
                                                </Multiselect>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3 pt-0">
                                        <h1>Mandi</h1>
                                        <VField class="is-autocomplete-select">
                                            <VControl>
                                                <Multiselect v-model="input.mandi" :attrs="{ value }"
                                                    placeholder="--Pilih--" label="label" :options="d_mandi"
                                                    :searchable="true" track-by="label" mode="single"
                                                    autocomplete="off">
                                                </Multiselect>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3 pt-0">
                                        <h1>Nilai</h1>
                                        <VField addons>
                                            <VControl expanded>
                                                <VInput type="text" class="heightinput input" placeholder=""
                                                    v-model="input.nilaimandi" disabled />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="columns is-multiline column is-12">
                                        <div class="column is-12 pb-0">
                                            <h1>Keterangan</h1>
                                        </div>
                                        <div class="column is-4">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Ketergantungan total (0-4)"
                                                    label="Ketergantungan total (0-4)"
                                                    v-model="input.CBStatusFungsional" disabled />
                                            </VControl>
                                        </div>
                                        <div class="column is-4">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Ketergantungan berat (5-8)"
                                                    label="Ketergantungan berat (5-8)"
                                                    v-model="input.CBStatusFungsional" disabled />
                                            </VControl>
                                        </div>
                                        <div class="column is-4">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Ketergantungan sedang (9-11)"
                                                    label="Ketergantungan sedang (9-11)"
                                                    v-model="input.CBStatusFungsional" disabled />
                                            </VControl>
                                        </div>
                                        <div class="column is-4">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Ketergantungan ringan(12-19)"
                                                    label="Ketergantungan ringan(12-19)"
                                                    v-model="input.CBStatusFungsional" disabled />
                                            </VControl>
                                        </div>
                                        <div class="column is-4">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Mandiri (20)"
                                                    label="Mandiri (20)" v-model="input.CBStatusFungsional" disabled />
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12 pt-0 pb-0">
                        <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
                    </div>

                    <div class="column">
                        <div class="columns is-multiline">
                            <div class="column is-12 pb-0">
                                <h1>ASESMEN RISIKO JATUH</h1>
                            </div>
                            <div class="column">
                                <h1>Morse Fall Scale</h1>
                                <div class="columns is-multiline">
                                    <div class="column is-4">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Resiko Rendah"
                                                label="Resiko Rendah : 0-7" v-model="input.CBResikoJatuh" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Resiko Sedang"
                                                label="Resiko Sedang : 8-13" v-model="input.CBResikoJatuh" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Resiko Tinggi"
                                                label="Resiko Tinggi : > 14" v-model="input.CBResikoJatuh" />
                                        </VControl>
                                    </div>
                                </div>
                            </div>
                            <div class="column">
                                <h1>Humty Dumpty</h1>
                                <div class="columns is-multiline">
                                    <div class="column is-4">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Resiko Rendah"
                                                label="Resiko Rendah : 7-11" v-model="input.CBResikoRendah_HD" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Resiko Tinggi"
                                                label="Resiko Tinggi : ≥ 12" v-model="input.CBResikoTinggi_HD" />
                                        </VControl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12 pt-0 pb-0">
                        <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
                    </div>

                    <div class="column">
                        <div class="columns is-multiline">
                            <div class="column is-12 pb-0">
                                <h1>RIWAYAT PENGGUNAAN OBAT</h1>
                            </div>
                            <div class="column">
                                <VField>
                                    <VTextarea rows="2" v-model="input.TARiwayatPenggunaanObat"></VTextarea>
                                </VField>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12 pt-0 pb-0">
                        <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
                    </div>

                    <div class="column">
                        <div class="columns is-multiline">
                            <div class="column is-12 pb-0">
                                <h1>RENCANA PEMULANGAN PASIEN</h1>
                            </div>
                            <div class="column">
                                <div class="columns is-multiline">
                                    <div class="column is-3">
                                        <Multiselect v-model="input.SRencanaPP" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_perluTidakPerlu"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </div>
                                    <div class="column is-3" v-for="(detail, index) in input.details" :key="index" v-if="input.SRencanaPP == 1">
                                        <Multiselect v-model="detail.SPerlu_RPP" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_Perlu_RPP"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                        <VButtons style="justify-content: space-around; margin-top: 10px;margin-left: 150px;">
                                            <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                                                        v-tooltip.bubble="'Tambah'">
                                            </VIconButton>
                                            <VIconButton class="mt-1" v-if="input.details.length > 1" type="button" raised circle
                                                        icon="feather:trash" @click="removeItem(index)" color="danger">
                                            </VIconButton>
                                        </VButtons>
                                    </div>
                                    <div class="column is-12">
                                        <VControl>
                                            <VTextarea rows="4" v-model="input.KeteranganRencanaPulang" placeholder="Keterangan..."></VTextarea>
                                        </VControl>
                                    </div>
                                    
                                    <div class="column is-12 pt-0 pb-0"></div>
                                    <!-- <div class="column is-6">
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBRPP_1"
                                                placeholder="1..." />
                                        </VControl>
                                    </div>
                                    <div class="column is-6">
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBRPP_2"
                                                placeholder="2..." />
                                        </VControl>
                                    </div>
                                    <div class="column is-6">
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBRPP_3"
                                                placeholder="3..." />
                                        </VControl>
                                    </div>
                                    <div class="column is-6">
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBRPP_4"
                                                placeholder="4..." />
                                        </VControl>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12 pt-0 pb-0">
                        <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
                    </div>

                    <div class="column">
                        <div class="columns is-multiline">
                            <div class="column is-12 pb-0">
                                <h1>DIAGNOSA KEPERAWATAN</h1>
                            </div>
                            <div class="column is-12 pb-0">
                                <VField label="Diagnosa">
                                    <VTextarea rows="2" v-model="input.Diagnosa"></VTextarea>
                                </VField>
                            </div>
                            <div class="column is-12 pb-0">
                                <VField label="Berhubungan Dengan (B.D)">
                                    <VTextarea rows="2" v-model="input.DiagnosaBD"></VTextarea>
                                </VField>
                            </div>
                            <div class="column is-12 pb-0">
                                <VField label="Ditandai Dengan (D.D)">
                                    <VTextarea rows="2" v-model="input.DiagnosaDD"></VTextarea>
                                </VField>
                            </div>
                            <!-- <div class="column is-12" v-for="(dt,index) in input.TADiagnosaKeperawatan">
                                <div class="columns is-multiline">
                                    <div class="column is-1">
                                        <label>{{ index + 1 }}.</label>
                                    </div>
                                    <div class="column is-11">
                                        <VField>
                                            
                                            <VTextarea rows="4" v-model="dt['TADiagnosaKeperawatan']"></VTextarea>
                                        </VField>
                                    </div>
                                </div>
                            </div> -->
                            <!-- <VButton type="button" rounded outlined color="info" @click="setRenpra()" icon="lucide:file-text">
                            Rencana Keperawatan
                            </VButton> -->
                            <div class="column is-2 mt-5">
                                <!-- <VButtons>
                                    <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                                        v-tooltip.bubble="'Tambah '">
                                    </VIconButton>
                                    <VIconButton v-if="index > 0" class="mt-1" type="button" raised circle icon="feather:trash"
                                        @click="removeItem(index)" color="danger">
                                    </VIconButton>
                                </VButtons> -->
                            </div>
                        </div>
                    </div>

                    <div class="column is-12 pt-0 pb-0">
                        <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
                    </div>

                    <div class="column">
                        <div class="columns is-multiline">
                            <div class="column is-12 pb-0">
                                <h1>RENCANA KEPERAWATAN</h1>
                            </div>
                            <div class="column">
                                <h1>Lihat pada form Rencana keperawatan</h1>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12 pt-0 pb-0">
                        <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
                    </div>

                    <div class="column">
                        <div class="columns is-multiline">
                            <div class="column is-12 pb-0">
                                <h1>PROSEDUR INVASIF</h1>
                            </div>
                            <div class="column">
                                <div class="columns">
                                    <div class="column is-4">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Infus Intra Vena"
                                                label="Infus Intra Vena" v-model="input.CBInfusIntraVena" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <h1>Dipasang di</h1>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBInfusIntraVena" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <h1>Tanggal</h1>
                                        <VDatePicker v-model="input.DInfusIntraVena" mode="date" trim-weeks
                                            :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" v-on="inputEvents" />
                                                </VControl>
                                            </template>
                                        </VDatePicker>
                                    </div>
                                </div>
                                <div class="columns">
                                    <div class="column is-4">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square
                                                true-value="Central Line (CVC)" label="Central Line (CVC)"
                                                v-model="input.CBCentralLine" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <h1>Dipasang di</h1>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBCentralLine" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <h1>Tanggal</h1>
                                        <VDatePicker v-model="input.DCentralLine" mode="date" trim-weeks
                                            :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" v-on="inputEvents" />
                                                </VControl>
                                            </template>
                                        </VDatePicker>
                                    </div>
                                </div>
                                <div class="columns">
                                    <div class="column is-4">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Dower chateter"
                                                label="Dower chateter" v-model="input.CBDowerChateter" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <h1>Dipasang di</h1>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBDowerChateter" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <h1>Tanggal</h1>
                                        <VDatePicker v-model="input.DDowerChateter" mode="date" trim-weeks
                                            :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" v-on="inputEvents" />
                                                </VControl>
                                            </template>
                                        </VDatePicker>
                                    </div>
                                </div>
                                <div class="columns">
                                    <div class="column is-4">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Selang NGT"
                                                label="Selang NGT" v-model="input.CBSelangNGT" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <h1>Dipasang di</h1>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBSelangNGT" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <h1>Tanggal</h1>
                                        <VDatePicker v-model="input.DSelangNGT" mode="date" trim-weeks
                                            :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" v-on="inputEvents" />
                                                </VControl>
                                            </template>
                                        </VDatePicker>
                                    </div>
                                </div>
                                <div class="columns">
                                    <div class="column is-4">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Trakeostomy"
                                                label="Trakeostomy" v-model="input.CBTrakeostomy" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <h1>Dipasang di</h1>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBTrakeostomy" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <h1>Tanggal</h1>
                                        <VDatePicker v-model="input.DTrakeostomy" mode="date" trim-weeks
                                            :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" v-on="inputEvents" />
                                                </VControl>
                                            </template>
                                        </VDatePicker>
                                    </div>
                                </div>
                                <div class="columns">
                                    <div class="column is-4">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="EET/Ventilator"
                                                label="EET/Ventilator" v-model="input.CBVentilator" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <h1>Dipasang di</h1>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBVentilator" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <h1>Tanggal</h1>
                                        <VDatePicker v-model="input.DVentilator" mode="date" trim-weeks
                                            :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" v-on="inputEvents" />
                                                </VControl>
                                            </template>
                                        </VDatePicker>
                                    </div>
                                </div>
                                <div class="columns">
                                    <div class="column is-4">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Lain-lain"
                                                label="Lain-lain" v-model="input.CBLainlain_PI" />
                                        </VControl>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBLainlain_PI" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <h1>Dipasang di</h1>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBLainlain_dipasang_PI" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <h1>Tanggal</h1>
                                        <VDatePicker v-model="input.DLainlain_PI" mode="date" trim-weeks
                                            :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" v-on="inputEvents" />
                                                </VControl>
                                            </template>
                                        </VDatePicker>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12 pt-0">
                        <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
                    </div>

                    <div class="columns">
                        <div class="column is-8"></div>
                        <div class="column is-4">
                            <VField label="Garut">
                                <VDatePicker v-model="input.DTttd" mode="datetime" trim-weeks :max-date="new Date()">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                        </VControl>
                                    </template>
                                </VDatePicker>
                            </VField>
                            <div class="column" style="text-align:center;">
                                <h1>Tanda Tangan Perawat</h1>
                                <TandaTangan :elemenID="'TTDPerawat'" :width="'150'" :height="'150'" class="dek" />
                                <VControl class="prime-auto">
                                    <AutoComplete v-model="input.DDPerawat" :suggestions="d_Pegawai"
                                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                        :field="'label'" class="mt-2" />
                                </VControl>
                            </div>
                        </div>
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
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import * as EMR2 from "../page-emr-plugins/rencana-keperawatan-ranap";
console.log("AYAAAAAA",EMR2.RencanaKeperawatanRawatInap());

import Dialog from 'primevue/dialog';
import RencanaPP from "../page-emr/perencanaan-pulang-ranap.vue";

useHead({ title: 'Asesmen Awal Keperawatan Pasien Rawat Inap - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let RencanaKeperawatanRawatInap: any = ref(EMR2.RencanaKeperawatanRawatInap())
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
const showRenpra: any = ref(false)
const isLoading = ref(false)
const isAktive = ref()
const router = useRouter()
const selectedRegistrasi: any = ref({})
const isRemoveTAB: any = ref(false)
const TAB_ACTIVE: any = ref('Dashboard');
const TAB_URL = ref('')
const NAMA_RUANGAN: any = ref()
const modalRencanaPP = ref(false)
const TAB_ACTIVE_ROUTER: any = ref(null)
const TAB_ROUTER_DEFAULT = ref('module-emr-profile-pasien-page-emr-not-found')
const COLLECTION: any = ref('AsesmenAwalKeperawatanRawatInap') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const d_skalanyeri: any = ref([{ value: 'NRS', label: 'NRS' }, { value: 'WBS', label: 'WBS' }, { value: 'FLACC', label: 'FLACC' }, {value: 'BPS', label: 'BPS' }, { value: 'NPA', label: 'NPA' }])
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

NAMA_RUANGAN.value = route.query.nama_ruangan as string ?? props.registrasi.namaruangan;
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
    DTttd: new Date(),
    DTanggalAsesmenAwal: new Date(),
    TADiagnosaKeperawatan: [
        {
            no: 1,
        }
    ],
    details: [{ SPerlu_RPP: [] }]
})

const renpra: any = ref({
  tanggal: new Date(),
  details: [{
    no: 1,
  }]
});

const addNewDPJP = () => {
  input.value.TADiagnosaKeperawatan.push({
    no: input.value.TADiagnosaKeperawatan[input.value.TADiagnosaKeperawatan.length - 1].no + 1,
  });
}
const removeDPJP = (index: any) => {
  input.value.TADiagnosaKeperawatan.splice(index, 1)
}

const addNewItem = () => {
  input.value.details.push({ selectedOptions: [] });
};

// Fungsi menghapus Multiselect tertentu
const removeItem = (index) => {
  input.value.details.splice(index, 1);
};

const addNewItemRenpra = () => {
  renpra.value.details.push({ selectedOptions: [] });
};

// Fungsi menghapus Multiselect tertentu
const removeItemRenpra = (index) => {
  renpra.value.details.splice(index, 1);
};

const loadRiwayat = async () => {
    isLoading.value = true
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&namaruangan=${NAMA_RUANGAN.value}`)
    if (response.length) {
        input.value = response[0] //set ke inputan
        console.log("ini 5", input.value.pasien);
        console.log("ini 6", input.value.registrasi);
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        if (typeof input.value.TADiagnosaKeperawatan === "string") {
            input.value.TADiagnosaKeperawatan = input.value.TADiagnosaKeperawatan
                .split("\n") 
                .map(text => ({ TADiagnosaKeperawatan: text.trim() })); // Ubah ke objek agar sesuai dengan v-model
        }
        // const renpra = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=RencanaKeperawatanRawatInap" + `&field=details&ruangan=${NAMA_RUANGAN.value}`)
        // if (renpra && renpra.details && renpra.details.length > 0) {
        //     let bdModels = [
        //     "penurunanIramaJantung","perubahanFrekuensiJantung","perubahanKontraktilitas","perubahanPreload","perubahanAfterload","efekKetidakmampuanFisik","keterbatasanLingkungan","inkonsistensiRespon","pengabaian",
        //     "terpisahDariOrangTuaAtauOrangTerdekat","defisiensiStimulus","hipoksemia","hipoksia","hipotensi","kekuranganVolumeCairan","sepsisDanSIRS","inflamasiGastrointestinal","iritasiGastrointestinal","prosesInfeksi","malabsorbsi","kecemasan","terpaparKontaminanToxin","programPengobatan","penyalahgunaanLaksatifZat",
        //     "perubahanAirMakanan","bakteriPadaAir","Dilatasiserviks","PengeluaranJanin","KetidakseimbangancairanRKE","KelebihanvolumeairRKE","GangguanmekanismeregulasiRKE","EfeksampingprosedurpembedahanRKE","DiareRKE","MuntahRKE","DisfungsiginjalRKE","DisfungsiendokrinRKE","GagalginjalRKE","AnoreksianervosaRKE","DiabetesmellitusRKE","PenyakitCrohnRKE","GastroenteritisRKE","PankreatitisRKE","CederaKepalaRKE",
        //     "KankerRKE","TraumamultipelRKE","LukabakarRKE","AnemiaselsabitRKE","ProsedurpembedahanmayorRKC","TraumaperdarahanRKC","LukabakarRKC","AferesisRKC","AsitesRKC","ObstruksiintestinalRKC","PeradanganpankreasRKC","PenyakitginjalandanKelenjarRKC","DisfungsiintestinalRKC","ProsedurpembedahanMayorRKC","PenyakitGinjalDanKelenjarRKC","PerdarahanRKC","LukaBakarRKC","PenurunanBeratBadanAbnormalRIN","PolaMakanTidakBaikRIN","KesulitanTransisiEkstraUterinRIN","UsiaKurangDari7HariRIN","KeterlambatanPengeluaranFesesRIN",
        //     "PrematuritasKurang37MingguRIN","NeonatusRIN","BayiPrematurRIN","MakananAlergenRA","ZatAlergenRA","AlergenLingkunganRA","SengatanSeranggaRA","PenurunanImunitasRA","RiwayatPembedahanRA","RiwayatAlergiRA","AsmaRA","GangguanTidurK","GayaHidupMonotonK","KondisiFisiologisK","ProgramPerawatanJangkaPanjangK","PeristiwaHidupNegatifK","StresBerlebihanK","DepresiK","HambatanLingkunganGPT","KurangKontrolTidurGPT","KurangPrivasiGPT",
        //     "RestraintFisikGPT", "KetiadaanTemanTidurGPT", "TidakFamiliarPeralatanTidurGPT",
        //     "StimulasiIntelektualGM", "SirkulasiOtakGM", "CairanElektrolitGM", "ProsesPenuaanGM",
        //     "HipoksiaGM", "GangguanNeurologisGM", "AgenFarmakologisGM", "PenyalahgunaanZatGM",
        //     "FaktorPsikologisGM", "DistraksiLingkunganGM", "GangguanPenglihatanGPS",
        //     "GangguanPendengaranGPS", "GangguanPenghiduanGPS", "GangguanPerabaanGPS",
        //     "HipoksiaSerebralGPS", "PenyalahgunaanZatGPS", "UsiaLanjutGPS",
        //     "PemajananToksinLingkunganGPS", "GangguanPerilakuRBD", "DemografiRisikoRBD",
        //     "GangguanFisikRBD", "MasalahSosialRBD", "GangguanPsikologisRBD", "SkorASA3RPPP",
        //     "HiperglikemiaRPPP", "EdemaPembedahanRPPP", "ProsedurEkstensifRPPP", "UsiaEkstremRPPP",
        //     "RiwayatPenyembuhanLukaRPPP", "GangguanMobilitasRPPP", "MalnutrisiRPPP",
        //     "ObesitasRPPP", "InfeksiLukaPerioperatifRPPP", "MualMuntahPersistenRPPP",
        //     "ResponEmosionalPascaOperasiRPPP", "PemanjanganProsesOperasiRPPP",
        //     "GangguanPsikologisPascaOperasiRPPP", "KontaminasiBedahRPPP", "TraumaLukaOperasiRPPP",
        //     "EfekAgenFarmakologisRPPP", "SkorBradenRLT", "PerubahanFungsiKognitifRLT",
        //     "PerubahanSensasiRLT", "SkorASARLT", "AnemiaRLT", "PenurunanMobilisasiRLT",
        //     "PenurunanAlbuminRLT", "PenurunanOksigenasiJaringanRLT", "PenurunanPerfusiJaringanRLT",
        //     "DehidrasiRLT", "KulitKentangRLT", "EdemaRLT", "PeningkatanSuhuKulitRLT",
        //     "ImobilisasiLamaRLT", "UsiaLanjutRLT", "BeratBadanLebihRLT", "FrakturTungkaiRLT",
        //     "RiwayatStrokeRLT", "RiwayatLukaTekanRLT", "RiwayatTraumaRLT", "HipertermiRLT",
        //     "InkontinensiaRLT", "KetidakadekuatanNutrisiRLT", "SkorRAPSRLT",
        //     "KlasifikasiNYHARLT", "EfekAgenFarmakologisRLT", "ImobilisasiFisikRLT",
        //     "PenekananTulangRLT", "PenurunanLipatanKulitRLT", "KulitBersisikRLT",
        //     "GesekanKulitRLT", "Usia65Atau2TahunRJ", "RiwayatJatuhRJ",
        //     "ProsthesisAnggotaGerakBawahRJ", "AlatBantuBerjalanRJ", "PenurunanKesadaranRJ",
        //     "PerubahanFungsiKognitifRJ", "LingkunganTidakAmanRJ", "KondisiPascaOperasiRJ",
        //     "HipotensiOrtostatikRJ", "PerubahanGlukosaDarahRJ", "AnemiaRJ",
        //     "KekuatanOtotMenurunRJ", "GangguanPendengaranRJ", "GangguanKeseimbanganRJ",
        //     "GangguanPengelihatanRJ", "NeuropatiRJ", "EfekFarmakologisRJ",
        //     "PenurunanMotilitasGIK", "KetidakcukupanDietK", "KetidakcukupanSeratK",
        //     "KetidakcukupanCairanK", "AganglionikK", "KelemahanOtotAbdomenK",
        //     "GangguanEmosionalK", "AktivitasFisikKurangK", "EfekAgenFarmakologisK",
        //     "KetidakteraturanDefekasiK", "PerubahanLingkunganK", "KetidakadekuatanSuplaiASIMTE",
        //     "HambatanPadaNeonatusMTE", "AnomaliPayudaraIbuMTE", "KetidakadekuatanRefleksOksitosinMTE", "KetidakadekuatanRefleksMenghisapBayiMTE", "PayudaraBengkakMTE", "RiwayatOperasiPayudaraMTE", "NyeriPostOperasiSectioCaesariaMTE", "KelahiranKembarMTE", "TidakRawatGabungMTE", "KurangTerpaparInformasiMenyusuiMTE",  
        //     "KurangnyaDukunganKeluargaMTE", "FaktorBudayaMTE", "AneurismaRP", "GangguanGastrointestinalRP", "GangguanFungsiHatiRP", "KomplikasiKehamilanRP", "KomplikasiPascaPartumRP", "GangguanKoagulasiRP", "EfekFarmakologisRP", "TindakanPembedahanRP",  
        //     "TraumaRP", "ProsesKeganasanRP", "KurangTerpaparInformasiRP", "KurangTerpaparInformasiRP", "DepresiPusatPernapasanPNTE", "HambatanUpayaNapasPNTE", "DeformitasDindingDadaPNTE", "DeformitasTulangDadaPNTE", "GangguanNeuromuscularPNTE", "GangguanNeurologisPNTE",  
        //     "PenurunanEnergiPNTE", "ObesitasPNTE", "KerusakanSarafC5KeAtasPNTE", "CederaMedullaSpinalisPNTE", "KecemasanPNTE", "hiperglikemiaRPPTE", "gayaHidupKurangGerakRPPTE", "hipertensiRPPTE", "merokokRPPTE", "prosedurEndovaskulerRPPTE",  
        //     "traumaRPPTE", "kurangTerpaparInformasiFaktorPemberatRPPTE", "LesiAkibatTumorAtauAbsesPKAI", "GangguanMetabolismePKAI", "EdemaSerebralPKAI", "PeningkatanTekananVenaPKAI", "HidrosefalusPKAI", "HipertensiIntrakranialIdiopatikPKAI", "TraumaPerineumKPP", "InvolusiUterusKPP",  
        //     "PembengkakanPayudaraKPP", "KurangDukunganKeluargaTenagaKesehatanKPP", "PosisiDudukTidakTepatKPP", "FaktorBudayaKPP", "KondisiMuskuloskeletalKronisNK", "KerusakanSistemSarafNK", "PenekananSarafNK", "InfiltrasiTumorNK", "KetidakseimbanganNeurotransmitterNK", "GangguanImunitasNK",  
        //     "GangguanFungsiMetabolikNK", "RiwayatPosisiKerjaStatisNK", "PeningkatanIMTNK", "KondisiPascaTraumaNK", "TekananEmosionalNK", "RiwayatPenganiayaanNK", "RiwayatPenyalahgunaanObatNK", "AgenPencederaFisiologisNA", "AgenPencederaKimiawiNA", "AgenPencederaFisikNA",  
        //     "GangguanBiokimiawiN", "GangguanEsofagusN", "DistensiIritasiLambungN", "GangguanPankreasN", "PereganganKapsulLimpaN", "TumorTerlokalisasiN", "PeningkatanTekananIntraAbdominalN", "PeningkatanTIKN", "PeningkatanTekananIntraorbitalN", "FaktorPsikologisN",  
        //     "EfekAgenFarmakologisN", "EfekToksinN", "KehamilanN", "DeliriumKA", "DemensiaKA", "FluktuasiTidurBangunKA", "UsiaLebih60KA", "PenyalahgunaanZatKA", "DisfungsiPankreasKKGD", "ResistensiInsulinKKGD"
        //     ];

        //     let data = renpra.details.map((item, index) => {
        //         let diagnosis = `${item.rencanaKeperawatanRawatInap} Berhubungan `;

        //         let getDiagnosaFunction = EMR2[item.rencanaKeperawatanRawatInap.replace(/\s+/g, "")];
        //         let diagnosaKeperawatan = getDiagnosaFunction ? getDiagnosaFunction() : [];

        //         // Normalisasi teks untuk pencocokan fuzzy
        //         let inputDiagnosis = item.rencanaKeperawatanRawatInap.toLowerCase().trim();

        //         console.log(diagnosaKeperawatan);
                
        //         let matchingDiagnosa = diagnosaKeperawatan.filter(d => 
        //             d.keteranganTambahan.toLowerCase().trim().includes(inputDiagnosis) || 
        //             inputDiagnosis.includes(d.keteranganTambahan.toLowerCase().trim())
        //         );

        //         if (!matchingDiagnosa) {
        //             console.warn(`Tidak ditemukan diagnosis yang cocok untuk: ${item.rencanaKeperawatanRawatInap}`);
        //             return diagnosis; // Lewati proses jika tidak ada kecocokan
        //         }

        //         let kondisiKeys = Object.keys(item).filter(key => key !== "rencanaKeperawatanRawatInap" && key !== "no" && item[key]);

        //         let bdKondisi = "";
        //         let ddKondisiList = [];

        //         let detailList = matchingDiagnosa
        //         .filter(d => d.labelDiagnosaKeperawatan === "Diagnosa Keperawatan")
        //         .flatMap(d => d.detailDiagnosaKeperawatan || []);

        //         kondisiKeys.forEach((key) => {
        //             let matchingDetail = detailList.find(detail => detail.model === key);
                    
        //             if (matchingDetail) {
        //                 let labelDetail = matchingDetail.labelDetail.replace(/^\d+\.\s*/, ""); // Hilangkan nomor di awal
        //                 if (bdModels.includes(key)) {
        //                     bdKondisi += `dengan (b.d) ${labelDetail} `;
        //                 } else {
        //                     ddKondisiList.push(labelDetail);
        //                 }
        //             }
        //         });

        //         let ddKondisi = "";
        //         if (ddKondisiList.length > 0) {
        //             ddKondisi = `dengan (d.d) ${ddKondisiList[0]}`; // Elemen pertama dengan "dengan (d.d)"
        //             if (ddKondisiList.length > 1) {
        //                 ddKondisi += " " + ddKondisiList.slice(1).map(item => `dan ${item}`).join(" ");
        //             }
        //         }
        //         return diagnosis + bdKondisi.trim() + " " + ddKondisi.trim();
        //     });
        //     // Menyimpan ke input berdasarkan index
        //   // Konversi ke format array objek agar cocok dengan v-model di v-for
        //     input.value.TADiagnosaKeperawatan = data.map(text => ({
        //         TADiagnosaKeperawatan: text || "Data tidak tersedia" // Hindari null
        // }));

        //     }
        dataTTD.value = response[0]
        H.tandaTangan().set("TTDPerawat", dataTTD.value.TTDPerawat)
    } else {
        input.value.DDPerawat = { label: user.namaLengkap, value: user.id }
        const ttv = await useApi().get(`emr/get-data-exist?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}`)
        const rj = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=PengkajianResikoJatuhDewasa" + `&field=jumlahNilaiSN`)
        // const renpra = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=RencanaKeperawatanRawatInap" + `&field=details&ruangan=${NAMA_RUANGAN.value}`)
        if (ttv) {
            input.value.GCSe = ttv.GCSe;
            input.value.GCSv = ttv.GCSv;
            input.value.GCSm = ttv.GCSm;
            input.value.TBStekananDarah = ttv.tekananDarah;
            input.value.TBSnadi = ttv.nadi
            input.value.TBSrespirasi = ttv.pernapasan
            input.value.TBSsuhu = ttv.suhu
            input.value.TBSSaO2 = ttv.SPO2
            input.value.TBSBeratBadan = ttv.beratBadan
            input.value.TBStinggiBadan = ttv.tinggiBadan
        }

        if (rj) {
            let data = rj.jumlahNilaiSN;
            if (data <= 7 && data >= 0) {
                input.value.CBResikoJatuh = 'Resiko Rendah'
            } else if (data <= 13 && data >= 8) {
                input.value.CBResikoJatuh = 'Resiko Sedang'
            } else if (data >= 14) {
                input.value.CBResikoJatuh = 'Resiko Tinggi'
            }
        }


    //     if (renpra && renpra.details && renpra.details.length > 0) {
    //         let bdModels = [
    //         "penurunanIramaJantung","perubahanFrekuensiJantung","perubahanKontraktilitas","perubahanPreload","perubahanAfterload","efekKetidakmampuanFisik","keterbatasanLingkungan","inkonsistensiRespon","pengabaian",
    //         "terpisahDariOrangTuaAtauOrangTerdekat","defisiensiStimulus","hipoksemia","hipoksia","hipotensi","kekuranganVolumeCairan","sepsisDanSIRS","inflamasiGastrointestinal","iritasiGastrointestinal","prosesInfeksi","malabsorbsi","kecemasan","terpaparKontaminanToxin","programPengobatan","penyalahgunaanLaksatifZat",
    //         "perubahanAirMakanan","bakteriPadaAir","Dilatasiserviks","PengeluaranJanin","KetidakseimbangancairanRKE","KelebihanvolumeairRKE","GangguanmekanismeregulasiRKE","EfeksampingprosedurpembedahanRKE","DiareRKE","MuntahRKE","DisfungsiginjalRKE","DisfungsiendokrinRKE","GagalginjalRKE","AnoreksianervosaRKE","DiabetesmellitusRKE","PenyakitCrohnRKE","GastroenteritisRKE","PankreatitisRKE","CederaKepalaRKE",
    //         "KankerRKE","TraumamultipelRKE","LukabakarRKE","AnemiaselsabitRKE","ProsedurpembedahanmayorRKC","TraumaperdarahanRKC","LukabakarRKC","AferesisRKC","AsitesRKC","ObstruksiintestinalRKC","PeradanganpankreasRKC","PenyakitginjalandanKelenjarRKC","DisfungsiintestinalRKC","ProsedurpembedahanMayorRKC","PenyakitGinjalDanKelenjarRKC","PerdarahanRKC","LukaBakarRKC","PenurunanBeratBadanAbnormalRIN","PolaMakanTidakBaikRIN","KesulitanTransisiEkstraUterinRIN","UsiaKurangDari7HariRIN","KeterlambatanPengeluaranFesesRIN",
    //         "PrematuritasKurang37MingguRIN","NeonatusRIN","BayiPrematurRIN","MakananAlergenRA","ZatAlergenRA","AlergenLingkunganRA","SengatanSeranggaRA","PenurunanImunitasRA","RiwayatPembedahanRA","RiwayatAlergiRA","AsmaRA","GangguanTidurK","GayaHidupMonotonK","KondisiFisiologisK","ProgramPerawatanJangkaPanjangK","PeristiwaHidupNegatifK","StresBerlebihanK","DepresiK","HambatanLingkunganGPT","KurangKontrolTidurGPT","KurangPrivasiGPT",
    //         "RestraintFisikGPT", "KetiadaanTemanTidurGPT", "TidakFamiliarPeralatanTidurGPT",
    //         "StimulasiIntelektualGM", "SirkulasiOtakGM", "CairanElektrolitGM", "ProsesPenuaanGM",
    //         "HipoksiaGM", "GangguanNeurologisGM", "AgenFarmakologisGM", "PenyalahgunaanZatGM",
    //         "FaktorPsikologisGM", "DistraksiLingkunganGM", "GangguanPenglihatanGPS",
    //         "GangguanPendengaranGPS", "GangguanPenghiduanGPS", "GangguanPerabaanGPS",
    //         "HipoksiaSerebralGPS", "PenyalahgunaanZatGPS", "UsiaLanjutGPS",
    //         "PemajananToksinLingkunganGPS", "GangguanPerilakuRBD", "DemografiRisikoRBD",
    //         "GangguanFisikRBD", "MasalahSosialRBD", "GangguanPsikologisRBD", "SkorASA3RPPP",
    //         "HiperglikemiaRPPP", "EdemaPembedahanRPPP", "ProsedurEkstensifRPPP", "UsiaEkstremRPPP",
    //         "RiwayatPenyembuhanLukaRPPP", "GangguanMobilitasRPPP", "MalnutrisiRPPP",
    //         "ObesitasRPPP", "InfeksiLukaPerioperatifRPPP", "MualMuntahPersistenRPPP",
    //         "ResponEmosionalPascaOperasiRPPP", "PemanjanganProsesOperasiRPPP",
    //         "GangguanPsikologisPascaOperasiRPPP", "KontaminasiBedahRPPP", "TraumaLukaOperasiRPPP",
    //         "EfekAgenFarmakologisRPPP", "SkorBradenRLT", "PerubahanFungsiKognitifRLT",
    //         "PerubahanSensasiRLT", "SkorASARLT", "AnemiaRLT", "PenurunanMobilisasiRLT",
    //         "PenurunanAlbuminRLT", "PenurunanOksigenasiJaringanRLT", "PenurunanPerfusiJaringanRLT",
    //         "DehidrasiRLT", "KulitKentangRLT", "EdemaRLT", "PeningkatanSuhuKulitRLT",
    //         "ImobilisasiLamaRLT", "UsiaLanjutRLT", "BeratBadanLebihRLT", "FrakturTungkaiRLT",
    //         "RiwayatStrokeRLT", "RiwayatLukaTekanRLT", "RiwayatTraumaRLT", "HipertermiRLT",
    //         "InkontinensiaRLT", "KetidakadekuatanNutrisiRLT", "SkorRAPSRLT",
    //         "KlasifikasiNYHARLT", "EfekAgenFarmakologisRLT", "ImobilisasiFisikRLT",
    //         "PenekananTulangRLT", "PenurunanLipatanKulitRLT", "KulitBersisikRLT",
    //         "GesekanKulitRLT", "Usia65Atau2TahunRJ", "RiwayatJatuhRJ",
    //         "ProsthesisAnggotaGerakBawahRJ", "AlatBantuBerjalanRJ", "PenurunanKesadaranRJ",
    //         "PerubahanFungsiKognitifRJ", "LingkunganTidakAmanRJ", "KondisiPascaOperasiRJ",
    //         "HipotensiOrtostatikRJ", "PerubahanGlukosaDarahRJ", "AnemiaRJ",
    //         "KekuatanOtotMenurunRJ", "GangguanPendengaranRJ", "GangguanKeseimbanganRJ",
    //         "GangguanPengelihatanRJ", "NeuropatiRJ", "EfekFarmakologisRJ",
    //         "PenurunanMotilitasGIK", "KetidakcukupanDietK", "KetidakcukupanSeratK",
    //         "KetidakcukupanCairanK", "AganglionikK", "KelemahanOtotAbdomenK",
    //         "GangguanEmosionalK", "AktivitasFisikKurangK", "EfekAgenFarmakologisK",
    //         "KetidakteraturanDefekasiK", "PerubahanLingkunganK", "KetidakadekuatanSuplaiASIMTE",
    //         "HambatanPadaNeonatusMTE", "AnomaliPayudaraIbuMTE", "KetidakadekuatanRefleksOksitosinMTE", "KetidakadekuatanRefleksMenghisapBayiMTE", "PayudaraBengkakMTE", "RiwayatOperasiPayudaraMTE", "NyeriPostOperasiSectioCaesariaMTE", "KelahiranKembarMTE", "TidakRawatGabungMTE", "KurangTerpaparInformasiMenyusuiMTE",  
    //         "KurangnyaDukunganKeluargaMTE", "FaktorBudayaMTE", "AneurismaRP", "GangguanGastrointestinalRP", "GangguanFungsiHatiRP", "KomplikasiKehamilanRP", "KomplikasiPascaPartumRP", "GangguanKoagulasiRP", "EfekFarmakologisRP", "TindakanPembedahanRP",  
    //         "TraumaRP", "ProsesKeganasanRP", "KurangTerpaparInformasiRP", "KurangTerpaparInformasiRP", "DepresiPusatPernapasanPNTE", "HambatanUpayaNapasPNTE", "DeformitasDindingDadaPNTE", "DeformitasTulangDadaPNTE", "GangguanNeuromuscularPNTE", "GangguanNeurologisPNTE",  
    //         "PenurunanEnergiPNTE", "ObesitasPNTE", "KerusakanSarafC5KeAtasPNTE", "CederaMedullaSpinalisPNTE", "KecemasanPNTE", "hiperglikemiaRPPTE", "gayaHidupKurangGerakRPPTE", "hipertensiRPPTE", "merokokRPPTE", "prosedurEndovaskulerRPPTE",  
    //         "traumaRPPTE", "kurangTerpaparInformasiFaktorPemberatRPPTE", "LesiAkibatTumorAtauAbsesPKAI", "GangguanMetabolismePKAI", "EdemaSerebralPKAI", "PeningkatanTekananVenaPKAI", "HidrosefalusPKAI", "HipertensiIntrakranialIdiopatikPKAI", "TraumaPerineumKPP", "InvolusiUterusKPP",  
    //         "PembengkakanPayudaraKPP", "KurangDukunganKeluargaTenagaKesehatanKPP", "PosisiDudukTidakTepatKPP", "FaktorBudayaKPP", "KondisiMuskuloskeletalKronisNK", "KerusakanSistemSarafNK", "PenekananSarafNK", "InfiltrasiTumorNK", "KetidakseimbanganNeurotransmitterNK", "GangguanImunitasNK",  
    //         "GangguanFungsiMetabolikNK", "RiwayatPosisiKerjaStatisNK", "PeningkatanIMTNK", "KondisiPascaTraumaNK", "TekananEmosionalNK", "RiwayatPenganiayaanNK", "RiwayatPenyalahgunaanObatNK", "AgenPencederaFisiologisNA", "AgenPencederaKimiawiNA", "AgenPencederaFisikNA",  
    //         "GangguanBiokimiawiN", "GangguanEsofagusN", "DistensiIritasiLambungN", "GangguanPankreasN", "PereganganKapsulLimpaN", "TumorTerlokalisasiN", "PeningkatanTekananIntraAbdominalN", "PeningkatanTIKN", "PeningkatanTekananIntraorbitalN", "FaktorPsikologisN",  
    //         "EfekAgenFarmakologisN", "EfekToksinN", "KehamilanN", "DeliriumKA", "DemensiaKA", "FluktuasiTidurBangunKA", "UsiaLebih60KA", "PenyalahgunaanZatKA", "DisfungsiPankreasKKGD", "ResistensiInsulinKKGD"
    //         ];

    //         let data = renpra.details.map((item, index) => {
    //             let diagnosis = `${item.rencanaKeperawatanRawatInap} Berhubungan `;

    //             let getDiagnosaFunction = EMR2[item.rencanaKeperawatanRawatInap.replace(/\s+/g, "")];
    //             let diagnosaKeperawatan = getDiagnosaFunction ? getDiagnosaFunction() : [];

    //             // Normalisasi teks untuk pencocokan fuzzy
    //             let inputDiagnosis = item.rencanaKeperawatanRawatInap.toLowerCase().trim();

    //             let matchingDiagnosa = diagnosaKeperawatan.filter(d => 
    //                 d.keteranganTambahan.toLowerCase().trim().includes(inputDiagnosis) || 
    //                 inputDiagnosis.includes(d.keteranganTambahan.toLowerCase().trim())
    //             );

    //             if (!matchingDiagnosa) {
    //                 console.warn(`Tidak ditemukan diagnosis yang cocok untuk: ${item.rencanaKeperawatanRawatInap}`);
    //                 return diagnosis; // Lewati proses jika tidak ada kecocokan
    //             }

    //             let kondisiKeys = Object.keys(item).filter(key => key !== "rencanaKeperawatanRawatInap" && key !== "no" && item[key]);

    //             let bdKondisi = "";
    //             let ddKondisiList = [];

    //             let detailList = matchingDiagnosa
    //             .filter(d => d.labelDiagnosaKeperawatan === "Diagnosa Keperawatan")
    //             .flatMap(d => d.detailDiagnosaKeperawatan || []);;

    //             kondisiKeys.forEach((key) => {
    //                 let matchingDetail = detailList.find(detail => detail.model === key);
                    
    //                 if (matchingDetail) {
    //                     let labelDetail = matchingDetail.labelDetail.replace(/^\d+\.\s*/, ""); // Hilangkan nomor di awal
    //                     if (bdModels.includes(key)) {
    //                         bdKondisi += `dengan (b.d) ${labelDetail} `;
    //                     } else {
    //                         ddKondisiList.push(labelDetail);
    //                     }
    //                 }
    //             });

    //             let ddKondisi = "";
    //             if (ddKondisiList.length > 0) {
    //                 ddKondisi = `dengan (d.d) ${ddKondisiList[0]}`; // Elemen pertama dengan "dengan (d.d)"
    //                 if (ddKondisiList.length > 1) {
    //                     ddKondisi += " " + ddKondisiList.slice(1).map(item => `dan ${item}`).join(" ");
    //                 }
    //             }
    //             return diagnosis + bdKondisi.trim() + " " + ddKondisi.trim();
    //         });
    //         // Menyimpan ke input berdasarkan index
    //       // Konversi ke format array objek agar cocok dengan v-model di v-for
    //         input.value.TADiagnosaKeperawatan = data.map(text => ({
    //             TADiagnosaKeperawatan: text || "Data tidak tersedia" // Hindari null
    // }));

    //     }
    } 
    isLoading.value = false
}

const simpan = () => {
    if (!input.value.Diagnosa) {
    H.alert('warning', 'Diagnosa Keperawatan wajib diisi');
    return;
  }
    if (!input.value.DiagnosaBD) {
    H.alert('warning', 'Diagnosa Keperawatan wajib diisi');
    return;
  }
    if (!input.value.DiagnosaDD) {
    H.alert('warning', 'Diagnosa Keperawatan wajib diisi');
    return;
  }

    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    object.nocm = pasien.value.nocm
    if (object.hasOwnProperty('namatemplate')) {
        delete object.namatemplate
    }
    object['TTDPerawat'] = H.tandaTangan().get("TTDPerawat");
    if (route.query.nama_ruangan) {
        object.registrasi = H.setObjectRegistrasi(input.value.registrasi)
        object.pasien = H.setObjectPasien(input.value.pasien)
    } else {
        object.registrasi = H.setObjectRegistrasi(props.registrasi)
        object.pasien = H.setObjectPasien(pasien.value)
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

    isLoading.value = true
    useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
        isLoading.value = false
        if(input.value.SRencanaPP == 1){
              modalRencanaPP.value =true
            } 
        loadRiwayat();
    }).catch((e: any) => {
        isLoading.value = false
    })
}
const fetchPasien = () => {
    pasien.value = props.pasien
    pasien.value.registrasi = props.registrasi
    NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}
const fetchPegawai = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => {
        d_Pegawai.value = response
    })
}

const simpanTemplate = () => {
    if (!input.value.namatemplate) {
        H.alert('warning', "Nama Template wajib diisi")
        return;
    }
    let ID = input.id ? input.id : ''
    let object: any = {}

    object = input.value
    object.nocm = pasien.value.nocm

    object.pasien = H.setObjectPasien(pasien.value)
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
    input.value = response //set ke inputan
    input.value.namatemplate = null
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

function setRenpra () {
  const datarenpra = 'RencanaKeperawatanRawatInap';
  isLoading.value = true;
  showRenpra.value = true;
  useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${datarenpra}`).then(async (dt) => {
    isLoading.value = false;
    if (dt.length > 0) {
      renpra.value = dt[0]
    }
  })
}

function updateRenpra() {
  let ID = renpra.value.id ? renpra.value.id : ''
  let object: any = {}
  object = renpra.value
  object.nocm = props.pasien.nocm
  // object['canvasmata'] = H.tandaTangan().get("canvasmata");

  if (object.hasOwnProperty('namatemplate')) {
    delete object.namatemplate
  }

  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': 'RencanaKeperawatanRawatInap',
    'url_form': props.FORM_URL,
    'name_form': 'Rencana Keperawatan Rawat Inap',
    'jenis_emr': 'asesmen_medis',
    'data': object
  }
  isLoading.value = true

  useApi().post(
    `/emr/simpan-emr`, json).then(async (response: any) => {

      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      renpra.value.id = response.id
    }).catch((e: any) => {
      isLoading.value = false
    })
}

watch(() => [input.value.penurunanbb, input.value.penurunannafsu, input.value.penurunanbbYa], ([newValuePenurunanBB, newValuePenurunanBBYa, newValuePenurunanNafsu]) => {
    let totalNilaiSkriningKalkulasi
    //? Mencegah value checbox dari undefined
    newValuePenurunanBB = newValuePenurunanBB ?? 0;
    newValuePenurunanBBYa = newValuePenurunanBBYa ?? 0;
    newValuePenurunanNafsu = newValuePenurunanNafsu ?? 0;

    //? Calculate total Skrining Nutrisi
    totalNilaiSkriningKalkulasi = newValuePenurunanBB + newValuePenurunanBBYa + newValuePenurunanNafsu
    input.value.nilaiSkrining = totalNilaiSkriningKalkulasi

    if (totalNilaiSkriningKalkulasi >= 0 && totalNilaiSkriningKalkulasi <= 1) {
        input.value.nilai = "RISIKO RENDAH (MST 0-1)";
    } else if (totalNilaiSkriningKalkulasi >= 2 && totalNilaiSkriningKalkulasi <= 3) {
        input.value.nilai = "RISIKO SEDANG (MST 2-3)";
    } else if (totalNilaiSkriningKalkulasi >= 4) {
        input.value.nilai = "RISIKO TINGGI (MST 4-5)";
    }
});

watch(() => [
    input.value.mengontrolbab,
    input.value.mengontrolbak,
    input.value.bersihdiri,
    input.value.toilet,
    input.value.makan,
    input.value.berpindahtt,
    input.value.mobilisasi,
    input.value.berpakaian,
    input.value.tangga,
    input.value.mandi,
], ([
    newValueMengontrolBab,
    newValueMengontrolBak,
    newValueBersihDiri,
    newValueToilet,
    newValueMakan,
    newValueBerpindahTT,
    newValueMobilisasi,
    newValueBerpakaian,
    newValueTangga,
    newValueMandi,
]) => {
    let totalNilaiStatusFungsional;
    //? Mencegah dari undefined
    newValueMengontrolBab = newValueMengontrolBab ?? 0;
    newValueMengontrolBak = newValueMengontrolBak ?? 0;
    newValueBersihDiri = newValueBersihDiri ?? 0;
    newValueToilet = newValueToilet ?? 0;
    newValueMakan = newValueMakan ?? 0;
    newValueBerpindahTT = newValueBerpindahTT ?? 0;
    newValueMobilisasi = newValueMobilisasi ?? 0;
    newValueBerpakaian = newValueBerpakaian ?? 0;
    newValueTangga = newValueTangga ?? 0;
    newValueMandi = newValueMandi ?? 0;

    //? Calculate Status Fungsional
    totalNilaiStatusFungsional = newValueMengontrolBab + newValueMengontrolBak + newValueBersihDiri + newValueToilet + newValueMakan + newValueBerpindahTT + newValueMobilisasi + newValueBerpakaian + newValueTangga + newValueMandi
    input.value.nilaimandi = totalNilaiStatusFungsional

    if (totalNilaiStatusFungsional >= 0 && totalNilaiStatusFungsional <= 4) {
        input.value.CBStatusFungsional = "Ketergantungan total (0-4)"
    } else if (totalNilaiStatusFungsional >= 5 && totalNilaiStatusFungsional <= 8) {
        input.value.CBStatusFungsional = "Ketergantungan berat (5-8)"
    } else if (totalNilaiStatusFungsional >= 9 && totalNilaiStatusFungsional <= 11) {
        input.value.CBStatusFungsional = "Ketergantungan sedang (9-11)"
    } else if (totalNilaiStatusFungsional >= 12 && totalNilaiStatusFungsional <= 19) {
        input.value.CBStatusFungsional = "Ketergantungan ringan(12-19)"
    } else if (totalNilaiStatusFungsional >= 20) {
        input.value.CBStatusFungsional = "Mandiri (20)"
    }
});

onBeforeMount(async () => {
    try {
        await loadRiwayat()
        await fetchPasien()
        if (!route.query.nama_ruangan) {
            let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
            if (cache) input.value = cache
        }
        loadData.value = false
    } catch (error) {
        console.error('Error mount cache TAB EMR:', error);
    }
});

onBeforeRouteLeave((to, from, next) => {
    try {
        let rouutename = from?.name;
        if (!route.query.nama_ruangan) {
            H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value);
        }
        next(); // Proceed without changing the URL
    } catch (error) {
        console.error('Error leave cache TAB EMR:', error);
        next();
    }
});

// ===== ARRAY =====
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
const d_tidakYa: any = ref([
    { value: 1, label: 'Tidak' },
    { value: 2, label: 'Ya' }
])
const d_yaTidak: any = ref([
    { value: 1, label: 'Ya' },
    { value: 2, label: 'Tidak' }
])
const d_tidakAda_ada: any = ref([
    { value: 1, label: 'Tidak' },
    { value: 2, label: 'Ya' }
])
const d_tempatRujukan: any = ref([
    { value: 1, label: 'Rumah Sakit' },
    { value: 2, label: 'Puskesmas' },
    { value: 3, label: 'dr.' },
    { value: 4, label: 'Lainnya' }
]);
const d_penurunanbb: any = ref([
    { value: 0, label: 'Tidak' },
    { value: 2, label: 'Tidak Yakin' }
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
