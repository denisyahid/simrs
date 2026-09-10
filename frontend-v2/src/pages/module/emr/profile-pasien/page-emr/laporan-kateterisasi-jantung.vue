<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>{{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr
              :NOREC_EMRPASIEN="NOREC_EMRPASIEN"
              :COLLECTION="COLLECTION"
              :isLoading="isLoading"
              @simpan="simpan"
              @kembaliKeun="kembaliKeun"
              isHideST
            ></ButtonEmr>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="columns is-multiline p-2">
    <div class="column is-12">
      <VCard>
        <div class="columns is-multiline">
          <div class="column is-12 is-flex ml-5">
            <div class="column is-5">
              <h1>Diagnosa pra-kateterisasi (Anatomi, Etiologi, Fungsionil) :</h1>
            </div>
            <div class="column is-7">
              <VControl>
                <VInput type="text" class="input" v-model="input.diagnosaPra" />
              </VControl>
            </div>
          </div>
          <div class="column is-12 is-flex ml-5">
            <div class="column is-3">
              <h1>Data penyadapan jantung :</h1>
            </div>
            <div class="column is-8">
              <VControl>
                <VInput type="text" class="input" v-model="input.DtPenyadapan" />
              </VControl>
            </div>
          </div>

          <table border="1">
            <tr>
              <td>
                <div class="column is-12 is-flex ml-5">
                  <div class="column is-4">
                    <h1>NAMA :</h1>
                  </div>
                  <div class="column is-8" style="margin-top: -10px">
                    <VControl>
                      <VTextarea type="text" class="input" v-model="input.namaPasien" />
                    </VControl>
                  </div>
                </div>
              </td>
              <td>
                <div class="column is-12 is-flex ml-5">
                  <div class="column is-4">
                    <h1>Tgl.Kateterisasi</h1>
                  </div>
                  <div class="column is-8" style="margin-top: -10px">
                    <VField>
                      <VDatePicker v-model="input.tglkateter" mode="date" trim-weeks>
                        <template #default="{ inputValue, inputEvents }">
                          <VField>
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput
                                :value="inputValue"
                                v-on="inputEvents"
                                placeholder="Tanggal"
                              />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </VField>
                  </div>
                </div>
              </td>
              <td>
                <div class="column is-12 is-flex ml-5">
                  <div class="column is-6 pt-0 pb-0">
                    <span>BB : </span>
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBberatBadan" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Kg</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6 pt-0 pb-0">
                    <span>suhu : </span>
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBSuhu" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>°C</VButton>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <div class="column is-12 is-flex ml-5">
                  <div class="column is-6">
                    <h1>Umur/Tgl.Lahir :</h1>
                  </div>
                  <div class="column is-6" style="margin-top: -10px; margin-left: -20px">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.UmurTgllahir" />
                    </VControl>
                  </div>
                </div>
              </td>
              <td>
                <div class="column is-12 is-flex ml-5">
                  <div class="column is-6">
                    <h1>No. Rekam Medis</h1>
                  </div>
                  <div class="column is-6" style="margin-top: -10px; margin-left: -20px">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.norm" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </td>
              <td>
                <div class="column is-12 is-flex ml-5">
                  <div class="column is-6 pt-0 pb-0">
                    <span>TB : </span>
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBtinggiBadan" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>cm</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6 pt-0 pb-0">
                    <span>BSA : </span>
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBBsa" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>m<sup>2</sup></VButton>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <div class="column is-12 is-flex ml-5">
                  <div class="column is-6">
                    <h1>Alamat :</h1>
                  </div>
                  <div class="column is-6" style="margin-top: -10px; margin-left: -20px">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.Alamat" />
                    </VControl>
                  </div>
                </div>
              </td>
              <td>
                <div class="column is-12 is-flex ml-5">
                  <div class="column is-6">
                    <h1>No. Kunjungan</h1>
                  </div>
                  <div class="column is-6" style="margin-top: -10px; margin-left: -20px">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.nokunjungan" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </td>
              <td>
                <div class="column is-12 is-flex ml-5">
                  <div class="column is-6 pt-0 pb-0">
                    <span>TD : </span>
                    <VField addons>
                      <VControl>
                        <VInput
                          type="text"
                          class="input"
                          v-model="input.TBtekananDarah"
                        />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>mmHg</VButton>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <td></td>
              <td>
                <div class="column is-12 is-flex ml-5">
                  <div class="column is-6">
                    <h1>No. Penyadapan</h1>
                  </div>
                  <div class="column is-6" style="margin-top: -10px; margin-left: -20px">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.nokunjungan" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </td>
              <td>
                <div class="column is-12 is-flex ml-5">
                  <div class="column is-6 pt-0 pb-0">
                    <span>Nadi : </span>
                    <VField addons>
                      <VControl>
                        <VInput
                          type="text"
                          class="input"
                          v-model="input.TBtekananDarah"
                        />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>x/mnt</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1">
                    <VField v-for="items in Reguler" :key="items.value">
                      <VControl raw subcontrol>
                        <VCheckbox
                          v-model="input.RegulerIreguler"
                          class="pt-1 pb-1"
                          :true-value="items.label"
                          :label="items.label"
                          color="primary"
                          circle
                        />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <td></td>
              <td>
                <div class="column is-12 is-flex ml-5">
                  <div class="column is-5">
                    <h1>Dokter Pengirim</h1>
                  </div>
                  <div class="column is-6" style="margin-top: -10px">
                    <VField>
                      <VControl>
                        <AutoComplete
                          v-model="input.dokterPengirim"
                          :suggestions="d_Dokter"
                          @complete="fetchDokter($event)"
                          :optionLabel="'label'"
                          :dropdown="true"
                          :minLength="3"
                          :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'"
                          :field="'label'"
                        />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </td>
              <td>
                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2">
                    <h1>EKG :</h1>
                  </div>
                  <div class="column is-6">
                    <Multiselect
                      v-model="input.ekg"
                      :attrs="{ value }"
                      placeholder="--Pilih--"
                      label="label"
                      :options="d_ekg"
                      :searchable="true"
                      track-by="label"
                      mode="single"
                      autocomplete="off"
                    >
                    </Multiselect>
                  </div>
                  <div class="column is-2">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.EKGTb" />
                    </VControl>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <td></td>
              <td>
                <div class="column is-12 is-flex ml-5">
                  <div class="column is-6">
                    <h1>Kls. Rawat/Jaminan</h1>
                  </div>
                  <div class="column is-6" style="margin-top: -10px">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.nokunjungan" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </td>
              <td>
                <div class="column is-12 is-flex ml-5">
                  <div class="column is-4">
                    <h1>Allan test :</h1>
                  </div>
                  <div class="column is-6">
                    <Multiselect
                      v-model="input.allantest"
                      :attrs="{ value }"
                      placeholder="--Pilih--"
                      label="label"
                      :options="d_allantest"
                      :searchable="true"
                      track-by="label"
                      mode="single"
                      autocomplete="off"
                    >
                    </Multiselect>
                  </div>
                </div>
              </td>
            </tr>
          </table>

          <div class="column is-12 columns is-multiline pt-0" style="margin-top: 20px">
            <div class="column is-2 pt-0 pb-0">
              <h1>Laboratorium :</h1>
            </div>
            <div class="column is-10 pt-0 pb-0">
              <VField>
                <VTextarea v-model="input.Lab" rows="2"> </VTextarea>
              </VField>
            </div>
          </div>

          <div class="column is-12 columns is-multiline pt-0" style="margin-top: 20px">
            <div class="column is-2 pt-0 pb-0">
              <span>HGB : </span>
              <VField addons>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBHgb" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>g/dl</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-2 pt-0 pb-0">
              <span>HCT : </span>
              <VField addons>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBHct" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>%</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-2 pt-0 pb-0">
              <span>RBC : </span>
              <VField addons>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBRbc" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>x10^6/ul</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-2 pt-0 pb-0">
              <span>WBC :</span>
              <VField addons>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBWbc" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>x10^3/ul</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-2 pt-0 pb-0">
              <span>PLT :</span>
              <VField addons>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBWbc" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>x10^6/ul</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-2 pt-0 pb-0">
              <span>Bun :</span>
              <VField addons>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBWbc" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>mg/dl</VButton>
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-12 columns is-multiline pt-0" style="margin-top: 20px">
            <div class="column is-2 pt-0 pb-0">
              <span>Creatinin : </span>
              <VField addons>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBHgb" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>mg/dl</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-1 pt-0 pb-0" style="margin-top: 30px">
              <VControl raw subcontrol>
                <VCheckbox
                  class="p-0"
                  color="primary"
                  square
                  :true-value="BT"
                  label="BT"
                  v-model="input.BT"
                />
              </VControl>
            </div>
            <div class="column is-1 pt-0 pb-0" style="margin-top: 30px">
              <VControl raw subcontrol>
                <VCheckbox
                  class="p-0"
                  color="primary"
                  square
                  :true-value="CT"
                  label="CT"
                  v-model="input.CT"
                />
              </VControl>
            </div>
            <div class="column is-1 pt-0 pb-0" style="margin-top: 30px">
              <VControl raw subcontrol>
                <VCheckbox
                  class="p-0"
                  color="primary"
                  square
                  :true-value="PT"
                  label="PT"
                  v-model="input.PT"
                />
              </VControl>
            </div>
            <div class="column is-1 pt-0 pb-0" style="margin-top: 30px">
              <VControl raw subcontrol>
                <VCheckbox
                  class="p-0"
                  color="primary"
                  square
                  :true-value="APTT"
                  label="APTT"
                  v-model="input.APTT"
                />
              </VControl>
            </div>
            <div class="column is-1 pt-0 pb-0" style="margin-top: 30px">
              <VControl raw subcontrol>
                <VCheckbox
                  class="p-0"
                  color="primary"
                  square
                  :true-value="INR"
                  label="INR"
                  v-model="input.INR"
                />
              </VControl>
            </div>
            <div class="column is-2 pt-0 pb-0">
              <span>Natrium : </span>
              <VField addons>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBNatrium" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>mol/l</VButton>
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 columns is-multiline pt-0" style="margin-top: 20px">
            <div class="column is-2 pt-0 pb-0">
              <span>Kalium : </span>
              <VField addons>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBKalium" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>mmol/l</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-2 pt-0 pb-0">
              <span>BS : </span>
              <VField addons>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBBS" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>mm/dl</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-2 pt-0 pb-0">
              <span>Gol darah/RH : </span>
              <VField addons>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBGoldarah" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2 pt-0 pb-0">
              <span>HBsAg :</span>
              <VField addons>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBHBsAg" />
                </VControl>
              </VField>
            </div>
          </div>

          <table border="1">
            <tr>
              <td>
                <div class="column is-12">
                  <h1>Obat-obat yang diberikan/ditunda :</h1>
                </div>
                <div class="column is-12 is-flex">
                  <div class="column is-1">
                    <h1>1.</h1>
                  </div>
                  <div class="column is-3">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.ObatSatu" />
                    </VControl>
                  </div>
                  <div class="column is-1">
                    <h1>4.</h1>
                  </div>
                  <div class="column is-3">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.ObatEmpat" />
                    </VControl>
                  </div>
                  <div class="column is-1">
                    <h1>7.</h1>
                  </div>
                  <div class="column is-3">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.ObatTujuh" />
                    </VControl>
                  </div>
                </div>
                <div class="column is-12 is-flex">
                  <div class="column is-1">
                    <h1>2.</h1>
                  </div>
                  <div class="column is-3">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.ObatDua" />
                    </VControl>
                  </div>
                  <div class="column is-1">
                    <h1>5.</h1>
                  </div>
                  <div class="column is-3">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.ObatLima" />
                    </VControl>
                  </div>
                  <div class="column is-1">
                    <h1>8.</h1>
                  </div>
                  <div class="column is-3">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.ObatDelapan" />
                    </VControl>
                  </div>
                </div>
                <div class="column is-12 is-flex">
                  <div class="column is-1">
                    <h1>3.</h1>
                  </div>
                  <div class="column is-3">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.ObatTiga" />
                    </VControl>
                  </div>
                  <div class="column is-1">
                    <h1>6.</h1>
                  </div>
                  <div class="column is-3">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.ObatEnam" />
                    </VControl>
                  </div>
                  <div class="column is-1">
                    <h1>9.</h1>
                  </div>
                  <div class="column is-3">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.ObatSembilan" />
                    </VControl>
                  </div>
                </div>
              </td>
              <td>
                <div class="column is-12">
                  <h1>Premedikasi :</h1>
                </div>
                <div class="column is-12 is-flex">
                  <div class="column is-1">
                    <h1>1.</h1>
                  </div>
                  <div class="column is-3">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.ObatSatu" />
                    </VControl>
                  </div>
                  <div class="column is-2">
                    <h1>Jam :</h1>
                  </div>
                  <div class="column is-3">
                    <VField>
                      <VField>
                        <VDatePicker
                          v-model="input.jamPremedaksi1"
                          color="green"
                          trim-weeks
                          mode="Time"
                        >
                          <template #default="{ inputValue, inputEvents }" class="pb-0">
                            <VField>
                              <VControl icon="feather:calendar">
                                <VInput
                                  type="text"
                                  placeholder=""
                                  :value="inputValue"
                                  v-on="inputEvents"
                                  class="is-rounded_Z"
                                />
                              </VControl>
                            </VField>
                          </template>
                        </VDatePicker>
                      </VField>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <Multiselect
                      v-model="input.jam1"
                      :attrs="{ value }"
                      placeholder="--Pilih--"
                      label="label"
                      :options="d_jam"
                      :searchable="true"
                      track-by="label"
                      mode="single"
                      autocomplete="off"
                    >
                    </Multiselect>
                  </div>
                </div>
                <div class="column is-12 is-flex">
                  <div class="column is-1">
                    <h1>2.</h1>
                  </div>
                  <div class="column is-3">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.ObatSatu" />
                    </VControl>
                  </div>
                  <div class="column is-2">
                    <h1>Jam :</h1>
                  </div>
                  <div class="column is-3">
                    <VField>
                      <VField>
                        <VDatePicker
                          v-model="input.jamPremedaksi2"
                          color="green"
                          trim-weeks
                          mode="Time"
                        >
                          <template #default="{ inputValue, inputEvents }" class="pb-0">
                            <VField>
                              <VControl icon="feather:calendar">
                                <VInput
                                  type="text"
                                  placeholder=""
                                  :value="inputValue"
                                  v-on="inputEvents"
                                  class="is-rounded_Z"
                                />
                              </VControl>
                            </VField>
                          </template>
                        </VDatePicker>
                      </VField>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <Multiselect
                      v-model="input.jam2"
                      :attrs="{ value }"
                      placeholder="--Pilih--"
                      label="label"
                      :options="d_jam"
                      :searchable="true"
                      track-by="label"
                      mode="single"
                      autocomplete="off"
                    >
                    </Multiselect>
                  </div>
                </div>
                <div class="column is-12 is-flex">
                  <div class="column is-1">
                    <h1>3.</h1>
                  </div>
                  <div class="column is-3">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.ObatSatu" />
                    </VControl>
                  </div>
                  <div class="column is-2">
                    <h1>Jam :</h1>
                  </div>
                  <div class="column is-3">
                    <VField>
                      <VDatePicker
                        v-model="input.jamPremedaksi3"
                        color="green"
                        trim-weeks
                        mode="Time"
                      >
                        <template #default="{ inputValue, inputEvents }" class="pb-0">
                          <VField>
                            <VControl icon="feather:calendar">
                              <VInput
                                type="text"
                                placeholder=""
                                :value="inputValue"
                                v-on="inputEvents"
                                class="is-rounded_Z"
                              />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <Multiselect
                      v-model="input.jam3"
                      :attrs="{ value }"
                      placeholder="--Pilih--"
                      label="label"
                      :options="d_jam"
                      :searchable="true"
                      track-by="label"
                      mode="single"
                      autocomplete="off"
                    >
                    </Multiselect>
                  </div>
                </div>
              </td>
            </tr>
          </table>

          <div class="column is-12 is-flex">
            <div class="column is-2" style="margin-top: 10px">
              <h1>Mulai Tindakan : Jam</h1>
            </div>
            <div class="column is-2">
              <VField>
                <VDatePicker
                  v-model="input.JamTindakan1"
                  color="green"
                  trim-weeks
                  mode="Time"
                >
                  <template #default="{ inputValue, inputEvents }" class="pb-0">
                    <VField>
                      <VControl icon="feather:calendar">
                        <VInput
                          type="text"
                          placeholder=""
                          :value="inputValue"
                          v-on="inputEvents"
                          class="is-rounded_Z"
                        />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
            <div class="column is-1" style="margin-top: 10px">
              <h1>s/d</h1>
            </div>
            <div class="column is-2" style="margin-left: -40px">
              <VField>
                <VDatePicker
                  v-model="input.JamTindakan2"
                  color="green"
                  trim-weeks
                  mode="Time"
                >
                  <template #default="{ inputValue, inputEvents }" class="pb-0">
                    <VField>
                      <VControl icon="feather:calendar">
                        <VInput
                          type="text"
                          placeholder=""
                          :value="inputValue"
                          v-on="inputEvents"
                          class="is-rounded_Z"
                        />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
            <div class="column is-2" style="margin-top: 10px">
              <h1>Cara Masuk :</h1>
            </div>
            <div class="column is-3" style="margin-left: -30px">
              <VControl>
                <VInput type="text" class="input" v-model="input.CaraMasuk" />
              </VControl>
            </div>
          </div>

          <div class="column is-12">
            <h1>Jenis tindakan yang dilakukan :</h1>
          </div>

          <div class="column is-3">
            <VField v-for="items in jenisTindakan" :key="items.value">
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.jenisTindakan"
                  class="pt-1 pb-1"
                  :true-value="items.label"
                  :label="items.label"
                  color="primary"
                  square
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField v-for="items in jenisTindakan2" :key="items.value">
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.jenisTindakan2"
                  class="pt-1 pb-1"
                  :true-value="items.label"
                  :label="items.label"
                  color="primary"
                  square
                />
              </VControl>
            </VField>
            <div class="column is-12 is-flex">
              <div class="column is-5" style="margin-top: 10px">
                <h1>di</h1>
              </div>
              <div class="column is-8">
                <VControl>
                  <VInput type="text" class="input" v-model="input.tempatDI" />
                </VControl>
              </div>
            </div>
          </div>
          <div class="column is-3">
            <VField v-for="items in jenisTindakan3" :key="items.value">
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.jenisTindakan2"
                  class="pt-1 pb-1"
                  :true-value="items.label"
                  :label="items.label"
                  color="primary"
                  square
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <div class="column is-12">
              <h1>Tempat Masuk :</h1>
            </div>
            <div class="column is-12">
              <VControl raw subcontrol>
                <VCheckbox
                  class="p-0"
                  color="primary"
                  square
                  :true-value="Arteri / VenaBrachialisKanan / Kiri"
                  label="1. Arteri/Vena Brachialis Kanan/Kiri"
                  v-model="input.tempatmasuk1"
                />
              </VControl>
            </div>
            <div class="column is-12">
              <VControl raw subcontrol>
                <VCheckbox
                  class="p-0"
                  color="primary"
                  square
                  :true-value="Arteri / VenaFemoralisKanan / Kiri"
                  label="2. Arteri/Vena Femoralis Kanan/Kiri"
                  v-model="input.tempatmasuk2"
                />
              </VControl>
            </div>
            <div class="column is-12">
              <VControl raw subcontrol>
                <VCheckbox
                  class="p-0"
                  color="primary"
                  square
                  :true-value="ArteriRadialisKanan / Kiri"
                  label="3. Arteri Radialis Kanan/Kiri"
                  v-model="input.tempatmasuk3"
                />
              </VControl>
            </div>
            <div class="column is-12 is-flex">
              <div class="column is-5" style="margin-top: 10px">
                <h1>Lain-lain</h1>
              </div>
              <div class="column is-8">
                <VControl>
                  <VInput type="text" class="input" v-model="input.tmptmasuklainlain" />
                </VControl>
              </div>
            </div>
          </div>

          <div class="column is-12">
            <h1>Obat-obatan yang diberikan selama tindakan :</h1>
          </div>
          <div class="column is-12 is-flex">
            <div class="column is-3">
              <h1>Jam :</h1>
            </div>
            <div class="column is-3">
              <h1>Obat :</h1>
            </div>
            <div class="column is-3">
              <h1>Dosis :</h1>
            </div>
            <div class="column is-3">
              <h1>Cara pemberian :</h1>
            </div>
          </div>
          <div class="column is-3">
            <VField v-for="items in JamObatObatan" :key="items.value">
              <VDatePicker
                v-model="input[items.value]"
                color="green"
                trim-weeks
                mode="Time"
              >
                <template #default="{ inputValue, inputEvents }" class="pb-0">
                  <VField>
                    <VControl icon="feather:calendar">
                      <VInput
                        type="text"
                        placeholder=""
                        :value="inputValue"
                        v-on="inputEvents"
                        class="is-rounded_Z"
                      />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>
          <div class="column is-3">
            <VField v-for="items in ObatObat" :key="items.value">
              <VControl>
                <VInput type="text" class="input" v-model="input[items.value]" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField v-for="items in DosisObat" :key="items.value">
              <VControl>
                <VInput type="text" class="input" v-model="input[items.value]" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField v-for="items in CaraPakai" :key="items.value">
              <VControl>
                <VInput type="text" class="input" v-model="input[items.value]" />
              </VControl>
            </VField>
          </div>

          <div class="column is-12 is-flex ml-5">
            <div class="column is-2 3pt-0 pb-0">
              <h1>Lama Penyinaran :</h1>
            </div>
            <div class="column is-2 pt-0 pb-0">
              <VField addons>
                <VControl>
                  <VInput type="text" class="input" v-model="input.Lamapenyinaran" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>menit</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-2 3pt-0 pb-0">
              <h1>Jenis kontras :</h1>
            </div>
            <div
              class="column is-2 3pt-0 pb-0"
              style="margin-top: -10px; margin-left: -30px"
            >
              <VControl>
                <VInput type="text" class="input" v-model="input.Jeniskontras" />
              </VControl>
            </div>
            <div class="column is-2 3pt-0 pb-0">
              <h1>Volume kontras :</h1>
            </div>
            <div class="column is-2 pt-0 pb-0">
              <VField addons>
                <VControl>
                  <VInput type="text" class="input" v-model="input.Lamapenyinaran" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>ml</VButton>
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 is-flex">
            <div class="column is-1 pt-0 pb-0" style="margin-top: 30px">
              <h1>Kondisi I :</h1>
            </div>
            <div class="column is-2 pt-0 pb-0" style="margin-top: 30px">
              <VControl raw subcontrol>
                <VCheckbox
                  class="p-0"
                  color="primary"
                  square
                  :true-value="KondisiI"
                  v-model="input.KondisiI"
                />
              </VControl>
            </div>
            <div
              class="column is-1 pt-0 pb-0"
              style="margin-top: 30px; margin-left: -90px"
            >
              <h1>Istirahat,</h1>
            </div>
            <div
              class="column is-2 pt-0 pb-0"
              style="margin-top: 30px; margin-left: -30px"
            >
              <VControl raw subcontrol>
                <VCheckbox
                  class="p-0"
                  color="primary"
                  square
                  :true-value="Istirahat"
                  v-model="input.Istirahat"
                />
              </VControl>
            </div>
            <div
              class="column is-2 pt-0 pb-0"
              style="margin-top: 15px; margin-left: -100px"
            >
              <VControl>
                <VInput type="text" class="input" v-model="input.TBistirahat" />
              </VControl>
            </div>
            <div class="column is-2 pt-0 pb-0" style="margin-top: 30px">
              <h1>Kondisi II :</h1>
            </div>
            <div
              class="column is-2 pt-0 pb-0"
              style="margin-top: 30px; margin-left: -90px"
            >
              <VControl raw subcontrol>
                <VCheckbox
                  class="p-0"
                  color="primary"
                  square
                  :true-value="KondisiII"
                  v-model="input.KondisiII"
                />
              </VControl>
            </div>
            <div
              class="column is-2 pt-0 pb-0"
              style="margin-top: 30px; margin-left: -90px"
            >
              <h1>Poat Angio,</h1>
            </div>
            <div
              class="column is-2 pt-0 pb-0"
              style="margin-top: 30px; margin-left: -70px"
            >
              <VControl raw subcontrol>
                <VCheckbox
                  class="p-0"
                  color="primary"
                  square
                  :true-value="PoatAngio"
                  v-model="input.PoatAngio"
                />
              </VControl>
            </div>
            <div
              class="column is-2 pt-0 pb-0"
              style="margin-top: 15px; margin-left: -100px"
            >
              <VControl>
                <VInput type="text" class="input" v-model="input.TBPoatAngio" />
              </VControl>
            </div>
          </div>

          <div class="column is-12 is-flex">
            <table border="1">
              <tr>
                <th><h1>Jam</h1></th>
                <th><h1>Posisi Kateter</h1></th>
                <th><h1>Tekanan/Nadi</h1></th>
                <th><h1>Saturasi</h1></th>
                <th><h1>jam</h1></th>
                <th><h1>Posisi Kateter</h1></th>
                <th><h1>Tekanan/Nadi</h1></th>
                <th><h1>Saturasi</h1></th>
              </tr>
              <tr v-for="(input, index) in input.details" :key="index">
                <td>
                  <div class="column is-12">
                    <VField>
                      <VDatePicker
                        v-model="input.JamIstirahat"
                        color="green"
                        trim-weeks
                        mode="time"
                        is24hr
                      >
                        <template #default="{ inputValue, inputEvents }" class="pb-0">
                          <VField>
                            <VControl icon="feather:calendar">
                              <VInput
                                type="text"
                                placeholder=""
                                :value="inputValue"
                                v-on="inputEvents"
                                class="is-rounded_Z"
                              />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </VField>
                  </div>
                </td>
                <td>
                  <div class="column is-12">
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        v-model="input.PosisiKateterIstirahat"
                      />
                    </VControl>
                  </div>
                </td>
                <td>
                  <div class="column is-12">
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        v-model="input.TekananDarahIstirahat"
                      />
                    </VControl>
                  </div>
                </td>
                <td>
                  <div class="column is-12">
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        v-model="input.SaturasiIstirahat"
                      />
                    </VControl>
                  </div>
                </td>
                <td>
                  <div class="column is-12">
                    <VField>
                      <VDatePicker
                        v-model="input.JamPoat"
                        color="green"
                        trim-weeks
                        mode="Time"
                        is24hr
                      >
                        <template #default="{ inputValue, inputEvents }" class="pb-0">
                          <VField>
                            <VControl icon="feather:calendar">
                              <VInput
                                type="text"
                                placeholder=""
                                :value="inputValue"
                                v-on="inputEvents"
                                class="is-rounded_Z"
                              />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </VField>
                  </div>
                </td>
                <td>
                  <div class="column is-12">
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        v-model="input.PosisiKateterPoat"
                      />
                    </VControl>
                  </div>
                </td>
                <td>
                  <div class="column is-12">
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        v-model="input.TekananDarahPoat"
                      />
                    </VControl>
                  </div>
                </td>
                <td>
                  <div class="column is-12">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.SaturasiPoat" />
                    </VControl>
                  </div>
                </td>
                <td>
                  <div class="column">
                    <VButtons style="justify-content: space-around">
                      <VIconButton
                        type="button"
                        raised
                        circle
                        icon="feather:plus"
                        @click="addNewItem()"
                        color="info"
                        v-tooltip.bubble="'Tambah '"
                      >
                      </VIconButton>
                      <VIconButton
                        class="mt-1"
                        v-if="index > 0"
                        type="button"
                        raised
                        circle
                        icon="feather:trash"
                        @click="removeItem(index)"
                        color="danger"
                      >
                      </VIconButton>
                    </VButtons>
                  </div>
                </td>
              </tr>
            </table>
          </div>

          <div class="column is-12">
            <h1>Komplikasi / Penyulit : (uraikan dengan singkat)</h1>
          </div>
          <div class="column is-12">
            <h1>Kesan Penyadapan : (diagnosa pasca kateterisasi)</h1>
          </div>
          <div class="column is-12">
            <VField>
              <VTextarea rows="2" v-model="input.KompilasiKesan"></VTextarea>
            </VField>
          </div>
          <div class="column is-12">
            <h1>Catatan Pasca Kateterisasi :</h1>
          </div>
          <div class="column is-12 is-flex">
            <div class="column is-3 pt-0 pb-0" style="margin-top: 10px">
              <span>Hemostasis dengan tangan/alat :</span>
            </div>
            <div class="column is-3 pt-0 pb-0">
              <VField addons>
                <VControl>
                  <VInput type="text" class="input" v-model="input.Hemostasis" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>Kg</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-3 pt-0 pb-0" style="margin-top: 10px">
              <span>Hematom :</span>
            </div>
            <div class="column is-3 pt-0 pb-0" style="margin-left: -100px">
              <VField addons>
                <VControl>
                  <VInput type="text" class="input" v-model="input.Hematom" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>cm</VButton>
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-12 is-flex">
            <div class="column is-3 pt-0 pb-0" style="margin-top: 10px">
              <span>Minum :</span>
            </div>
            <div class="column is-3 pt-0 pb-0">
              <VField addons>
                <VControl>
                  <VInput type="text" class="input" v-model="input.Minum" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>ml</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-3 pt-0 pb-0" style="margin-top: 10px">
              <span>Perdarahan :</span>
            </div>
            <div class="column is-3 pt-0 pb-0" style="margin-left: -100px">
              <VField addons>
                <VControl>
                  <VInput type="text" class="input" v-model="input.Perdarahan" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>ml</VButton>
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-12 is-flex">
            <div class="column is-3 pt-0 pb-0" style="margin-top: 10px">
              <span>Makan/makanan ringan :</span>
            </div>
            <div class="column is-3 pt-0 pb-0">
              <VField addons>
                <VControl>
                  <VInput type="text" class="input" v-model="input.makanringan" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>porsi</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-3 pt-0 pb-0" style="margin-top: 10px">
              <span>Urin :</span>
            </div>
            <div class="column is-3 pt-0 pb-0" style="margin-left: -100px">
              <VField addons>
                <VControl>
                  <VInput type="text" class="input" v-model="input.Urin" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>ml</VButton>
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-12 is-flex">
            <div class="column is-3 pt-0 pb-0" style="margin-top: 10px"></div>
            <div class="column is-3 pt-0 pb-0"></div>
            <div class="column is-3 pt-0 pb-0" style="margin-top: 10px">
              <span>Muntah :</span>
            </div>
            <div class="column is-3 pt-0 pb-0" style="margin-left: -100px">
              <VField addons>
                <VControl>
                  <VInput type="text" class="input" v-model="input.Muntah" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>ml</VButton>
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-12">
            <h1>Instruksi perawatan pasca kateterisasi :</h1>
          </div>
          <div class="column is-12">
            <div class="column is-6">
              <VControl raw subcontrol>
                <VCheckbox
                  class="p-0"
                  color="primary"
                  square
                  :true-value="Awasi"
                  label="Awasi kesadaran, hematom dan perdarahan"
                  v-model="input.Awasi"
                />
              </VControl>
            </div>
          </div>
          <div class="column is-12">
            <div class="column is-8">
              <VControl raw subcontrol>
                <VCheckbox
                  class="p-0"
                  color="primary"
                  square
                  :true-value="ObsTekanandarah"
                  label="Observasi : Tekanan darah, nadi, pernapasan, pengeluaran urin, reaksi alergi"
                  v-model="input.ObsTekanandarah"
                />
              </VControl>
            </div>
          </div>
          <div class="column is-12 is-flex">
            <div class="column is-6">
              <VControl raw subcontrol>
                <VCheckbox
                  class="p-0"
                  color="primary"
                  square
                  :true-value="ObsiArteri"
                  label="Observasi dan tandai pulsasi arteri"
                  v-model="input.ObsArteri"
                />
              </VControl>
            </div>
            <div class="column is-6">
              <VControl>
                <VInput type="text" class="input" v-model="input.TbObsArteri" />
              </VControl>
            </div>
          </div>
          <div class="column is-12 is-flex">
            <div class="column is-6">
              <VControl raw subcontrol>
                <VCheckbox
                  class="p-0"
                  color="primary"
                  square
                  :true-value="ObsiArtikulasi"
                  label="Observasi artikulasi ke bagian distal daerah punksi"
                  v-model="input.ObsiArtikulasi"
                />
              </VControl>
            </div>
          </div>
          <div class="column is-12 is-flex">
            <div class="column is-8">
              <VControl raw subcontrol>
                <VCheckbox
                  class="p-0"
                  color="primary"
                  square
                  :true-value="Hemoptasis"
                  label="Hemoptasis bantal pasir/radial band 3-4 jam di atas daerah punksi pasca pencabutan sheath"
                  v-model="input.Hemoptasis"
                />
              </VControl>
            </div>
            <div class="column is-2">
              <span>Sampai dengan jam</span>
            </div>
            <div class="column is-2" style="margin-top: -10px">
              <VField>
                <VDatePicker
                  v-model="input.JamHemoptasis"
                  color="green"
                  trim-weeks
                  mode="Time"
                >
                  <template #default="{ inputValue, inputEvents }" class="pb-0">
                    <VField>
                      <VControl icon="feather:calendar">
                        <VInput
                          type="text"
                          placeholder=""
                          :value="inputValue"
                          v-on="inputEvents"
                          class="is-rounded_Z"
                        />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
          </div>
          <div class="column is-12 is-flex">
            <div class="column is-8">
              <VControl raw subcontrol>
                <VCheckbox
                  class="p-0"
                  color="primary"
                  square
                  :true-value="Immobilisasi"
                  label="Immobilisasi ekstremitas kanan/kiri atas/bawah 8/11 jam pasca pencabutan sheath"
                  v-model="input.Immobilisasi"
                />
              </VControl>
            </div>
            <div class="column is-2">
              <span>Sampai dengan jam</span>
            </div>
            <div class="column is-2" style="margin-top: -10px">
              <VField>
                <VDatePicker
                  v-model="input.JamImmobilisasi"
                  color="green"
                  trim-weeks
                  mode="Time"
                >
                  <template #default="{ inputValue, inputEvents }" class="pb-0">
                    <VField>
                      <VControl icon="feather:calendar">
                        <VInput
                          type="text"
                          placeholder=""
                          :value="inputValue"
                          v-on="inputEvents"
                          class="is-rounded_Z"
                        />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
          </div>
          <div class="column is-12 is-flex">
            <div class="column is-2">
              <VControl raw subcontrol>
                <VCheckbox
                  class="p-0"
                  color="primary"
                  square
                  :true-value="Makan"
                  label="Makan"
                  v-model="input.Makan"
                />
              </VControl>
            </div>
            <div class="column is-4" style="margin-left: -50px; margin-top: -10px">
              <VControl>
                <VInput type="text" class="input" v-model="input.TbObsArteri" />
              </VControl>
            </div>
            <div class="column is-2">
              <VControl raw subcontrol>
                <VCheckbox
                  class="p-0"
                  color="primary"
                  square
                  :true-value="Minum"
                  label="Minum"
                  v-model="input.Minum"
                />
              </VControl>
            </div>
            <div class="column is-4" style="margin-left: -50px; margin-top: -10px">
              <VControl>
                <VInput type="text" class="input" v-model="input.TbObsArteri" />
              </VControl>
            </div>
          </div>
          <div class="column is-12">
            <div class="column is-6">
              <VControl raw subcontrol>
                <VCheckbox
                  class="p-0"
                  color="primary"
                  square
                  :true-value="GantiPenutup"
                  label="Ganti penutup luka tiap 24 jam atausetiap diperlukan"
                  v-model="input.GantiPenutup"
                />
              </VControl>
            </div>
          </div>
          <div class="column is-6">
            <span>Obat-obatan</span>
          </div>
          <div class="column is-6">
            <span>EKG :</span>
          </div>
          <div class="column is-12 is-flex">
            <div class="column is-1">
              <span>1.</span>
            </div>
            <div class="column is-5" style="margin-top: -10px">
              <VControl>
                <VInput type="text" class="input" v-model="input.Obatobatan1" />
              </VControl>
            </div>
            <div class="column is-1">
              <span>Jam :</span>
            </div>
            <div class="column is-3" style="margin-top: -10px">
              <VField>
                <VDatePicker v-model="input.JamEKG" color="green" trim-weeks mode="Time">
                  <template #default="{ inputValue, inputEvents }" class="pb-0">
                    <VField>
                      <VControl icon="feather:calendar">
                        <VInput
                          type="text"
                          placeholder=""
                          :value="inputValue"
                          v-on="inputEvents"
                          class="is-rounded_Z"
                        />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
          </div>
          <div class="column is-12 is-flex">
            <div class="column is-1">
              <span>2.</span>
            </div>
            <div class="column is-5" style="margin-top: -10px">
              <VControl>
                <VInput type="text" class="input" v-model="input.Obatobatan2" />
              </VControl>
            </div>
            <div class="column is-1">
              <span>Jam :</span>
            </div>
            <div class="column is-3" style="margin-top: -10px">
              <VField>
                <VDatePicker v-model="input.JamEKG2" color="green" trim-weeks mode="Time">
                  <template #default="{ inputValue, inputEvents }" class="pb-0">
                    <VField>
                      <VControl icon="feather:calendar">
                        <VInput
                          type="text"
                          placeholder=""
                          :value="inputValue"
                          v-on="inputEvents"
                          class="is-rounded_Z"
                        />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
          </div>
          <div class="column is-12 is-flex">
            <div class="column is-1">
              <span>3.</span>
            </div>
            <div class="column is-5" style="margin-top: -10px">
              <VControl>
                <VInput type="text" class="input" v-model="input.Obatobatan3" />
              </VControl>
            </div>
            <div class="column is-1">
              <span>Jam :</span>
            </div>
            <div class="column is-3" style="margin-top: -10px">
              <VField>
                <VDatePicker v-model="input.JamEKG3" color="green" trim-weeks mode="Time">
                  <template #default="{ inputValue, inputEvents }" class="pb-0">
                    <VField>
                      <VControl icon="feather:calendar">
                        <VInput
                          type="text"
                          placeholder=""
                          :value="inputValue"
                          v-on="inputEvents"
                          class="is-rounded_Z"
                        />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
          </div>
          <div class="column is-12 is-flex">
            <div class="column is-1">
              <span>4.</span>
            </div>
            <div class="column is-5" style="margin-top: -10px">
              <VControl>
                <VInput type="text" class="input" v-model="input.Obatobatan4" />
              </VControl>
            </div>
            <div class="column is-2">
              <span>Foto rontgen :</span>
            </div>
            <div class="column is-3" style="margin-top: -10px">
              <VControl>
                <VInput type="text" class="input" v-model="input.ISISENDIRI" />
              </VControl>
            </div>
          </div>
          <div class="column is-12 is-flex">
            <div class="column is-1">
              <span>5.</span>
            </div>
            <div class="column is-5" style="margin-top: -10px">
              <VControl>
                <VInput type="text" class="input" v-model="input.Obatobatan5" />
              </VControl>
            </div>
            <div class="column is-1">
              <span>Jam :</span>
            </div>
            <div class="column is-2" style="margin-top: -10px">
              <VField>
                <VDatePicker v-model="input.JamEKG5" color="green" trim-weeks mode="Time">
                  <template #default="{ inputValue, inputEvents }" class="pb-0">
                    <VField>
                      <VControl icon="feather:calendar">
                        <VInput
                          type="text"
                          placeholder=""
                          :value="inputValue"
                          v-on="inputEvents"
                          class="is-rounded_Z"
                        />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
            <div class="column is-1">
              <span>Posisi :</span>
            </div>
            <div class="column is-2" style="margin-top: -10px">
              <VControl>
                <VInput type="text" class="input" v-model="input.Posisi1" />
              </VControl>
            </div>
          </div>
          <div class="column is-12 is-flex">
            <div class="column is-1">
              <span>6.</span>
            </div>
            <div class="column is-5" style="margin-top: -10px">
              <VControl>
                <VInput type="text" class="input" v-model="input.Obatobatan6" />
              </VControl>
            </div>
            <div class="column is-1">
              <span>Jam :</span>
            </div>
            <div class="column is-2" style="margin-top: -10px">
              <VField>
                <VDatePicker v-model="input.JamEKG6" color="green" trim-weeks mode="Time">
                  <template #default="{ inputValue, inputEvents }" class="pb-0">
                    <VField>
                      <VControl icon="feather:calendar">
                        <VInput
                          type="text"
                          placeholder=""
                          :value="inputValue"
                          v-on="inputEvents"
                          class="is-rounded_Z"
                        />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
            <div class="column is-1">
              <span>Posisi :</span>
            </div>
            <div class="column is-2" style="margin-top: -10px">
              <VControl>
                <VInput type="text" class="input" v-model="input.Posisi1" />
              </VControl>
            </div>
          </div>

          <div class="column is-12 is-flex ml-5 justify-content-between">
            <div class="column is-6"></div>
            <div class="column is-6" style="text-align: center">
              <h1 style="font-weight: bold">Operator :</h1>
              <VField>
                <VControl>
                  <AutoComplete
                    v-model="input.petugasAddmision"
                    :suggestions="d_Pegawai"
                    @complete="fetchPegawai($event)"
                    :optionLabel="'label'"
                    :dropdown="true"
                    :minLength="3"
                    :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'"
                    :field="'label'"
                  />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-12 is-flex ml-5 justify-content-between">
            <div class="column is-6"></div>
            <div class="column is-6" style="text-align: center">
              <h1 style="font-weight: bold">Ners :</h1>
              <VField>
                <VControl>
                  <AutoComplete
                    v-model="input.Ners"
                    :suggestions="d_Pegawai"
                    @complete="fetchPegawai($event)"
                    :optionLabel="'label'"
                    :dropdown="true"
                    :minLength="3"
                    :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'"
                    :field="'label'"
                  />
                </VControl>
              </VField>
            </div>
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
import AutoComplete from 'primevue/autocomplete'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import * as EMR from '../page-emr-plugins/lembaran-penyiaran-radioterapi'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let JenisKelamin = ref(EMR.JenisKelamin())
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
const isStuck = computed(() => {
  return y.value > 30
})
const isLoading: any = ref(false)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
})
const d_ruangan: any = ref([])
const d_agama: any = ref([])
const d_Dokter: any = ref([])
const d_Pegawai: any = ref([])
const d_allantest: any = ref([
  { value: 1, label: 'lengan kanan' },
  { value: 2, label: 'kiri' },
  { value: 3, label: '(+/-)' },
])
const d_ekg: any = ref([
  { value: 1, label: 'SR' },
  { value: 2, label: 'AVB' },
  { value: 3, label: 'AF' },
])
const d_jam: any = ref([
  { value: 1, label: 'IV' },
  { value: 2, label: 'IM' },
  { value: 3, label: 'SL' },
])
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  details: [
    {
      no: 1,
      JamIstirahat: new Date().setHours(0, 0, 0, 0),
      JamPoat: new Date().setHours(0, 0, 0, 0),
    },
  ],
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const user = useUserSession().getUser().pegawai

