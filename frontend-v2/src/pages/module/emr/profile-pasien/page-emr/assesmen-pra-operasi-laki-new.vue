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
              <h3>{{ props.FORM_NAME }}</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun"></ButtonEmr>
            </div>
          </div>
        </div>

        <!-- form baru -->

        <div class="column is-12 mt-5">
          <div class="columns is-multiline mt-5">
            <div class="column is-6">
              <h1 style="font-weight: bold;">Data Subyektif (anamnesis)</h1>
              <div class="columns is-multiline">
                  <div class="column is-4">
                      <VCheckbox class="fontcheckbox" v-model="input.batuk"
                      true-value="Batuk" color="primary"/><span v-html="highlightMatch('Batuk')" class="highlighted-label"></span><br>
                      <VCheckbox class="fontcheckbox" v-model="input.pilek"
                      true-value="Pilek" color="primary"/><span v-html="highlightMatch('Pilek')" class="highlighted-label"></span><br>
                      <VCheckbox class="fontcheckbox" v-model="input.gigipalsu"
                      true-value="Gigi Palsu" color="primary"/><span v-html="highlightMatch('Gigi Palsu')" class="highlighted-label"></span><br>
                  </div>
                  <div class="column is-4">
                      <VCheckbox class="fontcheckbox" v-model="input.pusing"
                      true-value="Pusing" color="primary"/><span v-html="highlightMatch('Pusing')" class="highlighted-label"></span><br>
                      <VCheckbox class="fontcheckbox" v-model="input.mual"
                      true-value="Mual" color="primary"/><span v-html="highlightMatch('Mual')" class="highlighted-label"></span><br>
                      <VCheckbox class="fontcheckbox" v-model="input.lainnya1"
                      true-value="Lainnya" color="primary"/>
                      <textarea v-model="input.lainnya" class="textarea" placeholder="" rows="1"></textarea>
                  </div>
                  <div class="column is-4">
                      <VCheckbox class="fontcheckbox" v-model="input.sesaknafas"
                      true-value="Sesak Nafas" color="primary"/><span v-html="highlightMatch('Sesak Nafas')" class="highlighted-label"></span><br>
                      <VCheckbox class="fontcheckbox" v-model="input.puasa"
                      true-value="Puasa" color="primary"/><span v-html="highlightMatch('Puasa')" class="highlighted-label"></span><br>
                  </div>
              </div>
            </div>
            <div class="column is-6">
              <h1 style="font-weight: bold;">Riwayat penyakit</h1>
              <div class="columns is-multiline">
                  <div class="column is-4">
                      <VCheckbox class="fontcheckbox" v-model="input.dm"
                      true-value="DM" color="primary"/><span v-html="highlightMatch('DM')" class="highlighted-label"></span><br>
                      <VCheckbox class="fontcheckbox" v-model="input.hipertensi"
                      true-value="Hipertensi" color="primary"/><span v-html="highlightMatch('Hipertensi')" class="highlighted-label"></span><br>
                      <VCheckbox class="fontcheckbox" v-model="input.asthma"
                      true-value="Asthma" color="primary"/><span v-html="highlightMatch('Asthma')" class="highlighted-label"></span><br>
                  </div>
                  <div class="column is-4">
                      <VCheckbox class="fontcheckbox" v-model="input.tb"
                      true-value="TB Paru" color="primary"/><span v-html="highlightMatch('TB Paru')" class="highlighted-label"></span><br>
                      <VCheckbox class="fontcheckbox" v-model="input.ami"
                      true-value="AMI" color="primary"/><span v-html="highlightMatch('AMI')" class="highlighted-label"></span><br>
                      <VCheckbox class="fontcheckbox" v-model="input.chf"
                      true-value="CHF" color="primary"/><span v-html="highlightMatch('CHF')" class="highlighted-label"></span><br>
                  </div>
                  <div class="column is-4">
                      <VCheckbox class="fontcheckbox" v-model="input.hepatitis"
                      true-value="Hepatitis B-C" color="primary"/><span v-html="highlightMatch('Hepatitis B-C')" class="highlighted-label"></span><br>
                      <VCheckbox class="fontcheckbox" v-model="input.hiv"
                      true-value="HIV / AIDS" color="primary"/><span v-html="highlightMatch('HIV / AIDS')" class="highlighted-label"></span><br>
                      <VCheckbox class="fontcheckbox" v-model="input.lainnya2"
                      true-value="Lainnya2" color="primary"/>
                      <textarea v-model="input.lainnya22" class="textarea" placeholder="" rows="1"></textarea>
                  </div>
              </div>
            </div>
          </div>
        </div>
        
        <hr>

        <div class="column is-12 mb-0 mt-0">
          <div class="columns is-multiline">
            <div class="column is-6">
              <div class="columns is-multiline">
                <div class="column is-12 pt-0 mt-2">
                  <h1 style="">Data obyektif (pemeriksaan fisik)</h1>
                </div>
              </div>
            </div>
            <div class="column is-6">
              <div class="columns is-multiline">
                <div class="column is-12 pt-0 mt-2">
                  <h1 style=" text-align: right">Hasil pemeriksaan penunjang yang telah teridentifikasi</h1>
                </div>
              </div>
            </div>
          </div>
        </div>

        <hr>

        <div class="column is-12 mb-0 mt-0">
          <div class="columns is-multiline">
            <div class="column is-6">
              <div class="columns is-multiline">
                  <div class="column is-4">
                      <h1>Pemeriksaan</h1>
                  </div>
                  <div class="column is-2">
                      <h1>Jam :</h1>
                  </div>
                  <div class="column is-6">
                      <VField>
                          <VDatePicker v-model="input.jamPemeriksaan" color="green" mode="time" is24hr>
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
                  <div class="column is-4" style="margin-top: -20px;">
                      <h1>Tekanan darah</h1>
                  </div>
                  <div class="column is-8" style="margin-top: -20px;">
                      <VField addons>
                          <VControl expanded>
                              <VInput type="text" class="input" placeholder="Tekanan Darah" v-model="input.tekananDarahObgyn" />
                          </VControl>
                          <VControl class="field-addon-body">
                              <VButton static>mmHg</VButton>
                          </VControl>
                      </VField>
                  </div>
                  <div class="column is-4" style="margin-top: -10px;">
                      <h1>Frekuensi nafas</h1>
                  </div>
                  <div class="column is-8" style="margin-top: -10px;">
                      <VField addons>
                          <VControl expanded>
                              <VInput type="text" class="input" placeholder="" v-model="input.nafasObgyn" />
                          </VControl>
                          <VControl class="field-addon-body">
                              <VButton static>x/menit</VButton>
                          </VControl>
                      </VField>
                  </div>
                  <div class="column is-4" style="margin-top: -10px;">
                      <h1>Nadi</h1>
                  </div>
                  <div class="column is-8" style="margin-top: -10px;">
                      <VField addons>
                          <VControl expanded>
                              <VInput type="text" class="input" placeholder="" v-model="input.nadiObgyn" />
                          </VControl>
                          <VControl class="field-addon-body">
                              <VButton static>x/menit</VButton>
                          </VControl>
                      </VField>
                  </div>
                  <div class="column is-4" style="margin-top: -10px;">
                      <h1>Suhu aksila</h1>
                  </div>
                  <div class="column is-8" style="margin-top: -10px;">
                      <VField addons>
                          <VControl expanded>
                              <VInput type="text" class="input" placeholder="" v-model="input.celciusObgyn" />
                          </VControl>
                          <VControl class="field-addon-body">
                              <VButton static>°C </VButton>
                          </VControl>
                      </VField>
                  </div>
                  <div class="column is-4" style="margin-top: -10px;">
                      <h1>Abdomen</h1>
                  </div>
                  <div class="column is-8" style="margin-top: -10px;">
                      <VField>
                          <VControl>
                              <VInput type="text" class="input" placeholder="" v-model="input.abdomen" />
                          </VControl>
                      </VField>
                  </div>
                  <div class="column is-4" style="margin-top: -10px;">
                      <h1>Thorax</h1>
                  </div>
                  <div class="column is-8" style="margin-top: -10px;">
                      <VField>
                          <VControl>
                              <VInput type="text" class="input" placeholder="" v-model="input.thorax" />
                          </VControl>
                      </VField>
                  </div>
                  <div class="column is-4" style="margin-top: -10px;">
                      <h1>Extermitas</h1>
                  </div>
                  <div class="column is-8" style="margin-top: -10px;">
                      <VField>
                          <VControl>
                              <VInput type="text" class="input" placeholder="" v-model="input.extermitas" />
                          </VControl>
                      </VField>
                  </div>
              </div>
            </div>
            <div class="column is-6">
              <div class="columns is-multiline">
                  <div class="column is-6">
                      <h1>Secara benar</h1>
                  </div>
                  <div class="column is-6">
                      <VField>
                          <VControl>
                              <VInput type="text" class="input" placeholder="" v-model="input.extermitas" />
                          </VControl>
                      </VField>
                  </div>
                  <div class="column is-6" style="margin-top: -5px;">
                      <VCheckbox class="fontcheckbox" v-model="input.rontgen"
                      true-value="Foto Rontgen" color="primary"/><span v-html="highlightMatch('Foto Rontgen')" class="highlighted-label"></span>
                  </div>
                  <div class="column is-6" style="margin-top: -5px;">
                      <VCheckbox class="fontcheckbox" v-model="input.laboratorium"
                      true-value="Laboratorium" color="primary"/><span v-html="highlightMatch('Laboratorium')" class="highlighted-label"></span>
                  </div>
                  <div class="column is-6" style="margin-top: -5px;">
                      <VCheckbox class="fontcheckbox" v-model="input.ctscan"
                      true-value="CT-Scan" color="primary"/><span v-html="highlightMatch('CT-Scan')" class="highlighted-label"></span>
                  </div>
                  <div class="column is-1" style="margin-top: -5px;">
                      <VCheckbox class="fontcheckbox" v-model="input.ya1" true-value="Ya" color="primary"/>
                  </div>
                  <div class="column is-5" style="margin-top: -5px;">
                      <textarea v-model="input.textya1" class="textarea" placeholder="" rows="1"></textarea>
                  </div>
                  <div class="column is-6" style="margin-top: -5px;">
                      <VCheckbox class="fontcheckbox" v-model="input.mri"
                      true-value="MRI" color="primary"/><span v-html="highlightMatch('MRI')" class="highlighted-label"></span>
                  </div>
                  <div class="column is-1" style="margin-top: -5px;">
                      <VCheckbox class="fontcheckbox" v-model="input.ya2" true-value="Ya" color="primary"/>
                  </div>
                  <div class="column is-5" style="margin-top: -5px;">
                      <textarea v-model="input.textya2" class="textarea" placeholder="" rows="1"></textarea>
                  </div>
                  <div class="column is-6" style="margin-top: -5px;">
                      <VCheckbox class="fontcheckbox" v-model="input.usg"
                      true-value="USG" color="primary"/><span v-html="highlightMatch('USG')" class="highlighted-label"></span>
                  </div>
                  <div class="column is-1" style="margin-top: -5px;">
                      <VCheckbox class="fontcheckbox" v-model="input.ya3" true-value="Ya" color="primary"/>
                  </div>
                  <div class="column is-5" style="margin-top: -5px;">
                      <textarea v-model="input.textya3" class="textarea" placeholder="" rows="1"></textarea>
                  </div>
                  <div class="column is-6" style="margin-top: -5px;">
                      <VCheckbox class="fontcheckbox" v-model="input.usg"
                      true-value="EKG" color="primary"/><span v-html="highlightMatch('EKG')" class="highlighted-label"></span>
                  </div>
                  <div class="column is-1" style="margin-top: -5px;">
                      <VCheckbox class="fontcheckbox" v-model="input.ya4" true-value="Ya" color="primary"/>
                  </div>
                  <div class="column is-5" style="margin-top: -5px;">
                      <textarea v-model="input.textya4" class="textarea" placeholder="" rows="1"></textarea>
                  </div>
                  <div class="column is-6" style="margin-top: -5px;">
                      <h1>Riwayat operasi sebelumnya</h1>
                  </div>
                  <div class="column is-6" style="margin-top: -5px;">
                      <textarea v-model="input.riwayatoperasi" class="textarea" placeholder="" rows="1"></textarea>
                  </div>
                  <div class="column is-6" style="margin-top: -5px;">
                      <h1>Riwayat alergi</h1>
                  </div>
                  <div class="column is-6" style="margin-top: -5px;">
                      <textarea v-model="input.riwayatalergi" class="textarea" placeholder="" rows="1"></textarea>
                  </div>
                  <div class="column is-6" style="margin-top: -5px;">
                      <h1>Dokumen rekam medis terkait</h1>
                  </div>
                  <div class="column is-6" style="margin-top: -5px;">
                      <textarea v-model="input.dokumenrm" class="textarea" placeholder="" rows="1"></textarea>
                  </div>
              </div>
            </div>
          </div>
        </div>

        <hr>

        <div class="column is-12 mb-0 mt-0">
          <div class="columns is-multiline">
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-12 pt-0 mt-5">
                  <h1>Catatan Penting</h1>
                  <VField>
                      <VControl>
                          <VTextarea v-model="input.catatanpenting" placeholder="" rows="3"></VTextarea>
                      </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </div>

        <hr>

        <div class="column is-12 mb-0 mt-0">
          <div class="columns is-multiline">
            <div class="column is-6">
              <div class="columns is-multiline">
                <div class="is-6" style="margin-top: 10px;">
                  <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" @click="DiagnosaAsmed()">
                    Diagnosa ASMED
                  </VButton>
                </div>
                <div class="is-6" style="margin-left: 10px;margin-top: 10px;">
                  <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" @click="DiagnosaCPPT()">
                    Diagnosa CPPT
                  </VButton>
                </div>
                <div class="column is-12 pt-0 mt-5">
                  <h1>Diagnosa pra-operasi</h1>
                  <VField>
                      <VControl>
                          <VTextarea v-model="input.diagnosapra" placeholder="" rows="3"></VTextarea>
                      </VControl>
                  </VField>
                </div>
                <div class="column is-12 pt-0">
                  <h1>Posisi pasien dalam operasi</h1>
                  <VField>
                      <VControl>
                          <VTextarea v-model="input.posisiop" placeholder="" rows="3"></VTextarea>
                      </VControl>
                  </VField>
                </div>
                <div class="column is-12 pt-0">
                  <h1>Profilaksis</h1>
                  <VField>
                      <VControl>
                          <VTextarea v-model="input.profilaksis" placeholder="" rows="1"></VTextarea>
                      </VControl>
                  </VField>
                </div>
                <div class="column is-12 pt-0">
                  <h1>Alat khusus</h1>
                  <VField>
                      <VControl>
                          <VTextarea v-model="input.alatkhusus" placeholder="" rows="1"></VTextarea>
                      </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-6">
              <div class="columns is-multiline">
                <div class="column is-12 pt-0 mt-5">
                  <h1>Persiapan darah</h1>
                  <VField>
                      <VControl>
                          <VTextarea v-model="input.persiapandarah" placeholder="" rows="1"></VTextarea>
                      </VControl>
                  </VField>
                </div>
                <div class="column is-12 pt-0">
                  <h1>Rencana operasi</h1>
                  <VField>
                      <VControl>
                          <VTextarea v-model="input.rencanaoperasi" placeholder="" rows="12"></VTextarea>
                      </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </div>

        <hr>

        <div class="column is-12 mb-0 mt-0">
          <div class="columns is-multiline">
            <div class="column is-6 mt-5">
              <div class="columns is-multiline">
                <div class="column is-12 pt-0 mt-5">
                  <h1>Dengan ini saya menyatakan bahwa saya telah menerima
                  informasi tentang tujuan dan pentingnya dilakukan assesment
                  pra operasi dan penandaan pada area operasi saya mengerti
                  serta memahami hal tersebut</h1>
                  <div class="column" style="text-align:center;">
                      <h1>Tanda tangan pasien / keluarga</h1>
                      <TandaTangan :elemenID="'TTDDokter1'" :width="'150'" :height="'150'" class="dek" />
                      <VField class="is-autocomplete-select">
                          <VControl>
                              <VInput type="text" class="input" placeholder="" v-model="item.ttdPasien" />
                          </VControl>
                      </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-6 mt-5">
              <div class="columns is-multiline mt-5">
                  <div class="column" style="text-align:center; margin-top: 45px;">
                      <h1>Operator</h1>
                      <TandaTangan :elemenID="'TTDDokter2'" :width="'150'" :height="'150'" class="dek" />
                      <VControl class="prime-auto">
                          <AutoComplete v-model="input.CBDokter" :suggestions="d_Dokter"
                              @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                              :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                              :field="'label'" class="mt-2" />
                      </VControl>
                  </div>
              </div>
            </div>
          </div>
        </div>

        <hr>

        <div class="column is-12 mt-5">
          <h1 style="font-size:larger;font-weight:bold; text-align: center;">SITE MARKING LAKI LAKI</h1>
          <div class="columns is-multiline" style="margin-top: 50px;">
              <div class="column is-6" style="padding-left: 20px;">
                  <div class="columns is-multiline">
                      <div class="column is-2">
                          <span class="label-ro">Prosedur</span>
                      </div>
                      <div class="column is-9">
                          <VField class="pt-3">
                              <VControl>
                                  <VInput type="text" v-model="input.namaprosedur" style="margin-top: -15px;"/>
                              </VControl>
                          </VField>
                      </div>
                  </div>
              </div>
              <div class="column is-6" style="padding-right: 20px;">
                  <div class="columns is-multiline">
                      <div class="column is-3">
                          <span class="label-ro">Tgl Prosedur</span>
                      </div>
                      <div class="column is-9">
                          <VDatePicker v-model="input.tglProsedur" class="pt-3" mode="dateTime" style="width: 100%; margin-top: -15px;" trim-weeks
                          :max-date="new Date()">
                          <template #default="{ inputValue, inputEvents }">
                              <VField>
                              <VControl icon="feather:calendar" fullwidth>
                                  <VInput :value="inputValue" placeholder="Tanggal Prosedur" v-on="inputEvents" />
                              </VControl>
                              </VField>
                          </template>
                          </VDatePicker>
                      </div>
                  </div>
              </div>
              <div class="column is-12 mb-0 mt-5">
                <div class="columns is-multiline">
                  <div class="column is-6 mt-5">
                      <ImgDraw elemenID="Gambar1" height="780" width="600" imageSrc="/images/simrs/fullbody1.png"/>
                      <ImgDraw elemenID="Gambar6" height="300" width="600" imageSrc="/images/simrs/kaki.png" class="mt-5"/>
                  </div>
                  <div class="column is-6 mt-5">
                      <ImgDraw elemenID="Gambar2" height="300" width="600" imageSrc="/images/simrs/kepalasamping.png"/>
                      <ImgDraw elemenID="Gambar3" height="300" width="600" imageSrc="/images/simrs/kepaladepan.png"/>
                      <ImgDraw elemenID="Gambar4" height="300" width="600" imageSrc="/images/simrs/tanganatas.png"/>
                      <ImgDraw elemenID="Gambar5" height="300" width="600" imageSrc="/images/simrs/tanganbawah.png"/>
                  </div>
                </div>
              </div>
              <TOdontogram></TOdontogram>
              <div class="column is-12 mb-0 mt-5">
                  <h1 style="font-size:14px;font-weight:bold; text-align: center;">Saya menyatakan bahwa lokasi operasi yang telah ditetapkan pada diagram adalah benar</h1>
              </div>
              <div class="column is-12 mb-0 mt-5">
                <div class="columns is-multiline">
                  <div class="column is-6 mt-5">
                      <div class="columns is-multiline">
                      <div class="column is-12 pt-0 mt-5">
                          <div class="column" style="text-align:center;">
                              <h1>Nama dan Tanda tangan pasien</h1>
                              <TandaTangan :elemenID="'TTDDokter3'" :width="'150'" :height="'150'" class="dek" />
                              <VField class="is-autocomplete-select">
                                  <VControl>
                                      <VInput type="text" class="input" placeholder="" v-model="item.ttdPasien1" />
                                  </VControl>
                              </VField>
                          </div>
                      </div>
                      </div>
                  </div>
                  <div class="column is-6 mt-5">
                      <div class="columns is-multiline">
                          <div class="column" style="text-align:center; margin-top: 14px;">
                              <h1>Nama dan Tanda tangan dokter yang merawat</h1>
                              <TandaTangan :elemenID="'TTDDokter4'" :width="'150'" :height="'150'" class="dek" />
                              <VControl class="prime-auto">
                                  <AutoComplete v-model="input.CBDokter1" :suggestions="d_Dokter"
                                      @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                      :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                      :field="'label'" class="mt-2" />
                              </VControl>
                          </div>
                      </div>
                  </div>
                  </div>
              </div>
          </div>
        </div>

      </div>
    </div>
  </div>
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
    @close="showModalTemplateFix = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12 pt-0 pb-0">
          <span style="font-size:9pt;font-weight:bold">List Template</span>
          <div style="overflow-y:auto;" class="mt-1">
            <table class="tg table-tg" v-if="listTemplateFix.length > 0">
              <thead>
                <tr>
                  <td class="tg-0lax text-center" width="15%">#</td>
                  <td class="tg-0lax text-center" width="15%">No</td>
                  <td class="tg-0lax text-center" width="20%">Tanggal Dibuat</td>
                  <td class="tg-0lax text-center" width="20%">Nama Ruangan</td>
                  <td class="tg-0lax text-center" width="50%">Nama Template</td>
                </tr>
              </thead>
              <tbody v-for="resep in listTemplateFix">
                <tr>
                  <td style="width:15%;text-align:center">
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)" color="info"
                      v-tooltip-prime.top="'Pilih'">
                    </VIconButton>
                  </td>
                  <td style="width:15%;text-align:center">
                    <span class="mb-2">{{ resep.no }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center">
                    <span class="mb-2">{{ resep.created_at }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center">
                    <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                  </td>
                  <td style="width:50%;text-align:center">
                    <span class="mb-2">{{ resep.namatemplate }}</span><br>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </form>
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
import TOdontogram from './odontogram.vue'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import * as EMR from '../page-emr-plugins/asesmen-awal-keper-rj'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from "primevue/useconfirm"
import moment from 'moment'
import { useToaster } from '/@src/composable/toaster'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import ImgDraw from '../page-emr-plugins/img-draw.vue'




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

const route = useRoute()
const pasien: any = ref({})
const d_pegawai: any = ref([])
const loadData: any = ref(true)
const d_Dokter: any = ref([])
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

const COLLECTION: any = ref('AssesmenPraOperasiLaki') //table mongodb

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
})
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
const d_allo: any = ref([{ value: 1, label: 'Suami/Istri' }, { value: 2, label: 'Orang Tua' }, { value: 3, label: 'Anak' }, { value: 4, label: 'Pasien' }, { value: 5, label: 'Lainnya' }])
const d_mata: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
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
const dataTTD: any = ref([])

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

