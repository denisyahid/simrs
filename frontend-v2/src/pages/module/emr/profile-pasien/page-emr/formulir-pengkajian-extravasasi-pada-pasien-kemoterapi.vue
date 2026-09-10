<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>{{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpan" @kembaliKeun="kembaliKeun" isHideST></ButtonEmr>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="columns is-multiline p-2">
    <div class="column is-12">
      <VCard>
        <div class="columns is-multiline">
          <div class="column is-2">
            <h1 style="font-weight: bold">Nama Pasien :</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput v-model="input.namaPasien" class="input" type="text" />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <h1 style="font-weight: bold">Tanggal Lahir Pasien:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
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
          <div class="column is-2">
            <h1 style="font-weight: bold">Jenis Kelamin :</h1>
          </div>
          <div class="column is-10" style="display: flex">
            <VField v-for="items in JenisKelamin" :key="items.value">
              <VControl raw subcontrol>
                <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label" :label="items.label"
                  color="primary" circle />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <h1 style="font-weight: bold">No. Rekam Medis:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput type="text" class="input" placeholder="No. Rekam Medis" v-model="input.norm" />
              </VControl>
            </VField>
          </div>

          <div class="column is-2">
            <h1 style="font-weight: bold">Tanggal dan Jam:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VDatePicker v-model="input.tanggalKunjunganPasien" mode="dateTime" style="width: 100%" trim-weeks
                :max-date="new Date()">
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" placeholder="Tanggal dan Jam" v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>
          <div class="column is-2">
            <h1 style="font-weight: bold">RUANGAN:</h1>
          </div>
          <div class="column is-10">
            <VField class="is-autocomplete-select">
              <VControl class="prime-auto-cus" icon="feather:search">
                <AutoComplete v-model="input.ruangan" :suggestions="d_Ruangan" :optionLabel="'label'"
                  @complete="fetchRuangan($event)" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ruangan..." class="mt-2" />
              </VControl>
            </VField>
          </div>

          <div class="column is-12">
            <div class="column is-12">
              <div class="column is-12" style="overflow-y: auto;">
                <table width="100%" class="table-pri" style="width:100% !important">
                  <thead>
                    <tr class="tr-pri">
                      <th class="th-pri" style="vertical-align: inherit; text-align: center">Kejadian Extravasasi</th>
                      <th class="th-pri" style="vertical-align: inherit; text-align: center" colspan="2">Extravasasi
                        Terdeteksi
                      </th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr>
                      <td class="td-pri" style="vertical-align: inherit;" width="50%">
                        <div class="column is-flex">
                          <div class="column is-3">
                            <p>Kejadian Extravasasi:</p>
                          </div>
                          <div class="column is-9">
                            <VField>
                              <VControl>
                                <VInput placeholder="Kejadian Extravasasi" v-model="input.KejadianExtravasasi" rows="5"
                                  type="text" />
                              </VControl>
                            </VField>
                          </div>
                        </div>

                        <div class="column is-mulitiline">
                          <div class="column is-12">
                            <p>Nama obat yang menimbulkan extravasasi:</p>
                          </div>
                          <div class="column is-12">
                            <VField class="is-autocomplete-select">
                              <VControl icon="feather:search">
                                <AutoComplete v-model="input.namaObat" :suggestions="d_ObatRS"
                                  @complete="fetchObat($event)" :optionLabel="'namaproduk'" :dropdown="true"
                                  :minLength="3" :appendTo="'body'" @item-select="search(item.namaObat)"
                                  :loadingIcon="'pi pi-spinner'" :field="'namaproduk'" placeholder="Cari Nama Obat" />
                              </VControl>
                            </VField>
                          </div>
                        </div>

                        <div class="column is-multiline">
                          <div class="column is-12">
                            <p>Perkiraan volume yang menimbulkan extravasasi :</p>
                          </div>
                          <div class="column is-12">
                            <VField>
                              <VControl>
                                <VInput placeholder="Perkiraan volume yang menimbulkan extravasasi "
                                  v-model="input.PerkiraanExtravasasiVolume" rows="5" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </td>

                      <td class="td-pri" style="vertical-align: inherit;" width="50%" colspan="2">
                        <div class="column is-flex">
                          <div class="column is-12">
                            <VField>
                              <VControl>
                                <VCheckbox label="Selama pemberian" v-model="input.SelamaPemberian"
                                  true-value="Selama pemberian" rows="5" />
                              </VControl>
                            </VField>
                          </div>
                        </div>

                        <div class="column is-mulitiline">
                          <div class="column is-12">
                            <VField>
                              <VControl>
                                <VCheckbox label="Segera setelah pemberian" v-model="input.SegeraSetelahPemberian"
                                  true-value="Segera setelah pemberian" rows="5" />
                              </VControl>
                            </VField>
                          </div>
                        </div>

                        <div class="column is-flex">
                          <div class="column is-2">
                            <VCheckbox v-model="input.jamSetelahPemberian" true-value="jam setelah pemberian" />
                          </div>
                          <div class="column is-4">
                            <VField>
                              <VControl>
                                <VInput v-model="input.jamSetelahPemberianKeterangan" class="input primary"
                                  type="text" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-6">
                            <p>jam setelah pemberian</p>
                          </div>
                        </div>

                        <div class="column is-flex">
                          <div class="column is-2">
                            <VCheckbox v-model="input.hariSetelahPemberian" true-value="hari setelah pemberian" />
                          </div>
                          <div class="column is-4">
                            <VField>
                              <VControl>
                                <VInput v-model="input.hariSetelahPemberianKeterangan" class="input primary"
                                  type="text" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-6">
                            <p>hari setelah pemberian</p>
                          </div>
                        </div>
                      </td>
                    </tr>
                  </tbody>

                  <thead>
                    <tr class="tr-pri">
                      <th class="th-pri" style="vertical-align: inherit; text-align: center">Type Akses Vena</th>
                      <th class="th-pri" style="vertical-align: inherit; text-align: center" colspan="2">Pemasangan</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr>
                      <td class="td-pri" style="vertical-align: inherit;" width="50%">
                        <div class="column is-flex">
                          <div class="column is-12">
                            <VField v-for="items in tipeAksesVena" :key="items.value">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.tipeAksesVena" class="pt-1 pb-1" :true-value="items.label"
                                  :label="items.label" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </td>

                      <td class="td-pri" style="vertical-align: inherit;" width="50%" colspan="2">
                        <div class="column is-flex">
                          <div class="column is-5">
                            <p>Tanggal & waktu pemasangan IV cannula :</p>
                          </div>
                          <div class="column is-7">
                            <VField>
                              <VDatePicker v-model="input.pemasanganIVCannula" mode="dateTime" style="width: 100%"
                                trim-weeks :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                  <VField>
                                    <VControl icon="feather:calendar" fullwidth>
                                      <VInput :value="inputValue" placeholder="Tanggal dan Jam" v-on="inputEvents" />
                                    </VControl>
                                  </VField>
                                </template>
                              </VDatePicker>
                            </VField>
                          </div>
                        </div>

                        <div class="column is-flex">
                          <div class="column is-3">
                            <p>Petugas</p>
                          </div>
                          <div class="column is-9">
                            <VField class="is-autocomplete-select">
                              <VControl icon="feather:search">
                                <AutoComplete v-model="input.petugas" :suggestions="d_Petugas"
                                  @complete="fetchPegawai($event)" :optionLabel="'nama'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" @item-select="search(item.namaObat)" :loadingIcon="'pi pi-spinner'"
                                  :field="'nama'" placeholder="Cari Petugas" />
                              </VControl>
                            </VField>
                          </div>
                        </div>

                        <div class="column is-flex">
                          <div class="column is-4">
                            <h1>Sulit memasang</h1>
                          </div>
                          <div class="column 8" style="display: flex">
                            <VField>
                              <VControl>
                                <VCheckbox v-model="input.sulitMemasang" class="pt-1 pb-1" true-value="Ya" label="Ya"
                                  color="primary" circle />
                              </VControl>
                            </VField>
                            <VField>
                              <VControl>
                                <VCheckbox v-model="input.sulitMemasang" class="pt-1 pb-1" true-value="Tidak"
                                  label="Tidak" color="primary" circle />
                              </VControl>
                            </VField>
                          </div>
                        </div>

                        <div class="column is-multiline">
                          <div class="column is-12">
                            <p>No. Needle yang dipasang :</p>
                          </div>
                          <div class="column is-12">
                            <VField>
                              <VControl>
                                <VInput placeholder="No. Needle" v-model="input.noNeedle" rows="5" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </td>
                    </tr>
                  </tbody>

                  <thead>
                    <tr class="tr-pri">
                      <th class="th-pri" style="vertical-align: inherit; text-align: center">Cara Pemberian</th>
                      <th class="th-pri" colspan="2" style="vertical-align: inherit; text-align: center">Tanda dan
                        Gejala</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr>
                      <td class="td-pri" style="vertical-align: inherit;" width="50%">
                        <div class="column">
                          <div class="column is-12">
                            <VField v-for="items in caraPemberian" :key="items.value">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.caraPemberian" class="pt-1 pb-1" :true-value="items.label"
                                  :label="items.label" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </td>

                      <td class="td-pri" style="vertical-align: inherit;" width="50%" colspan="2">
                        <div class="columns is-multiline">
                          <div class="column is-4 is-flex" v-for="(items, index) in tandaDanGejala" :key="items.value">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input['tandaDanGejala_' + index]" class="pt-1 pb-1"
                                  :true-value="items.label" :label="items.label" color="primary" square />
                              </VControl>
                            </VField>
                            <VField v-if="items.value == 'Lainnya'" class="pt-5 pb-1 column is-12">
                              <VControl>
                                <VInput placeholder="Lainnya" v-model="input.tandaDanGejalaLainnya" rows="5" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </td>
                    </tr>

                    <tr>
                      <td class="td-pri">
                        <div class="column is-flex">
                          <div class="column is-3">
                            <p>Warna Kulit:</p>
                          </div>
                          <div class="column is-9">
                            <VField>
                              <VControl>
                                <VInput placeholder="Warna Kulit" v-model="input.WarnaKulit" rows="5" type="text" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </td>
                      <td class="td-pri" colspan="2">
                        <div class="column is-flex">
                          <div class="column is-3">
                            <p>Luas Area:</p>
                          </div>
                          <div class="column is-9">
                            <VField>
                              <VControl>
                                <VInput placeholder="cm" v-model="input.WarnaKulit" rows="5" type="number" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </td>
                    </tr>
                  </tbody>

                  <thead>
                    <tr class="tr-pri">
                      <th class="th-pri" colspan="3" rowspan="3" style="vertical-align: inherit; text-align: center">
                        Cara
                        Pemberian</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr>
                      <td class="td-pri" style="vertical-align: inherit; text-align: center"
                        v-for="(items, index) in tandaiAreaExtravasasi" :key="items.value">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input.tandaiAreaExtravasasi" class="pt-1 pb-1" :true-value="items.label"
                              :label="items.label" color="primary" square />
                          </VControl>
                        </VField>
                        <ImgDraw :elemenID="'GambarTubuh_' + index" height="520" width="600" :imageSrc="items.img" />
                      </td>
                    </tr>
                  </tbody>
                </table>
                <table width="100%" class="table-pri" style="width:100% !important">
                  <thead>
                    <tr class="tr-pri">
                      <th class="th-pri" colspan="3" style="vertical-align: inherit; text-align: center">Tindakan Segera
                      </th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr>
                      <th class="th-pri" width="33%">
                        <div class="column is-flex">
                          <div class="column is-5">
                            <h1 style="font-weight: bold">Tanggal dan Jam</h1>
                          </div>
                          <div class="column is-7">
                            <VField>
                              <VDatePicker v-model="input.tanggalTindakanSegera" mode="dateTime" style="width: 100%"
                                trim-weeks>
                                <template #default="{ inputValue, inputEvents }">
                                  <VField>
                                    <VControl icon="feather:calendar" fullwidth>
                                      <VInput :value="inputValue" placeholder="Tanggal dan Jam" v-on="inputEvents" />
                                    </VControl>
                                  </VField>
                                </template>
                              </VDatePicker>
                            </VField>
                          </div>
                        </div>

                      </th>
                      <th class="th-pri">
                        <div class="column 8">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.kompres" class="pt-1 pb-1" true-value="Kompres dingin"
                                label="Kompres dingin" color="primary" circle />
                            </VControl>
                          </VField>
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.kompres" class="pt-1 pb-1" true-value="Kompres hangat"
                                label="Kompres hangat" color="primary" circle />
                            </VControl>
                          </VField>
                        </div>
                      </th>
                      <th class="th-pri">
                        <div class="column is-flex">
                          <div class="column is-4">
                            <h1>Sudah difoto</h1>
                          </div>
                          <div class="column 8" style="display: flex">
                            <VField>
                              <VControl>
                                <VCheckbox v-model="input.foto" class="pt-1 pb-1" true-value="Ya" label="Ya"
                                  color="primary" circle />
                              </VControl>
                            </VField>
                            <VField>
                              <VControl>
                                <VCheckbox v-model="input.foto" class="pt-1 pb-1" true-value="Tidak" label="Tidak"
                                  color="primary" circle />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </th>
                    </tr>
                  </tbody>
                </table>

                <table style="width:100% !important" width="100%" class="table-pri">
                  <thead>
                    <tr class="tr-pri">
                      <th class="th-pri" style="vertical-align: inherit; text-align: center" width="50%">Konsultasi</th>
                      <th class="th-pri" style="vertical-align: inherit; text-align: center" colspan="2">Ceklist
                        Pemulangan Pasien
                      </th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr>
                      <td class="td-pri" style="vertical-align: inherit;" width="50%">
                        <div class="column is-flex">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.konsultasi_1" label="Divisi Kulit" true-value="Divisi Kulit"
                                color="primary" />
                            </VControl>
                          </VField>
                          <VField>
                            <VControl>
                              <VInput v-model="input.konsultasiKeteranganDivisiKulit" type="text"
                                placeholder="keterangan" color="primary" />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-flex">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.konsultasi_2" label="Bedah plastik" true-value="Bedah plastik"
                                color="primary" />
                            </VControl>
                          </VField>
                          <VField>
                            <VControl>
                              <VInput v-model="input.konsultasiKeteranganBedahPlastik" type="text"
                                placeholder="keterangan" color="primary" />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-flex">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.konsultasi_3" label="Lain-lain" true-value="Lain-lain"
                                color="primary" />
                            </VControl>
                          </VField>
                          <VField>
                            <VControl>
                              <VInput v-model="input.konsultasiKeteranganLain" type="text" placeholder="keterangan"
                                color="primary" />
                            </VControl>
                          </VField>
                        </div>
                      </td>

                      <td>
                        <div class="column is-12 is-multiline">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklistPemulanganPasien_1"
                                true-value="Lembar informasi termasuk tindakan mandiri"
                                label="Lembar informasi termasuk tindakan mandiri" color="primary" />
                            </VControl>
                          </VField>
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklistPemulanganPasien_2"
                                true-value="Obat dibawa pulang (analgetik, cream)"
                                label="Obat dibawa pulang (analgetik, cream)" color="primary" />
                            </VControl>
                          </VField>
                          <VField>
                            <VControl>
                              <VCheckbox v-model="input.ceklistPemulanganPasien_3" true-value="Amprahkan perjanjian"
                                label="Amprahkan perjanjian" color="primary" />
                            </VControl>
                          </VField>

                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="column is-12" style="overflow-y: auto;">
                <table style="width:150% !important" width="150%" class="table-pri">
                  <thead>
                    <tr class="tr-pri">
                      <th class="th-pri" style="vertical-align: inherit; text-align: center"></th>
                      <th class="th-pri" style="vertical-align: inherit; text-align: center">Hari 1</th>
                      <th class="th-pri" style="vertical-align: inherit; text-align: center">Hari 3</th>
                      <th class="th-pri" style="vertical-align: inherit; text-align: center">Hari 5</th>
                      <th class="th-pri" style="vertical-align: inherit; text-align: center">Hari 7</th>
                      <th class="th-pri" style="vertical-align: inherit; text-align: center">Hari 14</th>
                      <th class="th-pri" style="vertical-align: inherit; text-align: center">Hari 21</th>
                      <th class="th-pri" style="vertical-align: inherit; text-align: center">Hari 28</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr v-for="(item, index) in flowchartPengkajian" :key="index">
                      <td class="td-pri" style="font-weight: bold; vertical-align: inherit;">{{ item.label }}</td>
                      <td class="td-pri" v-for="n in 7" :key="n" style="text-align: center !important;">
                        <div class="column is-12">
                          <VField v-if="item.label === 'Tanggal'">
                            <VDatePicker v-model="input[`${item.label}-${n}`]" mode="date" trim-weeks
                              :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" :placeholder="`${item.label} ${n}`"
                                      v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>

                          <VField v-if="item.label === 'Warna Kulit'">
                            <VControl>
                              <VInput v-model="input[`${item.label}-${n}`]" type="text"
                                :placeholder="`${item.label} ${n}`" color="primary" />
                            </VControl>
                          </VField>

                          <VField v-if="item.label === 'Temp kulit'">
                            <VControl>
                              <VInput v-model="input[`${item.label}-${n}`]" type="text"
                                :placeholder="`${item.label} ${n}`" color="primary" />
                            </VControl>
                          </VField>

                          <VField v-if="item.label === 'Integritas kulit'">
                            <VControl>
                              <VInput v-model="input[`${item.label}-${n}`]" type="text"
                                :placeholder="`${item.label} ${n}`" color="primary" />
                            </VControl>
                          </VField>

                          <VField v-if="item.label === 'Oedem'">
                            <VControl>
                              <VInput v-model="input[`${item.label}-${n}`]" type="text"
                                :placeholder="`${item.label} ${n}`" color="primary" />
                            </VControl>
                          </VField>

                          <VField v-if="item.label === 'Mobilitas'">
                            <VControl>
                              <VInput v-model="input[`${item.label}-${n}`]" type="text"
                                :placeholder="`${item.label} ${n}`" color="primary" />
                            </VControl>
                          </VField>

                          <VField v-if="item.label === 'Kesemutan'">
                            <VControl>
                              <VInput v-model="input[`${item.label}-${n}`]" type="text"
                                :placeholder="`${item.label} ${n}`" color="primary" />
                            </VControl>
                          </VField>

                          <VField v-if="item.label === 'Nyeri'">
                            <VControl>
                              <VInput v-model="input[`${item.label}-${n}`]" type="text"
                                :placeholder="`${item.label} ${n}`" color="primary" />
                            </VControl>
                          </VField>

                          <VField v-if="item.label === 'Panas'">
                            <VControl>
                              <VInput v-model="input[`${item.label}-${n}`]" type="number"
                                :placeholder="`${item.label} ${n}`" color="primary" />
                            </VControl>
                          </VField>

                          <VField v-if="item.label === 'Photo'">
                            <VControl>
                              <VInput v-model="input[`${item.label}-${n}`]" type="text"
                                :placeholder="`${item.label} ${n}`" color="primary" />
                            </VControl>
                          </VField>

                          <VField v-if="item.label === 'Nama & Tanda tangan'">
                            <VControl>
                              <TandaTangan :elemenID="`tandaTangan-${n}`" :width="'150'" :height="'150'" class="dek" />
                              <VControl class="prime-auto">
                                <AutoComplete v-model="input[`${item.label}-${n}`]" :suggestions="d_Perawat"
                                  @complete="fetchPerawat($event)" :optionLabel="'label'" :dropdown="true"
                                  :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                  placeholder="Nama..." class="mt-2" />
                              </VControl>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="column is-12" style="overflow-y: auto;">
                <table class="table-pri">
                  <thead>
                    <tr class="tr-pri">
                      <th class="th-pri" style="vertical-align: inherit; text-align: center; font-weight: bold">Skala
                        Penilaian
                      </th>
                      <th class="th-pri" style="vertical-align: inherit; text-align: center">0</th>
                      <th class="th-pri" style="vertical-align: inherit; text-align: center">1</th>
                      <th class="th-pri" style="vertical-align: inherit; text-align: center">2</th>
                      <th class="th-pri" style="vertical-align: inherit; text-align: center">3</th>
                      <th class="th-pri" style="vertical-align: inherit; text-align: center">4</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr>
                      <td class="td-pri" style="vertical-align: inherit; text-align: center; font-weight: bold">Warna
                        Kulit</td>
                      <td class="td-pri" style="vertical-align: inherit; text-align: center">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="input.warnaKulit" type="checkbox" true-value="Normal" label="Normal"
                              color="primary" />
                          </VControl>
                        </VField>
                      </td>
                      <td class="td-pri" style="vertical-align: inherit; text-align: center">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="input.warnaKulit" type="checkbox" true-value="Pink" label="Pink"
                              color="primary" />
                          </VControl>
                        </VField>
                      </td>
                      <td class="td-pri" style="vertical-align: inherit; text-align: center">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="input.warnaKulit" type="checkbox" true-value="Merah" label="Merah"
                              color="primary" />
                          </VControl>
                        </VField>
                      </td>
                      <td class="td-pri" style="vertical-align: inherit; text-align: center">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="input.warnaKulit" type="checkbox" true-value="Pucat" label="Pucat"
                              color="primary" />
                          </VControl>
                        </VField>
                      </td>
                      <td class="td-pri" style="vertical-align: inherit; text-align: center">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="input.warnaKulit" type="checkbox" true-value="Kehitaman"
                              label="Kehitaman" color="primary" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>

                    <tr>
                      <td class="td-pri" style="vertical-align: inherit; text-align: center; font-weight: bold">
                        Temperature kulit
                      </td>
                      <td class="td-pri" style="vertical-align: inherit; text-align: center">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="input.tempeaturKulit" type="checkbox" true-value="Normal" label="Normal"
                              color="primary" />
                          </VControl>
                        </VField>
                      </td>
                      <td class="td-pri" style="vertical-align: inherit; text-align: center">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="input.tempeaturKulit" type="checkbox" true-value="Hangat" label="Hangat"
                              color="primary" />
                          </VControl>
                        </VField>
                      </td>
                      <td class="td-pri" style="vertical-align: inherit; text-align: center">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="input.tempeaturKulit" type="checkbox" true-value="Panas" label="Panas"
                              color="primary" />
                          </VControl>
                        </VField>
                      </td>
                      <td style="background-color: gray;" class="td-pri"></td>
                      <td style="background-color: gray;" class="td-pri"></td>
                    </tr>

                    <tr>
                      <td class="td-pri" style="vertical-align: inherit; text-align: center; font-weight: bold">
                        Temperature Kulit
                      </td>
                      <td class="td-pri" style="vertical-align: inherit; text-align: center">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="input.tempeaturKulit2" type="checkbox" true-value="Normal"
                              label="Normal" color="primary" />
                          </VControl>
                        </VField>
                      </td>
                      <td class="td-pri" style="vertical-align: inherit; text-align: center">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="input.tempeaturKulit2" type="checkbox" true-value="Bula" label="Bula"
                              color="primary" />
                          </VControl>
                        </VField>
                      </td>
                      <td class="td-pri" style="vertical-align: inherit; text-align: center">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="input.tempeaturKulit2" type="checkbox"
                              true-value="kerusakanPermukaanKulit" label="Kerusakan Permukaan Kulit" color="primary" />
                          </VControl>
                        </VField>
                      </td>
                      <td class="td-pri" style="vertical-align: inherit; text-align: center">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="input.tempeaturKulit2" type="checkbox"
                              true-value="kerusakanJaringanSubkutan" label="Kerusakan Jaringan Subkutan"
                              color="primary" />
                          </VControl>
                        </VField>
                      </td>
                      <td class="td-pri" style="vertical-align: inherit; text-align: center">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="input.tempeaturKulit2" type="checkbox"
                              true-value="kerusakanJaringanSampaiKeOtotAtauTulang"
                              label="Kerusakan Jaringan Sampai Ke Otot atau Tulang" color="primary" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>

                    <tr>
                      <td class="td-pri" style="vertical-align: inherit; text-align: center; font-weight: bold">Oedem
                      </td>
                      <td class="td-pri" style="vertical-align: inherit; text-align: center">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="input.oedem" type="checkbox" true-value="tidakAda" label="Tidak Ada"
                              color="primary" />
                          </VControl>
                        </VField>
                      </td>
                      <td class="td-pri" style="vertical-align: inherit; text-align: center">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="input.oedem" type="checkbox" true-value="nonPitting" label="Non Pitting"
                              color="primary" />
                          </VControl>
                        </VField>
                      </td>
                      <td class="td-pri" style="vertical-align: inherit; text-align: center">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="input.oedem" type="checkbox" true-value="Pitting" label="Pitting"
                              color="primary" />
                          </VControl>
                        </VField>
                      </td>
                      <td style="background-color: gray;" class="td-pri"></td>
                      <td style="background-color: gray;" class="td-pri"></td>
                    </tr>

                    <tr>
                      <td class="td-pri" style="vertical-align: inherit; text-align: center; font-weight: bold">
                        Mobilitas</td>
                      <td class="td-pri" style="vertical-align: inherit; text-align: center">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="input.Mobilitas" type="checkbox" true-value="Penuh" label="Penuh"
                              color="primary" />
                          </VControl>
                        </VField>
                      </td>
                      <td class="td-pri" style="vertical-align: inherit; text-align: center">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="input.Mobilitas" type="checkbox" true-value="sedikitTerbatas"
                              label="Sedikit Terbatas" color="primary" />
                          </VControl>
                        </VField>
                      </td>
                      <td class="td-pri" style="vertical-align: inherit; text-align: center">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="input.Mobilitas" type="checkbox" true-value="sangatTerbatas"
                              label="Sangat Terbatas" color="primary" />
                          </VControl>
                        </VField>
                      </td>
                      <td class="td-pri">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="input.Mobilitas" type="checkbox" true-value="Imobilisasi"
                              label="Imobilisasi" color="primary" />
                          </VControl>
                        </VField>
                      </td>
                      <td style="background-color: gray;" class="td-pri"></td>
                    </tr>

                    <tr>
                      <td class="td-pri" style="vertical-align: inherit; text-align: center; font-weight: bold">Skala
                        Nyeri</td>
                      <td class="td-pri" style="vertical-align: inherit; text-align: center" colspan="5">
                        <VField>
                          <VControl>
                            <VInput type="number" v-model="input.skalaNyeri"
                              placeholder="Gunakan skala 0-10, 0 = Tidak nyeri 10 = Nyeri hebat" color="primary" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>

                    <tr>
                      <td class="td-pri" style="vertical-align: inherit; text-align: center; font-weight: bold">Panas
                      </td>
                      <td class="td-pri" style="vertical-align: inherit; text-align: center">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="input.panas" type="checkbox" true-value="Normal" label="Normal"
                              color="primary" />
                          </VControl>
                        </VField>
                      </td>
                      <td class="td-pri" style="vertical-align: inherit; text-align: center">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="input.panas" type="checkbox" true-value="Meningkat" label="Meningkat"
                              color="primary" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>


            </div>
          </div>
        </div>
      </VCard>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from "@vueuse/core";
import { useApi } from "/@src/composable/useApi";
import { h, reactive, ref, computed, watch, onBeforeMount, onMounted, watchEffect } from "vue";
import { useRoute, onBeforeRouteLeave } from "vue-router";
import { useHead } from "@vueuse/head";
import * as H from "/@src/utils/appHelper";
import { useViewWrapper } from "/@src/stores/viewWrapper";
import { useUserSession } from "/@src/stores/userSession";
import AutoComplete from "primevue/autocomplete";
import * as EMR from "../page-emr-plugins/pengkajian-extravasasi-pada-pasien-kemoterapi";
import TandaTangan from "../page-emr-plugins/tanda-tangan.vue";
import Fieldset from "primevue/fieldset";
import ImgDraw from '../page-emr-plugins/img-draw.vue'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'

let ID_PASIEN = useRoute().query.nocmfk as string;
let NOREC_PD = useRoute().query.norec_pd as string;
let JenisKelamin = EMR.JenisKelamin();
let tipeAksesVena = EMR.tipeAksesVena();
let caraPemberian = EMR.caraPemberian();
let tandaDanGejala = EMR.tandaDanGejala();
let tandaiAreaExtravasasi = EMR.tandaiAreaExtravasasi();
let flowchartPengkajian: any = ref(EMR.flowchartPengkajian());

const props = withDefaults(
  defineProps<{
    pasien?: any;
    registrasi?: any;
    FORM_NAME?: string;
    FORM_URL?: string;
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: "",
    FORM_URL: "",
  }
);
const isAktive = ref();

const { y } = useWindowScroll();
const isStuck = computed(() => {
  return y.value > 30;
});
const route = useRoute()
const isLoading: any = ref(false);
const isDisabled: any = ref(false);
const isLoadingVitalSign: any = ref(false);
const d_Perawat: any = ref([]);
const d_Ruangan: any = ref([]);
const d_produk = ref([]);
const d_ObatRS = ref([]);
const d_Dokter = ref([]);
const d_Petugas = ref([]);
const dataTTD: any = ref([]);
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : "",
  NOREC_APD: "",
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
});
const COLLECTION: any = ref("FormulirPengkajianExtravasasiPadaPasienKemoterapi"); //table mongodb
const NOREC_EMRPASIEN: any = ref("");
const input = ref({});
const setView = () => {
  useHead({
    title: props.FORM_NAME + " - " + import.meta.env.VITE_PROJECT,
  });
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT);
  useViewWrapper().setFullWidth(true);
};

