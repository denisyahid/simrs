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
              <!-- <VButton type="button" rounded outlined color="warning" raised icon="lnir lnir-printer" @click="print()">
                Cetak
              </VButton> -->
              <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                @click="simpan()"> Simpan
              </VButton>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
  <VCard>
    <div>
      <div class="columns is-multiline p-2">
        <div class="column is-12">
          <VCard>
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-3">
                  <VField label="Tanggal/ Jam">
                    <VDatePicker v-model="input.tglPembuatan" mode="dateTime" style="width: 100%" trim-weeks
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
                <div class="column is-3">
                  <VField label="No Mesin">
                    <VControl>
                      <VInput type="text" class="input" placeholder="" v-model="input.noMesin" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField label="Hemodialisis Ke">
                    <VControl>
                      <VInput type="text" class="input" placeholder="" v-model="input.hemodialisisKe" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField label="Tipe Dialiser N/R">
                    <VControl>
                      <VInput type="text" class="input" placeholder="" v-model="input.tipeDialiser" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12 P-0">
                  <div class="column is-12">
                    <div class="columns is-multiline">
                      <div class="column is-2">
                        <h5>Diagnosa Medis :</h5>
                      </div>
                      <div class="column is-5">
                        <VField>
                          <VControl>
                            <AutoComplete v-model="input.diagnosa" :suggestions="d_Diagnosa"
                              @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                              placeholder="Cari Diagnosa" />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-2">
                        <h5>Riwayat Alergi :</h5>
                      </div>
                      <div class="column is-1" v-for="(data, i) in riwayatAlergiObat">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input.riwayatAlergiObat" :true-value="data.value" :label="data.label"
                              class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </VCard>
        </div>
        <h1 style="font-size: 18px; font-weight: bold; margin-bottom: 1rem;" class="ml-3"> PENGKAJIAN KEPERAWATAN</h1>
        <div class="column is-12">
          <VCard>
            <h1 style="font-weight: bold; margin-bottom: 1rem;"> 1. KELUHAN UTAMA NYERI</h1>
            <div class="columns is-multiline">
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Sesak" label="Sesak"
                      v-model="input.sesak" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Lemas" label="Lemas"
                      v-model="input.lemas" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Demam" label="Demam"
                      v-model="input.demam" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Mual/Muntah" label="Mual/Muntah"
                      v-model="input.mualMuntah" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Gatal" label="Gatal"
                      v-model="input.gatal" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Lain-Lain" label="Lain-Lain"
                      v-model="input.lainLain" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-6" v-if="input.lainLain">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Lain-Lain... " v-model="input.lainLainKet" />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="column is-12">
              <!-- <VAvatar size="big" picture="/images/emr/wongBaker.png" color="primary" squared bordered /> -->
              <Image src="/images/emr/wongBaker.png" alt="Image" width="600" class="center" preview />
            </div>
            <div class="columns is-multiline">

              <div class="column is-12" style="width: 100%;overflow: auto;">
                <table class="tg" style="width: 50%;">
                  <tr>
                    <th rowspan="2" style="width:50px;position:sticky;left:0;z-index:2;background-color: aliceblue;">
                      Pengkajian Nyeri</th>

                    <th rowspan="2" style="width: 10px;left:0px;z-index:2;background-color: aliceblue;">
                      Skor</th>

                    <th :colspan="jumlahImdex" class="text-left bg-th" style="width:50px">Tanggal</th>
                  </tr>
                  <tr>

                    <th class="bg-th" v-for="index in jumlahImdex">
                      <VDatePicker v-model="input.tanggal[index]" color="green" trim-weeks :input="'YYYY-MM-DD'"
                        mode="date">
                        <template #default="{ inputValue, inputEvents }">
                          <VField>
                            <VControl icon="feather:calendar">
                              <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                                class="is-rounded_Z input-30" style="width:150px" />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </th>
                  </tr>
                  <tr>
                    <td class="bg-colatas ">Tidak Nyeri</td>
                    <td class="bg-colatas3">0 - 1</td>

                    <td class="tg-0lax" v-for="index in jumlahImdex" style="position: center;">
                      <Checkbox v-model="input.tidakNyeri[index]" :binary="true"
                        @change="setSkor(input.tidakNyeri[index], index, 0)" />

                    </td>
                  </tr>
                  <tr>
                    <td class="bg-colatas">Sedikit Nyeri</td>

                    <td class="bg-colatas3">2 - 3</td>

                    <td class="tg-0lax" v-for="index in jumlahImdex">
                      <Checkbox v-model="input.sedikitNyeri[index]" :binary="true"
                        @change="setSkor(input.sedikitNyeri[index], index, 2)" />

                    </td>
                  </tr>
                  <tr>
                    <td class="bg-colatas">Cukup Nyeri</td>

                    <td class="bg-colatas3">4-5</td>

                    <td class="tg-0lax" v-for="index in jumlahImdex">
                      <Checkbox v-model="input.cukupNyeri[index]" :binary="true"
                        @change="setSkor(input.cukupNyeri[index], index, 4)" />

                    </td>
                  </tr>
                  <tr>
                    <td class="bg-colatas">Lumayan Nyeri</td>

                    <td class="bg-colatas3">6 - 7</td>

                    <td class="tg-0lax" v-for="index in jumlahImdex">
                      <Checkbox v-model="input.lumayanNyeri[index]" :binary="true"
                        @change="setSkor(input.lumayanNyeri[index], index, 6)" />

                    </td>
                  </tr>
                  <tr>
                    <td class="bg-colatas">Sangat Nyeri</td>

                    <td class="bg-colatas3">8 - 9</td>

                    <td class="tg-0lax" v-for="index in jumlahImdex">
                      <Checkbox v-model="input.sangatNyeri[index]" :binary="true"
                        @change="setSkor(input.sangatNyeri[index], index, 8)" />

                    </td>
                  </tr>
                  <tr>
                    <td class="bg-colatas">Berat Nyeri</td>

                    <td class="bg-colatas3">10</td>

                    <td class="tg-0lax" v-for="index in jumlahImdex">
                      <Checkbox v-model="input.nyeriBerat[index]" :binary="true"
                        @change="setSkor(input.nyeriBerat[index], index, 10)" />

                    </td>
                  </tr>

                  <tr>
                    <th class="bg-colatas">Total Skor</th>

                    <td class="bg-colatas3"></td>

                    <td class="tg-0lax" v-for="index in jumlahImdex">
                      <VField>
                        <VControl>
                          <VInput type="text" v-model="input.totalSkor[index]" placeholder="Total Skor"
                            class="is-rounded_Z input-30" />
                        </VControl>
                      </VField>
                    </td>
                  </tr>
                  <tr>
                    <th class="bg-colatas">Nama</th>

                    <td class="bg-colatas3"></td>

                    <td class="tg-0lax">
                      <VField class="is-autocomplete-select" v-slot="{ id }">
                        <VControl icon="feather:search">
                          <AutoComplete v-model="input.penangungJawab1" :suggestions="d_Dokter"
                            @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik nama" />
                        </VControl>
                      </VField>
                    </td>
                  </tr>
                </table>
              </div>
            </div>
          </VCard>
        </div>

        <div class="column is-12">
          <Fieldset class="p-fieldsets" legend="2. PEMERIKSAAN FISIK" :toggleable="true">
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-2">
                  <h5>Keadaan Umum :</h5>
                </div>
                <div class="column is-2" v-for="(data, i) in keadaanUmum">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.keadaanUmum" :true-value="data.value" :label="data.label" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2">
                  <h5>Kesadaran :</h5>
                </div>
                <div class="column is-2" v-for="(data, i) in kesadaran">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.kesadaran" :true-value="data.value" :label="data.label" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="column is-6" v-if="input.kesadaran == 'Coma'">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder=".. " v-model="input.comaKet" />
                  </VControl>
                </VField>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2">
                  <h5>Tekanan Darah :</h5>
                </div>
                <div class="column is-3">
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.tekananDarahket" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>mmHg </VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-for="(data, i) in tekananDarah">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.tekananDarah" :true-value="data.value" :label="data.label" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="input.tekananDarah == 'Ferbis'">
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.ferbisKet" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static><sup>o </sup>C</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2">
                  <h5>Nadi :</h5>
                </div>
                <div class="column is-2" v-for="(data, i) in nadi">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.nadi" :true-value="data.value" :label="data.label" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" v-if="input.nadi == 'Frek'">
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.frekKet" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>x/mnt </VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2">
                  <h5>Respirasi :</h5>
                </div>
                <div class="column is-2" v-for="(data, i) in respirasi">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.respirasi" :true-value="data.value" :label="data.label" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="input.respirasi == 'Frek'">
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.frekRespirasiKet" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>x/mnt </VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2">
                  <h5>Konjungtiva :</h5>
                </div>
                <div class="column is-2" v-for="(data, i) in konjungtiva">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.konjungtiva" :true-value="data.value" :label="data.label" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2">
                  <h5>Ekstremitas :</h5>
                </div>
                <div class="column is-2" v-for="(data, i) in ekstremitas">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.ekstremitas" :true-value="data.value" :label="data.label" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="input.ekstremitas == 'LainLain'">
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" placeholder="..." v-model="input.ekstremitasLain" />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="column is-12">
                <Fieldset :toggleable="true" legend="PENILAIAN RESIKO JATUH MORSE">
                  <div class="column is-12">
                    <div class="columns is-multiline" style="border-bottom: 1px solid;">
                      <div class="column is-1">
                        <h1 class="emr">No</h1>
                      </div>
                      <div class="column is-5">
                        <h1 class="emr">Parameter</h1>
                      </div>
                      <div class="column is-5"></div>
                      <div class="column">
                        <h1 class="emr">Nilai</h1>
                      </div>
                    </div>
                    <div class="columns is-multiline" v-for="(data) in resikoJatuhMorse.pertama">
                      <div class="column is-1">
                        <h1 class="emr">{{ data.no }}</h1>
                      </div>
                      <div class="column is-5">
                        <h1 class="emr">{{ data.parameter }}</h1>
                      </div>
                      <div class="column is-5 pt-0">
                        <div class="column is-12 pb-0" v-for="(value) in data.pengkajian">
                          <VField class="">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input[value.model]" class="p-0" :true-value="value.value"
                                :label="value.title" color="primary" circle />
                            </VControl>
                          </VField>

                        </div>
                      </div>
                      <div class="column pt-0">
                        <div class="column pb-0" v-for="(nilai) in data.pengkajian">
                          <h1 class="emr">{{ nilai.poin }}
                          </h1>
                        </div>
                      </div>
                    </div>
                    <div class="columns is-multiline" v-for="(data) in resikoJatuhMorse.kedua">
                      <div class="column is-1">
                        <h1 class="emr">{{ data.no }}</h1>
                      </div>
                      <div class="column is-5">
                        <h1 class="emr">{{ data.parameter }}</h1>
                      </div>
                      <div class="column is-5 pt-0">
                        <div class="column is-12 pb-0" v-for="(value) in data.pengkajian">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input[value.model]" :true-value="value.value" :label="value.title"
                                class="p-0" color="primary" circle />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                      <div class="column pt-0">
                        <div class="column pb-0" v-for="(nilai) in data.pengkajian">
                          <h1 class="emr">{{ nilai.poin }}
                          </h1>
                        </div>
                      </div>
                    </div>
                    <div class="columns is-multiline" v-for="(data) in resikoJatuhMorse.ketiga">
                      <div class="column is-1">
                        <h1 class="emr">{{ data.no }}</h1>
                      </div>
                      <div class="column is-5">
                        <h1 class="emr">{{ data.parameter }}</h1>
                      </div>
                      <div class="column is-5 pt-0">
                        <div class="column is-12 pb-0" v-for="(value) in data.pengkajian">
                          <VField class="">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input[value.model]" class="p-0" :true-value="value.value"
                                :label="value.title" color="primary" circle />
                            </VControl>
                          </VField>

                        </div>
                      </div>
                      <div class="column pt-0">
                        <div class="column pb-0" v-for="(nilai) in data.pengkajian">
                          <h1 class="emr">{{ nilai.poin }}
                          </h1>
                        </div>
                      </div>
                    </div>
                    <div class="columns is-multiline" v-for="(data) in resikoJatuhMorse.keempat">
                      <div class="column is-1">
                        <h1 class="emr">{{ data.no }}</h1>
                      </div>
                      <div class="column is-5">
                        <h1 class="emr">{{ data.parameter }}</h1>
                      </div>
                      <div class="column is-5 pt-0">
                        <div class="column is-12 pb-0" v-for="(value) in data.pengkajian">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input[value.model]" :true-value="value.value" :label="value.title"
                                class="p-0" color="primary" circle />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                      <div class="column pt-0">
                        <div class="column pb-0" v-for="(nilai) in data.pengkajian">
                          <h1 class="emr">{{ nilai.poin }}
                          </h1>
                        </div>
                      </div>
                    </div>
                    <div class="columns is-multiline" v-for="(data) in resikoJatuhMorse.lima">
                      <div class="column is-1">
                        <h1 class="emr">{{ data.no }}</h1>
                      </div>
                      <div class="column is-5">
                        <h1 class="emr">{{ data.parameter }}</h1>
                      </div>
                      <div class="column is-5 pt-0">
                        <div class="column is-12 pb-0" v-for="(value) in data.pengkajian">
                          <VField class="">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input[value.model]" class="p-0" :true-value="value.value"
                                :label="value.title" color="primary" circle />
                            </VControl>
                          </VField>

                        </div>
                      </div>
                      <div class="column pt-0">
                        <div class="column pb-0" v-for="(nilai) in data.pengkajian">
                          <h1 class="emr">{{ nilai.poin }}
                          </h1>
                        </div>
                      </div>
                    </div>
                    <div class="columns is-multiline" v-for="(data) in resikoJatuhMorse.enam">
                      <div class="column is-1">
                        <h1 class="emr">{{ data.no }}</h1>
                      </div>
                      <div class="column is-5">
                        <h1 class="emr">{{ data.parameter }}</h1>
                      </div>
                      <div class="column is-5 pt-0">
                        <div class="column is-12 pb-0" v-for="(value) in data.pengkajian">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input[value.model]" :true-value="value.value" :label="value.title"
                                class="p-0" color="primary" circle />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                      <div class="column pt-0">
                        <div class="column pb-0" v-for="(nilai) in data.pengkajian">
                          <h1 class="emr">{{ nilai.poin }}
                          </h1>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="column is-3" style="margin-left: auto;">
                    <VField label="Jumlah Total">
                      <VControl raw subcontrol>
                        <input v-model="input.totalNilaiRJM" class="input v-else" disabled />
                      </VControl>
                    </VField>
                  </div>

                  <div class="column is-12">
                    <table class="assesment-awal">
                      <thead>
                        <tr>
                          <th>Tingkatan Resiko</th>
                          <th>Nilai MFC</th>
                          <th>Tindakan</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(datas) in ketJumlahPoin">
                          <td>
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input[datas.model]"
                                  style="font-weight: bold !important;color:var(--dark-text);" :true-value="datas.value"
                                  :label="datas.title" class="p-0" color="primary" circle disabled />
                              </VControl>
                            </VField>
                          </td>
                          <td>
                            <h1 class="emr">{{ datas.nilai }}</h1>
                          </td>
                          <td>
                            <h1 class="emr">{{ datas.tindakan }}</h1>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </Fieldset>


              </div>
              <div class="columns is-multiline">
                <div class="column is-6">
                  <span> Keluhan Utama : </span>
                  <VField>
                    <VTextarea rows="2" placeholder="Keluhan Utama....." v-model="input.keluhanUtama"></VTextarea>
                  </VField>
                </div>
                <div class="column is-6">
                  <span> Lama Keluhan : </span>
                  <VField>
                    <VTextarea rows="2" placeholder="Lama Keluhan......" v-model="input.lamaKeluhan">
                    </VTextarea>
                  </VField>
                </div>
                <div class="column is-6">
                  <span> Mulai Timbul keluhan : </span>
                  <VField>
                    <VTextarea rows="2" placeholder="Mulai Timbul Keluhan......" v-model="input.mulaiTimbulKeluhan">
                    </VTextarea>
                  </VField>
                </div>
                <div class="column is-6">
                  <span> Sifat Keluhan : </span>
                  <VField>
                    <VTextarea rows="2" placeholder="Sifat Keluhan......" v-model="input.sifatKeluhan"></VTextarea>
                  </VField>
                </div>
              </div>
            </div>
          </Fieldset>
        </div>
        <div class="column is-12">
          <Fieldset class="p-fieldsets" legend="3. PEMERIKSAAN PENUNJANG (Lab, EKG, Lain-lain)" :toggleable="true">
            <div class="columns is-multiline mb-5">
              <div class="column is-12 P-0">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <span> Pre Hemodialisis : </span>
                    <VField>
                      <VTextarea rows="2" placeholder="Pre Hemodialisis...." v-model="input.preHemodialisis"></VTextarea>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <span> Post Hemodialisis : </span>
                    <VField>
                      <VTextarea rows="2" placeholder="Post Hemodialisis....." v-model="input.postHemodialisis">
                      </VTextarea>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </Fieldset>
        </div>
        <div class="column is-12">
          <Fieldset class="p-fieldsets"
            legend="4. GIZI (Dikaji 6 bukan sekali atau diulang jika dianggap terjadi pemburukan asupan gizi)"
            :toggleable="true">
            <div class="columns is-multiline mb-5">
              <div class="column is-12 P-0">
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <span> Tanggal/ Jam : </span>
                    <VField>
                      <VDatePicker v-model="input.tglGizi" mode="date" style="width: 100%" trim-weeks
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
                  <div class="column is-2 mt-5">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="MIS" label="MIS" v-model="input.MIS" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2 mt-4" v-if="input.MIS == 'MIS'">
                    <h5>Score Total :</h5>
                  </div>
                  <div class="column is-2 mt-4" v-if="input.MIS == 'MIS'">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="..." v-model="input.MISKet" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-2">
                    <h5>kesimpulan :</h5>
                  </div>
                  <div class="column is-2" v-for="(data, i) in kesimpulan">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.kesimpulan" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-12">
                    <span> Rekomendasi : </span>
                    <VField>
                      <VTextarea rows="3" placeholder="...." v-model="input.Rekomendasi"></VTextarea>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-12">
                    <span> Perencanaan Pengkajian Ulang : </span>
                    <VField>
                      <VTextarea rows="3" placeholder="....." v-model="input.perencanaanPengkajianUlang">
                      </VTextarea>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </Fieldset>
        </div>
        <div class="column is-12">
          <Fieldset class="p-fieldsets"
            legend="5. RIWAYAT PSIKOSOSIAL : (Dikaji saat kunjungan pertama atau kunjungan terakhir > 1 tahun)"
            :toggleable="true">
            <div class="columns is-multiline mb-5">
              <div class="column is-12 P-0">
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <span> Tangal Pengkajian : </span>
                    <VField>
                      <VDatePicker v-model="input.tglPengkajian" mode="date" style="width: 100%" trim-weeks
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
                </div>
                <div class="columns is-multiline">
                  <div class="column is-8">
                    <h5>Adakah keyakinan/tradisi/budaya yang berkaitan dengan pelayanan kesehatan yang akan diberikan :
                    </h5>
                  </div>
                  <div class="column is-2" v-for="(data, i) in keyakinanBerkaitPelayanan">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.keyakinanBerkaitPelayanan" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <h5>Kendali Komunikasi :</h5>
                  </div>
                  <div class="column is-2" v-for="(data, i) in kendaliKomunikasi">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.kendaliKomunikasi" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2 mt-4" v-if="input.kendaliKomunikasi == 'Ada'">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="..." v-model="input.kendaliKomunikasiKet" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <h5>Yang Merawat Dirumah :</h5>
                  </div>
                  <div class="column is-2" v-for="(data, i) in yangMerawatDirumah">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.yangMerawatDirumah" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2 mt-4" v-if="input.yangMerawatDirumah == 'Ada'">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="..." v-model="input.yangMerawatDirumahKet" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <h5>Kondisi yang Ada Saat Ini :</h5>
                  </div>
                  <div class="column is-2" v-for="(data, i) in kondisiSaatIni">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.kondisiSaatIni" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline mt-6">
                  <h1 class="ml-3" style=" font-weight: bold;">DIAGNOSA KEPERAWATAN</h1>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-4" v-for="(data, i) in diagnosaKeperawatan">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.diagnosaKeperawatan" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2 mt-4" v-if="input.diagnosaKeperawatan == 'LainLain'">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="..." v-model="input.diagnosaKeperawatanLain" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline mt-6">
                  <h1 class="ml-3" style=" font-weight: bold;">INTERVENSI KEPERAWATAN (rekapitulasi Pre-intra-post HD) :
                  </h1>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-6" v-for="(data, i) in intervensiKeperawatan">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.intervensiKeperawatan" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2 mt-4" v-if="input.intervensiKeperawatan == 'LainLain'">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="..." v-model="input.intervensiKeperawatanLain" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline mt-6">
                  <h1 class="ml-3" style=" font-weight: bold;">INTERVENSI KOLABORASI :</h1>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-6" v-for="(data, i) in kolaborasi">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.kolaborasi" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline mt-6">
                  <h1 class="ml-3" style=" font-weight: bold;"> RESEP HD :</h1>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in resepHd">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.resepHd" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2 mt-4" v-if="input.resepHd == 'SLEED'">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="..." v-model="input.resepHdLain" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <span> BB Post HD yl : </span>
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.BBPostHD" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>kg </VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <span> BB Pre HD : </span>
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.BBPreHD" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <span> BBK : </span>
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.BBK" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <span> BBK Post HD : </span>
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.BBKpostHd" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <span> Lama HD : </span>
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.LamaHd" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Jam </VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <span> UF Goal : </span>
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.UfGoal" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <span> TMP : </span>
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.Tmp" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <span> QB : </span>
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.Qb" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>ml/mnt </VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <span> QD : </span>
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.Qd" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>ml/mnt </VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <span> T. Vena : </span>
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.Tvena" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>ml/mnt </VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <span> T. Arteri : </span>
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TArteri" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>ml/mnt </VButton>
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline mt-6">
                  <h1 class="ml-3" style=" font-weight: bold;"> Program Profiling :</h1>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <span> Iso UF : </span>
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.IsoUF" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>ml </VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <span> Vaskular Akses : </span>
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.vaskularAkses" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline mt-6">
                  <h1 class="ml-3" style=" font-weight: bold;"> UF Prifiling Mode :</h1>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <span> Lama Iso UF : </span>
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.LamaIsoUF" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>jam </VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2 mt-5">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="AvShunt" label="AV Shunt"
                          v-model="input.avShunt" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2 mt-5" v-if="input.avShunt">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Kiri" label="Kiri"
                          v-model="input.avShuntKiri" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2 mt-5" v-if="input.avShunt">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Kanan" label="Kanan"
                          v-model="input.avShuntKanan" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline mt-6">
                  <h1 class="ml-3" style=" font-weight: bold;"> Na Prifiling Mode :</h1>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-2 mt-5">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="CDL" label="CDL" v-model="input.CDL" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2 mt-5" v-if="input.CDL">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Jugularis" label="Jugularis"
                          v-model="input.CDLJugularis" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2 mt-5" v-if="input.CDL">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Subclavia" label="Subclavia"
                          v-model="input.CDLSubclavia" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline mt-6">
                  <h1 class="ml-3" style=" font-weight: bold;"> Bicarbonat Profiling :</h1>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-2 mt-5">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Femoral" label="Femoral"
                          v-model="input.Femoral" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2 mt-5" v-if="input.Femoral">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Kiri" label="Kiri"
                          v-model="input.FemoralKiri" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2 mt-5" v-if="input.Femoral">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Kanan" label="Kanan"
                          v-model="input.FemoralKanan" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline mt-6">
                  <h1 class="ml-3" style=" font-weight: bold;"> Dialisat :</h1>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-2 mt-5">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Bicarbonat" label="Bicarbonat"
                          v-model="input.Bicarbonat" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2 mt-5">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Conductivity" label="Conductivity"
                          v-model="input.Conductivity" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2 mt-4" v-if="input.Conductivity">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="..." v-model="input.ConductivityKet" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2 mt-5">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Temperatur" label="Temperatur"
                          v-model="input.Temperatur" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2 mt-4" v-if="input.Temperatur">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="..." v-model="input.TemperaturKet" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2 mt-5">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Kalium" label="Kalium"
                          v-model="input.Kalium" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2 mt-4" v-if="input.Kalium">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="..." v-model="input.KaliumKet" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2 mt-5">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="BaseNa" label="Base Na"
                          v-model="input.BaseNa" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2 mt-4" v-if="input.BaseNa">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" placeholder="..." v-model="input.BaseNaKet" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-12">
                    <span> Obat rutin : </span>
                    <VField>
                      <VTextarea rows="3" placeholder="...." v-model="input.obatRutin"></VTextarea>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <span> Observasi Medis : </span>
                    <VField>
                      <VTextarea rows="3" placeholder="...." v-model="input.observasiMedis"></VTextarea>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <span> Jam Observasi : </span>
                    <VField>
                      <VDatePicker v-model="input.jamObservasi" mode="dateTime" style="width: 100%" trim-weeks
                        :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }">
                          <VField>
                            <VControl icon="feather:clock" fullwidth>
                              <VInput :value="inputValue" placeholder="Jam Observasi" v-on="inputEvents" />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline mt-6">
                  <h1 class="ml-3" style=" font-weight: bold;"> Heparnasi :</h1>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <span> Dosis Sirkulasi : </span>
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.dosisSirkulasi" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>IU</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <span> Dosis Awal : </span>
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.dosisAwal" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>IU</VButton>
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline mt-6">
                  <h1 class="ml-3" style=" font-weight: bold;"> Dosis Maintenance :</h1>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <span> Kontinyu : </span>
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.Kontinyu" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>IU/Jam</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <span> Intermitten : </span>
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.intermitten" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>IU/Jam</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <span> LMWH : </span>
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.LMWH" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <span> Tanpa Hepain : </span>
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.tanpaHepain" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </Fieldset>
        </div>
        <div class="column is-12">
          <Fieldset class="p-fieldsets" legend="TINDAKAN KEPERAWATAN" :toggleable="true">
            <div class="columns is-multiline mb-5">
              <div class="column is-12 P-0">
                <div class="columns is-multiline">
                  <div class="column is-12">
                    <div style="overflow-y:auto;" class="mt-1">
                      <table class="tg" style="width:200% ">
                        <thead>
                          <tr>
                            <th rowspan="2" class="td-fkprj" width="2%"
                              style="vertical-align:inherit;text-align: center;font-size:9pt;">
                              No
                            </th>
                            <th rowspan="2" class="td-fkprj" width="2%"
                              style="vertical-align:inherit;text-align: center;font-size:9pt;">
                              Jam
                            </th>
                            <th rowspan="2" class="td-fkprj" width="8%"
                              style="vertical-align:inherit;text-align: center;font-size:9pt;">
                              QB (ml/mnt)
                            </th>
                            <th rowspan="2" class="td-fkprj" width="8%"
                              style="vertical-align:inherit;text-align: center;font-size:9pt;">
                              QD (ml/mnt)
                            </th>
                            <th rowspan="2" class="td-fkprj" width="8%"
                              style="vertical-align:inherit;text-align: center;font-size:9pt;">
                              UF Rate (ml)
                            </th>
                            <th rowspan="2" class="td-fkprj" width="8%"
                              style="vertical-align:inherit;text-align: center;font-size:9pt;">
                              Nadi (x/mt)
                            </th>
                            <th rowspan="2" class="td-fkprj" width="8%"
                              style="vertical-align:inherit;text-align: center;font-size:9pt;">
                              Suhu Pasien (<sup>o</sup>C)
                            </th>
                            <th rowspan="2" class="td-fkprj" width="8%"
                              style="vertical-align:inherit;text-align: center;font-size:9pt;">
                              Resep (x/mnt)
                            </th>
                            <th colspan="3" width="20%" class="td-fkprj"
                              style="vertical-align:inherit;text-align: center;font-size:9pt;">
                              Intake (ml)
                            </th>
                            <th rowspan="2" class="td-fkprj" width="8%"
                              style="vertical-align:inherit;text-align: center;font-size:9pt;">
                              Output (ml) UF Volume
                            </th>
                            <th rowspan="2" class="td-fkprj" width="12%"
                              style="vertical-align:inherit;text-align: center;font-size:9pt;">
                              Keterangan, Paraf & Nama Jelas
                            </th>
                          </tr>
                          <tr>
                            <th class="td-fkprj"
                              style="vertical-align:inherit;text-align: center;font-size:9pt;">
                             NaCl 0,9%
                            </th>
                            <th class="td-fkprj"
                              style="vertical-align:inherit;text-align: center;font-size:9pt;">
                             Minum
                            </th>
                            <th class="td-fkprj"
                              style="vertical-align:inherit;text-align: center;font-size:9pt;">
                             Lain-Lain
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="tg-0lax">
                              <div class="column is-2">
                                <p class="mt-5">1</p>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="time" class="input mt-4" v-model="input.jamTindakanKeperawatan1" />
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.qbTidakanKeperawatan1" />
                                  </VControl>
                                  <VControl class="field-addon-body mt-4">
                                    <VButton static>ml/mnt </VButton>
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.qdTidakanKeperawatan1" />
                                  </VControl>
                                  <VControl class="field-addon-body mt-4">
                                    <VButton static>ml/mnt </VButton>
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.ufRateTindakanKeperawatan1" />
                                  </VControl>
                                  <VControl class="field-addon-body mt-4">
                                    <VButton static>ml </VButton>
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.nadiTindakanKeperawatan1" />
                                  </VControl>
                                  <VControl class="field-addon-body mt-4">
                                    <VButton static>(x/mt) </VButton>
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.suhuTindakanKeperawatan1" />
                                  </VControl>
                                  <VControl class="field-addon-body mt-4">
                                    <VButton static><sup>o</sup>C </VButton>
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.resepTindakanKeperawatan1" />
                                  </VControl>
                                  <VControl class="field-addon-body mt-4">
                                    <VButton static>(x/mt) </VButton>
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.naclTindakanKeperawatan1" />
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.minumTindakanKeperawatan1" />
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.lainTindakanKeperawatan1" />
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4"
                                      v-model="input.OutputVolumeTindakanKeperawatan1" />
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td>
                              <div class="column  pb-0">
                                <VField>
                                  <VTextarea rows="2" placeholder="Keterangan....."
                                    v-model="input.ketTindakanKeperawatan1">
                                  </VTextarea>
                                </VField>
                                <VCard>
                                  <div>
                                    <TandaTangan :elemenID="'signature_1'" :height="'180'"></TandaTangan>
                                  </div>
                                  <div>
                                    <h1 class="p-0" style="font-weight: bold;">Petugas Perawat</h1>
                                    <VField>
                                      <VControl class="prime-auto">
                                        <AutoComplete v-model="input.petugasPerawat1" :suggestions="d_Perawat"
                                          @complete="fetchPerawat($event)" :optionLabel="'label'" :dropdown="true"
                                          :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                          :field="'label'" placeholder="Pegawai..." class="mt-2"
                                          @item-select="setTandaTangan($event)" />
                                      </VControl>
                                    </VField>
                                  </div>
                                </VCard>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column p-1">

                              </div>
                            </td>
                          </tr>
                          <tr>
                            <td class="tg-0lax">
                              <div class="column is-2">
                                <p class="mt-5">2</p>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="time" class="input mt-4" v-model="input.jamTindakanKeperawatan2" />
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.qbTidakanKeperawatan2" />
                                  </VControl>
                                  <VControl class="field-addon-body mt-4">
                                    <VButton static>ml/mnt </VButton>
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.qdTidakanKeperawatan2" />
                                  </VControl>
                                  <VControl class="field-addon-body mt-4">
                                    <VButton static>ml/mnt </VButton>
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.ufRateTindakanKeperawatan2" />
                                  </VControl>
                                  <VControl class="field-addon-body mt-4">
                                    <VButton static>ml</VButton>
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.nadiTindakanKeperawatan2" />
                                  </VControl>
                                  <VControl class="field-addon-body mt-4">
                                    <VButton static>(x/mt)</VButton>
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.suhuTindakanKeperawatan2" />
                                  </VControl>
                                  <VControl class="field-addon-body mt-4">
                                    <VButton static><sup>o</sup>C</VButton>
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.resepTindakanKeperawatan2" />
                                  </VControl>
                                  <VControl class="field-addon-body mt-4">
                                    <VButton static>(x/mt)</VButton>
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.naclTindakanKeperawatan2" />
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.minumTindakanKeperawatan2" />
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.lainTindakanKeperawatan2" />
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4"
                                      v-model="input.OutputVolumeTindakanKeperawatan2" />
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField>
                                  <VTextarea rows="2" placeholder="Keterangan....."
                                    v-model="input.ketTindakanKeperawatan2">
                                  </VTextarea>
                                </VField>
                                <VCard>
                                  <div>
                                    <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan>
                                  </div>
                                  <div>
                                    <h1 class="p-0" style="font-weight: bold;">Petugas Perawat</h1>
                                    <VField>
                                      <VControl class="prime-auto">
                                        <AutoComplete v-model="input.petugasPerawat2" :suggestions="d_Perawat"
                                          @complete="fetchPerawat($event)" :optionLabel="'label'" :dropdown="true"
                                          :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                          :field="'label'" placeholder="Pegawai..." class="mt-2"
                                          @item-select="setTandaTangan($event)" />
                                      </VControl>
                                    </VField>
                                  </div>
                                </VCard>
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <td class="tg-0lax">
                              <div class="column is-2">
                                <p class="mt-5">3</p>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="time" class="input mt-4" v-model="input.jamTindakanKeperawatan3" />
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.qbTidakanKeperawatan3" />
                                  </VControl>
                                  <VControl class="field-addon-body mt-4">
                                    <VButton static>ml/mnt </VButton>
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.qdTidakanKeperawatan3" />
                                  </VControl>
                                  <VControl class="field-addon-body mt-4">
                                    <VButton static>ml/mnt </VButton>
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.ufRateTindakanKeperawatan3" />
                                  </VControl>
                                  <VControl class="field-addon-body mt-4">
                                    <VButton static>ml</VButton>
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.nadiTindakanKeperawatan3" />
                                  </VControl>
                                  <VControl class="field-addon-body mt-4">
                                    <VButton static>(x/mt)</VButton>
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.suhuTindakanKeperawatan3" />
                                  </VControl>
                                  <VControl class="field-addon-body mt-4">
                                    <VButton static><sup>o</sup>C</VButton>
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.resepTindakanKeperawatan3" />
                                  </VControl>
                                  <VControl class="field-addon-body mt-4">
                                    <VButton static>(x/mt)</VButton>
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.naclTindakanKeperawatan3" />
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.minumTindakanKeperawatan3" />
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.lainTindakanKeperawatan3" />
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4"
                                      v-model="input.OutputVolumeTindakanKeperawatan3" />
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField>
                                  <VTextarea rows="2" placeholder="Keterangan....."
                                    v-model="input.ketTindakanKeperawatan3">
                                  </VTextarea>
                                </VField>
                                <VCard>
                                  <div>
                                    <TandaTangan :elemenID="'signature_3'" :width="'180'" :height="'180'"></TandaTangan>
                                  </div>
                                  <div>
                                    <h1 class="p-0" style="font-weight: bold;">Petugas Perawat</h1>
                                    <VField>
                                      <VControl class="prime-auto">
                                        <AutoComplete v-model="input.petugasPerawat3" :suggestions="d_Perawat"
                                          @complete="fetchPerawat($event)" :optionLabel="'label'" :dropdown="true"
                                          :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                          :field="'label'" placeholder="Pegawai..." class="mt-2"
                                          @item-select="setTandaTangan($event)" />
                                      </VControl>
                                    </VField>
                                  </div>
                                </VCard>
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <td class="tg-0lax">
                              <div class="column is-2">
                                <p class="mt-5">4</p>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="time" class="input mt-4" v-model="input.jamTindakanKeperawatan4" />
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.qbTidakanKeperawatan4" />
                                  </VControl>
                                  <VControl class="field-addon-body mt-4">
                                    <VButton static>ml/mnt </VButton>
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.qdTidakanKeperawatan4" />
                                  </VControl>
                                  <VControl class="field-addon-body mt-4">
                                    <VButton static>ml/mnt </VButton>
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.ufRateTindakanKeperawatan4" />
                                  </VControl>
                                  <VControl class="field-addon-body mt-4">
                                    <VButton static>ml</VButton>
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.nadiTindakanKeperawatan4" />
                                  </VControl>
                                  <VControl class="field-addon-body mt-4">
                                    <VButton static>(x/mt)</VButton>
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.suhuTindakanKeperawatan4" />
                                  </VControl>
                                  <VControl class="field-addon-body mt-4">
                                    <VButton static><sup>o</sup>C</VButton>
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.resepTindakanKeperawatan4" />
                                  </VControl>
                                  <VControl class="field-addon-body mt-4">
                                    <VButton static>(x/mt)</VButton>
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.naclTindakanKeperawatan4" />
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.minumTindakanKeperawatan4" />
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.lainTindakanKeperawatan4" />
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4"
                                      v-model="input.OutputVolumeTindakanKeperawatan4" />
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField>
                                  <VTextarea rows="2" placeholder="Keterangan....."
                                    v-model="input.ketTindakanKeperawatan4">
                                  </VTextarea>
                                </VField>
                                <VCard>
                                  <div>
                                    <TandaTangan :elemenID="'signature_4'" :width="'180'" :height="'180'"></TandaTangan>
                                  </div>
                                  <div>
                                    <h1 class="p-0" style="font-weight: bold;">Petugas Perawat</h1>
                                    <VField>
                                      <VControl class="prime-auto">
                                        <AutoComplete v-model="input.petugasPerawat4" :suggestions="d_Perawat"
                                          @complete="fetchPerawat($event)" :optionLabel="'label'" :dropdown="true"
                                          :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                          :field="'label'" placeholder="Pegawai..." class="mt-2"
                                          @item-select="setTandaTangan($event)" />
                                      </VControl>
                                    </VField>
                                  </div>
                                </VCard>
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <td class="tg-0lax">
                              <div class="column is-2">
                                <p class="mt-5">5</p>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="time" class="input mt-4" v-model="input.jamTindakanKeperawatan5" />
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.qbTidakanKeperawatan5" />
                                  </VControl>
                                  <VControl class="field-addon-body mt-4">
                                    <VButton static>ml/mnt </VButton>
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.qdTidakanKeperawatan5" />
                                  </VControl>
                                  <VControl class="field-addon-body mt-4">
                                    <VButton static>ml/mnt </VButton>
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.ufRateTindakanKeperawatan5" />
                                  </VControl>
                                  <VControl class="field-addon-body mt-4">
                                    <VButton static>ml</VButton>
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.nadiTindakanKeperawatan5" />
                                  </VControl>
                                  <VControl class="field-addon-body mt-4">
                                    <VButton static>(x/mt)</VButton>
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.suhuTindakanKeperawatan5" />
                                  </VControl>
                                  <VControl class="field-addon-body mt-4">
                                    <VButton static><sup>o</sup>C</VButton>
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.resepTindakanKeperawatan5" />
                                  </VControl>
                                  <VControl class="field-addon-body mt-4">
                                    <VButton static>(x/mt)</VButton>
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.naclTindakanKeperawatan5" />
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.minumTindakanKeperawatan5" />
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4" v-model="input.lainTindakanKeperawatan5" />
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input mt-4"
                                      v-model="input.OutputVolumeTindakanKeperawatan5" />
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="tg-0lax">
                              <div class="column pt-3 pb-0">
                                <VField>
                                  <VTextarea rows="2" placeholder="Keterangan....."
                                    v-model="input.ketTindakanKeperawatan5">
                                  </VTextarea>
                                </VField>
                                <VCard>
                                  <div>
                                    <TandaTangan :elemenID="'signature_5'" :width="'180'" :height="'180'"></TandaTangan>
                                  </div>
                                  <div>
                                    <h1 class="p-0" style="font-weight: bold;">Petugas Perawat</h1>
                                    <VField>
                                      <VControl class="prime-auto">
                                        <AutoComplete v-model="input.petugasPerawat5" :suggestions="d_Perawat"
                                          @complete="fetchPerawat($event)" :optionLabel="'label'" :dropdown="true"
                                          :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                          :field="'label'" placeholder="Pegawai..." class="mt-2"
                                          @item-select="setTandaTangan($event)" />
                                      </VControl>
                                    </VField>
                                  </div>
                                </VCard>
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
          </Fieldset>
        </div>
        <div class="column is-12">
          <Fieldset class="p-fieldsets" legend="PENYAKIT SELAMA HD" :toggleable="true">
            <div class="columns is-multiline mb-5">
              <div class="column is-12 P-0">
                <div class="columns is-multiline">
                  <div class="column is-2">
                    <span>Klinis :</span>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in penyulitSemalaHd">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.penyulitSemalaHd" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4" v-if="input.penyulitSemalaHd == 'LainLain'">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Lain-Lain... "
                          v-model="input.penyulitSemalaHdLain" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-2">
                    <span>Teknis :</span>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in Teknis">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Teknis" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4" v-if="input.Teknis == 'LainLain'">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Lain-Lain... " v-model="input.TeknisLain" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </Fieldset>
        </div>
        <div class="column is-12">
          <Fieldset class="p-fieldsets" legend="EVALUASI KEPERAWATAN" :toggleable="true">
            <div class="columns is-multiline mb-5">
              <div class="column is-12 P-0">
                <div class="column is-12">
                  <VField>
                    <VTextarea rows="3" placeholder="Evaluasi Keperawatan" v-model="input.evaluasiKeperawatan">
                    </VTextarea>
                  </VField>
                </div>
              </div>
            </div>
            <div class="columns is-multiline mt-6">
              <h1 class="ml-3" style=" font-weight: bold;"> Perencanaan Pulang (gunakan form edukasi jika diperlukan)</h1>
            </div>
            <div class="columns is-multiline">
              <div class="column is-3">
                <h5>Pembatasan Asupan Cairan :</h5>
              </div>
              <div class="column is-4" v-for="(data, i) in pembatasanAsupanCairan">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.pembatasanAsupanCairan" :true-value="data.value" :label="data.label"
                      class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-2">
                <h5>Perawatan Akses :</h5>
              </div>
              <div class="column is-2" v-for="(data, i) in perawatanAkses">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.perawatanAkses" :true-value="data.value" :label="data.label" class="p-0"
                      color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="column is-4" v-if="input.perawatanAkses == 'LainLain'">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Lain-Lain... " v-model="input.perawatanAksesLain" />
                  </VControl>
                </VField>
              </div>
            </div>
          </Fieldset>
        </div>
      </div>
    </div>
    <div class="column is-12">
      <div class="columns is-multiline">
        <div class="column is-6">
          <h1 class="p-0" style="font-weight: bold;">Akses Vaskular Oleh</h1>
          <TandaTangan class="mt-1" :elemenID="'signature_7'" :width="'180'" :height="'180'"></TandaTangan>
        </div>
        <div class="column is-6 ">
          <h1 class="p-0" style="font-weight: bold;">Tanda Tangan Perawat yang Bertugas</h1>
          <TandaTangan class="mt-1" :elemenID="'signature_6'" :width="'180'" :height="'180'"></TandaTangan>
        </div>
        <div class="column is-4">
          <h1 class="p-0" style="font-weight: bold;">Askes Veskular</h1>
          <VField>
            <VControl class="prime-auto">
              <AutoComplete v-model="input.petugasAskesVeskular" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" placeholder="Pegawai..." class="mt-2" @item-select="setTandaTangan($event)" />
            </VControl>
          </VField>
        </div>
        <div class="column is-4 is-offset-2">
          <h1 class="p-0" style="font-weight: bold;">Perawat Bertugas</h1>
          <VField>
            <VControl class="prime-auto">
              <AutoComplete v-model="input.petugasPerawat6" :suggestions="d_Perawat" @complete="fetchPerawat($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" placeholder="Pegawai..." class="mt-2" @item-select="setTandaTangan($event)" />
            </VControl>
          </VField>
        </div>
      </div>
    </div>
    <div class="column is-12">
      <Fieldset class="p-fieldsets" legend="EVALUASI KEPERAWATAN" :toggleable="true">
        <div class="columns is-multiline mb-5">
          <div class="column is-6">
            <span>Obat Tambahan</span>
            <VField>
              <VTextarea rows="3" placeholder="Obat Tambahan" v-model="input.obatTambahan">
              </VTextarea>
            </VField>
          </div>
          <div class="column is-6">
            <span>Evaluasi Medis</span>
            <VField>
              <VTextarea rows="3" placeholder="Evaluasi Medis" v-model="input.evaluasiMedis">
              </VTextarea>
            </VField>
          </div>
        </div>
      </Fieldset>
    </div>
    <div class="column is-4" style="margin-left: auto;">
      <VCard>
        <div>
          <TandaTangan :elemenID="'signature_8'" :width="'180'" :height="'180'"></TandaTangan>
        </div>
        <div>
          <h1 class="p-0" style="font-weight: bold;">Tanda Tangan dan Nama Dokter</h1>
          <VField>
            <VControl class="prime-auto">
              <AutoComplete v-model="input.dokterPengkajian" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" placeholder="Dokter..." class="mt-2" @item-select="setTandaTangan($event)" />
            </VControl>
          </VField>
        </div>
      </VCard>
    </div>
  </VCard>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
