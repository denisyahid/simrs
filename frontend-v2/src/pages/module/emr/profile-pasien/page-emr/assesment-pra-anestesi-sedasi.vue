<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Assesment Pra Anestesi / Sedasi</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" @simpan="simpan"
                @kembaliKeun="kembaliKeun" isHideST></ButtonEmr>
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
        <div class="column">
          <VCard>
          <div class="column">
            <div class="columns is-multiline">
              <div class="column is-4">
                <span class="label-apas">Tanggal Dan Jam</span>
                <VField class="column is-10 p-0 mt-3">
                  <VDatePicker v-model="input.tglDanJam" mode="dateTime" style="width: 100%" trim-weeks
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
              <div class="column is-6">
                <span class="label-apas">
                  Sumber data :
                </span>
                <div class="columns is-multiline">
                  <div class="column is-3" style="margin-top: 10px;">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="Pasien"
                            label="Pasien"
                            v-model="input.Pasien"
                        />
                    </VControl>
                  </div>
                  <div class="column is-3" style="margin-top: 10px;">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="Keluarga"
                            label="Keluarga"
                            v-model="input.Keluarga"
                        />
                    </VControl>
                  </div>
                  <div class="column is-6">
                    <div class="columns is-multline">
                      <div class="column is-4" style="margin-top: 10px;">
                        <VControl raw subcontrol>
                            <VCheckbox
                                class="p-0"
                                color="primary"
                                square
                                :true-value="Lainnya"
                                label="Lainnya :"
                                v-model="input.Lainnya"
                            />
                        </VControl>
                      </div>
                      <div class="column is-8">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.LainnyaText" />
                        </VControl>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-12">
            <span class="label-apas">RENCANA TINDAKAN :</span>
            <VField class="pt-3">
              <VControl>
                <VTextarea v-model="input.rencanaTindakan" rows="2" placeholder="Rencana Tindakan">
                </VTextarea>
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <span class="label-apas">RIWAYAT KESEHATAN/PENYAKIT :</span>
            <VField class="pt-3">
              <VControl>
                <VTextarea v-model="input.riwayatKesehatanPenyakit" rows="2" placeholder="Riwayat Kesehatan/Penyakit">
                </VTextarea>
              </VControl>
            </VField>
          </div>
      
          <div class="column is-12">
            <span class="label-apas">Alergi Obat :</span>
            <div class="columns is-multiline">
              <div class="column is-2" style="margin-top: 10px;">
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        true-value="Tidak"
                        label="Tidak"
                        v-model="input.alergiObatTidak"
                    />
                </VControl>
              </div>
              <div class="column is-8">
                <div class="columns is-multiline">
                  <div class="column is-2" style="margin-top: 10px;">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Ya"
                            label="Ya, sebutkan :"
                            v-model="input.alergiObatYa"
                        />
                    </VControl>
                  </div>
                  <div class="column is-10">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.alergiObatYaText" />
                    </VControl>
                  </div>
                </div>
              </div>
            </div>
          </div>
      
          <div class="column is-12">
            <span class="label-apas">Alergi makanan :</span>
            <div class="columns is-multiline">
              <div class="column is-2" style="margin-top: 10px;">
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        true-value="Tidak"
                        label="Tidak"
                        v-model="input.alergiMakananTidak"
                    />
                </VControl>
              </div>
              <div class="column is-8">
                <div class="columns is-multiline">
                  <div class="column is-2" style="margin-top: 10px;">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Ya"
                            label="Ya, sebutkan :"
                            v-model="input.alergiMakananYa"
                        />
                    </VControl>
                  </div>
                  <div class="column is-10">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.alergiMakananYaText" />
                    </VControl>
                  </div>
                </div>
              </div>
            </div>
          </div>
      
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-3">
                <span class="label-apas">Operasi / anesthesi dan sebelumnya :</span>
                <div class="columns is-multiline">
                  <div class="column is-6" style="margin-top: 10px;">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Ya"
                            label="Ya"
                            v-model="input.operasiAnesthesiSebelumnyaYa"
                        />
                    </VControl>
                  </div>
                  <div class="column is-6" style="margin-top: 10px;">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Tidak"
                            label="Tidak"
                            v-model="input.operasiAnesthesiSebelumnyaTidak"
                        />
                    </VControl>
                  </div>
                </div>
              </div>
          
              <div class="column is-3">
                <span class="label-apas">Sedang mengkonsumsi obat :</span>
                <div class="columns is-multiline">
                  <div class="column is-6" style="margin-top: 10px;">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Ya"
                            label="Ya"
                            v-model="input.sedangMengkonsumsiObatYa"
                        />
                    </VControl>
                  </div>
                  <div class="column is-6" style="margin-top: 10px;">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Tidak"
                            label="Tidak"
                            v-model="input.sedangMengkonsumsiObatTidak"
                        />
                    </VControl>
                  </div>
                </div>
              </div>
          
              <div class="column is-3">
                <span class="label-apas">Riwayat anesthesi dan komplikasi :</span>
                <div class="columns is-multiline">
                  <div class="column is-6" style="margin-top: 10px;">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Ya"
                            label="Ya"
                            v-model="input.riwayatAnesthesiKomplikasiYa"
                        />
                    </VControl>
                  </div>
                  <div class="column is-6" style="margin-top: 10px;">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Tidak"
                            label="Tidak"
                            v-model="input.riwayatAnesthesiKomplikasiTidak"
                        />
                    </VControl>
                  </div>
                </div>
              </div>
          
              <div class="column is-3">
                <span class="label-apas">Kebiasaan :</span>
                <div class="columns is-multiline">
                  <div class="column is-6" style="margin-top: 10px;">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Merokok"
                            label="Merokok"
                            v-model="input.kebiasaanMerokok"
                        />
                    </VControl>
                  </div>
                  <div class="column is-6" style="margin-top: 10px;">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Minum alkohol"
                            label="Minum alkohol"
                            v-model="input.kebiasaanMinumAlkohol"
                        />
                    </VControl>
                  </div>
                </div>
              </div>
            </div>
          </div>
      
      
          </VCard>
        </div>
      
        <div class="column">
          <VCard>
            <table border="1" width="100%">
              <tr>
                <td width="35%">
                  <div class="column is-12">
                    <span class="label-apas">Respiratory</span>
                    <VField style="display: flex; justify-content: start; width: 100%;">
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Asthma"
                              label="Asthma"
                              v-model="input.respiratoryAsthma"
                          />
                      </VControl>
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Productive cough"
                              label="Productive cough"
                              v-model="input.respiratoryProductiveCough"
                          />
                      </VControl>
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Bronchitis"
                              label="Bronchitis"
                              v-model="input.respiratoryBronchitis"
                          />
                      </VControl>
                    </VField>
                    <VField style="display: flex; justify-content: start; width: 100%;">
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Recent URI"
                              label="Recent URI"
                              v-model="input.respiratoryRecentURI"
                          />
                      </VControl>
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="COPD"
                              label="COPD"
                              v-model="input.respiratoryCOPD"
                          />
                      </VControl>
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="SOB"
                              label="SOB"
                              v-model="input.respiratorySOB"
                          />
                      </VControl>
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Dyspepsia"
                              label="Dyspepsia"
                              v-model="input.respiratoryDyspepsia"
                          />
                      </VControl>
                    </VField>
                    <VField style="display: flex; justify-content: start; width: 100%;">
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Tuberculosis"
                              label="Tuberculosis"
                              v-model="input.respiratoryTuberculosis"
                          />
                      </VControl>
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Arthopnea"
                              label="Arthopnea"
                              v-model="input.respiratoryArthopnea"
                          />
                      </VControl>
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Pneumonia"
                              label="Pneumonia"
                              v-model="input.respiratoryPneumonia"
                          />
                      </VControl>
                    </VField>
                  </div>
                </td>
                <td width="5%" style="vertical-align:middle;">
                  <div class="column is-12">
                    <VField style="text-align:center; display: flex; justify-content: start; width: 100%; flex-direction:column;">
                      <span class="label-apas">WNL</span>
                      <VControl raw subcontrol>
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="WNL"
                              label=""
                              v-model="input.respiratoryWNL"
                          />
                      </VControl>
                    </VField>
                  </div>
                </td>
                <td width="55%">
                  <div class="column is-12">
                    <span class="label-apas">Cardiovascular</span>
                    <VField style="display: flex; justify-content: start; width: 100%;">
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Abnormal EKG"
                              label="Abnormal EKG"
                              v-model="input.cardiovascularAbnormalEKG"
                          />
                      </VControl>
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Hypertensi"
                              label="Hypertensi"
                              v-model="input.cardiovascularHypertensi"
                          />
                      </VControl>
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Angina"
                              label="Angina"
                              v-model="input.cardiovascularAngina"
                          />
                      </VControl>
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="MI"
                              label="MI"
                              v-model="input.cardiovascularMI"
                          />
                      </VControl>
                    </VField>
                    <VField style="display: flex; justify-content: start; width: 100%;">
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="ASHD"
                              label="ASHD"
                              v-model="input.cardiovascularASHD"
                          />
                      </VControl>
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Murmur"
                              label="Murmur"
                              v-model="input.cardiovascularMurmur"
                          />
                      </VControl>
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="CHF"
                              label="CHF"
                              v-model="input.cardiovascularCHF"
                          />
                      </VControl>
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Pacemaker"
                              label="Pacemaker"
                              v-model="input.cardiovascularPacemaker"
                          />
                      </VControl>
                    </VField>
                    <VField style="display: flex; justify-content: start; width: 100%;">
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Dysrhythmia"
                              label="Dysrhythmia"
                              v-model="input.cardiovascularDysrhythmia"
                          />
                      </VControl>
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Rheumatic fever"
                              label="Rheumatic fever"
                              v-model="input.cardiovascularRheumaticFever"
                          />
                      </VControl>
                    </VField>
                    <VField style="display: flex; justify-content: start; width: 100%;">
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Exercise / tolerance valvuvar discase"
                              label="Exercise / tolerance valvuvar discase"
                              v-model="input.cardiovascularExerciseToleranceValvuvarDiscase"
                          />
                      </VControl>
                    </VField>
                  </div>
                </td>
                <td width="5%" style="vertical-align: middle;">
                  <div class="column is-12">
                    <VField style="text-align:center; display: flex; justify-content: start; width: 100%; flex-direction:column;">
                      <span class="label-apas">WNL</span>
                      <VControl raw subcontrol>
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="WNL"
                              label=""
                              v-model="input.cardiovascularWNL"
                          />
                      </VControl>
                    </VField>
                  </div>
                </td>
              </tr>
              <!-- Start row 2 -->
              <tr>
                <td width="35%">
                  <div class="column is-12">
                    <span class="label-apas">Hepato</span>
                    <VField style="display: flex; justify-content: start; width: 100%;">
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Bowel obstruction"
                              label="Bowel obstruction"
                              v-model="input.hepatoBowelObstruction"
                          />
                      </VControl>
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Hiatal hernia / reflux"
                              label="Hiatal hernia / reflux"
                              v-model="input.hepatoHiatalHerniaReflux"
                          />
                      </VControl>
                    </VField>
                    <VField style="display: flex; justify-content: start; width: 100%;">
                      <VControl raw subcontrol style="margin: 5px;">
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Ucer"
                            label="Ucer"
                            v-model="input.hepatoUcer"
                        />
                    </VControl>
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Chirrosis"
                              label="Chirrosis"
                              v-model="input.hepatoChirrosis"
                          />
                      </VControl>
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Nausea & vomiting"
                              label="Nausea & vomiting"
                              v-model="input.hepatoNauseaVomiting"
                          />
                      </VControl>
                    </VField>
                    <VField style="display: flex; justify-content: start; width: 100%;">
                      <VControl raw subcontrol style="margin: 5px;">
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Hepatitis / jaundice"
                            label="Hepatitis / jaundice"
                            v-model="input.hepatoHepatitisJaundice"
                        />
                    </VControl>
                    </VField>
                  </div>
                </td>
                <td width="5%" style="vertical-align:middle;">
                  <div class="column is-12">
                    <VField style="text-align:center; display: flex; justify-content: start; width: 100%; flex-direction:column;">
                      <VControl raw subcontrol>
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="WNL"
                              label=""
                              v-model="input.hepatoWNL"
                          />
                      </VControl>
                    </VField>
                  </div>
                </td>
                <td width="55%">
                  <div class="column is-12">
                    <span class="label-apas">Renal</span>
                    <VField style="display: flex; justify-content: start; width: 100%;">
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Diabetes"
                              label="Diabetes"
                              v-model="input.renalDiabetes"
                          />
                      </VControl>
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Thyroid disease"
                              label="Thyroid disease"
                              v-model="input.renalThyroidDisease"
                          />
                      </VControl>
                    </VField>
                    <VField style="display: flex; justify-content: start; width: 100%;">
                      <VControl raw subcontrol style="margin: 5px;">
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Renal failure / Dialysis"
                            label="Renal failure / Dialysis"
                            v-model="input.renalFailureDialysis"
                        />
                      </VControl>
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Weight loss / gain"
                              label="Weight loss / gain"
                              v-model="input.renalWeightLossGain"
                          />
                      </VControl>
                    </VField>
                    <VField style="display: flex; justify-content: start; width: 100%;">
                      <VControl raw subcontrol style="margin: 5px;">
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Dysrhythmia"
                            label="Dysrhythmia"
                            v-model="input.renalDysrhythmia"
                        />
                      </VControl>
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Urinary tract infection"
                              label="Urinary tract infection"
                              v-model="input.renalUrinaryTractInfection"
                          />
                      </VControl>
                    </VField>
                  </div>
                </td>
                <td width="5%" style="vertical-align: middle;">
                  <div class="column is-12">
                    <VField style="text-align:center; display: flex; justify-content: start; width: 100%; flex-direction:column;">
                      <VControl raw subcontrol>
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="WNL"
                              label=""
                              v-model="input.renalWNL"
                          />
                      </VControl>
                    </VField>
                  </div>
                </td>
              </tr>
              <!-- Start row 3 -->
              <tr>
                <td width="35%">
                  <div class="column is-12">
                    <span class="label-apas">Neuro / Musculoskeletal</span>
                    <VField style="display: flex; justify-content: start; width: 100%;">
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Artritis"
                              label="Artritis"
                              v-model="input.neuroMusculoskeletalArtritis"
                          />
                      </VControl>
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Muscule"
                              label="Muscule"
                              v-model="input.neuroMusculoskeletalMuscule"
                          />
                      </VControl>
                      <VControl raw subcontrol style="margin: 5px;">
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Weaknes"
                            label="Weaknes"
                            v-model="input.neuroMusculoskeletalWeaknes"
                        />
                    </VControl>
                    </VField>
                    <VField style="display: flex; justify-content: start; width: 100%;">
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="DJD"
                              label="DJD"
                              v-model="input.neuroMusculoskeletalDJD"
                          />
                      </VControl>
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Seizures"
                              label="Seizures"
                              v-model="input.neuroMusculoskeletalSeizures"
                          />
                      </VControl>
                    </VField>
                    <VField style="display: flex; justify-content: start; width: 100%;">
                      <VControl raw subcontrol style="margin: 5px;">
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Back prolems"
                            label="Back prolems"
                            v-model="input.neuroMusculoskeletalBackProlems"
                        />
                    </VControl>
                    <VControl raw subcontrol style="margin: 5px;">
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Neuromuscular dis paralys"
                            label="Neuromuscular dis paralys"
                            v-model="input.neuroMusculoskeletalNeuromuscularDisParalys"
                        />
                    </VControl>
                    </VField>
                    <VField style="display: flex; justify-content: start; width: 100%;">
                      <VControl raw subcontrol style="margin: 5px;">
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="CVA/strok/TIA"
                            label="CVA/strok/TIA"
                            v-model="input.neuroMusculoskeletalCVAStrokTIA"
                        />
                    </VControl>
                    <VControl raw subcontrol style="margin: 5px;">
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Paresthesia"
                            label="Paresthesia"
                            v-model="input.neuroMusculoskeletalParesthesia"
                        />
                    </VControl>
                    <VControl raw subcontrol style="margin: 5px;">
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Syncope"
                            label="Syncope"
                            v-model="input.neuroMusculoskeletalSyncope"
                        />
                    </VControl>
                    </VField>
                    <VField style="display: flex; justify-content: start; width: 100%;">
                      <VControl raw subcontrol style="margin: 5px;">
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Headaches / ICP"
                            label="Headaches / ICP"
                            v-model="input.neuroMusculoskeletalHeadachesICP"
                        />
                    </VControl>
                    <VControl raw subcontrol style="margin: 5px;">
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Loss of consiousness"
                            label="Loss of consiousness"
                            v-model="input.neuroMusculoskeletalLossOfConsiousness"
                        />
                    </VControl>
                    </VField>
                  </div>
                </td>
                <td width="5%" style="vertical-align:middle;">
                  <div class="column is-12">
                    <VField style="text-align:center; display: flex; justify-content: start; width: 100%; flex-direction:column;">
                      <VControl raw subcontrol>
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="WNL"
                              label=""
                              v-model="input.neuroMusculoskeletalWNL"
                          />
                      </VControl>
                    </VField>
                  </div>
                </td>
                <td width="55%">
                  <div class="column is-12">
                    <span class="label-apas">Other</span>
                    <VField style="display: flex; justify-content: start; width: 100%;">
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Anemia"
                              label="Anemia"
                              v-model="input.otherAnemia"
                          />
                      </VControl>
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Immunosuppresed"
                              label="Immunosuppresed"
                              v-model="input.otherImmunosuppresed"
                          />
                      </VControl>
                    </VField>
                    <VField style="display: flex; justify-content: start; width: 100%;">
                      <VControl raw subcontrol style="margin: 5px;">
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Bleeding tendencis"
                            label="Bleeding tendencis"
                            v-model="input.otherBleedingTendencis"
                        />
                      </VControl>
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Pregnancy"
                              label="Pregnancy"
                              v-model="input.otherPregnancy"
                          />
                      </VControl>
                    </VField>
                    <VField style="display: flex; justify-content: start; width: 100%;">
                      <VControl raw subcontrol style="margin: 5px;">
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Cancer"
                            label="Cancer"
                            v-model="input.otherCancer"
                        />
                      </VControl>
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Sickie cell dis / trait"
                              label="Sickie cell dis / trait"
                              v-model="input.otherSickieCellDisTrait"
                          />
                      </VControl>
                    </VField>
                    <VField style="display: flex; justify-content: start; width: 100%;">
                      <VControl raw subcontrol style="margin: 5px;">
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Chemotheraphy"
                            label="Chemotheraphy"
                            v-model="input.otherChemotheraphy"
                        />
                      </VControl>
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Recent steroids"
                              label="Recent steroids"
                              v-model="input.otherRecentSteroids"
                          />
                      </VControl>
                    </VField>
                    <VField style="display: flex; justify-content: start; width: 100%;">
                      <VControl raw subcontrol style="margin: 5px;">
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Dehydration"
                            label="Dehydration"
                            v-model="input.otherDehydration"
                        />
                      </VControl>
                      <VControl raw subcontrol style="margin: 5px;">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Transfusion history"
                              label="Transfusion history"
                              v-model="input.otherTransfusionHistory"
                          />
                      </VControl>
                    </VField>
                    <VField style="display: flex; justify-content: start; width: 100%;">
                      <VControl raw subcontrol style="margin: 5px;">
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Hemophilia"
                            label="Hemophilia"
                            v-model="input.otherHemophilia"
                        />
                      </VControl>
                    </VField>
                  </div>
                </td>
                <td width="5%" style="vertical-align: middle;">
                  <div class="column is-12">
                    <VField style="text-align:center; display: flex; justify-content: start; width: 100%; flex-direction:column;">
                      <VControl raw subcontrol>
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="WNL"
                              label=""
                              v-model="input.otherWNL"
                          />
                      </VControl>
                    </VField>
                  </div>
                </td>
              </tr>
            </table>
      
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-6">
                  <span class="label-apas">Diagnostic Studies</span>
                  <VField>
                    <span class="label" style="font-weight:normal;">EKG :</span>
                    <VControl>
                        <VInput type="text" class="input" v-model="input.dSEKG" style="width: 80%;" />
                    </VControl>
                  </VField>
                  <VField>
                    <span class="label" style="font-weight:normal;">Pulmanory studies :</span>
                    <VControl>
                        <VInput type="text" class="input" v-model="input.dSPulmanoryStudies" style="width: 80%;" />
                    </VControl>
                  </VField>
                  <VField>
                    <span class="label" style="font-weight:normal;">X-ray :</span>
                    <VControl>
                        <VInput type="text" class="input" v-model="input.dSXray" style="width: 80%;" />
                    </VControl>
                  </VField>
                  <VField>
                    <span class="label" style="font-weight:normal;">Lain-lain :</span>
                    <VControl>
                        <VInput type="text" class="input" v-model="input.dSLainLain" style="width: 80%;" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <span class="label-apas">Laboratory studies</span>
                  <VField>
                    <span class="label" style="font-weight:normal;">Hb/Hct/CBC :</span>
                    <VControl>
                        <VInput type="text" class="input" v-model="input.lSHbHctCbc" style="width: 80%;" />
                    </VControl>
                  </VField>
                  <VField>
                    <span class="label" style="font-weight:normal;">Electrolit :</span>
                    <VControl>
                        <VInput type="text" class="input" v-model="input.lSElectrolit" style="width: 80%;" />
                    </VControl>
                  </VField>
                  <VField>
                    <span class="label" style="font-weight:normal;">Urinalisis :</span>
                    <VControl>
                        <VInput type="text" class="input" v-model="input.lSUrinalisis" style="width: 80%;" />
                    </VControl>
                  </VField>
                  <VField>
                    <span class="label" style="font-weight:normal;">Lain-lain :</span>
                    <VControl>
                        <VInput type="text" class="input" v-model="input.lSLainLain" style="width: 80%;" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
      
            <div class="column is-12">
              <span class="label-apas">EVALUASI PRA SEDASI :</span>
              <div class="columns is-multiline" style="margin-top: 10px;">
                <div class="column is-12">
                  <span class="label-apas">VITAL SIGN :</span>
                </div>
                <div class="column is-3">
                  <span class="label-apas">Tensi :</span>
                  <VField addons>
                      <VControl>
                          <VInput type="text" class="input" v-model="input.ePSTensi" />
                      </VControl>
                      <VControl class="field-addon-body">
                          <VButton static>mmHg</VButton>
                      </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <span class="label-apas">Nadi :</span>
                  <VField addons>
                      <VControl>
                          <VInput type="text" class="input" v-model="input.ePSNadi" />
                      </VControl>
                      <VControl class="field-addon-body">
                          <VButton static>x/mnt</VButton>
                      </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <span class="label-apas">Respirasi :</span>
                  <VField addons>
                      <VControl>
                          <VInput type="text" class="input" v-model="input.ePSRespirasi" />
                      </VControl>
                      <VControl class="field-addon-body">
                          <VButton static>x/mnt</VButton>
                      </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <span class="label-apas">Suhu :</span>
                  <VField addons>
                      <VControl>
                          <VInput type="text" class="input" v-model="input.ePSSuhu" />
                      </VControl>
                      <VControl class="field-addon-body">
                          <VButton static><sup>o</sup>C</VButton>
                      </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <span class="label-apas">Jalan nafas / gigi geligi / leher :</span>
                  <VField>
                      <VTextarea rows="3" v-model="input.ePSJalanNafasGigiLeher"></VTextarea>
                  </VField>
                </div>
              </div>
            </div>
      
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-6">
                  <span class="label-apas">MASALAH / DIAGNOSTIK</span>
                  <VField>
                      <VTextarea rows="2" v-model="input.masalahDiagnostik"></VTextarea>
                  </VField>
                </div>
                <div class="column is-6">
                  <span class="label-apas">PREMIDIKASI</span>
                  <VField>
                      <VTextarea rows="2" v-model="input.premidikasi"></VTextarea>
                  </VField>
                </div>
                <div class="column is-6">
                  <span class="label-apas">RENCANA SEDASI</span>
                  <VField>
                      <VTextarea rows="2" v-model="input.rencanaSedasi"></VTextarea>
                  </VField>
                </div>
                <div class="column is-6">
                  <span class="label-apas">PS ASA</span>
                  <VField style="display: flex;">
                    <VControl raw subcontrol style="margin: 5px;">
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="1"
                            label="1"
                            v-model="input.pSAsa1"
                        />
                    </VControl>
                    <VControl raw subcontrol style="margin: 5px;">
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="2"
                            label="2"
                            v-model="input.pSAsa2"
                        />
                    </VControl>
                    <VControl raw subcontrol style="margin: 5px;">
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="3"
                            label="3"
                            v-model="input.pSAsa3"
                        />
                    </VControl>
                    <VControl raw subcontrol style="margin: 5px;">
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="4"
                            label="4"
                            v-model="input.pSAsa4"
                        />
                    </VControl>
                    <VControl raw subcontrol style="margin: 5px;">
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="5"
                            label="5"
                            v-model="input.pSAsa5"
                        />
                    </VControl>
                    <VControl raw subcontrol style="margin: 5px;">
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="6"
                            label="6"
                            v-model="input.pSAsa6"
                        />
                    </VControl>
                    <VControl raw subcontrol style="margin: 5px;">
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="E"
                            label="E"
                            v-model="input.pSAsaE"
                        />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
      
            <div class="column is-4">
              <span class="label-apas">Nama dan tanda tangan Dokter Spesialis Anestesi</span>
            </div>
            <div class="column is-4" style="text-align: center;">
              <TandaTangan :elemenID="'signatureDokter'" :width="'180'" :height="'180'" class="dek" />
            </div>
            <div class="column is-4">
              <VField class="pt-3">
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    @item-select="setTandaTanganDokter($event)" :loadingIcon="'pi pi-spinner'" :field="'label'"
                    placeholder="Cari Dokter..." />
                </VControl>
              </VField>
            </div>
          </VCard>
        </div>
  
      </div>
    </div>
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
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import * as EMR from '../page-emr-plugins/assesment-pra-anestesi-sedasi'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let statusAnestesi = ref(EMR.statusAnestesi())
let asesmenSedasi = ref(EMR.asesmenSedasi())
let fisikDanPenunjang = ref(EMR.fisikDanPenunjang())
let pemrksPenunjang = ref(EMR.pemrksPenunjang())
let analisaFisik = ref(EMR.analisaFisik())
let rencanaAnestesi = ref(EMR.rencanaAnestesi())
let alatKhusus = ref(EMR.alatKhusus())

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
const loadData: any = ref(false)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('AssesmentPraAnestesiSedasi') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  tglDanJam: new Date(),
  makanTerakhir: new Date(),
  minumTerakhir: new Date(),
  tglDibuat: new Date()
})
const setView = () => {
  useHead({
    title: 'Assesment Pra Anestesi / Sedasi' + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = async () => {

  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length) {
    input.value = response[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
    if (response[0].tandaTanganDokter) {
      H.tandaTangan().set("signatureDokter", response[0].tandaTanganDokter)
    }
  }
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  object.tandaTanganDokter = H.tandaTangan().get("signatureDokter")
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': route.name,
    'name_form': 'Assesment Pra Anestesi / Sedasi',
    'jenis_emr': 'asesmen_medis',
    'data': object
  }
  isLoading.value = true
  useApi().post(
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
      sudahDisimpan.value = true
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const setTandaTanganDokter = async (e: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signatureDokter", element.ttd)
    } else {
      H.tandaTangan().set("signatureDokter", '')
    }
  })
}

const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}


const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien;
  input.value.norm = props.pasien.nocm;
};
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

const lockedFormName = ref(props.FORM_NAME)

let sudahDisimpan = ref(false)

onBeforeRouteLeave((to, from, next) => {
    try {
        let rouutename = from?.name + '-' + route.params.index_tabs
        H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
        if (!sudahDisimpan.value) {
            const konfirmasi = H.alert('warning', 'Perubahan belum disimpan. Yakin ingin meninggalkan halaman?');
            if (!konfirmasi) {
            return next(false);
            }
        }
    } catch (error) {
        console.error('Error leave cache TAB EMR:', error);
    }
    next();
});

watch(input, () => {
    sudahDisimpan.value = false
}, { deep: true });

</script>

<style lang="scss">
.label-apas {
  font-weight: 500;
}

.p-fieldset-legend {
  margin-left: 15px;
}
</style>
