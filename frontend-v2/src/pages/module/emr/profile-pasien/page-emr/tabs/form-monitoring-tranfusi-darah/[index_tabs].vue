<template>
  <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
          <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
              <div class="form-header-inner">
                  <div class="left">
                      <h3> {{ props.FORM_NAME }} Halaman ke-{{ route.params.index_tabs }}</h3>
                  </div>
                  <div class="right">
                      <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :ID_EMR="ID_EMR" :COLLECTION="COLLECTION" :isLoading="isLoading"
                      @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
                  </div>
              </div>
          </div>
          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-1 mb-1">
          <div style="text-align: center;font-size: large;font-weight: bold;">
              <VTag :class="isSave ? 'has-background-success' : 'has-background-danger'"
                  style="color:white;width: 100%;font-size: large;">
                  {{ isSave ? 'Form Sudah Tersimpan / Data Sudah Ada' : 'Form Belum Tersimpan' }}
              </VTag>
          </div>

      </div>
  </div>
            <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Jenis Komponen Darah : </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Jenis Komponen Darah " v-model="input.JenisKomponenDarah" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Nomor Kantong Darah : </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Nomor Kantong Darah " v-model="input.NoKantongDarah" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Volume Transfusi : </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Volume Transfusi " v-model="input.VolumeTransfusi" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Golongan Darah : </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Golongan Darah " v-model="input.GolonganDarah" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          
          <h1 style="font-weight: bold;">Pengirim/penerima darah
          </h1>

          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Nama : </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Nama " v-model="input.PengirimDarah" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>

          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Waktu : </span>
              </div>
              <div class="column is-6">
                <VField>
                      <VDatePicker v-model="input.tglKirimDarah" mode="dateTime" style="width: 100%" trim-weeks
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
          </div>
          <h1 style="font-weight: bold;">Penerima darah
          </h1>

          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Nama : </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Nama " v-model="input.PenerimaDarah" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>

          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Waktu : </span>
              </div>
              <div class="column is-6">
                <VField>
                      <VDatePicker v-model="input.tglTerimaDarah" mode="dateTime" style="width: 100%" trim-weeks
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
          </div>

              
              

  <div class="columns is-multiline p-2">
      <div class="column is-12">
          <VCard>
              <div class="columns is-multiline">
                  <div class="column is-12">
                      <div class="column is-12" style="overflow-x:auto;">
                          <table class="table is-bordered is-striped is-narrow is-hoverable" style="width: 2400px;">
                              <thead>
                                  <tr>
                                      <td class="tg-0lax text-center" rowspan="2">Kondisi</td>
                                      <td class="tg-0lax text-center" rowspan="2">
                                          <span>SEBELUM TRANSFUSI DIMULAI</span>
                                          <VField>
                                              <VDatePicker v-model="input.jamSebelumTransfusi" mode="time" is24hr>
                                                  <template #default="{ inputValue, inputEvents }">
                                                  <VControl icon="feather:clock" fullwidth>
                                                      <VInput :value="inputValue" v-on="inputEvents" />
                                                  </VControl>
                                                  </template>
                                              </VDatePicker>
                                          </VField>
                                      </td>
                                      <td class="tg-0lax text-center" rowspan="2"> 
                                          <span>15 MENIT SETELAH TRANSFUSI DIMULAI</span>
                                          <VField>
                                              <VDatePicker v-model="input.Jam15menitSebelumTransfusi" mode="time" is24hr>
                                                  <template #default="{ inputValue, inputEvents }">
                                                  <VControl icon="feather:clock" fullwidth>
                                                      <VInput :value="inputValue" v-on="inputEvents" />
                                                  </VControl>
                                                  </template>
                                              </VDatePicker>
                                          </VField>
                                      </td>   
                                      <td class="tg-0lax text-center" colspan="4">Setiap Jam </td>
                                      <td class="tg-0lax text-center" rowspan="2">
                                          <span>SAAT TRANSFUSI BERAKHIR</span>
                                          <VField>
                                              <VDatePicker v-model="input.jamTransfusiberakhir" mode="time" is24hr>
                                                  <template #default="{ inputValue, inputEvents }">
                                                  <VControl icon="feather:clock" fullwidth>
                                                      <VInput :value="inputValue" v-on="inputEvents" />
                                                  </VControl>
                                                  </template>
                                              </VDatePicker>
                                          </VField>
                                      </td>
                                      <td class="tg-0lax text-center" rowspan="2">
                                          <span>4 JAM SETELAH TRANSFUSI</span>
                                          <VField>
                                              <VDatePicker v-model="input.jamSetelahTransfusi" mode="time" is24hr>
                                                  <template #default="{ inputValue, inputEvents }">
                                                  <VControl icon="feather:clock" fullwidth>
                                                      <VInput :value="inputValue" v-on="inputEvents" />
                                                  </VControl>
                                                  </template>
                                              </VDatePicker>
                                          </VField>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td class="tg-0lax" style="text-align: center;">JAM I </td>
                                      <td class="tg-0lax" style="text-align: center;">JAM II</td>
                                      <td class="tg-0lax" style="text-align: center;">JAM III</td>
                                      <td class="tg-0lax" style="text-align: center;">JAM IV</td>
                                  </tr>
                              </thead>
                              <tbody v-for="(item, index) in input.details" :key="index">
                                  <tr>
                                      <td>
                                          <VField class="pt-11">
                                              <p>Keadaan Umum</p>
                                              <VControl class="prime-auto">
                                                  <!-- <VInput type="text" v-model="item.kantong" placeholder=""/> -->
                                                  <!-- <Calendar v-model="item.tgl" selectionMode="single" :manualInput="false" -->
                                                      <!-- class="w-100" :showIcon="true" showTime hourFormat="24" -->
                                                      <!-- :date-format="'yy-mm-dd'" /> -->
                                              </VControl>
                                          </VField>
                                      </td>

                                      <td>
                                          <VField class="pt-2a">
                                              <VControl>
                                                  <VInput type="text" v-model="item.kantong11" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-2a">
                                              <VControl>
                                                  <VInput type="text" v-model="item.tekananDarah" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-2a">
                                              <VControl>
                                                  <VInput type="text" v-model="item.nadi" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-2a">
                                              <VControl>
                                                  <VInput type="text" v-model="item.pernapasan" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-2a">
                                              <VControl>
                                                  <VInput type="text" v-model="item.suhu" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-2a">
                                              <VControl>
                                                  <VInput type="text" v-model="item.lokasiInsersi" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField>
                                              <VControl class="prime-auto">
                                                  <VInput type="text" v-model="item.jam23" placeholder=""/>
                                                  <!-- <AutoComplete v-model="item.dokterRawatBersama" :suggestions="d_Dokter" -->
                                                      <!-- @complete="fetchDokter($event)" :optionLabel="'label'" -->
                                                      <!-- :dropdown="true" :minLength="3" :appendTo="'body'" -->
                                                      <!-- :loadingIcon="'pi pi-spinner'" :field="'label'" -->
                                                      <!-- placeholder="Cari Perawat..." class="mt-2" /> -->
                                              </VControl>

                                          </VField>
                                      </td>
                                      <td>
                                          <VField>
                                              <VControl class="prime-auto"></VControl>
                                              <VInput type="text" v-model="item.test1" placeholder=""/>
                                              
                                          </VField>
                                      </td>                                        
                                  </tr>

                                  <tr>
                                      <td>
                                          <VField class="pt-12">
                                              <p>Suhu</p>
                                              <VControl class="prime-auto">
                                                  <!-- <VInput type="text" v-model="item.kantong" placeholder=""/> -->
                                                  <!-- <Calendar v-model="item.tgl" selectionMode="single" :manualInput="false" -->
                                                      <!-- class="w-100" :showIcon="true" showTime hourFormat="24" -->
                                                      <!-- :date-format="'yy-mm-dd'" /> -->
                                              </VControl>
                                          </VField>
                                      </td>

                                      <td>
                                          <VField class="pt-2a">
                                              <VControl>
                                                  <VInput type="text" v-model="item.kantong1" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-3a">
                                              <VControl>
                                                  <VInput type="text" v-model="item.tekananDarah1" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4a">
                                              <VControl>
                                                  <VInput type="text" v-model="item.nadi1" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-5a">
                                              <VControl>
                                                  <VInput type="text" v-model="item.pernapasan1" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-5a">
                                              <VControl>
                                                  <VInput type="text" v-model="item.suhu1" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-6a">
                                              <VControl>
                                                  <VInput type="text" v-model="item.lokasiInsersi1" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField>
                                              <VControl class="prime-auto">
                                                  <VInput type="text" v-model="item.jam34" placeholder=""/>
                                                  <!-- <AutoComplete v-model="item.dokterRawatBersama" :suggestions="d_Dokter" -->
                                                      <!-- @complete="fetchDokter($event)" :optionLabel="'label'" -->
                                                      <!-- :dropdown="true" :minLength="3" :appendTo="'body'" -->
                                                      <!-- :loadingIcon="'pi pi-spinner'" :field="'label'" -->
                                                      <!-- placeholder="Cari Perawat..." class="mt-2" /> -->
                                              </VControl>

                                          </VField>
                                      </td>
                                      <td>
                                          <VField>
                                              <VControl class="prime-auto"></VControl>
                                              <VInput type="text" v-model="item.test" placeholder=""/>
                                              
                                          </VField>
                                      </td>                                        
                                  </tr>
                                  <tr>
                                      <td>
                                          <VField class="pt-1b">
                                              <p>Nadi</p>
                                              <VControl class="prime-auto">
                                                  <!-- <VInput type="text" v-model="item.kantong" placeholder=""/> -->
                                                  <!-- <Calendar v-model="item.tgl" selectionMode="single" :manualInput="false" -->
                                                      <!-- class="w-100" :showIcon="true" showTime hourFormat="24" -->
                                                      <!-- :date-format="'yy-mm-dd'" /> -->
                                              </VControl>
                                          </VField>
                                      </td>

                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <VInput type="text" v-model="item.kantong2" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <VInput type="text" v-model="item.tekananDarah2" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <VInput type="text" v-model="item.nadi2" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <VInput type="text" v-model="item.pernapasan2" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <VInput type="text" v-model="item.suhu2" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <VInput type="text" v-model="item.lokasiInsersi2" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField>
                                              <VControl class="prime-auto">
                                                  <VInput type="text" v-model="item.jam1" placeholder=""/>
                                                  <!-- <AutoComplete v-model="item.dokterRawatBersama" :suggestions="d_Dokter" -->
                                                      <!-- @complete="fetchDokter($event)" :optionLabel="'label'" -->
                                                      <!-- :dropdown="true" :minLength="3" :appendTo="'body'" -->
                                                      <!-- :loadingIcon="'pi pi-spinner'" :field="'label'" -->
                                                      <!-- placeholder="Cari Perawat..." class="mt-2" /> -->
                                              </VControl>

                                          </VField>
                                      </td>
                                      <td>
                                          <VField>
                                              <VControl class="prime-auto"></VControl>
                                              <VInput type="text" v-model="item.test12" placeholder=""/>
                                              
                                          </VField>
                                      </td>                                        
                                  </tr>
                                  <tr>
                                      <td>
                                          <VField class="pt-4">
                                              <p>Tekanan Darah</p>
                                              <VControl class="prime-auto">
                                                  <!-- <VInput type="text" v-model="item.kantong" placeholder=""/> -->
                                                  <!-- <Calendar v-model="item.tgl" selectionMode="single" :manualInput="false" -->
                                                      <!-- class="w-100" :showIcon="true" showTime hourFormat="24" -->
                                                      <!-- :date-format="'yy-mm-dd'" /> -->
                                              </VControl>
                                          </VField>
                                      </td>

                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <VInput type="text" v-model="item.kantong3" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <VInput type="text" v-model="item.tekananDarah3" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <VInput type="text" v-model="item.nadi3" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <VInput type="text" v-model="item.pernapasan3" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <VInput type="text" v-model="item.suhu3" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <VInput type="text" v-model="item.lokasiInsersi3" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField>
                                              <VControl class="prime-auto">
                                                  <VInput type="text" v-model="item.jam00" placeholder=""/>
                                                  <!-- <AutoComplete v-model="item.dokterRawatBersama" :suggestions="d_Dokter" -->
                                                      <!-- @complete="fetchDokter($event)" :optionLabel="'label'" -->
                                                      <!-- :dropdown="true" :minLength="3" :appendTo="'body'" -->
                                                      <!-- :loadingIcon="'pi pi-spinner'" :field="'label'" -->
                                                      <!-- placeholder="Cari Perawat..." class="mt-2" /> -->
                                              </VControl>

                                          </VField>
                                      </td>
                                      <td>
                                          <VField>
                                              <VControl class="prime-auto"></VControl>
                                              <VInput type="text" v-model="item.test90" placeholder=""/>
                                              
                                          </VField>
                                      </td>                                        
                                  </tr>
                                  <tr>
                                      <td>
                                          <VField class="pt-4">
                                              <p>Respiratory rate</p>
                                              <VControl class="prime-auto">
                                                  <!-- <VInput type="text" v-model="item.kantong" placeholder=""/> -->
                                                  <!-- <Calendar v-model="item.tgl" selectionMode="single" :manualInput="false" -->
                                                      <!-- class="w-100" :showIcon="true" showTime hourFormat="24" -->
                                                      <!-- :date-format="'yy-mm-dd'" /> -->
                                              </VControl>
                                          </VField>
                                      </td>

                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <VInput type="text" v-model="item.kantong4" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <VInput type="text" v-model="item.tekananDarah4" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <VInput type="text" v-model="item.nadi4" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <VInput type="text" v-model="item.pernapasan4" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <VInput type="text" v-model="item.suhu4" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <VInput type="text" v-model="item.lokasiInsersi4" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField>
                                              <VControl class="prime-auto">
                                                  <VInput type="text" v-model="item.jam567" placeholder=""/>
                                                  <!-- <AutoComplete v-model="item.dokterRawatBersama" :suggestions="d_Dokter" -->
                                                      <!-- @complete="fetchDokter($event)" :optionLabel="'label'" -->
                                                      <!-- :dropdown="true" :minLength="3" :appendTo="'body'" -->
                                                      <!-- :loadingIcon="'pi pi-spinner'" :field="'label'" -->
                                                      <!-- placeholder="Cari Perawat..." class="mt-2" /> -->
                                              </VControl>

                                          </VField>
                                      </td>
                                      <td>
                                          <VField>
                                              <VControl class="prime-auto"></VControl>
                                              <VInput type="text" v-model="item.test34" placeholder=""/>
                                              
                                          </VField>
                                      </td>                                        
                                  </tr>
                                  <tr>
                                      <td>
                                          <VField class="pt-4">
                                              <p>Produksi Urine dan Warna</p>
                                              <VControl class="prime-auto">
                                                  <!-- <VInput type="text" v-model="item.kantong" placeholder=""/> -->
                                                  <!-- <Calendar v-model="item.tgl" selectionMode="single" :manualInput="false" -->
                                                      <!-- class="w-100" :showIcon="true" showTime hourFormat="24" -->
                                                      <!-- :date-format="'yy-mm-dd'" /> -->
                                              </VControl>
                                          </VField>
                                      </td>

                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <VInput type="text" v-model="item.kantong5" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <VInput type="text" v-model="item.tekananDarah5" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <VInput type="text" v-model="item.nadi5" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <VInput type="text" v-model="item.pernapasan5" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <VInput type="text" v-model="item.suhu5" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <VInput type="text" v-model="item.lokasiInsersi5" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField>
                                              <VControl class="prime-auto">
                                                  <VInput type="text" v-model="item.jam12367" placeholder=""/>
                                                  <!-- <AutoComplete v-model="item.dokterRawatBersama" :suggestions="d_Dokter" -->
                                                      <!-- @complete="fetchDokter($event)" :optionLabel="'label'" -->
                                                      <!-- :dropdown="true" :minLength="3" :appendTo="'body'" -->
                                                      <!-- :loadingIcon="'pi pi-spinner'" :field="'label'" -->
                                                      <!-- placeholder="Cari Perawat..." class="mt-2" /> -->
                                              </VControl>

                                          </VField>
                                      </td>
                                      <td>
                                          <VField>
                                              <VControl class="prime-auto"></VControl>
                                              <VInput type="text" v-model="item.test78" placeholder=""/>
                                              
                                          </VField>
                                      </td>                                        
                                  </tr>
                                  <tr>
                                      <td>
                                          <VField class="pt-4">
                                              <p>Tanda Reaksi transfusi</p>
                                              <VControl class="prime-auto">
                                                  <!-- <VInput type="text" v-model="item.kantong" placeholder=""/> -->
                                                  <!-- <Calendar v-model="item.tgl" selectionMode="single" :manualInput="false" -->
                                                      <!-- class="w-100" :showIcon="true" showTime hourFormat="24" -->
                                                      <!-- :date-format="'yy-mm-dd'" /> -->
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td style="min-width: 100px;">
                                          <div class="columns is-multiline">
                                              <div class="column is-4">                                          
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi1" />
                                                  </VControl>
                                              </div>
                                              <div class="column is-4"> 
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi2" />
                                                  </VControl>
                                              </div>    
                                              <div class="column is-4">
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi3" />
                                                  </VControl>
                                              </div>                                            
                                          </div>
                                          <div class="columns is-multiline">
                                              <div class="column is-4">                                          
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi4" />
                                                  </VControl>
                                              </div>
                                              <div class="column is-4"> 
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi5" />
                                                  </VControl>
                                              </div>    
                                              <div class="column is-4">
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi6" />
                                                  </VControl>
                                              </div>                                            
                                          </div>   
                                      </td>
                                      <td style="min-width: 100px;">
                                          <div class="columns is-multiline">
                                                  <div class="column is-4">                                          
                                                      <VControl>
                                                          <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi7" />
                                                      </VControl>
                                                  </div>
                                                  <div class="column is-4"> 
                                                      <VControl>
                                                          <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi8" />
                                                      </VControl>
                                                  </div>    
                                                  <div class="column is-4">
                                                      <VControl>
                                                          <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi9" />
                                                      </VControl>
                                                  </div>                                            
                                              </div>
                                              <div class="columns is-multiline">
                                                  <div class="column is-4">                                          
                                                      <VControl>
                                                          <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi10" />
                                                      </VControl>
                                                  </div>
                                                  <div class="column is-4"> 
                                                      <VControl>
                                                          <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi11" />
                                                      </VControl>
                                                  </div>    
                                                  <div class="column is-4">
                                                      <VControl>
                                                          <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi12" />
                                                      </VControl>
                                                  </div>                                            
                                              </div>  
                                      </td>
                                      <td style="min-width: 100px;">
                                          <div class="columns is-multiline">
                                              <div class="column is-4">                                          
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi13" />
                                                  </VControl>
                                              </div>
                                              <div class="column is-4"> 
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi14" />
                                                  </VControl>
                                              </div>    
                                              <div class="column is-4">
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi15" />
                                                  </VControl>
                                              </div>                                            
                                          </div>
                                          <div class="columns is-multiline">
                                              <div class="column is-4">                                          
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi16" />
                                                  </VControl>
                                              </div>
                                              <div class="column is-4"> 
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi17" />
                                                  </VControl>
                                              </div>    
                                              <div class="column is-4">
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi18" />
                                                  </VControl>
                                              </div>                                            
                                          </div>  
                                      </td>
                                      <td style="min-width: 100px;">
                                          <div class="columns is-multiline">
                                              <div class="column is-4">                                          
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi19" />
                                                  </VControl>
                                              </div>
                                              <div class="column is-4"> 
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi20" />
                                                  </VControl>
                                              </div>    
                                              <div class="column is-4">
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi21" />
                                                  </VControl>
                                              </div>                                            
                                          </div>
                                          <div class="columns is-multiline">
                                              <div class="column is-4">                                          
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi22" />
                                                  </VControl>
                                              </div>
                                              <div class="column is-4"> 
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi23" />
                                                  </VControl>
                                              </div>    
                                              <div class="column is-4">
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi24" />
                                                  </VControl>
                                              </div>                                            
                                          </div>  
                                      </td>
                                      <td style="min-width: 100px;">
                                          <div class="columns is-multiline">
                                              <div class="column is-4">                                          
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi25" />
                                                  </VControl>
                                              </div>
                                              <div class="column is-4"> 
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi26" />
                                                  </VControl>
                                              </div>    
                                              <div class="column is-4">
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi27" />
                                                  </VControl>
                                              </div>                                            
                                          </div>
                                          <div class="columns is-multiline">
                                              <div class="column is-4">                                          
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi28" />
                                                  </VControl>
                                              </div>
                                              <div class="column is-4"> 
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi29" />
                                                  </VControl>
                                              </div>    
                                              <div class="column is-4">
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi30" />
                                                  </VControl>
                                              </div>                                            
                                          </div>                                              
                                      </td>
                                      <td style="min-width: 100px;">
                                          <div class="columns is-multiline">
                                              <div class="column is-4">                                          
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi31" />
                                                  </VControl>
                                              </div>
                                              <div class="column is-4"> 
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi32" />
                                                  </VControl>
                                              </div>    
                                              <div class="column is-4">
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi33" />
                                                  </VControl>
                                              </div>                                            
                                          </div>
                                          <div class="columns is-multiline">
                                              <div class="column is-4">                                          
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi34" />
                                                  </VControl>
                                              </div>
                                              <div class="column is-4"> 
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi35" />
                                                  </VControl>
                                              </div>    
                                              <div class="column is-4">
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi36" />
                                                  </VControl>
                                              </div>                                            
                                          </div>                                              
                                      </td>
                                      <td style="min-width: 100px;">
                                          <div class="columns is-multiline">
                                              <div class="column is-4">                                          
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi37" />
                                                  </VControl>
                                              </div>
                                              <div class="column is-4"> 
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi38" />
                                                  </VControl>
                                              </div>    
                                              <div class="column is-4">
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi39" />
                                                  </VControl>
                                              </div>                                            
                                          </div>
                                          <div class="columns is-multiline">
                                              <div class="column is-4">                                          
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi40" />
                                                  </VControl>
                                              </div>
                                              <div class="column is-4"> 
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi41" />
                                                  </VControl>
                                              </div>    
                                              <div class="column is-4">
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi42" />
                                                  </VControl>
                                              </div>                                            
                                          </div>                                              
                                      </td>
                                      <td style="min-width: 100px;">
                                          <div class="columns is-multiline">
                                              <div class="column is-4">                                          
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi43" />
                                                  </VControl>
                                              </div>
                                              <div class="column is-4"> 
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi44" />
                                                  </VControl>
                                              </div>    
                                              <div class="column is-4">
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi45" />
                                                  </VControl>
                                              </div>                                            
                                          </div>
                                          <div class="columns is-multiline">
                                              <div class="column is-4">                                          
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi46" />
                                                  </VControl>
                                              </div>
                                              <div class="column is-4"> 
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi47" />
                                                  </VControl>
                                              </div>    
                                              <div class="column is-4">
                                                  <VControl>
                                                      <VInput type="number" class="input p-0" v-model="input.reaksiTransfusi48" />
                                                  </VControl>
                                              </div>                                            
                                          </div>                                              
                                      </td>                                      
                                  </tr>
                                  <tr>
                                      <td>
                                          <VField class="pt-4">
                                              <p>Petugas</p>
                                              <p>Nama</p>
                                              <VControl class="prime-auto">
                                                  <!-- <VInput type="text" v-model="item.kantong" placeholder=""/> -->
                                                  <!-- <Calendar v-model="item.tgl" selectionMode="single" :manualInput="false" -->
                                                      <!-- class="w-100" :showIcon="true" showTime hourFormat="24" -->
                                                      <!-- :date-format="'yy-mm-dd'" /> -->
                                              </VControl>
                                          </VField>
                                      </td>

                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <AutoComplete v-model="input.Petugas1" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                                  :loadingIcon="'pi pi-spinner'" :field="'label'" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <AutoComplete v-model="input.Petugas2" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                                  :loadingIcon="'pi pi-spinner'" :field="'label'" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <AutoComplete v-model="input.Petugas3" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                                  :loadingIcon="'pi pi-spinner'" :field="'label'" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <AutoComplete v-model="input.Petugas4" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                                  :loadingIcon="'pi pi-spinner'" :field="'label'" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <AutoComplete v-model="input.Petugas5" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                                  :loadingIcon="'pi pi-spinner'" :field="'label'" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <AutoComplete v-model="input.Petugas6" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                                  :loadingIcon="'pi pi-spinner'" :field="'label'" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <AutoComplete v-model="input.Petugas7" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                                  :loadingIcon="'pi pi-spinner'" :field="'label'" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <AutoComplete v-model="input.Petugas8" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                                  :loadingIcon="'pi pi-spinner'" :field="'label'" />
                                              </VControl>
                                          </VField>
                                      </td>                                        
                                  </tr>
                                  <tr>
                                      <td>
                                          <VField class="pt-4">
                                              <p>Paraf/tanda tangan</p>
                                              <VControl class="prime-auto">
                                                  <!-- <VInput type="text" v-model="item.kantong" placeholder=""/> -->
                                                  <!-- <Calendar v-model="item.tgl" selectionMode="single" :manualInput="false" -->
                                                      <!-- class="w-100" :showIcon="true" showTime hourFormat="24" -->
                                                      <!-- :date-format="'yy-mm-dd'" /> -->
                                              </VControl>
                                          </VField>
                                      </td>

                                      <td>
                                          <VField class="pt-4">
                                              <!-- <VControl>
                                                  <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                                  :loadingIcon="'pi pi-spinner'" :field="'label'" />
                                              </VControl> -->
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <!-- <VControl>
                                                  <VInput type="text" v-model="item.tekananDarah9" placeholder="" />
                                              </VControl> -->
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <!-- <VControl>
                                                  <VInput type="text" v-model="item.nadi9" placeholder="" />
                                              </VControl> -->
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <!-- <VControl>
                                                  <VInput type="text" v-model="item.pernapasan9" placeholder="" />
                                              </VControl> -->
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <!-- <VControl>
                                                  <VInput type="text" v-model="item.suhu9" placeholder="" />
                                              </VControl> -->
                                          </VField>
                                      </td>
                                      <td>
                                          <VField class="pt-4">
                                              <!-- <VControl>
                                                  <VInput type="text" v-model="item.lokasiInsersi9" placeholder="" />
                                              </VControl> -->
                                          </VField>
                                      </td>
                                      <td>
                                          <VField>
                                              <VControl class="prime-auto">
                                                  <!-- <VInput type="text" v-model="item.jam1239" placeholder=""/> -->
                                                  <!-- <AutoComplete v-model="item.dokterRawatBersama" :suggestions="d_Dokter" -->
                                                      <!-- @complete="fetchDokter($event)" :optionLabel="'label'" -->
                                                      <!-- :dropdown="true" :minLength="3" :appendTo="'body'" -->
                                                      <!-- :loadingIcon="'pi pi-spinner'" :field="'label'" -->
                                                      <!-- placeholder="Cari Perawat..." class="mt-2" /> -->
                                              </VControl>

                                          </VField>
                                      </td>
                                      <td>
                                          <VField>
                                              <!-- <VControl class="prime-auto"></VControl>
                                              <VInput type="text" v-model="item.test96" placeholder=""/> -->
                                              
                                          </VField>
                                      </td>                                        
                                  </tr>
                              </tbody>
                          </table>
                      </div>
                  </div>
                  <div class="column is-12">
                    <p><i>Tanda-tanda reaksi transfusi (ditulis nomor saja) :</i></p>
                    <ul>
                      <li>
                          <p><i>(1) Urtikaria,</i></p>
                      </li>
                      <li>
                          <p><i>(2) Demam,</i></p>
                      </li>
                      <li>
                          <p><i>(3) Gatal,</i></p>
                      </li>
                      <li>
                          <p><i>(4) Hemoglobinuria,</i></p>
                      </li>
                      <li>
                          <p><i>(5) Nyeri dada,</i></p>
                      </li>
                      <li>
                          <p><i>(6) Nyeri kepala,</i></p>
                      </li>
                      <li>
                          <p><i>(7) Sesak,</i></p>
                      </li>
                      <li>
                          <p><i>(8) Syok</i></p>
                      </li>
                    </ul>
                  </div>
              </div>
          </VCard>
      </div>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, onBeforeMount, watch } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