const setAutoFill = async () => {
  const response_NS = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=AsesmenAwalKeperawatanPasienRawatJalanNurse" + `&field=tekananDarahObgyn,nadiObgyn,nafasObgyn,celciusObgyn,sao2Obgyn`)
  if (response_NS != null) {
        input.value.tekananDarahObgyn = response_NS.tekananDarahObgyn;
        input.value.nafasObgyn = response_NS.nafasObgyn;
        input.value.nadiObgyn = response_NS.nadiObgyn;
        input.value.celciusObgyn = response_NS.celciusObgyn;
        input.value.sao2Obgyn = response_NS.sao2Obgyn;
    }

}

let PilihdiagnosaAsmed = 0;
let PilihdiagnosaCPPT = 0;

const DiagnosaAsmed = async () => {
  PilihdiagnosaAsmed++;
  if  (PilihdiagnosaAsmed >= 2) {
    input.value.diagnosapra = '';
    PilihdiagnosaAsmed = 0;
  } else {
    const response_Asmed = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=AsesmenMedisRawatJalan" + `&field=TADiagnosa`)
    if (response_Asmed != null) {
    input.value.diagnosapra = response_Asmed.TADiagnosa
    }
  }
}

const DiagnosaCPPT = async () => {
  PilihdiagnosaCPPT++;
  if  (PilihdiagnosaCPPT >= 2) {
    input.value.diagnosapra = '';
    PilihdiagnosaCPPT = 0;
  } else {
    const response_CPPT = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=CPPTDetail" + `&field=A`)
    if (response_CPPT != null) {
    input.value.diagnosapra = response_CPPT.A
    }
  }
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
        dataTTD.value = response[0]
        H.tandaTangan().set("TTDDokter1", dataTTD.value.TTDDokter1)
        H.tandaTangan().set("TTDDokter2", dataTTD.value.TTDDokter2)
        H.tandaTangan().set("TTDDokter3", dataTTD.value.TTDDokter3)
        H.tandaTangan().set("TTDDokter4", dataTTD.value.TTDDokter4)

      }
    })

    loadGambar("Gambar1", input.value.Gambar1)
    loadGambar("Gambar2", input.value.Gambar2)
    loadGambar("Gambar3", input.value.Gambar3)
    loadGambar("Gambar4", input.value.Gambar4)
    loadGambar("Gambar5", input.value.Gambar5)
    loadGambar("Gambar6", input.value.Gambar6)
}

