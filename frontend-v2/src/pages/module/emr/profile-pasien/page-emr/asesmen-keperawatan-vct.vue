<template>
    <ConfirmDialog group="templating">
        <template #message="slotProps">
            <div style="width:500px;height:300px;">
                <table style="width:100%;height:100%;border-collapse: collapse">
                    <tr>
                        <td style="text-align:center;vertical-align:middle">
                            <i :class="slotProps.message.icon"
                                style="font-size:125px;text-align:center;color:#FDDA0D"></i>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:7px;text-align:center">
                            <p style="font-size:large">{{ slotProps.message.message }}</p>
                        </td>
                    </tr>
                </table>
            </div>
        </template>
    </ConfirmDialog>

    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Asesmen Keperawatan VCT</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate"
                                @kembaliKeun="kembaliKeun"></ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <div class="column is-12" style="margin: 10px;">
                    <div class="columns is-mobile is-centered">
                        <div class="column is-auto" style="display: flex; gap: 5px;"> <!-- Flexbox with gap -->
                            <VButton type="button" rounded outlined color="primary" raised icon="feather:folder"
                                :loading="isLoading" @click="pilihTemplateFix(index)">
                                Pilih Template
                            </VButton>
                            <VButton type="button" rounded outlined color="info" raised icon="feather:file-text"
                                :loading="isLoading" @click="pilihTemplate(index)">
                                Pilih Riwayat
                            </VButton>
                        </div>
                        <div class="column is-4">
                            <label class="label" style="margin-bottom: 5px;">Nama Perawat</label>
                            <VField class="is-rounded-select_Z is-autocomplete-select" v-slot="{ id }">
                                <VControl icon="fa:stethoscope" class="prime-auto"
                                    style="max-width: 400px; width: 100%;" fullwidth>
                                    <AutoComplete v-model="input.perawat" :suggestions="d_pegawai"
                                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                        :field="'label'" placeholder="Masukan Nama Perawat" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="column is-12 mb-0">
                    <h1>Nama Template&emsp;&emsp;<span style="color:red">**Hanya diisi jika ingin
                            membuat
                            template</span></h1>
                    <VField>
                        <VControl>
                            <VTextarea v-model="input.namatemplate" rows="1">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>

                <hr>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-4">
                            <h1 style="font-weight: bold;">Tanggal Kedatangan</h1>
                            <VField>
                                <VDatePicker v-model="input.kebtanggalKedatangan" mode="date" trim-weeks
                                    :max-date="new Date()">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents"
                                                    class="is-rounded" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1 style="font-weight: bold;">Jam Kedatangan</h1>
                            <VField>
                                <VDatePicker v-model="input.kebjamKedatangan" color="green" mode="time" is24hr>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VControl icon="feather:clock">
                                                <VInput class="input form-timepicker is-rounded" :value="inputValue"
                                                    v-on="inputEvents" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1 style="font-weight: bold;">Jam Asesmen Awal</h1>
                            <VField>
                                <VDatePicker v-model="input.kebjamAsesmenAwal" color="green" mode="time" is24hr>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VControl icon="feather:clock">
                                                <VInput class="input form-timepicker is-rounded" :value="inputValue"
                                                    v-on="inputEvents" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                    </div>
                </div>

                <div class="column is-12 mb-0 mt-0">
                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <div class="columns is-multiline">
                                <div class="column is-12 pt-0">
                                    <h1 style="font-weight: bold;">Rujukan</h1>
                                </div>
                                <div class="column is-4 pt-0">
                                    <VField>
                                        <VControl>
                                            <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kebrujukan"
                                                true-value="YA" label="Ya" color="primary" circle />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4 pt-0">
                                    <VField>
                                        <VControl>
                                            <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kebrujukan"
                                                true-value="TIDAK" label="Tidak" color="primary" circle />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-6 pl-0 pt-0" v-if="input.kebrujukan == 'YA'">
                            <div class="column is-12 pt-0">
                                <h1 class="bold">Dari</h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" class="heightinput input" placeholder="Keterangan Rujukan"
                                            v-model.number="input.TBKetRujukanDari" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                        <div class="column is-6" v-else-if="input.kebrujukan == 'TIDAK'">
                            <div class="columns is-multiline">
                                <div class="column is-12 pt-0">
                                    <h1 class="bold">
                                        Kedatangan
                                    </h1>
                                </div>
                                <div class="column is-4 pt-0">
                                    <VField>
                                        <VControl>
                                            <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kebrujuklanjutan"
                                                @change="" true-value="SENDIRI" label="Sendiri" color="primary"
                                                circle />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4 pt-0">
                                    <VField>
                                        <VControl>
                                            <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kebrujuklanjutan"
                                                true-value="DIANTAR" label="Diantar" color="primary" circle />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12"
                            v-if="input.kebrujukan == 'TIDAK' && input.kebrujuklanjutan == 'DIANTAR'">
                            <div class="columns is-multiline">
                                <div class="column is-2 center">
                                    <h3 style="font-weight: bold;">
                                        Diantar Oleh
                                    </h3>
                                </div>
                                <div class="column is-10">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="heightinput input" placeholder="Diantar Oleh"
                                                v-model.number="input.TBDiantarOleh" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-4">
                            <h1 style="font-size:larger;font-weight:bold" class="mb-4">No Reg.Nas</h1>
                        </div>
                        <div class="column is-8">
                            <VField>
                                <VControl>
                                    <VInput class="heightinput input" v-model="input.noregnas" type="text" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="column is-12">
                    <div class="columns is-multiline mb-3">
                        <div class="column is-4">
                            <h1>Tanggal Positif 1</h1>
                            <VField>
                                <VControl>
                                    <VInput class="heightinput input" v-model="input.tanggalpositif" type="text" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1>Tempat</h1>
                            <VField>
                                <VControl>
                                    <VInput class="heightinput input" v-model="input.tempat" type="text" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1>Nama Pengawas minum obat (PMO)</h1>
                            <VField>
                                <VControl>
                                    <VInput class="heightinput input" v-model="input.obatpmo" type="text" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1>Alamat dan No. telp PMO</h1>
                            <VField>
                                <VControl>
                                    <VInput class="heightinput input" v-model="input.noalamatpmo" type="text" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1>Entry Point</h1>
                            <VField class="is-autocomplete-select" v-slot="{ id }">
                                <VControl>
                                    <Multiselect v-model="input.entrypoint" :attrs="{ value }" label="label"
                                        :options="d_entrypoint" :searchable="true" track-by="label" mode="single"
                                        autocomplete="off">
                                    </Multiselect>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4 mt-5">
                            <VField>
                                <VControl>
                                    <VInput class="heightinput input" v-model="input.kebentrypoint" type="text"
                                        placeholder="Keterangan Entry Point" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1>Pasien dirujuk dari klinik lain</h1>
                            <VField class="is-autocomplete-select" v-slot="{ id }">
                                <VControl>
                                    <Multiselect v-model="input.klinikrujuk" :attrs="{ value }" label="label"
                                        :options="d_klinik" :searchable="true" track-by="label" mode="single"
                                        autocomplete="off">
                                    </Multiselect>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1>Nama klinik sebelumnya</h1>
                            <VField>
                                <VControl>
                                    <VInput class="heightinput input" v-model="input.kliniksebelumnya" type="text"
                                        placeholder="" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1>Tanggal Rujuk masuk (RM)</h1>
                            <VField>
                                <VControl>
                                    <VInput class="heightinput input" v-model="input.tglrujukmasuk" type="text"
                                        placeholder="" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="column is-12">
                    <h1 style="font-size:larger;font-weight:bold" class="mb-4">ALLOANAMNESIS</h1>
                    <div class="columns is-multiline mb-3">
                        <div class="column is-4">
                            <VField class="is-autocomplete-select" v-slot="{ id }">
                                <VControl>
                                    <Multiselect v-model="input.kebpilihanallo" :attrs="{ value }" label="label"
                                        :options="d_allo" :searchable="true" track-by="label" mode="single"
                                        autocomplete="off">
                                    </Multiselect>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-8">
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.keballoanamnesis" placeholder="Ketik Alloanamnesis"
                                        rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>

                <hr>


                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12 mt-auto">
                            <h1 class="mb-1" style="font-size:larger;font-weight:bold">ANAMNESIS
                            </h1>
                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <h1>Riwayat penyakit terdahulu</h1>
                                    <VField>
                                        <VControl>
                                            <VTextarea v-model="input.riwayatpenyakitdahulu" rows="7">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <h1>Riwayat Operasi</h1>
                                    <VField>
                                        <VControl>
                                            <VTextarea v-model="input.riwayatoperasi" rows="7">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <h1>Riwayat penyakit keluarga</h1>
                                    <VField>
                                        <VControl>
                                            <VTextarea v-model="input.riwayatpenyakitkeluarga" rows="7">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <h1>Riwayat alergi</h1>
                                    <VField>
                                        <VControl>
                                            <VTextarea v-model="input.riwayatalergi" placeholder="" rows="7">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <hr>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12 pb-0">
                            <h1 class="bold" style="font-size:larger;">
                                PEMERIKSAAN FISIK :
                            </h1>
                        </div>
                        <div class="column is-3">
                            <h1>Keadaan Umum</h1>
                            <VField class="is-autocomplete-select">
                                <VControl>
                                    <Multiselect v-model="input.keadaanumumobgyn" :attrs="{ value }"
                                        placeholder="--Pilih--" label="label" :options="d_keadaanumum"
                                        :searchable="true" track-by="label" mode="single" autocomplete="off">
                                    </Multiselect>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <h1>Tekanan Darah</h1>
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
                        <div class="column is-3">
                            <h1>Nadi</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="number" class="input" placeholder="" v-model="input.nadiObgyn" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>x/menit</VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <h1>Respirasi</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="number" class="input" placeholder="" v-model="input.nafasObgyn" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>x/menit</VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3 pt-0">
                            <h1>Suhu</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="number" class="input" placeholder="" v-model="input.celciusObgyn" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>°C </VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3 pt-0">
                            <h1>SaO2</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="number" class="input" placeholder="" v-model="input.sao2Obgyn" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>%</VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3 pt-0">
                            <h1>Berat Badan</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="number" class="input" placeholder="Berat Badan"
                                        v-model="input.beratbadanObgyn" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>kg</VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3 pt-0">
                            <h1>Tinggi Badan</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="number" class="input" placeholder="Tinggi Badan"
                                        v-model="input.tinggibadanObgyn" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>cm</VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12 pb-0 pt-0">
                            <h1>GCS : </h1>
                        </div>
                        <div class="column is-3 pt-0">
                            <VField addons>
                                <VControl class="field-addon-body">
                                    <VButton static>E</VButton>
                                </VControl>
                                <VControl expanded>
                                    <Multiselect v-model="input.gcse" :attrs="{ value }" placeholder="E" label="label"
                                        :options="d_gcse" :searchable="true" track-by="label" mode="single"
                                        autocomplete="off" style="border-radius:0px 4px 4px 0px;height:100%">
                                    </Multiselect>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3 pt-0">
                            <VField addons>
                                <VControl class="field-addon-body">
                                    <VButton static>V</VButton>
                                </VControl>
                                <VControl expanded>
                                    <Multiselect v-model="input.gcsv" :attrs="{ value }" placeholder="V" label="label"
                                        :options="d_gcsv" :searchable="true" track-by="label" mode="single"
                                        autocomplete="off" style="border-radius:0px 4px 4px 0px;height:100%">
                                    </Multiselect>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3 pt-0">
                            <VField addons>
                                <VControl class="field-addon-body">
                                    <VButton static>M</VButton>
                                </VControl>
                                <VControl expanded>
                                    <Multiselect v-model="input.gcsm" :attrs="{ value }" placeholder="M" label="label"
                                        :options="d_gcsm" :searchable="true" track-by="label" mode="single"
                                        autocomplete="off" style="border-radius:0px 4px 4px 0px;height:100%">
                                    </Multiselect>
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12 pb-0">
                            <h1 class="bold" style="font-size:larger">PENILAIAN NYERI : </h1>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <h1>Nyeri</h1>
                                    <VField class="is-autocomplete-select">
                                        <VControl>
                                            <Multiselect v-model="input.nyeri" :attrs="{ value }" placeholder=""
                                                label="label" :options="d_nyeri" :searchable="true" track-by="label"
                                                mode="single" autocomplete="off">
                                            </Multiselect>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <h1>Skala nyeri (0 - 10)</h1>
                                    <VField addons>
                                        <VControl expanded>
                                            <VInput type="text" class="heightinput input" placeholder=""
                                                v-model="input.skalanyeri" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <h1>Lokasi</h1>
                                    <VField addons>
                                        <VControl expanded>
                                            <VInput type="text" class="heightinput input" placeholder=""
                                                v-model="input.lokasi" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <h1>Jenis</h1>
                                    <VField class="is-autocomplete-select">
                                        <VControl>
                                            <Multiselect v-model="input.jenisnyeri" :attrs="{ value }" placeholder=""
                                                label="label" :options="d_jenisnyeri" :searchable="true"
                                                track-by="label" mode="single" autocomplete="off">
                                            </Multiselect>
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12 pt-0">
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <h1>Frekuensi Nyeri</h1>
                                    <VField class="is-autocomplete-select">
                                        <VControl>
                                            <Multiselect v-model="input.frekuensinyeri" :attrs="{ value }" label="label"
                                                :options="d_freknyeri" :searchable="true" track-by="label" mode="single"
                                                autocomplete="off">
                                            </Multiselect>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <h1>Lama Nyeri</h1>
                                    <VField addons>
                                        <VControl expanded>
                                            <VInput type="text" class="heightinput input" placeholder=""
                                                v-model="input.lamanyeri" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <h1>Menjalar</h1>
                                    <VField class="is-autocomplete-select">
                                        <VControl>
                                            <Multiselect v-model="input.menjalarnyeri" :attrs="{ value }" label="label"
                                                :options="d_menjalarnyeri" :searchable="true" track-by="label"
                                                mode="single" autocomplete="off">
                                            </Multiselect>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <h1>Menjalar Ke</h1>
                                    <VField addons>
                                        <VControl expanded>
                                            <VInput type="text" class="heightinput input" placeholder=""
                                                v-model="input.menjalarkenyeri" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12 pt-0">
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                    <h1>Kualitas Nyeri</h1>
                                    <VField class="is-autocomplete-select">
                                        <VControl>
                                            <Multiselect v-model="input.kualitasnyeri" :attrs="{ value }" label="label"
                                                :options="d_kualitasnyeri" :searchable="true" track-by="label"
                                                mode="single" autocomplete="off">
                                            </Multiselect>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <h1>Yang menyebabkan Nyeri Bertambah</h1>
                                    <VField addons>
                                        <VControl expanded>
                                            <VInput type="text" class="heightinput input" placeholder=""
                                                v-model="input.tambahnyeri" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <h1>Yang menyebabkan Nyeri Berkurang</h1>
                                    <VField addons>
                                        <VControl expanded>
                                            <VInput type="text" class="heightinput input" placeholder=""
                                                v-model="input.kurangnyeri" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12 pt-0">
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                    <h1>Input Angka (Gambar)</h1>
                                    <VField class="is-autocomplete-select">
                                        <VControl>
                                            <Multiselect v-model="input.angkanyeri" :attrs="{ value }" label="label"
                                                :options="d_angkanyeri" :searchable="true" track-by="label"
                                                mode="single" autocomplete="off">
                                            </Multiselect>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-8">
                                    <img src="/images/emr/wongBaker-2.png" alt="skalanyeri.png" style="max-width:100%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div v-if="kelompokUser.toUpperCase().indexOf('NURSE-STATION') == -1"> -->

                <hr>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12 pb-0">
                            <h1 class="bold" style="font-size:larger;">
                                KONDISI PSIKOLOGI, SOSIAL, EKONOMI DAN SPIRITUAL
                            </h1>
                        </div>
                        <div class="column is-3">
                            <h1 class="emr">Warganegara</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBWargaNegara" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <h1 class="emr">Pembiayaan Kesehatan</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBPembiayaanKesehatan" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <h1 class="emr">Tinggal Bersama</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBTinggalBersama" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <h1 class="emr">Nama</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBNama_TB" />
                            </VControl>
                        </div>
                        <div class="column is-3 pt-0">
                            <h1 class="emr">Kebiasaan</h1>
                            <div class="columns m-0 column is-12 p-0">
                                <VField vertical>
                                    <VControl>
                                        <VCheckbox class="p-0" color="primary" square true-value="Merokok"
                                        label="Merokok" v-model="input.CBMerokok_Kebiasaan" />
                                        <VInput v-if="input.CBMerokok_Kebiasaan == 'Merokok'" type="text" class="input" v-model="input.TBMerokok_Kebiasaan" />
                                    </VControl>
                                    <VControl>
                                        <VCheckbox class="p-0" color="primary" square true-value="Alkohol"
                                            label="Alkohol" v-model="input.CBAlkohol_Kebiasaan" />
                                        <VInput type="text" class="input" v-model="input.TBAlkohol_Kebiasaan" v-if="input.CBAlkohol_Kebiasaan == 'Alkohol'"/>
                                    </VControl>
                                    <VControl>
                                        <VCheckbox class="p-0" color="primary" square true-value="Lainnya"
                                            label="Lainnya" v-model="input.CBLainnya_Kebiasaan" />
                                        <VInput v-if="input.CBLainnya_Kebiasaan == 'Lainnya'" type="text" class="input" v-model="input.TBLainnya_Kebiasaan" />
                                    </VControl>

                                </VField>
                            </div>
                        </div>
                        <div class="column is-3 pt-0">
                            <h1 class="emr">No Telepon</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBNoTelepon" />
                            </VControl>
                        </div>
                        <div class="column is-3 pt-0">
                            <h1 class="emr">Jenis dan jumlah per hari</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBJenisJumlahPerHari" />
                            </VControl>
                        </div>
                        <div class="column is-3 pt-0">
                            <h1 class="emr">Agama</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBAgama" />
                            </VControl>
                        </div>
                        <div class="column is-3 pt-0">
                            <h1 class="emr">Perlu Rohani</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBPerluRohani" />
                            </VControl>
                        </div>
                        <div class="column is-3 pt-0">
                            <h1 class="emr">Jelaskan</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBLainnya_PR" />
                            </VControl>
                        </div>
                        <!-- <div class="column is-3">
                            <h1>Masalah perkawinan</h1>
                            <VField class="is-autocomplete-select">
                                <VControl>
                                    <Multiselect v-model="input.masalahperkawinan" :attrs="{ value }"
                                        placeholder="--Pilih--" label="label" :options="d_masalahkawin"
                                        :searchable="true" track-by="label" mode="single" autocomplete="off">
                                    </Multiselect>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <h1 class="emr">Jelaskan</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="text" class="heightinput input" placeholder=""
                                        v-model="input.masalahkawin" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <h1>Mengalami kekerasan fisik</h1>
                            <VField class="is-autocomplete-select">
                                <VControl>
                                    <Multiselect v-model="input.kekerasanfisik" :attrs="{ value }"
                                        placeholder="--Pilih--" label="label" :options="d_kekerasan"
                                        :searchable="true" track-by="label" mode="single" autocomplete="off">
                                    </Multiselect>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <h1 class="emr">Jelaskan</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="text" class="heightinput input" placeholder=""
                                        v-model="input.ketkekerasanfisik" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6 pt-0">
                            <h1>Gangguan Psikologis</h1>
                            <VField class="is-autocomplete-select">
                                <VControl>
                                    <Multiselect v-model="input.gangguanpsikologis" :attrs="{ value }"
                                        placeholder="--Pilih--" label="label" :options="d_gangguanpsikologis"
                                        :searchable="true" track-by="label" mode="single" autocomplete="off">
                                    </Multiselect>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6 pt-0">
                            <h1 class="emr">Keyakinan dan nilai pribadi</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="text" class="heightinput input" placeholder=""
                                        v-model="input.keyakinanpribadi" />
                                </VControl>
                            </VField>
                        </div> -->
                    </div>
                </div>

                <hr>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12 pb-0">
                            <h1 style="font-size:larger;font-weight:bold">RIWAYAT PRIBADI</h1>
                        </div>
                        <div class="column is-3">
                            <h1>Pendidikan</h1>
                            <Multiselect v-model="input.SPendidikan" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_Pendidikan" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3">
                            <h1>Status Pekerjaan</h1>
                            <Multiselect v-model="input.SStatusPekerjaan" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_StatusPekerjaan" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3">
                            <h1>Faktor Resiko</h1>
                            <Multiselect v-model="input.SFaktorResiko" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_FaktorResiko" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                            <VControl v-if="input.SFaktorResiko == 8">
                                <VInput type="text" class="input" v-model="input.TBLainnya_FR" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <h1>Uraikan</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBUraikan" />
                            </VControl>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- RIWAYAT KELUARGA -->
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12 pb-0">
                            <h1 style="font-size:larger;font-weight:bold">RIWAYAT KELUARGA</h1>
                        </div>
                        <div class="column is-3">
                            <h1>Status Pernikahan</h1>
                            <Multiselect v-model="input.SStatusPernikahan" :attrs="{ value }"
                                placeholder="--Pilih--" label="label" :options="d_StatusPernikahan"
                                :searchable="true" track-by="label" mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-12 pt-0">
                            <table style="width: 100%;border-collapse: collapse;">
                                <tr>
                                    <th class="border center" style="width: 14%;">#</th>
                                    <th class="border center" style="width: 14%;">Nama</th>
                                    <th class="border center" style="width: 14%;">Hubungan</th>
                                    <th class="border center" style="width: 14%;">Umur</th>
                                    <th class="border center" style="width: 14%;">HIV +/-</th>
                                    <th class="border center" style="width: 14%;">ART Ya/Tidak</th>
                                    <th class="border center" style="width: 14%;">No.Reg&gt;Nas</th>
                                </tr>
                                <tr v-for="(item, index) in input.details" :key="index">
                                    <td class="border" style="vertical-align: inherit">
                                        <div class="column">
                                            <VButtons style="justify-content:space-around">
                                                <VIconButton type="button" raised circle icon="feather:plus"
                                                    @click="addNewItem()" color="info" v-tooltip.bubble="'Tambah '">
                                                </VIconButton>
                                                <VIconButton class="mt-1" v-if="index > 0" type="button" raised
                                                    circle icon="feather:trash" @click="removeItem(index)"
                                                    color="danger">
                                                </VIconButton>
                                            </VButtons>
                                        </div>
                                    </td>
                                    <td class="border" style="vertical-align: middle;">
                                        <VControl>
                                            <VInput type="text" class="input" v-model="item.nama" />
                                        </VControl>
                                    </td>
                                    <td class="border" style="vertical-align: middle;">
                                        <VControl>
                                            <VInput type="text" class="input" v-model="item.hubungan" />
                                        </VControl>
                                    </td>
                                    <td class="border" style="vertical-align: middle;">
                                        <VControl>
                                            <VInput type="text" class="input" v-model="item.umur" />
                                        </VControl>
                                    </td>
                                    <td class="border" style="vertical-align: middle;">
                                        <Multiselect v-model="item.hiv" :attrs="{ value }" placeholder="--Pilih--"
                                            label="label" :options="d_hiv" :searchable="true" track-by="label"
                                            mode="single" autocomplete="off">
                                        </Multiselect>
                                    </td>
                                    <td class="border" style="vertical-align: middle;">
                                        <Multiselect v-model="item.art" :attrs="{ value }" placeholder="--Pilih--"
                                            label="label" :options="d_art" :searchable="true" track-by="label"
                                            mode="single" autocomplete="off">
                                        </Multiselect>
                                    </td>
                                    <td class="border" style="vertical-align: middle;">
                                        <VControl>
                                            <VInput type="text" class="input" v-model="item.noreg" />
                                        </VControl>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- SKRINING NUTRISI -->
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12 pb-0">
                                    <h1 style="font-size:larger;font-weight:bold">SKRINNING NUTRISI</h1>
                                </div>
                                <div class="column is-3">
                                    <h1>Berat Badan (BB) biasanya</h1>
                                    <VField addons>
                                        <VControl expanded>
                                            <VInput type="text" class="heightinput input" placeholder=""
                                                v-model="input.bbBiasanya" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <h1>Penurunan BB 6 bulan terakhir?</h1>
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
                                <div class="column is-3">
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
                                <!-- <div class="column is-8"></div> -->
                                <div class="column is-3">
                                    <h1>Nilai</h1>
                                    <VField addons>
                                        <VControl expanded>
                                            <VInput type="text" class="heightinput input" placeholder=""
                                                v-model="input.nilaiSkrining" disabled />
                                        </VControl>
                                    </VField>
                                </div>
                                <!-- <div class="column is-12 pt-0 pb-0">
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
                                </div> -->
                                <!-- <div class="column is-12 pt-0">
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
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- STATUS FUNGSIONAL -->
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12 pb-0">
                            <h1 style="font-size:larger;font-weight:bold">STATUS FUNGSIONAL</h1>
                        </div>
                        <div class="column is-6">
                            <h1>Status Fungsional</h1>
                            <VField class="is-autocomplete-select">
                                <VControl>
                                    <Multiselect v-model="input.statusfungsi" :attrs="{ value }"
                                        placeholder="--Pilih--" label="label" :options="d_statusfungsi"
                                        :searchable="true" track-by="label" mode="single"
                                        autocomplete="off">
                                    </Multiselect>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <h1>Sebutkan</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="text" class="heightinput input" placeholder=""
                                        v-model="input.ketstatusfungsi" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Pengkajian Risiko Jatuh -->

                <hr>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12 pb-0">
                            <h1 style="font-size:larger;font-weight:bold">ASESMEN RISIKO JATUH</h1>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-10">
                                    <h1>A. Perhatikan cara duduk pasien saat akan duduk di kursi.
                                        Apakah
                                        pasien tampak
                                        tidak seimbang (sempoyongan/limbung)?</h1>
                                </div>
                                <div class="column is-2">
                                    <VField class="is-autocomplete-select">
                                        <VControl>
                                            <Multiselect v-model="input.caraduduk" :attrs="{ value }"
                                                placeholder="--Pilih--" label="label" :options="d_caraduduk"
                                                :searchable="true" track-by="label" mode="single"
                                                autocomplete="off">
                                            </Multiselect>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-10">
                                    <h1>B. Apakah pasien memegang pinggiran kursi atau meja atau
                                        benda
                                        lain sebagai
                                        penopang saat akan duduk?</h1>
                                </div>
                                <div class="column is-2">
                                    <VField class="is-autocomplete-select">
                                        <VControl>
                                            <Multiselect v-model="input.kursi" :attrs="{ value }"
                                                placeholder="--Pilih--" label="label" :options="d_kursi"
                                                :searchable="true" track-by="label" mode="single"
                                                autocomplete="off">
                                            </Multiselect>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <h1>Hasil</h1>
                                    <VField addons>
                                        <VControl expanded>
                                            <VInput type="text" class="heightinput input" placeholder=""
                                                v-model="input.hasiljatuh" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <h1>Tindakan</h1>
                                    <VField class="is-autocomplete-select">
                                        <VControl>
                                            <Multiselect v-model="input.tindakan" :attrs="{ value }"
                                                placeholder="--Pilih--" label="label" :options="d_tindakan"
                                                :searchable="true" track-by="label" mode="single"
                                                autocomplete="off">
                                            </Multiselect>
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12 pb-0">
                            <h1 style="font-size:larger;font-weight:bold">PEMERIKSAAN KLINIS DAN LABORATORIUM</h1>
                        </div>
                        <div class="column is-12">
                            <table style="width: 100%;border-collapse: collapse;">
                                <tr>
                                    <th style="width: 14%;">&nbsp;</th>
                                    <th style="width: 14%;">Tanggal</th>
                                    <th style="width: 14%;">Stad WHO</th>
                                    <th style="width: 14%;">Berat Badan</th>
                                    <th style="width: 14%;">Status Fungsional</th>
                                    <th style="width: 14%;">Jumlah CD4</th>
                                    <th style="width: 14%;">Lain-lain</th>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold;vertical-align: middle;" class="border center">
                                        Kunjungan Pertama</td>
                                    <th>
                                        <VDatePicker v-model="input.D_KP" mode="date" trim-weeks>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" v-on="inputEvents" />
                                                </VControl>
                                            </template>
                                        </VDatePicker>
                                    </th>
                                    <th>
                                        <Multiselect v-model="input.SStadWHO_KP" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_stadWHO"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </th>
                                    <th>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBBB_KP" />
                                        </VControl>
                                    </th>
                                    <th>
                                        <Multiselect v-model="input.SSF_KP" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_statusFungsional"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </th>
                                    <th>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBJumlahCD4_KP" />
                                        </VControl>
                                    </th>
                                    <th>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBLainLain_KP" />
                                        </VControl>
                                    </th>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold;vertical-align: middle;" class="border center">
                                        Memenuhi
                                        Syarat Medis Untuk ART</td>
                                    <th>
                                        <VDatePicker v-model="input.D_MSM" mode="date" trim-weeks>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" v-on="inputEvents" />
                                                </VControl>
                                            </template>
                                        </VDatePicker>
                                    </th>
                                    <th>
                                        <Multiselect v-model="input.SStadWHO_MSM" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_stadWHO"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </th>
                                    <th>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBBB_MSM" />
                                        </VControl>
                                    </th>
                                    <th>
                                        <Multiselect v-model="input.SSF_MSM" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_statusFungsional"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </th>
                                    <th>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBJumlahCD4_MSM" />
                                        </VControl>
                                    </th>
                                    <th>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBLainLain_MSM" />
                                        </VControl>
                                    </th>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold;vertical-align: middle;" class="border center">Saat
                                        Mulai ART</td>
                                    <th>
                                        <VDatePicker v-model="input.D_SMA" mode="date" trim-weeks>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" v-on="inputEvents" />
                                                </VControl>
                                            </template>
                                        </VDatePicker>
                                    </th>
                                    <th>
                                        <Multiselect v-model="input.SStadWHO_SMA" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_stadWHO"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </th>
                                    <th>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBBB_SMA" />
                                        </VControl>
                                    </th>
                                    <th>
                                        <Multiselect v-model="input.SSF_SMA" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_statusFungsional"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </th>
                                    <th>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBJumlahCD4_SMA" />
                                        </VControl>
                                    </th>
                                    <th>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBLainLain_SMA" />
                                        </VControl>
                                    </th>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold;vertical-align: middle;" class="border center">
                                        Setelah
                                        6 Bulan ART</td>
                                    <th>
                                        <VDatePicker v-model="input.D_S6BART" mode="date" trim-weeks>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" v-on="inputEvents" />
                                                </VControl>
                                            </template>
                                        </VDatePicker>
                                    </th>
                                    <th>
                                        <Multiselect v-model="input.SStadWHO_S6BART" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_stadWHO"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </th>
                                    <th>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBBB_S6BART" />
                                        </VControl>
                                    </th>
                                    <th>
                                        <Multiselect v-model="input.SSF_S6BART" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_statusFungsional"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </th>
                                    <th>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBJumlahCD4_S6BART" />
                                        </VControl>
                                    </th>
                                    <th>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBLainLain_S6BART" />
                                        </VControl>
                                    </th>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold;vertical-align: middle;" class="border center">
                                        Setelah
                                        12 Bulan ART</td>
                                    <th>
                                        <VDatePicker v-model="input.D_S12BART" mode="date" trim-weeks>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" v-on="inputEvents" />
                                                </VControl>
                                            </template>
                                        </VDatePicker>
                                    </th>
                                    <th>
                                        <Multiselect v-model="input.SStadWHO_S12BART" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_stadWHO"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </th>
                                    <th>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBBB_S12BART" />
                                        </VControl>
                                    </th>
                                    <th>
                                        <Multiselect v-model="input.SSF_S12BART" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_statusFungsional"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </th>
                                    <th>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBJumlahCD4_S12BART" />
                                        </VControl>
                                    </th>
                                    <th>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBLainLain_S12BART" />
                                        </VControl>
                                    </th>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold;vertical-align: middle;" class="border center">
                                        Setelah
                                        24 Bulan ART</td>
                                    <th>
                                        <VDatePicker v-model="input.D_S24BART" mode="date" trim-weeks>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" v-on="inputEvents" />
                                                </VControl>
                                            </template>
                                        </VDatePicker>
                                    </th>
                                    <th>
                                        <Multiselect v-model="input.SStadWHO_S24BART" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_stadWHO"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </th>
                                    <th>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBBB_S24BART" />
                                        </VControl>
                                    </th>
                                    <th>
                                        <Multiselect v-model="input.SSF_S24BART" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_statusFungsional"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </th>
                                    <th>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBJumlahCD4_S24BART" />
                                        </VControl>
                                    </th>
                                    <th>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBLainLain_S24BART" />
                                        </VControl>
                                    </th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12 pb-0">
                            <h1 style="font-size:larger;font-weight:bold">TERAPI ANTIRETROVIRAL (ART)</h1>
                        </div>
                        <div class="column is-4">
                            <Multiselect v-model="input.STerapiAntire" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_TA" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-6" v-if="input.STerapiAntire == 'Lainnya'">
                            <h1>
                                Lainnya :
                            </h1>
                            <VField>
                                <VTextarea rows="2" v-model="input.STerapiAntireText"></VTextarea>
                            </VField>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Subtitusi -->
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12 pb-0">
                            <h1 style="font-size:larger;font-weight:bold">SUBSTITUSI dalam lini-1. SWITCH lini-2.
                                STOP
                            </h1>
                        </div>
                        <div class="column is-12 pt-0">
                            <table style="width: 100%;border-collapse: collapse;">
                                <tr>
                                    <th class="border center" style="width: 10%;">#</th>
                                    <th class="border center" style="width: 10%;">Nama Rejimen ART Orisinal</th>
                                    <th class="border center" style="width: 10%;">Tanggal</th>
                                    <th class="border center" style="width: 5%;">Substitusi</th>
                                    <th class="border center" style="width: 5%;">Switch</th>
                                    <th class="border center" style="width: 5%;">Stop</th>
                                    <th class="border center" style="width: 5%;">Restart</th>
                                    <th class="border center" style="width: 10%;">Alasan</th>
                                    <th class="border center" style="width: 10%;">Nama Rejimen Baru</th>
                                </tr>
                                <tr v-for="(item, index) in input.details2" :key="index">
                                    <td class="border" style="vertical-align: inherit">
                                        <div class="column">
                                            <VButtons style="justify-content:space-around">
                                                <VIconButton type="button" raised circle icon="feather:plus"
                                                    @click="addNewItem2()" color="info"
                                                    v-tooltip.bubble="'Tambah '">
                                                </VIconButton>
                                                <VIconButton class="mt-1" v-if="index > 0" type="button" raised
                                                    circle icon="feather:trash" @click="removeItem2(index)"
                                                    color="danger">
                                                </VIconButton>
                                            </VButtons>
                                        </div>
                                    </td>
                                    <td class="border" style="vertical-align: middle;">
                                        <VControl>
                                            <VInput type="text" class="input" v-model="item.NamaRejimenART" />
                                        </VControl>
                                    </td>
                                    <td class="border" style="vertical-align: middle;">
                                        <VDatePicker v-model="item.D_Subtitusi" mode="date" trim-weeks>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" v-on="inputEvents" />
                                                </VControl>
                                            </template>
                                        </VDatePicker>
                                    </td>
                                    <td class="border" style="vertical-align: middle;text-align: center;">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Subtitusi"
                                                label="" v-model="item.Subtitusi" />
                                        </VControl>
                                    </td>
                                    <td class="border" style="vertical-align: middle;text-align: center;">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Switch"
                                                label="" v-model="item.Switch" />
                                        </VControl>
                                    </td>
                                    <td class="border" style="vertical-align: middle;text-align: center;">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Stop" label=""
                                                v-model="item.Stop" />
                                        </VControl>
                                    </td>
                                    <td class="border" style="vertical-align: middle;text-align: center;">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Restart"
                                                label="" v-model="item.Restart" />
                                        </VControl>
                                    </td>
                                    <td class="border" style="vertical-align: middle;text-align: center;">
                                        <VControl>
                                            <VInput type="text" class="input" v-model="item.Alasan" />
                                        </VControl>
                                    </td>
                                    <td class="border" style="vertical-align: middle;text-align: center;">
                                        <VControl>
                                            <VInput type="text" class="input" v-model="item.NamaRejimenBaru" />
                                        </VControl>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <hr>
                <!-- Pengobatan HIV -->
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12 pb-0">
                            <h1 style="font-size:larger;font-weight:bold">
                                PENGOBATAN TB SELAMA PERAWATAN HIV
                            </h1>
                        </div>
                        <div class="column is-12 pb-0" style="font-weight:bold">
                            Klasifikasi TB (pilih)
                        </div>
                        <div class="column is-4">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="TB Paru" label="TB Paru"
                                    v-model="input.CBTBParu" />
                            </VControl>
                        </div>
                        <div class="column is-4">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="TB Extra Paru"
                                    label="TB Extra Paru" v-model="input.CBTBExtraParu" />
                            </VControl>
                        </div>
                        <div class="column is-4">
                            <h1>Lokasi</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBLokasi_KT" />
                            </VControl>
                        </div>
                        <div class="column is-12 pb-0" style="font-weight:bold">
                            Regimen TB
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Kategori I"
                                    label="Kategori I" v-model="input.CBKategoriI_RT" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Kategori II"
                                    label="Kategori II" v-model="input.CBKategoriII_RT" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Kategori Anak"
                                    label="Kategori Anak" v-model="input.CBKategoriAnak_RT" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="OAT lini 2 (MDR)"
                                    label="OAT lini 2 (MDR)" v-model="input.CBOATlini2_RT" />
                            </VControl>
                        </div>
                        <div class="column is-12 pb-0" style="font-weight:bold">
                            Tempat Pengobatan TB
                        </div>
                        <div class="column is-4">
                            <h1>Kabupaten</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBKabupaten_TPT" />
                            </VControl>
                        </div>
                        <div class="column is-4">
                            <h1>Nama Sarana Kesehatan</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBNamaSarana_TPT" />
                            </VControl>
                        </div>
                        <div class="column is-4">
                            <h1>No Reg TB Kabupaten/Kota</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBNoreg_TPT" />
                            </VControl>
                        </div>
                        <div class="column is-12 pb-0" style="font-weight:bold">
                            Tipe B
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Paru" label="Paru"
                                    v-model="input.CBParu_TipeB" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Kambuh" label="Kambuh"
                                    v-model="input.CBKambuh_TipeB" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Default" label="Default"
                                    v-model="input.CBDefault_TipeB" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Gagal" label="Gagal"
                                    v-model="input.CBGagal_TipeB" />
                            </VControl>
                        </div>
                        <div class="column is-6">
                            <h1 style="font-weight: bold;">Tgl Mulai Terapi TB</h1>
                            <VDatePicker v-model="input.DMulaiTerapiTB" mode="date" trim-weeks>
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-6">
                            <h1 style="font-weight: bold;">Tgl Selesai Terapi TB</h1>
                            <VDatePicker v-model="input.DSelesaiTerapiTB" mode="date" trim-weeks>
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Diagnosa keperawatan -->
                <div class="column is-12 forCB">
                    <h1 style="font-size: larger; font-weight: bold">DIAGNOSA KEPERAWATAN</h1>
                    <div class="control">
                        <input type="text" v-model="filterMenu" class="input" placeholder="Search..." />
                    </div>

                    <div v-for="diagnosis in filteredDiagnoses" :key="diagnosis.value" class="checkbox-container">
                        <VCheckbox class="fontcheckbox" v-model="input[diagnosis.value]"
                            :true-value="diagnosis.text" color="primary" circle />
                        <span v-html="highlightMatch(diagnosis.text)" class="highlighted-label"></span><br />

                        <!-- Conditionally show the textarea if "Lainnya" is selected -->
                        <div v-if="diagnosis.value === 'lainnya1' && input.lainnya1">
                            <textarea v-model="input.textLainnya1" class="textarea"
                                placeholder="Tuliskan diagnosis lainnya..."></textarea>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Rencana Keperawatan -->
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <div clas="column is-12">
                                        <h1 style="font-size:larger;font-weight:bold">
                                            RENCANA KEPERAWATAN
                                        </h1>
                                    </div>
                                    <VCheckbox class="fontcheckbox" v-model="input.istirahatkan"
                                        true-value="Istirahatkan pasien pada posisi yang nyaman dalam batas yang ditoleransi oleh pasien"
                                        color="primary" circle /><span
                                        v-html="highlightMatch('Istirahatkan pasien pada posisi yang nyaman dalam batas yang ditoleransi oleh pasien')"
                                        class="highlighted-label"></span><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.berikaninfo"
                                        true-value="Berikan informasi tentang nyeri meliputi penyebab, lamanya nyeri berlangsung, faktor yang dapat memperburuk atau meredakan nyeri"
                                        color="primary" circle /><span
                                        v-html="highlightMatch('Berikan informasi tentang nyeri meliputi penyebab, lamanya nyeri berlangsung, faktor yang dapat memperburuk atau meredakan nyeri')"
                                        class="highlighted-label"></span><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.bantupasien"
                                        true-value="Bantu pasien mengidentifikasi tindakan memenuhi kebutuhan rasa nyaman yang telah berhasil dilakukan oleh pasien"
                                        color="primary" circle /><span
                                        v-html="highlightMatch('Bantu pasien mengidentifikasi tindakan memenuhi kebutuhan rasa nyaman yang telah berhasil dilakukan oleh pasien')"
                                        class="highlighted-label"></span><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.observasi"
                                        true-value="Observasi tanda-tanda vital" color="primary" circle /><span
                                        v-html="highlightMatch('Observasi tanda-tanda vital')"
                                        class="highlighted-label"></span><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.ajarkan"
                                        true-value="Ajarkan teknik nonfarmakologis seperti : Relaksasi napas dalam/otot progesif, Distraksi, kompres hangat/dingin, terapi music, massage punggung"
                                        color="primary" circle /><span
                                        v-html="highlightMatch('Ajarkan teknik nonfarmakologis seperti : Relaksasi napas dalam/otot progesif, Distraksi, kompres hangat/dingin, terapi music, massage punggung')"
                                        class="highlighted-label"></span><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.monitor"
                                        true-value="Monitor Frekuensi nafas pasien/ status oksigen pasien"
                                        color="primary" circle />
                                    <span
                                        v-html="highlightMatch('Monitor Frekuensi nafas pasien/ status oksigen pasien')"
                                        class="highlighted-label"></span>
                                    <br>
                                    <VCheckbox class="fontcheckbox" v-model="input.posisikan"
                                        true-value="Posisikan pasien untuk memaksimalkan ventilasi (head up/semifowler)"
                                        color="primary" circle />
                                    <span
                                        v-html="highlightMatch('Posisikan pasien untuk memaksimalkan ventilasi (head up/semifowler)')"
                                        class="highlighted-label"></span>
                                    <br>
                                    <VCheckbox class="fontcheckbox" v-model="input.latihanbatuk"
                                        true-value="Latihan teknik batuk efektif" color="primary" circle />
                                    <span v-html="highlightMatch('Latihan teknik batuk efektif')"
                                        class="highlighted-label"></span>
                                    <br>
                                    <VCheckbox class="fontcheckbox" v-model="input.chest"
                                        true-value="Lakukan chest fisioterapi sesuai indikasi/bila perlu"
                                        color="primary" circle />
                                    <span
                                        v-html="highlightMatch('Lakukan chest fisioterapi sesuai indikasi/bila perlu')"
                                        class="highlighted-label"></span>
                                    <br>
                                    <VCheckbox class="fontcheckbox" v-model="input.berikie"
                                        true-value="Beri KIE tentang tanda-tanda penurunan curah jantung"
                                        color="primary" circle />
                                    <span
                                        v-html="highlightMatch('Beri KIE tentang tanda-tanda penurunan curah jantung')"
                                        class="highlighted-label"></span>
                                    <br>
                                    <VCheckbox class="fontcheckbox" v-model="input.latihrentang"
                                        true-value="Latih rentang pergerakan aktif/pasif untuk memperbaiki kekuatan dan daya tahan otot"
                                        color="primary" circle />
                                    <span
                                        v-html="highlightMatch('Latih rentang pergerakan aktif/pasif untuk memperbaiki kekuatan dan daya tahan otot')"
                                        class="highlighted-label" />
                                    <br>
                                    <VCheckbox class="fontcheckbox" v-model="input.edukasi"
                                        true-value="Edukasi untuk memberikan kompres dengan air biasa/ hangat"
                                        color="primary" circle />
                                    <span
                                        v-html="highlightMatch('Edukasi untuk memberikan kompres dengan air biasa/ hangat')"
                                        class="highlighted-label"></span>
                                    <br>
                                    <VCheckbox class="fontcheckbox" v-model="input.kajidokumentasi"
                                        true-value="Kaji dan dokumentasi frekuensi, warna, konsistensi, jumlah (ukuran) feces, turgor kulit dan kondisi mukosa mulut sebagai indikator dehidrasi"
                                        color="primary" circle />
                                    <span
                                        v-html="highlightMatch('Kaji dan dokumentasi frekuensi, warna, konsistensi, jumlah (ukuran) feces, turgor kulit dan kondisi mukosa mulut sebagai indikator dehidrasi')"
                                        class="highlighted-label"></span>
                                    <br>
                                    <VCheckbox class="fontcheckbox" v-model="input.sarankan"
                                        true-value="Sarankan menghindari makanan yang mengandung lactose, makan makanan yang rendah serat, tinggi kalori dan tinggi protein"
                                        color="primary" circle />
                                    <span
                                        v-html="highlightMatch('Sarankan menghindari makanan yang mengandung lactose, makan makanan yang rendah serat, tinggi kalori dan tinggi protein')"
                                        class="highlighted-label"></span>
                                    <br>
                                    <VCheckbox class="fontcheckbox" v-model="input.imunisasi"
                                        true-value="Lakukan manajemen imunisasi/vaksinasi" color="primary" circle />
                                    <span v-html="highlightMatch('Lakukan manajemen imunisasi/vaksinasi')"
                                        class="highlighted-label"></span>
                                    <br>
                                    <VCheckbox class="fontcheckbox" v-model="input.dukungan"
                                        true-value="Beri dudkungan dalam mengambil keputusan" color="primary"
                                        circle />
                                    <span v-html="highlightMatch('Beri dudkungan dalam mengambil keputusan')"
                                        class="highlighted-label"></span>
                                    <br>
                                    <VCheckbox class="fontcheckbox" v-model="input.kontrol"
                                        true-value="Edukasi dan sarankan untuk kontrol sebagai upaya meningkatkan status kesehatan pasien"
                                        color="primary" circle />
                                    <span
                                        v-html="highlightMatch('Edukasi dan sarankan untuk kontrol sebagai upaya meningkatkan status kesehatan pasien')"
                                        class="highlighted-label"></span>
                                    <br>
                                    <VCheckbox class="fontcheckbox" v-model="input.kaji"
                                        true-value="Kaji integritas kulit" color="primary" circle />
                                    <span v-html="highlightMatch('Kaji integritas kulit')"
                                        class="highlighted-label"></span>
                                    <br>
                                    <VCheckbox class="fontcheckbox" v-model="input.ajarkanteknik"
                                        true-value="Ajarkan teknik nonfarmakologis seperti : Relaksasi napas dalam/ otot progresif, Distraksi, Kompres hangat/ dingin, Terapi music, Massage punggung"
                                        color="primary" circle />
                                    <span
                                        v-html="highlightMatch('Ajarkan teknik nonfarmakologis seperti : Relaksasi napas dalam/ otot progresif, Distraksi, Kompres hangat/ dingin, Terapi music, Massage punggung')"
                                        class="highlighted-label"></span>
                                    <br>
                                    <VCheckbox class="fontcheckbox" v-model="input.identifikasi"
                                        true-value="Identifikasi level cemas pada pasien" color="primary" circle />
                                    <span v-html="highlightMatch('Identifikasi level cemas pada pasien')"
                                        class="highlighted-label"></span>
                                    <br>
                                    <VCheckbox class="fontcheckbox" v-model="input.cemas"
                                        true-value="Berikan pengetahuan yang adekuat tentang penyakit yang diderita untuk mengurangi cemas"
                                        color="primary" circle />
                                    <span
                                        v-html="highlightMatch('Berikan pengetahuan yang adekuat tentang penyakit yang diderita untuk mengurangi cemas')"
                                        class="highlighted-label"></span>
                                    <br>
                                    <VCheckbox class="fontcheckbox" v-model="input.prosedur"
                                        true-value="Jelaskan semua posedur, termasuk beberapa pengalaman sebelum prosedur"
                                        color="primary" circle />
                                    <span
                                        v-html="highlightMatch('Jelaskan semua posedur, termasuk beberapa pengalaman sebelum prosedur')"
                                        class="highlighted-label"></span>
                                    <br>
                                    <VCheckbox class="fontcheckbox" v-model="input.dekatipasien"
                                        true-value="Dekati pasien untuk memberikan rasa aman dan mengurangi rasa takut"
                                        color="primary" circle />
                                    <span
                                        v-html="highlightMatch('Dekati pasien untuk memberikan rasa aman dan mengurangi rasa takut')"
                                        class="highlighted-label"></span>
                                    <br>
                                    <VCheckbox class="fontcheckbox" v-model="input.dengarkan"
                                        true-value="Dengarkan pasien dengan penuh perhatian" color="primary"
                                        circle />
                                    <span v-html="highlightMatch('Dengarkan pasien dengan penuh perhatian')"
                                        class="highlighted-label"></span>
                                    <br>
                                    <VCheckbox class="fontcheckbox" v-model="input.KIEKonseling"
                                        true-value="KIE / Konseling kepatuhan minum obat dan efek samping obat"
                                        color="primary" circle /><span
                                        v-html="highlightMatch('KIE / Konseling kepatuhan minum obat dan efek samping obat')"
                                        class="highlighted-label"></span><br>
                                    <VCheckbox class="fontcheckbox" v-model="input.KIEKontrol"
                                        true-value="KIE Kontrol Rutin" color="primary" circle /><span
                                        v-html="highlightMatch('KIE Kontrol Rutin')"
                                        class="highlighted-label"></span><br>
                                    <div class="columns m-0" v-if="input.KIEKontrol == 'KIE Kontrol Rutin'">
                                        <div class="column is-2">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Poliklinik" label="Poliklinik"
                                                    v-model="input.CBPoliklinik_KIEK" />
                                            </VControl>
                                        </div>
                                        <div class="column is-4" v-if="input.CBPoliklinik_KIEK == 'Poliklinik'">
                                            <h1>Tanggal</h1>
                                            <VDatePicker v-model="input.DPoliklinik_KIEK" mode="date" trim-weeks>
                                                <template #default="{ inputValue, inputEvents }">
                                                    <VControl icon="feather:calendar" fullwidth>
                                                        <VInput :value="inputValue" v-on="inputEvents" />
                                                    </VControl>
                                                </template>
                                            </VDatePicker>
                                        </div>
                                        <div class="column is-4" v-if="input.CBPoliklinik_KIEK == 'Poliklinik'">
                                            <h1>Dirujuk Ke</h1>
                                            <VControl>
                                                <VInput type="text" class="input"
                                                    v-model="input.TBDirujukKe_KIEK" />
                                            </VControl>
                                        </div>
                                    </div>
                                    <VCheckbox class="fontcheckbox" v-model="input.Rencanalainnya1" color="primary"
                                        circle />
                                    <span v-html="highlightMatch('Lainnya')" class="highlighted-label"></span>
                                    <br>
                                    <VControl v-if="input.Rencanalainnya1">
                                        <VInput type="text" class="heightinput input"
                                            v-model.number="input.RencanatextLainnya1" />
                                    </VControl>
                                    <div v-if="input.Rencanalainnya1">
                                        <VCheckbox class="fontcheckbox" v-model="input.Rencanalainnya2"
                                            color="primary" circle /><br>
                                        <VControl v-if="input.Rencanalainnya2">
                                            <VInput type="text" class="heightinput input"
                                                v-model.number="input.RencanatextLainnya2" />
                                        </VControl>
                                        <VCheckbox class="fontcheckbox" v-model="input.Rencanalainnya3"
                                            color="primary" circle v-if="input.Rencanalainnya2" /><br>
                                        <VControl v-if="input.Rencanalainnya3">
                                            <VInput type="text" class="heightinput input"
                                                v-model.number="input.RencanatextLainnya3" />
                                        </VControl>
                                        <VCheckbox class="fontcheckbox" v-model="input.Rencanalainnya4"
                                            color="primary" circle v-if="input.Rencanalainnya3" /><br>
                                        <VControl v-if="input.Rencanalainnya4">
                                            <VInput type="text" class="heightinput input"
                                                v-model.number="input.RencanatextLainnya4" />
                                        </VControl>
                                        <VCheckbox class="fontcheckbox" v-model="input.Rencanalainnya5"
                                            color="primary" circle v-if="input.Rencanalainnya4" /><br>
                                        <VControl v-if="input.Rencanalainnya5">
                                            <VInput type="text" class="heightinput input"
                                                v-model.number="input.RencanatextLainnya5" />
                                        </VControl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>
                <!-- Pengobatan HIV -->
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12 pb-0">
                            <h1 style="font-size:larger;font-weight:bold">
                                DISPOSISI
                            </h1>
                        </div>
                        <div class="column is-4 pb-0" style="font-weight:bold">
                            <VField horizontal>
                                <VControl raw subcontrol>
                                    <VCheckbox class="" color="primary" square true-value="Hidup" label="Hidup"
                                        v-model="input.disHidup" />
                                </VControl>
                                <VControl raw subcontrol>
                                    <VCheckbox class="" color="primary" square true-value="Boleh Pulang" label="Boleh Pulang"
                                        v-model="input.disBolehPulang" />
                                </VControl>
                                <!-- <VControl raw subcontrol>
                                    <VLabel>
                                        Tanggal
                                    </VLabel>
                                    <VDatePicker v-model="input.DMulaiTerapiTB" mode="date" trim-weeks>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" v-on="inputEvents" />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </VControl> -->
                            </VField>
                        </div>
                        <div class="column is-4 mt-3">
                            <VField class="is-flex" horizontal label="Jam Keluar">
                                <VControl>
                                    <VDatePicker v-model="input.disJamkeluar" mode="time" is24Hr>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="lucide:clock" fullwidth>
                                                <VInput :value="inputValue" v-on="inputEvents" />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4 mt-3">
                            <VField class="is-flex" horizontal label="Tanggal">
                                <VControl>
                                    <VDatePicker v-model="input.disTanggal" mode="date" trim-weeks>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" v-on="inputEvents" />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </VControl>
                            </VField>
                        </div>
                        <hr>
                        <div class="column is-12 pb-0" style="font-weight:bold">
                            Kontrol Poliklinik
                        </div>
                        <div class="column is-2">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox class="pt-2" color="primary" square true-value="Ya"
                                        label="Ya" v-model="input.kontrolYa" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField horizontal label="Keterangan">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.kontrolKet" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField class="is-flex" horizontal label="Tanggal">
                                <VControl>
                                    <VDatePicker v-model="input.kontrolTanggal" mode="date" trim-weeks>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" v-on="inputEvents" />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="pt-2" color="primary" square true-value="Tidak"
                                    label="Tidak" v-model="input.kontrolYa" />
                            </VControl>
                        </div>
                        <hr>
                        <div class="column is-4">
                            <h1>Dirujuk Ke</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.kontrolDirujuk" />
                            </VControl>
                        </div>
                        <div class="column is-4">
                            <h1>Transportasi yang dianjurkan</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.kontrolTransport" />
                            </VControl>
                        </div>
                        <div class="column is-4">
                            <h1>Pendamping</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.kontrolPendamping" />
                            </VControl>
                        </div>
                        <hr>
                        <div class="column is-4">
                            <VControl raw subcontrol>
                                <VCheckbox class="pt-2" color="primary" square true-value="Dirawat diruang Intensif"
                                    label="Dirawat diruang Intensif" v-model="input.kontrolIntensif" />
                            </VControl>
                        </div>
                        <div class="column is-4">
                            <h1>Ruang Lain</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.kontrolRuangLain" />
                            </VControl>
                        </div>
                        <div class="column is-4">
                            <h1>Kelas</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.kontrolKelas" />
                            </VControl>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- form baru -->

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
                                    <td class="tg-0lax text-center" width="5%">#</td>
                                    <td class="tg-0lax text-center" width="25%">Tanggal Input</td>
                                    <td class="tg-0lax text-center" width="25%">Tanggal Registrasi</td>
                                    <td class="tg-0lax text-center" width="25%">No Registrasi</td>
                                    <td class="tg-0lax text-center" width="20%">No EMR</td>
                                    <td class="tg-0lax text-center" width="20%">Dokter</td>
                                    <td class="tg-0lax text-center" width="20%">Penyakit</td>
                                    <td class="tg-0lax text-center" width="20%">Section</td>
                                </tr>
                            </thead>
                            <tbody v-for="resep in listTemplate">
                                <tr>
                                    <td style="width:5%;text-align:center">
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="addTemplate(resep)" color="info" v-tooltip-prime.top="'Pilih'">
                                        </VIconButton>
                                    </td>
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
                                    <td style="width:20%;text-align:center">
                                        <span class="mb-2">{{ resep.riwayatpenyakit }}</span><br>
                                    </td>
                                    <td style="width:25%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
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
        @close="isAlltemplate = false; showModalTemplateFix = false">
        <template #content>
            <DataTable :pt="{
                table: { style: 'min-width: 50rem; min-height: 10rem;' },
                column: {
                    bodycell: ({ state }) => ({
                        class: [{ 'pt-0 pb-0': state['d_editing'] }]
                    })
                }
            }" v-model:filters="filtersTemplate" :value="listTemplateFix" :metaKeySelection="false" :rows="8"
                :loading="isLoading" paginator tableStyle="min-width: 50rem" dataKey="no"
                :totalRecords="listTemplateFix.length" :globalFilterFields="['namatemplate', 'registrasi.namaruangan']"
                responsiveLayout="stack" breakpoint="960px">
                <template #header>
                    <div class="columns is-multiline">
                        <div class="column is-8">
                            <VField>
                                <InputText v-model="filtersTemplate['global'].value"
                                    placeholder="Search Nama Template" />
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField>
                                <VControl>
                                    <VSwitchBlock v-model="isAlltemplate" color="success" label="Semua Template" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </template>
                <template #empty> No customers found. </template>
                <template #loading>
                    <img src="/images/other/loadingspin.gif" alt="Loading..." width="100" />
                    <p style="color:white">Loading data, please wait...</p>
                </template>
                <Column headerStyle="width: 8rem">
                    <template #body="slotProps">
                        <VButtons>
                            <VIconButton color="danger" light raised circle icon="lucide:x"
                                @click="deleteTemplate(slotProps.data.id)" v-if="!isAlltemplate"
                                v-tooltip-prime.top="'Hapus'" />
                            <VIconButton type="button" raised circle icon="fas fa-plus"
                                @click="addTemplate(slotProps.data)" color="info" v-tooltip-prime.top="'Pilih'">
                            </VIconButton>
                        </VButtons>
                    </template>
                </Column>
                <Column field="namatemplate" header="Nama" :sortable="true"></Column>
                <Column field="registrasi.namaruangan" header="Nama Ruangan" :sortable="true">
                    <template #body="slotProps">
                        {{ slotProps.data.registrasi.namaruangan }}
                    </template>
                </Column>
                <Column field="created_at" header="Tanggal" :sortable="true">
                    <template #body="slotProps">
                        <span>{{ H.formatDateToLocalString(slotProps.data.created_at) }}</span>
                    </template>
                </Column>
            </DataTable>
        </template>
    </VModal>

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
            <VButton type="button" color="primary" raised @click="addToInput()">
                Tambah
            </VButton>
        </template>
    </VModal>


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
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from "primevue/useconfirm"
import moment from 'moment'
import { useToaster } from '/@src/composable/toaster'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';


