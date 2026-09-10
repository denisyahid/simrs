<style lang="scss">
.table {
    border-collapse: collapse;
    width: 100% !important;
}

.table td {
    border: 1px solid black !important;
    height: 4rem !important;
    text-align: center !important;
    ;
}

.table th {
    text-align: center !important;
    border: 1px solid black !important;
    font-weight: bold !important
}

.center {
    text-align: center !important;
    vertical-align: middle !important;
}

h1 {
    font-weight: bold !important
}
</style>
<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Pemantauan Modified Early Obstetric Warning Score (MEOWS)</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate"
                                @kembaliKeun="kembaliKeun" :isHideCetak="true" isHideST></ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

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
                    <div class="column is-4 pr-0">
                        <table style="width: 100%;border-collapse: collapse;border:1px solid black;">
                            <tr>
                                <th style="height: 4rem;border-bottom: 1px solid black;" class="center" colspan="3">
                                    Tanggal & Jam</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;width: 55%;border-right:1px solid black" class="center"
                                    rowspan="4">Pernapasan (x/menit)</th>
                                <th style="height: 4rem;width: 25%;" class="center">> 25</th>
                                <th style="height: 4rem;width: 20%;background-color:red;border: 1px solid black;color:white;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">21 - 25</th>
                                <th style="height: 4rem;background-color:yellow;border: 1px solid black;"
                                    class="center">2</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">12 - 20</th>
                                <th style="height: 4rem;background-color:white;border: 1px solid black;" class="center">
                                    0</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">12</th>
                                <th style="height: 4rem;width: 20%;background-color:red;border: 1px solid black;color:white;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;border-right:1px solid black;border-top:1px solid black;"
                                    class="center" rowspan="3">Saturasi (%)</th>
                                <th style="height: 4rem;border-top: 1px solid black;" class="center">&gt; 92</th>
                                <th style="height: 4rem;width: 20%;background-color:red;border: 1px solid black;color:white;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">92 - 95</th>
                                <th style="height: 4rem;background-color:yellow;border: 1px solid black;"
                                    class="center">2</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">95 &gt;</th>
                                <th style="height: 4rem;background-color:white;border: 1px solid black;" class="center">
                                    0</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;border-right:1px solid black;border-top:1px solid black;"
                                    class="center" rowspan="2">Penggunaan O2</th>
                                <th style="height: 4rem;border-top: 1px solid black;" class="center">
                                    Ya</th>
                                <th style="height: 4rem;background-color:yellow;border: 1px solid black;"
                                    class="center">2</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">Tidak</th>
                                <th style="height: 4rem;background-color:white;border: 1px solid black;" class="center">
                                    0</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;border-right:1px solid black;border-top:1px solid black;"
                                    class="center" rowspan="4">Suhu (°C)</th>
                                <th style="height: 4rem;border-top: 1px solid black;" class="center">&gt; 37,7</th>
                                <th style="height: 4rem;width: 20%;background-color:red;border: 1px solid black;color:white;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">37,3 - 37,7</th>
                                <th style="height: 4rem;background-color:yellow;border: 1px solid black;"
                                    class="center">2</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">36,1 - 37,2</th>
                                <th style="height: 4rem;background-color:white;border: 1px solid black;" class="center">
                                    0</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">≤ 36</th>
                                <th style="height: 4rem;width: 20%;background-color:red;border: 1px solid black;color:white;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;border-right:1px solid black;border-top:1px solid black;"
                                    class="center" rowspan="6">Nadi (x/menit)</th>
                                <th style="height: 4rem;border-top: 1px solid black;" class="center">&gl; 120</th>
                                <th style="height: 4rem;background-color:red;border: 1px solid black;color:white;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">111 - 120</th>
                                <th style="height: 4rem;background-color:yellow;border: 1px solid black;"
                                    class="center">2</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">101 - 110</th>
                                <th style="height: 4rem;background-color:lime;border: 1px solid black;" class="center">1
                                </th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">61 - 100</th>
                                <th style="height: 4rem;background-color:white;border: 1px solid black;" class="center">
                                    0</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">50 - 60</th>
                                <th style="height: 4rem;background-color:yellow;border: 1px solid black;"
                                    class="center">2</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">&lt; 50</th>
                                <th style="height: 4rem;background-color:red;border: 1px solid black;color:white;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;border-right:1px solid black;border-top:1px solid black;"
                                    class="center" rowspan="5">Tekanan Darah Sistolik (mmHg)</th>
                                <th style="height: 4rem;border-top: 1px solid black;" class="center">&gt; 160</th>
                                <th style="height: 4rem;background-color:red;border: 1px solid black;color:white;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">151 - 160</th>
                                <th style="height: 4rem;background-color:yellow;border: 1px solid black;"
                                    class="center">2</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">141 - 150</th>
                                <th style="height: 4rem;background-color:lime;border: 1px solid black;" class="center">1
                                </th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">90 - 140</th>
                                <th style="height: 4rem;background-color:white;border: 1px solid black;" class="center">
                                    0</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">&lt; 90</th>
                                <th style="height: 4rem;background-color:red;border: 1px solid black;color:white;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;border-right:1px solid black;border-top:1px solid black;"
                                    class="center" rowspan="4">Tekanan Darah Diastolik (mmHg)</th>
                                <th style="height: 4rem;border-top: 1px solid black;" class="center">&gt; 110</th>
                                <th style="height: 4rem;background-color:red;border: 1px solid black;color:white;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">101 - 110</th>
                                <th style="height: 4rem;background-color:yellow;border: 1px solid black;"
                                    class="center">2</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">91 - 100</th>
                                <th style="height: 4rem;background-color:lime;border: 1px solid black;" class="center">1
                                </th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">60 - 90</th>
                                <th style="height: 4rem;background-color:white;border: 1px solid black;" class="center">
                                    0</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;border-right:1px solid black;border-top:1px solid black;"
                                    class="center" rowspan="2">Discharge/Lochea</th>
                                <th style="height: 4rem;border-top: 1px solid black;" class="center">Normal</th>
                                <th style="height: 4rem;background-color:white;border: 1px solid black;" class="center">
                                    0</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">Abnormal</th>
                                <th style="height: 4rem;background-color:red;border: 1px solid black;color:white;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;border-right:1px solid black;border-top:1px solid black;"
                                    class="center" rowspan="2">Protein Urine</th>
                                <th style="height: 4rem;border-top: 1px solid black;" class="center">≥ + 2</th>
                                <th style="height: 4rem;background-color:red;border: 1px solid black;color:white;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">+ 1</th>
                                <th style="height: 4rem;background-color:yellow;border: 1px solid black;"
                                    class="center">2</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;border-right:1px solid black;border-top:1px solid black;"
                                    class="center" rowspan="3">Kesadaran</th>
                                <th style="height: 4rem;border-top: 1px solid black;" class="center">Alert</th>
                                <th style="height: 4rem;background-color:white;border: 1px solid black;" class="center">
                                    0</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">Voice</th>
                                <th style="height: 4rem;background-color:red;border: 1px solid black;color:white;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">Pain/Unrespon</th>
                                <th style="height: 4rem;background-color:red;border: 1px solid black;color:white;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;border-right:1px solid black;border-top:1px solid black;"
                                    class="center" rowspan="2">Nyeri</th>
                                <th style="height: 4rem;border-top: 1px solid black;" class="center">Normal</th>
                                <th style="height: 4rem;background-color:white;border: 1px solid black;" class="center">
                                    0</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;" class="center">Abnormal</th>
                                <th style="height: 4rem;background-color:red;border: 1px solid black;color:white;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;border-top:1px solid black" class="center" colspan="3">
                                    Total MEOWS
                                </th>
                            </tr>
                            <tr>
                                <th style="height: 4rem;border-top:1px solid black" class="center" colspan="3">
                                    Paraf & Nama
                                </th>
                            </tr>
                        </table>
                    </div>
                    <div class="column is-8 pl-0" style="overflow: auto;">
                        <table class="table" style="width: auto !important;">
                            <tr>
                                <td v-for="(data, index) in input.details" :key="index" class="p-0">
                                    <table class="table" style="width: 12rem !important;">
                                        <tr>
                                            <td>
                                                <VDatePicker v-model="data['DT_MEOWS']" mode="datetime" is24hr>
                                                    <template #default="{ inputValue, inputEvents }">
                                                        <VControl icon="feather:calendar" fullwidth>
                                                            <VInput :value="inputValue" v-on="inputEvents" />
                                                        </VControl>
                                                    </template>
                                                </VDatePicker>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: red !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="3"
                                                        label="" v-model="data['CB_Pernapasan']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: yellow !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="2"
                                                        label="" v-model="data['CB_Pernapasan']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="0"
                                                        label="" v-model="data['CB_Pernapasan']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: red !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="03"
                                                        label="" v-model="data['CB_Pernapasan']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: red !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="3"
                                                        label="" v-model="data['CB_Saturasi']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: yellow !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="2"
                                                        label="" v-model="data['CB_Saturasi']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="0"
                                                        label="" v-model="data['CB_Saturasi']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: yellow !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="2"
                                                        label="" v-model="data['CB_PenggunaanO2']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="0"
                                                        label="" v-model="data['CB_PenggunaanO2']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: red !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="3"
                                                        label="" v-model="data['CB_Suhu']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: yellow !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="2"
                                                        label="" v-model="data['CB_Suhu']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="00"
                                                        label="" v-model="data['CB_Suhu']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: red !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="03"
                                                        label="" v-model="data['CB_Suhu']" />
                                                </VControl>
                                            </td>
                                        </tr>   
                                        <tr>
                                            <td style="background-color: red !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="3"
                                                        label="" v-model="data['CB_Nadi']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: yellow !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="2"
                                                        label="" v-model="data['CB_Nadi']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: lime !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="1"
                                                        label="" v-model="data['CB_Nadi']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="0"
                                                        label="" v-model="data['CB_Nadi']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: yellow !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="02"
                                                        label="" v-model="data['CB_Nadi']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: red !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="03"
                                                        label="" v-model="data['CB_Nadi']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: red !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="3"
                                                        label="" v-model="data['CB_TDSistolik']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: yellow !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="2"
                                                        label="" v-model="data['CB_TDSistolik']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: lime !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="1"
                                                        label="" v-model="data['CB_TDSistolik']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="0"
                                                        label="" v-model="data['CB_TDSistolik']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: red !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="03"
                                                        label="" v-model="data['CB_TDSistolik']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: red !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="3"
                                                        label="" v-model="data['CB_TDDiastolik']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: yellow !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="2"
                                                        label="" v-model="data['CB_TDDiastolik']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: lime !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="1"
                                                        label="" v-model="data['CB_TDDiastolik']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="0"
                                                        label="" v-model="data['CB_TDDiastolik']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="0"
                                                        label="" v-model="data['CB_Discharge']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: red !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="3"
                                                        label="" v-model="data['CB_Discharge']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: red !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="3"
                                                        label="" v-model="data['CB_ProteinUrine']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: yellow !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="2"
                                                        label="" v-model="data['CB_ProteinUrine']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="0"
                                                        label="" v-model="data['CB_Kesadaran']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: red !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="3"
                                                        label="" v-model="data['CB_Kesadaran']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: red !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="3"
                                                        label="" v-model="data['CB_Kesadaran']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="0"
                                                        label="" v-model="data['CB_Nyeri']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: red !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="3"
                                                        label="" v-model="data['CB_Nyeri']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="data['TB_TOTAL']"
                                                        disabled />
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
                </div>

                <div class="column">
                    <table style="width: 100%;border-collapse: collapse;">
                        <tr>
                            <th style="border: 1px solid black;" class="center" colspan="5">RENTANG SKOR TOTAL MEOWS
                            </th>
                        </tr>
                        <tr>
                            <td style="width: 20%;background-color:lime !important;border: 1px solid black;font-weight:bold;color:black"
                                class="center">
                                0
                            </td>
                            <td style="width: 20%;background-color:yellow !important;border: 1px solid black;font-weight:bold;color:black"
                                class="center">
                                1-4
                            </td>
                            <td style="width: 20%;background-color:orange !important;border: 1px solid black;font-weight:bold;color:black"
                                class="center">
                                5-6 / SATU PARAMETER (3)
                            </td>
                            <td style="width: 20%;background-color:red !important;border: 1px solid black;font-weight:bold;color:white"
                                class="center">
                                ≥ 7
                            </td>
                            <td style="width: 20%;background-color:blue !important;border: 1px solid black;font-weight:bold;color:black"
                                class="center">
                                HENTI NAPAS ATAU JANTUNG
                            </td>
                        </tr>
                    </table>
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
// import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
// import DataTable from 'primevue/datatable'
// import Column from 'primevue/column'
// import InputText from 'primevue/inputtext';