const loadGambar = async (element_id: string, value: string) => {
  let sigCanvas: any = document.getElementById(element_id);
  if (sigCanvas) {
      let context = sigCanvas.getContext("2d");
      context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
      let imagess = value
      let background = new Image();
      background.src = imagess
      background.onload = function () {
          context.drawImage(background, 0, 0, 680, 680);
      }
  }
}

const simpan = () => {

  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.nocm = pasien.value.nocm
  object['TTDDokter1'] = H.tandaTangan().get("TTDDokter1");
  object['TTDDokter2'] = H.tandaTangan().get("TTDDokter2");
  object['TTDDokter3'] = H.tandaTangan().get("TTDDokter3");
  object['TTDDokter4'] = H.tandaTangan().get("TTDDokter4");

  object['Gambar1'] = H.tandaTangan().get("Gambar1");
  object['Gambar2'] = H.tandaTangan().get("Gambar2");
  object['Gambar3'] = H.tandaTangan().get("Gambar3");
  object['Gambar4'] = H.tandaTangan().get("Gambar4");
  object['Gambar5'] = H.tandaTangan().get("Gambar5");
  object['Gambar6'] = H.tandaTangan().get("Gambar6");


  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': route.name,
    'name_form': 'Assesment Pra Operasi Laki-Laki',
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

const kembaliKeun = () => {
  window.history.back()
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

const addTemplate = (response: any) => {
  console.log(response)
  input.value = response //set ke inputan
  input.value.namatemplate = null
}

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


watch(() => [input.value.penurunanbb, input.value.penurunannafsu, input.value.penurunanbbYa], ([newValuePenurunanBB, newValuePenurunanBBYa, newValuePenurunanNafsu]) => {
  console.log('Selected penurunan:', newValuePenurunanBB, newValuePenurunanBBYa, newValuePenurunanNafsu);
  let totalNilaiSkriningKalkulasi
  totalNilaiSkriningKalkulasi = newValuePenurunanBB + newValuePenurunanBBYa + newValuePenurunanNafsu
  console.log(totalNilaiSkriningKalkulasi)
  input.value.nilaiSkrining = totalNilaiSkriningKalkulasi

  if (totalNilaiSkriningKalkulasi >= 0 && totalNilaiSkriningKalkulasi <= 1) {
    input.value.nilai = "RISIKO RENDAH (MST 0-1)";
  } else if (totalNilaiSkriningKalkulasi >= 2 && totalNilaiSkriningKalkulasi <= 3) {
    input.value.nilai = "RISIKO SEDANG (MST 2-3)";
  } else if (totalNilaiSkriningKalkulasi >= 4) {
    input.value.nilai = "RISIKO TINGGI (MST 4-5)";
  }
});

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
  let totalNilaiStatusFungsional

  totalNilaiStatusFungsional = newValueMengontrolBab + newValueMengontrolBak + newValueBersihDiri + newValueToilet + newValueMakan + newValueBerpindahTT + newValueMobilisasi + newValueBerpakaian + newValueTangga + newValueMandi

  input.value.nilaimandi = totalNilaiStatusFungsional

  if (totalNilaiStatusFungsional >= 0 && totalNilaiStatusFungsional <= 4) {
    input.value.CBKetergantunganTotal = "Ketergantungan total (0-4)"
    input.value.CBKetergantunganBerat = false
    input.value.CBKetergantunganSedang = false
    input.value.CBKetergantunganRingan = false
    input.value.CBKMandiriK = false
  } else if (totalNilaiStatusFungsional >= 5 && totalNilaiStatusFungsional <= 8) {
    input.value.CBKetergantunganTotal = false
    input.value.CBKetergantunganBerat = "Ketergantungan berat (5-8)"
    input.value.CBKetergantunganSedang = false
    input.value.CBKetergantunganRingan = false
    input.value.CBKMandiriK = false
  } else if (totalNilaiStatusFungsional >= 9 && totalNilaiStatusFungsional <= 11) {
    input.value.CBKetergantunganTotal = false
    input.value.CBKetergantunganBerat = false
    input.value.CBKetergantunganSedang = "Ketergantungan sedang (9-11)"
    input.value.CBKetergantunganRingan = false
    input.value.CBKMandiriK = false
  } else if (totalNilaiStatusFungsional >= 12 && totalNilaiStatusFungsional <= 19) {
    input.value.CBKetergantunganTotal = false
    input.value.CBKetergantunganBerat = false
    input.value.CBKetergantunganSedang = false
    input.value.CBKetergantunganRingan = "Ketergantungan ringan(12-19)"
    input.value.CBKMandiriK = false
  } else if (totalNilaiStatusFungsional >= 20) {
    input.value.CBKetergantunganTotal = false
    input.value.CBKetergantunganBerat = false
    input.value.CBKetergantunganSedang = false
    input.value.CBKetergantunganRingan = false
    input.value.CBKMandiriK = "Mandiri (20)"
  }
});

