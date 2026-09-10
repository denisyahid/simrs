<style>
.tabels tr td {
    border: 1px solid black;
}

.tabels tr th {
    border: 1px solid black;
}
</style>
<template>
    <ConfirmDialog />
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Nurse Station</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate"
                                @kembaliKeun="kembaliKeun" :isHideST="true"></ButtonEmr>
                        </div>
                        <div class="right" style="display: none !important">
                            <div class="buttons">
                                <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                    Kembali
                                </VButton>
                                <VButton type="button" rounded outlined color="warning" raised icon="lnir lnir-printer"
                                    @click="print()"> Cetak
                                </VButton>
                                <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                    :loading="isLoading" @click="simpan()"> Simpan
                                </VButton>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="column is-12" style="margin-top: 30px;">
                    <div class="columns is-multiline">
                        <!-- <div class="column is-12">
                                <h1 class="mb-3 emr">Nama Template&emsp;&emsp;**Hanya diisi jika ingin membuat template</h1>
                                <VField>
                                    <VControl>
                                        <VTextarea v-model="input.namatemplate" rows="1">
                                        </VTextarea>
                                    </VControl>
                                </VField>
                            </div> -->
                        <div class="column is-6">
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
                                        <VDatePicker v-model="input.kebjamKedatangan" color="green" mode="time" is24hr>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField>
                                                    <VControl icon="feather:clock">
                                                        <VInput class="input form-timepicker is-rounded"
                                                            :value="inputValue" v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <h1 class="mb-3" style="font-weight: bold;">Jam Asesmen Awal</h1>
                                    <VField>
                                        <VDatePicker v-model="input.kebjamAsesmenAwal" color="green" mode="time" is24hr>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField>
                                                    <VControl icon="feather:clock">
                                                        <VInput class="input form-timepicker is-rounded"
                                                            :value="inputValue" v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                            </div>

                        </div>
                        <div class="column is-6">
                            <div class="columns is-multiline">
                                <!-- <div class="column is-6">
                                    <h1 class="mb-3 emr">Pilih Template</h1>
                                    <div class="columns is-multiline">
                                        <div class="column is-9">
                                        <VField>
                                            <VControl>
                                                <input v-model="input.template" class="input" disabled/>
                                            </VControl>
                                        </VField>
                                        </div>
                                        <div class="column is-3">
                                        <VIconButton type="button" raised circle icon="lnir lnir-checkmark-circle" @click="pilihTemplateFix(index)"
                                            color="success" v-tooltip-prime.top="'Template'">
                                        </VIconButton>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="column is-6">
                                    <h1 class="mb-3 emr">Pilih Riwayat</h1>
                                    <div class="columns is-multiline">
                                        <div class="column is-9">
                                            <VField>
                                                <VControl>
                                                    <input v-model="input.template" class="input" disabled />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-3">
                                            <VIconButton type="button" raised circle icon="lnir lnir-checkmark-circle"
                                                @click="pilihTemplate(index)" color="success"
                                                v-tooltip-prime.top="'Riwayat'">
                                            </VIconButton>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="column is-12" style="margin-top: -20px;">
                    <div class="column is-12 pl-0 pr-0">
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <div class="columns is-multiline">
                                        <div class="column is-2">
                                            <h1 style="font-weight: bold; margin-bottom: 10px;" class="ml-3">Rujukan
                                            </h1>
                                        </div>
                                        <div class="column is-2">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox" v-model="input.kebkontrol"
                                                        true-value="YA" label="Ya" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-2">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox" v-model="input.kebkontrol"
                                                        true-value="TIDAK" label="Tidak" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
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
                                <div class="column is-2">
                                    <h1 class="mb-3 emr">ALLOANAMNESIS</h1>
                                </div>
                                <div class="column is-4">
                                    <VField class="is-autocomplete-select" v-slot="{ id }">
                                        <VControl icon="feather:search">
                                            <Multiselect v-model="input.kebpilihanallo" :attrs="{ value }"
                                                placeholder="--Pilih--" label="label" :options="d_allo"
                                                :searchable="true" track-by="label" mode="single" autocomplete="off">
                                            </Multiselect>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VControl>
                                            <VTextarea v-model="input.keballoanamnesis" rows="3">
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
                                <div class="column is-12">
                                    <h1 class="mb-5 emr">ANAMNESIS</h1>
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
                                        <div class="column is-6">
                                            <h1 class="mb-3 emr">Riwayat penyakit keluarga</h1>
                                            <VField>
                                                <VControl>
                                                    <VTextarea v-model="input.riwayatpenyakitkeluarga" rows="3">
                                                    </VTextarea>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <h1 class="mb-3 emr">Riwayat alergi</h1>
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
                                    <h1 class="mb-5 emr">Riwayat menstruasi:</h1>
                                    <div class="columns is-multiline">
                                        <div class="column is-3">
                                            <div class="columns is-multiline">
                                                <div class="column is-5">
                                                    <h1 style="font-weight: bold;">Menarche umur:</h1>
                                                </div>
                                                <div class="column is-7">
                                                    <VField addons>
                                                        <VControl expanded>
                                                            <VInput type="text" class="input" placeholder=""
                                                                v-model="input.menarcheumur" />
                                                        </VControl>
                                                        <VControl class="field-addon-body">
                                                            <VButton static>tahun</VButton>
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column is-3">
                                            <div class="columns is-multiline">
                                                <div class="column is-5">
                                                    <h1 style="font-weight: bold;">Siklus:</h1>
                                                </div>
                                                <div class="column is-7">
                                                    <VField addons>
                                                        <VControl expanded>
                                                            <VInput type="text" class="input" placeholder=""
                                                                v-model="input.siklus" />
                                                        </VControl>
                                                        <VControl class="field-addon-body">
                                                            <VButton static>hari</VButton>
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column is-3">
                                            <div class="columns is-multiline">
                                                <div class="column is-5">
                                                    <h1 style="font-weight: bold;">Lama:</h1>
                                                </div>
                                                <div class="column is-7">
                                                    <VField addons>
                                                        <VControl expanded>
                                                            <VInput type="text" class="input" placeholder=""
                                                                v-model="input.lama" />
                                                        </VControl>
                                                        <VControl class="field-addon-body">
                                                            <VButton static>hari</VButton>
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column is-3">
                                            <div class="columns is-multiline">
                                                <div class="column is-5">
                                                    <h1 style="font-weight: bold;">Volume:</h1>
                                                </div>
                                                <div class="column is-7">
                                                    <VField addons>
                                                        <VControl expanded>
                                                            <VInput type="text" class="input" placeholder=""
                                                                v-model="input.volume" />
                                                        </VControl>
                                                        <VControl class="field-addon-body">
                                                            <VButton static>cc</VButton>
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
                                                    <h1 style="font-weight: bold;">Keluhan saat haid:</h1>
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
                                    <h1 class="mb-5 emr">Riwayat kehamilan, persalinan dan nifas yang lalu:</h1>
                                </div>
                                <div class="column is-12">
                                    <div style="overflow-y:auto;" class="mt-1">
                                        <table class="table-rpo tabels" border="1"
                                            style="width: 175%; border: 1px solid black;">
                                            <thead>
                                                <tr>
                                                    <th class="th-po" style="vertical-align: inherit;text-align:center"
                                                        rowspan="3">Tgl
                                                        Partus</th>
                                                    <th class="th-po" style="vertical-align: inherit;text-align:center"
                                                        colspan="3">Umur
                                                        Hamil</th>
                                                    <th class="th-po" style="vertical-align: inherit;text-align:center"
                                                        rowspan="3" width="15%">Jenis Partus</th>
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
                                                            <!-- <VField>
                                                                <VDatePicker v-model="input.tanggalPartus" mode="date"
                                                                    trim-weeks :max-date="new Date()">
                                                                    <template #default="{ inputValue, inputEvents }">
                                                                        <VField>
                                                                            <VControl icon="feather:calendar" fullwidth>
                                                                                <VInput :value="inputValue"
                                                                                    placeholder="Tanggal"
                                                                                    v-on="inputEvents" />
                                                                            </VControl>
                                                                        </VField>
                                                                    </template>
                                                                </VDatePicker>
                                                            </VField> -->
                                                            <VField addons>
                                                                <VControl expanded>
                                                                    <VInput type="text" class="heightinput input"
                                                                        placeholder="Tanggal"
                                                                        v-model="input.tanggalPartus" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </td>
                                                    <td class="td-po">
                                                        <div class="column pt-3 pb-0">
                                                            <VField>
                                                                <VControl>
                                                                    <VCheckbox class="fontcheckbox"
                                                                        v-model="input.abortus" true-value="true"
                                                                        label="" color="primary" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </td>
                                                    <td class="td-po">
                                                        <div class="column pt-3 pb-0">
                                                            <VField>
                                                                <VControl>
                                                                    <VCheckbox class="fontcheckbox"
                                                                        v-model="input.prematur" true-value="true"
                                                                        label="" color="primary" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </td>
                                                    <td class="td-po">
                                                        <div class="column pt-3 pb-0">
                                                            <VField>
                                                                <VControl>
                                                                    <VCheckbox class="fontcheckbox"
                                                                        v-model="input.aterm" true-value="true" label=""
                                                                        color="primary" />
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
                                                                        :searchable="true" track-by="label"
                                                                        mode="single" autocomplete="off">
                                                                    </Multiselect>
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </td>
                                                    <td class="td-po">
                                                        <div class="column pt-3 pb-0">
                                                            <VField>
                                                                <VControl>
                                                                    <VCheckbox class="fontcheckbox"
                                                                        v-model="input.nakes" true-value="true" label=""
                                                                        color="primary" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </td>
                                                    <td class="td-po">
                                                        <div class="column pt-3 pb-0">
                                                            <VField>
                                                                <VControl>
                                                                    <VCheckbox class="fontcheckbox"
                                                                        v-model="input.nonnakes" true-value="true"
                                                                        label="" color="primary" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </td>
                                                    <td class="td-po">
                                                        <div class="column pt-3 pb-0">
                                                            <VField>
                                                                <VControl>
                                                                    <VCheckbox class="fontcheckbox"
                                                                        v-model="input.perempuan" true-value="true"
                                                                        label="" color="primary" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </td>
                                                    <td class="td-po">
                                                        <div class="column pt-3 pb-0">
                                                            <VField>
                                                                <VControl>
                                                                    <VCheckbox class="fontcheckbox"
                                                                        v-model="input.lakilaki" true-value="true"
                                                                        label="" color="primary" />
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
                                                                    <VCheckbox class="fontcheckbox"
                                                                        v-model="input.normal" true-value="true"
                                                                        label="" color="primary" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </td>
                                                    <td class="td-po">
                                                        <div class="column pt-3 pb-0">
                                                            <VField>
                                                                <VControl>
                                                                    <VCheckbox class="fontcheckbox"
                                                                        v-model="input.cacat" true-value="true" label=""
                                                                        color="primary" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </td>
                                                    <td class="td-po">
                                                        <div class="column pt-3 pb-0">
                                                            <VField>
                                                                <VControl>
                                                                    <VCheckbox class="fontcheckbox"
                                                                        v-model="input.meninggal" true-value="true"
                                                                        label="" color="primary" />
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
                                                                <VIconButton type="button" raised circle
                                                                    icon="feather:plus" @click="addNewItem()"
                                                                    color="info" v-tooltip.bubble="'Tambah '">
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
                    </div>
                </div>

                <hr><br>

                <div class="column is-12" style="margin-top: -20px;">
                    <div class="column is-12 pl-0 pr-0">
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <h1 class="mb-3 emr">Riwayat pemakaian alat kontrasepsi:</h1>
                                </div>
                                <div class="column is-4">
                                    <h1 class="mb-3 emr">-</h1>
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
                                    <h1 class="mb-3 emr">Jenis</h1>
                                    <VField addons>
                                        <VControl expanded>
                                            <VInput type="text" class="heightinput input" placeholder=""
                                                v-model="input.jenis" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <h1 class="mb-3 emr">Lama pemakaian</h1>
                                    <VField addons>
                                        <VControl expanded>
                                            <VInput type="text" class="heightinput input" placeholder=""
                                                v-model="input.lamapemakaian" />
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
                                <div class="column is-12">
                                    <h1 class="mb-3 emr">Riwayat hamil ini:</h1>
                                </div>
                                <div class="column is-2">
                                    <h1 class="mb-3 emr">Hari pertama haid terakhir</h1>
                                    <VField addons>
                                        <VControl expanded>
                                            <VInput type="text" class="heightinput input" placeholder=""
                                                v-model="input.pertamahaid" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-2">
                                    <h1 class="mb-3 emr">Tafsiran partus</h1>
                                    <VField addons>
                                        <VControl expanded>
                                            <VInput type="text" class="heightinput input" placeholder=""
                                                v-model="input.tafsiranpartus" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-2">
                                    <h1 class="mb-3 emr">Ante Natal Care</h1>
                                    <VField class="is-autocomplete-select" v-slot="{ id }">
                                        <VControl icon="feather:search">
                                            <Multiselect v-model="input.antenatalcare" :attrs="{ value }"
                                                placeholder="--Pilih--" label="label" :options="d_antenatalcare"
                                                :searchable="true" track-by="label" mode="single" autocomplete="off">
                                            </Multiselect>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-1">
                                    <h1 class="mb-3 emr">di</h1>
                                    <VField class="is-autocomplete-select" v-slot="{ id }">
                                        <VControl icon="feather:search">
                                            <Multiselect v-model="input.di" :attrs="{ value }" placeholder="--Pilih--"
                                                label="label" :options="d_di" :searchable="true" track-by="label"
                                                mode="single" autocomplete="off">
                                            </Multiselect>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-2">
                                    <h1 class="mb-3 emr">Frekuensi</h1>
                                    <VField class="is-autocomplete-select" v-slot="{ id }">
                                        <VControl icon="feather:search">
                                            <Multiselect v-model="input.frekuensi" :attrs="{ value }"
                                                placeholder="--Pilih--" label="label" :options="d_frekuensi"
                                                :searchable="true" track-by="label" mode="single" autocomplete="off">
                                            </Multiselect>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-2">
                                    <h1 class="mb-3 emr">Imunisasi TT</h1>
                                    <VField class="is-autocomplete-select" v-slot="{ id }">
                                        <VControl icon="feather:search">
                                            <Multiselect v-model="input.imunisasitt" :attrs="{ value }"
                                                placeholder="--Pilih--" label="label" :options="d_imunisasitt"
                                                :searchable="true" track-by="label" mode="single" autocomplete="off">
                                            </Multiselect>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-1">
                                    <h1 class="mb-3 emr">Sebanyak</h1>
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
                    </div>
                </div>

                <div class="column is-12" style="margin-top: -20px;">
                    <div class="column is-12 pl-0 pr-0">
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <h1 class="mb-3 emr">Keluhan saat hamil</h1>
                                </div>
                                <div class="column is-1">
                                    <VCheckbox class="fontcheckbox" v-model="input.mual" true-value="Mual" label="Mual"
                                        color="primary" circle />
                                </div>
                                <div class="column is-1">
                                    <VCheckbox class="fontcheckbox" v-model="input.muntah" true-value="Muntah"
                                        label="Muntah" color="primary" circle />
                                </div>
                                <div class="column is-1">
                                    <VCheckbox class="fontcheckbox" v-model="input.pendarahan" true-value="Pendarahan"
                                        label="Pendarahan" color="primary" circle />
                                </div>
                                <div class="column is-1">
                                    <VCheckbox class="fontcheckbox" v-model="input.pusing" true-value="Pusing"
                                        label="Pusing" color="primary" circle />
                                </div>
                                <div class="column is-2">
                                    <VCheckbox class="fontcheckbox" v-model="input.sakitkepala"
                                        true-value="Sakit Kepala" label="Sakit Kepala" color="primary" circle />
                                </div>
                                <div class="column is-6">
                                    <div class="columns is-multiline">
                                        <div class="column is-2">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox" v-model="input.keluhanhamillain"
                                                        true-value="Lainnya" label="Lainnya" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-4">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" class="heightinput input" placeholder=""
                                                        v-model.number="input.ketKeluhan" />
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
                <hr>


                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <h1 class="mb-3 emr">PEMERIKSAAN FISIK:</h1>
                                </div>
                                <div class="column is-2">
                                    <h1 class="mb-12 emr">Keadaan Umum</h1>
                                    <VField class="is-autocomplete-select">
                                        <VControl icon="feather:search">
                                            <Multiselect v-model="input.keadaanumumobgyn" :attrs="{ value }"
                                                placeholder="--Pilih--" label="label" :options="d_keadaanumum"
                                                :searchable="true" track-by="label" mode="single" autocomplete="off">
                                            </Multiselect>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-2">
                                    <div class="column is-12" style="margin-top: -10px;">
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
                                </div>
                                <div class="column is-2">
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
                                <div class="column is-2">
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
                                <div class="column is-2">
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
                                <div class="column is-2">
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
                                <div class="column is-2">
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
                                <div class="column is-2">
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
                                <div class="column is-8">
                                    <h1 class="mb-3 emr">GCS</h1>
                                    <div class="columns is-multiline">
                                        <div class="column is-4">
                                            <VField addons>
                                                <VControl class="field-addon-body">
                                                    <VButton static>E</VButton>
                                                </VControl>
                                                <VControl expanded>
                                                    <Multiselect v-model="input.gcse" :attrs="{ value }" placeholder="E"
                                                        label="label" :options="d_gcse" :searchable="true"
                                                        track-by="label" mode="single" autocomplete="off">
                                                    </Multiselect>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-4">
                                            <VField addons>
                                                <VControl class="field-addon-body">
                                                    <VButton static>V</VButton>
                                                </VControl>
                                                <VControl expanded>
                                                    <Multiselect v-model="input.gcsv" :attrs="{ value }" placeholder="V"
                                                        label="label" :options="d_gcsv" :searchable="true"
                                                        track-by="label" mode="single" autocomplete="off">
                                                    </Multiselect>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-4">
                                            <VField addons>
                                                <VControl class="field-addon-body">
                                                    <VButton static>M</VButton>
                                                </VControl>
                                                <VControl expanded>
                                                    <Multiselect v-model="input.gcsm" :attrs="{ value }" placeholder="M"
                                                        label="label" :options="d_gcsm" :searchable="true"
                                                        track-by="label" mode="single" autocomplete="off">
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
                                    <td style="width:25%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
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
                                        <span class="mb-2">{{ resep.no }}</span><br>
                                    </td>
                                    <td style="width:20%;text-align:center">
                                        <span class="mb-2">{{ resep.created_at }}</span><br>
                                    </td>
                                    <td style="width:20%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                                    </td>
                                    <td style="width:50%;text-align:center">
                                        <span class="mb-2">{{ resep.namatemplate }}</span><br>
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
import * as EMR from '../page-emr-plugins/asesmen-medis-rj'
import AutoComplete from 'primevue/autocomplete';
import Slider from 'primevue/slider';
import Fieldset from 'primevue/fieldset';
import TOdontogram from './odontogram.vue'
import TRiwayatRegistrasi from './riwayat-registrasi.vue'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from "primevue/useconfirm"
import ButtonEmr from '../page-emr-plugins/button-emr.vue'