useHead({
    title: 'Asesmen Keperawatan VCT - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let detailSkriningNutrisi = ref(EMR.detailSkriningNutrisi())
let detailStatusFungsional = ref(EMR.detailStatusFungsional())
let statusFungsional: any = ref(EMR.statusFungsional())

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

const filtersTemplate = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS }
});
const route = useRoute()
const pasien: any = ref({})
const d_pegawai: any = ref([])
const metaKey = ref(true);
const loadData: any = ref(true)
const listSIMRSLama: any = ref([])
const showModalObat: any = ref(false);
const ObatSelected: any = ref()
const isAlltemplate: any = ref(false);
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

const COLLECTION: any = ref('AsesmenKeperawatanVCT') //table mongodb

const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    kebjamKedatangan: new Date(),
    kebjamAsesmenAwal: new Date(),
    kebtanggalKedatangan: new Date(),
    nilaiSkrining: 0,
    penurunanbb: 0,
    penurunannafsu: 0,
    penurunanbbYa: 0,
    nilai: "RISIKO RENDAH (MST 0-1)",
    perawat: '',

    mengontrolbab: 0,
    mengontrolbak: 0,
    bersihdiri: 0,
    toilet: 0,
    makan: 0,
    berpindahtt: 0,
    mobilisasi: 0,
    berpakaian: 0,
    tangga: 0,
    mandi: 0,
    nilaimandi: 0,
    CBKetergantunganTotal: "Ketergantungan total (0-4)",

    hasiljatuh: 'tidak berisiko',
    caraduduk: null,
    kursi: null,
    details: [{
        no: 1,
    }],
    details2: [{
        no: 1,
    }],
    disJamkeluar: new Date(),
    disTanggal: new Date(),
    kontrolTanggal: new Date()
})
const addNewItem = () => {
    let newItem: any = {}
    newItem = {
        no: input.value.details[input.value.details.length - 1].no + 1,
    }
    input.value.details.push(newItem);
}
const removeItem = (index: any) => {
    input.value.details.splice(index, 1)
}
const addNewItem2 = () => {
    let newItem: any = {}
    newItem = {
        no: input.value.details2[input.value.details2.length - 1].no + 1,
    }
    input.value.details2.push(newItem);
}
const removeItem2 = (index: any) => {
    input.value.details2.splice(index, 1)
}
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
const d_allo: any = ref([{ value: 'Suami/Istri', label: 'Suami/Istri' }, { value: 'Orang Tua', label: 'Orang Tua' }, { value: 'Anak', label: 'Anak' }, { value: 'Pasien', label: 'Pasien' }, { value: 'Lainnya', label: 'Lainnya' }])
const d_mata: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_gcse: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }])
const d_gcsv: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }])
const d_gcsm: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }, { value: 6, label: '6' }])
const d_angkanyeri: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }, { value: 6, label: '6' }, , { value: 7, label: '7' }, { value: 8, label: '8' }, { value: 9, label: '9' }, { value: 10, label: '10' }])
const d_keadaanumum: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Sedang' }, { value: 3, label: 'Buruk' }])
const d_freknyeri: any = ref([{ value: 1, label: 'Jarang' }, { value: 2, label: 'Hilang Timbul' }, { value: 3, label: 'Terus Menerus' }])
const d_nyeri: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_klinik: any = ref([{ value: 1, label: 'Tanpa ART' }, { value: 2, label: 'Dengan ART' }])
const d_entrypoint: any = ref([{ value: 1, label: 'KIA' }, { value: 2, label: 'Rawat Jalan' }, { value: 3, label: 'Rawat Inap' }, { value: 4, label: 'Praktek Swasta' }, { value: 5, label: 'Jangkauan' }, { value: 6, label: 'LSM' }, { value: 7, label: 'Datang Sendiri' }, { value: 8, label: 'Lainnya' }])
const d_menjalarnyeri: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_jenisnyeri: any = ref([{ value: 1, label: 'Akut' }, { value: 2, label: 'Kronis' }])
const d_kualitasnyeri: any = ref([{ value: 1, label: 'Tumpul' }, { value: 2, label: 'Tajam' }, { value: 3, label: 'Panas/Terbakar' }, { value: 4, label: 'Lain-lain' }])
const d_gangguanpsikologis: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Gelisah' }, { value: 3, label: 'Takut' }, { value: 4, label: 'Sedih' }, { value: 5, label: 'Rendah diri' }, { value: 6, label: 'Acuh tak acuh' }, { value: 7, label: 'Mudah tersinggung' }, { value: 8, label: 'Menarik diri' }])
const d_masalahkawin: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Ada' }])
const d_kekerasan: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Ada' }])
const d_pembiayaankesehatan: any = ref([{ value: 1, label: 'Biaya sendiri/keluarga' }, { value: 2, label: 'Asuransi Lainnya' }])
const d_rohaniawan: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_penurunanbb: any = ref([{ value: 1, label: 'Tidak' }, { value: 2, label: 'Tidak Yakin' }])
const d_penurunannafsu: any = ref([{ value: 1, label: 'Ya' }, { value: 0, label: 'Tidak' }])
const d_statusfungsi: any = ref([{ value: 1, label: 'Mandiri' }, { value: 2, label: 'Perlu Bantuan' }, { value: 3, label: 'Ketergantungan Total' }])
const d_penurunanbbYa: any = ref([
    { value: 0, label: 'YA' },
    { value: 2, label: 'Tidak Yakin' },
    { value: 2, label: 'Tidak Yakin' },
    { value: 1, label: '1-5 kg' },
    { value: 3, label: '6-10 kg' },
    { value: 4, label: '11-15 kg' },
    { value: 5, label: '>15 kg' }
])
const d_Pendidikan: any = ref([
    { value: 'Tidak Sekolah', label: 'Tidak Sekolah' },
    { value: 'SD', label: 'SD' },
    { value: 'SMP', label: 'SMP' },
    { value: 'SMU', label: 'SMU' },
    { value: 'Perguruan Tinggi', label: 'Perguruan Tinggi' }
])
const d_StatusPekerjaan: any = ref([
    { value: 'Tidak Bekerja', label: 'Tidak Bekerja' },
    { value: 'Bekerja', label: 'Bekerja' }
])
const d_FaktorResiko: any = ref([
    { value: 'Heteroseksual', label: 'Heteroseksual' },
    { value: 'Homoseksual', label: 'Homoseksual' },
    { value: 'Biseksual', label: 'Biseksual' },
    { value: 'Perinatal', label: 'Perinatal' },
    { value: 'Transfusi Darah', label: 'Transfusi Darah' },
    { value: 'Napza Suntik', label: 'Napza Suntik' },
    { value: 'Pasangan ODHA', label: 'Pasangan ODHA' },
    { value: 'Pasangan NAPZA suntik', label: 'Pasangan NAPZA suntik' },
    { value: 'Lainnya', label: 'Lainnya' },
])
const d_StatusPernikahan: any = ref([
    { value: 'Menikah', label: 'Menikah' },
    { value: 'Belum Menikah', label: 'Belum Menikah' },
    { value: 'Janda/duda', label: 'Janda/duda' }
])
const d_hiv: any = ref([
    { value: 'Reaktif', label: 'Reaktif' },
    { value: 'Non Reaktif', label: 'Non Reaktif' }
])
const d_art: any = ref([
    { value: 'Tidak', label: 'Tidak' },
    { value: 'Ya', label: 'Ya' }
])
const d_stadWHO: any = ref([
    { value: 'I', label: 'I' },
    { value: 'II', label: 'II' },
    { value: 'III', label: 'III' },
    { value: 'VI', label: 'IV' }
])
const d_statusFungsional: any = ref([
    { value: 'Kerja', label: 'Kerja' },
    { value: 'Ambulatori', label: 'Ambulatori' },
    { value: 'Baring', label: 'Baring' }
])
const d_TA: any = ref([
    { value: 'AZT+3TC+NVP', label: 'AZT+3TC+NVP' },
    { value: 'AZT+3TC+EFV', label: 'AZT+3TC+EFV' },
    { value: 'TDF+3TC+NVP', label: 'TDF+3TC+NVP' },
    { value: 'TDF/3TC/EFV', label: 'TDF/3TC/EFV' },
    { value: 'TDF/3TC/DTG', label: 'TDF/3TC/DTG' },
    { value: 'Lainnya', label: 'Lainnya' }
])

