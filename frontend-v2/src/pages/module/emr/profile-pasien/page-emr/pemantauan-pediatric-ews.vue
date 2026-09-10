<style lang="scss">
.table {

    width: 100% !important;
    border: 1px solid black !important;
}

.table td {
    height: 4rem !important;
    text-align: center !important;
}

// .table th {
//     text-align: center !important;
//     border: 1px solid black !important;
//     font-weight: bold !important
// }

.center {
    text-align: center !important;
    vertical-align: middle !important;
}

h1 {
    font-weight: bold !important
}

.rotated-table {
    transform: rotate(90deg) translate(0, 50%); /* Move down by 50% */
    transform-origin: middle right;
    display: inline-block;
}

</style>
<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Pemantauan Pediatric Early Warning Score (PEWS)</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate"
                                @kembaliKeun="kembaliKeun" :isHideCetak="true" isHideST></ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-2">
                            <div class="column is-12">
                            Kategori  Usia
                            <Multiselect v-model="input.usia" :attrs="{ label }" placeholder="--Pilih--"
                            label="label" :options="d_usia" :searchable="true" track-by="label" mode="single"
                            autocomplete="off" >
                            </Multiselect>
                            </div>
                        </div>
                        <div class="column is-2">
                            <div class="column is-12">
                            Tanggal Lahir Pasien
                                <VDatePicker v-model="input.tglLahir" mode="date" trim-weeks :max-date="new Date()">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </template>
                                </VDatePicker>
                                <div v-if="patientAge !== null">
                                    <strong>Usia:</strong> {{ patientAge.years }} tahun {{ patientAge.months }} bulan
                                  </div>
                            </div>
                        </div>
                        <div class="column is-6">
                        </div>
                    </div>
                </div>

                <div class="column columns is-multiline">
                    <div class="column is-4 pl-0 pb-0" style="text-align: center;">
                        <VButton type="button" rounded color="dark" class="mb-3"> Tambah Kolom
                        </VButton>
                        <VButtons style="justify-content:space-around">
                            <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()"
                                color="info" v-tooltip.bubble="'Tambah '">
                            </VIconButton>
                            <VIconButton class="mt-1" v-if="input.details.length > 1" type="button" raised circle
                                icon="feather:trash" @click="removeItem(index)" color="danger">
                            </VIconButton>
                        </VButtons>
                    </div>
                    <div class="column is-8 pt-0 pb-0"></div>
                    <div class="column is-3 pr-0">
                        <table style="width: 100%;border-collapse: collapse;border:1px solid black;">
                            <tr>
                                <th style="height: 4rem;border-bottom: 1px solid black;" class="center" colspan="3">
                                    Tanggal & Jam</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;width: 20%;border-right:1px solid black" class="center"
                                    rowspan="9"><b>PERNAPASAN</b> (<i>Respiration</i>) Frekuensi Pernapasan</th>
                                <th style="height: 4rem;width: 10%" class="center">80</th>

                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">70</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">60</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">50</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">40</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">30</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">20</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">10</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center"> &lt;10</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;border-right:1px solid black;border-top:1px solid black;"
                                    class="center" colspan="2">Oksigen yang digunakan
                                </th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;border-right:1px solid black;border-top:1px solid black;"
                                    class="center" colspan="2">Usaha Nafas </th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;width:10rem;border-right:1px solid black;border-top:1px solid black;background-color:lightgray !important"
                                    class="center" colspan="2">(RESPIRATION / R)</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;border-right:1px solid black;border-top:1px solid black;"
                                    class="center" rowspan="16"><b>SIRKULASI</b> (<i>Circulation</i>) Nadi Tekanan Darah
                                </th>
                                <th style="height: 4rem;border-top: 1px solid black;" class="center">&gt;180</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">170</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">160</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">150</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">140</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">130</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">120</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">110</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">100</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">90</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">80</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">70</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">60</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">55</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">50</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">&lt;45</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;border-right:1px solid black;border-top:1px solid black;"
                                    class="center" colspan="2">Capilary Refill Time(CRT)</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;border-right:1px solid black;border-top:1px solid black;background-color:lightgray !important"
                                    class="center" colspan="2">PEW SCORE SIRKULASI (CIRCULATION / C)</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;border-right:1px solid black;border-top:1px solid black;"
                                    class="center" colspan="2">Suhu (<i>Temperature</i>)</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;border-right:1px solid black;border-top:1px solid black;"
                                    class="center" rowspan="4">Behavior</th>
                                <th style="height: 4rem;border-top: 1px solid black;" class="center">Alert</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">Verbal</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">Pain</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">Unrespon</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;border-right:1px solid black;border-top:1px solid black;background-color:lightgray !important"
                                    class="center" colspan="2">PEW SCORE BEHAVIOUR (B)</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;border-top:1px solid black" class="center" colspan="3">
                                    TOTAL PEW SCORE (R+C+B)
                                </th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;border-right:1px solid black;border-top:1px solid black;"
                                    class="center" colspan="2">Skala Nyeri (<i>Pain Scale</i>)</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;border-right:1px solid black;border-top:1px solid black;"
                                    class="center" colspan="2">BAB (<i>defecation</i>)</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;border-right:1px solid black;border-top:1px solid black;"
                                    class="center" colspan="2">BAK (<i>Urination</i>)</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;border-top:1px solid black" class="center" colspan="3">
                                    Paraf & Nama
                                </th>
                            </tr>
                        </table>
                    </div>
                    <div class="column is-5 pl-0" style="overflow: auto;">
                        <table class="table" style="width: auto !important;">
                            <tr>
                                <td v-for="(data, index) in input.details" :key="index" class="p-0">
                                    <table class="table" style="width: 12rem !important;">
                                        <tr>
                                            <td>
                                                <VDatePicker v-model="data['DT_PEWS']" mode="datetime" is24hr>
                                                    <template #default="{ inputValue, inputEvents }">
                                                        <VControl icon="feather:calendar" fullwidth>
                                                            <VInput :value="inputValue" v-on="inputEvents" />
                                                        </VControl>
                                                    </template>
                                                </VDatePicker>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <VControl raw subcontrol>
                                                    <VCheckbox
                                                        class="p-0"
                                                        color="primary"
                                                        square
                                                        true-value="3"
                                                        v-model="data['CB_Respirasi']"
                                                        @change="updateSkorPernapasan(index)"
                                                    />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="2"
                                                        label="" v-model="data['CB_Respirasi']"
                                                        @change="updateSkorPernapasan(index)"/>
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="02"
                                                        label="" v-model="data['CB_Respirasi']"
                                                        @change="updateSkorPernapasan(index)"/>
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="00"
                                                        label="" v-model="data['CB_Respirasi']"
                                                        @change="updateSkorPernapasan(index)"/>
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="000"
                                                        label="" v-model="data['CB_Respirasi']"
                                                        @change="updateSkorPernapasan(index)"/>
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="1"
                                                        label="" v-model="data['CB_Respirasi']"
                                                        @change="updateSkorPernapasan(index)"/>
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="03"
                                                        label="" v-model="data['CB_Respirasi']"
                                                        @change="updateSkorPernapasan(index)"/>
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="0"
                                                        label="" v-model="data['CB_Respirasi']"
                                                        @change="updateSkorPernapasan(index)"/>
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="0000000"
                                                        label="" v-model="data['CB_Respirasi']"
                                                        @change="updateSkorPernapasan(index)"/>
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <Multiselect v-model="data['oxygen']" :attrs="{ label }" placeholder="--Pilih--"
                                                    label="label" :options="d_oksigen" :searchable="true" track-by="label" mode="single"
                                                    autocomplete="off" @update:modelValue="updateSkorOxygen(index)">
                                                </Multiselect>
                                                <!-- <VControl>
                                                    <VInput type="text" class="input" v-model="data['oxygen']" />
                                                </VControl> -->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <Multiselect v-model="data['breath']" :attrs="{ label }" placeholder="--Pilih--"
                                                    label="label" :options="d_usahaNafas" :searchable="true" track-by="label" mode="single"
                                                    autocomplete="off" >
                                                </Multiselect>
                                                <!-- <VControl>
                                                    <VInput type="text" class="input" v-model="data['breath']" />
                                                </VControl> -->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: lightgrey !important;">
                                                <VControl>
                                                    <VInput type="text" class="input"
                                                        v-model="data['skorpernapasan']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="1"
                                                        label="" v-model="data['CB_Nadi']"
                                                        @change="updateSkorNadi(index)" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="001"
                                                        label="" v-model="data['CB_Nadi']"
                                                        @change="updateSkorNadi(index)" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="002"
                                                        label="" v-model="data['CB_Nadi']"
                                                        @change="updateSkorNadi(index)" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="01"
                                                        label="" v-model="data['CB_Nadi']"
                                                        @change="updateSkorNadi(index)" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="02"
                                                        label="" v-model="data['CB_Nadi']"
                                                        @change="updateSkorNadi(index)" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="03"
                                                        label="" v-model="data['CB_Nadi']"
                                                        @change="updateSkorNadi(index)" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="04"
                                                        label="" v-model="data['CB_Nadi']"
                                                        @change="updateSkorNadi(index)" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="2"
                                                        label="" v-model="data['CB_Nadi']"
                                                        @change="updateSkorNadi(index)" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="003"
                                                        label="" v-model="data['CB_Nadi']"
                                                        @change="updateSkorNadi(index)" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="3"
                                                        label="" v-model="data['CB_Nadi']"
                                                        @change="updateSkorNadi(index)" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="0"
                                                        label="" v-model="data['CB_Nadi']"
                                                        @change="updateSkorNadi(index)" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="00"
                                                        label="" v-model="data['CB_Nadi']"
                                                        @change="updateSkorNadi(index)" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="000"
                                                        label="" v-model="data['CB_Nadi']"
                                                        @change="updateSkorNadi(index)" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="0000"
                                                        label="" v-model="data['CB_Nadi']"
                                                        @change="updateSkorNadi(index)" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="00000"
                                                        label="" v-model="data['CB_Nadi']"
                                                        @change="updateSkorNadi(index)" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="000000"
                                                        label="" v-model="data['CB_Nadi']"
                                                        @change="updateSkorNadi(index)" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <Multiselect v-model="data['crt']" :attrs="{ label }" placeholder="--Pilih--"
                                                    label="label" :options="d_capillaryRefillTime" :searchable="true" track-by="label" mode="single"
                                                    autocomplete="off" >
                                                </Multiselect>
                                                <!-- <VControl>
                                                    <VInput type="text" class="input" v-model="data['crt']" />
                                                </VControl> -->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: lightgrey !important;">
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="data['skorsirkulasi']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="data['temperature']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="003"
                                                        label="" v-model="data['CB_Behave']"
                                                        @change="updateSkorBehaviour(index)" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="0003"
                                                        label="" v-model="data['CB_Behave']"
                                                        @change="updateSkorBehaviour(index)" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="00003"
                                                        label="" v-model="data['CB_Behave']"
                                                        @change="updateSkorBehaviour(index)" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="000003"
                                                        label="" v-model="data['CB_Behave']"
                                                        @change="updateSkorBehaviour(index)" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: lightgrey !important;">
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="data['skorbehaviour']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: lightgrey !important;">
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="data['TOTAL_RCB']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="data['skalanyeri']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="data['defecation']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="data['Urination']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <VControl class="prime-auto">
                                                    <AutoComplete v-model="data['DD_Perawat']" :suggestions="d_Pegawai"
                                                        @complete="fetchPegawai($event)" :optionLabel="'label'"
                                                        :dropdown="true" :minLength="3" :appendTo="'body'"
                                                        :loadingIcon="'pi pi-spinner'" :field="'label'" />
                                                </VControl>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="column is-4 pl-0" style="overflow-x: auto;">
                        <div class="columns is-multiline">
                            <div class="column is-12" style="overflow: auto !important; height: 825px; width: 830px; padding-top: 10;">
                                <table class="tg" border="1" style="text-align: center;width: 100% !important">
                                    <thead>
                                        <tr>
                                            <th style="background-color: lightblue !important;" rowspan="2">Parameter</th>
                                            <th style="background-color: lightblue !important;" rowspan="2">Item yang dipantau</th>
                                            <th style="background-color: lightblue !important;" rowspan="2">Usia</th>
                                            <th style="background-color: lightblue !important;" colspan="4">Skor</th>
                                        </tr>
                                        <tr>
                                            <th>0</th>
                                            <th style="background-color: green !important;">1</th>
                                            <th style="background-color: yellow !important;">2</th>
                                            <th style="background-color: red !important;">3</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="background-color: lightblue !important;" rowspan="12"><b>Respiration (R)</b>
                                            </td>
                                            <td style="background-color: lightblue !important;" rowspan="10"><b>Pernafasan</b></td>
                                            <td style="background-color: lightblue !important;" rowspan="2"><b>0-&lt;3 bln</b></td>
                                            <td style="background-color: white !important;" rowspan="2">30-50</td>
                                            <td style="background-color: lightgreen !important;">25-29</td>
                                            <td style="background-color: yellow !important;" rowspan="2">66-70</td>
                                            <td style="background-color: red !important;">&lt;25</td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: lightgreen !important;">51-65</td>
                                            <td style="background-color: red !important;">&gt;70</td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: lightblue !important;" rowspan="2"><b>3-&lt;12 bln</b></td>
                                            <td style="background-color: white !important;" rowspan="2">20-44</td>
                                            <td style="background-color: lightgreen !important;">15-19</td>
                                            <td style="background-color: yellow !important;" rowspan="2">50-55</td>
                                            <td style="background-color: red !important;">&lt;15</td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: lightgreen !important;">45-49</td>
                                            <td style="background-color: red !important;">&gt;55</td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: lightblue !important;" rowspan="2"><b>1-&lt;5 th</b></td>
                                            <td style="background-color: white !important;" rowspan="2">20-34</td>
                                            <td style="background-color: lightgreen !important;">15-19</td>
                                            <td style="background-color: yellow !important;" rowspan="2">40-50</td>
                                            <td style="background-color: red !important;">&lt;15</td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: lightgreen !important;">35-39</td>
                                            <td style="background-color: red !important;">&gt;50</td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: lightblue !important;" rowspan="2"><b>5-12 th</b></td>
                                            <td style="background-color: white !important;" rowspan="2">20-29</td>
                                            <td style="background-color: lightgreen !important;">16-19</td>
                                            <td style="background-color: yellow !important;" rowspan="2">35-40</td>
                                            <td style="background-color: red !important;">&lt;16</td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: lightgreen !important;">30-34</td>
                                            <td style="background-color: red !important;">&gt;40</td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: lightblue !important;" rowspan="2"><b>12-18 th</b></td>
                                            <td style="background-color: white !important;" rowspan="2">16-18</td>
                                            <td style="background-color: lightgreen !important;">11-15</td>
                                            <td style="background-color: yellow !important;" rowspan="2">30-35</td>
                                            <td style="background-color: red !important;">&lt;11</td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: lightgreen !important;">26-29</td>
                                            <td style="background-color: red !important;">&gt;35</td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: lightblue !important;"><b>Oksigen Terapi</b></td>
                                            <td style="background-color: lightblue !important;"><b>0 bln-18 th</b></td>
                                            <td style="background-color: white !important;">Tidak Menggunakan Oksigen</td>
                                            <td style="background-color: lightgreen !important;">FiO₂ ≤ 21% atau 1 L/menit</td>
                                            <td style="background-color: yellow !important;">FiO₂ ≥ 40% atau ≥ 40 L/menit</td>
                                            <td style="background-color: red !important;">FiO₂ ≥ 50% atau ≥ 6 L/menit</td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: lightblue !important;"><b>Usaha Nafas</b></td>
                                            <td style="background-color: lightblue !important;"><b>0 bln-18 th</b></td>
                                            <td style="background-color: white !important;">Tidak ada retraksi</td>
                                            <td style="background-color: lightgreen !important;">Ada sedikit retraksi</td>
                                            <td style="background-color: yellow !important;">Retraksi agak dalam, tracheal tug</td>
                                            <td style="background-color: red !important;">Retraksi dalam, tracheal tug, grunting
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div>
                            <div class="column is-12" style="overflow: auto !important; height: 825px; width: 872px;">
                                <table class="tg" border="1" style="text-align: center;width: 100% !important">
                                        <thead>
                                            <tr>
                                                <th style="background-color: lightblue !important;" rowspan="2">Parameter</th>
                                                <th style="background-color: lightblue !important;" rowspan="2">Item yang dipantau</th>
                                                <th style="background-color: lightblue !important;" rowspan="2">Usia</th>
                                                <th style="background-color: lightblue !important;" colspan="4">Skor</th>
                                            </tr>
                                            <tr>
                                                <th>0</th>
                                                <th style="background-color: green !important;">1</th>
                                                <th style="background-color: yellow !important;">2</th>
                                                <th style="background-color: red !important;">3</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="background-color: lightblue !important;" rowspan="11"><b>Kardio Vaskular
                                                        (C)</b></td>
                                                <td style="background-color: lightblue !important;" rowspan="10"><b>Nadi (x/menit)</b>
                                                </td>
                                                <td style="background-color: lightblue !important;vertical-align: middle;" rowspan="2">
                                                    <b>0-&lt;3
                                                        bln</b>
                                                </td>
                                                <td style="background-color: white !important;vertical-align: middle;" rowspan="2">
                                                    100-149</td>
                                                <td style="background-color: lightgreen !important;">90-99</td>
                                                <td style="background-color: yellow !important;">80-89</td>
                                                <td style="background-color: red !important;">&lt;80</td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: lightgreen !important;">150-159</td>
                                                <td style="background-color: yellow !important;">160-169</td>
                                                <td style="background-color: red !important;">&gt;169</td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: lightblue !important;vertical-align: middle;" rowspan="2">
                                                    <b>3-&lt;12
                                                        bln</b>
                                                </td>
                                                <td style="background-color: white !important;vertical-align: middle;" rowspan="2">
                                                    90-159</td>
                                                <td style="background-color: lightgreen !important;">80-89</td>
                                                <td style="background-color: yellow !important;">70-79</td>
                                                <td style="background-color: red !important;">&lt;70</td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: lightgreen !important;">160-169</td>
                                                <td style="background-color: yellow !important;">170-180</td>
                                                <td style="background-color: red !important;">&gt;180</td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: lightblue !important;vertical-align: middle;" rowspan="2">
                                                    <b>1-&lt;5 th</b>
                                                </td>
                                                <td style="background-color: white !important;vertical-align: middle;" rowspan="2">
                                                    90-139</td>
                                                <td style="background-color: lightgreen !important;">80-89</td>
                                                <td style="background-color: yellow !important;vertical-align: middle;" rowspan="2">
                                                    150-160</td>
                                                <td style="background-color: red !important;">&lt;80</td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: lightgreen !important;">140-149</td>
                                                <td style="background-color: red !important;">&gt;160</td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: lightblue !important;vertical-align: middle;" rowspan="2">
                                                    <b>5-12 th</b>
                                                </td>
                                                <td style="background-color: white !important;vertical-align: middle;" rowspan="2">
                                                    60-109</td>
                                                <td style="background-color: lightgreen !important;vertical-align: middle;" rowspan="2">
                                                    110-129</td>
                                                <td style="background-color: yellow !important;">50-59</td>
                                                <td style="background-color: red !important;">&lt;50</td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: yellow !important;">130-139</td>
                                                <td style="background-color: red !important;">&gt;139</td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: lightblue !important;vertical-align: middle;" rowspan="2">
                                                    <b>12-18 th</b>
                                                </td>
                                                <td style="background-color: white !important;vertical-align: middle;" rowspan="2">55-99
                                                </td>
                                                <td style="background-color: lightgreen !important;vertical-align: middle;" rowspan="2">
                                                    101-109</td>
                                                <td style="background-color: yellow !important;">45-55</td>
                                                <td style="background-color: red !important;">&lt;45</td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: yellow !important;">110-119</td>
                                                <td style="background-color: red !important;">&gt;119</td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: lightblue !important;"><b>Capillary Refill Time (CRT)</b>
                                                </td>
                                                <td style="background-color: lightblue !important;"><b>0 bln-18 th</b></td>
                                                <td style="background-color: white !important;">PINK (CRT 1 - 2 detik)</td>
                                                <td style="background-color: lightgreen !important;">Pucat (CRT 3 detik)</td>
                                                <td style="background-color: yellow !important;">Abu - abu (CRT 4 detik)</td>
                                                <td style="background-color: red !important;">Abu - abu (CRT≥5 detik)</td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: lightblue !important;"><b>Kesadaran/Behavior (B)</b></td>
                                                <td style="background-color: lightblue !important;"><b>Tingkat Kesadaran</b></td>
                                                <td style="background-color: lightblue !important;"><b>0 bln-18 th</b></td>
                                                <td style="background-color: white !important;">Sadar baik (Alert)</td>
                                                <td style="background-color: lightgreen !important;">Lemah, banyak tidur, berespon
                                                    dengan suara (Verbal)
                                                </td>
                                                <td style="background-color: yellow !important;">Gelisah, berespon dengan nyeri (Pain)
                                                </td>
                                                <td style="background-color: red !important;">Letargi, tidak berespon (Unrespon)</td>
                                            </tr>
                                        </tbody>
                                </table>
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
import * as H from '/@src/utils/appHelper'
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount, watchEffect } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';