// List Pilihan
const Reguler: any = ref([
  { label: 'Reguler', value: 'Reguler' },
  { label: 'Ireguler', value: 'Ireguler' },
])
const EKG: any = ref([
  { label: 'SR', value: 'SR' },
  { label: 'AVB', value: 'AVB' },
  { label: 'AF', value: 'AF' },
])
const Allantest: any = ref([
  { label: 'Lengan Kanan', value: 'Lengan Kanan' },
  { label: 'Kiri', value: 'Kiri' },
  { label: '(+/-)', value: '(+/-)' },
])
const hubunganPasien: any = ref([
  { label: 'Diri sendiri', value: 'Diri sendiri' },
  { label: 'Ayah', value: 'Ayah' },
  { label: 'Ibu', value: 'Ibu' },
  { label: 'Istri', value: 'Istri' },
  { label: 'Suami', value: 'Suami' },
  { label: 'Anak', value: 'Anak' },
])
const jenisTindakan: any = ref([
  { label: 'Peny. Jantung kanan', value: 'Peny. Jantung kanan' },
  { label: 'Peny. Jantung kiri', value: 'Peny. Jantung kiri' },
  { label: 'Septostomi', value: 'Septostomi' },
  {
    label: 'Valvuloplasti (Mitral, Pulmonal, Aorta)',
    value: 'Valvuloplasti (Mitral, Pulmonal, Aorta)',
  },
  { label: 'Elektrofisiologi', value: 'Elektrofisiologi' },
  { label: 'Ablasi', value: 'Ablasi' },
  { label: 'ADO', value: 'ADO' },
  { label: 'ASO', value: 'ASO' },
  { label: 'AMVO', value: 'AMVO' },
  { label: 'Koronarografi', value: 'Koronarografi' },
  { label: 'DSA', value: 'DSA' },
  { label: 'Pericardiosintesis', value: 'Pericardiosintesis' },
])
const jenisTindakan2: any = ref([
  { label: 'Primary PCI', value: 'Primary PCI' },
  { label: 'Elektif PCI', value: 'Elektif PCI' },
  { label: 'Stanby PCI', value: 'Stanby PCI' },
  { label: 'TACI / TACE / TAE', value: 'TACI / TACE / TAE' },
  { label: 'LV-Grafi', value: 'LV-Grafi' },
  { label: 'RV-Grafi', value: 'RV-Grafi' },
  { label: 'PA-Grafi', value: 'PA-Grafi' },
  { label: 'AO-Grafi', value: 'AO-Grafi' },
  { label: 'TPM', value: 'TPM' },
  { label: 'PPM', value: 'PPM' },
  { label: 'Arteriografi / Venografi', value: 'Arteriografi / Venografi' },
])
const jenisTindakan3: any = ref([
  { label: 'Perkutan', value: 'Perkutan' },
  { label: 'Cut-down', value: 'Cut-down' },
  { label: 'Lain-lain', value: 'Lain-lain' },
])
const JamObatObatan: any = ref([
  { value: 'JamObat1' },
  { value: 'JamObat2' },
  { value: 'JamObat3' },
  { value: 'JamObat4' },
  { value: 'JamObat5' },
  { value: 'JamObat6' },
  { value: 'JamObat7' },
  { value: 'JamObat8' },
  { value: 'JamObat9' },
])
const ObatObat: any = ref([
  { value: 'Obat1' },
  { value: 'Obat2' },
  { value: 'Obat3' },
  { value: 'Obat4' },
  { value: 'Obat5' },
  { value: 'Obat6' },
  { value: 'Obat7' },
  { value: 'Obat8' },
  { value: 'Obat9' },
])
const DosisObat: any = ref([
  { value: 'DosisObat1' },
  { value: 'DosisObat2' },
  { value: 'DosisObat3' },
  { value: 'DosisObat4' },
  { value: 'DosisObat5' },
  { value: 'DosisObat6' },
  { value: 'DosisObat7' },
  { value: 'DosisObat8' },
  { value: 'DosisObat9' },
])
const CaraPakai: any = ref([
  { value: 'CaraPakaiObat1' },
  { value: 'CaraPakaiObat2' },
  { value: 'CaraPakaiObat3' },
  { value: 'CaraPakaiObat4' },
  { value: 'CaraPakaiObat5' },
  { value: 'CaraPakaiObat6' },
  { value: 'CaraPakaiObat7' },
  { value: 'CaraPakaiObat8' },
  { value: 'CaraPakaiObat9' },
])