useHead({
    title: 'Asesmen Awal - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let kelompokQuery = useRoute().query.kelompokuser as string
let norec_emr = useRoute().query.norec_emr as string
let isfromCPPT = useRoute().query.iscppt as boolean

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

const formName = ref(props.FORM_NAME);

const route = useRoute()
const pasien: any = ref({})
const isLoadingPasien: any = ref(false)
const modalConfirm: any = ref(false)
const confirm = useConfirm();
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
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

const COLLECTION: any = ref('AsesmenAwalKebidananRawatJalanNurse') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const d_ko: any = ref('')
const input: any = ref({
    waktuTataLaksana: new Date,
    waktuKontrol: new Date,
    kebjamKedatangan: new Date(),
    kebjamAsesmenAwal: new Date(),
    kebtanggalKedatangan: new Date(),
    keadaanumumobgyn: 1,
    gcse: 4,
    gcsv: 5,
    gcsm: 6,
    details: [{
        no: 1,
    }]
})
const d_Dokter: any = ref([])
const dataSourceICD9: any = ref([])
const dataSourceICD10: any = ref([])
const isPemeriksaanFisik: any = ref(true)
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const isLoading = ref(false)
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)


// ==================== Start List Data =================
const anamnesa = ref(EMR.anamnesa())
const anamnesa1 = ref(EMR.anamnesa1())
const listFaktorResJantung = ref(EMR.faktorResJantung())
const keadaanUmum = ref(EMR.keadaanUmum_1())
const pemeriksaanFisik = ref(EMR.pemeriksaanFisik())
const kesadaran = ref(EMR.kesadaran())
const descRangeKesadaran = ref(EMR.dscRangeKesadaran())
const keadaanUmum2 = ref(EMR.keadaanUmum_2())
const pemeriksaanPenunjang = ref(EMR.penunjang())
const dateAndDescrip = ref(EMR.dateAndDescrip())
const prognosis = ref(EMR.prognosis())


// ==================== End List Data ==================

const fetchDokter = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
        d_Dokter.value = response
    })
}