const diagnosesOptions = [
  { text: "Nyeri akut b/d kondisi fisik", value: "nyeriakut" },
  { text: "Bersihan jalan nafas tidak efektif b/d alergi jalan nafas, adanya eksudat di jalan nafas /", value: "Bersihan" },
  { text: "Risiko / Penurunan curah jantung b/d anomaly jantung / peningkatan beban", value: "Risiko" },
  { text: "Risiko / Kekurangan Volume Cairan b/d kehilangan volume cairan ", value: "Risiko" },
  { text: "Kurang pengetahuan tentang penyakit, rencana tindakan dan pengobatan b/d kurang terpanjannya", value: "Kurang pengetahuan" },
  { text: "Ansietas b/d krisis situasi, kebutuhan yang tidak", value: "Ansietas b/d" },
  { text: "Risiko gangguan integritas", value: "kulit" },
  { text: "Kelebihan volume cairan b/d asupan cairan", value: "berlebihan" },
  { text: "Kesiapan meningkatkan status", value: "kesehatan" },
  { text: "Ketidakefektifan pemeliharaan kesehatan b/d hambatan", value: "kognitif" },
  { text: "Hambatan mobilitas fisik b/d intoleran", value: "aktivitas" },
  { text: "Diare akut b/d mal absorpsi, peningkatan motilitas", value: "usus" },
  { text: "Nausea b/d biofisik, psikologis, pemberian kemoterapi, pemberian ", value: "steroid" },
  { text: "Risiko Ketidakstabilan kadar Glukosa Darah b/d Kurang pengetahuan tentang manajemen diabetes, Asupan diet,", value: "Pemantauan glukosa darah tidak adekuat" },
  { text: "Hipertemia b/d kekurangan cairan, proses infeksi, gangguan", value: "termoregulasi" },
  { text: "Gangguan fungsi", value: "gigi" },
  { text: "Gangguan jaringan keras", value: "gigi" },
  { text: "Gangguan jaringan lunak dan pendukung", value: "gigi" },
  { text: "Gangguan", value: "estetika" },
  { text: "Gangguan persepsi ", value: "sensori" },
  { text: "Risiko jatuh b/d riwayat terjatuh / usia lebih dari 65 th / menggunakan alat bantu (walker, tongkat, kursi roda) / ", value: " sulit penglihatan" },
  { text: "Pola pemberian ASI tidak efektif b/d ketidakefektifan pemeliharaan", value: "kesehatan" },
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

setAutoFill()
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
</style>