const d_diagnosakhusus: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_mengontrolbab: any = ref([{ value: 1, label: 'Inkontinen/tidak teratur (perlu enema)' }, { value: 2, label: 'Kadang inkontinen (1xseminggu)' }, { value: 3, label: 'Kontinen teratur' }])
const d_mengontrolbak: any = ref([{ value: 1, label: 'Inkontinen/pakai kateter dan tidak terkontrol' }, { value: 2, label: 'Kadang inkontinen (max 1x24 jam)' }, { value: 3, label: 'Mandiri' }])
const d_bersihdiri: any = ref([{ value: 1, label: 'Butuh pertolongan orang lain' }, { value: 2, label: 'Mandiri' }])
const d_toilet: any = ref([{ value: 1, label: 'Tergantung pertolongan orang lain' }, { value: 2, label: 'Perlu pertolongan pada beberapa aktivitas terapi dan dapat mengerjakan sendiri beberapa aktivitas lain' }, { value: 3, label: 'Mandiri' }])
const d_makan: any = ref([{ value: 1, label: 'Tidak mampu' }, { value: 2, label: 'Perlu seseorang menolong memotong makanan' }, { value: 3, label: 'Mandiri' }])
const d_berpindahtt: any = ref([{ value: 1, label: 'Tidak Mampu' }, { value: 2, label: 'Perlu banyak bantuan untuk bisa duduk (2 orang)' }, { value: 3, label: 'Bantuan 1 orang' }, { value: 4, label: 'Mandiri' }])
const d_mobilisasi: any = ref([{ value: 1, label: 'Tidak Mampu' }, { value: 2, label: 'Dengan kursi roda' }, { value: 3, label: 'Bantuan 1 orang' }, { value: 4, label: 'Mandiri' }])
const d_berpakaian: any = ref([{ value: 1, label: 'Tergantung orang lain' }, { value: 2, label: 'Sebagian dibantu (misal mengancing baju)' }, { value: 3, label: 'Mandiri' }])
const d_tangga: any = ref([{ value: 1, label: 'Tidak Mampu' }, { value: 2, label: 'Butuh Pertolongan' }, { value: 3, label: 'Mandiri' }])
const d_mandi: any = ref([{ value: 1, label: 'Teragantung orang lain' }, { value: 2, label: 'Mandiri' }])
const d_caraduduk: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_kursi: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_tindakan: any = ref([{ value: 1, label: 'Tidak ada tindakan' }, { value: 2, label: 'Edukasi' }, { value: 3, label: 'Pasang penanda resiko jatuh' }])
const listTemplate: any = ref([])
const listTemplateFix: any = ref([])
const showModalTemplate: any = ref(false)
const showModalTemplateFix: any = ref(false)
const filterMenu: any = ref('')