const loadGambar = async (element_id: string, value: string) => {
  let sigCanvas: any = document.getElementById(element_id);
  if (sigCanvas) {
    let context = sigCanvas.getContext("2d");
    context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
    let imagess = value
    let background = new Image();
    background.src = imagess
    background.onload = function () {
      context.drawImage(background, 0, 0, 600, 520);
    }
  }
}
const loadRiwayat = async () => {
  let response = await useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
  );
  if (response.length) {
    input.value = response[0]; //set ke inputan
    if (NOREC_EMRPASIEN.value == "") {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk;
    }
    dataTTD.value = response[0];
    H.tandaTangan().set("tandaTangan-1", dataTTD.value['tandaTangan-1']);
    H.tandaTangan().set("tandaTangan-2", dataTTD.value['tandaTangan-2']);
    H.tandaTangan().set("tandaTangan-3", dataTTD.value['tandaTangan-3']);
    H.tandaTangan().set("tandaTangan-4", dataTTD.value['tandaTangan-4']);
    H.tandaTangan().set("tandaTangan-5", dataTTD.value['tandaTangan-5']);
    H.tandaTangan().set("tandaTangan-6", dataTTD.value['tandaTangan-6']);
    H.tandaTangan().set("tandaTangan-7", dataTTD.value['tandaTangan-7']);
    await loadGambar("GambarTubuh_0", dataTTD.value.GambarTubuh_0)
    await loadGambar("GambarTubuh_1", dataTTD.value.GambarTubuh_1)
  }
};

