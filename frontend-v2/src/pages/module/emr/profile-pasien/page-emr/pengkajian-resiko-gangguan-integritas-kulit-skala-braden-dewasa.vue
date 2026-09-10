<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header" style="margin-bottom: 10px">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Pengkajian Resiko Gangguan Integritas Kulit Skala Braden Pasien Dewasa</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate"
                                @kembaliKeun="kembaliKeun" :isHideCetak="true">
                            </ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
                    <VButton type="button" rounded outlined color="primary" raised icon="feather:folder"
                        isLoading="false" @click="pilihTemplateFix(index)"> Pilih Template
                    </VButton>
                    <VButton type="button" rounded outlined color="info" raised icon="feather:file-text"
                        isLoading="false" @click="pilihTemplate(index)"> Pilih Riwayat
                    </VButton>
                </div>

                <hr>

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

                <hr>

                <div class="columns is-multiline">
                    <div class="column is-12 is-flex">
                        <div class="column is-12">
                            <h1>Pengkajian dilakukan saat :</h1>
                        </div>
                    </div>

                    <div class="column is-12 is-flex ml-5">
                        <div class="column is-12">
                            <ul style="list-style-type: disc;">
                            <li>Initial assessment dilakukan pertama kali di ruang rawat inap.</li>
                            <li>Pengkajian ulang (re-assessment) dilakukan setiap 48 jam atau perubahan kondisi pasien.</li>
                        </ul>
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
                    <!-- <div class="column is-8 pt-0 pb-0"></div> -->
                    <div class="column is-12 pr-0">
                        <div style="overflow-x: auto;">
                            <table style="width: 100%;border-collapse: collapse;border:1px solid black;" border="1">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="background-color: gainsboro; position: sticky">
                                            No
                                        </th>
                                        <th style="background-color: gainsboro; position: sticky">
                                            <b>DIMENSI</b>
                                        </th>
                                        <th :colspan="input.details.length" style="height: 2.7rem;background-color: gainsboro;text-align: center;">
                                            <b>SKOR PENGKAJIAN</b>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="1" style="height: 3.7rem; border-bottom: 1px solid black; width: 150px;" class="center">
                                            Tanggal & Jam
                                        </th>
                                        <th v-for="(item, index) in input.details" :key="index">
                                            <VDatePicker v-model="item.tanggal" mode="datetime" is24hr>
                                                <template #default="{ inputValue, inputEvents }">
                                                    <VControl icon="feather:calendar" fullwidth>
                                                        <VInput :value="inputValue" v-on="inputEvents" />
                                                    </VControl>
                                                </template>
                                            </VDatePicker>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="height: 3.7rem;" class="center">
                                            1
                                        </td>
                                        <td class="center">
                                            SENSORI PERSEPSI
                                        </td>
                                        <td class="center" style="height: 3.7rem" v-for="(item, index) in input.details" :key="index">
                                            <VControl>
                                                <VInput type="number" class="input" placeholder=""
                                                    v-model="item.sensori" />
                                            </VControl>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="height: 3.7rem;" class="center">
                                            2
                                        </td>
                                        <td class="center">
                                            KELEMBABAN KULIT
                                        </td>
                                        <td class="center" style="height: 3.7rem" v-for="(item, index) in input.details" :key="index">
                                            <VControl>
                                                <VInput type="number" class="input" placeholder=""
                                                    v-model="item.kelembaban" />
                                            </VControl>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="height: 3.7rem;" class="center">
                                            3
                                        </td>
                                        <td class="center">
                                            AKTIVITAS
                                        </td>
                                        <td class="center" style="height: 3.7rem" v-for="(item, index) in input.details" :key="index">
                                            <VControl>
                                                <VInput type="number" class="input" placeholder=""
                                                    v-model="item.aktivitas" />
                                            </VControl>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="height: 3.7rem;" class="center">
                                            4
                                        </td>
                                        <td class="center">
                                            MOBILISASI
                                        </td>
                                        <td class="center" style="height: 3.7rem" v-for="(item, index) in input.details" :key="index">
                                            <VControl>
                                                <VInput type="number" class="input" placeholder=""
                                                    v-model="item.mobilisasi" />
                                            </VControl>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="height: 3.7rem;" class="center">
                                            5
                                        </td>
                                        <td class="center">
                                            STATUS NUTRISI
                                        </td>
                                        <td class="center" style="height: 3.7rem" v-for="(item, index) in input.details" :key="index">
                                            <VControl>
                                                <VInput type="number" class="input" placeholder=""
                                                    v-model="item.statusnutrisi" />
                                            </VControl>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="height: 3.7rem;" class="center">
                                            6
                                        </td>
                                        <td class="center">
                                            PERGESEKAN KULIT
                                        </td>
                                        <td class="center" style="height: 3.7rem" v-for="(item, index) in input.details" :key="index">
                                            <VControl>
                                                <VInput type="number" class="input" placeholder=""
                                                    v-model="item.pergesekankulit" />
                                            </VControl>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="height: 3.7rem;" class="center">
                                            TOTAL SKOR
                                        </td>
                                        <td class="center" style="height: 3.7rem" v-for="(item, index) in input.details" :key="index">
                                            <VControl>
                                                <VInput type="text" class="input" placeholder=""
                                                    v-model="item.totalskor" disabled/>
                                            </VControl>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="height: 3.7rem;" class="center">
                                            PARAF/NAMA TERANG
                                        </td>
                                        <td class="center" style="height: 3.7rem" v-for="(item, index) in input.details" :key="index">
                                            <div class="column is-12">
                                                <VField>
                                                    <VControl>
                                                        <TandaTangan :elemenID="`parafPerawat_${index}`" :width="'150'" :height="'150'" class="dek" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-12">
                                                <VField>
                                                    <VControl>
                                                    <AutoComplete v-model="item.parafPerawatNama" :suggestions="d_pegawai" @complete="fetchPegawai($event)"
                                                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari..." />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="columns is-multiline">


                    <div class="column is-12">
                        <div style="overflow-x:auto;" class="mt-1">
                            <table style="width: 100%;border-collapse: collapse;border:1px solid black;" border="1">
                                <tr>
                                    <td rowspan="2" class="has-text-centered"><b>No</b></td>
                                    <td rowspan="2" class="has-text-centered"><b>Sub Skala</b></td>
                                    <td colspan="4" class="has-text-centered"><b>SKALA</b></td>
                                </tr>
                                <tr>
                                    <td class="has-text-centered"><b>1</b></td>
                                    <td class="has-text-centered"><b>2</b></td>
                                    <td class="has-text-centered"><b>3</b></td>
                                    <td class="has-text-centered"><b>4</b></td>
                                </tr>
                                <tr>
                                    <td class="has-text-centered"><b>1</b></td>
                                    <td>
                                        <div>
                                            <span><b>SENSORI PERSEPSI</b></span><br>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span><b>Keterbatasan Total</b></span><br>
                                            <span>Tidak ada respon saat ada stimulasi nyeri,
                                                mengalami penurunan kesadaran atau
                                                pengaruh obat
                                            </span><br>
                                            <span><b>ATAU</b></span><br>
                                            <span>Keterbatasan merespon nyeri pada sebagian
                                                besar bagian tubuh
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span><b>Sangat Terbatas</b></span><br>
                                            <span>Hanya merespon nyeri tidak bisa menyatakan
                                                ketidaknyamanan
                                            </span><br>
                                            <span><b>ATAU</b></span><br>
                                            <span>Memiliki keterbatasan sensori yang
                                                membatasi kemampuan untuk merasakan
                                                nyeri dan ketidaknyamanan lebih dari
                                                setengah bagian tubuh
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div>
                                            <span><b>Agak Terbatas</b></span><br>
                                            <span>Dapat merespon perintah. Tetapi tidak bisa
                                                selalu menyatakan ketidaknyamanan
                                            </span><br>
                                            <span><b>ATAU</b></span><br>
                                            <span>Memiliki beberapa keterbatasan sensori
                                                dengan keterbatasan untuk merasakan nyeri
                                                atau ketidaknyamanan pada 1 atau 2
                                                ekstremitas
                                            </span>
                                        </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span><b>Tidak Ada Kelemahan</b></span><br>
                                            <span>Meresponperintah, tidak punya
                                                keterbatasan sensori, memiliki perasaan
                                                yang baik dalam merespon nyeri, suara
                                                dan ketidaknyamanan
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="has-text-centered"><b>2</b></td>
                                    <td>
                                        <div>
                                            <span><b>KELEMBABAN KULIT</b></span><br>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span><b>Selalu Lembab</b></span><br>
                                            <span>Kulit pasien selalu dalam keadaan lembab/
                                                basah karena keringat urine dsb .
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span><b>Sering Lembab</b></span><br>
                                            <span>Kulit sering lembab namun tidak selalu.
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span><b>Kadang-kadang Lembab</b></span><br>
                                            <span>Kulit kadang-kadang lembab, memerlukan
                                                penggantian linen ekstra
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span><b>Jarang Lembab</b></span><br>
                                            <span>Kulit selalu dalam keadaan kering
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="has-text-centered"><b>3</b></td>
                                    <td>
                                        <div>
                                            <span><b>AKTIVITAS</b></span><br>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span><b>Bedrest</b></span><br>
                                            <span>Terbatas di tempat tidur</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span><b>Bisa Duduk</b></span><br>
                                            <span>Kemampuan berjalan sangat terbatas atau
                                                tidak bisa sama sekali. Tidak bisa menahan
                                                berat badan sendiri
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span><b>Kadang-kadang Jalan</b></span><br>
                                            <span>Kadang-kadang dapat berjalan, namun
                                                dalam jarak yang dekat dengan atau tanpa
                                                bantuan.
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span><b>Sering Jalan</b></span><br>
                                            <span>Dapat berjalan-jalan tanpa mengalami hambatan
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="has-text-centered"><b>4</b></td>
                                    <td>
                                        <div>
                                            <span><b>MOBILISASI</b></span><br>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span><b>Immobilisasi Total</b></span><br>
                                            <span>Tidak dapat menggerakkan tubuh sedikitpun <br>
                                                tanpa bantuan
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span><b>Sangat Terbatas</b></span><br>
                                            <span>Kadang-kadang dapat menggerakkan tubuh<br>
                                                namun tidak dapat terlalu sering
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span><b>Agak Terbatas</b></span><br>
                                            <span>Dapat sedikit menggerakkan tubuh dengan<br>
                                                bebas
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span><b>Tidak ada batasan</b></span><br>
                                            <span>Dapat melakukan gerakkan dengan bebas<br>
                                                tanpa bantuan
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="has-text-centered"><b>5</b></td>
                                    <td>
                                        <div>
                                            <span><b>STATUS NUTRISI</b></span><br>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span><b>Sangat Kurang</b></span><br>
                                            <span>tidak dapat menghabiskan makanan yang diberikan, jarang menghabiskan lebih dari 1/3 porsi, kurang minum
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span><b>Mungkin Tidak Cukup</b></span><br>
                                            <span>Jarang dapat menghabiskan makanan yang
                                                diberikan, pada umumnya dapat menghabiskan setengah porsi
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span><b>Cukup</b></span><br>
                                            <span>Makan lebih dari setengah porsi.
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span><b>Sangat Baik</b></span><br>
                                            <span>
                                                Tidak pernah menolak makanan yang
                                                diberikan. Dapat makan berbagai jenis makanan
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="has-text-centered"><b>6</b></td>
                                    <td>
                                        <div>
                                            <span><b>PERGESEKAN</b></span><br>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span><b>Bermasalah</b></span><br>
                                            <span>Memerlukan bantuan maksimal dalam memindahkan pasien, tidak dapat menghindari gesekan dengan alas
                                                tempat tidur saat mengubah posisi, sering melorot di temoat tidur, atau kursi.
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span><b>Potensi Ada Masalah</b></span><br>
                                            <span>Dapat berpindah sendiri dengan kelemahan
                                                atau dengan bantuan minimal. Selama
                                                perpindahan kulit mungkin mengalami
                                                gesekan. Dapat dalam posisi baik selama di
                                                atas tempat tidur atau kursi namun kadang-
                                                kadang melorot
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span><b>Tidak Ada Masalah</b></span><br>
                                            <span>Dapat berpindah di tempat tidur atau kursi
                                                dengan bebas, dapat mengangkat beban
                                                tubuh dengan baik selama berpindah.
                                                Selalu dalam posisi baik di tempat tidur dan
                                                kursi
                                            </span>
                                        </div>
                                    </td>
                                    <td>

                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="column is-12 is-flex ml-5">
                        <div class="column is-12">
                            <h1><b>Manajemen Penatalaksanaan Gangguan Integritas Kulit</b></h1>
                        </div>

                    </div>
                    <div class="column is-12">
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
                            <div class="column is-12 pr-0">
                                <div style="overflow: auto;">
                                    <table style="width: 100%;border-collapse: collapse;border:1px solid black;" border="1">
                                        <thead>
                                            <tr>
                                                <th class="sticky-col" rowspan="2" style="background-color: gainsboro; width: 100px;">
                                                    <b>Derajat Risiko</b>
                                                </th>
                                                <th class="sticky-col-2 center" rowspan="2" style="background-color: gainsboro; width: 500px; text-align: center;">
                                                    <b>Penatalaksanaan</b>
                                                </th>
                                                <th :colspan="input.details.length" style="border-bottom: 1px solid black; width: 170px; background-color: gainsboro; text-align: center;" class="center">
                                                    Tanggal
                                                </th>
                                            </tr>
                                            <tr>
                                                <th v-for="(item, index) in input.details" :key="index">
                                                    <VDatePicker v-model="item.tanggalderajat" mode="datetime" is24hr>
                                                        <template #default="{ inputValue, inputEvents }">
                                                            <VControl icon="feather:calendar" fullwidth>
                                                                <VInput :value="inputValue" v-on="inputEvents" style="width: 170px;"/>
                                                            </VControl>
                                                        </template>
                                                    </VDatePicker>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="height: 3.7rem;" class="center sticky-col" rowspan="5" >
                                                    18-15 (risiko rendah)
                                                </td>
                                                <td class="center sticky-col-2">
                                                    1. Inspeksi kulit pada area tertekan minimal 2X sehari
                                                </td>
                                                <td class="center" v-for="(item, index) in input.details" :key="index">
                                                    <div style="text-align: center; margin-top: 5px">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox
                                                                class="p-0"
                                                                color="primary"
                                                                square
                                                                :true-value="risikorendah1"
                                                                label=""
                                                                v-model="item.risikorendah1"
                                                            />
                                                        </VControl>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="center sticky-col-2">
                                                    2. Sarankan pemberian nutrisi yang adekuat
                                                </td>
                                                <td class="center" v-for="(item, index) in input.details" :key="index">
                                                    <div style="text-align: center; margin-top: 5px;">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox
                                                                class="p-0"
                                                                color="primary"
                                                                square
                                                                :true-value="risikorendah2"
                                                                label=""
                                                                v-model="item.risikorendah2"
                                                            />
                                                        </VControl>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="center sticky-col-2">
                                                    3. Berikan mobilisasi semaksimal mungkin
                                                </td>
                                                <td class="center" v-for="(item, index) in input.details" :key="index">
                                                    <div style="text-align: center; margin-top: 5px">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox
                                                                class="p-0"
                                                                color="primary"
                                                                square
                                                                :true-value="risikorendah3"
                                                                label=""
                                                                v-model="item.risikorendah3"
                                                            />
                                                        </VControl>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="center sticky-col-2">
                                                    4. Bantu dan lakukan perubahan posisi setiap 4 jam
                                                </td>
                                                <td class="center" v-for="(item, index) in input.details" :key="index">
                                                    <div style="text-align: center; margin-top: 5px">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox
                                                                class="p-0"
                                                                color="primary"
                                                                square
                                                                :true-value="risikorendah4"
                                                                label=""
                                                                v-model="item.risikorendah4"
                                                            />
                                                        </VControl>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="center sticky-col-2">
                                                    5. Elevasi tempat tidur tidak lebih dari 30<sup>o</sup>
                                                </td>
                                                <td class="center" v-for="(item, index) in input.details" :key="index">
                                                    <div style="text-align: center; margin-top: 5px">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox
                                                                class="p-0"
                                                                color="primary"
                                                                square
                                                                :true-value="risikorendah5"
                                                                label=""
                                                                v-model="item.risikorendah5"
                                                            />
                                                        </VControl>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="height: 3.7rem;" class="center sticky-col" rowspan="4">
                                                    13-14 (risiko sedang)
                                                </td>
                                                <td class="center sticky-col-2">
                                                    1. Inspeksi kulit pada area tertekan setiap melakukan perubahan posisi
                                                </td>
                                                <td class="center" v-for="(item, index) in input.details" :key="index">
                                                    <div style="text-align: center; margin-top: 5px">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox
                                                                class="p-0"
                                                                color="primary"
                                                                square
                                                                :true-value="risikorendah100"
                                                                label=""
                                                                v-model="item.risikorendah100"
                                                            />
                                                        </VControl>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="center sticky-col-2">
                                                    2. Lakukan perubahan posisi sedikitnya setiap 4 jam, elevasi tempat tidur tidak lebih dari 30 <sup>o</sup>
                                                </td>
                                                <td class="center" v-for="(item, index) in input.details" :key="index">
                                                    <div style="text-align: center; margin-top: 5px">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox
                                                                class="p-0"
                                                                color="primary"
                                                                square
                                                                :true-value="risikorendah51"
                                                                label=""
                                                                v-model="item.risikorendah51"
                                                            />
                                                        </VControl>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="center sticky-col-2">
                                                3. Konsulkan dengan ahli gizi untuk pemberian nutrisi yang adekuat sesuai yang direkomendasikan
                                                </td>
                                                <td class="center" v-for="(item, index) in input.details" :key="index">
                                                    <div style="text-align: center; margin-top: 5px">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox
                                                                class="p-0"
                                                                color="primary"
                                                                square
                                                                :true-value="risikorendah52"
                                                                label=""
                                                                v-model="item.risikorendah52"
                                                            />
                                                        </VControl>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="center sticky-col-2">
                                                4. Amankan tumit/area-area tertekan dengan memasang bantalan air
                                                </td>
                                                <td class="center" v-for="(item, index) in input.details" :key="index">
                                                    <div style="text-align: center; margin-top: 5px">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox
                                                                class="p-0"
                                                                color="primary"
                                                                square
                                                                :true-value="risikorendah53"
                                                                label=""
                                                                v-model="item.risikorendah53"
                                                            />
                                                        </VControl>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="height: 3.7rem;" class="center sticky-col" rowspan="4">
                                                    ≤9 (risiko sangat tinggi)
                                                </td>
                                                <td class="center sticky-col-2">
                                                    1. Inspeksi kulit pada area tertekan setiap jam
                                                </td>
                                                <td class="center" v-for="(item, index) in input.details" :key="index">
                                                    <div style="text-align: center; margin-top: 5px">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox
                                                                class="p-0"
                                                                color="primary"
                                                                square
                                                                :true-value="risikorendah1000"
                                                                label=""
                                                                v-model="item.risikorendah1000"
                                                            />
                                                        </VControl>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="center sticky-col-2">
                                                2. Konsulkan dengan ahli gizi untuk pemberian nutrisi yang adekuat sesuai yang direkomendasikan
                                                </td>
                                                <td class="center" v-for="(item, index) in input.details" :key="index">
                                                    <div style="text-align: center; margin-top: 5px">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox
                                                                class="p-0"
                                                                color="primary"
                                                                square
                                                                :true-value="risikorendah54"
                                                                label=""
                                                                v-model="item.risikorendah54"
                                                            />
                                                        </VControl>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="center sticky-col-2">
                                                3. Buat jadwal tertulis dan lakukan perubahan posisi sedikitnya setiap 2 jam, elevasi tempat tidur tidak lebih dari 30 <sup>o</sup>
                                                </td>
                                                <td class="center" v-for="(item, index) in input.details" :key="index">
                                                    <div style="text-align: center; margin-top: 5px">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox
                                                                class="p-0"
                                                                color="primary"
                                                                square
                                                                :true-value="risikorendah55"
                                                                label=""
                                                                v-model="item.risikorendah55"
                                                            />
                                                        </VControl>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="center sticky-col-2">
                                                4. Amankan tumit/area-area tertekan dengan memasang bantalan air
                                                </td>
                                                <td class="center" v-for="(item, index) in input.details" :key="index">
                                                    <div style="text-align: center; margin-top: 5px">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox
                                                                class="p-0"
                                                                color="primary"
                                                                square
                                                                :true-value="risikorendah56"
                                                                label=""
                                                                v-model="item.risikorendah56"
                                                            />
                                                        </VControl>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="sticky-col pl-3" colspan="2">
                                                <b>Paraf</b>
                                                </td>
                                                <td class="center" v-for="(item, index) in input.details" :key="index">
                                                    <div class="column is-12">
                                                        <VField>
                                                            <VControl>
                                                                <TandaTangan :elemenID="`parafPegawai_${index}`" :width="'150'" :height="'150'" class="dek" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="sticky-col pl-3" colspan="2">
                                                <b>Nama Terang</b>
                                                </td>
                                                <td class="center" v-for="(item, index) in input.details" :key="index">
                                                    <div class="column is-12">
                                                        <VField>
                                                            <VControl>
                                                            <AutoComplete v-model="item.parafPegawaiNama" :suggestions="d_pegawai" @complete="fetchPegawai($event)"
                                                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari..." />
                                                            </VControl>
                                                        </VField>
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