useHead({ title: 'Pemantauan Pediatric Early Warning Score (PEWS) - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const COLLECTION: any = ref('PemantauanPEWS') //table mongodb
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
        DT_PEWS: new Date(),
        DD_Perawat: { label: user.namaLengkap, value: user.id }
    }],
})

// Define the scoring thresholds based on usia
const respirationScoring = {
  "0-<3 bln": [
    { min: 30, max: 50, score: 0 },
    { min: 25, max: 29, score: 1 },
    { min: 51, max: 65, score: 1 },
    { min: 66, max: 70, score: 2 },
    { min: -Infinity, max: 24, score: 3 },
    { min: 71, max: Infinity, score: 3 }
  ],
  "3-<12 bln": [
    { min: 20, max: 44, score: 0 },
    { min: 15, max: 19, score: 1 },
    { min: 45, max: 49, score: 1 },
    { min: 50, max: 55, score: 2 },
    { min: -Infinity, max: 14, score: 3 },
    { min: 56, max: Infinity, score: 3 }
  ],
  "1-<5 th": [
    { min: 20, max: 34, score: 0 },
    { min: 15, max: 19, score: 1 },
    { min: 35, max: 39, score: 1 },
    { min: 40, max: 50, score: 2 },
    { min: -Infinity, max: 14, score: 3 },
    { min: 51, max: Infinity, score: 3 }
  ],
  "5-12 th": [
    { min: 20, max: 29, score: 0 },
    { min: 16, max: 19, score: 1 },
    { min: 30, max: 34, score: 1 },
    { min: 35, max: 40, score: 2 },
    { min: -Infinity, max: 15, score: 3 },
    { min: 41, max: Infinity, score: 3 }
  ],
  "12-18 th": [
    { min: 16, max: 18, score: 0 },
    { min: 11, max: 15, score: 1 },
    { min: 26, max: 29, score: 1 },
    { min: 30, max: 35, score: 2 },
    { min: -Infinity, max: 10, score: 3 },
    { min: 36, max: Infinity, score: 3 }
  ]
};
const circulationScoring = {
  "0-<3 bln": [
    { min: 100, max: 149, score: 0 },
    { min: 90, max: 99, score: 1 },
    { min: 150, max: 159, score: 1 },
    { min: 80, max: 89, score: 2 },
    { min: 160, max: 169, score: 2 },
    { min: -Infinity, max: 79, score: 3 },
    { min: 170, max: Infinity, score: 3 }
  ],
  "3-<12 bln": [
    { min: 90, max: 159, score: 0 },
    { min: 80, max: 89, score: 1 },
    { min: 160, max: 169, score: 1 },
    { min: 70, max: 79, score: 2 },
    { min: 170, max: 180, score: 2 },
    { min: -Infinity, max: 69, score: 3 },
    { min: 181, max: Infinity, score: 3 }
  ],
  "1-<5 th": [
    { min: 90, max: 139, score: 0 },
    { min: 80, max: 89, score: 1 },
    { min: 140, max: 149, score: 1 },
    { min: 150, max: 160, score: 2 },
    { min: -Infinity, max: 79, score: 3 },
    { min: 161, max: Infinity, score: 3 }
  ],
  "5-12 th": [
    { min: 60, max: 109, score: 0 },
    { min: 110, max: 129, score: 1 },
    { min: 50, max: 59, score: 2 },
    { min: 130, max: 139, score: 2 },
    { min: -Infinity, max: 49, score: 3 },
    { min: 140, max: Infinity, score: 3 }
  ],
  "12-18 th": [
    { min: 55, max: 99, score: 0 },
    { min: 100, max: 109, score: 1 },
    { min: 45, max: 55, score: 2 },
    { min: 110, max: 119, score: 2 },
    { min: -Infinity, max: 44, score: 3 },
    { min: 120, max: Infinity, score: 3 }
  ]
};

