<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top: 15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Asesmen Gizi Dewasa Rawat Jalan</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
            </div>
          </div>
        </div>

        <div class="column is-flex p-0">
          <div class="column is-2">
            <h1 style="font-weight: bold">Nama Pasien:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput v-model="input.namaPasien" class="input" disabled />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column is-flex p-0">
          <div class="column is-2">
            <h1 style="font-weight: bold">No RM:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput v-model="input.norm" class="input" disabled />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column is-flex p-0">
          <div class="column is-2">
            <h1 style="font-weight: bold">TANGGAL LAHIR PASIEN:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" placeholder="Tanggal" disabled />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>
        </div>

        <div class="column is-flex p-0">
          <div class="column is-2">
            <h1 style="font-weight: bold">JENIS KELAMIN:</h1>
          </div>
          <div class="column is-10" style="display: flex">
            <VField v-for="items in JenisKelamin" :key="items.value">
              <VControl raw subcontrol>
                <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label" :label="items.label"
                  color="primary" disabled circle />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="is-flex p-0">
          <div class="column is-2">
            <h1 style="font-weight: bold">Ruangan:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <AutoComplete v-model="input.ruanganSelanjutnya" :suggestions="d_Ruangan"
                  @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                  placeholder="ketik untuk mencari ruangan..." />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column is-flex p-0">
          <div class="column is-2">
            <h1 style="font-weight: bold">TANGGAL & JAM KEDATANGAN:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VDatePicker v-model="input.tanggalJamKedatangan" mode="dateTime" style="width: 100%" trim-weeks
                :max-date="new Date()">
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" placeholder="" v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>
        </div>
        <div class="column p-0">
          <Fieldset legend="ASESMEN GIZI" :toggleable="true">
            <Fieldset legend="1. Antropometri" :toggleable="true">
              <div class="columns is-multiline">
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-3">
                      <h1 style="font-weight: bold">Berat Badan</h1>
                      <VField addons>
                        <VControl expanded>
                          <VInput type="text" class="input" placeholder="Berat Badan" v-model="input.beratbadanObgyn" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>kg</VButton>
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <h1 style="font-weight: bold">Tinggi Badan</h1>
                      <VField addons>
                        <VControl expanded>
                          <VInput type="text" class="input" placeholder="Tinggi Badan"
                            v-model="input.tinggibadanObgyn" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>cm</VButton>
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <h1 style="font-weight: bold">Estimasi tinggi badan (cm)</h1>
                      <VField addons>
                        <VControl expanded>
                          <VInput type="text" class="input" placeholder="Estimasi Tinggi Badan"
                            v-model="input.estimasiTinggiBadan" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>cm</VButton>
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <h1 style="font-weight: bold">Berat badan ideal (kg)</h1>
                      <VField addons>
                        <VControl expanded>
                          <VInput type="text" class="input" placeholder="Berat Bada Ideal"
                            v-model="input.beratbadanIdeal" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>kg</VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>

              <div class="columns is-multiline">
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-3">
                      <h1 style="font-weight: bold">BMI</h1>
                      <VField addons>
                        <VControl expanded>
                          <VInput type="text" class="input" placeholder="BMI" v-model="input.bmi" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>kg/m2</VButton>
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <h1 style="font-weight: bold">Status gizi</h1>
                      <VField addons>
                        <VControl expanded>
                          <VInput type="text" class="input" placeholder="Status Gizi" v-model="input.statusGizi" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static></VButton>
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <h1 style="font-weight: bold">Lingkar Lengan Atas (cm)</h1>
                      <VField addons>
                        <VControl expanded>
                          <VInput type="text" class="input" placeholder="Lingkar Lengan Atas"
                            v-model="input.lingkarLenganAtas" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>cm</VButton>
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <h1 style="font-weight: bold">Lingkar Perut (cm)</h1>
                      <VField addons>
                        <VControl expanded>
                          <VInput type="text" class="input" placeholder="Lingkar Perut" v-model="input.lingkarPerut" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>cm</VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </Fieldset>

            <Fieldset legend="2. Biokimia" :toggleable="true">
              <div class="columns is-flex p-0 is-12">
                <div class="column is-6">
                  <div v-for="item in BioKimias1" class="column p-0">
                    <VField>
                      <VControl>
                        <VCheckbox :label="item.label" v-model="input[item.model]" :true-value="item.value"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="column is-6">
                  <div v-for="item in BioKimias2" class="column p-0">
                    <VField>
                      <VControl>
                        <VCheckbox :label="item.label" v-model="input[item.model]" :true-value="item.value"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column p-0 is-flex">
                    <VField>
                      <VControl>
                        <VCheckbox label="lainnya" v-model="input.lainnya" true-value="Lainnya" color="primary" />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl expanded>
                        <VInput type="text" class="input" placeholder="Lainnya" v-model="input.keteranganLainnya" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </Fieldset>

            <Fieldset legend="3. Klinis/fisik :" :toggleable="true">
              <div class="columns is-multiline">
                <div class="column is-one-third p-0" v-for="(item, index) in KlinisFisik" :key="index">
                  <VField>
                    <VControl>
                      <VCheckbox :label="item.label" v-model="input[item.model]" :true-value="item.value"
                        color="primary" />
                    </VControl>
                  </VField>
                </div>
                <div class="column p-0 is-flex">
                  <VField>
                    <VControl>
                      <VCheckbox label="lainnya" v-model="input.lainnyaKlinisFisik" true-value="Lainnya"
                        color="primary" />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl expanded>
                      <VInput type="text" class="input" placeholder="Lainnya"
                        v-model="input.keteranganLainnyaKlinisFisik" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </Fieldset>

            <Fieldset legend="4. Dietary / Riwayat Gizi" :toggleable="true">
              <Fieldset legend="A. Pola Makan Dirumah" :toggleable="true">

                <Fieldset legend="makanan pokok" :toggleable="true">
                  <div class="columns is-flex is-12 p-0">
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Makanan Pokok" v-model="input.makananPokok" true-value="Makanan Pokok"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VInput placeholder="Keterangan Makanan Pokok" v-model="input.makananPokokKeterangan"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Setiap Hari" v-model="input.setiapHariMakananPokok" true-value="setiap Hari"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="1x" v-model="input.banyakMakananPokok" true-value="1x" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="2x" v-model="input.banyakMakananPokok" true-value="2x" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="3x" v-model="input.banyakMakananPokok" true-value="3x" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Seminggu" v-model="input.semingguMakananPokok" true-value="Seminggu"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="columns is-flex is-12 p-0">
                    <VField class="p-0">
                      <VControl>
                        <VInput placeholder="Keterangan Seminggu Makanan Pokok" v-model="input.semingguKeteranganMakananPokok"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Bulan" v-model="input.sebulanMakananPokok" true-value="Sebulan"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VInput placeholder="Keterangan Sebulan Makanan Pokok" v-model="input.sebulanKeteranganMakananPokok"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Tidak Ada" v-model="input.tidakAdaMakananPokok" true-value="Tidak Ada"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                </Fieldset>

                <Fieldset legend="Lauk Hewani" :toggleable="true">
                  <div class="columns is-flex is-12 p-0">
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Makanan Pokok" v-model="input.LaukHewani" true-value="Makanan Pokok"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VInput placeholder="Keterangan Makanan Pokok" v-model="input.LaukHewaniKeterangan"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Setiap Hari" v-model="input.setiapHariLaukHewani" true-value="setiap Hari"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="1x" v-model="input.banyakLaukHewani" true-value="1x" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="2x" v-model="input.banyakLaukHewani" true-value="2x" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="3x" v-model="input.banyakLaukHewani" true-value="3x" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Seminggu" v-model="input.semingguLaukHewani" true-value="Seminggu"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="columns is-flex is-12 p-0">
                    <VField class="p-0">
                      <VControl>
                        <VInput placeholder="Keterangan Seminggu Makanan Pokok" v-model="input.semingguKeteranganLaukHewani"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Bulan" v-model="input.sebulanLaukHewani" true-value="Sebulan"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VInput placeholder="Keterangan Sebulan Makanan Pokok" v-model="input.sebulanKeteranganLaukHewani"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Tidak Ada" v-model="input.tidakAdaLaukHewani" true-value="Tidak Ada"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                </Fieldset>

                <Fieldset legend="Lauk Nabati" :toggleable="true">
                  <div class="columns is-flex is-12 p-0">
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Makanan Pokok" v-model="input.LaukNabati" true-value="Makanan Pokok"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VInput placeholder="Keterangan Makanan Pokok" v-model="input.LaukNabatiKeterangan"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Setiap Hari" v-model="input.setiapHariLaukNabati" true-value="setiap Hari"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="1x" v-model="input.banyakLaukNabati" true-value="1x" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="2x" v-model="input.banyakLaukNabati" true-value="2x" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="3x" v-model="input.banyakLaukNabati" true-value="3x" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Seminggu" v-model="input.semingguLaukNabati" true-value="Seminggu"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="columns is-flex is-12 p-0">
                    <VField class="p-0">
                      <VControl>
                        <VInput placeholder="Keterangan Seminggu Makanan Pokok" v-model="input.semingguKeteranganLaukNabati"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Bulan" v-model="input.sebulanLaukNabati" true-value="Sebulan"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VInput placeholder="Keterangan Sebulan Makanan Pokok" v-model="input.sebulanKeteranganLaukNabati"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Tidak Ada" v-model="input.tidakAdaLaukNabati" true-value="Tidak Ada"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                </Fieldset>

                <Fieldset legend="Sayur" :toggleable="true">
                  <div class="columns is-flex is-12 p-0">
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Makanan Pokok" v-model="input.Sayur" true-value="Makanan Pokok"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VInput placeholder="Keterangan Makanan Pokok" v-model="input.SayurKeterangan"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Setiap Hari" v-model="input.setiapHariSayur" true-value="setiap Hari"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="1x" v-model="input.banyakSayur" true-value="1x" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="2x" v-model="input.banyakSayur" true-value="2x" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="3x" v-model="input.banyakSayur" true-value="3x" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Seminggu" v-model="input.semingguSayur" true-value="Seminggu"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="columns is-flex is-12 p-0">
                    <VField class="p-0">
                      <VControl>
                        <VInput placeholder="Keterangan Seminggu Makanan Pokok" v-model="input.semingguKeteranganSayur"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Bulan" v-model="input.sebulanSayur" true-value="Sebulan" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VInput placeholder="Keterangan Sebulan Makanan Pokok" v-model="input.sebulanKeteranganSayur"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Tidak Ada" v-model="input.tidakAdaSayur" true-value="Tidak Ada"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                </Fieldset>

                <Fieldset legend="Buah" :toggleable="true">
                  <div class="columns is-flex is-12 p-0">
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Makanan Pokok" v-model="input.Buah" true-value="Makanan Pokok"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VInput placeholder="Keterangan Makanan Pokok" v-model="input.BuahKeterangan" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Setiap Hari" v-model="input.setiapHariBuah" true-value="setiap Hari"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="1x" v-model="input.banyakBuah" true-value="1x" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="2x" v-model="input.banyakBuah" true-value="2x" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="3x" v-model="input.banyakBuah" true-value="3x" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Seminggu" v-model="input.semingguBuah" true-value="Seminggu"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="columns is-flex is-12 p-0">
                    <VField class="p-0">
                      <VControl>
                        <VInput placeholder="Keterangan Seminggu Makanan Pokok" v-model="input.semingguKeteranganBuah"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Bulan" v-model="input.sebulanBuah" true-value="Sebulan" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VInput placeholder="Keterangan Sebulan Makanan Pokok" v-model="input.sebulanKeteranganBuah"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Tidak Ada" v-model="input.tidakAdaBuah" true-value="Tidak Ada"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                </Fieldset>

                <Fieldset legend="Snack" :toggleable="true">
                  <div class="columns is-flex is-12 p-0">
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Makanan Pokok" v-model="input.Snack" true-value="Makanan Pokok"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VInput placeholder="Keterangan Makanan Pokok" v-model="input.SnackKeterangan"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Setiap Hari" v-model="input.setiapHariSnack" true-value="setiap Hari"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="1x" v-model="input.banyakSnack" true-value="1x" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="2x" v-model="input.banyakSnack" true-value="2x" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="3x" v-model="input.banyakSnack" true-value="3x" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Seminggu" v-model="input.semingguSnack" true-value="Seminggu"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="columns is-flex is-12 p-0">
                    <VField class="p-0">
                      <VControl>
                        <VInput placeholder="Keterangan Seminggu Makanan Pokok" v-model="input.semingguKeteranganSnack"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Bulan" v-model="input.sebulanSnack" true-value="Sebulan" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VInput placeholder="Keterangan Sebulan Makanan Pokok" v-model="input.sebulanKeteranganSnack"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Tidak Ada" v-model="input.tidakAdaSnack" true-value="Tidak Ada"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                </Fieldset>

                <Fieldset legend="Susu" :toggleable="true">
                  <div class="columns is-flex is-12 p-0">
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Makanan Pokok" v-model="input.Susu" true-value="Makanan Pokok"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VInput placeholder="Keterangan Makanan Pokok" v-model="input.SusuKeterangan" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Setiap Hari" v-model="input.setiapHariSusu" true-value="setiap Hari"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="1x" v-model="input.banyakSusu" true-value="1x" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="2x" v-model="input.banyakSusu" true-value="2x" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="3x" v-model="input.banyakSusu" true-value="3x" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Seminggu" v-model="input.semingguSusu" true-value="Seminggu"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="columns is-flex is-12 p-0">
                    <VField class="p-0">
                      <VControl>
                        <VInput placeholder="Keterangan Seminggu Makanan Pokok" v-model="input.semingguKeteranganSusu"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Bulan" v-model="input.sebulanSusu" true-value="Sebulan" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VInput placeholder="Keterangan Sebulan Makanan Pokok" v-model="input.sebulanKeteranganSusu"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Tidak Ada" v-model="input.tidakAdaSusu" true-value="Tidak Ada"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                </Fieldset>

              </Fieldset>

              <Fieldset legend="B. Pantangan Makan" :toggleable="true" class="p-0 mt-4">
                <div class="columns is-flex is-12 p-0">
                  <VField class="p-0">
                    <VControl>
                      <VCheckbox label="Tidak Ada" v-model="input.pantanganMakan" true-value="Tidak Ada"
                        color="primary" />
                    </VControl>
                  </VField>
                  <VField class="p-0">
                    <VControl>
                      <VCheckbox label="Ada, Jelaskan" v-model="input.pantanganMakan" true-value="Ada"
                        color="primary" />
                    </VControl>
                  </VField>
                  <VField class="p-0">
                    <VControl>
                      <VInput placeholder="Keterangan" v-model="input.pantanganMakanKeterangan" color="primary" />
                    </VControl>
                  </VField>
                </div>
              </Fieldset>

              <Fieldset legend="C. Asupan Makan" :toggleable="true" class="p-0 mt-4">
                <div class="columns is-flex is-12 p-0">
                  <VField class="p-0">
                    <VControl>
                      <VCheckbox label="< 80%" v-model="input.asupanMakan" true-value="< 80%" color="primary" />
                    </VControl>
                  </VField>
                  <VField class="p-0">
                    <VControl>
                      <VCheckbox label="≥80%" v-model="input.asupanMakan" true-value="≥80%" color="primary" />
                    </VControl>
                  </VField>

                </div>
              </Fieldset>

              <Fieldset legend="D. Alergi Makan" :toggleable="true" class="p-0 mt-4">
                <div class="columns is-flex is-12 p-0">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">Alergi Makan : </h1>
                  </div>
                  <div class="column">
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Tidak Ada" v-model="input.alergiMakanTidakAda" true-value="Tidak Ada"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Telur" v-model="input.alergiMakanTelur" true-value="Telur" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Susu sapi & produk olahannya " v-model="input.alergiMakanSusu"
                          true-value="Susu sapi & produk olahannya" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Kacang kedelai/tanah" v-model="input.alergiMakanKacang"
                          true-value="Kacang kedelai/tanah" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Gluten/gandum " v-model="input.alergiMakanGluten" true-value="Gluten/gandum "
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Hazelnut/almond" v-model="input.alergiMakanHazelnut"
                          true-value="Hazelnut/almond" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Udang" v-model="input.alergiMakanUdang" true-value="Udang" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Ikan" v-model="input.alergiMakanIkan" true-value="Ikan" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Lain-lain" v-model="input.alergiMakanLainLain" true-value="Lain-lain"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VInput placeholder="Keterangan" v-model="input.alergiMakan" color="primary" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </Fieldset>
              <Fieldset legend="E. Suplemen Gizi" :toggleable="true" class="p-0 mt-4">
                <div class="columns is-flex is-12 p-0">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">Suplemen Gizi : </h1>
                  </div>
                  <div class="column is-flex">
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Tanpa Suplemen Gizi" v-model="input.sumplemenGizi"
                          true-value="Tanpa Suplemen Gizi" color="primary" />
                      </VControl>
                    </VField>
                    <VField class="p-0">
                      <VControl>
                        <VCheckbox label="Dengan Sumplemen Gizi" v-model="input.sumplemenGizi"
                          true-value="Dengan Sumplemen Gizi" color="primary" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </Fieldset>
            </Fieldset>

            <Fieldset legend="5. Riwayat Personal" :toggleable="true">
              <div class="columns is-flex is-12 p-0">
                <div class="column is-2">
                  <h1 style="font-weight: bold">Riwayat Personal : </h1>
                </div>
                <div class="column is-10">
                  <VField class="p-0">
                    <VControl>
                      <VTextarea v-model="input.riwayatPersonal" color="primary" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </Fieldset>
          </Fieldset>
          <Fieldset legend="DIAGNOSIS GIZI" :toggleable="true">
            <div class="columns is-flex is-12 p-0">
              <div class="column is-2">
                <h1 style="font-weight: bold">Diagnosis Medis : </h1>
              </div>
              <div class="column is-10">
                <VField class="p-0">
                  <VControl>
                    <VTextarea v-model="input.diagnosisGizi" color="primary" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="columns is-flex is-12 p-0">
              <div class="column is-2">
                <h1 style="font-weight: bold">Diagnosis Gizi (PES = Problem, Etiology, Sign and Symptom): </h1>
              </div>
              <div class="column is-10">
                <VField class="p-0">
                  <VControl>
                    <VTextarea v-model="input.diagnosisGiziPES" color="primary" />
                  </VControl>
                </VField>
              </div>
            </div>
          </Fieldset>
          <Fieldset legend="RENCANA EDUKASI/KONSULTASI GIZI" :toggleable="true">
            <div class="columns is-flex is-12 p-0">
              <div class="column is-2">
                <h1 style="font-weight: bold">Diet : </h1>
              </div>
              <div class="column is-10">
                <VField class="p-0">
                  <VControl>
                    <VInput v-model="input.dietRencanaEdukasi" color="primary" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="columns is-flex is-12 p-0">
              <div class="column is-2">
                <h1 style="font-weight: bold">Tujuan Edukasi Diet : </h1>
              </div>
              <div class="column is-10">
                <VField class="p-0">
                  <VControl>
                    <VTextarea v-model="input.TujuanRencanaEdukasi" color="primary" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="columns is-flex is-12 p-0">
              <div class="column is-2">
                <h1 style="font-weight: bold">Kebutuhan Nutrisi : </h1>
              </div>
              <div class="column is-10 is-flex">
                <div class="columns is-multiline">
                  <div class="column is-12">
                    <div class="columns is-multiline">
                      <div class="column is-6">
                        <h1 style="font-weight: bold">Energi</h1>
                        <VField addons>
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Energi"
                              v-model="input.energiKebutuhanNutrisi" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>Kilokalori</VButton>
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-6">
                        <h1 style="font-weight: bold">Protein</h1>
                        <VField addons>
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Protein"
                              v-model="input.ProteinKebutuhanNutrisi" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>gr</VButton>
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-6">
                        <h1 style="font-weight: bold">Zat Gizi Lainnya</h1>
                        <VField addons>
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Zat Gizi Lainnya"
                              v-model="input.lainnyaKebutuhanNutrisi" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>gr</VButton>
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="columns is-flex is-12 p-0">
              <div class="column is-2">
                <h1 style="font-weight: bold">Bentuk makanan : </h1>
              </div>
              <div class="column is-flex is-10">
                <VField class="mr-2">
                  <VControl>
                    <VCheckbox v-model="input.bentukMakanan" label="Nasi" true-value="Nasi" class="p-0"
                      color="primary" />
                  </VControl>
                </VField>
                <VField class="mr-2">
                  <VControl>
                    <VCheckbox v-model="input.bentukMakanan" label="Bubur/tim" true-value="Bubur/tim" class="p-0"
                      color="primary" />
                  </VControl>
                </VField>
                <VField class="mr-2">
                  <VControl>
                    <VCheckbox v-model="input.bentukMakanan" label="Bubur saring/cincang"
                      true-value="Bubur saring/cincang" class="p-0" color="primary" />
                  </VControl>
                </VField>
                <VField class="mr-2">
                  <VControl>
                    <VCheckbox v-model="input.bentukMakanan" label="Cair/sonde/formula" true-value="Cair/sonde/formula"
                      class="p-0" color="primary" />
                  </VControl>
                </VField>
                <VField>
                  <VControl>
                    <VInput v-model="input.bentukMakananLainnya" placeholder="keterangan" color="primary" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="columns is-flex is-12 p-0">
              <div class="column is-2">
                <h1 style="font-weight: bold">Cara Pemberian : </h1>
              </div>
              <div class="column is-flex is-10">
                <VField class="mr-2">
                  <VControl>
                    <VCheckbox v-model="input.caraPemberian" label="Oral" true-value="Oral" class="p-0"
                      color="primary" />
                  </VControl>
                </VField>
                <VField class="mr-2">
                  <VControl>
                    <VCheckbox v-model="input.caraPemberian" label="Enteral / NGT" true-value="Enteral / NGT"
                      class="p-0" color="primary" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="columns is-flex is-12 p-0">
              <div class="column is-2">
                <h1 style="font-weight: bold">Konsultasi Gizi : </h1>
              </div>
              <div class="column is-10">
                <VField class="mr-2">
                  <VControl>
                    <VTextarea v-model="input.konsultasiGizi" class="p-0" color="primary" />
                  </VControl>
                </VField>

              </div>
            </div>
          </Fieldset>

          <Fieldset legend="RENCANA MONITORING DAN EVALUASI" :toggleable="true">
            <div class="columns is-flex is-12 p-0">
              <div class="column is-2">
                <h1 style="font-weight: bold">Kontrol ulang ke Poli Gizi : </h1>
              </div>
              <div class="column is-flex is-10">

                <VField class="p-0 mr-2">
                  <VControl>
                    <VCheckbox v-model="input.kontrolUlang" label="1 Minggu" true-value="1 Minggu" class="p-0"
                      color="primary" />
                  </VControl>
                </VField>

                <VField class="p-0 mr-2">
                  <VControl>
                    <VCheckbox v-model="input.kontrolUlang" label="2 Minggu" true-value="2 Minggu" class="p-0"
                      color="primary" />
                  </VControl>
                </VField>

                <VField class="p-0 mr-2">
                  <VControl>
                    <VCheckbox v-model="input.kontrolUlang" label="3 Minggu" true-value="3 Minggu" class="p-0"
                      color="primary" />
                  </VControl>
                </VField>

                <VField class="p-0 mr-2">
                  <VControl>
                    <VCheckbox v-model="input.kontrolUlang" label="1 Bulan" true-value="1 Bulan" class="p-0"
                      color="primary" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="columns is-12 p-0">
              <div class="column is-2">
                <h1 style="font-weight: bold">Keterangan : </h1>
              </div>
            </div>

            <div class="columns">
              <h1 style="font-weight: bold">Status Gizi berdasarkan Indek Masa Tubuh (WHO Asia Pasific, 2000)</h1>
            </div>

            <div class="columns is-12 p-0">
              <table class="table-rpo">
                <thead>
                  <tr>
                    <th class="th-rpo">Klasifikasi</th>
                    <th class="th-rpo">IMT (kg/m<sup>2</sup>)</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="td-rpo">Underweight</td>
                    <td class="td-rpo">&lt; 18,5</td>
                  </tr>
                  <tr>
                    <td class="td-rpo">Normal</td>
                    <td class="td-rpo">18,5 – 22,9</td>
                  </tr>
                  <tr>
                    <td class="td-rpo">Overweight</td>
                    <td class="td-rpo">&gt; 23</td>
                  </tr>
                  <tr>
                    <td class="td-rpo">Pre-Obese (beresiko)</td>
                    <td class="td-rpo">23 – 24,9</td>
                  </tr>
                  <tr>
                    <td class="td-rpo">Obesitas I</td>
                    <td class="td-rpo">25 – 29,9</td>
                  </tr>
                  <tr>
                    <td class="td-rpo">Obesitas II</td>
                    <td class="td-rpo">≥ 30</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="columns">
              <h1 style="font-weight: bold">Resiko Obesitas berdasarkan lingkar perut</h1>
            </div>

            <div class="columns is-12 p-0">
              <table class="table-rpo">
                <thead>
                  <tr>
                    <th class="th-rpo">Jenis Kelamin</th>
                    <th class="th-rpo">Batas Ambang</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="td-rpo">Laki-laki</td>
                    <td class="td-rpo">≤ 90 cm</td>
                  </tr>
                  <tr>
                    <td class="td-rpo">Perempuan</td>
                    <td class="td-rpo">≤ 80 cm</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div>
              <p>@ Kriteria Asupan makan (Gibson, 2005) (ADA):
                Baiik: ≥ 80%
                Kurang:&lt; 18,580%</p>
            </div>

            <div class="column is-4 mt-3" style="margin-left: auto">
              <VCard class="border-card pink">
                <div class="column is-9">
                  <VField>
                    <h1 style="font-weight: bold">Garut , tanggal dan jam</h1>
                  </VField>
                  <VField>
                    <VDatePicker v-model="input.tanggalTTD" mode="dateTime" style="width: 100%" trim-weeks
                      :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" placeholder="" v-on="inputEvents" v-model="input.tanggalTTD" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </VField>
                  <TandaTangan :elemenID="'TTDAhliGizi'" :width="'150'" :height="'150'" class="dek" />
                  <AutoComplete v-model="input.ahliGizi" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                    :field="'label'" />
                </div>
              </VCard>
            </div>

          </Fieldset>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from "@vueuse/core";