const diagnosa = async () => {
    await useApi().get(`emr/get-diagnosa-pasien-icd9?nocmfk=${ID_PASIEN}`).then((response) => {
        response.forEach((element: any, i: any) => {
            element.no = i + 1
            element.tglInput = H.formatDate(element.tglinputdiagnosa, 'DD-MM-YYYY')
        });
        dataSourceICD9.value = response
        // console.log(response)
    })
    await useApi().get(`emr/get-diagnosa-pasien-icd10?nocmfk=${ID_PASIEN}`).then((response) => {
        response.forEach((element: any, i: any) => {
            element.no = i + 1
            element.tglInput = H.formatDate(element.tglinputdiagnosa, 'DD-MM-YYYY')
        });
        dataSourceICD10.value = response
    })
}

const loadRiwayat = async () => {
    isLoading.value = true
    let responsex = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    isLoading.value = false
    if (responsex.length) {
        input.value = responsex[0] //set ke inputan 
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = responsex[0].emrpasienfk
        }
    }
    else {
        isLoading.value = true
        let responsetgl = await useApi().get(`/emr/get-emr-tgl-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`)
        isLoading.value = false

        if (responsetgl.length) {
            confirm.require({
                message: 'Nurse Station terakhir tanggal ' + H.formatDate(responsetgl[0].created_at, 'DD-MM-YYYY HH:mm:ss') + ', apakah mau mengambil data sebelumnya?',
                header: 'Riwayat Terakhir',
                icon: 'pi pi-info-circle',
                acceptClass: 'p-button-danger',
                accept: () => {
                    isLoading.value = true
                    useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
                        isLoading.value = false
                        if (responselast.length) {
                            input.value = responselast[0]
                            let d = input.value
                            delete d['_id']
                            delete d['emrpasienfk']
                            delete d['user_input']
                            delete d['created_at']
                            d.id = ''
                            d.keadaanumumobgyn = 1
                            d.gcse = 4
                            d.gcsv = 5
                            d.gcsm = 6
                            d.kebtanggalKedatangan = new Date()
                            d.kebjamKedatangan = new Date()
                            d.kebjamAsesmenAwal = new Date()
                            d.tekananDarahObgyn = ''
                            d.nadiObgyn = ''
                            d.nafasObgyn = ''
                            d.celciusObgyn = ''
                            d.sao2Obgyn = ''
                            d.beratbadanObgyn = ''
                            d.tinggibadanObgyn = ''
                            d.namatemplate = ''
                        } else {
                            H.alert('warning', 'Data tidak ada')
                        }
                    })
                },
                reject: () => { },
            })
        }
    }
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

