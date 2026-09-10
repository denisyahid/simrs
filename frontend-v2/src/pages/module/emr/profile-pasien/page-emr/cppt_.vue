<template>
    <div class="form-layout is-stacked-2" style="
    width: 100%;
    max-width: none;">
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
    <Fieldset legend="Catatan Pasien Terintegrasi Rawat Inap" :toggleable="true">
      <p class="m-0">
      <div class="columns is-multiline p-2">
        <div class="column is-12">
          <VCard>
            <div class="columns is-multiline">
              <div class="column is-2 mt-5">
                <VControl>
                  <VSwitchBlock v-model="isCPPTOLD" label="Riwayat CPPT" color="danger" />
                </VControl>
              </div>
              <div class="column is-4" style=" margin-top: 2.8rem;" v-if="isloadingLAMPAU">
                <VProgress size="tiny" color="info" />
              </div>
              <div class="column is-12" v-if="isCPPTOLD && input2.length">
                <VCard class="tg-card">
                  <h3 class="title is-5 mb-2">Riwayat CPPT</h3>
                  <div class="columns is-multiline mt-3">
                    <div class="column is-12 CPPT_HEIGHT">
                      <table class="tg">
                        <thead>
                        </thead>
                        <tbody v-for="(itemss, index) in input2" :key="index">
                          <tr style="background: #d6d4d4;">
                            <td>
                              <VIconButton circle icon="feather:chevron-down" raised bold v-tooltip.bubble="'Hide Expand'"
                                v-if="itemss.show" @click="itemss.show = false"> </VIconButton>
                              <VIconButton circle icon="feather:chevron-right" raised bold v-tooltip.bubble="' Expand'"
                                v-if="!itemss.show" @click="itemss.show = true"> </VIconButton>
  
                            </td>
                            <td>
                              <span class="text-normal-1 bold">
                                <b> {{ itemss.registrasi ? itemss.registrasi.noregistrasi : '' }}
                                  - {{ itemss.registrasi ? H.formatDateIndo(itemss.registrasi.tglregistrasi) : '' }}
                                  - {{ itemss.registrasi ? itemss.registrasi.namaruangan : '' }}
                                  - {{ itemss.registrasi ? itemss.noemr : '' }}</b>
                              </span>
                            </td>
                          </tr>
                          <tr v-for="(item, index2) in itemss.details" :key="index2" v-if="itemss.show">
                            <td colspan="2" style="size:100%;">
                              <table class="tg">
  
                                <!-- <td colspan="2" style="size:100%;"> -->
                                <tr>
  
                                  <td colspan="2">
  
                                    <span style="font-weight: bold;">Tanggal / Jam</span>
                                    <VField class="mt-2">
                                      <VDatePicker v-model="item.tgl" mode="dateTime" style="width: 100%" trim-weeks
                                        :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }">
                                          <VField>
                                            <VControl icon="feather:calendar" fullwidth>
                                              <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents"
                                                :disabled="true" />
                                            </VControl>
                                          </VField>
                                        </template>
                                      </VDatePicker>
                                    </VField>
  
                                  </td>
  
                                </tr>
                                <tr>
                                  <td class="tg-0lax" width="10%" style="text-align:center;vertical-align: inherit;">
                                    <span style="font-size:20pt;font-weight:bold">S</span>
                                  </td>
                                  <td class="tg-0lax">
                                    <VField>
                                      <VControl>
                                        <VTextarea rows="2" v-model="item.hasilSub" :disabled="true">
                                        </VTextarea>
                                      </VControl>
                                    </VField>
                                    <VButton type="button" rounded outlined color="danger" raised icon="lnir lnir-copy"
                                      @click="copyToClipboard(item.hasilSub)"> Copy
                                    </VButton>
                                  </td>
                                </tr>
                                <tr>
                                  <td class="tg-0lax" width="10%" style="text-align:center">
                                    <span style="font-size:20pt;font-weight:bold">O</span>
                                  </td>
                                  <td class="tg-0lax">
                                    <VField>
                                      <VControl>
                                        <VTextarea rows="2" v-model="item.hasilObj" :disabled="true">
                                        </VTextarea>
                                      </VControl>
                                    </VField>
                                    <VButton type="button" rounded outlined color="danger" raised icon="lnir lnir-copy"
                                      @click="copyToClipboard(item.hasilObj)"> Copy
                                    </VButton>
                                  </td>
                                </tr>
                                <tr>
                                  <td class="tg-0lax" width="10%" style="text-align:center;vertical-align: inherit;">
                                    <span style="font-size:20pt;font-weight:bold">A</span>
                                  </td>
                                  <td class="tg-0lax">
                                    <div class="column">
                                      <span style="font-size:11pt;font-weight:bold">Diagnosis Keperawatan</span>
                                      <div class="mt-1">
                                        <table width="100%">
                                          <thead>
                                            <tr>
                                              <th class="td-fkprj" width="2%"
                                                style="vertical-align: inherit;text-align: center">NO
                                              </th>
                                              <th class="td-fkprj" width="50%"
                                                style="vertical-align:inherit;text-align: center;">
                                                Diagnosa
                                                Keperawatan
                                              </th>
  
                                            </tr>
                                          </thead>
                                          <tbody v-for="(item2, index2) in item.details" :key="index2">
                                            <tr>
                                              <td class="tg-0lax" style="vertical-align:inherit;text-align:center">{{
                                                item2.no }}
                                              </td>
                                              <td class="tg-0lax">
                                                <div class="column p-1">
                                                  <VField>
                                                    <VControl class="prime-auto">
                                                      <AutoComplete v-model="item2.diagnosaKeperawatan"
                                                        :suggestions="d_DiagnosaKeperawatan"
                                                        @complete="fetchDiagnosaKeperawatan($event)"
                                                        :optionLabel="'caption'" :dropdown="true" :minLength="3"
                                                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'caption'"
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
  
                                    <div class="column">
                                      <span style="font-size:11pt;font-weight:bold">Diagnosis Dokter</span>
                                      <div style="overflow-y:auto;" class="mt-1">
                                        <table width="120%">
                                          <thead>
                                            <tr>
                                              <th class="td-fkprj" width="2%" style="vertical-align: inherit;">NO</th>
                                              <th class="td-fkprj" width="23%"
                                                style="vertical-align:inherit;text-align: center;">
                                                Jenis
                                                Diagnosa
                                              </th>
                                              <th class="td-fkprj" width="25%"
                                                style="vertical-align:inherit;text-align: center;">
                                                Diagnosa
                                                Dokter
                                              </th>
                                              <th class="td-fkprj" style="vertical-align:inherit;text-align: center;">
                                                Diagnosa
                                                ICD
                                                10</th>
  
                                            </tr>
                                          </thead>
                                          <tbody v-for="(itemsss, index3) in item.diagnosaDokter" :key="index3">
                                            <tr>
                                              <td class="tg-0lax" style="vertical-align:inherit;text-align:center">{{
                                                itemsss.no }}
                                              </td>
                                              <td class="tg-0lax">
                                                <div class="column p-1">
                                                  <VField>
                                                    <VControl class="prime-auto">
                                                      <AutoComplete v-model="itemsss.jenisDiagnosa"
                                                        :suggestions="d_JenisDiagnosa"
                                                        @complete="fetchJenisDiagnosa($event)" :optionLabel="'label'"
                                                        :dropdown="true" :minLength="3" :appendTo="'body'"
                                                        :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                        placeholder="Cari Jenis Diagnosa..." class="mt-2" />
                                                    </VControl>
                                                  </VField>
                                                </div>
                                              </td>
                                              <td class="tg-0lax">
                                                <div class="column pt-3 pb-0">
                                                  <VField>
                                                    <VControl icon="feather:bookmark">
                                                      <VInput type="text" v-model="itemsss.diagnosaDokter"
                                                        placeholder="Diagnosa Dokter" />
                                                    </VControl>
                                                  </VField>
                                                </div>
                                              </td>
                                              <td class="tg-0lax">
                                                <div class="column p-1">
                                                  <VField>
                                                    <VControl class="prime-auto">
                                                      <AutoComplete v-model="itemsss.diagnosaIcd10"
                                                        :suggestions="d_Diagnosa" @complete="fetchDiagnosa($event)"
                                                        :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                        placeholder="Cari Diagnosa ICD 10 ..." class="mt-2" />
                                                    </VControl>
                                                  </VField>
                                                </div>
                                              </td>
                                              <td class="tg-0lax" style="vertical-align: inherit;">
  
                                              </td>
  
                                            </tr>
                                          </tbody>
                                        </table>
                                      </div>
                                    </div>
  
                                  </td>
                                <tr>
  
                                </tr>
  
                          </tr>
                          <tr>
                            <td class="tg-0lax" width="10%" style="text-align:center;vertical-align: inherit;">
                              <span style="font-size:20pt;font-weight:bold">P</span>
                            </td>
                            <td class="tg-0lax">
                              <VField>
                                <VControl>
                                  <VTextarea rows="2" v-model="item.hasilPe" :disabled="true">
                                  </VTextarea>
                                </VControl>
                              </VField>
                              <VButton type="button" rounded outlined color="danger" raised icon="lnir lnir-copy"
                                @click="copyToClipboard(item.hasilPe)"> Copy
                              </VButton>
                            </td>
                          </tr>
                          <tr>
                            <td class="tg-0lax" colspan="2">
                              <div class="columns is-multiline">
                                <div class="column is-2" style="text-align: center;margin-top: 10px;">
                                  <span>DPJP</span>
                                </div>
                                <div class="column">
                                  <VField class="is-autocomplete-select" v-slot="{ id }">
                                    <VControl icon="feather:search">
                                      <AutoComplete v-model="item.dokterDPJP" :suggestions="d_Dokter"
                                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="Cari Dokter" />
                                    </VControl>
                                  </VField>
                                </div>
                              </div>
                              <div class="columns is-multiline">
                                <div class="column is-2" style="text-align: center;margin-top: 10px;">
                                  <span>Perawat</span>
                                </div>
                                <div class="column">
                                  <VField class="is-autocomplete-select" v-slot="{ id }">
                                    <VControl icon="feather:search">
                                      <AutoComplete v-model="input.perawat" :suggestions="d_Pegawai"
                                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="Cari Perawat" />
                                    </VControl>
                                  </VField>
                                </div>
                              </div>
                            </td>
  
                          </tr>
                          <!-- </td> -->
  
                      </table>
                      </td>
                      </tr>
                      </tbody>
                      </table>
                    </div>
                  </div>
                </VCard>
                <div class="content mt-3 mb-0">
                  <div class="is-divider mt-3 mb-2"
                    :data-content="'CPPT Sekarang ' + (input.registrasi ? input.registrasi.noregistrasi : '')"></div>
                </div>
              </div>
  
              <div class="column is-12">
                <table class="tg">
                  <thead>
  
  
                  </thead>
                  <tbody v-for="(item, index) in input.details" :key="index">
                    <tr>
                      <td class="tg-0lax" style="vertical-align: inherit; size:10%;">
                        <div class="column p-0">
                          <VButtons style="justify-content: space-around;">
                            <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                              v-tooltip.bottom.right="'Tambah'">
                            </VIconButton>
                            <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                              @click="removeItem(index)" color="danger">
                            </VIconButton>
                          </VButtons>
                        </div>
                      </td>
                      <td>
                        <div class="columns is-multiline">
                          <div class="column is-2" style="text-align: center;margin-top: 15px;">
                            <span style="font-weight: bold;">Tanggal / Jam</span>
                          </div>
                          <div class="column">
                            <VField class="mt-2">
                              <VDatePicker v-model="item.tgl" mode="dateTime" style="width: 100%" trim-weeks
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
                        </div>
                      </td>
  
                    </tr>
                    <tr>
                      <td class="tg-0lax" width="10%" style="text-align:center;vertical-align: inherit;">
                        <span style="font-size:20pt;font-weight:bold">S</span>
                      </td>
                      <td class="tg-0lax">
                        <VField>
                          <VControl>
                            <VTextarea rows="2" v-model="item.hasilSub">
                            </VTextarea>
                          </VControl>
                        </VField>
                        <!-- <VIconButton color="danger" outlined circle icon="lnil lnil-trash-can-alt-1" @click="clear(index,'hasilSub')" style="float:right" />
                      <VIconButton color="success" outlined circle icon="lnir lnir-copy"  @click="copyToClipboard(item.hasilSub)" style="float:right; margin-left: -30px;" /> -->
                        <VButton type="button" rounded outlined color="danger" raised icon="lnir lnir-copy"
                          @click="copyToClipboard(item.hasilSub)" style="float:left" v-if="kelompokUser == 'dokter'"> Copy
                        </VButton>
  
                        <VButton type="button" rounded outlined color="danger" raised icon="lnir lnir-copy"
                          @click="clear(index, 'hasilSub')" style="float:left; margin-left: 20px" v-if="kelompokUser == 'dokter'"> Clear
                        </VButton>
  
  
                      </td>
                    </tr>
                    <tr>
                      <td class="tg-0lax" width="10%" style="text-align:center">
                        <span style="font-size:20pt;font-weight:bold">O</span>
                      </td>
                      <td class="tg-0lax">
                        <VField>
                          <VControl>
                            <VTextarea rows="2" v-model="item.hasilObj">
                            </VTextarea>
                          </VControl>
                        </VField>
                        <VButton type="button" rounded outlined color="danger" raised icon="lnir lnir-copy"
                          @click="copyToClipboard(item.hasilObj)" style="float:left" v-if="kelompokUser == 'dokter'"> Copy
                        </VButton>
  
                        <VButton type="button" rounded outlined color="danger" raised icon="lnir lnir-copy"
                          @click="clear(index, 'hasilObj')" style="float:left; margin-left: 20px" v-if="kelompokUser == 'dokter'"> Clear
                        </VButton>
  
  
                      </td>
                    </tr>
                    <tr>
                      <td class="tg-0lax" width="10%" style="text-align:center;vertical-align: inherit;">
                        <span style="font-size:20pt;font-weight:bold">A</span>
                      </td>
                      <td class="tg-0lax">
  
                        <div class="column">
                          <VField>
                          <VControl>
                            <VTextarea rows="1" v-model="item.hasilA">
                            </VTextarea>
                          </VControl>
                        </VField>
  
                        </div>
                        
                        <div class="column">
                          <span style="font-size:11pt;font-weight:bold">Diagnosis Keperawatan</span>
                          <div class="mt-1">
                            <table width="100%">
                              <thead>
                                <tr>
                                  <th class="td-fkprj" width="2%" style="vertical-align: inherit;text-align: center">NO</th>
                                  <th class="td-fkprj" width="50%" style="vertical-align:inherit;text-align: center;">
                                    Diagnosa
                                    Keperawatan
                                  </th>
                                  <th class="td-fkprj" rowspan="2" style="vertical-align:inherit;text-align: center;"
                                    width="7%">#</th>
                                </tr>
                              </thead>
                              <tbody v-for="(item2, index2) in item.details" :key="index2">
                                <tr>
                                  <td class="tg-0lax" style="vertical-align:inherit;text-align:center">{{ item2.no }}</td>
                                  <td class="tg-0lax">
                                    <div class="column p-1">
                                      <VField>
                                        <VControl class="prime-auto">
                                          <AutoComplete v-model="item2.diagnosaKeperawatan"
                                            :suggestions="d_DiagnosaKeperawatan"
                                            @complete="fetchDiagnosaKeperawatan($event)" :optionLabel="'caption'"
                                            :dropdown="true" :minLength="3" :appendTo="'body'"
                                            :loadingIcon="'pi pi-spinner'" :field="'caption'"
                                            placeholder="Cari Diagnosa Keperawatan ..." class="mt-2" />
                                        </VControl>
                                      </VField>
                                    </div>
                                  </td>
                                  <td class="tg-0lax" style="vertical-align: inherit;">
                                    <div class="column p-0">
                                      <div class="columns is-multiline">
                                        <div class="column is-6">
                                          <VIconButton type="button" raised circle icon="feather:plus"
                                            @click="addNewItem2(index)" outlined color="info"
                                            v-tooltip.bubble="'Tambah Petugas '">
                                          </VIconButton>
                                        </div>
                                        <div class="column is-6 ml-3-min">
                                          <VIconButton v-if="index2 > 0" type="button" raised circle outlined
                                            icon="feather:trash" @click="removeItem2(index, index2)" color="danger">
                                          </VIconButton>
                                        </div>
                                      </div>
                                    </div>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
  
                        <div class="column">
                          <span style="font-size:11pt;font-weight:bold">Diagnosis Dokter</span>
                          <div style="overflow-y:auto;" class="mt-1">
                            <table width="120%">
                              <thead>
                                <tr>
                                  <th class="td-fkprj" width="2%" style="vertical-align: inherit;">NO</th>
                                  <th class="td-fkprj" width="23%" style="vertical-align:inherit;text-align: center;">Jenis
                                    Diagnosa
                                  </th>
                                  <th class="td-fkprj" width="25%" style="vertical-align:inherit;text-align: center;">
                                    Diagnosa
                                    Dokter
                                  </th>
                                  <th class="td-fkprj" style="vertical-align:inherit;text-align: center;">Diagnosa ICD 10
                                  </th>
                                  <th class="td-fkprj" rowspan="2" style="vertical-align:inherit;text-align: center;"
                                    width="12%">
                                    #
                                  </th>
                                </tr>
                              </thead>
                              <tbody v-for="(itemsss, index3) in item.diagnosaDokter" :key="index3">
                                <tr>
                                  <td class="tg-0lax" style="vertical-align:inherit;text-align:center">{{ itemsss.no }}</td>
                                  <td class="tg-0lax">
                                    <div class="column p-1">
                                      <VField>
                                        <VControl class="prime-auto">
                                          <AutoComplete v-model="itemsss.jenisDiagnosa" :suggestions="d_JenisDiagnosa"
                                            @complete="fetchJenisDiagnosa($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Cari Jenis Diagnosa..." class="mt-2" />
                                        </VControl>
                                      </VField>
                                    </div>
                                  </td>
                                  <td class="tg-0lax">
                                    <div class="column pt-3 pb-0">
                                      <VField>
                                        <VControl icon="feather:bookmark">
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
                                            :field="'label'" placeholder="Cari Diagnosa ICD 10 ..." class="mt-2" />
                                        </VControl>
                                      </VField>
                                    </div>
                                  </td>
                                  <td class="tg-0lax" style="vertical-align: inherit;">
                                    <div class="column p-0">
                                      <div class="columns is-multiline">
                                        <div class="column is-6">
                                          <VIconButton type="button" raised circle icon="feather:plus"
                                            @click="addNewItem3(index)" outlined color="info"
                                            v-tooltip.bubble="'Tambah Petugas '">
                                          </VIconButton>
                                        </div>
                                        <div class="column is-6 ml-3-min">
                                          <VIconButton v-if="index3 > 0" type="button" raised circle outlined
                                            icon="feather:trash" @click="removeItem3(index, index3)" color="danger">
                                          </VIconButton>
                                        </div>
                                      </div>
                                    </div>
                                  </td>
  
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
  
                      </td>
                    <tr>
  
                    </tr>
  
                    </tr>
                    <tr>
                      <td class="tg-0lax" width="10%" style="text-align:center;vertical-align: inherit;">
                        <span style="font-size:20pt;font-weight:bold">P</span>
                      </td>
                      <td class="tg-0lax">
                        <VField>
                          <VControl>
                            <VTextarea rows="2" v-model="item.hasilPe">
                            </VTextarea>
                          </VControl>
                        </VField>
                        <VButton type="button" rounded outlined color="danger" raised icon="lnir lnir-copy"
                          @click="copyToClipboard(item.hasilPe)" style="float:left" v-if="kelompokUser == 'dokter'"> Copy
                        </VButton>
  
                        <VButton type="button" rounded outlined color="danger" raised icon="lnir lnir-copy"
                          @click="clear(index, 'hasilPe')" style="float:left; margin-left: 20px" v-if="kelompokUser == 'dokter'"> Clear
                        </VButton>
                      </td>
                    </tr>
                    <tr>
                      <td class="tg-0lax" colspan="2">
                        <div class="columns is-multiline">
                          <div class="column is-2" style="text-align: center;margin-top: 10px;">
                            <span>DPJP</span>
                          </div>
                          <div class="column">
                            <VField class="is-autocomplete-select" v-slot="{ id }">
                              <VControl icon="feather:search">
                                <AutoComplete v-model="item.dokterDPJP" :suggestions="d_Dokter"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                  placeholder="Cari Dokter" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                        <div class="columns is-multiline">
                          <div class="column is-2" style="text-align: center;margin-top: 10px;">
                            <span>Perawat</span>
                          </div>
                          <div class="column">
                            <VField class="is-autocomplete-select" v-slot="{ id }">
                              <VControl icon="feather:search">
                                <AutoComplete v-model="item.tenagaMedis" :suggestions="d_Pegawai"
                                  @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                  placeholder="Cari Perawat" :disabled="item.tenagaMedis2" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                  
                      </td>
  
                    </tr>
  
                  </tbody>
                </table>
              </div>
  
            </div>
          </VCard>
        </div>
      </div>
      </p>
    </Fieldset>
    <br>
    <Fieldset legend="Order Resep" :toggleable="true">
      <p class="m-0">
        <OrderResep v-if="props.registrasi" :pasien="props.pasien" :registrasi="props.registrasi" :hilangkanStuck="true"></OrderResep>
      </p>
    </Fieldset>
  </template>
  
  <script setup lang="ts">
  import { useWindowScroll } from '@vueuse/core'
  import { useApi } from '/@src/composable/useApi'
  import { h, reactive, ref, computed, defineComponent, watch, onMounted, PropType } from 'vue'
  import { useRoute, useRouter } from 'vue-router'
  import { useHead } from '@vueuse/head'
  import * as H from '/@src/utils/appHelper'
  import { useViewWrapper } from '/@src/stores/viewWrapper'
  import { useThemeColors } from '/@src/composable/useThemeColors'
  import { useUserSession } from '/@src/stores/userSession'
  import AutoComplete from 'primevue/autocomplete';
  import Fieldset from 'primevue/fieldset';
  import Dropdown from 'primevue/dropdown';
  import Calendar from 'primevue/calendar';
  import ButtonEmr from '../page-emr-plugins/button-emr.vue'
  import OrderResep from './order-resep.vue'
  
  
  
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
  
  // useViewWrapper().setFullWidth(props.pasien ? true : false)
  const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
  const { y } = useWindowScroll()
  const isStuck = computed(() => { return y.value > 30 })
  const isLoading: any = ref(false)
  const isloadingLAMPAU: any = ref(false)
  const isCPPTOLD: any = ref(false)
  const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
  })
  const COLLECTION: any = ref(props.COLLECTION) //table mongodb
  const NOREC_EMRPASIEN: any = ref('')
  const pegawaiId = useUserSession().getUser().pegawai.id
  const NamaPegawai = useUserSession().getUser().pegawai.namalengkap
  const input: any = ref({
    details: [{
      no: 1,
      tgl: new Date(),
      tglVerifikasi: new Date(),
      details: [{
        no: 1,
      }],
      diagnosaDokter: [{
        no: 1,
      }]
    }]
  })
  const input2: any = ref({
    registrasi: {},
    details: []
  })
  const d_Dokter: any = ref([])
  const d_Pegawai: any = ref([])
  const d_DiagnosaKeperawatan: any = ref([])
  const d_JenisDiagnosa: any = ref([])
  const d_Diagnosa: any = ref([])
  
  const setView = () => {
    useHead({
      title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
  }
  const loadRiwayat = async () => {
    await setAutoFill()
    // if (NOREC_EMRPASIEN.value == '') return
    useApi().get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
        if (response.length) {
          for (let x = 0; x < response[0].details.length; x++) {
            const element = response[0].details[x];
            element.tgl = H.formatDate(new Date(element.tgl), 'YYYY-MM-DD HH:mm')
            element.tglVerifikasi = H.formatDate(new Date(element.tglVerifikasi), 'YYYY-MM-DD HH:mm')
          }
          input.value = response[0] //set ke inputan 
          setValueDisabled()
          if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
          }
        }
      })
  }
  
  
  const loadRiwayatOld = async () => {
    isloadingLAMPAU.value = true
    let paramsPD = ``
    useApi().get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((response: any) => {
        isloadingLAMPAU.value = false
        if (response.length) {
          let dataOLD = []
          for (let x = 0; x < response.length; x++) {
            const element = response[x];
            element.show = true
            if (element.registrasi.norec_pd != props.registrasi.norec_pd) {
              for (let y = 0; y < element.details.length; y++) {
                const element2 = element.details[y];
                element2.tgl = H.formatDate(new Date(element2.tgl), 'YYYY-MM-DD HH:mm')
                element2.tglVerifikasi = H.formatDate(new Date(element2.tglVerifikasi), 'YYYY-MM-DD HH:mm')
              }
              dataOLD.push(element)
            }
          }
          input2.value = dataOLD
        } else {
          H.alert('warning', 'Data Tidak Ada')
        }
      })
  }
  
  const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
  
    let object: any = {}
  
    if (input.value.details) {
      for (let x = 0; x < input.value.details.length; x++) {
        const element = input.value.details[x];
        if (element.tgl2 != undefined) delete element.tgl2;
        if (element.hasilSub2 != undefined) delete element.hasilSub2;
        if (element.hasilObj2 != undefined) delete element.hasilObj2;
        if (element.tenagaMedis2 != undefined) delete element.tenagaMedis2;
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
      `/emr/simpan-emr`, json).then((response: any) => {
        isLoading.value = false
        NOREC_EMRPASIEN.value = response.norec_emr
        input.value.id = response.id
        setValueDisabled()
      }).catch((e: any) => {
        isLoading.value = false
      })
  }
  const setValueDisabled = () => {
      if (input.value.details) {
          for (let x = 0; x < input.value.details.length; x++) {
              const element = input.value.details[x];
              if(element.tenagaMedis){
                  if(element.tenagaMedis.value != pegawaiId){
                      element.tenagaMedis2 = true
                  }
              }
              if(element.dokterDPJP){
                  if(element.dokterDPJP.value != pegawaiId){
                      element.keteranganVerifikasiDPJP2 =   element.keteranganVerifikasiDPJP ? true:false
                      element.dokterDPJP2 =  element.dokterDPJP ? true:false
                
                  }
              }
          }
      }
  }
  
  const kembaliKeun = () => {
    window.history.back()
  }
  const fetchDiagnosa = async (filter: any) => {
    const response = await useApi().get(
      `/emr/dropdown/diagnosa_m?select=kddiagnosa,namadiagnosa&param_search=kddiagnosa&query=${filter.query}&limit=10`)
    d_Diagnosa.value = response
  }
  const fetchPegawai = async (filter: any) => {
  
    await useApi().get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
      d_Pegawai.value = response
    })
  }
  const fetchJenisDiagnosa = async (filter: any) => {
    const response = await useApi().get(
      `/emr/dropdown/jenisdiagnosa_m?select=id,jenisdiagnosa&param_search=jenisdiagnosa&query=${filter.query}&limit=10`)
    d_JenisDiagnosa.value = response
  }
  const fetchDokter = async (filter: any) => {
    const response = await useApi().get(
      `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_Dokter.value = response
  }
  
  const addNewItem = () => {
    input.value.details.push({
      no: input.value.details[input.value.details.length - 1].no + 1,
      tgl: new Date(),
      tglVerifikasi: new Date(),
      details: [{
        no: 1,
      }],
      diagnosaDokter: [{
        no: 1,
      }]
    });
  }
  const removeItem = (index: any) => {
    input.value.details.splice(index, 1)
  }
  
  
  const fetchDiagnosaKeperawatan = async (filter: any) => {
    await useApi().get(`/emr/get-dropdown-diagnosa-keperawatan?search=${filter.query}`).then((response) => {
      d_DiagnosaKeperawatan.value = response
    })
  }
  
  const addNewItem2 = (item2: number) => {
    input.value.details[item2].details.push({
      no: input.value.details[item2].details[input.value.details[item2].details.length - 1].no + 1,
    })
  }
  const removeItem2 = (index: any, index2: any) => {
    input.value.details[index].details.splice(index2, 1)
  }
  
  const addNewItem3 = (itemsss: number) => {
    input.value.details[itemsss].diagnosaDokter.push({
      no: input.value.details[itemsss].diagnosaDokter[input.value.details[itemsss].diagnosaDokter.length - 1].no + 1,
    })
  }
  const removeItem3 = (index: any, index3: any) => {
    input.value.details[index].diagnosaDokter.splice(index3, 1)
  }
  
  const setAutoFill = async () => {
  
    input.value.dpjpUtama = props.registrasi.dokter
  
    input.value.details.forEach(element => {
      element.dokterDPJP = { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk }
      element.tglVerifikasi = new Date()
      element.tenagaMedis = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
   
    });
  
    await useApi().get(
      "diagnosa/riwayat-diagnosa-x-cppt?noregistrasi=" + props.registrasi.noregistrasi
    ).then((response) => {
      
      for (let x = 0; x <  input.value.details.length; x++) {
        const element =  input.value.details[x];
        if(response.data.length){
          element.diagnosaDokter = []
          for (let z = 0; z < response.data.length; z++) {
            const element2 = response.data[z];
            element.diagnosaDokter.push({
              no: z+1,
              keterangan: element2.keterangan,
              jenisDiagnosa: {label:element2.jenisdiagnosa,value:element2.objectjenisdiagnosafk},
              diagnosaa: {label:element2.namadiagnosa,value:element2.id}
            })
          }
        }
      }
  
    })
  
    await useApi().get(
      "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
      "&collection=pengajianAwalRawatJalan" +
      "&field=alasanKunjunagn,biologi,hubunganPasien,ketLaporkan,statusPsikologisPasien"
    ).then((response) => {
      let data2 = ''
      if(response == null)return
      data2 += response.alasanKunjunagn ? `Alasan Kunjungan : ${response.alasanKunjunagn}` + ' ' + ',' : ''
      data2 += response.biologi ? `Biologi : ${response.biologi}` + ' ' + ',' : ''
      data2 += response.hubunganPasien ? `Hubungan Pasien : ${response.hubunganPasien}` + ' ' + ',' : ''
      data2 += response.ketLaporkan ? `Dilaporkan ke : ${response.ketLaporkan}` + ' ' + ',' : ''
      data2 += response.statusPsikologisPasien ? `Status Psikologis : ${response.statusPsikologisPasien}` + ' ' + ',' : ''
  
  
      input.value.details[0].hasilSub = data2 != '' ? `${data2}` : ''
  
      // input.value.hasilSub = `Alasan Kunjungan : ${response.alasanKunjunagn}, Biologi : ${response.biologi}, Hubungan Pasien : ${response.hubunganPasien}, ` +
      //   `Dilaporkan ke : ${response.ketLaporkan}, Status Psikologis : ${response.statusPsikologisPasien}`
  
    })
  
    await useApi().get(
      "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
      "&collection=VitalSign" + "&field=tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi,beratBadan,SPO2"
    ).then((response) => {
      if (response == null) return
      let data = ''
      data += response.suhu ? `Suhu : ${response.suhu} °C` + ' ' + ',' : ''
      data += response.nadi ? `Nadi : ${response.nadi} x/mnt` + ' ' + ',' : ''
      data += response.pernapasan ? `Pernafasan : ${response.pernapasan} x/mnt` + ' ' + ',' : ''
      data += response.tekananDarah ? `Tekanan Darah : ${response.tekananDarah} mmHg` + ' ' + ',' : ''
      data += response.tinggiBadan ? `Tinggi Badan : ${response.tinggiBadan} Cm` + ' ' + ',' : ''
      data += response.beratBadan ? `Berat Badan : ${response.beratBadan} Kg` + ' ' + ',' : ''
      data += response.SPO2 ? `SPO2 : ${response.SPO2} %` + ' ' + ',' : ''
      data += response.IMT ? `IMT : ${response.IMT}` + ' ' + ',' : ''
  
      input.value.details[0].hasilObj = data != '' ? `${data}` : ''
  
    })
  
  }
  
  const clear = (index: any, element: string) => {
    input.value.details[index][element] = ''
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
  watch(
    () => isCPPTOLD.value, () => {
      if (isCPPTOLD.value) {
        loadRiwayatOld()
      }
    }
  )
  setView()
  loadRiwayat()
  </script>
  
  <style lang="scss">
  .field>label {
    font-family: var(--font);
    font-size: 0.9rem;
    color: black !important;
    font-weight: 400;
  }
  
  .tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
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
    border-color: var(--fade-grey-dark-3);
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
    vertical-align: top
  }
  </style>