import { useApi } from "/@src/composable/useApi";
import { h, reactive, ref, computed, watch, onBeforeMount, nextTick } from "vue";
import { useRoute, useRouter, onBeforeRouteLeave } from "vue-router";
import { useHead } from "@vueuse/head";
import { useViewWrapper } from "/@src/stores/viewWrapper";
import { useUserSession } from "/@src/stores/userSession";
import ButtonEmr from "../page-emr-plugins/button-emr.vue";
import * as H from "/@src/utils/appHelper";
import AutoComplete from "primevue/autocomplete";
import Fieldset from "primevue/fieldset";
import * as EMR from "../page-emr-plugins/asesmen-awal-keper-rj";
import * as EMR2 from "../page-emr-plugins/asesmen-gizi-rajal";
import TandaTangan from "../page-emr-plugins/tanda-tangan.vue";

useHead({
  title: "Monitoring dan Evaluasi Gizi - " + import.meta.env.VITE_PROJECT,
});
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT);
useViewWrapper().setFullWidth(true);
let ID_PASIEN = useRoute().query.nocmfk as string;
let NOREC_PD = useRoute().query.norec_pd as string;
let norec_emr = useRoute().query.norec_emr as string;
let JenisKelamin = ref(EMR.JenisKelamin());
let BioKimias1: any = ref(EMR2.BioKimias1());
let BioKimias2: any = ref(EMR2.BioKimias2());
let KlinisFisik: any = ref(EMR2.KlinisFisik());

