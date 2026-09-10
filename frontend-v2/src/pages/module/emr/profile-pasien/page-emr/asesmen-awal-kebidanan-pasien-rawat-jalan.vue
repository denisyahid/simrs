<template>
    <ConfirmDialog />
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>{{ props.FORM_NAME }}</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate"
                                @kembaliKeun="kembaliKeun" isHideCetak="true"></ButtonEmr>
                        </div>
                    </div>
                </div>

                <div class="column is-12">
                    <div class="column is-12 buttons mb-0 mt-0" style="vertical-align:middle">
                        <VButton type="button" rounded outlined color="primary" raised icon="feather:folder"
                            isLoading="false" @click="pilihTemplateFix(index)"> Pilih Template
                        </VButton>
                        <VButton type="button" rounded outlined color="info" raised icon="feather:file-text"
                            isLoading="false" @click="pilihTemplate(index)"> Pilih Riwayat
                        </VButton>
                    </div>

                    <hr class="mt-0">

                    <div class="column is-12 pt-0 pb-0">
                        <h1>Nama Template&emsp;&emsp;
                            <span style="color:red">**Hanya diisi jika ingin membuat template</span>
                        </h1>
                        <VField>
                            <VControl>
                                <VInput v-model="input.namatemplate" type="text" placeholder="Nama Template"
                                    class="input" />
                            </VControl>
                        </VField>
                    </div>

                    <hr class="mb-0">

                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-4">
                                <h1>Tanggal Kedatangan</h1>
                                <VDatePicker v-model="input.kebtanggalKedatangan" mode="date" trim-weeks
                                    :max-date="new Date()">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </div>
                            <div class="column is-4">
                                <h1>Jam Kedatangan</h1>
                                <VDatePicker v-model="input.kebjamKedatangan" color="green" mode="time" is24hr>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VControl icon="feather:clock">
                                                <VInput class="input form-timepicker" :value="inputValue"
                                                    v-on="inputEvents" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </div>
                            <div class="column is-4">
                                <h1>Jam Asesmen Awal</h1>
                                <VDatePicker v-model="input.kebjamAsesmenAwal" color="green" mode="time" is24hr>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VControl icon="feather:clock">
                                                <VInput class="input form-timepicker" :value="inputValue"
                                                    v-on="inputEvents" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12 pt-0 pb-0">
                        <div class="columns">
                            <div class="column is-4">
                                <h1>Rujukan</h1>
                                <Multiselect v-model="input.SRujukan" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_rujukan" :searchable="true" track-by="label" mode="single"
                                    autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-4" v-if="input.SRujukan == 1">
                                <h1>&nbsp;</h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" placeholder="Keterangan Rujukan"
                                            v-model="input.kebketrujukan" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>

                    <hr class="mb-0">

                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-12 pb-0">
                                <h1>Alloanamnesis</h1>
                            </div>
                            <div class="column is-4 pt-2">
                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                    <VControl icon="feather:search">
                                        <Multiselect v-model="input.kebpilihanallo" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_allo" :searchable="true"
                                            track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-12 pt-0">
                                <VField>
                                    <VControl>
                                        <VTextarea v-model="input.keballoanamnesis" rows="3">
                                        </VTextarea>
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="column is-12 pt-0">
                        <div class="columns is-multiline">
                            <div class="column is-12 pt-0">
                                <h1 class="pb-3">Anamnesis</h1>
                                <div class="columns is-multiline">
                                    <div class="column is-6">
                                        <h1>Keluhan Utama</h1>
                                        <VField>
                                            <VControl>
                                                <VTextarea v-model="input.keluhanutama" rows="3">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-6">
                                        <h1>Riwayat penyakit sekarang</h1>
                                        <VField>
                                            <VControl>
                                                <VTextarea v-model="input.riwayatpenyakit" rows="3">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-6">
                                        <h1>Riwayat penyakit terdahulu</h1>
                                        <VField>
                                            <VControl>
                                                <VTextarea v-model="input.riwayatpenyakitdahulu" rows="3">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-6">
                                        <h1>Riwayat pengobatan</h1>
                                        <VField>
                                            <VControl>
                                                <VTextarea v-model="input.riwayatpengobatan" rows="3">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-6">
                                        <h1>Riwayat penyakit keluarga</h1>
                                        <VField>
                                            <VControl>
                                                <VTextarea v-model="input.riwayatpenyakitkeluarga" rows="3">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-6">
                                        <h1>Riwayat alergi</h1>
                                        <VField>
                                            <VControl>
                                                <VTextarea v-model="input.riwayatalergi" rows="3">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-12">
                                <h1 class="mb-2">Riwayat menstruasi:</h1>
                                <div class="columns is-multiline">
                                    <div class="column is-3">
                                        <div class="columns is-multiline">
                                            <div class="column is-5">
                                                <h1>Menarche umur:</h1>
                                            </div>
                                            <div class="column is-7">
                                                <VField addons>
                                                    <VControl expanded>
                                                        <VInput type="text" class="input" placeholder=""
                                                            v-model="input.menarcheumur" />
                                                    </VControl>
                                                    <VControl class="field-addon-body">
                                                        <VButton static disabled>tahun</VButton>
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-3">
                                        <div class="columns is-multiline">
                                            <div class="column is-5">
                                                <h1>Siklus:</h1>
                                            </div>
                                            <div class="column is-7">
                                                <VField addons>
                                                    <VControl expanded>
                                                        <VInput type="text" class="input" placeholder=""
                                                            v-model="input.siklus" />
                                                    </VControl>
                                                    <VControl class="field-addon-body">
                                                        <VButton static disabled>hari</VButton>
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-3">
                                        <div class="columns is-multiline">
                                            <div class="column is-5">
                                                <h1>Lama:</h1>
                                            </div>
                                            <div class="column is-7">
                                                <VField addons>
                                                    <VControl expanded>
                                                        <VInput type="text" class="input" placeholder=""
                                                            v-model="input.lama" />
                                                    </VControl>
                                                    <VControl class="field-addon-body">
                                                        <VButton static disabled>hari</VButton>
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-3">
                                        <div class="columns is-multiline">
                                            <div class="column is-5">
                                                <h1>Volume:</h1>
                                            </div>
                                            <div class="column is-7">
                                                <VField addons>
                                                    <VControl expanded>
                                                        <VInput type="text" class="input" placeholder=""
                                                            v-model="input.volume" />
                                                    </VControl>
                                                    <VControl class="field-addon-body">
                                                        <VButton static disabled>cc</VButton>
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-12">
                                <div class="columns is-multiline">
                                    <div class="column is-3">
                                        <div class="columns is-multiline">
                                            <div class="column is-5">
                                                <h1>Keluhan saat haid:</h1>
                                            </div>
                                            <div class="column is-7">
                                                <VField addons>
                                                    <VControl expanded>
                                                        <VInput type="text" class="heightinput input" placeholder=""
                                                            v-model="input.keluhanhaid" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-3">
                                        <div class="columns is-multiline">
                                            <div class="column is-5">
                                                <VCheckbox class="fontcheckbox" v-model="input.teratur"
                                                    true-value="Teratur" label="Teratur" color="primary" circle />
                                            </div>
                                            <div class="column is-7">
                                                <VCheckbox class="fontcheckbox" v-model="input.tidakteratur"
                                                    true-value="Tidak Teratur" label="Tidak Teratur" color="primary"
                                                    circle />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-12">
                                <h1>Riwayat kehamilan, persalinan dan nifas yang lalu:</h1>
                            </div>
                            <div class="column is-12">
                                <div style="overflow-y:auto;" class="mt-1">
                                    <table class="table-rpo tabels" border="1"
                                        style="width: 200%; border: 1px solid black;">
                                        <thead>
                                            <tr>
                                                <th class="th-po" style="vertical-align: inherit;text-align:center"
                                                    rowspan="3">Tgl
                                                    Partus</th>
                                                <th class="th-po" style="vertical-align: inherit;text-align:center"
                                                    colspan="3">Umur
                                                    Hamil</th>
                                                <th class="th-po" style="vertical-align: inherit;text-align:center"
                                                    rowspan="3" width="15%">
                                                    Jenis Partus</th>
                                                <th class="th-po" style="vertical-align: inherit;text-align:center"
                                                    colspan="2">
                                                    Penolong</th>
                                                <th class="th-po" style="vertical-align: inherit;text-align:center"
                                                    colspan="3">Anak
                                                </th>
                                                <th class="th-po" style="vertical-align: inherit;text-align:center"
                                                    colspan="3">
                                                    Keadaan Anak Sekarang</th>
                                                <th class="th-po" style="vertical-align: inherit;text-align:center"
                                                    rowspan="3">
                                                    Keterangan / Komplikasi</th>
                                                <th class="th-po" style="vertical-align: inherit;text-align:center;"
                                                    rowspan="3">#
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="th-po" style="vertical-align: inherit;text-align:center"
                                                    rowspan="2">
                                                    Abortus</th>
                                                <th class="th-po" style="vertical-align: inherit;text-align:center"
                                                    rowspan="2">
                                                    Prematur</th>
                                                <th class="th-po" style="vertical-align: inherit;text-align:center"
                                                    rowspan="2">
                                                    Atern</th>
                                                <th class="th-po" style="vertical-align: inherit;text-align:center"
                                                    rowspan="2">
                                                    Nakes</th>
                                                <th class="th-po" style="vertical-align: inherit;text-align:center"
                                                    rowspan="2">Non
                                                </th>
                                                <th class="th-po" style="vertical-align: inherit;text-align:center"
                                                    colspan="2">JK
                                                </th>
                                                <th class="th-po" style="vertical-align: inherit;text-align:center"
                                                    rowspan="2">BBL
                                                </th>
                                                <th class="th-po" style="vertical-align: inherit;text-align:center"
                                                    colspan="2">
                                                    Hidup</th>
                                                <th class="th-po" style="vertical-align: inherit;text-align:center"
                                                    rowspan="2">
                                                    Meninggal</th>
                                            </tr>
                                            <tr>
                                                <th class="th-po" style="vertical-align: inherit;text-align:center">
                                                    P</th>
                                                <th class="th-po" style="vertical-align: inherit;text-align:center">
                                                    L</th>
                                                <th class="th-po" style="vertical-align: inherit;text-align:center">
                                                    Normal</th>
                                                <th class="th-po" style="vertical-align: inherit;text-align:center">
                                                    Cacat</th>
                                            </tr>
                                        </thead>
                                        <tbody v-for="(input, index) in input.details" :key="index">
                                            <tr>
                                                <td class="td-po">
                                                    <div class="column pt-3 pb-0">
                                                        <VField>
                                                            <VInput type="text" v-model="input.tanggalPartus"
                                                                placeholder="Tanggal" />
                                                        </VField>
                                                    </div>
                                                </td>
                                                <td class="td-po">
                                                    <div class="column pt-3 pb-0">
                                                        <VField>
                                                            <VControl>
                                                                <VCheckbox class="fontcheckbox" v-model="input.abortus"
                                                                    true-value="true" label="" color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </td>
                                                <td class="td-po">
                                                    <div class="column pt-3 pb-0">
                                                        <VField>
                                                            <VControl>
                                                                <VCheckbox class="fontcheckbox" v-model="input.prematur"
                                                                    true-value="true" label="" color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </td>
                                                <td class="td-po">
                                                    <div class="column pt-3 pb-0">
                                                        <VField>
                                                            <VControl>
                                                                <VCheckbox class="fontcheckbox" v-model="input.aterm"
                                                                    true-value="true" label="" color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </td>
                                                <td class="td-po">
                                                    <div class="column pt-3 pb-0">
                                                        <VField class="is-autocomplete-select" v-slot="{ id }">
                                                            <VControl icon="feather:search">
                                                                <Multiselect v-model="input.jenispartus"
                                                                    :attrs="{ value }" placeholder="--Pilih--"
                                                                    label="label" :options="d_jenispartus"
                                                                    :searchable="true" track-by="label" mode="single"
                                                                    autocomplete="off">
                                                                </Multiselect>
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </td>
                                                <td class="td-po">
                                                    <div class="column pt-3 pb-0">
                                                        <VField>
                                                            <VControl>
                                                                <VCheckbox class="fontcheckbox" v-model="input.nakes"
                                                                    true-value="true" label="" color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </td>
                                                <td class="td-po">
                                                    <div class="column pt-3 pb-0">
                                                        <VField>
                                                            <VControl>
                                                                <VCheckbox class="fontcheckbox" v-model="input.nonnakes"
                                                                    true-value="true" label="" color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </td>
                                                <td class="td-po">
                                                    <div class="column pt-3 pb-0">
                                                        <VField>
                                                            <VControl>
                                                                <VCheckbox class="fontcheckbox"
                                                                    v-model="input.perempuan" true-value="true" label=""
                                                                    color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </td>
                                                <td class="td-po">
                                                    <div class="column pt-3 pb-0">
                                                        <VField>
                                                            <VControl>
                                                                <VCheckbox class="fontcheckbox" v-model="input.lakilaki"
                                                                    true-value="true" label="" color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </td>
                                                <td class="td-po">
                                                    <div class="column pt-3 pb-0">
                                                        <VField>
                                                            <VControl icon="">
                                                                <VInput type="text" v-model="input.bbl"
                                                                    placeholder="" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </td>
                                                <td class="td-po">
                                                    <div class="column pt-3 pb-0">
                                                        <VField>
                                                            <VControl>
                                                                <VCheckbox class="fontcheckbox" v-model="input.normal"
                                                                    true-value="true" label="" color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </td>
                                                <td class="td-po">
                                                    <div class="column pt-3 pb-0">
                                                        <VField>
                                                            <VControl>
                                                                <VCheckbox class="fontcheckbox" v-model="input.cacat"
                                                                    true-value="true" label="" color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </td>
                                                <td class="td-po">
                                                    <div class="column pt-3 pb-0">
                                                        <VField>
                                                            <VControl>
                                                                <VCheckbox class="fontcheckbox"
                                                                    v-model="input.meninggal" true-value="true" label=""
                                                                    color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </td>
                                                <td class="td-po">
                                                    <div class="column pt-3 pb-0">
                                                        <VField>
                                                            <VControl>
                                                                <VTextarea v-model="input.komplikasi" rows="3">
                                                                </VTextarea>
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </td>
                                                <td class="td-rpo" style="vertical-align: inherit">
                                                    <div class="column">
                                                        <VButtons style="justify-content:space-around">
                                                            <VIconButton type="button" raised circle icon="feather:plus"
                                                                @click="addNewItem()" color="info"
                                                                v-tooltip.bubble="'Tambah '">
                                                            </VIconButton>
                                                            <VIconButton class="mt-1" v-if="index > 0" type="button"
                                                                raised circle icon="feather:trash"
                                                                @click="removeItem(index)" color="danger">
                                                            </VIconButton>
                                                        </VButtons>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="column is-12 pt-0 pb-0">
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <h1>Riwayat pemakaian alat kontrasepsi:</h1>
                            </div>
                            <div class="column is-4">
                                <h1>-</h1>
                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                    <VControl icon="feather:search">
                                        <Multiselect v-model="input.kontrasepsi" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_kontrasepsi"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <h1>Jenis</h1>
                                <VField addons>
                                    <VControl expanded>
                                        <VInput type="text" class="heightinput input" placeholder=""
                                            v-model="input.jenis" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <h1>Lama pemakaian</h1>
                                <VField addons>
                                    <VControl expanded>
                                        <VInput type="text" class="heightinput input" placeholder=""
                                            v-model="input.lamapemakaian" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="column is-12 pt-0">
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <h1>Riwayat hamil ini:</h1>
                            </div>
                            <div class="column is-3">
                                <h1>Hari pertama haid terakhir</h1>
                                <VField addons>
                                    <VControl expanded>
                                        <VInput type="text" class="heightinput input" placeholder=""
                                            v-model="input.pertamahaid" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <h1>Tafsiran partus</h1>
                                <VField addons>
                                    <VControl expanded>
                                        <VInput type="text" class="heightinput input" placeholder=""
                                            v-model="input.tafsiranpartus" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <h1>Ante Natal Care</h1>
                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                    <VControl icon="feather:search">
                                        <Multiselect v-model="input.antenatalcare" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_antenatalcare"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <h1>di</h1>
                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                    <VControl icon="feather:search">
                                        <Multiselect v-model="input.di" :attrs="{ value }" placeholder="--Pilih--"
                                            label="label" :options="d_di" :searchable="true" track-by="label"
                                            mode="single" autocomplete="off">
                                        </Multiselect>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <h1>Frekuensi</h1>
                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                    <VControl icon="feather:search">
                                        <Multiselect v-model="input.frekuensi" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_frekuensi"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <h1>Imunisasi TT</h1>
                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                    <VControl icon="feather:search">
                                        <Multiselect v-model="input.imunisasitt" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_imunisasitt"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <h1>Sebanyak</h1>
                                <VField addons>
                                    <VControl expanded>
                                        <VInput type="text" class="input" placeholder="" v-model="input.sebanyak" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>kali</VButton>
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-12 pb-0">
                                <h1>Keluhan saat hamil</h1>
                            </div>
                            <div class="column is-4 pt-0">
                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                    <VControl icon="feather:search">
                                        <Multiselect v-model="input.SKeluhan_Saat_Hamil" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_KSH" :searchable="true"
                                            track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4 pt-0" v-if="input.SKeluhan_Saat_Hamil == 6">
                                <VField>
                                    <VControl>
                                        <VInput type="text" class="heightinput input" placeholder="Lainnya"
                                            v-model.number="input.ketKeluhan" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <h1>PEMERIKSAAN FISIK:</h1>
                                    </div>
                                    <div class="column is-2">
                                        <h1>Keadaan Umum</h1>
                                        <VField class="is-autocomplete-select">
                                            <VControl icon="feather:search">
                                                <Multiselect v-model="input.keadaanumumobgyn" :attrs="{ value }"
                                                    placeholder="--Pilih--" label="label" :options="d_keadaanumum"
                                                    :searchable="true" track-by="label" mode="single"
                                                    autocomplete="off">
                                                </Multiselect>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-2">
                                        <div class="column is-12" style="margin-top: -10px;">
                                            <h1>Tekanan Darah</h1>
                                            <VField addons>
                                                <VControl expanded>
                                                    <VInput type="text" class="input" placeholder="Tekanan Darah"
                                                        v-model="input.tekananDarahObgyn" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static disabled>mmHg</VButton>
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                    <div class="column is-2">
                                        <h1>Nadi</h1>
                                        <VField addons>
                                            <VControl expanded>
                                                <VInput type="text" class="input" placeholder=""
                                                    v-model="input.nadiObgyn" />
                                            </VControl>
                                            <VControl class="field-addon-body">
                                                <VButton static disabled>x/menit</VButton>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-2">
                                        <h1>Respirasi</h1>
                                        <VField addons>
                                            <VControl expanded>
                                                <VInput type="text" class="input" placeholder=""
                                                    v-model="input.nafasObgyn" />
                                            </VControl>
                                            <VControl class="field-addon-body">
                                                <VButton static disabled>x/menit</VButton>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-2">
                                        <h1>Suhu</h1>
                                        <VField addons>
                                            <VControl expanded>
                                                <VInput type="text" class="input" placeholder=""
                                                    v-model="input.celciusObgyn" />
                                            </VControl>
                                            <VControl class="field-addon-body">
                                                <VButton static disabled>°C </VButton>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-2">
                                        <h1>SaO2</h1>
                                        <VField addons>
                                            <VControl expanded>
                                                <VInput type="text" class="input" placeholder=""
                                                    v-model="input.sao2Obgyn" />
                                            </VControl>
                                            <VControl class="field-addon-body">
                                                <VButton static disabled>%</VButton>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-2">
                                        <h1>Berat Badan</h1>
                                        <VField addons>
                                            <VControl expanded>
                                                <VInput type="text" class="input" placeholder="Berat Badan"
                                                    v-model="input.beratbadanObgyn" />
                                            </VControl>
                                            <VControl class="field-addon-body">
                                                <VButton static disabled>kg</VButton>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-2">
                                        <h1>Tinggi Badan</h1>
                                        <VField addons>
                                            <VControl expanded>
                                                <VInput type="text" class="input" placeholder="Tinggi Badan"
                                                    v-model="input.tinggibadanObgyn" />
                                            </VControl>
                                            <VControl class="field-addon-body">
                                                <VButton static disabled>cm</VButton>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-7">
                                        <h1>GCS</h1>
                                        <div class="columns is-multiline">
                                            <div class="column is-4">
                                                <VField addons>
                                                    <VControl class="field-addon-body">
                                                        <VButton static disabled>E</VButton>
                                                    </VControl>
                                                    <VControl expanded>
                                                        <Multiselect v-model="input.gcse" :attrs="{ value }"
                                                            placeholder="E" label="label" :options="d_gcse"
                                                            :searchable="true" track-by="label" mode="single"
                                                            autocomplete="off">
                                                        </Multiselect>
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-4">
                                                <VField addons>
                                                    <VControl class="field-addon-body">
                                                        <VButton static disabled>V</VButton>
                                                    </VControl>
                                                    <VControl expanded>
                                                        <Multiselect v-model="input.gcsv" :attrs="{ value }"
                                                            placeholder="V" label="label" :options="d_gcsv"
                                                            :searchable="true" track-by="label" mode="single"
                                                            autocomplete="off">
                                                        </Multiselect>
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-4">
                                                <VField addons>
                                                    <VControl class="field-addon-body">
                                                        <VButton static disabled>M</VButton>
                                                    </VControl>
                                                    <VControl expanded>
                                                        <Multiselect v-model="input.gcsm" :attrs="{ value }"
                                                            placeholder="M" label="label" :options="d_gcsm"
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
                        </div>
                    </div>

                    <div class="column is-12" v-if="kelompokUser.toUpperCase().indexOf('NURSE-STATION') == -1">
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <h1>Pemeriksaan Khusus Obstetri: Pemeriksaan Luar</h1>
                                </div>
                                <div class="column is-3">
                                    <h1>Tinggi fundus uteri</h1>
                                    <VField addons>
                                        <VControl expanded>
                                            <VInput type="text" class="heightinput input" placeholder=""
                                                v-model="input.tinggifundus" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <h1>TFU (Mc Donald)</h1>
                                    <VField addons>
                                        <VControl expanded>
                                            <VInput type="text" class="input" placeholder="" v-model="input.tfu" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>cm</VButton>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <h1>Letak anak</h1>
                                    <VField addons>
                                        <VControl expanded>
                                            <VInput type="text" class="heightinput input" placeholder=""
                                                v-model="input.letakanak" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <h1>Denyut jantung janin</h1>
                                    <VField addons>
                                        <VControl expanded>
                                            <VInput type="text" class="input" placeholder=""
                                                v-model="input.denyutjantung" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>x/menit</VButton>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <h1>His</h1>
                                    <VField addons>
                                        <VControl expanded>
                                            <VInput type="text" class="heightinput input" placeholder=""
                                                v-model="input.his" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <h1>Lainnya</h1>
                                    <VField addons>
                                        <VControl expanded>
                                            <VInput type="text" class="heightinput input" placeholder=""
                                                v-model="input.obstetriLainnya" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <h1>Pemeriksaan Dalam</h1>
                                    <h1>Nama Pemeriksa & Waktu</h1>
                                    <VField addons>
                                        <VControl expanded>
                                            <VInput type="text" class="heightinput input" placeholder=""
                                                v-model="input.namapemeriksawaktu" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="column is-12">
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.dalamBatasNormalStatusFungsional"
                                        true-value="Dalam Batas Normal" label="Dalam Batas Normal" color="primary"
                                        @click="normalStatusFungsional()" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12 pb-0">
                                    <div class="is-flex" style="justify-content: space-between;">
                                        <h1 class="bold" style="font-size:larger">ASSESMEN NYERI : </h1>
                                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.DBNnyeri"
                                            :true-value="true" label="Dalam Batas Normal" color="primary" circle />
                                    </div>
                                </div>
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-4">
                                            <h1>Skala nyeri</h1>
                                            <VField addons>
                                                <VControl expanded>
                                                    <VInput type="text" class="heightinput input" placeholder=""
                                                        v-model="input.skalanyeri" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-4">
                                            <h1>Lokasi</h1>
                                            <VField addons>
                                                <VControl expanded>
                                                    <VInput type="text" class="heightinput input" placeholder=""
                                                        v-model="input.lokasi" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-4">
                                            <h1>Faktor yang memperberat</h1>
                                            <VField addons>
                                                <VControl expanded>
                                                    <VInput type="text" class="heightinput input" placeholder=""
                                                        v-model="input.memperberat" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-6 pt-0">
                                    <div class="columns is-multiline">
                                        <div class="column is-4">
                                            <h1>Frekuensi Nyeri</h1>
                                            <VField class="is-autocomplete-select">
                                                <VControl>
                                                    <Multiselect v-model="input.frekuensinyeri" :attrs="{ value }"
                                                        placeholder="--Pilih--" label="label" :options="d_freknyeri"
                                                        :searchable="true" track-by="label" mode="single"
                                                        autocomplete="off">
                                                    </Multiselect>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-4">
                                            <h1>Lama Nyeri</h1>
                                            <VField addons>
                                                <VControl expanded>
                                                    <VInput type="text" class="heightinput input" placeholder=""
                                                        v-model="input.lamanyeri" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-4">
                                            <h1>Kualitas Nyeri</h1>
                                            <VField class="is-autocomplete-select">
                                                <VControl>
                                                    <Multiselect v-model="input.kualitasnyeri" :attrs="{ value }"
                                                        placeholder="--Pilih--" label="label" :options="d_kualitasnyeri"
                                                        :searchable="true" track-by="label" mode="single"
                                                        autocomplete="off">
                                                    </Multiselect>
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-6 pt-0">
                                    <h1>Faktor yang meringankan nyeri</h1>
                                    <VField addons>
                                        <VControl expanded>
                                            <VInput type="text" class="heightinput input" placeholder=""
                                                v-model="input.meringankan" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>


                        <hr>

                        <div class="columns is-multiline">
                            <div class="column is-12 pb-0">
                                <div class="is-flex" style="justify-content: space-between;">
                                    <h1 class="bold" style="font-size:larger;">
                                        KONDISI PSIKOLOGI, SOSIAL, EKONOMI DAN SPIRITUAL
                                    </h1>
                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.DBNPsikologi"
                                        :true-value="true" label="Dalam Batas Normal" color="primary" circle />
                                </div>
                            </div>
                            <div class="column is-3">
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
                            </div>
                            <!-- <div class="column is-3">
                                        <h1>Pembiayaan kesehatan</h1>
                                        <VField class="is-autocomplete-select">
                                            <VControl >
                                                <Multiselect v-model="input.pembiayaankesehatan" :attrs="{ value }" placeholder="--Pilih--" label="label"
                                                    :options="d_pembiayaankesehatan" :searchable="true" track-by="label" mode="single" autocomplete="off">
                                                </Multiselect>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <h1 class="emr">Kebiasaan adat istiadat yang memengaruhi kesehatan</h1>
                                        <VField addons>
                                            <VControl expanded>
                                                <VInput type="text" class="heightinput input" placeholder=""
                                                    v-model="input.adatistiadat" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3" style="display: none !important">
                                        <h1>Dukungan sosial dari</h1>
                                        <VField class="is-autocomplete-select">
                                            <VControl >
                                                <Multiselect v-model="input.dukungansosial" :attrs="{ value }" placeholder="--Pilih--" label="label"
                                                    :options="d_dukungansosial" :searchable="true" track-by="label" mode="single" autocomplete="off">
                                                </Multiselect>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-2" style="display: none !important">
                                        <h1>Kebiasaan ibu</h1>
                                        <VField class="is-autocomplete-select">
                                            <VControl >
                                                <Multiselect v-model="input.kebiasaanibu" :attrs="{ value }" placeholder="--Pilih--" label="label"
                                                    :options="d_kebiasaanibu" :searchable="true" track-by="label" mode="single" autocomplete="off">
                                                </Multiselect>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-2">
                                        <h1>Perlu rohaniawan</h1>
                                        <VField class="is-autocomplete-select">
                                            <VControl >
                                                <Multiselect v-model="input.rohaniawan" :attrs="{ value }" placeholder="--Pilih--" label="label"
                                                    :options="d_rohaniawan" :searchable="true" track-by="label" mode="single" autocomplete="off">
                                                </Multiselect>
                                            </VControl>
                                        </VField>
                                    </div> -->
                        </div>


                        <hr>

                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <div class="columns is-multiline">
                                    <div class="column is-12 pb-0">
                                        <div class="is-flex" style="justify-content: space-between;">
                                            <h1 style="font-size:larger;font-weight:bold">SKRINNING NUTRISI</h1>
                                            <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.DBNNutrisi"
                                                :true-value="true" label="Dalam Batas Normal" color="primary" circle />
                                        </div>

                                    </div>
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


                        <hr>

                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-12 pb-0">
                                            <div class="is-flex" style="justify-content: space-between;">
                                                <h1 style="font-size:larger;font-weight:bold">STATUS FUNGSIONAL</h1>
                                                <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.DBNFungsional"
                                                    :true-value="true" label="Dalam Batas Normal" color="primary"
                                                    circle />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-2">
                                    <h1>Mengontrol BAB</h1>
                                    <VField class="is-autocomplete-select">
                                        <VControl>
                                            <Multiselect v-model="input.mengontrolbab" :attrs="{ value }"
                                                placeholder="--Pilih--" label="label" :options="d_mengontrolbab"
                                                :searchable="true" track-by="label" mode="single" autocomplete="off">
                                            </Multiselect>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-2">
                                    <h1>Mengontrol BAK</h1>
                                    <VField class="is-autocomplete-select">
                                        <VControl>
                                            <Multiselect v-model="input.mengontrolbak" :attrs="{ value }"
                                                placeholder="--Pilih--" label="label" :options="d_mengontrolbak"
                                                :searchable="true" track-by="label" mode="single" autocomplete="off">
                                            </Multiselect>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-2">
                                    <h1>Membersihkan diri</h1>
                                    <VField class="is-autocomplete-select">
                                        <VControl>
                                            <Multiselect v-model="input.bersihdiri" :attrs="{ value }"
                                                placeholder="--Pilih--" label="label" :options="d_bersihdiri"
                                                :searchable="true" track-by="label" mode="single" autocomplete="off">
                                            </Multiselect>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-2">
                                    <h1>Penggunaan toilet</h1>
                                    <VField class="is-autocomplete-select">
                                        <VControl>
                                            <Multiselect v-model="input.toilet" :attrs="{ value }"
                                                placeholder="--Pilih--" label="label" :options="d_toilet"
                                                :searchable="true" track-by="label" mode="single" autocomplete="off">
                                            </Multiselect>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-2">
                                    <h1>Makan</h1>
                                    <VField class="is-autocomplete-select">
                                        <VControl>
                                            <Multiselect v-model="input.makan" :attrs="{ value }"
                                                placeholder="--Pilih--" label="label" :options="d_makan"
                                                :searchable="true" track-by="label" mode="single" autocomplete="off">
                                            </Multiselect>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-2">
                                    <h1>Berpindah dari tempat tidur</h1>
                                    <VField class="is-autocomplete-select">
                                        <VControl>
                                            <Multiselect v-model="input.berpindahtt" :attrs="{ value }"
                                                placeholder="--Pilih--" label="label" :options="d_berpindahtt"
                                                :searchable="true" track-by="label" mode="single" autocomplete="off">
                                            </Multiselect>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-2">
                                    <h1>Mobilisai / Berjalan</h1>
                                    <VField class="is-autocomplete-select">
                                        <VControl>
                                            <Multiselect v-model="input.mobilisasi" :attrs="{ value }"
                                                placeholder="--Pilih--" label="label" :options="d_mobilisasi"
                                                :searchable="true" track-by="label" mode="single" autocomplete="off">
                                            </Multiselect>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-2">
                                    <h1>Berpakaian</h1>
                                    <VField class="is-autocomplete-select">
                                        <VControl>
                                            <Multiselect v-model="input.berpakaian" :attrs="{ value }"
                                                placeholder="--Pilih--" label="label" :options="d_berpakaian"
                                                :searchable="true" track-by="label" mode="single" autocomplete="off">
                                            </Multiselect>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-2">
                                    <h1>Naik turun tangga</h1>
                                    <VField class="is-autocomplete-select">
                                        <VControl>
                                            <Multiselect v-model="input.tangga" :attrs="{ value }"
                                                placeholder="--Pilih--" label="label" :options="d_tangga"
                                                :searchable="true" track-by="label" mode="single" autocomplete="off">
                                            </Multiselect>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-2">
                                    <h1>Mandi</h1>
                                    <VField class="is-autocomplete-select">
                                        <VControl>
                                            <Multiselect v-model="input.mandi" :attrs="{ value }"
                                                placeholder="--Pilih--" label="label" :options="d_mandi"
                                                :searchable="true" track-by="label" mode="single" autocomplete="off">
                                            </Multiselect>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <h1>Nilai</h1>
                                    <VField addons>
                                        <VControl expanded>
                                            <VInput type="text" class="heightinput input" placeholder=""
                                                v-model="input.nilaimandi" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-12 pb-0">
                                            <div class="is-flex" style="justify-content: space-between;">
                                                <h1 style="font-size:larger;font-weight:bold">
                                                    ASESMEN RISIKO JATUH
                                                </h1>
                                                <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.DBNResikoJatuh"
                                                    :true-value="true" label="Dalam Batas Normal" color="primary"
                                                    circle />
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="columns is-multiline">
                                        <div class="column is-12">
                                            
                                            <h1></h1>
                                        </div>
                                    </div> -->
                                </div>
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-10">
                                            <h1>
                                                Perhatikan cara duduk pasien saat akan duduk di kursi.
                                                Apakah pasien
                                                tampak tidak seimbang (sempoyongan/limbung)?
                                            </h1>
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
                                            <h1>Apakah pasien memegang pinggiran kursi atau meja atau
                                                benda lain sebagai
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
                                        <div class="column is-2">
                                            <h1>Hasil</h1>
                                        </div>
                                        <div class="column is-2">
                                            <VField addons>
                                                <VControl expanded>
                                                    <VInput type="text" class="heightinput input" placeholder=""
                                                        v-model="input.hasiljatuh" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-8"></div>
                                        <div class="column is-2">
                                            <h1>Tindakan</h1>
                                        </div>
                                        <div class="column is-2">
                                            <VField class="is-autocomplete-select">
                                                <VControl icon="feather:search">
                                                    <Multiselect v-model="input.tindakan" :attrs="{ value }"
                                                        placeholder="--Pilih--" label="label" :options="d_tindakan"
                                                        :searchable="true" track-by="label" mode="single"
                                                        autocomplete="off">
                                                    </Multiselect>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-8"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-12">
                                            <h1>RIWAYAT PENGGUNAAN OBAT</h1>
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

                        <hr>

                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-12">
                                            <h1>DIAGNOSA KEBIDANAN</h1>
                                        </div>
                                        <div class="column is-2">
                                            <div class="columns is-multiline">
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl>
                                                            <VCheckbox class="fontcheckbox" v-model="input.g"
                                                                true-value="G" label="G" color="primary" circle />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-8">
                                                    <VField>
                                                        <VControl>
                                                            <input v-model="input.ketG" class="input"
                                                                style="height:25px" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column is-2">
                                            <div class="columns is-multiline">
                                                <div class="column is-1">
                                                    <h1>P</h1>
                                                </div>
                                                <div class="column is-10">
                                                    <VField>
                                                        <VControl>
                                                            <input v-model="input.ketP" class="input"
                                                                style="height:25px" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column is-2">
                                            <div class="columns is-multiline">
                                                <div class="column is-1">
                                                    <h1>UK</h1>
                                                </div>
                                                <div class="column is-10">
                                                    <VField>
                                                        <VControl>
                                                            <input v-model="input.ketUK" class="input"
                                                                style="height:25px" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column is-2">
                                            <div class="columns is-multiline">
                                                <div class="column is-5">
                                                    <h1>Minggu</h1>
                                                </div>
                                                <div class="column is-6">
                                                    <VField>
                                                        <VControl>
                                                            <input v-model="input.ketMinggu" class="input"
                                                                style="height:25px" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column is-2">
                                            <div class="columns is-multiline">
                                                <div class="column is-3">
                                                    <h1>Hari</h1>
                                                </div>
                                                <div class="column is-8">
                                                    <VField>
                                                        <VControl>
                                                            <input v-model="input.ketHari" class="input"
                                                                style="height:25px" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column is-2"></div>
                                        <div class="column is-12">
                                            <div class="columns is-multiline">
                                                <div class="column is-2">
                                                    <div class="columns is-multiline">
                                                        <div class="column is-4">
                                                            <VField>
                                                                <VControl>
                                                                    <VCheckbox class="fontcheckbox" v-model="input.p2"
                                                                        true-value="P" label="P" color="primary"
                                                                        circle />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-8">
                                                            <VField>
                                                                <VControl>
                                                                    <input v-model="input.ketP2" class="input"
                                                                        style="height:25px" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="column is-1">
                                                    <h1>A</h1>
                                                </div>
                                                <div class="column is-9">
                                                    <VField>
                                                        <VControl>
                                                            <input v-model="input.ketA2" class="input"
                                                                style="height:25px" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column is-12">
                                            <div class="columns is-multiline">
                                                <div class="column is-3">
                                                    <VField>
                                                        <VControl>
                                                            <VCheckbox class="fontcheckbox" v-model="input.akseptorbaru"
                                                                true-value="Akseptor baru kontrasepsi"
                                                                label="Akseptor baru kontrasepsi" color="primary"
                                                                circle />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-9">
                                                    <VField>
                                                        <VControl>
                                                            <input v-model="input.ketAkseptorbaru" class="input"
                                                                style="height:25px" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-3">
                                                    <VField>
                                                        <VControl>
                                                            <VCheckbox class="fontcheckbox" v-model="input.akseptorlama"
                                                                true-value="Akseptor lama kontrasepsi"
                                                                label="Akseptor lama kontrasepsi" color="primary"
                                                                circle />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-9">
                                                    <VField>
                                                        <VControl>
                                                            <input v-model="input.ketAkseptorlama" class="input"
                                                                style="height:25px" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-3">
                                                    <VField>
                                                        <VControl>
                                                            <VCheckbox class="fontcheckbox" v-model="input.akslama"
                                                                true-value="Akseptor lama" label="Akseptor lama"
                                                                color="primary" circle />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-3">
                                                    <VField>
                                                        <VControl>
                                                            <input v-model="input.ketAkseptorlama2" class="input"
                                                                style="height:25px" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-3">
                                                    <h1>ganti cara ke kontrasepsi</h1>
                                                </div>
                                                <div class="column is-3">
                                                    <VField>
                                                        <VControl>
                                                            <input v-model="input.gantiKontrasepsi" class="input"
                                                                style="height:25px" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column is-12">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox" v-model="input.puswus"
                                                        true-value="PUS / WUS dengan pilihan kontrasepsi yang belum rasional"
                                                        label="PUS / WUS dengan pilihan kontrasepsi yang belum rasional"
                                                        color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VControl>
                                                <VCheckbox class="fontcheckbox"
                                                    v-model="input.CBLainnya_Diagnosa_Kebidanan" true-value="Lainnya"
                                                    label="Lainnya" color="primary" circle />
                                            </VControl>
                                        </div>
                                        <div class="column is-6" v-if="input.CBLainnya_Diagnosa_Kebidanan != null">
                                            <VControl>
                                                <VInput type="text" class="input" v-model="input.TBDiagnosaLainnya" />
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-12">
                                            <h1>RENCANA KEBIDANAN</h1>
                                            <VCheckbox class="fontcheckbox" v-model="input.informasikan"
                                                true-value="Informasikan hasil pemeriksaan dan kondisi saat ini"
                                                label="Informasikan hasil pemeriksaan dan kondisi saat ini"
                                                color="primary" circle /> <br>
                                            <VCheckbox class="fontcheckbox" v-model="input.kolaborasi"
                                                true-value="Kolaborasi dengan dokter spesialis obgyn untuk tindakan dan therapy selanjutnya"
                                                label="Kolaborasi dengan dokter spesialis obgyn untuk tindakan dan therapy selanjutnya"
                                                color="primary" circle /> <br>
                                            <VCheckbox class="fontcheckbox" v-model="input.fasilitas"
                                                true-value="Fasilitasi dokter dalam pemeriksaan USG"
                                                label="Fasilitasi dokter dalam pemeriksaan USG" color="primary"
                                                circle /> <br>

                                            <VCheckbox class="fontcheckbox" v-model="input.lakukan"
                                                true-value="Lakukan pemeriksaan NST" label="Lakukan pemeriksaan NST"
                                                color="primary" circle /> <br>
                                            <VCheckbox class="fontcheckbox" v-model="input.anjurkan"
                                                true-value="Anjurkan pasien untuk skrining rutin kehamilan"
                                                label="Anjurkan pasien untuk skrining rutin kehamilan" color="primary"
                                                circle /> <br>
                                            <VCheckbox class="fontcheckbox" v-model="input.pengetahuan"
                                                true-value="Berikan pengetahuan tentang tanda bahaya kehamilan, keluhan lazim dan cara mengatasinya"
                                                label="Berikan pengetahuan tentang tanda bahaya kehamilan, keluhan lazim dan cara mengatasinya"
                                                color="primary" circle /> <br>
                                            <VCheckbox class="fontcheckbox" v-model="input.infodeteksi"
                                                true-value="Berikan informasi tentang deteksi dan pencegahan kelainan kongenital"
                                                label="Berikan informasi tentang deteksi dan pencegahan kelainan kongenital"
                                                color="primary" circle /> <br>
                                            <VCheckbox class="fontcheckbox" v-model="input.pengetahuangizi"
                                                true-value="Berikan pengetahuan tentang Nutrisi/ gizi"
                                                label="Berikan pengetahuan tentang Nutrisi/ gizi" color="primary"
                                                circle /> <br>

                                            <VCheckbox class="fontcheckbox" v-model="input.pengetahuanadekuatif"
                                                true-value="Berikan pengetahuan yang adekuat tentang penyakit yang diderita untuk mengurangi cemas"
                                                label="Berikan pengetahuan yang adekuat tentang penyakit yang diderita untuk mengurangi cemas"
                                                color="primary" circle /> <br>
                                            <VCheckbox class="fontcheckbox" v-model="input.jelaskanprosedur"
                                                true-value="Jelaskan semua posedur, termasuk beberapa pengalaman sebelum prosedur"
                                                label="Jelaskan semua posedur, termasuk beberapa pengalaman sebelum prosedur"
                                                color="primary" circle /> <br>
                                            <VCheckbox class="fontcheckbox" v-model="input.dekati"
                                                true-value="Dekati pasien untuk memberikan rasa aman dan mengurangi rasa takut"
                                                label="Dekati pasien untuk memberikan rasa aman dan mengurangi rasa takut"
                                                color="primary" circle /> <br>
                                            <VCheckbox class="fontcheckbox" v-model="input.dengarkan"
                                                true-value="Dengarkan pasien dengan penuh perhatian"
                                                label="Dengarkan pasien dengan penuh perhatian" color="primary"
                                                circle /> <br>

                                            <VCheckbox class="fontcheckbox" v-model="input.papsmear"
                                                true-value="Fasilitasi dokter pemeriksaan papsmear"
                                                label="Fasilitasi dokter pemeriksaan papsmear" color="primary" circle />
                                            <br>
                                            <VCheckbox class="fontcheckbox" v-model="input.biopsi"
                                                true-value="Fasilitasi dokter pemeriksaan biopsi"
                                                label="Fasilitasi dokter pemeriksaan biopsi" color="primary" circle />
                                            <br>
                                            <VCheckbox class="fontcheckbox" v-model="input.cryoterapi"
                                                true-value="Fasilitasi dokter pemeriksaan cryoterapi"
                                                label="Fasilitasi dokter pemeriksaan cryoterapi" color="primary"
                                                circle /> <br>

                                            <VCheckbox class="fontcheckbox" v-model="input.nonfarmakologis"
                                                true-value="Ajarkan teknik nonfarmakologis seperti Relaksasi napas dalam/otot progesif, Distraksi, kompres hangat/dingin, terapi music, massage punggung,"
                                                label="Ajarkan teknik nonfarmakologis seperti Relaksasi napas dalam/otot progesif, Distraksi, kompres hangat/dingin, terapi music, massage punggung,"
                                                color="primary" circle /> <br>
                                            <VCheckbox class="fontcheckbox" v-model="input.gerakhamil"
                                                true-value="Berikan informasi tentang gerak dan aktivitas selama hamil/ nifas"
                                                label="Berikan informasi tentang gerak dan aktivitas selama hamil/ nifas"
                                                color="primary" circle /> <br>
                                            <VCheckbox class="fontcheckbox" v-model="input.calondonor"
                                                true-value="Beritahu pasien dan keluarga tentang persiapan persalinan, peran pendamping, persiapan menyusui, termasuk Calon Pendonor"
                                                label="Beritahu pasien dan keluarga tentang persiapan persalinan, peran pendamping, persiapan menyusui, termasuk Calon Pendonor"
                                                color="primary" circle /> <br>
                                            <VCheckbox class="fontcheckbox" v-model="input.senamhamil"
                                                true-value="Berikan informasi tentang kelas ibu hamil, Senam Hamil"
                                                label="Berikan informasi tentang kelas ibu hamil, Senam Hamil"
                                                color="primary" circle />
                                            <br>
                                            <VCheckbox class="fontcheckbox" v-model="input.tandapersalinan"
                                                true-value="Beritahu pasien tentang tanda – tanda persalinan"
                                                label="Beritahu pasien tentang tanda – tanda persalinan" color="primary"
                                                circle /> <br>
                                            <VCheckbox class="fontcheckbox" v-model="input.infonifas"
                                                true-value="Berikan informasi tentang tanda Bahaya masa nifas"
                                                label="Berikan informasi tentang tanda Bahaya masa nifas"
                                                color="primary" circle /> <br>
                                            <VCheckbox class="fontcheckbox" v-model="input.caraperiksa"
                                                true-value="Ajarkan pasien cara memeriksa kontraksi uterus, Cara masase uterus, perawatan Perineum, Senam nifas"
                                                label="Ajarkan pasien cara memeriksa kontraksi uterus, Cara masase uterus, perawatan Perineum, Senam nifas"
                                                color="primary" circle /> <br>
                                            <VCheckbox class="fontcheckbox" v-model="input.caramenyusui"
                                                true-value="Berikan Informasi cara menyusui yang benar, dan ASI Ekslusif, perawatan payudara"
                                                label="Berikan Informasi cara menyusui yang benar, dan ASI Ekslusif, perawatan payudara"
                                                color="primary" circle /> <br>
                                            <VCheckbox class="fontcheckbox" v-model="input.kiekontrasepsi"
                                                true-value="Beri KIE tentang, Keuntungan, Kelemahan, Efek samping, Lama penggunaan , Cara mengatasi efek samping Kontrasepsi"
                                                label="Beri KIE tentang, Keuntungan, Kelemahan, Efek samping, Lama penggunaan , Cara mengatasi efek samping Kontrasepsi"
                                                color="primary" circle /> <br>
                                            <VCheckbox class="fontcheckbox" v-model="input.kiesex"
                                                true-value="Beri KIE tentang Sex Hygine/ hubungan seksual"
                                                label="Beri KIE tentang Sex Hygine/ hubungan seksual" color="primary"
                                                circle /> <br>
                                            <VCheckbox class="fontcheckbox" v-model="input.kieluka"
                                                true-value="Beri KIE pasien tentang Perawatan luka pasca operasi"
                                                label="Beri KIE pasien tentang Perawatan luka pasca operasi"
                                                color="primary" circle /> <br>
                                            <VCheckbox class="fontcheckbox" v-model="input.posisiistirahat"
                                                true-value="Istirahatkan pasien pada posisi yang nyaman dalam batas yang ditoleransi oleh pasien"
                                                label="Istirahatkan pasien pada posisi yang nyaman dalam batas yang ditoleransi oleh pasien"
                                                color="primary" circle /> <br>
                                            <VCheckbox class="fontcheckbox" v-model="input.infonyeri"
                                                true-value="Berikan informasi tentang nyeri meliputi penyebab, lamanya nyeri berlangsung, faktor yang dapat memperburuk atau meredakan nyeri"
                                                label="Berikan informasi tentang nyeri meliputi penyebab, lamanya nyeri berlangsung, faktor yang dapat memperburuk atau meredakan nyeri"
                                                color="primary" circle /> <br>
                                            <VCheckbox class="fontcheckbox" v-model="input.identifikasi"
                                                true-value="Bantu pasien mengidentifikasi tindakan memenuhi kebutuhan rasa nyaman yang telah berhasil dilakukan oleh pasien"
                                                label="Bantu pasien mengidentifikasi tindakan memenuhi kebutuhan rasa nyaman yang telah berhasil dilakukan oleh pasien"
                                                color="primary" circle /> <br>
                                            <VCheckbox class="fontcheckbox" v-model="input.tandavital"
                                                true-value="Observasi tanda-tanda vital"
                                                label="Observasi tanda-tanda vital" color="primary" circle /> <br>
                                            <VCheckbox class="fontcheckbox" v-model="input.monitor"
                                                true-value="Monitor Frekuensi nafas pasien/ status oksigen pasien"
                                                label="Monitor Frekuensi nafas pasien/ status oksigen pasien"
                                                color="primary" circle /> <br>
                                            <VCheckbox class="fontcheckbox" v-model="input.posisikan"
                                                true-value="Posisikan pasien untuk memaksimalkan ventilasi (head up/semifowler)"
                                                label="Posisikan pasien untuk memaksimalkan ventilasi (head up/semifowler)"
                                                color="primary" circle /> <br>
                                            <VCheckbox class="fontcheckbox" v-model="input.imunisasi"
                                                true-value="Lakukan manajemen imunisasi/vaksinasi"
                                                label="Lakukan manajemen imunisasi/vaksinasi" color="primary" circle />
                                            <br>
                                            <VCheckbox class="fontcheckbox" v-model="input.keputusan"
                                                true-value="Beri dukungan dalam mengambil keputusan"
                                                label="Beri dukungan dalam mengambil keputusan" color="primary"
                                                circle /> <br>

                                            <VCheckbox class="fontcheckbox" v-model="input.edukasikontrol"
                                                true-value="Edukasi dan sarankan untuk kontrol sebagai upaya meningkatkan status kesehatan pasien"
                                                label="Edukasi dan sarankan untuk kontrol sebagai upaya meningkatkan status kesehatan pasien"
                                                color="primary" circle /> <br>
                                            <div class="is-12 is-flex" v-for="item in 5" :key="item">
                                                <div class="is-2">
                                                    <VCheckbox class="fontcheckbox" v-model="input[`keterangan${item}`]"
                                                        true-value="YA" color="primary" circle /> <br>
                                                </div>
                                                <div class="column is-10">
                                                    <VField>
                                                        <VControl>
                                                            <VInput type="text" class="input"
                                                                v-model="input[`ket${item}`]"
                                                                :placeholder="`Keterangan. ${item}`" />
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
                                        <span class="mb-2">{{ resep.created_at }}</span>
                                    </td>
                                    <td style="width:25%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span>
                                    </td>
                                    <td style="width:25%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span>
                                    </td>
                                    <td style="width:20%;text-align:center">
                                        <span class="mb-2">{{ resep.pasien.nocm }}</span>
                                    </td>
                                    <td style="width:20%;text-align:center">
                                        <span class="mb-2">{{ resep.dpjpUtama }}</span>
                                    </td>
                                    <td style="width:25%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span>
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
                                    <td class="tg-0lax text-center" width="15%">No</td>
                                    <td class="tg-0lax text-center" width="20%">Tanggal Dibuat</td>
                                    <td class="tg-0lax text-center" width="20%">Nama Ruangan</td>
                                    <td class="tg-0lax text-center" width="50%">Nama Template</td>
                                    <td class="tg-0lax text-center" width="15%">#</td>
                                </tr>
                            </thead>
                            <tbody v-for="resep in listTemplateFix">
                                <tr>
                                    <td style="width:15%;text-align:center">
                                        <span class="mb-2">{{ resep.no }}</span>
                                    </td>
                                    <td style="width:20%;text-align:center">
                                        <span class="mb-2">{{ resep.created_at }}</span>
                                    </td>
                                    <td style="width:20%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span>
                                    </td>
                                    <td style="width:50%;text-align:center">
                                        <span class="mb-2">{{ resep.namatemplate }}</span>
                                    </td>
                                    <td style="width:15%;text-align:center">
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

    <!-- <Dialog v-model:visible="modalConfirm" modal header="Pilih Jenis Assesmen Awal Obgyn Yang Sesuai" :style="{ width: '30vw' }" maximizable>
        <VButton icon="feather:book" color="success" raised @click="ginekologi()"
          style="float:right; margin-top: 20px; margin: 10px;" :loading="isloadingCopy">
          Ginekologi
        </VButton>
        <VButton icon="feather:book" color="info" raised @click="obstetri()"
          style="float:right; margin-top: 20px; margin: 10px;" :loading="isloadingCopy">
          Obstetri
        </VButton>
    </Dialog> -->
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Slider from 'primevue/slider';
import Fieldset from 'primevue/fieldset';
import TOdontogram from './odontogram.vue'
import TRiwayatRegistrasi from './riwayat-registrasi.vue'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from "primevue/useconfirm"
import ButtonEmr from '../page-emr-plugins/button-emr.vue'

