<style lang="scss">
  td {
    height: 3rem !important;
    vertical-align: middle !important;
    padding: 3px !important;
    padding-bottom: 1px !important;
    padding-top: 1px !important;
}
</style>
<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>PROTOKOL KONKUREN KEMORADIOTERAPI</h3>
          </div>
          <div class="right">
            <div class="buttons">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @kembaliKeun="kembaliKeun" isHideST></ButtonEmr>
            </div>
          </div>
        </div>
      </div>

      <div class="columns is-multiline p-2">
        <VCard>
            <TabView :scrollable="true" class="skuy">
              <TabPanel header="Halaman 1">
                <div class="column is-12">
                  <Fieldset>
                      <div class="columns is-multiline">
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-2">
                            <h1>TB/BB/ BSA :</h1>
                          </div>
                          <div class="column is-2">
                            <VField addons>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TB" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>cm</VButton>
                                </VControl>
                            </VField>
                        </div>
                          <div class="column is-2">
                            <VField addons>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.BB" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>kg</VButton>
                                </VControl>
                            </VField>
                        </div>
                          <div class="column is-2">
                            <VField addons>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.BSA" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>m<sup>2</sup></VButton>
                                </VControl>
                            </VField>
                        </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-2">
                            <h1>Siklus/Total :</h1>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl>
                                <VInput v-model="input.Siklustotal" class="input" type="text" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                              <div class="column is-2">
                                <h1>Diagnosis :</h1>
                              </div>
                              <div class="column is-10">
                                <VField>
                                  <VControl>
                                    <VInput v-model="input.DiagnosisHal1" class="input" type="text" />
                                  </VControl>
                                </VField>
                              </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-2">
                            <h1>Patologi Anatomi :</h1>
                          </div>
                          <div class="column is-10">
                            <VField>
                              <VControl>
                                <VInput v-model="input.PatologiAnatomiHal1 " class="input" type="text" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-2">
                            <h1>Stadium :</h1>
                          </div>
                          <div class="column is-10">
                            <VField>
                              <VControl>
                                <VInput v-model="input.StadiumHal1" class="input" type="text" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-12">
                            <h1>Regimen Kemoterapi: <b>Neo-Adjuvant Kemoterapi</b></h1>
                          </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-6">
                            <h1><b>Paclitaxel	: 175 mg/m2</b></h1>
                          </div>
                          <div class="column is-3">
                            <h1><b>i.v.</b></h1>
                          </div>
                          <div class="column is-3">
                            <h1><b>d 1</b></h1>
                          </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-6">
                            <h1><b>haloxan	: 1000 mg/m2/hari</b></h1>
                          </div>
                          <div class="column is-3">
                            <h1><b>i.v.</b></h1>
                          </div>
                          <div class="column is-3">
                            <h1><b>d 1-3</b></h1>
                          </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-6">
                            <h1><b>Carboplatin	: AUC = 6</b></h1>
                          </div>
                          <div class="column is-3">
                            <h1><b>i.v.</b></h1>
                          </div>
                          <div class="column is-3">
                            <h1><b>d 1</b></h1>
                          </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-6">
                            <h1><b>Mesna : Total 60% dosis Ifosfamide / hari</b></h1>
                          </div>
                          <div class="column is-3">
                            <h1><b>i.v.</b></h1>
                          </div>
                          <div class="column is-3">
                            <h1><b>d 1-3</b></h1>
                          </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <table width="100%" border="1" style="padding: 5px;">
                              <tr class="has-text-centered" style="background-color:rgb(77, 160, 224);">
                                  <th><b>WAKTU</b></th>
                                  <th><b>RINCIAN</b></th>
                              </tr>
                              <tr>
                                <td colspan="2"><b>Hari Pertama:</b></td>
                              </tr>
                              <tr>
                                <td width="20%">07:00 - 09:00</td>
                                <td>Pre-hidrasi dengan 1 liter NaCl/Dextrose selama 2 jam</td>
                              </tr>
                              <tr>
                                <td>09:00 - 09:30</td>
                                <td>
                                  <div class="content">
                                    <p>Pre-medikasi:</p>
                                    <ul style="">
                                      <li>Untuk paclitaxel</li>
                                      <li>Dexamethasone 20mg i.v</li>
                                      <li>Ondansetrone 8mg i.v</li>
                                      <li>Diphenhydramine HCl 50mg i.v</li>
                                    </ul>
                                  </div>
                                </td>
                              </tr>
                              <tr>
                                <td>09:30 - 09:45</td>
                                <td>
                                  <div class="columns is-multiline">
                                    <div class="column is-12 pt-0" style="margin-top: 10px;">
                                      <p>Infus singkat Mesna dalam 50ml cairan NaCl/Dextrose selama 15 menit</p>
                                    </div>
                                    <div class="column is-6 pt-0">
                                      <p>Dengan dosis Mesna = 20% dari dosis Ifosfamide</p>
                                    </div>
                                    <div class="column is-1 pt-0">Mesna</div>
                                    <div class="column is-2 pt-0">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="input.mesna1" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mg</VButton>
                                          </VControl>
                                      </VField>
                                  </div>
                                  </div>
                                </td>
                              </tr>
                              <tr>
                                <td>09:45 - 11:45</td>
                                <td>
                                  Infus <strong>haloxan</strong> mg dalam 500ml cairan NaCl/Dextrose selama 2 jam
                                </td>
                              </tr>
                              <tr>
                                <td>11:45 - 12:15</td>
                                <td>
                                  <div class="columns is-multiline">
                                    <div class="column is-12 pt-0" style="margin-top: 10px;">
                                     <p>Infus Mesna dalam 50ml cairan NaCl/Dextrose selama 30 menit.</p>
                                    </div>
                                    <div class="column is-6 pt-0">
                                      Dengan dosis Mesna = 20% dari dosis Ifosfamide
                                    </div>
                                    <div class="column is-1 pt-0">Mesna</div>
                                    <div class="column is-2 pt-0">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="input.mesna2" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mg</VButton>
                                          </VControl>
                                      </VField>
                                  </div>
                                    </div>
                                </td>
                              </tr>
                              <tr>
                                <td>15:45 - 16:15</td>
                                <td>
                                  <div class="columns is-multiline">
                                    <div class="column is-12 pt-0" style="margin-top: 10px;">
                                     <p>Infus Mesna dalam 50ml cairan NaCl/Dextrose selama 30 menit.</p>
                                    </div>
                                    <div class="column is-6 pt-0">
                                      Dengan dosis Mesna = 20% dari dosis Ifosfamide
                                    </div>
                                    <div class="column is-1 pt-0">Mesna</div>
                                    <div class="column is-2 pt-0">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="input.mesna3" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mg</VButton>
                                          </VControl>
                                      </VField>
                                    </div>
                                  </div>
                                </td>
                              </tr>
                              <tr>
                                <td>16:15 - 19:15</td>
                                <td>
                                  <div class="columns is-multiline">
                                    <div class="column is-1 pt-0" style="margin-top: 10px;">Infus</div>
                                    <div class="column is-1 pt-0" style="margin-top: 10px;">Paclitaxel</div>
                                    <div class="column is-2 pt-0" style="margin-top: 10px;"> 
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="input.Paclitaxel" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mg</VButton>
                                          </VControl>
                                      </VField>
                                    </div>
                                    <div class="column is-5 pt-0" style="margin-top: 10px;">dalam 1 liter NaCl/Dextrose selama 3 jam</div>
                                  </div>
                                </td>
                              </tr>
                              <tr>
                                <td>19:15 - 19:45</td>
                                <td>
                                  Infus Carboplatin     mg dalam 250ml NaCl/Dextrose selama 30 menit
                                </td>
                              </tr>
                              <tr>
                                <td colspan="2"><b>Hari Kedua-Ketiga</b></td>
                              </tr>
                              <tr>
                                <td>07:00 - 09:00</td>
                                <td>Pre-hidrasi dengan 1 liter NaCl/Dextrose selama 2 jam</td>
                              </tr>
                              <tr>
                                <td>09:00 - 09:30</td>
                                <td>
                                  <div class="content">
                                    <p>Pre-medikasi</p>
                                    <ul>
                                      <li>Dexamethasone 20mg i.v</li>
                                      <li>Ondansetrone 8mg i.v</li>
                                    </ul>
                                  </div>
                                </td>
                              </tr>
                              <tr>
                                <td>09:30 - 09:45</td>
                                <td>
                                  <div class="columns is-multiline">
                                    <div class="column is-12 pt-0" style="margin-top: 10px;">
                                      <p>Infus singkat Mesna dalam 50ml cairan NaCl/Dextrose selama 15 menit</p>
                                    </div>
                                    <div class="column is-6 pt-0">
                                      <p>Dengan dosis Mesna = 20% dari dosis Ifosfamide</p>
                                    </div>
                                    <div class="column is-1 pt-0">Mesna</div>
                                    <div class="column is-2 pt-0">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="input.mesna4" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mg</VButton>
                                          </VControl>
                                      </VField>
                                  </div>
                                  </div>
                                </td>
                              </tr>
                              <tr>
                                <td>09:45 - 11:45</td>
                                <td>
                                  Infus <strong>haloxan</strong>      mg dalam 500ml cairan NaCl/Dextrose selama 2 jam
                                </td>
                              </tr>
                              <tr>
                                <td>11:45 - 12:15</td>
                                <td>
                                  <div class="columns is-multiline">
                                    <div class="column is-12 pt-0" style="margin-top: 10px;">
                                      <p>Infus singkat Mesna dalam 50ml cairan NaCl/Dextrose selama 30 menit</p>
                                    </div>
                                    <div class="column is-6 pt-0">
                                      <p>Dengan dosis Mesna = 20% dari dosis Ifosfamide</p>
                                    </div>
                                    <div class="column is-1 pt-0">Mesna</div>
                                    <div class="column is-2 pt-0">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="input.mesna5" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mg</VButton>
                                          </VControl>
                                      </VField>
                                  </div>
                                  </div>
                                </td>
                              </tr>
                              <tr>
                                <td>15:45 - 16:15</td>
                                <td>
                                  <div class="columns is-multiline">
                                    <div class="column is-12 pt-0" style="margin-top: 10px;">
                                      <p>Infus singkat Mesna dalam 50ml cairan NaCl/Dextrose selama 30 menit</p>
                                    </div>
                                    <div class="column is-6 pt-0">
                                      <p>Dengan dosis Mesna = 20% dari dosis Ifosfamide</p>
                                    </div>
                                    <div class="column is-1 pt-0">Mesna</div>
                                    <div class="column is-2 pt-0">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="input.mesna6" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mg</VButton>
                                          </VControl>
                                      </VField>
                                  </div>
                                  </div>
                                </td>
                              </tr>
                          </table>
                        </div>
                        <div class="column is-12 is-flex ml-5 justify-content-end">
                          <div class="column is-4" style="text-align: center">
                            <h1 style="font-weight: bold" class="mr-5">DPJP</h1>
                            <TandaTangan :elemenID="'TTDdokter1'" :width="'150'" :height="'150'" class="dek" />
                            <VField>
                              <VControl>
                                <AutoComplete v-model="input.DDDokter1" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                  :loadingIcon="'pi pi-spinner'" :field="'label'" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </div>
                  </Fieldset>
                </div>
              </TabPanel>
              <TabPanel header="Halaman 2">
                  <div class="column is-12">
                    <div class="is-12">
                      <Fieldset>
                        <div class="columns is-multiline">
                          <div class="column is-12 is-flex ml-5">
                            <div class="column is-2">
                              <h1>TB/BB/ BSA :</h1>
                            </div>
                            <div class="column is-2">
                              <VField addons>
                                  <VControl>
                                      <VInput type="text" class="input" v-model="input.TB" />
                                  </VControl>
                                  <VControl class="field-addon-body">
                                      <VButton static>cm</VButton>
                                  </VControl>
                              </VField>
                          </div>
                            <div class="column is-2">
                              <VField addons>
                                  <VControl>
                                      <VInput type="text" class="input" v-model="input.BB" />
                                  </VControl>
                                  <VControl class="field-addon-body">
                                      <VButton static>kg</VButton>
                                  </VControl>
                              </VField>
                          </div>
                            <div class="column is-2">
                              <VField addons>
                                  <VControl>
                                      <VInput type="text" class="input" v-model="input.BSA" />
                                  </VControl>
                                  <VControl class="field-addon-body">
                                      <VButton static>m<sup>2</sup></VButton>
                                  </VControl>
                              </VField>
                          </div>
                          </div>
                          <div class="column is-12 is-flex ml-5">
                            <div class="column is-2">
                              <h1>Siklus/Total :</h1>
                            </div>
                            <div class="column is-3">
                              <VField>
                                <VControl>
                                  <VInput v-model="input.Siklustotal" class="input" type="text" />
                                </VControl>
                              </VField>
                            </div>
                          </div>
                          <div class="column is-12 is-flex ml-5">
                            <div class="column is-2">
                              <h1>Diagnosis :</h1>
                            </div>
                            <div class="column is-10">
                              <VField>
                                <VControl>
                                  <VInput v-model="input.Diagnosis" class="input" type="text" />
                                </VControl>
                              </VField>
                            </div>
                          </div>
                          <div class="column is-12 is-flex ml-5">
                            <div class="column is-2">
                              <h1>Patologi Anatomi :</h1>
                            </div>
                            <div class="column is-10">
                              <VField>
                                <VControl>
                                  <VInput v-model="input.PatologiAnatomi " class="input" type="text" />
                                </VControl>
                              </VField>
                            </div>
                          </div>
                          <div class="column is-12 is-flex ml-5">
                            <div class="column is-2">
                              <h1>Stadium :</h1>
                            </div>
                            <div class="column is-10">
                              <VField>
                                <VControl>
                                  <VInput v-model="input.Stadium" class="input" type="text" />
                                </VControl>
                              </VField>
                            </div>
                          </div>
                          <div class="column is-12 is-flex ml-5">
                            <div class="column is-12">
                              <h1>Regimen Kemoterapi: <b>Cisplatin</b></h1>
                            </div>
                          </div>
                          
                          <div class="column is-12 is-flex ml-5">
                            <h1><b>Premedikasi</b></h1>
                          </div>
                          <div class="column is-12 is-flex ml-5">
                              <span>Deksametason 1 amp</span>
                            </div>
                          <div class="column is-12 is-flex ml-5">
                              <span>Ondansentron 1 amp</span>
                            </div>
                          <div class="column is-12 is-flex ml-5">
                              <span><b>Hidrasi sebelum pemberian cisplatin</b>:  Hidrasi 1000 ml NaCl 0,9% sebelum pemberian cisplatin selama ± 2 jam</span>
                            </div>
                          <div class="column is-12 is-flex ml-5">
                            <h1><b>Kemoterapi Cisplatin</b></h1>
                          </div>
                          <div class="column is-12 is-flex ml-5">
                              <span>Kemoterapi cisplatin 40 mg diberikan intravena dalam 500 ml cairan NaCl 0,9% selama 60 menit setiap 1 minggu selama pemberian radioterapi</span>
                            </div>
                          <div class="column is-12 is-flex ml-5">
                              <span><b>Hidrasi setelah pemberian cisplatin</b>:  berikan hidrasi NaCl 0,9% 1000 mL per infus</span>
                            </div>


                          <div class="column is-12 is-flex ml-5 justify-content-end">
                            <div class="column is-4" style="text-align: center">
                              <h1 style="font-weight: bold" class="mr-5">DPJP</h1>
                              <TandaTangan :elemenID="'TTDdokter2'" :width="'150'" :height="'150'" class="dek" />
                              <VField>
                                <VControl>
                                  <AutoComplete v-model="input.DDDokter2" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                    :loadingIcon="'pi pi-spinner'" :field="'label'" />
                                </VControl>
                              </VField>
                            </div>
                          </div>
                        </div>
                      </Fieldset>
                    </div>
                  </div>
              </TabPanel>
              <TabPanel header="Halaman 3">
                <div class="column is-12">
                  <Fieldset>
                      <div class="columns is-multiline">
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-2">
                            <h1>TB/BB/ BSA :</h1>
                          </div>
                          <div class="column is-2">
                            <VField addons>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TB" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>cm</VButton>
                                </VControl>
                            </VField>
                        </div>
                          <div class="column is-2">
                            <VField addons>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.BB" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>kg</VButton>
                                </VControl>
                            </VField>
                        </div>
                          <div class="column is-2">
                            <VField addons>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.BSA" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>m<sup>2</sup></VButton>
                                </VControl>
                            </VField>
                        </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-2">
                            <h1>Siklus/Total :</h1>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl>
                                <VInput v-model="input.Siklustotal" class="input" type="text" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                              <div class="column is-2">
                                <h1>Diagnosis :</h1>
                              </div>
                              <div class="column is-10">
                                <VField>
                                  <VControl>
                                    <VInput v-model="input.DiagnosisHal3" class="input" type="text" />
                                  </VControl>
                                </VField>
                              </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-2">
                            <h1>Patologi Anatomi :</h1>
                          </div>
                          <div class="column is-10">
                            <VField>
                              <VControl>
                                <VInput v-model="input.PatologiAnatomiHal3 " class="input" type="text" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-2">
                            <h1>Stadium :</h1>
                          </div>
                          <div class="column is-10">
                            <VField>
                              <VControl>
                                <VInput v-model="input.StadiumHal3" class="input" type="text" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-12">
                            <h1>Regimen Kemoterapi: <b>Karboplatin-Paclitaxel</b></h1>
                          </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-12">
                            <h1>Hidrasi Nacl 0,9% 500 ml</h1>
                          </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-12">
                            <h1><b>Premedikasi 30 menit sebelum kemoterapi :</b></h1>
                          </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-12">
                            <h1>Dexametasone 10 mg IV</h1>
                          </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-12">
                            <h1>Ranitidin 100 mg IV</h1>
                          </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-12">
                            <h1>Ondansentron 8 mg IV</h1>
                          </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-12">
                            <h1>Dipenhidramin 20 mg IV</h1>
                          </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-12">
                            <h1><b>Kemoterapi</b></h1>
                          </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-12">
                            <div class="columns is-multiline">
                              <div class="column is-3 pt-0" style="margin-top: 10px;">
                                <p>Kemoterapi Paclitaxel 175 mg/m</p>
                              </div>
                              <div class="column is-2 pt-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input" v-model="input.KemoterapiPaclitaxel" />
                                  </VControl>
                                  <VControl class="field-addon-body">
                                    <VButton static>mg</VButton>
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-7 pt-0" style="margin-top: 10px;">
                                <p>diberikan intravena dalam 500 mL cairan NaCl 0,9% selama 3 Jam setiap 21 hari</p>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-12">
                            <div class="columns is-multiline">
                              <div class="column is-5 pt-0" style="margin-top: 10px;">
                                Kemoterapi Carboplatin target AUC x (GFR+25) = 6 X (
                              </div>
                              <div class="column is-2 pt-0">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.Carboplatin" />
                                </VControl>
                              </div>
                              <div class="column is-1 pt-0" style="margin-top: 10px;">
                                <p>+ 25)</p>
                              </div>
                              <div class="column is-2 pt-0">
                                <VField addons>
                                  <VControl>
                                    <VInput type="text" class="input" v-model="input.Carboplatinmg" />
                                  </VControl>
                                  <VControl class="field-addon-body">
                                    <VButton static>mg</VButton>
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-2 pt-0">diberikan intravena</div>
                            </div>
                          </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-12" style="margin-top: 10px;">
                            dalam 500 mL cairan NaCl 0,9% selama 2 Jam setiap 21 hari
                          </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-12" style="margin-top: 10px;">
                            D5% 500 ml maintenance
                          </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-12" style="margin-top: 10px;">
                            Ranitidin 50 mg @8jam IV
                          </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-12" style="margin-top: 10px;">
                            Ondansentron 8 mg @8jam IV
                          </div>
                        </div>
                      </div>
                      <div class="column is-12 is-flex ml-5 justify-content-end">
                          <div class="column is-4" style="text-align: center">
                            <h1 style="font-weight: bold" class="mr-5">DPJP</h1>
                            <TandaTangan :elemenID="'TTDdokter3'" :width="'150'" :height="'150'" class="dek" />
                            <VField>
                              <VControl>
                                <AutoComplete v-model="input.DDDokter3" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                  :loadingIcon="'pi pi-spinner'" :field="'label'" />
                              </VControl>
                            </VField>
                          </div>
                      </div>
                  </Fieldset>
                </div>
              </TabPanel>
              <TabPanel header="Halaman 4">
                <div class="column is-12">
                  <Fieldset>
                      <div class="columns is-multiline">
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-2">
                            <h1>TB/BB/ BSA :</h1>
                          </div>
                          <div class="column is-2">
                            <VField addons>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TB" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>cm</VButton>
                                </VControl>
                            </VField>
                        </div>
                          <div class="column is-2">
                            <VField addons>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.BB" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>kg</VButton>
                                </VControl>
                            </VField>
                        </div>
                          <div class="column is-2">
                            <VField addons>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.BSA" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>m<sup>2</sup></VButton>
                                </VControl>
                            </VField>
                        </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-2">
                            <h1>Siklus/Total :</h1>
                          </div>
                          <div class="column is-3">
                            <VField>
                              <VControl>
                                <VInput v-model="input.Siklustotal" class="input" type="text" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-2">
                            <h1>Diagnosis :</h1>
                          </div>
                          <div class="column is-10">
                            <VField>
                              <VControl>
                                <VInput v-model="input.DiagnosisHal4" class="input" type="text" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-12">
                            <h1>Regimen Kemoterapi: <b>ZOMETA</b></h1>
                          </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-12">
                            <h1>Hidrasi Nacl 0,9 % 100 ml</h1>
                          </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-12">
                            <h1>Zoometa 4mg habis dalam 30 menit</h1>
                          </div>
                        </div>
                        <div class="column is-12 is-flex ml-5">
                          <div class="column is-12">
                            <h1>Lanjut Loading Nacl 100ml</h1>
                          </div>
                        </div>
                      </div>
                      <div class="column is-12 is-flex ml-5 justify-content-end">
                          <div class="column is-4" style="text-align: center">
                            <h1 style="font-weight: bold" class="mr-5">DPJP</h1>
                            <TandaTangan :elemenID="'TTDdokter4'" :width="'150'" :height="'150'" class="dek" />
                            <VField>
                              <VControl>
                                <AutoComplete v-model="input.DDDokter4" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                  :loadingIcon="'pi pi-spinner'" :field="'label'" />
                              </VControl>
                            </VField>
                          </div>
                      </div>
                  </Fieldset>
                </div>
              </TabPanel>
            </TabView>
          </VCard>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete'
