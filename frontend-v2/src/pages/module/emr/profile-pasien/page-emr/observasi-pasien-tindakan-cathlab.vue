<template>
    <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3> {{ props.FORM_NAME }}</h3>
                    </div>
                    <div class="right">
                        <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                            @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="column is-12">
        <VCard>
            <div class="column is-multiline">
                <div class="column is-4">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Tanggal</h1>
                    <VField>
                        <VDatePicker v-model="input.tanggalObservasi" mode="dateTime" style="width: 100%" trim-weeks
                            :max-date="new Date()">
                            <template #default="{ inputValue, inputEvents }">
                                <VField>
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                    </VControl>
                                </VField>
                            </template>
                        </VDatePicker>
                    </VField>
                </div>
                <TabView>
                    <TabPanel header="A. Observasi Pasien Tindakan Cath Lab">
                        <div class="column is-6">
                            <h1 style="font-weight : bold; margin-bottom: 1rem;"> Lokasi Pungsi / Insisi</h1>
                            <VField>
                                <VControl>
                                    <VInput type="text" v-model="input.lokasiPungsi" placeholder="Lokasi Pungsi" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <h1 style="font-weight : bold;"> Jenis Observasi</h1>
                            <div class="columns is-multiline pt-3 pl-3">
                                <div class="column is-3" v-for="(data, i) in Observasi">
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input[data.model + i]" :true-value="data.title"
                                                :label="data.title" class="p-0" color="primary" square />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>

                        </div>
                        <div class="column is-12" style="overflow:auto;">
                            <table class="tc">
                                <tr>
                                    <th rowspan="2"
                                        style="text-align : center;font-weight: bold; min-width: 50px;position:sticky !important">
                                        No </th>
                                    <th rowspan="2"
                                        style="text-align : center; font-weight: bold;  position:sticky !important;">
                                        Observasi </th>
                                    <th colspan="4" style="text-align : center; font-weight: bold;"> Jam ke 1 </th>
                                    <th colspan="2" style="text-align : center; font-weight: bold;"> Jam ke 2 </th>
                                    <th style="text-align : center; font-weight: bold;"> Jam ke 3 </th>
                                    <th style="text-align : center; font-weight: bold;"> Jam ke 4 </th>
                                    <th style="text-align : center; font-weight: bold;"> Jam ke 5 </th>
                                    <th style="text-align : center; font-weight: bold;"> Jam ke 6 </th>

                                </tr>
                                <tr>
                                    <th>
                                        <VField class="pt-4">
                                            <VControl class="prime-auto">
                                                <Calendar v-model="item.tgl_1" selectionMode="single" :manualInput="false"
                                                    class="w-100" :showIcon="true" showTime hourFormat="24"
                                                    :date-format="'yy-mm-dd'" />
                                            </VControl>
                                        </VField>
                                    </th>
                                    <th>
                                        <VField class="pt-4">
                                            <VControl class="prime-auto">
                                                <Calendar v-model="item.tgl_2" selectionMode="single" :manualInput="false"
                                                    class="w-100" :showIcon="true" showTime hourFormat="24"
                                                    :date-format="'yy-mm-dd'" />
                                            </VControl>
                                        </VField>
                                    </th>
                                    <th>
                                        <VField class="pt-4">
                                            <VControl class="prime-auto">
                                                <Calendar v-model="item.tgl_3" selectionMode="single" :manualInput="false"
                                                    class="w-100" :showIcon="true" showTime hourFormat="24"
                                                    :date-format="'yy-mm-dd'" />
                                            </VControl>
                                        </VField>
                                    </th>
                                    <th>
                                        <VField class="pt-4">
                                            <VControl class="prime-auto">
                                                <Calendar v-model="item.tgl_4" selectionMode="single" :manualInput="false"
                                                    class="w-100" :showIcon="true" showTime hourFormat="24"
                                                    :date-format="'yy-mm-dd'" />
                                            </VControl>
                                        </VField>
                                    </th>
                                    <th>
                                        <VField class="pt-4">
                                            <VControl class="prime-auto">
                                                <Calendar v-model="item.tgl_5" selectionMode="single" :manualInput="false"
                                                    class="w-100" :showIcon="true" showTime hourFormat="24"
                                                    :date-format="'yy-mm-dd'" />
                                            </VControl>
                                        </VField>
                                    </th>
                                    <th>
                                        <VField class="pt-4">
                                            <VControl class="prime-auto">
                                                <Calendar v-model="item.tgl_6" selectionMode="single" :manualInput="false"
                                                    class="w-100" :showIcon="true" showTime hourFormat="24"
                                                    :date-format="'yy-mm-dd'" />
                                            </VControl>
                                        </VField>
                                    </th>
                                    <th>
                                        <VField class="pt-4">
                                            <VControl class="prime-auto">
                                                <Calendar v-model="item.tgl_7" selectionMode="single" :manualInput="false"
                                                    class="w-100" :showIcon="true" showTime hourFormat="24"
                                                    :date-format="'yy-mm-dd'" />
                                            </VControl>
                                        </VField>
                                    </th>
                                    <th>
                                        <VField class="pt-4">
                                            <VControl class="prime-auto">
                                                <Calendar v-model="item.tgl_8" selectionMode="single" :manualInput="false"
                                                    class="w-100" :showIcon="true" showTime hourFormat="24"
                                                    :date-format="'yy-mm-dd'" />
                                            </VControl>
                                        </VField>
                                    </th>
                                    <th>
                                        <VField class="pt-4">
                                            <VControl class="prime-auto">
                                                <Calendar v-model="item.tgl_9" selectionMode="single" :manualInput="false"
                                                    class="w-100" :showIcon="true" showTime hourFormat="24"
                                                    :date-format="'yy-mm-dd'" />
                                            </VControl>
                                        </VField>
                                    </th>
                                    <th>
                                        <VField class="pt-4">
                                            <VControl class="prime-auto">
                                                <Calendar v-model="item.tgl_10" selectionMode="single" :manualInput="false"
                                                    class="w-100" :showIcon="true" showTime hourFormat="24"
                                                    :date-format="'yy-mm-dd'" />
                                            </VControl>
                                        </VField>
                                    </th>



                                </tr>
                                <tr>
                                    <th colspan="10" style="font-weight: bold;"> Tanda - tanda vital </th>
                                </tr>
                                <tr v-for="(datas) in dataTable">
                                    <td style="min-width: 50px; text-align : center; position:sticky !important;">{{
                                        datas.no }}</td>
                                    <td style="position:sticky !important;">{{ datas.Kriteria }}</td>
                                    <td>
                                        <div class="columns is 12">
                                            <div class="column" v-for="(dot) in datas.kolom1">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" v-model="input[dot.model]" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>

                                    </td>
                                    <td>
                                        <div class="columns is-multiline">
                                            <div class="column" v-for="(cek) in datas.kolom2">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" v-model="input[cek.model]" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="columns is-multiline">
                                            <div class="column" v-for="(cik) in datas.kolom3">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" v-model="input[cik.model]" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="columns is-multiline">
                                            <div class="column" v-for="(cok) in datas.kolom4">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" v-model="input[cok.model]" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="columns is-multiline">
                                            <div class="column" v-for="(dok) in datas.kolom5">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" v-model="input[dok.model]" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="columns is-multiline">
                                            <div class="column" v-for="(dik) in datas.kolom6">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" v-model="input[dik.model]" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="columns is-multiline">
                                            <div class="column" v-for="(duk) in datas.kolom7">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" v-model="input[duk.model]" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="columns is-multiline">
                                            <div class="column" v-for="(dek) in datas.kolom8">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" v-model="input[dek.model]" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="columns is-multiline">
                                            <div class="column" v-for="(bi) in datas.kolom9">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" v-model="input[bi.model]" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="columns is-multiline">
                                            <div class="column" v-for="(ci) in datas.kolom10">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" v-model="input[ci.model]" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                                <tr>
                                    <th colspan="10" style="font-weight: bold;"> Lokasi Pungsi </th>
                                </tr>
                                <tr v-for="(datas) in Pungsi">
                                    <td style="min-width: 50px; text-align : center; position:sticky !important;">{{
                                        datas.no }}</td>
                                    <td style="position:sticky !important;">{{ datas.Kriteria }}</td>
                                    <td>
                                        <div class="columns is 12">
                                            <div class="column" v-for="(aa) in datas.kolom1">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" v-model="input[aa.model]" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>

                                    </td>
                                    <td>
                                        <div class="columns is-multiline">
                                            <div class="column" v-for="(bb) in datas.kolom2">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" v-model="input[bb.model]" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="columns is-multiline">
                                            <div class="column" v-for="(cc) in datas.kolom3">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" v-model="input[cc.model]" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="columns is-multiline">
                                            <div class="column" v-for="(dd) in datas.kolom4">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" v-model="input[dd.model]" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="columns is-multiline">
                                            <div class="column" v-for="(ff) in datas.kolom5">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" v-model="input[ff.model]" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="columns is-multiline">
                                            <div class="column" v-for="(gg) in datas.kolom6">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" v-model="input[gg.model]" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="columns is-multiline">
                                            <div class="column" v-for="(hh) in datas.kolom7">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" v-model="input[hh.model]" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="columns is-multiline">
                                            <div class="column" v-for="(kk) in datas.kolom8">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" v-model="input[kk.model]" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="columns is-multiline">
                                            <div class="column" v-for="(ii) in datas.kolom9">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" v-model="input[ii.model]" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="columns is-multiline">
                                            <div class="column" v-for="(mn) in datas.kolom10">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" v-model="input[mn.model]" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                                <tr>
                                    <th colspan="10" style="font-weight: bold;"> Darah distal pungsi</th>
                                </tr>
                                <tr v-for="(datas) in distalPungsi">
                                    <td style="min-width: 50px; text-align : center; position:sticky !important;">{{
                                        datas.no }}</td>
                                    <td style="position:sticky !important;">{{ datas.Kriteria }}</td>
                                    <td>
                                        <div class="columns is 12">
                                            <div class="column" v-for="(ab) in datas.kolom1">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" v-model="input[ab.model]" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>

                                    </td>
                                    <td>
                                        <div class="columns is-multiline">
                                            <div class="column" v-for="(ac) in datas.kolom2">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" v-model="input[ac.model]" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="columns is-multiline">
                                            <div class="column" v-for="(ad) in datas.kolom3">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" v-model="input[ad.model]" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="columns is-multiline">
                                            <div class="column" v-for="(ae) in datas.kolom4">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" v-model="input[ae.model]" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="columns is-multiline">
                                            <div class="column" v-for="(af) in datas.kolom5">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" v-model="input[af.model]" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="columns is-multiline">
                                            <div class="column" v-for="(ag) in datas.kolom6">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" v-model="input[ag.model]" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="columns is-multiline">
                                            <div class="column" v-for="(ah) in datas.kolom7">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" v-model="input[ah.model]" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="columns is-multiline">
                                            <div class="column" v-for="(ak) in datas.kolom8">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" v-model="input[ak.model]" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="columns is-multiline">
                                            <div class="column" v-for="(aj) in datas.kolom9">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" v-model="input[aj.model]" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="columns is-multiline">
                                            <div class="column" v-for="(am) in datas.kolom10">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" v-model="input[am.model]" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                            </table>


                        </div>
                    </TabPanel>
                    <TabPanel header="B. Observasi Pasien Post Tindakan Cath Lab">
                        <div class="column is-12">
                            <h1 style="font-weight : bold;"> Jenis Observasi</h1>
                            <div class="columns is-multiline pt-3 pl-3">
                                <div class="column is-3" v-for="(data, i) in PostObservasi">
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input[data.model + i]" :true-value="data.title"
                                                :label="data.title" class="p-0" color="primary" square />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Radial / Femoral*"
                                                v-model="input.jenisObservasi" />
                                        </VControl>

                                    </VField>
                                </div>

                            </div>

                        </div>
                        <div class="column is-12" style="width: 100%;overflow: auto;">
                            <table class="tg" style="width: 200%;">
                                <tr>
                                    <th rowspan="2"
                                        style="width:150px;position:sticky;left:0;z-index:2;background-color: aliceblue;">
                                        Observasi</th>
                                    <th :colspan="jumlahImdex" class="text-left bg-th" style="width:50px">Tanggal</th>
                                </tr>
                                
                                <tr>

                                    <th class="bg-th" v-for="index in jumlahImdex">
                                        <VDatePicker v-model="input.tanggal[index]" color="green" trim-weeks
                                            :input="'YYYY-MM-DD'" mode="date">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField>
                                                    <VControl icon="feather:calendar">
                                                        <VInput type="text" placeholder="Select a date" :value="inputValue"
                                                            v-on="inputEvents" class="is-rounded_Z input-30"
                                                            style="width:150px" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </th>
                                </tr>
                                <tr>
                                    <td colspan="10"> Tanda Vital</td>
                                </tr>
                                <tr>
                                    <td class="bg-colatas ">Tekanan Darah (BP)</td>
                                  
                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <VControl>
                                            <VInput type="text" v-model="input.tekananDarah[index]" placeholder=""
                                                class="is-rounded_Z input-30" />
                                        </VControl>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-colatas ">Nadi (HR)</td>
                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <VControl>
                                            <VInput type="text" v-model="input.nadi[index]" placeholder=""
                                                class="is-rounded_Z input-30" />
                                        </VControl>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-colatas ">Pernafasan (RR)</td>
                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <VControl>
                                            <VInput type="text" v-model="input.pernapasan[index]" placeholder=""
                                                class="is-rounded_Z input-30" />
                                        </VControl>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-colatas "> Saturasi Oksigen (Sp02)</td>
                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <VControl>
                                            <VInput type="text" v-model="input.saturasi[index]" placeholder=""
                                                class="is-rounded_Z input-30" />
                                        </VControl>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="10"> Lokasi Pungsi </td>
                                </tr>
                                <tr>
                                    <td class="bg-colatas ">Hematoma (Ada / Tidak)</td>
                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <VControl>
                                            <VInput type="text" v-model="input.hematoma[index]" placeholder=""
                                                class="is-rounded_Z input-30" />
                                        </VControl>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-colatas ">Bengkak (Ada / Tidak) tulis diameter jika ada</td>
                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <VControl>
                                            <VInput type="text" v-model="input.bengkak[index]" placeholder=""
                                                class="is-rounded_Z input-30" />
                                        </VControl>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-colatas ">Perdarahan (Ada / Tidak)</td>
                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <VControl>
                                            <VInput type="text" v-model="input.perdarahan[index]" placeholder=""
                                                class="is-rounded_Z input-30" />
                                        </VControl>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-colatas ">Nyeri lokasi pungsi (Skala nyeri, 0 - 10)</td>
                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <VControl>
                                            <VInput type="text" v-model="input.nyeri[index]" placeholder=""
                                                class="is-rounded_Z input-30" />
                                        </VControl>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="10"> Darah distal pungsi </td>
                                </tr>
                                <tr>
                                    <td class="bg-colatas ">Pulsasi (Ada / Tidak)</td>
                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <VControl>
                                            <VInput type="text" v-model="input.pulsasi[index]" placeholder=""
                                                class="is-rounded_Z input-30" />
                                        </VControl>
                                    </td>
                                </tr>
                               
                                <tr>
                                    <th class="bg-colatas">Nama</th>

                                    <td class="tg-0lax">
                                        <VField class="is-autocomplete-select" v-slot="{ id }">
                                            <VControl icon="feather:search">
                                                <AutoComplete v-model="input.penangungJawab1" :suggestions="d_Dokter"
                                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                    :field="'label'" placeholder="ketik nama" />
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td class="tg-0lax">
                                        <VField class="is-autocomplete-select" v-slot="{ id }">
                                            <VControl icon="feather:search">
                                                <AutoComplete v-model="input.penangungJawab2" :suggestions="d_Dokter"
                                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                    :field="'label'" placeholder="ketik nama" />
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td class="tg-0lax">
                                        <VField class="is-autocomplete-select" v-slot="{ id }">
                                            <VControl icon="feather:search">
                                                <AutoComplete v-model="input.penangungJawab3" :suggestions="d_Dokter"
                                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                    :field="'label'" placeholder="ketik nama" />
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td class="tg-0lax">
                                        <VField class="is-autocomplete-select" v-slot="{ id }">
                                            <VControl icon="feather:search">
                                                <AutoComplete v-model="input.penangungJawab4" :suggestions="d_Dokter"
                                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                    :field="'label'" placeholder="ketik nama" />
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td class="tg-0lax">
                                        <VField class="is-autocomplete-select" v-slot="{ id }">
                                            <VControl icon="feather:search">
                                                <AutoComplete v-model="input.penangungJawab5" :suggestions="d_Dokter"
                                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                    :field="'label'" placeholder="ketik nama" />
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td class="tg-0lax">
                                        <VField class="is-autocomplete-select" v-slot="{ id }">
                                            <VControl icon="feather:search">
                                                <AutoComplete v-model="input.penangungJawab6" :suggestions="d_Dokter"
                                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                    :field="'label'" placeholder="ketik nama" />
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td class="tg-0lax">
                                        <VField class="is-autocomplete-select" v-slot="{ id }">
                                            <VControl icon="feather:search">
                                                <AutoComplete v-model="input.penangungJawab7" :suggestions="d_Dokter"
                                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                    :field="'label'" placeholder="ketik nama" />
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td class="tg-0lax">
                                        <VField class="is-autocomplete-select" v-slot="{ id }">
                                            <VControl icon="feather:search">
                                                <AutoComplete v-model="input.penangungJawab8" :suggestions="d_Dokter"
                                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                    :field="'label'" placeholder="ketik nama" />
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td class="tg-0lax">
                                        <VField class="is-autocomplete-select" v-slot="{ id }">
                                            <VControl icon="feather:search">
                                                <AutoComplete v-model="input.penangungJawab9" :suggestions="d_Dokter"
                                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                    :field="'label'" placeholder="ketik nama" />
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                            </table>

                        </div>
                    </TabPanel>
                </TabView>

            </div>
        </VCard>
    </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
