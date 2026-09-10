<template>
    <div class="form-layout is-stacked-3">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3> {{ props.FORM_NAME }}</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate"
                @kembaliKeun="kembaliKeun" isHideCetak></ButtonEmr>
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
                        <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;" width="15%">Tanggal Input</td>
                        <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;" width="15%">Tanggal Registrasi</td>
                        <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;" width="15%">No Registrasi</td>
                        <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;" width="15%">No EMR</td>
                        <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;" width="20%">Dokter</td>
                        <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;" width="15%">Section</td>
                        <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;" width="5%">#</td>
                      </tr>
                    </thead>
                    <tbody v-for="resep in listTemplate">
                      <tr>
                        <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                          <span class="mb-2">{{ resep.created_at }}</span><br>
                        </td>
                        <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                          <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                        </td>
                        <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                          <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                        </td>
                        <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                          <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                        </td>
                        <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                          <span class="mb-2">{{ resep.dpjpUtama }}</span><br>
                        </td>
                        <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                          <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                        </td>
                        <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                          <VIconButton type="button" raised circle icon="fas fa-plus" @click="addRiwayat(resep)"
                            color="info" v-tooltip-prime.top="'Pilih'">
                          </VIconButton>
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
              :globalFilterFields="['namatemplate', 'registrasi.namaruangan']" responsiveLayout="stack"
              breakpoint="960px">
              <template #header>
                <div class="columns is-multiline">
                  <div class="column is-8">
                    <VField>
                      <InputText v-model="filtersTemplate['global'].value" placeholder="Search Nama Template" />
                    </VField>
                  </div>
                  <div class="column is-4"></div>
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
                    <VIconButton color="danger" light raised circle icon="lucide:x"
                      @click="deleteTemplate(slotProps.data.id)" v-if="!isAlltemplate" v-tooltip-prime.top="'Hapus'" />
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(slotProps.data)"
                      color="info" v-tooltip-prime.top="'Pilih'">
                    </VIconButton>
                    <VIconButton type="button" raised circle icon="fas fa-pencil-alt"
                      @click="editTemplate(slotProps.data)" color="info" v-tooltip-prime.top="'Edit'"
                      v-if="!isAlltemplate">
                    </VIconButton>
                  </VButtons>
                </template>
              </Column>
              <Column field="namatemplate" header="Nama" :sortable="true"></Column>
              <!-- <Column field="registrasi.namaruangan" header="Nama Ruangan" :sortable="true">
                <template #body="slotProps">
                  {{ slotProps.data.registrasi.namaruangan }}
                </template>
              </Column> -->
              <Column field="created_at" header="Tanggal" :sortable="true">
                <template #body="slotProps">
                  <span>{{ H.formatDateToLocalString(slotProps.data.created_at) }}</span>
                </template>
              </Column>
            </DataTable>
          </template>
        </VModal>

        <div class="column is-12">
          <VCard>
            <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
              <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" isLoading="false"
                @click="pilihTemplateFix(index)"> Pilih Template
              </VButton>
              <VButton type="button" rounded outlined color="info" raised icon="feather:file-text" isLoading="false"
                @click="pilihTemplate(index)"> Pilih Riwayat
              </VButton>
            </div>
  
            <div class="column is-12 pt-0 pb-0">
              <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
            </div>
  
            <div class="column is-12">
              <div class="columns">
                <div class="column is-12">
                  <h1><b>Nama Template</b>&emsp;&emsp;<span style="color:red">**Hanya diisi jika ingin membuat
                      template</span></h1>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.namatemplate" rows="1">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>

            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-4">
                  <span style="font-weight: 500;">Berat badan :</span>
                  <VField addons>
                      <VControl>
                          <VInput type="text" class="input" v-model="input.beratBadan" />
                      </VControl>
                      <VControl class="field-addon-body">
                          <VButton static>kg</VButton>
                      </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <span style="font-weight: 500;">Tanggal dan jam masuk :</span>
                  <VDatePicker v-model="input.tanggalMasuk" mode="datetime" trim-weeks>
                      <template #default="{ inputValue, inputEvents }">
                          <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" v-on="inputEvents" />
                          </VControl>
                      </template>
                  </VDatePicker>
                </div>
                <div class="column is-4">
                </div>
              </div>
            </div>
    
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-4">
                    <span style="font-weight: 500;">Tinggi badan :</span>
                    <VField addons>
                        <VControl>
                            <VInput type="text" class="input" v-model="input.tinggiBadan" />
                        </VControl>
                        <VControl class="field-addon-body">
                            <VButton static>cm</VButton>
                        </VControl>
                    </VField>
                </div>  
                <div class="column is-4">
                    <span style="font-weight: 500;">Tanggal dan jam keluar :</span>
                    <VDatePicker v-model="input.tanggalKeluar" mode="datetime" trim-weeks>
                        <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:calendar" fullwidth>
                                <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </template>
                    </VDatePicker>
                </div>  
              </div>
            </div>

            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-12">
                  <span style="font-weight: 500;">Diagnosis MRS :</span>
                  <VControl>
                      <VInput type="text" class="input" v-model="input.diagnosisMRS" />
                  </VControl>
                </div>
              </div>
            </div>

            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-4">
                  <span style="font-weight: 500;">Penyakit Utama</span>
                  <VField>
                      <VTextarea rows="2" v-model="input.penyakitUtama"></VTextarea>
                  </VField>
                </div>

                <div class="column is-2">
                  <span style="font-weight: 500;">Kode ICD</span>
                  <VField>
                      <VTextarea rows="2" v-model="input.kodeIcd1"></VTextarea>
                  </VField>
                </div>

                <div class="column is-3">
                  <span style="font-weight: 500;">Lama rawat :</span>
                  <VField addons>
                      <VControl>
                          <VInput type="text" class="input" v-model="input.lamaRawat" />
                      </VControl>
                      <VControl class="field-addon-body">
                          <VButton static>hari</VButton>
                      </VControl>
                  </VField>
                </div>

              </div>
            </div>

            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-4">
                  <span style="font-weight: 500;">Diagnosis penyerta</span>
                  <VField>
                      <VTextarea rows="2" v-model="input.diagnosisPenyerta"></VTextarea>
                  </VField>
                </div>

                <div class="column is-2">
                  <span style="font-weight: 500;">Kode ICD</span>
                  <VField>
                      <VTextarea rows="2" v-model="input.kodeIcd2"></VTextarea>
                  </VField>
                </div>

                <div class="column is-3">
                  <span style="font-weight: 500;">Rencana rawat 3 hari</span>
                  <VField>
                      <VControl>
                          <VInput type="text" class="input" v-model="input.rencanaRawat3hari" />
                      </VControl>
                  </VField>
                </div>

              </div>
            </div>

            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-4">
                  <span style="font-weight: 500;">Komplikasi</span>
                  <VField>
                      <VTextarea rows="2" v-model="input.komplikasi"></VTextarea>
                  </VField>
                </div>

                <div class="column is-2">
                  <span style="font-weight: 500;">Kode ICD</span>
                  <VField>
                      <VTextarea rows="2" v-model="input.kodeIcd3"></VTextarea>
                  </VField>
                </div>

                <div class="column is-2">
                  <span style="font-weight: 500;">Ruang rawat :</span>
                  <VField>
                      <VControl>
                          <VInput type="text" class="input" v-model="input.ruangRawat" />
                      </VControl>
                  </VField>
                  <span style="font-weight: 500;">Kelas :</span>
                  <VField>
                      <VControl>
                          <VInput type="text" class="input" v-model="input.kelasruangRawat" />
                      </VControl>
                  </VField>
                </div>
              </div>
            </div>

            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-4">
                  <span style="font-weight: 500;">Tindakan</span>
                  <VField>
                      <VTextarea rows="2" v-model="input.tindakan"></VTextarea>
                  </VField>
                </div>

                <div class="column is-2">
                  <span style="font-weight: 500;">Kode ICD</span>
                  <VField>
                      <VTextarea rows="2" v-model="input.kodeIcd4"></VTextarea>
                  </VField>
                </div>

                <div class="column is-2" >
                  <span style="font-weight: 500;">Rujukan :</span>
                  <VControl raw subcontrol>
                      <VCheckbox
                          class="p-0"
                          color="primary"
                          square
                          true-value="Ya"
                          label="Ya"
                          v-model="input.ya"
                      />
                  </VControl>
                  <VControl raw subcontrol>
                      <VCheckbox
                          class="p-0"
                          color="primary"
                          square
                          true-value="Tidak"
                          label="Tidak"
                          v-model="input.tidak"
                      />
                  </VControl>
                </div>
              </div>
            </div>

            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-6">
                  <b>
                    <span style="font-weight: 500px;">Dietary Counseling and Surveillance</span>
                  </b>
                </div>
                <div class="column is-2">
                  <span>Kode ICD Z73.1</span>
                  <VControl>
                      <VInput type="text" class="input" v-model="input.kodeICD4" />
                  </VControl>
                </div>
                <div class="column is-4">
                </div>
              </div>
            </div>

            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-6">
                  <span>Tanggal pemberlakuan CP :</span>
                  <VDatePicker v-model="input.tanggalPemberlakuanCP" mode="date" trim-weeks>
                      <template #default="{ inputValue, inputEvents }">
                          <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" v-on="inputEvents" />
                          </VControl>
                      </template>
                  </VDatePicker>
                </div>
                <div class="column is-6">
                  <span style="font-weight: 500;">Tanggal review CP :</span>
                  <VDatePicker v-model="input.tanggalReviewCP" mode="date" trim-weeks>
                      <template #default="{ inputValue, inputEvents }">
                          <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" v-on="inputEvents" />
                          </VControl>
                      </template>
                  </VDatePicker>
                </div>
              </div>
            </div>
            
            <div class="column is-12">
              <div class="table-container">
                <table class="table is-bordered is-fullwidth">
                  <tr>
                    <th rowspan="4" class="has-text-centered" style="vertical-align:middle; min-width:200px;">Kegiatan</th>
                    <th rowspan="4" class="has-text-centered" style="vertical-align:middle; min-width:200px;">Uraian Kegiatan</th>
                    <th colspan="3" class="has-text-centered" style="vertical-align:middle; min-width:200px;"><b>Hari Penyakit</b></th>
                    <th rowspan="4" class="has-text-centered" style="vertical-align:middle; min-width:200px;"><b>Keterangan Tambahan</b></th>
                    <th rowspan="4" class="has-text-centered" style="vertical-align:middle; min-width:200px;"><b>Paraf PPA</b></th>
                  </tr>
                  <tr>
                    <th rowspan="1" colspan="1" class="has-text-centered" style="vertical-align:middle; min-width:40px;">1</th>
                    <th rowspan="1" colspan="1" class="has-text-centered" style="vertical-align:middle; min-width:40px;">2</th>
                    <th rowspan="1" colspan="1" class="has-text-centered" style="vertical-align:middle; min-width:40px;">3</th>
                  </tr>
                  <tr>
                    <th rowspan="1" colspan="3" class="has-text-centered" style="vertical-align:middle;">Hari Rawat</th>
                  </tr>
                  <tr>
                    <th rowspan="1" colspan="1" class="has-text-centered" style="vertical-align:middle;">1</th>
                    <th rowspan="1" colspan="1" class="has-text-centered" style="vertical-align:middle;">2</th>
                    <th rowspan="1" colspan="1" class="has-text-centered" style="vertical-align:middle;">3</th>
                  </tr>
                  <tr v-for="(row, rowIndex) in table1" :key="rowIndex">
                    <td 
                      v-for="(item, colIndex) in row" 
                      :key="colIndex"
                      :rowspan="item.rowspan" 
                      :colspan="item.colspan" 
                      class="has-text-centered" 
                      style="vertical-align:middle;"
                    >
                      <div v-if="item.type === 'text'" class="has-text-left">
                        {{ item.nama }}
                      </div>
                      <div v-else-if="item.type === 'text'" class="has-text-left">
                        <i>{{ item.nama }}</i>
                      </div>
                      <div v-else-if="item.type === 'text-bold'" class="has-text-left">
                        <b><span>{{ item.nama }}</span></b>
                      </div>
                      <div v-else-if="item.type === 'checkbox'" >
                        <VControl raw subcontrol>
                            <VCheckbox
                                class="p-0"
                                color="primary"
                                square
                                :true-value="item.value"
                                :label="item.label"
                                v-model="input[item.nama]"
                            />
                        </VControl>
                      </div>
                      <div v-else-if="item.type === 'kosong'">
                      </div>
                      <div v-else-if="item.type === 'textbox'">
                        <VControl>
                            <VInput type="text" class="input" v-model="input[item.nama]" />
                        </VControl>
                      </div>
                      <div v-else-if="item.type === 'textarea'">
                        <VField label="">
                            <VTextarea rows="4" v-model="input[item.nama]"></VTextarea>
                        </VField>
                      </div>
                      <div v-else-if="item.type === 'textarea'">
                        <VField>
                            <VTextarea rows="2" v-model="input[item.nama]"></VTextarea>
                        </VField>
                      </div>
                      <div v-else-if="item.type === 'combobox_perawat'">
                        <VControl class="prime-auto">
                            <AutoComplete v-model="input[item.nama]" :suggestions="d_Pegawai"
                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                class="mt-2" />
                        </VControl>
                      </div>
                      <div v-else-if="item.type === 'paraf'">
                        <TandaTangan :elemenID="item.nama" :width="'150'" :height="'150'" class="dek" />
                      </div>
                    </td>
                  </tr>
                </table>
              </div>
            </div>

            <div class="column is-12">
              <div class="table-container">
                <table class="table is-bordered is-fullwidth">
                  <tr>
                    <th class="has-text-centered">
                      Variasi pelayanan yang diberikan
                    </th>
                    <th class="has-text-centered">
                      Tanggal / jam
                    </th>
                    <th class="has-text-centered">
                      Alasan
                    </th>
                    <th class="has-text-centered">
                      Nama PPA dan tanda tangan
                    </th>
                    <th class="has-text-centered">
                      #
                    </th>
                  </tr>
                  <tr v-for="(input, index) in input.details" :key="index">
                    <td>
                      <VField>
                          <VTextarea rows="2" v-model="input.variasiText1"></VTextarea>
                      </VField>
                    </td>
                    <td>
                      <VDatePicker v-model="input.tanggalPelayanan1" mode="datetime" trim-weeks :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }">
                          <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" v-on="inputEvents" />
                          </VControl>
                        </template>
                      </VDatePicker>
                    </td>
                    <td>
                      <VField>
                          <VTextarea rows="2" v-model="input.alasanText1"></VTextarea>
                      </VField>
                    </td>
                    <td class="has-text-centered">
                      <VControl class="prime-auto">
                          <AutoComplete v-model="input.petugas1" :suggestions="d_Pegawai"
                              @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                              class="mt-2" />
                      </VControl>
                      <VControl class="mt-4">
                        <TandaTangan :elemenID="`TTDPetugas_${index}`" :width="'150'" :height="'150'"/>
                      </VControl>
                    </td>
                    <td class="has-text-centered">
                      <VButtons style="justify-content:space-around">
                        <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem(index)"
                          color="info" v-tooltip.bubble="'Tambah '">
                        </VIconButton>
                        <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                          @click="removeItem(index)" color="danger">
                        </VIconButton>
                      </VButtons>
                    </td>
                  </tr>
                </table>
              </div>
            </div>

            <div class="column is-12">
              <div class="column is-3">
                <label>Garut, </label>
                <VDatePicker v-model="input.tanggalPengisian" mode="datetime" trim-weeks :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                        <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" v-on="inputEvents" />
                        </VControl>
                    </template>
                </VDatePicker>
              </div>
              <div class="columns is-multiline">
                <div class="column is-4 has-text-centered">
                  <span>Dokter Penanggung Jawab Pelayanan,</span>
                  <VField class="is-flex is-justify-content-center">
                    <VControl class="prime-auto" style="width: 60%;">
                        <AutoComplete v-model="input.dokterPelaksana" :suggestions="d_Dokter"
                            @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                            class="mt-2" />
                    </VControl>
                  </VField>
                  <TandaTangan :elemenID="'TTDDokterPelaksana'" :width="'150'" :height="'150'" class="dek"/>
                </div>
                <div class="column is-4 has-text-centered">
                  <span>Bidan Penanggung Jawab,</span>
                  <VField class="is-flex is-justify-content-center">
                    <VControl class="prime-auto" style="width: 60%;">
                        <AutoComplete v-model="input.bidan" :suggestions="d_Pegawai"
                            @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                            class="mt-2" />
                    </VControl>
                  </VField>
                  <TandaTangan :elemenID="'TTDBidan'" :width="'150'" :height="'150'" class="dek"/>
                </div>
                <div class="column is-4 has-text-centered">
                  <span>Pelaksana Verifikasi,</span>
                  <VField class="is-flex is-justify-content-center">
                    <VControl class="prime-auto" style="width: 60%;">
                        <AutoComplete v-model="input.pelaksanaVerifikasi" :suggestions="d_Pegawai"
                            @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                            class="mt-2" />
                    </VControl>
                  </VField>
                  <TandaTangan :elemenID="'TTDPelaksanaVerifikasi'" :width="'150'" :height="'150'" class="dek"/>
                </div>
              </div>
            </div>

            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

            <div class="columns is-centered">
              <div class="column is-12">
                <h2 class="title has-text-centered">PETUNJUK PENGISIAN</h2>
                <p><strong>Petunjuk Umum:</strong></p>
                <p>1. Form <em>Clinical Pathway</em> (CP) dimasukkan ke dalam rekam medis pada saat pasien terdiagnosis sesuai kriteria.</p>
                <p>2. Form ini hanya bisa digunakan untuk pasien dengan kriteria sebagai berikut:</p>
                <p style="margin-left: 20px;"><strong>Petunjuk Khusus:</strong> (ditentukan oleh DPJP terkait)</p>
                <p style="margin-left: 30px;"><strong>a. Kriteria Inklusi</strong></p>
                <ul style="margin-left: 40px;">
                  <li>✓ Hamil aterm dengan indikasi SC.</li>
                  <li>✓ Pasien dengan penyakit komorbid ringan yang tidak perlu konsultasi dengan DPJP lain.</li>
                </ul>
                <p style="margin-left: 30px;"><strong>b. Kriteria Eksklusi</strong></p>
                <ul style="margin-left: 40px;">
                  <li>✓ Pasien tanpa tanda infeksi (demam, ketuban berbau, WBC &gt; 15.000).</li>
                  <li>✓ Pasien KPD 24 jam.</li>
                  <li>✓ Hamil dengan resiko tinggi (anemia, APB, HDK, HPP).</li>
                  <li>✓ Pasien dengan penyakit komorbid yang memerlukan perawatan bersama dengan DPJP lain.</li>
                  <li>✓ SC greencode.</li>
                </ul>
                <p>3. Form CP memandu PPA untuk menerapkan asuhan sesuai standar dan setelah membaca lakukan paraf.</p>
                <p>4. Form CP diisi oleh Kepala Ruangan atau MPP, dengan mencontreng di kotak yang sesuai (tanda "☐", contoh: "☑") untuk monitor kepatuhan PPA, list yang tanpa kotak bisa diconteng saat dibutuhkan.</p>
                <p>5. Setelah melakukan pengisian form CP, PPA wajib menandatangani dan memberikan nama pada form.</p>
                <p>6. Jika pelayanan yang diberikan diluar kriteria CP, penjelasan ditulis dalam kolom variasi.</p>
                <p>7. Form CP diisi setiap hari setelah melakukan visite dan mengisi rekam medis.</p>
                <p>8. Form CP selalu disimpan di rekam medis.</p>
                <p>9. Penggunaan CP dengan pertimbangan besaran tanggungan asuransi yang dimiliki pasien.</p>
      
                <p class="mt-4"><strong>Referensi:</strong></p>
                <ol style="margin-left: 20px;">
                  <li>PPK KSM Obstetri dan Ginekologi</li>
                  <li>Standar Luaran Keperawatan Indonesia (SLKI)</li>
                  <li>Formularium rumah sakit tahun 2022</li>
                  <li>Panduan asuhan gizi</li>
                </ol>
              </div>
            </div>

          </VCard>
        </div>

      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { useWindowScroll } from '@vueuse/core'
  import { useApi } from '/@src/composable/useApi'
  import { h, reactive, ref, computed, defineComponent, watch, onMounted, nextTick } from 'vue'
  import { useRoute, useRouter } from 'vue-router'
  import { useHead } from '@vueuse/head'
  import * as H from '/@src/utils/appHelper'
  import ButtonEmr from '../page-emr-plugins/button-emr.vue'
  import AutoComplete from 'primevue/autocomplete';
  // import Fieldset from 'primevue/fieldset';
  import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
  import { useViewWrapper } from '/@src/stores/viewWrapper'
  import { useThemeColors } from '/@src/composable/useThemeColors'
  import { useUserSession } from '/@src/stores/userSession'
  import { FilterMatchMode } from 'primevue/api';
  import InputText from 'primevue/inputtext';
  import Column from 'primevue/column'
  import DataTable from 'primevue/datatable'
  import * as EMR from '../page-emr-plugins/hamil-aterm-indikasi-sectio-cesaria-tanpa-tanda-infeksi'
  
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
      COLLECTION: 'HamilAtermIndikasiSectioCesariaTanpaTandaInfeksi',
    }
  )
  const { y } = useWindowScroll()
  const isStuck = computed(() => { return y.value > 30 })
  const isLoading: any = ref(false)
  const d_Ruangan: any = ref([])
  const d_Pegawai: any = ref([])
  const d_Dokter: any = ref([])
  const filtersTemplate = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS } });
  const listTemplate: any = ref([])
  const listTemplateFix: any = ref([])
  const showModalTemplate: any = ref(false)
  const showModalTemplateFix: any = ref(false)
  const idTemplate: any = ref('');
  const checkTemplate: any = ref(false)
  const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
  })
  const COLLECTION: any = ref(props.COLLECTION) //table mongodb
  const NOREC_EMRPASIEN: any = ref('')
  const dataTTD: any = ref([]);
  const input: any = ref({
    penyakitUtama: "Hamil Aterm Indikasi Sectio Cesaria Tanpa Tanda-tanda Infeksi",
    tglDibuat : new Date,
    peningkatanKebutuhanEnergi1Text: "Sesuai dengan assesmen gizi, kemungkinan ada diagnosis tambahan atau perubahan selama perawatan",
    TLIGizi1Text: "Sesuai dengan diagnosis gizi, kemungkinan ada tambahan atau perubahan tujuan dan preskripsi diet selama perawatan",
    hasilScreeningPerawat2Text: "Lihal risiko malnutrisi melalui skrining gizi dan mengkaji data antropometri, biokimia, fisik/klinis, riwayat makan termasuk alergi makanan serta riwayat personal. Assesmen dapat dilakukan dalam waktu 24 jam",
    details: [{
      no: 1,
      waktu: new Date
    }]
  })

  let table1: any = ref(EMR.table1())

  async function setTTD() {
  let i = 0;
  while (dataTTD.value[`TTDPetugas_${i}`] !== undefined) {
    await nextTick();
    H.tandaTangan().set(`TTDPetugas_${i}`, dataTTD.value[`TTDPetugas_${i}`]);
    i++;
    // console.log('masuk while', 'TTDPetugas_' + i)
  }
}

  const setView = () => {
    useHead({
      title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
  }
  const loadRiwayat = async () => {
  try {
    const response = await useApi().get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    );

    if (response.length) {
      input.value = response[0]; // Set to input
      if (NOREC_EMRPASIEN.value === '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk;
      }
      dataTTD.value = response[0];
      await nextTick(() => {
        H.tandaTangan().set("TTDDokterPelaksana", dataTTD.value.TTDDokterPelaksana);
        H.tandaTangan().set("TTDBidan", dataTTD.value.TTDBidan);
        H.tandaTangan().set("TTDPelaksanaVerifikasi", dataTTD.value.TTDPelaksanaVerifikasi);
      });
    } else {
      setAutoFill();
    }
  } catch (error) {
    console.error("Error loading data:", error);
  }
};

  
  const setAutoFill = async () => {
    input.value.tanggalMasukRS = props.registrasi.tglregistrasi;
    input.value.ruangan = { value: props.registrasi.objectruanganfk, label: props.registrasi.namaruangan }
  };

  const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
  
    let object: any = {}
  
    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)

    for (let i = 0; i <= input.value.details.length; i++) {
      object[`TTDPetugas_${i}`] = H.tandaTangan().get(`TTDPetugas_${i}`);
    }
    object['TTDDokterPelaksana'] = H.tandaTangan().get("TTDDokterPelaksana");
    object['TTDBidan'] = H.tandaTangan().get("TTDBidan");
    object['TTDPelaksanaVerifikasi'] = H.tandaTangan().get("TTDPelaksanaVerifikasi");

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
  
  const simpanTemplate = () => {
  if (!input.value.namatemplate) {
    H.alert('warning', "Nama Template wajib diisi")
    console.log()
    return;
  }
  let ID = idTemplate.value ? idTemplate.value : ''
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

  useApi().post(`/emr/simpan-emr-template`, json).then((response: any) => {
    isLoading.value = false
    input.value.namatemplate = null
    delete object.namatemplate;
    delete object['_id'];
    delete object.pasien;
    delete object.registrasi;
  }).catch((e: any) => {
    isLoading.value = false
  })
}
const pilihTemplate = async (index: any) => {
  isLoading.value = true
  useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
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
  const skipKeys = ['id', '_id', 'namatemplate','beratBadan','tanggalMasuk','tinggiBadan','tanggalKeluar','diagnosisMRS']; // Keys to be skipped

  for (const key in response) {
    if (!skipKeys.includes(key)) {
      input.value[key] = response[key]; // Only update allowed keys
    }
  }
  showModalTemplateFix.value = false
  H.alert('info', 'Template berhasil ditambahkan')
}