import MultiSelect from 'primevue/multiselect'
import * as EMR from '../page-emr-plugins/lembaran-penyiaran-radioterapi'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import Fieldset from 'primevue/fieldset'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string

let jenisTindakanRadioterapi = ref(EMR.jenisTindakanRadioterapi())
let JenisKelamin = ref(EMR.JenisKelamin())
let energy = ref(EMR.energy())
let accessories: any = ref(EMR.accessories())
let posisiMeja: any = ref(EMR.formField())
const user = useUserSession().getUser().pegawai

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
const RiwayatPsikososial: any = ref([
  { label: 'Baik', value: 'Baik' },
  { label: 'Tidak Baik', value: 'Tidak Baik' },
])

const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const isLoading: any = ref(false)
const i: any = ref(false)
const isLoadingVitalSign: any = ref(false)
const dataTTD: any = ref([])
const d_Perawat: any = ref([])
const d_Dokter: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
})
const COLLECTION: any = ref('FormulirKonkurenKemoradioterapi') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  tanggalKontrol: new Date(),
  tanggal: new Date(),
})
const route = useRoute()
const setView = () => {
  useHead({
    title: 'Formulir Konkuren Kemoradioterapi' + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const loadRiwayat = async () => {
  // if (NOREC_EMRPASIEN.value == '') return
  await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
    if (response.length) {
      input.value = response[0] //set ke inputan
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
      dataTTD.value = response[0]
      H.tandaTangan().set('TTDdokter1', dataTTD.value.TTDdokter1)
      H.tandaTangan().set('TTDdokter2', dataTTD.value.TTDdokter2)
      H.tandaTangan().set('TTDdokter3', dataTTD.value.TTDdokter3)
      H.tandaTangan().set('TTDdokter4', dataTTD.value.TTDdokter4)
      H.tandaTangan().set('TTDpasien', dataTTD.value.TTDpasien)
      H.tandaTangan().set('TTDadmission', dataTTD.value.TTDadmission)
    }
    else {
      setAutoFill()
    }
  })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  object['TTDdokter1'] = H.tandaTangan().get('TTDdokter1')
  object['TTDdokter2'] = H.tandaTangan().get('TTDdokter2')
  object['TTDdokter3'] = H.tandaTangan().get('TTDdokter3')
  object['TTDdokter4'] = H.tandaTangan().get('TTDdokter4')
  object['TTDpasien'] = H.tandaTangan().get('TTDpasien')
  object['TTDadmission'] = H.tandaTangan().get('TTDadmission')
  let json = {
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: route.name,
    name_form: 'Formulir Konkuren Kemoradioterapi',
    jenis_emr: 'asesmen_medis',
    data: object,
  }
  isLoading.value = true
  useApi().post(`/emr/simpan-emr-surket`, json).then((response: any) => {
    isLoading.value = false
    NOREC_EMRPASIEN.value = response.norec_emr
    loadRiwayat()
  })
    .catch((e: any) => {
      isLoading.value = false
    })
}

const setAutoFill = async () => {
  input.value.petugasAddmision = { label: user.namaLengkap, value: user.id }
  input.value.namaPasien = props.pasien.namapasien
  input.value.CBPasien = props.pasien.namapasien
  input.value.jeniskelamin = props.pasien.jeniskelamin
  input.value.norm = props.pasien.nocm
  input.value.tanggalLahirPasien = props.pasien.tgllahir
  input.value.alamat = props.pasien.alamatlengkap
  input.value.pekerjaan = props.pasien.pekerjaan
  input.value.tanggalKunjunganPasien = props.registrasi.tglregistrasi
  input.value.DDDokter = { label: props.registrasi.dokter, value: props.registrasi.iddokter }
  input.value.ruangan = props.registrasi.namaruangan
  input.value.telepon = props.pasien.nohp
  input.value.kepalaPenanggungJawab = props.pasien.penanggungjawab ?? '-'
  input.value.tglPembuatan = new Date()
  input.value.tanggalAdmission = new Date()
  input.value.hubungan = props.pasien.hubungankeluarga ?? '-'
  input.value.tanggalTindakan = new Date()
  input.value.tanggalRawatInap = new Date()
  const response_AsmedRajal = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=AsesmenMedisRawatJalan" + `&field=TADiagnosa`)
  if (response_AsmedRajal != null) {
    input.value.diagnosa = response_AsmedRajal.TADiagnosa
  }
}


const fetchDokter = async (filter: any) => {
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    )
    .then((response) => {
      d_Dokter.value = response
    })
}

const d_Ruangan: any = ref([])
const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  )
  d_Ruangan.value = response
}

const d_Petugas = ref([]);

const fetchPetugas = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Petugas.value = response
  })
}

setView()
loadRiwayat()
</script>

