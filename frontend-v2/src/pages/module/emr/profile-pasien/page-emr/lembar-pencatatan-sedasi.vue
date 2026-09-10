<style lang="scss">
table {
    border-collapse: collapse;
    width: 100%;
}

.border {
    border: 1px solid black;
}

.th-pri,
.td-pri {
    padding: 7px;
    border: 1px solid black;
    vertical-align: inherit;
}

hr {
    margin: 0px;
}
</style>

<template>
    <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3>Lembar Pencatatan Sedasi</h3>
                    </div>
                    <div class="right">
                        <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                            @simpan="simpan" @kembaliKeun="kembaliKeun" :isHideCetak="true">
                        </ButtonEmr>
                    </div>
                </div>
            </div>

            <!-- form baru -->

            <div class="column is-12">
                <div class="columns is-multiline" style="border-bottom: 1px solid black;">
                    <div class="column is-6">
                        <p class="m-0">Tanggal</p>
                        <VDatePicker v-model="input.DTTanggal" mode="date" trim-weeks :max-date="new Date()">
                            <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                            </template>
                        </VDatePicker>
                    </div>
                    <div class="column is-6">
                        <p class="m-0">Jam</p>
                        <VDatePicker v-model="input.TJam" mode="time" is24hr>
                            <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:clock" fullwidth>
                                    <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                            </template>
                        </VDatePicker>
                    </div>
                    <div class="column is-6" style="border-right: 1px solid black;border-top:1px solid black">
                        <p style="font-weight: bold;">RENCANA SEDASI</p>
                        <div class="columns is-multiline">
                            <div class="column is-12 pb-0">
                                <p>Tingkat Sedasi : </p>
                            </div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Moderat" label="Moderat"
                                        v-model="input.CBModerat_TS" />
                                </VControl>
                            </div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Sedasi" label="Sedasi"
                                        v-model="input.CBSedasi_TS" />
                                </VControl>
                            </div>
                        </div>
                        <div class="columns is-multiline">
                            <div class="column is-12 pb-0 pt-0">
                                <p>Jenis Sedasi : </p>
                            </div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Oral" label="Oral"
                                        v-model="input.CBOral_JS" />
                                </VControl>
                            </div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="IM" label="IM"
                                        v-model="input.CBIM_JS" />
                                </VControl>
                            </div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="IV" label="IV"
                                        v-model="input.CBIV_JS" />
                                </VControl>
                            </div>
                            <div class="column is-3"></div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Rektal" label="Rektal"
                                        v-model="input.CBRektal_JS" />
                                </VControl>
                            </div>
                            <div class="column is-3">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBRektal_JS" />
                                </VControl>
                            </div>
                        </div>
                        <div class="columns is-multiline">
                            <div class="column is-12 pb-0 pt-0">
                                <p>Analgesia pasca sedasi : </p>
                            </div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Oral" label="Oral"
                                        v-model="input.CBOral_APS" />
                                </VControl>
                            </div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="IM" label="IM"
                                        v-model="input.CBIM_APS" />
                                </VControl>
                            </div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="IV" label="IV"
                                        v-model="input.CBIV_APS" />
                                </VControl>
                            </div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Rektal" label="Rektal"
                                        v-model="input.CBRektal_APS" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Tidak Diberikan"
                                        label="Tidak Diberikan" v-model="input.CBTidakDiberikan_APS" />
                                </VControl>
                            </div>
                        </div>
                        <div>
                            <p class="m-0">Obat : </p>
                            <VField>
                                <VTextarea rows="2" v-model="input.TAObat"></VTextarea>
                            </VField>
                        </div>
                    </div>
                    <div class="column is-6" style="border-top:1px solid black">
                        <p style="font-weight: bold;">EVALUASI PRASEDASI</p>
                        <div class="columns is-multiline">
                            <div class="column is-6">
                                <p style="margin: 0px;">Kesadaran : </p>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBKesadaran" />
                                </VControl>
                            </div>
                            <div class="column is-6">
                                <div class="columns is-multiline column is-12 pb-0">
                                    <div class="column is-12 pt-0 pb-0">
                                        <h1>GCS</h1>
                                    </div>
                                    <div class="column is-6 pt-0">
                                        <VField addons>
                                            <VControl class="field-addon-body">
                                                <VButton static>E</VButton>
                                            </VControl>
                                            <VControl expanded>
                                                <Multiselect v-model="input.gcse" :attrs="{ value }" placeholder="E"
                                                    label="label" :options="d_gcse" :searchable="true" track-by="label"
                                                    mode="single" autocomplete="off"
                                                    style="border-radius:0px 4px 4px 0px;height:100%">
                                                </Multiselect>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-6 pt-0">
                                        <VField addons>
                                            <VControl class="field-addon-body">
                                                <VButton static>V</VButton>
                                            </VControl>
                                            <VControl expanded>
                                                <Multiselect v-model="input.gcsv" :attrs="{ value }" placeholder="V"
                                                    label="label" :options="d_gcsv" :searchable="true" track-by="label"
                                                    mode="single" autocomplete="off"
                                                    style="border-radius:0px 4px 4px 0px;height:100%">
                                                </Multiselect>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-6 pt-0">
                                        <VField addons>
                                            <VControl class="field-addon-body">
                                                <VButton static>M</VButton>
                                            </VControl>
                                            <VControl expanded>
                                                <Multiselect v-model="input.gcsm" :attrs="{ value }" placeholder="M"
                                                    label="label" :options="d_gcsm" :searchable="true" track-by="label"
                                                    mode="single" autocomplete="off"
                                                    style="border-radius:0px 4px 4px 0px;height:100%">
                                                </Multiselect>
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6 pt-0">
                                <label>Tekanan Darah</label>
                                <VField addons>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBS_TD" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>mmHg</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-6 pt-0">
                                <label>Nadi</label>
                                <VField addons>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBS_Nadi" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>x/menit</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="columns is-multiline column is-12 pb-0">
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Reg" label="Reg"
                                            v-model="input.CBReg" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Ireg" label="Ireg"
                                            v-model="input.CBIreg" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Adekuat"
                                            label="Adekuat" v-model="input.CBAdekuat" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Inadekuat"
                                            label="Inadekuat" v-model="input.CBInadekuat" />
                                    </VControl>
                                </div>
                            </div>
                            <div class="columns is-multiline column is-12 pb-0">
                                <div class="column is-12 pt-0 pb-0">
                                    <p class="p-0">Respirasi : </p>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Spontan"
                                            label="Spontan" v-model="input.CBSpontan" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Assisted"
                                            label="Assisted" v-model="input.CBAssisted" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Controled"
                                            label="Controled" v-model="input.CBControled" />
                                    </VControl>
                                </div>
                            </div>
                            <div class="column is-6 pt-0">
                                <label>RR</label>
                                <VField addons>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBS_RR" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>x/menit</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-6 pt-0">
                                <label>O<sub>2</sub></label>
                                <VField addons>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBS_O2" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>L/mnt</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-6 pt-0">
                                <label>SpO2</label>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TB_SpO2" />
                                </VControl>
                            </div>
                            <div class="columns is-multiline column is-12 pb-0">
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Udara Bebas"
                                            label="Udara Bebas" v-model="input.CBUdaraBebas" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Kanul Nasal"
                                            label="Kanul Nasal" v-model="input.CBKanul_Nasal" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Simple Mask"
                                            label="Simple Mask" v-model="input.CBSimple_Mask" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Rebreathing Mask"
                                            label="Rebreathing Mask" v-model="input.CBRebreathing_Mask" />
                                    </VControl>
                                </div>
                                <div class="column is-3 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Non Rebreathing Mask"
                                            label="Non Rebreathing Mask" v-model="input.CBNon_Rebreathing_Mask" />
                                    </VControl>
                                </div>
                                <div class="column is-3 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Terintubasi"
                                            label="Terintubasi" v-model="input.CBTerintubasi" />
                                    </VControl>
                                </div>
                            </div>
                            <div class="columns is-multiline column is-12">
                                <div class="column is-12 pb-0 pt-0">
                                    Status fisik ASA :
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="I" label="I"
                                            v-model="input.CBI_SFSA" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="II" label="II"
                                            v-model="input.CBII_SFSA" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="III" label="III"
                                            v-model="input.CBIII_SFSA" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="IV" label="IV"
                                            v-model="input.CBIV_SFSA" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="V" label="V"
                                            v-model="input.CBV_SFSA" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="E" label="E"
                                            v-model="input.CBE_SFSA" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-12 pt-0" style="text-align: center;font-weight: bold;font-size: large;">
                    <h1>MONITORING SEDASI</h1>
                </div>
                <div class="column is-12">
                    <VCard style="border-radius: 16px;" v-if="chartData != null">
                        <Chart type="line" :data="chartData" :options="chartOptions" :height="300" class="h-30rem" />
                    </VCard>
                </div>
                <div class="column is-12" style="overflow-y: auto;height: 500px;">
                    <table style="border: 1px solid black;">
                        <tbody v-for="(item, index) in input.details" :key="index">
                            <tr>
                                <th style="text-align: center;" class="th-pri" colspan="2">
                                    Pukul
                                </th>
                                <th style="text-align: center;" class="th-pri" colspan="2">
                                    Saturasi O<sub>2</sub>
                                </th>
                                <th style="width: 15%;text-align: center;" class="th-pri">
                                    #
                                </th>
                            </tr>
                            <tr>
                                <th style="text-align: center;" class="th-pri" colspan="2">
                                    <VDatePicker v-model="item.pukul" mode="time" is24hr>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:clock" style="width:100%">
                                                <VInput :value="inputValue" placeholder="Pukul..." v-on="inputEvents" />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </th>
                                <th style="text-align: center;" class="th-pri" colspan="2">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.SaturasiO2" />
                                    </VControl>
                                </th>
                                <th>
                                    <div class="column">
                                        <VButtons style="justify-content:space-around;">
                                            <VIconButton type="button" raised circle icon="feather:plus"
                                                @click="addNewItem()" color="info" v-tooltip.bubble="'Tambah '">
                                            </VIconButton>
                                            <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle
                                                icon="feather:trash" @click="removeItem(index)" color="danger">
                                            </VIconButton>
                                        </VButtons>
                                    </div>
                                </th>
                            </tr>
                            <tr>
                                <th style="width: 20%;text-align: center;" class="th-pri">
                                    Temperature
                                </th>
                                <th style="width: 20%;text-align: center;" class="th-pri">
                                    Respirasi
                                </th>
                                <th style="width: 20%;text-align: center;" class="th-pri">
                                    Nadi
                                </th>
                                <th style="width: 20%;text-align: center;" class="th-pri">
                                    Tekanan Darah
                                </th>
                            </tr>
                            <tr>
                                <td class="td-pri">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.Temperature" />
                                    </VControl>
                                </td>
                                <td class="td-pri">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.Respirasi" />
                                    </VControl>
                                </td>
                                <td class="td-pri">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.Nadi" />
                                    </VControl>
                                </td>
                                <td class="td-pri">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.TekananDarah" />
                                    </VControl>
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 20%;text-align: center;" class="th-pri">
                                    Obat-obatan
                                </th>
                                <th style="width: 20%;text-align: center;" class="th-pri">
                                    Keterangan
                                </th>
                                <th style="width: 20%;text-align: center;" class="th-pri">
                                    Cairan
                                </th>
                                <th style="width: 20%;text-align: center;" class="th-pri">
                                    Keterangan Cairan
                                </th>
                            </tr>
                            <tr>
                                <td class="td-pri">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.Obat" />
                                    </VControl>
                                </td>
                                <td class="td-pri">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.KeteranganObat" />
                                    </VControl>
                                </td>
                                <td class="td-pri">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.Cairan" />
                                    </VControl>
                                </td>
                                <td class="td-pri">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.KeteranganCairan" />
                                    </VControl>
                                </td>
                            </tr>
                            <tr>
                                <td class="td-pri">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.Obat2" />
                                    </VControl>
                                </td>
                                <td class="td-pri">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.KeteranganObat2" />
                                    </VControl>
                                </td>
                                <td class="td-pri">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.Cairan2" />
                                    </VControl>
                                </td>
                                <td class="td-pri">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.KeteranganCairan2" />
                                    </VControl>
                                </td>
                            </tr>
                            <tr>
                                <td class="td-pri">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.Obat3" />
                                    </VControl>
                                </td>
                                <td class="td-pri">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.KeteranganObat3" />
                                    </VControl>
                                </td>
                                <td class="td-pri">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.Cairan3" />
                                    </VControl>
                                </td>
                                <td class="td-pri">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.KeteranganCairan3" />
                                    </VControl>
                                </td>
                            </tr>
                            <tr>
                                <td class="td-pri">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.Obat4" />
                                    </VControl>
                                </td>
                                <td class="td-pri">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.KeteranganObat4" />
                                    </VControl>
                                </td>
                                <td class="td-pri">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.Cairan4" />
                                    </VControl>
                                </td>
                                <td class="td-pri">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.KeteranganCairan4" />
                                    </VControl>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="column is-12">
                    <p>N* &nbsp;&nbsp; TDSv &nbsp;&nbsp; TDD^ &nbsp;&nbsp; MAP X &nbsp;&nbsp;
                        RR+ &nbsp;&nbsp; Suhu o &nbsp;&nbsp; Mulai sedasi -> x &nbsp;&nbsp;
                        Selesai sedasi x &lt;- &nbsp;&nbsp; Mulai Prosedur -> o &nbsp;&nbsp;
                        Selesai Prosedur o &lt;-</p>
                </div>
                <div class="column is-12">
                    <p>Hal penting yang terjadi selama prosedur sedasi
                        (komplikasi/efek samping, intervensi jalan napas,
                        pemberian
                        antidotum, resusitasi, dll) :</p>
                    <VField>
                        <VTextarea rows="2" v-model="input.TAHalPenting_MS"></VTextarea>
                    </VField>
                </div>
                <div class="columns">
                    <div class="column is-6">
                        <p style="font-weight: bold;">Kedalaman Sedasi : </p>
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Tak Tersedasi"
                                label="Tak Tersedasi (typical response/cooperation for this patient)"
                                v-model="input.CBTakTersedasi" />
                        </VControl>
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Ringan"
                                label="Ringan (anxiolysis)" v-model="input.CBRingan" />
                        </VControl>
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Sedang"
                                label="Sedang (purposeful response to verbal commands/light tactile sensation)"
                                v-model="input.CBSedang" />
                        </VControl>
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Dalam"
                                label="Dalam (purposeful response after repeated verbal/painful stimulation)"
                                v-model="input.CBDalam" />
                        </VControl>
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Anestesi Umum"
                                label="Anestesi Umum /not arausable" v-model="input.CBAnestesiUmum" />
                        </VControl>
                    </div>
                    <div class="column is-6">
                        <p style="font-weight: bold;">Respon Terhadap Sedasi : </p>
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Sangat Baik"
                                label="Sangat Baik : tenang dan kooperatif" v-model="input.CBSangatBaik" />
                        </VControl>
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Baik"
                                label="Baik : keluhan ringan &/or meringis tapi prosedur tidak terganggu"
                                v-model="input.CBBaik" />
                        </VControl>
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Cukup"
                                label="Cukup : menangis/meringis dan prosedur terganggu minimal"
                                v-model="input.CBCukup" />
                        </VControl>
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Kurang"
                                label="Kurang : adanya gerakan yang mengganggu prosedur" v-model="input.CBKurang" />
                        </VControl>
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Jelek"
                                label="Jelek : gerak aktif, menangis/meringis, prosedur berhenti"
                                v-model="input.CBJelek" />
                        </VControl>
                    </div>
                </div>
                <div class="column is-12 p-0">
                    <p style="font-weight: bold;">Efektifitas secara menyeluruh : </p>
                </div>
                <div class="column is-12 p-0 is-flex">
                    <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Tidak Efektif" label="Tidak Efektif"
                            v-model="input.CBTidakEfektif" />
                    </VControl>
                    <VControl raw subcontrol>
                        <VCheckbox class="p-0 pl-4" color="primary" square true-value="Efektif" label="Efektif"
                            v-model="input.CBEfektif" />
                    </VControl>
                    <VControl raw subcontrol>
                        <VCheckbox class="p-0 pl-4" color="primary" square true-value="Sangat Efektif"
                            label="Sangat Efektif" v-model="input.CBSangatEfektif" />
                    </VControl>
                    <VControl raw subcontrol>
                        <VCheckbox class="p-0 pl-4" color="primary" square true-value="Sedasi Berlebihan"
                            label="Sedasi Berlebihan" v-model="input.CBSedasiBerlebihan" />
                    </VControl>
                </div>
                <hr class="mt-5">
                <div class="column is-12" style="text-align: center;font-weight: bold;font-size: large;">
                    <h1>MONITORING PASCA SEDASI</h1>
                </div>
                <div class="column is-12">
                    <VCard style="border-radius: 16px;" v-if="chartData2 != null">
                        <Chart type="line" :data="chartData2" :options="chartOptions2" :height="300" class="h-30rem" />
                    </VCard>
                </div>
                <div class="column is-12" style="overflow-y: auto;height: 500px;">
                    <table style="border: 1px solid black;">
                        <tbody v-for="(item, index) in input.details2" :key="index">
                            <tr>
                                <th style="text-align: center;" class="th-pri" colspan="2">
                                    Pukul
                                </th>
                                <th style="text-align: center;" class="th-pri" colspan="2">
                                    Saturasi O<sub>2</sub>
                                </th>
                                <th style="width: 15%;text-align: center;" class="th-pri">
                                    #
                                </th>
                            </tr>
                            <tr>
                                <th style="text-align: center;" class="th-pri" colspan="2">
                                    <VDatePicker v-model="item.pukul" mode="time" is24hr>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:clock" style="width:100%">
                                                <VInput :value="inputValue" placeholder="Pukul..." v-on="inputEvents" />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </th>
                                <th style="text-align: center;" class="th-pri" colspan="2">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.SaturasiO2" />
                                    </VControl>
                                </th>
                                <th>
                                    <div class="column">
                                        <VButtons style="justify-content:space-around;">
                                            <VIconButton type="button" raised circle icon="feather:plus"
                                                @click="addNewItem2()" color="info" v-tooltip.bubble="'Tambah '">
                                            </VIconButton>
                                            <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle
                                                icon="feather:trash" @click="removeItem2(index)" color="danger">
                                            </VIconButton>
                                        </VButtons>
                                    </div>
                                </th>
                            </tr>
                            <tr>
                                <th style="width: 20%;text-align: center;" class="th-pri">
                                    Temperature
                                </th>
                                <th style="width: 20%;text-align: center;" class="th-pri">
                                    Respirasi
                                </th>
                                <th style="width: 20%;text-align: center;" class="th-pri">
                                    Nadi
                                </th>
                                <th style="width: 20%;text-align: center;" class="th-pri">
                                    Tekanan Darah
                                </th>
                            </tr>
                            <tr>
                                <td class="td-pri">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.Temperature" />
                                    </VControl>
                                </td>
                                <td class="td-pri">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.Respirasi" />
                                    </VControl>
                                </td>
                                <td class="td-pri">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.Nadi" />
                                    </VControl>
                                </td>
                                <td class="td-pri">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.TekananDarah" />
                                    </VControl>
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 20%;text-align: center;" class="th-pri" rowspan="2">
                                    Skor Pemulihan :
                                </th>
                                <th style="width: 20%;text-align: center;" class="th-pri">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Aldrete (0-10)"
                                            label="Aldrete (0-10)" v-model="input.CBAldrete" />
                                    </VControl>
                                </th>
                                <th style="width: 20%;text-align: center;" class="th-pri">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Steward (0-6)"
                                            label="Steward (0-6)" v-model="input.CBSteward" />
                                    </VControl>
                                </th>
                                <th style="width: 20%;text-align: center;" class="th-pri">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="PADDS (0-10)"
                                            label="PADDS (0-10)" v-model="input.CBPADDS" />
                                    </VControl>
                                </th>
                            </tr>
                            <tr>
                                <td class="td-pri">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.Aldrete" />
                                    </VControl>
                                </td>
                                <td class="td-pri">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.Steward" />
                                    </VControl>
                                </td>
                                <td class="td-pri">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.PADDS" />
                                    </VControl>
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 50%;text-align: center;" class="th-pri" colspan="2">
                                    Obat/Cairan
                                </th>
                                <th style="width: 50%;text-align: center;" class="th-pri" colspan="2">
                                    Keterangan
                                </th>
                            </tr>
                            <tr>
                                <td class="td-pri" colspan="2">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.ObatCairan" />
                                    </VControl>
                                </td>
                                <td class="td-pri" colspan="2">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.Keterangan" />
                                    </VControl>
                                </td>
                            </tr>
                            <tr>
                                <td class="td-pri" colspan="2">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.ObatCairan2" />
                                    </VControl>
                                </td>
                                <td class="td-pri" colspan="2">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.Keterangan2" />
                                    </VControl>
                                </td>
                            </tr>
                            <tr>
                                <td class="td-pri" colspan="2">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.ObatCairan3" />
                                    </VControl>
                                </td>
                                <td class="td-pri" colspan="2">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.Keterangan3" />
                                    </VControl>
                                </td>
                            </tr>
                            <tr>
                                <td class="td-pri" colspan="2">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.ObatCairan4" />
                                    </VControl>
                                </td>
                                <td class="td-pri" colspan="2">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.Keterangan4" />
                                    </VControl>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="column is-12">
                    <p>N* &nbsp;&nbsp; TDSv &nbsp;&nbsp; TDD^ &nbsp;&nbsp; MAPX &nbsp;&nbsp;
                        RR+ &nbsp;&nbsp; Suhu o </p>
                </div>
                <div class="column is-12">
                    <p>Hal penting yang terjadi selama pemulihan (komplikasi/efek samping, intervensi jalan napas,
                        pemberian antidotum,
                        resusitasi, dll) :</p>
                    <VField>
                        <VTextarea rows="2" v-model="input.TAHalPenting_MPS"></VTextarea>
                    </VField>
                </div>
                <div class="column is-12">
                    <p style="font-weight: bold;">Kriteria Pemulihan :</p>
                </div>
                <div class="column is-12 pt-0">
                    <table>
                        <tr>
                            <td class="td-pri" style="width: 33%;">
                                <p style="font-weight: bold;font-style: italic;">
                                    Modified Aldrete’s Scoring System
                                </p>
                                <div class="column is-12">
                                    <p>1. Aktivitas</p>
                                    <div class="columns pl-4">
                                        <div class="column is-8">
                                            <p>
                                                2 = dapat menggerakan 4 ekstremitas<br>
                                                1 = dapat menggerakan 2 ekstremitas<br>
                                                0 = tidak ada gerakan
                                            </p>
                                        </div>
                                        <div class="column is-4">
                                            <VControl>
                                                <VInput type="number" class="input" v-model="input.TBAktivitas_MASS" />
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12">
                                    <p>2. Pernafasan</p>
                                    <div class="columns pl-4">
                                        <div class="column is-8">
                                            <p>
                                                2 = nafas dalam dan batuk<br>
                                                1 = dyspnea/nafas dangkal<br>
                                                0 = apnea
                                            </p>
                                        </div>
                                        <div class="column is-4">
                                            <VControl>
                                                <VInput type="number" class="input" v-model="input.TBPernafasan_MASS" />
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12">
                                    <p>3. Sirkulasi</p>
                                    <div class="columns pl-4">
                                        <div class="column is-8">
                                            <p>
                                                2 = TD ± 20 mmHg dari preoperatif<br>
                                                1 = TD ± 20-50 mmHg dari preoperatif<br>
                                                0 = TD ± 50 mmHg dari preoperatif
                                            </p>
                                        </div>
                                        <div class="column is-4">
                                            <VControl>
                                                <VInput type="number" class="input" v-model="input.TBSirkulasi_MASS" />
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12">
                                    <p>4. Kesadaran</p>
                                    <div class="columns pl-4">
                                        <div class="column is-8">
                                            <p>
                                                2 = Sadar penuh, mudah dipanggil<br>
                                                1 = Bangun jika dipanggil<br>
                                                0 = Tidak ada respon
                                            </p>
                                        </div>
                                        <div class="column is-4">
                                            <VControl>
                                                <VInput type="number" class="input" v-model="input.TBKesadaran_MASS" />
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12">
                                    <p>5. Warna kulit</p>
                                    <div class="columns pl-4">
                                        <div class="column is-8">
                                            <p>
                                                2 = Kemerahan/normal<br>
                                                1 = Pucat<br>
                                                0 = Sianosis
                                            </p>
                                        </div>
                                        <div class="column is-4">
                                            <VControl>
                                                <VInput type="number" class="input" v-model="input.TBWarnaKulit_MASS" />
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12">
                                    <div class="columns">
                                        <div class="column is-8">
                                            <p>(Total skor ≥ 9 untuk pemulangan) Total skor</p>
                                        </div>
                                        <div class="column is-4">
                                            <VControl>
                                                <VInput type="number" class="input" v-model="input.TBTotal_MASS" />
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="td-pri" style="width: 33%;vertical-align: top;">
                                <p style="font-weight: bold;font-style: italic;">
                                    Steward Score (Pediatri)
                                </p>
                                <div class="column is-12">
                                    <p>1. Kesadaran</p>
                                    <div class="columns pl-4">
                                        <div class="column is-8">
                                            <p>
                                                2 = bangun<br>
                                                1 = respon terhadap stimulus<br>
                                                0 = tidak respon terhadap stimulus
                                            </p>
                                        </div>
                                        <div class="column is-4">
                                            <VControl>
                                                <VInput type="number" class="input" v-model="input.TBKesadaran_SS" />
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12">
                                    <p>2. Jalan Nafas</p>
                                    <div class="columns pl-4">
                                        <div class="column is-8">
                                            <p>
                                                2 = aktif menangis/batuk<br>
                                                1 = dapat menjaga potensi jalan nafas<br>
                                                0 = perlu bantuan nafas
                                            </p>
                                        </div>
                                        <div class="column is-4">
                                            <VControl>
                                                <VInput type="number" class="input" v-model="input.TBJalanNafas_SS" />
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12">
                                    <p>3. Gerakan</p>
                                    <div class="columns pl-4">
                                        <div class="column is-8">
                                            <p>
                                                2 = gerakan bertujuan<br>
                                                1 = gerak tanpa tujuan<br>
                                                0 = tidak bergerak
                                            </p>
                                        </div>
                                        <div class="column is-4">
                                            <VControl>
                                                <VInput type="number" class="input" v-model="input.TBJalanNafas_SS" />
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12">
                                    <div class="columns">
                                        <div class="column is-8">
                                            <p>(Total skor ≥ 5 untuk pemulangan) Total skor</p>
                                        </div>
                                        <div class="column is-4">
                                            <VControl>
                                                <VInput type="number" class="input" v-model="input.TBTotal_SS" />
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="td-pri" style="width: 33%;vertical-align: top;">
                                <p style="font-weight: bold;font-style: italic;">
                                    Bromage score (Regional anestesi)
                                </p>
                                <div class="column is-12">
                                    <table>
                                        <tr>
                                            <th class="th-pri" style="width: 25%;text-align: center;"></th>
                                            <th class="th-pri" style="width: 25%;text-align: center;">Melipat Lutut
                                            </th>
                                            <th class="th-pri" style="width: 25%;text-align: center;">Melipat Jari
                                            </th>
                                            <th class="th-pri" style="width: 25%;text-align: center;">Score</th>
                                        </tr>
                                        <tr>
                                            <td class="td-pri">Blok Tidak</td>
                                            <td class="td-pri" style="text-align:center">++</td>
                                            <td class="td-pri" style="text-align:center">++</td>
                                            <td class="td-pri" style="text-align:center">0</td>
                                        </tr>
                                        <tr>
                                            <td class="td-pri">Blok Partial</td>
                                            <td class="td-pri" style="text-align:center">+</td>
                                            <td class="td-pri" style="text-align:center">++</td>
                                            <td class="td-pri" style="text-align:center">1</td>
                                        </tr>
                                        <tr>
                                            <td class="td-pri">Blok Hampir</td>
                                            <td class="td-pri" style="text-align:center">-</td>
                                            <td class="td-pri" style="text-align:center">++</td>
                                            <td class="td-pri" style="text-align:center">2</td>
                                        </tr>
                                        <tr>
                                            <td class="td-pri">Blok Lengkap</td>
                                            <td class="td-pri" style="text-align:center">-</td>
                                            <td class="td-pri" style="text-align:center">-</td>
                                            <td class="td-pri" style="text-align:center">3</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="column is-12">
                                    <div class="columns">
                                        <div class="column is-8">
                                            <p>(Total skor ≥ 0 untuk pemulangan) Total skor</p>
                                        </div>
                                        <div class="column is-4">
                                            <VControl>
                                                <VInput type="number" class="input" v-model="input.TBTotal_BS" />
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="column is-12">
                    <p style="font-weight: bold;">Keadaan Sebelum Pemulangan :</p>
                </div>
                <div class="column is-12 columns is-multiline pt-0">
                    <div class="column is-3">
                        <label>Kesadaran :</label>
                        <VControl>
                            <VInput type="text" class="input" v-model="input.TBKesadaran_KSP" />
                        </VControl>
                    </div>
                    <div class="column is-3">
                        <label>TD :</label>
                        <VField addons>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBSTD_KSP" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>mmHg</VButton>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <label>Nadi :</label>
                        <VField addons>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBSNadi_KSP" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>x/menit</VButton>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <label>Support :</label>
                        <VControl>
                            <VInput type="text" class="input" v-model="input.TBSupport_KSP" />
                        </VControl>
                    </div>
                    <div class="column is-3 pt-0">
                        <label>Resp :</label>
                        <VField addons>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBSResp_KSP" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>x/menit</VButton>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3 pt-0">
                        <label>O<sub>2</sub> :</label>
                        <VField addons>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBSO2_KSP" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>L/menit</VButton>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3 pt-0">
                        <label>SpO<sub>2</sub> :</label>
                        <VField addons>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBSSpO2_KSP" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>%</VButton>
                            </VControl>
                        </VField>
                    </div>
                    <div class="columns is-multiline column is-12">
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Udara Bebas"
                                    label="Udara Bebas" v-model="input.CBUdaraBebas_KSP" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Kanul Nasal"
                                    label="Kanul Nasal" v-model="input.CBKanulNasal_KSP" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Simple Mask"
                                    label="Simple Mask" v-model="input.CBSimpleMask_KSP" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Rebreathing Mask"
                                    label="Rebreathing Mask" v-model="input.CBRebreathingMask_KSP" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Non Rebreathing Mask"
                                    label="Non Rebreathing Mask" v-model="input.CBNonRebreathingMask_KSP" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Terintubasi"
                                    label="Terintubasi" v-model="input.CBTerintubasi_KSP" />
                            </VControl>
                        </div>
                    </div>
                </div>
                <div class="column is-12 pt-0">
                    <p style="font-weight: bold;">Proses Pemulangan :</p>
                </div>
                <div class="column is-12 pt-0 columns is-multiline">
                    <div class="column is-12 pb-0">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Ruang perawatan inap"
                                label="Ruang perawatan inap" v-model="input.CBRuangPerawatanInap_PP" />
                        </VControl>
                    </div>
                    <div class="columns column is-12">
                        <div class="column is-2">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="ICU" label="ICU"
                                    v-model="input.CBICU_RPI" />
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="PICU" label="PICU"
                                    v-model="input.CBPICU_RPI" />
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="HCU" label="HCU"
                                    v-model="input.CBHCU_RPI" />
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Bangsal" label="Bangsal"
                                    v-model="input.CBBangsal_RPI" />
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBRPI" />
                            </VControl>
                        </div>
                    </div>
                    <div class="column is-12 columns pt-0">
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Pulang ke rumah"
                                    label="Pulang ke rumah" v-model="input.CBPulangKeRumah_PP" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Lain-lain" label="Lain-lain"
                                    v-model="input.CBLainLain_PP" />
                            </VControl>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBLainLain_PP" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <label>Pukul : </label>
                            <VDatePicker v-model="input.TPukul_PP" mode="time" is24hr>
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:clock" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                    </div>
                    <div class="column is-12 pt-0">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Instruksi post prosedur sedasi"
                                label="Instruksi post prosedur sedasi"
                                v-model="input.CBInstruksiPostProsedurSedasi_PP" />
                        </VControl>
                    </div>
                    <div class="column is-12 pt-0">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.TB_1_IPPS" />
                        </VControl>
                    </div>
                    <div class="column is-12">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.TB_2_IPPS" />
                        </VControl>
                    </div>
                    <div class="column is-12">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.TB_3_IPPS" />
                        </VControl>
                    </div>
                    <div class="column is-12 columns">
                        <div class="column is-6 p-5" style="text-align:center">
                            <p style="font-weight:bold">Dokter</p>
                            <TandaTangan :elemenID="'TDDokter'" :width="'150'" :height="'150'" class="dek" />
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.CBDokter" :suggestions="d_Dokter" :optionLabel="'label'"
                                    :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                    :field="'label'" class="mt-2" @complete="fetchDokter($event)" />
                            </VControl>
                        </div>
                        <div class="column is-6 p-5" style="text-align:center">
                            <p style="font-weight:bold">Perawat/Penata Anestesi</p>
                            <TandaTangan :elemenID="'TDPerawat'" :width="'150'" :height="'150'" class="dek" />
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.CBPerawat" :suggestions="d_Perawat" :optionLabel="'label'"
                                    @complete="fetchPerawat($event)" :dropdown="true" :minLength="3" :appendTo="'body'"
                                    :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                            </VControl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- form baru -->
        </div>
    </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import { useConfirm } from "primevue/useconfirm"