useHead({
    title: 'Asesmen Awal Kebidanan - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const route = useRoute()
const pasien: any = ref({})
const isLoadingPasien: any = ref(false)
const modalConfirm: any = ref(false)
const confirm = useConfirm();
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
const COLLECTION: any = ref('AsesmenAwalKebidananRawatJalan') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const d_ko: any = ref('')
const d_Dokter: any = ref([])
const dataSourceICD9: any = ref([])
const dataSourceICD10: any = ref([])
const isPemeriksaanFisik: any = ref(true)
const { y } = useWindowScroll()
const isLoading = ref(false)
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const isStuck = computed(() => {
    return y.value > 30
})


// ==================== Array Input ==================
const d_KSH: any = ref([{ value: 1, label: 'Mual' }, { value: 2, label: 'Muntah' }, { value: 3, label: 'Pendarahan' }, { value: 4, label: 'Pusing' }, { value: 5, label: 'Sakit Kepala' }, { value: 6, label: 'Lainnya' }])
const d_rujukan: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_allo: any = ref([{ value: 1, label: 'Suami/Istri' }, { value: 2, label: 'Orang Tua' }, { value: 3, label: 'Anak' }, { value: 4, label: 'Pasien' }, { value: 5, label: 'Lainnya' }])
const d_mata: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_jenispartus: any = ref([{ value: 1, label: 'Spt/Normal' }, { value: 2, label: 'SC' }, { value: 3, label: 'VaE' }])
const d_gcse: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }])
const d_gcsv: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }])
const d_gcsm: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }, { value: 6, label: '6' }])
const d_keadaanumum: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Sedang' }, { value: 3, label: 'Buruk' }])
const d_freknyeri: any = ref([{ value: 1, label: 'Jarang' }, { value: 2, label: 'Hilang Timbul' }, { value: 3, label: 'Terus Menerus' }])
const d_kualitasnyeri: any = ref([{ value: 1, label: 'Tumpul' }, { value: 2, label: 'Tajam' }, { value: 3, label: 'Panas/Terbakar' }, { value: 4, label: 'Lain-lain' }])
const d_gangguanpsikologis: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Gelisah' }, { value: 3, label: 'Takut' }, { value: 4, label: 'Sedih' }, { value: 5, label: 'Rendah diri' }, { value: 6, label: 'Acuh tak acuh' }, { value: 7, label: 'Mudah tersinggung' }, { value: 8, label: 'Menarik diri' }])
const d_masalahkawin: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Ada' }])
const d_kekerasan: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Ada' }])
const d_pembiayaankesehatan: any = ref([{ value: 1, label: 'Biaya sendiri/keluarga' }, { value: 2, label: 'Asuransi Lainnya' }])
const d_rohaniawan: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_penurunanbb: any = ref([{ value: 1, label: 'Tidak' }, { value: 2, label: 'Tidak Yakin' }, { value: 3, label: '1-5 kg' }, { value: 4, label: '6-10 kg' }, { value: 5, label: '11-15 kg' }, { value: 6, label: '>15 kg' }])
const d_penurunannafsu: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
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
const d_kontrasepsi: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_antenatalcare: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_di: any = ref([{ value: 1, label: 'Dokter Kandungan' }, { value: 2, label: 'Dokter Umum' }, { value: 3, label: 'Bidan' }, { value: 4, label: 'Lainnya' }])
const d_frekuensi: any = ref([{ value: 1, label: '1x' }, { value: 2, label: '2x' }, { value: 3, label: '3x' }, { value: 4, label: '>3x' }])
const d_imunisasitt: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
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
    waktuTataLaksana: new Date,
    waktuKontrol: new Date,
    kebjamKedatangan: new Date(),
    kebjamAsesmenAwal: new Date(),
    kebtanggalKedatangan: new Date(),
    dalamBatasNormalStatusFungsional: false,
    DBNnyeri: false,
    details: [{
        no: 1,
    }]
})