const fetchPerawat = async (filter: any) => {
  // let data = filter.query ? filter.query : filter
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    )
    .then((response) => {
      d_Perawat.value = response;
    });
};

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=id&query=${filter.query}&limit=10`
  );
  d_Ruangan.value = response;
};

const getDataExist = async () => {
  await useApi()
    .get(
      `emr/get-data-exist?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    )
    .then((response) => {
      // input.value.beratBadan = response.beratBadan
      // input.value.tinggiBadan = response.tinggiBadan
      // input.value.IMT = response.IMT
      console.log();
    });
};

const fetchObat = async (filter: any) => {
  const response = await useApi().get(
    `/farmasi/dropdown-obat?namaproduk=${filter.query}&limit=10`
  );
  response.map((element: any) => {
    (element.label = element.productname), (element.value = element.id);
  });
  d_ObatRS.value = response;
};

const simpan = () => {
  let ID = input.value.id ? input.value.id : '';

  let object: any = {};

  object = input.value;
  object.pasien = H.setObjectPasien(props.pasien);
  object.registrasi = H.setObjectRegistrasi(props.registrasi);
  object['tandaTangan-1'] = H.tandaTangan().get("tandaTangan-1");
  object['tandaTangan-2'] = H.tandaTangan().get("tandaTangan-2");
  object['tandaTangan-3'] = H.tandaTangan().get("tandaTangan-3");
  object['tandaTangan-4'] = H.tandaTangan().get("tandaTangan-4");
  object['tandaTangan-5'] = H.tandaTangan().get("tandaTangan-5");
  object['tandaTangan-6'] = H.tandaTangan().get("tandaTangan-6");
  object['tandaTangan-7'] = H.tandaTangan().get("tandaTangan-7");
  object['GambarTubuh_0'] = H.tandaTangan().get("GambarTubuh_0");
  object['GambarTubuh_1'] = H.tandaTangan().get("GambarTubuh_1");

  let json = {
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: props.FORM_URL,
    name_form: props.FORM_NAME,
    jenis_emr: "asesmen_medis",
    data: object,
  };
  isLoading.value = true;
  useApi()
    .post(`/emr/simpan-emr`, json)
    .then((response: any) => {
      isLoading.value = false;
      NOREC_EMRPASIEN.value = response.norec_emr;
      loadRiwayat();
    })
    .catch((e: any) => {
      isLoading.value = false;
    });
};

