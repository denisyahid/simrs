<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header" style="margin-bottom: 10px">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Pengkajian Resiko Gangguan Integritas Kulit Skala Braden Anak-Anak</h3>
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
                            <li>Pengkajian ulang (re-assesment) dilakukan setiap minggu atau bila ada perubahan kondisi yang signifikan dan post operasi.</li>
                            <li>Semua kejadian dekubitus harus dilaporkan baik yang dibawa dari rumah ataupun didapat di rumah sakit.</li>
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
                    <div class="column is-8 pt-0 pb-0"></div>
                    <div class="column is-4 pr-0">
                        <table style="width: 100%;border-collapse: collapse;border:1px solid black;" border="1">
                            <tr>
                                <th rowspan="2" style="background-color: gainsboro;">NO</th>
                                <th style="background-color: gainsboro;"><b>DIMENSI</b></th>
                            </tr>
                            <tr>
                                <th style="height: 3.7rem; border-bottom: 1px solid black;" class="center">
                                    Tanggal & Jam</th>
                            </tr>
                            <tr>
                                <th style="height: 3.7rem;" class="center">1</th>
                                <th class="center">MOBILISASI</th>
                            </tr>
                            <tr>
                                <th style="height: 3.9rem;" class="center">2</th>
                                <th class="center">AKTIVITAS</th>
                            </tr>
                            <tr>
                                <th style="height: 3.7rem;" class="center">3</th>
                                <th class="center">SENSORI PERSEPSI</th>
                            </tr>
                            <tr>
                                <th style="height: 3.7rem;" class="center">4</th>
                                <th class="center">KELEMBABAN KULIT</th>
                            </tr>
                            <tr>
                                <th style="height: 3.9rem;" class="center">5</th>
                                <th class="center">PERGESEKAN KULIT</th>
                            </tr>
                            <tr>
                                <th style="height: 3.7rem;" class="center">6</th>
                                <th class="center">STATUS NUTRISI</th>
                            </tr>
                            <tr>
                                <th style="height: 3.7rem;" class="center">7</th>
                                <th class="center">OKSIGENASI DAN PERFUSI JARINGAN</th>
                            </tr>
                            <tr>
                                <th style="height: 3.7rem;" colspan="2" class="center" ><b>TOTAL SKOR</b></th>
                            </tr>
                            <tr>
                                <th  style="height: 20.8rem;border-right:1px solid black;border-top:1px solid black;"
                                    class="center" colspan="2">PARAF/NAMA TERANG
                                </th>
                            </tr>
                        </table>
                    </div>
                    <div class="column is-8 pl-0" style="overflow: auto;">
                        <table>
                            <tr>
                                <td v-for="(item, index) in input.details" :key="index">
                                    <table class="table">
                                        <tr>
                                            <td style="height: 2.7rem;background-color: gainsboro;"></td>
                                        </tr>
                                        <tr>
                                            <td style="height: 3.7rem">
                                                <VDatePicker v-model="item.tanggal" mode="datetime" is24hr>
                                                    <template #default="{ inputValue, inputEvents }">
                                                        <VControl icon="feather:calendar" fullwidth>
                                                            <VInput :value="inputValue" v-on="inputEvents" />
                                                        </VControl>
                                                    </template>
                                                </VDatePicker>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="height: 2.2rem">
                                                <VControl>
                                                    <VInput type="number" class="input" placeholder=""
                                                        v-model="item.mobilisasi" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="height: 2.8rem">
                                                <VControl>
                                                    <VInput type="number" class="input" placeholder=""
                                                        v-model="item.aktivitas" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl>
                                                    <VInput type="number" class="input" placeholder=""
                                                        v-model="item.sensori" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl>
                                                    <VInput type="number" class="input" placeholder=""
                                                        v-model="item.kelembapan" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl>
                                                    <VInput type="number" class="input" placeholder=""
                                                        v-model="item.pergesekankulit" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <VControl>
                                                    <VInput type="number" class="input" placeholder=""
                                                        v-model="item.statusnutrisi" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="height: 4.2rem;">
                                                <VControl>
                                                    <VInput type="number" class="input" placeholder=""
                                                        v-model="item.oksigenasi" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: white !important;">
                                                <VControl>
                                                    <VInput type="text" class="input" placeholder=""
                                                        v-model="item.totalskor" />
                                                </VControl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
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
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="columns is-multiline">
                    <div class="column is-12 is-flex">
                        <div class="column is-12">
                            <h1><b>PENCEGAHAN :</b></h1>
                        </div>
                    </div>

                    <div class="column is-12 is-flex ml-5">
                        <div class="column is-12">
                            <h1><b>1. Cegah pergesekan kulit dari permukaan yang kasar</b></h1>
                        </div>
                        
                    </div>
                    <div class="column columns is-multiline">
                    <div class="column is-1"></div>
                    <div class="column is-11">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary"
                            square :true-value="gesekankulit1" label="Ketika akan menggeser pasien, ratakan terlebih dahulu bed di bagian kepala dan gunakan slide sheet" v-model="input.gesekankulit1"
                            />
                        </VControl>
                    </div>
                    <div class="column is-1"></div>
                    <div class="column is-11">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary"
                            square :true-value="gesekankulit2" label="Observasi secermat mungkin terhadap kejadian luka akibat adanya pergesekan jika pasien setengah gelisah." v-model="input.gesekankulit2"
                            />
                        </VControl>
                    </div>
                    </div>
                    <div class="column is-12 is-flex ml-5">
                        <div class="column is-12">
                            <h1><b>2. Lakukan pemeriksaan/observasi dengan cermat pada area-area tertekan berikut</b></h1>
                        </div>
                        
                    </div>

                    <div class="column is-12">
                        <div class="column columns is-multiline">

                            <div class="column is-1"></div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary"
                                    square :true-value="Belakangkepala" label="Belakang kepala" v-model="input.Belakangkepala"
                                    />
                                </VControl>
                            </div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary"
                                    square :true-value="Bahu" label="Bahu" v-model="input.Bahu"
                                    />
                                </VControl>
                            </div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary"
                                    square :true-value="BokongSakrum" label="Bokong/Sakrum" v-model="input.BokongSakrum"
                                    />
                                </VControl>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <div class="column columns is-multiline">
                        <div class="column is-1"></div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary"
                                square :true-value="Pinggul" label="Pinggul" v-model="input.Pinggul"
                                />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary"
                                square :true-value="Telinga" label="Telinga" v-model="input.Telinga"
                                />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary"
                                square :true-value="Siku" label="Siku" v-model="input.Siku"
                                />
                            </VControl>
                        </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <div class="column columns is-multiline">
                            <div class="column is-1"></div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary"
                                    square :true-value="Tumit" label="Tumit" v-model="input.Tumit"
                                    />
                                </VControl>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12 is-flex ml-5">
                        <div class="column is-12">
                            <h1><b>3. Cegah lembab/basah</b></h1>
                        </div>
                        
                    </div>
                    <div class="column columns is-multiline">
                    <div class="column is-1"></div>
                    <div class="column is-11">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary"
                            square :true-value="cegahlembabbasah1" label="Oleskan krim/minyak pada area yang sering lembab." v-model="input.cegahlembabbasah1"
                            />
                        </VControl>
                    </div>
                    <div class="column is-1"></div>
                    <div class="column is-11">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary"
                            square :true-value="cegahlembabbasah2" label="Ganti kain/alas/popok lembab sesering mungkin minimal diperiksa setiap 2 jam sekali." v-model="input.cegahlembabbasah2"
                            />
                        </VControl>
                    </div>
                    <div class="column is-1"></div>
                    <div class="column is-11">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary"
                            square :true-value="cegahlembabbasah3" label="Gunakan kain/alas/popok yang menyerap air sehingga alas tetap kering." v-model="input.cegahlembabbasah3"
                            />
                        </VControl>
                    </div>
                    </div>
                    <div class="column is-12 is-flex ml-5">
                        <div class="column is-12">
                            <h1><b>4. Kurangi penekanan</b></h1>
                        </div>
                        
                    </div>
                    <div class="column columns is-multiline">
                    <div class="column is-1"></div>
                    <div class="column is-11">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary"
                            square :true-value="kurangipenekanan1" label="Gunakan bantal air/busa lembut di bawah area tertekan atau pasien ditidurkan dengan posisi melayang." v-model="input.kurangipenekanan1"
                            />
                        </VControl>
                    </div>
                    <div class="column is-1"></div>
                    <div class="column is-11">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary"
                            square :true-value="kurangipenekanan2" label="Elevasi tumit dan berikan bantalan lembut di bawah betis, tunjang kaki dengan bantal/kain lembut." v-model="input.kurangipenekanan2"
                            />
                        </VControl>
                    </div>
                    </div>
                    <div class="column is-12 is-flex ml-5">
                        <div class="column is-12">
                            <h1><b>5. Manajemen Penatalaksanaan Gangguan Integritas Kulit</b></h1>
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
                        <div class="column is-8 pt-0 pb-0"></div>
                        <div class="column is-8 pr-0">
                            <table style="width: 100%;border-collapse: collapse;border:1px solid black;" border="1">
                                <tr>
                                    <th style="background-color: gainsboro;">Derajat Risiko</th>
                                    <th style="background-color: gainsboro;text-align: center;"><b>Penatalaksanaan</b></th>
                                    <th style="height: 3.7rem; border-bottom: 1px solid black;" class="has-text-centered">
                                        Tanggal -></th>
                                </tr>
                                <tr>
                                </tr>
                                <tr>
                                    <th rowspan="5" style="height: 3.7rem;" class="center">16-23 (risiko rendah)</th>
                                    <th colspan="2" class="center">1. Inspeksi kulit pada area tertekan minimal 2X sehari</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="center">2. Sarankan pemberian nutrisi yang adekuat</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="center">3. Berikan mobilisasi semaksimal mungkin</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="center">4. Bantu dan lakukan perubahan posisi setiap 4 jam</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="center">5. Elevasi tempat tidur tidak lebih dari 30<sup>o</sup></th>
                                </tr>
                                <tr>
                                    <th rowspan="4" style="height: 3.7rem;" class="center">13-15 (risiko sedang)</th>
                                    <th colspan="2" class="center">1. Inspeksi kulit pada area tertekan setiap melakukan perubahan posisi</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="center">2. Lakukan perubahan posisi sedikitnya setiap 4 jam, elevasi tempat tidur tidak lebih dari 30 <sup>o</sup></th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="center">3. Konsulkan dengan ahli gizi untuk pemberian nutrisi yang adekuat sesuai yang direkomendasikan</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="center">4. Amankan tumit/area-area tertekan dengan memasang bantalan air</th>
                                </tr>
                                <tr>
                                    <th rowspan="4" style="height: 3.7rem;" class="center">10-12 (risiko tinggi)</th>
                                    <th colspan="2" class="center">1. Inspeksi kulit pada area tertekan setiap melakukan perubahan posisi</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="center">2. Konsulkan dengan ahli gizi untuk pemberian nutrisi yang adekuat sesuai yang direkomendasikan</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="center">3. Lakukan perubahan posisi sedikitnya setiap 2 jam, elevasi tempat tidur tidak lebih dari 30<sup>o</sup></th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="center">4. Amankan tumit/area-area tertekan dengan memasang bantalan air</th>
                                </tr>
                                <tr>
                                    <th rowspan="4" style="height: 3.7rem;" class="center">≤9 (risiko sangat tinggi)</th>
                                    <th colspan="2" class="center">1. Inspeksi kulit pada area tertekan setiap jam</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="center">2. Konsulkan dengan ahli gizi untuk pemberian nutrisi yang adekuat sesuai yang direkomendasikan</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="center">3. Buat jadwal tertulis dan lakukan perubahan posisi sedikitnya setiap 2 jam, elevasi tempat tidur tidak lebih dari 30 <sup>o</sup></th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="center">4. Amankan tumit/area-area tertekan dengan memasang bantalan air</th>
                                </tr>
                            </table>
                        </div>
                        <div class="column is-4 pl-0" style="overflow: auto;">
                            <table>
                                <tr>
                                    <td v-for="(item, index) in input.details" :key="index">
                                        <table class="table" style="width: 12rem !important;">
                                            <tr>
                                                <td style="height: 3.7rem">
                                                    <VDatePicker v-model="item.tanggalderajat" mode="datetime" is24hr>
                                                        <template #default="{ inputValue, inputEvents }">
                                                            <VControl icon="feather:calendar" fullwidth>
                                                                <VInput :value="inputValue" v-on="inputEvents" />
                                                            </VControl>
                                                        </template>
                                                    </VDatePicker>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="has-text-centered" style="height: 2.7rem">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox
                                                            class="p-0 is-outlined.is-primary"
                                                            color="primary"
                                                            square
                                                            :true-value="risikorendah1"
                                                            label=""
                                                            v-model="item.risikorendah1"
                                                        />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="has-text-centered" style="height: 2.6rem">
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
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="has-text-centered" style="height: 2.8rem">
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
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="has-text-centered" style="height: 2.7rem">
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
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="has-text-centered" style="height: 2.8rem">
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
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="has-text-centered" style="height: 2.7rem">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox
                                                            class="p-0"
                                                            color="primary"
                                                            square
                                                            :true-value="risikosedang1"
                                                            label=""
                                                            v-model="item.risikosedang1"
                                                        />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="has-text-centered" style="height: 4.4rem">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox
                                                            class="p-0"
                                                            color="primary"
                                                            square
                                                            :true-value="risikosedang2"
                                                            label=""
                                                            v-model="item.risikosedang2"
                                                        />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="has-text-centered" style="height: 4.2rem">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox
                                                            class="p-0"
                                                            color="primary"
                                                            square
                                                            :true-value="risikosedang3"
                                                            label=""
                                                            v-model="item.risikosedang3"
                                                        />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="has-text-centered" style="height: 2.7rem">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox
                                                            class="p-0"
                                                            color="primary"
                                                            square
                                                            :true-value="risikosedang4"
                                                            label=""
                                                            v-model="item.risikosedang4"
                                                        />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="has-text-centered" style="height: 2.6rem">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox
                                                            class="p-0"
                                                            color="primary"
                                                            square
                                                            :true-value="risikotinggi1"
                                                            label=""
                                                            v-model="item.risikotinggi1"
                                                        />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="has-text-centered" style="height: 4.3rem">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox
                                                            class="p-0"
                                                            color="primary"
                                                            square
                                                            :true-value="risikotinggi2"
                                                            label=""
                                                            v-model="item.risikotinggi2"
                                                        />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="has-text-centered" style="height: 4.3rem">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox
                                                            class="p-0"
                                                            color="primary"
                                                            square
                                                            :true-value="risikotinggi3"
                                                            label=""
                                                            v-model="item.risikotinggi3"
                                                        />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="has-text-centered" style="height: 2.8rem">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox
                                                            class="p-0"
                                                            color="primary"
                                                            square
                                                            :true-value="risikotinggi4"
                                                            label=""
                                                            v-model="item.risikotinggi4"
                                                        />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="has-text-centered" style="height: 2.7rem">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox
                                                            class="p-0"
                                                            color="primary"
                                                            square
                                                            :true-value="risikosangattinggi1"
                                                            label=""
                                                            v-model="item.risikosangattinggi1"
                                                        />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="has-text-centered" style="height: 4.1rem">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox
                                                            class="p-0"
                                                            color="primary"
                                                            square
                                                            :true-value="risikosangattinggi2"
                                                            label=""
                                                            v-model="item.risikosangattinggi2"
                                                        />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="has-text-centered" style="height: 4.4rem">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox
                                                            class="p-0"
                                                            color="primary"
                                                            square
                                                            :true-value="risikosangattinggi3"
                                                            label=""
                                                            v-model="item.risikosangattinggi3"
                                                        />
                                                    </VControl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="has-text-centered" style="height: 2.7rem">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox
                                                            class="p-0"
                                                            color="primary"
                                                            square
                                                            :true-value="risikosangattinggi4"
                                                            label=""
                                                            v-model="item.risikosangattinggi4"
                                                        />
                                                    </VControl>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <div style="overflow-x:auto;" class="mt-1">
                        <table style="width: 100%;border-collapse: collapse;border:1px solid black;" border="1">
                            <tr>
                                <td>
                                    <div>
                                            <span><b>MOBILISASI</b></span><br>
                                            <span>Kemampuan merubah dan <br>
                                                mengontrol posisi tubuh
                                            </span>
                                    </div> 
                                </td>
                                <td>
                                    <div>
                                        <span><b>1. Immobilisasi Total</b></span><br>
                                        <span>Tidak dapat menggerakkan tubuh sedikitpun <br>
                                            tanpa bantuan
                                        </span>
                                    </div> 
                                </td>
                                <td>
                                    <div>
                                        <span><b>2. Sangat Terbatas</b></span><br>
                                        <span>Kadang-kadang dapat menggerakkan tubuh<br>
                                            atau merubah sedikit posisi tetapi tidak<br>
                                            mampu melakukan sendiri
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span><b>3. Agak Terbatas</b></span><br>
                                        <span>Dapat sedikit menggerakkan tubuh dengan<br>
                                            bebas
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span><b>4. Tidak ada batasan</b></span><br>
                                        <span>Dapat melakukan gerakkan dengan bebas<br>
                                            tanpa bantuan
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <span><b>AKTIVITAS</b></span><br>
                                        <span>Kemampuan melakukan aktivitas fisik</span>
                                    </div> 
                                </td>
                                <td>
                                    <div>
                                        <span><b>1. Bedrest</b></span><br>
                                        <span>Terbatas di tempat tidur</span>
                                    </div> 
                                </td>
                                <td>
                                    <div>
                                        <span><b>2. Hanya Bisa Duduk</b></span><br>
                                        <span>Kemampuan berjalan sangat terbatas atau
                                            tidak bisa sama sekali. Tidak bisa menahan
                                            berat badan sendiri dan atau perlu bantuan
                                            kursi roda
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span><b>3. Kadang-kadang Jalan</b></span><br>
                                        <span>Kadang-kadang dapat berjalan, namun
                                            dalam jarak yang dekat dengan atau tanpa
                                            bantuan. Kebanyakan berada di kursi atau
                                            tempat tidur
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span><b>4. Sering Jalan</b></span><br>
                                        <span>Dapat berjalan-jalan keluar ruangan
                                            setidaknya 2 kali sehari, dan di dalam
                                            ruangan sekali dalam 2 jam saat terjadi
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <span><b>SENSORI PERSEPSI</b></span><br>
                                        <span>Kemampuan untuk
                                            merespon, merasakan
                                            tekanan, ketidaknyamanan
                                        </span>
                                    </div> 
                                </td>
                                <td>
                                    <div>
                                        <span><b>1. Keterbatasan Total</b></span><br>
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
                                        <span><b>2. Sangat Terbatas</b></span><br>
                                        <span>Hanya merespon nyeri tidak bisa menyatakan
                                            ketidaknyamanan kecuali dengan mengeram
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
                                        <span><b>3. Agak Terbatas</b></span><br>
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
                                </td>
                                <td>
                                    <div>
                                        <span><b>4. Tidak Ada Kelemahan</b></span><br>
                                        <span>Meresponperintah, tidak punya
                                            keterbatasan sensori, memiliki perasaan
                                            yang baik dalam merespon nyeri, suara
                                            dan ketidaknyamanan
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <span><b>KELEMBABAN KULIT</b></span><br>
                                        <span>Derajat kelembaban kulit</span>
                                    </div> 
                                </td>
                                <td>
                                    <div>
                                        <span><b>1. Selalu Lembab</b></span><br>
                                        <span>Kulit pasien selalu dalam keadaan lembab/
                                            basah karena keringat urine dll .
                                            Kelembaban selalu ditemukan pada setiap
                                            kali pasien membalikkan badan
                                        </span>
                                    </div> 
                                </td>
                                <td>
                                    <div>
                                        <span><b>2. Sering Lembab</b></span><br>
                                        <span>Kulit sering lembab namun tidak selalu. Linen
                                            harus diganti setidaknya setiap shift
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span><b>3. Kadang-kadang Lembab</b></span><br>
                                        <span>Kulit kadang-kadang lembab, memerlukan
                                            penggantian linen ekstra, setidaknya diganti 2
                                            kali sehari
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span><b>4. Jarang Lembab</b></span><br>
                                        <span>Kulit selalu dalam keadaan kering,
                                            penggantian popok dilakukan secara rutin,
                                            linen hanya perlu diganti setiap minimal 24
                                            jam
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <span><b>PERGESEKAN KULIT</b></span><br>
                                    </div> 
                                </td>
                                <td>
                                    <div>
                                        <span><b>1. Sangat Bermasalah</b></span><br>
                                        <span>Pasien dengan kelakuan, kontraktur, gatal
                                            dan agitasi hampir selalu mengalami gesekan
                                        </span>
                                    </div> 
                                </td>
                                <td>
                                    <div>
                                        <span><b>2. Bermasalah</b></span><br>
                                        <span>Memerlukan bantuan maksimal untuk
                                            memindahkan pasien. Pasien tidak mungkin
                                            dapat diangkat dengan sempurna tanpa ada
                                            gesekan dengan jelas. Pasien sering melorot
                                            di tempat tidur atau kursi, memerlukan
                                            reposisi yang sering dengan bantuan
                                            maksimal
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span><b>3. Potensi Ada Masalah</b></span><br>
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
                                        <span><b>4. Tidak Ada Masalah</b></span><br>
                                        <span>Dapat berpindah di tempat tidur atau kursi
                                            dengan bebas, dapat mengangkat beban
                                            tubuh dengan baik selama berpindah.
                                            Selalu dalam posisi baik di tempat tidur dan
                                            kursi
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <span><b>STATUS NUTRISI</b></span><br>
                                    </div> 
                                </td>
                                <td>
                                    <div>
                                        <span><b>1. Sangat Kurang</b></span><br>
                                        <span>Puasa/tidak mendapat nutrisi atau cairan
                                            tambahan baik enteral/parenteral selama
                                            lebih dari 5 hari
                                        </span><br>
                                        <span><b>ATAU</b></span><br>
                                        <span>Tidak pernah menghabiskan makanan yang
                                            diberikan. Jarang bisa menghabiskan
                                            1 makanan lebih dari / porsi. Makan kurang 2
                                            dari 2 jenis protein dan produk susu per hari.
                                            Kurang minum dan tidak mendapat suplemen
                                            tambahan
                                        </span>
                                    </div> 
                                </td>
                                <td>
                                    <div>
                                        <span><b>2. Mungkin Tidak Cukup</b></span><br>
                                        <span>Jarang dapat menghabiskan makanan yang
                                            diberikan, pada umumnya hanya makan 1/2
                                            porsi makanan yang diberikan. Sumber
                                            protein hanya 3 jenis. Kadang-kadang
                                            mendapatkan suplemen tambahan
                                        </span><br>
                                        <span><b>ATAU</b></span><br>
                                        <span>Minum cairan kurang dari kebutuhan optimal
                                            atau memakai NGT
                                        </span>
                                    </div>  
                                </td>
                                <td>
                                    <div>
                                        <span><b>3. Cukup</b></span><br>
                                        <span>Makan lebih dari 1/2 porsi. Makan 4 sumber
                                            protein. Kadang-kadang menolak makanan
                                            yang diberikan namun selalu mendapatkan
                                            suplemen makanan
                                        </span><br>
                                        <span><b>ATAU</b></span><br>
                                        <span>Memakai NGT atau dapat memenuhi
                                            kebutuhan secara optimal
                                        </span>
                                    </div> 
                                </td>
                                <td>
                                    <div>
                                        <span><b>4. Sangat Baik</b></span><br>
                                        <span>Dapat makan berbagai macam makanan.
                                            Tidak pernah menolak makanan yang
                                            diberikan. Selalu makan 4 atau lebih
                                            sumber protein. Kadang-kadang mendapat
                                            snack. Tidak diperlukan suplemen
                                            makanan
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <span><b>OKSIGENASI DAN PERFUSI JARINGAN</b></span><br>
                                    </div> 
                                </td>
                                <td>
                                    <div>
                                        <span><b>1. Sangat Menurun</b></span><br>
                                        <span>Hipotensi
                                        </span><br>
                                        <span><b>ATAU</b></span><br>
                                        <span>Secara fisiologis pasien tidak dapat dilakukan
                                            perubahan posisinya
                                        </span>
                                    </div> 
                                </td>
                                <td>
                                    <div>
                                        <span><b>2. Menurun</b></span><br>
                                        <span>Normotensi</span><br>
                                        <span><b>ATAU</b> Saturasi oksigen &lt; 95%</span><br>
                                        <span><b>ATAU</b> Hb &lt; 10 mg/dl</span><br>
                                        <span><b>ATAU</b> CRT &gt; 2 detik</span><br>
                                        <span><b>ATAU</b> pH serum &lt; 7,40</span>
                                       
                                    </div>  
                                </td>
                                <td>
                                    <div>
                                        <span><b>3. Cukup</b></span><br>
                                        <span>Normotensi</span><br>
                                        <span><b>ATAU</b> Saturasi oksigen &lt; 95%</span><br>
                                        <span><b>ATAU</b> Hb &lt; 10 mg/dl</span><br>
                                        <span><b>ATAU</b> CRT &lt; 2 detik</span><br>
                                        <span><b>ATAU</b> serum normal</span>
                                       
                                    </div>  
                                </td>
                                <td>
                                    <div>
                                        <span><b>3. Cukup</b></span><br>
                                        <span>Normotensi</span><br>
                                        <span><b>ATAU</b> Saturasi oksigen &lt; 95%</span><br>
                                        <span><b>ATAU</b> Hb normal</span><br>
                                        <span><b>ATAU</b> CRT &lt; 2 detik</span><br>
                                       
                                    </div>
                                </td>
                            </tr>
                        </table>
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
        element.parafPerawat = parafPerawat
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
      parseFloat(item.kelembapan || 0) +
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


</style>