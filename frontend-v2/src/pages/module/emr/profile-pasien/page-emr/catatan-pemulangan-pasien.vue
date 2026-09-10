<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Catatan Pemulangan Pasien</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :ID_EMR="ID_EMR" :isLoading="isLoading"
                @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun" isHideST isHideCetak>
              </ButtonEmr>
            </div>
          </div>
        </div>

        <div class="column">
          <div class="columns is-multiline">

            <div class="column is-12 is-flex">
              <div class="column is-3">
                <div class="column is-12">
                  <h1 style="font-weight: bold">Tanggal MRS :</h1>
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <h1><i>(Admitted Date)</i></h1>
                      <VField class="mt-2">
                        <VControl>
                          <VDatePicker v-model="input.tglMRS" mode="datetime" trim-weeks :max-date="new Date()">
                            <template #default="{ inputValue, inputEvents }">
                              <VField>
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput :value="inputValue" v-on="inputEvents" placeholder="Tanggal" />
                                </VControl>
                              </VField>
                            </template>
                          </VDatePicker>
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
              <div class="column is-3">
                <div class="column is-12">
                  <h1 style="font-weight: bold">Tanggal KRS :</h1>
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <h1><i>(Discharged Date)</i></h1>
                      <VField class="mt-2">
                        <VControl>
                          <VDatePicker v-model="input.tglKRS" mode="datetime" trim-weeks :max-date="new Date()">
                            <template #default="{ inputValue, inputEvents }">
                              <VField>
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput :value="inputValue" v-on="inputEvents" placeholder="Tanggal" />
                                </VControl>
                              </VField>
                            </template>
                          </VDatePicker>
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
              <div class="column is-3">
                <div class="column is-12">
                  <h1 style="font-weight: bold">Diagnosa MRS :</h1>
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <h1><i>(Admitted Diagnose)</i></h1>
                      <VField class="mt-2">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.diagnosaMRS" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
              <div class="column is-3">
                <div class="column is-12">
                  <h1 style="font-weight: bold">Diagnosa KRS :</h1>
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <h1><i>(Discharged Diagnose)</i></h1>
                      <VField class="mt-2">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.diagnosaKRS" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="column is-12 is-flex">
              <div class="column is-3">
                <h1 style="font-weight: bold">Kontrol Kembali pada :</h1>
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <h1><i>(Follow Up Visit)</i></h1>
                      <VField class="mt-2">
                        <VControl>
                          <VDatePicker v-model="input.kontrolKembaliPada" mode="datetime" trim-weeks>
                            <template #default="{ inputValue, inputEvents }">
                              <VField>
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput :value="inputValue" v-on="inputEvents" placeholder="Tanggal" />
                                </VControl>
                              </VField>
                            </template>
                          </VDatePicker>
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
                <div class="column is-3">
                <h1 style="font-weight: bold">Ke :</h1>
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <h1><i>(to)</i></h1>
                      <VField>
                        <VControl class="prime-auto">
                            <AutoComplete v-model="input.kontrolKembaliKe" :suggestions="d_Ruangan"
                                @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                class="mt-2" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
              </div>
              <div class="column is-3">
              </div>
              <div class="column is-3">
              </div>
            </div>

            <div class="column is-12 is-flex">
              <div class="column is-6">
                <h1 style="font-weight: bold">Dipulangkan dari UPTD.RSUD Bali Mandara dengan keadaan :</h1>
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <h1><i>(Discharge Patient Condition)</i></h1>
                      <VField class="mt-2">
                        <div class="column is-12">
                            <VControl raw subcontrol>
                              <VCheckbox
                                  class="p-0"
                                  color="primary"
                                  square
                                  true-value="atasIjinDokter"
                                  label="Atas Ijin Dokter (Doctor's Permission)"
                                  v-model="input.dipulangkanDenganKeadaan"
                              />
                          </VControl>
                          </div>
                          <div class="column is-12">
                            <VControl raw subcontrol>
                              <VCheckbox
                                  class="p-0"
                                  color="primary"
                                  square
                                  true-value="dirujuk"
                                  label="Dirujuk (Referred)"
                                  v-model="input.dipulangkanDenganKeadaan"
                              />
                            </VControl>
                          </div>
                          <div class="column is-12">
                            <VControl raw subcontrol>
                              <VCheckbox
                                  class="p-0"
                                  color="primary"
                                  square
                                  true-value="atasPermintaanSendiri"
                                  label="Atas Permintaan Sendiri (Own Request)"
                                  v-model="input.dipulangkanDenganKeadaan"
                              />
                            </VControl>
                          </div>
                          <div class="column is-12">
                            <VControl raw subcontrol>
                              <VCheckbox
                                  class="p-0"
                                  color="primary"
                                  square
                                  true-value="kabur"
                                  label="Kabur (Escape)"
                                  v-model="input.dipulangkanDenganKeadaan"
                              />
                            </VControl>
                          </div>
                          <div class="column is-12">
                            <VControl raw subcontrol>
                              <VCheckbox
                                  class="p-0"
                                  color="primary"
                                  square
                                  true-value="meninggal"
                                  label="Meninggal (Die)"
                                  v-model="input.dipulangkanDenganKeadaan"
                              />
                            </VControl>
                          </div>
                      </VField>
                    </div>
                  </div>
                </div>
                <div class="column is-6">
                <h1 style="font-weight: bold">Keadaan Umum Saat Pulang :</h1>
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <h1><i>(Discharge General Condition)</i></h1>
                    </div>
                    <div class="column is-6">
                      <h1>Suhu :</h1>
                      <h1><i>(Temp)</i></h1>
                      <VField addons class="mt-2">
                          <VControl>
                              <VInput type="text" class="input" v-model="input.suhuUmumSaatPulang" />
                          </VControl>
                          <VControl class="field-addon-body">
                              <VButton static>°C</VButton>
                          </VControl>
                      </VField>
                    </div>
                    <div class="column is-6">
                      <h1>Pernafasan :</h1>
                      <h1><i>(Respiration Rate)</i></h1>
                      <VField addons class="mt-2">
                          <VControl>
                              <VInput type="text" class="input" v-model="input.pernafasanUmumSaatPulang" />
                          </VControl>
                          <VControl class="field-addon-body">
                              <VButton static>x/mnt</VButton>
                          </VControl>
                      </VField>
                    </div>
                    <div class="column is-6">
                      <h1>Nadi :</h1>
                      <h1><i>(Pulse)</i></h1>
                      <VField addons class="mt-2">
                          <VControl>
                              <VInput type="text" class="input" v-model="input.nadiUmumSaatPulang" />
                          </VControl>
                          <VControl class="field-addon-body">
                              <VButton static>x/mnt</VButton>
                          </VControl>
                      </VField>
                    </div>
                    <div class="column is-6">
                      <h1>Tekanan Darah :</h1>
                      <h1><i>(Blood Pressure)</i></h1>
                      <VField addons class="mt-2">
                          <VControl>
                              <VInput type="text" class="input" v-model="input.tekananDarahUmumSaatPulang" />
                          </VControl>
                          <VControl class="field-addon-body">
                              <VButton static>mmHg</VButton>
                          </VControl>
                      </VField>
                    </div>
                    <div class="column is-12">
                      <h1>Kesadaran :</h1>
                      <h1><i>(Consciousness)</i></h1>
                      <VField  class="mt-2">
                          <VInput type="text" class="input" v-model="input.kesadaranUmumSaatPulang" />
                      </VField>
                    </div>
                  </div>
              </div>
            </div>

            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-12">
                  <h1 style="font-weight:bold;">Bayi :</h1>
                  <h1><i>(Baby)</i></h1>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">1. Mencocokkan Gelang Ibu dengan Bayi</h1>
                  <h1 class="ml-4"><i>(Matching The Mother’s Bracelet with Her Baby)</i></h1>
                  <div class="columns is-multiline ml-2 mt-2">
                    <div class="column is-2">
                      <VControl raw subcontrol>
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="ya"
                              label="Ya (Yes)"
                              v-model="input.gelangIbuBayi"
                          />
                      </VControl>
                    </div>
                    <div class="column is-6">
                      <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="tidak"
                            label="Tidak (No)"
                            v-model="input.gelangIbuBayi"
                        />
                      </VControl>
                    </div>
                  </div>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">2. Disaksikan Orang Tua Bayi saat Mencocokkan Gelang</h1>
                  <h1 class="ml-4"><i>(Witnessed By The Baby’s Parent while Matching)</i></h1>
                  <div class="columns is-multiline ml-2 mt-2">
                    <div class="column is-2">
                      <VControl raw subcontrol>
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="ya"
                              label="Ya (Yes)"
                              v-model="input.disaksikanOrangTuaBayi"
                          />
                      </VControl>
                    </div>
                    <div class="column is-6">
                      <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="tidak"
                            label="Tidak (No)"
                            v-model="input.disaksikanOrangTuaBayi"
                        />
                      </VControl>
                    </div>
                  </div>
                </div>
              </div>

              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <h1 style="font-weight:bold;">Penyuluhan Kesehatan yang diberikan :</h1>
                    <h1><i>(Health Education Provided)</i></h1>
                    <div class="columns is-multiline mt-2">
                      <div class="column is-12">
                        <VControl raw subcontrol>
                            <VCheckbox
                                class="p-0"
                                color="primary"
                                square
                                true-value="caraPemberianASi"
                                label="Cara Pemberian ASI / Pengenceran Susu (Breast Feed Method)"
                                v-model="input.caraPemberianASI"
                            />
                        </VControl>
                      </div>
                      <div class="column is-12">
                        <VControl raw subcontrol>
                            <VCheckbox
                                class="p-0"
                                color="primary"
                                square
                                true-value="merawatBayi"
                                label="Merawat Bayi (Baby Care)"
                                v-model="input.merawatBayi"
                            />
                        </VControl>
                      </div>
                      <div class="column is-12">
                        <VControl raw subcontrol>
                            <VCheckbox
                                class="p-0"
                                color="primary"
                                square
                                true-value="caraMenjemurBayiKuning"
                                label="Cara Menjemur Bayi Kuning (Icterus Baby Sunbathe Method)"
                                v-model="input.caraMenjemurBayiKuning"
                            />
                        </VControl>
                      </div>
                      <div class="column is-12">
                        <VControl raw subcontrol>
                            <VCheckbox
                                class="p-0"
                                color="primary"
                                square
                                true-value="memandikanBayi"
                                label="Memandikan Bayi (Baby’s Hygiene)"
                                v-model="input.memandikanBayi"
                            />
                        </VControl>
                      </div>
                      <div class="column is-12">
                        <VControl raw subcontrol>
                            <VCheckbox
                                class="p-0"
                                color="primary"
                                square
                                true-value="merawatTaliPusat"
                                label="Merawat Tali Pusat (Umbilical Cord Care)"
                                v-model="input.merawatTaliPusat"
                            />
                        </VControl>
                      </div>
                      <div class="column is-12 mt-2">
                        <VControl raw subcontrol>
                            <VCheckbox
                                class="p-0"
                                color="primary"
                                square
                                true-value="caraPemberianMakanMinum"
                                label="Cara Pemberian Makan / Minum (Food and Beverage Intake Method)"
                                v-model="input.caraPemberianMakanMinum"
                            />
                        </VControl>
                      </div>
                    </div>
                  </div>
                  <div class="column is-6">
                    <div class="columns is-multiline mt-2">
                      <div class="column is-12">
                        <VControl raw subcontrol>
                            <VCheckbox
                                class="p-0"
                                color="primary"
                                square
                                true-value="caraPemakaianObat"
                                label="Cara Pemakaian Obat (Medication Method)"
                                v-model="input.caraPemakaianObat"
                            />
                        </VControl>
                      </div>
                      <div class="column is-12">
                        <VControl raw subcontrol>
                            <VCheckbox
                                class="p-0"
                                color="primary"
                                square
                                true-value="caraMelakukanTeknikRelaksasi"
                                label="Cara Melakukan Tekhnik Relaksasi (Relaxation Technique)"
                                v-model="input.caraMelakukanTeknikRelaksasi"
                            />
                        </VControl>
                      </div>
                      <div class="column is-12">
                        <VControl raw subcontrol>
                            <VCheckbox
                                class="p-0"
                                color="primary"
                                square
                                true-value="caraMelakukanFisioterapi"
                                label="Cara Melakukan Fisioterapi (Physiotherapi Care)"
                                v-model="input.caraMelakukanFisioterapi"
                            />
                        </VControl>
                      </div>
                      <div class="column is-12">
                        <VControl raw subcontrol>
                            <VCheckbox
                                class="p-0"
                                color="primary"
                                square
                                true-value="caraPerawatanLuka"
                                label="Cara Perawatan Luka (Application of Wound Dressing)"
                                v-model="input.caraPerawatanLuka"
                            />
                        </VControl>
                      </div>
                      <div class="column is-12">
                        <VControl raw subcontrol>
                            <VCheckbox
                                class="p-0"
                                color="primary"
                                square
                                true-value="caraBatukEfektif"
                                label="Cara Batuk Effektif(Effective Cough)"
                                v-model="input.caraBatukEfektif"
                            />
                        </VControl>
                      </div>
                      <div class="column is-12">
                        <VControl raw subcontrol>
                            <VCheckbox
                                class="p-0"
                                color="primary"
                                square
                                true-value="pengaturanDiet"
                                label="Pengaturan Diet (Diet Control)"
                                v-model="input.pengaturanDiet"
                            />
                        </VControl>
                      </div>
                      <div class="column is-12">
                        <div class="columns is-multiline">
                          <div class="column is-3 mt-2">
                            <VControl raw subcontrol>
                                <VCheckbox
                                    class="p-0"
                                    color="primary"
                                    square
                                    true-value="lainlainBayi"
                                    label="Lain-lain (Etc) :"
                                    v-model="input.lainlainBayi"
                                />
                            </VControl>
                          </div>
                          <div class="column is-9">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.lainlainBayiText" />
                            </VControl>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-12">
                    <h1 style="font-weight: bold;">Hasil Pemeriksaan yang dibawa pulang :</h1>
                    <h1><i>(Examination Result which may taken)</i></h1>
                    <div class="columns is-multiline mt-2">
                      <div class="column is-2">
                        <VControl raw subcontrol>
                            <VCheckbox
                                class="p-0"
                                color="primary"
                                square
                                true-value="hasilLab"
                                label="Lab (Laboratory)"
                                v-model="input.hasilLab"
                            />
                        </VControl>
                      </div>
                      <div class="column is-2">
                        <VControl raw subcontrol>
                            <VCheckbox
                                class="p-0"
                                color="primary"
                                square
                                true-value="hasilRontgen"
                                label="Photo Rontgen (X-Ray)"
                                v-model="input.hasilRontgen"
                            />
                        </VControl>
                      </div>
                      <div class="column is-2">
                        <VControl raw subcontrol>
                            <VCheckbox
                                class="p-0"
                                color="primary"
                                square
                                true-value="hasilUSG"
                                label="USG (Ultrasonography)"
                                v-model="input.hasilUSG"
                            />
                        </VControl>
                      </div>
                      <div class="column is-1">
                        <VControl raw subcontrol>
                            <VCheckbox
                                class="p-0"
                                color="primary"
                                square
                                true-value="hasilCTScan"
                                label="CT-Scan"
                                v-model="input.hasilCTScan"
                            />
                        </VControl>
                      </div>
                      <div class="column is-1">
                        <VControl raw subcontrol>
                            <VCheckbox
                                class="p-0"
                                color="primary"
                                square
                                true-value="hasilECG"
                                label="ECG"
                                v-model="input.hasilECG"
                            />
                        </VControl>
                      </div>
                      <div class="column is-2">
                        <VControl raw subcontrol>
                            <VCheckbox
                                class="p-0"
                                color="primary"
                                square
                                true-value="hasilEchocardiography"
                                label="Echocardiography"
                                v-model="input.hasilEchocardiography"
                            />
                        </VControl>
                      </div>
                      <div class="column is-2">
                        <VControl raw subcontrol>
                            <VCheckbox
                                class="p-0"
                                color="primary"
                                square
                                true-value="hasilLainLain"
                                label="Lain-lain (Etc) :"
                                v-model="input.hasilLainLain"
                            />
                        </VControl>
                      </div>
                      <div class="column is-10">
                      </div>
                      <div class="column is-2" v-if="input.hasilLainLain == 'hasilLainLain'">
                        <VField>
                            <VTextarea rows="2" v-model="input.hasilLainLainText"></VTextarea>
                        </VField>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-12">
                    <h1 style="font-weight: bold;">Surat Keterangan yang dibawa pulang :</h1>
                    <h1><i>(Reference which may taken)</i></h1>
                    <div class="columns is-multiline mt-2">
                      <div class="column is-4">
                        <VControl raw subcontrol>
                            <VCheckbox
                                class="p-0"
                                color="primary"
                                square
                                true-value="suratIstirahat"
                                label="Surat Istirahat (Letter of Excuse due to Sickness)"
                                v-model="input.suratIstirahat"
                            />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                            <VCheckbox
                                class="p-0"
                                color="primary"
                                square
                                true-value="suratKontrol"
                                label="Surat Kontrol (Control Letter)"
                                v-model="input.suratKontrol"
                            />
                        </VControl>
                      </div>
                      <div class="column is-2">
                        <VControl raw subcontrol>
                            <VCheckbox
                                class="p-0"
                                color="primary"
                                square
                                true-value="suratLainLain"
                                label="Lain-lain (Etc) :"
                                v-model="input.suratLainLain"
                            />
                        </VControl>
                      </div>
                      <div class="column is-3" v-if="input.suratLainLain == 'suratLainLain'">
                        <VField>
                            <VTextarea rows="2" v-model="input.suratLainLainText"></VTextarea>
                        </VField>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="column is-12 tc mt-2">
                <h1 style="font-size: large;">Saya selaku keluarga menyatakan sudah mendaptkan penjelasan tentang hal-hal tersebut diatas oleh Perawat</h1>
                <h1 style="font-size: large;">UPTD.RSUD Bali Mandara dan semuanya saya mengerti.</h1>
                <h1 style="font-size: large;"><i>(I as patient / familly state that I have received an explanation about all things above from the Nurse of Bali Mandara Hospital and I understand all of it.)</i></h1>
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-8">

                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-2 mt-2">
                          Garut,
                        </div>
                        <div class="column is-10">
                          <VDatePicker v-model="input.tglForm" mode="datetime" trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                  <VControl icon="feather:calendar" fullwidth>
                                      <VInput :value="inputValue" v-on="inputEvents" />
                                  </VControl>
                              </template>
                          </VDatePicker>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="columns is-multiline column is-12 pt-0 pb-3 mt-4">
                  <div class="column is-4 p-3 text-center">
                    <h1 style="font-weight: bold;">Mengetahui</h1>
                    <h1 style="font-weight: bold;">Kepala Ruangan</h1>
                    <h1 style="font-weight: bold;"><i>(Head of Ward)</i></h1>
                    <!-- <TandaTangan :elemenID="'TTDDiserahkanPerawat'" :width="'150'" :height="'150'" /> -->
                    <VField>
                      <VControl>
                        <AutoComplete v-model="input.kepalaRuangan" :suggestions="d_Perawat"
                          @complete="fetchPerawat($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                          :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                      </VControl>
                    </VField>
                  </div>

                  <div class="column is-4 p-3 text-center">
                    <h1 style="font-weight: bold;">Pasien / Keluarga</h1>
                    <h1 style="font-weight: bold;"><i>(Patient / Familly)</i></h1>
                    <TandaTangan :elemenID="'TTDDisetujuiPasien'" :width="'150'" :height="'150'" />
                    <VField class="mt-5">
                      <VControl>
                        <VInput type="text" class="input" v-model="input.pasienTTDText" />
                      </VControl>
                    </VField>
                  </div>

                  <div class="column is-4 p-3 text-center">
                    <h1 style="font-weight: bold;">Perawat yang memberi informasi</h1>
                    <h1 style="font-weight: bold;"><i>(Nurse)</i></h1>
                    <!-- <TandaTangan :elemenID="'TTDDiterimaPerawat'" :width="'150'" :height="'150'" /> -->
                    <VField class="mt-5">
                      <VControl>
                        <AutoComplete v-model="input.pemberiInformasiPerawat" :suggestions="d_Perawat"
                          @complete="fetchPerawat($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                          :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                      </VControl>
                    </VField>
                  </div>

                </div>
              </div>

            </div>
        </div>
      </div>
    </div>
  </div>