// import Fieldset from 'primevue/fieldset';
// import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/observasi-pasien-tindakan-cathlab'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Calendar from 'primevue/calendar';
import AutoComplete from 'primevue/autocomplete';



let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let Observasi = ref(EMR.Observasi())
let PostObservasi = ref(EMR.PostObservasi())
let dataTable = ref(EMR.dataTable())
let Pungsi = ref(EMR.Pungsi())
let distalPungsi = ref(EMR.distalPungsi())

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
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const jumlahImdex = ref(10)
const d_Dokter: any = ref([])
const input: any = ref({
    tanggalObservasi: new Date(),
    nadi: [],
    pernapasan: [],
    tanggal: [],
    tekananDarah: [],
    saturasi: [],
    hematoma: [],
    bengkak: [],
    perdarahan: [],
    nyeri: [],
    pulsasi: [],

    totalSkor: [],

    detailsTglSkor: [{
        no: 1,
        tgl: new Date(),
    }]
})
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}
const fetchDokter = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Dokter.value = response
    })
}
const loadRiwayat = () => {
    // if (NOREC_EMRPASIEN.value == '') return
    useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
            if (response.length) {
                input.value = response[0] //set ke inputan
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                }
            }
        })
}

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
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const kembaliKeun = () => {
    window.history.back()
}
const setAutoFill = async () => {

}
setView()
setAutoFill()
loadRiwayat()
</script>
<style lang="scss">
.skuy.p-tabview .p-tabview-panels {
    background: #ffffff;
    padding: 1rem;
    border: 0 none;
    color: red;
    border-bottom-right-radius: 3px;
    border-bottom-left-radius: 3px;
}

