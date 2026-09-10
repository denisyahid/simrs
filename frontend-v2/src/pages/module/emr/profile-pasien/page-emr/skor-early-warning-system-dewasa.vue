<template>
    <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3> {{ props.FORM_NAME }}</h3>
                    </div>
                    <div class="right">
                        <div class="buttons">
                            <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                Kembali
                            </VButton>
                            <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                :loading="isLoading" @click="simpan()"> Simpan
                            </VButton>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="columns is-multiline p-2">
        <div class="column is-12">
            <VCard>
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-4">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Dokter</h1>
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="" v-model="input.dokterRawat" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Diagnosa </h1>
                            <VField>
                                <VControl>
                                    <AutoComplete v-model="input.diagnosa" :suggestions="d_Diagnosa"
                                        @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="Cari Diagnosa" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Ruangan </h1>
                            <VField>
                                <VControl>
                                    <AutoComplete v-model="input.namaruangan" :suggestions="d_Ruangan"
                                        @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="Cari Ruangan" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12" style="width: 100%;overflow: auto;">
                            <table class="tg" style="width: 200%;">
                                <tr>
                                    <th rowspan="2"
                                        style="width:150px;position:sticky;left:0;z-index:2;background-color: aliceblue;">
                                        Pengkajian Nyeri</th>

                                    <th rowspan="2" style="width: 100px;left:0px;z-index:2;background-color: aliceblue;">
                                        Skor</th>

                                    <th :colspan="jumlahImdex" class="text-left bg-th"
                                        style="width:50px; font-weight: bold;">Tanggal</th>
                                    <th rowspan="2" style="width: 80px;left:0px;z-index:2;background-color: aliceblue;">
                                        Poin</th>
                                </tr>
                                <tr>

                                    <th class="bg-th" v-for="index in jumlahImdex">


                                        <VField>
                                            <VControl class="prime-auto">
                                                <Calendar v-model="input.tanggal[index]" selectionMode="single"
                                                    :manualInput="false" class="w-100" :showIcon="true" showTime
                                                    hourFormat="24" :date-format="'yy-mm-dd'" />
                                            </VControl>
                                        </VField>

                                    </th>
                                </tr>
                                <tr>
                                    <td rowspan="7" class="bg-colatas ">Laju Respirasi/menit</td>
                                    <td class="bg-colatas3"> &lt;= 5 </td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <Checkbox v-model="input.laju1[index]" :binary="true"
                                            @change="setSkor(input.laju1[index], index, 0)" />

                                    </td>
                                    <td class="bg-colatas"> Blue </td>
                                </tr>
                                <tr>

                                    <td class="bg-colatas3"> 6-8 </td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <Checkbox v-model="input.laju2[index]" :binary="true"
                                            @change="setSkor(input.laju2[index], index, 3)" />

                                    </td>
                                    <td class="bg-colatas"> 3 </td>
                                </tr>
                                <tr>

                                    <td class="bg-colatas3"> 9 - 11 </td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <Checkbox v-model="input.laju3[index]" :binary="true"
                                            @change="setSkor(input.laju3[index], index, 1)" />

                                    </td>
                                    <td class="bg-colatas"> 1 </td>
                                </tr>
                                <tr>

                                    <td class="bg-colatas3"> 12 -20 </td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <Checkbox v-model="input.laju3[index]" :binary="true"
                                            @change="setSkor(input.laju3[index], index, 0)" />

                                    </td>
                                    <td class="bg-colatas"> 0 </td>
                                </tr>
                                <tr>

                                    <td class="bg-colatas3"> 21 - 24 </td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <Checkbox v-model="input.laju4[index]" :binary="true"
                                            @change="setSkor(input.laju4[index], index, 2)" />

                                    </td>
                                    <td class="bg-colatas"> 2 </td>
                                </tr>
                                <tr>
                                    <td class="bg-colatas3"> 25 - 34 </td>
                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <Checkbox v-model="input.laju5[index]" :binary="true"
                                            @change="setSkor(input.laju5[index], index, 2)" />

                                    </td>
                                    <td class="bg-colatas"> 3 </td>
                                </tr>
                                <tr>
                                    <td class="bg-colatas3"> &gt;= 35</td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <Checkbox v-model="input.laju6[index]" :binary="true"
                                            @change="setSkor(input.laju6[index], index, 2)" />

                                    </td>
                                    <td class="bg-colatas"> Blue </td>
                                </tr>
                                <tr>
                                    <td rowspan="4" class="bg-colatas">Saturasi 02%</td>

                                    <td class="bg-colatas3"> &gt;= 96</td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex">
                                        <Checkbox v-model="input.saturasi1[index]" :binary="true"
                                            @change="setSkor(input.saturasi1[index], index, 0)" />
                                    </td>
                                    <td class="bg-colatas"> 0 </td>
                                </tr>
                                <tr>
                                    <td class="bg-colatas3"> 94 - 95 </td>
                                    <td class="tg-0lax" v-for="index in jumlahImdex">
                                        <Checkbox v-model="input.saturasi2[index]" :binary="true"
                                            @change="setSkor(input.saturasi2[index], index, 6)" />
                                    </td>
                                    <td class="bg-colatas"> 1 </td>
                                </tr>
                                <tr>
                                    <td class="bg-colatas3"> 92 - 93 </td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex">
                                        <Checkbox v-model="input.saturasi3[index]" :binary="true"
                                            @change="setSkor(input.saturasi3[index], index, 8)" />
                                    </td>
                                    <td class="bg-colatas"> 2 </td>
                                </tr>
                                <tr>
                                    <td class="bg-colatas3">&lt;= 91</td>
                                    <td class="tg-0lax" v-for="index in jumlahImdex">
                                        <Checkbox v-model="input.saturasi4[index]" :binary="true"
                                            @change="setSkor(input.saturasi4[index], index, 10)" />
                                    </td>
                                    <td class="bg-colatas"> 2 </td>
                                </tr>
                                <tr>
                                    <td rowspan="2" class="bg-colatas ">Suplemen 02</td>
                                    <td class="bg-colatas3"> Ada </td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <Checkbox v-model="input.suplemen1[index]" :binary="true"
                                            @change="setSkor(input.suplemen1[index], index, 2)" />

                                    </td>
                                    <td class="bg-colatas"> 2 </td>
                                </tr>
                                <tr>

                                    <td class="bg-colatas3"> Tidak Ada </td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <Checkbox v-model="input.suplemen1[index]" :binary="true"
                                            @change="setSkor(input.suplemen1[index], index, 3)" />

                                    </td>
                                    <td class="bg-colatas"> 0 </td>
                                </tr>
                                <tr>
                                    <td rowspan="7" class="bg-colatas">Tekanan Darah Sistolik (mmHg)</td>
                                    <td class="bg-colatas3"> &gt;220 </td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <Checkbox v-model="input.tekananDarah1[index]" :binary="true"
                                            @change="setSkor(input.tekananDarah1[index], index, 3)" />

                                    </td>
                                    <td class="bg-colatas"> 3 </td>
                                </tr>
                                <tr>

                                    <td class="bg-colatas3"> 181 - 220 </td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <Checkbox v-model="input.tekananDarah2[index]" :binary="true"
                                            @change="setSkor(input.tekananDarah2[index], index, 1)" />

                                    </td>
                                    <td class="bg-colatas"> 1 </td>
                                </tr>
                                <tr>

                                    <td class="bg-colatas3"> 111 - 180 </td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <Checkbox v-model="input.tekananDarah3[index]" :binary="true"
                                            @change="setSkor(input.tekananDarah3[index], index, 0)" />

                                    </td>
                                    <td class="bg-colatas"> 0 </td>
                                </tr>
                                <tr>

                                    <td class="bg-colatas3"> 101 - 110 </td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <Checkbox v-model="input.tekananDarah7[index]" :binary="true"
                                            @change="setSkor(input.tekananDarah7[index], index, 1)" />

                                    </td>
                                    <td class="bg-colatas"> 1 </td>
                                </tr>
                                <tr>

                                    <td class="bg-colatas3"> 91 - 100 </td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <Checkbox v-model="input.tekananDarah4[index]" :binary="true"
                                            @change="setSkor(input.tekananDarah4[index], index, 2)" />

                                    </td>
                                    <td class="bg-colatas"> 2 </td>
                                </tr>
                                <tr>
                                    <td class="bg-colatas3"> 71 - 90 </td>
                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <Checkbox v-model="input.tekananDarah5[index]" :binary="true"
                                            @change="setSkor(input.tekananDarah5[index], index, 3)" />

                                    </td>
                                    <td class="bg-colatas"> 3 </td>
                                </tr>
                                <tr>
                                    <td class="bg-colatas3"> &lt;= 70</td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex">
                                        <Checkbox v-model="input.tekananDarah6[index]" :binary="true"
                                            @change="setSkor(input.tekananDarah6[index], index, 2)" />

                                    </td>
                                    <td class="bg-colatas"> Blue </td>
                                </tr>
                                <tr>
                                    <td rowspan="7" class="bg-colatas">Laju Jantung/Menit</td>
                                    <td class="bg-colatas3"> 150 </td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <Checkbox v-model="input.jantung1[index]" :binary="true"
                                            @change="setSkor(input.jantung1[index], index, 0)" />

                                    </td>
                                    <td class="bg-colatas"> Blue </td>
                                </tr>
                                <tr>

                                    <td class="bg-colatas3"> 131 - 150 </td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <Checkbox v-model="input.jantung2[index]" :binary="true"
                                            @change="setSkor(input.jantung2[index], index, 3)" />

                                    </td>
                                    <td class="bg-colatas"> 3 </td>
                                </tr>
                                <tr>

                                    <td class="bg-colatas3"> 111 - 130 </td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <Checkbox v-model="input.jantung3[index]" :binary="true"
                                            @change="setSkor(input.jantung3[index], index, 2)" />

                                    </td>
                                    <td class="bg-colatas"> 2 </td>
                                </tr>
                                <tr>

                                    <td class="bg-colatas3"> 101 - 110 </td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <Checkbox v-model="input.jantung7[index]" :binary="true"
                                            @change="setSkor(input.jantung7[index], index, 1)" />

                                    </td>
                                    <td class="bg-colatas"> 1 </td>
                                </tr>
                                <tr>

                                    <td class="bg-colatas3"> 51 - 100 </td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <Checkbox v-model="input.jantung4[index]" :binary="true"
                                            @change="setSkor(input.jantung4[index], index, 0)" />

                                    </td>
                                    <td class="bg-colatas"> 0 </td>
                                </tr>
                                <tr>
                                    <td class="bg-colatas3"> 40 - 50 </td>
                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <Checkbox v-model="input.jantung5[index]" :binary="true"
                                            @change="setSkor(input.jantung5[index], index, 1)" />

                                    </td>
                                    <td class="bg-colatas"> 1 </td>
                                </tr>
                                <tr>
                                    <td class="bg-colatas3"> &lt; 40</td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex">
                                        <Checkbox v-model="input.jantung6[index]" :binary="true"
                                            @change="setSkor(input.jantung6[index], index, 2)" />

                                    </td>
                                    <td class="bg-colatas"> Blue </td>
                                </tr>

                                <tr>
                                    <td rowspan="3" class="bg-colatas ">Kesadaran</td>
                                    <td class="bg-colatas3"> Sadar </td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <Checkbox v-model="input.kesadaran1[index]" :binary="true"
                                            @change="setSkor(input.kesadaran1[index], index, 0)" />

                                    </td>
                                    <td class="bg-colatas"> 0 </td>
                                </tr>
                                <tr>

                                    <td class="bg-colatas3"> Nyeri/Verbal </td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <Checkbox v-model="input.kesadaran2[index]" :binary="true"
                                            @change="setSkor(input.kesadaran2[index], index, 3)" />

                                    </td>
                                    <td class="bg-colatas"> 3 </td>
                                </tr>
                                <tr>

                                    <td class="bg-colatas3"> Tak Respon </td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <Checkbox v-model="input.kesadaran3[index]" :binary="true"
                                            @change="setSkor(input.kesadaran3[index], index, 0)" />

                                    </td>
                                    <td class="bg-colatas"> Blue </td>
                                </tr>
                                <tr>
                                    <td rowspan="5" class="bg-colatas">Suhu</td>
                                    <td class="bg-colatas3"> &lt; = 35 </td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <Checkbox v-model="input.suhu1[index]" :binary="true"
                                            @change="setSkor(input.suhu1[index], index, 3)" />

                                    </td>
                                    <td class="bg-colatas"> 3 </td>
                                </tr>
                                <tr>

                                    <td class="bg-colatas3"> 35.1 - 36 </td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <Checkbox v-model="input.suhu2[index]" :binary="true"
                                            @change="setSkor(input.suhu2[index], index, 1)" />

                                    </td>
                                    <td class="bg-colatas"> 1 </td>
                                </tr>
                                <tr>

                                    <td class="bg-colatas3"> 36.1 - 38 </td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <Checkbox v-model="input.suhu3[index]" :binary="true"
                                            @change="setSkor(input.suhu3[index], index, 0)" />

                                    </td>
                                    <td class="bg-colatas"> 0 </td>
                                </tr>
                                <tr>

                                    <td class="bg-colatas3"> 38.1 - 39 </td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <Checkbox v-model="input.suhu5[index]" :binary="true"
                                            @change="setSkor(input.suhu5[index], index, 1)" />

                                    </td>
                                    <td class="bg-colatas"> 1 </td>
                                </tr>
                                <tr>

                                    <td class="bg-colatas3"> &gt; 39 </td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                                        <Checkbox v-model="input.suhu4[index]" :binary="true"
                                            @change="setSkor(input.suhu4[index], index, 2)" />

                                    </td>
                                    <td class="bg-colatas"> 2 </td>
                                </tr>



                                <tr>
                                    <th class="bg-colatas">Total Skor</th>

                                    <td class="bg-colatas3"></td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex">
                                        <VField>
                                            <VControl>
                                                <VInput type="text" v-model="input.totalSkor[index]"
                                                    placeholder="Total Skor" class="is-rounded_Z input-30" />
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th class="bg-colatas">Nama</th>

                                    <td class="bg-colatas3"></td>

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
                                    <td class="tg-0lax">
                                        <VField class="is-autocomplete-select" v-slot="{ id }">
                                            <VControl icon="feather:search">
                                                <AutoComplete v-model="input.penangungJawab10" :suggestions="d_Dokter"
                                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                    :field="'label'" placeholder="ketik nama" />
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th class="bg-colatas">Paramater tambahan yang mendukung</th>

                                    <td class="bg-colatas3">Skor Nyeri</td>

                                    <td class="tg-0lax" v-for="index in jumlahImdex">
                                        <VField>
                                            <VControl>
                                                <VInput type="text" v-model="input.namaParameter[index]" placeholder=""
                                                    class="is-rounded_Z input-30" />
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td></td>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <table class="tg" style="width: 100%;">
                        <tr>
                            <td style="width: 20%;">
                                Skor 1 - 4 (Resiko Ringan)
                            </td>
                            <td> Asesmen oleh perawat senior, respon segera, (maks 5 menit), eskalasi perawat dan frekuensi
                                monitoring per 4 - 6 jam, jika diperlukan assessment oleh dokter jaga bangsal </td>
                        </tr>
                        <tr>
                            <td style="width: 20%;">
                                Skor 5 - 6 (Resiko Tertinggi)
                            </td>
                            <td> Asesmen segera oleh dokter jaga (respon segera, maks 5 menit), konsultasi DPJP dan spesials
                                terkait, eskalasi perawatan dan monitoring tiap jam, pertimbangan perawatan dengan
                                monitoring yang sesuai (HCU?ICU) </td>
                        </tr>
                        <tr>
                            <td style="width: 20%;">
                                Skor 7 atau lebih / 1 PARAMETER Kriteria Blue (Risiko Tinggi)
                            </td>
                            <td> Resusitasi dan monitoring secara kontinyu oleh dokter jaga dan perawat senior, Aktivasi
                                kepegawaian medis respon Tim Medis Reaksi Cepat (TMRC) TELP (244) respon segera, (maksimal
                                10
                                menit), informasikan dan konsultasikan ke DPJP. </td>
                        </tr>
                        <tr>
                            <td style="width: 20%;">
                                Henti Nafas/ Jantung
                            </td>
                            <td> Lakukan RJP oleh petugas/tim primer, aktivasi code blue henti jantung (199), respon Tim
                                Medis Reaksi Cepat (TMRC) segera, maksimal 5 menit, informasikan dan konsultasikan DPJP
                            </td>
                        </tr>
                    </table>

                </div>

            </VCard>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
