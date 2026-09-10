<style lang="scss">
.table {
    border-collapse: collapse;
    width: 100% !important;
    border: 1px solid black !important;
}

.table td {
    height: 5rem !important;
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
</style>
<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Pemantauan Adult Early Warning Score (AEWS)</h3>
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
                                <th style="height: 5rem;border-bottom: 1px solid black;" class="center" colspan="3">
                                    Tanggal & Jam</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;width: 60%;border-right:1px solid black" class="center"
                                    rowspan="7">RESPIRASI (x/menit)</th>
                                <th style="height: 5rem;width: 20%;" class="center">25</th>
                                <th style="height: 5rem;width: 20%;background-color:red !important;border: 1px solid black;color:white !important;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">21</th>
                                <th style="height: 5rem;background-color:yellow !important;border: 1px solid black;"
                                    class="center">2</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">18</th>
                                <th style="height: 5rem;background-color:white !important;border: 1px solid black;"
                                    class="center">
                                    0</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">15</th>
                                <th style="height: 5rem;background-color:white !important;border: 1px solid black;"
                                    class="center">
                                    0</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">12</th>
                                <th style="height: 5rem;background-color:white !important;border: 1px solid black;"
                                    class="center">
                                    0</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">9</th>
                                <th style="height: 5rem;background-color:lime !important;border: 1px solid black;"
                                    class="center">1
                                </th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center"></th>
                                <th style="height: 5rem;background-color:red !important;border: 1px solid black;color:white !important;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;border-right:1px solid black;border-top:1px solid black;"
                                    class="center" rowspan="4">SpO<sub>2</sub> SKALA 1</th>
                                <th style="height: 5rem;border-top: 1px solid black;" class="center">96</th>
                                <th style="height: 5rem;background-color:white !important;border: 1px solid black;"
                                    class="center">
                                    0</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">94</th>
                                <th style="height: 5rem;background-color:lime !important;border: 1px solid black;"
                                    class="center">1
                                </th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">92</th>
                                <th style="height: 5rem;background-color:yellow !important;border: 1px solid black;"
                                    class="center">2</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center"></th>
                                <th style="height: 5rem;background-color:red !important;border: 1px solid black;color:white !important;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;border-right:1px solid black;border-top:1px solid black;"
                                    class="center" rowspan="8">SpO<sub>2</sub> SKALA 2 (Dipandu klinis ahli)</th>
                                <th style="height: 5rem;border-top: 1px solid black;" class="center">
                                    97<sub>on</sub>O<sub>2</sub></th>
                                <th style="height: 5rem;background-color:red !important;border: 1px solid black;color:white !important;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">95<sub>on</sub>O<sub>2</sub></th>
                                <th style="height: 5rem;background-color:yellow !important;border: 1px solid black;"
                                    class="center">2</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">93<sub>on</sub>O<sub>2</sub></th>
                                <th style="height: 5rem;background-color:lime !important;border: 1px solid black;"
                                    class="center">1
                                </th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">≥ 93<sub>on</sub>O<sub>2</sub></th>
                                <th style="height: 5rem;background-color:white !important;border: 1px solid black;"
                                    class="center">
                                    0</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">88</th>
                                <th style="height: 5rem;background-color:white !important;border: 1px solid black;"
                                    class="center">
                                    0</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">86</th>
                                <th style="height: 5rem;background-color:lime !important;border: 1px solid black;"
                                    class="center">1
                                </th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">84</th>
                                <th style="height: 5rem;background-color:yellow !important;border: 1px solid black;"
                                    class="center">2</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center"></th>
                                <th style="height: 5rem;background-color:red !important;border: 1px solid black;color:white !important;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;border-right:1px solid black;border-top:1px solid black;"
                                    class="center" rowspan="3">UDARA (O<sub>2</sub>)</th>
                                <th style="height: 5rem;border-top: 1px solid black;" class="center">Air</th>
                                <th style="height: 5rem;background-color:white !important;border: 1px solid black;"
                                    class="center">
                                    0</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">O<sub>2</sub> L/min</th>
                                <th style="height: 5rem;background-color:yellow !important;border: 1px solid black;"
                                    class="center">2</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">Device</th>
                                <th style="height: 5rem;background-color:white !important;border: 1px solid black;"
                                    class="center">
                                    0</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;border-right:1px solid black;border-top:1px solid black;"
                                    class="center" rowspan="14">TD SISTOLIK (mmHg)</th>
                                <th style="height: 5rem;border-top: 1px solid black;" class="center">220</th>
                                <th style="height: 5rem;background-color:red !important;border: 1px solid black;color:white !important;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">201</th>
                                <th style="height: 5rem;background-color:white !important;border: 1px solid black;"
                                    class="center">
                                    0</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">181</th>
                                <th style="height: 5rem;background-color:white !important;border: 1px solid black;"
                                    class="center">
                                    0</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">161</th>
                                <th style="height: 5rem;background-color:white !important;border: 1px solid black;"
                                    class="center">
                                    0</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">141</th>
                                <th style="height: 5rem;background-color:white !important;border: 1px solid black;"
                                    class="center">
                                    0</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">121</th>
                                <th style="height: 5rem;background-color:white !important;border: 1px solid black;"
                                    class="center">
                                    0</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">111</th>
                                <th style="height: 5rem;background-color:white !important;border: 1px solid black;"
                                    class="center">
                                    0</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">101</th>
                                <th style="height: 5rem;background-color:lime !important;border: 1px solid black;"
                                    class="center">1
                                </th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">91</th>
                                <th style="height: 5rem;background-color:yellow !important;border: 1px solid black;"
                                    class="center">2</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">81</th>
                                <th style="height: 5rem;background-color:red !important;border: 1px solid black;color:white !important;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">71</th>
                                <th style="height: 5rem;background-color:red !important;border: 1px solid black;color:white !important;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">61</th>
                                <th style="height: 5rem;background-color:red !important;border: 1px solid black;color:white !important;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">51</th>
                                <th style="height: 5rem;background-color:red !important;border: 1px solid black;color:white !important;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center"></th>
                                <th style="height: 5rem;background-color:red !important;border: 1px solid black;color:white !important;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;border-right:1px solid black;border-top:1px solid black;"
                                    class="center" rowspan="12">NADI/HR (Denyut/menit)</th>
                                <th style="height: 5rem;border-top: 1px solid black;" class="center">131</th>
                                <th style="height: 5rem;background-color:red !important;border: 1px solid black;color:white !important;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">121</th>
                                <th style="height: 5rem;background-color:yellow !important;border: 1px solid black;"
                                    class="center">2</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">111</th>
                                <th style="height: 5rem;background-color:yellow !important;border: 1px solid black;"
                                    class="center">2</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">101</th>
                                <th style="height: 5rem;background-color:lime !important;border: 1px solid black;"
                                    class="center">1
                                </th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">91</th>
                                <th style="height: 5rem;background-color:lime !important;border: 1px solid black;"
                                    class="center">1
                                </th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">81</th>
                                <th style="height: 5rem;background-color:white !important;border: 1px solid black;"
                                    class="center">
                                    0</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">71</th>
                                <th style="height: 5rem;background-color:white !important;border: 1px solid black;"
                                    class="center">
                                    0</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">61</th>
                                <th style="height: 5rem;background-color:white !important;border: 1px solid black;"
                                    class="center">
                                    0</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">51</th>
                                <th style="height: 5rem;background-color:white !important;border: 1px solid black;"
                                    class="center">
                                    0</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">41</th>
                                <th style="height: 5rem;background-color:lime !important;border: 1px solid black;"
                                    class="center">1
                                </th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">31</th>
                                <th style="height: 5rem;background-color:red !important;border: 1px solid black;color:white !important;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">30</th>
                                <th style="height: 5rem;background-color:red !important;border: 1px solid black;color:white !important;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;border-right:1px solid black;border-top:1px solid black;"
                                    class="center" rowspan="5">KESADARAN</th>
                                <th style="height: 5rem;border-top: 1px solid black;" class="center">A</th>
                                <th style="height: 5rem;background-color:white !important;border: 1px solid black;"
                                    class="center">
                                    0</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">C</th>
                                <th style="height: 5rem;background-color:red !important;border: 1px solid black;color:white !important;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">V</th>
                                <th style="height: 5rem;background-color:red !important;border: 1px solid black;color:white !important;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">P</th>
                                <th style="height: 5rem;background-color:red !important;border: 1px solid black;color:white !important;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">U</th>
                                <th style="height: 5rem;background-color:red !important;border: 1px solid black;color:white !important;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;border-right:1px solid black;border-top:1px solid black;"
                                    class="center" rowspan="6">TEMPERATUR (°C)</th>
                                <th style="height: 5rem;border-top: 1px solid black;" class="center">39.1°</th>
                                <th style="height: 5rem;background-color:yellow !important;border: 1px solid black;"
                                    class="center">2</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">38.1°</th>
                                <th style="height: 5rem;background-color:lime !important;border: 1px solid black;"
                                    class="center">1
                                </th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">37.1°</th>
                                <th style="height: 5rem;background-color:white !important;border: 1px solid black;"
                                    class="center">
                                    0</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">36.1°</th>
                                <th style="height: 5rem;background-color:white !important;border: 1px solid black;"
                                    class="center">
                                    0</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center">35.1°</th>
                                <th style="height: 5rem;background-color:lime !important;border: 1px solid black;"
                                    class="center">1
                                </th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;" class="center"></th>
                                <th style="height: 5rem;background-color:red !important;border: 1px solid black;color:white !important;"
                                    class="center">3</th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;border-top:1px solid black" class="center" colspan="3">
                                    Total AEWS
                                </th>
                            </tr>
                            <tr>
                                <th style="height: 5rem;border-top:1px solid black" class="center" colspan="3">
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
                                                <VDatePicker v-model="data['DT_AEWS']" mode="datetime" is24hr>
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
                                                        label="" v-model="data['CB_Respirasi']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: yellow !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="2"
                                                        label="" v-model="data['CB_Respirasi']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="0"
                                                        label="" v-model="data['CB_Respirasi']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="00"
                                                        label="" v-model="data['CB_Respirasi']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="000"
                                                        label="" v-model="data['CB_Respirasi']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: lime !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="1"
                                                        label="" v-model="data['CB_Respirasi']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: red !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="03"
                                                        label="" v-model="data['CB_Respirasi']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="0"
                                                        label="" v-model="data['CB_SpO2']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: lime !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="1"
                                                        label="" v-model="data['CB_SpO2']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: yellow !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="2"
                                                        label="" v-model="data['CB_SpO2']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: red !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="3"
                                                        label="" v-model="data['CB_SpO2']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: red !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="3"
                                                        label="" v-model="data['CB_SpO2_2']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: yellow !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="2"
                                                        label="" v-model="data['CB_SpO2_2']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: lime !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="1"
                                                        label="" v-model="data['CB_SpO2_2']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="0"
                                                        label="" v-model="data['CB_SpO2_2']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="00"
                                                        label="" v-model="data['CB_SpO2_2']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: lime !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="01"
                                                        label="" v-model="data['CB_SpO2_2']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: yellow !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="02"
                                                        label="" v-model="data['CB_SpO2_2']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: red !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="03"
                                                        label="" v-model="data['CB_SpO2_2']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="0"
                                                        label="" v-model="data['CB_Udara']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: yellow !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="2"
                                                        label="" v-model="data['CB_Udara']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;display: flex;align-items: center;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="00"
                                                        label="" v-model="data['CB_Udara']" />
                                                </VControl>
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="data['Udara_Lainnya']"/>
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: red !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="3"
                                                        label="" v-model="data['CB_TD_Sistolik']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="0"
                                                        label="" v-model="data['CB_TD_Sistolik']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="00"
                                                        label="" v-model="data['CB_TD_Sistolik']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="000"
                                                        label="" v-model="data['CB_TD_Sistolik']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="0000"
                                                        label="" v-model="data['CB_TD_Sistolik']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="00000"
                                                        label="" v-model="data['CB_TD_Sistolik']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="000000"
                                                        label="" v-model="data['CB_TD_Sistolik']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: lime !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="1"
                                                        label="" v-model="data['CB_TD_Sistolik']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: yellow !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="2"
                                                        label="" v-model="data['CB_TD_Sistolik']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: red !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="03"
                                                        label="" v-model="data['CB_TD_Sistolik']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: red !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="003"
                                                        label="" v-model="data['CB_TD_Sistolik']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: red !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="0003"
                                                        label="" v-model="data['CB_TD_Sistolik']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: red !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="00003"
                                                        label="" v-model="data['CB_TD_Sistolik']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: red !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="000003"
                                                        label="" v-model="data['CB_TD_Sistolik']" />
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
                                            <td style="background-color: yellow !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="02"
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
                                            <td style="background-color: lime !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="01"
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
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="00"
                                                        label="" v-model="data['CB_Nadi']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="000"
                                                        label="" v-model="data['CB_Nadi']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="0000"
                                                        label="" v-model="data['CB_Nadi']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: lime !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="001"
                                                        label="" v-model="data['CB_Nadi']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: red !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="003"
                                                        label="" v-model="data['CB_Nadi']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: red !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="0003"
                                                        label="" v-model="data['CB_Nadi']" />
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
                                                    <VCheckbox class="p-0" color="primary" square true-value="03"
                                                        label="" v-model="data['CB_Kesadaran']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: red !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="003"
                                                        label="" v-model="data['CB_Kesadaran']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: red !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="0003"
                                                        label="" v-model="data['CB_Kesadaran']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: yellow !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="2"
                                                        label="" v-model="data['CB_Temperature']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: lime !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="1"
                                                        label="" v-model="data['CB_Temperature']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="0"
                                                        label="" v-model="data['CB_Temperature']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="00"
                                                        label="" v-model="data['CB_Temperature']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: lime !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="01"
                                                        label="" v-model="data['CB_Temperature']" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: red !important;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="3"
                                                        label="" v-model="data['CB_Temperature']" />
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
                            <th style="border: 1px solid black;" class="center" colspan="4">RENTANG SKOR TOTAL AEWS</th>
                        </tr>
                        <tr>
                            <td style="width: 25%;background-color:lime !important;border: 1px solid black;font-weight:bold"
                                class="center">
                                0
                            </td>
                            <td style="width: 25%;background-color:yellow !important;border: 1px solid black;font-weight:bold"
                                class="center">
                                1-4
                            </td>
                            <td style="width: 25%;background-color:orange !important;border: 1px solid black;font-weight:bold"
                                class="center">
                                5-6 / SATU PARAMETER (3)
                            </td>
                            <td style="width: 25%;background-color:red !important;border: 1px solid black;font-weight:bold;color:white !important"
                                class="center">
                                ≥ 7
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

useHead({ title: 'Pemantauan Adult Early Warning Score (AEWS) - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const COLLECTION: any = ref('PemantauanAEWS') //table mongodb
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
        DT_AEWS: new Date(),
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

            //KONDISI NADI
            if (dataItem.nadi == "30") {
                  input.value.details[index].CB_Nadi = "003"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi >= "31" && dataItem.nadi < "41") {
                  input.value.details[index].CB_Nadi = "0003"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi >= "41" && dataItem.nadi < "51") {
                  input.value.details[index].CB_Nadi = "001"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi >= "51" && dataItem.nadi < "61") {
                  input.value.details[index].CB_Nadi = "0000"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi >= "61" && dataItem.nadi < "71") {
                  input.value.details[index].CB_Nadi = "000"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi >= "71" && dataItem.nadi < "81") {
                  input.value.details[index].CB_Nadi = "00"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi >= "81" && dataItem.nadi < "91") {
                  input.value.details[index].CB_Nadi = "0"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi >= "91" && dataItem.nadi < "101") {
                  input.value.details[index].CB_Nadi = "01"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi >= "101" && dataItem.nadi < "111") {
                  input.value.details[index].CB_Nadi = "1"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi >= "111" && dataItem.nadi < "121") {
                  input.value.details[index].CB_Nadi = "02"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi >= "121" && dataItem.nadi < "131") {
                  input.value.details[index].CB_Nadi = "2"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi >= "131") {
                  input.value.details[index].CB_Nadi = "3"; // Centang checkbox dengan true-value="0"
                }
                // KONDISI SUHU
            if (parseFloat(dataItem.suhu) < 35.1) {
                input.value.details[index].CB_Temperature = "3";
            }
            if (parseFloat(dataItem.suhu) >= 35.1 && parseFloat(dataItem.suhu) < 36.1) {
                input.value.details[index].CB_Temperature = "01";
            }
            if (parseFloat(dataItem.suhu) >= 36.1 && parseFloat(dataItem.suhu) < 37.1) {
                input.value.details[index].CB_Temperature = "00";
            }
            if (parseFloat(dataItem.suhu) >= 37.1 && parseFloat(dataItem.suhu) < 38.1) {
                input.value.details[index].CB_Temperature = "0";
            }
            if (parseFloat(dataItem.suhu) >= 38.1 && parseFloat(dataItem.suhu) < 39.1) {
                input.value.details[index].CB_Temperature = "1";
            }
            if (parseFloat(dataItem.suhu) >= 39.1 && parseFloat(dataItem.suhu) < 40.1) {
                input.value.details[index].CB_Temperature = "2";
            }
        //Pemisahan SISTOLIK
        const tekananDarahSistolik = dataItem.tekananDarah ? parseInt(dataItem.tekananDarah.split("/")[0]) : null;
                //KONIDISI TEKANAN DARAH
            if (tekananDarahSistolik === null || tekananDarahSistolik === undefined || tekananDarahSistolik === "") {
                input.value.details[index].CB_TD_Sistolik = null;
            } else if (tekananDarahSistolik < 51) {
                input.value.details[index].CB_TD_Sistolik = "000003";
            } else if (tekananDarahSistolik >= 51 && tekananDarahSistolik < 61) {
                input.value.details[index].CB_TD_Sistolik = "00003";
            } else if (tekananDarahSistolik >= 61 && tekananDarahSistolik < 71) {
                input.value.details[index].CB_TD_Sistolik = "0003";
            } else if (tekananDarahSistolik >= 71 && tekananDarahSistolik < 81) {
                input.value.details[index].CB_TD_Sistolik = "003";
            } else if (tekananDarahSistolik >= 81 && tekananDarahSistolik < 91) {
                input.value.details[index].CB_TD_Sistolik = "03";
            } else if (tekananDarahSistolik >= 91 && tekananDarahSistolik < 101) {
                input.value.details[index].CB_TD_Sistolik = "2";
            } else if (tekananDarahSistolik >= 101 && tekananDarahSistolik < 111) {
                input.value.details[index].CB_TD_Sistolik = "1";
            } else if (tekananDarahSistolik >= 111 && tekananDarahSistolik < 121) {
                input.value.details[index].CB_TD_Sistolik = "000000";
            } else if (tekananDarahSistolik >= 121 && tekananDarahSistolik < 141) {
                input.value.details[index].CB_TD_Sistolik = "00000";
            } else if (tekananDarahSistolik >= 141 && tekananDarahSistolik < 161) {
                input.value.details[index].CB_TD_Sistolik = "0000";
            } else if (tekananDarahSistolik >= 161 && tekananDarahSistolik < 181) {
                input.value.details[index].CB_TD_Sistolik = "000";
            } else if (tekananDarahSistolik >= 181 && tekananDarahSistolik < 201) {
                input.value.details[index].CB_TD_Sistolik = "00";
            } else if (tekananDarahSistolik >= 201 && tekananDarahSistolik < 221) {
                input.value.details[index].CB_TD_Sistolik = "0";
            } else if (tekananDarahSistolik >= 221) {
                input.value.details[index].CB_TD_Sistolik = "3";
            }

            //KONDISI PERNASASAN
            if (dataItem.pernapasan < 9) {
                  input.value.details[index].CB_Respirasi = "03"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan >= 9 && dataItem.pernapasan < 12) {
                  input.value.details[index].CB_Respirasi = "1"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan >= 12 && dataItem.pernapasan < 15) {
                  input.value.details[index].CB_Respirasi = "000"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan >= 15 && dataItem.pernapasan < 18) {
                  input.value.details[index].CB_Respirasi = "00"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan >= 18 && dataItem.pernapasan < 21) {
                  input.value.details[index].CB_Respirasi = "0"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan >= 21 && dataItem.pernapasan < 25) {
                  input.value.details[index].CB_Respirasi = "2"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan >= 25) {
                  input.value.details[index].CB_Respirasi = "3"; // Centang checkbox dengan true-value="0"
                }

            //KONDISI SPO2
            if (dataItem.SPO2 >= 96) {
                input.value.details[index].CB_SpO2 = "0";
            } else if (dataItem.SPO2 >= 94 && dataItem.SPO2 <= 95) {
                input.value.details[index].CB_SpO2 = "1";
            } else if (dataItem.SPO2 >= 92 && dataItem.SPO2 <= 93) {
                input.value.details[index].CB_SpO2 = "2";
            } else if (dataItem.SPO2 < 92) {
                input.value.details[index].CB_SpO2 = "3";
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

            //KONDISI NADI
            if (dataItem.nadi == "30") {
                  input.value.details[index].CB_Nadi = "003"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi >= "31" && dataItem.nadi < "41") {
                  input.value.details[index].CB_Nadi = "0003"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi >= "41" && dataItem.nadi < "51") {
                  input.value.details[index].CB_Nadi = "001"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi >= "51" && dataItem.nadi < "61") {
                  input.value.details[index].CB_Nadi = "0000"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi >= "61" && dataItem.nadi < "71") {
                  input.value.details[index].CB_Nadi = "000"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi >= "71" && dataItem.nadi < "81") {
                  input.value.details[index].CB_Nadi = "00"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi >= "81" && dataItem.nadi < "91") {
                  input.value.details[index].CB_Nadi = "0"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi >= "91" && dataItem.nadi < "101") {
                  input.value.details[index].CB_Nadi = "01"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi >= "101" && dataItem.nadi < "111") {
                  input.value.details[index].CB_Nadi = "1"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi >= "111" && dataItem.nadi < "121") {
                  input.value.details[index].CB_Nadi = "02"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi >= "121" && dataItem.nadi < "131") {
                  input.value.details[index].CB_Nadi = "2"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.nadi >= "131") {
                  input.value.details[index].CB_Nadi = "3"; // Centang checkbox dengan true-value="0"
                }
                // KONDISI SUHU
            if (parseFloat(dataItem.suhu) < 35.1) {
                input.value.details[index].CB_Temperature = "3";
            }
            if (parseFloat(dataItem.suhu) >= 35.1 && parseFloat(dataItem.suhu) < 36.1) {
                input.value.details[index].CB_Temperature = "01";
            }
            if (parseFloat(dataItem.suhu) >= 36.1 && parseFloat(dataItem.suhu) < 37.1) {
                input.value.details[index].CB_Temperature = "00";
            }
            if (parseFloat(dataItem.suhu) >= 37.1 && parseFloat(dataItem.suhu) < 38.1) {
                input.value.details[index].CB_Temperature = "0";
            }
            if (parseFloat(dataItem.suhu) >= 38.1 && parseFloat(dataItem.suhu) < 39.1) {
                input.value.details[index].CB_Temperature = "1";
            }
            if (parseFloat(dataItem.suhu) >= 39.1 && parseFloat(dataItem.suhu) < 40.1) {
                input.value.details[index].CB_Temperature = "2";
            }
        //Pemisahan SISTOLIK
        const tekananDarahSistolik = dataItem.tekananDarah ? parseInt(dataItem.tekananDarah.split("/")[0]) : null;
                //KONIDISI TEKANAN DARAH
            if (tekananDarahSistolik === null || tekananDarahSistolik === undefined || tekananDarahSistolik === "") {
                input.value.details[index].CB_TD_Sistolik = null;
            } else if (tekananDarahSistolik < 51) {
                input.value.details[index].CB_TD_Sistolik = "000003";
            } else if (tekananDarahSistolik >= 51 && tekananDarahSistolik < 61) {
                input.value.details[index].CB_TD_Sistolik = "00003";
            } else if (tekananDarahSistolik >= 61 && tekananDarahSistolik < 71) {
                input.value.details[index].CB_TD_Sistolik = "0003";
            } else if (tekananDarahSistolik >= 71 && tekananDarahSistolik < 81) {
                input.value.details[index].CB_TD_Sistolik = "003";
            } else if (tekananDarahSistolik >= 81 && tekananDarahSistolik < 91) {
                input.value.details[index].CB_TD_Sistolik = "03";
            } else if (tekananDarahSistolik >= 91 && tekananDarahSistolik < 101) {
                input.value.details[index].CB_TD_Sistolik = "2";
            } else if (tekananDarahSistolik >= 101 && tekananDarahSistolik < 111) {
                input.value.details[index].CB_TD_Sistolik = "1";
            } else if (tekananDarahSistolik >= 111 && tekananDarahSistolik < 121) {
                input.value.details[index].CB_TD_Sistolik = "000000";
            } else if (tekananDarahSistolik >= 121 && tekananDarahSistolik < 141) {
                input.value.details[index].CB_TD_Sistolik = "00000";
            } else if (tekananDarahSistolik >= 141 && tekananDarahSistolik < 161) {
                input.value.details[index].CB_TD_Sistolik = "0000";
            } else if (tekananDarahSistolik >= 161 && tekananDarahSistolik < 181) {
                input.value.details[index].CB_TD_Sistolik = "000";
            } else if (tekananDarahSistolik >= 181 && tekananDarahSistolik < 201) {
                input.value.details[index].CB_TD_Sistolik = "00";
            } else if (tekananDarahSistolik >= 201 && tekananDarahSistolik < 221) {
                input.value.details[index].CB_TD_Sistolik = "0";
            } else if (tekananDarahSistolik >= 221) {
                input.value.details[index].CB_TD_Sistolik = "3";
            }

            //KONDISI PERNASASAN
            if (dataItem.pernapasan < 9) {
                  input.value.details[index].CB_Respirasi = "03"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan >= 9 && dataItem.pernapasan < 12) {
                  input.value.details[index].CB_Respirasi = "1"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan >= 12 && dataItem.pernapasan < 15) {
                  input.value.details[index].CB_Respirasi = "000"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan >= 15 && dataItem.pernapasan < 18) {
                  input.value.details[index].CB_Respirasi = "00"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan >= 18 && dataItem.pernapasan < 21) {
                  input.value.details[index].CB_Respirasi = "0"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan >= 21 && dataItem.pernapasan < 25) {
                  input.value.details[index].CB_Respirasi = "2"; // Centang checkbox dengan true-value="0"
                }
            if (dataItem.pernapasan >= 25) {
                  input.value.details[index].CB_Respirasi = "3"; // Centang checkbox dengan true-value="0"
                }
            //KONDISI SPO2
            if (dataItem.SPO2 >= 96) {
                input.value.details[index].CB_SpO2 = "0";
            } else if (dataItem.SPO2 >= 94 && dataItem.SPO2 <= 95) {
                input.value.details[index].CB_SpO2 = "1";
            } else if (dataItem.SPO2 >= 92 && dataItem.SPO2 <= 93) {
                input.value.details[index].CB_SpO2 = "2";
            } else if (dataItem.SPO2 < 92) {
                input.value.details[index].CB_SpO2 = "3";
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

//function baru
// const loadRiwayat = async () => {
//   try {
//     // 1. Load data EMR yang sudah tersimpan
//     const emrResponse = await useApi().get(
//       `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
//     );

//     if (emrResponse.length) {
//       input.value = emrResponse[0];
//       if (!NOREC_EMRPASIEN.value) {
//         NOREC_EMRPASIEN.value = emrResponse[0].emrpasienfk;
//       }
//     }

//     // 2. Simpan detail yang sudah ada sebelum memuat data baru
//     const existingDetails = input.value.details
//       ? JSON.parse(JSON.stringify(input.value.details))
//       : [];

//             // 3. Load data TTV terbaru
//       const ttv = await useApi().get(`emr/get-data-exist-semua?norec_pd=${NOREC_PD}`);
//       console.log("Data TTV diterima:", ttv);

//       if (!Array.isArray(ttv)) {
//         console.error("Data TTV bukan array:", ttv);
//         return;
//       }

//       // 4. Inisialisasi array details jika belum ada
//       if (!Array.isArray(input.value.details)) {
//         input.value.details = [];
//       }

//       // 5. Proses setiap data TTV
//       ttv.forEach((dataItem, index) => {
//         // Gunakan data yang sudah ada atau buat objek baru
//         if (!input.value.details[index]) {
//           input.value.details[index] = existingDetails[index] || {};
//         }

//         const currentDetail = input.value.details[index];

//         // Fungsi helper untuk memproses field tanpa menimpa data yang sudah ada
//         const processField = (fieldName, newValue, mappingFn) => {
//           // Jika field sudah diisi oleh get-emr, JANGAN timpa
//           if (currentDetail[fieldName] !== undefined && currentDetail[fieldName] !== null && currentDetail[fieldName] !== "") {
//             return; // Skip, karena sudah ada data dari get-emr
//           }

//           // Kalau belum ada isi, baru gunakan data dari get-data
//           if (newValue !== undefined && newValue !== null && newValue !== "") {
//             currentDetail[fieldName] = mappingFn(newValue);
//           } else if (!(fieldName in currentDetail)) {
//             currentDetail[fieldName] = null;
//           }
//         };


//         // Update informasi dasar dari data baru
//         currentDetail.tgltindakan = dataItem.tanggal || currentDetail.tgltindakan;
//         currentDetail.paraf = dataItem.user_input?.namalengkap || currentDetail.paraf;

//         // NADI (pulse)
//         processField("CB_Nadi", dataItem.nadi, (nadi) => {
//           const pulse = parseInt(nadi);
//           if (isNaN(pulse)) return null;

//           if (pulse === 30) return "003";
//           if (pulse < 41) return "0003";
//           if (pulse < 51) return "001";
//           if (pulse < 61) return "0000";
//           if (pulse < 71) return "000";
//           if (pulse < 81) return "00";
//           if (pulse < 91) return "0";
//           if (pulse < 101) return "01";
//           if (pulse < 111) return "1";
//           if (pulse < 121) return "02";
//           if (pulse < 131) return "2";
//           return "3";
//         });

//         // SUHU (temperature)
//         processField("CB_Temperature", dataItem.suhu, (suhu) => {
//           const temp = parseFloat(suhu);
//           if (isNaN(temp)) return null;

//           if (temp < 35.1) return "3";
//           if (temp < 36.1) return "01";
//           if (temp < 37.1) return "00";
//           if (temp < 38.1) return "0";
//           if (temp < 39.1) return "1";
//           if (temp < 40.1) return "2";
//           return null;
//         });

//         // TEKANAN DARAH SISTOLIK
//         processField("CB_TD_Sistolik", dataItem.tekananDarah?.split("/")[0], (sistolikStr) => {
//           const sistolik = parseInt(sistolikStr);
//           if (isNaN(sistolik)) return null;

//           if (sistolik < 51) return "000003";
//           if (sistolik < 61) return "00003";
//           if (sistolik < 71) return "0003";
//           if (sistolik < 81) return "003";
//           if (sistolik < 91) return "03";
//           if (sistolik < 101) return "2";
//           if (sistolik < 111) return "1";
//           if (sistolik < 121) return "000000";
//           if (sistolik < 141) return "00000";
//           if (sistolik < 161) return "0000";
//           if (sistolik < 181) return "000";
//           if (sistolik < 201) return "00";
//           if (sistolik < 221) return "0";
//           return "3";
//         });

//         // RESPIRASI (pernapasan)
//         processField("CB_Respirasi", dataItem.pernapasan, (pernapasan) => {
//           const resp = parseInt(pernapasan);
//           if (isNaN(resp)) return null;

//           if (resp < 9) return "03";
//           if (resp < 12) return "1";
//           if (resp < 15) return "000";
//           if (resp < 18) return "00";
//           if (resp < 21) return "0";
//           if (resp < 25) return "2";
//           return "3";
//         });

//         // SpO2
//         processField("CB_SpO2", dataItem.SPO2, (spo2) => {
//           const sp = parseInt(spo2);
//           if (isNaN(sp)) return null;

//           if (sp >= 96) return "0";
//           if (sp >= 94) return "1";
//           if (sp >= 92) return "2";
//           return "3";
//         });
//       });

//     console.log("Data setelah diproses:", input.value.details);
//   } catch (error) {
//     console.error("Error saat memuat riwayat:", error);
//   }
// };

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
        'name_form': 'Pemantauan Adult Early Warning Score (AEWS)',
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
        let respirasi = parseFloat(a['CB_Respirasi'] ?? 0)
        let spo2 = parseFloat(a['CB_SpO2'] ?? 0)
        let spo22 = parseFloat(a['CB_SpO2_2'] ?? 0)
        let udara = parseFloat(a['CB_Udara'] ?? 0)
        let tdsistolik = parseFloat(a['CB_TD_Sistolik'] ?? 0)
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