useHead({ title: 'Pemantauan Modified Early Obstetric Warning Score (MEOWS) - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const COLLECTION: any = ref('PemantauanMEOWS') //table mongodb
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
        DT_MEOWS: new Date(),
        DD_Perawat: { label: user.namaLengkap, value: user.id }
    }],
})
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

           
            //KONDISI PERNASASAN
            if (dataItem.pernapasan > 25) {
                  input.value.details[index].CB_Pernapasan = "3"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan >= 21 && dataItem.pernapasan <= 25) {
                input.value.details[index].CB_Pernapasan = "2"; 
                }
            if (dataItem.pernapasan >= 12 && dataItem.pernapasan <= 20) {
                input.value.details[index].CB_Pernapasan = "0"; 
                }
            if (dataItem.pernapasan == 12) {
                  input.value.details[index].CB_Pernapasan = "03"; // Centang checkbox dengan true-value="0"
                }
            //KONDISI SATURASI
            if (dataItem.pernapasan > 92) {
                  input.value.details[index].CB_Saturasi = "3"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan >= 92 && dataItem.pernapasan <= 95) {
                input.value.details[index].CB_Saturasi = "2"; 
                }
            if (dataItem.pernapasan > 95) {
                  input.value.details[index].CB_Saturasi = "0"; // Centang checkbox dengan true-value="0"
                }
            // KONDISI SUHU
            if (parseFloat(dataItem.suhu) > 37.7) {
                input.value.details[index].CB_Suhu = "3"; 
                }
            if (parseFloat(dataItem.suhu) >= 37.3 && parseFloat(dataItem.suhu) <= 37.7) {
                input.value.details[index].CB_Suhu = "2"; 
                }
            if (parseFloat(dataItem.suhu) >= 36.1 && parseFloat(dataItem.suhu) <= 37.2) {
                input.value.details[index].CB_Suhu = "00"; 
                }
            if (parseFloat(dataItem.suhu) <= 36.1) {
                input.value.details[index].CB_Suhu = "03"; 
                }
            //KONDISI NADI
            if (dataItem.nadi > 120) {
                  input.value.details[index].CB_Nadi = "3"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi >= 111 && dataItem.nadi <= 120) {
                  input.value.details[index].CB_Nadi = "2"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi >= 101 && dataItem.nadi <= 110) {
                  input.value.details[index].CB_Nadi = "1"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi >= 61 && dataItem.nadi <= 100) {
                  input.value.details[index].CB_Nadi = "0"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi >= 50 && dataItem.nadi <= 60) {
                  input.value.details[index].CB_Nadi = "02"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi < 50) {
                  input.value.details[index].CB_Nadi = "03"; // Centang checkbox dengan true-value="0"
                }
            //Pemisahan SISTOLIK
        const tekananDarahSistolik = dataItem.tekananDarah ? parseInt(dataItem.tekananDarah.split("/")[0]) : null;
                //KONIDISI TEKANAN DARAH SISTOLIK
            if (tekananDarahSistolik > 160 ) {
                input.value.details[index].CB_TDSistolik = "3"; 
                }
            if (tekananDarahSistolik >= 151 && tekananDarahSistolik <= 160) {
                input.value.details[index].CB_TDSistolik = "2"; 
                }
            if (tekananDarahSistolik >= 141 && tekananDarahSistolik <= 150) {
                input.value.details[index].CB_TDSistolik = "1"; 
                }
            if (tekananDarahSistolik >= 90 && tekananDarahSistolik <= 140) {
                input.value.details[index].CB_TDSistolik = "0"; 
                }
            if (tekananDarahSistolik < 90 ) {
                input.value.details[index].CB_TDSistolik = "03"; 
                }
           //Pemisahan DIASTOLIK     
        const tekananDarahDiastolik = dataItem.tekananDarah ? parseInt(dataItem.tekananDarah.split("/")[1]) : null;
                //KONIDISI TEKANAN DARAH DIASTOLIK
            if (tekananDarahDiastolik > 110 ) {
                input.value.details[index].CB_TDDiastolik = "3"; 
                }
            if (tekananDarahDiastolik >= 101 && tekananDarahDiastolik <= 110) {
                input.value.details[index].CB_TDDiastolik = "2"; 
                }
            if (tekananDarahDiastolik >= 91 && tekananDarahDiastolik <= 100) {
                input.value.details[index].CB_TDDiastolik = "1"; 
                }
            if (tekananDarahDiastolik >= 60 && tekananDarahDiastolik <= 90) {
                input.value.details[index].CB_TDDiastolik = "0"; 
                }
          });
        } else {
          console.error("Data ttv bukan array:", ttv);
        }

      } else {
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
            input.value.details[index].paraf = dataItem.user_input.namalengkap;

            //KONDISI PERNASASAN
            if (dataItem.pernapasan > 25) {
                  input.value.details[index].CB_Pernapasan = "3"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan >= 21 && dataItem.pernapasan <= 25) {
                input.value.details[index].CB_Pernapasan = "2"; 
                }
            if (dataItem.pernapasan >= 12 && dataItem.pernapasan <= 20) {
                input.value.details[index].CB_Pernapasan = "0"; 
                }
            if (dataItem.pernapasan == 12) {
                  input.value.details[index].CB_Pernapasan = "03"; // Centang checkbox dengan true-value="0"
                }
            //KONDISI SATURASI
            if (dataItem.pernapasan > 92) {
                  input.value.details[index].CB_Saturasi = "3"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan >= 92 && dataItem.pernapasan <= 95) {
                input.value.details[index].CB_Saturasi = "2"; 
                }
            if (dataItem.pernapasan > 95) {
                  input.value.details[index].CB_Saturasi = "0"; // Centang checkbox dengan true-value="0"
                }
            // KONDISI SUHU
            if (parseFloat(dataItem.suhu) > 37.7) {
                input.value.details[index].CB_Suhu = "3"; 
                }
            if (parseFloat(dataItem.suhu) >= 37.3 && parseFloat(dataItem.suhu) <= 37.7) {
                input.value.details[index].CB_Suhu = "2"; 
                }
            if (parseFloat(dataItem.suhu) >= 36.1 && parseFloat(dataItem.suhu) <= 37.2) {
                input.value.details[index].CB_Suhu = "00"; 
                }
            if (parseFloat(dataItem.suhu) <= 36.1) {
                input.value.details[index].CB_Suhu = "03"; 
                }
            //KONDISI NADI
            if (dataItem.nadi > 120) {
                  input.value.details[index].CB_Nadi = "3"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi >= 111 && dataItem.nadi <= 120) {
                  input.value.details[index].CB_Nadi = "2"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi >= 101 && dataItem.nadi <= 110) {
                  input.value.details[index].CB_Nadi = "1"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi >= 61 && dataItem.nadi <= 100) {
                  input.value.details[index].CB_Nadi = "0"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi >= 50 && dataItem.nadi <= 60) {
                  input.value.details[index].CB_Nadi = "02"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi < 50) {
                  input.value.details[index].CB_Nadi = "03"; // Centang checkbox dengan true-value="0"
                }
            //Pemisahan SISTOLIK
        const tekananDarahSistolik = dataItem.tekananDarah ? parseInt(dataItem.tekananDarah.split("/")[0]) : null;
                //KONIDISI TEKANAN DARAH SISTOLIK
            if (tekananDarahSistolik > 160 ) {
                input.value.details[index].CB_TDSistolik = "3"; 
                }
            if (tekananDarahSistolik >= 151 && tekananDarahSistolik <= 160) {
                input.value.details[index].CB_TDSistolik = "2"; 
                }
            if (tekananDarahSistolik >= 141 && tekananDarahSistolik <= 150) {
                input.value.details[index].CB_TDSistolik = "1"; 
                }
            if (tekananDarahSistolik >= 90 && tekananDarahSistolik <= 140) {
                input.value.details[index].CB_TDSistolik = "0"; 
                }
            if (tekananDarahSistolik < 90 ) {
                input.value.details[index].CB_TDSistolik = "03"; 
                }
           //Pemisahan DIASTOLIK     
        const tekananDarahDiastolik = dataItem.tekananDarah ? parseInt(dataItem.tekananDarah.split("/")[1]) : null;
                //KONIDISI TEKANAN DARAH DIASTOLIK
            if (tekananDarahDiastolik > 110 ) {
                input.value.details[index].CB_TDDiastolik = "3"; 
                }
            if (tekananDarahDiastolik >= 101 && tekananDarahDiastolik <= 110) {
                input.value.details[index].CB_TDDiastolik = "2"; 
                }
            if (tekananDarahDiastolik >= 91 && tekananDarahDiastolik <= 100) {
                input.value.details[index].CB_TDDiastolik = "1"; 
                }
            if (tekananDarahDiastolik >= 60 && tekananDarahDiastolik <= 90) {
                input.value.details[index].CB_TDDiastolik = "0"; 
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

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    object.nocm = pasien.value.nocm

    object.pasien = H.setObjectPasien(pasien.value)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'url_form': route.name,
        'name_form': 'Pemantauan Modified Early Obstetric Warning Score (MEOWS)',
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
        no: input.value.details[input.value.details.length - 1].no + 1
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
        let pernapasan = parseFloat(a['CB_Pernapasan'] ?? 0)
        let saturasi = parseFloat(a['CB_Saturasi'] ?? 0)
        let penggunaanO2 = parseFloat(a['CB_PenggunaanO2'] ?? 0)
        let suhu = parseFloat(a['CB_Suhu'] ?? 0)
        let nadi = parseFloat(a['CB_Nadi'] ?? 0)
        let tdsistolik = parseFloat(a['CB_TDSistolik'] ?? 0)
        let tddiastolik = parseFloat(a['CB_TDDiastolik'] ?? 0)
        let discharge = parseFloat(a['CB_Discharge'] ?? 0)
        let proteinurine = parseFloat(a['CB_ProteinUrine'] ?? 0)
        let kesadaran = parseFloat(a['CB_Kesadaran'] ?? 0)
        let nyeri = parseFloat(a['CB_Nyeri'] ?? 0)

        total = pernapasan + saturasi + penggunaanO2 + suhu + nadi + tdsistolik + tddiastolik + discharge + proteinurine + kesadaran + nyeri        
        if (!isNaN(total)) {
            a['TB_TOTAL'] = total
        } else {
            a['TB_TOTAL'] = 0
        }
    });
});
</script>