const normalizeUsia = (usia: string): string => {
  return usia.trim();
};

const updateSkorPernapasan = (index: number) => {
if (!input.value.details || !input.value.details[index]) return;

if (!input.value.details[index]['CB_Respirasi']) {
    input.value.details[index].skorpernapasan = 0; // Reset when unchecked
    return;
}

// Map checkbox values (as strings) to actual scores
const scoreMap: Record<string, number> = {
    "3": 80,
    "2": 70,
    "02": 60,
    "00": 50,
    "000": 40,
    "1": 30,
    "03": 20,
    "0": 10,
    "0000000": 9,
};

// Get selected checkbox value
const selectedValue = String(input.value.details[index]['CB_Respirasi']);

// Get mapped score
const selectedScore = scoreMap[selectedValue] || 0;

// Get usia
const usia = normalizeUsia(input.value.usia);

if (!respirationScoring[usia]) return;

// Find correct score range
const scoreData = respirationScoring[usia].find(
    range => selectedScore >= range.min && selectedScore <= range.max
);

// ✅ Update reactive property instead of redefining `selectedScore`
input.value.details[index].skorpernapasan = scoreData ? scoreData.score : 0;
};

const updateSkorNadi = (index: number) => {
if (!input.value.details || !input.value.details[index]) return;

if (!input.value.details[index]['CB_Nadi']) {
    input.value.details[index].skorsirkulasi = 0; // Reset when unchecked
    return;
}

// Map checkbox values (as strings) to actual scores
const scoreMap: Record<string, number> = {
    "1": 181,
    "001": 170,
    "002": 160,
    "01": 150,
    "02": 140,
    "03": 130,
    "04": 120,
    "2": 110,
    "003": 100,
    "3": 90,
    "0": 80,
    "00": 70,
    "000": 60,
    "0000": 55,
    "00000": 50,
    "000000": 44,
};

// Get selected checkbox value
const selectedValue = String(input.value.details[index]['CB_Nadi']);

// Get mapped score
const selectedScore2 = scoreMap[selectedValue] || 0;

// Get usia
const usia = normalizeUsia(input.value.usia);

if (!circulationScoring[usia]) return;

// Find correct score range
const scoreData = circulationScoring[usia].find(
    range => selectedScore2 >= range.min && selectedScore2 <= range.max
);

// ✅ Update reactive property instead of redefining `selectedScore2`
input.value.details[index].skorsirkulasi = scoreData ? scoreData.score : 0;
};