const print = async () => {
  H.printBlade(
    `emr/formulir-catatan-pemberian-obat-kemoterapi?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
  );
};
console.log(props.registrasi)

const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien;
  input.value.jeniskelamin = props.pasien.jeniskelamin;
  input.value.norm = props.pasien.nocm;
  input.value.tanggalLahirPasien = props.pasien.tgllahir;
  input.value.tanggalKunjunganPasien = new Date();
  input.value.ruangan = props.registrasi.namaruangan;

  input.value.tglPembuatan = new Date();

  isLoadingVitalSign.value = true;
  await useApi()
    .get(
      "emr/auto-fill?norec_pd=" +
      props.registrasi.norec_pd +
      "&collection=VitalSign" +
      "&field=beratBadan,tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi"
    )
    .then((response) => {
      if (response != null) {
        input.value.beratBadan = response.beratBadan;
        input.value.tinggiBadan = response.tinggiBadan;
        input.value.IMT = response.IMT;
        input.value.lingkarPerut = response.lingkarPerut;
        input.value.tekananDarah = response.tekananDarah;
        input.value.pernapasan = response.pernapasan;
        input.value.suhu = response.suhu;
        input.value.nadi = response.nadi;
      }
      isLoadingVitalSign.value = false;
    });
};

const show = () => {
  showData.value = true;
};

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const fetchPegawai = async (filter: any) => {
  // let data = filter.query ? filter.query : filter
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Petugas.value = response
  })
}

onBeforeMount(async () => {
  try {
    await setView()
    await loadRiwayat()
    await setAutoFill()
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
</script>