const confirm = useConfirm();
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
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
watch([() => input.value.caraduduk, () => input.value.kursi], ([caraduduk, kursi]) => {
    if (caraduduk == null && kursi == null) {
        input.value.hasiljatuh = "Tidak Berisiko";
    } else if (caraduduk == 2 && kursi == 2) {
        input.value.hasiljatuh = "Tidak Berisiko";
    } else if (caraduduk == 1 && kursi == 1) {
        input.value.hasiljatuh = "Risiko Tinggi";
    } else if (caraduduk != null || kursi != null) {
        input.value.hasiljatuh = "Risiko Sedang";
    }
});
const loadRiwayat = async () => {
    isLoading.value = true
    let responsex = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    isLoading.value = false
    if (responsex.length) {
        input.value = responsex[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = responsex[0].emrpasienfk
        }
    } else {
        isLoading.value = true
        input.value.TBWargaNegara = props.pasien.objectkebangsaanfk == 1 ? 'WNI' : (props.pasien.objectkebangsaanfk == 2 ? 'WNA KITAS' : 'WNA NON KITAS')
        input.value.TBAgama = props.pasien.agama
        input.value.TBNoTelepon = props.pasien.notelepon ? props.pasien.notelepon : props.pasien.nohp;
        input.value.TBPembiayaanKesehatan = props.registrasi.kelompokpasien
        const NS = await useApi().get("emr/auto-fill?norec_pd=" + props.registrasi.norec_pd + "&collection=AsesmenAwalKeperawatanPasienRawatJalanNurse" + "&field=tekananDarahObgyn,nafasObgyn,keadaanumumobgyn,keadaanumum,celciusObgyn,nadiObgyn,sao2Obgyn,gcse,gcsv,gcsm,kebpilihanallo,keluhanutama,riwayatpenyakit,riwayatpenyakitdahulu,riwayatpengobatan,riwayatpenyakitkeluarga,riwayatalergi,beratbadanObgyn,tinggibadanObgyn")
        if (NS != null) {
            input.value.riwayatalergi = NS.riwayatalergi
            input.value.riwayatpenyakitkeluarga = NS.riwayatpenyakitkeluarga
            input.value.riwayatpenyakitdahulu = NS.riwayatpenyakitdahulu
            input.value.tekananDarahObgyn = NS.tekananDarahObgyn
            input.value.nadiObgyn = NS.nadiObgyn
            input.value.nafasObgyn = NS.nafasObgyn
            input.value.celciusObgyn = NS.celciusObgyn
            input.value.sao2Obgyn = NS.sao2Obgyn
            input.value.tinggibadanObgyn = NS.tinggibadanObgyn
            input.value.beratbadanObgyn = NS.beratbadanObgyn
            input.value.gcse = NS.gcse
            input.value.gcsv = NS.gcsv
            input.value.gcsm = NS.gcsm
            input.value.keadaanumumobgyn = NS.keadaanumumobgyn
            input.value.kebpilihanallo = NS.kebpilihanallo
        }
        const responseTglRuangan = await useApi().get(`/emr/get-emr-tgl-terakhir-dengan-ruangan?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`)
        const responseHistori = await useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`)
        isLoading.value = false
        if (responseTglRuangan.length && responseHistori.length) {
            console.log("Ruangan dulu : " + responseTglRuangan[0].registrasi.namaruangan)
            var tgl_EMR_terakhir = moment(responseTglRuangan[0].created_at).format("DD-MM-YYYY");
            var convertTgl = moment(tgl_EMR_terakhir, 'DD-MM-YYYY');
            var tgl_Sekarang = moment();
            const calculateDays = tgl_Sekarang.diff(convertTgl, 'days');
            console.log("Tanggal EMR terakhir : " + convertTgl);
            console.log("Tanggal Sekarang : " + tgl_Sekarang);
            console.log("Total hari : " + calculateDays)
            if (responseTglRuangan[0].registrasi.namaruangan.trim() == H.setObjectRegistrasi(pasien.value.registrasi).namaruangan.trim() && calculateDays < 90) {
                confirm.require({
                    message: 'Asesmen Keperawatan sudah pernah diinput ' + calculateDays + ' hari dari tanggal registrasi pasien ini. Apakah anda ingin melihat riwayat terakhirnya ?',
                    group: 'templating',
                    header: 'Informasi Asesmen Keperawatan',
                    icon: 'pi pi-exclamation-circle',
                    accept: () => {
                        if (responseHistori.length) {
                            input.value = responseHistori[0] //set ke inputan
                            input.value.namatemplate = ''
                            // console.log(input.value)
                        } else {
                            H.alert('warning', 'Data tidak ada')
                        }
                    },
                    reject: () => { }
                })
            }
        } else {
            console.log('Data EMR sebelumnya tidak ada!')
        }
        console.log("Ruangan pasien sekarang : " + H.setObjectRegistrasi(pasien.value.registrasi).namaruangan)
    }
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    if (!input.value.perawat) {
        useToaster().warn('Perawat Harus di isi !')
        return
    }
    if (input.value.kebrujukan == 'TIDAK') {
        if (input.value.kebrujuklanjutan == 'DIANTAR') {
            input.value.kebketrujukan = input.value.kebketrujukan;
        }
    }

    if (input.value.kebpilihanallo == 'Lainnya') {
        input.value.kebpilihanallo = input.value.keballoanamnesis;
    }

    if (input.value.kualitasnyeri == 'LAINNYA') {
        input.value.kualitasnyeri = input.value.kualitasnyerilain
    }

    if (input.value.pembiayaankesehatan == 'ASURANSI') {
        input.value.pembiayaankesehatan = input.value.ketpembiayaankesehatan
    }

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
    useApi().post(
        `/emr/simpan-emr`, json).then((response: any) => {
            isLoading.value = false
            // NOREC_EMRPASIEN.value = response.norec_emr
        }).catch((e: any) => {
            isLoading.value = false
        })

    // console.log(resultValue)
}

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
    console.log(listSIMRSLama.value)

}

const simpanTemplate = () => {
    if(!input.value.namatemplate) {
    H.alert('warning', "Nama Template wajib diisi")
    return;
  }
    let ID = input.id ? input.id : ''

    let object: any = {}

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
    isLoading.value = true

    useApi().post(
        `/emr/simpan-emr-template`, json).then((response: any) => {
            isLoading.value = false
            input.value.namatemplate = null
        }).catch((e: any) => {
            isLoading.value = false
        })
}

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

const pilihTemplateFix = async (index: any) => {
    isLoading.value = true
    useApi().get(
        `/emr/get-emr-template?collection=${COLLECTION.value}&isAll=${isAlltemplate.value}`).then((responselast: any) => {
            isLoading.value = false
            if (responselast.length) {
                for (var x = 0; x < responselast.length; x++) {
                    responselast[x].no = x + 1
                    // responselast[x].id = ''
                }
                listTemplateFix.value = responselast //set ke inputan
                showModalTemplateFix.value = true
            } else {
                H.alert('warning', 'Data tidak ada')
            }
        })
}

const kembaliKeun = () => {
    window.history.back()
}

const addToInput = (event) => {
    console.log("obat selected", ObatSelected)
    let inputss = input.value.riwayatobat == undefined ? '' : input.value.riwayatobat;
    if (ObatSelected.value.length > 0) {
        ObatSelected.value.forEach((obt, ind) => {
            inputss += ` # ${obt.namaobat} `
        })
    }
    input.value.riwayatobat = inputss
    showModalObat.value = false;
}