const addRiwayat = (response: any) => {
  input.value = response //set ke inputan
  delete input.value.namatemplate;
  delete input.value['_id'];
  showModalTemplate.value = false
  showModalTemplateFix.value = false
}

const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi().get(`/emr/get-emr-template?collection=${COLLECTION.value}&isAll=true`).then((responselast: any) => {
    isLoading.value = false
    if (responselast.length) {
      for (var x = 0; x < responselast.length; x++) {
        responselast[x].no = x + 1
      }
      listTemplateFix.value = responselast //set ke inputan
      showModalTemplateFix.value = true
    } else {
      H.alert('warning', 'Data tidak ada')
    }
  })
}

const deleteTemplate = (idTemplate) => {
  isLoading.value = true
  let json = {
    'id': idTemplate,
    'collection': COLLECTION.value
  }
  useApi().post(`/emr/hapus-template`, json).then((response: any) => {
    if (response.status !== 500) {
      isLoading.value = false;
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
  showModalTemplateFix.value = false;
}
const editTemplate = async (dt: any) => {
  if (!dt) return;
  H.alert('info', 'Silahkan ubah data dan Simpan Template Kembali');
  input.value = dt;
  idTemplate.value = dt.id;
  showModalTemplateFix.value = false;
  input.value.namatemplate = dt.namatemplate;
  checkTemplate.value = true
}
  const fetchRuangan = async (filter: any) => {
    const response = await useApi().get(
      `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
    d_Ruangan.value = response
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
  
  const addNewItem = (index: any) => {
    input.value.details.push({
    no: input.value.details[input.value.details.length - 1].no + 1,
  });
};
  const removeItem = (index: any) => {
    input.value.details.splice(index, 1)
  }
  
  const kembaliKeun = () => {
    window.history.back()
  }
  onMounted(async () => {
    try {
      await setView();
      await setAutoFill();
      await loadRiwayat().then((s) => {
        setTTD();
      })
    } catch (error) {

    }
  })
  </script>
  
  
  <style lang="scss">
  </style>
  