import Calendar from 'primevue/calendar';
import ButtonEmr from '../../../page-emr-plugins/button-emr.vue'

const route = useRoute()
const NAMA_RUANGAN: any = ref()
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const props = withDefaults(
  defineProps<{
      pasien?: any
      registrasi?: any
      FORM_NAME?: string
      FORM_URL?: string
  }>(),
  {
      pasien: {},
      registrasi: {},
      FORM_NAME: '',
      FORM_URL: '',
  }
)
NAMA_RUANGAN.value = route.query.nama_ruangan as string ?? props.registrasi.namaruangan;
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
const COLLECTION: any = ref('FormMonitoringTranfusiDarah') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const d_Dokter: any = ref([])
const d_Petugas: any = ref([])
const ID_EMR: any = ref('')
const isSave: any = ref(false)
const loadData: any = ref(true)
const input: any = ref({
  jamSebelumTransfusi: new Date().setHours(0, 0, 0, 0),
  Jam15menitSebelumTransfusi: new Date().setHours(0, 0, 0, 0),
  jamTransfusiberakhir: new Date().setHours(0, 0, 0, 0),
  jamSetelahTransfusi: new Date().setHours(0, 0, 0, 0),
  details: [{
      no: 1,
      
  }]
  
})
const setView = () => {
  useHead({
      title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const setAutoFill = async () => {
input.value.namaPasien = props.pasien.namapasien;
};

const loadRiwayat = async () => {
  let tabs = route.params.index_tabs < 1 ? route.params.index_tabs - 1 : 1
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&namaruangan=${NAMA_RUANGAN.value}&index_tabs=${route.params.index_tabs}`)
  let check = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&index_tabs=${tabs}&check_first_tab=true`)
  if (response.length && check.length != 0) {
      isSave.value = true
      input.value = response[0] //set ke inputan
      if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
      if (ID_EMR.value == '') {
          ID_EMR.value = response[0].id
      }
  } else {
      if (check.length == 0 && route.params.index_tabs != 1) {
          H.alert('warning', 'Halaman sebelumnnya belum disimpan!');
      }
      isSave.value = false
  }
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  if (route.params.index_tabs) {
        object.index_tabs = parseInt(route.params.index_tabs)
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
  isLoading.value = true
  useApi().post(
  `/emr/simpan-emr`, json).then((response: any) => {
    isLoading.value = false
    NOREC_EMRPASIEN.value = response.norec_emr
    ID_EMR.value = response.id;
    input.value.id = response.id
  }).catch((e: any) => {
    isLoading.value = false
  })
}
const fetchDokter = async (filter: any) => {
  const response = await useApi().get(
      `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
  d_Dokter.value = response
}

const fetchPetugas = async(filter :any)=>{
   const response = await useApi().get(
    `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_Petugas.value = response
}

const kembaliKeun = () => {
  window.history.back()
}
const addNewItem = () => {
  input.value.details.push({
      no: input.value.details[input.value.details.length - 1].no + 1,
      tgl: new Date(),
  });
}
const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
}

const d_reaksiTransfusi: any = ref([
{ value: '-', label: '-' },
{ value: '1', label: '1' },
{ value: '2', label: '2' },
{ value: '3', label: '3' },
{ value: '4', label: '4' },
{ value: '5', label: '5' },
{ value: '6', label: '6' },
{ value: '7', label: '7' },
{ value: '8', label: '8' }
])

onBeforeMount(async () => {
  try {
    await loadRiwayat()
    if (!route.query.nama_ruangan) {
      let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
      if (cache) input.value = cache
    }
    loadData.value = false
  } catch (error) {
    console.error('Error mount cache TAB EMR:', error);
  }
});

onBeforeRouteLeave((to, from, next) => {
    try {
        let rouutename = from?.name;
        if (!route.query.nama_ruangan) {
            H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value);
        }
        next(); // Proceed without changing the URL
    } catch (error) {
        console.error('Error leave cache TAB EMR:', error);
        next();
    }
});


watch(
    () => route.params.index_tabs,
    (newValue, oldValue) => {
        input.value = {}
        input.value.DTttd = new Date()
        ID_EMR.value = ""
        loadRiwayat()
        let rouutename = route.name + '-' + route.params.index_tabs
        let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${rouutename}`)
        if (cache) {
            input.value = cache
        }
    })
watch(
    () => input.value,
    (newValue, oldValue) => {
        let rouutename = route.name + '-' + route.params.index_tabs
        H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
        let timeout = null;
        if (timeout) {
            clearTimeout(timeout);
        }
        timeout = setTimeout(() => {
            H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, newValue);
        }, 500);
    }, { deep: true }
)

// const print = async () => {
//     H.printBlade(`emr/cetak/${COLLECTION.value}?emrpasienfk=${NOREC_EMRPASIEN.value}`)
// }

setView()
loadRiwayat()
</script>
<style lang="scss">
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
  min-width: 400px;
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
}

.tc .tg-0lax {
  text-align: left;
  vertical-align: top
}
</style>