function normalStatusFungsional() {
    if (input.value.dalamBatasNormalStatusFungsional == false) {
        input.value.caraduduk = 2
        input.value.kursi = 2
        input.value.tindakan = 1
        input.value.penurunanbb = 0
        input.value.penurunannafsu = 0
        input.value.diagnosakhusus = "TIDAK"
        input.value.caraduduk = 2
        input.value.kursi = 2
        input.value.tindakan = 1
        input.value.mengontrolbab = 3
        input.value.mengontrolbak = 3
        input.value.bersihdiri = 2
        input.value.toilet = 3
        input.value.makan = 3
        input.value.berpindahtt = 4
        input.value.mobilisasi = 4
        input.value.berpakaian = 3
        input.value.tangga = 3
        input.value.mandi = 2
        input.value.masalahperkawinan = 1
        input.value.kekerasanfisik = 1
        input.value.gangguanpsikologis = 1
        input.value.frekuensinyeri = 1
        input.value.kualitasnyeri = 1
        input.value.skalanyeri = '0';
    } else {
        input.value.caraduduk = null;
        input.value.kursi = null;
        input.value.tindakan = null;
        input.value.penurunanbb = null;
        input.value.penurunannafsu = null;
        input.value.diagnosakhusus = null;
        input.value.mengontrolbab = null;
        input.value.mengontrolbak = null;
        input.value.bersihdiri = null;
        input.value.toilet = null;
        input.value.makan = null;
        input.value.berpindahtt = null;
        input.value.mobilisasi = null;
        input.value.berpakaian = null;
        input.value.tangga = null;
        input.value.mandi = null;
        input.value.masalahperkawinan = null;
        input.value.kekerasanfisik = null;
        input.value.gangguanpsikologis = null;
        input.value.frekuensinyeri = null;
        input.value.kualitasnyeri = null;
        input.value.skalanyeri = null;
    }
}