import Checkbox from 'primevue/checkbox';
import Calendar from 'primevue/calendar';


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

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
const jumlahImdex = ref(10)
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})

const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    tanggal: [],
    laju1: [],
    laju2: [],
    laju3: [],
    laju4: [],
    laju5: [],
    laju6: [],
    saturasi1: [],
    saturasi2: [],
    saturasi3: [],
    suplemen1: [],
    tekananDarah1: [],
    tekananDarah2: [],
    tekananDarah3: [],
    tekananDarah4: [],
    tekananDarah5: [],
    tekananDarah6: [],
    tekananDarah7: [],
    kesadaran1: [],
    kesadaran2: [],
    kesadaran3: [],
    jantung1: [],
    jantung2: [],
    jantung3: [],
    jantung4: [],
    jantung5: [],
    jantung6: [],
    jantung7: [],
    suhu1: [],
    suhu2: [],
    suhu3: [],
    suhu4: [],
    suhu5: [],
    cukupNyeri: [],
    lumayanNyeri: [],
    sangatNyeri: [],
    saturasi4: [],
    namaParameter: [],

    totalSkor: [],

    detailsTglSkor: [{
        no: 1,
        tgl: new Date(),
    }]
})
const d_Dokter: any = ref([])
const d_Ruangan: any = ref([])
const d_Diagnosa: any = ref([])
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}