const props = withDefaults(
  defineProps<{
    pasien?: any;
    registrasi?: any;
    FORM_NAME?: string;
    FORM_URL?: string;
    COLLECTION?: string;
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: "",
    FORM_URL: "",
    COLLECTION: "",
  }
);

const route = useRoute();
const pasien: any = ref({});
const d_pegawai: any = ref([]);
const d_Dokter: any = ref([]);
const loadData: any = ref(true);
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : "",
  NOREC_APD: props.registrasi.norec_apd,
  RUANGAN_LAST: props.registrasi.objectruanganlastfk,
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  date: {
    tanggal: new Date(),
    jam: new Date(),
  },
  airway: [],
  disability: [],
});

const COLLECTION: any = ref("AsesmenGiziDewasaRawatJalan");

const NOREC_EMRPASIEN: any = ref("");
const input: any = ref({
  tanggalJamKedatangan: new Date(),
  tanggalTTD: new Date(),
});
const { y } = useWindowScroll();
const isStuck = computed(() => {
  return y.value > 30;
});
const isLoading = ref(false);
const isAktive = ref();

const dataTTD: any = ref([]);
const d_Ruangan: any = ref([]);

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  );
  d_Ruangan.value = response;
};

const addNewItem = () => {
  input.value.details.push({
    no: input.value.details[input.value.details.length - 1].no + 1,
    nama: props.pasien.namapasien,
    tanggal: new Date(),
    tanggalJam: new Date(),
  });
};
const removeItem = (index: any) => {
  input.value.details.splice(index, 1);
};