// ==================== Function ==================
const fetchDokter = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
        d_Dokter.value = response
    })
}

const loadRiwayat = async () => {
    isLoading.value = true
    let responsex = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    if (responsex.length) {
        isLoading.value = false
        input.value = responsex[0];
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = responsex[0].emrpasienfk
        }
    } else {
        // const nsBidan = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=AsesmenAwalKebidananRawatJalanNurse" + `&field=menarcheumur,siklus,lama,volume,keluhanhaid,teratur,tidakteratur`)
        let responsetgl = await useApi().get(`/emr/get-emr-tgl-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`)
        isLoading.value = false
        if (responsetgl.length) {
            confirm.require({
                message: 'Asesmen Kebidanan terakhir tanggal ' + H.formatDate(responsetgl[0].created_at, 'DD-MM-YYYY HH:mm:ss') + ', apakah mau mengambil data sebelumnya?',
                header: 'Riwayat Terakhir',
                icon: 'pi pi-info-circle',
                acceptClass: 'p-button-danger',
                accept: () => {
                    isLoading.value = true
                    useApi().get(
                        `/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
                            isLoading.value = false
                            if (responselast.length) {
                                input.value = responselast[0];
                                input.value.namatemplate = ''
                            } else {
                                H.alert('warning', 'Data tidak ada')
                            }
                        })
                },
                reject: () => {
                },
            })
        }
        // if(nsBidan.length) {
        //     input.value.menarcheumur = nsBidan.menarcheumur
        //     input.value.siklus = nsBidan.siklus
        //     input.value.lama = nsBidan.lama
        //     input.value.volume = nsBidan.volume
        //     input.value.keluhanhaid = nsBidan.keluhanhaid
        //     input.value.teratur = nsBidan.teratur ? nsBidan.teratur : ''
        //     input.value.tidakteratur = nsBidan.tidakteratur ? nsBidan.tidakteratur : ''
        // }
    }
}

const pilihTemplate = async (index: any) => {
    isLoading.value = true
    useApi().get(
        `/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
            isLoading.value = false
            if (responselast.length) {
                listTemplate.value = responselast
                showModalTemplate.value = true
            } else {
                H.alert('warning', 'Data tidak ada')
            }
        })
}

const addTemplate = (response: any) => {
    let TTV = {
        gcse: input.value.gcse,
        gcsv: input.value.gcsv,
        gcsm: input.value.gcsm,
        tekananDarahObgyn: input.value.tekananDarahObgyn,
        nadiObgyn: input.value.nadiObgyn,
        nafasObgyn: input.value.nafasObgyn,
        celciusObgyn: input.value.celciusObgyn,
        sao2Obgyn: input.value.sao2Obgyn,
        keadaanumumobgyn: input.value.keadaanumumobgyn,
        tinggibadanObgyn: input.value.tinggibadanObgyn,
        beratbadanObgyn: input.value.beratbadanObgyn,
        id: input.value.id,
        kebtanggalKedatangan: input.value.kebtanggalKedatangan,
        kebjamKedatangan: input.value.kebjamKedatangan,
        kebjamAsesmenAwal: input.value.kebjamAsesmenAwal,
        kebrujukan: input.value.kebrujukan,
        TBKetRujukanDari: input.value.TBKetRujukanDari,
        kebrujuklanjutan: input.value.kebrujuklanjutan,
        TBDiantarOleh: input.value.TBDiantarOleh,
        kebpilihanallo: input.value.kebpilihanallo,
        keballoanamnesis: input.value.keballoanamnesis,
        keluhanutama: input.value.keluhanutama,
        riwayatpenyakit: input.value.riwayatpenyakit,
        riwayatpenyakitdahulu: input.value.riwayatpenyakitdahulu,
        riwayatpengobatan: input.value.riwayatpengobatan,
        riwayatpenyakitkeluarga: input.value.riwayatpenyakitkeluarga,
        isalergi: input.value.isalergi,
        riwayatalergi: input.value.riwayatalergi,
        SRujukan: input.value.SRujukan,
        kebketrujukan: input.value.kebketrujukan,
        menarcheumur: input.value.menarcheumur,
        siklus: input.value.siklus,
        lama: input.value.lama,
        volume: input.value.volume,
        keluhanhaid: input.value.keluhanhaid,
        teratur: input.value.teratur,
        tidakteratur: input.value.tidakteratur,
        details: input.value.details,
        kontrasepsi: input.value.kontrasepsi,
        jenis: input.value.jenis,
        lamapemakaian: input.value.lamapemakaian,
        pertamahaid: input.value.pertamahaid,
        tafsiranpartus: input.value.tafsiranpartus,
        antenatalcare: input.value.antenatalcare,
        di: input.value.di,
        frekuensi: input.value.frekuensi,
        imunisasitt: input.value.imunisasitt,
        sebanyak: input.value.sebanyak,
        SKeluhan_Saat_Hamil: input.value.SKeluhan_Saat_Hamil,
        ketKeluhan: input.value.ketKeluhan,
        tinggifundus: input.value.tinggifundus,
        tfu: input.value.tfu,
        letakanak: input.value.letakanak,
        denyutjantung: input.value.denyutjantung,
        his: input.value.his,
        obstetriLainnya: input.value.obstetriLainnya,
        namapemeriksawaktu: input.value.namapemeriksawaktu,
        g: input.value.g,
        ketG: input.value.ketG,
        ketP: input.value.ketP,
        ketUK: input.value.ketUK,
        ketMinggu: input.value.ketMinggu,
        ketHari: input.value.ketHari,
        p2: input.value.p2,
        ketP2: input.value.ketP2,
        ketA2: input.value.ketA2,
        akseptorbaru: input.value.akseptorbaru,
        ketAkseptorbaru: input.value.ketAkseptorbaru,
        akseptorlama: input.value.akseptorlama,
        ketAkseptorlama: input.value.ketAkseptorlama,
        akslama: input.value.akslama,
        ketAkseptorlama2: input.value.ketAkseptorlama2,
        gantiKontrasepsi: input.value.gantiKontrasepsi,
        puswus: input.value.puswus,
        CBLainnya_Diagnosa_Kebidanan: input.value.CBLainnya_Diagnosa_Kebidanan,
        TBDiagnosaLainnya: input.value.TBDiagnosaLainnya,
        informasikan: input.value.informasikan,
        kolaborasi: input.value.kolaborasi,
        fasilitas: input.value.fasilitas,
        lakukan: input.value.lakukan,
        anjurkan: input.value.anjurkan,
        pengetahuan: input.value.pengetahuan,
        infodeteksi: input.value.infodeteksi,
        pengetahuangizi: input.value.pengetahuangizi,
        pengetahuanadekuatif: input.value.pengetahuanadekuatif,
        jelaskanprosedur: input.value.jelaskanprosedur,
        dekati: input.value.dekati,
        dengarkan: input.value.dengarkan,
        papsmear: input.value.papsmear,
        biopsi: input.value.biopsi,
        cryoterapi: input.value.cryoterapi,
        nonfarmakologis: input.value.nonfarmakologis,
        gerakhamil: input.value.gerakhamil,
        calondonor: input.value.calondonor,
        senamhamil: input.value.senamhamil,
        tandapersalinan: input.value.tandapersalinan,
        infonifas: input.value.infonifas,
        caraperiksa: input.value.caraperiksa,
        caramenyusui: input.value.caramenyusui,
        kiekontrasepsi: input.value.kiekontrasepsi,
        kiesex: input.value.kiesex,
        kieluka: input.value.kieluka,
        posisiistirahat: input.value.posisiistirahat,
        infonyeri: input.value.infonyeri,
        identifikasi: input.value.identifikasi,
        tandavital: input.value.tandavital,
        monitor: input.value.monitor,
        posisikan: input.value.posisikan,
        imunisasi: input.value.imunisasi,
        keputusan: input.value.keputusan,
        edukasikontrol: input.value.edukasikontrol,
        keterangan1: input.value.keterangan1,
        keterangan2: input.value.keterangan2,
        keterangan3: input.value.keterangan3,
        keterangan4: input.value.keterangan4,
        keterangan5: input.value.keterangan5,
        ket1: input.value.ket1,
        ket2: input.value.ket2,
        ket3: input.value.ket3,
        ket4: input.value.ket4,
        ket5: input.value.ket5,
    }
    input.value = response;
    for (const jsonTTV in TTV) {
        if (Object.prototype.hasOwnProperty.call(TTV, jsonTTV)) {
            input.value[jsonTTV] = TTV[jsonTTV];
        }
    }

    delete input.value['_id'];
    input.value.namatemplate = null
    showModalTemplateFix.value = false;
    H.alert('success', 'Template Berhasil ditambahkan')
}

const simpan = () => {
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

    isLoading.value = true
    useApi().post(
        `/emr/simpan-emr`, json).then((response: any) => {
            isLoading.value = false
            NOREC_EMRPASIEN.value = response.norec_emr
        }).catch((e: any) => {
            isLoading.value = false
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

const d_penurunanbbYa: any = ref([
    { value: 1, label: '1-5 kg' },
    { value: 2, label: '6-10 kg' },
    { value: 3, label: '11-15 kg' },
    { value: 4, label: '>15 kg' }
])

const pilihTemplateFix = async (index: any) => {
    isLoading.value = true
    useApi().get(
        `/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
            isLoading.value = false
            console.log(responselast)
            if (responselast.length) {
                for (var x = 0; x < responselast.length; x++) {
                    responselast[x].no = x + 1
                    responselast[x].id = ''
                }
                listTemplateFix.value = responselast
                showModalTemplateFix.value = true
            } else {
                H.alert('warning', 'Data tidak ada')
            }
        })
}
const fetchPasien = () => {
    pasien.value = props.pasien
    pasien.value.registrasi = props.registrasi
    NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}

const ginekologi = async () => {
    modalConfirm.value = false
}

const modal = async () => {
    modalConfirm.value = true
}

// const countRangeNilai = (e: any) => {
//     let cmc = {
//         "keterangan": "CMC (14-15)",
//         "poin": 15
//     }
//     let apatis = {
//         "keterangan": "Apatis (12-13)",
//         "poin": 13
//     }
//     let somnolen = {
//         "keterangan": "Somnolen (10-11)",
//         "poin": 11
//     }
//     let delirium = {
//         "keterangan": "Delirium (7-9)",
//         "poin": 9
//     }
//     let stupar = {
//         "keterangan": "Stupar (4-6)",
//         "poin": 6
//     }
//     let koma = {
//         "keterangan": "Koma ( <= 3)",
//         "poin": 3
//     }

//     descRangeKesadaran.value.forEach((elements: any) => {
//         if (e <= 3 && e <= elements.value.poin) {
//             input.value.rangeKesadaran = koma
//         }
//         else if (e <= 6 && e <= elements.value.poin) {
//             input.value.rangeKesadaran = stupar
//         }
//         else if (e <= 9 && e <= elements.value.poin) {
//             input.value.rangeKesadaran = delirium
//         }
//         else if (e <= 11 && e <= elements.value.poin) {
//             input.value.rangeKesadaran = somnolen
//         }
//         else if (e <= 13 && e <= elements.value.poin) {
//             input.value.rangeKesadaran = apatis
//         }
//         else if (e > 13 && e > elements.value.poin) {
//             input.value.rangeKesadaran = cmc
//         }
//     })
// }
const getDataExist = async () => {
    await useApi().get(
        "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
        "&collection=AsesmenAwalKebidananRawatJalanNurse" +
        "&field=beratbadanObgyn,tinggibadanObgyn,IMT,lingkarPerut,nadiObgyn,celciusObgyn,tekananDarahObgyn,nafasObgyn,sao2Obgyn,keadaanumumobgyn,gcse,gcsv,gcsm," +
        "kebkontrol,kebketrujukan,kebpilihanallo,keballoanamnesis,keluhanutama,riwayatpenyakit,riwayatpenyakitdahulu,riwayatpengobatan,riwayatpenyakitkeluarga," +
        "riwayatalergi,menarcheumur,siklus,lama,volume,keluhanhaid,teratur,tidakteratur,details"
    ).then((response) => {
        for (const jsonTTV in response) {
            if (Object.prototype.hasOwnProperty.call(response, jsonTTV)) {
                input.value[jsonTTV] = response[jsonTTV];
            }
        }
        if (response != null) {
            input.value.menarcheumur = response.menarcheumur
            input.value.siklus = response.siklus
            input.value.lama = response.lama
            input.value.volume = response.volume
            input.value.keluhanhaid = response.keluhanhaid
            input.value.teratur = response.teratur ? response.teratur : ''
            input.value.tidakteratur = response.tidakteratur ? response.tidakteratur : ''
            if (response.details) {
                input.value.detail = response.details
            }
        }
        //   input.value = response
    })
    // await useApi().get(`emr/get-data-exist?nocmfk=${ID_PASIEN}`).then((response) => {
    //     input.value.beratbadanObgyn = response.beratBadan
    //     input.value.tinggibadanObgyn = response.tinggiBadan
    //     input.value.IMT = response.IMT
    //     input.value.lingkarPerut = response.lingkarPerut
    //     input.value.nadiObgyn = response.nadi
    //     input.value.celciusObgyn = response.suhu
    //     input.value.tekananDarahObgyn = response.tekananDarah
    //     input.value.nafasObgyn = response.pernapasan
    //     input.value.sao2Obgyn = response.SPO2
    // })
}

const addNewItem = () => {
    input.value.details.push({
        no: input.value.details[input.value.details.length - 1].no + 1,
    });
}

const removeItem = (index: any) => {
    input.value.details.splice(index, 1)
}

onMounted(async () => {
    modalConfirm.value = true
})

onBeforeMount(async () => {
    try {
        await loadRiwayat()
        await fetchPasien()
        await getDataExist()
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

watch(
    () => input.value.DBNnyeri,
    (newValue, oldValue) => {
        if (newValue) {
            input.value.frekuensinyeri = 1
            input.value.kualitasnyeri = 1
            input.value.skalanyeri = '0';
        } else {
            input.value.frekuensinyeri = undefined
            input.value.kualitasnyeri = undefined
            input.value.skalanyeri = undefined
        }
    }
)

watch(
    () => input.value.DBNPsikologi,
    (newValue, oldValue) => {
        if (newValue) {
            input.value.masalahperkawinan = 1
            input.value.kekerasanfisik = 1
            input.value.gangguanpsikologis = 1
        } else {
            input.value.masalahperkawinan = undefined
            input.value.kekerasanfisik = undefined
            input.value.gangguanpsikologis = undefined
        }
    }
)

watch(
    () => input.value.DBNFungsional,
    (newValue, oldValue) => {
        if (newValue) {
            input.value.mengontrolbab = 3
            input.value.mengontrolbak = 3
            input.value.bersihdiri = 2
            input.value.toilet = 3
            input.value.makan = 3
            input.value.berpindahtt = 4
            input.value.mobilisasi = 4
            input.value.berpakaian = 3
            input.value.tangga = 3
            input.value.mandi = 2
        } else {
            input.value.mengontrolbab = undefined
            input.value.mengontrolbak = undefined
            input.value.bersihdiri = undefined
            input.value.toilet = undefined
            input.value.makan = undefined
            input.value.berpindahtt = undefined
            input.value.mobilisasi = undefined
            input.value.berpakaian = undefined
            input.value.tangga = undefined
            input.value.mandi = undefined
        }
    }
)

watch(
    () => input.value.DBNResikoJatuh,
    (newValue, oldValue) => {
        if (newValue) {
            input.value.caraduduk = 2
            input.value.kursi = 2
            input.value.tindakan = 1
        } else {
            input.value.caraduduk = undefined
            input.value.kursi = undefined
            input.value.tindakan = undefined
        }
    }
)

watch(
    () => input.value.DBNNutrisi,
    (newValue, oldValue) => {
        if (newValue) {
            input.value.penurunanbb = 0
            input.value.penurunannafsu = 0
            input.value.diagnosakhusus = "TIDAK"
        } else {
            input.value.penurunanbb = undefined
            input.value.penurunannafsu = undefined
            input.value.diagnosakhusus = undefined
        }
    }
)
watch(
    () => input.value.DBNJatuh,
    (newValue, oldValue) => {
        if (newValue) {
            input.value.caraduduk = 2
            input.value.kursi = 2
            input.value.tindakan = 1
            // input.value.hasiljatuh = "Tidak Beresiko"
        } else {
            input.value.caraduduk = undefined
            input.value.kursi = undefined
            input.value.tindakan = undefined
            // input.value.hasiljatuh = undefined
        }
    }
)


watch(() => [input.value?.penurunanbb, input.value?.penurunannafsu, input.value?.penurunanbbYa], ([newValuePenurunanBB, newValuePenurunanBBYa, newValuePenurunanNafsu]) => {
    let totalNilaiSkriningKalkulasi
    //? Mencegah value checbox dari undefined
    newValuePenurunanBB = newValuePenurunanBB ?? 0;
    newValuePenurunanBBYa = newValuePenurunanBBYa ?? 0;
    newValuePenurunanNafsu = newValuePenurunanNafsu ?? 0;

    //? kalkulasi
    let allCalculate = newValuePenurunanBB + newValuePenurunanBBYa + newValuePenurunanNafsu
    totalNilaiSkriningKalkulasi = allCalculate >= 5 ? 5 : allCalculate;
    // input.value.nilaiSkrining = totalNilaiSkriningKalkulasi

    if (input.value) {
        input.value.nilaiSkrining = totalNilaiSkriningKalkulasi;

        if (totalNilaiSkriningKalkulasi >= 0 && totalNilaiSkriningKalkulasi <= 1) {
            input.value.nilai = "RISIKO RENDAH (MST 0-1)";
        } else if (totalNilaiSkriningKalkulasi >= 2 && totalNilaiSkriningKalkulasi <= 3) {
            input.value.nilai = "RISIKO SEDANG (MST 2-3)";
        } else if (totalNilaiSkriningKalkulasi >= 4) {
            input.value.nilai = "RISIKO TINGGI (MST 4-5)";
        }
    }
});

// watch(() => [input.value.kesadaranE, input.value.kesadaranM, input.value.kesadaranV, input.value.totalKesadaran], () => {
//     let poin1 = input.value.kesadaranE ? parseInt(input.value.kesadaranE) : 0
//     let poin2 = input.value.kesadaranM ? parseInt(input.value.kesadaranM) : 0
//     let poin3 = input.value.kesadaranV ? parseInt(input.value.kesadaranV) : 0
//     const jumlahNilai = poin1 + poin2 + poin3
//     countRangeNilai(jumlahNilai)
//     input.value.totalKesadaran = jumlahNilai
// })
</script>


<style lang="scss">
.fontcheckbox {
    padding: 5px
}

h1 {
    font-weight: bold;
}

.tabels tr td {
    border: 1px solid black;
}

.tabels tr th {
    border: 1px solid black;
}
</style>