const updateSkorBehaviour = (index: number) => {
    if (!input.value.details || !input.value.details[index]) return;

    const ageYears = patientAge.value?.years ?? 0; // ✅ Get patient age in years safely

    if (ageYears > 18) {
        input.value.details[index].skorbehaviour = 0; // Reset if age > 18
        return;
    }

    if (!input.value.details[index]['CB_Behave']) {
        input.value.details[index].skorbehaviour = 0; // Reset when unchecked
        return;
    }

    // Map checkbox values (as strings) to actual scores
    const scoreMap: Record<string, number> = {
        "003": 0,
        "0003": 1,
        "00003": 2,
        "000003": 3,
    };

    // Get selected checkbox value
    const selectedValue = String(input.value.details[index]['CB_Behave']); // ✅ Correct field

    // Get mapped score (default to 0 if not found)
    const selectedScore = scoreMap[selectedValue] || 0;

    // ✅ Directly update the reactive property
    input.value.details[index].skorbehaviour = selectedScore;
};

const d_usia: any = ref([
    { value: '0-<3 bln', label: '0-<3 bln' },
    { value: '3-<12 bln', label: '3-<12 bln' },
    { value: '1-<5 th', label: '1-<5 th' },
    { value: '5-12 th', label: '5-12 th' },
    { value: '12-18 th', label: '12-18 th' },
]);
const d_oksigen: any = ref([
    { value: 'Tidak Menggunakan Oksigen', label: 'Tidak Menggunakan Oksigen' },
    { value: 'FiO₂ ≤ 21% atau 1 L/menit', label: 'FiO₂ ≤ 21% atau 1 L/menit' },
    { value: 'FiO₂ ≥ 40% atau ≥ 40 L/menit', label: 'FiO₂ ≥ 40% atau ≥ 40 L/menit' },
    { value: 'FiO₂ ≥ 50% atau ≥ 6 L/menit', label: 'FiO₂ ≥ 50% atau ≥ 6 L/menit' },
]);
const d_usahaNafas: any = ref([
    { value: 'Tidak ada retraksi', label: 'Tidak ada retraksi' },
    { value: 'Ada sedikit retraksi', label: 'Ada sedikit retraksi' },
    { value: 'Retraksi agak dalam, tracheal tug', label: 'Retraksi agak dalam, tracheal tug' },
    { value: 'Retraksi dalam, tracheal tug, grunting', label: 'Retraksi dalam, tracheal tug, grunting' },
]);
const d_capillaryRefillTime: any = ref([
    { value: 'PINK (CRT 1 - 2 detik)', label: 'PINK (CRT 1 - 2 detik)' },
    { value: 'Pucat (CRT 3 detik)', label: 'Pucat (CRT 3 detik)' },
    { value: 'Abu - abu (CRT 4 detik)', label: 'Abu - abu (CRT 4 detik)' },
    { value: 'Abu - abu (CRT≥5 detik)', label: 'Abu - abu (CRT≥5 detik)' },
]);
const d_tingkatKesadaran: any = ref([
    { value: 'Sadar baik (Alert)', label: 'Sadar baik (Alert)' },
    { value: 'Lemah, banyak tidur, berespon dengan suara (Verbal)', label: 'Lemah, banyak tidur, berespon dengan suara (Verbal)' },
    { value: 'Gelisah, berespon dengan nyeri (Pain)', label: 'Gelisah, berespon dengan nyeri (Pain)' },
    { value: 'Letargi, tidak berespon (Unrespon)', label: 'Letargi, tidak berespon (Unrespon)' },
]);
const d_Pegawai: any = ref([])
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