const loadRiwayat = () => {
  // if (NOREC_EMRPASIEN.value == '') return
  useApi()
    .get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    )
    .then((response: any) => {
      if (response.length) {
        input.value = response[0] //set ke inputan
        if (response[0].tandaTanganPerawat) {
          H.tandaTangan().set('signature_1', response[0].tandaTanganPerawat)
        }
        if (response[0].tandaTanganPasien) {
          H.tandaTangan().set('signature_2', response[0].tandaTanganPasien)
        }
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      }
    })
}
const getEmr = () => {
    // Pertama coba ambil dari CPPT
    useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN +
                "&norec_pd=" + NOREC_PD +
                "&collection=CPPTDetail" +
                "&flag=dokter" +
                "&ruangan=" + props.registrasi.namaruangan +
                "&field=A").then((cpptResponse) => {
        if (cpptResponse != null && cpptResponse.A) {
            input.value.diagnosaPra = cpptResponse.A;
            H.alert("success", "Data diambil dari CPPT");
        } else {
            // Jika CPPT kosong, ambil dari EMR biasa (Asesmen Medis Rawat Jalan)
            useApi().get(`emr/auto-fill?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=AsesmenMedisRawatJalan&field=TADiagnosa,MOI,diagnosaIcd10`).then((emrResponse) => {
                if (emrResponse != null) {
                    const dataDiagnosa = emrResponse.TADiagnosa || null;
                    if (dataDiagnosa) {
                        input.value.diagnosaPra = dataDiagnosa;
                        H.alert("success", "Data diambil dari Asesmen Medis Rawat Jalan");
                    }
                }
            });
        }
    });
};
const fetchAgama = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/agama_m?select=id,agama&param_search=agama&query=${filter.query}&limit=10`
  )
  d_agama.value = response
}
const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  )
  d_ruangan.value = response
}
const fetchDokter = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  )
  d_Dokter.value = response
}
const setTandaTangan = async (e: any) => {
  const response = await useApi().get(`/emr/tanda-tangan/${e.value.value}`)
  if (response != null) {
    H.tandaTangan().set('signature_1', response.ttd)
    input.value.tandaTanganPerawat = response.ttd
  } else {
    H.tandaTangan().set('signature_1', '')
  }
}

const fetchPegawai = async (filter: any) => {
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    )
    .then((response) => {
      d_Pegawai.value = response
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.tandaTanganPerawat = H.tandaTangan().get('signature_1')
  object.tandaTanganPasien = H.tandaTangan().get('signature_2')
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: props.FORM_URL,
    name_form: props.FORM_NAME,
    jenis_emr: 'asesmen_medis',
    data: object,
  }
  isLoading.value = true
  useApi()
    .post(`/emr/simpan-emr`, json)
    .then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}

const kembaliKeun = () => {
  window.history.back()
}

const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien
  input.value.jeniskelamin = props.pasien.jeniskelamin
  input.value.norm = props.pasien.nocm
  input.value.telepon = props.pasien.nohp
  input.value.alamatPasien = props.pasien.alamatlengkap
  input.value.dokterRawat = props.registrasi.dokter
  input.value.pihakPembayar = props.registrasi.kelompokpasien
  input.value.tanggalLahirPasien = props.pasien.tgllahir
  input.value.ruangan = props.registrasi.namaruangan
  input.value.carabayar = props.registrasi.carabayar
  input.value.tanggalAdmission = new Date()
  input.value.tglPembuatan = new Date()
  input.value.tanggalTindakan = new Date()
  input.value.jamPremedaksi1 = new Date()
  input.value.jamPremedaksi2 = new Date()
  input.value.jamPremedaksi3 = new Date()
  input.value.JamTindakan1 = new Date()
  input.value.JamTindakan2 = new Date()
  input.value.JamObat1 = new Date()
  input.value.JamObat2 = new Date()
  input.value.JamObat3 = new Date()
  input.value.JamObat4 = new Date()
  input.value.JamObat5 = new Date()
  input.value.JamObat6 = new Date()
  input.value.JamObat7 = new Date()
  input.value.JamObat8 = new Date()
  input.value.JamObat9 = new Date()
  input.value.JamIstirahat = new Date()
  input.value.JamPoat = new Date()
  input.value.JamHemoptasis = new Date()
  input.value.JamImmobilisasi = new Date()
  input.value.JamEKG = new Date()
  input.value.JamEKG2 = new Date()
  input.value.JamEKG3 = new Date()
  input.value.JamEKG4 = new Date()
  input.value.JamEKG5 = new Date()
  input.value.JamEKG6 = new Date()
  input.value.petugasAddmision = { label: user.namaLengkap, value: user.id }
  input.value.DDDokter = {
    label: props.registrasi.dokter,
    value: props.registrasi.iddokter,
  }
  const response_AsmedRajal = await useApi().get(
    'emr/auto-fill?nocmfk=' +
      ID_PASIEN +
      '&norec_pd=' +
      NOREC_PD +
      '&collection=AsesmenMedisRawatJalan' +
      `&field=TADiagnosa`
  )
  if (response_AsmedRajal != null) {
    input.value.diagnosa = response_AsmedRajal.TADiagnosa
  }
}

const addNewItem = () => {
  let newItem: any = {}
  newItem = {
    no: input.value.details[input.value.details.length - 1].no + 1,
    JamIstirahat: new Date().setHours(0, 0, 0, 0),
    JamPoat: new Date().setHours(0, 0, 0, 0),
  }
  input.value.details.push(newItem)
}
const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
}

setView()
setAutoFill()
loadRiwayat()
getEmr()
</script>
<style>
#signature {
  border: double 3px transparent;
  border-radius: 5px;
  background-image: linear-gradient(white, white),
    radial-gradient(circle at top left, #4bc5e8, #9f6274);
  background-origin: border-box;
  background-clip: content-box, border-box;
}

.container {
  width: '100%';
  padding: 8px 16px;
}

.buttons {
  display: flex;
  gap: 8px;
  justify-content: center;
  margin-top: 8px;
}
</style>