</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount, watchEffect, nextTick } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'


// Judul
useHead({
    title: 'Pengkajian Resiko Gangguan Integritas Kulit Skala Braden Anak-Anak - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const d_keadaanumum: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Sedang' }, { value: 3, label: 'Buruk' }])
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
const fetchPetugas = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&limit=10`).then((response) => {
        d_Petugas.value = response
    })
}
const dataTTD: any = ref([])
const route = useRoute()
const pasien: any = ref({})
const d_Petugas: any = ref([])
const loadData: any = ref(true)
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
    filter: '',
    airway: [],
    disability: []
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const { y } = useWindowScroll()
const router = useRouter()
const isStuck = computed(() => {
    return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const alertMid = ref(false);
const d_pegawai: any = ref([])
const dataDetailTTD = ref([])
const nilaiKajian:number = 0;
const input: any = ref({
  details: [{
    no: 1,
    mobilisasi: 0,
    aktivitas: 0,
    sensori: 0,
    kelembapan: 0,
    pergesekankulit: 0,
    statusnutrisi: 0,
    oksigenasi: 0,
    totalskor: 0,
  }]
})

const fetchPegawai = async (filter: any) => {

await useApi().get(
  `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
).then((response) => {
  d_pegawai.value = response
})
}

const loadRiwayat = async () => {
    isLoading.value = true
    let histori = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    if (histori.length) {
        input.value = histori[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = histori[0].emrpasienfk
        }
        dataTTD.value = histori[0]
        dataDetailTTD.value = histori[0].details
        await nextTick(() => {
        dataDetailTTD.value.forEach((item2, index2) => {
          if (!item2.parafPerawat) {
            H.tandaTangan().set(`parafPerawat_${index2}`, item2.parafPerawat);
          }else{
            H.tandaTangan().set(`parafPerawat_${index2}`, item2.parafPerawat);
          }
          if (!item2.parafPegawai) {
            H.tandaTangan().set(`parafPegawai_${index2}`, item2.parafPegawai);
          }else{
            H.tandaTangan().set(`parafPegawai_${index2}`, item2.parafPegawai);
          }
        });
      })
    }
    isLoading.value = false
}
const simpan = () => {

    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    // object['ttdPegawai'] = H.tandaTangan().get("ttdPegawai");
    // object['TTDDokterPemeriksa'] = H.tandaTangan().get("TTDDokterPemeriksa");
    object.nocm = pasien.value.nocm

    object.pasien = H.setObjectPasien(pasien.value)
    object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
    let pushData: any = []
    if (object.details.length >= 0) {
        object.details.forEach((element: any, i: any) => {
        const parafPerawat = H.tandaTangan().get(`parafPerawat_${i}`);
        const parafPegawai = H.tandaTangan().get(`parafPegawai_${i}`);
        element.parafPerawat = parafPerawat
        element.parafPegawai = parafPegawai
        })
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
            NOREC_EMRPASIEN.value = response.norec_emr
        }).catch((e: any) => {
            isLoading.value = false
        })
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