const fetchPasien = () => {
    pasien.value = props.pasien
    pasien.value.registrasi = props.registrasi
    NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
    console.log(norec_emr)
}

const fetchDokter = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=100`
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
            input.value.beratbadanObgyn = response.beratBadan
            input.value.tinggibadanObgyn = response.tinggiBadan
            input.value.IMT = response.IMT
            input.value.lingkarPerut = response.lingkarPerut
            input.value.nadiObgyn = response.nadi
            input.value.celciusObgyn = response.suhu
            input.value.tekananDarahObgyn = response.tekananDarah
            input.value.nafasObgyn = response.pernapasan
            input.value.sao2Obgyn = response.SPO2
        }
    })
}

const deleteTemplate = (idTemplate) => {
    isLoading.value = true
    let json = {
        'id': idTemplate,
        'collection': COLLECTION.value
    }
    useApi().post(
        `/emr/hapus-template`, json).then((response: any) => {
            if (response.status !== 500) {
                isLoading.value = false
                isAlltemplate.value = false;
                H.alert('sucess', response.message);
                pilihTemplateFix();
            } else {
                H.alert('danger', response.message);
            }
        }).catch((e: any) => {
            isLoading.value = false
            H.alert('danger', e);
        })
}

const addTemplate = (response) => {
    console.log(response);
    input.value = response;
    input.value.namatemplate = null;
    isAlltemplate.value = false;
    showModalTemplateFix.value = false;
    H.alert('success', 'Berhasil ditambahkan');
};

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


watch(() => [input.value.penurunanbb, input.value.penurunannafsu, input.value.penurunanbbYa], ([newValuePenurunanBB, newValuePenurunanBBYa, newValuePenurunanNafsu]) => {
    let totalNilaiSkriningKalkulasi
    //? Mencegah value checbox dari undefined
    newValuePenurunanBB = newValuePenurunanBB ?? 0;
    newValuePenurunanBBYa = newValuePenurunanBBYa ?? 0;
    newValuePenurunanNafsu = newValuePenurunanNafsu ?? 0;

    //? Calculate total Skrining Nutrisi
    totalNilaiSkriningKalkulasi = newValuePenurunanBB + newValuePenurunanBBYa + newValuePenurunanNafsu
    // input.value.nilaiSkrining = totalNilaiSkriningKalkulasi

    if (totalNilaiSkriningKalkulasi >= 0 && totalNilaiSkriningKalkulasi <= 1) {
        input.value.nilaiSkrining = "RISIKO RENDAH";
    } else if (totalNilaiSkriningKalkulasi >= 3 && totalNilaiSkriningKalkulasi <= 4) {
        input.value.nilaiSkrining = "RISIKO SEDANG";
    } else if (totalNilaiSkriningKalkulasi >= 5) {
        input.value.nilaiSkrining = "RISIKO TINGGI";
    }
});

watch(isAlltemplate, (newValue) => {
    pilihTemplateFix()
})

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

const diagnosesOptions = [
    { text: "HIV Counselling", value: "HIV Counselling1" },
    { text: "Nyeri akut b/d kondisi fisik", value: "nyeriakut" },
    { text: "Bersihan jalan nafas tidak efektif b/d alergi jalan nafas, adanya eksudat di jalan nafas /", value: "Bersihan" },
    { text: "Risiko / Penurunan curah jantung b/d anomaly jantung / peningkatan beban", value: "Risiko" },
    { text: "Risiko / Kekurangan Volume Cairan b/d kehilangan volume cairan ", value: "Risiko Cairan" },
    { text: "Kurang pengetahuan tentang penyakit, rencana tindakan dan pengobatan b/d kurang terpanjannya", value: "Kurang pengetahuan" },
    { text: "Ansietas b/d krisis situasi, kebutuhan yang tidak", value: "Ansietas b/d" },
    { text: "Risiko gangguan integritas", value: "kulit" },
    { text: "Kelebihan volume cairan b/d asupan cairan", value: "berlebihan" },
    { text: "Kesiapan meningkatkan status kesehatan", value: "kesiapan status" },
    { text: "Ketidakefektifan pemeliharaan kesehatan b/d hambatan", value: "kognitif" },
    { text: "Hambatan mobilitas fisik b/d intoleran", value: "aktivitas" },
    { text: "Diare akut b/d mal absorpsi, peningkatan motilitas", value: "usus" },
    { text: "Nausea b/d biofisik, psikologis, pemberian kemoterapi, pemberian ", value: "steroid" },
    { text: "Risiko Ketidakstabilan kadar Glukosa Darah b/d Kurang pengetahuan tentang manajemen diabetes, Asupan diet,", value: "Pemantauan glukosa darah tidak adekuat" },
    { text: "Hipertemia b/d kekurangan cairan, proses infeksi, gangguan", value: "termoregulasi" },
    { text: "Gangguan fungsi", value: "gangguan fungsi" },
    { text: "Gangguan jaringan keras", value: "gangguan keras" },
    { text: "Gangguan jaringan lunak dan pendukung", value: "gangguan lunak" },
    { text: "Gangguan", value: "gangguan" },
    { text: "Gangguan persepsi ", value: "gangguan persepsi" },
    { text: "Risiko jatuh b/d riwayat terjatuh / usia lebih dari 65 th / menggunakan alat bantu (walker, tongkat, kursi roda) / ", value: "sulit penglihatan" },
    { text: "Pola pemberian ASI tidak efektif b/d ketidakefektifan pemeliharaan", value: "kesehatan" },
    { text: "Lainnya", value: "lainnya1" }
];

const filteredDiagnoses = computed(() => {
    const term = filterMenu.value.toLowerCase();
    return diagnosesOptions.filter(diagnosis =>
        diagnosis.text.toLowerCase().includes(term)
    );
});

function highlightMatch(text) {
    if (!filterMenu.value) return text;

    const term = new RegExp(`(${filterMenu.value})`, 'gi');
    return text.replace(term, '<span style="background-color: yellow;">$1</span>');
}

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/asesmen-awal.scss';

th {
    text-align: center !important;
    vertical-align: middle !important;
    border: 1px solid black !important;
}

.vm {
    vertical-align: middle;
}

.bold {
    font-weight: bold;
}

hr {
    background-color: hsl(0deg 6.81% 88.68%);
    border: none;
    display: block;
    height: 2px;
    margin: 1rem 0;
}

.table.is-borderless th,
tr,
td {
    border: none !important;
    background-color: transparent !important;
}

hr {
    background-color: hsl(0deg 6.81% 88.68%);
    border: none;
    display: block;
    height: 2px;
    margin: 0px;
}

.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 150%;
}

.tg2 {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
}

.tg2 td {
    // border-color: var(--fade-grey-dark-2);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg2 th {
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

.fontcheckbox {
    padding: 0px;
    padding-top: 5px;
    padding-left: 5px;
}

.fontcheckbox label {
    color: black;
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

.border {
    border: 1px solid black !important;
}

.center {
    text-align: center !important;
}

.tg .tg-0lax {
    text-align: left;
    vertical-align: middle
}

mark {
    background-color: yellow;
    /* Pastikan warna yang Anda inginkan ditulis di sini */
    color: black;
    /* Warna teks jika perlu */
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type=number] {
    -moz-appearance: textfield;
    /* Firefox */
}

.is-autocomplete-select .multiselect .multiselect-single-label,
.is-autocomplete-select .multiselect .multiselect-placeholder {
    padding-left: auto !important;
}
</style>