const loadRiwayat = async () => {
  // if (NOREC_EMRPASIEN.value == '') return
  await useApi()
    .get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    )
    .then((response: any) => {
      if (response.length) {
        input.value = response[0]; //set ke inputan
        if (NOREC_EMRPASIEN.value == "") {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk;
        }
        dataTTD.value = response[0];
      }
    });
  H.tandaTangan().set("TTDAhliGizi", dataTTD.value.TTDAhliGizi);
};
const simpan = () => {
  let ID = input.value.id ? input.value.id : "";

  let object: any = {};

  object = input.value;
  object.nocm = pasien.value.nocm;

  object.pasien = H.setObjectPasien(pasien.value);
  object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi);
  object["TTDAhliGizi"] = H.tandaTangan().get("TTDAhliGizi");

  let json = {
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: "asesmen-gizi-dewasa-rajal",
    name_form: props.FORM_NAME,
    jenis_emr: "asesmen_medis",
    data: object,
  };
  console.log(json);

  isLoading.value = true;
  useApi()
    .post(`/emr/simpan-emr`, json)
    .then((response: any) => {
      isLoading.value = false;
      // NOREC_EMRPASIEN.value = response.norec_emr
    })
    .catch((e: any) => {
      isLoading.value = false;
    });

  // console.log(resultValue)
};

