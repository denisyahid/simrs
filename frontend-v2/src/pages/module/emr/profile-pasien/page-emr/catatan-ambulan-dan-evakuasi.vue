<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" @simpan="simpan"
              @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>

    </div>
  </div>

  <div class="column is-12">
    <VPlaceload height="37rem" width="100%" class="mx-2" v-if="loadData" />
    <VCard v-else>
      <div class="column">
        <h1 style="font-weight:bold">A. Permintaan Ambulan Internal</h1>
        <div class="columns is-multiline p-3">
          <div class="column is-6">
            <VCard>
              <div class="column is-8">
                <span>Diagnosa Rujukan</span>
                <VField class="is-autocomplete-select pt-3" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="input.diagnosa" :suggestions="d_Diagnosa" @complete="fetchDiagnosa($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik Diagnosa" />
                  </VControl>
                </VField>
              </div>
              <div class="columns is-multiline pt-3 pl-3 pr-3">
                <div class="column pb-0">
                  <span>Tanggal/Jam berangkat</span>
                  <VDatePicker class="pt-3" v-model="input.tglBerangkat" mode="dateTime" style="width: 100%" trim-weeks
                    :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </div>
                <div class="column pb-0">
                  <span>Tanggal/Jam permintaan</span>
                  <VDatePicker class="pt-3" v-model="input.tglPermintaan" mode="dateTime" style="width: 100%" trim-weeks
                    :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </div>
              </div>
              <div class="column is-8 pt-0">
                <span>Dokter Penanggung jawab</span>
                <VField class="is-autocomplete-select pt-3" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="input.dokterPenangungJwb" :suggestions="d_Dokter"
                      @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                      :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                      placeholder="ketik nama dokter" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-8">
                <span>Asal Ruangan</span>
                <VField class="is-autocomplete-select pt-3" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="input.ruanganAsal" :suggestions="d_Poli" @complete="fetchPoli($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik nama ruangan" />
                  </VControl>
                </VField>
              </div>
            </VCard>
          </div>

          <div class="column is-6">
            <VCard>
              <div class="column is-12">
                <span>Alasan rujuk/pindah</span>
                <VField class="pt-3">
                  <VControl>
                    <VInput type="text" v-model="input.alsRujuk" placeholder="Alasan rujuk" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12">
                <span>Dokumen yang diasiapkan</span>
                <VField class="pt-3">
                  <VControl>
                    <VInput type="text" v-model="input.dokumen" placeholder="Dokumen yang disiapkan" />
                  </VControl>
                </VField>
              </div>
              <div class="columns is-multiline pl-3 pr-3 pt-3">
                <div class="column is-6">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" v-model="input.resumMedis" true-value="RMR" label="Resume Medis/Rujuk"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" v-model="input.hasilLab" true-value="HLR" label="Hasil Lab & Radiologi"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
            </VCard>
          </div>
        </div>
      </div>

      <div class="column">
        <h1 style="font-weight:bold">B. Permintaan Ambulan External</h1>
        <div class="column">
          <VCard>
            <div class="columns is-multiline">
              <div class="column is-3">
                <span>Nomer Telpon</span>
                <VField class="pt-3">
                  <VControl>
                    <VInput type="text" v-model="input.noTelp" placeholder="No telpon yang bisa dihubungi" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <span>Tanggal / Jam Berangkat</span>
                <VDatePicker class="pt-3" v-model="input.tglBerangkatAmbulan" mode="dateTime" style="width: 100%"
                  trim-weeks :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </div>
              <div class="column is-3">
                <span>Alasan Penjemputan</span>
                <VField class="pt-3">
                  <VControl>
                    <VInput type="text" v-model="input.alsPenjeputanAmbulan"
                      placeholder="No telpon yang bisa dihubungi" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <span>Tanggal / Jam tiba di RS</span>
                <VDatePicker class="pt-3" v-model="input.tglAmbulanTibaRS" mode="dateTime" style="width: 100%" trim-weeks
                  :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-6 p-0 pt-0 pl-3 pr-3">
                <span>Sarana Evakuasi</span>
                <div class="columns is-multiline p-3">
                  <div class="column is-4">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" v-model="input.saranaEvakuasi" true-value="Ambulan" label="Ambulan"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" v-model="input.saranaEvakuasi" true-value="Non Ambulan" label="Non Ambulan"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" v-model="input.saranaEvakuasi" true-value="CKT"
                      label="Cek kebenaran telp dengan menghubungi kembali" color="primary" square />
                  </VControl>
                </VField>
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" v-model="input.saranaEvakuasi" true-value="CKR"
                      label="Cek Ketersediaan ruangan sesuai indikasi pasien" color="primary" square />
                  </VControl>
                </VField>
              </div>
            </div>
          </VCard>
        </div>

        <div class="column">
          <VCard>
            <div class="column">
              <div class="columns is-multiline">
                <div class="column is-4">
                  <span>Petugas Evakuasi 1</span>
                  <VField class="pt-3">
                    <VControl>
                      <VInput type="text" v-model="input.petugasEvakuasi_1" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <span>Petugas Evakuasi 2</span>
                  <VField class="pt-3">
                    <VControl>
                      <VInput type="text" v-model="input.petugasEvakuasi_2" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <span>Petugas Evakuasi 3</span>
                  <VField class="pt-3">
                    <VControl>
                      <VInput type="text" v-model="input.petugasEvakuasi_3" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column">
              <span>Alasan Transportasi</span>
              <div class="columns is-multiline p-3">
                <div class="column is-6">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" v-model="input.alsnTransportasi" true-value="rujuk" label="Rujuk ke RS"
                        color="primary" square />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.namaRSRujuk" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" v-model="input.alsnTransportasi" true-value="pindah" label="Pindah ke RS"
                        color="primary" square />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.namaRSPindah" />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline pl-3 pr-3">
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" v-model="input.alsnTransportasi" true-value="APS" label="APS" color="primary"
                        square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" v-model="input.alsnTransportasi" true-value="Dipulangkan" label="Dipulangkan"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" v-model="input.alsnTransportasi" true-value="lainnya" label="Lainnya"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.alsLainnya" />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="column is-8">
                <span>Keluhan Utama</span>
                <VField class="pt-3">
                  <VControl>
                    <VTextarea class="textarea" v-model="input.keluhanUtama" rows="2" placeholder="Keluhan Utama ..."
                      autocomplete="off" autocapitalize="off" spellcheck="true" />
                  </VControl>
                </VField>
              </div>
            </div>

            <hr style="border-bottom: dashed;color: hsl(0deg, 0%, 82%);">

            <div class="column">
              <span style="font-weight:500">TRIAGE</span>
              <div class="columns is-multiline p-3">
                <div class="column is-3">
                  <table border="1px" width="100%">
                    <tr>
                      <td border="1px" style="background-color: rgb(179, 74, 74); width: 50%;">
                      </td>
                      <td border="1px">
                        <div class="column p-2">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox class="p-0" v-model="input.triage" true-value="Merah" label="Merah"
                                color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                  </table>
                </div>
                <div class="column is-3">
                  <table border="1px" width="100%">
                    <tr>
                      <td border="1px" style="background-color: rgb(228, 226, 100); width: 50%;">
                      </td>
                      <td border="1px">
                        <div class="column p-2">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox class="p-0" v-model="input.triage" true-value="Kuning" label="Kuning"
                                color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                  </table>
                </div>
                <div class="column is-3">
                  <table border="1px" width="100%">
                    <tr>
                      <td border="1px" style="background-color: rgb(78, 173, 99) ; width: 50%;">
                      </td>
                      <td border="1px">
                        <div class="column p-2">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox class="p-0" v-model="input.triage" true-value="Hijau" label="Hijau"
                                color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                  </table>
                </div>
                <div class="column is-3">
                  <table border="1px" width="100%">
                    <tr>
                      <td border="1px" style="background-color: rgba(0, 0, 0, 0.849); width: 50%;">
                      </td>
                      <td border="1px">
                        <div class="column p-2">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox class="p-0" v-model="input.triage" true-value="Hitam" label="Hitam"
                                color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-8">
                  <span>Daftar Masakah / Kondisi Khusus</span>
                  <VField class="pt-3">
                    <VControl>
                      <VTextarea class="textarea" v-model="input.daftarMasalah" rows="2"
                        placeholder="Daftar Masalah / Kondisi Khusus ..." autocomplete="off" autocapitalize="off"
                        spellcheck="true" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <span>Intervensi</span>
                  <VDatePicker class="pt-3" v-model="input.intervensi" color="green" mode="time" is24hr>
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl>
                          <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </div>
              </div>

              <table border="1px" width="100%" style="border-collapse: collapse">
                <thead>
                  <tr>
                    <th>Airway</th>
                    <th>Breathing</th>
                    <th>Circulation</th>
                    <th>Exposure</th>
                    <th>Peralatan</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <div class="column pt-3 pb-0" v-for="(data) in airway">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" v-model="input[data.model]" :true-value="data.code" :label="data.label"
                              color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column">
                        <VField>
                          <VControl>
                            <VInput type="text" v-model="input.ketAirway" />
                          </VControl>
                        </VField>
                      </div>
                    </td>
                    <td>
                      <div class="column pt-3 pb-0" v-for="(data) in breathing" :class="data.code == 'BLN' ? 'pb-3' : ''">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" v-model="input[data.model]" :true-value="data.code" :label="data.label"
                              color="primary" square />
                          </VControl>
                        </VField>
                        <VField v-if="data.optional">
                          <VControl>
                            <VInput type="text" v-model="input[data.optional]" />
                          </VControl>
                        </VField>
                      </div>
                    </td>
                    <td>
                      <div class="column">
                        <span>IVFD (Jenis Cairan)</span>
                        <div class="columns is-multiline pt-3" v-for="(data) in circulation">
                          <div class="column is-1" style="margin: auto;">
                            <span>{{ data.label }}</span>
                          </div>
                          <div class="column p-2">
                            <VField>
                              <VControl>
                                <VInput type="text" v-model="input[data.model]" palceholder="Tpm/cc" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <div class="column p-0">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox class="p-0" v-model="input.circluFalley" true-value="CFC"
                                label="Falley Catether No" color="primary" square />
                            </VControl>
                          </VField>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.ketCFC" />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column p-0 mt-3">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox class="p-0" v-model="input.NGT" true-value="NGT" label="NGT> No" color="primary"
                                square />
                            </VControl>
                          </VField>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.ketNGT" />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="column pt-3 pb-0" v-for="(data) in exposure">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" v-model="input[data.model]" :true-value="data.code" :label="data.label"
                              color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column">
                        <VField>
                          <VControl>
                            <VInput type="text" v-model="input.ketExposure" />
                          </VControl>
                        </VField>
                      </div>
                    </td>
                    <td>
                      <div class="column pt-3 pb-0" v-for="(data) in peralatan">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" v-model="input[data.model]" :true-value="data.code" :label="data.label"
                              color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>

            </div>
          </VCard>
        </div>

        <div class="column" style="overflow:auto">
          <Fieldset :toggleable="true" legend="CATATAN OBSERVASI">
            <table border="1px" width="100%" style="border-collapse: collapse">
              <thead>
                <tr>
                  <th width="13%">Jam : Menit</th>
                  <th width="10%">BP (mmhg)</th>
                  <th width="10%">RR</th>
                  <th width="10%">SpO2</th>
                  <th width="18%">Nadi</th>
                  <th width="20%" colspan="2">kulit</th>
                  <th>Skala Koma Glasglow</th>
                  <th>#</th>
                </tr>
              </thead>
              <tbody v-for="(item, index) in input.details" :key="index">
                <tr>
                  <td>
                    <div class="column">
                      <VDatePicker v-model="item.waktuObservasi" mode="time" style="width: 100%" trim-weeks
                        :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }">
                          <VField>
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" placeholder="waktu" v-on="inputEvents" />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </div>
                  </td>
                  <td>
                    <div class="column">
                      <VField>
                        <VControl>
                          <VInput type="text" v-model="item.BP" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column">
                      <VField>
                        <VControl>
                          <VInput type="text" v-model="item.RR" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column">
                      <VField>
                        <VControl>
                          <VInput type="text" v-model="item.SpO2" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column">
                      <VField>
                        <VControl>
                          <VInput type="text" v-model="item.nadi" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="columns is-multiline pl-3 pr-3">
                      <div class="column">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" v-model="item.nadiReg" true-value="Reguler" label="Reguler"
                              color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" v-model="item.nadiKuat" true-value="Kuat" label="Kuat" color="primary"
                              square />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                    <div class="columns is-multiline pl-3 pr-3 pb-5">
                      <div class="column">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" v-model="item.nadiIrreg" true-value="Irreguler" label="Irreguler"
                              color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" v-model="item.nadiLemah" true-value="Lemah" label="Lemah"
                              color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </td>
                  <td width="10%">
                    <div class="column">
                      <span>Warna</span>
                      <VField>
                        <VControl>
                          <VInput type="text" v-model="item.warna" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column">
                      <span>Suhu</span>
                      <VField>
                        <VControl>
                          <VInput type="text" v-model="item.suhu" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column" v-for="(data) in kulit">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" v-model="item.model" :true-value="data.label" :label="data.label"
                            color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column">
                      <span>E</span>
                      <VField>
                        <VControl>
                          <VInput type="text" v-model="item.skalaKoma_E" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column">
                      <span>M</span>
                      <VField>
                        <VControl>
                          <VInput type="text" v-model="item.skalaKoma_M" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column">
                      <span>V</span>
                      <VField>
                        <VControl>
                          <VInput type="text" v-model="item.skalaKoma_V" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column">
                      <span>Score</span>
                      <VField>
                        <VControl>
                          <VInput type="text" v-model="item.score" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td>
                    <div class="column">
                      <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                        v-tooltip.bubble="'Tambah '">
                      </VIconButton>
                      <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                        @click="removeItem(index)" color="danger">
                      </VIconButton>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>

            <br>
            <table width="80%" border="1px" style="border-collapse:collapse">
              <thead>
                <tr>
                  <th colspan="6" style="text-align:center">
                    Keterangan Poin Skala Koma Glasglow
                  </th>
                </tr>
                <tr>
                  <th>
                    Eyes
                  </th>
                  <th>
                    Poin
                  </th>
                  <th>
                    Verbal
                  </th>
                  <th>
                    Poin
                  </th>
                  <th>
                    Motorik
                  </th>
                  <th>
                    Poin
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td border="1px">
                    Tidak respon
                  </td>
                  <td border="1px" class="td-cae">
                    1
                  </td>
                  <td border="1px">
                    Tidak Bicara
                  </td>
                  <td border="1px" class="td-cae">
                    1
                  </td>
                  <td border="1px">
                    Tidak respon
                  </td>
                  <td border="1px" class="td-cae">
                    1
                  </td>
                </tr>
                <tr>
                  <td border="1px">
                    Rangsang nyeri
                  </td>
                  <td border="1px" class="td-cae">
                    2
                  </td>
                  <td border="1px">
                    Tidak Mengerti
                  </td>
                  <td border="1px" class="td-cae">
                    2
                  </td>
                  <td border="1px">
                    Extensi
                  </td>
                  <td border="1px" class="td-cae">
                    2
                  </td>
                </tr>
                <tr>
                  <td border="1px">
                    Panggilan
                  </td>
                  <td border="1px" class="td-cae">
                    3
                  </td>
                  <td border="1px">
                    Kacau
                  </td>
                  <td border="1px" class="td-cae">
                    3
                  </td>
                  <td border="1px">
                    Fleksi
                  </td>
                  <td border="1px" class="td-cae">
                    3
                  </td>
                </tr>
                <tr>
                  <td rowspan="3" border="1px">
                    Spontan
                  </td>
                  <td rowspan="3" border="1px" class="td-cae">
                    4
                  </td>
                  <td order="1px">
                    Bingung
                  </td>
                  <td border="1px" class="td-cae">
                    4
                  </td>
                  <td border="1px">
                    Menghindar
                  </td>
                  <td border="1px" class="td-cae">
                    4
                  </td>
                </tr>
                <tr>

                  <td rowspan="2" border="1px">
                    Terarah
                  </td>
                  <td rowspan="2" border="1px" class="td-cae">
                    5
                  </td>
                  <td border="1px">
                    Rangsang Nyeri
                  </td>
                  <td border="1px" class="td-cae">
                    5
                  </td>
                </tr>
                <tr>
                  <td border="1px">
                    Ikut Perintah
                  </td>
                  <td border="1px" class="td-cae">
                    6
                  </td>
                </tr>

              </tbody>
            </table>
          </Fieldset>
        </div>

        <div class="column">
          <VCard>
            <table class="table-cade">
              <thead>
                <tr>
                  <th class="th-cade">No</th>
                  <th class="th-cade" width="10%">Jam Menit</th>
                  <th class="th-cade">Obata-obatan</th>
                  <th class="th-cade">Rute / Dosis</th>
                  <th class="th-cade">Urin Output</th>
                  <th class="th-cade">Alergi</th>
                  <th class="th-cade">#</th>
                </tr>
              </thead>
              <tbody v-for="(item, index) in input.pemberianObat" :key="index">
                <tr>
                  <td class="td-cade">
                    <span>{{ item.no }}</span>
                  </td>
                  <td class="td-cade">
                    <VDatePicker v-model="item.jamMenit" color="green" mode="time" is24hr>
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VControl>
                            <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </td>
                  <td class="td-cade">
                    <VField>
                      <VControl>
                        <VInput type="text" v-model="item.obatObatan" />
                      </VControl>
                    </VField>
                  </td>
                  <td class="td-cade">
                    <VField>
                      <VControl>
                        <VInput type="text" v-model="item.ruteDosis" />
                      </VControl>
                    </VField>
                  </td>
                  <td class="td-cade">
                    <VField>
                      <VControl>
                        <VInput type="text" v-model="item.urineOutput" />
                      </VControl>
                    </VField>
                  </td>
                  <td class="td-cade">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" v-model="item.isAlergi" true-value="Ya" label="Ya/Tuliskan" color="primary"
                          square />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl>
                        <VInput type="text" v-model="item.ketAlergi" />
                      </VControl>
                    </VField>
                  </td>
                  <td class="td-cade">
                    <VButtons style="justify-content: space-around;">
                      <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItemPemberianObat()" color="info"
                        v-tooltip.bubble="'Tambah '">
                      </VIconButton>
                      <VIconButton v-if="index > 0" class="mt-1" type="button" raised circle icon="feather:trash"
                        @click="removeItemPemberianObat(index)" color="danger">
                      </VIconButton>
                    </VButtons>
                  </td>
                </tr>
              </tbody>
            </table>
          </VCard>
        </div>

        <div class="column">
          <VCard>
            <div class="column">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" v-model="input.perhatianKhusus" true-value="PKCK"
                    label="Perhatian khusus/Catatan khusus" color="primary" square />
                </VControl>
                <VField class="pt-3">
                  <VControl>
                    <VTextarea v-model="input.ketPerhatianKhusus" rows="3" placeholder="...">
                    </VTextarea>
                  </VControl>
                </VField>

              </VField>
            </div>
          </VCard>
        </div>

        <div class="column">
          <Fieldset :toggleable="true" legend="SERAH TERIMA PASIEN">
            <div class="columns is-multiline p-3">
              <div class="column is-3">
                <span>TD</span>
                <VField class="pt-3">
                  <VControl>
                    <VInput type="text" v-model="input.TD" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <span>Nadi</span>
                <VField class="pt-3">
                  <VControl>
                    <VInput type="text" v-model="input.nadi" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <span>T</span>
                <VField class="pt-3">
                  <VControl>
                    <VInput type="text" v-model="input.T" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <span>RR</span>
                <VField class="pt-3">
                  <VControl>
                    <VInput type="text" v-model="input.RR" />
                  </VControl>
                </VField>
              </div>
            </div>
            <hr style="border-bottom: dashed;color: hsl(0deg, 0%, 82%);">
            <div class="column">
              <span>GCS</span>
              <div class="columns is-multiline p-3">
                <div class="column is-2">
                  <span>E</span>
                  <VField class="pt-3">
                    <VControl>
                      <VInput type="text" v-model="input.GCS_E" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <span>M</span>
                  <VField class="pt-3">
                    <VControl>
                      <VInput type="text" v-model="input.GCS_M" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <span>V</span>
                  <VField class="pt-3">
                    <VControl>
                      <VInput type="text" v-model="input.GCS_V" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </Fieldset>
        </div>

        <div class="column is-4" style="margin-left:auto">
          <VCard>
            <div class="column">
              <span style="font-weight:500">Petugas Ambulance</span>
              <VField>
                <VControl class="prime-auto">
                  <AutoComplete v-model="item.petugas" :suggestions="d_Pegawai" @complete="fetchPetugas($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Petugas..." class="mt-3" />
                </VControl>
              </VField>
            </div>
            <div class="column">
              <span style="font-weight:500">Petugas / Keluarga Penerima</span>
              <VField class="mt-3">
                <VControl>
                  <VInput type="text" v-model="item.keluargaPenerima" />
                </VControl>
              </VField>
            </div>
          </VCard>
        </div>
      </div>
    </VCard>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
