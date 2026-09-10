<style lang="scss">
table {
    border-collapse: collapse;
    width: 100%;
}

.border {
    border: 1px solid black;
}

.bg {
    background-color: #f6f6f6 !important;
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
                        <h3>Pemantauan Neonatus Early Warning Socre (NEWS)</h3>
                    </div>
                    <div class="right">
                        <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                            @simpan="simpan" @kembaliKeun="kembaliKeun" :isHideCetak="true" isHideST>
                        </ButtonEmr>
                    </div>
                </div>
            </div>

            <!-- form baru -->

            <div class="column is-12 pt-0">
                <div class="column is-12" style="text-align: center;font-size: large;font-weight: bold;">
                    <div class="card has-background-warning">
                        <div class="card-content">
                            <div class="content">
                                Data Form Ini Menyesuaikan Dari Data Vital Sign<br>
                                <i style="color:white">(Kecuali Merintih, Neuro, Skor, Nama Petugas)</i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-12 pt-0 pb-0">
                    <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                </div>
                <div class="column is-12 pb-0" style="text-align: center;font-weight: bold;font-size: large;">
                    <div class="column is-6">
                        <VField label="Periode" style="margin-bottom: 6px;" />
                        <VDatePicker v-model="item.qFilterTgl" is-range color="pink" locale="id" trim-weeks>
                            <template #default="{ inputValue, inputEvents }">
                            <VField addons>
                                <VControl icon="feather:calendar">
                                <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                </VControl>
                                <VControl>
                                <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                                </VControl>
                                <VControl icon="feather:calendar">
                                <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                </VControl>
                            </VField>
                            </template>
                        </VDatePicker>
                    </div>
                </div>
                <div class="column is-12 pb-0" style="text-align: center;font-weight: bold;font-size: large;">
                    <h1><b>RESPIRASI</b><br>(x/menit)</h1>
                </div>
                <div class="column is-12" v-if="setChartData != null">
                    <VCard style="border-radius: 16px;" v-if="setChartData != null">
                        <Chart
                            type="line"
                            :data="setChartData"
                            :options="chartOptions"
                            :height="300"
                            class="h-30rem"
                            />
                    </VCard>
                </div>
                <!-- <div class="column is-12">Grafik akan ditampilkan jika sudah mengisi inputan form...</div> -->
                <div class="column is-12 pb-0 pt-0">
                    <VCard style="border-radius: 16px;background-color: lightgray" class="p-3">
                        <div class="column is-12 columns is-multiline m-0 p-0" style="text-align: center;">
                            <div class="column is-12 pt-0 pb-0" style="font-weight: bold;">Warna Skor</div>
                            <div class="column is-2 is-flex" style="justify-content: center;">
                                <div
                                    style="width: 70px;height: 30px;background-color: red;color: white;border-radius: 10px;">
                                    20
                                </div>
                            </div>
                            <div class="column is-2 is-flex" style="justify-content: center;">
                                <div
                                    style="width: 70px;height: 30px;background-color: yellow;color: black;border-radius: 10px;">
                                    20-30
                                </div>
                            </div>
                            <div class="column is-4 is-flex" style="justify-content: center;">
                                <div
                                    style="width: 70px;height: 30px;background-color: white;color: black;border-radius: 10px;">
                                    30-60
                                </div>
                            </div>
                            <div class="column is-2 is-flex" style="justify-content: center;">
                                <div
                                    style="width: 70px;height: 30px;background-color: yellow;color: black;border-radius: 10px;">
                                    60-80
                                </div>
                            </div>
                            <div class="column is-2 is-flex" style="justify-content: center;">
                                <div
                                    style="width: 70px;height: 30px;background-color: red;color: white;border-radius: 10px;">
                                    80-85
                                </div>
                            </div>
                        </div>
                    </VCard>
                </div>
                <div class="column is-12 pb-0" style="overflow:auto;height: 150px;">
                    <table style="border: 1px solid black;">
                        <tbody>
                            <tr>
                                <th style="text-align: center;" class="th-pri bg">
                                    Tanggal & Jam
                                </th>
                                <th style="text-align: center;" class="th-pri bg">
                                    Respirasi
                                </th>
                                <th style="width: 15%;text-align: center;" class="th-pri bg">
                                    #
                                </th>
                            </tr>
                            <tr v-for="(item, index) in filteredDetails" :key="index">
                                <th style="text-align: center;" class="th-pri">
                                    <VDatePicker v-model="item.tglPukul" mode="datetime" is24hr>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue" placeholder="Pukul..." v-on="inputEvents" />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </th>
                                <th style="text-align: center;" class="th-pri">
                                    <VControl>
                                        <VInput type="number" class="input" v-model="item.respirasi" />
                                    </VControl>
                                </th>
                                <th class="th-pri">
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
                        </tbody>
                    </table>
                </div>
            </div>

            <hr style="border-top: 1px dashed gray;background-color:white" class="mt-0 mb-1">

            <div class="column is-12 pt-0">
                <div class="column is-12 pb-0" style="text-align: center;font-weight: bold;font-size: large;">
                    <h1><b>MERINTIH</b></h1>
                </div>
                <div class="column is-12 pb-0" style="overflow:auto;height: 150px;">
                    <table style="border: 1px solid black;">
                        <tbody>
                            <tr>
                                <th style="text-align: center;" class="th-pri bg">
                                    Tanggal & Jam
                                </th>
                                <th style="text-align: center;" class="th-pri bg">
                                    Merintih
                                </th>
                                <th style="width: 15%;text-align: center;" class="th-pri bg">
                                    #
                                </th>
                            </tr>
                            <tr v-for="(item, index) in filteredDetails" :key="index">
                                <th style="text-align: center;" class="th-pri">
                                    <VDatePicker v-model="item.tglPukul" mode="datetime" is24hr>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue" placeholder="Pukul..." v-on="inputEvents"
                                                    disabled />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </th>
                                <th class="th-pri" style="background-color: yellow;">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.merintih" />
                                    </VControl>
                                </th>
                                <th class="th-pri">
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
                        </tbody>
                    </table>
                </div>
            </div>

            <hr style="border-top: 1px dashed gray;background-color:white" class="mt-0 mb-1">

            <div class="column is-12 pt-0">
                <div class="column is-12 pb-0" style="text-align: center;font-weight: bold;font-size: large;">
                    <h1><b>WARNA (SpO<sub>2</sub>)</b></h1>
                </div>
                <div class="column is-12 pb-0" style="overflow:auto;height: 150px;">
                    <table style="border: 1px solid black;">
                        <tbody>
                            <tr>
                                <th style="text-align: center;" class="th-pri bg">
                                    Tanggal & Jam
                                </th>
                                <th style="text-align: center;" class="th-pri bg">
                                    MERAH MUDA (>94%)
                                </th>
                                <th style="text-align: center;" class="th-pri bg">
                                    90-94%
                                </th>
                                <th style="text-align: center;" class="th-pri bg">
                                    BIRU/KEHITAMAN (&lt;90%)
                                </th>
                                <th style="width: 15%;text-align: center;" class="th-pri bg">
                                    #
                                </th>
                            </tr>
                            <tr v-for="(item, index) in filteredDetails" :key="index">
                                <th style="text-align: center;" class="th-pri">
                                    <VDatePicker v-model="item.tglPukul" mode="datetime" is24hr>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue" placeholder="Pukul..." v-on="inputEvents"
                                                    disabled />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </th>
                                <th class="th-pri">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.warna_putih" />
                                    </VControl>
                                </th>
                                <th class="th-pri" style="background-color: yellow;">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.warna_kuning" />
                                    </VControl>
                                </th>
                                <th class="th-pri" style="background-color: red;">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.warna_merah" />
                                    </VControl>
                                </th>
                                <th class="th-pri">
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
                        </tbody>
                    </table>
                </div>
            </div>

            <hr style="border-top: 1px dashed gray;background-color:white" class="mt-0 mb-1">

            <div class="column is-12 pt-0">
                <div class="column is-12 pb-0" style="text-align: center;font-weight: bold;font-size: large;">
                    <h1><b>NADI/HR</b><br>(Denyut/menit)</h1>
                </div>
                <div class="column is-12" v-if="setChartData2 != null">
                    <VCard style="border-radius: 16px;" v-if="setChartData2 != null">
                        <Chart
                            type="line"
                            :data="setChartData2"
                            :options="chartOptions"
                            :height="300"
                            class="h-30rem"
                            />
                    </VCard>
                </div>
                <!-- <div class="column is-12" v-else>Grafik akan ditampilkan jika sudah mengisi inputan form...</div> -->
                <div class="column is-12 pb-0 pt-0">
                    <VCard style="border-radius: 16px;background-color: lightgray" class="p-3">
                        <div class="column is-12 columns is-multiline m-0 p-0" style="text-align: center;">
                            <div class="column is-12 pt-0 pb-0" style="font-weight: bold;">Warna Skor</div>
                            <div class="column is-2 is-flex" style="justify-content: center;">
                                <div
                                    style="width: 70px;height: 30px;background-color: red;color: white;border-radius: 10px;">
                                    60-70
                                </div>
                            </div>
                            <div class="column is-2 is-flex" style="justify-content: center;">
                                <div
                                    style="width: 70px;height: 30px;background-color: yellow;color: black;border-radius: 10px;">
                                    70-90
                                </div>
                            </div>
                            <div class="column is-4 is-flex" style="justify-content: center;">
                                <div
                                    style="width: 70px;height: 30px;background-color: white;color: black;border-radius: 10px;">
                                    90-150
                                </div>
                            </div>
                            <div class="column is-2 is-flex" style="justify-content: center;">
                                <div
                                    style="width: 70px;height: 30px;background-color: yellow;color: black;border-radius: 10px;">
                                    150-190
                                </div>
                            </div>
                            <div class="column is-2 is-flex" style="justify-content: center;">
                                <div
                                    style="width: 70px;height: 30px;background-color: red;color: white;border-radius: 10px;">
                                    190-200
                                </div>
                            </div>
                        </div>
                    </VCard>
                </div>
                <div class="column is-12 pb-0" style="overflow:auto;height: 150px;">
                    <table style="border: 1px solid black;">
                        <tbody>
                            <tr>
                                <th style="text-align: center;" class="th-pri bg">
                                    Tanggal & Jam
                                </th>
                                <th style="text-align: center;" class="th-pri bg">
                                    Nadi
                                </th>
                                <th style="width: 15%;text-align: center;" class="th-pri bg">
                                    #
                                </th>
                            </tr>
                            <tr v-for="(item, index) in filteredDetails" :key="index"> 
                                <th style="text-align: center;" class="th-pri">
                                    <VDatePicker v-model="item.tglPukul" mode="datetime" is24hr>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue" placeholder="Pukul..." v-on="inputEvents"
                                                    disabled />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </th>
                                <th style="text-align: center;" class="th-pri">
                                    <VControl>
                                        <VInput type="number" class="input" v-model="item.nadi" />
                                    </VControl>
                                </th>
                                <th class="th-pri">
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
                        </tbody>
                    </table>
                </div>
            </div>

            <hr style="border-top: 1px dashed gray;background-color:white" class="mt-0 mb-1">

            <div class="column is-12 pt-0">
                <div class="column is-12 pb-0" style="text-align: center;font-weight: bold;font-size: large;">
                    <h1><b>NEURO</b></h1>
                </div>
                <div class="column is-12 pb-0" style="overflow:auto;height: 150px;">
                    <table style="border: 1px solid black;">
                        <tbody>
                            <tr>
                                <th style="text-align: center;" class="th-pri bg">
                                    Tanggal & Jam
                                </th>
                                <th style="text-align: center;" class="th-pri bg">
                                    Refleksi Hisap Aktif
                                </th>
                                <th style="text-align: center;" class="th-pri bg">
                                    Gelisah/Rewel
                                </th>
                                <th style="text-align: center;" class="th-pri bg">
                                    Lunglai/Sulit Dibangunkan
                                </th>
                                <th style="text-align: center;" class="th-pri bg">
                                    Kejang
                                </th>
                                <th style="width: 15%;text-align: center;" class="th-pri bg">
                                    #
                                </th>
                            </tr>
                            <tr v-for="(item, index) in filteredDetails" :key="index">
                                <th style="text-align: center;" class="th-pri">
                                    <VDatePicker v-model="item.tglPukul" mode="datetime" is24hr>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue" placeholder="Pukul..." v-on="inputEvents"
                                                    disabled />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </th>
                                <th class="th-pri">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.refleksi_hisap" />
                                    </VControl>
                                </th>
                                <th class="th-pri" style="background-color: yellow;">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.gelisah" />
                                    </VControl>
                                </th>
                                <th class="th-pri" style="background-color: red;">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.lunglai" />
                                    </VControl>
                                </th>
                                <th class="th-pri" style="background-color: red;">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.kejang" />
                                    </VControl>
                                </th>
                                <th class="th-pri">
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
                        </tbody>
                    </table>
                </div>
            </div>

            <hr style="border-top: 1px dashed gray;background-color:white" class="mt-0 mb-1">

            <div class="column is-12 pt-0">
                <div class="column is-12 pb-0" style="text-align: center;font-weight: bold;font-size: large;">
                    <h1><b>TEMPERATURE</b><br>(C°)</h1>
                </div>
                <div class="column is-12" v-if="setChartData3 != null">
                    <VCard style="border-radius: 16px;" v-if="setChartData3 != null">
                        <Chart
                            type="line"
                            :data="setChartData3"
                            :options="chartOptions"
                            :height="300"
                            class="h-30rem"
                            />
                    </VCard>
                </div>
                <div class="column is-12" v-else>Grafik akan ditampilkan jika sudah mengisi inputan form...</div>
                <div class="column is-12 pb-0 pt-0">
                    <VCard style="border-radius: 16px;background-color: lightgray" class="p-3">
                        <div class="column is-12 columns is-multiline m-0 p-0" style="text-align: center;">
                            <div class="column is-12 pt-0 pb-0" style="font-weight: bold;">Warna Skor</div>
                            <div class="column is-2 is-flex" style="justify-content: center;">
                                <div
                                    style="width: 70px;height: 30px;background-color: red;color: white;border-radius: 10px;">
                                    35
                                </div>
                            </div>
                            <div class="column is-2 is-flex" style="justify-content: center;">
                                <div
                                    style="width: 70px;height: 30px;background-color: yellow;color: black;border-radius: 10px;">
                                    36
                                </div>
                            </div>
                            <div class="column is-4 is-flex" style="justify-content: center;">
                                <div
                                    style="width: 70px;height: 30px;background-color: white;color: black;border-radius: 10px;">
                                    36,5 - 37,5
                                </div>
                            </div>
                            <div class="column is-2 is-flex" style="justify-content: center;">
                                <div
                                    style="width: 70px;height: 30px;background-color: yellow;color: black;border-radius: 10px;">
                                    38
                                </div>
                            </div>
                            <div class="column is-2 is-flex" style="justify-content: center;">
                                <div
                                    style="width: 70px;height: 30px;background-color: red;color: white;border-radius: 10px;">
                                    38,5 - 39
                                </div>
                            </div>
                        </div>
                    </VCard>
                </div>
                <div class="column is-12 pb-0" style="overflow:auto;height: 150px;">
                    <table style="border: 1px solid black;">
                        <tbody>
                            <tr>
                                <th style="text-align: center;" class="th-pri bg">
                                    Tanggal & Jam
                                </th>
                                <th style="text-align: center;" class="th-pri bg">
                                    Suhu
                                </th>
                                <th style="width: 15%;text-align: center;" class="th-pri bg">
                                    #
                                </th>
                            </tr>
                            <tr v-for="(item, index) in filteredDetails" :key="index">
                                <th style="text-align: center;" class="th-pri">
                                    <VDatePicker v-model="item.tglPukul" mode="datetime" is24hr>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue" placeholder="Pukul..." v-on="inputEvents"
                                                    disabled />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </th>
                                <th style="text-align: center;" class="th-pri">
                                    <VControl>
                                        <VInput type="number" class="input" v-model="item.suhu" />
                                    </VControl>
                                </th>
                                <th class="th-pri">
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
                        </tbody>
                    </table>
                </div>
            </div>

            <hr style="border-top: 1px dashed gray;background-color:white" class="mt-0 mb-1">

            <div class="column is-12 pt-0">
                <div class="column is-12 pb-0" style="text-align: center;font-weight: bold;font-size: large;">
                    <h1><b>SKOR</b></h1>
                </div>
                <div class="column is-12 pb-0" style="overflow:auto;height: 150px;">
                    <table style="border: 1px solid black;">
                        <tbody>
                            <tr>
                                <th style="text-align: center;" class="th-pri bg">
                                    Tanggal & Jam
                                </th>
                                <th style="text-align: center;" class="th-pri bg">
                                    Merah
                                </th>
                                <th style="text-align: center;" class="th-pri bg">
                                    Yellow
                                </th>
                                <th style="width: 15%;text-align: center;" class="th-pri bg">
                                    #
                                </th>
                            </tr>
                            <tr v-for="(item, index) in filteredDetails" :key="index">
                                <th style="text-align: center;" class="th-pri">
                                    <VDatePicker v-model="item.tglPukul" mode="datetime" is24hr>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue" placeholder="Pukul..." v-on="inputEvents"
                                                    disabled />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </th>
                                <th class="th-pri" style="background-color: red;">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.skor_merah" />
                                    </VControl>
                                </th>
                                <th class="th-pri" style="background-color: yellow;">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="item.skor_kuning" />
                                    </VControl>
                                </th>
                                <th class="th-pri">
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
                        </tbody>
                    </table>
                </div>
            </div>

            <hr style="border-top: 1px dashed gray;background-color:white" class="mt-0 mb-1">

            <div class="column is-12 pt-0">
                <div class="column is-12 pb-0" style="overflow:auto;height: 150px;">
                    <table style="border: 1px solid black;">
                        <tbody>
                            <tr>
                                <th style="text-align: center;" class="th-pri bg">
                                    Tanggal & Jam
                                </th>
                                <th style="text-align: center;" class="th-pri bg">
                                    Nama
                                </th>
                                <th style="width: 15%;text-align: center;" class="th-pri bg">
                                    #
                                </th>
                            </tr>
                            <tr v-for="(item, index) in filteredDetails" :key="index">
                                <th style="text-align: center;" class="th-pri">
                                    <VDatePicker v-model="item.tglPukul" mode="datetime" is24hr>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue" placeholder="Pukul..." v-on="inputEvents"
                                                    disabled />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </th>
                                <th class="th-pri">
                                    <VControl class="prime-auto">
                                        <AutoComplete v-model="item.namaperawat" :suggestions="d_Perawat"
                                            @complete="fetchPerawat($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" />
                                    </VControl>
                                </th>
                                <th class="th-pri">
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
                        </tbody>
                    </table>
                </div>
            </div>

            <hr style="border-top: 1px dashed gray;background-color:white" class="mt-0 mb-1">

            <div class="column is-12 pt-0">
                <table style="border: 1px solid black;">
                    <tr>
                        <th style="text-align: center;vertical-align:middle;font-weight: bold;border:1px solid black"
                            colspan="3">
                            RENTANG SKOR TOTAL NEWS</th>
                    </tr>
                    <tr>
                        <th style="width: 33%;text-align: center;background-color:green">PUTIH</th>
                        <th style="width: 33%;text-align: center;background-color:yellow">1 KUNING</th>
                        <th style="width: 33%;text-align: center;background-color:red">2 KUNING DAN 1 MERAH</th>
                    </tr>
                </table>
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
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete'
import moment from 'moment'

useHead({
    title: 'Pemantauan Neonatus Early Warning Score - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const COLLECTION: any = ref('PemantauanNEWS') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const { y } = useWindowScroll()
const route = useRoute()
const isStuck = computed(() => {
    return y.value > 30
})
const input: any = ref({
    DTTanggal: new Date(),
    TJam: new Date(),
    details: [{
        no: 1,
        tglPukul: new Date()
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
        'url_form': route.name,
        'name_form': 'Pemantauan Neonatus Early Warning Score (NEWS)',
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
const isLoading = ref(false)
const dataChart = ref([]);
const chartData = ref();
const chartOptions = ref();
const dataChart2 = ref([]);
const chartData2 = ref();
const dataChart3 = ref([]);
const chartData3 = ref();
const d_Dokter: any = ref([])
const d_Perawat: any = ref([])

const item: any = ref({
  qFilterTgl: {
    start: new Date(), // Set jam 00:00:00.000
    end: new Date() // Set jam 23:59:59.999
  },
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
})


//? Function
// const loadRiwayat = async () => {
//     isLoading.value = true
//     let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
//     if (response.length) {
//         input.value = response[0] //set ke inputan 
//         if (NOREC_EMRPASIEN.value == '') {
//             NOREC_EMRPASIEN.value = response[0].emrpasienfk
//         }

//         dataChart.value = response[0].details
//         chartData.value = setChartData(dataChart.value);
//         chartData2.value = setChartData2(dataChart.value);
//         chartData3.value = setChartData3(dataChart.value);
//         chartOptions.value = setChartOptions();
//         isLoading.value = false
//         H.alert('info', 'Data berhasil dimuat')
//     } else {
//         isLoading.value = false
//     }
// }


function loadSpO2(data: any) {
    if (data > 94) {
        return 'white';
    } else if (data >= 90 && data <= 94) {
        return 'yellow';
    } else if (data < 90) {
        return 'red';
    } else {
        return 'unknown';
    }
}

const loadRiwayat = async () => {
    isLoading.value = true

    const url = `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`;

    let response = await useApi().get(url);
    const ttv = await useApi().get(`emr/get-data-exist-semua?norec_pd=${NOREC_PD}`);
    isLoading.value = false
    if (response.length) {
        console.log("Data ttv yang diterima:", ttv); // Debugging log
        input.value = response[0] //set ke inputan 
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        // Langsung cek apakah ttv adalah array
        if (Array.isArray(ttv)) {
            if (!Array.isArray(input.value.details)) {
                input.value.details = [];
            }
            ttv.forEach((dataItem: any, index: number) => {
                if (!input.value.details[index]) {
                    input.value.details.splice(index, 0, {});
                }
                input.value.details[index].respirasi = dataItem.pernapasan;
                input.value.details[index].nadi = dataItem.nadi;
                input.value.details[index].suhu = dataItem.suhu;
                input.value.details[index].tglPukul = dataItem.tanggal;
                switch (loadSpO2(dataItem.SPO2)) {
                    case 'white':
                        input.value.details[index].warna_putih = dataItem.SPO2;
                        break;
                    case 'yellow':
                        input.value.details[index].warna_kuning = dataItem.SPO2;
                        break;
                    case 'red':
                        input.value.details[index].warna_merah = dataItem.SPO2;
                        break;
                    default:
                        break;
                }
            });
        }
        dataChart.value = response[0].details
        // chartData.value = setChartData(dataChart.value);
        // chartData2.value = setChartData2(dataChart.value);
        // chartData3.value = setChartData3(dataChart.value);
        chartOptions.value = setChartOptions();
        H.alert('info', 'Data berhasil dimuat')
    } else {
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
                input.value.details[index].respirasi = dataItem.pernapasan;
                input.value.details[index].nadi = dataItem.nadi;
                input.value.details[index].suhu = dataItem.suhu;
                input.value.details[index].tglPukul = dataItem.tanggal;
                switch (loadSpO2(dataItem.SPO2)) {
                    case 'white':
                        input.value.details[index].warna_putih = dataItem.SPO2;
                        break;
                    case 'yellow':
                        input.value.details[index].warna_kuning = dataItem.SPO2;
                        break;
                    case 'red':
                        input.value.details[index].warna_merah = dataItem.SPO2;
                        break;
                    default:
                        break;
                }
            })
        }
    }
};
const filteredDetails = computed(() => {
  const start = new Date(item.value.qFilterTgl.start)
  start.setHours(0, 0, 0, 0)

  const end = new Date(item.value.qFilterTgl.end)
  end.setHours(23, 59, 59, 999)

  console.log(input.value.details)

  return input.value.details.filter((detail) => {
    const detailDate = new Date(detail.tglPukul) // konversi string ke Date
    return detailDate >= start && detailDate <= end
  })
})



const fetchPerawat = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => {
        d_Perawat.value = response
    })
}
//? Add New Rows Function
const addNewItem = () => {
    let newItem: any = {}
    newItem = {
        no: input.value.details[input.value.details.length - 1].no + 1,
        tglPukul: new Date(),
    }
    input.value.details.push(newItem);
}
const removeItem = (index: any) => {
    input.value.details.splice(index, 1)
}

// Chart
const setChartData = computed(() => {
  const data = filteredDetails.value
  const documentStyle = getComputedStyle(document.documentElement)

  const labels: string[] = []
  const seriesPernafasan: number[] = []

  for (const element of data) {
    const date = new Date(element.tglPukul)
    const makassarTime = date.toLocaleString('en-ID', { timeZone: 'Asia/Jakarta' })
    labels.push(makassarTime ?? '???')
    seriesPernafasan.push(element.respirasi ? parseFloat(element.respirasi) : 0)
  }

  return {
    labels,
    datasets: [
      {
        label: 'Respirasi',
        data: seriesPernafasan,
        fill: true,
        borderColor: documentStyle.getPropertyValue('--orange-500'),
        tension: 0.4,
        borderDash: [5, 5],
        backgroundColor: 'rgba(255,167,38,0.2)'
      },
    ]
  }
})

const setChartData2 = computed(() => {
    const data = filteredDetails.value
    const documentStyle = getComputedStyle(document.documentElement);
    let labels: string[] = []
    let seriesNadi: number[] = []
    for (const element of data) {
        // const element = data[i]
        const date = new Date(element.tglPukul);
        const makassarTime = date.toLocaleString('en-ID', { timeZone: 'Asia/Jakarta' });
        labels.push(makassarTime ?? '???');
        seriesNadi.push((element.nadi ? parseFloat(element.nadi) : 0))
    }

    return {
        labels: labels,
        datasets: [
            {
                label: 'Nadi',
                data: seriesNadi,
                fill: true,
                borderColor: documentStyle.getPropertyValue('--red-500'),
                tension: 0.4,
                borderDash: [5, 5],
                backgroundColor: 'rgba(255, 159, 169, 0.2)'
            },
        ]
    };
});
const setChartData3 = computed(() => {
    const data = filteredDetails.value
    const documentStyle = getComputedStyle(document.documentElement);
    let labels: string[] = []
    let seriesSuhu: number[] = []
    for (const element of data) {
        // const element = data[i]
        const date = new Date(element.tglPukul);
        const makassarTime = date.toLocaleString('en-ID', { timeZone: 'Asia/Jakarta' });
        labels.push(makassarTime ?? '???');
        seriesSuhu.push((element.suhu ? parseFloat(element.suhu) : 0))
    }

    return {
        labels: labels,
        datasets: [
            {
                label: 'Suhu',
                data: seriesSuhu,
                fill: true,
                borderColor: documentStyle.getPropertyValue('--blue'),
                tension: 0.4,
                borderDash: [5, 5],
                backgroundColor: 'rgba(0, 159, 255, 0.2)'
            },
        ]
    };
});
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

//? Function
loadRiwayat()
</script>