const setRoutingEMR = (form: any, norec_emr: any) => {

let query: any = {}
let params: any = {}
console.log("DATA ITEM", item);

if (NOREC_EMRPASIEN.value != '') {
  query = {
    nocmfk: pasien.value.nocmfk,
    norec_pasien_daftar: item.NOREC_PD,
    norec_pd: item.NOREC_PD,
    norec_apd: item.NOREC_APD,
    jenisobgyn: '',
    norec_emr: NOREC_EMRPASIEN.value,
  }
} else {
  query = {
    nocmfk: pasien.value.nocmfk,
    norec_pasien_daftar: item.NOREC_PD,
    norec_pd: item.NOREC_PD,
    norec_apd: item.NOREC_APD,
    jenisobgyn: '',
  }
}

console.log(query)
if (form.indexOf('index_tab') > -1) {
  params = {
    index_tabs: 1
  }
}
router.push({
  name: form,
  query: query,
  params: params
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

const addTemplate = (response: any) => {
    console.log(response)
    input.value = response //set ke inputan
    input.value.namatemplate = null
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
    console.log(norec_emr)
}

const addNewItem = () => {
  input.value.details.push({
    no: input.value.details[input.value.details.length - 1].no + 1,
  });
}
const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
}

watchEffect(() => {
  if (!input.value?.details?.length) return; // Pastikan details ada dan memiliki elemen

  input.value.details.forEach((item) => {
    let total =
      parseFloat(item.mobilisasi || 0) +
      parseFloat(item.aktivitas || 0) +
      parseFloat(item.sensori || 0) +
      parseFloat(item.kelembaban || 0) +
      parseFloat(item.pergesekankulit || 0) +
      parseFloat(item.statusnutrisi || 0) +
      parseFloat(item.oksigenasi || 0);

    item.totalskor = total;
  });
});


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



// watch(
//     () => Object.keys(input.value).filter(key => key.startsWith('checkboxSN_')).map(key => input.value[key]),
//     (newValues) => {
//         let sum = 0
//         newValues.forEach((checkboxValue, index) => {
//             const [_, rowIndex, itemIndex] = Object.keys(input.value).filter(key => key.startsWith('checkboxSN_'))[index].split('_')
//             const nilaiRow = detailSkriningNutrisi.value[parseInt(rowIndex)]?.child?.[parseInt(itemIndex) + 1]
//             if (checkboxValue && nilaiRow && !isNaN(parseInt(nilaiRow.caption))) {
//                 sum += parseInt(nilaiRow.caption)
//             }
//         })
//         input.value.jumlahNilaiSN = sum
//     },
//     { deep: true }
// )

fetchPasien();

</script>

<style lang="scss">
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
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg2 th {
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: normal;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
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
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: normal;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg .tg-0lax {
    text-align: left;
    vertical-align: middle
}

hr {
    background-color: hsl(0deg 6.81% 88.68%);
    border: none;
    display: block;
    height: 2px;
    margin: 0px;
}
.alert-nobg {
    background: none !important;
    height: 5em;
    width: 5em;

}
.table {

width: 100% !important;
border: 1px solid black !important;
}

table tbody td.center {
    // text-align: center;
    padding: 7px;
    font-weight: bold;
}

.sticky-col {
    background-color: aliceblue;
    position: sticky;
    left: 0;
    z-index: 2;
    vertical-align: inherit;
}
.sticky-col-2 {
    background-color: aliceblue;
    position: sticky;
    left: 60px;
    z-index: 2;
    vertical-align: inherit;
}

</style>