import Checkbox from 'primevue/checkbox';
import Image from 'primevue/image';
import Fieldset from 'primevue/fieldset';
import * as EMR from '../page-emr-plugins/asuhan-keperawatan-pasien-hemodialisis'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'



let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let riwayatAlergiObat = ref(EMR.riwayatAlergiObat())
let keadaanUmum = ref(EMR.keadaanUmum())
let kesadaran = ref(EMR.kesadaran())
let tekananDarah = ref(EMR.tekananDarah())
let nadi = ref(EMR.nadi())
let respirasi = ref(EMR.respirasi())
let konjungtiva = ref(EMR.konjungtiva())
let ekstremitas = ref(EMR.ekstremitas())
let resikoJatuhMorse = ref(EMR.listResikoJatuh())
let ketJumlahPoin = ref(EMR.descripJmlPoin())
let kesimpulan = ref(EMR.kesimpulan())
let keyakinanBerkaitPelayanan = ref(EMR.keyakinanBerkaitPelayanan())
let kendaliKomunikasi = ref(EMR.kendaliKomunikasi())
let yangMerawatDirumah = ref(EMR.yangMerawatDirumah())
let kondisiSaatIni = ref(EMR.kondisiSaatIni())
let diagnosaKeperawatan = ref(EMR.diagnosaKeperawatan())
let intervensiKeperawatan = ref(EMR.intervensiKeperawatan())
let kolaborasi = ref(EMR.kolaborasi())
let resepHd = ref(EMR.resepHd())
let index = ref(EMR.index())
let qbTidakanKeperawatan = ref(EMR.qbTidakanKeperawatan())
let jamTindakanKeperawatan = ref(EMR.jamTindakanKeperawatan())
let penyulitSemalaHd = ref(EMR.penyulitSemalaHd())
let Teknis = ref(EMR.Teknis())
let pembatasanAsupanCairan = ref(EMR.pembatasanAsupanCairan())
let perawatanAkses = ref(EMR.perawatanAkses())
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
  }
)
const route = useRoute()
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const loadData: any = ref(true)
const jumlahImdex = ref(1)
const d_Pegawai: any = ref([])
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
  tidakNyeri: [],
  sedikitNyeri: [],
  cukupNyeri: [],
  lumayanNyeri: [],
  sangatNyeri: [],
  nyeriBerat: [],

  totalSkor: [],

  detailsTglSkor: [{
    no: 1,
    tgl: new Date(),
  }]
})
const d_Dokter: any = ref([])
const d_Perawat: any = ref([])
const d_Ruangan: any = ref([])
const d_Diagnosa: any = ref([])
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
watch(() => [
  input.value.pasienPernahJatuh,
  input.value.berpenyakitBanyak,
  input.value.alatBantuJalan,
  input.value.terpasangInfus,
  input.value.gayaBerjalan,
  input.value.statusMental,
], () => {

  let poin1 = input.value.pasienPernahJatuh ? parseInt(input.value.pasienPernahJatuh.poin) : 0
  let poin2 = input.value.berpenyakitBanyak ? parseInt(input.value.berpenyakitBanyak.poin) : 0
  let poin3 = input.value.alatBantuJalan ? parseInt(input.value.alatBantuJalan.poin) : 0
  let poin4 = input.value.terpasangInfus ? parseInt(input.value.terpasangInfus.poin) : 0
  let poin5 = input.value.gayaBerjalan ? parseInt(input.value.gayaBerjalan.poin) : 0
  let poin6 = input.value.statusMental ? parseInt(input.value.statusMental.poin) : 0

  const total = poin1 + poin2 + poin3 + poin4 + poin5 + poin6
  input.value.totalNilaiRJM = total
  indikatorRangeNilai(total)
})
const fetchPerawat = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Perawat.value = response
  })
}

