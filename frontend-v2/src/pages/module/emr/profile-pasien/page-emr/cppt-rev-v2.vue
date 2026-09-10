<template>
    <div class="form-layout is-stacked-2" style="
       width: 100%;
       max-width: none;">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3> {{ props.FORM_NAME }} <span v-if="isStuck"></span></h3>
            </div>
            <div class="right">
              <div class="buttons">
                <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun">
                  Kembali
                </VButton>
                <VButton type="button" rounded outlined color="warning" raised icon="lnir lnir-printer"
                  :disabled="NOREC_EMRPASIEN.length == 0" @click="print"> Cetak
                </VButton>
                <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                  @click="simpan('')"> Preview
                </VButton>
              </div>
  
            </div>
          </div>
        </div>
      </div>
    </div>
  
    <div class="columns is-multiline p-2">
      <div class="column is-2 ml-5 mb-5-min" style="display: none !important">
        <VControl>
          <VSwitchBlock v-model="isCPPTOLD" label="HISTORY" color="success" />
        </VControl>
      </div>
      <div class="column is-12" style=" margin-top: 1.8rem;" v-if="isloadingLAMPAU">
        <VProgress size="tiny" color="info" />
      </div>
      <div class="column is-12">
        <VCard>
          <div class="columns is-multiline mt-3">
  
            <div class="column is-2" style="display: none !important">
              <VField label="DPJP Utama" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                  <AutoComplete v-model="input.dpjpUtama" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                    :field="'label'" placeholder="ketik untuk mencari..." />
                </VControl>
              </VField>
            </div>
            <div class="column is-1 mt-5" style="display: none !important">
              <VIconButton type="button" raised circle icon="feather:filter" @click="clear()" outlined color="info"
                v-tooltip-prime.right="'Clear Filter'">
              </VIconButton>
            </div>
            <div class="column is-12">
              <div class="columns is-multiline">
                  <div class="column is-6"></div>
                  <div class="column is-6">
                    <VButton icon="feather:book" color="info" raised @click="lihatCPPT()" class="tombol-cppt"
                      :loading="isloadingCopy" style="height: 30px;">
                      Lihat CPPT
                    </VButton>
                    <VButton icon="feather:book" color="info" raised @click="simpanTemplate()" class="tombol-cppt"
                      :loading="isloadingCopy" style="height: 30px;">
                      Simpan Template
                    </VButton>
                    <VButton icon="feather:book" color="info" raised @click="setujuiCPPT()" class="tombol-cppt"
                      :loading="isloadingCopy" style="height: 30px;">
                      Setujui CPPT
                    </VButton>
                  </div>
              </div>
            </div>
  
  
                        <div class="column is-12">
                          <div class="columns is-multiline">
                              <div class="column is-12">
                                  <div class="columns is-multiline">
                                    <div class="column is-3" style="margin-top: 10px;">
                                          <h1 class="mb-12 emr">Keadaan Umum</h1>
                                          <VField class="is-autocomplete-select">
                                              <VControl icon="feather:search">
                                                  <AutoComplete v-model="input.keadaanumum" :suggestions="d_allo"
                                                      :optionLabel="'label'"
                                                      :dropdown="true" :minLength="3" :appendTo="'body'"
                                                      :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                      placeholder="--Pilih--"/>
                                              </VControl>
                                          </VField>
                                      </div>
                                      <div class="column is-6">
                                          <h1 class="mb-3 emr">GCS</h1>
                                          <div class="columns is-multiline">
                                              <div class="column is-4">
                                                  <VField addons>
                                                      <VControl class="field-addon-body">
                                                          <VButton static>E</VButton>
                                                      </VControl>
                                                      <VControl expanded>
                                                          <VInput type="text" class="input" placeholder="E"
                                                          v-model.number="input.gcse" />
                                                      </VControl>
                                                  </VField>
                                              </div>
                                              <div class="column is-4">
                                                  <VField addons>
                                                      <VControl class="field-addon-body">
                                                          <VButton static>V</VButton>
                                                      </VControl>
                                                      <VControl expanded>
                                                          <VInput type="text" class="input" placeholder="V"
                                                          v-model.number="input.gcsv" />
                                                      </VControl>
                                                  </VField>
                                              </div>
                                              <div class="column is-4">
                                                  <VField addons>
                                                      <VControl class="field-addon-body">
                                                          <VButton static>M</VButton>
                                                      </VControl>
                                                      <VControl expanded>
                                                          <VInput type="text" class="input" placeholder="M"
                                                          v-model.number="input.gcsm" />
                                                      </VControl>
                                                  </VField>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="column is-3">
                                          <h1 class="mb-3 emr">Pilih Template</h1>
                                          <VField>
                                              <VControl>
                                                  <input v-model="input.template" class="input" />
                                              </VControl>
                                          </VField>
                                      </div>
                                  </div>
                              </div>
                              <div class="column is-12">
                                  <div class="columns is-multiline">
                                      <div class="column is-3">
                                          <div class="column is-12" style="margin-top: -10px;">
                                              <h1 style="font-weight: bold;">Tekanan Darah</h1>
                                              <VField addons>
                                                  <VControl expanded>
                                                      <VInput type="text" class="input" placeholder="Tekanan Darah"
                                                          v-model="input.tekananDarah" />
                                                  </VControl>
                                                  <VControl class="field-addon-body">
                                                      <VButton static>mmHg</VButton>
                                                  </VControl>
                                              </VField>
                                          </div>
                                      </div>
                                      <div class="column is-2">
                                      <h1 style="font-weight: bold;">PR</h1>
                                          <VField addons>
                                              <VControl expanded>
                                                  <VInput type="text" class="input" placeholder="PR" v-model="input.nadi" />
                                              </VControl>
                                              <VControl class="field-addon-body">
                                                  <VButton static>x/menit</VButton>
                                              </VControl>
                                          </VField>
                                      </div>
                                      <div class="column is-2">
                                          <h1 style="font-weight: bold;">RR</h1>
                                          <VField addons>
                                              <VControl expanded>
                                                  <VInput type="text" class="input" placeholder="RR"
                                                      v-model="input.nafas" />
                                              </VControl>
                                              <VControl class="field-addon-body">
                                                  <VButton static>x/menit</VButton>
                                              </VControl>
                                          </VField>
                                      </div>
                                      <div class="column is-2">
                                          <h1 style="font-weight: bold;">Suhu</h1>
                                          <VField addons>
                                              <VControl expanded>
                                                  <VInput type="text" class="input" placeholder="Suhu" v-model="input.celcius" />
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
                                                  <VInput type="text" class="input" placeholder="Saturasi O2 (SpO2)"
                                                      v-model="input.sao2" />
                                              </VControl>
                                              <VControl class="field-addon-body">
                                                  <VButton static>%</VButton>
                                              </VControl>
                                          </VField>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
  
            <div class="column is-8" style="overflow-y: auto;">
              <table class="tg table-tg">
                <tbody v-for="(item, index) in dataSourcefiltered" :key="index">
                  <tr v-if="item.flag != 'gizi'"
                    :style="[item.flag == 'dokter' ? 'background-color: var(--danger--light-color);' : (item.flag == 'perawat' ? 'background-color: var(--info--light-color);' : '')]">
                    <td style="width:15%; display: none !important">
                      <VField>
                        <VControl class="prime-auto">
                          <Calendar v-model="item.tgl" selectionMode="single" :manualInput="true" class="w-100"
                            :showIcon="true" showTime hourFormat="24" :date-format="H.dateTimeFormat().prime.date" :disabled="item.tgl2" />
                        </VControl>
                      </VField>
                      <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                        <VControl icon="fa:stethoscope" fullwidth class="prime-auto ">
                          <AutoComplete v-model="item.tenagaMedis" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                            :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Tenaga Medis..."
                            :disabled="item.tenagaMedis2" />
                        </VControl>
                      </VField>
                    </td>
                    <td>
                      <table class="tg">
                        <tr>
                          <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle; display: none !important">S</td>
                          <td>
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                  <h1 style="font-weight: bold; margin-bottom: -10px;">Subjective:</h1>
                                </div>
                                <div class="column is-8"></div>
                            </div>
                            <VField>
                              <VControl>
                                <VTextarea v-model="item.S" rows="5" placeholder="" :disabled="item.S2">
                                </VTextarea>
                              </VControl>
                            </VField>
                          </td>
                          <td v-if="item.flag == 'laras'">
                            <VIconButton color="danger" outlined circle icon="lnil lnil-trash-can-alt-1"
                              @click="clearItem(index, 'S')" style="float:right" v-tooltip-prime.top="'Hapus'">
                            </VIconButton>
                          </td>
                          <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle; display: none !important">O</td>
                          <td>
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                  <h1 style="font-weight: bold; margin-bottom: -10px;">Objective:</h1>
                                </div>
                                <div class="column is-8"></div>
                            </div>
                            <VField>
                              <VControl>
                                <VTextarea v-model="item.O" rows="5" placeholder="" :disabled="item.O2">
                                </VTextarea>
                              </VControl>
                            </VField>
                          </td>
                          <td v-if="item.flag == 'laras'">
                            <VIconButton color="danger" outlined circle icon="lnil lnil-trash-can-alt-1"
                              @click="clearItem(index, 'O')" style="float:right" v-tooltip-prime.top="'Hapus'"></VIconButton>
                          </td>
                        </tr>
                        <!-- <tr> -->
                          
                          <!-- <td width="5%" style="width: 10%;font-weight: bold;vertical-align: middle;">
                            <VIconButton type="button" raised v-if="item.flag == 'dokter'" circle icon="fas fa-copy"
                              @click="pasteFromClipboard(item)" v-tooltip-prime.bottom="'Terapkan'" color="warning">
                            </VIconButton>
                            <VIconButton type="button" outlined raised v-if="item.flag == 'perawat'" circle
                              @click="copyToClipboard(item.O)" icon="feather:copy" v-tooltip-prime.bottom="'Copy'"
                              color="warning"></VIconButton>
                          </td> -->
                        <!-- </tr> -->
                        <tr>
                          <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle; display: none !important">A</td>
                          <td v-if="item.flag == 'profesi lain' || item.flag == 'dokter' || item.flag == 'perawat'">
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                  <h1 style="font-weight: bold; margin-bottom: -10px;">Assesment:</h1>
                                </div>
                                <div class="column is-8"></div>
                            </div>
                            <VField>
                              <VControl>
                                <VTextarea v-model="item.A" rows="5" placeholder="" :disabled="item.A2">
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
                                              @click="pasteItemDiagnosa(index, 'all')" class="ml-1"
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
                                                <AutoComplete v-model="itemsss.jenisDiagnosa" :suggestions="d_JenisDiagnosa"
                                                  @complete="fetchJenisDiagnosa($event)" :optionLabel="'label'"
                                                  :dropdown="true" :minLength="3" :appendTo="'body'"
                                                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder=" Jenis ..."
                                                  class="mt-2" />
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
                                                  @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true"
                                                  :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                  :field="'label'" placeholder=" ICD 10 ..." class="mt-2" />
                                              </VControl>
                                            </VField>
                                          </div>
                                        </td>
                                        <td class="tg-0lax">
                                          <div class="column p-1">
                                            <VIconButton circle icon="feather:copy" color="warning" raised bold outlined
                                              @click="pasteItemDiagnosa(index, 'dokter')" class="ml-1"
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
                                                  @complete="fetchDiagnosa9($event)" :optionLabel="'label'" :dropdown="true"
                                                  :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                  :field="'label'" placeholder=" ICD 9 ..." class="mt-2" />
                                              </VControl>
                                            </VField>
                                          </div>
                                        </td>
                                        <td class="tg-0lax">
                                          <div class="column p-1">
                                            <VIconButton circle icon="feather:copy" color="warning" raised bold outlined
                                              @click="pasteItemDiagnosa(index, 'perawat')" class="ml-1"
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
                                      <th class="td-fkprj" width="50%" style="vertical-align:inherit;text-align: center;">
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
                          <td v-if="item.flag == 'laras'"></td>
                          <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle; display: none !important">P</td>
                          <td>
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                  <h1 style="font-weight: bold; margin-bottom: -10px;">Planning:</h1>
                                </div>
                                <div class="column is-8"></div>
                            </div>
                            <VField>
                              <VControl>
                                <VTextarea v-model="item.P" rows="5" placeholder="" :disabled="item.P2">
                                </VTextarea>
                              </VControl>
                            </VField>
                            <div class="column" v-if="item.flag == 'laras'">
                              <span style="font-size:11pt;font-weight:bold">Tujuan Kriteria (SLKI) </span>
                              <div class="mt-1">
                                <table class="tg">
                                  <thead>
                                    <tr>
                                      <th class="td-fkprj" width="50%" style="vertical-align:inherit;text-align: center;">
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
                                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                                :loadingIcon="'pi pi-spinner'" :field="'label'"
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
                              @click="clearItem(index, 'P')" style="float:right" v-tooltip-prime.top="'Hapus'"></VIconButton>
                          </td>
                        </tr>
                        <tr>
                          <td :colspan="item.flag == 'dokter' ? '6' : '4'">
  
                            <div class="columns is-multiline">
                              <div class="column is-12">
                                <VField>
                                    <VControl>
                                        <VCheckbox class="fontcheckbox" v-model="input.intruksi" true-value="Intruksi DPJP"
                                            label="Intruksi DPJP" color="primary" circle />
                                    </VControl>
                                </VField>
                                <VField>
                                  <VControl>
                                    <VTextarea v-model="item.intruksiPPA" rows="10" placeholder="Intruksi"
                                      :disabled="item.intruksiPPA2" style="height: 100px;">
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
                                      v-tooltip-prime.right="'Laboratorium'" @click="isLab = true"> Laboratorium </VButton>
                                  </div>
                                  <div class="column is-12">
                                    <VButton color="primary" class="w-100 btn-slim" light rounded outlined
                                      v-tooltip-prime.right="'Rujukan External'" @click="isRujukan = true"> Rujukan </VButton>
                                  </div>
                                </div>
                              </div>
                              <div class="column is-12">
                                <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                  <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                                    <AutoComplete v-model="input.dpjpUtama" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                      :field="'label'" placeholder="ketik untuk mencari..." />
                                  </VControl>
                                </VField>
                                <VField>
                                    <VControl>
                                        <VCheckbox class="fontcheckbox" v-model="input.dokterraber" true-value="Dokter Rawat Bersama"
                                            label="Dokter Rawat Bersama" color="primary" circle />
                                    </VControl>
                                </VField>
                                <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }" v-if="input.dokterraber == 'Dokter Rawat Bersama'">
                                  <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                                    <AutoComplete v-model="input.dpjpUtama" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                      :field="'label'" placeholder="ketik untuk mencari..." />
                                  </VControl>
                                </VField>
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
                    
  
                    <td style="width:15%; display: none !important" class="text-center">
                      <img v-if="item.dokterDPJP && isAfterSave"
                        :src="'https://api.qrserver.com/v1/create-qr-code/?size=70x70&data=' + (item.dokterDPJP ? item.dokterDPJP.label : '-')"><br
                        v-if="item.dokterDPJP && isAfterSave">
                      <VField>
                        <VControl class="prime-auto">
                          <VTextarea v-model="item.keteranganVerifikasiDPJP" rows="5" placeholder="Keterangan..."
                            :disabled="item.keteranganVerifikasiDPJP2">
                          </VTextarea>
  
                          <Calendar v-model="item.tglVerifikasi" selectionMode="single" :manualInput="true"
                            class="w-100 mt-2" :showIcon="true" showTime hourFormat="24" :date-format="H.dateTimeFormat().prime.date"
                            placeholder="yy-mm-dd HH:mm" :disabled="item.tglVerifikasi2" />
                          <AutoComplete v-model="item.dokterDPJP" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                            :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Verifikasi DPJP..." class="mt-2"
                            :disabled="item.dokterDPJP2" />
                        </VControl>
  
                      </VField>
                    </td>
                    <td style="width:7%;vertical-align: text-top;">
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <!-- <VIconButton type="button" raised circle icon="feather:save" :loading="isLoading"
                            @click="simpan(item)" color="success" v-tooltip-prime.top="'Preview '">
                          </VIconButton> -->
                          <VIconButton type="button" raised circle icon="feather:save" :loading="isLoading"
                            @click="simpanReal" color="success" v-tooltip-prime.top="'Simpan '">
                          </VIconButton>
                        </div>
                        <div class="column is-12">
                          <VIconButton type="button" raised circle icon="fas fa-paste" @click="paste(item, index)"
                            color="warning" v-tooltip-prime.top="'Terapkan'">
                          </VIconButton>
                        </div>
                        <div class="column is-12">
                          <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem(item)"
                            color="info" v-tooltip-prime.top="'Tambah Baris '">
                          </VIconButton>
                        </div>
                        <div class="column is-12">
                          <VIconButton v-if="item.no > 1" type="button" raised circle icon="feather:trash"
                            @click="removeItem(index)" color="danger">
                          </VIconButton>
                        </div>
                      </div>
                    </td>
                  </tr>
                  <tr v-if="item.flag == 'gizi'"
                    :style="[item.flag == 'dokter' ? 'background-color: var(--danger--light-color);' : (item.flag == 'perawat' ? 'background-color: var(--info--light-color);' : '')]">
                    <td style="width:15%; display: none !important">
                      <VField>
                        <VControl class="prime-auto">
                          <Calendar v-model="item.tgl" selectionMode="single" :manualInput="true" class="w-100"
                            :showIcon="true" showTime hourFormat="24" :date-format="H.dateTimeFormat().prime.date" :disabled="item.tgl2" />
                        </VControl>
                      </VField>
                      <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                        <VControl icon="fa:stethoscope" fullwidth class="prime-auto ">
                          <AutoComplete v-model="item.tenagaMedis" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                            :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Tenaga Medis..."
                            :disabled="item.tenagaMedis2" />
                        </VControl>
                      </VField>
                    </td>
                    <td>
                      <table class="tg">
                        <tr>
                          <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle; display: none !important">A</td>
                          <td>
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                  <h1 style="font-weight: bold; margin-bottom: -10px;">Assesment:</h1>
                                </div>
                                <div class="column is-8"></div>
                            </div>
                            <VField>
                              <VControl>
                                <VTextarea v-model="item.AGizi" rows="5" placeholder="" :disabled="item.S2"
                                  style="padding-left: 30px;">
                                </VTextarea>
                              </VControl>
                            </VField>
                          </td>
                          <td>
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                  <h1 style="font-weight: bold; margin-bottom: -10px;">Diagnosis:</h1>
                                </div>
                                <div class="column is-8"></div>
                            </div>
                            <VField>
                              <VControl>
                                <VTextarea v-model="item.DGizi" rows="5" placeholder="" :disabled="item.O2"
                                  style="padding-left: 30px;">
                                </VTextarea>
                              </VControl>
                            </VField>
                          </td>
                        </tr>
                        <tr>
                          <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle; display:none !important">I</td>
                          <td>
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                  <h1 style="font-weight: bold; margin-bottom: -10px;">Intervensi:</h1>
                                </div>
                                <div class="column is-8"></div>
                            </div>
                            <VField>
                              <VControl>
                                <VTextarea v-model="item.IGizi" rows="5" placeholder="" :disabled="item.A2"
                                  style="padding-left: 30px;">
                                </VTextarea>
                              </VControl>
                            </VField>
                          </td>
                          <td>
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                  <h1 style="font-weight: bold; margin-bottom: -10px;">Monitoring:</h1>
                                </div>
                                <div class="column is-8"></div>
                            </div>
                            <VField>
                              <VControl>
                                <VTextarea v-model="item.MGizi" rows="5" placeholder="" :disabled="item.P2"
                                  style="padding-left: 30px;">
                                </VTextarea>
                              </VControl>
                            </VField>
                          </td>
                        </tr>
                        <tr>
                          <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle; display:none !important">E</td>
                          <td colspan="2">
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                  <h1 style="font-weight: bold; margin-bottom: -10px;">Evaluasi:</h1>
                                </div>
                                <div class="column is-8"></div>
                            </div>
                            <VField>
                              <VControl>
                                <VTextarea v-model="item.EGizi" rows="5" placeholder="" :disabled="item.P2"
                                  style="padding-left: 30px;">
                                </VTextarea>
                              </VControl>
                            </VField>
                          </td>
                        </tr>
                        <tr>
                          <td colspan="2">
  
                            <div class="columns is-multiline">
                              <div class="column is-12">
                                <VField>
                                    <VControl>
                                        <VCheckbox class="fontcheckbox" v-model="input.intruksi" true-value="Intruksi DPJP"
                                            label="Intruksi DPJP" color="primary" circle />
                                    </VControl>
                                </VField>
                                <VField>
                                  <VControl>
                                    <VTextarea v-model="item.intruksiPPA" rows="10" placeholder="Intruksi"
                                      :disabled="item.intruksiPPA2" style="height: 100px;">
                                    </VTextarea>
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-12" v-if="item.flag == 'dokter'">
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
                                      v-tooltip-prime.right="'Laboratorium'" @click="isLab = true"> Laboratorium </VButton>
                                  </div>
                                  <div class="column is-12">
                                    <VButton color="primary" class="w-100 btn-slim" light rounded outlined
                                      v-tooltip-prime.right="'Rujukan External'" @click="isRujukan = true"> Rujukan </VButton>
                                  </div>
                                </div>
                              </div>
                              <div class="column is-12">
                                <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                  <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                                    <AutoComplete v-model="input.dpjpUtama" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                      :field="'label'" placeholder="ketik untuk mencari..." />
                                  </VControl>
                                </VField>
                                <VField>
                                    <VControl>
                                        <VCheckbox class="fontcheckbox" v-model="input.dokterraber" true-value="Dokter Rawat Bersama"
                                            label="Dokter Rawat Bersama" color="primary" circle />
                                    </VControl>
                                </VField>
                                <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }" v-if="input.dokterraber == 'Dokter Rawat Bersama'">
                                  <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                                    <AutoComplete v-model="input.dpjpUtama" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                      :field="'label'" placeholder="ketik untuk mencari..." />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </td>
                        </tr>
                      </table>
  
                    </td>
                    
  
                    <td style="width:15%; display: none !important" class="text-center" >
                      <img v-if="item.dokterDPJP && isAfterSave"
                        :src="'https://api.qrserver.com/v1/create-qr-code/?size=70x70&data=' + (item.dokterDPJP ? item.dokterDPJP.label : '-')"><br
                        v-if="item.dokterDPJP && isAfterSave">
                      <VField>
                        <VControl class="prime-auto">
                          <VTextarea v-model="item.keteranganVerifikasiDPJP" rows="5" placeholder="Keterangan..."
                            :disabled="item.keteranganVerifikasiDPJP2">
                          </VTextarea>
  
                          <Calendar v-model="item.tglVerifikasi" selectionMode="single" :manualInput="true"
                            class="w-100 mt-2" :showIcon="true" showTime hourFormat="24" :date-format="H.dateTimeFormat().prime.date"
                            placeholder="yy-mm-dd HH:mm" :disabled="item.tglVerifikasi2" />
                          <AutoComplete v-model="item.dokterDPJP" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                            :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Verifikasi DPJP..." class="mt-2"
                            :disabled="item.dokterDPJP2" />
                        </VControl>
  
                      </VField>
                    </td>
                    <td style="width:7%;vertical-align: text-top;">
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <!-- <VIconButton type="button" raised circle icon="feather:save" :loading="isLoading"
                            @click="simpan(item)" color="success" v-tooltip-prime.top="'Preview '">
                          </VIconButton> -->
                          <VIconButton type="button" raised circle icon="feather:save" :loading="isLoading"
                            @click="simpanReal" color="success" v-tooltip-prime.top="'Simpan '">
                          </VIconButton>
                        </div>
                        <div class="column is-12">
                          <VIconButton type="button" raised circle icon="fas fa-paste" @click="paste(item, index)"
                            color="warning" v-tooltip-prime.top="'Terapkan'">
                          </VIconButton>
                        </div>
                        <div class="column is-12">
                          <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem(item)"
                            color="info" v-tooltip-prime.top="'Tambah Baris '">
                          </VIconButton>
                        </div>
                        <div class="column is-12 ml-3-min">
                          <VIconButton v-if="item.no > 1" type="button" raised circle icon="feather:trash"
                            @click="removeItem(index)" color="danger">
                          </VIconButton>
                        </div>
                      </div>
                    </td>
                  </tr>
                  <tr
                    :style="[item.flag == 'dokter' ? 'background-color: var(--danger--light-color);' : (item.flag == 'perawat' ? 'background-color: var(--info--light-color);' : '')]">
  
                    <td colspan="5" style="text-align: center;">
                      <VTag class="mr-1 mb-1"
                        :color="item.flag == 'dokter' ? 'danger' : item.flag == 'perawat' ? 'info' : 'light'"
                        :label="item.flag" />
                    </td>
                  </tr>
  
  
                </tbody>
              </table>
            </div>
  
  
  
            <div class="column is-4">
              <!-- tempatnya disini -->
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
                  <div class="flex-list-inner" v-if="input2.length === 0">
                    <VPlaceholderSection :title="H.assets().notFound" class="my-6">
                      <template #image>
                        <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" style="width: 100px;" />
                        <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt=""
                          style="width: 100px;" />
                      </template>
                    </VPlaceholderSection>
                  </div>
                  <VCard class="tg-card" v-if="input2.length">
                    <div class="column is-12">
                      <div class="columns is-multiline">
                        <div class="column is-3">
                          <VControl>
                            <VSwitchBlock class="switch-profesi" v-model="isPerawat" label="CPPT PERAWAT" @change="switchFilter('perawat')" color="info"/>
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl>
                            <VSwitchBlock class="switch-profesi" v-model="isDokter" label="CPPT DOKTER" color="danger" @change="switchFilter('dokter')"/>
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl>
                            <VSwitchBlock class="switch-profesi" v-model="isProfesi" label="SEMUA" @change="switchFilter('profesi lain')"/>
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl>
                            <VSwitchBlock class="switch-profesi" v-model="isGizi" label="ADIME" color="warning" @change="switchFilter('gizi')"/>
                          </VControl>
                        </div>
                      </div>
                    </div>
  
                    <div class="columns is-multiline mt-3">
                      <div class="column is-12 CPPT_HEIGHT">
                        <table class="tg">
                          <thead>
                          </thead>
                          <tbody v-for="(itemss, index) in input2" :key="index">
                            <tr style="background: #d6d4d4;">
                              <td style="display:none !important">
                                <VIconButton circle icon="feather:chevron-down" raised bold v-tooltip-prime.top="' Collapsed'"
                                  v-if="itemss.show" @click="itemss.show = false"> </VIconButton>
                                <VIconButton circle icon="feather:chevron-right" raised bold v-tooltip-prime.top="' Expand'"
                                  v-if="!itemss.show" @click="itemss.show = true"> </VIconButton>
                                <!-- <VIconButton circle icon="feather:copy" color="warning" raised bold @click="copy(itemss.details[0])"
                                  class="ml-1" v-tooltip-prime.top="'Copy SEMUA'"> </VIconButton> -->
  
                              </td>
                              <td colspan="2">
                                <span class="text-normal-1">
                                  <b>PPA</b> : {{ itemss.registrasi ? itemss.registrasi.dokter : '' }}
                                  <b>Section</b> : {{ itemss.registrasi ? itemss.registrasi.namaruangan : '' }}
                                  <b>Tanggal</b> : {{ itemss.registrasi ? H.formatDateIndo(itemss.registrasi.tglregistrasi) : '' }} 
                                </span>
                              </td>
                              <td style="display:none !important">
                                <span class="text-normal-1 bold" v-if="itemss.noemr == ''">
                                  <VTag :color="'danger'" class="is-pulled-right" :label="'SIMRS LAMA'" rounded outlined />
                                </span>
                              </td>
                            </tr>
                            <tr>
                              <td colspan="3" style="size:100%;">
                                <table style="border:none; width:100%;">
                                  <tr v-for="(item, index2) in itemss.details" :key="index2">
  
                                    <td style="display: none !important">
                                      <span class="mb-2">{{ item.tgl }}</span><br>
                                      <span>{{ item.tenagaMedis ? item.tenagaMedis.label : '-' }}</span>
  
                                    </td>
                                    <td>
                                      <table class="tg">
                                        <tr>
                                          <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">S
                                          </td>
                                          <td colspan="2">
                                            {{ item.S ?? '' }}
                                          </td>
                                          <td width="5%" style="display:none !important">
                                            <VIconButton circle icon="feather:copy" color="warning" raised bold outlined
                                              @click="copyToClipboard(item.S)" class="ml-1" v-tooltip-prime.top="'Copy '">
                                            </VIconButton>
  
                                          </td>
                                        </tr>
                                        <tr>
                                          <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">O
                                          </td>
                                          <td colspan="2">
                                            {{ item.O ?? '' }}
                                          </td>
                                          <td width="5%" style="display:none !important">
                                            <VIconButton circle icon="feather:copy" color="warning" raised bold outlined
                                              @click="copyToClipboard(item.O)" class="ml-1" v-tooltip-prime.top="'Copy '">
                                            </VIconButton>
  
                                          </td>
                                        </tr>
                                        <tr>
                                          <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">A
                                          </td>
                                          <td colspan="2">
                                            {{ item.A ?? '' }}
                                          </td>
                                          <td style="display:none !important">
                                            <div class="columns is-multiline">
                                              <div class="column is-12">
                                                <span style="font-size:9pt;font-weight:bold">Diagnosis ICD 10</span>
                                                <div style="overflow-y:auto;" class="mt-1">
                                                  <table class="tg" style="width:100% !important">
                                                    <thead>
                                                      <tr>
  
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
                                                        </th>
                                                      </tr>
                                                    </thead>
                                                    <tbody v-for="(itemsss, index3) in item.diagnosaDokter" :key="index3">
                                                      <tr v-if="itemsss.jenisDiagnosa">
                                                        <td class="tg-0lax">
                                                          <div class="column p-1">
                                                            {{ itemsss.jenisDiagnosa ? itemsss.jenisDiagnosa.label : '' }}
                                                          </div>
                                                        </td>
                                                        <td class="tg-0lax">
                                                          <div class="column pt-3 pb-0">
                                                            {{ itemsss.keterangan ?? '' }}
  
                                                          </div>
                                                        </td>
                                                        <td class="tg-0lax">
                                                          <div class="column p-1">
                                                            {{ itemsss.diagnosaa ? itemsss.diagnosaa.label : '' }}
  
                                                          </div>
                                                        </td>
                                                        <td width="5%">
                                                          <VIconButton circle icon="feather:copy" color="warning" raised bold
                                                            outlined @click="copyItemDiagnosa(itemsss, 'ICD10')" class="ml-1"
                                                            v-tooltip-prime.top="'Copy Diagnosa '">
                                                          </VIconButton>
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
                                                      <tr v-if="itemsss.diagnosaa">
  
                                                        <td class="tg-0lax">
                                                          <div class="column pt-3 pb-0">
                                                            {{ itemsss.keterangan ?? '' }}
  
                                                          </div>
                                                        </td>
                                                        <td class="tg-0lax">
                                                          <div class="column p-1">
                                                            {{ itemsss.diagnosaa ? itemsss.diagnosaa.label : '' }}
  
                                                          </div>
                                                        </td>
                                                        <td class="tg-0lax">
                                                          <div class="column p-1">
                                                            <VIconButton circle icon="feather:copy" color="warning" raised bold
                                                              outlined @click="copyItemDiagnosa(itemsss, 'ICD9')" class="ml-1"
                                                              v-tooltip-prime.top="'Copy Diagnosa '">
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
                                          <td v-if="item.flag == 'perawat'" style="display:none !important">
                                            <div class="column">
                                              <span style="font-size:11pt;font-weight:bold">Diagnosis Keperawatan</span>
                                              <div class="mt-1">
                                                <table class="tg">
                                                  <thead>
                                                    <tr>
                                                      <th class="td-fkprj" width="50%"
                                                        style="vertical-align:inherit;text-align: center;">
                                                        Diagnosa
                                                        Keperawatan
                                                      </th>
                                                    </tr>
                                                  </thead>
                                                  <tbody v-for="(item2, index2) in item.diagnosaKep" :key="index2">
                                                    <tr>
                                                      <td class="tg-0lax">
                                                        <div class="column p-1">
                                                          {{ item2.diagnosaKeperawatan ? item2.diagnosaKeperawatan.label : '-' }}
                                                        </div>
                                                      </td>
                                                    </tr>
                                                  </tbody>
                                                </table>
                                              </div>
                                            </div>
                                          </td>
                                          <td width="5%" v-if="item.flag == 'profesi lain'" style="display:none !important">
                                            <VIconButton circle icon="feather:copy" color="warning" raised bold outlined
                                              @click="copyToClipboard(item.A)" class="ml-1" v-tooltip-prime.top="'Copy '">
                                            </VIconButton>
  
                                          </td>
                                          <td width="5%" style="vertical-align:middle; display:none !important" v-if="item.flag == 'dokter'">
                                            <VIconButton circle icon="feather:copy" color="warning" raised bold outlined
                                              @click="copyDOKTERA(item)" class="ml-1" v-tooltip-prime.top="'Copy Assesment'">
                                            </VIconButton>
                                          </td>
                                          <!-- <td width="5%"  v-if="item.flag == 'dokter'">
                                            <VIconButton circle icon="feather:copy" color="warning" raised bold outlined
                                              @click="copyDOKTER(item)" class="ml-1" v-tooltip-prime.top="'Copy '">
                                            </VIconButton>
  
                                          </td>
                                          <td width="5%"  v-if="item.flag == 'perawat'">
                                            <VIconButton circle icon="feather:copy" color="warning" raised bold outlined
                                              @click="copyPerawat(item.A)" class="ml-1" v-tooltip-prime.top="'Copy '">
                                            </VIconButton>
  
                                          </td> -->
                                        </tr>
                                        <tr>
                                          <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">P
                                          </td>
                                          <td colspan="2">
                                            {{ item.P ?? '' }}
  
                                            <div class="column" v-if="item.flag == 'perawat'" style="display:none !important">
                                              <span style="font-size:11pt;font-weight:bold">Tujuan Kriteria (SLKI) </span>
                                              <div class="mt-1">
                                                <table class="tg">
                                                  <thead>
                                                    <tr>
  
                                                      <th class="td-fkprj" width="50%"
                                                        style="vertical-align:inherit;text-align: center;">
                                                        Tujuan
                                                        Keperawatan
                                                      </th>
  
                                                    </tr>
                                                  </thead>
                                                  <tbody v-for="(item2, index2) in item.tujuanKep" :key="index2">
                                                    <tr>
                                                      <td class="tg-0lax">
                                                        <div class="column p-1">
                                                          {{ item2.tujuanKeperawatan ? item2.tujuanKeperawatan.label : '-' }}
                                                        </div>
                                                      </td>
  
                                                    </tr>
                                                  </tbody>
                                                </table>
                                              </div>
                                            </div>
                                          </td>
                                          <td width="5%" style="display:none !important">
                                            <VIconButton circle icon="feather:copy" color="warning" raised bold outlined
                                              @click="copyToClipboard(item.P)" class="ml-1" v-tooltip-prime.top="'Copy '">
                                            </VIconButton>
  
                                          </td>
                                        </tr>
                                        
                                        <tr>
                                          <td style="vertical-align: text-top;" colspan="2">
                                            <VIconButton circle icon="feather:copy" color="warning" raised bold
                                              @click="copy(itemss.details[index2])" class="ml-1" v-tooltip-prime.top="'Klik 2x'">
                                            </VIconButton>
                                          </td>
                                        </tr>
                                      </table>
  
                                    </td>
                                    <td style="width:15%; display: none !important">
                                      {{ item.intruksiPPA ?? '' }}
  
                                    </td>
  
                                    <td style="width:15%; display: none !important" class="text-center">
  
                                      <img v-if="item.dokterDPJP"
                                        :src="'https://api.qrserver.com/v1/create-qr-code/?size=70x70&data=' + (item.dokterDPJP ? item.dokterDPJP.label : '-')"><br>
  
                                      <span> {{ item.keteranganVerifikasiDPJP ?? '' }}</span> <br>
                                      <span> {{ item.tglVerifikasi ?? '' }}</span> <br>
                                      <span> {{ item.dokterDPJP ? item.dokterDPJP.label : '' }}</span> <br>
  
                                    </td>
                                    
                                  </tr>
                                </table>
                                <!-- <div v-for="(resep,i) in riwayatResep">
                                  <div v-for="(produk) in resep">
                                    <p style="font-weight:bold" v-if="itemss.registrasi.noregistrasi == produk.noregistrasi">{{ produk.noregistrasi }}</p>
                                  </div>
                                </div>  -->
                                <p style="font-size: 18px;margin-top: 12px;margin-bottom: 6px;"
                                  v-if="itemss.riwayatResep.length > 0">Order Resep</p>
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
              
              <!-- sampai sini -->
            </div>
  
            
  
          </div>
  
          <div>
                          <h1 style="font-weight: bold; margin-bottom: 10px; margin-top: 20px;" class="">Kondisi Keluar RS</h1>
                          <div class="columns is-multiline">
                              <div class="column is-4">
                                  <h1 style="font-weight: bold; margin-bottom: 10px;" class="ml-3">Riwayat Keluar RS</h1>
                                  <div class="columns is-multiline">
                                      <div class="column is-12">
                                          <VField>
                                              <VControl>
                                                  <VCheckbox class="fontcheckbox" v-model="input.sembuh" true-value="Sembuh"
                                                      label="Sembuh" color="primary" circle />
                                              </VControl>
                                          </VField>
                                          <VField>
                                              <VControl>
                                                  <VCheckbox class="fontcheckbox" v-model="input.membaik" true-value="Membaik"
                                                      label="Membaik" color="primary" circle />
                                              </VControl>
                                          </VField>
                                          <VField>
                                              <VControl>
                                                  <VCheckbox class="fontcheckbox" v-model="input.belumsembuh" true-value="Belum Sembuh"
                                                      label="Belum Sembuh" color="primary" circle />
                                              </VControl>
                                          </VField>
                                          <VField>
                                              <VControl>
                                                  <VCheckbox class="fontcheckbox" v-model="input.tidakadaperkembangan" true-value="Tidak Ada Perkembangan"
                                                      label="Tidak Ada Perkembangan" color="primary" circle />
                                              </VControl>
                                          </VField>
                                          <VField>
                                              <VControl>
                                                  <VCheckbox class="fontcheckbox" v-model="input.meninggallebih" true-value="Meninggal >= 48 Jam"
                                                      label="Meninggal > 48 Jam" color="primary" circle />
                                              </VControl>
                                          </VField>
                                          <VField>
                                              <VControl>
                                                  <VCheckbox class="fontcheckbox" v-model="input.meninggalkurang" true-value="Meninggal <= 48 Jam"
                                                      label="Meninggal <= 48 Jam" color="primary" circle />
                                              </VControl>
                                          </VField>
                                          <VField>
                                              <VControl>
                                                  <VCheckbox class="fontcheckbox" v-model="input.doa" true-value="DOA"
                                                      label="DOA" color="primary" circle />
                                              </VControl>
                                          </VField>
                                          <VField>
                                              <VControl>
                                                  <VCheckbox class="fontcheckbox" v-model="input.ranap" true-value="Rawat Inap"
                                                      label="Rawat Inap" color="primary" circle />
                                              </VControl>
                                          </VField>
                                      </div>
                                  </div>
                              </div>
                              <div class="column is-3">
                                  <h1 style="font-weight: bold; margin-bottom: 10px;" class="ml-3">Status Keluar RS</h1>
                                  <div class="columns is-multiline">
                                      <div class="column is-12">
                                          <VField>
                                              <VControl>
                                                  <VCheckbox class="fontcheckbox" v-model="input.belumkeluar" true-value="Belum Keluar RS"
                                                      label="Belum Keluar RS" color="primary" circle />
                                              </VControl>
                                          </VField>
                                          <VField>
                                              <VControl>
                                                  <VCheckbox class="fontcheckbox" v-model="input.diijinkanpulang" true-value="Diijinkan Pulang"
                                                      label="Diijinkan Pulang" color="primary" circle />
                                              </VControl>
                                          </VField>
                                          <VField>
                                              <VControl>
                                                  <VCheckbox class="fontcheckbox" v-model="input.pulangpaksa" true-value="Pulang Paksa"
                                                      label="Pulang Paksa" color="primary" circle />
                                              </VControl>
                                          </VField>
                                          <VField>
                                              <VControl>
                                                  <VCheckbox class="fontcheckbox" v-model="input.dirujuk" true-value="Dirujuk"
                                                      label="Dirujuk" color="primary" circle />
                                              </VControl>
                                          </VField>
                                      </div>
                                  </div>
                              </div>
                              <div class="column is-5">
                                  <h1 style="font-weight: bold; margin-bottom: 10px;" class="ml-3">Perlu Kontrol</h1>
                                  <div class="columns is-multiline">
                                      <div class="column is-3">
                                          <VField>
                                              <VControl>
                                                  <VCheckbox class="fontcheckbox" v-model="input.yakontrol" true-value="Ya"
                                                      label="Ya" color="primary" circle />
                                              </VControl>
                                          </VField>
                                      </div>
                                      <div class="column is-4">
                                          <VField>
                                              <VControl>
                                                  <VCheckbox class="fontcheckbox" v-model="input.tidakkontrol" true-value="Tidak"
                                                      label="Tidak" color="primary" circle />
                                              </VControl>
                                          </VField>
                                      </div>
                                      <div class="column is-5">
                                          <VField>
                                              <VControl>
                                                  <VCheckbox class="fontcheckbox" v-model="input.rujukbalik" true-value="Rujuk Balik"
                                                      label="Rujuk Balik" color="primary" circle />
                                              </VControl>
                                          </VField>
                                      </div>
                                  </div>
                              </div>
                            </div>
                          </div>
                        
        </VCard>
      </div>
    </div>
  
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
    <Dialog v-model:visible="isResep" modal header="Order Resep" :style="{ width: '80vw' }" >
      <OrderResep :pasien="props.pasien" :registrasi="props.registrasi" @berhasilSimpan="tutupModalisResep"/>
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
      <CpptPreviewRev :cppt="input.details" :show_resep="true" :norec_pd="NOREC_PD" />
      <template #footer>
        <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="isDialogSave = false">
          Tutup
        </VButton>
        <VButton type="button" rounded outlined color="primary" class="ml-2" raised icon="feather:save" :loading="isLoading"
          @click="simpanReal"> Simpan
        </VButton>
      </template>
    </Dialog>
  </template>
  
  <script setup lang="ts">
  import { useWindowScroll } from '@vueuse/core'
  import { useApi } from '/@src/composable/useApi'
  import { h, reactive, ref, computed, defineComponent, watch, onMounted,onBeforeMount } from 'vue'
  import { useRoute, useRouter,onBeforeRouteUpdate,onBeforeRouteLeave } from 'vue-router'
  import { useHead } from '@vueuse/head'
  import * as H from '/@src/utils/appHelper'
  import { useViewWrapper } from '/@src/stores/viewWrapper'
  import { useThemeColors } from '/@src/composable/useThemeColors'
  import { useUserSession } from '/@src/stores/userSession'
  import AutoComplete from 'primevue/autocomplete';
  import Dropdown from 'primevue/dropdown';
  import Calendar from 'primevue/calendar';
  import Dialog from 'primevue/dialog';
  import ButtonEmr from '../page-emr-plugins/button-emr.vue'
  import { v4 as uuidv4 } from 'uuid';
  import sleep from '/@src/utils/sleep'
  import OrderLab from './order-laboratorium.vue'
  import OrderRad from './order-radiologi.vue'
  import OrderResep from './order-resep.vue'
  import Konsul from './konsultasi.vue'
  import HasilLabPreview from './hasil-lab-preview.vue'
  import HasilRadPreview from './hasil-rad-preview.vue'
  import BerkasPasienView from './berkas-pasien-preview.vue'
  import TStatusPojokKanan from '../t-status-pojok-kanan.vue'
  import CpptPreviewRev from './cppt-preview-rev.vue'
  import Rujukan from '/@src/pages/module/integrasi-sistem/rujukan.vue'
  let ID_PASIEN = useRoute().query.nocmfk as string
  let NOREC_PD = useRoute().query.norec_pd as string
  let norec_emr = useRoute().query.norec_emr as string
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
  const { y } = useWindowScroll()
  const isStuck = computed(() => { return y.value > 30 })
  const isLoading: any = ref(false)
  const isloadingLAMPAU: any = ref(false)
  const isCPPTOLD: any = ref(true)
  const isHasilLab: any = ref(false)
  const isHasilRad: any = ref(false)
  const scrollContainer = ref(null);
  const tujuanKeper = ref('');
  const rowGroupLAB: any = ref({})
  const d_DiagnosaKeperawatan: any = ref([])
  const d_JenisDiagnosa: any = ref([])
  const d_Diagnosa: any = ref([])
  const d_Diagnosa9: any = ref([])
  const d_Diagnosa10: any = ref([])
  const d_CopyDiagnosa: any = ref([])
  const d_TujuanKeperawatan: any = ref([])
  const d_IntervensiKeperawatan: any = ref([])
  let panjangsoapbaru = []
  const isAfterSave: any = ref(false)
  const isDialogSave: any = ref(false)
  const isRaber: any = ref(false)
  const showPreviewResep: any = ref(false)
  const userLogin = useUserSession().getUser()
  const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
  const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    selectedMenu: [false],
    filter: '',
    lab: [],
    lab_GROUP: [],
    radiologi: [],
    patologi: []
  })
  const pegawaiId = useUserSession().getUser().pegawai.id
  const COLLECTION: any = ref('CatatanPerkembanganPasienTerintegrasi') //table mongodb
  const NOREC_EMRPASIEN: any = ref(norec_emr ? norec_emr : '')
  const array_dokter: any = ref(
    {
      uuid: uuidv4(),
      no: 1,
      tgl: new Date(),
      tglVerifikasi: new Date(),
      flag: 'dokter',
      diagnosaDokter: [{
        no: 1
      }],
      diagnosaDokter9: [{
        no: 1
      }]
    }
  )
  const array_perawat: any = ref({
    uuid: uuidv4(),
    no: 1,
    tgl: new Date(),
    tglVerifikasi: new Date(),
    flag: 'perawat',
    diagnosaKep: [{
      no: 1
    }],
    tujuanKep: [{
      no: 1
    }],
  }
  )
  const array_profesi: any = ref({
    uuid: uuidv4(),
    no: 1,
    tgl: new Date(),
    tglVerifikasi: new Date(),
    flag: 'profesi lain'
  })
  const array_gizi: any = ref({
    uuid: uuidv4(),
    no: 1,
    tgl: new Date(),
    tglVerifikasi: new Date(),
    flag: 'gizi'
  })
  const input: any = ref({
    details: [
      array_dokter.value, array_perawat.value, array_profesi.value, array_gizi.value]
  })
  const input2: any = ref([])
  let flagemr = null
  const riwayatResep: any = ref([])
  const d_Dokter: any = ref([])
  const d_Pegawai: any = ref([])
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
  
  const loadRiwayat = async () => {
    isAfterSave.value = false
    isloadingLAMPAU.value = true
    await setAutoFill()
  
    const response = await useApi().get(
      `/emr/get-emr-cppt?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    isloadingLAMPAU.value = false
    if (response.length) {
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
  
      // console.log(response)
      // debugger
      // console.log(response)
      for (let x = 0; x < response[0].details.length; x++) {
        const element = response[0].details[x];
        element.tgl = new Date(element.tgl);
        element.tglVerifikasi = new Date(element.tglVerifikasi);
  
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
      
      // setValueDisabled()
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
    }
  
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
  
  const loadRiwayatOld = async () => {
    isloadingLAMPAU.value = true
    let paramsPD = ``
    console.log(flagemr)
    // if(isCPPTOLD.value == true){
    //      paramsPD = ``
    // }
    // await sleep(1000)
    useApi().get(
      `/emr/get-emr-cppt?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}&flag=${flagemr != null ? flagemr : kelompokUser}`).then(async (response: any) => {
  
        isloadingLAMPAU.value = false
        if (response.length) {
          let dataOLD = []
          for (let x = 0; x < response.length; x++) {
            const element = response[x];
            element.show = false
            if (element.registrasi.norec_pd != props.registrasi.norec_pd) {
              for (let y = 0; y < element.details.length; y++) {
                const element2 = element.details[y];
                element2.tgl = H.formatDate(new Date(element2.tgl), 'YYYY-MM-DD HH:mm')
                element2.tglVerifikasi = H.formatDate(new Date(element2.tglVerifikasi), 'YYYY-MM-DD HH:mm')
              }
              element.riwayatResep = []
              dataOLD.push(element)
            }
          }
          input2.value.sort((a, b) => new Date(b.registrasi.tglregistrasi) - new Date(a.registrasi.tglregistrasi));
          input2.value = dataOLD
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
            // async function fetchDataAndPushDiagnosa(element, item) {
  
            //   for (let x = 0; x < element.details.length; x++) {
            //     const elementxx = element.details[x];
            //     if (elementxx.NamaDiagnosaICD10 && elementxx.NamaDiagnosaICD10 !== '' && elementxx.NamaDiagnosaICD10 !== null) {
            //       await fetchDiagnosa10({ query: elementxx.NamaDiagnosaICD10 });
            //       await new Promise(resolve => setTimeout(resolve, 1000));
            //       if (d_Diagnosa10.value.length) {
            //         for (let z = 0; z < d_Diagnosa10.value.length; z++) {
            //           const diagnosa = d_Diagnosa10.value[z];
  
            //           let newDiagnosa = {
            //             'jenisDiagnosa': { label: diagnosa.JenisDiagnosa },
            //             'keterangan': diagnosa.NamaDiagnosaDokter,
            //             'diagnosaa': { label: diagnosa.kddiagnosa + '-' + diagnosa.namadiagnosa, value: diagnosa.id },
            //           };
  
            //           elementxx.diagnosaDokter = elementxx.diagnosaDokter || [];
            //           elementxx.diagnosaDokter.push(newDiagnosa);
            //         }
            //       }
            //     }
            //   }
            // }
            // async function processElements() {
            //   let promises = [];
            //   input2.value.forEach(element => {
            //     responseYX.forEach(item => {
            //       if (item.NoPendaftaran == element.registrasi.noregistrasi) {
            //         promises.push(fetchDataAndPushDiagnosa(element, item));
            //       }
            //     });
            //   });
            //   await Promise.all(promises);
            // }
            // processElements();
          } else {
            H.alert('warning', 'Data Tidak Ada')
          }
        }
        // if (input2.value.length == 0) {
        //   H.alert('warning', 'Data Tidak Ada')
        // }
        input2.value.sort((objA, objB) => {
          // console.log(objA.registrasi.tglregistrasi);
          // console.log(objB.registrasi.tglregistrasi);
          const dateA = new Date(objA.registrasi.tglregistrasi);
          const dateB = new Date(objB.registrasi.tglregistrasi);
          return dateB - dateA;
        })
      })
  }
  loadRiwayatOld()
  const simpan = (e: any) => {
    // console.log(input.value.details)
    isDialogSave.value = true
  }
  const simpanReal = async () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}
    if (input.value.details) {
      for (let x = 0; x < input.value.details.length; x++) {
        const element = input.value.details[x];
        if (element.tgl2 != undefined) delete element.tgl2;
        if (element.SOAP2 != undefined) delete element.SOAP2;
        if (element.intruksiPPA2 != undefined) delete element.intruksiPPA2;
        if (element.keteranganVerifikasiDPJP2 != undefined) delete element.keteranganVerifikasiDPJP2;
        if (element.tglVerifikasi2 != undefined) delete element.tglVerifikasi2;
        if (element.dokterDPJP2 != undefined) delete element.dokterDPJP2;
        if (element.tenagaMedis2 != undefined) delete element.tenagaMedis2;
        if (element.flag == 'dokter') {
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
        // if(element.flag == 'dokter'){
        //   if(element.diagnosaDokter)
        //   element.diagnosaDokter.forEach((data, index) => {
        //     if (!data.norecDiagnosa) element.diagnosaDokter.splice(index, 1)
        //   });
        //   if(element.diagnosaDokter9)
        //   element.diagnosaDokter9.forEach((data, index) => {
        //     if (!data.norecDiagnosa9) element.diagnosaDokter9.splice(index, 1)
        //   });
        // }
  
      }
    }
  
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
      `/emr/simpan-emr-cppt`, json).then((response: any) => {
        isLoading.value = false
        isDialogSave.value = false
  
        NOREC_EMRPASIEN.value = response.norec_emr
        input.value.id = response.id
        // setValueDisabled()
      }).catch((e: any) => {
        isLoading.value = false
      })
  }
  
  const kembaliKeun = () => {
    window.history.back()
  }
  const fetchDiagnosaKeperawatan = async (filter: any) => {
    await useApi().get(`/emr/list-diagnosa-keperawatan?query=${filter.query}`).then((response) => {
      d_DiagnosaKeperawatan.value = response.diagnosaKeperawatan.map((e: any) => {
        return { value: e.id, label: e.diagnosakep, default: e }
      })
    })
  }
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
  
  const fetchDokter = async (filter: any) => {
    const response = await useApi().get(
      `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`)
    d_Dokter.value = response
  }
  
  const fetchPegawai = async (filter: any) => {
    const response = await useApi().get(
      `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_Pegawai.value = response
  }
  
  const addNewItem = (e: any) => {
    let push: any = {}
  
    if (e.flag == 'dokter') {
      let diagnosaDokter = []
      let diagnosaDokter9 = []
      input.value.details.forEach((data, index) => {
        if (!data.norecDiagnosa && data.flag == 'dokter') diagnosaDokter = data.diagnosaDokter
        if (!data.norecDiagnosa9 && data.flag == 'dokter') diagnosaDokter9 = data.diagnosaDokter9
      });
      push = {
        uuid: uuidv4(),
        no: input.value.details[input.value.details.length - 1].no + 1,
        tgl: new Date(),
        tglVerifikasi: new Date(),
        flag: 'dokter',
        diagnosaDokter: diagnosaDokter,
        diagnosaDokter9: diagnosaDokter9,
      }
  
    }
    if (e.flag == 'perawat') {
      push = {
        uuid: uuidv4(),
        no: input.value.details[input.value.details.length - 1].no + 1,
        tgl: new Date(),
        tglVerifikasi: new Date(),
        flag: 'perawat',
        diagnosaKep: [{
          no: 1
        }],
        tujuanKep: [{
          no: 1
        }]
      }
  
    }
    if (e.flag == 'profesi lain') {
      push = {
        uuid: uuidv4(),
        no: input.value.details[input.value.details.length - 1].no + 1,
        tgl: new Date(),
        tglVerifikasi: new Date(),
        flag: 'profesi lain'
      }
    }
    if (e.flag == 'gizi') {
      push = {
        uuid: uuidv4(),
        no: input.value.details[input.value.details.length - 1].no + 1,
        tgl: new Date(),
        tglVerifikasi: new Date(),
        flag: 'gizi'
      }
    }
    input.value.details.push(push);
  }
  const removeItem = (index: any) => {
    input.value.details.splice(index, 1)
  }
  const setAutoFill = async () => {
    input.value.dpjpUtama = props.registrasi.dokter
    // await fetchDokter({ query: props.registrasi.dokter })
    input.value.details.forEach((element: any) => {
      if (props.registrasi.objectpegawaifk)
        element.dokterDPJP = { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk }
      element.tenagaMedis = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
    });
    fetchDiagnosaX()
    fetchDiagnosaIX()
    await useApi().get(
      "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
      "&collection=VitalSign" + "&field=tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi,beratBadan,SPO2"
    ).then((response) => {
      if (response == null) return
      let data = ''
      data += response.suhu ? `     Suhu : ${response.suhu} °C\n` : ''
      data += response.nadi ? `     Nadi : ${response.nadi} x/mnt\n` : ''
      data += response.pernapasan ? `     Pernafasan : ${response.pernapasan} x/mnt\n` : ''
      data += response.tekananDarah ? `     Tekanan Darah : ${response.tekananDarah} mmHg\n` : ''
      data += response.tinggiBadan ? `     Tinggi Badan : ${response.tinggiBadan} Cm\n` : ''
      data += response.beratBadan ? `     Berat Badan : ${response.beratBadan} Kg\n` : ''
      data += response.SPO2 ? `     SPO2 : ${response.SPO2} %\n` : ''
      data += response.IMT ? `     IMT : ${response.IMT}\n` : ''
  
      input.value.details[1].O = data != '' ? `${data}` : ''
    })
    let idDiagKep = 0
    let idTujuanKep = 0
    let idIntervensiKep = 0
    let idPlanKep = 0
    await useApi().get(
      "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
      "&collection=pengajianAwalRawatJalan" + "&field=alasanKunjunagn,riwayatPenyakitDahulu,riwayatObat,riwayatAlergi,diagnosaKeperawatn,tujuanKeperawatan,intervensiKeperawatan,implementasiKeperawatan"
    ).then((response) => {
      if (response != null) {
        let data = ''
        data += response.alasanKunjunagn ? `     Alasan Kunjungan : ${response.alasanKunjunagn}\n` : ''
        data += response.riwayatPenyakitDahulu ? `     Riwayat Penyakit : ${response.riwayatPenyakitDahulu}\n` : ''
        data += response.riwayatObat ? `     Riwayat Obat : ${response.riwayatObat}\n` : ''
        data += response.riwayatAlergi ? `     Riwayat Alergi : ${response.riwayatAlergi}\n` : ''
        input.value.details[1].S = data != '' ? `${data}` : ''
        idDiagKep = response.diagnosaKeperawatn
        idTujuanKep = response.tujuanKeperawatan
        idIntervensiKep = response.intervensiKeperawatan
        idPlanKep = response.implementasiKeperawatan
      }
    })
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
        if (element.tenagaMedis) {
          if (element.tenagaMedis.value != pegawaiId) {
            element.tgl2 = true
            element.SOAP2 = true
            element.intruksiPPA2 = true
            element.tenagaMedis2 = true
          }
        }
        if (element.dokterDPJP) {
          if (element.dokterDPJP.value != pegawaiId) {
            element.keteranganVerifikasiDPJP2 = true//element.keteranganVerifikasiDPJP ? true:false
            element.dokterDPJP2 = element.dokterDPJP ? true : false
            element.tglVerifikasi2 = element.tglVerifikasi ? true : false
          }
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
    // console.log(e)
  
    // navigator.clipboard.readText().then(function (clipboardText) {
    //   // e.value = clipboardText
    //   // console.log(e)
    //   // Melakukan sesuatu dengan teks yang telah dibaca
    //   console.log("Teks yang disalin: ", clipboardText);
    // }).catch(function (err) {
    //   H.alert('warning','Klik Allow untuk izin paste')
    //   // Penanganan kesalahan, jika ada
    //   // console.error('Gagal membaca teks dari clipboard: ', err);
    // });
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
  const dataSourcefiltered = computed(() => {
    if (!item.filter) {
      return input.value.details
    }
  
    return input.value.details.filter((items: any) => {
      return (
        items.flag.match(new RegExp(item.filter, 'i'))
      )
    })
  })
  
  const copy = (e: any) => {
    console.log(e)
    console.log('Ini masuk Copy')
    console.log(panjangsoapbaru.length)
    if(panjangsoapbaru.length == 0){
      if (localStorage.getItem('cppt_copy') != null) {
        let data = JSON.parse(localStorage.getItem('cppt_copy'))
        data.tgl = new Date()
        input.value.details[0] = data
        H.alert('info', 'Berhasil diterapkan')
      } else {
        H.alert('error', 'Belum ada data yang dicopy')
      }
    } else{
      if (localStorage.getItem('cppt_copy') != null) {
        let data = JSON.parse(localStorage.getItem('cppt_copy'))
        data.tgl = new Date()
        input.value.details[panjangsoapbaru.length] = data
        H.alert('info', 'Berhasil diterapkan')
      } else {
        H.alert('error', 'Belum ada data yang dicopy')
      }
    }
    localStorage.removeItem('cppt_copy')
    localStorage.setItem('cppt_copy', JSON.stringify(e))
    console.log(localStorage.getItem('cppt_copy'))
    isCPPTOLD.value = false
  }
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
  const fetchDiagnosa10 = async (filter: any) => {
    let query = ''
    if (filter) {
      query = filter.query.toLowerCase()
    }
  
    const response = await useApi().get(
      `/diagnosa/diagnosa-x-paging?name=${query}&limit=10`)
    d_Diagnosa10.value = response.diagnosa
  
  }
  const fetchJenisDiagnosa = async (filter: any) => {
    const response = await useApi().get(
      `/emr/dropdown/jenisdiagnosa_m?select=id,jenisdiagnosa&param_search=jenisdiagnosa&query=${filter.query}&limit=10`)
    d_JenisDiagnosa.value = response
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
        item.filter = isPerawat.value ? 'perawat' : '';
        flagemr = isPerawat.value.toString() ? 'perawat' : '';
        loadRiwayatOld()
        console.log(flagemr)
        break;
      case 'dokter':
        isDokter.value = isDokter.value == true ? true : false
        isPerawat.value = false
        isProfesi.value = false
        isGizi.value = false
        item.filter = isDokter.value ? 'dokter' : '';
        flagemr = isDokter.value.toString() ? 'dokter' : '';
        loadRiwayatOld()
        console.log(flagemr)
        break;
      case 'profesi lain':
        isProfesi.value = isProfesi.value == true ? true : false
        isDokter.value = false
        isPerawat.value = false
        isGizi.value = false
        item.filter = isProfesi.value ? 'profesi lain' : '';
        flagemr = '';
        loadRiwayatOld()
        console.log(flagemr)
        break;
      case 'gizi':
        isGizi.value = isGizi.value == true ? true : false
        isProfesi.value = false
        isDokter.value = false
        isPerawat.value = false
        item.filter = isGizi.value ? 'gizi' : ' ';
        flagemr = isGizi.value.toString() ? 'gizi' : ' ';
        loadRiwayatOld()
        console.log(flagemr)
        break;
      default:
        item.filter = '';
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
  watch(
    () => isCPPTOLD.value, () => {
      if (isCPPTOLD.value) {
        loadRiwayatOld()
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
  watch(
    () => kelompokUser,
    (newKelompokUser, oldKelompokUser) => {
      if (newKelompokUser === 'dokter' && !isDokter.value) {
        isDokter.value = true;
        item.filter = 'dokter';
        isPerawat.value = false;
        isProfesi.value = false;
        isGizi.value = false
      }
    },
    { immediate: true }
  );
  
  // watch(
  //   () => isPerawat.value,
  //   (newValue, oldValue) => {
  
  //     if (newValue != oldValue) {
  //       if (newValue) {
  //         item.filter = 'perawat'
  //         isDokter.value = false
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
  watch(
    () => kelompokUser,
    (newKelompokUser, oldKelompokUser) => {
      if (newKelompokUser === 'perawat' && !isPerawat.value) {
        isPerawat.value = true;
        item.filter = 'perawat';
        isDokter.value = false;
        isProfesi.value = false;
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
  watch(
    () => kelompokUser,
    (newKelompokUser, oldKelompokUser) => {
      if (newKelompokUser !== 'dokter' && newKelompokUser !== 'perawat' && !isProfesi.value) {
        isProfesi.value = true;
        item.filter !== 'dokter' && newKelompokUser !== 'perawat';
        isDokter.value = false;
        isPerawat.value = false;
        isGizi.value = false
      }
    },
    { immediate: true }
  );
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
      H.printBlade(`emr/cetak/${props.COLLECTION}?pdf=false&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  }
  watch(
    () => kelompokUser,
    (newKelompokUser, oldKelompokUser) => {
      if (newKelompokUser !== 'dokter' && newKelompokUser !== 'perawat' && !isProfesi.value) {
        isGizi.value = true;
        item.filter !== 'dokter' && newKelompokUser !== 'perawat';
        isDokter.value = false;
        isPerawat.value = false;
        isProfesi.value = false
      }
    },
    { immediate: true }
  );
  watch(
    () => isResep.value, (newValue, oldValue) => {
      if (newValue != oldValue) {
        if (newValue) {
          showPreviewResep.value = true
        }
      }
    }
  )
  onBeforeMount(async() => {
    try {
      await loadRiwayat()
      let cache =  H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
      if(cache) input.value = cache
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
  
  setView()
  
  
  
  // fetchDiagnosaX()
  </script>
  
  <style lang="scss">
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
  
  .switch-profesi{
    font-size: 10px; font-weight: bold;  width: 10px; height: 10px;
  }
  
  .tg .tg-0lax {
    text-align: left;
    vertical-align: top
  }
  
  .scroll-container-rev {
    height: 1000px;
    overflow: auto;
  }
  
  .tombol-cppt{
    float:right; margin-right: 5px; height: 25px; margin-top: -20px;
  }
  
  @media (max-width: 1144px) {
  
    .table-tg {
      width: 150%;
    }
  }</style>
  