const addTemplate = (response: any) => {
    console.log(response)
    input.value = response //set ke inputan
    input.value.namatemplate = null
    let d = input.value
    delete d['_id']
    delete d['emrpasienfk']
    delete d['user_input']
    delete d['created_at']
    d.id = ''
    d.keadaanumumobgyn = 1
    d.gcse = 4
    d.gcsv = 5
    d.gcsm = 6
    d.kebtanggalKedatangan = new Date()
    d.kebjamKedatangan = new Date()
    d.kebjamAsesmenAwal = new Date()
    d.tekananDarahObgyn = ''
    d.nadiObgyn = ''
    d.nafasObgyn = ''
    d.celciusObgyn = ''
    d.sao2Obgyn = ''
    d.beratbadanObgyn = ''
    d.tinggibadanObgyn = ''
    showModalTemplate.value = false
}
const router = useRouter()
const simpan = () => {
    // Validasi
    if (!input.value.keluhanutama || input.value.keluhanutama && input.value.keluhanutama.replace(/\s/g, "").length < 4) {
        H.alert('error', 'Keluhan Utama, ' + 'diisi minimal 4 karakter');
        return;
    }

    let ID = input.id ? input.id : ''

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
        'name_form': formName.value,
        'jenis_emr': 'asesmen_medis',
        'data': object
    }
    isLoading.value = true
    // console.log(json)

    // // isLoading.value = true
    useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
        isLoading.value = false
        NOREC_EMRPASIEN.value = response.norec_emr
        const user = useUserSession().getUser()
        let lockRoute = H.cacheHelper().get('lockedRoute');
        console.log("KELOMPOK QUERY", kelompokQuery)
        if (kelompokQuery && kelompokQuery.toUpperCase().indexOf('NURSE') > -1) {
            console.log(lockRoute);
            if (lockRoute != null || lockRoute != undefined) {
                console.log("LOCK")
                router.push({
                    name: lockRoute,
                })
            } else {
                console.log("KELOMPOK USER MENU", user.kelompokUser.menu)
                router.push({
                    name: user.kelompokUser.menu,
                })
            }
        }
    }).catch((e: any) => {
        isLoading.value = false
    })

    // console.log(resultValue)
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
        'name_form': formName.value,
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