const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}
const setTandaTangan = async (e: any) => {
  const response = await useApi().get(
    `/emr/tanda-tangan/${e.value.value}`)
  if (response != null) {
    H.tandaTangan().set("signature_1", response.ttd)
    input.value.tandaTanganPerawat = response.ttd
  } else {
    H.tandaTangan().set("signature_1", '')
  }
}
const indikatorRangeNilai = (e: any) => {
  let tidakBeresiko = {
    "keterangan": "Tidak Beresiko",
    "rangeNilai": 24
  }
  let resikoRendah = {
    "keterangan": "Resiko Rendah",
    "rangeNilai": 45,
  }
  let resikoTinggi = {
    "keterangan": "Resiko Tinggi",
    "rangeNilai": 46,
  }

  ketJumlahPoin.value.forEach((element: any) => {
    if (e <= 24 && e <= element.value.rangeNilai) {
      input.value.tingkatResiko = tidakBeresiko
    }
    else if (e <= 45 && e <= element.value.rangeNilai) {
      input.value.tingkatResiko = resikoRendah
    }
    else if (e > 46 && e > element.value.rangeNilai) {
      input.value.tingkatResiko = resikoTinggi
    }

  })
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
const loadRiwayat = async () => {
  // if (NOREC_EMRPASIEN.value == '') return
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
      if (response.length) {
        input.value = response[0] //set ke inputan
        if (response[0].tandaTanganPerawat1) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganPerawat1)
        }
        if (response[0].tandaTanganPerawat2) {
          H.tandaTangan().set("signature_2", response[0].tandaTanganPerawat2)
        }
        if (response[0].tandaTanganPerawat3) {
          H.tandaTangan().set("signature_3", response[0].tandaTanganPerawat3)
        }
        if (response[0].tandaTanganPerawat4) {
          H.tandaTangan().set("signature_4", response[0].tandaTanganPerawat4)
        }
        if (response[0].tandaTanganPerawat5) {
          H.tandaTangan().set("signature_5", response[0].tandaTanganPerawat5)
        }
        if (response[0].tandaTanganPerawat6) {
          H.tandaTangan().set("signature_6", response[0].tandaTanganPerawat6)
        }
        if (response[0].tandaTanganAskes) {
          H.tandaTangan().set("signature_7", response[0].tandaTanganAskes)
        }
        if (response[0].tandaTanganDokter) {
          H.tandaTangan().set("signature_8", response[0].tandaTanganDokter)
        }
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      }
}

const simpan = () => {
  console.log(COLLECTION.value)
  debugger
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.tandaTanganPerawat1 = H.tandaTangan().get("signature_1")
  object.tandaTanganPerawat2 = H.tandaTangan().get("signature_2")
  object.tandaTanganPerawat3 = H.tandaTangan().get("signature_3")
  object.tandaTanganPerawat4 = H.tandaTangan().get("signature_4")
  object.tandaTanganPerawat5 = H.tandaTangan().get("signature_5")
  object.tandaTanganPerawat6 = H.tandaTangan().get("signature_6")
  object.tandaTanganAskes = H.tandaTangan().get("signature_7")
  object.tandaTanganDokter = H.tandaTangan().get("signature_8")
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
const print = async () => {
    // await useApi().get(`emr/cetak?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    H.printBlade(`emr/cetak-asesmen-awal-keper-ri?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
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
  input.value.tglPembuatan = new Date()
  input.value.namaruangan = props.registrasi.namaruangan

}
setView()
setAutoFill()


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

.p-fieldset-legend {
  margin-left: 15px;
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