const fetchDiagnosa = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/diagnosa_m?select=kddiagnosa,namadiagnosa&param_search=kddiagnosa&query=${filter.query}&limit=10`)
    d_Diagnosa.value = response
}
const fetchRuangan = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=id&query=${filter.query}&limit=10`)
    d_Ruangan.value = response
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

const addNewItem = () => {

    input.value.detailsTglSkor.push({
        no: input.value.detailsTglSkor[input.value.detailsTglSkor.length - 1].no + 1,
        tgl: new Date(),
    });
}
const removeItem = (index: any) => {
    input.value.detailsTglSkor.splice(index, 1)
}

const fetchDokter = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Dokter.value = response
    })
}
const setSkor = (e: any, index: any, skor: any) => {
    if (input.value.totalSkor[index] < 0) {
        input.value.totalSkor[index] = 0
    }
    if (e) {
        input.value.totalSkor[index] = parseFloat(input.value.totalSkor[index] ? input.value.totalSkor[index] : 0) + skor
    } else {
        input.value.totalSkor[index] = parseFloat(input.value.totalSkor[index] ? input.value.totalSkor[index] : 0) - skor
    }

}
const setAutoFill = async () => {
    input.value.dokterRawat = props.registrasi.dokter
    input.value.namaruangan = props.registrasi.namaruangan

}
setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
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