const patientAge = computed(() => {
  if (!input.value.tglLahir) return null;

  const birthDate = new Date(input.value.tglLahir);
  const today = new Date();

  let years = today.getFullYear() - birthDate.getFullYear();
  let months = today.getMonth() - birthDate.getMonth();
  let days = today.getDate() - birthDate.getDate();

  // Adjust if birth month hasn't occurred yet
  if (months < 0 || (months === 0 && days < 0)) {
    years--;
    months += 12;
  }

  // Adjust if birth day hasn't occurred yet in the current month
  if (days < 0) {
    const previousMonth = new Date(today.getFullYear(), today.getMonth(), 0);
    days += previousMonth.getDate();
    months--;
  }

  return { years, months };
});

const updateSkorOxygen = (index: number) => {
    if (!input.value.details || !input.value.details[index]) return;

    // Check if patientAge is available and under 18
    if (!patientAge.value || patientAge.value.years > 18) return;

    const selectedOxygen = input.value.details[index]['oxygen']; // Get selected oxygen value

    // Define a score mapping for oxygen values
    const oxygenScoreMap: Record<string, number> = {
        "Tidak Menggunakan Oksigen": 0,
        "FiO₂ ≤ 21% atau 1 L/menit": 1,
        "FiO₂ ≥ 40% atau ≥ 40 L/menit": 2,
        "FiO₂ ≥ 50% atau ≥ 6 L/menit": 3,
    };

    // Map selected value to a score
    const selectedScore = oxygenScoreMap[selectedOxygen] || 0;

    // ✅ Add the oxygen score to skorPernapasan
    input.value.details[index].skorpernapasan += selectedScore;
};
const updateSkorBreath = (index: number) => {
    if (!input.value.details || !input.value.details[index]) return;

    // Check if patientAge is available and under 18
    if (!patientAge.value || patientAge.value.years > 18) return;

    const selectedBreath = input.value.details[index]['breath']; // Get selected oxygen value

    // Define a score mapping for oxygen values
    const breathScoreMap: Record<string, number> = {
        "Tidak ada retraksi": 0,
        "Ada sedikit retraksi": 1,
        "Retraksi agak dalam, tracheal tug": 2,
        "Retraksi dalam, tracheal tug, grunting": 3,
    };

    // Map selected value to a score
    const selectedScore = breathScoreMap[selectedBreath] || 0;

    // ✅ Add the oxygen score to skorPernapasan
    input.value.details[index].skorpernapasan += selectedScore;
};
const updateSkorCRT = (index: number) => {
    if (!input.value.details || !input.value.details[index]) return;

    // Check if patientAge is available and under 18
    if (!patientAge.value || patientAge.value.years > 18) return;

    const selectedCRT = input.value.details[index]['crt']; // Get selected oxygen value

    // Define a score mapping for oxygen values
    const crtScoreMap: Record<string, number> = {
        "PINK (CRT 1 - 2 detik)": 0,
        "Pucat (CRT 3 detik)": 1,
        "Abu - abu (CRT 4 detik)": 2,
        "Abu - abu (CRT≥5 detik)": 3,
    };

    // Map selected value to a score
    const selectedScore = crtScoreMap[selectedCRT] || 0;

    // ✅ Add the oxygen score to skorPernapasan
    input.value.details[index].skorsirkulasi += selectedScore;
};



