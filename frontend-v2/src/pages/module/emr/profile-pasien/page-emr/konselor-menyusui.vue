<template>
    <ConfirmDialog group="templating">
      <template #message="slotProps">
        <div style="width:500px;height:300px;">
          <table style="width:100%;height:100%;border-collapse: collapse">
            <tr>
              <td style="text-align:center;vertical-align:middle">
                <i :class="slotProps.message.icon" style="font-size:125px;text-align:center;color:#FDDA0D"></i>
              </td>
            </tr>
            <tr>
              <td style="padding:7px;text-align:center">
                <p style="font-size:large">{{ slotProps.message.message }}</p>
              </td>
            </tr>
          </table>
        </div>
      </template>
    </ConfirmDialog>
  
    <div>
      <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px">
          <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
            <div class="form-header-inner">
              <div class="left">
                <h3>Konselor Menyusui</h3>
              </div>
              <div class="right">
                <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                  @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun"></ButtonEmr>
              </div>
            </div>
          </div>
  
          <!-- form baru -->
  
          <div class="column is-12" style="margin: 10px;">
            <div class="columns is-mobile is-centered">
              <div class="column is-auto" style="display: flex; gap: 5px;"> <!-- Flexbox with gap -->
                <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" :isLoading="false"
                  @click="pilihTemplateFix(index)">
                  Pilih Template
                </VButton>
                <VButton type="button" rounded outlined color="info" raised icon="feather:file-text" :isLoading="false"
                  @click="pilihTemplate(index)">
                  Pilih Riwayat
                </VButton>
              </div>
              <div class="column is-4" style="display: none !important">
                <label class="label" style="margin-bottom: 5px;">Nama Perawat</label>
                <VField class="is-rounded-select_Z is-autocomplete-select" v-slot="{ id }">
                  <VControl icon="fa:stethoscope" class="prime-auto" style="max-width: 400px; width: 100%;" fullwidth>
                    <AutoComplete v-model="input.perawat" :suggestions="d_pegawai" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Masukan Nama Perawat" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
  
          <hr class="mt-5">
  
          <div class="column is-12 mb-0">
            <h1>Nama Template&emsp;&emsp;<span style="color:red">**Hanya diisi jika ingin
                membuat
                template</span></h1>
            <VField>
              <VControl>
                <VTextarea v-model="input.namatemplate" rows="1">
                </VTextarea>
              </VControl>
            </VField>
          </div>
  
          <hr class="mt-5">
  
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-4">
                <h1 style="font-weight: bold;">Tanggal Kedatangan</h1>
                <VField>
                  <VDatePicker v-model="input.kebtanggalKedatangan" mode="date" trim-weeks :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" class="is-rounded" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
              <div class="column is-4">
                <h1 style="font-weight: bold;">Jam Kedatangan</h1>
                <VField>
                  <VDatePicker v-model="input.kebjamKedatangan" color="green" mode="time" is24hr>
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:clock">
                          <VInput class="input form-timepicker is-rounded" :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
              <div class="column is-4">
                <h1 style="font-weight: bold;">Jam Asesmen Awal</h1>
                <VField>
                  <VDatePicker v-model="input.kebjamAsesmenAwal" color="green" mode="time" is24hr>
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:clock">
                          <VInput class="input form-timepicker is-rounded" :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>
          </div>
  
          <hr class="mt-5">
  
          <div class="column is-12">
            <div class="columns is-multiline">
                <div class="column is-1">
                    <VField>
                        <VControl>
                            <VCheckbox class="fontcheckbox" v-model="input.ANC" true-value="ANC" color="primary" circle />
                            <span v-html="highlightMatch('ANC')" class="highlighted-label"></span>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-1">
                    <VField>
                        <VControl>
                            <VCheckbox class="fontcheckbox" v-model="input.PNC" true-value="PNC" color="primary" circle />
                            <span v-html="highlightMatch('PNC')" class="highlighted-label"></span>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2">
                    <h1 style="font-weight: bold;">Kunjungan Pertama</h1>
                </div>
                <div class="column is-4">
                    <VField>
                        <VControl icon="">
                            <VInput type="text" v-model="input.kunjunganpertama" placeholder="" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-6"></div>
            </div>
          </div>
          <hr class="mt-5">
  
  
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12 mt-auto">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <h1>IDENTITAS IBU</h1>
                    <div class="columns is-multiline mt-5">
                        <div class="column is-4">
                            <h1 style="font-weight: bold;">Nama</h1>
                        </div>
                        <div class="column is-8">
                            <VField>
                                <VControl icon="">
                                    <VInput type="text" v-model="input.namaibu" placeholder="" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1 style="font-weight: bold;">Umur</h1>
                        </div>
                        <div class="column is-8">
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="number" class="input" placeholder="" v-model="input.umuribu" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>Tahun</VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1 style="font-weight: bold;">Pendidikan</h1>
                        </div>
                        <div class="column is-8">
                            <VField>
                                <VControl icon="">
                                    <VInput type="text" v-model="input.pendidikanibu" placeholder="" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1 style="font-weight: bold;">Agama</h1>
                        </div>
                        <div class="column is-8">
                            <VField class="is-autocomplete-select" v-slot="{ id }">
                                <VControl>
                                    <Multiselect v-model="input.agamaibu" :attrs="{ value }" placeholder="" label="label"
                                    :options="d_agama" :searchable="true" track-by="label" mode="single" autocomplete="off">
                                    </Multiselect>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1 style="font-weight: bold;">Pekerjaan</h1>
                        </div>
                        <div class="column is-8">
                            <VField>
                                <VControl icon="">
                                    <VInput type="text" v-model="input.pekerjaanibu" placeholder="" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1 style="font-weight: bold;">Alamat</h1>
                        </div>
                        <div class="column is-8">
                            <VField>
                                <VControl icon="">
                                    <VInput type="text" v-model="input.alamatibu" placeholder="" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                  </div>
                  <div class="column is-6">
                    <h1>IDENTITAS SUAMI</h1>
                    <div class="columns is-multiline mt-5">
                        <div class="column is-4">
                            <h1 style="font-weight: bold;">Nama</h1>
                        </div>
                        <div class="column is-8">
                            <VField>
                                <VControl icon="">
                                    <VInput type="text" v-model="input.namasuami" placeholder="" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1 style="font-weight: bold;">Umur</h1>
                        </div>
                        <div class="column is-8">
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="number" class="input" placeholder="" v-model="input.umursuami" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>Tahun</VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1 style="font-weight: bold;">Pendidikan</h1>
                        </div>
                        <div class="column is-8">
                            <VField>
                                <VControl icon="">
                                    <VInput type="text" v-model="input.pendidikansuami" placeholder="" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1 style="font-weight: bold;">Agama</h1>
                        </div>
                        <div class="column is-8">
                            <VField class="is-autocomplete-select" v-slot="{ id }">
                                <VControl>
                                    <Multiselect v-model="input.agamasuami" :attrs="{ value }" placeholder="" label="label"
                                    :options="d_agama" :searchable="true" track-by="label" mode="single" autocomplete="off">
                                    </Multiselect>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1 style="font-weight: bold;">Pekerjaan</h1>
                        </div>
                        <div class="column is-8">
                            <VField>
                                <VControl icon="">
                                    <VInput type="text" v-model="input.pekerjaansuami" placeholder="" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1 style="font-weight: bold;">Alamat</h1>
                        </div>
                        <div class="column is-8">
                            <VField>
                                <VControl icon="">
                                    <VInput type="text" v-model="input.alamatsuami" placeholder="" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
  
  
          <hr class="mt-5">


          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12">
                  <h1 style="font-weight: bold;">CARA KUNJUNGAN</h1>
              </div>
              <div class="column is-2">
                <VField vertical>
                  <VControl>
                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.datangsendiri" true-value="Datang sendiri" label="Datang sendiri"
                      color="primary" circle />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2">
                <VField vertical>
                  <VControl>
                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.rbrs" true-value="Dikirim dari RB/RS" label="Dikirim dari RB/RS"
                      color="primary" circle />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                  <VField>
                      <VControl icon="">
                          <VInput type="text" v-model="input.ketrbrs" placeholder="" />
                      </VControl>
                  </VField>
              </div>
              <div class="column is-2">
                <VField vertical>
                  <VControl>
                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.drspa" true-value="Dikirim dr.SpA" label="Dikirim dr.SpA"
                      color="primary" circle />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                  <VField>
                      <VControl icon="">
                          <VInput type="text" v-model="input.ketdrspa" placeholder="" />
                      </VControl>
                  </VField>
              </div>
              <div class="column is-2">
                <VField vertical>
                  <VControl>
                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.daripolianc" true-value="Dikirim dari Poli ANC" label="Dikirim dari Poli ANC"
                      color="primary" circle />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2">
                <VField vertical>
                  <VControl>
                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.drspog" true-value="Dikirim dari dr.SpOG" label="Dikirim dari dr.SpOG"
                      color="primary" circle />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                  <VField>
                      <VControl icon="">
                          <VInput type="text" v-model="input.ketdrspog" placeholder="" />
                      </VControl>
                  </VField>
              </div>
              <div class="column is-2">
                <VField vertical>
                  <VControl>
                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.drspa" true-value="Dikirim dari kader ASI" label="Dikirim dari kader ASI"
                      color="primary" circle />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                  <VField>
                      <VControl icon="">
                          <VInput type="text" v-model="input.ketkaderasi" placeholder="" />
                      </VControl>
                  </VField>
              </div>
            </div>
          </div>

          <hr class="mt-5">

          <div class="column is-12">
            <div class="columns is-multiline">
                <div class="column is-2">
                  <h1>Keluhan Utama</h1>
                </div>
                <div class="column is-10">
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.keluhanutama" rows="7">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
            </div>
          </div>
  
          <hr class="mt-5">


          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-6">
                  <h1 style="font-weight: bold;">RIWAYAT KEHAMILAN, PERSALINAN DAN LAKTASI SEBELUMNYA</h1>
              </div>
              <div class="column is-1">
                  <h1 style="font-weight: bold;">G</h1>
              </div>
              <div class="column is-1">
                  <VField>
                      <VControl icon="">
                          <VInput type="text" v-model="input.G" placeholder="" />
                      </VControl>
                  </VField>
              </div>
              <div class="column is-1">
                  <h1 style="font-weight: bold;">P</h1>
              </div>
              <div class="column is-1">
                  <VField>
                      <VControl icon="">
                          <VInput type="text" v-model="input.P" placeholder="" />
                      </VControl>
                  </VField>
              </div>
              <div class="column is-1">
                  <h1 style="font-weight: bold;">A</h1>
              </div>
              <div class="column is-1">
                  <VField>
                      <VControl icon="">
                          <VInput type="text" v-model="input.A" placeholder="" />
                      </VControl>
                  </VField>
              </div>
            </div>
            <div class="column is-12">
              <div style="" class="mt-1">
                  <table class="tabels" border="1" style="width: 100%;">
                      <thead>
                          <tr>
                              <th width="10%" style="vertical-align: inherit;text-align:center">
                                  Anak Ke</th>
                              <th width="20%" style="vertical-align: inherit;text-align:center">
                                  Jenis Kelamin</th>
                              <th width="10%" style="vertical-align: inherit;text-align:center">
                                  Umur/Tanggal Lahir</th>
                              <th style="vertical-align: inherit; text-align:center;" width="15%">
                                  Menyusui Eksklusif</th>
                              <th style="vertical-align: inherit; text-align:center;" width="15%">
                                  Umur Disapih</th>
                              <th style="vertical-align: inherit; text-align:center;" width="20%">
                                  Masalah Dalam Menyusui</th>
                              <th style="vertical-align: inherit;text-align:center;" width="10%">
                                  #
                              </th>
                          </tr>
                      </thead>
                      <tbody v-for="(input, index) in details" :key="index">
                          <tr>
                              <td class="td-po">
                                  <div class="pb-0">
                                      <VField>
                                          <VControl icon="feather:bookmark">
                                              <VInput type="text" v-model="input.anakke"
                                                  placeholder="" />
                                          </VControl>
                                      </VField>
                                  </div>
                              </td>
                              <td class="td-po">
                                  <div class="pb-0">
                                    <VField class="is-autocomplete-select" v-slot="{ id }">
                                        <VControl>
                                            <Multiselect v-model="input.jkanaktabel" :attrs="{ value }" placeholder="" label="label"
                                            :options="d_jeniskelamin" :searchable="true" track-by="label" mode="single" autocomplete="off">
                                            </Multiselect>
                                        </VControl>
                                    </VField>
                                  </div>
                              </td>
                              <td class="td-po">
                                  <div class="pb-0">
                                      <VField>
                                          <VControl icon="feather:bookmark">
                                              <VInput type="text" v-model="input.umur"
                                                  placeholder="Target" />
                                          </VControl>
                                      </VField>
                                  </div>
                              </td>
                              <td class="td-po">
                                  <div class="pb-0">
                                      <VField>
                                          <VControl icon="feather:bookmark">
                                              <VInput type="text" v-model="input.menyusuieksklusif"
                                                  placeholder="Target" />
                                          </VControl>
                                      </VField>
                                  </div>
                              </td>
                              <td class="td-po">
                                  <div class="pb-0">
                                      <VField>
                                          <VControl icon="feather:bookmark">
                                              <VInput type="text" v-model="input.umurdisapih"
                                                  placeholder="Target" />
                                          </VControl>
                                      </VField>
                                  </div>
                              </td>
                              <td class="td-po">
                                  <div class="pb-0">
                                      <VField>
                                          <VControl icon="feather:bookmark">
                                              <VInput type="text" v-model="input.masalahdalammenyusui"
                                                  placeholder="Target" />
                                          </VControl>
                                      </VField>
                                  </div>
                              </td>
                              <td class="td-rpo" style="vertical-align: inherit">
                                  <VButtons style="justify-content:space-around">
                                      <VIconButton type="button" raised circle icon="feather:plus"
                                          @click="addNewItem()" color="info" v-tooltip.bubble="'Tambah '">
                                      </VIconButton>
                                      <VIconButton class="mt-1" v-if="index > 0" type="button" raised
                                          circle icon="feather:trash" @click="removeItem(index)"
                                          color="danger">
                                      </VIconButton>
                                  </VButtons>
                              </td>
                          </tr>
                      </tbody>
                  </table>
              </div>
          </div>
        </div>

        <hr class="mt-5">


        <div class="column is-12 mt-5">
          <div class="columns is-multiline">
            <div class="column is-12">
                <h1 style="font-weight: bold;">KEHAMILAN DAN PERSALINAN TERAKHIR</h1>
            </div>
            <div class="column is-6">
              <div class="columns is-multiline">
                <div class="column is-4">
                    <h1 style="font-weight: bold;">Kehamilan ini</h1>
                </div>
                <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.diharapkan" true-value="Diharapkan"
                            label="Diharapkan" color="primary"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.tidakdiharapkan" true-value="Tidak Diharapkan"
                            label="Tidak Diharapkan" color="primary"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <h1 style="font-weight: bold;">Usaha pengguguran</h1>
                </div>
                <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.tidakadapengguguran" true-value="Tidak Ada"
                            label="Tidak Ada" color="primary"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.adapengguguran" true-value="Ada, dengan cara"
                            label="Ada, dengan cara" color="primary"/>
                        </VControl>
                    </VField>
                    <VField>
                        <VControl icon="">
                            <VInput type="text" v-model="input.ketadapengguguran" placeholder="" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <h1 style="font-weight: bold;">Keadaan Sekarang</h1>
                </div>
                <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.masihhamil" true-value="masih hamil"
                            label="masih hamil" color="primary"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField>
                        <VControl icon="">
                            <VInput type="text" v-model="input.ketmasihhamil" placeholder="" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4"></div>
                <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.sudahmelahirkan" true-value="sudah melahirkan, tanggal"
                            label="sudah melahirkan, tanggal" color="primary"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField>
                        <VControl icon="">
                            <VInput type="text" v-model="input.ketsudahmelahirkan" placeholder="" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4"></div>
                <div class="column is-2">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.tidakhamil" true-value="tidak"
                            label="tidak" color="primary"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.yahamil" true-value="ya, di"
                            label="ya, di" color="primary"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField>
                        <VControl icon="">
                            <VInput type="text" v-model="input.ketyahamil" placeholder="" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <h1 style="font-weight: bold;">Penyakit ibu</h1>
                </div>
                <div class="column is-8">
                    <VField>
                        <VControl icon="">
                            <VInput type="text" v-model="input.penyakitibu" placeholder="" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <h1 style="font-weight: bold;">Penyakit kehamilan</h1>
                </div>
                <div class="column is-8">
                    <VField>
                        <VControl icon="">
                            <VInput type="text" v-model="input.penyakitkehamilan" placeholder="" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <h1 style="font-weight: bold;">Melakukan perawatan</h1>
                </div>
                <div class="column is-2">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.tidakperawatan" true-value="tidak"
                            label="tidak" color="primary"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.yaperawatan" true-value="ya"
                            label="ya" color="primary"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4"></div>
                <div class="column is-4">
                    <h1 style="font-weight: bold;">Puting susu</h1>
                </div>
                <div class="column is-8">
                    <VField>
                        <VControl icon="">
                            <VInput type="text" v-model="input.putingsusu" placeholder="" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight: bold;">Kelainan payudara</h1>
                </div>
                <div class="column is-2">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.tidakpayudara" true-value="tidak"
                            label="tidak" color="primary"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.yapayudara" true-value="ada, usaha perbaikan"
                            label="ada, usaha perbaikan" color="primary"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField>
                        <VControl icon="">
                            <VInput type="text" v-model="input.ketyapayudara" placeholder="" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <h1 style="font-weight: bold;">Obat selama hamil</h1>
                </div>
                <div class="column is-8">
                    <VField>
                        <VControl icon="">
                            <VInput type="text" v-model="input.obathamil" placeholder="" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <h1 style="font-weight: bold;">Jamu selama hamil</h1>
                </div>
                <div class="column is-8">
                    <VField>
                        <VControl icon="">
                            <VInput type="text" v-model="input.jamuhamil" placeholder="" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <h1 style="font-weight: bold;">Obat untuk kelancaran menyusui</h1>
                </div>
                <div class="column is-8">
                    <VField>
                        <VControl icon="">
                            <VInput type="text" v-model="input.obatmenyusui" placeholder="" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <h1 style="font-weight: bold;">Jamu untuk kelancaran menyusui</h1>
                </div>
                <div class="column is-8">
                    <VField>
                        <VControl icon="">
                            <VInput type="text" v-model="input.jamumenyusui" placeholder="" />
                        </VControl>
                    </VField>
                </div>
              </div>
            </div>
            <div class="column is-6">
              <div class="columns is-multiline">
                <div class="column is-4">
                    <h1 style="font-weight: bold;">Tempat Persalinan</h1>
                </div>
                <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.RS" true-value="RS"
                            label="RS" color="primary"/>
                        </VControl>
                    </VField>
                    <VField>
                        <VControl icon="">
                            <VInput type="text" v-model="input.ketRS" placeholder="" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.RB" true-value="RB"
                            label="RB" color="primary"/>
                        </VControl>
                    </VField>
                    <VField>
                        <VControl icon="">
                            <VInput type="text" v-model="input.ketRB" placeholder="" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight: bold;">Cara Lahir</h1>
                </div>
                <div class="column is-2">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.spontan" true-value="Spontan"
                            label="Spontan" color="primary"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.sungsang" true-value="Sungsang"
                            label="Sungsang" color="primary"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.ekstrasivakum" true-value="Ekstrasi vakum"
                            label="Ekstrasi vakum" color="primary"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4"></div>
                <div class="column is-2">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.forceps" true-value="Forceps"
                            label="Forceps" color="primary"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.SC" true-value="SC"
                            label="SC" color="primary"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.lainnyacaralahir" true-value="Lainnya"
                            label="Lainnya" color="primary"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <h1 style="font-weight: bold;">Keadaan Bayi</h1>
                </div>
                <div class="column is-8">
                    <VField>
                        <VControl icon="">
                            <VInput type="text" v-model="input.keadaanbayi" placeholder="" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <h1 style="font-weight: bold;">Skor APGAR</h1>
                </div>
                <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.tidaktahu" true-value="tidak tahu"
                            label="tidak tahu" color="primary"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.tahu" true-value="tahu"
                            label="tahu" color="primary"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4"></div>
                <div class="column is-2">
                    <h1 style="font-weight: bold;">1 menit</h1>
                </div>
                <div class="column is-2">
                    <VField>
                        <VControl icon="">
                            <VInput type="text" v-model="input.satumenit" placeholder="" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2">
                    <h1 style="font-weight: bold;">5 menit</h1>
                </div>
                <div class="column is-2">
                    <VField>
                        <VControl icon="">
                            <VInput type="text" v-model="input.limamenit" placeholder="" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <h1 style="font-weight: bold;">Jumlah Bayi</h1>
                </div>
                <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.tunggal" true-value="Tunggal"
                            label="Tunggal" color="primary"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kembar" true-value="Kembar"
                            label="Kembar" color="primary"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <h1 style="font-weight: bold;">Berat Lahir</h1>
                </div>
                <div class="column is-8">
                  <VField addons>
                    <VControl expanded>
                      <VInput type="number" class="input" placeholder="" v-model="input.beratLahir" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>gram</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                    <h1 style="font-weight: bold;">Panjang Lahir</h1>
                </div>
                <div class="column is-8">
                  <VField addons>
                    <VControl expanded>
                      <VInput type="number" class="input" placeholder="" v-model="input.panjangLahir" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>cm</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                    <h1 style="font-weight: bold;">Kelainan Bawaan</h1>
                </div>
                <div class="column is-8">
                    <VField>
                        <VControl icon="">
                            <VInput type="text" v-model="input.kelainanbawaan" placeholder="" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight: bold;">IMD</h1>
                </div>
                <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.yaimd" true-value="Ya"
                            label="Ya" color="primary"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.tidakimd" true-value="Tidak, alasan"
                            label="Tidak, alasan" color="primary"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight: bold;">Rawat Gabung</h1>
                </div>
                <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.yarb" true-value="Ya"
                            label="Ya" color="primary"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.tidakrb" true-value="Tidak, alasan"
                            label="Tidak, alasan" color="primary"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <h1 style="font-weight: bold;">Mulai Menyusui</h1>
                </div>
                <div class="column is-8">
                  <VField addons>
                    <VControl expanded>
                      <VInput type="number" class="input" placeholder="" v-model="input.jammenyusui" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>jam</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight: bold;">Minuman bayi selain ASI</h1>
                </div>
                <div class="column is-3">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.sufor" true-value="Susu formula"
                            label="Susu formula" color="primary"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.airgula" true-value="Air Gula"
                            label="Air Gula" color="primary"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4"></div>
                <div class="column is-3">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.airputih" true-value="Air Putih"
                            label="Air Putih" color="primary"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.lainnyacaramenyusui" true-value="Lainnya"
                            label="Lainnya" color="primary"/>
                        </VControl>
                    </VField>
                    <VField>
                        <VControl>
                            <VInput type="text" v-model="input.ketlainnyacaramenyusui" placeholder="" />
                        </VControl>
                    </VField>
                </div>
              </div>
            </div>
          </div>
        </div>

        <hr class="mt-5">
        
        <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12 pb-0">
                <h1 class="bold" style="font-size:larger;">
                  PEMERIKSAAN FISIK 
                </h1>
              </div>
              <div class="column is-6">
                <div class="columns is-multiline">
                  <div class="column is-12">
                    <h1 style="font-weight: bold;">IBU</h1>
                  </div>
                  <div class="column is-4">
                      <h1 style="font-weight: bold;">Keadaan Umum</h1>
                  </div>
                  <div class="column is-8">
                      <VField>
                          <VControl icon="">
                              <VInput type="text" v-model="input.keadaanumumibu" placeholder="" />
                          </VControl>
                      </VField>
                  </div>
                  <div class="column is-4">
                      <h1 style="font-weight: bold;">Berat</h1>
                  </div>
                  <div class="column is-3">
                    <VField addons>
                      <VControl expanded>
                        <VInput type="number" class="input" placeholder="" v-model="input.beratibu" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>kg</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                      <h1 style="font-weight: bold;">Tinggi</h1>
                  </div>
                  <div class="column is-3">
                    <VField addons>
                      <VControl expanded>
                        <VInput type="number" class="input" placeholder="" v-model="input.tinggiibu" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>cm</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                      <h1 style="font-weight: bold;">Psikis Kesan</h1>
                  </div>
                  <div class="column is-8">
                      <VField>
                          <VControl icon="">
                              <VInput type="text" v-model="input.psikiskesan" placeholder="" />
                          </VControl>
                      </VField>
                  </div>
                  <div class="column is-4">
                      <h1 style="font-weight: bold;">Keadaan Payudara</h1>
                  </div>
                  <div class="column is-8">
                      <VField>
                          <VControl icon="">
                              <VInput type="text" v-model="input.keadaanpayudara" placeholder="" />
                          </VControl>
                      </VField>
                  </div>
                  <div class="column is-4">
                      <h1 style="font-weight: bold;">Kelainan Payudara</h1>
                  </div>
                  <div class="column is-8">
                      <VField>
                          <VControl icon="">
                              <VInput type="text" v-model="input.kelainanpayudara" placeholder="" />
                          </VControl>
                      </VField>
                  </div>
                  <div class="column is-4">
                      <h1 style="font-weight: bold;">Kelainan Menyusui</h1>
                  </div>
                  <div class="column is-2">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.tidakadakelainanmenyusui" true-value="tidak ada"
                            label="tidak ada" color="primary"/>
                        </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.adakelainanmenyusui" true-value="ada, sebab"
                            label="ada, sebab" color="primary"/>
                        </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                      <VField>
                          <VControl icon="">
                              <VInput type="text" v-model="input.ketkelainanmenyusui" placeholder="" />
                          </VControl>
                      </VField>
                  </div>
                </div>
              </div>
              <div class="column is-6">
                <div class="columns is-multiline">
                  <div class="column is-12">
                    <h1 style="font-weight: bold;">ANAK</h1>
                  </div>
                  <div class="column is-4">
                      <h1 style="font-weight: bold;">Jenis Kelamin</h1>
                  </div>
                  <div class="column is-8">
                      <VField class="is-autocomplete-select" v-slot="{ id }">
                          <VControl>
                              <Multiselect v-model="input.jkanak" :attrs="{ value }" placeholder="" label="label"
                              :options="d_jeniskelamin" :searchable="true" track-by="label" mode="single" autocomplete="off">
                              </Multiselect>
                          </VControl>
                      </VField>
                      <VField>
                          <VControl icon="">
                              <VInput type="text" v-model="input.keadaanumumibu" placeholder="" />
                          </VControl>
                      </VField>
                  </div>
                  <div class="column is-4">
                      <h1 style="font-weight: bold;">Umur</h1>
                  </div>
                  <div class="column is-4">
                    <VField addons>
                      <VControl expanded>
                        <VInput type="number" class="input" placeholder="" v-model="input.bulananak" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>bulan</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField addons>
                      <VControl expanded>
                        <VInput type="number" class="input" placeholder="" v-model="input.harianak" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>hari</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                      <h1 style="font-weight: bold;">Keadaan Umum</h1>
                  </div>
                  <div class="column is-8">
                      <VField>
                          <VControl icon="">
                              <VInput type="text" v-model="input.keadaanumumanak" placeholder="" />
                          </VControl>
                      </VField>
                  </div>
                  <div class="column is-4">
                      <h1 style="font-weight: bold;">Refleks menghisap</h1>
                  </div>
                  <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.baikmenghisap" true-value="baik"
                            label="baik" color="primary"/>
                        </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.tidakmenghisap" true-value="tidak"
                            label="tidak" color="primary"/>
                        </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                      <h1 style="font-weight: bold;">Refleks menelan</h1>
                  </div>
                  <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.baikmenelan" true-value="baik"
                            label="baik" color="primary"/>
                        </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.tidakmenelan" true-value="tidak"
                            label="tidak" color="primary"/>
                        </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                      <h1 style="font-weight: bold;">Kelainan</h1>
                  </div>
                  <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.stomatitis" true-value="stomatitis"
                            label="stomatitis" color="primary"/>
                        </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.labio" true-value="stomatitis"
                            label="Labio/gnato/palatoskizis" color="primary"/>
                        </VControl>
                    </VField>
                  </div>
                  <div class="column is-4"></div>
                  <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.DS" true-value="sindrom down"
                            label="sindrom down" color="primary"/>
                        </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.ikterus" true-value="ikterus"
                            label="ikterus" color="primary"/>
                        </VControl>
                    </VField>
                  </div>
                  <div class="column is-4"></div>
                  <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.congenital" true-value="congenital"
                            label="Kelainan congenital lain" color="primary"/>
                        </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.muntah" true-value="muntah"
                            label="muntah" color="primary"/>
                        </VControl>
                    </VField>
                  </div>
                  <div class="column is-4"></div>
                  <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.panas" true-value="panas"
                            label="panas" color="primary"/>
                        </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.diare" true-value="diare"
                            label="diare" color="primary"/>
                        </VControl>
                    </VField>
                  </div>
                  <div class="column is-4"></div>
                  <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.tonguetie" true-value="tongue tie"
                            label="tongue tie" color="primary"/>
                        </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.tonguetie" true-value="BBLR"
                            label="BBLR" color="primary"/>
                        </VControl>
                    </VField>
                  </div>
                  <div class="column is-4"></div>
                  <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.lainkelainananak" true-value="Lainnya"
                            label="Lainnya" color="primary"/>
                        </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                      <VField>
                          <VControl icon="">
                              <VInput type="text" v-model="input.ketlainkelainananak" placeholder="" />
                          </VControl>
                      </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
  
          <hr class="mt-5">

          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-6">
                <div class="columns is-multiline">
                  <div class="column is-12 pb-0">
                    <h1 class="bold" style="font-size:larger;">
                      OBSERVASI TEKNIK MENYUSUI 
                    </h1>
                  </div>
                  <div class="column is-4">
                      <h1 style="font-weight: bold;">Teknik Menyusui</h1>
                  </div>
                  <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.baikmenyusui" true-value="Baik"
                            label="Baik" color="primary"/>
                        </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kurangmenyusui" true-value="Kurang"
                            label="Kurang" color="primary"/>
                        </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                      <h1 style="font-weight: bold;">Catatan (jelaskan kekurangannya)</h1>
                  </div>
                  <div class="column is-8">
                      <VField>
                          <VControl icon="">
                              <VInput type="text" v-model="input.jelaskankekurangannya" placeholder="" />
                          </VControl>
                      </VField>
                  </div>
                </div>
              </div>
              <div class="column is-6">
                <div class="columns is-multiline">
                  <div class="column is-12 pb-0">
                    <h1 class="bold" style="font-size:larger;">
                      RENCANA TINDAK LANJUT 
                    </h1>
                  </div>
                  <div class="column is-12">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.bimbinganlangsung" true-value="Bimbingan Langsung"
                            label="Bimbingan Langsung" color="primary"/>
                        </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.dirujuklangsung" true-value="Dirujuk Langsung ke"
                            label="Dirujuk Langsung ke" color="primary"/>
                        </VControl>
                    </VField>
                  </div>
                  <div class="column is-8">
                      <VField>
                          <VControl icon="">
                              <VInput type="text" v-model="input.ketdirujuklangsung" placeholder="" />
                          </VControl>
                      </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.pengobatan" true-value="Pengobatan"
                            label="Pengobatan" color="primary"/>
                        </VControl>
                    </VField>
                  </div>
                  <div class="column is-8">
                      <VField>
                          <VControl icon="">
                              <VInput type="text" v-model="input.ketpengobatan" placeholder="" />
                          </VControl>
                      </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                        <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kunjunganulang" true-value="Kunjungan ulang, tanggal"
                            label="Kunjungan ulang, tanggal" color="primary"/>
                        </VControl>
                    </VField>
                  </div>
                  <div class="column is-8">
                      <VField>
                          <VControl icon="">
                              <VInput type="text" v-model="input.ketkunjunganulang" placeholder="" />
                          </VControl>
                      </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
              
          <hr class="mt-5">

          <div>
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-12">
                <div style="" class="mt-1">
                  <table class="tabels" border="1" style="width: 100%;">
                      <thead>
                          <tr>
                              <th width="20%" style="vertical-align: inherit;text-align:center">
                                  Tanggal</th>
                              <th width="35%" style="vertical-align: inherit;text-align:center">
                                  Keluhan Ibu Hamil / Menyusui <br> Keadaan Payudara</th>
                              <th width="35%" style="vertical-align: inherit;text-align:center">
                                  Penanggulangan</th>
                              <th style="vertical-align: inherit;text-align:center;" width="10%">
                                  #
                              </th>
                          </tr>
                      </thead>
                      <tbody v-for="(input, index) in details1" :key="index">
                          <tr>
                              <td class="td-po">
                                  <div class="pb-0">
                                    <VField>
                                      <VDatePicker v-model="input.tanggalkeluhan" mode="date" trim-weeks :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }">
                                          <VField>
                                            <VControl icon="feather:calendar" fullwidth>
                                              <VInput :value="inputValue" placeholder="" v-on="inputEvents" class="is-rounded" />
                                            </VControl>
                                          </VField>
                                        </template>
                                      </VDatePicker>
                                    </VField>
                                  </div>
                              </td>
                              <td class="td-po">
                                  <div class="pb-0">
                                      <VField>
                                          <VControl icon="feather:bookmark">
                                              <VInput type="text" v-model="input.keluhanhamilmenyusui"
                                                  placeholder="" />
                                          </VControl>
                                      </VField>
                                  </div>
                              </td>
                              <td class="td-po">
                                  <div class="pb-0">
                                      <VField>
                                          <VControl icon="feather:bookmark">
                                              <VInput type="text" v-model="input.penanggulangankeluhan"
                                                  placeholder="" />
                                          </VControl>
                                      </VField>
                                  </div>
                              </td>
                              <td class="td-rpo" style="vertical-align: inherit">
                                  <VButtons style="justify-content:space-around">
                                      <VIconButton type="button" raised circle icon="feather:plus"
                                          @click="addNewItem1()" color="info" v-tooltip.bubble="'Tambah '">
                                      </VIconButton>
                                      <VIconButton class="mt-1" v-if="index > 0" type="button" raised
                                          circle icon="feather:trash" @click="removeItem1(index)"
                                          color="danger">
                                      </VIconButton>
                                  </VButtons>
                              </td>
                              
                          </tr>
                      </tbody>
                  </table>
                </div>
                </div>
              </div>
            </div>
          </div>
  
          <!-- form baru -->
  
        </div>
      </div>
    </div>
    <VModal :open="showModalObatAlergi" title="List Obat" :noclose="true" size="large" actions="right"
        @close="showModalObatAlergi = false">
        <template #content>
            <DataTable :pt="{
                table: { style: 'min-width: 50rem; min-height: 10rem;' },
                column: {
                    bodycell: ({ state }) => ({
                        class: [{ 'pt-0 pb-0': state['d_editing'] }]
                    })
                }
            }" v-model:selection="ObatSelectedAlergi" :value="listObat" v-model:filters="cariObat" :metaKeySelection="metaKey" :rows="10" paginator
                tableStyle="min-width: 50rem" dataKey="id" :totalRecords="listObat.length" responsiveLayout="stack"
                breakpoint="960px">
                <template #header>
                    <div class="column is-12 p-1">
                        <InputText v-model="cariObat['global'].value" placeholder="Cari obat.."/>
                    </div>
                </template>
                <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                <Column field="namaproduk" header="Nama Obat">
                    <template #body="slotProps">
                        <span>{{ slotProps.data.namaproduk }}</span>
                    </template>
                </Column>
            </DataTable>
        </template>
        <template #action>
            <VButton type="button" color="primary" raised @click="addToInputAlergi()">
                Tambah
            </VButton>
        </template>
    </VModal>
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
                    <td class="tg-0lax text-center" width="5%">#</td>
                    <td class="tg-0lax text-center" width="25%">Tanggal Input</td>
                    <td class="tg-0lax text-center" width="25%">Tanggal Registrasi</td>
                    <td class="tg-0lax text-center" width="25%">No Registrasi</td>
                    <td class="tg-0lax text-center" width="20%">No EMR</td>
                    <td class="tg-0lax text-center" width="20%">Dokter</td>
                    <td class="tg-0lax text-center" width="20%">Penyakit</td>
                    <td class="tg-0lax text-center" width="20%">Section</td>
                  </tr>
                </thead>
                <tbody v-for="resep in listTemplate">
                  <tr>
                    <td style="width:5%;text-align:center">
                      <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)" color="info"
                        v-tooltip-prime.top="'Pilih'">
                      </VIconButton>
                    </td>
                    <td style="width:25%;text-align:center">
                      <span class="mb-2">{{ resep.created_at }}</span><br>
                    </td>
                    <td style="width:25%;text-align:center">
                      <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                    </td>
                    <td style="width:25%;text-align:center">
                      <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                    </td>
                    <td style="width:20%;text-align:center">
                      <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                    </td>
                    <td style="width:20%;text-align:center">
                      <span class="mb-2">{{ resep.dpjpUtama }}</span><br>
                    </td>
                    <td style="width:20%;text-align:center">
                      <span class="mb-2">{{ resep.riwayatpenyakit }}</span><br>
                    </td>
                    <td style="width:25%;text-align:center">
                      <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
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
      @close="isAlltemplate = false; showModalTemplateFix = false">
      <template #content>
        <DataTable
            :pt="{
                table: { style: 'min-width: 50rem; min-height: 10rem;' },
                column: {
                    bodycell: ({ state }) => ({
                        class: [{ 'pt-0 pb-0': state['d_editing'] }]
                    })
                }
            }"
            v-model:filters="filtersTemplate"
            :value="listTemplateFix"
            :metaKeySelection="false"
            :rows="8"
            :loading="isLoading"
            paginator
            tableStyle="min-width: 50rem"
            dataKey="no"
            :totalRecords="listTemplateFix.length"
            :globalFilterFields="['namatemplate', 'registrasi.namaruangan']"
            responsiveLayout="stack" breakpoint="960px"
            >
            <template #header>
                <div class="columns is-multiline">
                    <div class="column is-8">
                        <VField>
                            <InputText v-model="filtersTemplate['global'].value" placeholder="Search Nama Template" />
                        </VField>
                    </div>
                    <div class="column is-4">
                      <VField>
                          <VControl>
                              <VSwitchBlock v-model="isAlltemplate" color="success" label="Semua Template" />
                          </VControl>
                      </VField>
                    </div>
                </div>
            </template>
            <template #empty> No customers found. </template>
            <template #loading>
                <img src="/images/other/loadingspin.gif" alt="Loading..." width="100"/>
                <p style="color:white">Loading data, please wait...</p>
            </template>
            <Column headerStyle="width: 8rem">
                <template #body="slotProps">
                  <VButtons>
                    <VIconButton color="danger" light raised circle icon="lucide:x" @click="deleteTemplate(slotProps.data.id)" 
                    v-if="!isAlltemplate" v-tooltip-prime.top="'Hapus'"/>
                    <VIconButton type="button" raised circle icon="fas fa-plus"
                        @click="addTemplate(slotProps.data)" color="info" v-tooltip-prime.top="'Pilih'">
                    </VIconButton>
                  </VButtons>
                </template>
            </Column>
            <Column field="namatemplate" header="Nama" :sortable="true"></Column>
            <Column field="registrasi.namaruangan" header="Nama Ruangan" :sortable="true">
                <template #body="slotProps">
                    {{ slotProps.data.registrasi.namaruangan }}
                </template>
            </Column>
            <Column field="created_at" header="Tanggal" :sortable="true">
                <template #body="slotProps">
                    <span>{{ H.formatDateToLocalString(slotProps.data.created_at) }}</span>
                </template>
            </Column>
        </DataTable>
      </template>
    </VModal>
  
    <VModal :open="showModalObat" title="Riwayat Obat" :noclose="true" size="large" actions="right"
      @close="showModalObat = false">
      <template #content>
        <DataTable :pt="{
          table: { style: 'min-width: 50rem; min-height: 10rem;' },
          column: {
            bodycell: ({ state }) => ({
              class: [{ 'pt-0 pb-0': state['d_editing'] }]
            })
          }
        }" v-model:selection="ObatSelected" :value="listSIMRSLama" :metaKeySelection="metaKey" :rows="10" paginator
          tableStyle="min-width: 50rem" dataKey="no" :totalRecords="listSIMRSLama.length" responsiveLayout="stack"
          breakpoint="960px">
          <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
          <Column field="namaobat" header="Nama" :sortable="true">
            <template #body="slotProps">
              <span>{{ slotProps.data.namaobat + ' - ' + slotProps.data.jenisobat }}</span>
            </template>
          </Column>
          <Column field="noorder" header="No Resep" :sortable="true"></Column>
          <Column field="noregistrasi" header="No Registrasi" :sortable="true"></Column>
          <Column field="namalengkap" header="Dokter" :sortable="true" style="width: 150px;;"></Column>
          <Column field="tglorder" header="Tanggal" :sortable="true">
            <template #body="slotProps">
              <span>{{ H.formatDateToLocalString(slotProps.data.tglorder) }}</span>
            </template>
          </Column>
        </DataTable>
      </template>
      <template #action>
        <VButton type="button" color="primary" raised @click="addToInput()">
          Tambah
        </VButton>
      </template>
    </VModal>
  
  
  </template>
  
  <script setup lang="ts">
  import { useWindowScroll } from '@vueuse/core'
  import { useApi } from '/@src/composable/useApi'
  import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
  import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
  import { useHead } from '@vueuse/head'
  import { useViewWrapper } from '/@src/stores/viewWrapper'
  import { useUserSession } from '/@src/stores/userSession'
  import ButtonEmr from '../page-emr-plugins/button-emr.vue'
  import * as H from '/@src/utils/appHelper'
  import AutoComplete from 'primevue/autocomplete';
  import Fieldset from 'primevue/fieldset';
  import * as EMR from '../page-emr-plugins/asesmen-awal-keper-rj'
  import ConfirmDialog from 'primevue/confirmdialog'
  import { useConfirm } from "primevue/useconfirm"
  import moment from 'moment'
  import { useToaster } from '/@src/composable/toaster'
  import DataTable from 'primevue/datatable';
  import Column from 'primevue/column'
  import { FilterMatchMode } from 'primevue/api';
  import InputText from 'primevue/inputtext';
  
  
  useHead({
    title: 'Asesmen Awal - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
  let ID_PASIEN = useRoute().query.nocmfk as string
  let NOREC_PD = useRoute().query.norec_pd as string
  let norec_emr = useRoute().query.norec_emr as string
  
  let detailSkriningNutrisi = ref(EMR.detailSkriningNutrisi())
  let detailStatusFungsional = ref(EMR.detailStatusFungsional())
  let statusFungsional: any = ref(EMR.statusFungsional())
  
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
  
  const filtersTemplate = ref({
      global: { value: null, matchMode: FilterMatchMode.CONTAINS }
  });
  const cariObat = ref({
      global: { value: null, matchMode: FilterMatchMode.CONTAINS }
  });
  const route = useRoute()
  const pasien: any = ref({})
  const d_pegawai: any = ref([])
  const metaKey = ref(true);
  const loadData: any = ref(true)
  const listSIMRSLama: any = ref([])
  const showModalObat: any = ref(false);
  const ObatSelected: any = ref()
  const isAlltemplate: any = ref(false);
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
    airway: [],
    disability: []
  
  })
  
  const COLLECTION: any = ref('AsesmenAwalKeperawatanPasienRawatJalan') //table mongodb
  
  const NOREC_EMRPASIEN: any = ref('')
  const input: any = ref({
    kebjamKedatangan: new Date(),
    kebjamAsesmenAwal: new Date(),
    kebtanggalKedatangan: new Date(),
    nilaiSkrining: 0,
    penurunanbb: 0,
    penurunannafsu: 0,
    penurunanbbYa: 0,
    nilai: "RISIKO RENDAH (MST 0-1)",
    perawat: '',
  
    mengontrolbab: 0,
    mengontrolbak: 0,
    bersihdiri: 0,
    toilet: 0,
    makan: 0,
    berpindahtt: 0,
    mobilisasi: 0,
    berpakaian: 0,
    tangga: 0,
    mandi: 0,
    nilaimandi: 0,
    CBKetergantunganTotal: "Ketergantungan total (0-4)",
  
    hasiljatuh: 'tidak berisiko',
    caraduduk: null,
    kursi: null,

    
    
  })
  const { y } = useWindowScroll()
  const isStuck = computed(() => {
    return y.value > 30
  })
  const details = [{
        no: 1,
    }]
  const details1 = [{
        no: 1,
    }]
  const isLoading = ref(false)
  const isAktive = ref()
  const d_allo: any = ref([{ value: 1, label: 'Suami/Istri' }, { value: 2, label: 'Orang Tua' }, { value: 3, label: 'Anak' }, { value: 4, label: 'Pasien' }, { value: 5, label: 'Lainnya' }])
  const d_mata: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
  const d_jeniskelamin: any = ref([{ value: 1, label: 'Laki-Laki' }, { value: 2, label: 'Perempuan' }])
  const d_agama: any = ref([{ value: 1, label: 'BUDHA' }, { value: 2, label: 'HINDU' }, { value: 3, label: 'ISLAM' }, { value: 4, label: 'KONGHUCU' }, { value: 5, label: 'KRISTEN' }, { value: 6, label: 'KATHOLIK' }, { value: 7, label: 'LAIN-LAIN' }])
  const d_gcse: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }])
  const d_gcsv: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }])
  const d_gcsm: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }, { value: 6, label: '6' }])
  const d_keadaanumum: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Sedang' }, { value: 3, label: 'Buruk' }])
  const d_freknyeri: any = ref([{ value: 1, label: 'Jarang' }, { value: 2, label: 'Hilang Timbul' }, { value: 3, label: 'Terus Menerus' }])
  const d_kualitasnyeri: any = ref([{ value: 1, label: 'Tumpul' }, { value: 2, label: 'Tajam' }, { value: 3, label: 'Panas/Terbakar' }, { value: 4, label: 'Lain-lain' }])
  const d_gangguanpsikologis: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Gelisah' }, { value: 3, label: 'Takut' }, { value: 4, label: 'Sedih' }, { value: 5, label: 'Rendah diri' }, { value: 6, label: 'Acuh tak acuh' }, { value: 7, label: 'Mudah tersinggung' }, { value: 8, label: 'Menarik diri' }])
  const d_masalahkawin: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Ada' }])
  const d_kekerasan: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Ada' }])
  const d_pembiayaankesehatan: any = ref([{ value: 1, label: 'Biaya sendiri/keluarga' }, { value: 2, label: 'Asuransi Lainnya' }])
  const d_rohaniawan: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
  const d_penurunanbb: any = ref([{ value: 1, label: 'Tidak' }, { value: 2, label: 'Tidak Yakin' }])
  const d_penurunannafsu: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
  const d_penurunanbbYa: any = ref([
    { value: 3, label: '1-5 kg' },
    { value: 4, label: '6-10 kg' },
    { value: 5, label: '11-15 kg' },
    { value: 6, label: '>15 kg' }
  ])
  const d_diagnosakhusus: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
  const d_mengontrolbab: any = ref([{ value: 1, label: 'Inkontinen/tidak teratur (perlu enema)' }, { value: 2, label: 'Kadang inkontinen (1xseminggu)' }, { value: 3, label: 'Kontinen teratur' }])
  const d_mengontrolbak: any = ref([{ value: 1, label: 'Inkontinen/pakai kateter dan tidak terkontrol' }, { value: 2, label: 'Kadang inkontinen (max 1x24 jam)' }, { value: 3, label: 'Mandiri' }])
  const d_bersihdiri: any = ref([{ value: 1, label: 'Butuh pertolongan orang lain' }, { value: 2, label: 'Mandiri' }])
  const d_toilet: any = ref([{ value: 1, label: 'Tergantung pertolongan orang lain' }, { value: 2, label: 'Perlu pertolongan pada beberapa aktivitas terapi dan dapat mengerjakan sendiri beberapa aktivitas lain' }, { value: 3, label: 'Mandiri' }])
  const d_makan: any = ref([{ value: 1, label: 'Tidak mampu' }, { value: 2, label: 'Perlu seseorang menolong memotong makanan' }, { value: 3, label: 'Mandiri' }])
  const d_berpindahtt: any = ref([{ value: 1, label: 'Tidak Mampu' }, { value: 2, label: 'Perlu banyak bantuan untuk bisa duduk (2 orang)' }, { value: 3, label: 'Bantuan 1 orang' }, { value: 4, label: 'Mandiri' }])
  const d_mobilisasi: any = ref([{ value: 1, label: 'Tidak Mampu' }, { value: 2, label: 'Dengan kursi roda' }, { value: 3, label: 'Bantuan 1 orang' }, { value: 4, label: 'Mandiri' }])
  const d_berpakaian: any = ref([{ value: 1, label: 'Tergantung orang lain' }, { value: 2, label: 'Sebagian dibantu (misal mengancing baju)' }, { value: 3, label: 'Mandiri' }])
  const d_tangga: any = ref([{ value: 1, label: 'Tidak Mampu' }, { value: 2, label: 'Butuh Pertolongan' }, { value: 3, label: 'Mandiri' }])
  const d_mandi: any = ref([{ value: 1, label: 'Teragantung orang lain' }, { value: 2, label: 'Mandiri' }])
  const d_caraduduk: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
  const d_kursi: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
  const d_tindakan: any = ref([{ value: 1, label: 'Tidak ada tindakan' }, { value: 2, label: 'Edukasi' }, { value: 3, label: 'Pasang penanda resiko jatuh' }])
  const listTemplate: any = ref([])
  const listTemplateFix: any = ref([])
  const showModalTemplate: any = ref(false)
  const showModalTemplateFix: any = ref(false)
  const filterMenu: any = ref('')
  const listObat: any = ref([])
  const showModalObatAlergi: any = ref(false);  
  const ObatSelectedAlergi: any = ref()

  
  const confirm = useConfirm();
  const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
  let listHipertensi: any = ref(EMR.hipertensi())
  let listDiabetes: any = ref(EMR.diabetes())
  let listDyslipidemia: any = ref(EMR.dyslipidemia())
  let listDuaPilihan: any = ref(EMR.duaPilihan())
  let listAgama: any = ref(EMR.agama())
  let listStatus: any = ref(EMR.status())
  let listKeluarga: any = ref(EMR.keluarga())
  let listTempatTinggal: any = ref(EMR.tempatTinggal())
  let listPsikologis: any = ref(EMR.psikologis())
  let listMore: any = ref(EMR.more())
  let listImageNyeri: any = ref(EMR.imgNyeri())
  let listSkoringNyeri: any = ref(EMR.skoringNyeri())
  let resikoNutrisional: any = ref(EMR.resikoNutrisional())
  let fungsionalPertama: any = ref(EMR.fungsionalPertama())
  let listRangeNilaiPoin: any = ref(EMR.nilaiPoin())
  let listDESCNilai: any = ref(EMR.descNilai())
  let pertanyaanA: any = ref(EMR.pertanyaanA())
  let pertanyaanB: any = ref(EMR.pertanyaanB())
  let pertanyaanC: any = ref(EMR.pertanyaanC())
  let dropdownAllo: any = ref([
    "Suami/Istri",
    "Orang tua",
    "Anak",
    "Lainnya"
  ])
  watch([() => input.value.caraduduk, () => input.value.kursi], ([caraduduk, kursi]) => {
    if (caraduduk == null && kursi == null) {
      input.value.hasiljatuh = "tidak berisiko";
    } else if (caraduduk == 2 && kursi == 2) {
      input.value.hasiljatuh = "tidak berisiko";
    } else if (caraduduk == 1 && kursi == 1) {
      input.value.hasiljatuh = "risiko tinggi";
    } else if (caraduduk != null || kursi != null) {
      input.value.hasiljatuh = "risiko sedang";
    }
  });
  const loadRiwayat = async () => {
    isLoading.value = true
    let responsex = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    isLoading.value = false
    if (responsex.length) {
      input.value = responsex[0] //set ke inputan
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = responsex[0].emrpasienfk
      }
    } else {
      isLoading.value = true
      const responseTglRuangan = await useApi().get(`/emr/get-emr-tgl-terakhir-dengan-ruangan?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`)
      const responseHistori = await useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`)
      isLoading.value = false
      if (responseTglRuangan.length && responseHistori.length) {
        console.log("Ruangan dulu : " + responseTglRuangan[0].registrasi.namaruangan)
        var tgl_EMR_terakhir = moment(responseTglRuangan[0].created_at).format("DD-MM-YYYY");
        var convertTgl = moment(tgl_EMR_terakhir, 'DD-MM-YYYY');
        var tgl_Sekarang = moment();
        const calculateDays = tgl_Sekarang.diff(convertTgl, 'days');
        console.log("Tanggal EMR terakhir : " + convertTgl);
        console.log("Tanggal Sekarang : " + tgl_Sekarang);
        console.log("Total hari : " + calculateDays)
        if (responseTglRuangan[0].registrasi.namaruangan.trim() == H.setObjectRegistrasi(pasien.value.registrasi).namaruangan.trim() && calculateDays < 90) {
          confirm.require({
            message: 'Asesmen Keperawatan sudah pernah diinput ' + calculateDays + ' hari dari tanggal registrasi pasien ini. Apakah anda ingin melihat riwayat terakhirnya ?',
            group: 'templating',
            header: 'Informasi Asesmen Keperawatan',
            icon: 'pi pi-exclamation-circle',
            accept: () => {
              if (responseHistori.length) {
                input.value = responseHistori[0] //set ke inputan
                input.value.namatemplate = ''
                // console.log(input.value)
              } else {
                H.alert('warning', 'Data tidak ada')
              }
            },
            reject: () => { }
          })
        }
      } else {
        console.log('Data EMR sebelumnya tidak ada!')
      }
      console.log("Ruangan pasien sekarang : " + H.setObjectRegistrasi(pasien.value.registrasi).namaruangan)
    }
  }

  const addNewItem = () => {
      input.value.details.push({
          no: input.value.details[input.value.details.length - 1].no + 1,
      });
  }

  const removeItem = (index: any) => {
      input.value.details.splice(index, 1)
  }

  const addNewItem1 = () => {
      input.value.details1.push({
          no: input.value.details1[input.value.details1.length - 1].no + 1,
      });
  }

  const removeItem1 = (index: any) => {
      input.value.details1.splice(index, 1)
  }
  
  const simpan = () => {
  
    let ID = input.value.id ? input.value.id : ''
  
    let object: any = {}
  
    // if (!input.value.perawat) {
    //   useToaster().warn('Perawat Harus di isi !')
    //   return
    // }
    if (input.value.kebrujukan == 'TIDAK') {
      if (input.value.kebrujuklanjutan == 'DIANTAR') {
        input.value.kebketrujukan = input.value.kebketrujukan;
      }
    }
  
    if (input.value.kebpilihanallo == 'Lainnya') {
      input.value.kebpilihanallo = input.value.keballoanamnesis;
    }
  
    if (input.value.kualitasnyeri == 'LAINNYA') {
      input.value.kualitasnyeri = input.value.kualitasnyerilain
    }
  
    if (input.value.pembiayaankesehatan == 'ASURANSI') {
      input.value.pembiayaankesehatan = input.value.ketpembiayaankesehatan
    }
  
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
    console.log(json)
  
    isLoading.value = true
    useApi().post(
      `/emr/simpan-emr`, json).then((response: any) => {
        isLoading.value = false
        // NOREC_EMRPASIEN.value = response.norec_emr
      }).catch((e: any) => {
        isLoading.value = false
      })
  
    // console.log(resultValue)
  }

  const inputObatAlergi = async (filter: any) => {
    listObat.value = []
    let nomor = 0;
    isLoading.value = true
    let response = await useApi().get(`emr/get-master-obat`)
    isLoading.value = false
    if (response.length > 0) {
        listObat.value = response
        showModalObatAlergi.value = true;
    } else {
        H.alert('warning', 'Data Obat Tidak Ada!')
    }
}
  
  const inputObat = async () => {
  
    if (listSIMRSLama.value.length > 0) {
      ObatSelected.value = [];
      showModalObat.value = true;
    } else {
      listSIMRSLama.value = []
      let lokal = false;
      let riwayat1 = []
      isLoading.value = true
  
      let responseX = await useApi().get(`/farmasi/riwayat-order-resep?norec_pd=${NOREC_PD}`)
  
      isLoading.value = false
      let nomor = 0;
      if (responseX.length > 0) {
        for (let x = 0; x < responseX.length; x++) {
          const element = responseX[x];
          for (let d = 0; d < element.details.length; d++) {
            nomor++;
            const detail = element.details[d];
            riwayat1.push({
              'no': nomor,
              'namalengkap': element.namalengkap,
              'noregistrasi': element.noregistrasi,
              'noorder': element.noorder,
              'tglorder': moment(element.tglorder).format('DD-MM-YYYY'),
              'namaobat': detail.namaproduk,
              'jenisobat': detail.jeniskemasan,
              'simslama': true,
            })
          }
        }
        listSIMRSLama.value = riwayat1
        showModalObat.value = true;
      } else {
        H.alert('warning', 'Pasien belum mempunyai riwayat obat')
      }
    }
    console.log(listSIMRSLama.value)
  
  }

  const addToInputAlergi = (event) => {
    console.log("obat selected", ObatSelectedAlergi)
    let inputss = input.value.riwayatalergi == undefined ? '' : input.value.riwayatalergi;
    if (ObatSelectedAlergi.value.length > 0) {
        ObatSelectedAlergi.value.forEach((obt, ind) => {
            inputss += ` # ${obt.namaproduk} `
        })
    }
    input.value.riwayatalergi = inputss
    showModalObatAlergi.value = false;
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
  
  const pilihTemplateFix = async (index: any) => {
    isLoading.value = true
    useApi().get(
      `/emr/get-emr-template?collection=${COLLECTION.value}&isAll=${isAlltemplate.value}`).then((responselast: any) => {
        isLoading.value = false
        if (responselast.length) {
          for (var x = 0; x < responselast.length; x++) {
            responselast[x].no = x + 1
            // responselast[x].id = ''
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
  
  const addToInput = (event) => {
    console.log("obat selected", ObatSelected)
    let inputss = input.value.riwayatobat == undefined ? '' : input.value.riwayatobat;
    if (ObatSelected.value.length > 0) {
      ObatSelected.value.forEach((obt, ind) => {
        inputss += ` # ${obt.namaobat} `
      })
    }
    input.value.riwayatobat = inputss
    showModalObat.value = false;
  }
  
  
  
  const fetchPasien = () => {
    pasien.value = props.pasien
    pasien.value.registrasi = props.registrasi
    NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
    console.log(norec_emr)
  }
  
  const fetchDokter = async (filter: any) => {
    await useApi().get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=100`
    ).then((response) => {
      d_pegawai.value = response
    })
  }
  
  const skor = (e: any, i: any) => {
  
    let listSkor = listSkoringNyeri.value.detail
  
    listSkor.forEach((element: any) => {
      if (element.descNilai == e.descNilai) {
        input.value.skoringNyeri = e.descNilai
      }
    });
    isAktive.value = i
  
  }
  
  const getDataExist = async () => {
    await useApi().get(`emr/get-data-exist?nocmfk=${ID_PASIEN}`).then((response) => {
  
      if (response != null || response != undefined) {
        input.value.beratbadanObgyn = response.beratBadan
        input.value.tinggibadanObgyn = response.tinggiBadan
        input.value.IMT = response.IMT
        input.value.lingkarPerut = response.lingkarPerut
        input.value.nadiObgyn = response.nadi
        input.value.celciusObgyn = response.suhu
        input.value.tekananDarahObgyn = response.tekananDarah
        input.value.nafasObgyn = response.pernapasan
        input.value.sao2Obgyn = response.SPO2
      }
    })
  }
  
  const deleteTemplate = (idTemplate) => {
    isLoading.value = true
    let json = {
      'id': idTemplate,
      'collection': COLLECTION.value
    }
    useApi().post(
      `/emr/hapus-template`, json).then((response: any) => {
        if(response.status !== 500) {
          isLoading.value = false
          isAlltemplate.value = false;
          H.alert('sucess', response.message);
          pilihTemplateFix();
        }else {
          H.alert('danger', response.message);
        }
      }).catch((e: any) => {
        isLoading.value = false
        H.alert('danger', e);
      })
  }
  
  const addTemplate = (response) => {
    console.log(response);
    input.value = response;
    input.value.namatemplate = null;
    isAlltemplate.value = false;
    showModalTemplateFix.value = false;
    H.alert('success', 'Berhasil ditambahkan');
  };
  
  const handlerRujukanChange = (val: any) => {
    console.log(val);
    if (val === "YA") {
  
    }
  }
  
  const print = async () => {
    H.printBlade(`emr/cetak-asesmen-keper-rj?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  }
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
  
  getDataExist()
  fetchPasien()
  
  
  watch(() => [input.value.penurunanbb, input.value.penurunannafsu, input.value.penurunanbbYa], ([newValuePenurunanBB, newValuePenurunanBBYa, newValuePenurunanNafsu]) => {
    let totalNilaiSkriningKalkulasi
    //? Mencegah value checbox dari undefined
    newValuePenurunanBB = newValuePenurunanBB ?? 0;
    newValuePenurunanBBYa = newValuePenurunanBBYa ?? 0;
    newValuePenurunanNafsu = newValuePenurunanNafsu ?? 0;
  
    //? Calculate total Skrining Nutrisi
    totalNilaiSkriningKalkulasi = newValuePenurunanBB + newValuePenurunanBBYa + newValuePenurunanNafsu
    input.value.nilaiSkrining = totalNilaiSkriningKalkulasi
  
    if (totalNilaiSkriningKalkulasi >= 0 && totalNilaiSkriningKalkulasi <= 1) {
      input.value.nilai = "RISIKO RENDAH (MST 0-1)";
    } else if (totalNilaiSkriningKalkulasi >= 2 && totalNilaiSkriningKalkulasi <= 3) {
      input.value.nilai = "RISIKO SEDANG (MST 2-3)";
    } else if (totalNilaiSkriningKalkulasi >= 4) {
      input.value.nilai = "RISIKO TINGGI (MST 4-5)";
    }
  });
  
  watch(isAlltemplate, (newValue) => {
    pilihTemplateFix()
  })
  
  watch(() => [
    input.value.mengontrolbab,
    input.value.mengontrolbak,
    input.value.bersihdiri,
    input.value.toilet,
    input.value.makan,
    input.value.berpindahtt,
    input.value.mobilisasi,
    input.value.berpakaian,
    input.value.tangga,
    input.value.mandi,
  ], ([
    newValueMengontrolBab,
    newValueMengontrolBak,
    newValueBersihDiri,
    newValueToilet,
    newValueMakan,
    newValueBerpindahTT,
    newValueMobilisasi,
    newValueBerpakaian,
    newValueTangga,
    newValueMandi,
  ]) => {
    let totalNilaiStatusFungsional;
    //? Mencegah dari undefined
    newValueMengontrolBab = newValueMengontrolBab ?? 0;
    newValueMengontrolBak = newValueMengontrolBak ?? 0;
    newValueBersihDiri = newValueBersihDiri ?? 0;
    newValueToilet = newValueToilet ?? 0;
    newValueMakan = newValueMakan ?? 0;
    newValueBerpindahTT = newValueBerpindahTT ?? 0;
    newValueMobilisasi = newValueMobilisasi ?? 0;
    newValueBerpakaian = newValueBerpakaian ?? 0;
    newValueTangga = newValueTangga ?? 0;
    newValueMandi = newValueMandi ?? 0;
  
    //? Calculate Status Fungsional
    totalNilaiStatusFungsional = newValueMengontrolBab + newValueMengontrolBak + newValueBersihDiri + newValueToilet + newValueMakan + newValueBerpindahTT + newValueMobilisasi + newValueBerpakaian + newValueTangga + newValueMandi
    input.value.nilaimandi = totalNilaiStatusFungsional
  
    if (totalNilaiStatusFungsional >= 0 && totalNilaiStatusFungsional <= 4) {
      input.value.CBStatusFungsional = "Ketergantungan total (0-4)"
    } else if (totalNilaiStatusFungsional >= 5 && totalNilaiStatusFungsional <= 8) {
      input.value.CBStatusFungsional = "Ketergantungan berat (5-8)"
    } else if (totalNilaiStatusFungsional >= 9 && totalNilaiStatusFungsional <= 11) {
      input.value.CBStatusFungsional = "Ketergantungan sedang (9-11)"
    } else if (totalNilaiStatusFungsional >= 12 && totalNilaiStatusFungsional <= 19) {
      input.value.CBStatusFungsional = "Ketergantungan ringan(12-19)"
    } else if (totalNilaiStatusFungsional >= 20) {
      input.value.CBStatusFungsional = "Mandiri (20)"
    }
  });
  
  const diagnosesOptions = [
    { text: "Kurang pengetahuan tentang penyakit, rencana tindakan, dan pengobatan b/d kurang terpajannya informasi", value: "kurangpengetahuan" },
    { text: "Diare akut b/d mal absorbsi, peningkatan motilitas usus", value: "diareakut" },
    { text: "Kesiapan meningkatkan status kesehatan", value: "statuskesehatan" },
    { text: "Risiko/Gangguan integritas kulit", value: "integritaskulit" },
    { text: "HIV Counselling", value: "hiv" },
    { text: "Sex Counselling", value: "sex" },
    { text: "Counselling related to sexual attitude, behavior and orientation", value: "counselling" },
    { text: "Lainnya", value: "lainnya1" }
  ];
  
  const filteredDiagnoses = computed(() => {
    const term = filterMenu.value.toLowerCase();
    return diagnosesOptions.filter(diagnosis =>
      diagnosis.text.toLowerCase().includes(term)
    );
  });
  
  function highlightMatch(text) {
    if (!filterMenu.value) return text;
  
    const term = new RegExp(`(${filterMenu.value})`, 'gi');
    return text.replace(term, '<span style="background-color: yellow;">$1</span>');
  }
  
  </script>
  <style lang="scss">
  @import '/@src/scss/abstracts/all';
  @import '/@src/scss/components/forms-outer';
  @import '/@src/scss/custom/config';
  @import '/@src/scss/custom/timeline-css';
  @import '/@src/scss/module/emr/asesmen-awal.scss';
  
  .center {
    text-align: center;
  }
  
  .vm {
    vertical-align: middle;
  }
  
  .bold {
    font-weight: bold;
  }
  
  hr {
    background-color: hsl(0deg 6.81% 88.68%);
    border: none;
    display: block;
    height: 2px;
    margin: 1rem 0;
  }
  
  .table.is-borderless th,
  tr,
  td {
    border: none !important;
    background-color: transparent !important;
  }
  
  hr {
    background-color: hsl(0deg 6.81% 88.68%);
    border: none;
    display: block;
    height: 2px;
    margin: 0px;
  }
  
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
    // border-color: var(--fade-grey-dark-2);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
  }
  
  .tg2 th {
    // border-color: var(--fade-grey-dark-3);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: normal;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
  }
  
  .fontcheckbox {
    padding: 0px;
    padding-top: 5px;
    padding-left: 5px;
  }
  
  .fontcheckbox label {
    color: black;
  }
  
  
  .tg td {
    // border-color: var(--fade-grey-dark-2);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
  }
  
  .tg th {
    // border-color: var(--fade-grey-dark-3);
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
  
  mark {
    background-color: yellow;
    /* Pastikan warna yang Anda inginkan ditulis di sini */
    color: black;
    /* Warna teks jika perlu */
  }
  
  input::-webkit-outer-spin-button,
  input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
  }
  
  input[type=number] {
      -moz-appearance:textfield; /* Firefox */
  }
  </style>
  