const kembaliKeun = () => {
  window.history.back();
};
const fetchPasien = () => {
  pasien.value = props.pasien;
  pasien.value.registrasi = props.registrasi;
  NOREC_EMRPASIEN.value = norec_emr ? norec_emr : "";
  console.log(norec_emr);
};

const fetchDokter = async (filter: any) => {
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    )
    .then(response => {
      d_Dokter.value = response;
    });
};
const getDataExist = async () => {
  await useApi()
    .get(`emr/get-data-exist?nocmfk=${ID_PASIEN}`)
    .then(response => {
      input.value.beratBadan = response.beratBadan;
      input.value.tinggiBadan = response.tinggiBadan;
      input.value.IMT = response.IMT;
      input.value.lingkarPerut = response.lingkarPerut;
      input.value.nadiObgyn = response.nadi;
      input.value.celciusObgyn = response.suhu;
      input.value.tekananDarahObgyn = response.tekananDarah;
      input.value.nafasObgyn = response.pernapasan;
      input.value.sao2Obgyn = response.SPO2;
    });
};

const handlerRujukanChange = (val: any) => {
  console.log(val);
  if (val === "YA") {
  }
};

const print = async () => {
  H.printBlade(
    `emr/cetak-asesmen-keper-rj?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
  );
};

onBeforeMount(async () => {
  try {
    await loadRiwayat();
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`);
    if (cache) input.value = cache;
    loadData.value = false;
  } catch (error) {
    console.error("Error mount cache TAB EMR:", error);
  }
});

onBeforeRouteLeave((to, from, next) => {
  try {
    let rouutename = from?.name;
    H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value);
  } catch (error) {
    console.error("Error leave cache TAB EMR:", error);
  }
  next();
});

const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien;
  input.value.norm = props.pasien.nocm;
  input.value.tanggalLahirPasien = props.pasien.tgllahir;
  input.value.jeniskelamin = props.pasien.jeniskelamin;
};

setAutoFill();
getDataExist();
fetchPasien();
</script>

<style lang="scss">
.table-rpo {
  width: 100%;
  border: 1px solid;
}

.th-rpo,
.td-rpo {
  padding: 7px;
  border: 1px solid black;
  vertical-align: inherit;
}

.th-rpo {
  text-align: center !important;
}

.p-fieldset-legend {
  margin-left: 15px;
}
</style>