</div>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount, nextTick } from 'vue'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import { v4 as uuidv4 } from 'uuid';
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Dialog from 'primevue/dialog';
import ImgDraw from '../page-emr-plugins/img-draw.vue'
import Fieldset from 'primevue/fieldset';

useHead({
  title: 'Formulir Transfer Pasien Intra Rumah Sakit - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const router = useRouter();
const TAB_ACTIVE: any = ref('');
const TAB_URL = ref('');
const TAB_ACTIVE_ROUTER: any = ref(null);
const isRemoveTAB: any = ref(false);
const userLogin = useUserSession().getUser()
let kelompokUser = '';
const isloadingLAMPAU: any = ref(false)
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
    FORM_NAME: 'Catatan Pemulangan Pasien',
    FORM_URL: 'catatan-pemulangan-pasien',
    COLLECTION: '',
  }
)

const route = useRoute()
const pasien: any = ref({})
const d_pegawai: any = ref([])
const d_Dokter: any = ref([])
const loadData: any = ref(true)
const item: any = reactive({
  NOREC_PD: props.registrasi.norec_pd,
  ID_PASIEN: props.pasien.nocmfk,
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

const COLLECTION: any = ref('CatatanPemulanganPasien') //table mongodb

const input2: any = ref([])
const NOREC_EMRPASIEN: any = ref('')
const ID_EMR: any = ref('')
const medgadar: any = ref({
  details: [{
    no: 1,
  }],
})
const input: any = ref({
  tglForm: new Date(),
})
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()

// === Array Default ===
// =====================

const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)

// Loopingan
const dataTTD: any = ref([])
const loadRiwayat = async () => {
  await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then(async (response: any) => {
    if (response.length) {
      input.value = response[0] //set ke inputan
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
      if (ID_EMR.value == '') {
        ID_EMR.value = response[0].id
      }
      dataTTD.value = response[0]
      await nextTick(() => {
        H.tandaTangan().set("TTDDisetujuiPasien", dataTTD.value.TTDDisetujuiPasien);
      });
    } else {
    }
  })
}

const addDokter = () => {
  input.value.dokterList.push({ value: "" });
};

const removeDokter = (index) => {
  input.value.dokterList.splice(index, 1);
};

const simpan = () => {

  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.nocm = pasien.value.nocm

  object['TTDDisetujuiPasien'] = H.tandaTangan().get("TTDDisetujuiPasien");
  object.pasien = H.setObjectPasien(pasien.value)
  object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': route.name,
    'name_form': 'Catatan Pemulangan Pasien',
    'jenis_emr': 'asesmen_medis',
    'data': object
  }

  isLoading.value = true
  useApi().post(
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      ID_EMR.value = response.id;
    }).catch((e: any) => {
      isLoading.value = false
    })

  // console.log(resultValue)
}