// Watch for changes in patientAge and update usia
watch(patientAge, (age) => {
  if (!age) {
    input.value.usia = null;
    return;
  }

  input.value.details.forEach((_, index) => {
        updateSkorPernapasan(index);
        updateSkorNadi(index);
        updateSkorBehaviour(index);
        updateSkorOxygen(index);
        updateSkorBreath(index);
        updateSkorCRT(index);

        _.TOTAL_RCB =
                (_.skorpernapasan || 0) +
                (_.skorsirkulasi || 0) +
                (_.skorbehaviour || 0);
    });

  if (age.years === 0 && age.months < 3) {
    input.value.usia = "0-<3 bln";
  } else if (age.years === 0 && age.months >= 3) {
    input.value.usia = "3-<12 bln";
  } else if (age.years >= 1 && age.years < 5) {
    input.value.usia = "1-<5 th";
  } else if (age.years >= 5 && age.years < 12) {
    input.value.usia = "5-12 th";
  } else if (age.years >= 12 && age.years < 18) {
    input.value.usia = "12-18 th";
  } else {
    input.value.usia = null;
  }
}, { immediate: true });

watch(
    () => input.value.details, // Watch entire details array
    (newDetails) => {
        if (!newDetails) return;

        newDetails.forEach((detail, index) => {
            // ✅ Recalculate scores based on checkbox values
            updateSkorPernapasan(index);
            updateSkorNadi(index);
            updateSkorBehaviour(index);
            updateSkorOxygen(index);
            updateSkorBreath(index);
            updateSkorCRT(index);

            // ✅ Ensure TOTAL_RCB is updated
            detail.TOTAL_RCB =
                (detail.skorpernapasan || 0) +
                (detail.skorsirkulasi || 0) +
                (detail.skorbehaviour || 0);
        });
    },
    { deep: true } // ✅ Reacts to changes inside objects
);