import * as H from '/@src/utils/appHelper'
import Chart from 'primevue/chart';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
// import Calendar from 'primevue/calendar'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const COLLECTION: any = ref('LembarPencatatanSedasi') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const input: any = ref({
    DTTanggal: new Date(),
    TJam: new Date(),
    details: [{
        no: 1,
        pukul: new Date()
    }],
    details2: [{
        no: 1,
        pukul: new Date()
    }]
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

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
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
    useApi().post(
        `/emr/simpan-emr`, json).then((response: any) => {
            isLoading.value = false
            loadRiwayat()
            NOREC_EMRPASIEN.value = response.norec_emr
            input.value.id = response.id
        }).catch((e: any) => {
            isLoading.value = false
        })
}

//? Array
const d_gcse: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }])
const d_gcsv: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }])
const d_gcsm: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }, { value: 6, label: '6' }])
const isLoading = ref(false)
const dataChart = ref([]);
const chartData = ref();
const chartOptions = ref();
const dataChart2 = ref([]);
const chartData2 = ref();
const chartOptions2 = ref();
const d_Dokter: any = ref([])
const d_Perawat: any = ref([])

//? Function
const loadRiwayat = async () => {
    isLoading.value = true
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    if (response.length) {
        input.value = response[0] //set ke inputan 
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }

        dataChart.value = response[0].details
        dataChart2.value = response[0].details2
        chartData.value = setChartData(dataChart.value);
        chartData2.value = setChartData(dataChart2.value);
        chartOptions.value = setChartOptions();
        chartOptions2.value = setChartOptions();
        isLoading.value = false
        H.alert('info', 'Data berhasil dimuat')
    } else {
        isLoading.value = false
    }
}
const fetchPerawat = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => {
        d_Perawat.value = response
    })
}
const fetchDokter = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`).then((response) => {
        d_Dokter.value = response
    })
}

//? Add New Rows Function
const addNewItem = () => {
    let newItem: any = {}
    newItem = {
        no: input.value.details[input.value.details.length - 1].no + 1,
        pukul: new Date(),
        Obat: input.value.details[input.value.details.length - 1].Obat,
        Obat2: input.value.details[input.value.details.length - 1].Obat2,
        Obat3: input.value.details[input.value.details.length - 1].Obat3,
        Obat4: input.value.details[input.value.details.length - 1].Obat4,
        Cairan: input.value.details[input.value.details.length - 1].Cairan,
        Cairan2: input.value.details[input.value.details.length - 1].Cairan2,
        Cairan3: input.value.details[input.value.details.length - 1].Cairan3,
        Cairan4: input.value.details[input.value.details.length - 1].Cairan4
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
        pukul: new Date(),
        ObatCairan: input.value.details2[input.value.details2.length - 1].ObatCairan,
        ObatCairan2: input.value.details2[input.value.details2.length - 1].ObatCairan2,
        ObatCairan3: input.value.details2[input.value.details2.length - 1].ObatCairan3,
        ObatCairan4: input.value.details2[input.value.details2.length - 1].ObatCairan4,
    }
    input.value.details2.push(newItem);
}
const removeItem2 = (index: any) => {
    input.value.details2.splice(index, 1)
}

// Chart
const setChartData = (data: any) => {
    const documentStyle = getComputedStyle(document.documentElement);
    let labels = []
    let seriesNadi = []
    let seriesTemperature = []
    let seriesPernafasan = []
    let seriesTekananDarah = []
    for (var i = data.length - 1; i >= 0; i--) {
        const element = data[i]
        labels.push(element.pukul ?? '???');
        seriesNadi.push((element.Nadi ? parseFloat(element.Nadi) : 0))
        seriesTemperature.push((element.Temperature ? parseFloat(element.Temperature) : 0))
        seriesPernafasan.push((element.Respirasi ? parseFloat(element.Respirasi) : 0))
        seriesTekananDarah.push((element.TekananDarah ? parseFloat(element.TekananDarah) : 0))
    }

    return {
        labels: labels,
        datasets: [
            {
                label: 'T',
                data: seriesTemperature,
                fill: false,
                tension: 0.4,
                borderColor: documentStyle.getPropertyValue('--teal-500')
            },
            {
                label: 'RR',
                data: seriesPernafasan,
                fill: true,
                borderColor: documentStyle.getPropertyValue('--orange-500'),
                tension: 0.4,
                borderDash: [5, 5],
                backgroundColor: 'rgba(255,167,38,0.2)'
            },
            {
                label: 'N',
                data: seriesNadi,
                fill: false,
                tension: 0.4,
                borderColor: documentStyle.getPropertyValue('--blue-500')
            },
            {
                label: 'TD',
                data: seriesTekananDarah,
                fill: false,
                tension: 0.4,
                borderColor: documentStyle.getPropertyValue('--red-500')
            }
        ]
    };
};
const setChartOptions = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue('--text-color');
    const textColorSecondary = documentStyle.getPropertyValue('--text-color-secondary');
    const surfaceBorder = documentStyle.getPropertyValue('--surface-border');

    return {
        maintainAspectRatio: false,
        aspectRatio: 0.6,
        plugins: {
            legend: {
                labels: {
                    color: textColor
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder
                }
            },
            y: {
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder
                }
            }
        }
    };
}
const setChartData2 = (data: any) => {
    const documentStyle = getComputedStyle(document.documentElement);
    let labels = []
    let seriesNadi = []
    let seriesTemperature = []
    let seriesPernafasan = []
    let seriesTekananDarah = []
    for (var i = data.length - 1; i >= 0; i--) {
        const element = data[i]
        labels.push(element.pukul ?? '???');
        seriesNadi.push((element.Nadi ? parseFloat(element.Nadi) : 0))
        seriesTemperature.push((element.Temperature ? parseFloat(element.Temperature) : 0))
        seriesPernafasan.push((element.Respirasi ? parseFloat(element.Respirasi) : 0))
        seriesTekananDarah.push((element.TekananDarah ? parseFloat(element.TekananDarah) : 0))
    }

    return {
        labels: labels,
        datasets: [
            {
                label: 'T',
                data: seriesTemperature,
                fill: false,
                tension: 0.4,
                borderColor: documentStyle.getPropertyValue('--teal-500')
            },
            {
                label: 'RR',
                data: seriesPernafasan,
                fill: true,
                borderColor: documentStyle.getPropertyValue('--orange-500'),
                tension: 0.4,
                borderDash: [5, 5],
                backgroundColor: 'rgba(255,167,38,0.2)'
            },
            {
                label: 'N',
                data: seriesNadi,
                fill: false,
                tension: 0.4,
                borderColor: documentStyle.getPropertyValue('--blue-500')
            },
            {
                label: 'TD',
                data: seriesTekananDarah,
                fill: false,
                tension: 0.4,
                borderColor: documentStyle.getPropertyValue('--red-500')
            }
        ]
    };
};
const setChartOptions2 = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue('--text-color');
    const textColorSecondary = documentStyle.getPropertyValue('--text-color-secondary');
    const surfaceBorder = documentStyle.getPropertyValue('--surface-border');

    return {
        maintainAspectRatio: false,
        aspectRatio: 0.6,
        plugins: {
            legend: {
                labels: {
                    color: textColor
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder
                }
            },
            y: {
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder
                }
            }
        }
    };
}
// chartOptions.value = setChartOptions();
// chartData.value = setChartData();
// chartOptions2.value = setChartOptions2();
// chartData2.value = setChartData2();

//? Function
loadRiwayat()
</script>