const countRangeNilai = (e: any) => {

    let cmc = {
        "keterangan": "CMC (14-15)",
        "poin": 15
    }
    let apatis = {
        "keterangan": "Apatis (12-13)",
        "poin": 13
    }
    let somnolen = {
        "keterangan": "Somnolen (10-11)",
        "poin": 11
    }
    let delirium = {
        "keterangan": "Delirium (7-9)",
        "poin": 9
    }
    let stupar = {
        "keterangan": "Stupar (4-6)",
        "poin": 6
    }
    let koma = {
        "keterangan": "Koma ( <= 3)",
        "poin": 3
    }

    descRangeKesadaran.value.forEach((elements: any) => {
        if (e <= 3 && e <= elements.value.poin) {
            input.value.rangeKesadaran = koma
        }
        else if (e <= 6 && e <= elements.value.poin) {
            input.value.rangeKesadaran = stupar
        }
        else if (e <= 9 && e <= elements.value.poin) {
            input.value.rangeKesadaran = delirium
        }
        else if (e <= 11 && e <= elements.value.poin) {
            input.value.rangeKesadaran = somnolen
        }
        else if (e <= 13 && e <= elements.value.poin) {
            input.value.rangeKesadaran = apatis
        }
        else if (e > 13 && e > elements.value.poin) {
            input.value.rangeKesadaran = cmc
        }
    })
}
const getDataExist = async () => {
    await useApi().get(`emr/get-data-exist?nocmfk=${ID_PASIEN}`).then((response) => {
        if (response) {
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

const print = async () => {
    H.printBlade(`emr/cetak-asesmen-medis-rj?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
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
    if (isfromCPPT) {
        H.alert('error', 'Silahkan isi Nurse Station Terlebih Dahulu !');
        formName.value = 'Nurse Station'
    }
    modalConfirm.value = true
    getDataExist()
    diagnosa()
    fetchPasien()
})

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

watch(() => [
    input.value.kesadaranE,
    input.value.kesadaranM,
    input.value.kesadaranV,
    input.value.totalKesadaran,
    // input.value.point2
], () => {
    let poin1 = input.value.kesadaranE ? parseInt(input.value.kesadaranE) : 0
    let poin2 = input.value.kesadaranM ? parseInt(input.value.kesadaranM) : 0
    let poin3 = input.value.kesadaranV ? parseInt(input.value.kesadaranV) : 0
    const jumlahNilai = poin1 + poin2 + poin3
    countRangeNilai(jumlahNilai)
    input.value.totalKesadaran = jumlahNilai
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

.checkbox.is-outlined {
    padding: unset !important;
}

// .p-fieldset.p-component{
//     border-left: ;
// }

h1.emr {
    font-weight: bold;
}

table.assesment {
    border-collapse: collapse;
    width: 100%;
}

.heightinput {
    height: 25px
}

.assesment th {
    text-align: center !important;
    border-bottom: 1px solid black;
    // border: 1px solid black;
}


.assesment th,
td {
    padding: 8px;
    vertical-align: middle !important;
}

.fontcheckbox {
    font-size: 12px;
}

hr {
    background-color: hsl(0deg 6.81% 88.68%);
    border: none;
    display: block;
    height: 2px;
    margin: 1rem 0;
}
</style>