const simpanTemplate = () => {
  if (!input.value.namatemplate) {
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

const addTemplate = (response: any) => {
  console.log(response)
  input.value = response //set ke inputan
  input.value.namatemplate = null
}

const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi().get(
    `/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
      isLoading.value = false
      console.log(responselast)
      if (responselast.length) {
        for (var x = 0; x < responselast.length; x++) {
          responselast[x].no = x + 1
          responselast[x].id = ''
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
const fetchPasien = () => {
  pasien.value = props.pasien
  pasien.value.registrasi = props.registrasi
  NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
  console.log(norec_emr)
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
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

const d_Petugas = ref([]);

const fetchPetugas = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Petugas.value = response
  })
}

const d_Perawat = ref([]);

const fetchPerawat = async (filter: any) => {
  // let data = filter.query ? filter.query : filter
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Perawat.value = response
  })
}


const handlerRujukanChange = (val: any) => {
  console.log(val);
  if (val === "YA") {

  }
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


fetchPasien()

const loadGambar = async (element_id: string, value: string) => {
  let sigCanvas: any = document.getElementById(element_id);
  console.log("CANVAS", sigCanvas)
  if (sigCanvas) {
    let context = sigCanvas.getContext("2d");
    context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
    let imagess = value
    let background = new Image();
    background.src = imagess
    background.onload = function () {
      context.drawImage(background, 0, 0, sigCanvas.width, sigCanvas.height);
    }
  }

}


const d_Ruangan: any = ref([])
const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  )
  d_Ruangan.value = response
}

</script>


<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/asesmen-awal.scss';

.v-avatar.is-medium.active {
  padding: 3px;
  background: var(--success);
  display: inline-table !important;
}

.p-fieldset-legend {
  margin-left: 14px;
}

.p-fieldset .p-fieldset-content {
  background: none;
}

// .p-fieldset.p-component{
//     border-left: ;
// }

table.assesment {
  border-collapse: collapse;
  width: 100%;
}


.assesment th {
  text-align: center !important;
  border-bottom: 1px solid black;
  // border: 1px solid black;
}

.assesment th,
.assesment td {
  padding: 8px;
  vertical-align: middle !important;
}

hr {
  background-color: hsl(0deg 6.81% 88.68%);
  border: none;
  display: block;
  height: 2px;
  margin: 1rem 0;
}

.table.is-borderless {
  border: none !important;
  background-color: transparent;
}

.tg3 {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  background-color: var(--white);
  border-color: var(--fade-grey-dark-2) !important;
}

.is-dark {
  .tg3 {
    background-color: var(--dark-sidebar-light-6)
  }

  .tg-card {
    background-color: var(--dark-sidebar-light-6)
  }
}

.tg-card {
  background-color: #feffed !important;
  height: 720px;
}

.tg3 td {
  border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg3 th {
  border-color: var(--fade-grey-dark-3) !important;
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: normal;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.switch-profesi {
  font-size: 10px;
  font-weight: bold;
  width: 10px;
  height: 10px;
}

.tg3 .tg-0lax {
  text-align: left;
  vertical-align: top
}

.tadar {
  border: 1px black solid;
}

.tadar td {
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tadar th {
  text-align: center !important;
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: bold;
  overflow: hidden;
  background-color: aquamarine;
  vertical-align: middle;
  padding: 10px 5px;
  word-break: normal;
}

.tg2 {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100% !important;
  border: 1px solid black;
}

.tg2 td {
  border-style: solid;
  border-width: 1px;
  border-color: black;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg2 th {
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  border-color: black;
  font-weight: normal;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.CPPT_HEIGHT {
  overflow: auto;
  height: 500px;
}

.table.is-borderless th,
tr,
td {
  border: none !important;
  background-color: transparent !important;
}

// tr:hover {
//     background-color: #f5f5f5;
// }</style>

<style lang="scss">
.table-fro {
  width: 100%;
  border: 1px solid black;
}

.th-fro,
.td-fro {
  padding: 7px;
  border: 1px solid black;
  vertical-align: inherit;
}

.setFRO-center {
  text-align: center !important;
}

.p-fieldset-legend {
  margin-left: 15px;
}

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 150%;
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

.tc {
  text-align: center;
}
</style>