// import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import Fieldset from 'primevue/fieldset';
import { useViewWrapper } from '/@src/stores/viewWrapper'
import * as EMR from '../page-emr-plugins/catatan-ambulan-dan-evakuasi'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let airway = ref(EMR.airway())
let breathing = ref(EMR.breathing())
let circulation = ref(EMR.circulation())
let exposure = ref(EMR.exposure())
let peralatan = ref(EMR.peralatan())
let kulit = ref(EMR.kulit())

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

const route = useRoute()
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const loadData: any = ref(true)
const d_Diagnosa: any = ref([])
const d_Poli: any = ref(false)
const d_Dokter: any = ref(false)
const d_Pegawai: any = ref(false)
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
  intervensi: new Date(),
  details: [{
    waktuObservasi: new Date,
  }],
  pemberianObat: [
    {
      no: 1,
      jamMenit: new Date()
    }
  ]
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = async () => {
  // if (NOREC_EMRPASIEN.value == '') return
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
      if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      }
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

const addNewItem = () => {
  input.value.details.push({
    no: input.value.details[input.value.details.length - 1].no + 1,
    waktuObservasi: new Date()
  });
}
const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
}

const addNewItemPemberianObat = () => {
  input.value.pemberianObat.push({
    no: input.value.pemberianObat[input.value.pemberianObat.length - 1].no + 1,
    jamMenit: new Date()
  });
}

const removeItemPemberianObat = (index: any) => {
  input.value.pemberianObat.splice(index, 1)
}

const fetchDiagnosa = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/diagnosa_m?select=kddiagnosa,namadiagnosa&param_search=kddiagnosa&query=${filter.query}&limit=10`)
  d_Diagnosa.value = response
}

const fetchDokter = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const fetchPetugas = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const fetchPoli = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Poli.value = response
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {

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
.td-cae {
  text-align: center !important;
}

.table-cade {
  width: 100%;
  border: 1px solid black;
}

.th-cade{
  text-align:center !important;
}
.th-cade,
.td-cade {
  padding: 7px;
  border: 1px solid black;
  vertical-align: inherit;
}
</style>
