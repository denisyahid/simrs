<template>
  <ConfirmDialog />
  <div :class="[!isStuck && 'px-0 mb-4']" class="form-layout is-stacked-2" style="width: 100%; max-width: none;">
    <div class="form-outer" style="margin-top:15px" v-if="!hideButtons">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>{{ FORM_NAME }}</h3>
            <div v-if="kelompokUser == 'dokter'">
              <VButton v-if="isResumeMedis" color="danger" bold style="height: 10px !important">
                Resume Medis Belum Dibuat
              </VButton>
              <VButton v-else color="primary" bold style="height: 10px !important">
                Resume Medis Sudah Dibuat
              </VButton>
            </div>
          </div>
          <div class="buttons" v-if="editMode == false">
            <VButton type="button" rounded outlined color="warning" raised icon="lnir lnir-printer" @click="print">
              Cetak
            </VButton>
            <VButton type="button" rounded outlined color="info" raised icon="feather:airplay" :loading="isLoading"
              @click="simpan('')"> Preview
            </VButton>
            <VButton type="button" outlined rounded color="purple" raised icon="feather:save" :loading="isLoading"
              :disabled="paramRiwayat" @click="simpanTemplate()"> Simpan Template
            </VButton>
            <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
              :disabled="paramRiwayat" @click="simpanReal()"> Simpan
            </VButton>
          </div>
          <div class="buttons" v-else>
            <VButton type="button" rounded outlined color="info" raised icon="feather:save" :loading="isLoading"
              :disabled="paramRiwayat" @click="editReal()"> Simpan Edit
            </VButton>
          </div>
        </div>
        <!--? Khusus Dokter  -->
        <div class="column py-2" v-if="kelompokUser == 'dokter'">
          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
        </div>
        <div class="form-header-inner" v-if="kelompokUser == 'dokter'">
          <VButton type="button" rounded outlined color="info" @click="modalTindakan = true" icon="lucide:layers">
            Tindakan
          </VButton>
          <VButton type="button" rounded outlined color="info" @click="modalResep = true" icon="lnir lnir-medical-sign">
            Resep
          </VButton>
          <VButton type="button" rounded outlined color="info" @click="modalKonsultasi = true" icon="lucide:send">
            Transfer Pasien
          </VButton>
          <VButton type="button" rounded outlined color="info" @click="modalLaboratorium = true"
            icon="fas fa-temperature-high">
            Laboratorium
          </VButton>
          <VButton type="button" rounded outlined color="info" @click="modalRadiologi = true" icon="fas fa-radiation">
            Radiologi
          </VButton>
          <VButton type="button" rounded outlined color="info" @click="modalPenunjangKhusus = true"
            icon="lucide:file-text">
            Penunjang Khusus
          </VButton>
          <VButton type="button" rounded outlined color="info" @click="modalBedah = true" icon="lnil lnil-cut">
            Bedah
          </VButton>
        </div>
      </div>
    </div>
  </div>

  <!--? CPPT  -->
  <div class="columns is-multiline p-2">
    <div class="column is-12" style=" margin-top: 1.8rem;" v-if="isloadingLAMPAU">
      <VProgress size="tiny" color="info" />
    </div>
    <div class="columns is-multiline">
      <div class="column is-12" style="margin-left: 0px;">
        <VCard>
          <div class="columns is-multiline mt-3">
            <!--? Header  -->
            <div>
              <div class="column is-12 pt-0" v-if="!hideButtons">
                <div class="columns is-multiline">
                  <div class="column is-9 pt-0">
                    <h1>Nama Template&emsp;&emsp;<span style="color: rgb(230, 41, 100);">**Hanya diisi jika ingin
                        membuat template</span></h1>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.namatemplate" :disabled="editMode" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 mt-auto">
                    <VButton type="button" rounded outlined color="primary" raised icon="feather:folder"
                      :loading="isLoading" @click="pilihTemplateFix(index)"> Pilih Template
                    </VButton>
                  </div>
                </div>
              </div>
              <div class="column is-12 py-0">
                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
              </div>
              <div class="column is-12 pt-0">
                <div class="columns is-multiline">
                  <div class="column is-4" style="margin-top: 10px;">
                    <h1 class="mb-12 emr font-bold">Keadaan Umum</h1>
                    <VField class="is-autocomplete-select">
                      <VControl icon="feather:search">
                        <Multiselect v-model="input.keadaanumumobgyn" :attrs="{ value }" placeholder="--Pilih--"
                          label="label" :options="d_keadaanumum" :searchable="true" track-by="label" mode="single"
                          autocomplete="off" :disabled="editMode">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-8">
                    <h1 class="mt-2 emr font-bold">GCS</h1>
                    <div class="columns is-multiline">
                      <div class="column is-4">
                        <VField addons>
                          <VControl class="field-addon-body">
                            <VButton static>E</VButton>
                          </VControl>
                          <VControl expanded>
                            <Multiselect v-model="input.gcse" :attrs="{ value }" placeholder="E" label="label"
                              :options="d_gcse" :searchable="true" track-by="label" mode="single" autocomplete="off"
                              :disabled="editMode">
                            </Multiselect>
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-4">
                        <VField addons>
                          <VControl class="field-addon-body">
                            <VButton static>V</VButton>
                          </VControl>
                          <VControl expanded>
                            <Multiselect v-model="input.gcsv" :attrs="{ value }" placeholder="V" label="label"
                              :options="d_gcsv" :searchable="true" track-by="label" mode="single" autocomplete="off"
                              :disabled="editMode">
                            </Multiselect>
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-4">
                        <VField addons>
                          <VControl class="field-addon-body">
                            <VButton static>M</VButton>
                          </VControl>
                          <VControl expanded>
                            <Multiselect v-model="input.gcsm" :attrs="{ value }" placeholder="M" label="label"
                              :options="d_gcsm" :searchable="true" track-by="label" mode="single" autocomplete="off"
                              :disabled="editMode">
                            </Multiselect>
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <h1 style="font-weight: bold;">Tekanan Darah</h1>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="text" class="input" placeholder="Tekanan Darah" v-model="input.tekananDarah"
                          :disabled="editMode" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>mmHg</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <h1 style="font-weight: bold;">PR</h1>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="text" @keypress="onlyNumber($event)" class="input" placeholder="PR"
                          v-model="input.nadi" :disabled="editMode" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>x/menit</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <h1 style="font-weight: bold;">RR</h1>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="text" @keypress="onlyNumber($event)" class="input" placeholder="RR"
                          v-model="input.nafas" :disabled="editMode" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>x/menit</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <h1 style="font-weight: bold;">Suhu</h1>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="text" class="input" @keypress="onlyNumber($event)" placeholder="Suhu"
                          v-model="input.celcius" :disabled="editMode" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>°C </VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <h1 style="font-weight: bold;">SaO2</h1>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="text" class="input" @keypress="onlyNumber($event)"
                          placeholder="Saturasi O2 (SpO2)" v-model="input.sao2" :disabled="editMode" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <h1 style="font-weight: bold;">Berat Badan</h1>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="text" class="input" @keypress="onlyNumber($event)" placeholder="Berat Badan"
                          v-model="input.beratBadan" :disabled="editMode" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Kg</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <h1 style="font-weight: bold;">Tinggi Badan</h1>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="text" class="input" @keypress="onlyNumber($event)" placeholder="Tinggi Badan"
                          v-model="input.tinggiBadan" :disabled="editMode" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Cm</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 is-flex" style="justify-content: center;align-items: center;"
                    v-if="props.registrasi.namaruangan.toUpperCase().indexOf('RADIOTERAPI') > -1 || props.registrasi.namaruangan.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') > -1">
                    <VButton type="button" rounded outlined color="info" raised icon="feather:copy"
                      :isLoading="isLoading" @click="copyTTV()"> Copy TTV
                    </VButton>
                  </div>
                </div>
              </div>
            </div>
            <!--? Header  -->

            <!--? Inputan  -->
            <div class="column is-8" style="overflow-y: auto;" id="form-step-0" v-if="currentStep >= 0">
              <table class="tg table-tg" style="width:100% !important">
                <tbody v-for="(item, index) in dataSourceFiltered" :key="index">
                  <!--? Dokter, Perawat, dll  -->
                  <tr v-if="item.flag != 'gizi' && !item.isDeleted" :style="[item.flag == 'dokter' ? 'background-color: var(--danger--light-color) !important;' : (item.flag == 'perawat' ? 'background-color: var(--info--light-color) !important;' : '')]">
                    <td>
                      <table class="tg" style="width:100% !important;">
                        <tr>
                          <td colspan="4">
                            <div class="columns is-multiline">
                              <div class="column is-12">
                                <VField>
                                  <VControl class="prime-auto">
                                    <Calendar v-model="input.details[item.originalIndex].tgl" selectionMode="single"
                                      :manualInput="true" class="w-100" :showIcon="true" showTime hourFormat="24"
                                      :date-format="H.dateTimeFormat().prime.date" :disabled="item.tgl2" />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-12">
                                <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                  <VControl icon="fa:stethoscope" fullwidth class="prime-auto ">
                                    <AutoComplete v-model="input.details[item.originalIndex].tenagaMedis"
                                      :suggestions="d_Pegawai" @complete="fetchPegawai($event)" :optionLabel="'label'"
                                      :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                      :field="'label'" placeholder="Tenaga Medis..." :disabled="item.tenagaMedis2" />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="column is-6 p-0 is-flex" style="justify-content: center;align-items: center;"
                              v-if="props.registrasi.namaruangan.toUpperCase().indexOf('BEDAH MULUT') > -1 || props.registrasi.namaruangan.toUpperCase().indexOf('IGD') > -1">
                              <VButton type="button" rounded outlined color="success" raised icon="feather:file"
                                :loading="isLoading" @click="checkAsmed()"> Copy Data Asesmen Medis
                              </VButton>
                            </div>
                            <div class="columns mr-3 mt-0" style="justify-content: end;" v-else>
                              &nbsp;
                            </div>
                            <h1 style="font-weight: bold;" class="mt-5">Subjective:</h1>
                            <VField>
                              <VControl>
                                <VTextarea v-model="input.details[item.originalIndex].S" rows="5" placeholder=""
                                  :disabled="item.S2">
                                </VTextarea>
                              </VControl>
                            </VField>
                          </td>
                          <td v-if="item.flag == 'laras'">
                            <VIconButton color="danger" outlined circle icon="lnil lnil-trash-can-alt-1"
                              @click="clearItem(item.originalIndex, 'S')" style="float:right"
                              v-tooltip-prime.top="'Hapus'">
                            </VIconButton>
                          </td>
                          <td>
                            <div class="columns mr-3 mt-0" style="justify-content: end;">
                              <div class="mr-2">
                                <!-- <VIconButton type="button" raised circle icon="lucide:check-circle"
                                  @click="showLokalis(item.originalIndex)" :disabled="item.button"
                                  :loading="isLoadingBill" color="success" v-tooltip-prime.top="'Set Lokalis'"
                                  v-if="props.registrasi.namaruangan.toUpperCase().indexOf('MATA') > -1 && userLogin.kelompokUser.kelompokUser.toUpperCase().indexOf('DOKTER') > -1">
                                </VIconButton> -->
                                <VIconButton type="button" raised circle icon="lucide:check-circle"
                                  @click="handleCopyAndShowLokalis(item.originalIndex, item, true)"
                                  :disabled="item.button" :loading="isLoadingBill" color="success"
                                  v-tooltip-prime.top="'Set Lokalis'"
                                  v-if="(props.registrasi.namaruangan.toUpperCase().indexOf('MATA') > -1 || props.registrasi.objectdepartemenfk == 16 || props.registrasi.namadepartemen.indexOf('Rawat Inap') > -1) && userLogin.kelompokUser.kelompokUser.toUpperCase().indexOf('DOKTER') > -1">
                                </VIconButton>
                              </div>
                              <div class="mr-2">
                                <VIconButton type="button" raised circle icon="fas fa-file-medical-alt"
                                  @click="setPenunjang(item.originalIndex)" :disabled="item.button"
                                  :loading="isLoadingBill" color="success" v-tooltip-prime.top="'Set Penunjang'">
                                </VIconButton>
                              </div>
                              <div class="mr-2" v-if="isRanap">
                                <VIconButton type="button" raised circle icon="feather:book"
                                  @click="riwayatVitalSign(item.originalIndex)" :disabled="item.button"
                                  :loading="isLoadingBill" color="success" v-tooltip-prime.top="'Riwayat Vital Sign'">
                                </VIconButton>
                              </div>
                            </div>
                            <h1 style="font-weight: bold;">Objective:</h1>
                            <VField>
                              <VControl>
                                <VTextarea style="height: auto; " v-model="input.details[item.originalIndex].O"
                                  :autogrow="true" rows="5" placeholder="" :disabled="item.O2">
                                </VTextarea>
                              </VControl>
                            </VField>
                          </td>
                          <td v-if="item.flag == 'laras'">
                            <VIconButton color="danger" outlined circle icon="lnil lnil-trash-can-alt-1"
                              @click="clearItem(item.originalIndex, 'O')" style="float:right"
                              v-tooltip-prime.top="'Hapus'">
                            </VIconButton>
                          </td>
                        </tr>
                        <tr>
                          <td v-if="item.flag == 'profesi lain' || item.flag == 'dokter' || item.flag == 'perawat'">
                            <div class="columns ml-3 mt-0" style="justify-content: end; margin-right: 10px;">
                              <div class="mr-2">
                                <VIconButton type="button" raised circle icon="fas fa-stethoscope"
                                  @click="addDiagnosaTen(item.originalIndex)" :loading="isLoadingBill" color="success"
                                  :disabled="item.button" v-tooltip-prime.top="'Diagnosa ICD 10'">
                                </VIconButton>
                              </div>
                              <div class="mr-2">
                                <VIconButton type="button" raised circle icon="fas fa-file-medical-alt"
                                  @click="konselor(item.originalIndex)" :disabled="item.button" color="success"
                                  v-tooltip-prime.top="'Diagnosa Konselor'">
                                </VIconButton>
                              </div>
                              <div class="mr-2">
                                <VIconButton type="button" raised circle icon="fas fa-book-medical"
                                  @click="addNewKeperawatan(item.originalIndex, checkBidan)" color="success"
                                  v-tooltip-prime.top="'Diagnosa ' + (checkBidan == 'perawat' ? 'Keperawatan' : 'Kebidanan') + ' (SDKI)'"
                                  :disabled="item.button">
                                </VIconButton>
                              </div>
                            </div>
                            <h1 style="font-weight: bold;">Assesments:</h1>
                            <VField>
                              <VControl>
                                <VTextarea v-model="input.details[item.originalIndex].A" rows="5" placeholder=""
                                  :disabled="item.A2">
                                </VTextarea>
                              </VControl>
                            </VField>
                          </td>
                          <td v-if="item.flag == 'laras'" style="width:50% !important">
                            <div class="columns is-multiline">
                              <div class="column is-12">
                                <span style="font-size:9pt;font-weight:bold">Diagnosis ICD 10</span>
                                <div style="overflow-y:auto;" class="mt-1">
                                  <table class="tg" style="width:100% !important">
                                    <thead>
                                      <tr>
                                        <th class="td-fkprj" rowspan="2"
                                          style="vertical-align:inherit;text-align: center;font-size:9pt;" width="12%">
                                          #
                                        </th>
                                        <th class="td-fkprj" width="23%"
                                          style="vertical-align:inherit;text-align: center;font-size:9pt;">
                                          Jenis

                                        </th>
                                        <th class="td-fkprj" width="25%"
                                          style="vertical-align:inherit;text-align: center;font-size:9pt;">
                                          Diagnosa
                                          Dokter
                                        </th>
                                        <th class="td-fkprj"
                                          style="vertical-align:inherit;text-align: center;font-size:9pt;"> ICD
                                          10
                                        </th>
                                        <th class="td-fkprj"
                                          style="vertical-align:inherit;text-align: center;font-size:9pt;">
                                          <div class="column p-1">
                                            <VIconButton circle icon="feather:copy" color="primary" raised bold outlined
                                              @click="pasteItemDiagnosa(item.originalIndex, 'all')" class="ml-1"
                                              v-tooltip-prime.top="'Terapkan Assesment '">
                                            </VIconButton>
                                          </div>
                                        </th>
                                      </tr>
                                    </thead>
                                    <tbody v-for="(itemsss, index3) in item.diagnosaDokter" :key="index3">
                                      <tr>
                                        <td class="tg-0lax" style="vertical-align: inherit;">
                                          <div class="column p-0">
                                            <div class="columns is-multiline">
                                              <div class="column is-12">
                                                <VIconButton type="button" raised circle icon="feather:plus"
                                                  :loading="itemsss.isLoadBtnDiagnosaDokter"
                                                  @click="addDiagnosaDok(itemsss)" outlined color="info">
                                                </VIconButton>
                                              </div>
                                              <div class="column is-12 mt-3-min">
                                                <VIconButton type="button" raised circle outlined
                                                  :disabled="itemsss.norecDiagnosa ? false : true" icon="feather:trash"
                                                  @click="removeDiagnosaDok(itemsss)" color="danger">
                                                </VIconButton>
                                              </div>
                                            </div>
                                          </div>
                                        </td>
                                        <td class="tg-0lax">
                                          <div class="column p-1">
                                            <VField>
                                              <VControl class="prime-auto">
                                                <AutoComplete v-model="itemsss.jenisDiagnosa"
                                                  :suggestions="d_JenisDiagnosa" @complete="fetchJenisDiagnosa($event)"
                                                  :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                  placeholder=" Jenis ..." class="mt-2" />
                                              </VControl>
                                            </VField>
                                          </div>
                                        </td>
                                        <td class="tg-0lax">
                                          <div class="column pt-3 pb-0">
                                            <VField>
                                              <VControl icon="feather:bookmark">
                                                <VInput type="text" v-model="itemsss.norecDiagnosa" disabled
                                                  style="display:none" placeholder="Diagnosa Dokter" />
                                                <VInput type="text" v-model="itemsss.keterangan"
                                                  placeholder="Diagnosa Dokter" />

                                              </VControl>
                                            </VField>
                                          </div>
                                        </td>
                                        <td class="tg-0lax">
                                          <div class="column p-1">
                                            <VField>
                                              <VControl class="prime-auto">
                                                <AutoComplete v-model="itemsss.diagnosaa" :suggestions="d_Diagnosa"
                                                  @complete="fetchDiagnosa($event)" :optionLabel="'label'"
                                                  :dropdown="true" :minLength="3" :appendTo="'body'"
                                                  :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                  placeholder=" ICD 10 ..." class="mt-2" />
                                              </VControl>
                                            </VField>
                                          </div>
                                        </td>
                                        <td class="tg-0lax">
                                          <div class="column p-1">
                                            <VIconButton circle icon="feather:copy" color="warning" raised bold outlined
                                              @click="pasteItemDiagnosa(item.originalIndex, 'dokter')" class="ml-1"
                                              v-tooltip-prime.top="'Terapkan Diagnosa '">
                                            </VIconButton>
                                          </div>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                              <div class="column is-12">
                                <span style="font-size:9pt;font-weight:bold">Diagnosis ICD 9</span>
                                <div style="overflow-y:auto;" class="mt-1">
                                  <table class="tg" style="width:100% !important">
                                    <thead>
                                      <tr>
                                        <th class="td-fkprj" rowspan="2"
                                          style="vertical-align:inherit;text-align: center;font-size:9pt;" width="15%">
                                          #
                                        </th>

                                        <th class="td-fkprj" width="40%"
                                          style="vertical-align:inherit;text-align: center;font-size:9pt;">
                                          Keterangan
                                        </th>
                                        <th class="td-fkprj"
                                          style="vertical-align:inherit;text-align: center;font-size:9pt;"> ICD
                                          9
                                        </th>
                                        <th class="td-fkprj"
                                          style="vertical-align:inherit;text-align: center;font-size:9pt;">
                                        </th>
                                      </tr>
                                    </thead>
                                    <tbody v-for="(itemsss, index3) in item.diagnosaDokter9" :key="index3">
                                      <tr>
                                        <td class="tg-0lax" style="vertical-align: inherit;">
                                          <div class="column p-0">
                                            <div class="columns is-multiline">
                                              <div class="column is-12">

                                                <VIconButton type="button" raised circle icon="feather:plus"
                                                  :loading="itemsss.isLoadBtnDiagnosaDokter9"
                                                  @click="addDiagnosaDok9(itemsss)" outlined color="info">
                                                </VIconButton>
                                              </div>
                                              <div class="column is-12 mt-3-min">
                                                <VIconButton type="button" raised circle outlined
                                                  :disabled="itemsss.norecDiagnosa9 ? false : true" icon="feather:trash"
                                                  @click="removeDiagnosaDok9(itemsss)" color="danger">
                                                </VIconButton>
                                              </div>
                                            </div>
                                          </div>
                                        </td>

                                        <td class="tg-0lax">
                                          <div class="column pt-3 pb-0">
                                            <VField>
                                              <VControl icon="feather:bookmark">
                                                <VInput type="text" v-model="itemsss.norecDiagnosa9" disabled
                                                  style="display:none" placeholder="Diagnosa Dokter" />
                                                <VInput type="text" v-model="itemsss.keterangan"
                                                  placeholder="Diagnosa Dokter" />
                                              </VControl>
                                            </VField>
                                          </div>
                                        </td>
                                        <td class="tg-0lax">
                                          <div class="column p-1">
                                            <VField>
                                              <VControl class="prime-auto">
                                                <AutoComplete v-model="itemsss.diagnosaa" :suggestions="d_Diagnosa9"
                                                  @complete="fetchDiagnosa9($event)" :optionLabel="'label'"
                                                  :dropdown="true" :minLength="3" :appendTo="'body'"
                                                  :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                  placeholder=" ICD 9 ..." class="mt-2" />
                                              </VControl>
                                            </VField>
                                          </div>
                                        </td>
                                        <td class="tg-0lax">
                                          <div class="column p-1">
                                            <VIconButton circle icon="feather:copy" color="warning" raised bold outlined
                                              @click="pasteItemDiagnosa(item.originalIndex, 'perawat')" class="ml-1"
                                              v-tooltip-prime.top="'Terapkan Diagnosa '">
                                            </VIconButton>
                                          </div>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </td>
                          <td v-if="item.flag == 'laras'">
                            <div class="column">
                              <span style="font-size:11pt;font-weight:bold">Diagnosis Keperawatan</span>
                              <div class="mt-1">
                                <table class="tg">
                                  <thead>
                                    <tr>
                                      <!-- <th class="td-fkprj" width="2%" style="vertical-align: inherit;text-align: center">NO
                                    </th> -->
                                      <th class="td-fkprj" width="50%"
                                        style="vertical-align:inherit;text-align: center;">
                                        Diagnosa
                                        Keperawatan
                                      </th>

                                    </tr>
                                  </thead>
                                  <tbody v-for="(item2, index2) in item.diagnosaKep" :key="index2">
                                    <tr>
                                      <!-- <td class="tg-0lax" style="vertical-align:inherit;text-align:center">{{
                                      item2.no }}
                                    </td> -->
                                      <td class="tg-0lax">
                                        <div class="column p-1">
                                          <VField>
                                            <VControl class="prime-auto">
                                              <AutoComplete v-model="item2.diagnosaKeperawatan"
                                                :suggestions="d_DiagnosaKeperawatan"
                                                @complete="fetchDiagnosaKeperawatan($event)" :optionLabel="'label'"
                                                :dropdown="true" :minLength="3" :appendTo="'body'"
                                                :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                placeholder="Cari Diagnosa Keperawatan ..." class="mt-2" />
                                            </VControl>
                                          </VField>
                                        </div>
                                      </td>

                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="columns ml-3 mt-0" style="justify-content: end; margin-right: 10px;">
                              <VButtons>
                                <VIconButton type="button" raised circle icon="lnir lnir-medicine-alt"
                                  @click="inputObat(item.originalIndex)" :disabled="item.button" :loading="isLoading"
                                  color="success" v-tooltip-prime.top="'Input Obat'">
                                </VIconButton>
                                <VIconButton type="button" raised circle icon="fas fa-file-medical-alt"
                                  @click="inputTindakan(item.originalIndex)" :disabled="item.button"
                                  :loading="isLoadingBill" color="success" v-tooltip-prime.top="'Tindakan'">
                                </VIconButton>
                                <VIconButton type="button" raised circle icon="fas fa-book-medical"
                                  @click="addNewRencanaKeperawatan(item.originalIndex, checkBidan)"
                                  :disabled="item.button" color="success" v-tooltip-prime.top="'SIKI'">
                                </VIconButton>
                              </VButtons>
                            </div>
                            <h1 style="font-weight: bold;">Planning:</h1>
                            <VField>
                              <VControl>
                                <VTextarea v-model="input.details[item.originalIndex].P" rows="5" placeholder=""
                                  :disabled="item.P2">
                                </VTextarea>
                              </VControl>
                            </VField>
                            <div class="column" v-if="item.flag == 'laras'">
                              <span style="font-size:11pt;font-weight:bold">Tujuan Kriteria (SLKI) </span>
                              <div class="mt-1">
                                <table class="tg">
                                  <thead>
                                    <tr>
                                      <th class="td-fkprj" width="50%"
                                        style="vertical-align:inherit;text-align: center;">
                                        Tujuan Keperawatan & Intervensi
                                      </th>
                                    </tr>
                                  </thead>
                                  <tbody v-for="(item2, index2) in item.tujuanKep" :key="index2">
                                    <tr>
                                      <td class="tg-0lax">
                                        <div class="column p-1">
                                          <VField>
                                            <VControl class="prime-auto">
                                              <AutoComplete v-model="item2.tujuanKeperawatan"
                                                :suggestions="d_TujuanKeperawatan" @complete="fetchTujuan($event)"
                                                @item-select="getIDTujuanKeper(item2.tujuanKeperawatan)"
                                                :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                placeholder="Cari Tujuan Keperawatan ..." class="mt-2" />
                                            </VControl>
                                          </VField>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="tg-0lax">
                                        <div class="column p-1">
                                          <VField>
                                            <VControl class="prime-auto">
                                              <AutoComplete v-model="item2.intervensiKeperawatan"
                                                :suggestions="d_IntervensiKeperawatan" @complete="fetchIntervensi()"
                                                :optionLabel="'label'" :dropdown="true" :appendTo="'body'"
                                                :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                placeholder="Cari Intervensi" class="mt-2" />
                                            </VControl>
                                          </VField>
                                        </div>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </td>
                          <td v-if="item.flag == 'laras'">
                            <VIconButton color="danger" outlined circle icon="lnil lnil-trash-can-alt-1"
                              @click="clearItem(item.originalIndex, 'P')" style="float:right"
                              v-tooltip-prime.top="'Hapus'">
                            </VIconButton>
                          </td>
                        </tr>
                        <tr>
                          <td :colspan="item.flag == 'dokter' ? '6' : '4'">

                            <div class="columns is-multiline">
                              <div class="column is-12 bg-warning pb-0">
                                <VField>
                                  <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.details[item.originalIndex].intruksi"
                                      true-value="Intruksi DPJP" label="Intruksi DPJP" color="primary" circle
                                      :disabled="item.intruksiPPA2" />
                                  </VControl>
                                </VField>
                                <VField>
                                  <VControl>
                                    <VCheckbox class="fontcheckbox"
                                      v-model="input.details[item.originalIndex].noticeDokter" true-value="Notice"
                                      label="Notice Dokter" color="primary" circle v-if="item.intruksi" />
                                  </VControl>
                                </VField>
                                <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }"
                                  v-if="item.intruksi">
                                  <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                                    <AutoComplete v-model="input.details[item.originalIndex].dpjpUtama"
                                      :suggestions="d_Dokter" @complete="fetchDokter($event)" :optionLabel="'label'"
                                      :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                      :field="'label'" placeholder="ketik untuk mencari..."
                                      :disabled="item.dpjpUtamaDisabled" />
                                  </VControl>
                                </VField>
                                <VField>
                                  <VControl>
                                    <VIconButton style="float:right" raised circle icon="fas fa-sign-out-alt"
                                      @click="transferPasien(item.originalIndex)" :loading="isLoading" color="success"
                                      v-tooltip-prime.top="'Transfer Pasien'" v-if="item.intruksi"
                                      :disabled="item.transferPasien2">
                                    </VIconButton>
                                    <VTextarea v-model="input.details[item.originalIndex].intruksiPPA" rows="10"
                                      placeholder="Intruksi" :disabled="item.intruksiPPA2" style="height: 100px;"
                                      v-if="item.intruksi">
                                    </VTextarea>
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-12" v-if="item.flag == 'laras'">
                                <div class="columns is-multiline">
                                  <div class="column is-12">
                                    <VButton color="info" class="w-100 btn-slim" light rounded outlined
                                      v-tooltip-prime.right="'Konsul'" @click="isResep = true"> Resep </VButton>
                                  </div>
                                  <div class="column is-12">
                                    <VButton color="success" class="w-100 btn-slim" light rounded outlined
                                      v-tooltip-prime.right="'Konsul'" @click="isKonsul = true"> Konsul </VButton>
                                  </div>
                                  <div class="column is-12">
                                    <VButton color="warning" class="w-100 btn-slim" light rounded outlined
                                      v-tooltip-prime.right="'Radiologi'" @click="isRad = true"> Radiologi </VButton>
                                  </div>
                                  <div class="column is-12">
                                    <VButton color="primary" class="w-100 btn-slim" light rounded outlined
                                      v-tooltip-prime.right="'Laboratorium'" @click="isLab = true"> Laboratorium
                                    </VButton>
                                  </div>
                                  <div class="column is-12">
                                    <VButton color="primary" class="w-100 btn-slim" light rounded outlined
                                      v-tooltip-prime.right="'Rujukan External'" @click="isRujukan = true"> Rujukan
                                    </VButton>
                                  </div>
                                </div>
                              </div>
                              <div class="column is-12">
                                <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }"
                                  v-if="props.registrasi.namaruangan.toUpperCase().indexOf('IGD') > -1">
                                  <p>Dokter Jaga :</p>
                                  <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                                    <AutoComplete v-model="input.details[item.originalIndex].dokterjaga"
                                      :suggestions="d_Dokter" @complete="fetchDokter($event)" :optionLabel="'label'"
                                      :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                      :field="'label'" placeholder="ketik untuk mencari..."
                                      :disabled="item.dokterjaga2" />
                                  </VControl>
                                </VField>
                                <div class="columns is-multiline">
                                  <div class="column is-12 pb-0">
                                    <VField>
                                      <VControl>
                                        <VCheckbox class="fontcheckbox"
                                          v-model="input.details[item.originalIndex].dokterraber" :true-value="true"
                                          label="Dokter Rawat Bersama" color="primary" circle
                                          @change.stop="raberChange(item.dokterraber)" :disabled="item.dokterraber2" />
                                      </VControl>
                                    </VField>
                                  </div>
                                </div>
                                <div class="column is-12">
                                  <template v-if="item.dokterraber">
                                    <div class="columns is-multiline"
                                      v-for="(dokterTambahan, dIndex) in item.dpjpRawatBersama" :key="dIndex">
                                      <div class="column is-10">
                                        <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                          <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                                            <AutoComplete
                                              v-model="input.details[item.originalIndex].dpjpRawatBersama[dIndex].id"
                                              :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                              :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                              :loadingIcon="'pi pi-spinner'" :field="'label'"
                                              placeholder="ketik untuk mencari..." :disabled="item.dokterraber2" />
                                          </VControl>
                                        </VField>

                                        <VField>
                                          <VControl>
                                            <VCheckbox class="fontcheckbox"
                                              v-model="input.details[item.originalIndex].dpjpRawatBersama[dIndex].isintruksi"
                                              true-value="Intruksi untuk Dokter Bersama"
                                              label="Intruksi untuk Dokter Bersama" color="primary" circle
                                              :disabled="item.intruksiPPA2" />
                                          </VControl>
                                        </VField>

                                        <VField
                                          v-if="input.details[item.originalIndex].dpjpRawatBersama[dIndex].isintruksi">
                                          <VControl>
                                            <VTextarea
                                              v-model="input.details[item.originalIndex].dpjpRawatBersama[dIndex].intruksi"
                                              rows="10" placeholder="Intruksi" :disabled="item.dokterraber2"
                                              style="height: 100px;">
                                            </VTextarea>
                                          </VControl>
                                        </VField>
                                      </div>
                                      <div class="column is-2 mt-3">
                                        <VButtons class="columns">
                                          <VIconButton color="primary" circle icon="feather:plus"
                                            @click="tambahDokter(item.originalIndex)" :disabled="item.dokterraber2" />
                                          <VIconButton v-if="dIndex > 0" color="danger" circle icon="feather:trash-2"
                                            @click="hapusDokter(item.originalIndex, dIndex)"
                                            :disabled="item.dokterraber2" />
                                        </VButtons>
                                        <!-- <div class="button-wrap">
                                        <VButton type="button" @click="tambahDokter(index)" color="primary" bold raised
                                          size="small" icon="feather:plus">
                                        </VButton>
                                        <VButton type="button" @click="hapusDokter(index, dIndex)" color="danger" bold raised
                                          size="small" icon="feather:trash-2" v-if="dIndex > 0">
                                        </VButton>
                                      </div> -->
                                      </div>
                                    </div>
                                  </template>
                                </div>
                                <div class="columns is-multiline">
                                  <div class="column is-12 pb-0">
                                    <VField>
                                      <VControl>
                                        <VCheckbox class="fontcheckbox"
                                          v-model="input.details[item.originalIndex].isPPRA" :true-value="true"
                                          label="Ronde PPRA (Antimicrobial Stewardship Round)" color="primary" circle
                                          :disabled="item.ppra2" />
                                      </VControl>
                                    </VField>
                                  </div>
                                  <template v-if="item.isPPRA">
                                    <div class="column is-12">
                                      <VField label="Terapi Antibiotik Saat ini">
                                        <VControl>
                                          <VTextarea v-model="input.details[item.originalIndex].terapiAntibiotikPPRA"
                                            rows="10" :disabled="item.ppra2" style="height: 100px;">
                                          </VTextarea>
                                        </VControl>
                                      </VField>
                                    </div>
                                    <div class="column is-12">
                                      <VField label="Diagnosis">
                                        <VControl>
                                          <VTextarea v-model="input.details[item.originalIndex].diagnosisPPRA" rows="10"
                                            :disabled="item.ppra2" style="height: 100px;">
                                          </VTextarea>
                                        </VControl>
                                      </VField>
                                    </div>
                                    <div class="column is-12">
                                      <VField label="Hasil Lab.Mikrobiologi">
                                        <VControl>
                                          <VTextarea v-model="input.details[item.originalIndex].mikroBiologiPPRA"
                                            rows="10" :disabled="item.ppra2" style="height: 100px;">
                                          </VTextarea>
                                        </VControl>
                                      </VField>
                                    </div>
                                    <div class="column is-12">
                                      <VField label="Rekomendasi">
                                        <VControl>
                                          <VTextarea v-model="input.details[item.originalIndex].rekomendasiPPRA"
                                            rows="10" :disabled="item.ppra2" style="height: 100px;">
                                          </VTextarea>
                                        </VControl>
                                      </VField>
                                    </div>
                                  </template>
                                </div>

                                <!-- <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }"
                                  v-if="input.dokterraber == 'Dokter Rawat Bersama'">
                                  <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                                    <AutoComplete v-model="input.dpjpRawatBersama1" :suggestions="d_Dokter"
                                      @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                      :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                      placeholder="ketik untuk mencari..." />
                                  </VControl>
                                </VField> -->
                              </div>


                              <VField v-if="isRanap" class="ml-3">
                                <VControl>
                                  <VCheckbox class="fontcheckbox" v-model="input.details[item.originalIndex].handover"
                                    :true-value="true" label="Handover Shift Rawat Inap" color="primary" circle
                                    @change.stop="handoverChange(item.handover)" />
                                </VControl>
                              </VField>
                              <div class="column is-12 mt-0 pt-0" v-if="isRanap">
                                <template v-if="item.handover">
                                  <VField>
                                    <div class="columns is-multiline p-2">

                                      <div class="column is-12 has-text-centered is-size-5">
                                        Serah Terima Pasien
                                      </div>

                                      <div class="column is-12 mb-6">
                                        <div class="columns is-multiline">
                                          <div class="column is-6">
                                            <div class="column is-12 has-text-centered is-size-6">
                                              <span class="label-apas">Pemberi Informasi</span>
                                            </div>
                                            <div class="columns is-multiline">
                                              <div class="column is-4 mt-2 has-text-right">
                                                Tanggal dan Jam :
                                              </div>
                                              <div class="column is-6">
                                                <VDatePicker v-model="input.details[item.originalIndex].tanggalPemberi"
                                                  mode="datetime" trim-weeks>
                                                  <template #default="{ inputValue, inputEvents }">
                                                    <VControl icon="feather:calendar" fullwidth>
                                                      <VInput :value="inputValue" v-on="inputEvents" />
                                                    </VControl>
                                                  </template>
                                                </VDatePicker>
                                              </div>
                                            </div>
                                            <div class="columns is-multiline">
                                              <div class="column is-4 mt-2 has-text-right">
                                                Nama :
                                              </div>
                                              <div class="column is-8">
                                                <VControl class="prime-auto">
                                                  <AutoComplete
                                                    v-model="input.details[item.originalIndex].petugasPemberi"
                                                    :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                                    :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                    class="mt-2" />
                                                </VControl>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="column is-6">
                                            <div class="column is-12 has-text-centered is-size-6">
                                              <span class="label-apas">Penerima Informasi</span>
                                            </div>
                                            <div class="columns is-multiline">
                                              <div class="column is-4 mt-2 has-text-right">
                                                Tanggal dan Jam :
                                              </div>
                                              <div class="column is-6">
                                                <VDatePicker v-model="input.details[item.originalIndex].tanggalPenerima"
                                                  mode="datetime" trim-weeks>
                                                  <template #default="{ inputValue, inputEvents }">
                                                    <VControl icon="feather:calendar" fullwidth>
                                                      <VInput :value="inputValue" v-on="inputEvents" />
                                                    </VControl>
                                                  </template>
                                                </VDatePicker>
                                              </div>
                                            </div>
                                            <div class="columns is-multiline">
                                              <div class="column is-4 mt-2 has-text-right">
                                                Nama :
                                              </div>
                                              <div class="column is-8">
                                                <VControl class="prime-auto">
                                                  <AutoComplete
                                                    v-model="input.details[item.originalIndex].petugasPenerima"
                                                    :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                                    :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                    class="mt-2" />
                                                </VControl>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>

                                    </div>
                                  </VField>
                                </template>
                              </div>
                            </div>
                          </td>

                          <!-- <td width="5%" style="width: 10%;font-weight: bold;vertical-align:top;">
                          <VIconButton type="button" raised v-if="item.flag == 'dokter'" circle icon="fas fa-copy"
                            v-tooltip-prime.bottom="'Terapkan'" color="warning" @click="pasteFromClipboard(item)">
                          </VIconButton>
                          <VIconButton type="button" outlined raised v-if="item.flag == 'perawat'" circle
                            icon="feather:copy" v-tooltip-prime.bottom="'Copy'" @click="copyToClipboard(item.P)"
                            color="warning"></VIconButton>
                        </td> -->
                        </tr>
                      </table>
                    </td>

                    <td style="width:7%;vertical-align: text-top;text-align: center;">
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem(item)"
                            color="info" v-tooltip-prime.top="'Tambah Baris '" v-if="editMode == false">
                          </VIconButton>
                        </div>
                        <div class="column is-12">
                          <VIconButton v-if="index == 0 && dataSourceFiltered.length > (0 || 1)" type="button" raised
                            circle icon="feather:trash" @click="removeItem(item.originalIndex, item.flag)"
                            :disabled="item.button" color="danger">
                          </VIconButton>
                        </div>
                      </div>
                    </td>
                  </tr>

                  <!--? Khusus Gizi  -->
                  <tr v-else-if="item.flag == 'gizi' && !item.isDeleted"
                    :style="[item.flag == 'gizi' ? 'background-color: var(--warning--light-color) !important;' : '']">
                    <td>
                      <table class="tg">
                        <tr>
                          <td colspan="4">
                            <div class="columns is-multiline">
                              <div class="column is-12">
                                <VField>
                                  <VControl class="prime-auto">
                                    <Calendar v-model="input.details[item.originalIndex].tgl" selectionMode="single"
                                      :manualInput="true" class="w-100" :showIcon="true" showTime hourFormat="24"
                                      :date-format="H.dateTimeFormat().prime.date" :disabled="item.tgl2" />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-12">
                                <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                  <VControl icon="fa:stethoscope" fullwidth class="prime-auto ">
                                    <AutoComplete v-model="input.details[item.originalIndex].tenagaMedis"
                                      :suggestions="d_Pegawai" @complete="fetchPegawai($event)" :optionLabel="'label'"
                                      :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                      :field="'label'" placeholder="Tenaga Medis..." :disabled="item.tenagaMedis2" />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="columns is-multiline">
                              <div class="column is-4 pb-0">
                                <h1 style="font-weight: bold;">Assesment:</h1>
                              </div>
                              <div class="column is-8"></div>
                            </div>
                            <VField>
                              <VControl>
                                <VTextarea v-model="input.details[item.originalIndex].AGizi" rows="5" placeholder=""
                                  :disabled="item.S2">
                                </VTextarea>
                              </VControl>
                            </VField>
                          </td>
                          <td>
                            <div class="columns is-multiline">
                              <div class="column is-4 pb-0">
                                <h1 style="font-weight: bold;">Diagnosis:</h1>
                              </div>
                              <div class="column is-8"></div>
                            </div>
                            <VField>
                              <VControl>
                                <VTextarea v-model="input.details[item.originalIndex].DGizi" rows="5" placeholder=""
                                  :disabled="item.O2">
                                </VTextarea>
                              </VControl>
                            </VField>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="columns is-multiline">
                              <div class="column is-4 pb-0">
                                <h1 style="font-weight: bold;">Intervensi:</h1>
                              </div>
                              <div class="column is-8"></div>
                            </div>
                            <VField>
                              <VControl>
                                <VTextarea v-model="input.details[item.originalIndex].IGizi" rows="5" placeholder=""
                                  :disabled="item.A2">
                                </VTextarea>
                              </VControl>
                            </VField>
                          </td>
                          <td>
                            <div class="columns is-multiline">
                              <div class="column is-12 pb-0">
                                <h1 style="font-weight: bold;">Monitoring & Evaluasi:</h1>
                              </div>
                            </div>
                            <VField>
                              <VControl>
                                <VTextarea v-model="input.details[item.originalIndex].MEGizi" rows="5" placeholder=""
                                  :disabled="item.P2">
                                </VTextarea>
                              </VControl>
                            </VField>
                          </td>
                        </tr>
                      </table>
                    </td>
                    <td style="width:7%;vertical-align: text-top;">
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <!-- <VIconButton type="button" raised circle icon="feather:save" :loading="isLoading"
                          @click="simpan(item)" color="success" v-tooltip-prime.top="'Preview '">
                        </VIconButton> -->
                          <!-- <VIconButton type="button" raised circle icon="feather:save" :loading="isLoading"
                            @click="simpanReal" color="success" v-tooltip-prime.top="'Simpan '">
                          </VIconButton> -->
                        </div>
                        <!-- <div class="column is-12">
                          <VIconButton type="button" raised circle icon="fas fa-paste" @click="paste(item, index)"
                            color="warning" v-tooltip-prime.top="'Terapkan'">
                          </VIconButton>
                        </div> -->
                        <div class="column is-12">
                          <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem(item)"
                            color="info" v-tooltip-prime.top="'Tambah Baris '">
                          </VIconButton>
                        </div>
                        <div class="column is-12 ml-3-min">
                          <VIconButton v-if="item.no > 1" type="button" raised circle icon="feather:trash"
                            @click="removeItem(item.originalIndex, item.flag)" :disabled="item.button" color="danger">
                          </VIconButton>
                        </div>
                      </div>
                    </td>
                  </tr>

                  <tr v-if="!item.isDeleted"
                    :style="[item.flag == 'dokter' ? 'background-color: var(--danger--light-color);' : (item.flag == 'perawat' ? 'background-color: var(--info--light-color);' : (item.flag == 'gizi' ? 'background-color: var(--warning--light-color);' : ''))]">
                    <td colspan="5" style="text-align: center;">
                      <VTag class="mr-1 mb-1"
                        :color="item.flag == 'dokter' ? 'danger' : item.flag == 'perawat' ? 'info' : (item.flag == 'gizi' ? 'warning' : 'light')"
                        :label="item.flag == 'perawat' && props.registrasi.namaruangan.toUpperCase().indexOf('FISIO') == -1 ? 'PERAWAT / BIDAN / PPR' : (props.registrasi.namaruangan.toUpperCase().indexOf('FISIO') > -1 && item.flag == 'perawat' ? 'FISIOTERAPI' : item.flag.toUpperCase())" />
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!--? Riwayat CPPT -->
            <div class="column is-4">
              <div class="columns is-multiline">
                <div class="column is-12" v-if="isloadingLAMPAU">
                  <div class="flex-list-inner mb-2 mt-5">
                    <div class="flex-table-item grid-item mb-1" v-for="key in 5" :key="key">
                      <VPlaceloadWrap>
                        <VPlaceloadAvatar size="small" />
                        <VPlaceloadText last-line-width="60%" class="mx-2" />
                        <VPlaceload class="mx-2" disabled />
                        <VPlaceload class="mx-2 h-hidden-tablet-p" />
                        <VPlaceload class="mx-2 h-hidden-tablet-p" />
                        <VPlaceload class="mx-2" />
                      </VPlaceloadWrap>
                    </div>
                  </div>
                </div>
                <div class="column is-12" v-else-if="!isloadingLAMPAU">
                  <VCard class="tg-card">
                    <div class="column is-12">
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <VField label="Periode" style="margin-bottom: 6px;" />
                          <VDatePicker v-model="item.qFilterTgl" is-range color="pink" locale="id" trim-weeks>
                            <template #default="{ inputValue, inputEvents }">
                              <VField addons>
                                <VControl icon="feather:calendar">
                                  <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                </VControl>
                                <VControl>
                                  <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                                </VControl>
                                <VControl icon="feather:calendar">
                                  <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                </VControl>
                              </VField>
                            </template>
                          </VDatePicker>
                        </div>
                        <div class="column is-12 pt-0 pb-0">
                          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                        </div>
                        <div class="column is-3 pt-0">
                          <VField label="Filter Ruangan" class="text-muted px-0">
                            <VControl>
                              <VSwitchBlock class="switch-profesi" v-model="isRuangan" @change.once="loadRiwayatOld()"
                                color="success" />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-3 pt-0">
                          <VField label="All Periode" class="text-muted px-0">
                            <VControl>
                              <VSwitchBlock class="switch-profesi" v-model="isAllPeriode"
                                @change.once="loadRiwayatOld()" color="success" />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-6 p-0"></div>
                        <div class="column is-3">
                          <VField
                            :label="user.toUpperCase().indexOf('PONEK') > -1 || user.toUpperCase().indexOf('BIDAN') > -1 ? 'BIDAN' : 'PERAWAT'"
                            class="text-muted px-0"
                            v-if="props.registrasi?.namaruangan.toUpperCase().indexOf('FISIOTERAPI') == -1">
                            <VControl>
                              <VSwitchBlock class="switch-profesi" v-model="isPerawat"
                                @change.stop="switchFilter('perawat')" color="info" />
                            </VControl>
                          </VField>
                          <VField label="FISIOTERAPI" class="text-muted px-0"
                            v-if="props.registrasi?.namaruangan.toUpperCase().indexOf('FISIOTERAPI') > -1">
                            <VControl>
                              <VSwitchBlock class="switch-profesi" v-model="isPerawat"
                                @change.stop="switchFilter('perawat')" color="info" />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-3">
                          <VField vertical label="DOKTER" class="text-muted">
                            <VControl>
                              <VSwitchBlock class="switch-profesi" v-model="isDokter" color="danger"
                                @change.stop="switchFilter('dokter')" />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-3">
                          <VField vertical label="SEMUA" class="text-muted">
                            <VControl>
                              <VSwitchBlock class="switch-profesi" v-model="isProfesi"
                                @change.stop="switchFilter('profesi lain')" />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-3">
                          <VField vertical label="ADIME" class="text-muted">
                            <VControl>
                              <VSwitchBlock class="switch-profesi" v-model="isGizi" color="warning"
                                @change.stop="switchFilter('gizi')" />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>

                    <div class="columns is-multiline mt-3">
                      <div class="column is-12 CPPT_HEIGHT">
                        <div class="flex-list-inner" v-if="input2.length === 0">
                          <VPlaceholderSection :title="H.assets().notFound" class="my-6">
                            <template #image>
                              <img class="light-image" :src="H.assets().iconNotFound_rev" alt=""
                                style="width: 100px;" />
                              <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                alt="" style="width: 100px;" />
                            </template>
                          </VPlaceholderSection>
                        </div>
                        <table class="tg" v-else>
                          <tbody v-for="(itemss, index) in input2" :key="index">
                            <tr v-for="(item, index2) in filterData" :key="index2">
                              <td colspan="3" style="size:100%;">
                                <table style="border:none; min-width:100%;">
                                  <tr style="background: #d6d4d4;">
                                    <td colspan="2">
                                      <span class="text-normal-1">
                                        <b>PPA</b> : {{ item.tenagaMedis && item.tenagaMedis.label ?
                                          item.tenagaMedis.label : '' }}
                                        <!-- <b>Section</b> : {{ item.registrasi ? item.ruangan : (itemss.registrasi ? itemss.registrasi.namaruangan : '') }} -->
                                        &nbsp;<b>Section</b> : {{ item.ruangan ? item.ruangan :
                                          (item.apd ? item.apd.namaruangan : itemss.registrasi.namaruangan) }} &nbsp;
                                        <b>Tanggal</b> : {{ item ?
                                          H.formatDateIndo(item.tgl) : '' }}
                                      </span>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <table class="tg" v-if="item.flag !== 'gizi'">
                                        <tr>
                                          <td width="5%"
                                            style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">S
                                          </td>
                                          <td colspan="2">
                                            <!-- {{ item.S ?? '' }} -->
                                            <div style="white-space: pre-line">{{ item.S ?? '' }}</div>
                                          </td>
                                        </tr>
                                        <tr>
                                          <td width="5%"
                                            style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">O
                                          </td>
                                          <td colspan="2">
                                            <!-- {{ item.O ?? '' }} -->
                                            <div style="white-space: pre-line">{{ item.O ?? '' }}</div>
                                          </td>
                                        </tr>
                                        <tr>
                                          <td width="5%"
                                            style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">A
                                          </td>
                                          <td colspan="2">
                                            <!-- {{ item.A ?? '' }} -->
                                            <div style="white-space: pre-line">{{ item.A ?? '' }}</div>
                                          </td>
                                        </tr>
                                        <tr>
                                          <td width="5%"
                                            style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">P
                                          </td>
                                          <td colspan="2">
                                            <!-- {{ item.P ?? '' }} -->
                                            <div style="white-space: pre-line">{{ item.P ?? '' }}</div>
                                          </td>
                                        </tr>
                                        <tr>
                                          <td width="5%"
                                            style="width: 5%;vertical-align: middle;font-size:14px;font-weight:bold">
                                            Intruksi
                                          </td>
                                          <td colspan="2">
                                            {{ item.dpjpUtama?.label }} <br>
                                            <span v-if="item.intruksiPPA" style="white-space: pre-line">Instruksi: {{
                                              item.intruksiPPA ?? '' }}</span>
                                            <VTag color="success" v-if="item.intruksi && item.isverif">
                                              Intruksi Sudah Diverifikasi
                                            </VTag>
                                            <VTag color="warning" v-else-if="item.intruksi">
                                              Intruksi Belum Diverifikasi
                                            </VTag>
                                          </td>
                                        </tr>
                                        <tr
                                          v-if="item.dokterjaga != null && item.dokterjaga != undefined && item.dokterjaga.label != null">
                                          <td style="vertical-align: middle;" colspan="3">
                                            <span style="font-size:14px;font-weight:bold">Dokter Jaga :</span> {{
                                              item.dokterjaga.label }}
                                          </td>
                                        </tr>
                                        <tr
                                          v-if="item.dpjpRawatBersama != null && item.dpjpRawatBersama.length && item.dpjpRawatBersama[0].id != null">
                                          <td
                                            style="vertical-align: middle;text-align:center;font-size:14px;font-weight:bold"
                                            colspan="3">
                                            Dokter Rawat Bersama
                                          </td>
                                        </tr>
                                        <tr
                                          v-if="item.dpjpRawatBersama != null && item.dpjpRawatBersama.length && item.dpjpRawatBersama[0].id != null"
                                          v-for="data in item.dpjpRawatBersama" :key="index">
                                          <td style="vertical-align: middle;text-align:left;font-size:12px;"
                                            colspan="3">
                                            {{ data.id != null && data.id.label != null ? data.id.label : '' }}<br>
                                            <span v-if="data.intruksi != null">Intruksi : {{ data.intruksi }}</span>
                                          </td>
                                        </tr>
                                        <tr v-if="item.handover">
                                          <td
                                            style="vertical-align: middle;text-align:center;font-size:14px;font-weight:bold"
                                            colspan="3">
                                            Handover Shift Rawat Inap
                                          </td>
                                        </tr>
                                        <tr v-if="item.handover">
                                          <td style="vertical-align: top;text-align:left;font-size:14px;" colspan="3">
                                            <div class="column is-12">
                                              <div class="columns is-multiline">
                                                <div class="column is-6" style="font-size: 12px">
                                                  <div class="column is-12">
                                                    <b>Pemberi Informasi</b> <br>
                                                    <b>Tanggal</b> : {{ item ?
                                                      H.formatDateIndo(item.tanggalPemberi) : '' }}
                                                    <br>
                                                    <b>Nama</b> : {{ item.petugasPemberi ?
                                                      item.petugasPemberi.label : '' }} <br>
                                                    <img
                                                      src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{ ($item.petugasPemberi ? $item.petugasPemberi.label : '-')}}"
                                                      alt="TTD" width="100">
                                                  </div>
                                                </div>
                                                <div class="column is-6" style="font-size: 12px">
                                                  <div class="column is-12">
                                                    <b>Penerima Informasi</b> <br>
                                                    <b>Tanggal</b> : {{ item ?
                                                      H.formatDateIndo(item.tanggalPenerima) : '' }}
                                                    <br>
                                                    <b>Nama</b> : {{ item.petugasPenerima ?
                                                      item.petugasPenerima.label : '' }} <br>
                                                    <img
                                                      src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{ ($item.petugasPenerima ? $item.petugasPenerima.label : '-')}}"
                                                      alt="TTD" width="100">
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                          </td>
                                        </tr>
                                        <tr v-if="item.isPPRA != null">
                                          <td
                                            style="vertical-align: middle;text-align:center;font-size:14px;font-weight:bold"
                                            colspan="3">
                                            Ronde PPRA
                                          </td>
                                        </tr>
                                        <tr v-if="item.isPPRA != null">
                                          <td style="vertical-align: middle;text-align:left;font-size:12px;"
                                            colspan="3">
                                            <span style="font-weight: bold;"> Terapi Antibiotik Saat ini</span> <br>
                                            <span>{{ item.terapiAntibiotikPPRA }}</span>
                                          </td>
                                        </tr>
                                        <tr v-if="item.isPPRA != null">
                                          <td style="vertical-align: middle;text-align:left;font-size:12px;"
                                            colspan="3">
                                            <span style="font-weight: bold;">Diagnosis</span><br>
                                            <span>{{ item.diagnosisPPRA }}</span>
                                          </td>
                                        </tr>
                                        <tr v-if="item.isPPRA != null">
                                          <td style="vertical-align: middle;text-align:left;font-size:12px;"
                                            colspan="3">
                                            <span style="font-weight: bold;">Hasil Lab.Mikrobiologi</span> <br>
                                            <span>{{ item.mikroBiologiPPRA }}</span>
                                          </td>
                                        </tr>
                                        <tr v-if="item.isPPRA != null">
                                          <td style="vertical-align: middle;text-align:left;font-size:12px;"
                                            colspan="3">
                                            <span style="font-weight: bold;">Rekomendasi</span> <br>
                                            <span>{{ item.rekomendasiPPRA }}</span>
                                          </td>
                                        </tr>
                                        <tr>
                                          <td style="vertical-align: text-top;" colspan="2">
                                            <VIconButton circle icon="feather:copy" color="warning" raised bold
                                              @click="copy(itemss.detailsFull[index2])" class="ml-1 mr-1"
                                              v-tooltip-prime.top="'Klik 2x'">
                                            </VIconButton>
                                            <VIconButton circle icon="feather:trash" color="danger" raised bold
                                              @click="hapusCPPT(itemss.detailsFull[index2])" class="ml-1 mr-1"
                                              v-tooltip-prime.top="'Hapus CPPT'"
                                              v-if="pegawaiId == itemss.detailsFull[index2].tenagaMedis.value">
                                            </VIconButton>
                                            <VIconButton circle icon="feather:edit" color="info" raised bold
                                              @click="editCPPT(itemss.detailsFull[index2])" class="ml-1 mr-1"
                                              v-tooltip-prime.top="'Edit CPPT'"
                                              v-if="pegawaiId == itemss.detailsFull[index2].tenagaMedis.value">
                                            </VIconButton>
                                          </td>
                                        </tr>
                                      </table>
                                      <table class="tg" v-else>
                                        <tr>
                                          <td width="5%"
                                            style="width: 5%;font-weight: bold;text-align:center;vertical-align: middle;font-size:18px">
                                            A
                                          </td>
                                          <td colspan="2">
                                            <pre>{{ item.AGizi ?? '' }}</pre>
                                          </td>
                                        </tr>
                                        <tr>
                                          <td width="5%"
                                            style="width: 5%;font-weight: bold;text-align:center;vertical-align: middle;font-size:18px">
                                            D
                                          </td>
                                          <td colspan="2">
                                            <pre>{{ item.DGizi ?? '' }}</pre>
                                          </td>
                                        </tr>
                                        <tr>
                                          <td width="5%"
                                            style="width: 5%;font-weight: bold;text-align:center;vertical-align: middle;font-size:18px">
                                            I
                                          </td>
                                          <td colspan="2">
                                            <pre>{{ item.IGizi ?? '' }}</pre>
                                          </td>
                                        </tr>
                                        <tr>
                                          <td width="5%"
                                            style="width: 5%;font-weight: bold;text-align:center;vertical-align: middle;font-size:18px">
                                            M & E
                                          </td>
                                          <td colspan="2">
                                            <pre>{{ item.MEGizi ?? '' }}</pre>
                                          </td>
                                        </tr>

                                        <tr>
                                          <td style="vertical-align: text-top;" colspan="2">
                                            <VIconButton circle icon="feather:copy" color="warning" raised bold
                                              @click="copy(itemss.detailsFull[index2])" class="ml-1 mr-1"
                                              v-tooltip-prime.top="'Klik 2x'">
                                            </VIconButton>
                                            <VIconButton circle icon="feather:trash" color="danger" raised bold
                                              @click="hapusCPPT(itemss.detailsFull[index2])" class="ml-1 mr-1"
                                              v-tooltip-prime.top="'Hapus CPPT'"
                                              v-if="pegawaiId == itemss.detailsFull[index2].tenagaMedis.value">
                                            </VIconButton>
                                            <VIconButton circle icon="feather:edit" color="info" raised bold
                                              @click="editCPPT(itemss.detailsFull[index2])" class="ml-1 mr-1"
                                              v-tooltip-prime.top="'Edit CPPT'"
                                              v-if="pegawaiId == itemss.detailsFull[index2].tenagaMedis.value">
                                            </VIconButton>
                                          </td>
                                        </tr>
                                      </table>
                                    </td>
                                  </tr>
                                </table>
                                <p style="font-size: 18px;margin-top: 12px;margin-bottom: 6px;"
                                  v-if="itemss.riwayatResep.length > 0">
                                  Order Resep</p>
                                <table class="tg table-tg" v-if="itemss.riwayatResep.length > 0">
                                  <thead>
                                    <tr>
                                      <td class="tg-0lax text-center">Tanggal/Jam</td>
                                      <td class="tg-0lax text-center">Nama Produk</td>
                                      <td class="tg-0lax text-center">Aturan Pakai</td>
                                      <td class="tg-0lax text-center">Jumlah</td>
                                    </tr>
                                  </thead>
                                  <tbody v-for="(resep, i) in itemss.riwayatResep">
                                    <tr>
                                      <td style="width:15%">
                                        <span class="mb-2">{{ resep.tglorder }}</span><br>
                                      </td>
                                      <td style="width:26%">
                                        <span class="mb-2">{{ resep.namaproduk }}</span><br>
                                      </td>
                                      <td style="width:10%;text-align:center">
                                        <span class="mb-2">{{ resep.aturanpakai }}</span><br>
                                      </td>
                                      <td style="width:10%;text-align:center">
                                        <span class="mb-2">{{ resep.jumlah }}</span><br>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </VCard>
                </div>
              </div>
            </div>
          </div>

          <hr style="margin:0px;">

          <VField>
            <VControl>
              <VCheckbox class="fontcheckbox" v-model="input.perluRiwayatKeluar" true-value="Perlu"
                label="Riwayat Keluar RS" color="primary"
                v-if="props.registrasi.namadepartemen.indexOf('Rawat Inap') > -1" circle />
            </VControl>
          </VField>

          <div
            v-if="(props.registrasi.namadepartemen.indexOf('Rawat Inap') === -1 || input.perluRiwayatKeluar === 'Perlu') &&
              (kelompokUser === 'dokter' || (kelompokUser === 'perawat' && props.registrasi.namaruangan.toUpperCase().indexOf('FISIO') > -1))">
            <h1 style="font-weight: bold; margin-bottom: 10px; margin-top: 10px;font-size:large"
              class="text-center mb-5">Kondisi
              Keluar RS</h1>
            <div class="columns is-multiline">
              <div class="column is-12">
                <h1 style="font-weight: bold; margin-bottom: 10px;" class="ml-3">Riwayat Keluar RS</h1>
                <div class="columns is-multiline">
                  <div class="column is-3 pt-0 pb-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar" true-value="Sembuh" label="Sembuh"
                          color="primary" circle :disabled="editMode" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0 pb-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar" true-value="Membaik"
                          label="Membaik" color="primary" circle :disabled="editMode" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0 pb-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar" true-value="Belum Sembuh"
                          label="Belum Sembuh" color="primary" circle :disabled="editMode" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0 pb-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar" :disabled="editMode"
                          true-value="Tidak Ada Perkembangan" label="Tidak Ada Perkembangan" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0 pb-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar" true-value="Meninggal >= 48 Jam"
                          label="Meninggal > 48 Jam" color="primary" circle :disabled="editMode" />
                      </VControl>
                    </VField>
                    <VField v-if="input.riwayatkeluar === 'Meninggal >= 48 Jam'">
                      <VControl>
                        <VTextarea v-model="input.keluar" rows="2" placeholder="Alasan Meninggal"
                          :disabled="editMode" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0 pb-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar" true-value="Meninggal <= 48 Jam"
                          label="Meninggal <= 48 Jam" color="primary" circle :disabled="editMode" />
                      </VControl>
                    </VField>
                    <VField v-if="input.riwayatkeluar === 'Meninggal <= 48 Jam'">
                      <VControl>
                        <VTextarea v-model="input.keluar" rows="2" placeholder="Alasan Meninggal"
                          :disabled="editMode" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0 pb-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar" true-value="DOA" label="DOA"
                          color="primary" circle :disabled="editMode" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0 pb-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar" true-value="Rawat Inap"
                          label="Rawat Inap" color="primary" circle :disabled="editMode" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-12 pb-0">
                <h1 style="margin-bottom: 10px;font-weight: bold" class="ml-3">
                  Status Keluar RS
                </h1>
                <div class="columns is-multiline">
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.statuskeluar" true-value="Belum Keluar RS"
                          label="Belum Keluar RS" color="primary" circle :disabled="editMode" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.statuskeluar" true-value="Diijinkan Pulang"
                          label="Diijinkan Pulang" color="primary" circle :disabled="editMode" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.statuskeluar" true-value="Pulang Paksa"
                          label="Pulang Paksa" color="primary" circle :disabled="editMode" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.statuskeluar" true-value="Dirujuk"
                          label="Dirujuk" color="primary" circle :disabled="editMode" />
                      </VControl>
                      <div v-if="input.statuskeluar == 'Dirujuk'">
                        <h1>Tujuan</h1>
                        <VControl>
                          <VInput type="text" class="input" v-model="input.tujuan_skrs" :disabled="editMode" />
                        </VControl>
                        <h1>Alasan</h1>
                        <VControl>
                          <VInput type="text" class="input" v-model="input.alasan_skrs" :disabled="editMode" />
                        </VControl>
                      </div>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-12">
                <h1 style="margin-bottom: 10px;font-weight: bold" class="ml-3">Perlu Kontrol
                </h1>
                <div class="columns is-multiline">
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.perlukontrol" true-value="Ya" label="Ya"
                          color="primary" circle :disabled="editMode" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.perlukontrol" true-value="Tidak" label="Tidak"
                          :disabled="editMode" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.perlukontrol" true-value="Rujuk Balik"
                          label="Rujuk Balik" color="primary" circle :disabled="editMode" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <template v-if="props.registrasi?.namaruangan.toUpperCase().indexOf('REHAB MEDIK') > -1">
                <div class="column is-12">
                  <h1 style="margin-bottom: 10px;font-weight: bold" class="ml-3">Tindakan Rehab Medik
                  </h1>
                  <div class="columns is-multiline">
                    <div class="column is-3 pt-0">
                      <VField>
                        <VControl>
                          <VCheckbox class="fontcheckbox" v-model="input.tindakanRehabInfra" true-value="Infrared"
                            label="Infrared" color="primary" circle :disabled="editMode" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3 pt-0">
                      <VField>
                        <VControl>
                          <VCheckbox class="fontcheckbox" v-model="input.tindakanRehabSWD" true-value="SWD" label="SWD"
                            color="primary" circle :disabled="editMode" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3 pt-0">
                      <VField>
                        <VControl>
                          <VCheckbox class="fontcheckbox" v-model="input.tindakanRehabMWD" true-value="MWD" label="MWD"
                            color="primary" circle :disabled="editMode" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3 pt-0">
                      <VField>
                        <VControl>
                          <VCheckbox class="fontcheckbox" v-model="input.tindakanRehabUSD" true-value="USD" label="USD"
                            color="primary" circle :disabled="editMode" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3 pt-0">
                      <VField>
                        <VControl>
                          <VCheckbox class="fontcheckbox" v-model="input.tindakanRehabTENS" true-value="TENS"
                            label="TENS" color="primary" circle :disabled="editMode" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3 pt-0">
                      <VField>
                        <VControl>
                          <VCheckbox class="fontcheckbox" v-model="input.tindakanRehabElectric" :disabled="editMode"
                            true-value="Electrical Stimulation (E.S)" label="Electrical Stimulation" color="primary"
                            circle />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3 pt-0">
                      <VField>
                        <VControl>
                          <VCheckbox class="fontcheckbox" v-model="input.tindakanRehabExer" true-value="Exercise"
                            label="Exercise" color="primary" circle :disabled="editMode" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3 pt-0">
                      <VField>
                        <VControl>
                          <VCheckbox class="fontcheckbox" v-model="input.tindakanRehabTraksi" true-value="Traksi"
                            label="Traksi" color="primary" circle :disabled="editMode" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3 pt-0">
                      <VField>
                        <VControl>
                          <VCheckbox class="fontcheckbox" v-model="input.tindakanRehabOther" true-value="Lainnya"
                            label="Lainnya" color="primary" circle :disabled="editMode" />
                        </VControl>
                      </VField>
                      <VField v-if="input.tindakanRehabOther && input.tindakanRehabOther == 'Lainnya'">
                        <VControl>
                          <VInput type="text" v-model="input.tindakanRehablain" :disabled="editMode" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
                <div class="column is-12">
                  <h1 style="margin-bottom: 10px;font-weight: bold" class="ml-3">Terapi Ringkasan Keluar
                  </h1>
                  <div class="columns is-multiline">
                    <div class="column is-12 pt-0">
                      <VField>
                        <VControl>
                          <VTextarea v-model="input.terapiRingkasan" :disabled="editMode" rows="4" placeholder="">
                          </VTextarea>
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </template>
            </div>
          </div>
        </VCard>
      </div>
    </div>
  </div>

  <!--? LIST POP-UP  -->
  <VModal :open="showModalKonselor" title="Diagnosa Konselor" :noclose="false" size="big" actions="right"
    @close="showModalKonselor = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12 pt-0 pb-0">
          <!-- <span style="font-size:9pt;font-weight:bold" class="mb-5">Diagnosa Keperawatan</span> -->
          <div class="column is-12 forCB">
            <VCheckbox class="fontcheckbox" v-model="kons[0]"
              true-value="Kurang pengetahuan tentang penyakit, rencana tindakan, dan pengobatan b/d kurang terpajannya informasi"
              label="Kurang pengetahuan tentang penyakit, rencana tindakan, dan pengobatan b/d kurang terpajannya informasi"
              color="primary" circle />
            <br>
            <VCheckbox class="fontcheckbox" v-model="kons[1]"
              true-value="Diare akut b/d mal absorsi, peningkatan motilitas usus"
              label="Diare akut b/d mal absorsi, peningkatan motilitas usus" color="primary" circle />
            <br>
            <VCheckbox class="fontcheckbox" v-model="kons[2]" true-value="Kesiapan meningkatkan status kesehatan"
              label="Kesiapan meningkatkan status kesehatan" color="primary" circle />
            <br>
            <VCheckbox class="fontcheckbox" v-model="kons[3]" true-value="Risiko/Gangguan integritas kulit"
              label="Risiko/Gangguan integritas kulit" color="primary" circle />
            <br>
            <VCheckbox class="fontcheckbox" v-model="kons[4]" true-value="HIV Counselling" label="HIV Counselling"
              color="primary" circle />
            <br>
            <VCheckbox class="fontcheckbox" v-model="kons[5]" true-value="Sex Counselling" label="Sex Counselling"
              color="primary" circle />
            <br>
            <VCheckbox class="fontcheckbox" v-model="kons[6]"
              true-value="Counselling related to sexual attitude, behavior and orientation"
              label="Counselling related to sexual attitude, behavior and orientation" color="primary" circle />
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:plus" @click="addKonselor()" :loading="isLoading" color="primary" raised>
        Tambah
      </VButton>
      <!-- <VButton icon="feather:plus" @click="addKonselor()" :loading="isLoading" color="primary" raised>Tambah ke CPPT
                </VButton> -->
    </template>
  </VModal>
  <VModal :open="showModalKeperawatan" title="Diagnosa Keperawatan" size="large" actions="right"
    @close="showModalKeperawatan = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12 pt-0 pb-0">
          <span style="font-size:9pt;font-weight:bold" class="mb-5">Diagnosa Keperawatan</span>
          <div class="control" v-if="isRanap">
            <input type="text" v-model="filterMenu" class="input" placeholder="Search..." />
          </div>

          <div class="column is-12 forCB">
            <div v-if="filteredDiagnosa.length && isRanap">
              <div v-for="diagnosa in filteredDiagnosa" :key="diagnosa.id">
                <VCheckbox class="fontcheckbox" v-model="selectedDiagnosa" :label="diagnosa.label" :value="diagnosa.id"
                  color="primary" circle />
              </div>
            </div>
          </div>

          <div class="column is-12 forCB" v-if="isRanap === false">
            <VCheckbox class="fontcheckbox" v-model="dig[0]" true-value="Nyeri akut b/d kondisi fisik"
              label="Nyeri akut b/d kondisi fisik" color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="dig[1]"
              true-value="Bersihan jalan nafas tidak efektif b/d alergi jalan nafas, adanya eksudat di jalan nafas / sekresi tertahan"
              label="Bersihan jalan nafas tidak efektif b/d alergi jalan nafas, adanya eksudat di jalan nafas / sekresi tertahan"
              color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="dig[2]"
              true-value="Risiko /Penurunan curah jantung b/d anomaly jantung / peningkatan beban kerja ventrikel"
              label="Risiko /Penurunan curah jantung b/d anomaly jantung / peningkatan beban kerja ventrikel"
              color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="dig[3]"
              true-value="Risiko / Kekurangan Volume Cairan b/d kehilangan volume cairan secara aktif"
              label="Risiko / Kekurangan Volume Cairan b/d kehilangan volume cairan secara aktif" color="primary"
              circle /><br>
            <VCheckbox class="fontcheckbox" v-model="dig[4]"
              true-value="Kurang pengetahuan tentang penyakit, rencana tindakan dan pengobatan b/d kurang terpajannya informasi"
              label="Kurang pengetahuan tentang penyakit, rencana tindakan dan pengobatan b/d kurang terpajannya informasi"
              color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="dig[5]"
              true-value="Ansietas b/d krisis situasi, kebutuhan yang tidak terpenuhi"
              label="Ansietas b/d krisis situasi, kebutuhan yang tidak terpenuhi" color="primary" circle />
            <br>
            <VCheckbox class="fontcheckbox" v-model="dig[6]" true-value="Risiko gangguan integritas kulit"
              label="Risiko gangguan integritas kulit" color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="dig[7]"
              true-value="Kelebihan volume cairan b/d asupan cairan berlebihan"
              label="Kelebihan volume cairan b/d asupan cairan berlebihan" color="primary" circle />
            <br>
            <VCheckbox class="fontcheckbox" v-model="dig[8]" true-value="Kesiapan meningkatkan status kesehatan"
              label="Kesiapan meningkatkan status kesehatan" color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="dig[9]"
              true-value="Ketidakefektifan pemeliharaan kesehatan b/d hambatan kognitif"
              label="Ketidakefektifan pemeliharaan kesehatan b/d hambatan kognitif" color="primary" circle />
            <br>
            <VCheckbox class="fontcheckbox" v-model="dig[10]"
              true-value="Hambatan mobilitas fisik b/d intoleran aktivitas"
              label="Hambatan mobilitas fisik b/d intoleran aktivitas" color="primary" circle />
            <br>
            <VCheckbox class="fontcheckbox" v-model="dig[11]"
              true-value="Diare akut b/d mal absorbsi, peningkatan motilitas usus"
              label="Diare akut b/d mal absorbsi, peningkatan motilitas usus" color="primary" circle />
            <br>
            <VCheckbox class="fontcheckbox" v-model="dig[12]"
              true-value="Nausea b/d biofisik, psikologis, pemberian kemotherapi, pemberian steroid"
              label="Nausea b/d biofisik, psikologis, pemberian kemotherapi, pemberian steroid" color="primary"
              circle /><br>
            <VCheckbox class="fontcheckbox" v-model="dig[13]"
              true-value="Risiko Ketidakstabilan kadar Glukosa Darah b/d Kurang pengetahuan tentang menejemen diabetes, Asupan diet, Pemantauan glukosa darah tidak adekuat"
              label="Risiko Ketidakstabilan kadar Glukosa Darah b/d Kurang pengetahuan tentang menejemen diabetes, Asupan diet, Pemantauan glukosa darah tidak adekuat"
              color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="dig[14]"
              true-value="Hipertermia b/d kekurangan cairan, proses infeksi, gangguan termoregulasi"
              label="Hipertermia b/d kekurangan cairan, proses infeksi, gangguan termoregulasi" color="primary"
              circle /><br>
            <VCheckbox class="fontcheckbox" v-model="dig[15]" true-value="Gangguan fungsi gigi"
              label="Gangguan fungsi gigi" color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="dig[16]" true-value="Gangguan jaringan keras gigi"
              label="Gangguan jaringan keras gigi" color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="dig[17]" true-value="Gangguan jaringan lunak dan pendukung gigi"
              label="Gangguan jaringan lunak dan pendukung gigi" color="primary" circle />
            <br>
            <VCheckbox class="fontcheckbox" v-model="dig[18]" true-value="Gangguan estetika" label="Gangguan estetika"
              color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" true-value="Gangguan persepsi sensori" v-model="dig[19]"
              label="Gangguan persepsi sensori" color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="dig[20]"
              true-value="Risiko jatuh b/d riwayat terjatuh/usia lebih dari 65th / menggunakan alat bantu (walker, tongkat, kursi roda) / sulit penglihatan"
              label="Risiko jatuh b/d riwayat terjatuh/usia lebih dari 65th / menggunakan alat bantu (walker, tongkat, kursi roda) / sulit penglihatan"
              color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="dig[21]"
              true-value="Pola pemberian ASI tidak efektif b/d ketidakefektifan pemeliharaan kesehatan"
              label="Pola pemberian ASI tidak efektif b/d ketidakefektifan pemeliharaan kesehatan" color="primary"
              circle />
            <br>
            <VCheckbox class="fontcheckbox" label="Lainnya" v-model="diaglainnya" color="primary" circle />

            <br>
            <div v-if="diaglainnya">
              <VControl v-for="(item, index) in diaglainnyaList" :key="index" class="input-container">
                <!-- Input dan tombol berada dalam flex container -->
                <div class="input-group">
                  <!-- <VTextarea v-model="diaglainnyaList[index]" rows="5" placeholder="" :style="{ width: '100%' }">
                  </VTextarea> -->
                  <VInput v-model="diaglainnyaList[index]" rows="5" placeholder="" :style="{ width: '58rem' }" />
                  <VIconButton type="button" raised circle icon="feather:plus" @click="tambahan" color="success"
                    v-tooltip-prime.top="'Tambah'" class="custom-icon-button" />
                  <VIconButton v-if="diaglainnyaList.length > 1" type="button" raised circle icon="feather:trash"
                    @click="hapus(index)" color="danger" v-tooltip-prime.top="'Hapus'" class="custom-icon-button" />
                </div>
              </VControl>
            </div>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton type="button" color="primary" raised @click="addKeperawatan()">
        Tambah
      </VButton>
    </template>
  </VModal>
  <VModal :open="showModalDiagnosaBidan" title="Diagnosa Kebidanan" size="large" actions="right"
    @close="showModalDiagnosaBidan = false">
    <template #content>
      <form class="modal-form">
        <div class="columns is-multiline">
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12">
                <h1>DIAGNOSA KEBIDANAN</h1>
              </div>
              <div class="column is-2">
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="diagBidan.g" true-value="G" label="G" color="primary"
                          circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-8">
                    <VField>
                      <VControl>
                        <input v-model="diagBidan.ketG" class="input" style="height:25px" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-2">
                <div class="columns is-multiline">
                  <div class="column is-1">
                    <h1>P</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <input v-model="diagBidan.ketP" class="input" style="height:25px" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-2">
                <div class="columns is-multiline">
                  <div class="column is-1">
                    <h1>UK</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <input v-model="diagBidan.ketUK" class="input" style="height:25px" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-2">
                <div class="columns is-multiline">
                  <div class="column is-5">
                    <h1>Minggu</h1>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="diagBidan.ketMinggu" class="input" style="height:25px" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-2">
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <h1>Hari</h1>
                  </div>
                  <div class="column is-8">
                    <VField>
                      <VControl>
                        <input v-model="diagBidan.ketHari" class="input" style="height:25px" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-2"></div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2">
                    <div class="columns is-multiline">
                      <div class="column is-4">
                        <VField>
                          <VControl>
                            <VCheckbox class="fontcheckbox" v-model="diagBidan.p2" true-value="P" label="P"
                              color="primary" circle />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-8">
                        <VField>
                          <VControl>
                            <input v-model="diagBidan.ketP2" class="input" style="height:25px" />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                  <div class="column is-1">
                    <h1>A</h1>
                  </div>
                  <div class="column is-9">
                    <VField>
                      <VControl>
                        <input v-model="diagBidan.ketA2" class="input" style="height:25px" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="diagBidan.akseptorbaru"
                          true-value="Akseptor baru kontrasepsi" label="Akseptor baru kontrasepsi" color="primary"
                          circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-9">
                    <VField>
                      <VControl>
                        <input v-model="diagBidan.ketAkseptorbaru" class="input" style="height:25px" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="diagBidan.akseptorlama"
                          true-value="Akseptor lama kontrasepsi" label="Akseptor lama kontrasepsi" color="primary"
                          circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-9">
                    <VField>
                      <VControl>
                        <input v-model="diagBidan.ketAkseptorlama" class="input" style="height:25px" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="diagBidan.akslama" true-value="Akseptor lama"
                          label="Akseptor lama" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField>
                      <VControl>
                        <input v-model="diagBidan.ketAkseptorlama2" class="input" style="height:25px" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <h1>ganti cara ke kontrasepsi</h1>
                  </div>
                  <div class="column is-3">
                    <VField>
                      <VControl>
                        <input v-model="diagBidan.gantiKontrasepsi" class="input" style="height:25px" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-12">
                <VField>
                  <VControl>
                    <VCheckbox class="fontcheckbox" v-model="diagBidan.puswus"
                      true-value="PUS / WUS dengan pilihan kontrasepsi yang belum rasional"
                      label="PUS / WUS dengan pilihan kontrasepsi yang belum rasional" color="primary" circle />
                  </VControl>
                </VField>
              </div>
              <div class="column is-6">
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="diagBidan.CBLainnya_Diagnosa_Kebidanan" true-value="Lainnya"
                    label="Lainnya" color="primary" circle />
                </VControl>
              </div>
              <div class="column is-6" v-if="diagBidan.CBLainnya_Diagnosa_Kebidanan == 'Lainnya'">
                <VControl>
                  <VInput type="text" class="input" v-model="diagBidan.TBDiagnosaLainnya" />
                </VControl>
              </div>
            </div>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton type="button" color="primary" raised @click="addKeperawatan('bidan')">
        Tambah
      </VButton>
    </template>
  </VModal>
  <VModal :open="showModalRencanaKeperawatan" title="SLKI" size="large" actions="right"
    @close="showModalRencanaKeperawatan = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12 pt-0 pb-0">
          <span style="font-size:9pt;font-weight:bold" class="mb-5">Rencana Keperawatan</span>
          <div class="column is-12 forCB">

            <div v-if="d_Siki.length && isRanap">
              <div v-for="siki in d_Siki" :key="siki.value">
                <!-- Jika type == label -->
                <div v-if="siki.type === 'label'" class="fontcheckbox">
                  {{ siki.label }}
                </div>
                <!-- Jika type == checkbox -->
                <VCheckbox v-else-if="siki.type === 'checkbox'" class="fontcheckbox" v-model="selectedSiki"
                  :label="siki.label" :value="siki.label" color="primary" circle />
                <!-- Jika type == head -->
                <h3 v-else-if="siki.type === 'head'" class="fontcheckbox font-bold">
                  {{ siki.label }}
                </h3>
              </div>
            </div>
          </div>
          <div class="column is-12 forCB" v-if="isRanap === false">
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[1]"
              true-value="Istirahatkan pasien pada posisi yang nyaman dalam batas yang ditoleransi oleh pasien"
              label="Istirahatkan pasien pada posisi yang nyaman dalam batas yang ditoleransi oleh pasien"
              color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[2]"
              true-value="Berikan informasi tentang nyeri meliputi penyebab, lamanya nyeri berlangsung, faktor yang dapat memperburuk atau meredakan nyeri"
              label="Berikan informasi tentang nyeri meliputi penyebab, lamanya nyeri berlangsung, faktor yang dapat memperburuk atau meredakan nyeri"
              color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[3]"
              true-value="Bantu pasien mengidentifikasi tindakan memenuhi kebutuhan rasa nyaman yang telah berhasil dilakukan oleh pasien"
              label="Bantu pasien mengidentifikasi tindakan memenuhi kebutuhan rasa nyaman yang telah berhasil dilakukan oleh pasien"
              color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[4]" true-value="Observasi tanda-tanda vital"
              label="Observasi tanda-tanda vital" color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[5]"
              true-value="Ajarkan teknik nonfarmakologis seperti : Relaksasi napas dalam/otot progesif, Distraksi, kompres hangat/dingin, terapi music, massage punggung"
              label="Ajarkan teknik nonfarmakologis seperti : Relaksasi napas dalam/otot progesif, Distraksi, kompres hangat/dingin, terapi music, massage punggung"
              color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[6]"
              true-value="Monitor Frekuensi nafas pasien/ status oksigen pasien"
              label="Monitor Frekuensi nafas pasien/ status oksigen pasien" color="primary" circle />
            <br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[7]"
              true-value="Posisikan pasien untuk memaksimalkan ventilasi (head up/semifowler)"
              label="Posisikan pasien untuk memaksimalkan ventilasi (head up/semifowler)" color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[8]" true-value="Latihan teknik batuk efektif"
              label="Latihan teknik batuk efektif" color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[9]"
              true-value="Lakukan chest fisioterapi sesuai indikasi/bila perlu"
              label="Lakukan chest fisioterapi sesuai indikasi/bila perlu" color="primary" circle />
            <br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[10]"
              true-value="Beri KIE tentang tanda-tanda penurunan curah jantung"
              label="Beri KIE tentang tanda-tanda penurunan curah jantung" color="primary" circle />
            <br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[11]"
              true-value="Latih rentang pergerakan aktif/pasif untuk memperbaiki kekuatan dan daya tahan otot"
              label="Latih rentang pergerakan aktif/pasif untuk memperbaiki kekuatan dan daya tahan otot"
              color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[12]"
              true-value="Edukasi untuk memberikan kompres dengan air biasa/ hangat"
              label="Edukasi untuk memberikan kompres dengan air biasa/ hangat" color="primary" circle />
            <br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[13]"
              true-value="Kaji dan dokumentasi frekuensi, warna, konsistensi, jumlah (ukuran) feces, turgor kulit dan kondisi mukosa mulut sebagai indikator dehidrasi"
              label="Kaji dan dokumentasi frekuensi, warna, konsistensi, jumlah (ukuran) feces, turgor kulit dan kondisi mukosa mulut sebagai indikator dehidrasi"
              color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[14]"
              true-value="Sarankan menghindari makanan yang mengandung lactose, makan makanan yang rendah serat, tinggi kalori dan tinggi protein"
              label="Sarankan menghindari makanan yang mengandung lactose, makan makanan yang rendah serat, tinggi kalori dan tinggi protein"
              color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[15]"
              true-value="Lakukan manajemen imunisasi/vaksinasi" label="Lakukan manajemen imunisasi/vaksinasi"
              color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[16]"
              true-value="Beri dukungan dalam mengambil keputusan" label="Beri dukungan dalam mengambil keputusan"
              color="primary" circle />
            <br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[17]"
              true-value="Edukasi dan sarankan untuk kontrol sebagai upaya meningkatkan status kesehatan pasien"
              label="Edukasi dan sarankan untuk kontrol sebagai upaya meningkatkan status kesehatan pasien"
              color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[18]" true-value="Kaji integritas kulit"
              label="Kaji integritas kulit" color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[19]"
              true-value="Ajarkan teknik nonfarmakologis seperti : Relaksasi napas dalam/ otot progresif, Distraksi, Kompres hangat/ dingin, Terapi music, Massage punggung"
              label="Ajarkan teknik nonfarmakologis seperti : Relaksasi napas dalam/ otot progresif, Distraksi, Kompres hangat/ dingin, Terapi music, Massage punggung"
              color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[20]" true-value="Identifikasi level cemas pada pasien"
              label="Identifikasi level cemas pada pasien" color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[21]"
              true-value="Berikan pengetahuan yang adekuat tentang penyakit yang diderita untuk mengurangi cemas"
              label="Berikan pengetahuan yang adekuat tentang penyakit yang diderita untuk mengurangi cemas"
              color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[22]"
              true-value="Jelaskan semua posedur, termasuk beberapa pengalaman sebelum prosedur"
              label="Jelaskan semua posedur, termasuk beberapa pengalaman sebelum prosedur" color="primary" circle />
            <br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[23]"
              true-value="Dekati pasien untuk memberikan rasa aman dan mengurangi rasa takut"
              label="Dekati pasien untuk memberikan rasa aman dan mengurangi rasa takut" color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[24]"
              true-value="Dengarkan pasien dengan penuh perhatian" label="Dengarkan pasien dengan penuh perhatian"
              color="primary" circle />
            <br>
            <VCheckbox class="fontcheckbox" label="Lainnya" v-model="rencanaKeperLainnya" color="primary" circle /><br>
            <VControl v-if="rencanaKeperLainnya">
              <VInput type="text" class="heightinput input" v-model="rencanaKeperLain" />
            </VControl>
          </div>

        </div>
      </form>
    </template>
    <template #action>
      <VButton type="button" color="primary" raised @click="addRencanaKeperawatan(index)">
        Tambah
      </VButton>
    </template>
  </VModal>
  <VModal :open="showRencanaKebidanan" title="SLKI" size="large" actions="right" @close="showRencanaKebidanan = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12 pt-0 pb-0">
          <span style="font-size:9pt;font-weight:bold" class="mb-5">Rencana Kebidanan</span>
          <div class="column is-12 forCB">
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[0]"
              true-value="Informasikan hasil pemeriksaan dan kondisi saat ini"
              label="Informasikan hasil pemeriksaan dan kondisi saat ini" color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[1]"
              true-value="Kolaborasi dengan dokter spesialis obgyn untuk tindakan dan therapy selanjutnya"
              label="Kolaborasi dengan dokter spesialis obgyn untuk tindakan dan therapy selanjutnya" color="primary"
              circle /><br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[2]"
              true-value="Fasilitasi dokter dalam pemeriksaan USG" label="Fasilitasi dokter dalam pemeriksaan USG"
              color="primary" circle /><br>

            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[3]" true-value="Lakukan pemeriksaan NST"
              label="Lakukan pemeriksaan NST" color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[4]"
              true-value="Anjurkan pasien untuk skrining rutin kehamilan"
              label="Anjurkan pasien untuk skrining rutin kehamilan" color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[5]"
              true-value="Berikan pengetahuan tentang tanda bahaya kehamilan, keluhan lazim dan cara mengatasinya"
              label="Berikan pengetahuan tentang tanda bahaya kehamilan, keluhan lazim dan cara mengatasinya"
              color="primary" circle /> <br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[6]"
              true-value="Berikan informasi tentang deteksi dan pencegahan kelainan kongenital"
              label="Berikan informasi tentang deteksi dan pencegahan kelainan kongenital" color="primary" circle />
            <br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[7]"
              true-value="Berikan pengetahuan tentang Nutrisi/ gizi" label="Berikan pengetahuan tentang Nutrisi/ gizi"
              color="primary" circle /><br>

            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[8]"
              true-value="Berikan pengetahuan yang adekuat tentang penyakit yang diderita untuk mengurangi cemas"
              label="Berikan pengetahuan yang adekuat tentang penyakit yang diderita untuk mengurangi cemas"
              color="primary" circle /> <br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[9]"
              true-value="Jelaskan semua posedur, termasuk beberapa pengalaman sebelum prosedur"
              label="Jelaskan semua posedur, termasuk beberapa pengalaman sebelum prosedur" color="primary" circle />
            <br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[10]"
              true-value="Dekati pasien untuk memberikan rasa aman dan mengurangi rasa takut"
              label="Dekati pasien untuk memberikan rasa aman dan mengurangi rasa takut" color="primary" circle /> <br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[11]"
              true-value="Dengarkan pasien dengan penuh perhatian" label="Dengarkan pasien dengan penuh perhatian"
              color="primary" circle /><br>

            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[12]"
              true-value="Fasilitasi dokter pemeriksaan papsmear" label="Fasilitasi dokter pemeriksaan papsmear"
              color="primary" circle /> <br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[13]" true-value="Fasilitasi dokter pemeriksaan biopsi"
              label="Fasilitasi dokter pemeriksaan biopsi" color="primary" circle /> <br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[14]"
              true-value="Fasilitasi dokter pemeriksaan cryoterapi" label="Fasilitasi dokter pemeriksaan cryoterapi"
              color="primary" circle /><br>

            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[15]"
              true-value="Ajarkan teknik nonfarmakologis seperti Relaksasi napas dalam/otot progesif, Distraksi, kompres hangat/dingin, terapi music, massage punggung,"
              label="Ajarkan teknik nonfarmakologis seperti Relaksasi napas dalam/otot progesif, Distraksi, kompres hangat/dingin, terapi music, massage punggung,"
              color="primary" circle /> <br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[16]"
              true-value="Berikan informasi tentang gerak dan aktivitas selama hamil/ nifas"
              label="Berikan informasi tentang gerak dan aktivitas selama hamil/ nifas" color="primary" circle /> <br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[17]"
              true-value="Beritahu pasien dan keluarga tentang persiapan persalinan, peran pendamping, persiapan menyusui, termasuk Calon Pendonor"
              label="Beritahu pasien dan keluarga tentang persiapan persalinan, peran pendamping, persiapan menyusui, termasuk Calon Pendonor"
              color="primary" circle /> <br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[18]"
              true-value="Berikan informasi tentang kelas ibu hamil, Senam Hamil"
              label="Berikan informasi tentang kelas ibu hamil, Senam Hamil" color="primary" circle /> <br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[19]"
              true-value="Beritahu pasien tentang tanda – tanda persalinan"
              label="Beritahu pasien tentang tanda – tanda persalinan" color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[20]"
              true-value="Berikan informasi tentang tanda Bahaya masa nifas"
              label="Berikan informasi tentang tanda Bahaya masa nifas" color="primary" circle /> <br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[21]"
              true-value="Ajarkan pasien cara memeriksa kontraksi uterus, Cara masase uterus, perawatan Perineum, Senam nifas"
              label="Ajarkan pasien cara memeriksa kontraksi uterus, Cara masase uterus, perawatan Perineum, Senam nifas"
              color="primary" circle /> <br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[22]"
              true-value="Berikan Informasi cara menyusui yang benar, dan ASI Ekslusif, perawatan payudara"
              label="Berikan Informasi cara menyusui yang benar, dan ASI Ekslusif, perawatan payudara" color="primary"
              circle /> <br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[23]"
              true-value="Beri KIE tentang, Keuntungan, Kelemahan, Efek samping, Lama penggunaan , Cara mengatasi efek samping Kontrasepsi"
              label="Beri KIE tentang, Keuntungan, Kelemahan, Efek samping, Lama penggunaan , Cara mengatasi efek samping Kontrasepsi"
              color="primary" circle /> <br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[24]"
              true-value="Beri KIE tentang Sex Hygine/ hubungan seksual"
              label="Beri KIE tentang Sex Hygine/ hubungan seksual" color="primary" circle /><br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[25]"
              true-value="Beri KIE pasien tentang Perawatan luka pasca operasi"
              label="Beri KIE pasien tentang Perawatan luka pasca operasi" color="primary" circle /> <br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[26]"
              true-value="Istirahatkan pasien pada posisi yang nyaman dalam batas yang ditoleransi oleh pasien"
              label="Istirahatkan pasien pada posisi yang nyaman dalam batas yang ditoleransi oleh pasien"
              color="primary" circle /> <br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[27]"
              true-value="Berikan informasi tentang nyeri meliputi penyebab, lamanya nyeri berlangsung, faktor yang dapat memperburuk atau meredakan nyeri"
              label="Berikan informasi tentang nyeri meliputi penyebab, lamanya nyeri berlangsung, faktor yang dapat memperburuk atau meredakan nyeri"
              color="primary" circle /> <br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[28]"
              true-value="Bantu pasien mengidentifikasi tindakan memenuhi kebutuhan rasa nyaman yang telah berhasil dilakukan oleh pasien"
              label="Bantu pasien mengidentifikasi tindakan memenuhi kebutuhan rasa nyaman yang telah berhasil dilakukan oleh pasien"
              color="primary" circle /> <br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[29]" true-value="Observasi tanda-tanda vital"
              label="Observasi tanda-tanda vital" color="primary" circle /> <br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[30]"
              true-value="Monitor Frekuensi nafas pasien/ status oksigen pasien"
              label="Monitor Frekuensi nafas pasien/ status oksigen pasien" color="primary" circle /> <br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[31]"
              true-value="Posisikan pasien untuk memaksimalkan ventilasi (head up/semifowler)"
              label="Posisikan pasien untuk memaksimalkan ventilasi (head up/semifowler)" color="primary" circle /> <br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[32]"
              true-value="Lakukan manajemen imunisasi/vaksinasi" label="Lakukan manajemen imunisasi/vaksinasi"
              color="primary" circle /> <br>
            <VCheckbox class="fontcheckbox" v-model="rencanaKeper[33]"
              true-value="Beri dukungan dalam mengambil keputusan" label="Beri dukungan dalam mengambil keputusan"
              color="primary" circle />
            <br>
            <VCheckbox class="fontcheckbox" label="Lainnya" v-model="rencanaKeperLainnya" color="primary" circle /><br>
            <VControl v-if="rencanaKeperLainnya">
              <VInput type="text" class="heightinput input" v-model="rencanaKeperLain" />
            </VControl>
          </div>

        </div>
      </form>
    </template>
    <template #action>
      <VButton type="button" color="primary" raised @click="addRencanaKeperawatan(index)">
        Tambah
      </VButton>
    </template>
  </VModal>
  <VModal :open="showModalVitalSign" title="Riwayat" :noclose="true" size="large" actions="right"
    @close="showModalVitalSign = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12 pt-0 pb-0">
          <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
          <div style="overflow-y:auto;" class="mt-1">
            <table class="tg table-tg" v-if="listTemplate.length > 0">
              <thead>
                <tr>
                  <td class="tg-0lax text-center" width="5%">#</td>
                  <td class="tg-0lax text-center" width="5%">Tanggal Input</td>
                  <td class="tg-0lax text-center" width="5%">Suhu</td>
                  <td class="tg-0lax text-center" width="5%">Nadi</td>
                  <td class="tg-0lax text-center" width="5%">Tekanan Darah</td>
                  <td class="tg-0lax text-center" width="5%">Pernafasan</td>
                  <td class="tg-0lax text-center" width="5%">SPO2</td>
                </tr>
              </thead>
              <tbody v-for="response in listTemplate">
                <tr>
                  <td style="width:5%;text-align:center">
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="addRiwayatVitalSign(response)"
                      color="info" v-tooltip-prime.top="'Pilih'">
                    </VIconButton>
                  </td>
                  <td style="width:5%;text-align:center">
                    <VDatePicker class="pt-0 pb-0 pl-0" v-model="response.tanggal" color="green" trim-weeks
                      mode="dateTime">
                      <template #default="{ inputValue, inputEvents }" class="pb-0">
                        <VField>
                          <VControl icon="feather:calendar">
                            <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                              class="is-rounded" disabled />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker><br>
                  </td>
                  <td style="width:5%;text-align:center">
                    <span class="mb-2">{{ response.suhu }}</span><br>
                  </td>
                  <td style="width:5%;text-align:center">
                    <span class="mb-2">{{ response.nadi }}</span><br>
                  </td>
                  <td style="width:5%;text-align:center">
                    <span class="mb-2">{{ response.tekananDarah }}</span><br>
                  </td>
                  <td style="width:5%;text-align:center">
                    <span class="mb-2">{{ response.pernapasan }}</span><br>
                  </td>
                  <td style="width:5%;text-align:center">
                    <span class="mb-2">{{ response.SPO2 }}</span><br>
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
      <DataTable :pt="{
        table: { style: 'min-width: 50rem; min-height: 10rem;' },
        column: {
          bodycell: ({ state }) => ({
            class: [{ 'pt-0 pb-0': state['d_editing'] }]
          })
        }
      }" v-model:filters="filtersTemplate" :value="listTemplateFix" :metaKeySelection="false" :rows="10" paginator
        tableStyle="min-width: 50rem" dataKey="no" :totalRecords="listTemplateFix.length"
        :globalFilterFields="['namatemplate', 'registrasi.namaruangan']" responsiveLayout="stack" breakpoint="960px">
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
          <img src="/images/other/loadingspin.gif" alt="Loading..." width="100" />
          <p style="color:white">Loading data, please wait...</p>
        </template>
        <Column headerStyle="width: 8rem">
          <template #body="slotProps">
            <VButtons>
              <VIconButton color="danger" light raised circle icon="lucide:x" @click="deleteTemplate(slotProps.data.id)"
                v-if="!isAlltemplate" v-tooltip-prime.top="'Hapus'" />
              <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(slotProps.data)"
                color="info" v-tooltip-prime.top="'Pilih'">
              </VIconButton>
              <VIconButton type="button" raised circle icon="fas fa-pencil-alt" @click="editTemplate(slotProps.data)"
                color="info" v-tooltip-prime.top="'Edit'" v-if="!isAlltemplate">
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
  <VModal :open="showModalObat" title="Input Obat" :noclose="true" size="large" actions="right"
    @close="showModalObat = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12 pt-0 pb-0">
          <span style="font-size:9pt;font-weight:bold">Input Obat</span>
          <div style="overflow-y:auto;" class="mt-1">
            <table class="tg table-tg" v-if="listSIMRSLama.length > 0">
              <thead>
                <tr>
                  <td class="tg-0lax text-center" width="5%">#</td>
                  <td class="tg-0lax text-center" width="25%">Tanggal</td>
                  <td class="tg-0lax text-center" width="25%">No Resep</td>
                  <td class="tg-0lax text-center" width="25%">No Registrasi</td>
                  <td class="tg-0lax text-center" width="20%">Nama Dokter</td>
                </tr>
              </thead>
              <tbody v-for="resep in listSIMRSLama">
                <tr>
                  <td style="width:5%;text-align:center">
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="addObat(resep)" color="info"
                      v-tooltip-prime.top="'Tambah Obat'">
                    </VIconButton>
                  </td>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ resep.tglorder }}</span><br>
                  </td>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ resep.noorder }}</span><br>
                  </td>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ resep.noregistrasi }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center">
                    <span class="mb-2">{{ resep.namalengkap }}</span><br>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </form>
    </template>
  </VModal>
  <VModal :open="showModalTindakan" title="Input Tindakan" :noclose="true" size="large" actions="right"
    @close="showModalTindakan = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12 pt-0 pb-0">
          <span style="font-size:9pt;font-weight:bold">Input Tindakan</span>
          <div style="overflow-y:auto;" class="mt-1">
            <table class="tg table-tg" v-if="dataSource.length > 0">
              <thead>
                <tr>
                  <td class="tg-0lax text-center" width="5%">#</td>
                  <td class="tg-0lax text-center" width="25%">Tanggal</td>
                  <td class="tg-0lax text-center" width="25%">Tindakan</td>
                  <td class="tg-0lax text-center" width="25%">Ruangan</td>
                  <td class="tg-0lax text-center" width="20%">Jumlah Tindakan</td>
                </tr>
              </thead>
              <tbody v-for="tindakan in dataSource">
                <tr v-for="item in tindakan.details">
                  <td style="width:5%;text-align:center">
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTindakan(item)" color="info"
                      v-tooltip-prime.top="'Tambah Tindakan'">
                    </VIconButton>
                  </td>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ item.tglpelayanan }}</span><br>
                  </td>
                  <td style="width:25%;text-align:left">
                    <span class="mb-2">{{ item.namaproduk }}</span><br>
                  </td>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ item.namaruangan }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center">
                    <span class="mb-2">{{ item.jumlah }}</span><br>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </form>
    </template>
  </VModal>
  <VModal :open="alertMid" actions="center" noscroll title="Konfirmasi"
    @close="kelompokUser == 'dokter' ? closeAlert() : ''">
    <template #content>
      <div class="is-flex" style="justify-content: start !important; ">
        <VIconBox size="xl" color="danger" class="alert-nobg">
          <VIcon icon="lucide:alert-triangle" />
        </VIconBox>
        <VPlaceholderSection title="Post Ranap" subtitle="Apakah Pasien POST RANAP ?"
          style="justify-content: center !important; " />
      </div>
    </template>
    <template #cancel>
      <VButton :loading="isLoading" raised @click="needRedirect()">
        Tidak
      </VButton>
    </template>
    <template #action>
      <VButton color="primary" :loading="isLoading" raised @click="closeAlert()">
        Ya
      </VButton>
    </template>
  </VModal>

  <Dialog v-model:visible="isLab" modal header="Order Laboratorim" :style="{ width: '80vw' }">
    <OrderLab :pasien="props.pasien" :registrasi="props.registrasi" />
    <template #footer>
      <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="isLab = false">
        Tutup
      </VButton>
    </template>
  </Dialog>
  <Dialog v-model:visible="isRujukan" modal header="Rujukan External" :style="{ width: '80vw' }">
    <Rujukan :pasien="props.pasien" :registrasi="props.registrasi" />
    <template #footer>
      <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="isRujukan = false">
        Tutup
      </VButton>
    </template>
  </Dialog>
  <Dialog v-model:visible="isRad" modal header="Order Radiologi" :style="{ width: '80vw' }">
    <OrderRad :pasien="props.pasien" :registrasi="props.registrasi" />
    <template #footer>
      <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="isRad = false">
        Tutup
      </VButton>
    </template>
  </Dialog>
  <Dialog v-model:visible="isKonsul" modal header="Konsultasi" :style="{ width: '80vw' }">
    <Konsul :pasien="props.pasien" :registrasi="props.registrasi" />
    <template #footer>
      <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="isKonsul = false">
        Tutup
      </VButton>
    </template>
  </Dialog>
  <Dialog v-model:visible="isResep" modal header="Order Resep" :style="{ width: '80vw' }">
    <OrderResep :pasien="props.pasien" :registrasi="props.registrasi" @berhasilSimpan="tutupModalisResep" />
    <template #footer>
      <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="isResep = false">
        Tutup
      </VButton>
    </template>
  </Dialog>
  <Dialog v-model:visible="isHasilLab" modal header="Laboratorium" :style="{ width: '40vw' }">
    <HasilLabPreview :laboratorium="item.lab" :laboratorium_GROUP="item.lab_GROUP" />
  </Dialog>
  <Dialog v-model:visible="isHasilRad" modal header="Radiologi" :style="{ width: '40vw' }">
    <HasilRadPreview :data="item.radiologi" />
  </Dialog>
  <Dialog v-model:visible="isBerkas" modal header="Berkas" :style="{ width: '40vw' }">
    <BerkasPasienView :data="item.berkas" :hide="true" @lihat="lihatBerkas" />
  </Dialog>
  <Dialog v-model:visible="isPA" modal header="PA" :style="{ width: '40vw' }">
    <VCard v-for="items in item.patologi" class="mt-1" v-if="item.patologi.length" style="
    background-color: var(--info--light-color);">
      <div class="columns is-multiline">
        <div class="column is-12 ">
          <h3 class="title is-6 mb-2">{{ items.namaproduk }}
            <VTag :color="'info'" class="is-pulled-right" :label="H.formatDateIndoSimple(items.tanggalpemeriksaan)"
              rounded />
          </h3>
        </div>
        <div class="column is-12 ">
          <TStatusPojokKanan :title="'Makroskopik'" :subtitle="items.makroskopik" class="inbox-widget-2 w-100" />
          <TStatusPojokKanan :title="'Mikroskopik'" :subtitle="items.mikroskopik" class="inbox-widget-2" />
          <TStatusPojokKanan :title="'Kesimpulan'" :subtitle="items.kesimpulan" class="inbox-widget-2" />
          <TStatusPojokKanan :title="'Anjuran'" :subtitle="items.anjuran" class="inbox-widget-2" />
        </div>
      </div>
    </VCard>
    <VPlaceholderPage v-else :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle" larger>
      <template #image>
        <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
        <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
      </template>
    </VPlaceholderPage>
  </Dialog>
  <Dialog v-model:visible="isDialogSave" modal header="Preview" :style="{ width: '70vw' }">
    <CpptPreviewRev :cppt="previewCPPT" :show_resep="true" :norec_pd="NOREC_PD" />
    <template #footer>
      <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="isDialogSave = false">
        Tutup
      </VButton>
    </template>
  </Dialog>
  <Dialog v-model:visible="showModalLokalis" maximizable modal header="Set Lokalis" :style="{ width: '70vw' }">
    <div class="column is-12"
      v-if="props.registrasi.namaruangan.toUpperCase().indexOf('MATA') > -1 || props.registrasi.objectdepartemenfk == 16"
      style="margin-top: -20px;">
      <div class="columns is-multiline">
        <div class="column is-12">
          <h1 style="font-weight: bold;">Status Opthalmologi</h1>
        </div>
        <div class="column is-6">
          <h1 style="font-weight: bold; text-align: center;">UVCA</h1>
        </div>
        <div class="column is-6">
          <h1 style="font-weight: bold; text-align: center;">BCVA</h1>
        </div>
        <div class="column is-2">
          <h1 style="font-weight: bold;">Visus Awal OD</h1>
        </div>
        <div class="column is-4">
          <VField>
            <VControl icon="">
              <VInput type="text" v-model="inputLokalis.visusawalodu" placeholder="" />
            </VControl>
          </VField>
        </div>
        <div class="column is-2">
          <h1 style="font-weight: bold;">Visus Awal OD</h1>
        </div>
        <div class="column is-4">
          <VField>
            <VControl icon="">
              <VInput type="text" v-model="inputLokalis.visusawalodb" placeholder="" />
            </VControl>
          </VField>
        </div>
        <div class="column is-2">
          <h1 style="font-weight: bold;">Visus Awal OS</h1>
        </div>
        <div class="column is-4">
          <VField>
            <VControl icon="">
              <VInput type="text" v-model="inputLokalis.visusawalosu" placeholder="" />
            </VControl>
          </VField>
        </div>
        <div class="column is-2">
          <h1 style="font-weight: bold;">Visus Awal OS</h1>
        </div>
        <div class="column is-4">
          <VField>
            <VControl icon="">
              <VInput type="text" v-model="inputLokalis.visusawalosb" placeholder="" />
            </VControl>
          </VField>
        </div>
        <div class="column is-2">
          <h1 style="font-weight: bold;">Kacamata OD</h1>
        </div>
        <div class="column is-4">
          <VField>
            <VControl icon="">
              <VInput type="text" v-model="inputLokalis.kacamataodu" placeholder="" />
            </VControl>
          </VField>
        </div>
        <div class="column is-2">
          <h1 style="font-weight: bold;">Kacamata OD</h1>
        </div>
        <div class="column is-4">
          <VField>
            <VControl icon="">
              <VInput type="text" v-model="inputLokalis.kacamataodb" placeholder="" />
            </VControl>
          </VField>
        </div>
        <div class="column is-2">
          <h1 style="font-weight: bold;">Kacamata OS</h1>
        </div>
        <div class="column is-4">
          <VField>
            <VControl icon="">
              <VInput type="text" v-model="inputLokalis.kacamataosu" placeholder="" />
            </VControl>
          </VField>
        </div>
        <div class="column is-2">
          <h1 style="font-weight: bold;">Kacamata OS</h1>
        </div>
        <div class="column is-4">
          <VField>
            <VControl icon="">
              <VInput type="text" v-model="inputLokalis.kacamataosb" placeholder="" />
            </VControl>
          </VField>
        </div>
        <div class="column is-12 mt-5">
          <h1 style="font-weight: bold; text-align: center;">Nystagmus</h1>
        </div>
        <div class="column is-12">
          <h1 style="font-weight: bold; text-align: center;">Posisi/Hirschberg</h1>
        </div>
        <div class="column is-1"></div>
        <div class="column is-4">
          <VField>
            <VControl icon="">
              <VInput type="text" v-model="inputLokalis.od" placeholder="" />
            </VControl>
          </VField>
        </div>
        <div class="column is-1">
          <h1 style="font-weight: bold; text-align: center;">OD</h1>
        </div>
        <div class="column is-1">
          <h1 style="font-weight: bold; text-align: center;">OS</h1>
        </div>
        <div class="column is-4">
          <VField>
            <VControl icon="">
              <VInput type="text" v-model="inputLokalis.os" placeholder="" />
            </VControl>
          </VField>
        </div>
        <div class="column is-1"></div>
        <div class="column is-3">
          <h1 style="font-weight: bold; text-align: right;">Mata Kanan</h1>
        </div>
        <div class="column is-6"></div>
        <div class="column is-3">
          <h1 style="font-weight: bold; text-align: left;">Mata Kiri</h1>
        </div>
        <!-- Kanan -->
        <div class="column is-3">
          <div class="columns is-multiline">
            <div class="column is-4">
              <h1 style="font-weight: bold;">Palpebra</h1>
            </div>
            <div class="column is-8">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.palpebran" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Konjungtiva</h1>
            </div>
            <div class="column is-8">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.konjungtivan" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Kornea</h1>
            </div>
            <div class="column is-8">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.kornean" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Bilik Mata</h1>
            </div>
            <div class="column is-8">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.bilikmatan" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Iris</h1>
            </div>
            <div class="column is-8">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.irisn" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Pupil</h1>
            </div>
            <div class="column is-8">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.pupiln" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Lensa</h1>
            </div>
            <div class="column is-8">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.lensan" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Vitreus</h1>
            </div>
            <div class="column is-8">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.vitreusn" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Funduskopi</h1>
            </div>
            <div class="column is-8">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.funduskopin" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Schiotz</h1>
            </div>
            <div class="column is-8">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.schiotzn" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Aplanasi</h1>
            </div>
            <div class="column is-8">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.aplanasin" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">NCT</h1>
            </div>
            <div class="column is-8">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.nctn" placeholder="" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
        <div class="column is-5">
          <ImgDraw elemenID="canvasmata" height="640" width="380" imageSrc="/images/simrs/matafix-mirror.png" />
        </div>
        <!-- KIRI -->
        <div class="column is-3">
          <div class="columns is-multiline">
            <div class="column is-4">
              <h1 style="font-weight: bold;">Palpebra</h1>
            </div>
            <div class="column is-8">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.palpebrai" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Konjungtiva</h1>
            </div>
            <div class="column is-8">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.konjungtivai" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Kornea</h1>
            </div>
            <div class="column is-8">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.korneai" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Bilik Mata</h1>
            </div>
            <div class="column is-8">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.bilikmatai" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Iris</h1>
            </div>
            <div class="column is-8">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.irisi" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Pupil</h1>
            </div>
            <div class="column is-8">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.pupili" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Lensa</h1>
            </div>
            <div class="column is-8">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.lensai" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Vitreus</h1>
            </div>
            <div class="column is-8">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.vitreusi" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Funduskopi</h1>
            </div>
            <div class="column is-8">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.funduskopii" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Schiotz</h1>
            </div>
            <div class="column is-8">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.schiotzi" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Aplanasi</h1>
            </div>
            <div class="column is-8">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.aplanasii" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">NCT</h1>
            </div>
            <div class="column is-8">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.ncti" placeholder="" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
        <div class="column is-2" style="margin-top: 50px;">
          <h1 style="font-weight: bold;">Test Anel</h1>
        </div>
        <div class="column is-10" style="margin-top: 50px;">
          <VField>
            <VControl icon="">
              <VInput type="text" v-model="inputLokalis.testanel" placeholder="" />
            </VControl>
          </VField>
        </div>
        <div class="column is-2">
          <h1 style="font-weight: bold;">Test Buta Warna</h1>
        </div>
        <div class="column is-10">
          <VField>
            <VControl icon="">
              <VInput type="text" v-model="inputLokalis.testbutawarna" placeholder="" />
            </VControl>
          </VField>
        </div>
        <div class="column is-2">
          <h1 style="font-weight: bold;">Test Fluoresin</h1>
        </div>
        <div class="column is-10">
          <VField>
            <VControl icon="">
              <VInput type="text" v-model="inputLokalis.testfluoresin" placeholder="" />
            </VControl>
          </VField>
        </div>
        <div class="column is-2" style="margin-top: 50px;">
          <h1 style="font-weight: bold;">Resep Kacamata</h1>
        </div>
        <div class="column is-2" style="margin-top: 50px;">
          <h1 style="font-weight: bold;">R/</h1>
        </div>
        <div class="column is-8">
          <VField>
            <VControl>
              <VRadio v-model="item.optionsmata" :value="'jauh'" label="Jauh" name="Jauh" color="primary" id="Jauh" />
              <VRadio v-model="item.optionsmata" :value="'dekat'" label="Dekat" name="Dekat" color="primary"
                id="Dekat" />
              <VRadio v-model="item.optionsmata" :value="'progresif'" label="Progresif" name="Progresif" color="primary"
                id="Progresif" />
              <VRadio v-model="item.optionsmata" :value="'tidakada'" label="Tidak Ada" name="Tidak Ada" color="danger"
                id="TidakAda" />
            </VControl>
          </VField>
        </div>
        <div class="column is-12" v-if="item.optionsmata != 'tidakada' && item.optionsmata != undefined">
          <div class="columns is-multiline">
            <div class="column is-6">
              <h1 style="font-weight: bold;">OD</h1>
            </div>
            <div class="column is-6">
              <h1 style="font-weight: bold;">OS</h1>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Spheris</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.spherisd" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Spheris</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.spheriss" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Cylinder</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.cylinderd" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Cylinder</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.cylinders" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Prisma</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.prismad" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Prisma</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.prismas" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Axis</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.axisd" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Axis</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.axiss" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2 mt-5">
              <h1 style="font-weight: bold;">Addition</h1>
            </div>
            <div class="column is-10 mt-5">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="inputLokalis.addition" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2 mt-5">
              <h1 style="font-weight: bold;">Pupil Distance</h1>
            </div>
            <div class="column is-10 mt-5">
              <VField addons>
                <VControl expanded>
                  <VInput type="number" class="input" placeholder="" v-model="inputLokalis.pupil" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>mm</VButton>
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </div>
    </div>
    <template #footer>
      <VButton icon="lucide:check" class="rem-100 mr-4" color="success" @click="setLokalisMata()" :loading="isLoading">
        Set Lokalis
      </VButton>
      <VButton icon="feather:save" class="rem-100 mr-4" color="info" @click="updateLokalisMata()" :loading="isLoading">
        Simpan Lokalis
      </VButton>
      <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="showModalLokalis = false">
        Tutup
      </VButton>
      <!-- <VButton type="button" rounded outlined color="primary" class="ml-2" raised icon="feather:save"
        :loading="isLoading" @click="simpanReal"> Simpan
      </VButton> -->
    </template>
  </Dialog>
  <Dialog v-model:visible="modalInputTP" modal header="Konsultasi" :style="{ width: '60vw' }" size="large">
    <div class="columns is-multiline">
      <div class="column is-3">
        <VDatePicker class="pt-0 pb-0 pl-0" v-model="inputTP.tanggal" color="green" trim-weeks mode="dateTime">
          <template #default="{ inputValue, inputEvents }" class="pb-0">
            <VField>
              <VLabel class="required-field">Tanggal</VLabel>
              <VControl icon="feather:calendar">
                <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                  class="is-rounded" :disabled="disabledJawab" />
              </VControl>
            </VField>
          </template>
        </VDatePicker>
      </div>
      <div class="column is-3">
        <VField class="is-select is-autocomplete-select
                            mt-0 pt-0" v-slot="{ id }">
          <VLabel class="required-field">Ruang Asal</VLabel>
          <VControl icon="fas fa-bookmark" fullwidth class="prime-auto-select">
            <Dropdown v-model="inputTP.ruanganasal" :options="d_Ruangan" :optionLabel="'label'" class="is-rounded"
              placeholder="Ruang Asal" style="width: 100%;" :filter="true" showClear :disabled="true" />
          </VControl>
        </VField>
      </div>
      <div class="column is-3">
        <VField class="is-select is-autocomplete-select
                            mt-0 pt-0" v-slot="{ id }">
          <VLabel class="required-field">Ruangan Tujuan</VLabel>
          <VControl icon="fas fa-bookmark" fullwidth class="prime-auto-select">
            <Dropdown v-model="inputTP.ruangantujuan" :options="d_Ruangan" :optionLabel="'label'" class="is-rounded"
              placeholder="Ruang Tujuan" style="width: 100%;" :filter="true" showClear />
          </VControl>
        </VField>
      </div>
      <div class="column is-3">
        <VField class="is-select is-autocomplete-select mt-0 pt-0" v-slot="{ id }">
          <VLabel>Dokter/Pegawai Medis </VLabel>
          <VControl icon="fas fa-bookmark" fullwidth class="prime-auto-select">
            <VInput type="text" placeholder="Dokter DPJP" v-model="inputTP.namadokter" class="is-rounded"
              :disabled="disabledJawab" />
          </VControl>
        </VField>
      </div>
    </div>
    <template #footer>
      <VButton icon="feather:refresh-cw rem-100" light dark-outlined @click="modalInputTP = false">
        Batal
      </VButton>
      <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
        @click="postTransferPasien()"> Simpan
      </VButton>
    </template>
  </Dialog>
  <Dialog v-model:visible="modalTindakan" modal header="Tindakan" :style="{ width: '100rem' }"
    :breakpoints="{ '1199px': '75vw', '575px': '90vw' }" maximizable>
    <Tindakan v-if="modalTindakan" :nocmfk="props.registrasi.nocmfk" :norec_pd="props.registrasi.norec_pd"
      :norec_apd="props.registrasi.norec_apd" :pasien="props.pasien" :registrasi="props.registrasi" />
  </Dialog>
  <Dialog v-model:visible="modalResep" modal header="Order Resep" :style="{ width: '100rem' }"
    :breakpoints="{ '1199px': '75vw', '575px': '90vw' }" maximizable>
    <Resep v-if="modalResep" :ID_PASIEN="ID_PASIEN" :NOREC_PD="NOREC_PD" :pasien="props.pasien"
      :noregis="props.registrasi.noregistrasi" :registrasi="props.registrasi" />
  </Dialog>
  <Dialog v-model:visible="modalKonsultasi" modal header="Konsultasi" :style="{ width: '100rem' }"
    :breakpoints="{ '1199px': '75vw', '575px': '90vw' }" maximizable>
    <Konsultasi v-if="modalKonsultasi" :nocmfk="props.registrasi.nocmfk" :norec_pd="props.registrasi.norec_pd"
      :norec_apd="props.registrasi.norec_apd" :pasien="props.pasien" :registrasi="props.registrasi" />
  </Dialog>
  <Dialog v-model:visible="modalLaboratorium" modal header="Order Laboratorium" :style="{ width: '100rem' }"
    :breakpoints="{ '1199px': '75vw', '575px': '90vw' }" maximizable>
    <Laboratorium v-if="modalLaboratorium" :nocmfk="props.registrasi.nocmfk" :NOREC_PD="NOREC_PD"
      :norec_apd="props.registrasi.norec_apd" :pasien="props.pasien" :registrasi="props.registrasi" />
  </Dialog>
  <Dialog v-model:visible="modalRadiologi" modal header="Order Radiologi" :style="{ width: '100rem' }"
    :breakpoints="{ '1199px': '75vw', '575px': '90vw' }" maximizable>
    <Radiologi v-if="modalRadiologi" :nocmfk="props.registrasi.nocmfk" :NOREC_PD="NOREC_PD"
      :norec_apd="props.registrasi.norec_apd" :pasien="props.pasien" :registrasi="props.registrasi" />
  </Dialog>
  <Dialog v-model:visible="modalPenunjangKhusus" modal header="Penunjang Khusus" :style="{ width: '100rem' }"
    :breakpoints="{ '1199px': '75vw', '575px': '90vw' }" maximizable>
    <Penunjang v-if="modalPenunjangKhusus" :nocmfk="props.registrasi.nocmfk" :norec_pd="props.registrasi.norec_pd"
      :norec_apd="props.registrasi.norec_apd" :pasien="props.pasien" :registrasi="props.registrasi" />
  </Dialog>
  <Dialog v-model:visible="modalBedah" modal header="Bedah" :style="{ width: '100rem' }"
    :breakpoints="{ '1199px': '75vw', '575px': '90vw' }" maximizable>
    <Bedah v-if="modalBedah" :nocmfk="props.registrasi.nocmfk" :norec_pd="props.registrasi.norec_pd"
      :norec_apd="props.registrasi.norec_apd" :pasien="props.pasien" :registrasi="props.registrasi" />
  </Dialog>
  <Dialog v-model:visible="modalHandoverAntarShiftRanap" modal header="Handover Antar Shift Rawat Inap"
    :style="{ width: '100rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }" maximizable>
    <Handover v-if="modalHandoverAntarShiftRanap" :nocmfk="props.registrasi.nocmfk"
      :norec_pd="props.registrasi.norec_pd" :norec_apd="props.registrasi.norec_apd" :pasien="props.pasien"
      :registrasi="props.registrasi" />
  </Dialog>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount, nextTick } from 'vue'
import { useRoute, useRouter, onBeforeRouteUpdate, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
import Dropdown from 'primevue/dropdown';
import Calendar from 'primevue/calendar';
import Dialog from 'primevue/dialog';
import moment from 'moment'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import { v4 as uuidv4 } from 'uuid';
import sleep from '/@src/utils/sleep'
import OrderLab from './order-laboratorium.vue'
import OrderRad from './order-radiologi.vue'
import OrderResep from './order-resep.vue'
import Tindakan from './tindakan.vue'
import Penunjang from './penunjang.vue'
import Resep from './order-resep.vue'
import Konsultasi from './konsultasi.vue'
import Laboratorium from './order-laboratorium.vue'
import Radiologi from './order-radiologi.vue'
import Bedah from './order-bedah.vue'
import Konsul from './konsultasi.vue'
import HasilLabPreview from './hasil-lab-preview.vue'
import HasilRadPreview from './hasil-rad-preview.vue'
import BerkasPasienView from './berkas-pasien-preview.vue'
import TStatusPojokKanan from '../t-status-pojok-kanan.vue'
import CpptPreviewRev from './cppt-preview-rev.vue'
import Rujukan from '/@src/pages/module/integrasi-sistem/rujukan.vue'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from "primevue/useconfirm"
import VueScrollTo from 'vue-scrollto'
import DataTable from 'primevue/datatable'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import Column from 'primevue/column'
import { useToaster } from '/@src/composable/toaster'
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';
import ImgDraw from '../page-emr-plugins/img-draw.vue'
import * as EMR2 from "../page-emr-plugins/rencana-keperawatan-ranap";

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let paramRiwayat = useRoute().query.riwayat as boolean
const props = withDefaults(
  defineProps<{
    pasien?: any
    registrasi?: any
    FORM_NAME?: string
    FORM_URL?: string
    COLLECTION?: string
    hideButtons?: boolean;
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: '',
    FORM_URL: '',
    COLLECTION: '',
    hideButtons: false,
  }
)

const filtersTemplate = ref({global: { value: null, matchMode: FilterMatchMode.CONTAINS }});
const isAlltemplate: any = ref(false);
const { scrollTo } = VueScrollTo
const route = useRoute()
const isResep: any = ref(false)
const isLab: any = ref(false)
const isRujukan: any = ref(false)
const isRad: any = ref(false)
const isPA: any = ref(false)
const isKonsul: any = ref(false)
const isBerkas: any = ref(false)
const isDokter: any = ref(false)
const isPerawat: any = ref(false)
const isProfesi: any = ref(false)
const isGizi: any = ref(false)
const isRuangan: any = ref(false)
const isAllPeriode: any = ref(false)
const dataSourceICD10: any = ref([])
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const isloadingLAMPAU: any = ref(false)
const isCPPTOLD: any = ref(true)
const isHasilLab: any = ref(false)
const isHasilRad: any = ref(false)
const strforS: any = ref('');
const strforO: any = ref('');
const strforA: any = ref('');
const strforP: any = ref('');
const scrollContainer = ref(null);
const isLoadingBill: any = ref(false)
const dataSource: any = ref([])
const tujuanKeper = ref('');
const rowGroupLAB: any = ref({})
const d_DiagnosaKeperawatan: any = ref([])
const d_DiagnosaSdki: any = ref([])
const d_Siki = ref([])
const selectedSiki = ref([])
const filterMenu: any = ref('')
const d_JenisDiagnosa: any = ref([])
const d_Diagnosa: any = ref([])
const d_Diagnosa9: any = ref([])
const d_Diagnosa10: any = ref([])
const d_CopyDiagnosa: any = ref([])
const d_TujuanKeperawatan: any = ref([])
const d_IntervensiKeperawatan: any = ref([])
let panjangsoapbaru = []
const isAfterSave: any = ref(false)
const listSIMRSLama: any = ref([])
const listTemplate: any = ref([])
const isDialogSave: any = ref(false)
const isRaber: any = ref(false)
const showPreviewResep: any = ref(false)
const showModalKonselor: any = ref(false)
const showModalKeperawatan: any = ref(false)
const showModalRencanaKeperawatan: any = ref(false)
const showRencanaKebidanan: any = ref(false)
const showModalDiagnosaBidan: any = ref(false)
const showModalObat: any = ref(false)
const showModalTemplate: any = ref(false)
const showModalVitalSign: any = ref(false)
const showModalTindakan: any = ref(false)
const showModalLokalis: any = ref(false)
const previewCPPT: any = ref([]);
const router = useRouter()
const isResumeMedis: any = ref(false);
const modalInputTP: any = ref(false);
const FORM_NAME = route.query.form_name as string || props.FORM_NAME

const userLogin = useUserSession().getUser()
const confirm = useConfirm();
const user = useUserSession().getUser().kelompokUser.kelompokUser
let kelompokUser = '';
let NORECTP = '';
if (user.toUpperCase().indexOf('GIZI') > -1 || userLogin.id == '988') {
  kelompokUser = 'gizi'
} else if (user.indexOf('perawat') > -1 || user == 'igd' || user.toUpperCase().indexOf('PONEK') > -1 || user.toUpperCase().indexOf('BIDAN') > -1) {
  kelompokUser = 'perawat'
} else if (user.toUpperCase().indexOf('DOKTER') > -1) {
  kelompokUser = 'dokter'
} else if (user.indexOf('farmasi') > -1) {
  kelompokUser = 'profesi lain'
} else {
  kelompokUser = 'profesi lain';
}

const emit = defineEmits<{
  (event: 'update-signature', signature: string): void;
}>();

let ctx: CanvasRenderingContext2D | null = null;
function asmedGadar() {
  router.push({
    name: 'module-emr-profile-pasien-page-emr-asesmen-awal-medis-gawat-darurat',
    query: {
      nocmfk: ID_PASIEN,
      norec_pd: item.NOREC_PD,
      norec_apd: item.NOREC_APD
    }
  })
}

const passCPPT: any = ref({
  status: false,
  title: '',
  message: '',
  link: '',
  tab: '',
  collection: ''
});
const dataAssesmen: any = ref();
const d_gcse: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }])
const d_gcsv: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }, { value: 'T', label: 'T' }, { value: 'X', label: 'X' }])
const d_gcsm: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }, { value: 6, label: '6' }])
const d_keadaanumum: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Sedang' }, { value: 3, label: 'Buruk' }])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: props.registrasi.norec_apd,
  registrasi: {},
  selectedMenu: [false],
  filter: '',
  lab: [],
  lab_GROUP: [],
  radiologi: [],
  patologi: [],
  tglpelayanan: new Date(),
  intruksiPPA: '',
  tgl: '',
  qFilterTgl: {
    start: new Date(new Date().setDate(new Date().getDate() - 3)),
    end: new Date()
  }
})
const pegawaiId = useUserSession().getUser().pegawai.id
const COLLECTION: any = ref('CatatanPerkembanganPasienTerintegrasi') //table mongodb
const NOREC_EMRPASIEN: any = ref(norec_emr ? norec_emr : '')
const idTemplateCPPT: any = ref('');
const alertMid: any = ref(false)
const array_dokter: any = ref(
  {
    uuid: uuidv4(),
    no: 1,
    tgl: new Date(),
    tglVerifikasi: new Date(),
    flag: 'dokter',
    ruangan: props.registrasi.namaruangan,
    diagnosaDokter: [{
      no: 1
    }],
    diagnosaDokter9: [{
      no: 1
    }],
    dpjpRawatBersama: [{ id: null, isintruksi: false, intruksi: null }],
    dokterraber: false,
    handover: false,
    dpjpUtama: { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk },
    dokterDPJP: { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk },
    tenagaMedis: { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id },
    author: userLogin.pegawai
  }
)
const array_perawat: any = ref({
  uuid: uuidv4(),
  no: 1,
  tgl: new Date(),
  tglVerifikasi: new Date(),
  flag: 'perawat',
  ruangan: props.registrasi.namaruangan,
  diagnosaKep: [{
    no: 1
  }],
  tujuanKep: [{
    no: 1
  }],
  dpjpRawatBersama: [{ id: null, isintruksi: false, intruksi: null }],
  dokterraber: false,
  handover: false,
  dpjpUtama: { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk },
  dokterDPJP: { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk },
  tenagaMedis: { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id },
  author: userLogin.pegawai
}
)
const array_profesi: any = ref({
  uuid: uuidv4(),
  no: 1,
  tgl: new Date(),
  tglVerifikasi: new Date(),
  flag: 'profesi lain',
  ruangan: props.registrasi.namaruangan,
  dpjpRawatBersama: [{ id: null, isintruksi: false, intruksi: null }],
  dokterraber: false,
  handover: false,
  dokterDPJP: { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk },
  dpjpUtama: { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk },
  tenagaMedis: { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id },
  author: userLogin.pegawai
})
const array_gizi: any = ref({
  uuid: uuidv4(),
  no: 1,
  tgl: new Date(),
  tglVerifikasi: new Date(),
  flag: 'gizi',
  ruangan: props.registrasi.namaruangan,
  dpjpRawatBersama: [{ id: null, isintruksi: false, intruksi: null }],
  dokterraber: false,
  handover: false,
  dokterDPJP: { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk },
  dpjpUtama: { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk },
  tenagaMedis: { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id },
  author: userLogin.pegawai
})
const input: any = ref({
  // dpjpRawatBersama: [{ id: null, isintruksi: false, intruksi: null }],
  details: [
    array_dokter.value, array_perawat.value, array_profesi.value, array_gizi.value],
  keadaanumumobgyn: 1,
  selectedDiagnosa: []
})
const inputLokalis: any = ref({})
const NOREC_EMRPASIEN_ASMED: any = ref('');
const input2: any = ref([])
let flagemr = null
const indexcppt: any = ref(0)
let indexcpptcopy = 1
const riwayatResep: any = ref([])
const d_Dokter: any = ref([])
const d_Pegawai: any = ref([])
const currentStep = ref(0)
const diaglainnyaList = ref<string[]>(['']);
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const riwayatDataCPPT: any = ref([]);
const diaglainnya = ref(false);
const dig = Array(23).fill(false);
const kons = Array(6).fill(false);
const rencanaKeper = Array(40).fill(false);
const rencanaKeperLain: any = ref('');
const rencanaKeperLainnya: any = ref(false);
const inputTP: any = ref({
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
})
const diagBidan: any = ref({});
const disabledJawab: any = ref(false);
const d_Kelas: any = ref([])
const d_Ruangan: any = ref([])

const filterData = computed(() => {
  if (!input2.value[0] || !input2.value[0].detailsFull) {
    return [];
  }

  if (isAllPeriode.value) {
    return input2.value[0].detailsFull;
  }

  let start = new Date(item.qFilterTgl.start);
  start.setHours(0, 0, 0, 0);

  let end = new Date(item.qFilterTgl.end);
  end.setHours(23, 59, 59, 999);

  const filtered = input2.value[0].detailsFull.filter((detail) => {
    const detailDate = new Date(detail.tgl);
    return detailDate >= start && detailDate <= end;
  });

  return filtered;
});

const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
  console.log(kelompokUser)
}
const tutupModalisResep = async () => {
  isResep.value = false
}

const raberChange = async (e: any) => {
  console.log(e)
  console.log("raber", input.value)
}

const handoverChange = async (e: any) => {
  console.log(e)
  console.log("handover", input.value)
}

const konselor = async (index: any) => {
  indexcppt.value = index
  for (let i = 0; i < kons.length; i++) {
    kons[i] = false;
  }
  showModalKonselor.value = true
}

const addNewKeperawatan = async (index: any, type: string) => {
  indexcppt.value = index

  for (let i = 0; i < dig.length; i++) {
    dig[i] = false;
  }

  if (type == 'perawat') {
    showModalKeperawatan.value = true
  } else if (type == 'bidan') {
    diagBidan.value = ref();
    showModalDiagnosaBidan.value = true;
  }

}

const editMode: any = ref(false)
const editReal = async () => {
  delete input.value.details[0]['_id']
  let json = {
    data: input.value.details[0],
    method: 'update'
  }
  isLoading.value = true
  await useApi().post('/emr/update-cppt', json).then((res: any) => {
    isLoading.value = false
    H.alert('success', 'Edit CPPT Berhasil!');
    location.reload();
  }).catch((e: any) => {
    isLoading.value = false
    H.alert('error', 'Edit CPPT Gagal!');
  })
}
const editCPPT = (e: any) => {
  confirm.require({
    message: 'Apakah anda yakin ingin mengedit riwayat CPPT ini ?',
    header: 'Edit CPPT',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      let lastId = 0;
      let lastIndex = 0;
      editMode.value = true
      for (let kDet = 0; kDet < input.value.details.length; kDet++) {
        const dt = input.value.details[kDet];
        if (dt.flag == kelompokUser) {
          lastIndex = kDet;
          lastId = dt.no
          break;
        }
      }
      console.log(e)
      input.value.details[lastIndex] = e
      if (e.handover) {
        nextTick(() => {
          H.tandaTangan().set(`TTDpetugasPenerima_${e.no}`, e.TTDpetugasPenerima)
          H.tandaTangan().set(`TTDpetugasPemberi_${e.no}`, e.TTDpetugasPemberi)
        });
      }
    },
    reject: () => { },
  })
}

const hapusCPPT = (e: any) => {
  confirm.require({
    message: 'Apakah anda yakin ingin menghapus riwayat CPPT ini ?',
    header: 'Hapus CPPT',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: async () => {
      let json = {
        uuid: e.uuid,
        method: 'hapus'
      }
      await useApi().post('/emr/update-cppt', json).then((res: any) => {
        loadRiwayatOld()
      }).catch((e: any) => {
        H.alert('error', 'Hapus CPPT Gagal!');
      })
    },
    reject: () => { },
  })
}

const addNewRencanaKeperawatan = async (index: any, type: string) => {
  indexcppt.value = index

  rencanaKeperLainnya.value = false;
  rencanaKeperLain.value = '';
  for (let i = 0; i < rencanaKeper.length; i++) {
    rencanaKeper[i] = false;
  }
  if (type == 'perawat') {
    showModalRencanaKeperawatan.value = true
  } else if (type == 'bidan') {
    showRencanaKebidanan.value = true
  }

}
const tambahan = () => {
  diaglainnyaList.value.push('');
};
const hapus = (index) => {
  diaglainnyaList.value.splice(index, 1);
};

const pilihTemplate = async (index: any) => {
  isloadingLAMPAU.value = true
  useApi().get(
    `/emr/get-emr-cppt?nocmfk=${ID_PASIEN}&norec_apd=${item.NOREC_APD}&collection=${COLLECTION.value}`).then((response: any) => {
      isloadingLAMPAU.value = false
      listTemplate.value = response
      showModalTemplate.value = true
    })

}

const inputObat = async (index: any) => {
  isLoading.value = true;
  isLoadingBill.value = true;
  let resObat = await useApi().get(`/farmasi/riwayat-order-resep?norec_pd=${item.NOREC_PD}`)
  if (resObat.length > 0) {
    isLoading.value = false;
    isLoadingBill.value = false;
    let filterNoreg = resObat.filter((dt) => {
      return dt.noregistrasi == props.registrasi.noregistrasi;
    });
    if (filterNoreg.length > 0) {
      let stringObat = ''

      filterNoreg[0].details.forEach(elO => {
        stringObat += `# ${elO.namaproduk} (${elO.aturanpakai}) `;
      });

      // input.value.details.forEach(element => {
      let element = input.value.details[indexcppt.value];
      // if (element.no == indexcppt.value + 1) {
      if (element.P == undefined) {
        element.P = ''
      }

      element.P += stringObat
      // }
      // });
    } else {
      isLoading.value = false;
      isLoadingBill.value = false;
      H.alert('warning', 'Belum ada Obat');
    }

  } else {
    isLoadingBill.value = false;
    isLoading.value = false;
    H.alert('warning', 'Belum ada Obat');
  }
}

const addDiagnosaTen = async (index: any) => {
  if (kelompokUser.toUpperCase().indexOf('PERAWAT') > -1) {
    H.alert('warning', 'Tidak ada data');
    return;
  }
  indexcppt.value = index;
  isLoading.value = true;
  isLoadingBill.value = true;
  let ruangan = props.registrasi.namaruangan;
  let type = "POLI";
  if (ruangan.toUpperCase().indexOf('IGD') > -1) type = "IGD";
  if (ruangan.toUpperCase().indexOf('KEDOKTERAN NUKLIR') > -1) type = "NUKLIR";


  await useApi().get(`emr/get-medis-diagnosa?nocmfk=${ID_PASIEN}&type=${type}`).then((res) => {
    isLoading.value = false;
    isLoadingBill.value = false;
    if (res) {
      let element = input.value.details[indexcppt.value];
      if (element.A == undefined) {
        element.A = ''
      }

      element.A += res.TADiagnosa
      if (type == "NUKLIR") {
        element.A += res.TADiagnosa
      }
    } else {
      H.alert('warning', 'Diagnosa belum diinput');
    }
  });
}

const inputObatOLD = async (index: any) => {
  showModalObat.value = true
  indexcppt.value = index
  listSIMRSLama.value = []
  let lokal = false;
  let riwayat1 = []

  let responseX = await useApi().get(`/farmasi/riwayat-order-resep?norec_pd=${NOREC_PD}`)

  console.log(responseX)

  for (let x = 0; x < responseX.length; x++) {
    const element = responseX[x];
    riwayat1.push({
      'namalengkap': element.namalengkap,
      'noregistrasi': element.noregistrasi,
      'noorder': element.noorder,
      'tglorder': moment(element.tglorder).format('DD-MM-YYYY'),
      'details': element.details,
      'simslama': true,
    })
  }
  listSIMRSLama.value = riwayat1
  console.log(listSIMRSLama.value)

}

const validateStep = async () => {
  if (currentStep.value === 4) {
    if (isLoading.value) {
      return
    }

    isLoading.value = true

    return
  }

  isLoading.value = true
  await sleep(400)
  currentStep.value += 1

  nextTick(() => {
    scrollTo(`#form-step-${currentStep.value}`, 1000)
    isLoading.value = false
  })
}

const diagnosa = async () => {
  await useApi().get(`emr/get-diagnosa-pasien-icd10?nocmfk=${ID_PASIEN}`).then((response) => {
    response.forEach((element: any, i: any) => {
      element.no = i + 1
      element.tglInput = H.formatDate(element.tglinputdiagnosa, 'DD-MM-YYYY')
    });
    dataSourceICD10.value = response
  })
}

const loadRiwayat = async () => {
  isAfterSave.value = false
  isloadingLAMPAU.value = true
  setAutoFill();
  //ini tambahan
  let urlGetRiwayat = `/emr/get-emr-cppt?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&norec_apd=${item.NOREC_APD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`;
  const response = await useApi().get(urlGetRiwayat)
  isloadingLAMPAU.value = false
  if (response.length) {
    if (response[0].details.length == 0) {
      response[0].details = input.value.details;
    } else {
      if (response[0].details[0].flag == undefined) {
        response[0].details = input.value.details;
      }
    }

    let perawat = []
    let dokter = []
    isAfterSave.value = true
    let findMissing = response[0].details.filter((dcppt) => {
      return dcppt.flag == kelompokUser;
    });
    let findForMissing = input.value.details.filter((icppt) => {
      return icppt.flag == kelompokUser;
    });

    if (findMissing.length == 0) {
      // let glength = input.value.details[0]
      response[0].details.push(findForMissing[0]);
    }

    // for (let x = 0; x < response[0].details.length; x++) {
    if (kelompokUser.includes("perawat")) {

      for (let x = 0; x < response[0].details.length; x++) {
        const element = response[0].details[x];
        if (element.flag == 'perawat') {
          perawat.push(element)
        }
        if (element.flag == 'dokter') {
          dokter.push(element)
        }

        element.tgl = new Date(element.tgl);
        element.tglVerifikasi = new Date(element.tglVerifikasi);
        if (element.dpjpRawatBersama == undefined) {
          element.dpjpRawatBersama = [{
            id: null
          }]
        }

        dokter.forEach(item => {
          if (item.no == element.no) {
            if (!item.S) {
              item.S = element.S
            }
            if (!item.O) {
              item.O = element.O
            }
            if (!item.P) {
              item.P = element.P
            }
          }
        });

        if (element.flag == 'perawat') {
          dokter.forEach(item => {
            if (item.no == element.no) {
              if (!item.S) {
                item.S = element.S
              }
              if (!item.O) {
                item.O = element.O
              }
              if (!item.P) {
                item.P = element.P
              }
            }
          });
          if (element.tujuanKep == undefined) {
            element.tujuanKep = [{
              no: 1
            }]
          }
        }
      }
      input.value = response[0] //set ke inputan

      for (let z = 0; z < response.length; z++) {
        panjangsoapbaru.push(response[z])
      }

      setValueDisabled()
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
    } else {
      isLoading.value = true
      if (NOREC_EMRPASIEN.value == '') {
        let responsetgl = await useApi().get(
          `/emr/get-emr-tgl-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`)
        isLoading.value = false
        console.log(responsetgl.length)
        if (responsetgl.length > 0) {
          confirm.require({
            message: 'Asesmen terakhir tanggal ' + H.formatDate(responsetgl[0].created_at, 'DD-MM-YYYY HH:mm:ss') + ', apakah mau mengambil data sebelumnya?',
            header: 'Riwayat Terakhir',
            icon: 'pi pi-info-circle',
            acceptClass: 'p-button-danger',
            accept: () => {
              isloadingLAMPAU.value = true
              useApi().get(
                `/emr/get-emr-cppt?nocmfk=${ID_PASIEN}&norec_apd=${item.NOREC_APD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
                  isloadingLAMPAU.value = false
                  if (response.length) {
                    if (response[0].details.length == 0) {
                      response[0].details = input.value.details;
                    }
                    let perawat = []
                    let dokter = []
                    isAfterSave.value = true
                    response[0].details.forEach(element => {
                      if (element.flag == 'perawat') {
                        perawat.push(element)
                      }
                      if (element.flag == 'dokter') {
                        dokter.push(element)
                      }
                    });

                    for (let x = 0; x < response[0].details.length; x++) {
                      const element = response[0].details[x];
                      element.tgl = new Date(element.tgl);
                      element.tglVerifikasi = new Date(element.tglVerifikasi);

                      if (element.dpjpRawatBersama == undefined) {
                        element.dpjpRawatBersama = [{
                          id: null
                        }]
                      }
                      if (element.flag == 'perawat') {
                        dokter.forEach(item => {
                          if (item.no == element.no) {
                            if (!item.S) {
                              item.S = element.S
                            }
                            if (!item.O) {
                              item.O = element.O
                            }
                            if (!item.P) {
                              item.P = element.P
                            }
                          }
                        });
                        if (element.tujuanKep == undefined) {
                          element.tujuanKep = [{
                            no: 1
                          }]
                        }
                      }
                    }
                    input.value = response[0] //set ke inputan
                    input.value.namatemplate = ''

                    for (let z = 0; z < response.length; z++) {
                      panjangsoapbaru.push(response[z])
                    }

                    isLoading.value = false;

                    setValueDisabled()
                    if (NOREC_EMRPASIEN.value == '') {
                      NOREC_EMRPASIEN.value = response[0].emrpasienfk
                    }
                  }
                })

            },
            reject: () => {
              isLoading.value = false;
            },
          })
        }

      }
    }
    // }
    input.value = response[0]
    riwayatDataCPPT.value = response[0]
    for (let z = 0; z < response.length; z++) {
      panjangsoapbaru.push(response[z])
    }

    setValueDisabled()
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
  } else {
    isLoading.value = true
    if (NOREC_EMRPASIEN.value == '') {
      let responsetgl = await useApi().get(
        `/emr/get-emr-tgl-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`)
      isLoading.value = false
      if (responsetgl.length > 0) {
        confirm.require({
          message: 'Asesmen terakhir tanggal ' + H.formatDate(responsetgl[0].created_at, 'DD-MM-YYYY HH:mm:ss') + ', apakah mau mengambil data sebelumnya?',
          header: 'Riwayat Terakhir',
          icon: 'pi pi-info-circle',
          acceptClass: 'p-button-danger',
          accept: () => {
            isloadingLAMPAU.value = true
            useApi().get(
              `/emr/get-emr-cppt?nocmfk=${ID_PASIEN}&norec_apd=${item.NOREC_APD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
                isloadingLAMPAU.value = false
                if (response.length) {
                  if (response[0].details.length == 0) {
                    response[0].details = input.value.details;
                  }

                  let perawat = []
                  let dokter = []
                  isAfterSave.value = true
                  response[0].details.forEach(element => {
                    if (element.flag == 'perawat') {
                      perawat.push(element)
                    }
                    if (element.flag == 'dokter') {
                      dokter.push(element)
                    }
                  });

                  for (let x = 0; x < response[0].details.length; x++) {
                    const element = response[0].details[x];
                    element.tgl = new Date(element.tgl);
                    element.tglVerifikasi = new Date(element.tglVerifikasi);

                    if (element.dpjpRawatBersama == undefined) {
                      element.dpjpRawatBersama = [{
                        id: null
                      }]
                    }

                    if (element.flag == 'perawat') {
                      dokter.forEach(item => {
                        if (item.no == element.no) {
                          if (!item.S) {
                            item.S = element.S
                          }
                          if (!item.O) {
                            item.O = element.O
                          }
                          if (!item.P) {
                            item.P = element.P
                          }
                        }
                      });
                      if (element.tujuanKep == undefined) {
                        element.tujuanKep = [{
                          no: 1
                        }]
                      }
                    }
                  }
                  input.value = response[0] //set ke inputan
                  riwayatDataCPPT.value = response[0].details;
                  input.value.namatemplate = ''

                  for (let z = 0; z < response.length; z++) {
                    panjangsoapbaru.push(response[z])
                  }

                  // setValueDisabled()
                  if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                  }
                }
              })

          },
          reject: () => {
          },
        })
      }

    }
  }

  // await

}


const clearItem = (index: any, element: string) => {
  input.value.details[index][element] = ''
}

// const clearItem = (e: any) => {

//   const textArea = document.createElement('textarea');
//   input.value.details.forEach(element => {
//     if (element.flag == 'dokter') {
//       for (var key in myObject) {

//       }
//     }
//   });
//   console.log(e[key])
//   if (key == i) {
//     console.log(key)
//   }
// }
// document.body.removeChild(item)
// console.log(e)
const addDiagnosaDok = async (data: any) => {
  // if (!data.diagnosaa) {
  //   H.alert('info', 'Diagnosa harus di isi')
  //   return
  // }
  // if (!data.jenisDiagnosa) {
  //   H.alert('info', 'Jenis Diagnosa harus di isi')
  //   return
  // }
  // if( data.diagnosaa.value == undefined){
  //   H.alert('info', 'Diagnosa harus di isi')
  //   return
  // }
  data.isLoadBtnDiagnosaDokter = true
  let objSave = {
    'diagnosapasien': {
      'norec': data.norecDiagnosa ? data.norecDiagnosa : '',
      'noregistrasifk': props.registrasi.norec_apd,
      'ketdiagnosis': data.keterangan ? data.keterangan : null,
      'iskasusbaru': null,
      'iskasuslama': null,
    },
    'detaildiagnosapasien': {
      'objectdiagnosafk': data.diagnosaa ? data.diagnosaa.value : null,
      'tglinputdiagnosa': H.formatDate(new Date(), 'YYYY-MM-DD HH:mm:ss'),
      'objectjenisdiagnosafk': data.jenisDiagnosa ? data.jenisDiagnosa.value : null,
      'noregistrasi': props.registrasi.noregistrasi,
    },
    'pasien': {
      'nocm': props.pasien.nocm,
      'namapasien': props.pasien.namapasien,
      'noregistrasi': props.registrasi.noregistrasi,
    }
  }

  await useApi().post(`/diagnosa/save-diagnosa`, objSave).then((response: any) => {
    if (!data.norecDiagnosa) {
      // tambahBariDiag()
    }
    data.norecDiagnosa = response.norec
    fetchDiagnosaX()
    data.isLoadBtnDiagnosaDokter = false
  }).catch((e: any) => {
    console.log(e)
  })

}
const tambahBariDiag = () => {
  for (let x = 0; x < input.value.details.length; x++) {
    const element = input.value.details[x];
    if (element.diagnosaDokter && element.diagnosaDokter.length) {

    }
  }
}
const removeDiagnosaDok = async (data: any) => {
  await useApi().post(`/diagnosa/delete-diagnosa-x`, { norec: data.norecDiagnosa }).then((response: any) => {
    fetchDiagnosaX()
  }).catch((e: any) => {

  })
}

const deleteTemplate = (idTemplate) => {
  isLoading.value = true
  let json = {
    'id': idTemplate,
    'collection': 'CPPTDetail'
  }
  useApi().post(
    `/emr/hapus-template`, json).then((response: any) => {
      if (response.status !== 500) {
        isLoading.value = false
        isAlltemplate.value = false;
        H.alert('sucess', response.message);
        pilihTemplateFix();
      } else {
        H.alert('danger', response.message);
      }
    }).catch((e: any) => {
      isLoading.value = false
      H.alert('danger', e);
    })
}

const addDiagnosaDok9 = async (data: any) => {
  data.isLoadBtnDiagnosaDokter9 = true
  let objSave = {
    'diagnosapasien': {
      'norec': data.norecDiagnosa9 ? data.norecDiagnosa9 : '',
      'noregistrasifk': props.registrasi.norec_apd,
      'tglregistrasi': props.registrasi.tglregistrasi,
      'keterangantindakan': data.keterangan ? data.keterangan : null,

    },
    'detaildiagnosapasien': {
      'objectdiagnosatindakanfk': data.diagnosaa ? data.diagnosaa.value : null,
      'tglinputdiagnosa': H.formatDate(new Date(), 'YYYY-MM-DD HH:mm:ss'),
      'noregistrasi': props.registrasi.noregistrasi,
    }
  }
  await useApi().post(
    `/diagnosa/save-diagnosa-ix`, objSave).then((response: any) => {
      data.norecDiagnosa9 = response.norec
      fetchDiagnosaIX()
      data.isLoadBtnDiagnosaDokter9 = false
    }).catch((e: any) => {
      console.log(e)
    })

}
const removeDiagnosaDok9 = async (data: any) => {
  await useApi().post(`/diagnosa/delete-diagnosa-ix`, { norec: data.norecDiagnosa9 }).then((response: any) => {
    fetchDiagnosaIX()
  }).catch((e: any) => {

  })
}
const fetchDiagnosaIX = async () => {
  // dataSourceIX.value.loading = true
  await useApi().get(
    `/diagnosa/riwayat-diagnosa-ix?norec_pd=${props.registrasi.norec_pd}&nocmfk=${ID_PASIEN}`).then((response: any) => {

      for (let x = 0; x < input.value.details.length; x++) {
        const element = input.value.details[x];
        if (response.data.length) {
          element.diagnosaDokter9 = []
          for (let z = 0; z < response.data.length; z++) {
            const element2 = response.data[z];
            element.diagnosaDokter9.push({
              no: z + 1,
              keterangan: element2.keterangantindakan,
              norecDiagnosa9: element2.norec,
              diagnosaa: element2.namadiagnosatindakan && element2.kddiagnosatindakan ? { label: element2.namadiagnosatindakan, value: element2.kddiagnosatindakan } : '',
            })
          }
          element.diagnosaDokter9.push({ no: element.diagnosaDokter9.length + 1 })
        }
      }
    }).catch((e: any) => {
      // dataSourceIX.value.loading = false
    })
}

// =========== OLD FUNCTION ===========
const loadRiwayatOld = async () => {
  isloadingLAMPAU.value = true
  let paramsPD = ``
  let ruangan = ``
  let allPeriode = ``

  if (isRuangan.value) ruangan = `&ruangan=${props.registrasi.namaruangan}`
  if (isAllPeriode.value) allPeriode = `&allPeriode=true`

  useApi().get(`/emr/get-emr-cppt?nocmfk=${ID_PASIEN}&norec_apd=${item.NOREC_APD}&norec_pd=${item.NOREC_PD}&collection=${COLLECTION.value}&flag=${flagemr != null ? flagemr : kelompokUser}${ruangan}${allPeriode}`).then(async (response: any) => {
    isloadingLAMPAU.value = false
    if (response.length) {
      let dataOLD = []
      // for (let x = 0; x < response.length; x++) {
      //   const element = response[x];
      //   element.show = false
      //   for (let y = 0; y < element.detailsFull.length; y++) {
      //     const element2 = element.detailsFull[y];
      //     element2.tgl = H.formatDate(new Date(element2.tgl), 'YYYY-MM-DD HH:mm')
      //     element2.tglVerifikasi = H.formatDate(new Date(element2.tglVerifikasi), 'YYYY-MM-DD HH:mm')
      //   }
      //   element.riwayatResep = []
      //   dataOLD.push(element)
      // }

      const element = response[0];
      element.show = false
      for (let y = 0; y < element.detailsFull.length; y++) {
        const element2 = element.detailsFull[y];
        element2.tgl = H.formatDate(new Date(element2.tgl), 'YYYY-MM-DD HH:mm')
        element2.tglVerifikasi = H.formatDate(new Date(element2.tglVerifikasi), 'YYYY-MM-DD HH:mm')
      }
      element.riwayatResep = []
      dataOLD.push(element)
      input2.value = dataOLD
      input2.value.sort((a, b) => new Date(b.registrasi.tglregistrasi) - new Date(a.registrasi.tglregistrasi));
    } else {
      input2.value = []
    }
    if (props.pasien.enabledEMRSimrsLama == 'true') {
      let lokal = false;
      if (window.location.host.indexOf('192.168') > -1) {
        lokal = true;
      }

      isloadingLAMPAU.value = true
      let responseX = await useApi().get(`/emr/history-sim-lama?prefix=catatan_dokter&nocm=${props.pasien.nocm}&local=${lokal}`)
      isloadingLAMPAU.value = false
      let no = input2.value.length + 1
      for (let x = 0; x < responseX.length; x++) {
        const element = responseX[x];
        let dokterDPJP = null
        if (element.DokterPemeriksa && element.DokterPemeriksa != '' && element.DokterPemeriksa != null) {
          await fetchDokter({ query: element.DokterPemeriksa })
          if (d_Dokter.value.length) {
            for (let y = 0; y < d_Dokter.value.length; y++) {
              const element2 = d_Dokter.value[y];
              if (element2.label.toLowerCase().indexOf(element.DokterPemeriksa) > -1) {
                dokterDPJP = element2
                break
              }
            }
          }
        }
        if (dokterDPJP == null) {
          dokterDPJP = {
            label: element.DokterPemeriksa
          }
        }

        let pushOld = {
          'show': false,
          'registrasi': {
            'noregistrasi': element.Nopendaftaran,
            'tglregistrasi': element.TglMasuk,
            'namaruangan': '',
            'dokter': element.DokterPemeriksa,
          },
          'noemr': '',
          'details': [{
            'no': no++,
            'Nopendaftaran': element.Nopendaftaran,
            'tgl': element.TglMasuk,
            'S': element.CatatanS,
            'O': element.CatatanO,
            'flag': 'dokter',
            'diagnosaDokter': [{
              'no': 1
            }],
            'diagnosaDokter9': [{
              'no': 1
            }],
            'P': element.CatatanP,
            'dokterDPJP': dokterDPJP,
          }],
          'riwayatResep': []
        }
        input2.value.push(pushOld)
        input2.value.sort((a, b) => new Date(b.registrasi.tglregistrasi) - new Date(a.registrasi.tglregistrasi));
      }

      if (input2.value.length > 0) {
        let responseY = await useApi().get(`/emr/history-sim-lama?prefix=order_resep&nocm=${props.pasien.nocm}&local=${lokal}`)
        input2.value.forEach(element => {
          let data2 = []
          responseY.forEach(item => {
            if (item.NoPendaftaran == element.registrasi.noregistrasi) {

              let resep = {
                'noregistrasi': item.NoPendaftaran,
                'namaproduk': item.NamaBarang,
                'aturanpakai': item.AturanPakai,
                'jumlah': item.Jml,
                'tglorder': item.TglOrder
              }
              element.riwayatResep.push(resep)

            }
          });
        });
        let responseYX = await useApi().get(`/emr/history-sim-lama?prefix=diagnosa&nocm=${props.pasien.nocm}&local=${lokal}`)
        ////coding an mas egie

        for (let x = 0; x < input2.value.length; x++) {
          const element = input2.value[x];
          let data2 = []
          for (let y = 0; y < responseYX.length; y++) {
            const elementyyy = responseYX[y];
            if (elementyyy.NoPendaftaran == element.registrasi.noregistrasi) {
              let jenisDiagnosLocal = null
              let diagnosaLocal = null
              if (elementyyy.JenisDiagnosa == 'Diagnosa Utama') {
                jenisDiagnosLocal = {
                  "value": 1,
                  "label": "Primary / utama"
                }
              }
              if (elementyyy.JenisDiagnosa == 'Diagnosa Tambahan') {
                jenisDiagnosLocal = {
                  "value": 2,
                  "label": "Secondary"
                }
              }
              if (elementyyy.KdDiagnosaICD10 && elementyyy.KdDiagnosaICD10 !== '' && elementyyy.KdDiagnosaICD10 !== null) {
                await fetchDiagnosa({ query: elementyyy.KdDiagnosaICD10 });
                if (d_Diagnosa.value.length) {
                  for (let z = 0; z < d_Diagnosa.value.length; z++) {
                    const diagnosa10 = d_Diagnosa.value[z];
                    if (diagnosa10.label.split(' - ')[0] == elementyyy.KdDiagnosaICD10) {
                      diagnosaLocal = diagnosa10
                      break
                    }
                  }
                }
              }
              for (let x = 0; x < element.details.length; x++) {
                const elementxx = element.details[x];
                if (diagnosaLocal != null) {
                  let resep = {
                    'jenisDiagnosa': jenisDiagnosLocal,
                    'keterangan': elementyyy.NamaDiagnosaDokter,
                    'diagnosaa': diagnosaLocal
                  }
                  elementxx.diagnosaDokter.push(resep)
                }
              }
            }
          }
        }

      } else {
        H.alert('warning', 'Data Tidak Ada')
      }
    }
    // input2.value.sort((objA, objB) => {
    //   const dateA = new Date(objA.registrasi.tglregistrasi);
    //   const dateB = new Date(objB.registrasi.tglregistrasi);
    //   return dateB - dateA;
    // })

    // Sort by namaruangan
    const targetRoomName = props.registrasi.namaruangan;
    input2.value.sort((a, b) => {
      if (a.registrasi.namaruangan === targetRoomName && b.registrasi.namaruangan !== targetRoomName) {
        return -1;
      }
      if (b.registrasi.namaruangan === targetRoomName && a.registrasi.namaruangan !== targetRoomName) {
        return 1;
      }
      let dateA = new Date(a.registrasi.tglregistrasi);
      let dateB = new Date(b.registrasi.tglregistrasi);
      return dateB - dateA; // Sort in descending order by date
    });
  })
}
// loadRiwayatOld()
const simpan = (e: any) => {
  // console.log(input.value.details)
  previewCPPT.value = []
  input.value.details.forEach((cppt) => {
    if (cppt.flag == kelompokUser) {
      previewCPPT.value.push(cppt);
    }
  })
  isDialogSave.value = true
}

async function editItems(e: any) {
  clearInput()
  item.NOREC_DIAGNOSA10 = e.norec_diagnosapasien
  item.isKasusBaru = e.iskasusbaru == true ? 'baru' : (e.iskasuslama == true ? 'lama' : null)
  item.keterangan10 = e.keterangan
  // fetchDiagnosa10(e.kddiagnosa)
  // const response = await useApi().get(
  //     `/diagnosa/diagnosa-x-paging?name= ${e.kddiagnosa}&limit=1`)
  // d_Diagnosa.value = response.diagnosa.map((item: any) => {
  //     return { value: item.id, label: item.kddiagnosa + ' - ' + item.namadiagnosa, default: item }
  // })

  item.diagnosa10 = e.objectdiagnosafk
  item.jenisDiagnosis10 = e.objectjenisdiagnosafk
  item.tglpelayanan = new Date(e.tglinputdiagnosa)
  modalInput.value = true
}

async function simpanICD10() {
  if (!item.tglpelayanan) {
    useToaster().error('Tgl  harus di isi')
    return
  }
  if (!item.jenisDiagnosis10) {
    useToaster().error('Jenis Diagnosis harus di isi')
    return
  }
  if (!item.diagnosa10) {
    useToaster().error('Diagnosis harus di isi')
    return
  }

  let json = {
    'diagnosapasien': {
      'norec': item.NOREC_DIAGNOSA10 ? item.NOREC_DIAGNOSA10 : '',
      'noregistrasifk': item.NOREC_APD,
      'tglregistrasi': props.registrasi.tglregistrasi,
      'ketdiagnosis': item.keterangan10 ? item.keterangan10 : null,
      'iskasusbaru': item.isKasusBaru == 'baru' ? true : null,
      'iskasuslama': item.isKasusBaru == 'lama' ? true : null,
    },
    'detaildiagnosapasien': {
      'objectdiagnosafk': item.diagnosa10,
      'tglinputdiagnosa': H.formatDate(item.tglpelayanan, 'YYYY-MM-DD HH:mm:ss'),
      'objectjenisdiagnosafk': item.jenisDiagnosis10,
      'noregistrasi': props.registrasi.noregistrasi
    },
    'pasien': {
      'nocm': props.pasien.nocm,
      'namapasien': props.pasien.namapasien,
      'noregistrasi': props.registrasi.noregistrasi,
    }
  }

  isLoading.value = true
  await useApi().post(
    `/diagnosa/save-diagnosa`, json).then((response: any) => {
      useApi().get(`emr/get-diagnosa-pasien-icd10?nocmfk=${ID_PASIEN}`).then((response) => {
        response.forEach((element: any, i: any) => {
          element.no = i + 1
          element.tglInput = H.formatDate(element.tglinputdiagnosa, 'DD-MM-YYYY')
        });
        dataSourceICD10.value = response
        isLoading.value = false
        modalInput.value = false
        item.jenisDiagnosis10 = null
        item.diagnosa10 = null
      })
    }).catch((e: any) => {
      isLoading.value = false
    })
}

function copyTTV() {
  let data = input.value
  let ttv = ''
  if (data.keadaanumumobgyn != null) {
    if (data.keadaanumumobgyn == 1) {
      data.keadaanumumobgyn = 'Baik'
    } else if (data.keadaanumumobgyn == 2) {
      data.keadaanumumobgyn = 'Sedang'
    } else if (data.keadaanumumobgyn == 3) {
      data.keadaanumumobgyn = 'Buruk'
    }
    ttv += `Keadaan Umum : ${data.keadaanumumobgyn}\n`
  }
  if (data.gcse != null && data.gcsv != null && data.gcsm != null) {
    ttv += `GCS E: ${data.gcse} V: ${data.gcsv} M: ${data.gcsm}\n`
  }
  if (data.tekananDarah != null) {
    ttv += `Tekanan Darah : ${data.tekananDarah} mmHg\n`
  }
  if (data.nadi != null) {
    ttv += `PR : ${data.nadi} x/menit\n`
  }
  if (data.nafas != null) {
    ttv += `RR : ${data.nafas} x/menit\n`
  }
  if (data.celcius != null) {
    ttv += `Suhu : ${data.celcius} °C\n`
  }
  if (data.sao2 != null) {
    ttv += `SaO2 : ${data.sao2} %\n`
  }
  for (let x = 0; x < input.value.details.length; x++) {
    const element = input.value.details[x];
    input.value.details[x].O = ttv != '' ? `${ttv}` : ''
  }
}

const saveSignature = (item: any) => {
  const signature = H.tandaTangan().get(`TTDpetugasPemberi_${item.no}`);
  if (signature) {
    // Cek apakah `input.value.details` adalah array dan memiliki indeks `item.originalIndex`
    if (Array.isArray(input.value.details) && input.value.details[item.originalIndex]) {
      input.value.details[item.originalIndex].TTDpetugasPemberi = signature;
    } else {
      console.warn("input.value.details tidak tersedia atau index tidak ada:", item.originalIndex);
    }
  }
};


const saveSignature2 = (item: any) => {
  const signature2 = H.tandaTangan().get(`TTDpetugasPenerima_${item.no}`);
  if (signature2) {
    // Cek apakah `input.value.details` adalah array dan memiliki indeks `item.originalIndex`
    if (Array.isArray(input.value.details) && input.value.details[item.originalIndex]) {
      input.value.details[item.originalIndex].TTDpetugasPenerima = signature2;
    } else {
      console.warn("input.value.details tidak tersedia atau index tidak ada:", item.originalIndex);
    }
  }
};


const simpanReal = async () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}
  let getKelompok = kelompokUser;
  let real: any = [];
  let sendData: any = {};
  let inputVal = input.value;
  // if (kelompokUser == 'dokter') {
  //   if (!inputVal.riwayatkeluar && props.registrasi.namadepartemen.indexOf ('RAWAT INAP') > -1) {
  //     H.alert('error', `Silahkan Pilih Riwayat Keluar RS`)
  //     return
  //   }

  //   if (!inputVal.statuskeluar) {
  //     H.alert('error', `Silahkan Pilih Status Keluar RS`)
  //     return
  //   }

  //   if (!inputVal.perlukontrol) {
  //     H.alert('error', `Silahkan Pilih Status Kontrol`)
  //     return
  //   }

  //   // if(!inputVal.tekananDarah){
  //   //   H.alert('error', `Silahkan Isi Tekanan Darah`)
  //   //   return
  //   // }
  //   // if(!inputVal.nadi){
  //   //   H.alert('error', `Silahkan Isi Nadi`)
  //   //   return
  //   // }
  //   // if(!inputVal.nafas){
  //   //   H.alert('error', `Silahkan Isi Nafas`)
  //   //   return
  //   // }
  //   // if(!inputVal.celcius){
  //   //   H.alert('error', `Silahkan Isi Suhu`)
  //   //   return
  //   // }
  // }
  if (kelompokUser == 'dokter') {
    const isRawatInapCPPT = props.registrasi.namadepartemen.indexOf('RAWAT INAP') > -1;
    if (!isRawatInapCPPT) {
      if (!inputVal.riwayatkeluar) {
        H.alert('error', `Silahkan Pilih Riwayat Keluar RS`);
        return;
      }
      if (!inputVal.statuskeluar) {
        H.alert('error', `Silahkan Pilih Status Keluar RS`);
        return;
      }
      if (!inputVal.perlukontrol) {
        H.alert('error', `Silahkan Pilih Status Kontrol`);
        return;
      }
    }
  }
  if (input.value.details) {
    for (let index = 0; index < input.value.details.length; index++) {
      const element = input.value.details[index];

      if (element.flag == getKelompok && !element.isDeleted) {
        var dataidTambahan = [];
        if (element.flag !== 'gizi' && element.SOAP2 == undefined) {
          if (element.S == undefined || element.S == null || element.S == '') {
            H.alert('error', `Kolom Subjective pada section ke ${index + 1} belum terisi`)
            return
          }
          if (element.O == undefined || element.O == null || element.O == '') {
            H.alert('error', `Kolom Objective pada section ke ${index + 1} belum terisi`)
            return
          }
          if (element.A == undefined || element.A == null || element.A == '') {
            H.alert('error', `Kolom Assesments pada section ke ${index + 1} belum terisi`)
            return
          }
          if (kelompokUser == 'dokter') {
            if (element.P == undefined || element.P == null || element.P == '') {
              H.alert('error', `Kolom Planning pada section ke ${index + 1} belum terisi`)
              return
            } else {
              if (!element.P || element.P && element.P.replace(/\s/g, "").length < 4) {
                H.alert('error', 'Planning, ' + 'diisi minimal 4 karakter');
                return;
              }
            }
          } else {
            if (element.P == undefined || element.P == null || element.P == '') {
              H.alert('error', `Kolom Planning pada section ke ${index + 1} belum terisi`)
              return
            }
          }
        }

        if (element.tgl2 != undefined) delete element.tgl2;
        if (element.SOAP2 != undefined) delete element.SOAP2;
        if (element.intruksiPPA2 != undefined) delete element.intruksiPPA2;
        if (element.keteranganVerifikasiDPJP2 != undefined) delete element.keteranganVerifikasiDPJP2;
        if (element.tglVerifikasi2 != undefined) delete element.tglVerifikasi2;
        if (element.dokterDPJP2 != undefined) delete element.dokterDPJP2;
        if (element.tenagaMedis2 != undefined) delete element.tenagaMedis2;

        if (element.tenagaMedis == undefined || element.tenagaMedis == "") {
          H.alert('error', `Kolom Author / Tenaga Medis pada section ke ${index + 1} belum terisi`)
          return;
        }
        if (element.flag == 'dokter' || element.flag == 'dokter-igd') {
          if (element.diagnosaDokter.length) {
            for (let y = 0; y < element.diagnosaDokter.length; y++) {
              const element2 = element.diagnosaDokter[y];
              if (element2.diagnosaa && element2.norecDiagnosa == undefined) {
                await addDiagnosaDok(element2)
              }
            }
          }
          if (element.diagnosaDokter9.length) {
            for (let y = 0; y < element.diagnosaDokter9.length; y++) {
              const element3 = element.diagnosaDokter9[y];
              if (element3.diagnosaa && element3.norecDiagnosa9 == undefined) {
                await addDiagnosaDok9(element3)
              }
            }
          }
        }

        if (element.intruksi && element.intruksi == 'Intruksi DPJP') {
          if (!element.intruksiPPA || element.intruksiPPA == '') {
            H.alert('error', `Intruksi DPJP di section ke ${index + 1} belum di isi`)
            return
          }
          if (!element.dpjpUtama) {
            H.alert('error', `Dokter pada Intruksi DPJP di section ke ${index + 1} belum di isi`)
            return
          }
        }
        if (element.dokterraber) {
          for (let n = 0; n < element.dpjpRawatBersama.length; n++) {
            const dokterbersama = element.dpjpRawatBersama[n];
            if (!dokterbersama.id) {
              H.alert('error', `Dokter Rawat Bersama di section ke ${index + 1} belum di isi`)
              return
            }
            if (dokterbersama.isintruksi == "Intruksi DPJP" && dokterbersama.intruksi == '') {
              H.alert('error', `Intruksi Rawat Bersama di section ke ${index + 1} belum di isi`)
              return
            }
          }
        }
        if (element.handover) {
        }
        real.push(element);
      }
    }
  }
  console.log('input details sebelum', real)

  if (riwayatDataCPPT.value.details && riwayatDataCPPT.value.details.length > 0) {

    for (let indexR = 0; indexR < riwayatDataCPPT.length; indexR++) {
      let elR = riwayatDataCPPT[indexR];

      let inpD = real.filter((dt) => {
        if (elR.flag == dt.flag) {
          return;
        }
      })
      if (inpD.length > 0) {
        riwayatDataCPPT[indexR] = inpD[0];
      }
      // console.log("Loop 1ss", elR);
    }
  } else {
    riwayatDataCPPT.value.details = real;
    // console.log("DARI ELSE", real)
  }
  console.log('input details riwayat', riwayatDataCPPT)
  if (riwayatDataCPPT.value.details.length == 0) {
    riwayatDataCPPT.value.details = input.value.details;
  }

  isLoading.value = true
  sendData = input.value;
  sendData.details = riwayatDataCPPT.value.details
  sendData.registrasi = H.setObjectRegistrasi(props.registrasi)
  sendData.pasien = H.setObjectPasien(props.pasien)
  sendData.kelompokUser = getKelompok

  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': props.FORM_URL,
    'name_form': props.FORM_NAME,
    'jenis_emr': 'asesmen_medis',
    'data': sendData
  }

  // Ternyata ini penyebabnya
  // if (ID && NOREC_EMRPASIEN.value) isResumeMedis.value = false;


  useApi().post(`/emr/simpan-emr-cppt`, json).then(async (response: any) => {
    // makeRingkasanData(json);
    if (isResumeMedis.value) {
      // makeRingkasanData(json).then((res) => {
      //   console.log('Berhasi', res)
      //   saveKlaimSEP();
      // }).catch((err) => {
      //   console.log('ERR RINGKASAN', err)
      //   H.alert('warning', 'Gagal membuat ringkasan keluar');
      // });
      try {
        let res = await makeRingkasanData(json);
        console.log('Berhasil', res);
        await saveKlaimSEP();
      } catch (err) {
        console.log('ERR RINGKASAN', err);
        H.alert('warning', 'Gagal membuat ringkasan keluar');
      }
    }
    // try {
    //   let res = await makeRingkasanData(json);
    //   console.log('Berhasil', res);
    //   await saveKlaimSEP();
    // } catch (err) {
    //   console.log('ERR RINGKASAN', err);
    //   H.alert('warning', 'Gagal membuat ringkasan keluar');
    // }

    NOREC_EMRPASIEN.value = response.norec_emr
    console.log("EMR NOREC", NOREC_EMRPASIEN.value);
    input.value.id = response.id
    isLoading.value = false
    isDialogSave.value = false

    setValueDisabled()
    loadRiwayatOld()
  }).catch((e: any) => {
    isLoading.value = false
    H.alert('warning', `Gagal Simpan, ${e}`);
  })
}

const saveKlaimSEP = async () => {
  isLoading.value = true
  await useApi().post('/bridging/inacbgs/collect-dokumen', {
    'norec_pd': props.registrasi.norec_pd,
    'documentklaimfk': 207,
    'namafile': "resume_medis_rj",
    'tglregistrasi': props.registrasi.tglregistrasi,
    'api': "EMR-ReportEMRCtrl@cetakEMR-RingkasanKeluar"
  }).then((r) => {
    isLoading.value = false
  }).catch((er) => {
    isLoading.value = false
  })
}

function checkResume() {
  if (props.registrasi.namaruangan.toUpperCase().indexOf('IGD') > -1) {
    return;
  } else {
    if (kelompokUser && kelompokUser.toUpperCase() == 'DOKTER' || (props.registrasi.namaruangan.toUpperCase().indexOf('FISIO') > -1 && kelompokUser.toUpperCase() == 'PERAWAT')) {
      let params = `?norec_pd=${props.registrasi.norec_pd}&norec_apd=${props.registrasi.norec_apd}`
      let uri = `/emr/check-resume-medis${params}`;
      useApi().get(uri).then((res) => {
        console.log(`res Check Resume`, res);
        if (res) { 
          isResumeMedis.value = true
        }
      })
    } else {
      return;
    }
  }
}

async function makeRingkasanData(json) {
  return new Promise((resolve, reject) => {
    try {
      if (props.registrasi.namadepartemen.indexOf('RAWAT INAP') > -1) {
        return
      }
      isLoading.value = true;
      let dpjpUtamas = null;
      let detailSOAP = {
        S: "", A: "", O: "", P: "", tanggal: ""
      };

      // Cari data SOAP dokter
      for (let i = 0; i < json.data.details.length; i++) {
        const element = json.data.details[i];
        if (element.flag === 'dokter' || (props.registrasi.namaruangan.toUpperCase().indexOf('FISIO') > -1 && element.flag === 'perawat')) {
          dpjpUtamas = element.dokterDPJP || "Tidak Ada DPJP";
          detailSOAP = {
            S: element.S || "",
            A: element.A || "",
            O: element.O || "",
            P: element.P || "",
            tanggal: element.tgl || new Date().toISOString()
          };
          break;
        }
      }

      // Validasi jika ada data yang kosong
      if (!detailSOAP.S) {
        H.alert('warning', 'Keluhan pasien wajib diisi.');
        isLoading.value = false;
        return resolve(false);
      }
      // if (!json.data.tekananDarah || !json.data.nadi || !json.data.nafas || !json.data.celcius) {
      //   H.alert('warning', 'Tanda-tanda vital (TTV) wajib diisi.');
      //   isLoading.value = false;
      //   return resolve(false);
      // }
      if (!detailSOAP.O) {
        H.alert('warning', 'Pemeriksaan fisik wajib diisi.');
        isLoading.value = false;
        return resolve(false);
      }
      if (!detailSOAP.A) {
        H.alert('warning', 'Diagnosis primer wajib diisi.');
        isLoading.value = false;
        return resolve(false);
      }
      if (!json.data.riwayatkeluar) {
        H.alert('warning', 'Riwayat keluar wajib diisi.');
        isLoading.value = false;
        return resolve(false);
      }

      let object = {
        "waktuTataLaksana": detailSOAP.tanggal,
        "waktuKontrol": detailSOAP.tanggal,
        "jamKedatangan": detailSOAP.tanggal,
        "jamAsesmenAwal": detailSOAP.tanggal,
        "riwayatkeluar": json.data.riwayatkeluar || "",
        "statuskeluar": json.data.statuskeluar || "",
        "perlukontrol": json.data.perlukontrol || "",
        "tanggalKedatangan": detailSOAP.tanggal,
        "dpjpUtama": dpjpUtamas,
        "TAKondisiSaatMasuk": json.data.TAKondisiSaatMasuk || "",
        "TADiagnosisPrimer": detailSOAP.A,
        "gcse": json.data.gcse || 0,
        "gcsv": json.data.gcsv || 0,
        "gcsm": json.data.gcsm || 0,
        "kesanUmum": json.data.keadaanumumobgyn || "",
        "nadi": json.data.nadi || 0,
        "nafas": json.data.nafas || 0,
        "celcius": json.data.celcius || 0,
        "tekananDarah": json.data.tekananDarah || "",
        "anamnesis": detailSOAP.S,
        "pemeriksaanfisik": detailSOAP.O,
        "intruksi": detailSOAP.P,
        "hasilpemeriksaanpenunjang": passCPPT.value?.result?.hasilpemeriksaanpenunjang || "",
        "sumber": "CPPT"
      }
      object.nocm = json.data.pasien.nocm
      object.pasien = json.data.pasien
      object.registrasi = json.data.registrasi

      let sendData = {
        'id': '',
        'norec_emr': '',
        'collection': 'RingkasanKeluar',
        'url_form': 'module-emr-profile-pasien-page-emr-ringkasan-keluar',
        'name_form': 'Ringkasan Keluar',
        'jenis_emr': 'asesmen_medis',
        'data': object
      };

      useApi().postNoMessage('/emr/simpan-emr', sendData).then(async (response) => {
        isLoading.value = false;
        H.alert('success', 'Ringkasan keluar berhasil dibuat');
        resolve(true);
      }).catch(() => {
        isLoading.value = false;
        H.alert('error', 'Ringkasan keluar gagal dibuat');
        resolve(false);
      });
    } catch (error) {
      reject(error);
    }
  });
}


const kembaliKeun = () => {
  window.history.back()
}
const fetchDiagnosaKeperawatan = async (filter: any) => {
  let q = ''
  if (filter) {
    q = filter.query
  }
  await useApi().get(`/emr/list-diagnosa-keperawatan?query=${q}`).then((response) => {
    d_DiagnosaKeperawatan.value = response.diagnosaKeperawatan.map((e: any) => {
      return { value: e.diagnosakep, label: e.diagnosakep, default: e }
    })
  })
}
const fetchDiagnosaSdki = async (filter: any) => {
  let q = ''
  if (filter) {
    q = filter.query
  }
  await useApi().get(`/emr/list-diagnosa-sdki?query=${q}`).then((response) => {
    d_DiagnosaSdki.value = response.diagnosasdki.map((e: any) => {
      return { value: e.deskripsidiagnosakep, label: e.deskripsidiagnosakep, id: e.id, default: e }
    })
  })
}

const fetchSiki = async (diagnosaId) => {
  await useApi()
    .get(`/emr/list-siki?query=${diagnosaId}`)
    .then(async (response) => {
      console.log("Response SIKI:", response.siki);

      if (response.siki.length > 0) {
        d_Siki.value = response.siki.map((e) => ({
          value: e.id,
          label: e.name,
          type: e.type,
          default: e,
        }));
      } else {
        d_Siki.value = [];
      }

      await nextTick(); // Paksa Vue untuk merender ulang
      console.log("Updated d_Siki:", d_Siki.value);
    })
    .catch((error) => {
      console.error("Error fetching SIKI:", error);
    });
};

const fetchTujuan = async (filter: any) => {
  await useApi().get(`/emr/list-tujuan-keperawatan?query=${filter.query}`).then((response: any) => {
    d_TujuanKeperawatan.value = response.tujuanPerawat.map((e: any) => {
      return { value: e.id, label: e.tujuankep, default: e }
    })
  })
}

const fetchIntervensi = async (keperawatanfk: any) => {
  let keperawatan = keperawatanfk ? keperawatanfk.value : ''
  await useApi().get(`/emr/list-intervensi?keperawatanfk=${tujuanKeper.value}`).then((response: any) => {
    d_IntervensiKeperawatan.value = response.intervensi.map((e: any) => {
      return { value: e.id, label: e.name, }
    })
  })
}

const d_Petugas = ref([]);

const fetchPetugas = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Petugas.value = response
  })
}

const fetchDokter = async (filter: any) => {
  // const response = await useApi().get(
  //   `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`)
  const response = await useApi().get(
    `/emr/dropdown/pegawai_m?select=id,namalengkap&query=${filter.query}&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`)
  d_Dokter.value = response
}

const fetchPegawai = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
  d_Pegawai.value = response
}

const addNewItem = (e: any) => {
  let push: any = {}

  console.log('Sebelm di sort', input.value.details);
  if (e.flag == 'dokter') {
    let diagnosaDokter = []
    let diagnosaDokter9 = []
    input.value.details.forEach((data, index) => {
      if (!data.norecDiagnosa && data.flag == 'dokter') diagnosaDokter = data.diagnosaDokter
      if (!data.norecDiagnosa9 && data.flag == 'dokter') diagnosaDokter9 = data.diagnosaDokter9
    });
    push = {
      uuid: uuidv4(),
      no: input.value.details[0].no + 1,
      tgl: new Date(),
      P: strforP.value,
      O: strforO.value,
      S: strforS.value,
      A: strforA.value,
      tglVerifikasi: new Date(),
      flag: 'dokter',
      diagnosaDokter: diagnosaDokter,
      diagnosaDokter9: diagnosaDokter9,
      ruangan: props.registrasi.namaruangan,
      apd: props.registrasi.apd,
      dpjpRawatBersama: [{ id: null, isintruksi: false, intruksi: null }],
      dokterDPJP: { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk },
      tenagaMedis: { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id },
      author: userLogin.pegawai
    }

  }
  if (e.flag == 'perawat') {
    push = {
      uuid: uuidv4(),
      no: input.value.details[0].no + 1,
      tgl: new Date(),
      tglVerifikasi: new Date(),
      P: strforP.value,
      O: strforO.value,
      S: strforS.value,
      A: strforA.value,
      flag: 'perawat',
      diagnosaKep: [{
        no: 1
      }],
      tujuanKep: [{
        no: 1
      }],
      apd: props.registrasi.apd,
      ruangan: props.registrasi.namaruangan,
      dpjpRawatBersama: [{ id: null, isintruksi: false, intruksi: null }],
      dokterDPJP: { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk },
      tenagaMedis: { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id },
      author: userLogin.pegawai
    }

  }
  if (e.flag == 'profesi lain') {
    push = {
      uuid: uuidv4(),
      no: input.value.details[0].no + 1,
      tgl: new Date(),
      tglVerifikasi: new Date(),
      flag: 'profesi lain',
      O: strforO.value,
      S: strforS.value,
      A: strforA.value,
      apd: props.registrasi.apd,
      ruangan: props.registrasi.namaruangan,
      dpjpRawatBersama: [{ id: null, isintruksi: false, intruksi: null }],
      dokterDPJP: { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk },
      tenagaMedis: { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id },
      author: userLogin.pegawai
    }
  }
  if (e.flag == 'gizi') {
    push = {
      uuid: uuidv4(),
      no: input.value.details[0].no + 1,
      tgl: new Date(),
      tglVerifikasi: new Date(),
      flag: 'gizi',
      O: strforO.value,
      S: strforS.value,
      A: strforA.value,
      apd: props.registrasi.apd,
      ruangan: props.registrasi.namaruangan,
      dpjpRawatBersama: [{ id: null, isintruksi: false, intruksi: null }],
      dokterDPJP: { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk },
      tenagaMedis: { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id },
      author: userLogin.pegawai
    }
  }

  // Get last number for flag
  let lastId = 0;
  let lastIndex = 0;
  input.value.details.forEach((dt, index) => {
    if (dt.flag == kelompokUser) {
      lastIndex = index;
      lastId = dt.no
    }
  });
  indexcpptcopy = input.value.details[lastIndex].no + (lastId + 1)

  // baru keatas :
  input.value.details.unshift(push);
  // input.value.details.sort((a, b) => (a.no < b.no) ? 1 : ((b.no < a.no) ? -1 : 0))
  // console.log('sesudah di sort', input.value.details);
}

const removeItem = (index: any, flag: string = 'dokter') => {
  // let filterByFlag = input.value.details.filter((dt) => {
  //   return dt.flag == flag;
  // });

  // input.value.details.forEach((val, ix) => {
  //   let detailData = filterByFlag[index];
  //   if (detailData.no == val.no) {
  //     input.value.details.splice(ix, 1)
  //   }
  // });
  // input.value.details.push(filterByFlag);
  // input.value.details.splice(index, 1)
  input.value.details[index].isDeleted = true;
}

const validateAssesment = async () => {
  // Make views rendered first;
  input.value.details = [
    array_dokter.value, array_perawat.value, array_profesi.value, array_gizi.value
  ]


}

const setAutoFill = async () => {
  input.value.dpjpUtama = props.registrasi.dokter
  // await fetchDokter({ query: props.registrasi.dokter })

  fetchDiagnosaX()
  fetchDiagnosaIX()

  let idDiagKep = 0
  let idTujuanKep = 0
  let idIntervensiKep = 0
  let idPlanKep = 0
  let forranap = '';
  if (isRanap.value) {
    await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=RencanaKeperawatanRawatInap" + `&field=details&ruangan=${props.registrasi.namaruangan}`).then((dt) => {
      if (dt && dt.details.length > 0) {
        for (let inkep = 0; inkep < dt.details.length; inkep++) {
          const renkep = dt.details[inkep];
          forranap += renkep.rencanaKeperawatanRawatInap + '\n';
        }
      }
      if (strforA.value) {
        strforA.value += `\n` + forranap;
      } else {
        strforA.value += forranap;
      }
    })
  }

  input.value.details.forEach((element: any) => {
    // if (props.registrasi.objectpegawaifk) {
    //   element.dokterDPJP = { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk }
    //   element.tenagaMedis = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
    // }
    if (element.A) {
      element.A = forranap;
    } else {
      element.A = forranap
    }
  });

  if (isRanap.value) {
    const renpra = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=RencanaKeperawatanRawatInap" + `&field=details&ruangan=${props.registrasi.namaruangan}`)

    if (renpra && renpra.details && renpra.details.length > 0) {

      let data = renpra.details.map((item, index) => {
        let diagnosis = `${item.rencanaKeperawatanRawatInap} \n`;

        let getDiagnosaFunction = EMR2[item.rencanaKeperawatanRawatInap.replace(/\s+/g, "")];
        let diagnosaKeperawatan = getDiagnosaFunction ? getDiagnosaFunction() : [];

        // Normalisasi teks untuk pencocokan fuzzy
        let inputDiagnosis = item.rencanaKeperawatanRawatInap.toLowerCase().trim();

        const rencanaTindakan = diagnosaKeperawatan.find(d => d.labelDiagnosaKeperawatan === 'RENCANA TINDAKAN');

        let bdModels = []; // RESET setiap loop

        if (rencanaTindakan) {
          bdModels = rencanaTindakan.detailDiagnosaKeperawatan
            .filter(t => t.model !== undefined)
            .map(t => t.model);

        } else {
        }

        let matchingDiagnosa = diagnosaKeperawatan.filter(d =>
          d.keteranganTambahan.toLowerCase().trim().includes(inputDiagnosis) ||
          inputDiagnosis.includes(d.keteranganTambahan.toLowerCase().trim())
        );

        if (!matchingDiagnosa || matchingDiagnosa.length === 0) {
          return diagnosis;
        }

        // Collect all detailDiagnosaKeperawatan from matchingDiagnosa
        let detailList = matchingDiagnosa
          .filter(d => d.labelDiagnosaKeperawatan === "RENCANA TINDAKAN")
          .flatMap(d => d.detailDiagnosaKeperawatan || []);

        // Collect keteranganTambahan as separate objects
        let keteranganTambahanList = matchingDiagnosa
          .filter(d => d.keteranganTambahan)
          .map(d => ({ labelDetail: d.keteranganTambahan, model: null })); // Structure to match detailList

        // Merge both lists
        detailList = [...detailList, ...keteranganTambahanList];

        let kondisiKeys = Object.keys(item).filter(key => key !== "rencanaKeperawatanRawatInap" && key !== "no" && item[key]);

        let bdKondisiList = [];
        let ddKondisiList = [];

        kondisiKeys.forEach((key) => {
          let matchingDetail = detailList.find(detail => detail.model === key);


          if (matchingDetail) {
            let labelDetail = matchingDetail.labelDetail.replace(/^\d+\.\s*/, "");

            if (bdModels.includes(key)) {
              bdKondisiList.push(labelDetail);
            } else {
              ddKondisiList.push(labelDetail);
            }
          }
        });

        // Format `bdKondisi` using ", \n" as separator
        let bdKondisi = bdKondisiList.length > 0 ? bdKondisiList.join(", \n") : "";

        // Format `ddKondisi` using ", \n" as separator
        let ddKondisi = ddKondisiList.length > 0 ? ddKondisiList.join(", \n") : "";

        // **Final diagnosis string**
        if (bdKondisi || ddKondisi) {
          return (bdKondisi ? bdKondisi + "\n" : "") + (ddKondisi ? ddKondisi + "\n" : "");
        } else {
          return ""; // Return an empty string if both are empty
        }


      });

      // Menyimpan ke input berdasarkan index
      // Konversi ke format array objek agar cocok dengan v-model di v-for
      input.value.details.forEach((element: any) => {
        const mappedData = data.filter(text => text).join("\n"); // Join all valid data entries with a newline

        if (element.P) {
          element.P = `${mappedData}`; // Append all mapped data
        } else {
          element.P = mappedData;
        }

      });
    }
  }

  if (idDiagKep != 0) {
    await useApi().get(
      "/emr/list-diagnosa-keperawatan"
    ).then((response) => {
      let index = 0
      input.value.details[1].diagnosaKep = []
      response.diagnosaKeperawatan.forEach((element) => {
        if (element.id == idDiagKep) {
          input.value.details[1].diagnosaKep.push({
            no: index + 1,
            diagnosaKeperawatan: { value: element.id, label: element.diagnosakep }
          })
        }
      })
    })
  }

  if (idTujuanKep != 0) {
    await useApi().get(
      "/emr/list-tujuan-keperawatan"
    ).then((response) => {
      input.value.details[1].tujuanKep = []
      let index = 0
      response.tujuanPerawat.forEach((element) => {
        if (element.id == idTujuanKep) {
          let intervensiKep
          useApi().get(
            `/emr/list-intervensi`
          ).then((response) => {
            response.intervensi.forEach((element2) => {
              if (element2.id == idIntervensiKep) {
                input.value.details[1].tujuanKep.push({
                  no: index + 1,
                  tujuanKeperawatan: { value: element.id, label: element.tujuankep },
                  intervensiKeperawatan: { value: element.id, label: element2.name }
                })
              }
            })
          })
        }
      })
    })
  }
  if (idPlanKep != 0) {
    await useApi().get(`/emr/list-implementasi`).then((response: any) => {
      response.implementasi.forEach(element => {
        if (element.id == idPlanKep) {
          input.value.details[1].P = element.name
        }
      })
    })
  }
}
const fetchDiagnosaX = async () => {
  await useApi().get(
    "diagnosa/riwayat-diagnosa-x-cppt?noregistrasi=" + props.registrasi.noregistrasi
  ).then((response) => {

    for (let x = 0; x < input.value.details.length; x++) {
      const element = input.value.details[x];
      if (response.data.length) {
        element.diagnosaDokter = []
        for (let z = 0; z < response.data.length; z++) {
          const element2 = response.data[z];
          element.diagnosaDokter.push({
            no: z + 1,
            keterangan: element2.keterangan,
            norecDiagnosa: element2.norec,
            jenisDiagnosa: element2.jenisdiagnosa && element2.objectjenisdiagnosafk ? { label: element2.jenisdiagnosa, value: element2.objectjenisdiagnosafk } : '',
            diagnosaa: element2.namadiagnosa && element2.id ? { label: element2.kddiagnosa + "-" + element2.namadiagnosa, value: element2.id } : '',
          })
        }
        element.diagnosaDokter.push({ no: element.diagnosaDokter.length + 1 })
      }
      // console.log(element.diagnosaDokter)
      // debugger
    }
  })
}
const setValueDisabled = () => {
  if (input.value.details) {
    for (let x = 0; x < input.value.details.length; x++) {
      const element = input.value.details[x];
      if (element) {
        if (element.tenagaMedis?.value != pegawaiId) {
          element.tgl2 = true
          element.SOAP2 = true
          element.intruksiPPA2 = true
          element.tenagaMedis2 = true
          element.S2 = true
          element.O2 = true
          element.A2 = true
          element.P2 = true
          item.intruksi2 = true
          element.dokterDPJP2 = true
          element.dpjpUtamaDisabled = true
          element.button = true;
          element.dokterraber2 = true;
          element.handover2 = true;
          element.transferPasien2 = true;
          if (element.flag == 'dokter') {
            element.keteranganVerifikasiDPJP2 = true
            element.dokterDPJP2 = element.dokterDPJP ? true : false
            element.tglVerifikasi2 = element.tglVerifikasi ? true : false
          }
        }
        // if (element.flag == 'perawat' && element.tenagaMedis?.value != pegawaiId) {
        //   element.tgl2 = true
        //   element.SOAP2 = true
        //   element.intruksiPPA2 = true
        //   element.tenagaMedis2 = true
        //   element.S2 = true
        //   element.O2 = true
        //   element.A2 = true
        //   element.P2 = true
        //   item.intruksi2 = true
        //   element.dokterDPJP2 = true
        //   element.dpjpUtamaDisabled = true
        //   element.button = true;
        //   element.dokterraber2 = true;
        //   element.transferPasien2 = true;
        //   if (props.registrasi.namaruangan.toUpperCase().indexOf('IGD') > -1) element.dokterjaga2 = true;
        // } else if (element.flag == 'dokter') {
        //   if (element.dokterDPJP && element.dokterDPJP.value != pegawaiId) {
        //     element.keteranganVerifikasiDPJP2 = true
        //     element.dokterDPJP2 = element.dokterDPJP ? true : false
        //     element.tglVerifikasi2 = element.tglVerifikasi ? true : false
        //   }
        // }
      }
    }
  }
}

const pasteFromClipboard = (e: any) => {
  input.value.details.forEach(element => {
    if (element.flag == e.flag) {
      navigator.clipboard.readText().then(function (clipboardText) {
        e.S = clipboardText
      }).catch(function (err) {
        H.alert('warning', 'Klik Allow untuk izin paste')
      });
    }
  });

}
const copyToClipboard = (e: any) => {
  const textArea = document.createElement('textarea');
  textArea.value = e;

  // Make sure the text area is not visible
  textArea.style.position = 'fixed';
  textArea.style.top = '0';
  textArea.style.left = '0';
  textArea.style.opacity = 0;

  document.body.appendChild(textArea);
  textArea.select();

  try {
    const successful = document.execCommand('copy');
    if (successful) {
      H.alert('info', 'Text copied to clipboard')
    } else {
      H.alert('error', 'Failed to copy text')

    }
  } catch (err) {
    console.error('Unable to copy text: ', err);
  }

  document.body.removeChild(textArea);
}

const scrollToBottom = () => {
  if (scrollContainer.value) {
    scrollContainer.value.scrollTop = scrollContainer.value.scrollHeight;
  }
}
const filterList = (e: any) => {

}

const dataSourceFiltered = computed(() => {
  if (!item.filter) {
    return input.value.details.map((item: any, index: number) => ({
      ...item,
      originalIndex: index,
    }));
  }

  return input.value.details
    .map((item: any, index: number) => ({
      ...item,
      originalIndex: index,
    }))
    .filter((items: any) => {
      return items.flag.match(new RegExp(item.filter, "i"));
    });
});

function parseLokalisMata(elementO) {
  const lines = elementO.split('\n'); // Pisahkan berdasarkan baris baru
  const result = {};

  lines.forEach(line => {
    if (line.trim() === '') return; // Abaikan baris kosong

    // Pisahkan label dan nilai
    const [label, value] = line.split(':').map(part => part.trim());

    // Mapping label ke v-model yang sesuai
    switch (label) {
      case 'Visus Awal OD UVCA':
        result.visusawalodu = value;
        break;
      case 'Visus Awal OD BCVA':
        result.visusawalodb = value;
        break;
      case 'Visus Awal OS UVCA':
        result.visusawalosu = value;
        break;
      case 'Visus Awal OS BCVA':
        result.visusawalosb = value;
        break;
      case 'Kacamata OD UVCA':
        result.kacamataodu = value;
        break;
      case 'Kacamata OD BCVA':
        result.kacamataodb = value;
        break;
      case 'Kacamata OS UVCA':
        result.kacamataosu = value;
        break;
      case 'Kacamata OS BCVA':
        result.kacamataosb = value;
        break;
      case 'Posisi/Hirschberg OD':
        result.od = value;
        break;
      case 'Posisi/Hirschberg OS':
        result.os = value;
        break;
      case 'Palpebra Mata Kiri':
        result.palpebrai = value;
        break;
      case 'Konjungtiva Mata Kiri':
        result.konjungtivai = value;
        break;
      case 'Kornea Mata Kiri':
        result.korneai = value;
        break;
      case 'Bilik Mata Mata Kiri':
        result.bilikmatai = value;
        break;
      case 'Iris Mata Kiri':
        result.irisi = value;
        break;
      case 'Pupil Mata Kiri':
        result.pupili = value;
        break;
      case 'Lensa Mata Kiri':
        result.lensai = value;
        break;
      case 'Vitreus Mata Kiri':
        result.vitreusi = value;
        break;
      case 'Funduskopi Mata Kiri':
        result.funduskopii = value;
        break;
      case 'Konjungtiva Mata Kiri':
        result.konjungtivai = value;
        break;
      case 'Schiotz Mata Kiri':
        result.schiotzi = value;
        break;
      case 'Aplanasi Mata Kiri':
        result.aplanasii = value;
        break;
      case 'NCT Mata Kiri':
        result.ncti = value;
        break;
      case 'Palpebra Mata Kanan':
        result.palpebran = value;
        break;
      case 'Konjungtiva Mata Kanan':
        result.konjungtivan = value;
        break;
      case 'Kornea Mata Kanan':
        result.kornean = value;
        break;
      case 'Bilik Mata Mata Kanan':
        result.bilikmatan = value;
        break;
      case 'Iris Mata Kanan':
        result.irisn = value;
        break;
      case 'Pupil Mata Kanan':
        result.pupiln = value;
        break;
      case 'Lensa Mata Kanan':
        result.lensan = value;
        break;
      case 'Vitreus Mata Kanan':
        result.vitreusn = value;
        break;
      case 'Funduskopi Mata Kanan':
        result.funduskopin = value;
        break;
      case 'Schiotz Mata Kanan':
        result.schiotzn = value;
        break;
      case 'Aplanasi Mata Kanan':
        result.aplanasin = value;
        break;
      case 'NCT Mata Kanan':
        result.nctn = value;
        break;
      case 'Test Anel':
        result.testanel = value;
        break;
      case 'Test Buta Warna':
        result.testbutawarna = value;
        break;
      case 'Test Fluoresin':
        result.testfluoresin = value;
        break;
      case 'Resep Kacamata':
        result.optionsmata = value;
        break;
      case '- Spheris OD':
        result.spherisd = value;
        break;
      case '- Spheris OS':
        result.spheriss = value;
        break;
      case '- Cylinder OD':
        result.cylinderd = value;
        break;
      case '- Cylinder OS':
        result.cylinders = value;
        break;
      case '- Prisma OD':
        result.prismad = value;
        break;
      case '- Prisma OS':
        result.prismas = value;
        break;
      case '- Axis OD':
        result.axisd = value;
        break;
      case '- Axis OS':
        result.axiss = value;
        break;
      case '- Addition OS':
        result.addition = value;
        break;
      case '- Pupil Distance':
        result.pupil = value;
        break;
      // Tambahkan case untuk semua field lainnya
      default:
        break;
    }
  });

  return result;
}

const isCopyClicked = ref(false);
const copiedData = ref();

function handleCopyAndShowLokalis(index, item) {
  console.log("handleCopyAndShowLokalis dipanggil", { index, item, isCopyClicked: isCopyClicked.value });

  if (isCopyClicked.value) {
    console.log("Tombol copy diklik, memanggil fungsi copy()");
    showLokalis(index, copiedData.value);
  } else {
    console.log("Tombol biasa diklik, hanya menjalankan showLokalis()");
    showLokalis(index);
  }
}

const handlecopy = async () => {
  isCopyClicked.value = true;
  console.log("handlecopy() dipanggil, isCopyClicked:", isCopyClicked.value);
};

const copy = (e: any) => {
  console.log('COPY CPPT', e);
  handlecopy();
  let lastId = 0;
  let lastIndex = 0;

  for (let kDet = 0; kDet < input.value.details.length; kDet++) {
    const dt = input.value.details[kDet];
    if (dt.flag == kelompokUser) {
      lastIndex = kDet;
      lastId = dt.no;
      break;
    }
  }

  let element = input.value.details[lastIndex];
  console.log('element :', element);
  console.log('lastIndex :', lastIndex);

  // Jika element.O sudah ada, parse data lokalis mata
  if (element.O) {
    const parsedData = parseLokalisMata(element.O);
    Object.assign(inputLokalis.value, parsedData);
    copiedData.value = parsedData;// Masukkan data ke inputLokalis.value
  }

  // Update data dari e ke element
  if (element.S == undefined) element.S = '';
  element.S = e.S;

  if (element.O == undefined) element.O = '';
  element.O = e.O;

  if (element.A == undefined) element.A = '';
  element.A = e.A;

  if (element.P == undefined) element.P = '';
  element.P = e.P;

  if (element.AGizi == undefined) element.AGizi = '';
  element.AGizi = e.AGizi;

  if (element.DGizi == undefined) element.DGizi = '';
  element.DGizi = e.DGizi;

  if (element.IGizi == undefined) element.IGizi = '';
  element.IGizi = e.IGizi;

  if (element.MGizi == undefined) element.MGizi = '';
  element.MGizi = e.MGizi;

  if (element.EGizi == undefined) element.EGizi = '';
  element.EGizi = e.EGizi;

  if (e.intruksi) element.intruksi = e.intruksi;
  if (e.intruksiPPA) element.intruksiPPA = e.intruksiPPA;
  if (e.dpjpUtama) element.dpjpUtama = e.dpjpUtama;

  // Kembalikan data yang telah dicopy
  return element;
};

// const copy = (e: any) => {
//   console.log('COPY CPPT', e);
//   // let element = input.value.details[0]
//   // console.log("from details", element);

//   let lastId = 0;
//   let lastIndex = 0;
//   // input.value.details.forEach((dt, index) => {
//   //   if (dt.flag == kelompokUser) {
//   //     lastIndex = index;
//   //     lastId = dt.no
//   //     console.log('Last Index Lopp :', index);
//   //   }
//   // });
//   for (let kDet = 0; kDet < input.value.details.length; kDet++) {
//     const dt = input.value.details[kDet];
//     if (dt.flag == kelompokUser) {
//       lastIndex = kDet;
//       lastId = dt.no
//       break;
//     }
//   }
//   let element = input.value.details[lastIndex];
//   console.log('element :', element);
//   console.log('lastIndex :', lastIndex);


//   if (element.S == undefined) {
//     element.S = ''
//   }
//   element.S = e.S

//   if (element.O == undefined) {
//     element.O = ''
//   }
//   element.O = e.O

//   if (element.A == undefined) {
//     element.A = ''
//   }
//   element.A = e.A

//   if (element.P == undefined) {
//     element.P = ''
//   }
//   element.P = e.P

//   if (element.AGizi == undefined) {
//     element.AGizi = ''
//   }
//   element.AGizi = e.AGizi

//   if (element.DGizi == undefined) {
//     element.DGizi = ''
//   }
//   element.DGizi = e.DGizi

//   if (element.IGizi == undefined) {
//     element.IGizi = ''
//   }
//   element.IGizi = e.IGizi

//   if (element.MGizi == undefined) {
//     element.MGizi = ''
//   }
//   element.MGizi = e.MGizi

//   if (element.EGizi == undefined) {
//     element.EGizi = ''
//   }
//   element.EGizi = e.EGizi
//   if (e.intruksi) {
//     element.intruksi = e.intruksi
//   }
//   if (e.intruksiPPA) {
//     element.intruksiPPA = e.intruksiPPA
//   }
//   if (e.dpjpUtama) {
//     element.dpjpUtama = e.dpjpUtama;
//   }
// }

const paste = (e: any, index: any) => {

  if (localStorage.getItem('cppt_copy') != null) {
    console.log(localStorage.getItem('cppt_copy'))
    let data = JSON.parse(localStorage.getItem('cppt_copy'))
    data.tgl = new Date()
    input.value.details[index] = data
    H.alert('info', 'Berhasil diterapkan')
  } else {
    H.alert('error', 'Belum ada data yang dicopy')
  }
}
const hasilLab = async (norec_pd: any, e: any) => {
  e.isLoading = true
  useApi().get(
    `/emr/hasil-lab?norec_pd=${norec_pd}&islab=true`).then((response: any) => {
      e.isLoading = false
      item.lab = setHasilLab(response.laboratorium)
      item.lab_GROUP = updateGroupLAB()

      isHasilLab.value = true
    })

}
const hasilRad = async (norec_pd: any, e: any) => {
  e.isLoadingRad = true
  useApi().get(
    `/emr/hasil-lab?norec_pd=${norec_pd}&israd=true`).then((response: any) => {
      e.isLoadingRad = false
      item.radiologi = setHasilRad(response.radiologi)
      isHasilRad.value = true
    })

}
const berkasPasien = async (norec_pd: any, e: any) => {
  e.isLoadingBerkas = true
  useApi().get(
    `/emr/berkas-pasien?nocm=${e.pasien.nocm}&noregistrasi=${e.registrasi.noregistrasi}`).then((response: any) => {
      e.isLoadingBerkas = false
      item.berkas = response.data
      isBerkas.value = true
    })

}
const hasilLabPA = async (norec_pd: any, e: any) => {
  e.isLoadingPA = true
  useApi().get(
    `/emr/hasil-lab-pa?norec_pd=${norec_pd}`).then((response: any) => {
      e.isLoadingPA = false
      item.patologi = response
      isPA.value = true
    })

}
const setHasilRad = (e: any) => {
  for (let x = 0; x < e.length; x++) {
    const element = e[x];
    element.isdetail = false
    if (element.expertise != null) {
      element.isdetail = true
    }
  }
  return e
}
const setHasilLab = (e: any) => {
  for (let x = 0; x < e.length; x++) {
    const element = e[x];
    element.isdetail = false
    if (element.hasil_lab.length > 0) {
      element.isdetail = true
    }
  }
  return e
}
const updateGroupLAB = () => {
  rowGroupLAB.value = {};

  if (item.lab.length) {
    for (let x = 0; x < item.lab.length; x++) {
      const element = item.lab[x];
      for (let i = 0; i < element.hasil_lab.length; i++) {
        let rowData = element.hasil_lab[i];
        let treatment_name = rowData.treatment_name;

        if (i == 0) {
          rowGroupLAB.value[treatment_name] = { index: 0, size: 1 };
        } else {
          let previousRowData = element.hasil_lab[i - 1];
          let previousRowGroup = previousRowData.treatment_name;
          if (treatment_name === previousRowGroup)
            rowGroupLAB.value[treatment_name].size++;
          else
            rowGroupLAB.value[treatment_name] = { index: i, size: 1 };
        }
      }
    }
  }
  return rowGroupLAB.value
}

const fetchDiagnosa = async (filter: any) => {
  const response = await useApi().get(
    `/diagnosa/diagnosa-x-paging?name=${filter.query}&limit=10`)

  d_Diagnosa.value = response.diagnosa.map((item: any) => {
    return { value: item.id, label: item.kddiagnosa + ' - ' + item.namadiagnosa }
  })
  // const response = await useApi().get(
  //   `/emr/dropdown/diagnosa_m?select=kddiagnosa,namadiagnosa&param_search=kddiagnosa&query=${filter.query}&limit=10`)
  // d_Diagnosa.value = response
}

const getIDTujuanKeper = (e: any) => {
  tujuanKeper.value = e.value
  console.log(tujuanKeper.value)
}

const fetchDiagnosa9 = async (filter: any) => {
  const response = await useApi().get(
    `/diagnosa/diagnosa-ix-paging?name=${filter.query}&limit=10`)

  d_Diagnosa9.value = response.diagnosatindakan.map((item: any) => {
    return { value: item.id, label: item.kddiagnosatindakan + ' - ' + item.namadiagnosatindakan, default: item }
  })
  // const response = await useApi().get(
  //   `/emr/dropdown/diagnosatindakan_m?select=kddiagnosatindakan,namadiagnosatindakan&param_search=kddiagnosatindakan&query=${filter.query}&limit=10`)
  // d_Diagnosa9.value = response
}


async function fetchDiagnosa10(filter: any) {
  let query = ''
  if (filter) {
    query = filter.toLowerCase()
  }
  const response = await useApi().get(
    `/diagnosa/diagnosa-x-paging?name=${query}&limit=10`)

  return response.diagnosa.map((item: any) => {
    return { value: item.id, label: item.kddiagnosa + ' - ' + item.namadiagnosa, default: item }
  })
}


// const fetchDiagnosa10 = async (filter: any) => {
//   let query = ''
//   if (filter) {
//     query = filter.query.toLowerCase()
//   }

//   const response = await useApi().get(
//     `/diagnosa/diagnosa-x-paging?name=${query}&limit=10`)
//   d_Diagnosa10.value = response.diagnosa

// }
// const fetchJenisDiagnosa = async (filter: any) => {
//   const response = await useApi().get(
//     `/emr/dropdown/jenisdiagnosa_m?select=id,jenisdiagnosa&param_search=jenisdiagnosa&query=${filter.query}&limit=10`)
//   d_JenisDiagnosa.value = response
// }

function fetchJenisDiagnosa() {
  useApi().get(
    `/diagnosa/list-dropdown`).then((response: any) => {
      d_JenisDiagnosa.value = response.jenisdiagnosa.map((e: any) => { return { label: e.jenisdiagnosa, value: e.id, default: e } })

    })
}

async function dropdownList() {
  useApi().get(
    `/diagnosa/list-dropdown`).then((response: any) => {
      d_JenisDiagnosa.value = response.jenisdiagnosa.map((e: any) => { return { label: e.jenisdiagnosa, value: e.id, default: e } })

    })
  d_Ruangan.value = await useApi().get(`emr/dropdown/ruangan_m?select=id,namaruangan&settingdatafix=objectdepartemenfk,kdDepartemenRawatJalanFix,kdDepartemenRanapFix`)
}

const lihatBerkas = async (e: any) => {
  H.openFile('berkaspasien/' + e.nocm + '/' + e.namafile);
}
const copyItemDiagnosa = (item: any, type: string) => {
  delete d_CopyDiagnosa.value;
  let query = '';
  let jenisDiagnosa = item.jenisDiagnosa ? item.jenisDiagnosa.label : '';

  if (item) {
    let diagnosa = item.diagnosaa ? item.diagnosaa.label : '';
    let parts
    type == 'ICD10' ? parts = diagnosa.split("-") : parts = diagnosa.split("-");;
    query = parts[1] ? parts[1].toLowerCase() : parts;
    console.log(parts);

  }
  if (type == 'ICD10') {
    try {
      useApi().get(`/diagnosa/diagnosa-x-paging?name=${query}&limit=10`)
        .then(response => {
          if (response.diagnosa && response.diagnosa.length > 0) {
            const firstElement = response.diagnosa[0];
            const label = `${firstElement.kddiagnosa} - ${firstElement.namadiagnosa}`;

            let diagnosaObj = {
              'jenisDiagnosa': { label: jenisDiagnosa, value: item.jenisDiagnosa.value },
              'keterangan': item.keterangan,
              'diagnosaa': { label: label, value: firstElement.id }
            };
            d_CopyDiagnosa.value = diagnosaObj;
            H.alert('info', 'Text copied to clipboard')
          } else {
            H.alert('error', 'Failed to copy text')
          }
        })
        .catch(error => {
          console.error('Error fetching data:', error);
        });
    } catch (error) {
      console.error('Error in try block:', error);
    }
  } else {
    try {
      useApi().get(`/diagnosa/diagnosa-ix-paging?name=${query}&limit=10`)
        .then(response => {
          if (response.diagnosa && response.diagnosa.length > 0) {
            const firstElement = response.diagnosa[0];
            const label = `${firstElement.kddiagnosatindakan} - ${firstElement.namadiagnosatindakan}`;

            let diagnosaObj = {
              'jenisDiagnosa': { label: jenisDiagnosa, value: item.jenisDiagnosa.value },
              'diagnosaa': { label: label, value: firstElement.id }
            };
            d_CopyDiagnosa.value = diagnosaObj;
            H.alert('info', 'Text copied to clipboard')
          } else {
            H.alert('error', 'Failed to copy text')
          }
        })
        .catch(error => {
          console.error('Error fetching data:', error);
        });
    } catch (error) {
      console.error('Error in try block:', error);
    }
  }

};
const copyDOKTERA = (item: any) => {
  d_CopyDiagnosa.value = []
  item.diagnosaDokter.forEach(element => {
    if (element.jenisDiagnosa != undefined) {
      let diagnosaObj = {
        'jenisDiagnosa': { label: element.jenisDiagnosa ? element.jenisDiagnosa.label : null, value: element.jenisDiagnosa ? element.jenisDiagnosa.value : null },
        'keterangan': element ? element.keterangan : null,
        'diagnosaa': { label: element.diagnosaa ? element.diagnosaa.label : null, value: element.diagnosaa ? element.diagnosaa.value : null },
        'type': 'ICD10'
      };
      d_CopyDiagnosa.value.push(diagnosaObj);
    }
  });
  H.alert('info', 'Text copied to clipboard')
  // item.diagnosaDokter9.forEach(element => {
  //   let diagnosaObj = {
  //     'jenisDiagnosa': { label: element.jenisDiagnosa.label, value: element.jenisDiagnosa.label },
  //     'keterangan': item.keterangan,
  //     'diagnosaa': { label: element.diagnosa.label, value: element.diagnosa.value },
  //     'type': 'IDC9'
  //   };
  //   d_CopyDiagnosa.value = diagnosaObj;
  // });
}

const pasteItemDiagnosa = (index: number, flex: any) => {
  if (flex == 'dokter') {
    const newDiagnosa = {
      ...d_CopyDiagnosa.value,
      no: input.value.details[index].diagnosaDokter.length + 1,
      norecDiagnosa: null
    };
    input.value.details[0].diagnosaDokter.push(newDiagnosa);
  } else if (flex == "all") {
    d_CopyDiagnosa.value.forEach(element => {
      if (element.type == 'ICD10') {
        const newDiagnosa = {
          ...element,
          no: input.value.details[index].diagnosaDokter.length + 1,
          norecDiagnosa: null
        };
        input.value.details[0].diagnosaDokter.push(newDiagnosa);
      } else {
        const newDiagnosa = {
          ...element,
          no: input.value.details[index].diagnosaDokter9.length + 1,
          norecDiagnosa: null
        };
        input.value.details[0].diagnosaDokter9.push(newDiagnosa);
      }
    });
  }
  else {
    const newDiagnosa = {
      ...d_CopyDiagnosa.value,
      no: input.value.details[index].diagnosaDokter9.length + 1,
      norecDiagnosa: null
    };
    input.value.details[0].diagnosaDokter9.push(newDiagnosa);
  }
}
const switchFilter = (key: any) => {
  switch (key) {
    case 'perawat':
      isPerawat.value = isPerawat.value == true ? true : false
      isDokter.value = false
      isProfesi.value = false
      isGizi.value = false
      // item.filter = isPerawat.value ? 'perawat' : '';
      flagemr = isPerawat.value.toString() ? 'perawat' : '';
      // console.log(`flag : ${flagemr}, isDokter : ${isDokter.value}, isPerawat : ${isPerawat.value}`)
      loadRiwayatOld()
      // console.log(flagemr)
      break;
    case 'dokter':
      isDokter.value = isDokter.value == true ? true : false
      isPerawat.value = false
      isProfesi.value = false
      isGizi.value = false
      // item.filter = isDokter.value ? 'dokter' : '';
      flagemr = isDokter.value.toString() ? 'dokter' : '';
      // console.log(`flag : ${flagemr}, isDokter : ${isDokter.value}, isPerawat : ${isPerawat.value}`)
      loadRiwayatOld()
      // console.log(flagemr)
      break;
    case 'profesi lain':
      isProfesi.value = isProfesi.value == true ? true : false
      isDokter.value = false
      isPerawat.value = false
      isGizi.value = false
      // item.filter = isProfesi.value ? 'profesi lain' : '';
      flagemr = '';
      loadRiwayatOld()
      console.log(flagemr)
      break;
    case 'gizi':
      isGizi.value = isGizi.value == true ? true : false
      isProfesi.value = false
      isDokter.value = false
      isPerawat.value = false
      // item.filter = isGizi.value ? 'gizi' : ' ';
      flagemr = isGizi.value.toString() ? 'gizi' : ' ';
      loadRiwayatOld()
      console.log(flagemr)
      break;
    default:
      item.filter = 'profesi lain';
      break;
  }
}
const clear = () => {
  item.filter = ''
  isPerawat.value = false
  isProfesi.value = false
  isDokter.value = false
  isGizi.value = false
}

function clearInput() {
  delete item.NOREC_DIAGNOSA9
  delete item.NOREC_DIAGNOSA10
  delete item.keterangan9
  delete item.diagnosa9
  delete item.keterangan10
  delete item.diagnosa10
  delete item.jenisDiagnosis10

  modalInput.value = false
  modalInput9.value = false
}

// const addKeperawatan = (val: string = '') => {

//   let element = input.value.details[indexcppt.value];
//   if (element.A == undefined) {
//     element.A = ''
//   }
//   if (val == 'bidan') {
//     let toInput = '';
//     toInput += diagBidan.value.g ? `G: ${diagBidan.value.ketG}\n` : '';
//     toInput += diagBidan.value.ketP ? `P: ${diagBidan.value.ketP}\n` : '';
//     toInput += diagBidan.value.ketUK ? `UK: ${diagBidan.value.ketUK}\n` : '';
//     toInput += diagBidan.value.ketMinggu ? `Minggu: ${diagBidan.value.ketMinggu}\n` : '';
//     toInput += diagBidan.value.ketHari ? `Hari: ${diagBidan.value.ketHari}\n` : '';
//     toInput += diagBidan.value.p2 ? `P2: ${diagBidan.value.ketP2}\n` : '';
//     toInput += diagBidan.value.ketA2 ? `A2: ${diagBidan.value.ketA2}\n` : '';
//     toInput += diagBidan.value.akseptorbaru ? `Akseptor Kontrasepsi Baru :${diagBidan.value.ketAkseptorbaru}\n` : '';
//     toInput += diagBidan.value.akseptorlama ? `Akseptor Kontrasepsi Lama :${diagBidan.value.ketAkseptorlama}\n` : '';
//     toInput += diagBidan.value.akslama ? `Akseptor Lama :${diagBidan.value.ketAkseptorlama2}\n` : '';
//     toInput += diagBidan.value.gantiKontrasepsi ? `ganti ke: ${diagBidan.value.gantiKontrasepsi}\n` : '';
//     toInput += diagBidan.value.puswus ? `ganti ke: ${diagBidan.value.puswus}\n` : '';
//     toInput += diagBidan.value.CBLainnya_Diagnosa_Kebidanan ? `ganti ke: ${diagBidan.value.TBDiagnosaLainnya}\n` : '';

//     element.A += toInput;
//     showModalDiagnosaBidan.value = false;
//   } else {
//     for (let index = 0; index < dig.length; index++) {
//       const dvalue = dig[index];
//       if (dvalue != false)
//         element.A += dvalue + '\n'
//     }

//     if (diaglainnyaList.value) {
//       element.A += diaglainnyaList.value + '\n'
//     }

//     showModalKeperawatan.value = false
//   }
// }
const selectedDiagnosa = ref([]);

const addKeperawatan = (val = '') => {
  let element = input.value.details[indexcppt.value];
  if (!element.A) {
    element.A = '';
  }

  if (val === 'bidan') {
    let toInput = '';
    toInput += diagBidan.value.g ? `G: ${diagBidan.value.ketG}\n` : '';
    toInput += diagBidan.value.ketP ? `P: ${diagBidan.value.ketP}\n` : '';
    toInput += diagBidan.value.ketUK ? `UK: ${diagBidan.value.ketUK}\n` : '';
    toInput += diagBidan.value.ketMinggu ? `Minggu: ${diagBidan.value.ketMinggu}\n` : '';
    toInput += diagBidan.value.ketHari ? `Hari: ${diagBidan.value.ketHari}\n` : '';
    toInput += diagBidan.value.p2 ? `P2: ${diagBidan.value.ketP2}\n` : '';
    toInput += diagBidan.value.ketA2 ? `A2: ${diagBidan.value.ketA2}\n` : '';
    toInput += diagBidan.value.akseptorbaru ? `Akseptor Kontrasepsi Baru: ${diagBidan.value.ketAkseptorbaru}\n` : '';
    toInput += diagBidan.value.akseptorlama ? `Akseptor Kontrasepsi Lama: ${diagBidan.value.ketAkseptorlama}\n` : '';
    toInput += diagBidan.value.gantiKontrasepsi ? `Ganti ke: ${diagBidan.value.gantiKontrasepsi}\n` : '';
    toInput += diagBidan.value.puswus ? `PUS/WUS: ${diagBidan.value.puswus}\n` : '';
    toInput += diagBidan.value.CBLainnya_Diagnosa_Kebidanan ? `Diagnosa Lainnya: ${diagBidan.value.TBDiagnosaLainnya}\n` : '';

    element.A += toInput;
    showModalDiagnosaBidan.value = false;
  }

  else {

    const tempDiagnosa = selectedDiagnosa.value.map(id => {
      const diagnosa = d_DiagnosaSdki.value.find(d => d.id === id);
      return diagnosa ? diagnosa.label : '';
    });

    for (let index = 0; index < dig.length; index++) {
      const dvalue = dig[index];
      if (dvalue != false)
        element.A += dvalue + '\n'
    }

    if (diaglainnyaList.value) {
      element.A += diaglainnyaList.value + '\n'
    }

    tempDiagnosa.forEach((dvalue) => {
      if (dvalue) {
        element.A += `${dvalue}\n`;
      }
    });

    showModalKeperawatan.value = false;
  }

  // selectedDiagnosa.value = [];
};


const addRencanaKeperawatan = () => {
  let element = input.value.details[indexcppt.value];
  if (element.P == undefined) {
    element.P = ''
  }

  for (let index = 0; index < rencanaKeper.length; index++) {
    const dvalue = rencanaKeper[index];
    if (dvalue != false) {
      element.P += dvalue + '\n'
    }
  }
  if (rencanaKeperLainnya.value) {
    element.P += rencanaKeperLain.value + '\n'
  }

  selectedSiki.value.forEach((dvalue) => {
    if (dvalue) {
      element.P += `${dvalue}\n`;
    }
  });
  showModalRencanaKeperawatan.value = false
  showRencanaKebidanan.value = false
}

const inputTindakan = (index: any) => {
  indexcppt.value = index
  // console.log('details', input.value.details[indexcppt])
  // console.log('index', indexcppt)

  isLoadingBill.value = true
  isLoading.value = true;
  let stringTindakan = '';
  useApi().get(
    `/kasir/billing?norec_pd=${item.NOREC_PD}&istindakan=true`).then(async (response: any) => {
      isLoadingBill.value = false
      isLoading.value = false;
      if (response.detail.length > 0 && response.detail[0].details.length > 0) {
        let details = response.detail[0].details;
        // if(input.value.details[indexcppt].O != undefined || input.value.instruksiAsesmen != null) {
        //     stringTindakan = ''
        // }else {
        //     stringTindakan = 'Tindakan : \n'
        // }

        details.forEach(elT => {
          stringTindakan += `# ${elT.namaproduk} (${elT.jumlah}) `
        });

        let element = input.value.details[indexcppt.value];
        if (element.P == undefined) {
          element.P = ''
        }

        element.P += stringTindakan

      } else {
        H.alert('warning', 'Belum ada Tindakan');
      }
    })
}

const addObat = (resep: any) => {
  console.log("ADD OBAT", resep);

  let element = input.value.details[indexcppt.value];

  if (element.P == undefined) {
    element.P = ''
  }
  resep.details.forEach(obt => {
    element.P += obt.namaproduk + ' (' + obt.aturanpakai + ') \n'
  });
}

const addTemplate = (response: any) => {
  let push: any = {}
  let lastTTV = {
    td: input.value.tekananDarah ?? '',
    nadi: input.value.nadi ?? '',
    nafas: input.value.nafas ?? '',
    celcius: input.value.celcius ?? '',
    sao2: input.value.sao2 ?? '',
    bb: input.value.beratBadan ?? '',
    tb: input.value.tinggiBadan ?? '',
    e: input.value.gcse ?? '',
    v: input.value.gcsv ?? '',
    m: input.value.gcsm ?? ''
  }

  // input.value.details = response //set ke inputan
  // Get last number for flag
  let lastId = 0;
  let lastIndex = 0;
  input.value.details.forEach((dt, index) => {
    if (dt.flag == kelompokUser) {
      lastIndex = index;
      lastId = dt.no
    }
  });
  indexcpptcopy = input.value.details[lastIndex].no + (lastId + 1)
  // let findperflag = input.value.details.filter((dtf) => {
  //   console.log("TENAGA MEDIS", dtf.tenagaMedis);
  //   return dtf.tenagaMedis.value == userLogin.pegawai.id && dtf.button == true;
  // });

  if (input.value.details > 4) {
    response.details.forEach(temp => {
      if (temp.flag == 'dokter') {
        let diagnosaDokter = []
        let diagnosaDokter9 = []
        input.value.details.forEach((data, index) => {
          if (!data.norecDiagnosa && data.flag == 'dokter') diagnosaDokter = data.diagnosaDokter
          if (!data.norecDiagnosa9 && data.flag == 'dokter') diagnosaDokter9 = data.diagnosaDokter9
        });
        push = {
          uuid: uuidv4(),
          no: input.value.details[0].no + 1,
          tgl: new Date(),
          O: temp.O,
          S: temp.S,
          A: temp.A,
          P: temp.P,
          tglVerifikasi: new Date(),
          flag: 'dokter',
          diagnosaDokter: diagnosaDokter,
          diagnosaDokter9: diagnosaDokter9,
          dpjpRawatBersama: temp.dpjpRawatBersama,
          dokterDPJP: temp.dokterDPJP,
          dokterraber: temp.dokterraber,
          intruksi: temp.intruksi,
          intruksiPPA: temp.intruksiPPA,
          dpjpUtama: temp.dpjpUtama,
          tenagaMedis: { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id },
          author: userLogin.pegawai
        }
      }
      if (temp.flag == 'perawat') {
        push = {
          uuid: uuidv4(),
          no: input.value.details[0].no + 1,
          tgl: new Date(),
          tglVerifikasi: new Date(),
          O: temp.O,
          S: temp.S,
          A: temp.A,
          P: temp.P,
          flag: 'perawat',
          diagnosaKep: temp.diagnosaKep,
          tujuanKep: temp.tujuanKep,
          intruksi: temp.intruksi,
          intruksiPPA: temp.intruksiPPA,
          dpjpRawatBersama: temp.dpjpRawatBersama,
          dokterDPJP: temp.dokterDPJP,
          dpjpUtama: temp.dpjpUtama,
          dokterraber: temp.dokterraber,
          tenagaMedis: { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id },
          author: userLogin.pegawai
        }

      }
      if (temp.flag == 'profesi lain') {
        push = {
          uuid: uuidv4(),
          no: input.value.details[0].no + 1,
          tgl: new Date(),
          tglVerifikasi: new Date(),
          flag: 'profesi lain',
          O: temp.O,
          S: temp.S,
          A: temp.A,
          P: temp.P,
          dpjpRawatBersama: temp.dpjpRawatBersama,
          intruksi: temp.intruksi,
          intruksiPPA: temp.intruksiPPA,
          dokterDPJP: temp.dokterDPJP,
          dokterraber: temp.dokterraber,
          dpjpUtama: temp.dpjpUtama,
          tenagaMedis: { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id },
          author: userLogin.pegawai
        }
      }
      if (temp.flag == 'gizi') {
        push = {
          uuid: uuidv4(),
          no: input.value.details[0].no + 1,
          tgl: new Date(),
          tglVerifikasi: new Date(),
          flag: 'gizi',
          O: temp.O,
          S: temp.S,
          A: temp.A,
          P: temp.P,
          dokterDPJP: temp.dokterDPJP,
          intruksi: temp.intruksi,
          intruksiPPA: temp.intruksiPPA,
          dpjpRawatBersama: temp.dpjpRawatBersama,
          dokterraber: temp.dokterraber,
          dpjpUtama: temp.dpjpUtama,
          tenagaMedis: { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id },
          author: userLogin.pegawai
        }
      }
      input.value.details.unshift(push);
      input.value.details.sort((a, b) => (a.no < b.no) ? 1 : ((b.no < a.no) ? -1 : 0))
    });
  } else {
    // input.value.details = response.details
    for (let index = 0; index < response.details.length; index++) {
      const element = response.details[index];
      if (element.S != '' && element.O != '' && element.S != undefined && element.O != undefined && element.A != '' && element.A != undefined && element.P != '' && element.P != undefined) {
        input.value.details[0] = element
        input.value.details[0].uuid = uuidv4()
        input.value.details[0].ruangan = props.registrasi.namaruangan
        input.value.details[0].tgl = new Date()
        input.value.details[0].tglVerifikasi = new Date()
        input.value.details[0].dpjpUtama = { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk }
        input.value.details[0].dokterDPJP = { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk }
        input.value.details[0].author = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
      }
    }
    input.value.details = [input.value.details[0]]
  }
  // input.value.details[0] = response.details[0];
  input.value.tekananDarah = lastTTV.td;
  input.value.nadi = lastTTV.nadi;
  input.value.nafas = lastTTV.nafas;
  input.value.celcius = lastTTV.celcius;
  input.value.sao2 = lastTTV.sao2;
  input.value.beratBadan = lastTTV.bb;
  input.value.tinggiBadan = lastTTV.tb;
  input.value.gcse = lastTTV.e;
  input.value.gcsv = lastTTV.v;
  input.value.gcsm = lastTTV.m;


  input.value.namatemplate = null
  isAlltemplate.value = false;
  showModalTemplateFix.value = false
  H.alert('success', 'Berhasil di tambahkan');
}
const simpanTemplate = () => {
  if (!input.value.namatemplate || input.value.namatemplate == '') {
    H.alert('warning', 'Nama Template wajib diisi');
    return;
  }
  let ID = idTemplateCPPT.value ? idTemplateCPPT.value : ''

  let object: any = {}

  object = input.value
  object.nocm = props.pasien.nocm
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': 'CPPTDetail',
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

const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi().get(
    `/emr/get-emr-template?collection=CPPTDetail`).then((responselast: any) => {
      isLoading.value = false
      console.log(responselast)
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

const addTindakan = (tindakan: any) => {

  let element = input.value.details[indexcppt.value];

  if (element.O == undefined) {
    element.O = ''
  }

  element.O += tindakan.namaproduk + ' (' + tindakan.jumlah + ') \n'

}

const addKonselor = () => {
  // console.log(indexcppt.value)
  // console.log(dataSourceICD10)
  let element = input.value.details[indexcppt.value];

  if (element.A == undefined) {
    element.A = ''
  }

  for (let index = 0; index < kons.length; index++) {
    const dvalue = kons[index];
    if (dvalue != false)
      element.A += dvalue + '\n'
  }

  // for (var i = 0; i < dataSourceICD10._value.length; i++) {
  //   element.A += dataSourceICD10._value[i].kddiagnosa + ' - ' + dataSourceICD10._value[i].namadiagnosa + '\n'
  // }


  showModalKonselor.value = false;

}

const tambahDokter = (index: number) => {
  input.value.details[index].dpjpRawatBersama.push({ id: null })
};

const hapusDokter = (index: number, dIndex: number) => {
  input.value.details[index].dpjpRawatBersama.splice(dIndex, 1);
};

async function checkAsmed() {
  isLoading.value = true
  let d = input.value;
  let field = 'anamnesis,kepala,ketKepala,ubunubun,ketUbunubun,normal,mikrosefali,ingkarkepala,ketLingkarkepala,lingkarlainnya,ketLingkarlainnya,makrosefali'
  let res = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&collection=AsesmenMedisRawatJalan&ruangan=" + props.registrasi.namaruangan + "&field=" + field + "&norec_pd=" + NOREC_PD);
  isLoading.value = false
  if (res != null) {
    let text =
      "1. Kepala \n" +
      (res.kepala ? 'Kepala : ' + res.ketKepala + '\n' : '') +
      (res.ubunubun ? 'Ubun-ubun besar : ' + res.ketUbunubun + '\n' : '') +
      (res.normal ? 'Normal \n' : '') +
      (res.mikrosefali ? 'Mikrosefali \n' : '') +
      (res.lingkarkepala ? 'Lingkar kepala : ' + res.ketLingkarkepala + '\n' : '') +
      (res.lingkarlainnya ? 'Lainnya : ' + res.ketLingkarlainnya + '\n' : '') +
      (res.makrosefali ? 'Makrosefali \n' : '') +

      "2. Mata \n" +
      (res.anemis ? 'Anemis : ' + res.ketAnemis + '\n' : '') +
      (res.konjungtiva ? 'Konjungtiva Pucat : ' + (res.ketKonjungtiva == 1 ? 'Ya' : 'Tidak') + '\n' : '') +
      (res.pupil ? 'Pupil Isokor : ' + (res.ketPupil == 1 ? 'Ya' : 'Tidak') + '\n' : '')
      ;
    for (let x = 0; x < input.value.details.length; x++) {
      const element = input.value.details[x];
      input.value.details[x].S = res != '' ? `${res.anamnesis}` : ''
      input.value.details[x].O = res != '' ? `${strforO.value} \n ${text}` : ''
    }
  } else {
    if (props.registrasi.namaruangan.toUpperCase().indexOf('IGD') > -1) {
      for (let x = 0; x < input.value.details.length; x++) {
        const element = input.value.details[x];
        input.value.details[x].S = strforS.value != '' ? `${strforS.value}` : ''
        input.value.details[x].O = strforO.value != '' ? `${strforO.value}` : ''
      }
    }
  }
}

const checkAsesmen = async () => {
  isLoading.value = true;
  if (props.registrasi.namaruangan.toUpperCase().indexOf('IGD') > -1) {
    let data = ''
    let datax = ''

    let dataCollection = 'AsesmenAwalMedisGawatDarurat';
    let fieldCollection = 'perlukontrol,riwayatkeluar,statuskeluar,TAKeluhanUtama,TARiwayatPenyakitDahulu,TARiwayatPenggunaanObat,TARiwayatVaksin,TARPS,CBisiMOI,TAMOI,isalergi,alergi_tidak_diketahui,CBAlergiObat,TBAlergiObat,CBAlergiMakanan,TBAlergiMakanan,CBAlergiLainnya,TBAlergiLainnya,TAKepalaSG,TBAnemisMata,TBIkterusMata,TBRefleksPupilMata,TBOedemaPalpebraeMata,TBTonsilTHT,TBPharingTHT,TBTelingaTHT,TBHidungTHT,TBBibirTHT,TBLainnyaTHT,TBJVPLeher,TBPembesaranKelenjarLeher,CBKakuKudukLeher,CBSimetrisThoraks,CBAsimetrisThoraks,TBSimetrisORAsimetrisThoraks,TBRetraksiThoraks,TBS1S2Cor,CBRegulerCor,CBIregulerCor,TBMurmurCor,TBLainLainCor,TBRonchiPulmo,TBWheezingPulmo,TBVesikulerPulmo,TBLainnyaPulmo,CBSouffleAbdomen,CBDistensiAbdomen,CBMeteorismusAbdomen,CBNormalPeristaltik,CBMeningkatPeristaltik,CBMenurunPeristaltik,CBAscitesPeristaltik,TBNyeriTekanLokasiPeristaltik,TBHeparPeristaltik,TBLienPeristaltik,CBHangatExtremitas,CBDinginExtremitas,TBOdemaExtremitas,TBLainlainExtremitas,TBLainlainSG'
    if (kelompokUser.toUpperCase().indexOf('PERAWAT') > -1 && props.registrasi.namaruangan.trim() == 'RUANG VK - IGD') {
      dataCollection = 'AsesmenAwalKebidananDanKandungan';
      fieldCollection = `TAKeluhanUtama,TARiwayatPenyakitSekarang,TARiwayatPenyakitDahulu,TARiwayatPengobatan,SRiwayatAlergi,TBAlergiLainnya,TBAlergiMakanan,TBAlergiObat,CBAlergiLainnya,
      CBAlergiMakanan,CBAlergiObat,SKeadaanUmum,GCSe,GCSv,GCSm,TBStekananDarah,TBSnadi,TBSrespirasi,TBSsuhu,TBSSaO2,TBSBeratBadan,TBStinggiBadan,TBSSkorEWS,TBSStatusObstetri,TADiagnosaKebidanan`
    }
    else if (kelompokUser.toUpperCase().indexOf('PERAWAT') > -1) {
      dataCollection = 'AsesmenAwalKeperawatanGawatDarurat';
      fieldCollection = 'keluhanutama,riwayatpenyakit,riwayatpenyakitdahulu,riwayatpenyakitkeluarga,riwayatpengobatan,isalergi,TBAlergiLainnya,TBAlergiMakanan,TBAlergiObat,CBAlergiLainnya,CBAlergiMakanan,CBAlergiObat'
    }
    const data_AMI = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD +
      `&collection=${dataCollection}` +
      `&field=${fieldCollection}`
    )
    const data_TPI = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=TriagePasienIGD" + `&field=keadaanumum,TBeGCS,TBvGCS,TBmGCS,TBcelciusTTV,TBPernafasanTTV,TBberatBadanTTV,TBnadiTTV,TBtekananDarahTTV,TBnspo2TTV,TBtinggiBadanTTV`)

    if (data_TPI && props.registrasi.namaruangan.trim() != 'RUANG VK - IGD') {
      data += data_TPI.TBcelciusTTV ? `Suhu : ${data_TPI.TBcelciusTTV} °C\n` : ''
      data += data_TPI.TBnadiTTV ? `Nadi : ${data_TPI.TBnadiTTV} x/mnt\n` : ''
      data += data_TPI.TBPernafasanTTV ? `Pernafasan : ${data_TPI.TBPernafasanTTV} x/mnt\n` : ''
      data += data_TPI.TBtekananDarahTTV ? `Tekanan Darah : ${data_TPI.TBtekananDarahTTV} mmHg\n` : ''
      data += data_TPI.TBtinggiBadanTTV ? `Tinggi Badan : ${data_TPI.TBtinggiBadanTTV} Cm\n` : ''
      data += data_TPI.TBberatBadanTTV ? `Berat Badan : ${data_TPI.TBberatBadanTTV} Kg\n` : ''
      data += data_TPI.TBnspo2TTV ? `SPO2 : ${data_TPI.TBnspo2TTV} %\n` : ''
      data += data_TPI.TBeGCS ? `GCS : E ${data_TPI.TBeGCS} V ${data_TPI.TBvGCS ? data_TPI.TBvGCS : ''} M ${data_TPI.TBmGCS ? data_TPI.TBmGCS : ''} \n` : ''

      input.value.tekananDarah = data_TPI.TBtekananDarahTTV ?? '';
      input.value.nadi = data_TPI.TBnadiTTV ?? '';
      input.value.nafas = data_TPI.TBPernafasanTTV ?? ''
      input.value.celcius = data_TPI.TBcelciusTTV ?? ''
      input.value.sao2 = data_TPI.TBnspo2TTV ?? ''
      input.value.beratBadan = data_TPI.TBberatBadanTTV ?? ''
      input.value.tinggiBadan = data_TPI.TBtinggiBadanTTV ?? ''
      input.value.gcse = data_TPI.TBeGCS ?? ''
      input.value.gcsv = data_TPI.TBvGCS ?? ''
      input.value.gcsm = data_TPI.TBmGCS ?? ''
      input.value.keadaanumumobgyn = data_TPI.keadaanumum ?? ''
    }

    if (data_AMI) {
      if (kelompokUser.toUpperCase().indexOf('DOKTER') > -1) {
        datax += data_AMI.TAKeluhanUtama ? `Keluhan Utama : ${data_AMI.TAKeluhanUtama}\n` : 'Keluhan Utama : -\n'
        datax += data_AMI.TARiwayatPenyakitDahulu ? `Riwayat Penyakit Dahulu : ${data_AMI.TARiwayatPenyakitDahulu}\n` : 'Riwayat Penyakit Dahulu : -\n'
        datax += data_AMI.TARiwayatPenggunaanObat ? `Riwayat Penggunaan obat : ${data_AMI.TARiwayatPenggunaanObat}\n` : 'Riwayat Penggunaan obat : -\n'
        datax += data_AMI.TARiwayatVaksin ? `Riwayat Vaksin : ${data_AMI.TARiwayatVaksin}\n` : 'Riwayat Vaksin : -\n'
        datax += data_AMI.TARPS ? `Riwayat Penyakit Sekarang : ${data_AMI.TARPS}\n` : 'Riwayat Penyakit Sekarang : -\n'
        datax += data_AMI.TAMOI ? `MOI : ${data_AMI.TAMOI}\n` : 'MOI : -\n'
        datax += data_AMI.isalergi ? `Riwayat Alergi : ` + (data_AMI.isalergi == 'YA' ? (data_AMI.CBAlergiObat == "Obat" ? data_AMI.TBAlergiObat : '') + ' ' + (data_AMI.CBAlergiMakanan == "Makanan" ? data_AMI.TBAlergiMakanan : '') + ' ' + (data_AMI.CBAlergiLainnya == "Lainnya" ? data_AMI.TBAlergiLainnya : '') + '\n' : (data_AMI.alergi_tidak_diketahui ? data_AMI.alergi_tidak_diketahui : 'Tidak ada \n')) : 'Riwayat Alergi : -\n'

        data += '\nStatus Generalis \n'
        data += data_AMI.TAKepalaSG ? `Kepala : ${data_AMI.TAKepalaSG}\n` : 'Kepala : -\n'
        data += 'Mata \n'
        data += data_AMI.TBAnemisMata ? `Anemis : ${data_AMI.TBAnemisMata}\n` : 'Anemis : -'
        data += data_AMI.TBIkterusMata ? `Ikterus : ${data_AMI.TBIkterusMata}\n` : 'Ikterus : -'
        data += data_AMI.TBRefleksPupilMata ? `Refleks Pupil : ${data_AMI.TBRefleksPupilMata}\n` : 'Refleks Pupil : -'
        data += data_AMI.TBOedemaPalpebraeMata ? `Oedema Palpebrae : ${data_AMI.TBOedemaPalpebraeMata}\n` : 'Oedema Palpebrae : -'
        data += 'THT \n'
        data += data_AMI.TBTonsilTHT ? `Tonsil : ${data_AMI.TBTonsilTHT}\n` : 'Tonsil : -\n'
        data += data_AMI.TBPharingTHT ? `Pharing : ${data_AMI.TBPharingTHT}\n` : 'Pharing : -\n'
        data += data_AMI.TBTelingaTHT ? `Telinga : ${data_AMI.TBTelingaTHT}\n` : 'Telinga : -\n'
        data += data_AMI.TBHidungTHT ? `Hidung : ${data_AMI.TBHidungTHT}\n` : 'Hidung : -\n'
        data += data_AMI.TBBibirTHT ? `Bibir : ${data_AMI.TBBibirTHT}\n` : 'Bibir : -\n'
        data += data_AMI.TBLainnyaTHT ? `Lainnya : ${data_AMI.TBLainnyaTHT}\n` : 'Lainnya : -\n'
        data += 'Leher \n'
        data += data_AMI.TBJVPLeher ? `JVP : ${data_AMI.TBJVPLeher}\n` : 'JVP : -\n'
        data += data_AMI.TBPembesaranKelenjarLeher ? `Pembesaran Kelenjar : ${data_AMI.TBPembesaranKelenjarLeher}\n` : 'Pharing : -\n'
        data += data_AMI.CBKakuKudukLeher ? `Kaku Duduk : Ya\n` : 'Kaku Duduk : Tidak\n'
        data += `Thoraks : ${data_AMI.CBSimetrisThoraks ? data_AMI.CBSimetrisThoraks : (data_AMI.CBAsimetrisThoraks ? data_AMI.CBAsimetrisThoraks : '')}, ${data_AMI.TBSimetrisORAsimetrisThoraks ? data_AMI.TBSimetrisORAsimetrisThoraks : ''} \n`
        data += data_AMI.TBRetraksiThoraks ? `Retraksi : ${data_AMI.TBRetraksiThoraks}\n` : 'Retraksi : -\n'
        data += 'Cor \n'
        data += data_AMI.TBS1S2Cor ? `S1, S2 : ${data_AMI.TBS1S2Cor}, ${data_AMI.CBRegulerCor ? data_AMI.CBRegulerCor : (data_AMI.CBIregulerCor ? data_AMI.CBIregulerCor : '')} \n` : `S1, S2 : -, ${(data_AMI.CBIregulerCor ? data_AMI.CBIregulerCor : '')}\n`
        data += data_AMI.TBMurmurCor ? `Murmur : ${data_AMI.TBMurmurCor}\n` : 'Murmur : -\n'
        data += data_AMI.TBLainLainCor ? `Lain-lain : ${data_AMI.TBLainLainCor}\n` : 'Lain-lain : -\n'
        data += 'Pulmo \n'
        data += data_AMI.TBRonchiPulmo ? `Ronchi : ${data_AMI.TBRonchiPulmo}\n` : 'Ronchi : -\n'
        data += data_AMI.TBWheezingPulmo ? `Wheezing : ${data_AMI.TBWheezingPulmo}\n` : 'Wheezing : -\n'
        data += data_AMI.TBVesikulerPulmo ? `Vesikuler : ${data_AMI.TBVesikulerPulmo}\n` : 'Vesikuler : -\n'
        data += data_AMI.TBLainnyaPulmo ? `Lainnya : ${data_AMI.TBLainnyaPulmo}\n` : 'Lainnya : -\n'
        data += `Abdomen : ${data_AMI.CBSouffleAbdomen ? data_AMI.CBSouffleAbdomen + ',' : ''}${data_AMI.CBDistensiAbdomen ? data_AMI.CBDistensiAbdomen + ',' : ''}${data_AMI.CBMeteorismusAbdomen ? data_AMI.CBMeteorismusAbdomen : ''} \n`
        data += `Peristaltik : ${data_AMI.CBNormalPeristaltik ? data_AMI.CBNormalPeristaltik + ',' : ''}${data_AMI.CBMeningkatPeristaltik ? data_AMI.CBMeningkatPeristaltik + ',' : ''}${data_AMI.CBMenurunPeristaltik ? data_AMI.CBMenurunPeristaltik + ',' : ''}${data_AMI.CBAscitesPeristaltik ? data_AMI.CBAscitesPeristaltik : ''} \n`
        data += data_AMI.TBNyeriTekanLokasiPeristaltik ? `Nyeri tekan lokasi : ${data_AMI.TBNyeriTekanLokasiPeristaltik}\n` : 'Nyeri tekan lokasi : -\n'
        data += data_AMI.TBHeparPeristaltik ? `Hepar : ${data_AMI.TBHeparPeristaltik}\n` : 'Hepar : -\n'
        data += data_AMI.TBLienPeristaltik ? `Lien : ${data_AMI.TBLienPeristaltik}\n` : 'Lien : -\n'
        data += `Extrimitas : ${data_AMI.CBHangatExtremitas ? data_AMI.CBHangatExtremitas + ',' : ''}${data_AMI.CBDinginExtremitas ? data_AMI.CBDinginExtremitas : ''} \n`
        data += data_AMI.TBOdemaExtremitas ? `Odema : ${data_AMI.TBOdemaExtremitas}\n` : 'Odema : -\n'
        data += data_AMI.TBLainlainExtremitas ? `Lain-lain : ${data_AMI.TBLainlainExtremitas}\n` : 'Lain-lain : -\n'
        data += `Lain-lain : ${data_AMI.TBLainlainSG ? data_AMI.TBLainlainSG : ''}`

        // datax += data_AMI.TARiwayatPenyakitSekarang ? `Riwayat Penyakit Sekarang : ${data_AMI.TARiwayatPenyakitSekarang}\n` : 'Riwayat Penyakit Sekarang : -\n'
        // datax += data_AMI.TARiwayatPenyakitDahulu ? `Riwayat Penyakit Dahulu : ${data_AMI.TARiwayatPenyakitDahulu}\n` : 'Riwayat Penyakit Dahulu : -\n'
        // datax += data_AMI.TARiwayatPenggunaanObat ? `Riwayat Penggunaan Obat : ${data_AMI.TARiwayatPenggunaanObat}\n` : 'Riwayat Pengobatan : -\n'
        // datax += data_AMI.TARiwayatAlergi ? `Riwayat Alergi : ${data_AMI.TARiwayatAlergi}\n` : 'Riwayat Alergi : -\n'
        // datax += data_AMI.TARiwayatVaksin ? `Riwayat Vaksin : ${data_AMI.TARiwayatVaksin}\n` : 'Riwayat Vaksin : -\n'
        // datax += data_AMI.TAMOI ? `MOI : ${data_AMI.TAMOI}\n` : 'MOI : -\n'
        // passCPPT.value.result = {
        //   hasilpemeriksaanpenunjang: data_AMI.TArpp ?? '',
        //   TADiagnosa: data_AMI.TADiagnosis ?? '',
        //   rencanaIntervensi: data_AMI.TArencanaIntervensi ?? ''
        // }
      }
      else if (kelompokUser.toUpperCase().indexOf('PERAWAT') > -1 && props.registrasi.namaruangan.trim() == 'RUANG VK - IGD') {
        datax += data_AMI.TAKeluhanUtama ? `Keluhan Utama : ${data_AMI.TAKeluhanUtama}\n` : 'Keluhan Utama : -\n'
        datax += data_AMI.TARiwayatPenyakitSekarang ? `Riwayat Penyakit Sekarang : ${data_AMI.TARiwayatPenyakitSekarang}\n` : 'Riwayat Penyakit Sekarang : -\n'
        datax += data_AMI.TARiwayatPenyakitDahulu ? `Riwayat Penyakit Dahulu : ${data_AMI.TARiwayatPenyakitDahulu}\n` : 'Riwayat Penyakit Dahulu : -\n'
        datax += data_AMI.TARiwayatPengobatan ? `Riwayat Pengobatan : ${data_AMI.TARiwayatPengobatan}\n` : 'Riwayat Pengobatan : -\n'
        datax += data_AMI.TADiagnosaKebidanan ? `Diagnosa Kebidanan : ${data_AMI.TADiagnosaKebidanan}\n` : 'Diagnosa Kebidanan : -\n'

        data += data_AMI.TBSsuhu ? `Suhu : ${data_AMI.TBSsuhu} °C\n` : ''
        data += data_AMI.TBSnadi ? `Nadi : ${data_AMI.TBSnadi} x/mnt\n` : ''
        data += data_AMI.TBSrespirasi ? `Pernafasan : ${data_AMI.TBSrespirasi} x/mnt\n` : ''
        data += data_AMI.TBStekananDarah ? `Tekanan Darah : ${data_AMI.TBStekananDarah} mmHg\n` : ''
        data += data_AMI.TBStinggiBadan ? `Tinggi Badan : ${data_AMI.TBStinggiBadan} Cm\n` : ''
        data += data_AMI.TBSBeratBadan ? `Berat Badan : ${data_AMI.TBSBeratBadan} Kg\n` : ''
        data += data_AMI.TBSSaO2 ? `SPO2 : ${data_AMI.TBSSaO2} %\n` : ''
        data += data_AMI.TBSSkorEWS ? `Skor EWS : ${data_AMI.TBSSkorEWS} \n` : ''
        data += data_AMI.TBSStatusObstetri ? `Status Obstetri : ${data_AMI.TBSStatusObstetri} \n` : ''

        // const strforS: any = ;
        // const strforO:any = ref('');
        input.value.tekananDarah = data_AMI.TBStekananDarah ?? '';
        input.value.nadi = data_AMI.TBSnadi ?? '';
        input.value.nafas = data_AMI.TBSrespirasi ?? ''
        input.value.celcius = data_AMI.TBSsuhu ?? ''
        input.value.sao2 = data_AMI.TBSSaO2 ?? ''
        input.value.beratBadan = data_AMI.TBSBeratBadan ?? ''
        input.value.tinggiBadan = data_AMI.TBStinggiBadan ?? ''
        input.value.gcse = data_AMI.GCSe ?? ''
        input.value.gcsv = data_AMI.GCSv ?? ''
        input.value.gcsm = data_AMI.GCSm ?? ''
        input.value.keadaanumumobgyn = data_AMI.SKeadaanUmum ?? ''
      }
      else {
        datax += data_AMI.riwayatpenyakit ? `Riwayat Penyakit Sekarang : ${data_AMI.riwayatpenyakit}\n` : 'Riwayat Penyakit Sekarang : -\n'
        datax += data_AMI.riwayatpenyakitdahulu ? `Riwayat Penyakit Dahulu : ${data_AMI.riwayatpenyakitdahulu}\n` : 'Riwayat Penyakit Dahulu : -\n'
        datax += data_AMI.riwayatpenyakitkeluarga ? `Riwayat Penyakit Keluarga : ${data_AMI.riwayatpenyakitkeluarga}\n` : 'Riwayat Penyakit Keluarga : -\n'
        datax += data_AMI.riwayatpengobatan ? `Riwayat Penggunaan Obat : ${data_AMI.riwayatpengobatan}\n` : 'Riwayat Pengobatan : -\n'

        let riAlergi = '';
        if (data_AMI.CBAlergiObat) {
          riAlergi += 'Obat : ' + data_AMI.TBAlergiObat + ' ' ?? '-'
        }
        if (data_AMI.CBAlergiMakanan) {
          riAlergi += 'Makanan : ' + data_AMI.TBAlergiMakanan + ' ' ?? '-'
        }
        if (data_AMI.CBAlergiLainnya) {
          riAlergi += 'Lain lain : ' + data_AMI.TBAlergiLainnya + ' ' ?? '-'
        }

        // datax += data_AMI.riAlergi ? `Riwayat Alergi : ${riAlergi}\n` : 'Riwayat Alergi : -\n'
        datax += riAlergi != '' ? riAlergi : 'Riwayat Alergi : -\n'
        passCPPT.value.result = {
          hasilpemeriksaanpenunjang: '',
          TADiagnosa: ''
        }
      }
    }
    // datax += strper != ''? strper + "\n" + strdok : strper + strdok;
    strforO.value = data;
    strforS.value = datax;
    for (let x = 0; x < input.value.details.length; x++) {
      const element = input.value.details[x];
      input.value.details[x].O = data != '' ? `${data}` : ''
      input.value.details[x].S = datax != '' ? `${datax}` : ''
      input.value.details[x].P = data_AMI != null ? (data_AMI.TArencanaIntervensi ? data_AMI.TArencanaIntervensi : '') : ''
    }
    setAutoFill();

    let urlGetRiwayat = `/emr/get-emr-cppt?nocmfk=${ID_PASIEN}&norec_apd=${item.NOREC_APD}&norec_pd=${item.NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`;
    const response = await useApi().get(urlGetRiwayat)
    isloadingLAMPAU.value = false
    if (response.length) {
      if (response[0].details.length == 0) {
        response[0].details = input.value.details;
      } else {
        if (response[0].details[0].flag == undefined) {
          response[0].details = input.value.details;
        }
      }

      let perawat = []
      let dokter = []
      isAfterSave.value = true
      let findMissing = response[0].details.filter((dcppt) => {
        return dcppt.flag == kelompokUser;
      });
      let findForMissing = input.value.details.filter((icppt) => {
        return icppt.flag == kelompokUser;
      });

      if (findMissing.length == 0) {
        // let glength = input.value.details[0]
        response[0].details.push(findForMissing[0]);
      }

      if (kelompokUser.includes("perawat")) {

        for (let x = 0; x < response[0].details.length; x++) {
          const element = response[0].details[x];
          if (element.flag == 'perawat') {
            perawat.push(element)
          }
          if (element.flag == 'dokter') {
            dokter.push(element)
          }

          element.tgl = new Date(element.tgl);
          element.tglVerifikasi = new Date(element.tglVerifikasi);
          if (element.dpjpRawatBersama == undefined) {
            element.dpjpRawatBersama = [{
              id: null
            }]
          }

          dokter.forEach(item => {
            if (item.no == element.no) {
              if (!item.S) {
                item.S = element.S
              }
              if (!item.O) {
                item.O = element.O
              }
              if (!item.P) {
                item.P = element.P
              }
            }
          });

          if (element.flag == 'perawat') {
            dokter.forEach(item => {
              if (item.no == element.no) {
                if (!item.S) {
                  item.S = element.S
                }
                if (!item.O) {
                  item.O = element.O
                }
                if (!item.P) {
                  item.P = element.P
                }
              }
            });
            if (element.tujuanKep == undefined) {
              element.tujuanKep = [{
                no: 1
              }]
            }
          }
        }
        input.value = response[0] //set ke inputan


        for (let z = 0; z < response.length; z++) {
          panjangsoapbaru.push(response[z])
        }

        setValueDisabled()
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      }

      input.value = response[0]
      if (kelompokUser.toUpperCase().indexOf('DOKTER') > -1) {
        if (!response[0].perlukontrol) {
          input.value.perlukontrol = data_AMI.perlukontrol ?? ''
        }
        if (!response[0].riwayatkeluar) {
          input.value.riwayatkeluar = data_AMI.riwayatkeluar ?? ''
        }
        if (!response[0].statuskeluar) {
          input.value.statuskeluar = data_AMI.statuskeluar ?? ''
        }
        // if(!response[0].keadaanumumobgyn) {
        //   input.value.keadaanumumobgyn = data_AMI.keadaanumumobgyn ?? ''
        // }
      }
      // if(response)
      riwayatDataCPPT.value = response[0]
      for (let z = 0; z < response.length; z++) {
        panjangsoapbaru.push(response[z])
      }

      await addNewItem({ flag: kelompokUser })
      input.value.details = [input.value.details[0]]

      setValueDisabled()
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
    }

    await loadRiwayatOld();
    isLoading.value = false;
  } else if (props.registrasi.namaruangan.toUpperCase().indexOf('HEMO') > -1) {
    let urlGetRiwayat = `/emr/get-emr-cppt?nocmfk=${ID_PASIEN}&norec_apd=${item.NOREC_APD}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`;
    const response = await useApi().get(urlGetRiwayat)

    if (response.length) {
      delete response[0]['details']
      input.value = response[0]
      input.value.details = [array_dokter.value, array_perawat.value, array_profesi.value, array_gizi.value]
      await addNewItem({ flag: kelompokUser })
      input.value.details = [input.value.details[0]]

      await setAutoFill();
      await setValueDisabled();
    } else {
      await addNewItem({ flag: kelompokUser })
      input.value.details = [input.value.details[0]]

      await setAutoFill();
      await setValueDisabled();
    }
    await loadRiwayatOld();
    isLoading.value = false;


    // await useApi().get(`/emr/check-asesmen-hemo?nocmfk=${ID_PASIEN}`).then(async (res) => {
    //   isLoading.value = false;
    //   if (res.status == 201 && res.type == "askep") {
    //     passCPPT.value.title = 'Assesmen Keperawatan'
    //     passCPPT.value.tab = 'Assesmen Keperawatan'
    //     passCPPT.value.link = `module-emr-profile-pasien-page-emr-formulir-asuhan-keperawatan-dan-observasi-pasien-hemodialisa`
    //     passCPPT.value.isneedasesmen = true
    //     passCPPT.value.subtitle = res.message
    //     passCPPT.value.result = res.result
    //   } else if (res.status == 201 && res.type == "medis") {
    //     passCPPT.value.title = 'Assesmen Medis'
    //     passCPPT.value.tab = 'Assesmen Medis'
    //     passCPPT.value.link = `module-emr-profile-pasien-page-emr-asesmen-awal-medis-hemodialisa`
    //     passCPPT.value.isneedasesmen = true
    //     passCPPT.value.subtitle = res.message
    //     passCPPT.value.result = res.result
    //   } else {
    //     passCPPT.value.result = res.result
    //     passCPPT.value.isneedasesmen = false
    //     getDataForHemo(res);
    //   }
    // })
  } else if (isRanap.value) {
    console.log("RUANGAN RANAP", isRanap.value);
    let data = ''
    let datax = ''

    const ttv = await useApi().get(`emr/get-data-exist?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}`)
    if (ttv) {
      // console.log("TTV HASIL", ttv.suhu)
      data += ttv.suhu ? `Suhu : ${ttv.suhu} °C\n` : ''
      data += ttv.nadi ? `Nadi : ${ttv.nadi} x/mnt\n` : ''
      data += ttv.pernapasan ? `Pernafasan : ${ttv.pernapasan} x/mnt\n` : ''
      data += ttv.tekananDarah ? `Tekanan Darah : ${ttv.tekananDarah} mmHg\n` : ''
      data += ttv.tinggiBadan ? `Tinggi Badan : ${ttv.tinggiBadan} Cm\n` : ''
      data += ttv.beratBadan ? `Berat Badan : ${ttv.beratBadan} Kg\n` : ''
      data += ttv.SPO2 ? `SPO2 : ${ttv.SPO2} %\n` : ''

      input.value.tekananDarah = ttv.tekananDarah ?? '';
      input.value.nadi = ttv.nadi ?? '';
      input.value.nafas = ttv.pernapasan ?? ''
      input.value.celcius = ttv.suhu ?? ''
      input.value.sao2 = ttv.SPO2 ?? ''
      input.value.beratBadan = ttv.beratBadan ?? ''
      input.value.tinggiBadan = ttv.tinggiBadan ?? ''
      input.value.gcse = ttv.GCSe ?? ''
      input.value.gcsv = ttv.GCSv ?? ''
      input.value.gcsm = ttv.GCSm ?? ''
    }
    const getDataAssesmen = await useApi().get(`emr/get-asesmen-ranap?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&roles=${userLogin.kelompokUser.kelompokUser}`);
    if (getDataAssesmen.askep) {
      // console.log('data xxx', datax);
      if (userLogin.kelompokUser.kelompokUser.toUpperCase().indexOf('DOKTER') > -1) {
        input.value.perlukontrol = getDataAssesmen.asmed?.perlukontrol
        input.value.riwayatkeluar = getDataAssesmen.asmed?.riwayatkeluar
        input.value.statuskeluar = getDataAssesmen.asmed?.statuskeluar
        datax += getDataAssesmen.asmed?.anamnesis;
      } else {
        datax += getDataAssesmen.askep.TAKeluhanUtama ? `Keluhan Utama : ${getDataAssesmen.askep.TAKeluhanUtama}\n` : 'Keluhan Utama : -\n'
        datax += getDataAssesmen.askep.TARiwayatPenyakitSekarang ? `Riwayat Penyakit Sekarang : ${getDataAssesmen.askep.TARiwayatPenyakitSekarang}\n` : 'Riwayat Penyakit Sekarang : -\n'
        datax += getDataAssesmen.askep.TARiwayatPenyakitDahulu ? `Riwayat Penyakit Dahulu : ${getDataAssesmen.askep.TARiwayatPenyakitDahulu}\n` : 'Riwayat Penyakit Dahulu : -\n'
        datax += getDataAssesmen.askep.TARiwayatPenyakitPengobatan ? `Riwayat Pengobatan : ${getDataAssesmen.askep.TARiwayatPenyakitPengobatan}\n` : 'Riwayat Pengobatan : -\n'
        datax += getDataAssesmen.askep.TARIwayatPenyakitKeluarga ? `Riwayat Penyakit Keluarga : ${getDataAssesmen.askep.TARIwayatPenyakitKeluarga}\n` : 'Riwayat Penyakit Keluarga : -\n'
      }

    }
    strforO.value = data;
    strforS.value = datax;
    for (let x = 0; x < input.value.details.length; x++) {
      const element = input.value.details[x];
      input.value.details[x].O = data != '' ? `${data}` : ''
      input.value.details[x].S = datax != '' ? `${datax}` : ''
      // input.value.statuskeluar =
    }
    setAutoFill();

    let urlGetRiwayat = `/emr/get-emr-cppt?nocmfk=${ID_PASIEN}&norec_apd=${item.NOREC_APD}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`;
    const response = await useApi().get(urlGetRiwayat)
    isloadingLAMPAU.value = false
    if (response.length) {
      if (response[0].details.length == 0) {
        response[0].details = input.value.details;
      } else {
        if (response[0].details[0].flag == undefined) {
          response[0].details = input.value.details;
        }
      }

      let perawat = []
      let dokter = []
      isAfterSave.value = true
      let findMissing = response[0].details.filter((dcppt) => {
        return dcppt.flag == kelompokUser;
      });
      let findForMissing = input.value.details.filter((icppt) => {
        return icppt.flag == kelompokUser;
      });

      if (findMissing.length == 0) {
        // let glength = input.value.details[0]
        response[0].details.push(findForMissing[0]);
      }

      if (kelompokUser.includes("perawat")) {

        for (let x = 0; x < response[0].details.length; x++) {
          const element = response[0].details[x];
          if (element.flag == 'perawat') {
            perawat.push(element)
          }
          if (element.flag == 'dokter') {
            dokter.push(element)
          }

          element.tgl = new Date(element.tgl);
          element.tglVerifikasi = new Date(element.tglVerifikasi);
          if (element.dpjpRawatBersama == undefined) {
            element.dpjpRawatBersama = [{
              id: null
            }]
          }

          dokter.forEach(item => {
            if (item.no == element.no) {
              if (!item.S) {
                item.S = element.S
              }
              if (!item.O) {
                item.O = element.O
              }
              if (!item.P) {
                item.P = element.P
              }
            }
          });

          if (element.flag == 'perawat') {
            dokter.forEach(item => {
              if (item.no == element.no) {
                if (!item.S) {
                  item.S = element.S
                }
                if (!item.O) {
                  item.O = element.O
                }
                if (!item.P) {
                  item.P = element.P
                }
              }
            });
            if (element.tujuanKep == undefined) {
              element.tujuanKep = [{
                no: 1
              }]
            }
          }
        }
        input.value = response[0] //set ke inputan


        for (let z = 0; z < response.length; z++) {
          panjangsoapbaru.push(response[z])
        }

        setValueDisabled()
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      }

      input.value = response[0]
      if (kelompokUser.toUpperCase().indexOf('DOKTER') > -1) {
        input.value.perlukontrol = getDataAssesmen.asmed?.perlukontrol
        input.value.riwayatkeluar = getDataAssesmen.asmed?.riwayatkeluar
        input.value.statuskeluar = getDataAssesmen.asmed?.statuskeluar
      }
      if (ttv) {
        input.value.tekananDarah = ttv.tekananDarah ?? '';
        input.value.nadi = ttv.nadi ?? '';
        input.value.nafas = ttv.pernapasan ?? ''
        input.value.celcius = ttv.suhu ?? ''
        input.value.sao2 = ttv.SPO2 ?? ''
        input.value.beratBadan = ttv.beratBadan ?? ''
        input.value.tinggiBadan = ttv.tinggiBadan ?? ''
        input.value.gcse = ttv.GCSe ?? ''
        input.value.gcsv = ttv.GCSv ?? ''
        input.value.gcsm = ttv.GCSm ?? ''
      }
      // if(response)
      riwayatDataCPPT.value = response[0]
      for (let z = 0; z < response.length; z++) {
        panjangsoapbaru.push(response[z])
      }

      await addNewItem({ flag: kelompokUser })
      input.value.details = [input.value.details[0]]

      setValueDisabled()
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
    }

    loadRiwayatOld();
    isLoading.value = false;
  } else if (props.registrasi.namaruangan.toUpperCase().indexOf('KEDOKTERAN NUKLIR') > -1 && kelompokUser.toUpperCase().indexOf('PERAWAT') > -1) {
    let data = ''
    let datax = ''
    let datas = ''
    let datao = ''

    let dataCollection = 'AsesmenAwalKeperawatanPasienRawatJalanNurse';
    let fieldCollection = "keluhanutama,riwayatpenyakit,riwayatpenyakitdahulu,riwayatpenyakitkeluarga,riwayatpengobatan,isalergi,riwayatalergi,tekananDarahObgyn,nadiObgyn,nafasObgyn,celciusObgyn,sao2Obgyn,beratbadanObgyn,tinggibadanObgyn,gcse,gcsv,gcsm,keadaanumumobgyn,nyeriakut,Bersihan,Risiko,Risiko Cairan,Kurang pengetahuan,Ansietas b/d,kulit,berlebihan,kesiapan status,kognitif,aktivitas,usus,steroid,Pemantauan glukosa darah tidak adekuat,termoregulasi,gangguan fungsi,gangguan keras,gangguan lunak,gangguan,gangguan persepsi,sulit penglihatan,kesehatan,lainnya1,istirahatkan,berikaninfo,bantupasien,observasi,ajarkan,monitor,posisikan,latihanbatuk,chest,berikie,latihrentang,edukasi,kajidokumentasi,sarankan,imunisasi,dukungan,kontrol,kaji,ajarkanteknik,identifikasi,cemas,prosedur,dekatipasien,dengarkan"
    let fieldCollection2 = 'tekananDarahObgyn,nafasObgyn,keadaanumumobgyn,keadaanumum,celciusObgyn,nadiObgyn,sao2Obgyn,gcse,gcsv,gcsm,kebpilihanallo,keluhanutama,riwayatpenyakit,riwayatpenyakitdahulu,riwayatpengobatan,riwayatpenyakitkeluarga,riwayatalergi,beratbadanObgyn,tinggibadanObgyn,isalergi,riwayatalergi'
    const data_NS = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD +
      `&collection=${dataCollection}` +
      `&field=${fieldCollection2}`
    )
    const data_AMI = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN +
      `&collection=AsesmenAwalKeperawatanPasienRawatJalan` +
      `&field=${fieldCollection}`
    )

    if (data_NS) {
      datao += data_NS.celciusObgyn ? `Suhu : ${data_NS.celciusObgyn} °C\n` : ''
      datao += data_NS.nadiObgyn ? `Nadi : ${data_NS.nadiObgyn} x/mnt\n` : ''
      datao += data_NS.nafasObgyn ? `Pernafasan : ${data_NS.nafasObgyn} x/mnt\n` : ''
      datao += data_NS.tekananDarahObgyn ? `Tekanan Darah : ${data_NS.tekananDarahObgyn} mmHg\n` : ''
      datao += data_NS.tinggibadanObgyn ? `Tinggi Badan : ${data_NS.tinggibadanObgyn} Cm\n` : ''
      datao += data_NS.beratbadanObgyn ? `Berat Badan : ${data_NS.beratbadanObgyn} Kg\n` : ''
      datao += data_NS.sao2Obgyn ? `SPO2 : ${data_NS.sao2Obgyn} %\n` : ''
      datao += data_NS.gcse ? `GCS : E ${data_NS.gcse} V ${data_NS.gcsv} M ${data_NS.gcsm} \n` : ''

      input.value.tekananDarah = data_NS.tekananDarahObgyn ?? '';
      input.value.nadi = data_NS.nadiObgyn ?? '';
      input.value.nafas = data_NS.nafasObgyn ?? ''
      input.value.celcius = data_NS.celciusObgyn ?? ''
      input.value.sao2 = data_NS.sao2Obgyn ?? ''
      input.value.beratBadan = data_NS.beratbadanObgyn ?? ''
      input.value.tinggiBadan = data_NS.tinggibadanObgyn ?? ''
      input.value.gcse = data_NS.gcse ?? ''
      input.value.gcsv = data_NS.gcsv ?? ''
      input.value.gcsm = data_NS.gcsm ?? ''
      input.value.keadaanumumobgyn = data_NS.keadaanumumobgyn ?? ''

      datas += data_NS.keluhanutama ? `Keluhan Utama : ${data_NS.keluhanutama}\n` : 'Keluhan Utama : -\n'
      datas += data_NS.riwayatpenyakit ? `Riwayat Penyakit Sekarang : ${data_NS.riwayatpenyakit}\n` : 'Riwayat Penyakit Sekarang : -\n'
      datas += data_NS.riwayatpenyakitdahulu ? `Riwayat Penyakit Dahulu : ${data_NS.riwayatpenyakitdahulu}\n` : 'Riwayat Penyakit Dahulu : -\n'
      datas += data_NS.riwayatpengobatan ? `Riwayat Pengobatan : ${data_NS.riwayatpengobatan}\n` : 'Riwayat Pengobatan : -\n'
      datas += data_NS.riwayatpenyakitkeluarga ? `Riwayat Penyakit Keluarga : ${data_NS.riwayatpenyakitkeluarga}\n` : 'Riwayat Penyakit Keluarga : -\n'
      datas += data_NS.isalergi ? `Alergi : ${data_NS.isalergi} ${data_NS.isalergi == 'YA' ? data_NS.riwayatalergi : ''}\n` : 'Alergi : -\n'
    }
    if (data_AMI) {
      data += data_AMI.nyeriakut ? 'Nyeri akut b/d kondisi fisik\n' : '';
      data += data_AMI.Bersihan ? 'Bersihan jalan nafas tidak efektif b/d alergi jalan nafas, adanya eksudat di jalan nafas / sekresi tertahan\n' : '';
      data += data_AMI.Risiko ? 'Risiko / Penurunan curah jantung b/d anomaly jantung / peningkatan beban kerja ventrikel\n' : '';
      data += data_AMI['Risiko Cairan'] ? 'Risiko / Kekurangan Volume Cairan b/d kehilangan volume cairan secara aktif\n' : '';
      data += data_AMI['Kurang pengetahuan'] ? 'Kurang pengetahuan tentang penyakit, rencana tindakan dan pengobatan b/d kurang terpanjannya informasi\n' : '';
      data += data_AMI['Ansietas b/d'] ? 'Ansietas b/d krisis situasi, kebutuhan yang tidak terpenuhi\n' : '';
      data += data_AMI.kulit ? 'Risiko gangguan integritas kulit\n' : '';
      data += data_AMI.berlebihan ? 'Kelebihan volume cairan b/d asupan cairan berlebihan\n' : '';
      data += data_AMI['kesiapan status'] ? 'Kesiapan meningkatkan status kesehatan\n' : '';
      data += data_AMI.kognitif ? 'Ketidakefektifan pemeliharaan kesehatan b/d hambatan kognitif\n' : '';
      data += data_AMI.aktivitas ? 'Hambatan mobilitas fisik b/d intoleran aktivitas\n' : '';
      data += data_AMI.usus ? 'Diare akut b/d mal absorpsi, peningkatan motilitas usus\n' : '';
      data += data_AMI.steroid ? 'Nausea b/d biofisik, psikologis, pemberian kemoterapi, pemberian steroid\n' : '';
      data += data_AMI['Pemantauan glukosa darah tidak adekuat'] ? 'Risiko Ketidakstabilan kadar Glukosa Darah b/d Kurang pengetahuan tentang manajemen diabetes, Asupan diet, Pemantauan glukosa darah tidak adekuat\n' : '';
      data += data_AMI.termoregulasi ? 'Hipertemia b/d kekurangan cairan, proses infeksi, gangguan termoregulasi\n' : '';
      data += data_AMI['gangguan fungsi'] ? 'Gangguan fungsi gigi\n' : '';
      data += data_AMI['gangguan keras'] ? 'Gangguan jaringan keras gigi\n' : '';
      data += data_AMI['gangguan lunak'] ? 'Gangguan jaringan lunak dan pendukung gigi\n' : '';
      data += data_AMI.gangguan ? 'Gangguan estetika\n' : '';
      data += data_AMI['gangguan persepsi'] ? 'Gangguan persepsi sensori\n' : '';
      data += data_AMI['sulit penglihatan'] ? 'Risiko jatuh b/d riwayat terjatuh / usia lebih dari 65 th / menggunakan alat bantu (walker, tongkat, kursi roda) / sulit penglihatan\n' : '';
      data += data_AMI.kesehatan ? 'Pola pemberian ASI tidak efektif b/d ketidakefektifan pemeliharaan kesehatan\n' : '';
      data += data_AMI.lainnya1 ? 'Lainnya\n' : '';

      datax += data_AMI.istirahatkan ? 'Istirahatkan pasien pada posisi yang nyaman dalam batas yang ditoleransi oleh pasien\n' : '';
      datax += data_AMI.berikaninfo ? 'Berikan informasi tentang nyeri meliputi penyebab, lamanya nyeri berlangsung, faktor yang dapat memperburuk atau meredakan nyeri\n' : '';
      datax += data_AMI.bantupasien ? 'Bantu pasien mengidentifikasi tindakan memenuhi kebutuhan rasa nyaman yang telah berhasil dilakukan oleh pasien\n' : '';
      datax += data_AMI.observasi ? 'Observasi tanda-tanda vital\n' : '';
      datax += data_AMI.ajarkan ? 'Ajarkan teknik nonfarmakologis seperti : Relaksasi napas dalam/otot progesif, Distraksi, kompres hangat/dingin, terapi music, massage punggung\n' : '';
      datax += data_AMI.monitor ? 'Monitor Frekuensi nafas pasien/ status oksigen pasien\n' : '';
      datax += data_AMI.posisikan ? 'Posisikan pasien untuk memaksimalkan ventilasi (head up/semifowler)\n' : '';
      datax += data_AMI.latihanbatuk ? 'Latihan teknik batuk efektif\n' : '';
      datax += data_AMI.chest ? 'Lakukan chest fisioterapi sesuai indikasi/bila perlu\n' : '';
      datax += data_AMI.berikie ? 'Beri KIE tentang tanda-tanda penurunan curah jantung\n' : '';
      datax += data_AMI.latihrentang ? 'Latih rentang pergerakan aktif/pasif untuk memperbaiki kekuatan dan daya tahan otot\n' : '';
      datax += data_AMI.edukasi ? 'Edukasi untuk memberikan kompres dengan air biasa/ hangat\n' : '';
      datax += data_AMI.kajidokumentasi ? 'Kaji dan dokumentasi frekuensi, warna, konsistensi, jumlah (ukuran) feces, turgor kulit dan kondisi mukosa mulut sebagai indikator dehidrasi\n' : '';
      datax += data_AMI.sarankan ? 'Sarankan menghindari makanan yang mengandung lactose, makan makanan yang rendah serat, tinggi kalori dan tinggi protein\n' : '';
      datax += data_AMI.imunisasi ? 'Lakukan manajemen imunisasi/vaksinasi\n' : '';
      datax += data_AMI.dukungan ? 'Beri dudkungan dalam mengambil keputusan\n' : '';
      datax += data_AMI.kontrol ? 'Edukasi dan sarankan untuk kontrol sebagai upaya meningkatkan status kesehatan pasien\n' : '';
      datax += data_AMI.kaji ? 'Kaji integritas kulit\n' : '';
      datax += data_AMI.ajarkanteknik ? 'Ajarkan teknik nonfarmakologis seperti : Relaksasi napas dalam/ otot progresif, Distraksi, Kompres hangat/ dingin, Terapi music, Massage punggung\n' : '';
      datax += data_AMI.identifikasi ? 'Identifikasi level cemas pada pasien\n' : '';
      datax += data_AMI.cemas ? 'Berikan pengetahuan yang adekuat tentang penyakit yang diderita untuk mengurangi cemas\n' : '';
      datax += data_AMI.prosedur ? 'Jelaskan semua posedur, termasuk beberapa pengalaman sebelum prosedur\n' : '';
      datax += data_AMI.dekatipasien ? 'Dekati pasien untuk memberikan rasa aman dan mengurangi rasa takut\n' : '';
      datax += data_AMI.dengarkan ? 'Dengarkan pasien dengan penuh perhatian\n' : '';
    }
    strforS.value = datas;
    strforO.value = datao;
    strforA.value = data;
    strforP.value = datax;
    for (let x = 0; x < input.value.details.length; x++) {
      const element = input.value.details[x];
      input.value.details[x].S = datas != '' ? `${datas}` : ''
      input.value.details[x].O = datao != '' ? `${datao}` : ''
      input.value.details[x].A = data != '' ? `${data}` : ''
      input.value.details[x].P = datax != '' ? `${datax}` : ''
    }
    setAutoFill();

    let urlGetRiwayat = `/emr/get-emr-cppt?nocmfk=${ID_PASIEN}&norec_apd=${item.NOREC_APD}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`;
    const response = await useApi().get(urlGetRiwayat)
    isloadingLAMPAU.value = false
    if (response.length) {
      if (response[0].details.length == 0) {
        response[0].details = input.value.details;
      } else {
        if (response[0].details[0].flag == undefined) {
          response[0].details = input.value.details;
        }
      }

      let perawat = []
      let dokter = []
      isAfterSave.value = true
      let findMissing = response[0].details.filter((dcppt) => {
        return dcppt.flag == kelompokUser;
      });
      let findForMissing = input.value.details.filter((icppt) => {
        return icppt.flag == kelompokUser;
      });

      if (findMissing.length == 0) {
        // let glength = input.value.details[0]
        response[0].details.push(findForMissing[0]);
      }

      if (kelompokUser.includes("perawat")) {

        for (let x = 0; x < response[0].details.length; x++) {
          const element = response[0].details[x];
          if (element.flag == 'perawat') {
            perawat.push(element)
          }
          if (element.flag == 'dokter') {
            dokter.push(element)
          }

          element.tgl = new Date(element.tgl);
          element.tglVerifikasi = new Date(element.tglVerifikasi);
          if (element.dpjpRawatBersama == undefined) {
            element.dpjpRawatBersama = [{
              id: null
            }]
          }

          dokter.forEach(item => {
            if (item.no == element.no) {
              if (!item.S) {
                item.S = element.S
              }
              if (!item.O) {
                item.O = element.O
              }
              if (!item.P) {
                item.P = element.P
              }
            }
          });

          if (element.flag == 'perawat') {
            dokter.forEach(item => {
              if (item.no == element.no) {
                if (!item.S) {
                  item.S = element.S
                }
                if (!item.O) {
                  item.O = element.O
                }
                if (!item.P) {
                  item.P = element.P
                }
              }
            });
            if (element.tujuanKep == undefined) {
              element.tujuanKep = [{
                no: 1
              }]
            }
          }
        }
        input.value = response[0] //set ke inputan


        for (let z = 0; z < response.length; z++) {
          panjangsoapbaru.push(response[z])
        }

        setValueDisabled()
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      }

      input.value = response[0]
      input.value.tekananDarah = data_NS.tekananDarahObgyn ?? '';
      input.value.nadi = data_NS.nadiObgyn ?? '';
      input.value.nafas = data_NS.nafasObgyn ?? ''
      input.value.celcius = data_NS.celciusObgyn ?? ''
      input.value.sao2 = data_NS.sao2Obgyn ?? ''
      input.value.beratBadan = data_NS.beratbadanObgyn ?? ''
      input.value.tinggiBadan = data_NS.tinggibadanObgyn ?? ''
      input.value.gcse = data_NS.gcse ?? ''
      input.value.gcsv = data_NS.gcsv ?? ''
      input.value.gcsm = data_NS.gcsm ?? ''
      input.value.keadaanumumobgyn = data_NS.keadaanumumobgyn ?? ''
      if (kelompokUser.toUpperCase().indexOf('DOKTER') > -1) {
        if (!response[0].perlukontrol) {
          input.value.perlukontrol = data_AMI.perlukontrol ?? ''
        }
        if (!response[0].riwayatkeluar) {
          input.value.riwayatkeluar = data_AMI.riwayatkeluar ?? ''
        }
        if (!response[0].statuskeluar) {
          input.value.statuskeluar = data_AMI.statuskeluar ?? ''
        }
      }
      riwayatDataCPPT.value = response[0]
      for (let z = 0; z < response.length; z++) {
        panjangsoapbaru.push(response[z])
      }

      await addNewItem({ flag: kelompokUser })
      input.value.details = [input.value.details[0]]

      setValueDisabled()
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
    }

    loadRiwayatOld();
    isLoading.value = false;
  } else if (props.registrasi.namaruangan.toUpperCase().indexOf('RADIOTERAPI') > -1) {
    let data = ''
    let datax = ''

    let dataCollection = 'CatatanKegiatanRadioterapi';
    let fieldCollection = 'details,tekananDarah,nadi,nafas,celcius,sao2,gcse,gcsv,gcsm,keadaanumumobgyn,tinggiBadan,beratBadan'
    const data_CK = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD +
      `&collection=${dataCollection}` +
      `&field=${fieldCollection}` +
      `&ruangan=UNIT RADIOTERAPI` +
      `&orderBy=created_at`
    )
    if (data_CK) {
      let dt = data_CK.details[0]
      datax += dt.keluhan ? `Keluhan : ${dt.keluhan}\n` : 'Keluhan : -\n'

      input.value.tekananDarah = data_CK.tekananDarah
      input.value.nadi = data_CK.nadi
      input.value.nafas = data_CK.nafas
      input.value.celcius = data_CK.celcius
      input.value.sao2 = data_CK.sao2
      input.value.beratBadan = data_CK.beratBadan
      input.value.tinggiBadan = data_CK.tinggiBadan
      input.value.gcse = data_CK.gcse
      input.value.gcsv = data_CK.gcsv
      input.value.gcsm = data_CK.gcsm
      // input.value.perlukontrol = data_CK.perlukontrol
      // input.value.riwayatkeluar = data_CK.riwayatkeluar
      // input.value.statuskeluar = data_CK.statuskeluar
      input.value.keadaanumumobgyn = data_CK.keadaanumumobgyn
    }
    strforS.value = datax;
    for (let x = 0; x < input.value.details.length; x++) {
      const element = input.value.details[x];
      input.value.details[x].S = datax != '' ? `${datax}` : ''
    }
    setAutoFill();

    let urlGetRiwayat = `/emr/get-emr-cppt?nocmfk=${ID_PASIEN}&norec_apd=${item.NOREC_APD}&norec_pd=${item.NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`;
    const response = await useApi().get(urlGetRiwayat)
    isloadingLAMPAU.value = false
    if (response.length) {
      if (response[0].details.length == 0) {
        response[0].details = input.value.details;
      } else {
        if (response[0].details[0].flag == undefined) {
          response[0].details = input.value.details;
        }
      }

      let perawat = []
      let dokter = []
      isAfterSave.value = true
      let findMissing = response[0].details.filter((dcppt) => {
        return dcppt.flag == kelompokUser;
      });
      let findForMissing = input.value.details.filter((icppt) => {
        return icppt.flag == kelompokUser;
      });

      if (findMissing.length == 0) {
        // let glength = input.value.details[0]
        response[0].details.push(findForMissing[0]);
      }

      if (kelompokUser.includes("perawat")) {

        for (let x = 0; x < response[0].details.length; x++) {
          const element = response[0].details[x];
          if (element.flag == 'perawat') {
            perawat.push(element)
          }
          if (element.flag == 'dokter') {
            dokter.push(element)
          }

          element.tgl = new Date(element.tgl);
          element.tglVerifikasi = new Date(element.tglVerifikasi);
          if (element.dpjpRawatBersama == undefined) {
            element.dpjpRawatBersama = [{
              id: null
            }]
          }

          dokter.forEach(item => {
            if (item.no == element.no) {
              if (!item.S) {
                item.S = element.S
              }
              if (!item.O) {
                item.O = element.O
              }
              if (!item.P) {
                item.P = element.P
              }
            }
          });

          if (element.flag == 'perawat') {
            dokter.forEach(item => {
              if (item.no == element.no) {
                if (!item.S) {
                  item.S = element.S
                }
                if (!item.O) {
                  item.O = element.O
                }
                if (!item.P) {
                  item.P = element.P
                }
              }
            });
            if (element.tujuanKep == undefined) {
              element.tujuanKep = [{
                no: 1
              }]
            }
          }
        }
        input.value = response[0] //set ke inputan


        for (let z = 0; z < response.length; z++) {
          panjangsoapbaru.push(response[z])
        }

        setValueDisabled()
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      }

      input.value = response[0]
      input.value.tekananDarah = response[0].tekananDarah != null ? response[0].tekananDarah : data_CK.tekananDarah;
      input.value.nadi = response[0].nadi != null ? response[0].nadi : data_CK.nadi;
      input.value.nafas = response[0].nafas != null ? response[0].nafas : data_CK.nafas;
      input.value.celcius = response[0].celcius != null ? response[0].celcius : data_CK.celcius;
      input.value.sao2 = response[0].sao2 != null ? response[0].sao2 : data_CK.sao2;
      input.value.beratBadan = response[0] != null ? response[0].beratBadan : (data_CK.beratBadan ? data_CK.beratBadan : null) ?? null;
      input.value.tinggiBadan = response[0] != null ? response[0].tinggiBadan : data_CK.tinggiBadan;
      input.value.gcse = response[0] != null ? response[0].gcse : data_CK.gcse;
      input.value.gcsv = response[0] != null ? response[0].gcsv : data_CK.gcsv;
      input.value.gcsm = response[0] != null ? response[0].gcsm : data_CK.gcsm;
      input.value.keadaanumumobgyn = response[0].keadaanumumobgyn != null ? response[0].keadaanumumobgyn : data_CK.keadaanumumobgyn;
      // if (kelompokUser.toUpperCase().indexOf('DOKTER') > -1) {
      //   if (!response[0].perlukontrol) {
      //     input.value.perlukontrol = data_AMI.perlukontrol ?? ''
      //   }
      //   if (!response[0].riwayatkeluar) {
      //     input.value.riwayatkeluar = data_AMI.riwayatkeluar ?? ''
      //   }
      //   if (!response[0].statuskeluar) {
      //     input.value.statuskeluar = data_AMI.statuskeluar ?? ''
      //   }
      // }
      // if(response)
      riwayatDataCPPT.value = response[0]
      for (let z = 0; z < response.length; z++) {
        panjangsoapbaru.push(response[z])
      }

      await addNewItem({ flag: kelompokUser })
      input.value.details = [input.value.details[0]]

      setValueDisabled()
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
    }
    loadRiwayatOld();
    isLoading.value = false;
  } else {
    await useApi().get(`/emr/check-asesmen-cppt?nocmfk=${ID_PASIEN}`).then(async (res) => {
      let query: any = {}
      isLoading.value = false;
      if (res.status == 201 && res.type == 'keperawatan' && props.registrasi.namaruangan.trim() != 'IGD' && props.registrasi.namaruangan.trim() != 'RUANG VK - IGD') {
        passCPPT.value.title = 'Nurse Station'
        passCPPT.value.isneedasesmen = true
        passCPPT.value.tab = 'Nurse Station'
        passCPPT.value.subtitle = res.message
        if (props.registrasi.namaruangan.toUpperCase().indexOf('OBGYN') > -1) {
          passCPPT.value.link = `module-emr-profile-pasien-page-emr-asesmen-awal-kebidanan-pasien-rawat-jalan-nurse`
        } else {
          passCPPT.value.link = `module-emr-profile-pasien-page-emr-asesmen-awal-keperawatan-pasien-rawat-jalan-nurse`
        }
      } else if (res.status == 201 && res.type == 'medis') {
        passCPPT.value.title = 'Assesmen Medis'
        passCPPT.value.tab = 'Assesmen Medis'
        passCPPT.value.subtitle = res.message
        passCPPT.value.isneedasesmen = true
        passCPPT.value.link = `module-emr-profile-pasien-page-emr-asesmen-medis-rawat-jalan`
        passCPPT.value.result = res.result
      } else if (res.status == 201 && res.type == 'askep') {
        passCPPT.value.title = 'Assesmen Keperawatan'
        passCPPT.value.tab = 'Assesmen Keperawatan'
        passCPPT.value.isneedasesmen = true
        passCPPT.value.subtitle = res.message
        if (props.registrasi.namaruangan.toUpperCase().indexOf('OBGYN') > -1) {
          passCPPT.value.link = `module-emr-profile-pasien-page-emr-asesmen-awal-kebidanan-pasien-rawat-jalan`
        } else {
          passCPPT.value.link = `module-emr-profile-pasien-page-emr-asesmen-awal-keperawatan-pasien-rawat-jalan`
        }
        passCPPT.value.result = res.result
      } else {
        passCPPT.value.result = res.result
        passCPPT.value.isneedasesmen = false
        let data = ''
        let datax = ''

        if (res.result.from && res.result.from == 'medis_dokter') {
          data += res.result.celciusObgyn ? `Suhu : ${res.result.celciusObgyn} °C\n` : ''
          data += res.result.nadiObgyn ? `Nadi : ${res.result.nadiObgyn} x/mnt\n` : ''
          data += res.result.nafasObgyn ? `Pernafasan : ${res.result.nafasObgyn} x/mnt\n` : ''
          data += res.result.tekananDarahObgyn ? `Tekanan Darah : ${res.result.tekananDarahObgyn} mmHg\n` : ''
          data += res.result.tinggibadanObgyn ? `Tinggi Badan : ${res.result.tinggibadanObgyn} Cm\n` : ''
          data += res.result.beratbadanObgyn ? `Berat Badan : ${res.result.beratbadanObgyn} Kg\n` : ''
          data += res.result.sao2Obgyn ? `SPO2 : ${res.result.sao2Obgyn} %\n` : ''
          data += res.result.IMT ? `IMT : ${res.result.IMT}\n` : ''
          data += res.result.lokalisNonTrauma ? `Status lokalis : ${res.result.lokalisNonTrauma}\n` : ''
        } else {
          data += res.result.celciusObgyn ? `Suhu : ${res.result.celciusObgyn} °C\n` : ''
          data += res.result.nadiObgyn ? `Nadi : ${res.result.nadiObgyn} x/mnt\n` : ''
          data += res.result.nafasObgyn ? `Pernafasan : ${res.result.nafasObgyn} x/mnt\n` : ''
          data += res.result.tekananDarahObgyn ? `Tekanan Darah : ${res.result.tekananDarahObgyn} mmHg\n` : ''
          data += res.result.tinggibadanObgyn ? `Tinggi Badan : ${res.result.tinggibadanObgyn} Cm\n` : ''
          data += res.result.beratbadanObgyn ? `Berat Badan : ${res.result.beratbadanObgyn} Kg\n` : ''
          data += res.result.sao2Obgyn ? `SPO2 : ${res.result.sao2Obgyn} %\n` : ''
          data += res.result.IMT ? `IMT : ${res.result.IMT}\n` : ''
        }


        if (res.result.from && res.result.from == 'medis_dokter') {
          datax += res.result.anamnesis;
        } else {
          datax += res.result.keluhanutama ? `Keluhan Utama : ${res.result.keluhanutama}\n` : 'Keluhan Utama : -\n'
          datax += res.result.riwayatpenyakit ? `Riwayat Penyakit Sekarang : ${res.result.riwayatpenyakit}\n` : 'Riwayat Penyakit Sekarang : -\n'
          datax += res.result.riwayatpenyakitdahulu ? `Riwayat Penyakit Dahulu : ${res.result.riwayatpenyakitdahulu}\n` : 'Riwayat Penyakit Dahulu : -\n'
          datax += res.result.riwayatpengobatan ? `Riwayat Pengobatan : ${res.result.riwayatpengobatan}\n` : 'Riwayat Pengobatan : -\n'
          datax += res.result.riwayatpenyakitkeluarga ? `Riwayat Penyakit Keluarga : ${res.result.riwayatpenyakitkeluarga}\n` : 'Riwayat Penyakit Keluarga : -\n'
          datax += res.result.isalergi ? `Alergi : ${res.result.isalergi} ${res.result.riwayatalergi}\n` : 'Alergi : -\n'
        }

        input.value.tekananDarah = res.result.tekananDarahObgyn
        input.value.nadi = res.result.nadiObgyn
        input.value.nafas = res.result.nafasObgyn
        input.value.celcius = res.result.celciusObgyn
        input.value.sao2 = res.result.sao2Obgyn
        input.value.beratBadan = res.result.beratbadanObgyn
        input.value.tinggiBadan = res.result.tinggibadanObgyn
        input.value.gcse = res.result.gcse
        input.value.gcsv = res.result.gcsv
        input.value.gcsm = res.result.gcsm
        input.value.perlukontrol = res.result.perlukontrol
        input.value.riwayatkeluar = res.result.riwayatkeluar
        input.value.statuskeluar = res.result.statuskeluar
        input.value.keadaanumumobgyn = res.result.keadaanumumobgyn

        strforO.value = data;
        strforS.value = datax;
        for (let x = 0; x < input.value.details.length; x++) {
          const element = input.value.details[x];
          input.value.details[x].O = data != '' ? `${data}` : ''
          input.value.details[x].S = datax != '' ? `${datax}` : ''
        }
        setAutoFill();

        let urlGetRiwayat = `/emr/get-emr-cppt?nocmfk=${ID_PASIEN}&norec_apd=${item.NOREC_APD}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`;
        const response = await useApi().get(urlGetRiwayat)
        isloadingLAMPAU.value = false
        if (response.length > 0) {
          if (response[0].details.length == 0) {
            response[0].details = input.value.details;
          } else {
            if (response[0].details[0].flag == undefined) {
              response[0].details = input.value.details;
            }
          }

          let perawat = []
          let dokter = []
          isAfterSave.value = true
          let findMissing = response[0].details.filter((dcppt) => {
            return dcppt.flag == kelompokUser;
          });
          let findForMissing = input.value.details.filter((icppt) => {
            return icppt.flag == kelompokUser;
          });

          if (findMissing.length == 0) {
            // let glength = input.value.details[0]
            response[0].details.push(findForMissing[0]);
          }

          if (kelompokUser.includes("perawat")) {

            for (let x = 0; x < response[0].details.length; x++) {
              const element = response[0].details[x];
              if (element.flag == 'perawat') {
                perawat.push(element)
              }
              if (element.flag == 'dokter') {
                dokter.push(element)
              }

              element.tgl = new Date(element.tgl);
              element.tglVerifikasi = new Date(element.tglVerifikasi);
              if (element.dpjpRawatBersama == undefined) {
                element.dpjpRawatBersama = [{
                  id: null
                }]
              }

              dokter.forEach(item => {
                if (item.no == element.no) {
                  if (!item.S) {
                    item.S = element.S
                  }
                  if (!item.O) {
                    item.O = element.O
                  }
                  if (!item.P) {
                    item.P = element.P
                  }
                }
              });

              if (element.flag == 'perawat') {
                dokter.forEach(item => {
                  if (item.no == element.no) {
                    if (!item.S) {
                      item.S = element.S
                    }
                    if (!item.O) {
                      item.O = element.O
                    }
                    if (!item.P) {
                      item.P = element.P
                    }
                  }
                });
                if (element.tujuanKep == undefined) {
                  element.tujuanKep = [{
                    no: 1
                  }]
                }
              }
            }
            input.value = response[0] //set ke inputan


            for (let z = 0; z < response.length; z++) {
              panjangsoapbaru.push(response[z])
            }

            setValueDisabled()
            if (NOREC_EMRPASIEN.value == '') {
              NOREC_EMRPASIEN.value = response[0].emrpasienfk
            }
          }

          input.value = response[0]
          if (!response[0]?.perlukontrol) {
            input.value.perlukontrol = res.result.perlukontrol
          }
          if (!response[0]?.riwayatkeluar) {
            input.value.riwayatkeluar = res.result.riwayatkeluar
          }
          if (!response[0]?.statuskeluar) {
            input.value.statuskeluar = res.result.statuskeluar
          }
          if (!response[0]?.keadaanumumobgyn) {
            input.value.keadaanumumobgyn = res.result.keadaanumumobgyn
          }

          input.value.tekananDarah = res.result.tekananDarahObgyn
          input.value.nadi = res.result.nadiObgyn
          input.value.nafas = res.result.nafasObgyn
          input.value.celcius = res.result.celciusObgyn
          input.value.sao2 = res.result.sao2Obgyn
          input.value.beratBadan = res.result.beratbadanObgyn
          input.value.tinggiBadan = res.result.tinggibadanObgyn
          input.value.gcse = res.result.gcse
          input.value.gcsv = res.result.gcsv
          input.value.gcsm = res.result.gcsm
          // if(response)
          riwayatDataCPPT.value = response[0]
          for (let z = 0; z < response.length; z++) {
            panjangsoapbaru.push(response[z])
          }

          await addNewItem({ flag: kelompokUser })
          input.value.details = [input.value.details[0]]

          setValueDisabled()
          if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
          }
        }

        loadRiwayatOld();
      }
    })
  }
}

async function getDataForHemo(res: any) {
  let data = ''
  let datax = ''

  data += res.result.celciusObgyn ? `Suhu : ${res.result.celciusObgyn} °C\n` : ''
  data += res.result.nadiObgyn ? `Nadi : ${res.result.nadiObgyn} x/mnt\n` : ''
  data += res.result.nafasObgyn ? `Pernafasan : ${res.result.nafasObgyn} x/mnt\n` : ''
  data += res.result.tekananDarahObgyn ? `Tekanan Darah : ${res.result.tekananDarahObgyn} mmHg\n` : ''
  data += res.result.tinggibadanObgyn ? `Tinggi Badan : ${res.result.tinggibadanObgyn} Cm\n` : ''
  data += res.result.beratbadanObgyn ? `Berat Badan : ${res.result.beratbadanObgyn} Kg\n` : ''
  data += res.result.sao2Obgyn ? `SPO2 : ${res.result.sao2Obgyn} %\n` : ''
  data += res.result.IMT ? `IMT : ${res.result.IMT}\n` : ''

  datax += res.result.keluhanutama ? `Keluhan Utama : ${res.result.keluhanutama}\n` : 'Keluhan Utama : -\n'
  datax += res.result.riwayatpenyakit ? `Riwayat Penyakit Sekarang : ${res.result.riwayatpenyakit}\n` : 'Riwayat Penyakit Sekarang : -\n'
  datax += res.result.riwayatpenyakitdahulu ? `Riwayat Penyakit Dahulu : ${res.result.riwayatpenyakitdahulu}\n` : 'Riwayat Penyakit Dahulu : -\n'
  datax += res.result.riwayatpengobatan ? `Riwayat Pengobatan : ${res.result.riwayatpengobatan}\n` : 'Riwayat Pengobatan : -\n'
  datax += res.result.riwayatpenyakitkeluarga ? `Riwayat Penyakit Keluarga : ${res.result.riwayatpenyakitkeluarga}\n` : 'Riwayat Penyakit Keluarga : -\n'
  datax += res.result.isalergi ? `Alergi : ${res.result.isalergi} ${res.result.riwayatalergi}\n` : 'Alergi : -\n'

  input.value.tekananDarah = res.result.tekananDarahObgyn
  input.value.nadi = res.result.nadiObgyn
  input.value.nafas = res.result.nafasObgyn
  input.value.celcius = res.result.celciusObgyn
  input.value.sao2 = res.result.sao2Obgyn
  input.value.beratBadan = res.result.beratbadanObgyn
  input.value.tinggiBadan = res.result.tinggibadanObgyn
  input.value.gcse = res.result.gcse
  input.value.gcsv = res.result.gcsv
  input.value.gcsm = res.result.gcsm
  input.value.perlukontrol = res.result.perlukontrol
  input.value.riwayatkeluar = res.result.riwayatkeluar
  input.value.statuskeluar = res.result.statuskeluar
  input.value.keadaanumumobgyn = res.result.keadaanumumobgyn


  strforO.value = data;
  strforS.value = datax;
  for (let x = 0; x < input.value.details.length; x++) {
    const element = input.value.details[x];
    input.value.details[x].O = data != '' ? `${data}` : ''
    input.value.details[x].S = datax != '' ? `${datax}` : ''
  }
  setAutoFill();

  let urlGetRiwayat = `/emr/get-emr-cppt?nocmfk=${ID_PASIEN}&norec_apd=${item.NOREC_APD}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`;
  const response = await useApi().get(urlGetRiwayat)
  isloadingLAMPAU.value = false
  if (response.length) {
    if (response[0].details.length == 0) {
      response[0].details = input.value.details;
    } else {
      if (response[0].details[0].flag == undefined) {
        response[0].details = input.value.details;
      }
    }

    let perawat = []
    let dokter = []
    isAfterSave.value = true
    let findMissing = response[0].details.filter((dcppt) => {
      return dcppt.flag == kelompokUser;
    });
    let findForMissing = input.value.details.filter((icppt) => {
      return icppt.flag == kelompokUser;
    });

    if (findMissing.length == 0) {
      // let glength = input.value.details[0]
      response[0].details.push(findForMissing[0]);
    }

    if (kelompokUser.includes("perawat")) {

      for (let x = 0; x < response[0].details.length; x++) {
        const element = response[0].details[x];
        if (element.flag == 'perawat') {
          perawat.push(element)
        }
        if (element.flag == 'dokter') {
          dokter.push(element)
        }

        element.tgl = new Date(element.tgl);
        element.tglVerifikasi = new Date(element.tglVerifikasi);
        if (element.dpjpRawatBersama == undefined) {
          element.dpjpRawatBersama = [{
            id: null
          }]
        }

        dokter.forEach(item => {
          if (item.no == element.no) {
            if (!item.S) {
              item.S = element.S
            }
            if (!item.O) {
              item.O = element.O
            }
            if (!item.P) {
              item.P = element.P
            }
          }
        });

        if (element.flag == 'perawat') {
          dokter.forEach(item => {
            if (item.no == element.no) {
              if (!item.S) {
                item.S = element.S
              }
              if (!item.O) {
                item.O = element.O
              }
              if (!item.P) {
                item.P = element.P
              }
            }
          });
          if (element.tujuanKep == undefined) {
            element.tujuanKep = [{
              no: 1
            }]
          }
        }
      }
      input.value = response[0] //set ke inputan


      for (let z = 0; z < response.length; z++) {
        panjangsoapbaru.push(response[z])
      }

      setValueDisabled()
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
    }

    input.value = response[0]
    if (!response[0].perlukontrol) {
      input.value.perlukontrol = res.result.perlukontrol
    }
    if (!response[0].riwayatkeluar) {
      input.value.riwayatkeluar = res.result.riwayatkeluar
    }
    if (!response[0].statuskeluar) {
      input.value.statuskeluar = res.result.statuskeluar
    }
    if (!response[0].keadaanumumobgyn) {
      input.value.keadaanumumobgyn = res.result.keadaanumumobgyn
    }
    // if(response)
    riwayatDataCPPT.value = response[0]
    for (let z = 0; z < response.length; z++) {
      panjangsoapbaru.push(response[z])
    }

    setValueDisabled()
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
  }

  loadRiwayatOld();
}

const needRedirect = () => {
  if (passCPPT.value.isneedasesmen) {
    console.log("PAS CPPT", passCPPT);
    H.cacheHelper().set('xxx_cache_menu_' + route.query.nocmfk, {
      'menu': passCPPT.value.link,
      'active': passCPPT.value.tab,
      'url_form': passCPPT.value.link,
      'collection': COLLECTION.value
    })
    setRoutingEMR(passCPPT.value.link)
  } else {
    console.log("CACHE HELPER", H.cacheHelper().get('xxx_cache_menu_' + route.query.nocmfk))
    H.cacheHelper().set('xxx_cache_menu_' + route.query.nocmfk, {
      'menu': props.FORM_URL,
      'active': props.FORM_NAME,
      'url_form': props.FORM_URL,
      'collection': COLLECTION.value
    })
    closeAlert()
  }
}

const setRoutingEMR = (form: any, norec_emr: any) => {
  let query: any = {}
  let params: any = {}
  isLoading.value = true;

  query = {
    nocmfk: ID_PASIEN,
    norec_pasien_daftar: NOREC_PD,
    norec_pd: NOREC_PD,
    norec_apd: props.registrasi.norec_apd,
    norec_emr: norec_emr ?? null,
    iscppt: true
  }
  console.log(query)
  console.log("FORM ROUTING", form);
  if (form.indexOf('index_tab') > -1) {
    params = {
      index_tabs: 1
    }
  }
  params = {
    FORM_NAME: "asesmen cenah"
  }
  router.push({
    name: form,
    query: query,
    params: params
  })
}

const editTemplate = async (dt: any) => {
  if (!dt) return;
  console.log("DT EDIT", dt);
  H.alert('info', 'Silahkan ubah data dan Simpan Template Kembali');
  // await addTemplate(dt);
  // input.value.namatemplate = null
  input.value = dt //set ke inputan
  isAlltemplate.value = false;
  idTemplateCPPT.value = dt.id;
  showModalTemplateFix.value = false;
  input.value.namatemplate = dt.namatemplate;
  console.log("ID Template", idTemplateCPPT)
  // showModalTemplateFix.value = false
}

const closeAlert = async () => {
  console.log("RUANGAN PASIEN : ", props.registrasi.namaruangan)
  isLoading.value = true;
  if (passCPPT.value.result) {
    let dataRes = passCPPT.value.result;
    console.log('data res post ranap', dataRes);
    let data = ''
    let datax = ''

    if (dataRes.from && dataRes.from == 'medis_dokter') {
      data += dataRes.celciusObgyn ? `Suhu : ${dataRes.celciusObgyn} °C\n` : ''
      data += dataRes.nadiObgyn ? `Nadi : ${dataRes.nadiObgyn} x/mnt\n` : ''
      data += dataRes.nafasObgyn ? `Pernafasan : ${dataRes.nafasObgyn} x/mnt\n` : ''
      data += dataRes.tekananDarahObgyn ? `Tekanan Darah : ${dataRes.tekananDarahObgyn} mmHg\n` : ''
      data += dataRes.tinggibadanObgyn ? `Tinggi Badan : ${dataRes.tinggibadanObgyn} Cm\n` : ''
      data += dataRes.beratbadanObgyn ? `Berat Badan : ${dataRes.beratbadanObgyn} Kg\n` : ''
      data += dataRes.sao2Obgyn ? `SPO2 : ${dataRes.sao2Obgyn} %\n` : ''
      data += dataRes.IMT ? `IMT : ${dataRes.IMT}\n` : ''
      data += dataRes.lokalisNonTrauma ? `Status lokalis : ${dataRes.lokalisNonTrauma}\n` : ''
    } else {
      data += dataRes.celciusObgyn ? `Suhu : ${dataRes.celciusObgyn} °C\n` : ''
      data += dataRes.nadiObgyn ? `Nadi : ${dataRes.nadiObgyn} x/mnt\n` : ''
      data += dataRes.nafasObgyn ? `Pernafasan : ${dataRes.nafasObgyn} x/mnt\n` : ''
      data += dataRes.tekananDarahObgyn ? `Tekanan Darah : ${dataRes.tekananDarahObgyn} mmHg\n` : ''
      data += dataRes.tinggibadanObgyn ? `Tinggi Badan : ${dataRes.tinggibadanObgyn} Cm\n` : ''
      data += dataRes.beratbadanObgyn ? `Berat Badan : ${dataRes.beratbadanObgyn} Kg\n` : ''
      data += dataRes.sao2Obgyn ? `SPO2 : ${dataRes.sao2Obgyn} %\n` : ''
      data += dataRes.IMT ? `IMT : ${dataRes.IMT}\n` : ''
    }

    if (dataRes.from && dataRes.from == 'medis_dokter') {
      datax += dataRes.anamnesis;
    } else {
      datax += dataRes.keluhanutama ? `Keluhan Utama : ${dataRes.keluhanutama}\n` : 'Keluhan Utama : -\n'
      datax += dataRes.riwayatpenyakit ? `Riwayat Penyakit Sekarang : ${dataRes.riwayatpenyakit}\n` : 'Riwayat Penyakit Sekarang : -\n'
      datax += dataRes.riwayatpenyakitdahulu ? `Riwayat Penyakit Dahulu : ${dataRes.riwayatpenyakitdahulu}\n` : 'Riwayat Penyakit Dahulu : -\n'
      datax += dataRes.riwayatpengobatan ? `Riwayat Pengobatan : ${dataRes.riwayatpengobatan}\n` : 'Riwayat Pengobatan : -\n'
      datax += dataRes.riwayatpenyakitkeluarga ? `Riwayat Penyakit Keluarga : ${dataRes.riwayatpenyakitkeluarga}\n` : 'Riwayat Penyakit Keluarga : \n'
      datax += dataRes.isalergi ? `Alergi : ${dataRes.isalergi} ${dataRes.riwayatalergi}\n` : 'Alergi : -\n'
    }

    input.value.tekananDarah = dataRes.tekananDarahObgyn
    input.value.nadi = dataRes.nadiObgyn
    input.value.nafas = dataRes.nafasObgyn
    input.value.celcius = dataRes.celciusObgyn
    input.value.sao2 = dataRes.sao2Obgyn
    input.value.beratBadan = dataRes.beratbadanObgyn
    input.value.tinggiBadan = dataRes.tinggibadanObgyn
    input.value.gcse = dataRes.gcse
    input.value.gcsv = dataRes.gcsv
    input.value.gcsm = dataRes.gcsm
    input.value.perlukontrol = dataRes.perlukontrol
    input.value.riwayatkeluar = dataRes.riwayatkeluar
    input.value.statuskeluar = dataRes.statuskeluar
    input.value.keadaanumumobgyn = dataRes.keadaanumumobgyn

    strforO.value = data;
    strforS.value = datax;
    for (let x = 0; x < input.value.details.length; x++) {
      const element = input.value.details[x];
      input.value.details[x].O = data != '' ? `${data}` : ''
      input.value.details[x].S = datax != '' ? `${datax}` : ''
    }
    setAutoFill();

    let urlGetRiwayat = `/emr/get-emr-cppt?nocmfk=${ID_PASIEN}&norec_apd=${item.NOREC_APD}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`;
    const response = await useApi().get(urlGetRiwayat)
    isloadingLAMPAU.value = false
    if (response.length) {
      if (response[0].details.length == 0) {
        response[0].details = input.value.details;
      } else {
        if (response[0].details[0].flag == undefined) {
          response[0].details = input.value.details;
        }
      }

      let perawat = []
      let dokter = []
      isAfterSave.value = true
      let findMissing = response[0].details.filter((dcppt) => {
        return dcppt.flag == kelompokUser;
      });
      let findForMissing = input.value.details.filter((icppt) => {
        return icppt.flag == kelompokUser;
      });

      if (findMissing.length == 0) {
        // let glength = input.value.details[0]
        response[0].details.push(findForMissing[0]);
      }

      if (kelompokUser.includes("perawat")) {

        for (let x = 0; x < response[0].details.length; x++) {
          const element = response[0].details[x];
          if (element.flag == 'perawat') {
            perawat.push(element)
          }
          if (element.flag == 'dokter') {
            dokter.push(element)
          }

          element.tgl = new Date(element.tgl);
          element.tglVerifikasi = new Date(element.tglVerifikasi);
          if (element.dpjpRawatBersama == undefined) {
            element.dpjpRawatBersama = [{
              id: null
            }]
          }

          dokter.forEach(item => {
            if (item.no == element.no) {
              if (!item.S) {
                item.S = element.S
              }
              if (!item.O) {
                item.O = element.O
              }
              if (!item.P) {
                item.P = element.P
              }
            }
          });

          if (element.flag == 'perawat') {
            dokter.forEach(item => {
              if (item.no == element.no) {
                if (!item.S) {
                  item.S = element.S
                }
                if (!item.O) {
                  item.O = element.O
                }
                if (!item.P) {
                  item.P = element.P
                }
              }
            });
            if (element.tujuanKep == undefined) {
              element.tujuanKep = [{
                no: 1
              }]
            }
          }
        }
        input.value = response[0] //set ke inputan

        setValueDisabled()
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      }

      input.value = response[0]

      if (!response[0]?.perlukontrol) {
        input.value.perlukontrol = dataRes.perlukontrol
      }
      if (!response[0]?.riwayatkeluar) {
        input.value.riwayatkeluar = dataRes.riwayatkeluar
      }
      if (!response[0]?.statuskeluar) {
        input.value.statuskeluar = dataRes.statuskeluar
      }
      if (!response[0]?.keadaanumumobgyn) {
        input.value.keadaanumumobgyn = dataRes.keadaanumumobgyn
      }

      if (dataRes) {
        input.value.tekananDarah = dataRes.tekananDarahObgyn
        input.value.nadi = dataRes.nadiObgyn
        input.value.nafas = dataRes.nafasObgyn
        input.value.celcius = dataRes.celciusObgyn
        input.value.sao2 = dataRes.sao2Obgyn
        input.value.beratBadan = dataRes.beratbadanObgyn
        input.value.tinggiBadan = dataRes.tinggibadanObgyn
        input.value.gcse = dataRes.gcse
        input.value.gcsv = dataRes.gcsv
        input.value.gcsm = dataRes.gcsm
        input.value.keadaanumumobgyn = dataRes.keadaanumumobgyn
      }

      for (let z = 0; z < response.length; z++) {
        panjangsoapbaru.push(response[z])
      }
      riwayatDataCPPT.value = response[0]
      for (let z = 0; z < response.length; z++) {
        panjangsoapbaru.push(response[z])
      }

      await addNewItem({ flag: kelompokUser })
      input.value.details = [input.value.details[0]]

      setValueDisabled()
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
    }

  }
  alertMid.value = false
  isLoading.value = false;
}

function onlyNumber(evt: any) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
    evt.preventDefault();
  } else {
    return true;
  }
}

const isRanap = computed(() => {
  const namaruangan = props.registrasi.namaruangan.toUpperCase()
  const ruanganInap = ["PERINATOLOGI", "SANDAT", "JEPUN", "CEMPAKA", "KASUARI", "MERAK", "RAWAT INAP SUITE", "RAWAT INAP VK", "RAWAT INAP TUNJUNG", "ISOLASI JEPUN", "RAWAT INAP HCU", "RAWAT INAP ICCU", "RAWAT INAP ICU", "RAWAT INAP PICU/NICU", "INTENSIF JEPUN", "STROKE CORNER", "RAWAT INAP KEDOKTERAN NUKLIR"];
  return ruanganInap.some(ruangan => new RegExp(ruangan, 'i').test(namaruangan));
})

// function isRanap(namaruangan: any) {
// }

const checkBidan = computed(() => {
  if (props.registrasi.namaruangan.toUpperCase().indexOf('PONEK') > -1) {
    return 'bidan';
  } else if (user.toUpperCase().indexOf('BIDAN') > -1) {
    return 'bidan';
  } else if (props.registrasi.namaruangan.toUpperCase().indexOf('OBGYN') > -1) {
    return 'bidan';
  } else if (props.registrasi.namaruangan.toUpperCase().indexOf('VK') > -1) {
    return 'bidan';
  } else {
    return 'perawat';
  }
})


function setPenunjang(indexCppt) {
  isLoading.value = true;
  isLoadingBill.value = true;
  indexcppt.value = indexCppt;
  let str = ''
  let gcol = `PemeriksaanKardiotokografi,PemeriksaanObstetri,PemeriksaanGynekologi,PemeriksaanFetal`;
  useApi().get(`emr/get-penunjang-khusus?tables=${gcol}&norec_pd=${NOREC_PD}&nocmfk=${ID_PASIEN}`).then((dt) => {
    // console.log("res", dt);
    isLoading.value = false;
    isLoadingBill.value = false;
    if (dt.length > 0) {
      // const val = dt[0];
      for (let kObject = 0; kObject < dt.length; kObject++) {
        const val = dt[kObject];
        if (val.table == 'PemeriksaanKardiotokografi') {
          str += val.jenisPemeriksaanObgyn ? `Jenis Pemeriksaan : ${val.jenisPemeriksaanObgyn}\n` : ''
          str += val.tdawal ? `TD Awal : ${val.tdawal}\n` : ''
          str += val.tg15 ? `TD Menit ke 15 : ${val.tg15}\n` : ''
          str += val.carapantau ? `Cara Pantau : ${val.carapantau}\n` : ''
          str += val.kecepatankertas ? `Kecepatan Kertas : ${val.kecepatankertas} cm/menit\n` : ''
          str += val.periksaDalam ? `Periksa Dalam : ${val.periksaDalam}\n` : ''
          str += val.denganhasil ? `Dengan Hasil : ${val.denganhasil}\n` : ''
          str += val.diagnosis ? `Diagnosis : ${val.diagnosis}\n` : ''
          str += val.denyutjantung ? `Denyut Jantung Janin : ${val.denyutjantung}\n` : ''
          str += val.frekuensidasar ? `Frekuensi Dasar : ${val.frekuensidasar}\n` : ''
          str += val.akselerasi ? `Akselerasi : ${val.akselerasi}\n` : ''
          str += val.deselerasi ? `Deselerasi : ${val.deselerasi}\n` : ''
          str += val.variabilitas ? `Variabilitas : ${val.variabilitas}\n` : ''
          str += val.jenisnya ? `Jenisnya : ${val.jenisnya}\n` : ''
          str += val.beratnya ? `Beratnya : ${val.beratnya}\n` : ''
          str += val.ssp ? `Pola disfungsi SSP : ${val.ssp}\n` : ''
          str += val.yaitu ? `Yaitu : ${val.yaitu}\n` : ''
          str += val.kontraksi ? `Kontraksi Uterus/His : ${val.kontraksi}\n` : ''
          str += val.frekuensi ? `Frekuensi : ${val.frekuensi} /10menit\n` : ''
          str += val.kekuatan ? `Kekuatan : ${val.kekuatan} mmHg\n` : ''
          str += val.lamanya ? `Lamanya : ${val.lamanya} menit\n` : ''
          str += val.relaksasi ? `Relaksasi : ${val.relaksasi}\n` : ''
          str += val.konfigurasi ? `Konfigurasi : ${val.konfigurasi}\n` : ''
          str += val.tumusdasar ? `Tumus Dasar : ${val.tumusdasar} mmHg\n` : ''
          str += val.gerakjanin ? `Gerak Janin : ${val.gerakjanin} kali\n` : ''
          str += val.lamagerak ? `dalam : ${val.lamagerak} menit\n` : ''
          str += val.diagnosisktg ? `Diagnosis KTG : ${val.diagnosisktg}\n` : ''
          str += val.kategoridiagnosisktg ? `Kategori : ${val.kategoridiagnosisktg}\n` : ''
          str += val.saran ? `Saran : ${val.saran}\n` : ''
        } else if (val.table == 'PemeriksaanObstetri' || val.table == 'PemeriksaanFetal') {
          str += val.jenisPemeriksaanObgyn ? `Jenis Pemeriksaan : ${val.jenisPemeriksaanObgyn}\n` : ''
          str += val.kondisiTeknisFetal ? `Kondisi Teknis : ${val.kondisiTeknisFetal} \n` : ''
          str += val.karenaFetal ? `Karena : ${val.karenaFetal} \n` : ''
          str += val.janin ? `Janin : ${val.janin} \n` : ''
          str += val.jumlahJanin ? `Jumlah Janin : ${val.jumlahJanin} \n` : ''
          str += val.khorionisitas ? `Khorionisitas : ${val.khorionisitas} \n` : ''
          str += val.djj ? `DJJ : ${val.djj} \n` : ''
          str += val.ketDJJ ? `Keterangan DJJ : ${val.ketDJJ} x/menit\n` : ''
          str += val.fetalmovement ? `Fetal Movement : ${val.fetalmovement}\n` : ''
          str += val.gestasionalsac || val.usiaGestasional || val.ketGestasional ? `Biometri :\n` : ''
          str += val.gestasionalsac ? `Gestasional sac : ${val.gestasionalsac} - ${val.usiaGestasional ?? ''}\n` : ''
          str += val.ketGestasional ? `AVE : ${val.ketGestasional}\n` : ''
          str += val.crown || val.usiaCrown || val.ketCrown ? `Crown-rump lenght : ${val.crown} - ${val.usiaCrown}\n` : ''
          str += val.ketCrown ? `EDD : ${val.ketCrown} \n` : ''
          str += val.biparietal || val.usiaBiparietal ? `Biparietal Diameter : ${val.biparietal} - ${val.usiaBiparietal} \n` : ''
          str += val.ketBiparietal ? `EFW : ${val.ketBiparietal} \n` : ''
          str += val.headcircum || val.usiaHeadcircum ? `Head Circumference : ${val.headcircum} - ${val.usiaHeadcircum} \n` : ''
          str += val.abdominalcircum || val.usiaAbdominalcircum ? `Abdominal Circumference : ${val.abdominalcircum} - ${val.usiaAbdominalcircum} \n` : ''
          str += val.Ketabdominalcircum ? `Keterangan Abdominalcircum : ${val.Ketabdominalcircum} \n` : ''
          str += val.femoral || val.usiaFemoral ? `Femoral Lenght : ${val.femoral} - ${val.usiaFemoral} \n` : ''
          str += val.plasenta ? `Plasenta : ${val.plasenta} \n` : ''
          str += val.menutupi ? `Menutupi : ${val.menutupi} \n` : ''
          str += val.ukuranMenutupi ? `Ukuran Menutupi : ${val.ukuranMenutupi} mm dari OUI\n` : ''
          str += val.maturasi ? `Maturasi : ${val.maturasi} \n` : ''
          str += val.cairanaminion ? `Cairan aminion : ${val.cairanaminion} \n` : ''
          str += val.AFI ? `AFI : ${val.AFI} \n` : ''
          str += val.SDP ? `SDP : ${val.SDP} \n` : ''
          str += val.temuanAbnormal ? `Temuan Abnormal : ${val.temuanAbnormal} \n` : ''
          str += val.kongenitalMayor ? `Kelainan kongenital mayor : ${val.kongenitalMayor} \n` : ''
          str += val.temuanAbnormalKongenital ? `Temuan Abnormal Kongenital : ${val.temuanAbnormalKongenital} \n` : ''
          str += val.adneksa ? `Adneksa : ${val.adneksa} \n` : ''
          str += val.temuanAbnormalAdneksa ? `Temuan Abnormal Adneksa : ${val.temuanAbnormalAdneksa} \n` : ''
          str += val.arteriUterina ? `Arteri Uterina : ${val.arteriUterina} \n` : ''
          str += val.arteriUmbilicalis ? `Arteri Umbilicalis : ${val.arteriUmbilicalis} \n` : ''
          str += val.riUterina ? `RI Uterina: ${val.riUterina} \n` : ''
          str += val.riUmbilicalis ? `RI Umbilicalis : ${val.riUmbilicalis} \n` : ''
          str += val.piUterina ? `PI Uterina : ${val.piUterina} \n` : ''
          str += val.piUmbilicalis ? `PI Umbilicalis: ${val.piUmbilicalis} \n` : ''
          str += val.ratioUterina ? `S/D Ratio Uterina: ${val.ratioUterina} \n` : ''
          str += val.ratioUmbilicalis ? `S/D Ratio Umbilicalis: ${val.ratioUmbilicalis} \n` : ''
          str += val.ductusVenosus ? `Ductus Venosus : ${val.ductusVenosus} \n` : ''
          str += val.arteriSerebi ? `Arteri Serebi Media : ${val.arteriSerebi} \n` : ''
          str += val.riDuctus ? `RI Ductus : ${val.riDuctus} \n` : ''
          str += val.riSerebi ? `RI Serebi : ${val.riSerebi} \n` : ''
          str += val.piDuctus ? `PI Ductus: ${val.piDuctus} \n` : ''
          str += val.piSerebi ? `PI Serebi: ${val.piSerebi} \n` : ''
          str += val.ratioDuctus ? `S/D Ratio Ductus: ${val.ratioDuctus} \n` : ''
          str += val.ratioSerebi ? `S/D Ratio Serebi: ${val.ratioSerebi} \n` : ''
          str += val.fetalLainnya ? `Lain-Lain : ${val.fetalLainnya} \n` : ''
          str += val.fetalSaran ? `Kesimpulan & Saran : ${val.fetalSaran} \n` : ''

        } else if (val.table == 'PemeriksaanGynekologi') {
          str += val.jenisPemeriksaanObgyn ? `Jenis Pemeriksaan : ${val.jenisPemeriksaanObgyn}\n` : ''
          str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          str += val.karena ? `Karena : ${val.karena} \n` : ''
          str += val.vesicaUrinaria ? `Vesica Urinaria : ${val.vesicaUrinaria} \n` : ''
          str += val.cairanBebas ? `Cairan Bebas : ${val.cairanBebas} \n` : ''
          str += val.uterus ? `Uterus : ${val.uterus} \n` : ''
          str += val.adnexa ? `Adnexa : ${val.adnexa} \n` : ''
          str += val.obstetriLainnya ? `Lain-Lain : ${val.obstetriLainnya} \n` : ''
          str += val.kesimpulansaran ? `Kesimpulan & Saran : ${val.kesimpulansaran} \n` : ''
        }
      }

      let detail = input.value.details[indexcppt.value];

      if (str != '') {
        if (detail.O == undefined) {
          detail.O = ''
          detail.O += str;
        } else {
          detail.O += '\n' + str
        }

        H.alert('success', 'Berhasil ambil data')
      } else {
        H.alert('warning', 'Penunjang Khusus belum ada')
      }
    }
  });
}

function setLokalisMata() {
  let str = '';
  let element = input.value.details[indexcppt.value];
  if (element.O == undefined || element.O == '') {
    element.O = '';
  } else {
    str += '\n';
  }

  str += inputLokalis.value.visusawalodu ? `Visus Awal OD UVCA : ${inputLokalis.value.visusawalodu}\n` : ''
  str += inputLokalis.value.visusawalodb ? `Visus Awal OD BCVA : ${inputLokalis.value.visusawalodb}\n` : ''
  str += inputLokalis.value.visusawalosu ? `Visus Awal OS UVCA : ${inputLokalis.value.visusawalosu}\n` : ''
  str += inputLokalis.value.visusawalosb ? `Visus Awal OS BCVA : ${inputLokalis.value.visusawalosb}\n` : ''
  str += inputLokalis.value.kacamataodu ? `Kacamata OD UVCA : ${inputLokalis.value.kacamataodu}\n` : ''
  str += inputLokalis.value.kacamataodb ? `Kacamata OD BCVA : ${inputLokalis.value.kacamataodb}\n` : ''
  str += inputLokalis.value.kacamataosu ? `Kacamata OS UVCA : ${inputLokalis.value.kacamataosu}\n` : ''
  str += inputLokalis.value.kacamataosb ? `Kacamata OS BCVA : ${inputLokalis.value.kacamataosb}\n` : ''
  str += inputLokalis.value.od ? `Posisi/Hirschberg OD : ${inputLokalis.value.od}\n` : ''
  str += inputLokalis.value.os ? `Posisi/Hirschberg OS : ${inputLokalis.value.os}\n` : ''
  str += inputLokalis.value.palpebrai ? `Palpebra Mata Kiri : ${inputLokalis.value.palpebrai}\n` : ''
  str += inputLokalis.value.konjungtivai ? `Konjungtiva Mata Kiri : ${inputLokalis.value.konjungtivai}\n` : ''
  str += inputLokalis.value.korneai ? `Kornea Mata Kiri : ${inputLokalis.value.korneai}\n` : ''
  str += inputLokalis.value.bilikmatai ? `Bilik Mata Mata Kiri : ${inputLokalis.value.bilikmatai}\n` : ''
  str += inputLokalis.value.irisi ? `Iris Mata Kiri : ${inputLokalis.value.irisi}\n` : ''
  str += inputLokalis.value.pupili ? `Pupil Mata Kiri : ${inputLokalis.value.pupili}\n` : ''
  str += inputLokalis.value.lensai ? `Lensa Mata Kiri : ${inputLokalis.value.lensai}\n` : ''
  str += inputLokalis.value.vitreusi ? `Vitreus Mata Kiri : ${inputLokalis.value.vitreusi}\n` : ''
  str += inputLokalis.value.funduskopii ? `Funduskopi Mata Kiri : ${inputLokalis.value.funduskopii}\n` : ''
  str += inputLokalis.value.schiotzi ? `Schiotz Mata Kiri : ${inputLokalis.value.schiotzi}\n` : ''
  str += inputLokalis.value.aplanasii ? `Aplanasi Mata Kiri : ${inputLokalis.value.aplanasii}\n` : ''
  str += inputLokalis.value.ncti ? `NCT Mata Kiri : ${inputLokalis.value.ncti}\n` : ''
  str += inputLokalis.value.palpebran ? `Palpebra Mata Kanan : ${inputLokalis.value.palpebran}\n` : ''
  str += inputLokalis.value.konjungtivan ? `Konjungtiva Mata Kanan : ${inputLokalis.value.konjungtivan}\n` : ''
  str += inputLokalis.value.kornean ? `Kornea Mata Kanan : ${inputLokalis.value.kornean}\n` : ''
  str += inputLokalis.value.bilikmatan ? `Bilik Mata Mata Kanan : ${inputLokalis.value.bilikmatan}\n` : ''
  str += inputLokalis.value.irisn ? `Iris Mata Kanan : ${inputLokalis.value.irisn}\n` : ''
  str += inputLokalis.value.pupiln ? `Pupil Mata Kanan : ${inputLokalis.value.pupiln}\n` : ''
  str += inputLokalis.value.lensan ? `Lensa Mata Kanan : ${inputLokalis.value.lensan}\n` : ''
  str += inputLokalis.value.vitreusn ? `Vitreus Mata Kanan : ${inputLokalis.value.vitreusn}\n` : ''
  str += inputLokalis.value.funduskopin ? `Funduskopi Mata Kanan : ${inputLokalis.value.funduskopin}\n` : ''
  str += inputLokalis.value.schiotzn ? `Schiotz Mata Kanan : ${inputLokalis.value.schiotzn}\n` : ''
  str += inputLokalis.value.aplanasin ? `Aplanasi Mata Kanan : ${inputLokalis.value.aplanasin}\n` : ''
  str += inputLokalis.value.nctn ? `NCT Mata Kanan : ${inputLokalis.value.nctn}\n` : ''
  str += inputLokalis.value.testanel ? `Test Anel : ${inputLokalis.value.testanel}\n` : ''
  str += inputLokalis.value.testbutawarna ? `Test Buta Warna : ${inputLokalis.value.testbutawarna}\n` : ''
  str += inputLokalis.value.testfluoresin ? `Test Fluoresin : ${inputLokalis.value.testfluoresin}\n` : ''
  str += inputLokalis.value.optionsmata ? `Resep Kacamata : ${inputLokalis.value.optionsmata}\n` : ''
  str += inputLokalis.value.spherisd ? `- Spheris OD : ${inputLokalis.value.spherisd}\n` : ''
  str += inputLokalis.value.spheriss ? `- Spheris OS : ${inputLokalis.value.spheriss}\n` : ''
  str += inputLokalis.value.cylinderd ? `- Cylinder OD : ${inputLokalis.value.cylinderd}\n` : ''
  str += inputLokalis.value.cylinders ? `- Cylinder OS : ${inputLokalis.value.cylinders}\n` : ''
  str += inputLokalis.value.prismad ? `- Prisma OD : ${inputLokalis.value.prismad}\n` : ''
  str += inputLokalis.value.prismas ? `- Prisma OS : ${inputLokalis.value.prismas}\n` : ''
  str += inputLokalis.value.axisd ? `- Axis OD : ${inputLokalis.value.axisd}\n` : ''
  str += inputLokalis.value.axiss ? `- Axis OS : ${inputLokalis.value.axiss}\n` : ''
  str += inputLokalis.value.addition ? `- Addition OS : ${inputLokalis.value.addition}\n` : ''
  str += inputLokalis.value.pupil ? `- Pupil Distance : ${inputLokalis.value.pupil}\n` : ''

  element.O += str;
  showModalLokalis.value = false;
}

function updateLokalisMata() {
  let ID = inputLokalis.value.id ? inputLokalis.value.id : ''
  let object: any = {}
  object = inputLokalis.value
  object.nocm = props.pasien.nocm
  object['canvasmata'] = H.tandaTangan().get("canvasmata");

  if (object.hasOwnProperty('namatemplate')) {
    delete object.namatemplate
  }

  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN_ASMED.value,
    'collection': props.registrasi.objectdepartemenfk == 16 ? 'AsesmenMedisRawatInap' : 'AsesmenMedisRawatJalan',
    'url_form': props.FORM_URL,
    'name_form': 'Assesmen Medis',
    'jenis_emr': 'asesmen_medis',
    'data': object
  }
  isLoading.value = true

  useApi().post(
    `/emr/simpan-emr`, json).then(async (response: any) => {

      saveKlaimSEP();
      isLoading.value = false
      NOREC_EMRPASIEN_ASMED.value = response.norec_emr
      inputLokalis.value.id = response.id
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const riwayatVitalSign = async (index: any) => {
  isLoading.value = true;

  useApi().get(`emr/get-data-exist-semua?norec_pd=${NOREC_PD}`)
    .then((responselast: any) => {
      isLoading.value = false;
      if (responselast.length) {
        listTemplate.value = responselast;
        showModalVitalSign.value = true;
      } else {
        H.alert('warning', 'Data tidak ada');
      }
    });
};

// Fungsi untuk memilih salah satu data dari modal
const pilihRiwayatVitalSign = (index: number) => {
  addRiwayatVitalSign(listTemplate.value[index]); // Panggil fungsi untuk set data
};


const addRiwayatVitalSign = (response: any) => {
  if (!input.value.details || input.value.details.length === 0) {
    console.error('details tidak ditemukan', input.value.details);
    return;
  }

  let index = input.value.details.findIndex(item => item.no === input.value.details[0].no);

  if (index === -1) {
    console.warn(`No tidak ditemukan, menggunakan index terakhir.`);
    index = input.value.details.length - 1;
  }

  let element = input.value.details[index];

  if (!element.O) {
    element.O = ''; // Inisialisasi jika O belum ada
  } else {
    element.O += '\n'; // Tambahkan baris baru jika sudah ada isi
  }

  let str = '';
  str += response.suhu ? `Suhu: ${response.suhu}°C\n` : '';
  str += response.nadi ? `Nadi: ${response.nadi} bpm\n` : '';
  str += response.tekananDarah ? `Tekanan Darah: ${response.tekananDarah} mmHg\n` : '';
  str += response.pernapasan ? `Pernapasan: ${response.pernapasan} x/menit\n` : '';
  str += response.SPO2 ? `SPO2: ${response.SPO2}%\n` : '';

  element.O += str;

  console.log(`Data berhasil ditambahkan ke O di index ${index}:`, element.O); // Debug log

  showModalVitalSign.value = false;
  showModalTemplateFix.value = false;
};





// function showLokalis(index) {
//   const tableAsmed = 'AsesmenMedisRawatJalan';
//   showModalLokalis.value = true;
//   isLoading.value = true;
//   isLoadingBill.value = true;
//   useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi?.norec_pd}&collection=${tableAsmed}`).then(async (dt) => {
//     isLoading.value = false;
//     isLoadingBill.value = false;
//     if (dt.length > 0) {
//       inputLokalis.value = dt[0]
//       await loadGambar('canvasmata', dt[0].canvasmata);
//       if (NOREC_EMRPASIEN_ASMED.value == '') {
//         NOREC_EMRPASIEN_ASMED.value = dt[0].emrpasienfk
//       }
//     }
//     indexcppt.value = index;
//   })
// }

function showLokalis(index, copiedData = null) {
  const tableAsmed = props.registrasi.objectdepartemenfk == 16 ? 'AsesmenMedisRawatInap' : 'AsesmenMedisRawatJalan';
  showModalLokalis.value = true;
  isLoading.value = true;
  isLoadingBill.value = true;

  // Jika ada data dari copy, langsung gunakan data tersebut
  if (copiedData && Object.keys(copiedData).length > 0) {
    console.log("Menggunakan data dari copy:", copiedData);
    isLoading.value = false;
    isLoadingBill.value = false;
    inputLokalis.value = copiedData;
    indexcppt.value = index;
    return;
  }


  // Jika tidak ada data dari copy, ambil data dari get-emr
  useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi?.norec_pd}&collection=${tableAsmed}`).then(async (dt) => {
    isLoading.value = false;
    isLoadingBill.value = false;
    if (dt.length > 0) {
      let res = dt;
      if (tableAsmed == 'AsesmenMedisRawatInap') {
        res = res.filter((data) => {
          if (data.section_SL) {
            return data.section_SL.label.toUpperCase().indexOf('MATA') > -1;
          }
        });
      } else {
        res = dt[0];
      }

      inputLokalis.value = res;
      await loadGambar('canvasmata', res.canvasmata);
      if (NOREC_EMRPASIEN_ASMED.value == '') {
        NOREC_EMRPASIEN_ASMED.value = res.emrpasienfk;
      }
    }
    indexcppt.value = index;
  }).catch((error) => {
    isLoading.value = false;
    isLoadingBill.value = false;
    console.error('Error fetching data from get-emr:', error);
  });
}

const loadGambar = async (element_id: string, value: string) => {
  let sigCanvas: any = document.getElementById('canvasmata');
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

const filteredDiagnosa = computed(() => {
  return d_DiagnosaSdki.value.filter(diagnosa =>
    diagnosa.label.toLowerCase().includes(filterMenu.value.toLowerCase())
  )
})

const diagnosaMap = computed(() => {
  return filteredDiagnosa.value.reduce((map, d) => {
    map[d.id] = d.label;
    return map;
  }, {});
});

// Simpan label yang dipilih
const selectedDiagnosaLabels = ref([]);

watch(selectedDiagnosa, (newIds) => {
  selectedDiagnosaLabels.value = newIds.map(id => diagnosaMap.value[id]);
}, { deep: true });


watch(selectedDiagnosa, async (newVal) => {
  console.log("Selected Diagnosa:", newVal);

  if (newVal.length > 0) {
    const selectedId = newVal[newVal.length - 1];
    console.log("Fetching SIKI for ID:", selectedId);

    await fetchSiki(selectedId);
  } else {
    d_Siki.value = [];
  }
});


watch(() => [
  item.diagnosa10,
], () => {
  useApi().get(`emr/get-kasus-diagnosa?nocmfk=${ID_PASIEN}&iddiagnosa=${item.diagnosa10}`).then((response) => {

    if (response.datas.length > 0) {
      item.isKasusBaru = 'lama'
    } else {
      item.isKasusBaru = 'baru'
    }

  })
})

watch(
  () => isCPPTOLD.value, () => {
    if (isCPPTOLD.value) {
      // loadRiwayatOld()
    }
  }
)
// watch(
//   () => isDokter.value,
//   (newValue, oldValue) => {
//     // item.filter = ''
//     if (newValue != oldValue) {
//       if (newValue) {
//         item.filter = 'dokter'
//         isPerawat.value = false
//         isProfesi.value = false
//         isGizi.value = false
//       } else {
//         item.filter = ''
//         isPerawat.value = false
//         isProfesi.value = false
//         isDokter.value = false
//         isGizi.value = false
//       }
//     }

//   }
// )
watch(isAlltemplate, (newValue) => {
  pilihTemplateFix()
})
watch(
  () => kelompokUser,
  (newKelompokUser, oldKelompokUser) => {
    if (newKelompokUser === 'dokter' || newKelompokUser === 'dokter-igd' && !isDokter.value) {
      isDokter.value = true;
      item.filter = 'dokter';
      isPerawat.value = false;
      isProfesi.value = false;
      isGizi.value = false
    } else if (newKelompokUser === 'perawat' && !isPerawat.value) {
      isPerawat.value = true;
      item.filter = 'perawat';
      isDokter.value = false;
      isProfesi.value = false;
      isGizi.value = false
    } else if (newKelompokUser !== 'dokter' && newKelompokUser !== 'perawat' && !isProfesi.value && newKelompokUser === 'gizi') {
      isGizi.value = true;
      item.filter = 'gizi';
      isDokter.value = false;
      isPerawat.value = false;
      isProfesi.value = false;
    } else {
      console.log("NEW USER KELOMPOK", newKelompokUser);
      isProfesi.value = true;
      item.filter = 'profesi lain';
      isDokter.value = false;
      isPerawat.value = false;
      isGizi.value = false
    }
  },
  { immediate: true }
);


// watch(
//   () => isProfesi.value, (newValue, oldValue) => {

//     if (newValue != oldValue) {
//       if (newValue) {
//         item.filter = 'profesi lain'
//         isDokter.value = false
//         isPerawat.value = false
//         isGizi.value = false
//       } else {

//         item.filter = ''
//         isPerawat.value = false
//         isProfesi.value = false
//         isDokter.value = false
//         isGizi.value = false
//       }
//     }
//   }
// )
// watch(
//   () => isGizi.value, (newValue, oldValue) => {

//     if (newValue != oldValue) {
//       if (newValue) {
//         item.filter = 'gizi'
//         isDokter.value = false
//         isPerawat.value = false
//         isProfesi.value = false
//       } else {

//         item.filter = ''
//         isPerawat.value = false
//         isProfesi.value = false
//         isDokter.value = false
//         isGizi.value = false
//       }
//     }
//   }
// )
const print = async () => {
  let realFlag = isPerawat.value ? 'perawat' :
    isDokter.value ? 'dokter' :
      isProfesi.value ? 'profesi' :
        isGizi.value ? 'gizi' : 'profesi lainnya';
  H.printBlade(`emr/cetak/cetak-cppt?pdf=true&norec_pd=${NOREC_PD}&emrpasienfk=${NOREC_EMRPASIEN.value}&nocmfk=${ID_PASIEN}&allPeriode=${isAllPeriode.value}&flag=${realFlag}&from=CPPT`)
}
watch(
  () => isResep.value, (newValue, oldValue) => {
    if (newValue != oldValue) {
      if (newValue) {
        showPreviewResep.value = true
      }
    }
  }
)
onBeforeMount(async () => {
  try {
    // await loadRiwayatOld()
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
setView();
onMounted(async () => {
  pasienByID(ID_PASIEN)
  checkAsesmen();
  checkResume();
  dropdownList()
  fetchDiagnosaSdki()
  // fetchSiki()
})


const isLoadingPasien: any = ref(false)
const activeValue = ref('icd10')
const pasien: any = ref({})
const dataSourceX: any = ref([])
const dataSourceIX: any = ref([])
const dataSourceXHistory: any = ref([])
const dataSourceIXHistory: any = ref([])
const listColor: any = ref(Object.keys(useThemeColors()))
const modalInput: any = ref(false)
const modalInput9: any = ref(false)
const isDetail: any = ref([false])
function pasienByID(id: any) {
  riwayatDiagnosa10()
  riwayatDiagnosa9()

  // riwayatDiagnosa10All()
  // riwayatDiagnosa9All()

}


function showModal() {
  clearInput()
  item.tglpelayanann = new Date()
  modalInput.value = true
}
function showModal9() {
  clearInput()
  item.tglpelayanann = new Date()
  modalInput9.value = true

}


async function editItemsIX(e: any) {
  clearInput()
  item.NOREC_DIAGNOSA9 = e.norec_diagnosapasien
  item.keterangan9 = e.keterangantindakan
  // fetchDiagnosa9(e.kddiagnosatindakan)
  item.diagnosa9 = e.objectdiagnosatindakanfk
  item.tglpelayanan = new Date(e.tglinputdiagnosa)
  modalInput9.value = true
}
function hapusItems(e: any) {
  useApi().post(
    `/diagnosa/delete-diagnosa-x`, { norec: e.norec_diagnosapasien }).then((response: any) => {
      isLoading.value = false
      riwayatDiagnosa10()
    }).catch((e: any) => {
      isLoading.value = false
    })
}
function hapusItemsIX(e: any) {
  useApi().post(
    `/diagnosa/delete-diagnosa-ix`, { norec: e.norec_diagnosapasien }).then((response: any) => {
      isLoading.value = false
      riwayatDiagnosa9()
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const DialogConfirm = (e) => {
  confirm.require({
    message: 'Apakah Anda yakin menghapus data ini?',
    header: 'Konfirmasi Hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: async () => {
      try {
        await hapusItems(e);
        dataSourceICD10.value = dataSourceICD10.value.filter(item => item.norec !== e.norec);
        useToaster().success('Data berhasil dihapus.');
      } catch (error) {
        console.error('Gagal menghapus data:', error);
        useToaster().error('Gagal menghapus data.');
      }
    },
    reject: () => {
      useToaster().info('Penghapusan dibatalkan.');
    },
  });
};


const DialogConfirmIX = (e: any) => {
  confirm.require({
    message: 'Apakah anda yakin menghapus data ini ?',
    header: 'Konfirmasi Hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      hapusItemsIX(e)

    },
    reject: () => { },
  })
}

async function simpanICD9() {
  if (!item.tglpelayanan) {
    useToaster().error('Tgl  harus di isi')
    return
  }

  if (!item.diagnosa9) {
    useToaster().error('Diagnosis harus di isi')
    return
  }

  let json = {
    'diagnosapasien': {
      'norec': item.NOREC_DIAGNOSA9 ? item.NOREC_DIAGNOSA9 : '',
      'noregistrasifk': item.NOREC_APD,
      'tglregistrasi': item.registrasi.tglregistrasi,
      'keterangantindakan': item.keterangan9 ? item.keterangan9 : null,

    },
    'detaildiagnosapasien': {
      'objectdiagnosatindakanfk': item.diagnosa9,
      'tglinputdiagnosa': H.formatDate(item.tglpelayanan, 'YYYY-MM-DD HH:mm:ss'),
      'noregistrasi': item.registrasi.noregistrasi
    }
  }
  isLoading.value = true
  await useApi().post(
    `/diagnosa/save-diagnosa-ix`, json).then((response: any) => {
      isLoading.value = false
      modalInput9.value = false
      // Kirim SatSet
      if (item.registrasi.noregistrasi) {
        sendSatuSehat(item.registrasi.noregistrasi, 'icd-9')
      }
      riwayatDiagnosa9()
    }).catch((e: any) => {
      isLoading.value = false
    })
}
async function riwayatDiagnosa10() {
  try {
    dataSourceICD10.value.loading = true;
    const response = await useApi().get(
      `/diagnosa/riwayat-diagnosa-x?norec_pd=${NOREC_PD}&nocmfk=${ID_PASIEN}`
    );
    const riwayat = response.data.map((diagnosa, index) => ({
      no: index + 1,
      noregistrasi: diagnosa.noregistrasi,
      jenisdiagnosa: diagnosa.jenisdiagnosa,
      kddiagnosa: diagnosa.kddiagnosa,
      namadiagnosa: diagnosa.namadiagnosa,
      namaruangan: diagnosa.namaruangan,
      tglInput: H.formatDate(diagnosa.tglinputdiagnosa, 'DD-MM-YYYY'),
      norec: diagnosa.norec,
    }));
    dataSourceICD10.value = riwayat;
  } catch (error) {
    console.error("Error fetching riwayat diagnosa:", error);
    useToaster().error("Gagal memuat riwayat diagnosa");
  } finally {
    dataSourceICD10.value.loading = false;
  }
}

async function riwayatDiagnosa9() {
  // dataSourceIX.value = []
  dataSourceIX.value.loading = true
  await useApi().get(
    `/diagnosa/riwayat-diagnosa-ix?norec_pd=${NOREC_PD}&nocmfk=${ID_PASIEN}`).then((response: any) => {
      dataSourceIX.value.loading = false
      dataSourceIX.value = response.data
    }).catch((e: any) => {
      dataSourceIX.value.loading = false
    })

}

// async function riwayatDiagnosa10All() {
//     // dataSourceX.value = []
//     dataSourceXHistory.value.loading = true
//     await useApi().get(
//         `/diagnosa/riwayat-diagnosa-x-history?norec_pd=${item.registrasi.norec_pd}&nocmfk=${ID_PASIEN}`).then((response: any) => {
//             dataSourceXHistory.value.loading = false
//             dataSourceXHistory.value = response.data
//         }).catch((e: any) => {
//             dataSourceXHistory.value.loading = false
//         })

// }
// async function riwayatDiagnosa9All() {
//     // dataSourceIX.value = []
//     dataSourceIXHistory.value.loading = true
//     await useApi().get(
//         `/diagnosa/riwayat-diagnosa-ix-history?norec_pd=${item.registrasi.norec_pd}&nocmfk=${ID_PASIEN}`).then((response: any) => {
//             dataSourceIXHistory.value.loading = false
//             dataSourceIXHistory.value = response.data
//         }).catch((e: any) => {
//             dataSourceIXHistory.value.loading = false
//         })

// }

const sendSatuSehat = async (noregistrasi: any, type: any) => {
  let jsonSatSet = {
    'noregistrasi': noregistrasi
  }
  let url = 'bridging/satusehat/Condition'
  if (type != 'icd-10') {
    url = 'bridging/satusehat/Procedure'
  }
  await useApi().postNoMessage(url, jsonSatSet).then((resp: any) => { })
}
const modalTindakan = ref(false);
const modalTindakanDokter = async (e: any) => {
  modalTindakan.value = true
  const pasien3 = pasien?.value
  console.table(pasien3)
  if (pasien3) {
    noreg_pasienDetail.value = pasien3.noregistrasi;
    norec_pd_pasienDetail.value = route.query.norec_pasien_daftar;
  }
}

const modalResep = ref(false);
const modalResepDokter = async (e: any) => {
  modalResep.value = true
  const pasien3 = pasien?.value
  console.table(pasien3)
  if (pasien3) {
    noreg_pasienDetail.value = pasien3.noregistrasi;
    norec_pd_pasienDetail.value = route.query.norec_pasien_daftar;
  }
}

const modalKonsultasi = ref(false);
const modalKonsultasiDokter = async (e: any) => {
  modalKonsultasi.value = true
  const pasien3 = pasien?.value
  console.table(pasien3)
  if (pasien3) {
    noreg_pasienDetail.value = pasien3.noregistrasi;
    norec_pd_pasienDetail.value = route.query.norec_pasien_daftar;
  }
}

const modalLaboratorium = ref(false);
const modalLaboratoriumDokter = async (e: any) => {
  modalLaboratorium.value = true
  const pasien3 = pasien?.value
  console.table(pasien3)
  if (pasien3) {
    noreg_pasienDetail.value = pasien3.noregistrasi;
    norec_pd_pasienDetail.value = route.query.norec_pasien_daftar;
  }
}
const modalRadiologi = ref(false);
const modalRadiologiDokter = async (e: any) => {
  modalRadiologi.value = true
  const pasien3 = pasien?.value
  console.table(pasien3)
  if (pasien3) {
    noreg_pasienDetail.value = pasien3.noregistrasi;
    norec_pd_pasienDetail.value = route.query.norec_pasien_daftar;
  }
}
const modalPenunjangKhusus = ref(false);
const modalPenunjangKhususDokter = async (e: any) => {
  modalPenunjangKhusus.value = true
  const pasien3 = pasien?.value
  console.table(pasien3)
  if (pasien3) {
    noreg_pasienDetail.value = pasien3.noregistrasi;
    norec_pd_pasienDetail.value = route.query.norec_pasien_daftar;
  }
}
const modalBedah = ref(false);
const modalBedahDokter = async (e: any) => {
  modalBedah.value = true
  const pasien3 = pasien?.value
  console.table(pasien3)
  if (pasien3) {
    noreg_pasienDetail.value = pasien3.noregistrasi;
    norec_pd_pasienDetail.value = route.query.norec_pasien_daftar;
  }
}
const modalHandoverAntarShiftRanap = ref(false);

const transferPasien = async (index: any) => {
  indexcppt.value = index
  disabledJawab.value = true
  isLoading.value = true
  inputTP.value.tanggal = new Date()
  console.log("INPUTTP", props);
  NORECTP = ''
  d_Ruangan.value.forEach((element) => {
    if (element.value == props.registrasi.objectruanganlastfk) {
      inputTP.value.ruanganasal = element
    }
  });
  inputTP.value.namadokter = props.registrasi.dokter
  inputTP.value.dokter = props.registrasi.objectpegawaifk
  await fetchKelas({ query: 'NON KELAS' })
  if (d_Kelas.value.length) {
    inputTP.value.kelas = d_Kelas.value[0]
  }
  isLoading.value = false
  modalInputTP.value = true
}

const postTransferPasien = async () => {
  if (!inputTP.value.tanggal) {
    H.alert('error', 'Tanggal harus di isi')
    return
  }
  if (!inputTP.value.ruanganasal) {
    H.alert('error', 'Ruang Asal harus di isi')
    return
  }
  if (!inputTP.value.ruangantujuan) {
    H.alert('error', 'Ruang Tujuan harus di isi')
    return
  }
  // if (!inputTP.dokter) {
  //     H.alert('error', 'Dokter harus di isi')
  //     return
  // }
  if (!inputTP.value.kelas) {
    H.alert('error', 'Kelas Konsultasi Harus di isi')
    return
  }

  let element = input.value.details[indexcppt.value];
  console.log("Element CPPT", element)
  let object = {
    "norec_pd": props.registrasi.norec_pd,
    "asalRujukanfk": 23,
    "norec": NORECTP,
    //"noAntrian": dataSource.value.length + 1,
    "dokterfk": inputTP.value.dokter,
    "objectruanganasalfk": inputTP.value.ruanganasal.value,
    "objectruangantujuan": inputTP.value.ruangantujuan.value,
    "kelasfk": inputTP.value.kelas.value,
  }
  // console.log("data kirim", object);
  // return;
  isLoading.value = true

  let ruangan = props.registrasi.namaruangan;
  let type = "POLI";
  if (ruangan.toUpperCase().indexOf('IGD') > -1) type = "IGD";
  if (ruangan.toUpperCase().indexOf('KEDOKTERAN NUKLIR') > -1) type = "NUKLIR";


  let resdiagnos = await useApi().get(`emr/get-medis-diagnosa?nocmfk=${ID_PASIEN}&type=${type}`);
  await useApi().post('registrasi/simpan-pasien-konsul', object).then((response) => {
    console.log('Response berahasil', response)
    isLoading.value = false

    if (element.intruksiPPA != undefined || element.intruksiPPA != null) {
      let words = `\nKonsul : ${inputTP.value.ruangantujuan.label} Kepada:\nYth.TS ${inputTP.value.ruangantujuan.label} Di RSBM DH,\nMenghadapakan pasien Di atas dengan diagnosa ${resdiagnos?.TADiagnosa ?? '...'}. Pasien saya rencanakan tindakan ... . Mohon evaluasi Di bidang TS.`
      element.intruksiPPA += words;
    } else {
      let words = `Konsul : ${inputTP.value.ruangantujuan.label} Kepada:\nYth.TS ${inputTP.value.ruangantujuan.label} Di RSBM DH,\nMenghadapakan pasien Di atas dengan diagnosa ${resdiagnos?.TADiagnosa ?? '...'}. Pasien saya rencanakan tindakan ... . Mohon evaluasi Di bidang TS.`
      element.intruksiPPA = words;
      // input.value.instruksiAsesmen = 'Pasien ditransferkan dari Ruangan : ' + inputTP.value.ruanganasal.label + ' ke ' + inputTP.value.ruangantujuan.label + ' oleh ' + inputTP.value.namadokter;
    }
    // console.log("WORDS ",words);
    H.alert('success', 'Berhasil ditambahkan')

    modalInputTP.value = false
    sendNotification(r)

    // loadRiwayat()
  }).catch((err) => {
    console.log('Response gagal', err)
    isLoading.value = false
  })

}

const fetchKelas = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/kelas_m?select=id,namakelas&param_search=namakelas&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Kelas.value = response
  })
}
</script>

<style lang="scss">
pre {
  margin: 0px !important;
  padding: 0px !important;
  font-family: Arial, Helvetica, sans-serif !important;
  background-color: white !important;
}

.form-layout.is-stacked {
  max-width: none;
  padding: 0 10px 0 10px;
}

.form-layout.is-separate {
  max-width: none;
}

.form-layout .form-outer {
  flex: 1;
  display: inline-block;
  width: 100%;
  padding: 20px;
  background-color: var(--white);
  border-radius: var(--radius-large);
  border: 1px solid var(--fade-grey-dark-3);
  transition: all 0.3s;
  padding: 0;
}

.hr-dashboard .block-header {
  display: none;
}

.block-green {
  background: var(--primary);
  font-family: var(--font);
  box-shadow: var(--primary-box-shadow);
  border-radius: 16px;
  padding: 25px;
  display: none;
}

.CPPT_HEIGHT {
  overflow: auto;
  height: 600px;
}

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  background-color: var(--white);
  border-color: var(--fade-grey-dark-2) !important;
}

.tg-card {
  background-color: #feffed;
  height: 720px;
}

.is-dark {
  .tg {
    background-color: var(--dark-sidebar-light-6)
  }

  .tg-card {
    background-color: var(--dark-sidebar-light-6)
  }
}

.tg td {
  border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg th {
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

.tg .tg-0lax {
  text-align: left;
  vertical-align: top
}

.scroll-container-rev {
  height: 1000px;
  overflow: auto;
}

.tombol-cppt {
  float: right;
  margin-right: 5px;
  height: 25px;
  margin-top: -20px;
}

@media (max-width: 1144px) {

  .table-tg {
    width: 150%;
  }
}

.input-container {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
}

.input-group {
  display: flex;
  align-items: center;
  gap: 10px;
}

.custom-icon-button {
  width: 40px;
  height: 40px;
  font-size: 20px;
  display: inline-flex;
  justify-content: center;
  align-items: center;
}

.wide-input {
  flex-grow: 1;
  min-width: 550px;
}

.text-muted {
  color: #e3e1e1 !important;
}
</style>
