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
              <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                @click="simpan()"> Simpan
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
        <div class="columns is-multiline">
          <div class="column is-12" style="width: 100%;overflow: auto;">
            <table class="tg" style="width: 200%;">
              <tr>
                <th rowspan="2" style="width:150px;position:sticky;left:0;z-index:2;background-color: aliceblue;">Skor
                  Morse</th>
                <th rowspan="2" style="width:150px;position:sticky;left:152px;z-index:2;background-color: aliceblue;">
                </th>
                <th rowspan="2" style="width: 100px;position:sticky;left:301px;z-index:2;background-color: aliceblue;">
                  Skor</th>
                <th rowspan="2" style="width: 150px;">Skor Awal</th>
                <th :colspan="jumlahImdex" class="text-left bg-th" style="width:50px">Tanggal</th>
              </tr>
              <tr>
                <!-- <th class="bg-th" > -->
                <!-- v-for="(item, index) in input.detailsTglSkor" :key="index" -->

                <!-- <div class="columns is-multiline">
                    <div class="column is-12" style="display:flex"> -->
                <!-- <VDatePicker v-model="item.tgl" color="green" trim-weeks :input="'YYYY-MM-DD'" mode="date">
                        <template #default="{ inputValue, inputEvents }">
                          <VField>
                            <VControl icon="feather:calendar">
                              <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                                class="is-rounded_Z input-30" style="width:150px" />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker> -->

                <!--
                      <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                        v-tooltip.bubble="'Tambah '" class="ml-2">
                      </VIconButton>

                      <VIconButton v-if="index > 0" type="button" raised circle icon="feather:trash"
                        @click="removeItem(index)" color="danger" class="ml-2">
                      </VIconButton> -->
                <!-- </div>
                  </div> -->

                <!-- </th> -->
                <th class="bg-th" v-for="index in jumlahImdex">
                  <VDatePicker v-model="input.tanggal[index]" color="green" trim-weeks :input="'YYYY-MM-DD'" mode="date">
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
                <td class="bg-colatas ">Riwayat Pasien Jatuh</td>
                <td class="bg-colatas2 ">Kurang dalam 3 bulan terakhir </td>
                <td class="bg-colatas3">30</td>
                <td class="tg-0lax">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.riwayatJatuhSkor" placeholder="" class="is-rounded_Z input-30" />
                    </VControl>
                  </VField>
                </td>
                <td class="tg-0lax" v-for="index in jumlahImdex">
                  <Checkbox v-model="input.riwayatJatuh[index]" :binary="true"
                    @change="setSkor(input.riwayatJatuh[index], index, 30)" />
                  <!-- <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.riwayatJatuh[index]" @change="setSkor($event, index)" :true-value="'Ya'"
                        :label="''" color="primary" square />
                    </VControl>
                  </VField> -->
                </td>
              </tr>
              <tr>
                <td class="bg-colatas">Kondisi Kesehatan</td>
                <td class="bg-colatas2">Lebih dari satu diagnosa penyakit</td>
                <td class="bg-colatas3">15</td>
                <td class="tg-0lax">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.kondisiKesehatanSkor" placeholder=""
                        class="is-rounded_Z input-30" />
                    </VControl>
                  </VField>
                </td>
                <td class="tg-0lax" v-for="index in jumlahImdex">
                  <Checkbox v-model="input.kondisiKesehatan[index]" :binary="true"
                    @change="setSkor(input.kondisiKesehatan[index], index, 15)" />
                  <!-- <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.kondisiKesehatan[index]" :true-value="'Ya'" :label="''" color="primary"
                        square />
                    </VControl>
                  </VField> -->
                </td>
              </tr>
              <tr>
                <td class="bg-colatas">Bantuan Ambulansi/alat bantu</td>
                <td class="bg-colatas2">Tirah baring butuh bantuan perawat </td>
                <td class="bg-colatas3">0</td>
                <td class="tg-0lax">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.bantuanAmbulanSkor" placeholder=""
                        class="is-rounded_Z input-30" />
                    </VControl>
                  </VField>
                </td>
                <td class="tg-0lax" v-for="index in jumlahImdex">
                  <Checkbox v-model="input.bantuanAmbulan[index]" :binary="true"
                    @change="setSkor(input.bantuanAmbulan[index], index, 0)" />
                  <!-- <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.bantuanAmbulan[index]" :true-value="'Ya'" :label="''" color="primary"
                        square />
                    </VControl>
                  </VField> -->
                </td>
              </tr>
              <tr>
                <td class="bg-colatas"></td>
                <td class="bg-colatas2">Kruk, tongkat, walker, kursi roda </td>
                <td class="bg-colatas3">15</td>
                <td class="tg-0lax">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.krukSkor" placeholder="" class="is-rounded_Z input-30" />
                    </VControl>
                  </VField>
                </td>
                <td class="tg-0lax" v-for="index in jumlahImdex">
                  <Checkbox v-model="input.kruk[index]" :binary="true" @change="setSkor(input.kruk[index], index, 15)" />

                </td>
              </tr>
              <tr>
                <td class="bg-colatas"></td>
                <td class="bg-colatas2">Berpegangan pada almari, meja, kursi (furniture)</td>
                <td class="bg-colatas3">30</td>
                <td class="tg-0lax">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.bepeganganSkor" placeholder="" class="is-rounded_Z input-30" />
                    </VControl>
                  </VField>
                </td>
                <td class="tg-0lax" v-for="index in jumlahImdex">
                  <Checkbox v-model="input.bepegangan[index]" :binary="true"
                    @change="setSkor(input.bepegangan[index], index, 30)" />
                  <!-- <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.bepegangan[index]" :true-value="'Ya'" :label="''" color="primary"
                        square />
                    </VControl>
                  </VField> -->
                </td>
              </tr>
              <tr>
                <td class="bg-colatas">Terapi IV/antikoagulan</td>
                <td class="bg-colatas2">Terapi Intravena, terpasang infus</td>
                <td class="bg-colatas3">20</td>
                <td class="tg-0lax">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.terapiSkor" placeholder="" class="is-rounded_Z input-30" />
                    </VControl>
                  </VField>
                </td>
                <td class="tg-0lax" v-for="index in jumlahImdex">
                  <Checkbox v-model="input.terapi[index]" :binary="true"
                    @change="setSkor(input.terapi[index], index, 20)" />
                  <!-- <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.terapi[index]" :true-value="'Ya'" :label="''" color="primary" square />
                    </VControl>
                  </VField> -->
                </td>
              </tr>
              <tr>
                <td class="bg-colatas">Gaya berjalan/berpindah</td>
                <td class="bg-colatas2">Normal</td>
                <td class="bg-colatas3">0</td>
                <td class="tg-0lax">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.gayaBerjalanSkor" placeholder="" class="is-rounded_Z input-30" />
                    </VControl>
                  </VField>
                </td>
                <td class="tg-0lax" v-for="index in jumlahImdex">
                  <Checkbox v-model="input.gayaBerjalan[index]" :binary="true"
                    @change="setSkor(input.gayaBerjalan[index], index, 0)" />
                  <!-- <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.gayaBerjalan[index]" :true-value="'Ya'" :label="''" color="primary"
                        square />
                    </VControl>
                  </VField> -->
                </td>
              </tr>
              <tr>
                <td class="bg-colatas"></td>
                <td class="bg-colatas2">Normal</td>
                <td class="bg-colatas3">0</td>
                <td class="tg-0lax">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.normalSkor" placeholder="" class="is-rounded_Z input-30" />
                    </VControl>
                  </VField>
                </td>
                <td class="tg-0lax" v-for="index in jumlahImdex">
                  <Checkbox v-model="input.normal[index]" :binary="true"
                    @change="setSkor(input.normal[index], index, 0)" />
                  <!-- <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.normal[index]" :true-value="'Ya'" :label="''" color="primary" square />
                    </VControl>
                  </VField> -->
                </td>
              </tr>
              <tr>
                <td class="bg-colatas"></td>
                <td class="bg-colatas2">Ada gangguan berjalan</td>
                <td class="bg-colatas3">20</td>
                <td class="tg-0lax">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.adaGangguanBerjalanSkor" placeholder=""
                        class="is-rounded_Z input-30" />
                    </VControl>
                  </VField>
                </td>
                <td class="tg-0lax" v-for="index in jumlahImdex">
                  <Checkbox v-model="input.adaGangguanBerjalan[index]" :binary="true"
                    @change="setSkor(input.adaGangguanBerjalan[index], index, 20)" />
                  <!-- <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.adaGangguanBerjalan[index]" :true-value="'Ya'" :label="''" color="primary"
                        square />
                    </VControl>
                  </VField> -->
                </td>
              </tr>
              <tr>
                <td class="bg-colatas">Status mental</td>
                <td class="bg-colatas2">Sadar dengan kemampuan sendiri</td>
                <td class="bg-colatas3">0</td>
                <td class="tg-0lax">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.sadarKemampuanSendiriSkor" placeholder=""
                        class="is-rounded_Z input-30" />
                    </VControl>
                  </VField>
                </td>
                <td class="tg-0lax" v-for="index in jumlahImdex">
                  <Checkbox v-model="input.sadarKemampuanSendiri[index]" :binary="true"
                    @change="setSkor(input.sadarKemampuanSendiri[index], index, 0)" />
                  <!-- <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.sadarKemampuanSendiri[index]" :true-value="'Ya'" :label="''"
                        color="primary" square />
                    </VControl>
                  </VField> -->
                </td>
              </tr>
              <tr>
                <td class="bg-colatas"></td>
                <td class="bg-colatas2">Sering lupa dengan keterbatasan dirinya /tidak sada</td>
                <td class="bg-colatas3">15</td>
                <td class="tg-0lax">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.lupaKeterbatasanSkor" placeholder=""
                        class="is-rounded_Z input-30" />
                    </VControl>
                  </VField>
                </td>
                <td class="tg-0lax" v-for="index in jumlahImdex">
                  <Checkbox v-model="input.lupaKeterbatasan[index]" :binary="true"
                    @change="setSkor(input.lupaKeterbatasan[index], index, 15)" />
                  <!-- <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.lupaKeterbatasan[index]" :true-value="'Ya'" :label="''" color="primary"
                        square />
                    </VControl>
                  </VField> -->
                </td>
              </tr>
              <tr>
                <th class="bg-colatas">Total Skor</th>
                <td class="bg-colatas2"></td>
                <td class="bg-colatas3"></td>
                <td class="tg-0lax">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.totalSkorAwal" placeholder="Skor Awal"
                        class="is-rounded_Z input-30" />
                    </VControl>
                  </VField>
                </td>
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
                <td class="bg-colatas2"></td>
                <td class="bg-colatas3"></td>
                <td class="tg-0lax">

                </td>
                <td class="tg-0lax">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:search">
                      <AutoComplete v-model="input.penangungJawab" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik nama" />
                    </VControl>
                  </VField>
                </td>
              </tr>
            </table>

          </div>
          <div class="column is-12">
            <table class="tg">
              <tr>
                <td>Bila 0 - 24 : Tidak Berisiko, Resiko jatuh rendah</td>
              </tr>
              <tr>
                <td>Bila 25 - 44 : Resiko jatuh sedang</td>
              </tr>
              <tr>
                <td>Bila > 44 : Resiko jatuh tinggi</td>
              </tr>
            </table>
          </div>
          <div class="column is-12" style="width: 100%;overflow: auto;">
            <table class="tg" style="width: 200%;">
              <tr>
                <th rowspan="2" style="width:57px;position:sticky;left:0;z-index:2;background-color: aliceblue;">No</th>
                <th rowspan="2" style="width:350px;position:sticky;left:57px;z-index:2;background-color: aliceblue;">
                  Tindakan Pencegahan </th>
                <th rowspan="2" style="width: 150px;">Pencegahan Awal</th>
                <th :colspan="jumlahImdex" class="text-left bg-th" style="width:50px">Tanggal</th>
              </tr>
              <tr>
                <th class="bg-th" v-for="index in jumlahImdex">
                  <VDatePicker v-model="input.tglPencegahan[index]" color="green" trim-weeks :input="'YYYY-MM-DD'"
                    mode="date">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar">
                          <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                            class="is-rounded_Z input-30" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </th>


              </tr>
              <tr>
                <td class="bg-col "></td>
                <td class="bg-col2 ">TINDAKAN TIDAK BERISIKO/RESIKO JATUH RENDAH </td>
                <td class="tg-0lax">

                </td>
                <td class="tg-0lax">

                </td>
              </tr>
              <tr>
                <td class="bg-col">1</td>
                <td class="bg-col2">Orientasikan</td>
                <td class="tg-0lax">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.orientasikan" placeholder="" class="is-rounded_Z input-30" />
                    </VControl>
                  </VField>
                </td>
                <td class="tg-0lax" v-for="index in jumlahImdex">
                  <Checkbox v-model="input.orientasikanYa[index]" :binary="true" />
                </td>
              </tr>
              <tr>
                <td class="bg-col">2</td>
                <td class="bg-col2">Kunci roda tempat tidur </td>
                <td class="tg-0lax">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.kunciRoda" placeholder="" class="is-rounded_Z input-30" />
                    </VControl>
                  </VField>
                </td>
                <td class="tg-0lax" v-for="index in jumlahImdex">
                  <Checkbox v-model="input.kunciRodaYa[index]" :binary="true" />
                </td>
              </tr>
              <tr>
                <td class="bg-col">3</td>
                <td class="bg-col2">Pastikan pagar pengaman tidur/Rel terpasang dengan baik</td>
                <td class="tg-0lax">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.pastikan" placeholder="" class="is-rounded_Z input-30" />
                    </VControl>
                  </VField>
                </td>
                <td class="tg-0lax" v-for="index in jumlahImdex">
                  <Checkbox v-model="input.pastikanYa[index]" :binary="true" />
                </td>
              </tr>
              <tr>
                <td class="bg-col">4</td>
                <td class="bg-col2">Edukasi pasien dan keluarga tentang hal-hal yang menyebabkan resiko jatuh, misal nya
                  cegah kencing yg urgen</td>
                <td class="tg-0lax">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.edukasi" placeholder="" class="is-rounded_Z input-30" />
                    </VControl>
                  </VField>
                </td>
                <td class="tg-0lax" v-for="index in jumlahImdex">
                  <Checkbox v-model="input.edukasiYa[index]" :binary="true" />
                </td>
              </tr>
              <tr>
                <td class="bg-col"></td>
                <td class="bg-col2">TINDAKAN PENCEGAHAN JATUH RESIKO TINGGI</td>
                <td class="tg-0lax">

                </td>
                <td class="tg-0lax">

                </td>
              </tr>
              <tr>
                <td class="bg-col">1</td>
                <td class="bg-col2">Rendahkan posisi tempat tidur</td>
                <td class="tg-0lax">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.rendahkan" placeholder="" class="is-rounded_Z input-30" />
                    </VControl>
                  </VField>
                </td>
                <td class="tg-0lax" v-for="index in jumlahImdex">
                  <Checkbox v-model="input.rendahkanYa[index]" :binary="true" />
                </td>
              </tr>
              <tr>
                <td class="bg-col">2</td>
                <td class="bg-col2">Pasang / tempelkan stiker jatuh warna kuning pada gelang identitas</td>
                <td class="tg-0lax">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.pasang" placeholder="" class="is-rounded_Z input-30" />
                    </VControl>
                  </VField>
                </td>
                <td class="tg-0lax" v-for="index in jumlahImdex">
                  <Checkbox v-model="input.pasangYa[index]" :binary="true" />
                </td>
              </tr>
              <tr>
                <td class="bg-col">3</td>
                <td class="bg-col2">Beri tanda segitiga waBeri tanda segitiga warna kuning pada tempat tidur, tiang
                  infuse, brandkard ( saat transfer pasien), kursi roda dan atau pintu kamar pasien</td>
                <td class="tg-0lax">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.beriTanda" placeholder="" class="is-rounded_Z input-30" />
                    </VControl>
                  </VField>
                </td>

                <td class="tg-0lax" v-for="index in jumlahImdex">
                  <Checkbox v-model="input.beritandaYa[index]" :binary="true" />
                </td>

              </tr>
              <tr>
                <td class="bg-col">4</td>
                <td class="bg-col2">Beri tanda segitiga waBeri tanda segitiga warna kuning pada tempat tidur, tiang
                  infuse, brandkard ( saat transfer pasien), kursi roda dan atau pintu kamar pasien</td>
                <td class="tg-0lax">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.beritanda4" placeholder="" class="is-rounded_Z input-30" />
                    </VControl>
                  </VField>
                </td>
                <td class="tg-0lax" v-for="index in jumlahImdex">
                  <Checkbox v-model="input.beritanda4Ya[index]" :binary="true" />
                </td>
              </tr>
              <tr>
                <td class="bg-col">5</td>
                <td class="bg-col2">Tempatkan kebutuhan pasien (makan,minum dll) dan alat bantu seperti
                  walkers/tongkat,kaca mata dll dalam jangkauan pasien</td>
                <td class="tg-0lax">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.tempatkan" placeholder="" class="is-rounded_Z input-30" />
                    </VControl>
                  </VField>
                </td>
                <td class="tg-0lax" v-for="index in jumlahImdex">
                  <Checkbox v-model="input.tempatkanYa[index]" :binary="true" />
                </td>
              </tr>
              <tr>
                <td class="bg-col">6</td>
                <td class="bg-col2">Libatkan keluarga untuk pendampingann</td>
                <td class="tg-0lax">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.libatkan" placeholder="" class="is-rounded_Z input-30" />
                    </VControl>
                  </VField>
                </td>

                <td class="tg-0lax" v-for="index in jumlahImdex">
                  <Checkbox v-model="input.libatkanYa[index]" :binary="true" />
                </td>

              </tr>
              <tr>
                <td class="bg-col">7</td>
                <td class="bg-col2">Tempatkan pasien di dekat nurs station bila memungkinkan</td>
                <td class="tg-0lax">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.nurs" placeholder="" class="is-rounded_Z input-30" />
                    </VControl>
                  </VField>
                </td>
                <td class="tg-0lax" v-for="index in jumlahImdex">
                  <Checkbox v-model="input.nursYa[index]" :binary="true" />
                </td>
              </tr>
              <tr>
                <td class="bg-col">8</td>
                <td class="bg-col2">Pastikan apakah pasien mendapatakan obat yang menyokong terjadinya resiko jatuh,
                  misalnya obat sedatif, psikotropika, anti depresan, obat diabetikum dan lain-lain</td>
                <td class="tg-0lax">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.pasienMenyokong" placeholder="" class="is-rounded_Z input-30" />
                    </VControl>
                  </VField>
                </td>
                <td class="tg-0lax" v-for="index in jumlahImdex">
                  <Checkbox v-model="input.pasienMenyokong[index]" :binary="true" />
                </td>
              </tr>
              <tr>
                <td class="bg-col">9</td>
                <td class="bg-col2">Pastikan apakah pasien yang dilakukan pemeriksaan diagnostik dalam
                  pengawasan/pendampingan</td>
                <td class="tg-0lax">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.diagnosa" placeholder="" class="is-rounded_Z input-30" />
                    </VControl>
                  </VField>
                </td>

                <td class="tg-0lax" v-for="index in jumlahImdex">
                  <Checkbox v-model="input.diagnosaYa[index]" :binary="true" />
                </td>

              </tr>
              <tr>
                <td class="bg-col">10</td>
                <td class="bg-col2">Transfer yang aman (pastikan pasien yang diangkut dengan brandcard/tempat tidur,
                  posisi bedside rel dalam keadaan terpasang)</td>
                <td class="tg-0lax">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.transfer" placeholder="" class="is-rounded_Z input-30" />
                    </VControl>
                  </VField>
                </td>
                <td class="tg-0lax" v-for="index in jumlahImdex">
                  <Checkbox v-model="input.transferYa[index]" :binary="true" />
                </td>
              </tr>
            </table>
          </div>
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
const COLLECTION: any = ref('PengkajianUlangRisikoJatuhPasienDewasa') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  tanggal: [],
  riwayatJatuh: [],
  kondisiKesehatan: [],
  bantuanAmbulan: [],
  kruk: [],
  bepegangan: [],
  terapi: [],
  gayaBerjalan: [],
  normal: [],
  adaGangguanBerjalan: [],
  sadarKemampuanSendiri: [],
  lupaKeterbatasan: [],
  totalSkor: [],
  tglPencegahan: [],
  orientasikanYa: [],
  transferYa: [],
  diagnosaYa: [],
  pasienMenyokong: [],
  nursYa: [],
  libatkanYa: [],
  tempatkanYa: [],
  beritanda4Ya: [],
  beritandaYa: [],
  pasangYa: [],
  rendahkanYa: [],
  edukasiYa: [],
  pastikanYa: [],

  kunciRodaYa: [],
  detailsTglSkor: [{
    no: 1,
    tgl: new Date(),
  }]
})
const d_Dokter: any = ref([])
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
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
  position: sticky;
  background-color: aliceblue;
  left: 301px;
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