watch(
    () => input.value.usia, // Watch usia changes
    (newUsia) => {
        if (newUsia === undefined) return;

        input.value.details.forEach((_, index) => {
            updateSkorPernapasan(index);
            updateSkorNadi(index);
            updateSkorBehaviour(index);
            updateSkorOxygen(index);
            updateSkorBreath(index);
            updateSkorCRT(index);

            _.TOTAL_RCB =
                (_.skorpernapasan || 0) +
                (_.skorsirkulasi || 0) +
                (_.skorbehaviour || 0);
        });
    }
);

console.log(props.pasien);
// const loadRiwayat = async () => {
//     isLoading.value = true;
//     let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
//     if (response.length) {
//         input.value = response[0] //set ke inputan
//         if (NOREC_EMRPASIEN.value == '') {
//             NOREC_EMRPASIEN.value = response[0].emrpasienfk
//         }
//     }
//     isLoading.value = false;
// }

const loadRiwayat = () => {
  useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    .then(async (response: any) => {
      if (response.length) {
        input.value = response[0];
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk;
        }

        const ttv = await useApi().get(`emr/get-data-exist-semua?norec_pd=${NOREC_PD}`);
        console.log("Data ttv yang diterima:", ttv); // Debugging log

        // Langsung cek apakah ttv adalah array
        if (Array.isArray(ttv)) {
            if (!Array.isArray(input.value.details)) {
            input.value.details = [];
          }

          ttv.forEach((dataItem: any, index: number) => {
            if (!input.value.details[index]) {
                input.value.details.splice(index, 0, {}); // Menyisipkan elemen baru di index
            }
            input.value.details[index].tgltindakan = dataItem.tanggal;
            input.value.details[index].paraf = dataItem.user_input.namalengkap;
            input.value.details[index].temperature = dataItem.suhu;

            //KONDISI NADI
            if (dataItem.nadi < 45) {
                  input.value.details[index].CB_Nadi = "000000"; // 1
                }
            if (dataItem.nadi == 50) {
                  input.value.details[index].CB_Nadi = "00000"; // 2
                }
            if (dataItem.nadi == 55) {
                  input.value.details[index].CB_Nadi = "0000"; // 3
                }
            if (dataItem.nadi == 60) {
                  input.value.details[index].CB_Nadi = "000"; // 4
                }
            if (dataItem.nadi == 70) {
                  input.value.details[index].CB_Nadi = "00"; // 5
                }
            if (dataItem.nadi == 80) {
                  input.value.details[index].CB_Nadi = "0"; // 6
                }
            if (dataItem.nadi == 90) {
                  input.value.details[index].CB_Nadi = "3"; // 7
                }
            if (dataItem.nadi == 100) {
                  input.value.details[index].CB_Nadi = "003"; // 8
                }
            if (dataItem.nadi == 110) {
                  input.value.details[index].CB_Nadi = "2"; // 9
                }
            if (dataItem.nadi == 120) {
                  input.value.details[index].CB_Nadi = "04"; // 10
                }
            if (dataItem.nadi == 130) {
                  input.value.details[index].CB_Nadi = "03"; // 11
                }
            if (dataItem.nadi == 140) {
                  input.value.details[index].CB_Nadi = "02"; // 12
                }
            if (dataItem.nadi == 150) {
                  input.value.details[index].CB_Nadi = "01"; // 13
                }
            if (dataItem.nadi == 160) {
                  input.value.details[index].CB_Nadi = "002"; // 14
                }
            if (dataItem.nadi == 170) {
                  input.value.details[index].CB_Nadi = "001"; // 15
                }
            if (dataItem.nadi == 180) {
                  input.value.details[index].CB_Nadi = "1"; // 16
                }
            //KONDISI PERNASASAN
            if (dataItem && Number(dataItem.pernapasan) < 10) {
                  input.value.details[index].CB_Respirasi = "0000000"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan == 10) {
                  input.value.details[index].CB_Respirasi = "0"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan == 20) {
                  input.value.details[index].CB_Respirasi = "03"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan == 30) {
                  input.value.details[index].CB_Respirasi = "1"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan == 40) {
                  input.value.details[index].CB_Respirasi = "000"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan == 50) {
                  input.value.details[index].CB_Respirasi = "00"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan == 60) {
                  input.value.details[index].CB_Respirasi = "02"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan == 70) {
                  input.value.details[index].CB_Respirasi = "2"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan == 80) {
                  input.value.details[index].CB_Respirasi = "3"; // Centang checkbox dengan true-value="0"
                }
          });
        } else {
          console.error("Data ttv bukan array:", ttv);
        }

      } else {
        await setAutoFill();
        const ttv = await useApi().get(`emr/get-data-exist-semua?norec_pd=${NOREC_PD}`);
        console.log("Data ttv yang diterima:", ttv); // Debugging log

        // Langsung cek apakah ttv adalah array
        if (Array.isArray(ttv)) {
          if (!Array.isArray(input.value.details)) {
            input.value.details = [];
          }

          ttv.forEach((dataItem: any, index: number) => {

            if (!input.value.details[index]) {
              input.value.details[index] = {};
            }
            input.value.details[index].tgltindakan = dataItem.tanggal;
            input.value.details[index].temperature = dataItem.suhu;

            //KONDISI NADI
            if (dataItem.nadi < 45) {
                  input.value.details[index].CB_Nadi = "000000"; // 1
                }
            if (dataItem.nadi == 50) {
                  input.value.details[index].CB_Nadi = "00000"; // 2
                }
            if (dataItem.nadi == 55) {
                  input.value.details[index].CB_Nadi = "0000"; // 3
                }
            if (dataItem.nadi == 60) {
                  input.value.details[index].CB_Nadi = "000"; // 4
                }
            if (dataItem.nadi == 70) {
                  input.value.details[index].CB_Nadi = "00"; // 5
                }
            if (dataItem.nadi == 80) {
                  input.value.details[index].CB_Nadi = "0"; // 6
                }
            if (dataItem.nadi == 90) {
                  input.value.details[index].CB_Nadi = "3"; // 7
                }
            if (dataItem.nadi == 100) {
                  input.value.details[index].CB_Nadi = "003"; // 8
                }
            if (dataItem.nadi == 110) {
                  input.value.details[index].CB_Nadi = "2"; // 9
                }
            if (dataItem.nadi == 120) {
                  input.value.details[index].CB_Nadi = "04"; // 10
                }
            if (dataItem.nadi == 130) {
                  input.value.details[index].CB_Nadi = "03"; // 11
                }
            if (dataItem.nadi == 140) {
                  input.value.details[index].CB_Nadi = "02"; // 12
                }
            if (dataItem.nadi == 150) {
                  input.value.details[index].CB_Nadi = "01"; // 13
                }
            if (dataItem.nadi == 160) {
                  input.value.details[index].CB_Nadi = "002"; // 14
                }
            if (dataItem.nadi == 170) {
                  input.value.details[index].CB_Nadi = "001"; // 15
                }
            if (dataItem.nadi == 180) {
                  input.value.details[index].CB_Nadi = "1"; // 16
                }
            //KONDISI PERNASASAN
            if (dataItem && Number(dataItem.pernapasan) < 10) {
                  input.value.details[index].CB_Respirasi = "0000000"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan == 10) {
                  input.value.details[index].CB_Respirasi = "0"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan == 20) {
                  input.value.details[index].CB_Respirasi = "03"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan == 30) {
                  input.value.details[index].CB_Respirasi = "1"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan == 40) {
                  input.value.details[index].CB_Respirasi = "000"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan == 50) {
                  input.value.details[index].CB_Respirasi = "00"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan == 60) {
                  input.value.details[index].CB_Respirasi = "02"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan == 70) {
                  input.value.details[index].CB_Respirasi = "2"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan == 80) {
                  input.value.details[index].CB_Respirasi = "3"; // Centang checkbox dengan true-value="0"
                }
          });
        } else {
          console.error("Data ttv bukan array:", ttv);
        }
      }
    })
    .catch(error => {
      console.error("Error saat memuat riwayat:", error);
    });
};