.tc {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100% !important;
    overflow-x: scroll;
}

.tc td {
    border-color: var(--fade-grey-dark-2);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
    min-width: 100px;
}

.tc th {
    border-color: var(--fade-grey-dark-3);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: normal;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
    min-width: 200px;
    position: sticky;
}

.tc .tg-0lax {
    text-align: left;
    vertical-align: top
}

.sticky-col {
    position: -webkit-sticky;
    position: sticky;
    background-color: white;
}

.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
}

.tg td {
    border-color: var(--fade-grey-dark-2);
    border-style: solid;
    border-width: 1px;

    // font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg tr {
    height: 20px;
}

.tg th {
    border-color: var(--fade-grey-dark-3);
    border-style: solid;
    border-width: 1px;
    vertical-align: middle;
    // font-size: 14px;
    font-weight: bold;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg .tg-0lax {
    text-align: left;
    vertical-align: top
}

.input-30 {
    height: 30px;
}

.bg-colatas {
    position: sticky;
    background-color: aliceblue;
    left: 0;
    z-index: 2;
}

.bg-colatas2 {
    position: sticky;
    background-color: aliceblue;
    left: 150px;
    z-index: 2;
}

.bg-colatas3 {

    background-color: aliceblue;
    left: 0px;
    z-index: 2;
}


.bg-col {
    position: sticky;
    background-color: aliceblue;
    left: 0;
    z-index: 2;
}

.bg-col2 {
    position: sticky;
    background-color: aliceblue;
    left: 57px;
    z-index: 2;
}

.bg-col3 {
    position: sticky;
    background-color: aliceblue;
    left: 357px;
    z-index: 2;
}



.bg-th {
    text-align: center;
    background-color: #dedfe2d3;
}
</style>