const setAutoFill = async () => {
  input.value.tglLahir = props.pasien.tgllahir
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    object.nocm = pasien.value.nocm

    delete input.value.namatemplate
    object.pasien = H.setObjectPasien(pasien.value)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'url_form': route.name,
        'name_form': 'Pemantauan Pediatric Early Warning Score (PEWS)',
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
    NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}
const fetchPegawai = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => { d_Pegawai.value = response })
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

const addNewItem = () => {
    let newItem: any = {}
    newItem = {
        no: input.value.details[input.value.details.length - 1].no + 1,
        DT_PEWS: new Date(),
        DD_Perawat: { label: user.namaLengkap, value: user.id }
    }
    input.value.details.push(newItem);
}
const removeItem = (index: any) => {
    let urut = input.value.details.length - 1
    input.value.details.splice(urut, 1)
}

watchEffect(() => {
    let total = 0;
    input.value.details.forEach((a, index) => {
        let respirasi = parseFloat(a['CB_Respirasi'] ?? 0)
        let spo2 = parseFloat(a['CB_SpO2'] ?? 0)
        let spo22 = parseFloat(a['CB_N'] ?? 0)
        let udara = parseFloat(a['CB_Nadi'] ?? 0)
        let tdsistolik = parseFloat(a['CB_Behave'] ?? 0)
        let nadi = parseFloat(a['CB_Nadi'] ?? 0)
        let kesadaran = parseFloat(a['CB_Kesadaran'] ?? 0)
        let temperature = parseFloat(a['CB_Temperature'] ?? 0)

        total = respirasi + spo2 + spo22 + udara + tdsistolik + nadi + kesadaran + temperature
        if (!isNaN(total)) {
            a['TB_TOTAL'] = total
        } else {
            a['TB_TOTAL'] = 0
        }
    });
});

</script>
